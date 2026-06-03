<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Medicine;
use App\Models\MedicineReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MedicineFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_medicine_is_visible_on_public_pages(): void
    {
        $medicine = Medicine::create([
            'name' => 'Paracetamol',
            'slug' => 'paracetamol',
            'generic_name' => 'Acetaminophen',
            'review_status' => 'published',
            'is_published' => true,
            'purpose_en' => 'Educational information for fever and pain.',
        ]);

        $this->get(route('medicines.index'))
            ->assertOk()
            ->assertSee('Paracetamol', false);

        $this->get(route('medicines.show', $medicine->slug))
            ->assertOk()
            ->assertSee('Medicine Safety Disclaimer', false)
            ->assertSee('Paracetamol', false);
    }

    public function test_unpublished_medicine_is_hidden_from_public_detail_page(): void
    {
        $medicine = Medicine::create([
            'name' => 'Hidden Medicine',
            'slug' => 'hidden-medicine',
            'review_status' => 'draft',
            'is_published' => false,
        ]);

        $this->get(route('medicines.show', $medicine->slug))->assertNotFound();
    }

    public function test_public_user_can_submit_medicine_report(): void
    {
        $medicine = Medicine::create([
            'name' => 'Paracetamol',
            'slug' => 'paracetamol',
            'review_status' => 'published',
            'is_published' => true,
        ]);

        $response = $this
            ->withSession(['_token' => 'test-token'])
            ->post(route('medicines.report', $medicine->slug), [
                '_token' => 'test-token',
                'report_type' => 'Wrong warning',
                'user_message' => 'This warning seems incomplete for liver disease users.',
                'corrected_information' => 'Please review the liver warning section.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('medicine_reports', [
            'medicine_id' => $medicine->id,
            'report_type' => 'Wrong warning',
            'status' => 'new',
        ]);
    }

    public function test_admin_can_view_medicine_management_pages(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        Medicine::create([
            'name' => 'Paracetamol',
            'slug' => 'paracetamol',
            'review_status' => 'published',
            'is_published' => true,
        ]);

        MedicineReport::create([
            'medicine_name' => 'Paracetamol',
            'report_type' => 'Wrong use',
            'user_message' => 'Needs review',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.medicines'))
            ->assertOk()
            ->assertSee('Medicine Information Management', false)
            ->assertSee('Paracetamol', false);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.medicine_reports'))
            ->assertOk()
            ->assertSee('Medicine Reports', false)
            ->assertSee('Wrong use', false);
    }
}
