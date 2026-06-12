<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotConversationalTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_returns_live_conversational_response_using_mocked_groq(): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'reply' => 'Hello! I am Jeeva. I can assist you with your health query.',
                                'detailed_reply' => 'Feel free to ask detailed medical questions.',
                                'department' => 'General Medicine',
                                'symptom_match' => false,
                                'emergency' => false,
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/chatbot', [
            'message' => 'Hello',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonPath('reply', 'Hello! I am Jeeva. I can assist you with your health query.')
            ->assertJsonPath('qa_answer.category', 'General Medicine')
            ->assertJsonPath('qa_answer.detailed_answer', 'Feel free to ask detailed medical questions.')
            ->assertJsonPath('symptom_match', false);
    }

    public function test_chatbot_symptom_match_routes_department_and_suggests_details(): void
    {
        $dept = Department::create([
            'name_en' => 'Cardiology',
            'name_hi' => 'हृदय रोग विभाग',
            'is_active' => true,
        ]);

        $doctor = Doctor::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'department_id' => $dept->id,
            'experience_years' => 12,
            'is_verified' => true,
        ]);

        $hospital = Hospital::create([
            'name_en' => 'Jaipur Heart Hospital',
            'name_hi' => 'जयपुर हार्ट अस्पताल',
            'type' => 'Hospital',
            'address' => 'Jaipur',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'is_verified' => true,
        ]);

        $doctor->hospitals()->attach($hospital->id);

        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'reply' => 'Chest pain should be checked. I recommend seeing a Cardiologist.',
                                'detailed_reply' => 'Chest pain can be serious...',
                                'department' => 'Cardiology',
                                'symptom_match' => true,
                                'emergency' => false,
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/api/chatbot', [
            'message' => 'I have chest pain',
            'city' => 'Jaipur',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonPath('reply', 'Chest pain should be checked. I recommend seeing a Cardiologist.')
            ->assertJsonPath('symptom_match', true)
            ->assertJsonPath('suggest_details', true);
    }
}
