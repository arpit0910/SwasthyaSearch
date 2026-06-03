<?php

namespace Tests\Feature;

use Database\Seeders\ActivitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivitiesFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_activities_pages_load(): void
    {
        $this->seed(ActivitySeeder::class);

        $this->get(route('activities.index'))
            ->assertOk()
            ->assertSee('Activities', false)
            ->assertSee('Breathing Exercise', false)
            ->assertSee('Health Quizzes', false);

        $this->get(route('activities.breathing'))
            ->assertOk()
            ->assertSee('Breathing Exercise', false);

        $this->get(route('activities.grounding'))
            ->assertOk()
            ->assertSee('Grounding Exercise', false);

        $this->get(route('activities.mood-check'))
            ->assertOk()
            ->assertSee('Mood Check-in', false);

        $this->get(route('support.crisis'))
            ->assertOk()
            ->assertSee('Crisis Support', false);
    }
}
