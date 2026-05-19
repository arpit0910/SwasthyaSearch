<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$cityStateMap = [
    'Jaipur' => 'Rajasthan',
    'Jodhpur' => 'Rajasthan',
    'Kota' => 'Rajasthan',
    'Mumbai' => 'Maharashtra',
    'Pune' => 'Maharashtra',
    'Bangalore' => 'Karnataka',
    'Chennai' => 'Tamil Nadu',
    'Hyderabad' => 'Telangana',
    'Delhi' => 'Delhi',
    'Kolkata' => 'West Bengal',
    'Ahmedabad' => 'Gujarat',
];

$stats = [
    'hospital_state_fixed' => 0,
    'hospital_address_fixed' => 0,
    'hospital_address_line1_fixed' => 0,
    'doctor_state_fixed' => 0,
    'doctor_address_line1_fixed' => 0,
    'doctor_phone_backfilled' => 0,
    'doctor_pincode_backfilled' => 0,
    'doctor_summary_backfilled' => 0,
];

DB::transaction(function () use ($cityStateMap, &$stats): void {
    $hospitals = DB::table('hospitals')->get([
        'id', 'city', 'state', 'address', 'address_line1', 'address_line2',
    ]);

    foreach ($hospitals as $hospital) {
        $city = trim((string) $hospital->city);
        if (!isset($cityStateMap[$city])) {
            continue;
        }

        $expectedState = $cityStateMap[$city];
        $update = [];

        if (trim((string) $hospital->state) !== $expectedState) {
            $update['state'] = $expectedState;
            $stats['hospital_state_fixed']++;
        }

        $address = trim((string) $hospital->address);
        if ($address !== '' && str_starts_with($address, 'Main Medical Avenue,')) {
            $update['address'] = "Main Medical Avenue, {$city}, {$expectedState}";
            $stats['hospital_address_fixed']++;
        }

        $line1 = trim((string) $hospital->address_line1);
        if ($line1 === '' || str_contains(strtolower($line1), 'main medical avenue')) {
            $update['address_line1'] = "Main Medical Avenue, {$city}";
            $stats['hospital_address_line1_fixed']++;
        }

        if ($update !== []) {
            DB::table('hospitals')->where('id', $hospital->id)->update($update);
        }
    }

    $doctorRows = DB::table('doctors')->leftJoin('departments', 'doctors.department_id', '=', 'departments.id')
        ->get([
            'doctors.id', 'doctors.city', 'doctors.state', 'doctors.phone', 'doctors.pincode',
            'doctors.address_line1', 'doctors.specialization_summary',
            'departments.name_en as department_name_en',
        ]);

    foreach ($doctorRows as $doctor) {
        $city = trim((string) $doctor->city);
        $update = [];

        if (isset($cityStateMap[$city])) {
            $expectedState = $cityStateMap[$city];
            if (trim((string) $doctor->state) !== $expectedState) {
                $update['state'] = $expectedState;
                $stats['doctor_state_fixed']++;
            }

            $line1 = trim((string) $doctor->address_line1);
            if ($line1 === '' || str_contains(strtolower($line1), 'main medical avenue')) {
                $update['address_line1'] = "Healthcare Center, {$city}";
                $stats['doctor_address_line1_fixed']++;
            }
        }

        if (trim((string) $doctor->specialization_summary) === '') {
            $dept = trim((string) $doctor->department_name_en);
            if ($dept !== '') {
                $update['specialization_summary'] = "Specialist in {$dept}";
                $stats['doctor_summary_backfilled']++;
            }
        }

        $needsPhone = trim((string) $doctor->phone) === '';
        $needsPincode = trim((string) $doctor->pincode) === '';

        if ($needsPhone || $needsPincode) {
            $linkedHospital = DB::table('doctor_hospital')
                ->join('hospitals', 'doctor_hospital.hospital_id', '=', 'hospitals.id')
                ->where('doctor_hospital.doctor_id', $doctor->id)
                ->orderBy('doctor_hospital.id')
                ->first(['hospitals.emergency_phone', 'hospitals.pincode']);

            if ($linkedHospital !== null) {
                if ($needsPhone && trim((string) $linkedHospital->emergency_phone) !== '') {
                    $update['phone'] = trim((string) $linkedHospital->emergency_phone);
                    $stats['doctor_phone_backfilled']++;
                }

                if ($needsPincode && trim((string) $linkedHospital->pincode) !== '') {
                    $update['pincode'] = trim((string) $linkedHospital->pincode);
                    $stats['doctor_pincode_backfilled']++;
                }
            }
        }

        if ($update !== []) {
            DB::table('doctors')->where('id', $doctor->id)->update($update);
        }
    }
});

echo "Data reliability repair completed:\n";
foreach ($stats as $key => $count) {
    echo "- {$key}: {$count}\n";
}
