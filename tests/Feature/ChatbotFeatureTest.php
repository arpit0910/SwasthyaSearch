<?php

namespace Tests\Feature;

use App\Models\ChatSession;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_defaults_to_jaipur_when_city_is_missing(): void
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
            ->assertJsonMissingPath('needs_city')
            ->assertJsonPath('city', 'Jaipur')
            ->assertJsonPath('city_options.0', 'Jaipur')
            ->assertJsonStructure([
                'session_token',
                'reply',
                'city',
                'city_options',
                'history',
            ]);
    }

    public function test_chatbot_boots_in_jaipur_even_without_city_options_in_database(): void
    {
        $response = $this->postJson('/api/chatbot', [
            'message' => '',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonMissingPath('needs_city')
            ->assertJsonPath('city', 'Jaipur')
            ->assertJsonPath('city_options.0', 'Jaipur')
            ->assertJsonPath('reply', '');
    }

    public function test_chatbot_normalizes_stale_session_history_before_returning_it(): void
    {
        ChatSession::create([
            'session_token' => 'stale-session-token',
            'messages' => [
                null,
                'bad-entry',
                ['sender' => 'bot', 'text' => 'Older reply', 'doctors' => 'invalid'],
                ['sender' => 'user', 'text' => 'Previous question'],
                ['sender' => 'unknown', 'text' => 'Skip me'],
            ],
        ]);

        $response = $this->postJson('/api/chatbot', [
            'session_token' => 'stale-session-token',
            'message' => '',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonCount(2, 'history')
            ->assertJsonPath('history.0.sender', 'bot')
            ->assertJsonPath('history.0.text', 'Older reply')
            ->assertJsonPath('history.0.doctors', [])
            ->assertJsonPath('history.1.sender', 'user')
            ->assertJsonPath('history.1.text', 'Previous question');
    }
}
