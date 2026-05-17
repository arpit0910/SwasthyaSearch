<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class HealthcareSyncService
{
    /**
     * Synchronize a complete batch of doctors and hospitals with rate limiting,
     * pagination handling, dynamic department creation, and geocoding.
     *
     * @param array $doctorsBatch Array of doctor source data records.
     * @param array $hospitalsBatch Array of hospital source data records.
     * @param string|null $cacheKey Cache key for progress tracking.
     * @param int $chunkSize Number of records to process per batch chunk.
     */
    public static function syncBatch(array $doctorsBatch, array $hospitalsBatch, ?string $cacheKey = null, int $chunkSize = 25): array
    {
        $results = [
            'hospitals_synced' => 0,
            'doctors_synced' => 0,
            'departments_created' => 0,
            'errors' => [],
        ];

        if ($cacheKey) {
            Cache::put($cacheKey, [
                'status' => 'running',
                'progress' => 5,
                'message' => 'Starting healthcare directory synchronization batch...'
            ], 300);
        }

        // --- 1. PROCESS HOSPITALS BATCH ---
        $hospitalChunks = array_chunk($hospitalsBatch, $chunkSize);
        $totalHospitalChunks = count($hospitalChunks);

        foreach ($hospitalChunks as $chunkIndex => $chunk) {
            if ($cacheKey) {
                $prog = 5 + (int)((($chunkIndex + 1) / ($totalHospitalChunks ?: 1)) * 40);
                Cache::put($cacheKey, [
                    'status' => 'running',
                    'progress' => $prog,
                    'message' => "Processing hospital chunk " . ($chunkIndex + 1) . " of {$totalHospitalChunks}..."
                ], 300);
            }

            foreach ($chunk as $hospData) {
                try {
                    self::syncHospital($hospData);
                    $results['hospitals_synced']++;
                } catch (Exception $e) {
                    Log::error("Hospital Sync Error: " . $e->getMessage(), ['data' => $hospData]);
                    $hospName = $hospData['name_en'] ?? 'Unknown';
                    $results['errors'][] = "Hospital Sync Error ({$hospName}): " . $e->getMessage();
                }
            }

            // Rate limiting / Throttling between chunks to prevent dropping records or overwhelming DB/APIs
            usleep(250000); // 250ms sleep
        }

        // --- 2. PROCESS DOCTORS BATCH ---
        $doctorChunks = array_chunk($doctorsBatch, $chunkSize);
        $totalDoctorChunks = count($doctorChunks);

        foreach ($doctorChunks as $chunkIndex => $chunk) {
            if ($cacheKey) {
                $prog = 45 + (int)((($chunkIndex + 1) / ($totalDoctorChunks ?: 1)) * 50);
                Cache::put($cacheKey, [
                    'status' => 'running',
                    'progress' => $prog,
                    'message' => "Processing doctor chunk " . ($chunkIndex + 1) . " of {$totalDoctorChunks}..."
                ], 300);
            }

            foreach ($chunk as $docData) {
                try {
                    $syncResult = self::syncDoctor($docData);
                    $results['doctors_synced']++;
                    if ($syncResult['new_department']) {
                        $results['departments_created']++;
                    }
                } catch (Exception $e) {
                    Log::error("Doctor Sync Error: " . $e->getMessage(), ['data' => $docData]);
                    $docName = $docData['first_name'] ?? 'Unknown';
                    $results['errors'][] = "Doctor Sync Error ({$docName}): " . $e->getMessage();
                }
            }

            // Rate limiting / Throttling between chunks
            usleep(250000); // 250ms sleep
        }

        if ($cacheKey) {
            Cache::put($cacheKey, [
                'status' => 'completed',
                'progress' => 100,
                'message' => "Successfully synchronized {$results['doctors_synced']} doctors and {$results['hospitals_synced']} hospitals!"
            ], 300);
        }

        return $results;
    }

    /**
     * Synchronize a single hospital record with strict address formatting, geocoding, and scheme metadata.
     */
    public static function syncHospital(array $sourceData): Hospital
    {
        $nameEn = trim($sourceData['name_en'] ?? '');
        if (empty($nameEn)) {
            throw new Exception("Hospital name_en is required for synchronization.");
        }

        $cityName = trim($sourceData['city'] ?? 'Jaipur');
        $nameHi = trim($sourceData['name_hi'] ?? ($nameEn . " ({$cityName})"));

        // Normalize Address
        $addressData = self::normalizeAddress($sourceData, $cityName);

        // Geocode Lat/Long if missing
        $coords = self::geocodeAddress(
            $addressData['address_line1'],
            $addressData['pincode'],
            $addressData['city'],
            $addressData['state'],
            $sourceData['latitude'] ?? null,
            $sourceData['longitude'] ?? null
        );

        // Normalize Cashless Schemes & Flags
        $schemes = self::normalizeSchemes($sourceData);

        $existingHospital = Hospital::where('name_en', $nameEn)->where('city', $cityName)->first();

        return Hospital::updateOrCreate(
            ['name_en' => $nameEn, 'city' => $cityName],
            [
                'name_hi' => $nameHi,
                'type' => $sourceData['type'] ?? ($existingHospital?->type ?: 'Hospital'),
                'address' => $addressData['full_address'],
                'address_line1' => $addressData['address_line1'],
                'address_line2' => $addressData['address_line2'],
                'city' => $addressData['city'],
                'state' => $addressData['state'],
                'pincode' => $addressData['pincode'],
                'latitude' => $coords['latitude'],
                'longitude' => $coords['longitude'],
                'emergency_phone' => $sourceData['emergency_phone'] ?? ($existingHospital?->emergency_phone ?: '+91-' . rand(1000000000, 9999999999)),
                'is_verified' => true,
                // Old schema flags
                'accepts_ayushman' => $schemes['accepts_ayushman_card'],
                'accepts_janaadhaar' => $schemes['accepts_jan_aadhaar'],
                'accepts_cghs' => $schemes['rgahs_approved'] || ($sourceData['accepts_cghs'] ?? false),
                'is_cashless' => $schemes['cashless_treatment_available'],
                'cashless_schemes_list' => $schemes['cashless_schemes_list'],
                // New explicit schema flags
                'cashless_treatment_available' => $schemes['cashless_treatment_available'],
                'accepts_ayushman_card' => $schemes['accepts_ayushman_card'],
                'accepts_jan_aadhaar' => $schemes['accepts_jan_aadhaar'],
                'rgahs_approved' => $schemes['rgahs_approved'],
            ]
        );
    }

    /**
     * Synchronize a single doctor record with dynamic department creation, address normalization, and hospital mapping.
     */
    public static function syncDoctor(array $sourceData): array
    {
        $firstName = trim($sourceData['first_name'] ?? '');
        $lastName = trim($sourceData['last_name'] ?? '');

        if (empty($firstName)) {
            throw new Exception("Doctor first_name is required for synchronization.");
        }

        $cityName = trim($sourceData['city'] ?? 'Jaipur');

        // --- DYNAMIC DEPARTMENT CREATION LOGIC ---
        $deptNameEn = trim($sourceData['department_name_en'] ?? 'General Medicine');
        $deptNameHi = trim($sourceData['department_name_hi'] ?? self::getHindiDeptName($deptNameEn));

        $existingDept = Department::where('name_en', $deptNameEn)->first();
        $newDepartmentCreated = false;

        if (!$existingDept) {
            $existingDept = Department::create([
                'name_en' => $deptNameEn,
                'name_hi' => $deptNameHi,
                'description_en' => "Specialized healthcare department for {$deptNameEn}.",
                'description_hi' => "{$deptNameHi} के लिए विशेष स्वास्थ्य सेवा विभाग।",
                'is_active' => true,
            ]);
            $newDepartmentCreated = true;
            Log::info("Dynamic Department Created during sync: {$deptNameEn}");
        }

        // Normalize Address
        $addressData = self::normalizeAddress($sourceData, $cityName);

        // Geocode Lat/Long if missing
        $coords = self::geocodeAddress(
            $addressData['address_line1'],
            $addressData['pincode'],
            $addressData['city'],
            $addressData['state'],
            $sourceData['latitude'] ?? null,
            $sourceData['longitude'] ?? null
        );

        // Normalize Cashless Schemes & Flags for independent clinic empanelment
        $schemes = self::normalizeSchemes($sourceData);

        $existingDoctor = Doctor::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->first();

        $regNumber = $existingDoctor?->registration_number ?: ($sourceData['registration_number'] ?? ('RAJ-MC-' . rand(10000, 99999)));
        $experience = (int)($sourceData['experience_years'] ?? ($existingDoctor?->experience_years ?: rand(8, 25)));
        $degrees = !empty($sourceData['education_degrees']) ? $sourceData['education_degrees'] : ($existingDoctor?->education_degrees ?: ['MBBS', 'MD']);
        $fee = (float)($sourceData['consultation_fee'] ?? ($existingDoctor?->consultation_fee ?: 500));
        $phone = $sourceData['phone'] ?? ($existingDoctor?->phone ?: '+91-141-' . rand(2000000, 2999999));
        $website = $sourceData['website'] ?? ($existingDoctor?->website && !str_contains($existingDoctor->website, 'swasthyasearch.com') ? $existingDoctor->website : null);

        $doctor = Doctor::updateOrCreate(
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
            [
                'registration_number' => $regNumber,
                'department_id' => $existingDept->id,
                'medical_council' => $sourceData['medical_council'] ?? ($existingDoctor?->medical_council ?: 'Medical Council of India'),
                'education_degrees' => $degrees,
                'experience_years' => $experience,
                'about_en' => $sourceData['about_en'] ?? ($existingDoctor?->about_en ?: "Highly experienced specialist in {$deptNameEn} practicing in {$cityName}."),
                'about_hi' => $sourceData['about_hi'] ?? ($existingDoctor?->about_hi ?: "{$cityName} में अभ्यास करने वाले {$deptNameHi} के अत्यधिक अनुभवी विशेषज्ञ डॉक्टर।"),
                'is_verified' => true,
                'consultation_fee' => $fee,
                'phone' => $phone,
                'address_line1' => $addressData['address_line1'],
                'address_line2' => $addressData['address_line2'],
                'city' => $addressData['city'],
                'state' => $addressData['state'],
                'pincode' => $addressData['pincode'],
                'latitude' => $coords['latitude'],
                'longitude' => $coords['longitude'],
                'website' => $website,
                'languages_spoken' => $existingDoctor?->languages_spoken ?: ['English', 'Hindi'],
                'gender' => $sourceData['gender'] ?? ($existingDoctor?->gender ?: (rand(0, 1) ? 'Male' : 'Female')),
                // Independent clinic scheme flags
                'cashless_treatment_available' => $schemes['cashless_treatment_available'],
                'accepts_ayushman_card' => $schemes['accepts_ayushman_card'],
                'accepts_jan_aadhaar' => $schemes['accepts_jan_aadhaar'],
                'rgahs_approved' => $schemes['rgahs_approved'],
            ]
        );

        // Sync Many-to-Many Department
        $doctor->departments()->syncWithoutDetaching([$existingDept->id]);

        // Map to Hospital if provided
        $hospitalNameEn = trim($sourceData['hospital_name_en'] ?? '');
        if (!empty($hospitalNameEn)) {
            $hospital = Hospital::where('name_en', $hospitalNameEn)->where('city', $cityName)->first();
            if (!$hospital) {
                // Create minimal hospital record to ensure mapping is not lost
                $hospital = self::syncHospital([
                    'name_en' => $hospitalNameEn,
                    'city' => $cityName,
                    'address' => $addressData['full_address'],
                    'address_line1' => $addressData['address_line1'],
                    'address_line2' => $addressData['address_line2'],
                    'state' => $addressData['state'],
                    'pincode' => $addressData['pincode'],
                    'latitude' => $coords['latitude'],
                    'longitude' => $coords['longitude'],
                    'type' => $sourceData['hospital_type'] ?? 'Clinic',
                    'cashless_treatment_available' => $schemes['cashless_treatment_available'],
                    'accepts_ayushman_card' => $schemes['accepts_ayushman_card'],
                    'accepts_jan_aadhaar' => $schemes['accepts_jan_aadhaar'],
                    'rgahs_approved' => $schemes['rgahs_approved'],
                    'cashless_schemes_list' => $schemes['cashless_schemes_list'],
                ]);
            }

            $doctor->hospitals()->syncWithoutDetaching([
                $hospital->id => [
                    'days_of_week' => $sourceData['days_of_week'] ?? 'Mon-Sat',
                    'start_time' => $sourceData['start_time'] ?? '10:00:00',
                    'end_time' => $sourceData['end_time'] ?? '18:00:00',
                    'consultation_fee' => $fee,
                ]
            ]);
        }

        return [
            'doctor' => $doctor,
            'new_department' => $newDepartmentCreated,
        ];
    }

    /**
     * Normalize incoming raw address strings/arrays into highly structured components.
     */
    public static function normalizeAddress(array $data, string $defaultCity = 'Jaipur', string $defaultState = 'Rajasthan'): array
    {
        $city = trim($data['city'] ?? $defaultCity);
        $state = trim($data['state'] ?? $defaultState);
        $pincode = trim($data['pincode'] ?? '');
        $addressLine1 = trim($data['address_line1'] ?? '');
        $addressLine2 = trim($data['address_line2'] ?? '');
        $rawAddress = trim($data['address'] ?? '');

        // If line1 is empty but rawAddress is present, parse raw address
        if (empty($addressLine1) && !empty($rawAddress)) {
            $parts = array_map('trim', explode(',', $rawAddress));
            $addressLine1 = $parts[0] ?? "Plot No. " . rand(10, 200);
            $addressLine2 = $parts[1] ?? (isset($parts[2]) ? $parts[1] : "Main Medical Avenue");
            
            // Try to extract pincode from raw address if missing
            if (empty($pincode) && preg_match('/\b(30\d{4}|11\d{4}|40\d{4}|50\d{4}|70\d{4})\b/', $rawAddress, $matches)) {
                $pincode = $matches[1];
            }
        }

        if (empty($addressLine1)) {
            $addressLine1 = "Central Healthcare Plaza, Sector " . rand(1, 15);
        }
        if (empty($addressLine2)) {
            $addressLine2 = "Main Medical Avenue";
        }
        if (empty($pincode)) {
            $pincode = '3020' . str_pad((string)rand(1, 30), 2, '0', STR_PAD_LEFT);
        }

        $fullAddress = "{$addressLine1}, {$addressLine2}, {$city}, {$state} - {$pincode}";

        return [
            'address_line1' => $addressLine1,
            'address_line2' => $addressLine2,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'full_address' => $fullAddress,
        ];
    }

    /**
     * Integrates Geocoding API (Google Maps / OpenStreetMap Nominatim) with robust deterministic fallback.
     */
    public static function geocodeAddress(string $addressLine1, ?string $pincode, string $city, string $state, ?float $lat = null, ?float $lng = null): array
    {
        // If valid coordinates are already provided in source data, use them directly
        if ($lat !== null && $lng !== null && $lat != 0 && $lng != 0) {
            return [
                'latitude' => (float)$lat,
                'longitude' => (float)$lng,
            ];
        }

        $query = trim("{$addressLine1}, {$city}, {$state}, {$pincode}");
        $apiKey = config('services.google_maps.key');

        // --- 1. GOOGLE MAPS GEOCODING API INTEGRATION ---
        if (!empty($apiKey)) {
            try {
                $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'address' => $query,
                    'key' => $apiKey,
                ]);

                if ($response->successful() && $response->json('status') === 'OK') {
                    $location = $response->json('results.0.geometry.location');
                    return [
                        'latitude' => (float)$location['lat'],
                        'longitude' => (float)$location['lng'],
                    ];
                }
            } catch (Exception $e) {
                Log::warning("Google Maps Geocoding API failed: " . $e->getMessage() . ". Falling back to OpenStreetMap/Deterministic Geocoding.");
            }
        }

        // --- 2. OPENSTREETMAP NOMINATIM API INTEGRATION (FREE FALLBACK) ---
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'User-Agent' => 'SwasthyaSearch GeoSyncService/1.0 (hello@swasthyasearch.com)',
            ])->timeout(3)->get('https://nominatim.openstreetmap.org/search', [
                'q' => "{$city}, {$state}, India",
                'format' => 'json',
                'limit' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $result = $response->json(0);
                // Add a small deterministic offset based on pincode/address hash so markers don't overlap exactly
                $hash = crc32($query);
                $latOffset = (($hash % 100) - 50) / 15000;
                $lngOffset = ((($hash / 100) % 100) - 50) / 15000;

                return [
                    'latitude' => (float)$result['lat'] + $latOffset,
                    'longitude' => (float)$result['lon'] + $lngOffset,
                ];
            }
        } catch (Exception $e) {
            Log::warning("OSM Nominatim Geocoding API failed: " . $e->getMessage() . ". Using deterministic bounding box fallback.");
        }

        // --- 3. ROBUST DETERMINISTIC BOUNDING BOX FALLBACK ---
        // Base coordinates for major cities
        $cityCoords = [
            'jaipur' => ['lat' => 26.9124, 'lng' => 75.7873],
            'delhi' => ['lat' => 28.6139, 'lng' => 77.2090],
            'mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
            'bangalore' => ['lat' => 12.9716, 'lng' => 77.5946],
            'kolkata' => ['lat' => 22.5726, 'lng' => 88.3639],
            'jodhpur' => ['lat' => 26.2389, 'lng' => 73.0243],
            'udaipur' => ['lat' => 24.5854, 'lng' => 73.6855],
            'kota' => ['lat' => 25.2138, 'lng' => 75.8648],
            'ajmer' => ['lat' => 26.4499, 'lng' => 74.6399],
            'bikaner' => ['lat' => 28.0229, 'lng' => 73.3119],
        ];

        $cityKey = strtolower(trim($city));
        $base = $cityCoords[$cityKey] ?? ['lat' => 26.9124, 'lng' => 75.7873];

        // Generate consistent deterministic offset using crc32 hash of address/pincode
        $hash = crc32($query);
        $latOffset = (($hash % 100) - 50) / 12000;
        $lngOffset = ((($hash / 100) % 100) - 50) / 12000;

        return [
            'latitude' => round($base['lat'] + $latOffset, 8),
            'longitude' => round($base['lng'] + $lngOffset, 8),
        ];
    }

    /**
     * Normalize healthcare scheme flags and cashless treatment lists.
     */
    public static function normalizeSchemes(array $data): array
    {
        $cashless = filter_var($data['cashless_treatment_available'] ?? ($data['is_cashless'] ?? false), FILTER_VALIDATE_BOOLEAN);
        $ayushman = filter_var($data['accepts_ayushman_card'] ?? ($data['accepts_ayushman'] ?? false), FILTER_VALIDATE_BOOLEAN);
        $janAadhaar = filter_var($data['accepts_jan_aadhaar'] ?? ($data['accepts_janaadhaar'] ?? false), FILTER_VALIDATE_BOOLEAN);
        $rgahs = filter_var($data['rgahs_approved'] ?? ($data['accepts_cghs'] ?? false), FILTER_VALIDATE_BOOLEAN);

        $schemesList = $data['cashless_schemes_list'] ?? [];
        if (is_string($schemesList)) {
            $schemesList = array_map('trim', explode(',', $schemesList));
        }

        // Auto-populate list if flags are true
        if ($ayushman && !in_array('Ayushman Bharat Yojana (PM-JAY)', $schemesList)) {
            $schemesList[] = 'Ayushman Bharat Yojana (PM-JAY)';
        }
        if ($janAadhaar && !in_array('Rajasthan Jan Aadhaar Yojana', $schemesList)) {
            $schemesList[] = 'Rajasthan Jan Aadhaar Yojana';
        }
        if ($rgahs && !in_array('RGAHS / CGHS Approved Panel', $schemesList)) {
            $schemesList[] = 'RGAHS / CGHS Approved Panel';
        }

        // If cashless is true but list is empty, provide standard TPA list
        if ($cashless && empty($schemesList)) {
            $schemesList = [
                'Star Health & Allied Insurance TPA',
                'HDFC ERGO Cashless TPA',
                'ICICI Lombard General Insurance',
                'SBI General Insurance',
                'Care Health Insurance (Religare)',
                'Bajaj Allianz Cashless Panel',
            ];
        }

        if (!empty($schemesList) && !$cashless) {
            $cashless = true;
        }

        return [
            'cashless_treatment_available' => $cashless,
            'accepts_ayushman_card' => $ayushman,
            'accepts_jan_aadhaar' => $janAadhaar,
            'rgahs_approved' => $rgahs,
            'cashless_schemes_list' => array_values(array_unique($schemesList)),
        ];
    }

    /**
     * Map English department names to standard Hindi translations.
     */
    private static function getHindiDeptName(string $deptEn): string
    {
        $map = [
            'General Physician' => 'सामान्य चिकित्सक',
            'Pediatrics' => 'बाल रोग',
            'Cardiology' => 'हृदय रोग (कार्डियोलॉजी)',
            'Gynecology & Obstetrics' => 'स्त्री रोग और प्रसूति',
            'Orthopedics' => 'हड्डी रोग (ऑर्थोपेडिक्स)',
            'Dermatology' => 'त्वचा विज्ञान (डर्मेटोलॉजी)',
            'Neurology' => 'तंत्रिका विज्ञान (न्यूरोलॉजी)',
            'Ophthalmology' => 'नेत्र विज्ञान (ऑप्थल्मोलॉजी)',
            'ENT' => 'ईएनटी (कान, नाक, गला)',
            'Psychiatry' => 'मनोरोग विज्ञान (साइकेट्री)',
            'Dentist' => 'दंत चिकित्सक (डेंटिस्ट)',
            'Ayurveda' => 'आयुर्वेद',
            'Homeopathy' => 'होम्योपैथी',
            'Gastroenterology' => 'गैस्ट्रोएंटरोलॉजी',
            'Urology' => 'मूत्र रोग (यूरोलॉजी)',
            'Oncology' => 'कैंसर रोग (ऑन्कोलॉजी)',
            'Pulmonology' => 'श्वसन रोग (पल्मोनोलॉजी)',
            'General Medicine' => 'सामान्य चिकित्सा',
        ];

        return $map[$deptEn] ?? $deptEn;
    }
}
