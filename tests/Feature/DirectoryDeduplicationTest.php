<?php

namespace Tests\Feature;

use App\Models\BloodBank;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DirectoryDeduplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_directory_deduplication_command_merges_duplicate_records(): void
    {
        $department = Department::create([
            'name_en' => 'Cardiology',
            'name_hi' => 'हृदय रोग विभाग',
            'description_en' => 'Heart care',
            'description_hi' => 'हृदय देखभाल',
            'is_active' => true,
        ]);

        $canonicalHospital = Hospital::create([
            'name_en' => 'Metro Heart Hospital',
            'name_hi' => 'मेट्रो हार्ट हॉस्पिटल',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'phone_1' => '0141000001',
            'is_verified' => true,
        ]);

        $duplicateHospital = Hospital::create([
            'name_en' => 'Metro Heart Hospital',
            'name_hi' => 'मेट्रो हार्ट हॉस्पिटल',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'phone_1' => '0141000001',
            'address_line1' => 'Civil Lines',
            'is_verified' => true,
        ]);

        $canonicalDoctor = Doctor::create([
            'first_name' => 'Amit',
            'last_name' => 'Sharma',
            'department_id' => $department->id,
            'registration_number' => 'REG-ABC12345',
            'city' => 'Jaipur',
            'phone_1' => '9999999999',
            'is_verified' => true,
        ]);

        $duplicateDoctor = Doctor::create([
            'first_name' => 'Amit',
            'last_name' => 'Sharma',
            'department_id' => $department->id,
            'registration_number' => 'REG-ABC12345',
            'city' => 'Jaipur',
            'phone_1' => '9999999999',
            'experience_years' => 12,
            'is_verified' => true,
        ]);

        DB::table('department_doctor')->insert([
            ['doctor_id' => $canonicalDoctor->id, 'department_id' => $department->id, 'created_at' => now(), 'updated_at' => now()],
            ['doctor_id' => $duplicateDoctor->id, 'department_id' => $department->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('doctor_hospital')->insert([
            ['doctor_id' => $canonicalDoctor->id, 'hospital_id' => $canonicalHospital->id, 'created_at' => now(), 'updated_at' => now()],
            ['doctor_id' => $duplicateDoctor->id, 'hospital_id' => $duplicateHospital->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        BloodBank::create([
            'name_en' => 'City Blood Bank',
            'name_hi' => 'सिटी ब्लड बैंक',
            'city' => 'Jaipur',
            'phone' => '0141222333',
            'is_verified' => true,
        ]);

        BloodBank::create([
            'name_en' => 'City Blood Bank',
            'name_hi' => 'सिटी ब्लड बैंक',
            'city' => 'Jaipur',
            'phone' => '0141222333',
            'address_en' => 'MI Road',
            'is_verified' => true,
        ]);

        $this->artisan('healthcare:dedupe Jaipur')
            ->assertExitCode(0);

        $this->assertSame(1, Doctor::count());
        $this->assertSame(1, Hospital::count());
        $this->assertSame(1, BloodBank::count());

        $doctor = Doctor::firstOrFail();
        $hospital = Hospital::firstOrFail();
        $bloodBank = BloodBank::firstOrFail();

        $this->assertSame(12, $doctor->experience_years);
        $this->assertSame('Civil Lines', $hospital->address_line1);
        $this->assertSame('MI Road', $bloodBank->address_en);

        $this->assertSame(1, DB::table('doctor_hospital')->count());
        $this->assertSame(1, DB::table('department_doctor')->count());
        $this->assertSame($hospital->id, DB::table('doctor_hospital')->value('hospital_id'));
        $this->assertSame($doctor->id, DB::table('doctor_hospital')->value('doctor_id'));
    }
}
