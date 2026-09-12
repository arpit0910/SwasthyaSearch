<?php

namespace Tests\Feature;

use App\Models\HomeRemedy;
use App\Models\HomeRemedyCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageDesignFeatureTest extends TestCase
{
    use RefreshDatabase;

    private int $categoryId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryId = HomeRemedyCategory::create([
            'name_en' => 'Wellness', 'name_hi' => 'वेलनेस', 'slug' => 'wellness',
        ])->id;
    }

    public function test_homepage_only_exposes_published_reviewed_remedies(): void
    {
        HomeRemedy::create([
            'category_id' => $this->categoryId,
            'title_en' => 'Private unreviewed draft',
            'slug' => 'private-unreviewed',
            'is_published' => true,
            'medical_review_status' => 'medical_review_pending',
        ]);
        HomeRemedy::create([
            'category_id' => $this->categoryId,
            'title_en' => 'Reviewed public remedy',
            'slug' => 'reviewed-public',
            'is_published' => true,
            'medical_review_status' => 'medical_reviewed',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Reviewed public remedy', false)
            ->assertDontSee('Private unreviewed draft', false)
            ->assertSee('arogio-home', false);
    }

    public function test_homepage_data_cannot_break_out_of_the_configuration_script(): void
    {
        HomeRemedy::create([
            'category_id' => $this->categoryId,
            'title_en' => '</script><script>alert("unsafe")</script>',
            'slug' => 'escaped-title',
            'is_published' => true,
            'medical_review_status' => 'medical_reviewed',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('</script><script>alert("unsafe")</script>', false)
            ->assertSee('window.arogioHome', false);
    }
}
