<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$cityClasses = [
    'App\\Services\\AhmedabadScraperService' => 'Ahmedabad',
    'App\\Services\\BangaloreScraperService' => 'Bangalore',
    'App\\Services\\ChennaiScraperService' => 'Chennai',
    'App\\Services\\DelhiScraperService' => 'Delhi',
    'App\\Services\\HyderabadScraperService' => 'Hyderabad',
    'App\\Services\\JaipurScraperService' => 'Jaipur',
    'App\\Services\\JodhpurScraperService' => 'Jodhpur',
    'App\\Services\\KolkataScraperService' => 'Kolkata',
    'App\\Services\\KotaScraperService' => 'Kota',
    'App\\Services\\MumbaiScraperService' => 'Mumbai',
    'App\\Services\\PuneScraperService' => 'Pune',
];

$requiredFields = [
    'bloodbanks' => ['name_en', 'city', 'state', 'phone', 'emergency_phone', 'address_en'],
    'hospitals' => ['name_en', 'city', 'state', 'emergency_phone', 'address'],
    'doctors' => ['first_name', 'city', 'department_name_en', 'hospital_name_en'],
];

$optionalQualityFields = [
    'bloodbanks' => ['email', 'website', 'source_url'],
    'hospitals' => ['latitude', 'longitude', 'is_cashless'],
    'doctors' => ['registration_number', 'phone', 'source_url'],
];

$invokeProtected = static function (string $class, string $method, string $city): array {
    $ref = new ReflectionMethod($class, $method);
    $ref->setAccessible(true);
    $data = $ref->invoke(null, $city);
    return is_array($data) ? $data : [];
};

$nonEmptyCount = static function (array $rows, string $field): int {
    $count = 0;
    foreach ($rows as $row) {
        if (isset($row[$field]) && trim((string) $row[$field]) !== '') {
            $count++;
        }
    }
    return $count;
};

foreach ($cityClasses as $class => $city) {
    $bloodBanks = $invokeProtected($class, 'getFallbackBloodBanks', $city);
    $hospitals = $invokeProtected($class, 'getFallbackHospitals', $city);
    $doctors = $invokeProtected($class, 'getFallbackDoctors', $city);

    echo "=== {$city} ===\n";

    $datasets = [
        'bloodbanks' => $bloodBanks,
        'hospitals' => $hospitals,
        'doctors' => $doctors,
    ];

    foreach ($datasets as $datasetName => $rows) {
        $badCity = 0;
        $missing = array_fill_keys($requiredFields[$datasetName], 0);

        foreach ($rows as $row) {
            foreach ($requiredFields[$datasetName] as $field) {
                if (!isset($row[$field]) || trim((string) $row[$field]) === '') {
                    $missing[$field]++;
                }
            }

            if (isset($row['city']) && strcasecmp(trim((string) $row['city']), $city) !== 0) {
                if (!($city === 'Delhi' && strcasecmp(trim((string) $row['city']), 'New Delhi') === 0)) {
                    $badCity++;
                }
            }
        }

        $line = strtoupper($datasetName) . ': count=' . count($rows) . ' bad_city=' . $badCity;
        $missingParts = [];
        foreach ($missing as $field => $missingCount) {
            if ($missingCount > 0) {
                $missingParts[] = "{$field}:{$missingCount}";
            }
        }
        if ($missingParts !== []) {
            $line .= ' missing{' . implode(',', $missingParts) . '}';
        }

        $qualityParts = [];
        foreach ($optionalQualityFields[$datasetName] as $field) {
            $qualityParts[] = $field . ':' . $nonEmptyCount($rows, $field) . '/' . count($rows);
        }
        $line .= ' quality{' . implode(', ', $qualityParts) . '}';

        echo $line . "\n";
    }
}

