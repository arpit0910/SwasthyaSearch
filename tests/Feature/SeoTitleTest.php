<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Department;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTitleTest extends TestCase
{
    use RefreshDatabase;

    public function test_key_public_pages_include_arogio_in_title(): void
    {
        Department::create([
            'name_en' => 'General Medicine',
            'name_hi' => 'जनरल मेडिसिन',
            'is_active' => true,
        ]);

        $article = Article::create([
            'title_en' => 'Healthy Sleep Basics',
            'title_hi' => 'बेहतर नींद की बुनियाद',
            'excerpt_en' => 'Simple sleep basics.',
            'excerpt_hi' => 'बेहतर नींद के आसान तरीके।',
            'content_en' => 'Sleep well.',
            'content_hi' => 'अच्छी नींद लें।',
            'category' => 'Wellness',
            'author_name' => 'Arogio Team',
            'is_published' => true,
        ]);

        $quiz = Quiz::create([
            'title_en' => 'Stress Check Quiz',
            'title_hi' => 'स्ट्रेस चेक क्विज़',
            'slug' => 'stress-check-quiz',
            'description_en' => 'A simple awareness quiz.',
            'description_hi' => 'एक आसान awareness quiz।',
            'questions_json' => [],
            'result_ranges_json' => [],
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('Arogio', false);

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('Arogio', false);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Arogio', false);

        $this->get(route('quizzes.index'))
            ->assertOk()
            ->assertSee('Arogio', false);

        $this->get(route('quizzes.show', $quiz->slug))
            ->assertOk()
            ->assertSee('Arogio', false);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Arogio', false);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('<title>', false)
            ->assertSee('Arogio', false);
    }
}
