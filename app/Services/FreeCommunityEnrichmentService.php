<?php

namespace App\Services;

use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeCommunityEnrichmentService
{
    private bool $disableSslVerify;

    public function __construct()
    {
        $this->disableSslVerify = (bool) config('services.free_geo.disable_ssl_verify', false);
    }

    /**
     * @return array<string,int>
     */
    public function enrichAll(?string $city = null, int $limitPerModule = 300): array
    {
        $hospital = $this->enrichHospitals($city, $limitPerModule);
        $doctor = $this->enrichDoctors($city, $limitPerModule);
        $bloodBank = $this->enrichBloodBanks($city, $limitPerModule);

        return [
            'hospitals_processed' => $hospital['processed'],
            'hospitals_updated' => $hospital['updated'],
            'hospitals_failed' => $hospital['failed'],
            'doctors_processed' => $doctor['processed'],
            'doctors_updated' => $doctor['updated'],
            'doctors_failed' => $doctor['failed'],
            'blood_banks_processed' => $bloodBank['processed'],
            'blood_banks_updated' => $bloodBank['updated'],
            'blood_banks_failed' => $bloodBank['failed'],
        ];
    }

    /**
     * @return array<string,int>
     */
    public function enrichHospitals(?string $city = null, int $limit = 300): array
    {
        $query = Hospital::query()->where('is_verified', true);
        if (!empty($city)) {
            $query->where('city', 'LIKE', '%' . trim($city) . '%');
        }
        $query->where(function ($q) {
            $q->whereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereNull('pincode')
                ->orWhere('pincode', '')
                ->orWhereNull('address_line1')
                ->orWhere('address_line1', '');
        });

        $rows = $query->limit(max(1, $limit))->get();
        $processed = 0;
        $updated = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $processed++;
            try {
                if ($this->enrichHospitalRow($row)) {
                    $updated++;
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Free enrichment hospital failed #' . $row->id . ': ' . $e->getMessage());
            }
        }

        return ['processed' => $processed, 'updated' => $updated, 'failed' => $failed];
    }

    /**
     * @return array<string,int>
     */
    public function enrichDoctors(?string $city = null, int $limit = 300): array
    {
        $query = Doctor::query()->where('is_verified', true);
        if (!empty($city)) {
            $query->where('city', 'LIKE', '%' . trim($city) . '%');
        }
        $query->where(function ($q) {
            $q->whereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereNull('pincode')
                ->orWhere('pincode', '')
                ->orWhereNull('address_line1')
                ->orWhere('address_line1', '');
        });

        $rows = $query->limit(max(1, $limit))->get();
        $processed = 0;
        $updated = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $processed++;
            try {
                if ($this->enrichDoctorRow($row)) {
                    $updated++;
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Free enrichment doctor failed #' . $row->id . ': ' . $e->getMessage());
            }
        }

        return ['processed' => $processed, 'updated' => $updated, 'failed' => $failed];
    }

    /**
     * @return array<string,int>
     */
    public function enrichBloodBanks(?string $city = null, int $limit = 300): array
    {
        $query = BloodBank::query()->where('is_verified', true);
        if (!empty($city)) {
            $query->where('city', 'LIKE', '%' . trim($city) . '%');
        }
        $query->where(function ($q) {
            $q->whereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhereNull('pincode')
                ->orWhere('pincode', '')
                ->orWhereNull('address_en')
                ->orWhere('address_en', '');
        });

        $rows = $query->limit(max(1, $limit))->get();
        $processed = 0;
        $updated = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $processed++;
            try {
                if ($this->enrichBloodBankRow($row)) {
                    $updated++;
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Free enrichment blood bank failed #' . $row->id . ': ' . $e->getMessage());
            }
        }

        return ['processed' => $processed, 'updated' => $updated, 'failed' => $failed];
    }

    private function enrichHospitalRow(Hospital $row): bool
    {
        $query = trim($row->name_en . ' ' . ($row->city ?: '') . ' hospital');
        $geo = $this->geocodeWithNominatim($query);
        if (empty($geo)) {
            return false;
        }

        $before = $row->toArray();
        $row->address_line1 = $row->address_line1 ?: ($geo['address_line1'] ?? null);
        $row->address_line2 = $row->address_line2 ?: ($geo['address_line2'] ?? null);
        $row->city = $row->city ?: ($geo['city'] ?? null);
        $row->state = $row->state ?: ($geo['state'] ?? null);
        $row->pincode = $row->pincode ?: ($geo['pincode'] ?? null);
        $row->latitude = $row->latitude ?: ($geo['latitude'] ?? null);
        $row->longitude = $row->longitude ?: ($geo['longitude'] ?? null);
        $row->address = null;

        if ($row->isDirty()) {
            $row->save();
            return true;
        }

        return false;
    }

    private function enrichDoctorRow(Doctor $row): bool
    {
        $query = trim('Dr ' . $row->first_name . ' ' . $row->last_name . ' ' . ($row->city ?: '') . ' clinic');
        $geo = $this->geocodeWithNominatim($query);

        if (empty($geo)) {
            $hosp = $row->hospitals()->first();
            if ($hosp) {
                $row->city = $row->city ?: $hosp->city;
                $row->state = $row->state ?: $hosp->state;
                $row->pincode = $row->pincode ?: $hosp->pincode;
                $row->address_line1 = $row->address_line1 ?: $hosp->address_line1;
                $row->address_line2 = $row->address_line2 ?: $hosp->address_line2;
                $row->latitude = $row->latitude ?: $hosp->latitude;
                $row->longitude = $row->longitude ?: $hosp->longitude;
                if ($row->isDirty()) {
                    $row->save();
                    return true;
                }
            }
            return false;
        }

        $row->address_line1 = $row->address_line1 ?: ($geo['address_line1'] ?? null);
        $row->address_line2 = $row->address_line2 ?: ($geo['address_line2'] ?? null);
        $row->city = $row->city ?: ($geo['city'] ?? null);
        $row->state = $row->state ?: ($geo['state'] ?? null);
        $row->pincode = $row->pincode ?: ($geo['pincode'] ?? null);
        $row->latitude = $row->latitude ?: ($geo['latitude'] ?? null);
        $row->longitude = $row->longitude ?: ($geo['longitude'] ?? null);

        if ($row->isDirty()) {
            $row->save();
            return true;
        }

        return false;
    }

    private function enrichBloodBankRow(BloodBank $row): bool
    {
        $query = trim($row->name_en . ' ' . ($row->city ?: '') . ' blood bank');
        $geo = $this->geocodeWithNominatim($query);
        if (empty($geo)) {
            return false;
        }

        $row->address_en = $row->address_en ?: trim(implode(', ', array_filter([
            $geo['address_line1'] ?? null,
            $geo['address_line2'] ?? null,
            $geo['city'] ?? null,
            $geo['state'] ?? null,
            $geo['pincode'] ?? null,
        ])));
        $row->city = $row->city ?: ($geo['city'] ?? null);
        $row->state = $row->state ?: ($geo['state'] ?? null);
        $row->pincode = $row->pincode ?: ($geo['pincode'] ?? null);
        $row->latitude = $row->latitude ?: ($geo['latitude'] ?? null);
        $row->longitude = $row->longitude ?: ($geo['longitude'] ?? null);

        if ($row->isDirty()) {
            $row->save();
            return true;
        }

        return false;
    }

    /**
     * @return array<string,mixed>
     */
    private function geocodeWithNominatim(string $query): array
    {
        $cacheKey = 'nominatim_' . md5(strtolower($query));

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($query) {
            // Respect Nominatim usage policy by throttling.
            usleep(1100000);

            $client = Http::timeout(15);
            if ($this->disableSslVerify) {
                $client = $client->withoutVerifying();
            }

            $response = $client
                ->withHeaders([
                    'User-Agent' => 'SwasthyaSearch/1.0 (community health directory)',
                    'Accept-Language' => 'en',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'jsonv2',
                    'addressdetails' => 1,
                    'limit' => 1,
                    'countrycodes' => 'in',
                ]);

            if (!$response->ok()) {
                return [];
            }

            $first = data_get($response->json(), '0');
            if (!is_array($first)) {
                return [];
            }

            $address = $first['address'] ?? [];
            $houseNo = trim((string)($address['house_number'] ?? ''));
            $road = trim((string)($address['road'] ?? ''));
            $line1 = trim($houseNo . ' ' . $road);
            $line2 = trim((string)($address['suburb'] ?? $address['neighbourhood'] ?? $address['quarter'] ?? ''));
            $city = trim((string)($address['city'] ?? $address['town'] ?? $address['village'] ?? ''));
            $state = trim((string)($address['state'] ?? ''));
            $pincode = trim((string)($address['postcode'] ?? ''));

            return [
                'latitude' => isset($first['lat']) ? (float)$first['lat'] : null,
                'longitude' => isset($first['lon']) ? (float)$first['lon'] : null,
                'address_line1' => $line1 !== '' ? $line1 : null,
                'address_line2' => $line2 !== '' ? $line2 : null,
                'city' => $city !== '' ? $city : null,
                'state' => $state !== '' ? $state : null,
                'pincode' => $pincode !== '' ? $pincode : null,
            ];
        });
    }
}
