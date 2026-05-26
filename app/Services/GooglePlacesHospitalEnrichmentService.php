<?php

namespace App\Services;

use App\Models\Hospital;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesHospitalEnrichmentService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) config('services.google_places.api_key', '');
    }

    /**
     * Enrich hospitals with missing critical fields using Google Places API.
     *
     * @return array<string, int>
     */
    public function enrichMissingHospitals(?string $city = null, int $limit = 200): array
    {
        if ($this->apiKey === '') {
            return [
                'processed' => 0,
                'updated' => 0,
                'failed' => 0,
            ];
        }

        $query = Hospital::query()->where('is_verified', true);
        if (!empty($city)) {
            $query->where('city', 'LIKE', '%' . trim($city) . '%');
        }

        $query->where(function ($q) {
            $q->whereNull('address_line1')
                ->orWhere('address_line1', '')
                ->orWhereNull('pincode')
                ->orWhere('pincode', '')
                ->orWhereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereNull('emergency_phone_1')
                ->orWhere('emergency_phone_1', '');
        });

        $hospitals = $query->limit(max(1, $limit))->get();

        $processed = 0;
        $updated = 0;
        $failed = 0;

        foreach ($hospitals as $hospital) {
            $processed++;
            try {
                if ($this->enrichHospital($hospital)) {
                    $updated++;
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Google Places enrichment failed for hospital ' . $hospital->id . ': ' . $e->getMessage());
            }
        }

        return [
            'processed' => $processed,
            'updated' => $updated,
            'failed' => $failed,
        ];
    }

    private function enrichHospital(Hospital $hospital): bool
    {
        $place = $this->findPlaceId($hospital);
        if (empty($place['place_id'])) {
            return false;
        }

        $details = $this->getPlaceDetails($place['place_id']);
        if (empty($details)) {
            return false;
        }

        $components = $this->extractAddressComponents($details['address_components'] ?? []);
        $line1 = $components['line1'] ?: $hospital->address_line1;
        $line2 = $components['line2'] ?: $hospital->address_line2;
        $city = $components['city'] ?: $hospital->city;
        $state = $components['state'] ?: $hospital->state;
        $pincode = $components['pincode'] ?: $hospital->pincode;

        $phoneParts = HealthcareSyncService::splitPhone(
            (string) ($details['international_phone_number'] ?? $details['formatted_phone_number'] ?? $hospital->emergency_phone)
        );

        $latitude = data_get($details, 'geometry.location.lat', $hospital->latitude);
        $longitude = data_get($details, 'geometry.location.lng', $hospital->longitude);

        $before = [
            $hospital->address_line1,
            $hospital->address_line2,
            $hospital->city,
            $hospital->state,
            $hospital->pincode,
            $hospital->latitude,
            $hospital->longitude,
            $hospital->emergency_phone,
        ];

        $hospital->fill([
            'address' => null,
            'address_line1' => $line1,
            'address_line2' => $line2,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'emergency_country_code' => $hospital->emergency_country_code ?: ($phoneParts['country_code'] ?? '+91'),
            'emergency_phone_1' => $phoneParts['phone'] ?: $hospital->emergency_phone_1,
        ]);

        $after = [
            $hospital->address_line1,
            $hospital->address_line2,
            $hospital->city,
            $hospital->state,
            $hospital->pincode,
            $hospital->latitude,
            $hospital->longitude,
            $hospital->emergency_phone,
        ];

        if ($before !== $after) {
            $hospital->save();
            return true;
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function findPlaceId(Hospital $hospital): array
    {
        $input = trim($hospital->name_en . ' ' . ($hospital->city ?: '') . ' hospital');
        $response = Http::timeout(12)->get('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', [
            'input' => $input,
            'inputtype' => 'textquery',
            'fields' => 'place_id,name,formatted_address',
            'key' => $this->apiKey,
        ]);

        if (!$response->ok()) {
            return [];
        }

        $candidate = data_get($response->json(), 'candidates.0', []);
        return is_array($candidate) ? $candidate : [];
    }

    /**
     * @return array<string, mixed>
     */
    private function getPlaceDetails(string $placeId): array
    {
        $response = Http::timeout(12)->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'fields' => 'name,formatted_address,address_components,formatted_phone_number,international_phone_number,geometry,website',
            'key' => $this->apiKey,
        ]);

        if (!$response->ok()) {
            return [];
        }

        $result = data_get($response->json(), 'result', []);
        return is_array($result) ? $result : [];
    }

    /**
     * @param array<int, array<string, mixed>> $components
     * @return array<string, string>
     */
    private function extractAddressComponents(array $components): array
    {
        $lineParts = [];
        $line2Parts = [];
        $city = '';
        $state = '';
        $pincode = '';

        foreach ($components as $component) {
            $types = $component['types'] ?? [];
            $longName = trim((string)($component['long_name'] ?? ''));
            if ($longName === '') {
                continue;
            }

            if (in_array('street_number', $types, true) || in_array('route', $types, true)) {
                $lineParts[] = $longName;
            } elseif (in_array('sublocality', $types, true) || in_array('sublocality_level_1', $types, true) || in_array('neighborhood', $types, true)) {
                $line2Parts[] = $longName;
            } elseif (in_array('locality', $types, true)) {
                $city = $longName;
            } elseif (in_array('administrative_area_level_1', $types, true)) {
                $state = $longName;
            } elseif (in_array('postal_code', $types, true)) {
                $pincode = $longName;
            }
        }

        return [
            'line1' => implode(' ', array_unique($lineParts)),
            'line2' => implode(', ', array_unique($line2Parts)),
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
        ];
    }
}
