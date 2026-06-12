<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ComingSoonPageTest extends TestCase
{
    use RefreshDatabase;

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
        $response->assertSee('Coming Soon', false);
    }

    public function test_live_environment_redirects_other_routes_to_coming_soon(): void
    {
        $this->app->detectEnvironment(fn () => 'live');

        $response = $this->get('/test-protected-page');

        $response->assertRedirect(route('coming-soon'));
    }

    public function test_live_environment_allows_admin_login_route(): void
    {
        $this->app->detectEnvironment(fn () => 'live');

        $response = $this->get('/admin/login');

        $response->assertOk();
    }

    public function test_live_environment_allows_logged_in_admin_to_access_website(): void
    {
        $this->app->detectEnvironment(fn () => 'live');

        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/test-protected-page');

        $response->assertOk();
        $response->assertSee('protected page');
    }
}

