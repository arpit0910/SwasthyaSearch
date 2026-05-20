<?php

namespace Tests\Feature;

use App\Models\BloodBank;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReliableDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $dept = Department::create([
            'name_en' => 'Cardiology',
            'name_hi' => 'हृदय रोग विभाग',
            'description_en' => 'Heart care',
            'description_hi' => 'हृदय देखभाल',
            'is_active' => true,
        ]);

        $hospital = Hospital::create([
            'name_en' => 'Sawai Man Singh (SMS) Hospital',
            'name_hi' => 'सवाई मान सिंह (एसएमएस) अस्पताल',
            'type' => 'Government Hospital',
            'address' => 'JLN Marg, Jaipur, Rajasthan - 302004',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'emergency_phone' => '0141-2560291',
            'is_verified' => true,
        ]);

        $doctor = Doctor::create([
            'first_name' => 'Rajeev',
            'last_name' => 'Bagarhatta',
            'department_id' => $dept->id,
            'registration_number' => 'RAJ-MC-14892',
            'experience_years' => 32,
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'phone' => '0141-2560291',
            'is_verified' => true,
        ]);

        $doctor->departments()->attach($dept->id);
        $doctor->hospitals()->attach($hospital->id);

        BloodBank::create([
            'name_en' => 'SMS Hospital Blood Bank',
            'name_hi' => 'एसएमएस अस्पताल ब्लड बैंक',
            'city' => 'Jaipur',
            'address_en' => 'JLN Marg, Jaipur',
            'address_hi' => 'जेएलएन मार्ग, जयपुर',
            'phone' => '0141-2560291',
            'is_verified' => true,
            'source_verification' => 'verified_active',
        ]);
    }

    public function test_city_based_search_returns_accurate_records()
    {
        $response = $this->getJson('/api/directory?city=Jaipur');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('counts.doctors', 1)
                 ->assertJsonPath('counts.hospitals', 1)
                 ->assertJsonPath('counts.blood_banks', 1);
    }

    public function test_doctor_department_filtering()
    {
        $response = $this->getJson('/api/doctors?city=Jaipur&department=Cardiology');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.department', 'Cardiology');
    }

    public function test_hospital_search()
    {
        $response = $this->getJson('/api/hospitals?city=Jaipur');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name_en', 'Sawai Man Singh (SMS) Hospital');
    }

    public function test_blood_bank_search()
    {
        $response = $this->getJson('/api/blood-banks?city=Jaipur');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name_en', 'SMS Hospital Blood Bank');
    }

    public function test_invalid_city_handling_returns_empty_array_and_clear_message()
    {
        $response = $this->getJson('/api/doctors?city=UnknownCity');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('message', 'No reliable doctor records found for the selected filters.')
                 ->assertJsonCount(0, 'data');
    }

    public function test_duplicate_prevention_during_sync()
    {
        $initialCount = Doctor::count();

        \App\Services\HealthcareSyncService::syncDoctor([
            'first_name' => 'Rajeev',
            'last_name' => 'Bagarhatta',
            'city' => 'Jaipur',
            'department_name_en' => 'Cardiology',
            'registration_number' => 'RAJ-MC-14892',
            'experience_years' => 35, // updated experience
        ]);

        $this->assertEquals($initialCount, Doctor::count());
        $this->assertEquals(35, Doctor::where('registration_number', 'RAJ-MC-14892')->first()->experience_years);
    }

    public function test_empty_verified_results()
    {
        $response = $this->getJson('/api/doctors?city=EmptyCity');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(0, 'data');
    }

    public function test_removal_of_dummy_data()
    {
        $dummyDoctor = Doctor::create([
            'first_name' => 'Test',
            'last_name' => 'Doctor',
            'city' => 'Jaipur',
            'is_verified' => false, // unverified dummy record
        ]);

        $response = $this->getJson('/api/doctors?city=Jaipur');

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonCount(1, 'data') // only the verified doctor from setUp
                 ->assertJsonMissing(['first_name' => 'Test']);
    }

    public function test_reliable_api_responses_include_source_metadata()
    {
        $response = $this->getJson('/api/directory?city=Jaipur');

        $response->assertStatus(200)
                 ->assertJsonPath('hospitals.0.source.name', null)
                 ->assertJsonPath('blood_banks.0.source.verification_status', 'verified_active');
    }

    public function test_correct_department_mapping()
    {
        $doc = Doctor::with('departments')->where('first_name', 'Rajeev')->first();

        $this->assertTrue($doc->departments->contains('name_en', 'Cardiology'));
    }
}
