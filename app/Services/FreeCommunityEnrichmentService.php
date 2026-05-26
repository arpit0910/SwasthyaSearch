<?php

namespace App\Services;

use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        return [
            ...$this->enrichHospitals($city, $limitPerModule),
            ...$this->enrichDoctors($city, $limitPerModule),
            ...$this->enrichBloodBanks($city, $limitPerModule),
        ];
    }

    /**
     * @return array<string,int>
     */
    public function enrichHospitals(?string $city = null, int $limit = 300): array
    {
        $query = $this->buildBaseQuery(Hospital::query(), $city, ['address_line1']);

        return $this->processBatch($query, $limit, 'hospitals', fn(Hospital $row) => $this->enrichHospitalRow($row));
    }

    /**
     * @return array<string,int>
     */
    public function enrichDoctors(?string $city = null, int $limit = 300): array
    {
        $query = $this->buildBaseQuery(Doctor::query(), $city, ['address_line1']);

        return $this->processBatch($query, $limit, 'doctors', fn(Doctor $row) => $this->enrichDoctorRow($row));
    }

    /**
     * @return array<string,int>
     */
    public function enrichBloodBanks(?string $city = null, int $limit = 300): array
    {
        $query = $this->buildBaseQuery(BloodBank::query(), $city, ['address_en']);

        return $this->processBatch($query, $limit, 'blood_banks', fn(BloodBank $row) => $this->enrichBloodBankRow($row));
    }

    /**
     * Helper to process batches uniformly.
     */
    private function processBatch(Builder $query, int $limit, string $prefix, callable $enrichCallback): array
    {
        $rows = $query->limit(max(1, $limit))->get();
        $stats = ["{$prefix}_processed" => 0, "{$prefix}_updated" => 0, "{$prefix}_failed" => 0];

        foreach ($rows as $row) {
            $stats["{$prefix}_processed"]++;
            try {
                if ($enrichCallback($row)) {
                    $stats["{$prefix}_updated"]++;
                }
            } catch (\Throwable $e) {
                $stats["{$prefix}_failed"]++;
                Log::warning("Free enrichment {$prefix} failed #{$row->id}: " . $e->getMessage());
            }
        }

        return $stats;
    }

    /**
     * Helper to build the standard missing-data query.
     */
    private function buildBaseQuery(Builder $query, ?string $city, array $addressFields): Builder
    {
        $query->where('is_verified', true);
        
        if (!empty($city)) {
            $query->where('city', 'LIKE', '%' . trim($city) . '%');
        }

        return $query->where(function ($q) use ($addressFields) {
            $q->whereNull('latitude')
              ->orWhereNull('longitude')
              ->orWhereNull('pincode')
              ->orWhere('pincode', '');

            foreach ($addressFields as $field) {
                $q->orWhereNull($field)->orWhere($field, '');
            }
        });
    }

    private function enrichHospitalRow(Hospital $row): bool
    {
        $query = trim("{$row->name_en} {$row->city} hospital");
        $geo = $this->geocodeWithNominatim($query);

        if (empty($geo)) {
            return false;
        }

        // Clear address block to force usage of line1/line2 natively if requested
        $row->address = null;

        return $this->fillAndSave($row, $geo);
    }

    private function enrichDoctorRow(Doctor $row): bool
    {
        $query = trim("Dr {$row->first_name} {$row->last_name} {$row->city} clinic");
        $geo = $this->geocodeWithNominatim($query);

        if (empty($geo)) {
            $hosp = $row->hospitals()->first();
            if ($hosp) {
                return $this->fillAndSave($row, $hosp->toArray());
            }
            return false;
        }

        return $this->fillAndSave($row, $geo);
    }

    private function enrichBloodBankRow(BloodBank $row): bool
    {
        $query = trim("{$row->name_en} {$row->city} blood bank");
        $geo = $this->geocodeWithNominatim($query);
        
        if (empty($geo)) {
            return false;
        }

        if (empty($row->address_en)) {
            $row->address_en = trim(implode(', ', array_filter([
                $geo['address_line1'] ?? null,
                $geo['address_line2'] ?? null,
                $geo['city'] ?? null,
                $geo['state'] ?? null,
                $geo['pincode'] ?? null,
            ])));
        }

        return $this->fillAndSave($row, $geo);
    }

    /**
     * Automatically maps and assigns missing fields without overriding existing ones.
     */
    private function fillAndSave(Model $row, array $geoData): bool
    {
        $fillable = ['address_line1', 'address_line2', 'city', 'state', 'pincode', 'latitude', 'longitude'];

        foreach ($fillable as $field) {
            if (empty($row->{$field}) && !empty($geoData[$field])) {
                $row->{$field} = $geoData[$field];
            }
        }

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
            // Respect Nominatim usage policy by throttling (1 request / sec).
            usleep(1100000);

            $client = Http::timeout(15)->withHeaders([
                'User-Agent' => 'SwasthyaSearch/1.0 (community health directory)',
                'Accept-Language' => 'en',
            ]);

            if ($this->disableSslVerify) {
                $client->withoutVerifying();
            }

            $response = $client->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'jsonv2',
                'addressdetails' => 1,
                'limit' => 1,
                'countrycodes' => 'in',
            ]);

            if (!$response->ok() || empty($response->json('0'))) {
                return [];
            }

            $first = $response->json('0');
            $address = $first['address'] ?? [];
            
            $houseNo = trim((string)($address['house_number'] ?? ''));
            $road = trim((string)($address['road'] ?? ''));
            
            return [
                'latitude' => isset($first['lat']) ? (float)$first['lat'] : null,
                'longitude' => isset($first['lon']) ? (float)$first['lon'] : null,
                'address_line1' => trim("{$houseNo} {$road}") ?: null,
                'address_line2' => $address['suburb'] ?? $address['neighbourhood'] ?? $address['quarter'] ?? null,
                'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? null,
                'state' => $address['state'] ?? null,
                'pincode' => $address['postcode'] ?? null,
            ];
        });
    }
}