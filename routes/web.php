<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SampleDownloadController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\HealthcareQueryController;
use App\Http\Controllers\Api\ReliableDirectoryController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

// --- CUSTOM ADMIN DASHBOARD ROUTES (Blade + Bootstrap 5) ---
Route::prefix('admin')->middleware('web')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/sample-download', [SampleDownloadController::class, 'download'])->name('sample.download');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Hospitals
        Route::get('/hospitals', [AdminDashboardController::class, 'hospitals'])->name('admin.hospitals');
        Route::get('/hospitals/create', [AdminDashboardController::class, 'createHospital'])->name('admin.hospitals.create');
        Route::get('/hospitals/{hospital}/edit', [AdminDashboardController::class, 'editHospital'])->name('admin.hospitals.edit');
        Route::post('/hospitals', [AdminDashboardController::class, 'storeHospital'])->name('admin.hospitals.store');
        Route::put('/hospitals/{hospital}', [AdminDashboardController::class, 'updateHospital'])->name('admin.hospitals.update');
        Route::delete('/hospitals/{hospital}', [AdminDashboardController::class, 'destroyHospital'])->name('admin.hospitals.destroy');
        Route::post('/hospitals/import', [AdminDashboardController::class, 'importHospitals'])->name('admin.hospitals.import');
        Route::get('/hospitals/export', [AdminDashboardController::class, 'exportHospitals'])->name('admin.hospitals.export');
        Route::post('/hospitals/sync', [AdminDashboardController::class, 'syncHospitals'])->name('admin.hospitals.sync');
        Route::get('/hospitals/sync-progress', [AdminDashboardController::class, 'syncHospitalsProgress'])->name('admin.hospitals.sync.progress');

        // Doctors
        Route::get('/doctors', [AdminDashboardController::class, 'doctors'])->name('admin.doctors');
        Route::get('/doctors/create', [AdminDashboardController::class, 'createDoctor'])->name('admin.doctors.create');
        Route::get('/doctors/{doctor}/edit', [AdminDashboardController::class, 'editDoctor'])->name('admin.doctors.edit');
        Route::post('/doctors', [AdminDashboardController::class, 'storeDoctor'])->name('admin.doctors.store');
        Route::put('/doctors/{doctor}', [AdminDashboardController::class, 'updateDoctor'])->name('admin.doctors.update');
        Route::delete('/doctors/{doctor}', [AdminDashboardController::class, 'destroyDoctor'])->name('admin.doctors.destroy');
        Route::post('/doctors/import', [AdminDashboardController::class, 'importDoctors'])->name('admin.doctors.import');
        Route::get('/doctors/export', [AdminDashboardController::class, 'exportDoctors'])->name('admin.doctors.export');
        Route::post('/doctors/sync', [AdminDashboardController::class, 'syncDoctors'])->name('admin.doctors.sync');
        Route::get('/doctors/sync-progress', [AdminDashboardController::class, 'syncDoctorsProgress'])->name('admin.doctors.sync.progress');

        // Blood Banks
        Route::get('/blood-banks', [AdminDashboardController::class, 'bloodBanks'])->name('admin.blood_banks');
        Route::get('/blood-banks/create', [AdminDashboardController::class, 'createBloodBank'])->name('admin.blood_banks.create');
        Route::get('/blood-banks/{bloodBank}/edit', [AdminDashboardController::class, 'editBloodBank'])->name('admin.blood_banks.edit');
        Route::post('/blood-banks', [AdminDashboardController::class, 'storeBloodBank'])->name('admin.blood_banks.store');
        Route::put('/blood-banks/{bloodBank}', [AdminDashboardController::class, 'updateBloodBank'])->name('admin.blood_banks.update');
        Route::delete('/blood-banks/{bloodBank}', [AdminDashboardController::class, 'destroyBloodBank'])->name('admin.blood_banks.destroy');
        Route::post('/blood-banks/import', [AdminDashboardController::class, 'importBloodBanks'])->name('admin.blood_banks.import');
        Route::get('/blood-banks/export', [AdminDashboardController::class, 'exportBloodBanks'])->name('admin.blood_banks.export');
        Route::post('/blood-banks/sync', [AdminDashboardController::class, 'syncBloodBanks'])->name('admin.blood_banks.sync');
        Route::get('/blood-banks/sync-progress', [AdminDashboardController::class, 'syncBloodBanksProgress'])->name('admin.blood_banks.sync.progress');

        // Full Directory Sync (Hospitals + Doctors + Blood Banks)
        Route::post('/sync-all', [AdminDashboardController::class, 'syncDirectoryAll'])->name('admin.directory.sync_all');
        Route::get('/sync-all-progress', [AdminDashboardController::class, 'syncDirectoryAllProgress'])->name('admin.directory.sync_all.progress');

        // Departments
        Route::get('/departments', [AdminDashboardController::class, 'departments'])->name('admin.departments');
        Route::get('/departments/create', [AdminDashboardController::class, 'createDepartment'])->name('admin.departments.create');
        Route::get('/departments/{department}/edit', [AdminDashboardController::class, 'editDepartment'])->name('admin.departments.edit');
        Route::post('/departments', [AdminDashboardController::class, 'storeDepartment'])->name('admin.departments.store');
        Route::put('/departments/{department}', [AdminDashboardController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/departments/{department}', [AdminDashboardController::class, 'destroyDepartment'])->name('admin.departments.destroy');
        Route::post('/departments/import', [AdminDashboardController::class, 'importDepartments'])->name('admin.departments.import');

        // Diseases
        Route::get('/diseases', [AdminDashboardController::class, 'diseases'])->name('admin.diseases');
        Route::get('/diseases/create', [AdminDashboardController::class, 'createDisease'])->name('admin.diseases.create');
        Route::get('/diseases/{disease}/edit', [AdminDashboardController::class, 'editDisease'])->name('admin.diseases.edit');
        Route::post('/diseases', [AdminDashboardController::class, 'storeDisease'])->name('admin.diseases.store');
        Route::put('/diseases/{disease}', [AdminDashboardController::class, 'updateDisease'])->name('admin.diseases.update');
        Route::delete('/diseases/{disease}', [AdminDashboardController::class, 'destroyDisease'])->name('admin.diseases.destroy');
        Route::post('/diseases/import', [AdminDashboardController::class, 'importDiseases'])->name('admin.diseases.import');
        Route::get('/diseases/export', [AdminDashboardController::class, 'exportDiseases'])->name('admin.diseases.export');

        // Articles
        Route::get('/articles', [AdminDashboardController::class, 'articles'])->name('admin.articles');
        Route::get('/articles/create', [AdminDashboardController::class, 'createArticle'])->name('admin.articles.create');
        Route::get('/articles/{article}/edit', [AdminDashboardController::class, 'editArticle'])->name('admin.articles.edit');
        Route::post('/articles', [AdminDashboardController::class, 'storeArticle'])->name('admin.articles.store');
        Route::put('/articles/{article}', [AdminDashboardController::class, 'updateArticle'])->name('admin.articles.update');
        Route::delete('/articles/{article}', [AdminDashboardController::class, 'destroyArticle'])->name('admin.articles.destroy');

        // FAQs
        Route::get('/faqs', [AdminDashboardController::class, 'faqs'])->name('admin.faqs');
        Route::get('/faqs/create', [AdminDashboardController::class, 'createFaq'])->name('admin.faqs.create');
        Route::get('/faqs/{faq}/edit', [AdminDashboardController::class, 'editFaq'])->name('admin.faqs.edit');
        Route::post('/faqs', [AdminDashboardController::class, 'storeFaq'])->name('admin.faqs.store');
        Route::put('/faqs/{faq}', [AdminDashboardController::class, 'updateFaq'])->name('admin.faqs.update');
        Route::delete('/faqs/{faq}', [AdminDashboardController::class, 'destroyFaq'])->name('admin.faqs.destroy');

        // General Medical Q&A
        Route::get('/general-qa', [AdminDashboardController::class, 'generalQa'])->name('admin.general_qa');
        Route::get('/general-qa/create', [AdminDashboardController::class, 'createGeneralQa'])->name('admin.general_qa.create');
        Route::get('/general-qa/{faq}/edit', [AdminDashboardController::class, 'editGeneralQa'])->name('admin.general_qa.edit');
        Route::post('/general-qa', [AdminDashboardController::class, 'storeGeneralQa'])->name('admin.general_qa.store');
        Route::put('/general-qa/{faq}', [AdminDashboardController::class, 'updateGeneralQa'])->name('admin.general_qa.update');
        Route::delete('/general-qa/{faq}', [AdminDashboardController::class, 'destroyGeneralQa'])->name('admin.general_qa.destroy');

        // Cached Medical Questions
        Route::get('/cached-medical-questions', [AdminDashboardController::class, 'cachedMedicalQuestions'])->name('admin.cached_medical_questions');
        Route::get('/cached-medical-questions/create', [AdminDashboardController::class, 'createCachedMedicalQuestion'])->name('admin.cached_medical_questions.create');
        Route::get('/cached-medical-questions/{cachedMedicalQuestion}/edit', [AdminDashboardController::class, 'editCachedMedicalQuestion'])->name('admin.cached_medical_questions.edit');
        Route::post('/cached-medical-questions', [AdminDashboardController::class, 'storeCachedMedicalQuestion'])->name('admin.cached_medical_questions.store');
        Route::put('/cached-medical-questions/{cachedMedicalQuestion}', [AdminDashboardController::class, 'updateCachedMedicalQuestion'])->name('admin.cached_medical_questions.update');
        Route::delete('/cached-medical-questions/{cachedMedicalQuestion}', [AdminDashboardController::class, 'destroyCachedMedicalQuestion'])->name('admin.cached_medical_questions.destroy');
    });
});

// Public Omni-Search & Chatbot Routes
Route::get('/', [SearchController::class, 'index'])->name('home');
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');
Route::get('/api/doctors/nearest', [HealthcareQueryController::class, 'nearestDoctors'])->name('api.doctors.nearest');
Route::get('/api/directory', [HealthcareQueryController::class, 'cityDirectory'])->name('api.directory.city');
Route::get('/api/doctors', [ReliableDirectoryController::class, 'doctors'])->name('api.doctors.city');
Route::get('/api/hospitals', [ReliableDirectoryController::class, 'hospitals'])->name('api.hospitals.city');
Route::get('/api/blood-banks', [ReliableDirectoryController::class, 'bloodBanks'])->name('api.blood_banks.city');
Route::post('/switch-locale', [SearchController::class, 'switchLocale'])->name('switch.locale');

Route::get('/sitemap.xml', function () {
    $base = rtrim(config('app.url', url('/')), '/');
    $lastDoctors = optional(\App\Models\Doctor::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? now()->toDateString();
    $lastHospitals = optional(\App\Models\Hospital::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? now()->toDateString();
    $lastBloodBanks = optional(\App\Models\BloodBank::query()->latest('updated_at')->value('updated_at'))->toDateString() ?? now()->toDateString();
    $lastArticles = optional(\App\Models\Article::query()->where('is_published', true)->latest('updated_at')->value('updated_at'))->toDateString() ?? now()->toDateString();

    $staticUrls = [
        ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0', 'lastmod' => now()->toDateString()],
        ['loc' => route('doctors.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastDoctors],
        ['loc' => route('hospitals.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastHospitals],
        ['loc' => route('blood_banks.index'), 'changefreq' => 'daily', 'priority' => '0.9', 'lastmod' => $lastBloodBanks],
        ['loc' => route('articles.index'), 'changefreq' => 'daily', 'priority' => '0.8', 'lastmod' => $lastArticles],
        ['loc' => route('departments.index'), 'changefreq' => 'weekly', 'priority' => '0.6', 'lastmod' => now()->toDateString()],
        ['loc' => route('diseases.index'), 'changefreq' => 'weekly', 'priority' => '0.6', 'lastmod' => now()->toDateString()],
        ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => now()->toDateString()],
        ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.6', 'lastmod' => now()->toDateString()],
        ['loc' => route('privacy.policy'), 'changefreq' => 'yearly', 'priority' => '0.3', 'lastmod' => now()->toDateString()],
        ['loc' => route('terms.service'), 'changefreq' => 'yearly', 'priority' => '0.3', 'lastmod' => now()->toDateString()],
    ];

    $articleUrls = \App\Models\Article::query()
        ->where('is_published', true)
        ->latest('updated_at')
        ->get(['id', 'updated_at'])
        ->map(function ($article) {
            return [
                'loc' => route('articles.show', $article->id),
                'changefreq' => 'weekly',
                'priority' => '0.7',
                'lastmod' => optional($article->updated_at)->toDateString() ?? now()->toDateString(),
            ];
        })->values()->all();

    $urls = array_merge($staticUrls, $articleUrls);

    $xml = view('sitemap.xml', compact('urls', 'base'))->render();
    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

// Doctors & Hospitals Directory Routes
Route::match(['get', 'post'], '/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::match(['get', 'post'], '/hospitals', [HospitalController::class, 'index'])->name('hospitals.index');
Route::get('/hospitals/{hospital}/doctors', [HospitalController::class, 'doctors'])->name('hospitals.doctors');
Route::match(['get', 'post'], '/blood-banks', [BloodBankController::class, 'index'])->name('blood_banks.index');

// Articles Routes
Route::match(['get', 'post'], '/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Static & Contact Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/departments', [PageController::class, 'departments'])->name('departments.index');
Route::get('/diseases', [PageController::class, 'diseases'])->name('diseases.index');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::post('/feedback', [PageController::class, 'submitFeedback'])->name('feedback.submit');
Route::post('/lead-capture', [PageController::class, 'submitLeadCapture'])->name('lead.capture.submit');
Route::post('/listing-report', [PageController::class, 'submitListingReport'])->name('listing.report.submit');
Route::post('/listing-vote', [PageController::class, 'submitListingVote'])->name('listing.vote.submit');

Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-service', [LegalController::class, 'termsOfService'])->name('terms.service');

Route::post('/api/chatbot', [ChatbotController::class, 'handleMessage'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('api.chatbot');

Route::post('/api/articles/{article}/comments', [ArticleCommentController::class, 'store'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('api.articles.comments.store');
