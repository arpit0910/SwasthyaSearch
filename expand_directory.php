<?php

function appendToFallbackMethod($filePath, $methodName, $newArrayCode)
{
    if (!file_exists($filePath)) {
        echo "File not found: $filePath\n";
        return;
    }

    $content = file_get_contents($filePath);

    // Find the method
    $pattern = '/(protected|private)\s+static\s+function\s+' . $methodName . '\s*\([^)]*\)\s*:\s*array\s*\{.*?(return\s*\[)(.*?)(\];\s*\})/s';

    if (preg_match($pattern, $content, $matches)) {
        // Find the last closing bracket of the return array
        // Instead of regex for the inner content which is complex, we find the method signature and then match braces.

        $methodPos = strpos($content, 'function ' . $methodName);
        if ($methodPos === false) return;

        $returnPos = strpos($content, 'return [', $methodPos);
        if ($returnPos === false) return;

        // Find the closing bracket of this return [
        $bracketCount = 1;
        $i = $returnPos + 8;
        $lastBracketPos = -1;

        while ($i < strlen($content) && $bracketCount > 0) {
            if ($content[$i] === '[') {
                $bracketCount++;
            } elseif ($content[$i] === ']') {
                $bracketCount--;
                if ($bracketCount === 0) {
                    $lastBracketPos = $i;
                    break;
                }
            }
            $i++;
        }

        if ($lastBracketPos !== -1) {
            // Insert the new code before the last bracket
            // Make sure the new code ends with a comma
            $insertion = "\n" . rtrim($newArrayCode, " \t\n\r\0\x0B,") . ",\n        ";
            $newContent = substr($content, 0, $lastBracketPos) . $insertion . substr($content, $lastBracketPos);
            file_put_contents($filePath, $newContent);
            echo "Successfully updated $methodName in " . basename($filePath) . "\n";
        }
    } else {
        echo "Could not find method $methodName in $filePath\n";
    }
}

$citiesData = [
    'JodhpurScraperService.php' => [
        'getFallbackBloodBanks' => <<<PHP
            [
                'name_en' => 'Umaid Hospital Blood Bank',
                'name_hi' => 'उम्मेद अस्पताल ब्लड बैंक',
                'city' => 'Jodhpur',
                'state' => 'Rajasthan',
                'pincode' => '342001',
                'address_en' => 'Siwanchi Gate, Jodhpur, Rajasthan - 342001',
                'address_hi' => 'सिवांची गेट, जोधपुर, राजस्थान - 342001',
                'country_code' => '+91',
                'phone' => '0291-2432611',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0291-2432611',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => false,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'MDM Hospital Blood Bank',
                'name_hi' => 'एमडीएम अस्पताल ब्लड बैंक',
                'city' => 'Jodhpur',
                'state' => 'Rajasthan',
                'pincode' => '342003',
                'address_en' => 'Shastri Nagar, Jodhpur, Rajasthan - 342003',
                'address_hi' => 'शास्त्री नगर, जोधपुर, राजस्थान - 342003',
                'country_code' => '+91',
                'phone' => '0291-2434374',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0291-2434374',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ]
PHP,
        'getFallbackHospitals' => <<<PHP
            [
                'name_en' => 'Mathura Das Mathur (MDM) Hospital',
                'name_hi' => 'मथुरा दास माथुर (एमडीएम) अस्पताल',
                'type' => 'Government Hospital',
                'address' => 'Shastri Nagar, Jodhpur, Rajasthan - 342003',
                'address_line1' => 'Shastri Nagar',
                'address_line2' => '',
                'city' => 'Jodhpur',
                'state' => 'Rajasthan',
                'pincode' => '342003',
                'emergency_phone' => '0291-2434374',
                'emergency_country_code' => '+91',
                'latitude' => 26.2655,
                'longitude' => 73.0054,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => true,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Rajasthan Jan Aadhaar Yojana', 'CGHS'],
            ],
            [
                'name_en' => 'All India Institute of Medical Sciences (AIIMS), Jodhpur',
                'name_hi' => 'अखिल भारतीय आयुर्विज्ञान संस्थान (एम्स), जोधपुर',
                'type' => 'Government Hospital',
                'address' => 'Basni Industrial Area, Phase-2, Jodhpur, Rajasthan - 342005',
                'address_line1' => 'Basni Industrial Area',
                'address_line2' => 'Phase-2',
                'city' => 'Jodhpur',
                'state' => 'Rajasthan',
                'pincode' => '342005',
                'emergency_phone' => '0291-2742200',
                'emergency_country_code' => '+91',
                'latitude' => 26.2238,
                'longitude' => 73.0069,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'CGHS'],
            ],
            [
                'name_en' => 'Medipulse Hospital',
                'name_hi' => 'मेडीपल्स अस्पताल',
                'type' => 'Private Hospital',
                'address' => 'E-4, MIA, Basni II Phase, Jodhpur, Rajasthan - 342005',
                'address_line1' => 'E-4, MIA',
                'address_line2' => 'Basni II Phase',
                'city' => 'Jodhpur',
                'state' => 'Rajasthan',
                'pincode' => '342005',
                'emergency_phone' => '0291-2760600',
                'emergency_country_code' => '+91',
                'latitude' => 26.2289,
                'longitude' => 73.0112,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => true,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Rajasthan Jan Aadhaar Yojana', 'CGHS', 'HDFC ERGO TPA'],
            ]
PHP
    ],
    'KotaScraperService.php' => [
        'getFallbackBloodBanks' => <<<PHP
            [
                'name_en' => 'MBS Hospital Blood Bank',
                'name_hi' => 'एमबीएस अस्पताल ब्लड बैंक',
                'city' => 'Kota',
                'state' => 'Rajasthan',
                'pincode' => '324001',
                'address_en' => 'Nayapura, Kota, Rajasthan - 324001',
                'address_hi' => 'नयापुरा, कोटा, राजस्थान - 324001',
                'country_code' => '+91',
                'phone' => '0744-2320015',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0744-2320015',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'Indian Red Cross Society Blood Bank Kota',
                'name_hi' => 'इंडियन रेड क्रॉस सोसाइटी ब्लड बैंक कोटा',
                'city' => 'Kota',
                'state' => 'Rajasthan',
                'pincode' => '324005',
                'address_en' => 'Talwandi, Kota, Rajasthan - 324005',
                'address_hi' => 'तलवंडी, कोटा, राजस्थान - 324005',
                'country_code' => '+91',
                'phone' => '0744-2426868',
                'emergency_country_code' => '+91',
                'emergency_phone' => '0744-2426868',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => false,
                'component_facility' => true,
                'apheresis_facility' => false,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ]
PHP,
        'getFallbackHospitals' => <<<PHP
            [
                'name_en' => 'Government Medical College & MBS Hospital',
                'name_hi' => 'सरकारी मेडिकल कॉलेज एवं एमबीएस अस्पताल',
                'type' => 'Government Hospital',
                'address' => 'Nayapura, Kota, Rajasthan - 324001',
                'address_line1' => 'Nayapura',
                'address_line2' => '',
                'city' => 'Kota',
                'state' => 'Rajasthan',
                'pincode' => '324001',
                'emergency_phone' => '0744-2320015',
                'emergency_country_code' => '+91',
                'latitude' => 25.1856,
                'longitude' => 75.8291,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => true,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Rajasthan Jan Aadhaar Yojana', 'CGHS'],
            ],
            [
                'name_en' => 'Sudha Hospital & Medical Research Centre',
                'name_hi' => 'सुधा अस्पताल एवं चिकित्सा अनुसंधान केंद्र',
                'type' => 'Private Hospital',
                'address' => '11-A, Talwandi, Kota, Rajasthan - 324005',
                'address_line1' => '11-A',
                'address_line2' => 'Talwandi',
                'city' => 'Kota',
                'state' => 'Rajasthan',
                'pincode' => '324005',
                'emergency_phone' => '0744-2422201',
                'emergency_country_code' => '+91',
                'latitude' => 25.1481,
                'longitude' => 75.8368,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => true,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Rajasthan Jan Aadhaar Yojana', 'CGHS', 'Bajaj Allianz TPA'],
            ]
PHP
    ],
    'JaipurScraperService.php' => [
        'getFallbackBloodBanks' => <<<PHP
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
PHP
    ],
    'MumbaiScraperService.php' => [
        'getFallbackBloodBanks' => <<<PHP
            [
                'name_en' => 'Tata Memorial Hospital Blood Bank',
                'name_hi' => 'टाटा मेमोरियल अस्पताल ब्लड बैंक',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400012',
                'address_en' => 'Dr. E Borges Road, Parel, Mumbai, Maharashtra - 400012',
                'address_hi' => 'डॉ. ई बोर्जेस रोड, परेल, मुंबई, महाराष्ट्र - 400012',
                'country_code' => '+91',
                'phone' => '022-24177000',
                'emergency_country_code' => '+91',
                'emergency_phone' => '022-24177000',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'KEM Hospital Blood Bank',
                'name_hi' => 'केईएम अस्पताल ब्लड बैंक',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400012',
                'address_en' => 'Acharya Donde Marg, Parel, Mumbai, Maharashtra - 400012',
                'address_hi' => 'आचार्य दोंदे मार्ग, परेल, मुंबई, महाराष्ट्र - 400012',
                'country_code' => '+91',
                'phone' => '022-24107000',
                'emergency_country_code' => '+91',
                'emergency_phone' => '022-24107000',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => true,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ],
            [
                'name_en' => 'Lilavati Hospital Blood Bank',
                'name_hi' => 'लीलावती अस्पताल ब्लड बैंक',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400050',
                'address_en' => 'A-791, Bandra Reclamation, Bandra West, Mumbai, Maharashtra - 400050',
                'address_hi' => 'ए-791, बांद्रा रिक्लेमेशन, बांद्रा वेस्ट, मुंबई, महाराष्ट्र - 400050',
                'country_code' => '+91',
                'phone' => '022-26751000',
                'emergency_country_code' => '+91',
                'emergency_phone' => '022-26751000',
                'is_verified' => true,
                'is_24_7' => true,
                'is_government' => false,
                'component_facility' => true,
                'apheresis_facility' => true,
                'available_blood_groups' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            ]
PHP,
        'getFallbackHospitals' => <<<PHP
            [
                'name_en' => 'Tata Memorial Hospital',
                'name_hi' => 'टाटा मेमोरियल अस्पताल',
                'type' => 'Government Hospital',
                'address' => 'Dr. E Borges Road, Parel, Mumbai, Maharashtra - 400012',
                'address_line1' => 'Dr. E Borges Road',
                'address_line2' => 'Parel',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400012',
                'emergency_phone' => '022-24177000',
                'emergency_country_code' => '+91',
                'latitude' => 19.0049,
                'longitude' => 72.8427,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'CGHS', 'Mahatma Jyotiba Phule Jan Arogya Yojana'],
            ],
            [
                'name_en' => 'Lilavati Hospital & Research Centre',
                'name_hi' => 'लीलावती अस्पताल एवं अनुसंधान केंद्र',
                'type' => 'Private Hospital',
                'address' => 'A-791, Bandra Reclamation, Bandra West, Mumbai, Maharashtra - 400050',
                'address_line1' => 'A-791, Bandra Reclamation',
                'address_line2' => 'Bandra West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400050',
                'emergency_phone' => '022-26751000',
                'emergency_country_code' => '+91',
                'latitude' => 19.0507,
                'longitude' => 72.8286,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat', 'CGHS', 'HDFC ERGO TPA', 'ICICI Lombard TPA'],
            ],
            [
                'name_en' => 'King Edward Memorial (KEM) Hospital',
                'name_hi' => 'किंग एडवर्ड मेमोरियल (केईएम) अस्पताल',
                'type' => 'Government Hospital',
                'address' => 'Acharya Donde Marg, Parel, Mumbai, Maharashtra - 400012',
                'address_line1' => 'Acharya Donde Marg',
                'address_line2' => 'Parel',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400012',
                'emergency_phone' => '022-24107000',
                'emergency_country_code' => '+91',
                'latitude' => 19.0031,
                'longitude' => 72.8415,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Mahatma Jyotiba Phule Jan Arogya Yojana', 'CGHS'],
            ]
PHP
    ],
    'DelhiScraperService.php' => [
        'getFallbackHospitals' => <<<PHP
            [
                'name_en' => 'Safdarjung Hospital',
                'name_hi' => 'सफदरजंग अस्पताल',
                'type' => 'Government Hospital',
                'address' => 'Ansari Nagar West, New Delhi, Delhi - 110029',
                'address_line1' => 'Ansari Nagar West',
                'address_line2' => '',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110029',
                'emergency_phone' => '011-26592800',
                'emergency_country_code' => '+91',
                'latitude' => 28.5684,
                'longitude' => 77.2066,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat Yojana (PM-JAY)', 'Central Government Health Scheme (CGHS)', 'Delhi Arogya Kosh'],
            ],
            [
                'name_en' => 'Indraprastha Apollo Hospital',
                'name_hi' => 'इंद्रप्रस्थ अपोलो अस्पताल',
                'type' => 'Private Hospital',
                'address' => 'Sarita Vihar, Delhi Mathura Road, New Delhi, Delhi - 110076',
                'address_line1' => 'Sarita Vihar',
                'address_line2' => 'Delhi Mathura Road',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110076',
                'emergency_phone' => '011-29871090',
                'emergency_country_code' => '+91',
                'latitude' => 28.5323,
                'longitude' => 77.2869,
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => false,
                'accepts_cghs' => true,
                'is_cashless' => true,
                'cashless_schemes_list' => ['Ayushman Bharat', 'CGHS', 'ECHS', 'Star Health TPA', 'Bajaj Allianz TPA'],
            ]
PHP
    ],
];

foreach ($citiesData as $fileName => $methods) {
    $filePath = __DIR__ . '/app/Services/' . $fileName;
    foreach ($methods as $method => $code) {
        appendToFallbackMethod($filePath, $method, $code);
    }
}

echo "Done.\n";
