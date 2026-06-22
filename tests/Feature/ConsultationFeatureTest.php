<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Consultation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_create_a_consultation_and_is_redirected_to_room(): void
    {
        $response = $this->post(route('consultations.store'), [
            'patient_name' => 'Riya Sharma',
        ]);

        $consultation = Consultation::first();

        $this->assertNotNull($consultation);
        $this->assertSame('Riya Sharma', $consultation->patient_name);
        $this->assertSame(Consultation::STATUS_PENDING, $consultation->status);
        $this->assertNotEmpty($consultation->uuid);
        $this->assertMatchesRegularExpression('/^[a-z0-9]{3}-[a-z0-9]{4}-[a-z0-9]{3}$/', $consultation->uuid);

        $response->assertRedirect(route('consultations.room', $consultation->uuid));
    }

    public function test_signaling_endpoints_store_offer_answer_and_candidates(): void
    {
        $consultation = Consultation::create([
            'patient_name' => 'Arjun',
            'ice_candidates_patient' => [],
            'ice_candidates_doctor' => [],
        ]);

        $offer = ['type' => 'offer', 'sdp' => 'offer-sdp'];
        $answer = ['type' => 'answer', 'sdp' => 'answer-sdp'];
        $patientCandidate = ['candidate' => 'patient-candidate', 'sdpMid' => '0', 'sdpMLineIndex' => 0];
        $doctorCandidate = ['candidate' => 'doctor-candidate', 'sdpMid' => '0', 'sdpMLineIndex' => 0];

        $this->postJson(route('consultations.signal', $consultation->uuid), [
            'role' => 'patient',
            'sdp_offer' => $offer,
            'ice_candidates' => [$patientCandidate],
        ])->assertOk();

        $this->postJson(route('consultations.signal', $consultation->uuid), [
            'role' => 'doctor',
            'status' => 'active',
            'sdp_answer' => $answer,
            'ice_candidates' => [$doctorCandidate],
        ])->assertOk();

        $pollResponse = $this->getJson(route('consultations.poll', $consultation->uuid))
            ->assertOk()
            ->json();

        $this->assertSame('active', $pollResponse['status']);
        $this->assertSame($offer, $pollResponse['sdp_offer']);
        $this->assertSame($answer, $pollResponse['sdp_answer']);
        $this->assertSame([$patientCandidate], $pollResponse['ice_candidates_patient']);
        $this->assertSame([$doctorCandidate], $pollResponse['ice_candidates_doctor']);
    }

    public function test_patient_offer_does_not_downgrade_an_accepted_consultation(): void
    {
        $consultation = Consultation::create([
            'patient_name' => 'Neha',
            'status' => Consultation::STATUS_ACCEPTED,
            'ice_candidates_patient' => [],
            'ice_candidates_doctor' => [],
        ]);

        $this->postJson(route('consultations.signal', $consultation->uuid), [
            'role' => 'patient',
            'status' => Consultation::STATUS_PENDING,
            'sdp_offer' => ['type' => 'offer', 'sdp' => 'offer-sdp'],
        ])->assertOk();

        $this->assertSame(
            Consultation::STATUS_ACCEPTED,
            $consultation->fresh()->status
        );
    }

    public function test_admin_must_be_authenticated_to_access_consultation_dashboard(): void
    {
        $consultation = Consultation::create([
            'patient_name' => 'Sneha',
        ]);

        $this->get(route('admin.consultations'))
            ->assertRedirect(route('admin.login'));

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.consultations.join', $consultation->uuid))
            ->assertOk()
            ->assertSee('Join patient consultation');
    }

    public function test_admin_can_accept_and_reject_consultations(): void
    {
        $pendingConsultation = Consultation::create([
            'patient_name' => 'Kiran',
        ]);

        $rejectableConsultation = Consultation::create([
            'patient_name' => 'Maya',
        ]);

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.consultations.accept', $pendingConsultation->uuid))
            ->assertRedirect();

        $this->assertSame(
            Consultation::STATUS_ACCEPTED,
            $pendingConsultation->fresh()->status
        );

        $this->actingAs($admin, 'admin')
            ->post(route('admin.consultations.reject', $rejectableConsultation->uuid))
            ->assertRedirect();

        $this->assertSame(
            Consultation::STATUS_REJECTED,
            $rejectableConsultation->fresh()->status
        );
    }

    public function test_chat_messages_are_stored_polled_and_cleared_on_call_end(): void
    {
        $consultation = Consultation::create([
            'patient_name' => 'Arjun Sharma',
        ]);

        // 1. Send patient message
        $this->postJson(route('consultations.signal', $consultation->uuid), [
            'role' => 'patient',
            'message' => 'Hello Doctor!',
        ])->assertOk();

        // 2. Send doctor message
        $this->postJson(route('consultations.signal', $consultation->uuid), [
            'role' => 'doctor',
            'message' => 'Hello Arjun, how are you today?',
        ])->assertOk();

        // 3. Poll to verify messages exist
        $pollResponse = $this->getJson(route('consultations.poll', $consultation->uuid))
            ->assertOk()
            ->json();

        $this->assertCount(2, $pollResponse['chat_messages']);
        $this->assertSame('patient', $pollResponse['chat_messages'][0]['sender']);
        $this->assertSame('Arjun Sharma', $pollResponse['chat_messages'][0]['sender_name']);
        $this->assertSame('Hello Doctor!', $pollResponse['chat_messages'][0]['text']);

        $this->assertSame('doctor', $pollResponse['chat_messages'][1]['sender']);
        $this->assertSame('Doctor', $pollResponse['chat_messages'][1]['sender_name']);
        $this->assertSame('Hello Arjun, how are you today?', $pollResponse['chat_messages'][1]['text']);

        // 4. End call
        $this->postJson(route('consultations.end', $consultation->uuid))->assertOk();

        // 5. Poll to verify messages are cleared
        $pollResponseAfterEnd = $this->getJson(route('consultations.poll', $consultation->uuid))
            ->assertOk()
            ->json();

        $this->assertEmpty($pollResponseAfterEnd['chat_messages']);
        $this->assertNull($pollResponseAfterEnd['sdp_offer']);
        $this->assertNull($pollResponseAfterEnd['sdp_answer']);
        $this->assertSame([], $pollResponseAfterEnd['ice_candidates_patient']);
        $this->assertSame([], $pollResponseAfterEnd['ice_candidates_doctor']);
    }
}
