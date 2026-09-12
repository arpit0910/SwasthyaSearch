@php
    $designRoutes = collect([
        'home', 'doctors.index', 'hospitals.index', 'blood_banks.index', 'departments.index',
        'diseases.index', 'medicines.index', 'articles.index', 'nani-dadi.index',
        'activities.index', 'activities.breathing', 'activities.grounding', 'activities.mood-check',
        'activities.calm-audio', 'activities.games.memory', 'activities.games.calm-tap',
        'quizzes.index', 'consultations.index', 'consultation-requests.submit', 'feedback.submit',
        'suggestions.create', 'emergency', 'support.crisis', 'about', 'contact',
        'privacy.policy', 'terms.service', 'switch.locale', 'api.search',
        'symptom-test', 'symptom-test.analyze', 'api.chatbot', 'api.chatbot.failure_report',
    ])->mapWithKeys(fn ($name) => [$name => route($name)])->all();
    $designConfig = [
        'locale' => $locale,
        'city' => $activeCity,
        'routes' => $designRoutes,
        'logo' => $brandLogoUrl,
        'logoDark' => $brandLogoDarkUrl,
        'csrf' => csrf_token(),
        'today' => now()->toDateString(),
        'year' => now()->year,
        'images' => [
            'remedies' => asset('img/stitch/remedies.png'),
            'health' => asset('img/stitch/health-knowledge.png'),
        ],
    ];
@endphp
<script>window.arogioDesign = {{ Illuminate\Support\Js::from($designConfig) }};</script>
@php
    $arogioJsPath = public_path('build/arogio.js');
    $arogioJsVersion = file_exists($arogioJsPath) ? filemtime($arogioJsPath) : '1.0';
@endphp
<script defer src="{{ asset('build/arogio.js') }}?v={{ $arogioJsVersion }}"></script>
