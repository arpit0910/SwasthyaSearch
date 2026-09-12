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

    public function test_sitemap_xml_includes_all_core_pages_remedies_and_medicines(): void
    {
        $cat = \App\Models\HomeRemedyCategory::create([
            'name_en' => 'Digestion',
            'name_hi' => 'पाचन',
            'slug' => 'digestion',
            'is_active' => true,
        ]);

        $ing = \App\Models\HomeRemedyIngredient::create([
            'name_en' => 'Ginger',
            'name_hi' => 'अदरक',
            'slug' => 'ginger',
            'is_active' => true,
        ]);

        $remedy = \App\Models\HomeRemedy::create([
            'category_id' => $cat->id,
            'title_en' => 'Ginger Tea for Cold',
            'title_hi' => 'जुकाम के लिए अदरक चाय',
            'slug' => 'ginger-tea-cold',
            'short_description_en' => 'Traditional tea.',
            'short_description_hi' => 'पारंपरिक चाय।',
            'is_published' => true,
            'medical_review_status' => 'medical_reviewed',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee(route('home'), false);
        $response->assertSee(route('doctors.index'), false);
        $response->assertSee(route('hospitals.index'), false);
        $response->assertSee(route('blood_banks.index'), false);
        $response->assertSee(route('emergency'), false);
        $response->assertSee(route('nani-dadi.index'), false);
        $response->assertSee(route('nani-dadi.show', $remedy->slug), false);
        $response->assertSee(route('nani-dadi.category', $cat->slug), false);
        $response->assertSee(route('nani-dadi.ingredient', $ing->slug), false);
    }

    public function test_homepage_and_directory_contain_high_intent_search_keywords(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('Doctor near me', false);
        $response->assertSee('Blood bank near me', false);

        $doctorsResponse = $this->get(route('doctors.index'));
        $doctorsResponse->assertOk();
        $doctorsResponse->assertSee('Doctors Near Me', false);

        $bloodBanksResponse = $this->get(route('blood_banks.index'));
        $bloodBanksResponse->assertOk();
        $bloodBanksResponse->assertSee('Blood Banks Near Me', false);
    }
}
