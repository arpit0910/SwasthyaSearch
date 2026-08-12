<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Services\DoctorImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DoctorImportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_preserves_blank_values_without_fallback_defaults(): void
    {
        app(DoctorImportService::class)->importRows([
            [
                'first_name' => 'Riya',
                'last_name' => '',
                'registration_number' => 'REG-1001',
                'department_name_en' => '',
                'medical_council' => '',
                'country_code_1' => '',
                'phone_1' => '',
                'consultation_fee' => '',
                'experience_years' => '',
                'education_degrees' => '',
                'about_en' => '',
                'about_hi' => '',
                'city' => '',
                'state' => '',
                'pincode' => '',
                'address_line1' => '',
                'languages_spoken' => '',
                'gender' => '',
                'is_verified' => '',
            ],
        ]);

        $doctor = Doctor::where('registration_number', 'REG-1001')->firstOrFail();

        $this->assertSame('Riya', $doctor->first_name);
        $this->assertSame('', $doctor->last_name);
        $this->assertNull($doctor->department_id);
        $this->assertNull($doctor->medical_council);
        $this->assertNull($doctor->country_code_1);
        $this->assertNull($doctor->phone_1);
        $this->assertNull($doctor->consultation_fee);
        $this->assertNull($doctor->experience_years);
        $this->assertNull($doctor->education_degrees);
        $this->assertNull($doctor->about_en);
        $this->assertNull($doctor->about_hi);
        $this->assertNull($doctor->city);
        $this->assertNull($doctor->state);
        $this->assertNull($doctor->pincode);
        $this->assertNull($doctor->address_line1);
        $this->assertNull($doctor->languages_spoken);
        $this->assertNull($doctor->gender);
        $this->assertNull($doctor->is_verified);
        $this->assertSame(0, DB::table('doctor_hospital')->count());
    }

    public function test_import_links_doctor_to_existing_hospital_from_flat_row(): void
    {
        $department = Department::create([
            'name_en' => 'Cardiology',
            'name_hi' => 'हृदय रोग विभाग',
            'description_en' => 'Heart care',
            'description_hi' => 'Heart care',
            'is_active' => true,
        ]);

        $hospital = Hospital::create([
            'name_en' => 'Apex Heart Hospital',
            'name_hi' => 'Apex Heart Hospital',
            'type' => 'Hospital',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'phone_1' => '0141000000',
            'is_verified' => true,
        ]);

        app(DoctorImportService::class)->importRows([
            [
                'doctor_hospital_link_id' => 'LINK-JPR-1',
                'registration_number' => 'REG-2002',
                'first_name' => 'Amit',
                'last_name' => 'Sharma',
                'department_name_en' => 'Cardiology',
                'hospital_id' => (string) $hospital->id,
                'hospital_name' => 'Apex Heart Hospital',
                'hospital_city' => 'Jaipur',
                'doctor_hospital_role' => 'Consultant',
                'consultation_mode' => 'In-person',
                'availability' => 'Mon-Fri OPD',
                'days_of_week' => 'Mon-Fri',
                'start_time' => '10:00',
                'end_time' => '13:00',
                'hospital_consultation_fee' => '900',
            ],
        ]);

        $doctor = Doctor::where('registration_number', 'REG-2002')->firstOrFail();
        $pivot = DB::table('doctor_hospital')->where('doctor_id', $doctor->id)->where('hospital_id', $hospital->id)->first();

        $this->assertSame($department->id, $doctor->department_id);
        $this->assertNotNull($pivot);
        $this->assertSame('LINK-JPR-1', $pivot->external_link_id);
        $this->assertSame('Consultant', $pivot->role);
        $this->assertSame('In-person', $pivot->consultation_mode);
        $this->assertSame('Mon-Fri OPD', $pivot->availability);
        $this->assertSame('Mon-Fri', $pivot->days_of_week);
        $this->assertSame('10:00', $pivot->start_time);
        $this->assertSame('13:00', $pivot->end_time);
        $this->assertSame(900.0, (float) $pivot->consultation_fee);
    }

    public function test_import_accepts_the_real_jaipur_doctor_hospital_csv_shape(): void
    {
        app(DoctorImportService::class)->importRows([
            [
                'doctor_hospital_link_id' => 'DHL-JAI-AC3B4AF9697730',
                'doctor_id' => 'DOC-JAI-2FD48CC2453C',
                'hospital_id' => '19',
                'doctor_full_name' => 'Dr. Jayant Sen',
                'first_name' => 'Jayant',
                'last_name' => 'Sen',
                'gender' => '',
                'qualification' => 'MS, DNB (Orthopedics)',
                'specialization' => 'Orthopaedics & Joint Replacement',
                'subspecialization' => 'Orthopaedics & Joint Replacement',
                'years_of_experience' => '30+',
                'registration_number' => '',
                'medical_council' => '',
                'phone' => '1413524444',
                'mobile' => '',
                'email' => '',
                'website' => '',
                'consultation_fee' => '',
                'languages_spoken' => '',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'country' => 'India',
                'full_address' => 'Bhawani Singh Marg, Near Rambagh Circle, Jaipur, Rajasthan 302015',
                'pincode' => '302015',
                'area_locality' => 'Rambagh Circle',
                'latitude' => '26.89270000',
                'longitude' => '75.80780000',
                'hospital_name' => 'Santokba Durlabhji Memorial Hospital (SDMH)',
                'hospital_type' => 'Semi-Private Hospital',
                'hospital_department' => 'Orthopaedics & Joint Replacement',
                'hospital_phone' => '1413524444',
                'hospital_email' => 'info@sdmh.in',
                'hospital_website' => 'https://sdmh.in',
                'hospital_address' => 'Bhawani Singh Marg, Near Rambagh Circle, Jaipur, Rajasthan 302015',
                'hospital_city' => 'Jaipur',
                'hospital_state' => 'Rajasthan',
                'hospital_country' => 'India',
                'hospital_pincode' => '302015',
                'doctor_hospital_role' => 'Senior Consultant',
                'consultation_mode' => '',
                'availability' => '',
                'source_url' => 'https://sdmh.in/centre-of-excellence/67ef66f1748736a26205e4f3',
                'source_name' => 'Santokba Durlabhji Memorial Hospital – Official Doctor Directory',
                'verification_status' => 'Verified',
                'last_verified_date' => '2026-08-12',
                'notes' => 'Doctor–hospital association verified from the current official hospital directory.',
            ],
        ]);

        $doctor = Doctor::where('first_name', 'Jayant')->where('last_name', 'Sen')->firstOrFail();
        $hospital = Hospital::where('name_en', 'Santokba Durlabhji Memorial Hospital (SDMH)')->firstOrFail();
        $pivot = DB::table('doctor_hospital')->where('doctor_id', $doctor->id)->where('hospital_id', $hospital->id)->first();

        $this->assertSame(30, $doctor->experience_years);
        $this->assertSame('Jaipur', $doctor->city);
        $this->assertSame('Rajasthan', $doctor->state);
        $this->assertSame('1413524444', $doctor->phone_1);
        $this->assertSame('Rambagh Circle', $doctor->landmark);
        $this->assertSame('Bhawani Singh Marg, Near Rambagh Circle, Jaipur, Rajasthan 302015', $doctor->address_line1);
        $this->assertSame('Semi-Private Hospital', $hospital->type);
        $this->assertSame('Jaipur', $hospital->city);
        $this->assertSame('1413524444', $hospital->phone_1);
        $this->assertNotNull($pivot);
        $this->assertSame('DHL-JAI-AC3B4AF9697730', $pivot->external_link_id);
        $this->assertSame('Senior Consultant', $pivot->role);
    }
}
