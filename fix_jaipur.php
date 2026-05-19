<?php
$filePath = __DIR__ . '/app/Services/JaipurScraperService.php';
$content = file_get_contents($filePath);

$method = <<<PHP
    protected static function getFallbackBloodBanks(string \$cityName): array
    {
        if (strtolower(trim(\$cityName)) !== 'jaipur') {
            return [];
        }

        return [
            [
                'name_en' => 'SMS Hospital Blood Bank',
                'name_hi' => 'एसएमएस अस्पताल ब्लड बैंक',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302004',
                'address_en' => 'JLN Marg, Jaipur, Rajasthan - 302004',
                'address_hi' => 'जेएलएन मार्ग, जयपुर, राजस्थान - 302004',
                'country_code' => '+91',
                'phone' => '0141-2560291',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0141-2560291',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'Santokba Durlabhji Memorial Hospital (SDMH) Blood Bank',
                'name_hi' => 'संतोकबा दुर्लभजी मेमोरियल अस्पताल ब्लड बैंक',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302015',
                'address_en' => 'Bhawani Singh Marg, Rambagh, Jaipur, Rajasthan - 302015',
                'address_hi' => 'भवानी सिंह मार्ग, रामबाग, जयपुर, राजस्थान - 302015',
                'country_code' => '+91',
                'phone' => '0141-2566251',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0141-2566251',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => false,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'Rotary Blood Bank Jaipur',
                'name_hi' => 'रोटरी ब्लड बैंक जयपुर',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'pincode' => '302004',
                'address_en' => 'Bapu Nagar, Jaipur, Rajasthan - 302004',
                'address_hi' => 'बापू नगर, जयपुर, राजस्थान - 302004',
                'country_code' => '+91',
                'phone' => '0141-2708306',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0141-2708306',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => false,
                'component_facility' => true,
                'apheresis_facility' => false,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ]
        ];
    }

PHP;

$content = str_replace('private static function getFallbackHospitals', $method . "\n    private static function getFallbackHospitals", $content);
file_put_contents($filePath, $content);
echo "Done.\n";
