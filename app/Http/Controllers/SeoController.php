<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BloodBank;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Medicine;
use App\Models\Quiz;
use App\Models\HomeRemedy;
use Symfony\Component\HttpFoundation\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $sitemapUrl = route('sitemap');

        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /admin/',
            'Disallow: /filament',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password',
            'Disallow: /api/',
            '',
            'Sitemap: ' . $sitemapUrl,
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $base = rtrim(config('app.url', url('/')), '/');
        $today = now()->toDateString();
        $lastDoctors = optional(Doctor::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastHospitals = optional(Hospital::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastBloodBanks = optional(BloodBank::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastArticles = optional(Article::query()->where('is_published', true)->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastQuizzes = optional(Quiz::query()->published()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastMedicines = optional(Medicine::query()->published()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;
        $lastRemedies = optional(HomeRemedy::query()->published()->latest('updated_at')->value('updated_at'))->toDateString() ?? $today;

        $staticUrls = [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0', 'lastmod' => $today],
            ['loc' => route('doctors.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastDoctors],
            ['loc' => route('hospitals.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastHospitals],
            ['loc' => route('blood_banks.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastBloodBanks],
            ['loc' => route('emergency'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $today],
            ['loc' => route('symptom-test'), 'changefreq' => 'weekly', 'priority' => '0.9', 'lastmod' => $today],
            ['loc' => route('nani-dadi.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastRemedies],
            ['loc' => route('articles.index'), 'changefreq' => 'daily', 'priority' => '0.8', 'lastmod' => $lastArticles],
            ['loc' => route('medicines.index'), 'changefreq' => 'daily', 'priority' => '0.8', 'lastmod' => $lastMedicines],
            ['loc' => route('departments.index'), 'changefreq' => 'weekly', 'priority' => '0.8', 'lastmod' => $today],
            ['loc' => route('diseases.index'), 'changefreq' => 'weekly', 'priority' => '0.8', 'lastmod' => $today],
            ['loc' => route('activities.index'), 'changefreq' => 'weekly', 'priority' => '0.7', 'lastmod' => $today],
            ['loc' => route('activities.breathing'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('activities.grounding'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('activities.mood-check'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('activities.calm-audio'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('activities.games.memory'), 'changefreq' => 'monthly', 'priority' => '0.5', 'lastmod' => $today],
            ['loc' => route('activities.games.calm-tap'), 'changefreq' => 'monthly', 'priority' => '0.5', 'lastmod' => $today],
            ['loc' => route('quizzes.index'), 'changefreq' => 'weekly', 'priority' => '0.7', 'lastmod' => $lastQuizzes],
            ['loc' => route('support.crisis'), 'changefreq' => 'monthly', 'priority' => '0.7', 'lastmod' => $today],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => $today],
            ['loc' => route('privacy.policy'), 'changefreq' => 'yearly', 'priority' => '0.3', 'lastmod' => $today],
            ['loc' => route('terms.service'), 'changefreq' => 'yearly', 'priority' => '0.3', 'lastmod' => $today],
        ];

        $articleUrls = Article::query()
            ->where('is_published', true)
            ->latest('updated_at')
            ->get(['id', 'updated_at'])
            ->map(fn (Article $article) => [
                'loc' => route('articles.show', $article->id),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'lastmod' => optional($article->updated_at)->toDateString() ?? $today,
            ])
            ->values()
            ->all();

        $medicineUrls = Medicine::query()
            ->published()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (Medicine $medicine) => [
                'loc' => route('medicines.show', $medicine->slug),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'lastmod' => optional($medicine->updated_at)->toDateString() ?? $today,
            ])
            ->values()
            ->all();

        $quizUrls = Quiz::query()
            ->published()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (Quiz $quiz) => [
                'loc' => route('quizzes.show', $quiz->slug),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'lastmod' => optional($quiz->updated_at)->toDateString() ?? $today,
            ])
            ->values()
            ->all();

        $hospitalDoctorUrls = Hospital::query()
            ->where('is_verified', true)
            ->whereHas('doctors', fn ($query) => $query->where('is_verified', true))
            ->latest('updated_at')
            ->get(['id', 'updated_at'])
            ->map(fn (Hospital $hospital) => [
                'loc' => route('hospitals.doctors', $hospital->id),
                'changefreq' => 'weekly',
                'priority' => '0.7',
                'lastmod' => optional($hospital->updated_at)->toDateString() ?? $today,
            ])
            ->values()
            ->all();

        $remedyUrls = HomeRemedy::query()
            ->published()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn ($r) => [
                'loc' => route('nani-dadi.show', $r->slug),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'lastmod' => optional($r->updated_at)->toDateString() ?? $today,
            ])
            ->all();

        $remedyCategoryUrls = \App\Models\HomeRemedyCategory::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn ($cat) => [
                'loc' => route('nani-dadi.category', $cat->slug),
                'changefreq' => 'weekly',
                'priority' => '0.7',
                'lastmod' => optional($cat->updated_at)->toDateString() ?? $today,
            ])
            ->all();

        $remedyIngredientUrls = \App\Models\HomeRemedyIngredient::query()
            ->where('is_active', true)
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn ($ing) => [
                'loc' => route('nani-dadi.ingredient', $ing->slug),
                'changefreq' => 'weekly',
                'priority' => '0.7',
                'lastmod' => optional($ing->updated_at)->toDateString() ?? $today,
            ])
            ->all();

        $urls = array_merge(
            $staticUrls,
            $articleUrls,
            $medicineUrls,
            $quizUrls,
            $hospitalDoctorUrls,
            $remedyUrls,
            $remedyCategoryUrls,
            $remedyIngredientUrls
        );

        $xml = view('sitemap', compact('urls', 'base'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function manifest(): Response
    {
        $appName = config('app.name', 'Arogio');

        return response()->json([
            'name' => $appName,
            'short_name' => $appName,
            'description' => 'Find verified doctors, hospitals, blood banks, and healthcare information.',
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => '#f8fafc',
            'theme_color' => '#0f766e',
            'icons' => [
                [
                    'src' => asset('img/fav-icon.png'),
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                [
                    'src' => asset('img/fav-icon.png'),
                    'sizes' => '32x32',
                    'type' => 'image/png',
                ],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json; charset=UTF-8'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
