<?php

namespace Tests\Feature;

use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_prompts_for_city_when_missing(): void
    {
        Hospital::create([
            'name_en' => 'City Care Hospital',
            'name_hi' => 'सिटी केयर हॉस्पिटल',
            'type' => 'Hospital',
            'address' => 'Jaipur',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'is_verified' => true,
        ]);

        $response = $this->postJson('/api/chatbot', [
            'message' => 'I need a doctor',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonPath('needs_city', true)
            ->assertJsonPath('city_options.0', 'Jaipur')
            ->assertJsonStructure([
                'session_token',
                'reply',
                'needs_city',
                'city_options',
                'history',
            ]);
    }

    public function test_chatbot_allows_boot_when_no_city_options_exist(): void
    {
        $response = $this->postJson('/api/chatbot', [
            'message' => '',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonMissingPath('needs_city')
            ->assertJsonPath('city', '')
            ->assertJsonPath('city_options', [])
            ->assertJsonPath('reply', '');
    }
}
