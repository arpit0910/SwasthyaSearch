<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\SymptomTestSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SymptomTestFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_symptom_test_page_loads(): void
    {
        $response = $this->get('/symptom-test');

        $response->assertOk();
        $response->assertSee('Symptom Test', false);
        $response->assertSee('data-step-panel="1"', false);
        $response->assertSee('data-step-panel="2"', false);
        $response->assertSee('data-step-panel="3"', false);
    }

    public function test_symptom_analysis_returns_likely_condition_and_follow_up_symptoms(): void
    {
        $department = Department::create([
            'name_en' => 'General Medicine',
            'name_hi' => 'सामान्य चिकित्सा',
            'description_en' => 'Primary care',
            'description_hi' => 'प्राथमिक देखभाल',
            'is_active' => true,
        ]);

        $disease = Disease::create([
            'name_en' => 'Influenza',
            'name_hi' => 'इन्फ्लुएंजा',
            'department_id' => $department->id,
        ]);

        $fever = Symptom::create([
            'name_en' => 'Fever',
            'name_hi' => 'बुखार',
        ]);

        $cough = Symptom::create([
            'name_en' => 'Cough',
            'name_hi' => 'खांसी',
        ]);

        $ache = Symptom::create([
            'name_en' => 'Muscle Ache',
            'name_hi' => 'मांसपेशियों में दर्द',
        ]);

        $disease->symptoms()->sync([$fever->id, $cough->id, $ache->id]);

        $response = $this->postJson('/api/symptom-test/analyze', [
            'age' => 34,
            'gender' => 'male',
            'symptoms' => ['Fever', 'Cough'],
            'symptom_text' => '',
        ]);

        $response->assertOk();
        $response->assertJsonPath('likely_conditions.0.name.en', 'Influenza');
        $response->assertJsonPath('likely_conditions.0.department.name.en', 'General Medicine');
        $response->assertJsonFragment(['label' => 'Muscle Ache']);
        $this->assertDatabaseHas('symptom_test_submissions', [
            'age' => 34,
            'gender' => 'male',
            'top_disease_id' => $disease->id,
        ]);
    }

    public function test_admin_symptom_reports_page_loads_with_submission_data(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $department = Department::create([
            'name_en' => 'General Medicine',
            'name_hi' => 'सामान्य चिकित्सा',
            'description_en' => 'Primary care',
            'description_hi' => 'प्राथमिक देखभाल',
            'is_active' => true,
        ]);

        $disease = Disease::create([
            'name_en' => 'Influenza',
            'name_hi' => 'इन्फ्लुएंजा',
            'department_id' => $department->id,
        ]);

        SymptomTestSubmission::create([
            'session_token' => 'session-1',
            'age' => 28,
            'gender' => 'female',
            'symptom_text' => 'Fever, cough',
            'selected_symptoms' => ['Fever', 'Cough'],
            'likely_conditions' => [['name' => 'Influenza', 'score' => 92]],
            'next_symptoms' => ['Shortness of breath'],
            'recommended_department_id' => $department->id,
            'top_disease_id' => $disease->id,
            'top_score' => 92.5,
            'locale' => 'en',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.symptom_tests'));

        $response->assertOk();
        $response->assertSee('Symptom Test Reports', false);
        $response->assertSee('Total Submissions', false);
        $response->assertSee('Influenza', false);
        $response->assertSee('Fever', false);
    }
}
