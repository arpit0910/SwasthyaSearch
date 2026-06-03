<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuizFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_pages_load_and_result_is_generated(): void
    {
        $quiz = Quiz::create([
            'title_en' => 'Stress Check Quiz',
            'title_hi' => 'तनाव जाँच क्विज़',
            'slug' => 'stress-check-quiz',
            'questions_json' => [
                [
                    'question_en' => 'Question 1',
                    'question_hi' => 'प्रश्न 1',
                    'options' => [
                        ['label_en' => 'A', 'label_hi' => 'A', 'score' => 0],
                        ['label_en' => 'B', 'label_hi' => 'B', 'score' => 2],
                    ],
                ],
            ],
            'result_ranges_json' => [
                ['min' => 0, 'max' => 0, 'title_en' => 'Low', 'title_hi' => 'कम', 'message_en' => 'Low score', 'message_hi' => 'कम स्कोर'],
                ['min' => 1, 'max' => 5, 'title_en' => 'High', 'title_hi' => 'अधिक', 'message_en' => 'High score', 'message_hi' => 'अधिक स्कोर'],
            ],
            'disclaimer_en' => 'Awareness only.',
            'disclaimer_hi' => 'केवल जागरूकता।',
            'is_published' => true,
        ]);

        $this->get(route('quizzes.index'))
            ->assertOk()
            ->assertSee('Stress Check Quiz', false);

        $this->get(route('quizzes.show', $quiz->slug))
            ->assertOk()
            ->assertSee('Question 1', false);

        $this->withSession(['_token' => 'quiz-test-token'])
            ->post(route('quizzes.result', $quiz->slug), [
                '_token' => 'quiz-test-token',
                'answers' => [0 => 1],
            ])->assertOk()->assertSee('High', false);
    }

    public function test_games_pages_load(): void
    {
        $this->get(route('activities.games.memory'))
            ->assertOk()
            ->assertSee('Memory Game', false);

        $this->get(route('activities.games.calm-tap'))
            ->assertOk()
            ->assertSee('Calm Tap Counter', false);
    }

    public function test_admin_quiz_pages_load(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        Quiz::create([
            'title_en' => 'Stress Check Quiz',
            'title_hi' => 'तनाव जाँच क्विज़',
            'slug' => 'stress-check-quiz',
            'is_published' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.quizzes'))
            ->assertOk()
            ->assertSee('Quiz Management', false)
            ->assertSee('Stress Check Quiz', false);
    }
}
