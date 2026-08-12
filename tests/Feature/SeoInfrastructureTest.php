<?php

namespace Tests\Feature;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_txt_exposes_dynamic_sitemap_and_disallows_private_sections(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Disallow: /admin', false);
        $response->assertSee(route('sitemap'), false);
    }

    public function test_manifest_uses_current_brand_name(): void
    {
        $response = $this->get('/site.webmanifest');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/manifest+json; charset=UTF-8');
        $response->assertJsonPath('name', config('app.name'));
        $response->assertJsonPath('short_name', config('app.name'));
    }

    public function test_homepage_links_manifest_and_robots_route_is_indexable(): void
    {
        Department::create([
            'name_en' => 'General Medicine',
            'name_hi' => 'जनरल मेडिसिन',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('rel="manifest"', false)
            ->assertSee('/site.webmanifest', false)
            ->assertSee('content="index,follow', false)
            ->assertSee('name="keywords"', false)
            ->assertSee('property="og:image:secure_url"', false)
            ->assertSee('property="og:image:type"', false)
            ->assertSee('hreflang="x-default"', false);
    }

    public function test_article_page_exposes_article_specific_meta_tags(): void
    {
        Department::create([
            'name_en' => 'General Medicine',
            'name_hi' => 'à¤œà¤¨à¤°à¤² à¤®à¥‡à¤¡à¤¿à¤¸à¤¿à¤¨',
            'is_active' => true,
        ]);

        $article = \App\Models\Article::create([
            'title_en' => 'Healthy Sleep Basics',
            'title_hi' => 'à¤¬à¥‡à¤¹à¤¤à¤° à¤¨à¥€à¤‚à¤¦ à¤•à¥€ à¤¬à¥à¤¨à¤¿à¤¯à¤¾à¤¦',
            'excerpt_en' => 'Simple sleep basics.',
            'excerpt_hi' => 'à¤¬à¥‡à¤¹à¤¤à¤° à¤¨à¥€à¤‚à¤¦ à¤•à¥‡ à¤†à¤¸à¤¾à¤¨ à¤¤à¤°à¥€à¤•à¥‡à¥¤',
            'content_en' => 'Sleep well.',
            'content_hi' => 'à¤…à¤šà¥à¤›à¥€ à¤¨à¥€à¤‚à¤¦ à¤²à¥‡à¤‚à¥¤',
            'category' => 'Wellness',
            'author_name' => 'Arogio Team',
            'is_published' => true,
        ]);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('property="og:type" content="article"', false)
            ->assertSee('property="article:published_time"', false)
            ->assertSee('property="article:modified_time"', false)
            ->assertSee('property="article:author"', false)
            ->assertSee('name="keywords"', false);
    }
}
