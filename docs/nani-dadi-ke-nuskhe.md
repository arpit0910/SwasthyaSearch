# Nani Dadi Ke Nuskhe

Run `php artisan migrate` and seed the masters with `php artisan db:seed --class=HomeRemedyCategorySeeder` and `php artisan db:seed --class=HomeRemedyIngredientSeeder`.

The Filament admin exposes **Nani Dadi Ke Nuskhe**, **Remedy Categories**, and **Remedy Ingredients** under Content Management. Import and export are available from the remedy list. Import accepts CSV/XLSX, uses `id` then `slug` for updates, and supports `create_only`, `create_update`, and `update_only`. The template/download columns are defined by `HomeRemedyExportService::columns()`; related category and ingredient values use their English name/slug and pipe-separated ingredient names.

Public routes: `/nani-dadi-ke-nuskhe`, `/nani-dadi-ke-nuskhe/{slug}`, `/nani-dadi-ke-nuskhe/category/{slug}`, and `/nani-dadi-ke-nuskhe/ingredient/{slug}`.

API routes: `GET /api/home-remedies?lang=en|hi&search=&category=`, `GET /api/home-remedies/{slug}`, `GET /api/home-remedy-categories`, and `GET /api/home-remedy-ingredients`. Unpublished or non-medically-reviewed remedies are never returned publicly.

Evidence values are `traditional`, `limited`, `supportive`, `moderate`, `strong`, and `not_recommended`. Review statuses are `draft`, `content_reviewed`, `medical_review_pending`, `medical_reviewed`, `published`, and `rejected`. Published imports require core safety content. No new dependency or environment setting is required.
