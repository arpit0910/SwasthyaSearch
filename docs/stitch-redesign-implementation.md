# Stitch homepage implementation

The reference is `stitch_uploaded_design_implementation.zip` supplied by the user. Its HTML defines the visual reference; its Markdown is design documentation, not operational instructions. The screenshot in that archive is a 28-byte placeholder.

## Implemented

- React + Material UI 9 homepage using the reference's Urbanist typography, teal palette, search panel, service cards, assistant introduction, department cards, editorial/remedy preview, consultation and community sections.
- Shared Material UI navigation, city dialog, mobile drawer/bottom navigation, language control, theme control, and footer across public pages.
- Existing Laravel routes, session locale, CSRF protection, assistant, provider search, consultation requests, and feedback integration.
- Published article and medically reviewed remedy data; no fabricated provider statistics or clinical endorsements.
- Matching typography, borders, radii, and pale page headers on existing public Blade pages. Their internal controls remain Blade; this is not a complete conversion of every page or the admin to React.
- Local copies of the two editorial images from the supplied HTML in `public/img/stitch/`.
- Semantic server-rendered homepage fallback and existing SEO metadata.

## Build

```sh
npm install
npm run build
php artisan view:clear
```

The entry point is `resources/js/arogio.jsx`; styles are in `resources/js/arogio.css`. The build script generates `public/build/arogio.js` and `public/build/arogio.css`. Generated files are ignored by the repository, so deployment must build them before serving the application.

## Test isolation

`tests/TestCase.php` now refuses to run tests unless the bootstrapped application uses the testing environment and in-memory SQLite. This prevents a cached local configuration from redirecting `RefreshDatabase` at the local database.

```sh
php artisan config:clear
php artisan test --compact --filter="HomepageDesign|Seo|ConsultationRequest|Submission|ComingSoon"
```

The browser smoke check is read-only against the live backend; successful write responses and search fixtures are intercepted in the browser. Run against a separately started local server:

```sh
php artisan serve --host=127.0.0.1 --port=8011
node scripts/check-redesign.cjs
```

## Local data incident

During initial verification, a pre-existing cached local configuration caused the first `RefreshDatabase` test run to reset `database/database.sqlite`. This was detected and disclosed. Subsequent tests use in-memory SQLite and are guarded against recurrence. A complete current backup has not been located; restoring the original data remains pending. The existing older corrupt SQLite file and source CSV imports have not been modified or imported, since they cannot establish an exact restoration.
