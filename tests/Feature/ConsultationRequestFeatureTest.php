<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationRequestFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_accepts_consultation_request_submission(): void
    {
        $response = $this->post(route('consultation-requests.submit'), [
            'name' => 'Riya Sharma',
            'email' => 'riya@example.com',
            'phone' => '9876543210',
            'reason' => 'I need guidance for a recurring migraine and want to discuss treatment options.',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '15:30',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('consultation_request_success');

        $this->assertDatabaseHas('consultation_requests', [
            'name' => 'Riya Sharma',
            'email' => 'riya@example.com',
            'phone' => '9876543210',
            'preferred_time' => '15:30',
        ]);
    }

    public function test_consultation_request_requires_all_required_fields(): void
    {
        $response = $this->from(route('home'))
            ->post(route('consultation-requests.submit'), []);

        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone',
            'reason',
            'preferred_date',
            'preferred_time',
        ]);
    }
}
