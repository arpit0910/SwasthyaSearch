<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        \App\Models\Department::create(['name_en' => 'Dept 1', 'name_hi' => 'विभाग 1', 'is_active' => true]);
        \App\Models\Department::create(['name_en' => 'Dept 2', 'name_hi' => 'विभाग 2', 'is_active' => true]);
        \App\Models\Department::create(['name_en' => 'Dept 3', 'name_hi' => 'विभाग 3', 'is_active' => true]);
        \App\Models\Department::create(['name_en' => 'Dept 4', 'name_hi' => 'विभाग 4', 'is_active' => true]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
