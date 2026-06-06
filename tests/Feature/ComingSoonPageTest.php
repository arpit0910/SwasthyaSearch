<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ComingSoonPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->get('/test-open-page', fn () => response('open page'));
        Route::middleware('web')->get('/test-protected-page', fn () => response('protected page'));
    }

    public function test_non_live_environment_keeps_normal_routes_available(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $response = $this->get('/test-open-page');

        $response->assertOk();
        $response->assertSee('open page');
    }

    public function test_live_environment_shows_coming_soon_page(): void
    {
        $this->app->detectEnvironment(fn () => 'live');

        $response = $this->get('/coming-soon');

        $response->assertOk();
        $response->assertSee('Coming soon.', false);
    }

    public function test_live_environment_redirects_other_routes_to_coming_soon(): void
    {
        $this->app->detectEnvironment(fn () => 'live');

        $response = $this->get('/test-protected-page');

        $response->assertRedirect(route('coming-soon'));
    }
}
