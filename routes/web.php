<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ArticleCommentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SampleDownloadController;
use App\Http\Controllers\SearchController;
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
        Route::post('/hospitals', [AdminDashboardController::class, 'storeHospital'])->name('admin.hospitals.store');
        Route::put('/hospitals/{hospital}', [AdminDashboardController::class, 'updateHospital'])->name('admin.hospitals.update');
        Route::delete('/hospitals/{hospital}', [AdminDashboardController::class, 'destroyHospital'])->name('admin.hospitals.destroy');
        Route::post('/hospitals/import', [AdminDashboardController::class, 'importHospitals'])->name('admin.hospitals.import');
        Route::post('/hospitals/sync', [AdminDashboardController::class, 'syncHospitals'])->name('admin.hospitals.sync');
        Route::get('/hospitals/sync-progress', [AdminDashboardController::class, 'syncHospitalsProgress'])->name('admin.hospitals.sync.progress');

        // Doctors
        Route::get('/doctors', [AdminDashboardController::class, 'doctors'])->name('admin.doctors');
        Route::post('/doctors', [AdminDashboardController::class, 'storeDoctor'])->name('admin.doctors.store');
        Route::put('/doctors/{doctor}', [AdminDashboardController::class, 'updateDoctor'])->name('admin.doctors.update');
        Route::delete('/doctors/{doctor}', [AdminDashboardController::class, 'destroyDoctor'])->name('admin.doctors.destroy');
        Route::post('/doctors/import', [AdminDashboardController::class, 'importDoctors'])->name('admin.doctors.import');
        Route::post('/doctors/sync', [AdminDashboardController::class, 'syncDoctors'])->name('admin.doctors.sync');
        Route::get('/doctors/sync-progress', [AdminDashboardController::class, 'syncDoctorsProgress'])->name('admin.doctors.sync.progress');

        // Departments
        Route::get('/departments', [AdminDashboardController::class, 'departments'])->name('admin.departments');
        Route::post('/departments', [AdminDashboardController::class, 'storeDepartment'])->name('admin.departments.store');
        Route::put('/departments/{department}', [AdminDashboardController::class, 'updateDepartment'])->name('admin.departments.update');
        Route::delete('/departments/{department}', [AdminDashboardController::class, 'destroyDepartment'])->name('admin.departments.destroy');
        Route::post('/departments/import', [AdminDashboardController::class, 'importDepartments'])->name('admin.departments.import');

        // Diseases
        Route::get('/diseases', [AdminDashboardController::class, 'diseases'])->name('admin.diseases');
        Route::post('/diseases', [AdminDashboardController::class, 'storeDisease'])->name('admin.diseases.store');
        Route::put('/diseases/{disease}', [AdminDashboardController::class, 'updateDisease'])->name('admin.diseases.update');
        Route::delete('/diseases/{disease}', [AdminDashboardController::class, 'destroyDisease'])->name('admin.diseases.destroy');
        Route::post('/diseases/import', [AdminDashboardController::class, 'importDiseases'])->name('admin.diseases.import');

        // Articles
        Route::get('/articles', [AdminDashboardController::class, 'articles'])->name('admin.articles');
        Route::post('/articles', [AdminDashboardController::class, 'storeArticle'])->name('admin.articles.store');
        Route::put('/articles/{article}', [AdminDashboardController::class, 'updateArticle'])->name('admin.articles.update');
        Route::delete('/articles/{article}', [AdminDashboardController::class, 'destroyArticle'])->name('admin.articles.destroy');

        // FAQs
        Route::get('/faqs', [AdminDashboardController::class, 'faqs'])->name('admin.faqs');
        Route::post('/faqs', [AdminDashboardController::class, 'storeFaq'])->name('admin.faqs.store');
        Route::put('/faqs/{faq}', [AdminDashboardController::class, 'updateFaq'])->name('admin.faqs.update');
        Route::delete('/faqs/{faq}', [AdminDashboardController::class, 'destroyFaq'])->name('admin.faqs.destroy');
    });
});

// Public Omni-Search & Chatbot Routes
Route::get('/', [SearchController::class, 'index'])->name('home');
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');
Route::post('/switch-locale', [SearchController::class, 'switchLocale'])->name('switch.locale');

// Doctors & Hospitals Directory Routes
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals.index');

// Articles Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Static & Contact Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/departments', [PageController::class, 'departments'])->name('departments.index');
Route::get('/diseases', [PageController::class, 'diseases'])->name('diseases.index');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-of-service', [LegalController::class, 'termsOfService'])->name('terms.service');

Route::post('/api/chatbot', [ChatbotController::class, 'handleMessage'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('api.chatbot');

Route::post('/api/articles/{article}/comments', [ArticleCommentController::class, 'store'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('api.articles.comments.store');
