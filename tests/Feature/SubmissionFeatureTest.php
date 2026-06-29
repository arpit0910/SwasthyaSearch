<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\UserSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubmissionFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggestion_page_loads_successfully(): void
    {
        $response = $this->get(route('suggestions.create'));
        $response->assertOk();
        $response->assertSee('Share Doctor or Hospital Details');
    }

    public function test_visitor_can_submit_doctor_suggestion(): void
    {
        $response = $this->post(route('suggestions.store'), [
            'type' => 'doctor',
            'name' => 'Dr. Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
            'address' => 'Malviya Nagar, Jaipur',
            'registration_number' => 'R-778899',
            'specialization' => 'Cardiologist',
            'latitude' => '26.9124',
            'longitude' => '75.7873',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_submissions', [
            'type' => 'doctor',
            'status' => 'pending',
            'name' => 'Dr. Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
        ]);

        $submission = UserSubmission::first();
        $this->assertEquals('Malviya Nagar, Jaipur', $submission->details['address']);
        $this->assertEquals('R-778899', $submission->details['registration_number']);
        $this->assertEquals('Cardiologist', $submission->details['specialization']);
        $this->assertEquals(26.9124, $submission->details['latitude']);
        $this->assertEquals(75.7873, $submission->details['longitude']);
    }

    public function test_visitor_can_submit_hospital_suggestion(): void
    {
        $response = $this->post(route('suggestions.store'), [
            'type' => 'hospital',
            'name' => 'Verma Heart Care',
            'phone' => '8888777766',
            'city' => 'Jaipur',
            'address' => 'C-Scheme, Jaipur',
            'hospital_type' => 'Multispecialty',
            'accepts_ayushman' => '1',
            'accepts_janaadhaar' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_submissions', [
            'type' => 'hospital',
            'status' => 'pending',
            'name' => 'Verma Heart Care',
            'phone' => '8888777766',
            'city' => 'Jaipur',
        ]);

        $submission = UserSubmission::first();
        $this->assertEquals('C-Scheme, Jaipur', $submission->details['address']);
        $this->assertEquals('Multispecialty', $submission->details['hospital_type']);
        $this->assertTrue($submission->details['accepts_ayushman']);
        $this->assertTrue($submission->details['accepts_janaadhaar']);
    }

    public function test_validation_fails_for_missing_required_fields(): void
    {
        $response = $this->from(route('suggestions.create'))
            ->post(route('suggestions.store'), [
                'type' => 'doctor',
            ]);

        $response->assertRedirect(route('suggestions.create'));
        $response->assertSessionHasErrors(['name', 'city', 'address', 'registration_number', 'specialization']);
    }

    public function test_guests_cannot_access_admin_submissions(): void
    {
        $response = $this->get(route('admin.submissions'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_submissions_list(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        UserSubmission::create([
            'type' => 'doctor',
            'status' => 'pending',
            'name' => 'Dr. Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
            'details' => ['address' => 'Jaipur', 'registration_number' => 'R-1', 'specialization' => 'General'],
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.submissions'));
        $response->assertOk();
        $response->assertSee('Dr. Aditi Verma');
    }

    public function test_admin_can_reject_submission(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $sub = UserSubmission::create([
            'type' => 'doctor',
            'status' => 'pending',
            'name' => 'Dr. Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
            'details' => ['address' => 'Jaipur', 'registration_number' => 'R-1', 'specialization' => 'General'],
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.submissions.reject', $sub));
        $response->assertRedirect();
        $this->assertEquals('rejected', $sub->fresh()->status);
    }

    public function test_admin_can_export_submissions_csv(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        UserSubmission::create([
            'type' => 'doctor',
            'status' => 'pending',
            'name' => 'Dr. Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
            'details' => ['address' => 'Jaipur', 'registration_number' => 'R-1', 'specialization' => 'General'],
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.submissions.export'));
        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'attachment; filename=user_submissions_export.csv');

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('Dr. Aditi Verma', $content);
    }

    public function test_admin_can_approve_and_import_doctor_submission(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $sub = UserSubmission::create([
            'type' => 'doctor',
            'status' => 'pending',
            'name' => 'Aditi Verma',
            'phone' => '9999888877',
            'city' => 'Jaipur',
            'details' => [
                'address' => 'Malviya Nagar, Jaipur',
                'registration_number' => 'R-998877',
                'specialization' => 'Pediatrics',
                'latitude' => 26.9124,
                'longitude' => 75.7873,
            ],
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.submissions.import', $sub));
        $response->assertRedirect();
        $this->assertEquals('approved', $sub->fresh()->status);

        $this->assertDatabaseHas('doctors', [
            'first_name' => 'Aditi',
            'last_name' => 'Verma',
            'registration_number' => 'R-998877',
            'phone_1' => '9999888877',
            'city' => 'Jaipur',
            'latitude' => 26.9124,
            'longitude' => 75.7873,
            'is_verified' => true,
        ]);
    }

    public function test_admin_can_approve_and_import_hospital_submission(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $sub = UserSubmission::create([
            'type' => 'hospital',
            'status' => 'pending',
            'name' => 'Jaipur Heart Hospital',
            'phone' => '8888777766',
            'city' => 'Jaipur',
            'details' => [
                'address' => 'Mansarovar, Jaipur',
                'hospital_type' => 'Multispecialty',
                'accepts_ayushman' => true,
                'accepts_janaadhaar' => true,
            ],
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.submissions.import', $sub));
        $response->assertRedirect();
        $this->assertEquals('approved', $sub->fresh()->status);

        $this->assertDatabaseHas('hospitals', [
            'name_en' => 'Jaipur Heart Hospital',
            'type' => 'Multispecialty',
            'phone_1' => '8888777766',
            'city' => 'Jaipur',
            'is_verified' => true,
            'accepts_ayushman_card' => true,
            'accepts_jan_aadhaar' => true,
        ]);
    }
}
