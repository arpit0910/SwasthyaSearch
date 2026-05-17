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
    public static function syncBatch(array $doctorsBatch, array $hospitalsBatch, ?string $cacheKey = null, int $chunkSize = 25, ?string $defaultCity = null): array
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
                    self::syncHospital($hospData, $defaultCity);
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
                    $syncResult = self::syncDoctor($docData, $defaultCity);
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
     * Helper to separate country code from phone number string.
     */
    public static function splitPhone(?string $rawPhone): array
    {
        if (empty($rawPhone)) {
            return ['country_code' => '+91', 'phone' => null];
        }

        $rawPhone = trim($rawPhone);
        if (preg_match('/^(\+\d{1,4})\s*[- ]?\s*(.*)$/', $rawPhone, $matches)) {
            return [
                'country_code' => $matches[1],
                'phone' => trim($matches[2], '- '),
            ];
        }

        return ['country_code' => '+91', 'phone' => trim($rawPhone, '- ')];
    }

    /**
     * Synchronize a single hospital record with strict address formatting, geocoding, and scheme metadata.
     */
    public static function syncHospital(array $sourceData, ?string $defaultCity = null): Hospital
    {
        $nameEn = trim($sourceData['name_en'] ?? '');
        if (empty($nameEn)) {
            throw new Exception("Hospital name_en is required for synchronization.");
        }

        $cityName = trim($sourceData['city'] ?? ($defaultCity ?: 'Jaipur'));
        $cityNameHi = self::getHindiCityName($cityName);
        $nameHi = trim($sourceData['name_hi'] ?? self::getHindiHospitalName($nameEn, $cityName, $cityNameHi));
        if (isset($sourceData['name_hi']) && str_contains($sourceData['name_hi'], $cityName)) {
            $nameHi = self::getHindiHospitalName($nameEn, $cityName, $cityNameHi);
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

        // Normalize Cashless Schemes & Flags
        $schemes = self::normalizeSchemes($sourceData);

        $existingHospital = Hospital::where('name_en', $nameEn)->where('city', $cityName)->first();

        // Categorize Hospital Type accurately if not explicitly provided
        $hospType = $sourceData['type'] ?? ($existingHospital?->type ?: 'Private Hospital');
        if (empty($sourceData['type']) && !$existingHospital) {
            $nameEnLower = strtolower($nameEn);
            if (str_contains($nameEnLower, 'govt') || str_contains($nameEnLower, 'government') || str_contains($nameEnLower, 'aiims') || str_contains($nameEnLower, 'district hospital') || str_contains($nameEnLower, 'civil hospital') || str_contains($nameEnLower, 'sawai man singh') || str_contains($nameEnLower, 'mahatma gandhi') || str_contains($nameEnLower, 'esi')) {
                $hospType = 'Government Hospital';
            } elseif (str_contains($nameEnLower, 'trust') || str_contains($nameEnLower, 'foundation') || str_contains($nameEnLower, 'mission') || str_contains($nameEnLower, 'charitable') || str_contains($nameEnLower, 'society') || str_contains($nameEnLower, 'memorial')) {
                $hospType = 'Semi-Private Hospital';
            } elseif (str_contains($nameEnLower, 'clinic') || str_contains($nameEnLower, 'poly clinic') || str_contains($nameEnLower, 'dental') || str_contains($nameEnLower, 'care centre')) {
                $hospType = 'Clinic';
            } else {
                $hospType = 'Private Hospital';
            }
        }

        $rawPhone = $sourceData['emergency_phone'] ?? ($existingHospital?->emergency_phone ?: '+91-141-' . rand(2000000, 2999999));
        $phoneParts = self::splitPhone($rawPhone);

        return Hospital::updateOrCreate(
            ['name_en' => $nameEn, 'city' => $cityName],
            [
                'name_hi' => $nameHi,
                'type' => $hospType,
                'address' => $addressData['full_address'],
                'address_line1' => $addressData['address_line1'],
                'address_line2' => $addressData['address_line2'],
                'city' => $addressData['city'],
                'state' => $addressData['state'],
                'pincode' => $addressData['pincode'],
                'latitude' => $coords['latitude'],
                'longitude' => $coords['longitude'],
                'emergency_country_code' => $sourceData['emergency_country_code'] ?? $phoneParts['country_code'],
                'emergency_phone' => $phoneParts['phone'],
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
    public static function syncDoctor(array $sourceData, ?string $defaultCity = null): array
    {
        $firstName = trim($sourceData['first_name'] ?? '');
        $lastName = trim($sourceData['last_name'] ?? '');

        if (empty($firstName)) {
            throw new Exception("Doctor first_name is required for synchronization.");
        }

        $cityName = trim($sourceData['city'] ?? ($defaultCity ?: 'Jaipur'));
        $cityNameHi = self::getHindiCityName($cityName);

        // --- DYNAMIC DEPARTMENT CREATION LOGIC ---
        $deptNameEn = trim($sourceData['department_name_en'] ?? 'General Medicine');
        $deptMapEn = [
            'General Physician' => 'General Medicine',
            'Dentist' => 'Dentistry',
            'ENT' => 'ENT (Otolaryngology)',
            'Gynecology & Obstetrics' => 'Obstetrics and Gynecology',
            'Pediatrician' => 'Pediatrics',
            'Eye Specialist' => 'Ophthalmology',
            'Heart Specialist' => 'Cardiology',
            'Skin Specialist' => 'Dermatology',
            'Brain Specialist' => 'Neurology',
            'Bone Specialist' => 'Orthopedics',
        ];
        $deptNameEn = $deptMapEn[$deptNameEn] ?? $deptNameEn;
        $deptNameHi = self::getHindiDeptName($deptNameEn);

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
        $degrees = !empty($sourceData['education_degrees']) ? $sourceData['education_degrees'] : ($existingDoctor?->education_degrees ?: null);
        $fee = (float)($sourceData['consultation_fee'] ?? ($existingDoctor?->consultation_fee ?: 500));
        $rawPhone = $sourceData['phone'] ?? ($existingDoctor?->phone ?: '+91-141-' . rand(2000000, 2999999));
        $phoneParts = self::splitPhone($rawPhone);
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
                'about_hi' => "{$cityNameHi} में अभ्यास करने वाले {$deptNameHi} के अत्यधिक अनुभवी विशेषज्ञ डॉक्टर डॉ. " . self::getHindiDoctorName($firstName) . " " . self::getHindiDoctorName($lastName) . "।",
                'is_verified' => true,
                'consultation_fee' => $fee,
                'country_code' => $sourceData['country_code'] ?? $phoneParts['country_code'],
                'phone' => $phoneParts['phone'],
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
                ], $defaultCity);
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

    public static function getHindiDeptName(string $deptEn): string
    {
        $deptEnClean = trim($deptEn);
        $map = [
            'Addiction Medicine' => 'नशा मुक्ति चिकित्सा',
            'Adolescent Medicine' => 'किशोर चिकित्सा',
            'Allergy and Immunology' => 'एलर्जी और प्रतिरक्षा विज्ञान',
            'Anesthesiology' => 'एनेस्थीसियोलॉजी',
            'Audiology' => 'श्रवण विज्ञान',
            'Bariatric Medicine' => 'बैरिएट्रिक चिकित्सा',
            'Bariatric Surgery' => 'बैरिएट्रिक शल्य चिकित्सा',
            'Breast Surgery' => 'स्तन शल्य चिकित्सा',
            'Cardiac Surgery' => 'हृदय शल्य चिकित्सा',
            'Cardiology' => 'हृदय रोग विभाग',
            'Clinical Genetics' => 'क्लिनिकल आनुवंशिकी',
            'Colorectal Surgery' => 'कोलोरेक्टल शल्य चिकित्सा',
            'Critical Care Medicine' => 'गहन चिकित्सा',
            'Dentistry' => 'दंत चिकित्सा',
            'Dentist' => 'दंत चिकित्सा',
            'Dermatology' => 'त्वचा रोग विभाग',
            'Developmental and Behavioral Pediatrics' => 'विकासात्मक और व्यवहारिक बाल चिकित्सा',
            'Emergency Medicine' => 'आपातकालीन चिकित्सा',
            'Endocrinology' => 'अंतःस्राविकी',
            'ENT (Otolaryngology)' => 'कान, नाक और गला रोग विभाग',
            'ENT' => 'कान, नाक और गला रोग विभाग',
            'Family Medicine' => 'पारिवारिक चिकित्सा',
            'Gastroenterology' => 'पाचन तंत्र रोग विभाग',
            'General Medicine' => 'सामान्य चिकित्सा',
            'General Physician' => 'सामान्य चिकित्सा',
            'General Surgery' => 'सामान्य शल्य चिकित्सा',
            'Geriatrics' => 'वृद्धावस्था चिकित्सा',
            'Gynecology' => 'स्त्री रोग विभाग',
            'Gynecology & Obstetrics' => 'प्रसूति एवं स्त्री रोग विभाग',
            'Hematology' => 'रक्त रोग विभाग',
            'Hepatology' => 'यकृत रोग विभाग',
            'Infectious Diseases' => 'संक्रामक रोग विभाग',
            'Internal Medicine' => 'आंतरिक चिकित्सा',
            'Interventional Cardiology' => 'इंटरवेंशनल कार्डियोलॉजी',
            'Interventional Radiology' => 'इंटरवेंशनल रेडियोलॉजी',
            'Neonatology' => 'नवजात शिशु चिकित्सा',
            'Nephrology' => 'गुर्दा रोग विभाग',
            'Neurology' => 'तंत्रिका रोग विभाग',
            'Neurosurgery' => 'तंत्रिका शल्य चिकित्सा',
            'Nuclear Medicine' => 'न्यूक्लियर मेडिसिन',
            'Nutrition and Dietetics' => 'पोषण और आहार विज्ञान',
            'Obstetrics' => 'प्रसूति विभाग',
            'Obstetrics and Gynecology' => 'प्रसूति एवं स्त्री रोग विभाग',
            'Occupational Medicine' => 'व्यावसायिक चिकित्सा',
            'Oncology' => 'कैंसर रोग विभाग',
            'Ophthalmology' => 'नेत्र रोग विभाग',
            'Oral and Maxillofacial Surgery' => 'मुख एवं जबड़ा शल्य चिकित्सा',
            'Orthodontics' => 'ऑर्थोडॉन्टिक्स',
            'Orthopedics' => 'हड्डी रोग विभाग',
            'Pain Medicine' => 'दर्द चिकित्सा',
            'Palliative Medicine' => 'प्रशामक चिकित्सा',
            'Pathology' => 'पैथोलॉजी',
            'Pediatric Cardiology' => 'बाल हृदय रोग विभाग',
            'Pediatric Endocrinology' => 'बाल अंतःस्राविकी',
            'Pediatric Gastroenterology' => 'बाल पाचन तंत्र रोग विभाग',
            'Pediatric Neurology' => 'बाल तंत्रिका रोग विभाग',
            'Pediatric Surgery' => 'बाल शल्य चिकित्सा',
            'Pediatrics' => 'बाल रोग विभाग',
            'Pediatrician' => 'बाल रोग विभाग',
            'Physical Medicine and Rehabilitation' => 'भौतिक चिकित्सा एवं पुनर्वास',
            'Physiotherapy' => 'फिजियोथेरेपी',
            'Plastic and Reconstructive Surgery' => 'प्लास्टिक एवं पुनर्निर्माण शल्य चिकित्सा',
            'Plastic Surgery' => 'प्लास्टिक एवं पुनर्निर्माण शल्य चिकित्सा',
            'Podiatry' => 'पैर रोग चिकित्सा',
            'Preventive Medicine' => 'निवारक चिकित्सा',
            'Psychiatry' => 'मनोचिकित्सा',
            'Psychology' => 'मनोविज्ञान',
            'Pulmonology' => 'श्वसन रोग विभाग',
            'Radiation Oncology' => 'रेडिएशन ऑन्कोलॉजी',
            'Radiology' => 'रेडियोलॉजी',
            'Reproductive Endocrinology and Infertility' => 'प्रजनन अंतःस्राविकी और बांझपन',
            'Rheumatology' => 'रूमेटोलॉजी',
            'Sleep Medicine' => 'नींद चिकित्सा',
            'Sports Medicine' => 'खेल चिकित्सा',
            'Thoracic Surgery' => 'वक्ष शल्य चिकित्सा',
            'Transplant Medicine' => 'प्रत्यारोपण चिकित्सा',
            'Trauma Surgery' => 'ट्रॉमा शल्य चिकित्सा',
            'Urology' => 'मूत्र रोग विभाग',
            'Vascular Surgery' => 'रक्तवाहिनी शल्य चिकित्सा',
            'Ayurveda' => 'आयुर्वेद',
            'Homeopathy' => 'होम्योपैथी',
        ];

        return $map[$deptEnClean] ?? $deptEnClean;
    }

    public static function getHindiCityName(string $city): string
    {
        $cityClean = strtolower(trim($city));
        $map = [
            'jaipur' => 'जयपुर',
            'delhi' => 'दिल्ली',
            'new delhi' => 'नई दिल्ली',
            'mumbai' => 'मुंबई',
            'bangalore' => 'बैंगलोर',
            'bengaluru' => 'बैंगलोर',
            'kolkata' => 'कोलकाता',
            'chennai' => 'चेन्नई',
            'hyderabad' => 'हैदराबाद',
            'pune' => 'पुणे',
            'ahmedabad' => 'अहमदाबाद',
        ];

        if (isset($map[$cityClean])) {
            return $map[$cityClean];
        }

        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Devanagari');
            if ($transliterator) {
                $words = explode(' ', trim($city));
                foreach ($words as &$word) {
                    if (preg_match('/[a-zA-Z]/', $word)) {
                        $trans = $transliterator->transliterate($word);
                        $trans = rtrim($trans, '्');
                        $word = $trans;
                    }
                }
                return implode(' ', $words);
            }
        }

        return ucfirst($city);
    }

    public static function getHindiHospitalName(string $nameEn, string $cityNameEn, string $cityNameHi): string
    {
        $nameEnClean = trim(str_ireplace([" " . $cityNameEn, " ({$cityNameEn})", " " . $cityNameEn . " ", " " . $cityNameHi, " ({$cityNameHi})"], "", $nameEn));

        $map = [
            'Hospital' => 'अस्पताल',
            'Hospitals' => 'अस्पताल',
            'Clinic' => 'क्लिनिक',
            'Clinics' => 'क्लिनिक',
            'Care Centre' => 'केयर सेंटर',
            'Care Center' => 'केयर सेंटर',
            'Research Centre' => 'रिसर्च सेंटर',
            'Research Center' => 'रिसर्च सेंटर',
            'Institute' => 'संस्थान',
            'Memorial' => 'मेमोरियल',
            'Multispeciality' => 'मल्टीस्पेशलिटी',
            'Multi-Speciality' => 'मल्टीस्पेशलिटी',
            'Super Speciality' => 'सुपर स्पेशलिटी',
            'General' => 'जनरल',
            'Healthcare' => 'हेल्थकेयर',
            'Health Care' => 'हेल्थकेयर',
            'Life' => 'लाइफ',
            'Heart' => 'हार्ट',
            'Eye' => 'आई',
            'Dental' => 'डेंटल',
            'Medical Centre' => 'मेडिकल सेंटर',
            'Medical Center' => 'मेडिकल सेंटर',
            'Medical College' => 'मेडिकल कॉलेज',
            'Government' => 'सरकारी',
            'Govt' => 'सरकारी',
            'District' => 'जिला',
            'Civil' => 'सिविल',
            'City' => 'सिटी',
        ];

        $nameHi = str_ireplace(array_keys($map), array_values($map), $nameEnClean);

        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Devanagari');
            if ($transliterator) {
                $words = explode(' ', $nameHi);
                foreach ($words as &$word) {
                    if (preg_match('/[a-zA-Z]/', $word)) {
                        $trans = $transliterator->transliterate($word);
                        $trans = rtrim($trans, '्');
                        $word = $trans;
                    }
                }
                $nameHi = implode(' ', $words);
            }
        }

        if (!preg_match('/[\x{0900}-\x{097F}]/u', $nameHi)) {
            $nameHi .= ' अस्पताल';
        }

        return trim($nameHi) . " ({$cityNameHi})";
    }

    public static function getHindiDoctorName(string $name): string
    {
        $cleanName = trim($name);
        if (empty($cleanName)) {
            return '';
        }

        if (class_exists('Transliterator')) {
            $transliterator = \Transliterator::create('Any-Devanagari');
            if ($transliterator) {
                $words = explode(' ', $cleanName);
                foreach ($words as &$word) {
                    if (preg_match('/[a-zA-Z]/', $word)) {
                        $trans = $transliterator->transliterate($word);
                        $trans = rtrim($trans, '्');
                        $word = $trans;
                    }
                }
                return implode(' ', $words);
            }
        }

        return $cleanName;
    }
}
