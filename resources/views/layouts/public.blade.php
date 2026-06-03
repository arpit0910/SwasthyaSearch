<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        (function() {
            function getStoredTheme() {
                try {
                    return localStorage.getItem('theme');
                } catch (error) {
                    return null;
                }
            }

            window.applyThemeMode = function applyThemeMode(theme) {
                const resolvedTheme = theme === 'dark' || theme === 'light'
                    ? theme
                    : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

                document.documentElement.classList.toggle('dark', resolvedTheme === 'dark');
                document.documentElement.setAttribute('data-theme', resolvedTheme);
                return resolvedTheme;
            };

            window.persistThemeMode = function persistThemeMode(theme) {
                try {
                    localStorage.setItem('theme', theme);
                } catch (error) {}
            };

            window.toggleThemeMode = function toggleThemeMode() {
                const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                window.applyThemeMode(nextTheme);
                window.persistThemeMode(nextTheme);
                if (typeof window.updateThemeToggleUI === 'function') {
                    window.updateThemeToggleUI();
                }
            };

            const storedTheme = getStoredTheme();
            window.applyThemeMode(storedTheme);
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $appName = config('app.name', 'Arogio');
    $siteUrl = rtrim(config('app.url', url('/')), '/');
    $currentUrl = url()->current();
    $hasQuery = request()->getQueryString() !== null;
    $locale = session('locale', app()->getLocale());
    $isHindi = $locale === 'hi';
    $activeCity = config('healthcare.active_city', 'Jaipur');
    $activeCityHi = config('healthcare.active_city_hi', 'जयपुर');

    $defaultTitle = $isHindi
    ? 'Arogio - डॉक्टर, अस्पताल और ब्लड बैंक खोजें'
    : 'Arogio - Find Doctors, Hospitals, and Blood Banks in Jaipur';
    $defaultDescription = $isHindi
    ? 'arogio पर अपने शहर में सत्यापित डॉक्टर, अस्पताल, क्लिनिक और ब्लड बैंक खोजें।'
    : 'Find verified doctors, hospitals, clinics, blood banks, and health articles in Jaipur on Arogio.';

    $routeName = request()->route()?->getName() ?? '';
    $routeSeo = [
    'home' => [
    'title' => $isHindi ? 'Arogio - अपने पास विश्वसनीय स्वास्थ्य सेवा खोजें' : 'Arogio - Trusted Healthcare Discovery Near You',
    'description' => $isHindi ? 'लक्षण, विभाग, शहर या नाम से डॉक्टर, अस्पताल, क्लिनिक और ब्लड बैंक खोजें।' : 'Search doctors, hospitals, blood banks, and departments by symptom, city, or keyword.',
    ],
    'doctors.index' => [
    'title' => $isHindi ? 'डॉक्टर निर्देशिका | Arogio' : 'Doctors Directory | Arogio',
    'description' => $isHindi ? 'अपने शहर में सत्यापित विशेषज्ञ डॉक्टर खोजें।' : 'Browse verified specialist doctors by city, department, and experience.',
    ],
    'hospitals.index' => [
    'title' => $isHindi ? 'अस्पताल और क्लिनिक निर्देशिका | Arogio' : 'Hospitals & Clinics Directory | Arogio',
    'description' => $isHindi ? 'अपने शहर के अस्पताल और क्लिनिक खोजें।' : 'Find verified hospitals and clinics with location and contact details.',
    ],
    'blood_banks.index' => [
    'title' => $isHindi ? 'ब्लड बैंक निर्देशिका | Arogio' : 'Blood Banks Directory | Arogio',
    'description' => $isHindi ? 'अपने शहर में ब्लड बैंक खोजें और उपलब्धता फोन पर पुष्टि करें।' : 'Find blood banks by city and blood group. Call to confirm current availability.',
    ],
    'articles.index' => [
    'title' => $isHindi ? 'स्वास्थ्य लेख | Arogio' : 'Health Articles | Arogio',
    'description' => $isHindi ? 'स्वास्थ्य, पोषण और वेलनेस पर उपयोगी लेख पढ़ें।' : 'Read useful health, wellness, and medical awareness articles.',
    ],
    'medicines.index' => [
    'title' => $isHindi ? 'दवा जानकारी | Arogio' : 'Medicine Information | Arogio',
    'description' => $isHindi ? 'दवाओं के उपयोग, दुष्प्रभाव, सावधानियां और चेतावनियों की सामान्य जानकारी खोजें।' : 'Search medicine uses, side effects, precautions, and warnings.',
    ],
    'medicines.show' => [
    'title' => $isHindi ? 'दवा विवरण | Arogio' : 'Medicine Details | Arogio',
    'description' => $isHindi ? 'चयनित दवा की सामान्य जानकारी और सुरक्षा सलाह देखें।' : 'View general medicine information and safety guidance.',
    ],
    'activities.index' => [
    'title' => $isHindi ? 'वेलनेस गतिविधियां | Arogio' : 'Wellness Activities | Arogio',
    'description' => $isHindi ? 'तनाव राहत, ग्राउंडिंग और मूड चेक-इन गतिविधियां उपयोग करें।' : 'Use stress-relief, grounding, and mood check-in activities.',
    ],
    'activities.breathing' => [
    'title' => $isHindi ? 'श्वास अभ्यास | Arogio' : 'Breathing Exercise | Arogio',
    'description' => $isHindi ? 'धीमी श्वास के शांत अभ्यास का उपयोग करें।' : 'Use a calm guided breathing exercise.',
    ],
    'activities.grounding' => [
    'title' => $isHindi ? 'ग्राउंडिंग अभ्यास | Arogio' : 'Grounding Exercise | Arogio',
    'description' => $isHindi ? '5-4-3-2-1 तकनीक से वर्तमान में लौटें।' : 'Use the 5-4-3-2-1 technique to return to the present moment.',
    ],
    'activities.mood-check' => [
    'title' => $isHindi ? 'मूड चेक-इन | Arogio' : 'Mood Check-in | Arogio',
    'description' => $isHindi ? 'अपनी भावना पहचानें और जरूरत पर सहायता देखें।' : 'Check how you feel and see support if needed.',
    ],
    'quizzes.index' => [
    'title' => $isHindi ? 'हेल्थ क्विज़ | Arogio' : 'Health Quizzes | Arogio',
    'description' => $isHindi ? 'सामान्य जागरूकता और आत्म-चिंतन के लिए क्विज़ लें।' : 'Take quizzes for awareness and self-reflection.',
    ],
    'quizzes.show' => [
    'title' => $isHindi ? 'क्विज़ विवरण | Arogio' : 'Quiz Details | Arogio',
    'description' => $isHindi ? 'सामान्य जागरूकता क्विज़ पूरा करें।' : 'Complete a general awareness quiz.',
    ],
    'support.crisis' => [
    'title' => $isHindi ? 'संकट सहायता | Arogio' : 'Crisis Support | Arogio',
    'description' => $isHindi ? 'असुरक्षित महसूस होने पर जयपुर में तुरंत सहायता विकल्प देखें।' : 'See immediate support options in Jaipur if you feel unsafe.',
    ],
    'about' => [
    'title' => $isHindi ? 'हमारे बारे में | Arogio' : 'About Us | Arogio',
    'description' => $isHindi ? 'arogio का मिशन भरोसेमंद हेल्थकेयर खोज को सरल बनाना है।' : 'Learn about Arogio and our mission for transparent healthcare discovery.',
    ],
    'contact' => [
    'title' => $isHindi ? 'संपर्क करें | Arogio' : 'Contact Us | Arogio',
    'description' => $isHindi ? 'सहायता और प्रतिक्रिया के लिए arogio से संपर्क करें।' : 'Contact Arogio for support, corrections, and feedback.',
    ],
    ];
    $computedTitle = $routeSeo[$routeName]['title'] ?? $defaultTitle;
    $computedDescription = $routeSeo[$routeName]['description'] ?? $defaultDescription;
    $metaTitle = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $computedTitle)));
    $metaDescription = trim($__env->yieldContent('meta_description', $computedDescription));
    $defaultCanonical = $currentUrl;
    $filterableRoutes = ['doctors.index', 'hospitals.index', 'blood_banks.index', 'articles.index'];
    $isFilterRoute = in_array($routeName, $filterableRoutes, true);
    $canonicalUrl = trim($__env->yieldContent('canonical_url', ($isFilterRoute && $hasQuery) ? route($routeName) : $defaultCanonical));
    $defaultRobots = ($isFilterRoute && $hasQuery)
    ? 'noindex,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'
    : 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
    $metaRobots = trim($__env->yieldContent('meta_robots', $defaultRobots));
    $metaKeywords = trim($__env->yieldContent('meta_keywords', 'Jaipur doctors, Jaipur hospitals, Jaipur blood banks, Jaipur healthcare, medical specialists'));
    $brandLogoUrl = asset('img/arogio-logo.png');
    $brandLogoDarkUrl = asset('img/arogio-logo-dark.png');
    $brandFaviconUrl = asset('img/fav-icon.png');
    $ogImage = trim($__env->yieldContent('og_image', $brandLogoUrl));
    $ogType = trim($__env->yieldContent('og_type', request()->routeIs('articles.show') ? 'article' : 'website'));
    @endphp

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="{{ $isHindi ? 'hi_IN' : 'en_US' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $brandFaviconUrl }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $brandFaviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $brandFaviconUrl }}">

    <link rel="alternate" hreflang="en" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="hi" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

    <script type="application/ld+json">
        {
            !!json_encode([
                '@'.
                'context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $appName,
                'url' => $siteUrl,
                'logo' => $brandLogoUrl,
                'sameAs' => [],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!
        }
    </script>
    <script type="application/ld+json">
        {
            !!json_encode([
                '@'.
                'context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $appName,
                'url' => $siteUrl,
                'inLanguage' => [$isHindi ? 'hi-IN' : 'en-IN', $isHindi ? 'en-IN' : 'hi-IN'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $siteUrl.
                    '/doctors?search={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!
        }
    </script>
    <script type="application/ld+json">
        {
            !!json_encode([
                '@'.
                'context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $metaTitle,
                'description' => $metaDescription,
                'url' => $canonicalUrl,
                'inLanguage' => $isHindi ? 'hi-IN' : 'en-IN',
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => $appName,
                    'url' => $siteUrl,
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!
        }
    </script>
    @php
    $breadcrumbItems = [
    ['name' => $isHindi ? 'होम' : 'Home', 'url' => route('home')],
    ];
    $routeCrumbs = [
    'doctors.index' => $isHindi ? 'डॉक्टर' : 'Doctors',
    'hospitals.index' => $isHindi ? 'अस्पताल' : 'Hospitals',
    'blood_banks.index' => $isHindi ? 'ब्लड बैंक' : 'Blood Banks',
    'articles.index' => $isHindi ? 'लेख' : 'Articles',
    'articles.show' => $isHindi ? 'लेख विवरण' : 'Article',
    'medicines.index' => $isHindi ? 'दवाएं' : 'Medicines',
    'medicines.show' => $isHindi ? 'दवा विवरण' : 'Medicine Details',
    'activities.index' => $isHindi ? 'गतिविधियां' : 'Activities',
    'activities.breathing' => $isHindi ? 'श्वास अभ्यास' : 'Breathing Exercise',
    'activities.grounding' => $isHindi ? 'ग्राउंडिंग अभ्यास' : 'Grounding Exercise',
    'activities.mood-check' => $isHindi ? 'मूड चेक-इन' : 'Mood Check-in',
    'quizzes.index' => $isHindi ? 'क्विज़' : 'Quizzes',
    'quizzes.show' => $isHindi ? 'क्विज़ विवरण' : 'Quiz Details',
    'support.crisis' => $isHindi ? 'संकट सहायता' : 'Crisis Support',
    'about' => $isHindi ? 'हमारे बारे में' : 'About',
    'contact' => $isHindi ? 'संपर्क' : 'Contact',
    'privacy.policy' => $isHindi ? 'गोपनीयता नीति' : 'Privacy Policy',
    'terms.service' => $isHindi ? 'सेवा की शर्तें' : 'Terms of Service',
    ];
    if (isset($routeCrumbs[$routeName])) {
    $breadcrumbItems[] = ['name' => $routeCrumbs[$routeName], 'url' => $canonicalUrl];
    }
    if ($isFilterRoute && request('city') && request('city') !== 'All') {
    $breadcrumbItems[] = ['name' => request('city'), 'url' => $currentUrl];
    }
    $breadcrumbSchema = [
    '@' . 'context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($breadcrumbItems)->values()->map(function ($crumb, $i) {
    return [
    '@type' => 'ListItem',
    'position' => $i + 1,
    'name' => $crumb['name'],
    'item' => $crumb['url'],
    ];
    })->all(),
    ];
    @endphp
    <script type="application/ld+json">
        {
            !!json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!
        }
    </script>
    @yield('structured_data')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Hind:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="{{ asset('vendor/lucide/lucide.min.js') }}"></script>
    <script>
        window.refreshLucideIcons = function refreshLucideIcons() {
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                window.lucide.createIcons();
            }
        };
        document.addEventListener('DOMContentLoaded', function () {
            window.refreshLucideIcons();
        });
    </script>
</head>

<body class="bg-[#F4FAF8] dark:bg-slate-950 font-sans antialiased text-[#2D3748] dark:text-slate-100 min-h-screen flex flex-col selection:bg-teal-500 selection:text-white relative overflow-x-hidden {{ session('locale', app()->getLocale()) === 'hi' ? 'lang-hi' : '' }}">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full bg-gradient-to-tr from-teal-400/20 to-emerald-400/20 dark:from-teal-500/10 dark:to-emerald-500/10 blur-[100px] opacity-75"></div>
        <div class="absolute top-1/4 -right-40 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-indigo-400/20 to-purple-400/20 dark:from-indigo-500/10 dark:to-purple-500/10 blur-[120px] opacity-75"></div>
        <div class="absolute -bottom-40 left-1/4 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-cyan-400/15 to-teal-400/15 dark:from-cyan-500/5 dark:to-teal-500/5 blur-[100px] opacity-75"></div>
    </div>
    <div id="site-toast-stack" class="site-toast-stack" aria-live="polite" aria-atomic="true"></div>
    @php
    $locale = session('locale', app()->getLocale());
    $chatbotCities = collect([$activeCity]);
    $chatbotCityPills = $chatbotCities;
    @endphp

    <!-- Header Navbar -->
    <nav class="sticky top-0 z-50 glass-panel shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between min-h-16 py-2 items-center gap-2 sm:gap-4">
                <div class="flex items-center min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-1.5 sm:space-x-3 group mr-1 sm:mr-6 shrink min-w-0">
                        <span class="relative block h-11 w-[148px] sm:h-12 sm:w-[162px] shrink-0" aria-label="Arogio">
                            <img src="{{ $brandLogoUrl }}" alt="Arogio" class="absolute inset-0 h-full w-full object-contain object-left drop-shadow-sm dark:opacity-0">
                            <img src="{{ $brandLogoDarkUrl }}" alt="Arogio" class="absolute inset-0 h-full w-full object-contain object-left drop-shadow-sm opacity-0 dark:opacity-100">
                        </span>
                    </a>

                    <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                        <a href="{{ route('doctors.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('doctors.*') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors' }}
                        </a>
                        <a href="{{ route('hospitals.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('hospitals.*') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals' }}
                        </a>
                        <a href="{{ route('blood_banks.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('blood_banks.*') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}
                        </a>
                        <a href="{{ route('medicines.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('medicines.*') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'दवाएं' : 'Medicines' }}
                        </a>
                        <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('articles.*') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles' }}
                        </a>
                        <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'हमारे बारे में' : 'About Us' }}
                        </a>
                        <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-teal-50 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 border border-teal-100/80 dark:border-teal-900/50 shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            {{ $locale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-0.5 rounded-xl border border-slate-200/60 dark:border-slate-700/60 shadow-inner">
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="flex items-center justify-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[11px] sm:text-sm font-medium leading-none whitespace-nowrap transition-all duration-200 min-w-[58px] sm:min-w-[88px] {{ $locale === 'en' ? 'bg-white dark:bg-slate-700 text-indigo-900 dark:text-indigo-200 shadow-sm font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                                <i data-lucide="globe" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-teal-600 shrink-0"></i>
                                <span class="hidden sm:inline">English</span><span class="sm:hidden font-bold">EN</span>
                            </button>
                        </form>
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="hi">
                            <button type="submit" class="flex items-center justify-center gap-1 px-2 sm:px-3 py-1 rounded-lg text-[11px] sm:text-sm font-medium leading-none whitespace-nowrap transition-all duration-200 min-w-[58px] sm:min-w-[88px] {{ $locale === 'hi' ? 'bg-white dark:bg-slate-700 text-indigo-900 dark:text-indigo-200 shadow-sm font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                                <i data-lucide="globe" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-teal-600 shrink-0"></i>
                                <span class="hidden sm:inline">हिन्दी</span><span class="sm:hidden font-bold">HI</span>
                            </button>
                        </form>
                    </div>
                    <button id="theme-toggle" type="button" onclick="window.toggleThemeMode()" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-xs min-w-[44px] sm:min-w-[52px]" aria-label="Switch to dark mode">
                        <span class="text-base leading-none dark:hidden" aria-hidden="true">🌙</span>
                        <span class="text-base leading-none hidden dark:inline" aria-hidden="true">☀️</span>
                        <span class="theme-toggle-dark-label hidden sm:inline text-xs font-semibold">Dark</span>
                        <span class="theme-toggle-light-label text-xs font-semibold">Light</span>
                    </button>
                    <button type="button" onclick="toggleMobileMenu()"
                        id="mobile-menu-toggle-btn"
                        class="md:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                        aria-label="Open navigation menu">
                        <i data-lucide="menu" id="mobile-menu-open-icon" class="w-5 h-5"></i>
                        <i data-lucide="x" id="mobile-menu-close-icon" class="w-5 h-5 hidden"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-nav-menu" class="md:hidden hidden pb-3 pt-2 border-t border-slate-200/80 dark:border-slate-800/80">
                <div class="mobile-nav-list">
                    <a href="{{ route('doctors.index') }}" class="mobile-nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i data-lucide="stethoscope" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors' }}</span>
                    </a>
                    <a href="{{ route('hospitals.index') }}" class="mobile-nav-item {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals' }}</span>
                    </a>
                    <a href="{{ route('blood_banks.index') }}" class="mobile-nav-item {{ request()->routeIs('blood_banks.*') ? 'active' : '' }}">
                        <i data-lucide="droplet" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}</span>
                    </a>
                    <a href="{{ route('medicines.index') }}" class="mobile-nav-item {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                        <i data-lucide="pill" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'दवाएं' : 'Medicines' }}</span>
                    </a>
                    <a href="{{ route('articles.index') }}" class="mobile-nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles' }}</span>
                    </a>
                    <a href="{{ route('about') }}" class="mobile-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'हमारे बारे में' : 'About Us' }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="mobile-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'संपर्क' : 'Contact' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <div id="chatbot-mobile-overlay" class="hidden fixed inset-0 bg-slate-950/45 backdrop-blur-[1px] z-[70] sm:hidden" onclick="toggleChatbot()"></div>
        <div id="lead-capture-overlay" class="hidden fixed inset-0 z-[95] bg-slate-950/60 backdrop-blur-[3px]"></div>
    <div id="lead-capture-modal" class="hidden fixed inset-0 z-[96] flex items-center justify-center p-4">
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-cyan-100/70 bg-white shadow-2xl ring-1 ring-cyan-100/60 dark:border-slate-700 dark:bg-slate-900 dark:ring-slate-700">
            <div class="absolute -top-16 -right-16 h-44 w-44 rounded-full bg-cyan-400/20 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-10 h-52 w-52 rounded-full bg-teal-400/15 blur-2xl"></div>

            <div class="relative px-6 pt-6 pb-4 border-b border-slate-100/90 dark:border-slate-800">
                <button type="button" id="lead-capture-close" aria-label="Close" class="absolute right-4 top-4 h-8 w-8 rounded-full border border-slate-200 bg-white/90 text-slate-500 hover:text-slate-800 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:text-white">
                    ×
                </button>
                <span class="inline-flex items-center gap-1 rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-teal-700">
                    {{ $locale === 'hi' ? 'स्वास्थ्य अपडेट' : 'Health Updates' }}
                </span>
                <h3 class="mt-3 text-xl font-extrabold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'जुड़ें और अपडेट पाएं' : 'Stay Connected' }}</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'बेहतर हेल्थकेयर सुझाव और अपडेट के लिए अपनी जानकारी साझा करें।' : 'Share your details to receive useful healthcare updates and tips.' }}</p>
            </div>

            <form id="lead-capture-form" class="relative px-6 py-5 space-y-4 bg-gradient-to-b from-white to-cyan-50/30 dark:from-slate-900 dark:to-slate-900">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'नाम' : 'Name' }}</label>
                    <input type="text" name="name" required placeholder="{{ $locale === 'hi' ? 'अपना नाम लिखें' : 'Enter your full name' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'ईमेल' : 'Email' }}</label>
                    <input type="email" name="email" required placeholder="you@example.com" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'मोबाइल नंबर' : 'Mobile Number' }}</label>
                    <input type="tel" name="mobile" inputmode="numeric" pattern="[0-9]{10,15}" required placeholder="{{ $locale === 'hi' ? '10-15 अंकों का नंबर' : '10-15 digit phone number' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500/25 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <p id="lead-capture-message" class="hidden rounded-lg px-3 py-2 text-xs font-semibold"></p>
                <div class="flex items-center justify-between gap-3 pt-1">
                    <button type="button" id="lead-capture-skip" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">{{ $locale === 'hi' ? 'अभी के लिए छोड़ें' : 'Skip for now' }}</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:from-teal-700 hover:to-cyan-700">{{ $locale === 'hi' ? 'सबमिट करें' : 'Submit' }}</button>
                </div>
            </form>
        </div>
    </div><div id="listing-report-overlay" class="hidden fixed inset-0 z-[96] bg-slate-950/55 backdrop-blur-[2px]"></div>
    <div id="listing-report-modal" class="hidden fixed inset-0 z-[97] flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'गलत जानकारी रिपोर्ट करें' : 'Report Incorrect Listing' }}</h3>
                <p id="listing-report-subtitle" class="mt-1 text-sm text-slate-600 dark:text-slate-300"></p>
            </div>
            <form id="listing-report-form" class="px-6 py-5 space-y-4">
                <input type="hidden" name="entity_type" id="listing-report-entity-type">
                <input type="hidden" name="entity_id" id="listing-report-entity-id">
                <input type="hidden" name="entity_name" id="listing-report-entity-name">
                <div>
<label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">{{ $locale === 'hi' ? 'समस्या का प्रकार' : 'Issue Type' }}</label>
                    <select name="issue" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                        <option value="wrong_phone">{{ $locale === 'hi' ? 'गलत फोन नंबर' : 'Wrong phone number' }}</option>
                        <option value="wrong_address">{{ $locale === 'hi' ? 'गलत पता' : 'Wrong address' }}</option>
                        <option value="duplicate">{{ $locale === 'hi' ? 'डुप्लिकेट लिस्टिंग' : 'Duplicate listing' }}</option>
                        <option value="closed">{{ $locale === 'hi' ? 'सेवा बंद है' : 'Service is closed' }}</option>
                        <option value="other">{{ $locale === 'hi' ? 'अन्य' : 'Other' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">{{ $locale === 'hi' ? 'टिप्पणी (आवश्यक)' : 'Remarks (Required)' }}</label>
                    <textarea name="details" required minlength="3" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500/30"></textarea>
                </div>
                <p id="listing-report-message" class="hidden text-xs font-semibold"></p>
                <div class="flex items-center justify-between pt-1">
                    <button type="button" id="listing-report-cancel" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">{{ $locale === 'hi' ? 'रद्द करें' : 'Cancel' }}</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm">{{ $locale === 'hi' ? 'रिपोर्ट जमा करें' : 'Submit Report' }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Floating Chatbot Widget -->
    <div class="fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-[95]" id="chatbot-container">
        <!-- Chat Button -->
        <button id="chatbot-toggle-btn" aria-label="Open AI assistant" onclick="toggleChatbot()" class="chatbot-fab fab-contracted relative isolate overflow-visible flex items-center gap-3 bg-cyan-600 dark:bg-cyan-500 text-white px-7 py-4 rounded-full shadow-[0_18px_40px_rgba(8,145,178,0.52)] dark:shadow-[0_18px_40px_rgba(6,182,212,0.4)] hover:bg-cyan-500 dark:hover:bg-cyan-400 hover:scale-105 transition-all duration-300 transform group ring-2 ring-white/35 dark:ring-cyan-100/35 border border-cyan-300/60 dark:border-cyan-200/45">
            <div class="chatbot-fab-icon w-6 h-6 flex items-center justify-center shrink-0">
                <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
            </div>
            <span id="chatbot-fab-label" class="chatbot-fab-label font-extrabold text-[17px] tracking-wide whitespace-nowrap leading-none pt-0.5">
                {{ $locale === 'hi' ? 'स्वास्थ्य साथी से पूछें' : 'Ask Swasthya Saathi' }}
            </span>
        </button>

        <!-- Chat Window -->
        <div id="chatbot-window" class="hidden w-[94vw] sm:w-[420px] h-[74vh] max-h-[680px] min-h-[520px] bg-white dark:bg-slate-950 rounded-3xl shadow-2xl border border-slate-300/90 dark:border-slate-800 flex flex-col overflow-hidden animate-in fade-in duration-300 chatbot-window">
            <!-- Header -->
            <div id="chatbot-header" class="relative bg-gradient-to-r from-cyan-900 via-teal-800 to-cyan-900 text-white p-4 flex justify-between items-start shadow-md">
                <div class="flex items-start space-x-3 min-w-0 pr-2">
                    <div class="p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30">
                        <i data-lucide="bot" class="w-6 h-6 text-teal-400"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-[17px] leading-tight text-white">Swasthya Saathi</h3>
                            <button type="button" id="chatbot-important-toggle" onclick="toggleChatbotImportant()" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500/90 text-[11px] font-bold text-white hover:bg-amber-400 transition-all" aria-label="Show important assistant details">i</button>
                        </div>
                        <p class="text-xs sm:text-[12px] text-teal-200 leading-snug mt-1 break-words">
                            {{ $locale === 'hi' ? 'तेज़ हेल्थकेयर खोज' : 'Quick healthcare search' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <select id="chatbot-language" class="text-xs bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border border-white/40 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-teal-400/60">
                        <option value="en" {{ $locale === 'en' ? 'selected' : '' }}>EN</option>
                        <option value="hi" {{ $locale === 'hi' ? 'selected' : '' }}>HI</option>
                    </select>
                    <button type="button" onclick="toggleSpeakEnabled()" id="chatbot-speak-toggle" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200" title="Speak replies">
                        <i data-lucide="volume-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <button onclick="toggleChatbot()" aria-label="Close AI assistant" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <div id="chatbot-important-details" class="hidden absolute left-4 right-4 top-[74px] z-20 text-[11px] leading-relaxed text-amber-950 bg-white border border-amber-200 rounded-xl px-3 py-2 shadow-xl">
                    This assistant does not provide diagnosis or treatment. For severe or urgent symptoms, visit the nearest hospital immediately and consult a qualified healthcare professional.
                </div>
            </div>

            <div class="px-3.5 py-2 bg-slate-50/90 dark:bg-slate-900/90 border-t border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="flex items-center justify-between gap-2 min-h-[36px] w-full text-xs">
                    <!-- City Pills Selection State (visible when not locked) -->
                    <div id="chatbot-city-select-wrapper" class="flex-1 flex items-center gap-1.5 min-w-0">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 shrink-0"></i>
                        <div id="chatbot-city-pill-wrap" class="flex flex-wrap gap-1.5 max-h-20 overflow-y-auto pr-1">
                            @foreach($chatbotCityPills as $city)
                            <button type="button" class="chatbot-city-pill" data-city="{{ $city }}" onclick="selectChatbotCity('{{ addslashes($city) }}')">
                                {{ $city }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Locked Selected State (visible when locked) -->
                    <div id="chatbot-city-locked-wrapper" class="hidden flex-1 items-center justify-between min-w-0">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-600 shrink-0 animate-custom-pulse"></i>
                            <span class="text-slate-500 mr-1 shrink-0 font-medium">{{ $locale === 'hi' ? 'शहर:' : 'City:' }}</span>
                            <span id="chatbot-selected-city-label" class="font-extrabold text-slate-900 dark:text-slate-100 truncate"></span>
                        </div>
                        <button type="button" id="chatbot-change-city-btn" onclick="enableCitySelection()" class="hidden shrink-0 text-[11px] font-bold text-cyan-700 hover:text-indigo-850 dark:text-indigo-400 dark:hover:text-indigo-350 bg-cyan-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 rounded-lg px-2.5 py-1.5 leading-none transition-all">
                            {{ $locale === 'hi' ? 'बदलें' : 'Change' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Body -->
            <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gradient-to-b from-cyan-50/50 via-white to-indigo-50/30 dark:from-slate-900/40 dark:via-slate-950 dark:to-slate-900/20">
                <!-- Initial Bot Message -->
                <div class="flex justify-start">
                    <div class="flex space-x-2 max-w-[85%] flex-row">
                        <div class="w-7 h-7 rounded-full bg-teal-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="bot" class="w-4 h-4"></i>
                        </div>
                        <div id="chatbot-initial-message" class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 border border-slate-200/60 dark:border-slate-700 rounded-tl-none">
                            {{ $locale === 'hi' ? 'नमस्ते, मैं जयपुर में डॉक्टर, अस्पताल, ब्लड बैंक और हेल्थ जानकारी खोजने में मदद कर सकता हूँ।' : "Hi, I can help you find doctors, hospitals, blood banks, and health information in Jaipur." }}
                        </div>
                    </div>
                </div>
                <div id="chatbot-city-select-message" class="hidden ml-9 max-w-[85%] rounded-xl border border-indigo-200 bg-cyan-50 px-3 py-2.5 dark:border-indigo-800 dark:bg-indigo-950/30">
                    <p class="text-xs font-bold text-indigo-900 dark:text-indigo-100 mb-2 leading-relaxed">
                        {{ $locale === 'hi' ? 'सक्रिय शहर: जयपुर' : 'Active city: Jaipur' }}
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($chatbotCityPills as $city)
                        <button type="button" class="chatbot-city-pill" data-city="{{ $city }}" onclick="selectChatbotCity('{{ addslashes($city) }}')">
                            {{ $city }}
                        </button>
                        @endforeach
                    </div>
                </div>
                <div id="chatbot-post-city-questions-wrapper" class="hidden ml-9 max-w-[85%] rounded-xl border border-teal-100 dark:border-teal-900/60 bg-teal-50/80 dark:bg-teal-950/25 px-3 py-2">
                    <p class="text-[11px] font-semibold text-teal-900 dark:text-teal-100">
                        {{ $locale === 'hi' ? 'उदाहरण: "Nearby में cardiologist", "नज़दीकी hospital", "A+ blood bank"' : 'Try: "cardiologist near me", "nearby hospital", "A+ blood bank"' }}
                    </p>
                </div>

                <div class="hidden" id="chatbot-quick-prompts-wrap">
                    <div class="ml-9 max-w-[85%]">
                        <div class="flex flex-wrap gap-1.5 mb-2" id="chatbot-quick-prompts">
                            <button type="button" onclick="handleQuickAction('doctors')" class="chatbot-chip">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctors' }}</button>
                            <button type="button" onclick="handleQuickAction('hospitals')" class="chatbot-chip">{{ $locale === 'hi' ? 'अस्पताल खोजें' : 'Find Hospitals' }}</button>
                            <button type="button" onclick="handleQuickAction('blood_banks')" class="chatbot-chip">{{ $locale === 'hi' ? 'ब्लड बैंक खोजें' : 'Find Blood Banks' }}</button>
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip chatbot-chip-danger" data-message="{{ $locale === 'hi' ? 'मुझे आपातकालीन मदद चाहिए' : 'I need emergency help' }}">{{ $locale === 'hi' ? 'आपातकालीन मदद' : 'Emergency Help' }}</button>
                        </div>
                        <div class="rounded-xl border border-cyan-200 dark:border-cyan-900/60 bg-cyan-50 dark:bg-cyan-950/25 px-3 py-2">
                            <p class="text-[11px] font-bold text-cyan-900 dark:text-cyan-100 mb-1.5">
                                {{ $locale === 'hi' ? 'त्वरित लक्षण विकल्प' : 'Quick Symptoms' }}
                            </p>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'मुझे बुखार है' : 'I have fever' }}">{{ $locale === 'hi' ? 'बुखार' : 'Fever' }}</button>
                                <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'मुझे खांसी है' : 'I have cough' }}">{{ $locale === 'hi' ? 'खांसी' : 'Cough' }}</button>
                                <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'मुझे सिरदर्द है' : 'I have headache' }}">{{ $locale === 'hi' ? 'सिरदर्द' : 'Headache' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="chatbot-loading" class="hidden px-4 py-2.5 flex space-x-2.5 items-center text-slate-700 dark:text-slate-200 text-sm bg-cyan-50/70 dark:bg-slate-900/80 border-y border-cyan-100 dark:border-slate-800">
                <div class="typing-dots" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <span id="chatbot-loading-text">{{ $locale === 'hi' ? 'Swasthya AI सोच रहा है...' : 'Swasthya AI is thinking...' }}</span>
            </div>
            <!-- Input Footer -->
            <form id="chatbot-form" onsubmit="handleChatbotSubmit(event)" class="p-3 bg-slate-50 dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800 flex items-center space-x-2 shadow-lg">
                <input type="text" id="chatbot-input" placeholder="{{ $locale === 'hi' ? 'लक्षण लिखें या डॉक्टर, अस्पताल, ब्लड बैंक खोजें...' : 'Describe symptoms or search doctors, hospitals, blood banks...' }}" class="flex-1 bg-white dark:bg-slate-950 border border-slate-300/80 dark:border-slate-700 rounded-2xl px-4 py-2.5 text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200">
                <button type="button" id="chatbot-voice-btn" onclick="toggleVoiceTyping()" class="bg-teal-600 hover:bg-teal-500 dark:bg-cyan-700 dark:hover:bg-cyan-600 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95" title="{{ $locale === 'hi' ? 'वॉइस टाइपिंग चालू/बंद करें' : 'Start/Stop voice typing' }}">
                    <i data-lucide="mic" class="w-5 h-5"></i>
                </button>
                <button type="submit" aria-label="Send message" class="bg-cyan-600 hover:bg-cyan-500 dark:bg-indigo-700 dark:hover:bg-indigo-600 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-cyan-950 text-white border-t border-cyan-900 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-gradient-to-tr from-teal-500 to-cyan-600 rounded-xl shadow-md">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Swasthya<span class="text-teal-400">Search</span></span>
                    </div>
                    <p class="text-sm text-slate-300">
                        {{ $locale === 'hi' ? 'arogio जयपुर में स्वास्थ्य सेवा प्रदाता खोजने में मदद करता है। हम निदान, उपचार या आपातकालीन प्रतिक्रिया प्रदान नहीं करते। जाने से पहले कृपया कॉल करें।' : 'Arogio helps users find healthcare providers in Jaipur. We do not provide diagnosis, treatment, or emergency response. Please call before visiting.' }}
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'प्लेटफ़ॉर्म' : 'Platform' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                        <a href="{{ route('contact') }}" class="block hover:text-white">Contact</a>
                        <a href="{{ route('medicines.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'दवाएं' : 'Medicines' }}</a>
                        <a href="{{ route('activities.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'गतिविधियां' : 'Activities' }}</a>
                        <a href="{{ route('quizzes.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'क्विज़' : 'Quizzes' }}</a>
                        <a href="{{ route('articles.index') }}" class="block hover:text-white">Articles</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'जयपुर हेल्थकेयर' : 'Healthcare in Jaipur' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('doctors.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'जयपुर के डॉक्टर' : 'Doctors in Jaipur' }}</a>
                        <a href="{{ route('hospitals.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'जयपुर के अस्पताल' : 'Hospitals in Jaipur' }}</a>
                        <a href="{{ route('blood_banks.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'जयपुर के ब्लड बैंक' : 'Blood Banks in Jaipur' }}</a>
                        <a href="{{ route('medicines.index') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'दवा जानकारी' : 'Medicine Information' }}</a>
                        <a href="{{ route('support.crisis') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'संकट सहायता' : 'Crisis Support' }}</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'सहायता व कानूनी' : 'Support & Legal' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('contact') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'गलत जानकारी रिपोर्ट करें' : 'Report Incorrect Information' }}</a>
                        <a href="{{ route('privacy.policy') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}</a>
                        <a href="{{ route('terms.service') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }}</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-800 text-xs text-slate-400 text-center md:text-left">
                {{ $locale === 'hi' ? '© 2026 Arogio. मरीजों के लिए निःशुल्क, भरोसेमंद और विज्ञापन-मुक्त हेल्थकेयर खोज मंच।' : '© 2026 Arogio. A free, trustworthy, ad-free healthcare discovery platform.' }}
            </div>
        </div>
    </footer>

    <!-- Compare Dock -->
    <div id="compare-dock" class="glass-panel py-4 px-6 z-[60] flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-650/10 dark:bg-indigo-400/15 flex items-center justify-center text-cyan-600 dark:text-indigo-400 shrink-0">
                    <i data-lucide="git-compare" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'अस्पतालों की तुलना' : 'Compare Hospitals' }}</h4>
                    <p id="compare-count-text" class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">0 hospitals selected</p>
                </div>
            </div>
            <!-- Thumbnails Container -->
            <div id="compare-thumbs" class="flex items-center gap-1.5 ml-0 sm:ml-4 flex-wrap"></div>
        </div>
        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <button onclick="clearCompare()" class="w-1/2 sm:w-auto px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl hover:bg-slate-50 dark:hover:bg-slate-755 transition-colors uppercase tracking-wider">
                {{ $locale === 'hi' ? 'साफ़ करें' : 'Clear All' }}
            </button>
            <button onclick="openCompareModal()" class="w-1/2 sm:w-auto px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-extrabold text-xs rounded-xl shadow-lg hover:shadow-indigo-500/20 transition-all uppercase tracking-wider">
                {{ $locale === 'hi' ? 'तुलना करें' : 'Compare Now' }}
            </button>
        </div>
    </div>

    <!-- Compare Modal -->
    <div id="compare-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-md" onclick="closeCompareModal()"></div>
        <div class="relative w-full max-w-4xl max-h-[85vh] bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden flex flex-col z-10">
            <!-- Header -->
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-850 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-cyan-50 dark:bg-indigo-950/40 text-cyan-600 dark:text-indigo-400 rounded-xl">
                        <i data-lucide="git-compare" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'विस्तृत तुलना मैट्रिक्स' : 'Detailed Comparison Matrix' }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'सर्वोत्तम विकल्प चुनने के लिए सुविधाओं की तुलना करें।' : 'Compare metrics side-by-side to make the best choice' }}</p>
                    </div>
                </div>
                <button onclick="closeCompareModal()" class="p-2 text-slate-500 hover:text-slate-850 dark:text-slate-400 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Table Content (Scrollable) -->
            <div class="flex-1 overflow-auto p-6 bg-white dark:bg-slate-900">
                <div class="w-full overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-slate-150 dark:border-slate-800" id="compare-table-head">
                                <!-- Populated dynamically -->
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800" id="compare-table-body">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        window.refreshLucideIcons();

        // Hospital Compare Controller Logic
        let comparedHospitals = [];

        function loadComparedHospitals() {
            try {
                const stored = localStorage.getItem('comparedHospitals');
                comparedHospitals = stored ? JSON.parse(stored) : [];
            } catch (e) {
                comparedHospitals = [];
            }
            updateCompareUI();
        }

        function toggleCompare(id, name, type, phone, cashless, ayushman, city, address, website) {
            const index = comparedHospitals.findIndex(h => h.id == id);
            if (index > -1) {
                comparedHospitals.splice(index, 1);
            } else {
                if (comparedHospitals.length >= 3) {
                    showSiteToast('You can only compare up to 3 hospitals at a time.', 'warning');
                    return;
                }
                comparedHospitals.push({
                    id,
                    name,
                    type,
                    phone,
                    cashless,
                    ayushman,
                    city,
                    address,
                    website
                });
            }
            try {
                localStorage.setItem('comparedHospitals', JSON.stringify(comparedHospitals));
            } catch (e) {}
            updateCompareUI();
        }

        function removeFromCompare(id) {
            comparedHospitals = comparedHospitals.filter(h => h.id != id);
            try {
                localStorage.setItem('comparedHospitals', JSON.stringify(comparedHospitals));
            } catch (e) {}
            updateCompareUI();
        }

        function clearCompare() {
            comparedHospitals = [];
            try {
                localStorage.removeItem('comparedHospitals');
            } catch (e) {}
            updateCompareUI();
        }

        function updateCompareUI() {
            const dock = document.getElementById('compare-dock');
            const countText = document.getElementById('compare-count-text');
            const thumbsContainer = document.getElementById('compare-thumbs');

            if (!dock || !countText || !thumbsContainer) return;

            // Highlight compare buttons on the page
            document.querySelectorAll('.compare-btn-card').forEach(btn => {
                const btnId = btn.getAttribute('data-id');
                if (comparedHospitals.some(h => h.id == btnId)) {
                    btn.classList.add('active');
                    const textSpan = btn.querySelector('span');
                    if (textSpan) {
                        textSpan.innerText = currentLocale === 'hi' ? 'हटाएँ' : 'Added';
                    }
                } else {
                    btn.classList.remove('active');
                    const textSpan = btn.querySelector('span');
                    if (textSpan) {
                        textSpan.innerText = currentLocale === 'hi' ? 'तुलना करें' : 'Compare';
                    }
                }
            });

            if (comparedHospitals.length > 0) {
                dock.classList.add('show');
                countText.innerText = currentLocale === 'hi' ?
                    `${comparedHospitals.length} अस्पताल चुने गए` :
                    `${comparedHospitals.length} hospital${comparedHospitals.length > 1 ? 's' : ''} selected`;

                // Render thumbnails
                thumbsContainer.innerHTML = comparedHospitals.map(h => `
                    <div class="relative group shrink-0">
                        <div class="compare-thumb shrink-0 shadow-2xs">${h.name.substring(0, 2).toUpperCase()}</div>
                        <button onclick="removeFromCompare('${h.id}')" class="absolute -top-1.5 -right-1.5 bg-slate-900 dark:bg-slate-800 text-white rounded-full p-0.5 shadow-md hover:bg-rose-600 transition-colors border border-white dark:border-slate-900">
                            <i data-lucide="x" class="w-2.5 h-2.5"></i>
                        </button>
                    </div>
                `).join('');
                window.refreshLucideIcons();
            } else {
                dock.classList.remove('show');
            }
        }

        function openCompareModal() {
            if (comparedHospitals.length === 0) return;
            const modal = document.getElementById('compare-modal');
            const head = document.getElementById('compare-table-head');
            const body = document.getElementById('compare-table-body');
            if (!modal || !head || !body) return;

            modal.classList.remove('hidden');

            // Build header
            let headHtml = `<th class="p-4 text-left text-xs font-bold text-slate-450 uppercase tracking-wider bg-slate-50 dark:bg-slate-900/50">${currentLocale === 'hi' ? 'विशेषताएँ' : 'Features'}</th>`;
            comparedHospitals.forEach(h => {
                headHtml += `
                    <th class="p-4 text-left bg-slate-50 dark:bg-slate-900/50 min-w-[200px]">
                        <div class="flex items-start gap-2.5">
                            <div class="compare-thumb shrink-0 shadow-2xs">${h.name.substring(0, 2).toUpperCase()}</div>
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white line-clamp-2">${h.name}</h4>
                                <span class="inline-block text-[10px] font-bold text-cyan-700 dark:text-indigo-400 bg-cyan-50 dark:bg-indigo-950/40 px-2 py-0.5 rounded-md mt-1 uppercase tracking-wider">${h.type}</span>
                            </div>
                        </div>
                    </th>
                `;
            });
            head.innerHTML = headHtml;

            // Define comparison fields
            const fields = [{
                    labelEn: 'Type',
                    labelHi: 'प्रकार',
                    key: 'type'
                },
                {
                    labelEn: 'City / Region',
                    labelHi: 'शहर / क्षेत्र',
                    key: 'city'
                },
                {
                    labelEn: 'Cashless Facility',
                    labelHi: 'कैशलेस सुविधा',
                    key: 'cashless',
                    isBadge: true,
                    yesColor: 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30'
                },
                {
                    labelEn: 'Ayushman Bharat',
                    labelHi: 'आयुष्मान भारत',
                    key: 'ayushman',
                    isBadge: true,
                    yesColor: 'text-teal-700 bg-teal-50 dark:text-teal-400 dark:bg-teal-950/30'
                },
                {
                    labelEn: 'Emergency Contact',
                    labelHi: 'आपातकालीन नंबर',
                    key: 'phone',
                    isCall: true
                },
                {
                    labelEn: 'Address',
                    labelHi: 'पता',
                    key: 'address'
                },
                {
                    labelEn: 'Action',
                    labelHi: 'कार्रवाई',
                    isAction: true
                }
            ];

            let bodyHtml = '';
            fields.forEach(f => {
                const label = currentLocale === 'hi' ? f.labelHi : f.labelEn;
                bodyHtml += `<tr class="border-b border-slate-100 dark:border-slate-800/80">`;
                bodyHtml += `<td class="p-4 text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-900/10">${label}</td>`;

                comparedHospitals.forEach(h => {
                    let cellVal = h[f.key] || '—';
                    let cellHtml = `<td class="p-4 text-xs text-slate-700 dark:text-slate-300">`;

                    if (f.isBadge) {
                        const isYes = cellVal === 'Yes' || cellVal === true || cellVal === 1 || cellVal === '1';
                        const badgeColor = isYes ? f.yesColor : 'text-slate-500 bg-slate-50 dark:bg-slate-800/60 dark:text-slate-450';
                        const badgeLabel = isYes ? (currentLocale === 'hi' ? 'उपलब्ध' : 'Available') : (currentLocale === 'hi' ? 'उपलब्ध नहीं' : 'Not Available');
                        cellHtml += `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold ${badgeColor}">${badgeLabel}</span>`;
                    } else if (f.isCall) {
                        cellHtml += (cellVal && cellVal !== '—') ?
                            `<a href="tel:${cellVal}" class="inline-flex items-center space-x-1 font-extrabold text-teal-600 dark:text-teal-400 hover:underline">
                                <i data-lucide="phone-call" class="w-3.5 h-3.5"></i>
                                <span>${cellVal}</span>
                               </a>` :
                            `<span class="text-slate-400 dark:text-slate-500">Unlisted</span>`;
                    } else if (f.isAction) {
                        cellHtml += `
                            <div class="flex gap-2">
                                <button onclick="removeFromCompare('${h.id}')" class="px-3 py-1.5 border border-rose-200 dark:border-rose-950/60 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-rose-600 dark:text-rose-450 font-bold text-[10px] rounded-lg transition-colors uppercase tracking-wider">
                                    ${currentLocale === 'hi' ? 'हटाएँ' : 'Remove'}
                                </button>
                                ${h.phone && h.phone !== '—' 
                                    ? `<a href="tel:${h.phone}" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-[10px] rounded-lg shadow-sm transition-colors uppercase tracking-wider text-center flex items-center justify-center">
                                        ${currentLocale === 'hi' ? 'कॉल' : 'Call'}
                                       </a>` 
                                    : ''}
                            </div>
                        `;
                    } else {
                        cellHtml += `<span>${cellVal}</span>`;
                    }
                    cellHtml += `</td>`;
                    bodyHtml += cellHtml;
                });
                bodyHtml += `</tr>`;
            });
            body.innerHTML = bodyHtml;
            window.refreshLucideIcons();
        }

        function closeCompareModal() {
            const modal = document.getElementById('compare-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');

        window.updateThemeToggleUI = function updateThemeToggleUI() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                if (themeToggleBtn) themeToggleBtn.setAttribute('aria-label', 'Switch to light mode');
            } else {
                if (themeToggleBtn) themeToggleBtn.setAttribute('aria-label', 'Switch to dark mode');
            }
        };

        // Initialize UI icon based on current class
        window.updateThemeToggleUI();

        // Chatbot Logic
        let chatbotOpen = false;
        let chatbotSessionToken = null;
        let chatbotInitialMessagesHtml = '';
        let currentLocale = "{{ $locale }}";
        let chatbotLocale = currentLocale === 'hi' ? 'hi' : 'en';
        let chatbotCity = '';
        let isCityLocked = false;
        let speechRecognition = null;
        let isVoiceTyping = false;
        let speakEnabled = false;
        let isSubmittingChat = false;
        let pendingChatAbortController = null;
        let hasCityPromptVisible = false;
        let chatbotDetailBlockCounter = 0;
        let activeListenButton = null;
        const CHATBOT_CITY_STORAGE_KEY = 'arogio_selected_city';
        const LEGACY_CHATBOT_CITY_STORAGE_KEYS = ['swasthya_selected_city', 'swasthyasearch_chatbot_city'];
        const CHATBOT_CITY_ONBOARDED_KEY = 'arogio_chatbot_city_onboarded';

        function hasCompletedCityOnboarding() {
            return true;
        }

        function markCityOnboardingComplete() {
            localStorage.setItem(CHATBOT_CITY_ONBOARDED_KEY, '1');
        }

        function getLegacyChatbotCity() {
            for (const key of LEGACY_CHATBOT_CITY_STORAGE_KEYS) {
                const value = normalizeCityValue(localStorage.getItem(key));
                if (value) return value;
            }

            return '';
        }

        function normalizeCityValue(city) {
            return String(city || '').trim().replace(/\s+/g, ' ');
        }

        function chatbotHasCityChoices() {
            return document.querySelectorAll('.chatbot-city-pill').length > 0;
        }

        function showSiteToast(message, type = 'info', timeoutMs = 3200) {
            const stack = document.getElementById('site-toast-stack');
            if (!stack || !message) return;

            const toast = document.createElement('div');
            toast.className = `site-toast site-toast-${type}`;
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 h-8 w-8 rounded-xl bg-white/70 dark:bg-white/10 flex items-center justify-center">
                        <i data-lucide="${type === 'success' ? 'check-circle-2' : type === 'error' ? 'x-circle' : type === 'warning' ? 'alert-triangle' : 'info'}" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold leading-relaxed">${escapeHtml(message)}</p>
                    </div>
                    <button type="button" class="shrink-0 rounded-lg px-2 py-1 text-xs font-bold opacity-70 hover:opacity-100" aria-label="Close notification">✕</button>
                </div>
            `;

            const closeButton = toast.querySelector('button');
            const removeToast = () => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(8px) scale(0.98)';
                setTimeout(() => toast.remove(), 180);
            };

            closeButton.addEventListener('click', removeToast);
            stack.appendChild(toast);
            if (window.lucide) window.refreshLucideIcons();

            if (timeoutMs > 0) {
                window.setTimeout(removeToast, timeoutMs);
            }
        }

        function confirmSiteAction(message) {
            return window.confirm(message);
        }

        function getCityFromUrl() {
            const params = new URLSearchParams(window.location.search);
            return normalizeCityValue(params.get('city'));
        }

        function getCityFromDropdowns() {
            const citySelects = Array.from(document.querySelectorAll('select[name="city"]'));
            for (const select of citySelects) {
                const value = normalizeCityValue(select.value);
                if (value && value.toLowerCase() !== 'all') return value;
            }
            return '';
        }

        function setCityStorage(city) {
            const normalized = normalizeCityValue(city);
            if (!normalized) return;
            localStorage.setItem(CHATBOT_CITY_STORAGE_KEY, normalized);
            LEGACY_CHATBOT_CITY_STORAGE_KEYS.forEach(key => localStorage.setItem(key, normalized));
        }

        function syncCityDropdowns(city) {
            const normalized = normalizeCityValue(city);
            if (!normalized) return;
            const citySelects = Array.from(document.querySelectorAll('select[name="city"]'));
            citySelects.forEach(select => {
                const matchOption = Array.from(select.options).find(opt => normalizeCityValue(opt.value).toLowerCase() === normalized.toLowerCase());
                if (matchOption) {
                    select.value = matchOption.value;
                    select.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });
        }

        function resolveSelectedCity() {
            const fromUrl = getCityFromUrl();
            if (fromUrl) return fromUrl;
            const fromDropdown = getCityFromDropdowns();
            if (fromDropdown) return fromDropdown;
            const fromState = normalizeCityValue(chatbotCity);
            if (fromState) return fromState;
            const fromStorage = normalizeCityValue(localStorage.getItem(CHATBOT_CITY_STORAGE_KEY) || getLegacyChatbotCity());
            if (fromStorage) return fromStorage;
            return '';
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatDetailedAnswer(value) {
            return formatMessageText(value);
        }

        function formatMessageText(value) {
            if (!value) return '';

            // First, escape HTML
            let escaped = escapeHtml(value);

            // Process lines
            let lines = escaped.split('\n');
            let inList = false;
            let listType = null; // 'ul' or 'ol'
            let resultLines = [];

            for (let i = 0; i < lines.length; i++) {
                let line = lines[i];
                let trimmed = line.trim();

                // Match unordered lists: lines starting with *, -, or +
                let ulMatch = line.match(/^(\s*)[*\-+]\s+(.+)$/);
                // Match ordered lists: lines starting with digits like 1.
                let olMatch = line.match(/^(\s*)\d+\.\s+(.+)$/);

                if (ulMatch) {
                    if (!inList || listType !== 'ul') {
                        if (inList) {
                            resultLines.push(`</${listType}>`);
                        }
                        resultLines.push('<ul style="list-style-type: disc; padding-left: 1.25rem; margin-top: 0.375rem; margin-bottom: 0.375rem;" class="space-y-1">');
                        inList = true;
                        listType = 'ul';
                    }
                    let content = processInlineMarkdown(ulMatch[2]);
                    resultLines.push(`<li>${content}</li>`);
                } else if (olMatch) {
                    if (!inList || listType !== 'ol') {
                        if (inList) {
                            resultLines.push(`</${listType}>`);
                        }
                        resultLines.push('<ol style="list-style-type: decimal; padding-left: 1.25rem; margin-top: 0.375rem; margin-bottom: 0.375rem;" class="space-y-1">');
                        inList = true;
                        listType = 'ol';
                    }
                    let content = processInlineMarkdown(olMatch[2]);
                    resultLines.push(`<li>${content}</li>`);
                } else {
                    if (inList) {
                        resultLines.push(`</${listType}>`);
                        inList = false;
                        listType = null;
                    }

                    // Headers: ### Header
                    let headerMatch = line.match(/^(#{1,6})\s+(.+)$/);
                    if (headerMatch) {
                        let level = headerMatch[1].length;
                        let content = processInlineMarkdown(headerMatch[2]);
                        let style = 'font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.25rem; display: block;';
                        let sizeClass = level === 1 ? 'text-base' : 'text-sm';
                        resultLines.push(`<span class="${sizeClass}" style="${style}">${content}</span>`);
                    } else if (trimmed === '') {
                        resultLines.push('<div class="h-2"></div>');
                    } else {
                        let content = processInlineMarkdown(line);
                        resultLines.push(`<p class="mb-1">${content}</p>`);
                    }
                }
            }

            if (inList) {
                resultLines.push(`</${listType}>`);
            }

            return resultLines.join('\n');
        }

        function processInlineMarkdown(text) {
            return text
                .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                .replace(/\*([^*]+)\*/g, '<em>$1</em>')
                .replace(/_([^_]+)_/g, '<em>$1</em>')
                .replace(/`([^`]+)`/g, '<code class="bg-slate-100 px-1 rounded text-xs font-mono">$1</code>');
        }

        function toggleChatbotDetails(buttonId, contentId) {
            const button = document.getElementById(buttonId);
            const content = document.getElementById(contentId);
            if (!button || !content) return;

            const isOpen = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            button.dataset.moreLabel = button.dataset.moreLabel || 'Know more about this';
            button.dataset.lessLabel = button.dataset.lessLabel || 'Show less';
            button.innerText = isOpen ? button.dataset.moreLabel : button.dataset.lessLabel;
            content.classList.toggle('hidden', isOpen);
        }

        function toggleChatbotActionPanel(buttonId, panelId) {
            const button = document.getElementById(buttonId);
            const panel = document.getElementById(panelId);
            if (!button || !panel) return;

            const isOpen = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden', isOpen);
            button.classList.toggle('active', !isOpen);
            scrollToChatBottom();
        }

        let fabCycleInterval = null;
        let fabSingleTimeout = null;

        function clearFabCycles() {
            if (fabCycleInterval) {
                clearInterval(fabCycleInterval);
                fabCycleInterval = null;
            }
            if (fabSingleTimeout) {
                clearTimeout(fabSingleTimeout);
                fabSingleTimeout = null;
            }
        }

        function contractFab() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;
            if (!chatbotOpen) {
                btn.classList.add('fab-contracted');
            }
        }

        function setupFabHintCycle() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;

            clearFabCycles();
            // Keep CTA compact as a circular button.
            btn.classList.add('fab-contracted');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-nav-menu');
            if (!menu) return;
            menu.classList.toggle('hidden');
            updateMobileMenuIcon();
        }

        const LEAD_CAPTURE_NEXT_SHOW_KEY = 'swasthya_lead_capture_next_show_at';
        const LEAD_CAPTURE_DONE_KEY = 'swasthya_lead_capture_done';

        function closeLeadCaptureModal() {
            document.getElementById('lead-capture-modal')?.classList.add('hidden');
            document.getElementById('lead-capture-overlay')?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function skipLeadCapture(days = 3) {
            const next = Date.now() + (days * 24 * 60 * 60 * 1000);
            localStorage.setItem(LEAD_CAPTURE_NEXT_SHOW_KEY, String(next));
            closeLeadCaptureModal();
        }

        function showLeadCaptureModal() {
            if (localStorage.getItem(LEAD_CAPTURE_DONE_KEY) === '1') return;
            const nextShowAt = Number(localStorage.getItem(LEAD_CAPTURE_NEXT_SHOW_KEY) || 0);
            if (nextShowAt && Date.now() < nextShowAt) return;
            if (chatbotOpen) return;

            document.getElementById('lead-capture-overlay')?.classList.remove('hidden');
            document.getElementById('lead-capture-modal')?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function openListingReportModal(entityType, entityId, entityName) {
            const typeInput = document.getElementById('listing-report-entity-type');
            const idInput = document.getElementById('listing-report-entity-id');
            const nameInput = document.getElementById('listing-report-entity-name');
            const subtitle = document.getElementById('listing-report-subtitle');
            const msg = document.getElementById('listing-report-message');
            if (msg) msg.classList.add('hidden');
            if (typeInput) typeInput.value = entityType;
            if (idInput) idInput.value = entityId;
            if (nameInput) nameInput.value = entityName;
            if (subtitle) subtitle.textContent = `${entityName}`;
            document.getElementById('listing-report-overlay')?.classList.remove('hidden');
            document.getElementById('listing-report-modal')?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function getVoteCountElementId(entityType, voteType, entityId) {
            return `vote-${voteType}-${entityType}-${entityId}`;
        }

        function applyVoteCountsFromResponse(entityType, entityId, counts) {
            if (!counts) return;
            const greenEl = document.getElementById(getVoteCountElementId(entityType, 'green', entityId));
            const redEl = document.getElementById(getVoteCountElementId(entityType, 'red', entityId));
            if (greenEl && Number.isFinite(Number(counts.green))) {
                greenEl.textContent = String(counts.green);
            }
            if (redEl && Number.isFinite(Number(counts.red))) {
                redEl.textContent = String(counts.red);
            }
        }

        async function submitGreenVote(entityType, entityId, entityName) {
            try {
                const res = await fetch('{{ route("listing.vote.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        entity_type: String(entityType || ''),
                        entity_id: Number(entityId || 0),
                        entity_name: String(entityName || ''),
                        vote_type: 'green',
                    }),
                });
                if (!res.ok) return;
                const data = await res.json();
                applyVoteCountsFromResponse(String(entityType), Number(entityId), data?.counts || null);
            } catch (err) {
                // Keep UI silent for vote failure to avoid interrupting browsing flow.
            }
        }

        function closeListingReportModal() {
            document.getElementById('listing-report-modal')?.classList.add('hidden');
            document.getElementById('listing-report-overlay')?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobile-nav-menu');
            if (!menu) return;
            menu.classList.add('hidden');
            updateMobileMenuIcon();
        }

        function updateMobileMenuIcon() {
            const menu = document.getElementById('mobile-nav-menu');
            const openIcon = document.getElementById('mobile-menu-open-icon');
            const closeIcon = document.getElementById('mobile-menu-close-icon');
            const btn = document.getElementById('mobile-menu-toggle-btn');
            if (!menu || !openIcon || !closeIcon || !btn) return;
            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                openIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                openIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
            btn.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
        }

        function selectChatbotCity(city) {
            const previousCity = chatbotCity;
            chatbotCity = normalizeCityValue(city);
            if (!chatbotCity) return;
            markCityOnboardingComplete();
            setCityStorage(chatbotCity);
            syncCityDropdowns(chatbotCity);
            isCityLocked = true;
            hasCityPromptVisible = false;
            refreshChatbotCityUI();
            if (chatbotCity !== previousCity) {
                appendMessage(
                    'bot',
                    chatbotLocale === 'hi' ?
                    `बहुत बढ़िया, आपने ${chatbotCity} चुना है। अब लक्षण लिखें या नीचे दिए गए विकल्प चुनें।` :
                    `Great, you've selected ${chatbotCity}. Now type your symptoms or use the quick options below.`
                );
            }
        }

        function enableCitySelection() {
            isCityLocked = false;
            refreshChatbotCityUI();
        }

        function moveQuickPromptsToBottom() {
            const quickPromptsWrap = document.getElementById('chatbot-quick-prompts-wrap');
            const messagesDiv = document.getElementById('chatbot-messages');
            if (!quickPromptsWrap || !messagesDiv) return;
            messagesDiv.appendChild(quickPromptsWrap);
        }

        function refreshChatbotCityUI() {
            const selectWrapper = document.getElementById('chatbot-city-select-wrapper');
            const lockedWrapper = document.getElementById('chatbot-city-locked-wrapper');
            const selectedLabel = document.getElementById('chatbot-selected-city-label');
            const cityPills = document.querySelectorAll('.chatbot-city-pill');
            const quickPromptsWrap = document.getElementById('chatbot-quick-prompts-wrap');
            const postCityQuestionsWrapper = document.getElementById('chatbot-post-city-questions-wrapper');
            const citySelectMessage = document.getElementById('chatbot-city-select-message');
            const input = document.getElementById('chatbot-input');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            if (!selectWrapper || !lockedWrapper || !selectedLabel || !quickPromptsWrap) return;
            const hasCityChoices = cityPills.length > 0;
            cityPills.forEach(pill => {
                const isActive = normalizeCityValue(pill.dataset.city || '') === chatbotCity;
                pill.classList.toggle('active', isActive);
            });

            if (isCityLocked && chatbotCity) {
                selectedLabel.textContent = chatbotCity;
                selectWrapper.classList.add('hidden');
                if (citySelectMessage) citySelectMessage.classList.add('hidden');
                lockedWrapper.classList.remove('hidden');
                lockedWrapper.classList.add('flex');
                quickPromptsWrap.classList.remove('hidden');
                if (postCityQuestionsWrapper) postCityQuestionsWrapper.classList.remove('hidden');
                moveQuickPromptsToBottom();
                if (input) {
                    input.disabled = false;
                    input.placeholder = chatbotLocale === 'hi' ? 'लक्षण बताएं या डॉक्टर, अस्पताल, ब्लड बैंक खोजें...' : 'Describe symptoms or search doctors, hospitals, blood banks...';
                }
                if (sendBtn) sendBtn.disabled = false;
                if (voiceBtn) voiceBtn.disabled = false;
                scrollToChatBottom();
            } else if (hasCityChoices) {
                selectedLabel.textContent = '';
                selectWrapper.classList.add('hidden');
                lockedWrapper.classList.add('hidden');
                quickPromptsWrap.classList.add('hidden');
                if (postCityQuestionsWrapper) postCityQuestionsWrapper.classList.add('hidden');
                if (citySelectMessage) citySelectMessage.classList.remove('hidden');
                if (input) {
                    input.disabled = true;
                    input.placeholder = chatbotLocale === 'hi' ? 'पहले शहर चुनें...' : 'Choose a city from the pills above';
                }
                if (sendBtn) sendBtn.disabled = true;
                if (voiceBtn) voiceBtn.disabled = true;
            } else {
                selectedLabel.textContent = '';
                selectWrapper.classList.add('hidden');
                lockedWrapper.classList.add('hidden');
                quickPromptsWrap.classList.add('hidden');
                if (postCityQuestionsWrapper) postCityQuestionsWrapper.classList.add('hidden');
                if (citySelectMessage) citySelectMessage.classList.add('hidden');
                if (input) {
                    input.disabled = false;
                    input.placeholder = chatbotLocale === 'hi' ? 'लक्षण लिखें या हेल्थ सवाल पूछें...' : 'Describe symptoms or ask a health question...';
                }
                if (sendBtn) sendBtn.disabled = false;
                if (voiceBtn) voiceBtn.disabled = false;
            }
        }
        function getInitialChatbotMessage() {
            const fixedCity = @json(config('healthcare.active_city', 'Jaipur'));
            const fixedCityHi = @json(config('healthcare.active_city_hi', 'जयपुर'));
            if (!chatbotHasCityChoices()) {
                return chatbotLocale === 'hi'
                    ? 'नमस्ते, मैं Swasthya AI Assistant हूँ। आप अबही सामान्य हेल्थ सवाल पूछ सकते हैं। शहर डाटा उपलब्ध होने पर नज़दीकी डॉक्टर और अस्पताल सुझाव भी दिखेंगे।'
                    : 'Hi, I'm Swasthya AI Assistant. You can ask general health questions right now. Nearby doctor and hospital suggestions will appear when city data is available.';
            }
            if (chatbotCity) {
                return chatbotLocale === 'hi' ?
                    `नमस्ते, मैं ${fixedCityHi} में डॉक्टर, अस्पताल, ब्लड बैंक और हेल्थ जानकारी खोजने में मदद कर सकता हूँ। आपको क्या चाहिए?` :
                    `Hi, I can help you find doctors, hospitals, blood banks, and health information in ${fixedCity}. What do you need help with today?`;
            }
            return chatbotLocale === 'hi'
                ? `नमस्ते, मैं ${fixedCityHi} में डॉक्टर, अस्पताल, ब्लड बैंक और हेल्थ जानकारी खोजने में मदद कर सकता हूँ। आपको क्या चाहिए?`
                : `Hi, I can help you find doctors, hospitals, blood banks, and health information in ${fixedCity}. What do you need help with today?`;
        }
        function initializeChatbotCity() {
            chatbotCity = @json(config('healthcare.active_city', 'Jaipur'));
            isCityLocked = true;
            setCityStorage(chatbotCity);
            syncCityDropdowns(chatbotCity);
            const initialMessage = document.getElementById('chatbot-initial-message');
            if (initialMessage) {
                initialMessage.textContent = getInitialChatbotMessage();
            }
            refreshChatbotCityUI();
        }

        async function initializeChatbot() {
            initializeChatbotCity();

            if (chatbotSessionToken) {
                try {
                    const res = await fetch('/api/chatbot', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            session_token: chatbotSessionToken,
                            message: '',
                            city: chatbotCity,
                            locale: chatbotLocale,
                        }),
                    });
                    const data = await res.json();
                    if (data.history && data.history.length > 0) {
                        const messagesDiv = document.getElementById('chatbot-messages');
                        messagesDiv.innerHTML = '';
                        let lastBotMsgIndex = -1;
                        for (let i = data.history.length - 1; i >= 0; i--) {
                            if (data.history[i].sender === 'bot') {
                                lastBotMsgIndex = i;
                                break;
                            }
                        }
                        data.history.forEach((msg, idx) => {
                            appendMessageObj(msg, idx === lastBotMsgIndex, data.history, idx);
                        });
                        window.refreshLucideIcons();
                        scrollToChatBottom();
                    }
                } catch (e) {
                    console.error("Error loading chat history:", e);
                }
            }
        }

        function resetChatbotForFreshStart() {
            chatbotSessionToken = null;
            hasCityPromptVisible = false;
            const messagesDiv = document.getElementById('chatbot-messages');
            if (messagesDiv && chatbotInitialMessagesHtml) {
                messagesDiv.innerHTML = chatbotInitialMessagesHtml;
            }
            const initialMessage = document.getElementById('chatbot-initial-message');
            if (initialMessage) {
                initialMessage.textContent = getInitialChatbotMessage();
            }
            refreshChatbotCityUI();
            window.refreshLucideIcons();
            scrollToChatBottom();
        }

        function toggleChatbot() {
            const btn = document.getElementById('chatbot-toggle-btn');
            const win = document.getElementById('chatbot-window');
            const overlay = document.getElementById('chatbot-mobile-overlay');
            chatbotOpen = !chatbotOpen;

            const syncChatbotVisibility = () => {
                if (!btn || !win) return;
                btn.classList.toggle('chatbot-fab-hidden', chatbotOpen);
                win.classList.toggle('hidden', !chatbotOpen);
            };

            if (chatbotOpen) {
                clearFabCycles();
                syncChatbotVisibility();
                if (window.innerWidth < 640) {
                    overlay?.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.getElementById('chatbot-input')?.focus();
                }
                setTimeout(handleChatbotKeyboardViewport, 80);
                scrollToChatBottom();
            } else {
                stopMessageListening();
                resetChatbotForFreshStart();
                if (pendingChatAbortController) {
                    pendingChatAbortController.abort();
                    pendingChatAbortController = null;
                }
                isSubmittingChat = false;
                const loadingDiv = document.getElementById('chatbot-loading');
                if (loadingDiv) loadingDiv.classList.add('hidden');
                const input = document.getElementById('chatbot-input');
                if (input) input.value = '';
                const details = document.getElementById('chatbot-important-details');
                if (details) details.classList.add('hidden');
                syncChatbotVisibility();
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                win.style.height = '';
                win.style.maxHeight = '';
                win.style.minHeight = '';
                win.style.bottom = '';
                setupFabHintCycle();
            }
        }

        function handleQuickAction(action) {
            const selectedCity = resolveSelectedCity();
            const query = selectedCity ? `?city=${encodeURIComponent(selectedCity)}` : '';
            if (action === 'doctors') window.location.href = `{{ route('doctors.index') }}${query}`;
            if (action === 'hospitals') window.location.href = `{{ route('hospitals.index') }}${query}`;
            if (action === 'blood_banks') window.location.href = `{{ route('blood_banks.index') }}${query}`;
        }

        function clearChatConversation() {
            const messagesDiv = document.getElementById('chatbot-messages');
            if (!messagesDiv) return;
            messagesDiv.innerHTML = '';
            appendMessage('bot',
                chatbotCity ?
                (chatbotLocale === 'hi' ?
                    `Chat cleared. Your selected city is ${chatbotCity}. What would you like to find?` :
                    `Chat cleared. Your selected city is ${chatbotCity}. What would you like to find?`) :
                (chatbotLocale === 'hi' ?
                    'Chat cleared. Please choose a city to continue.' :
                    'Chat cleared. Please choose a city to continue.')
            );
            refreshChatbotCityUI();
        }

        function toggleChatbotImportant() {
            const details = document.getElementById('chatbot-important-details');
            if (!details) return;
            details.classList.toggle('hidden');
            if (!details.classList.contains('hidden')) {
                setTimeout(() => scrollToChatBottom(), 20);
            }
        }
        async function useQuickPrompt(btn) {
            const msg = btn?.dataset?.message || '';
            if (!msg) return;
            await submitChatbotMessage(msg);
        }
        function scrollToChatBottom() {
            const messagesDiv = document.getElementById('chatbot-messages');
            if (!messagesDiv) return;
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function handleChatbotKeyboardViewport() {
            if (window.innerWidth >= 640 || !chatbotOpen) return;
            scrollToChatBottom();
        }

        function setupChatbotKeyboardHandlers() {
            const input = document.getElementById('chatbot-input');
            if (!input) return;

            input.addEventListener('focus', () => {
                setTimeout(() => {
                    handleChatbotKeyboardViewport();
                    scrollToChatBottom();
                }, 90);
            });

            input.addEventListener('input', () => {
                scrollToChatBottom();
            });

            if (window.visualViewport) {
                window.visualViewport.addEventListener('resize', scrollToChatBottom);
                window.visualViewport.addEventListener('scroll', scrollToChatBottom);
            }
            window.addEventListener('orientationchange', scrollToChatBottom);
        }

        function getLocalizedText(field, fallback = '') {
            if (!field) return fallback;
            if (typeof field === 'string') return field;
            const lang = chatbotLocale === 'hi' ? 'hi' : 'en';
            return field[lang] || field.en || fallback;
        }

        function speakText(text) {
            if (!('speechSynthesis' in window) || !speakEnabled || !text) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(String(text).replace(/<[^>]*>/g, ''));
            utterance.lang = chatbotLocale === 'hi' ? 'hi-IN' : 'en-US';
            window.speechSynthesis.speak(utterance);
        }

        function resetListenButtonState(btn) {
            if (!btn) return;
            btn.classList.remove('listening');
            btn.innerHTML = `🔊 Listen`;
        }

        function stopMessageListening() {
            if (!('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            resetListenButtonState(activeListenButton);
            activeListenButton = null;
        }

        function toggleMessageListen(encodedText, btn) {
            if (!('speechSynthesis' in window) || !btn) return;

            const text = decodeURIComponent(String(encodedText || '')).replace(/<[^>]*>/g, '').trim();
            if (!text) return;

            if (activeListenButton === btn && window.speechSynthesis.speaking) {
                stopMessageListening();
                return;
            }

            stopMessageListening();
            activeListenButton = btn;
            btn.classList.add('listening');
            btn.innerHTML = `⏹ Stop`;

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = chatbotLocale === 'hi' ? 'hi-IN' : 'en-US';
            utterance.onend = () => {
                if (activeListenButton === btn) {
                    resetListenButtonState(btn);
                    activeListenButton = null;
                }
            };
            utterance.onerror = () => {
                if (activeListenButton === btn) {
                    resetListenButtonState(btn);
                    activeListenButton = null;
                }
            };
            window.speechSynthesis.speak(utterance);
        }

        function toggleSpeakEnabled() {
            speakEnabled = !speakEnabled;
            const btn = document.getElementById('chatbot-speak-toggle');
            if (!btn) return;
            btn.classList.toggle('text-teal-300', speakEnabled);
        }

        function getSpeechLocale() {
            return chatbotLocale === 'hi' ? 'hi-IN' : 'en-US';
        }

        function updateVoiceButtonState() {
            const btn = document.getElementById('chatbot-voice-btn');
            if (!btn) return;

            if (isVoiceTyping) {
                btn.classList.remove('bg-teal-600', 'hover:bg-teal-500');
                btn.classList.add('bg-rose-600', 'hover:bg-rose-500');
                btn.title = 'Stop voice typing';
            } else {
                btn.classList.remove('bg-rose-600', 'hover:bg-rose-500');
                btn.classList.add('bg-teal-600', 'hover:bg-teal-500');
                btn.title = 'Start voice typing';
            }
        }

        function toggleVoiceTyping() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                appendMessage('bot', chatbotLocale === 'hi' ?
                    'Voice typing is not supported in your browser. Please use Chrome or Edge.' :
                    'Voice typing is not supported in your browser. Please use Chrome or Edge.');
                return;
            }

            if (!speechRecognition) {
                speechRecognition = new SpeechRecognition();
                speechRecognition.lang = getSpeechLocale();
                speechRecognition.interimResults = true;
                speechRecognition.continuous = false;

                speechRecognition.onresult = function(event) {
                    let transcript = '';
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        transcript += event.results[i][0].transcript;
                    }
                    document.getElementById('chatbot-input').value = transcript.trim();
                };

                speechRecognition.onerror = function() {
                    isVoiceTyping = false;
                    updateVoiceButtonState();
                };

                speechRecognition.onend = function() {
                    isVoiceTyping = false;
                    updateVoiceButtonState();
                };
            }

            if (isVoiceTyping) {
                speechRecognition.stop();
                isVoiceTyping = false;
                updateVoiceButtonState();
                return;
            }

            speechRecognition.lang = getSpeechLocale();
            speechRecognition.start();
            isVoiceTyping = true;
            updateVoiceButtonState();
        }

        async function submitChatbotMessage(message) {
            const languageInput = document.getElementById('chatbot-language');
            chatbotLocale = languageInput?.value === 'hi' ? 'hi' : 'en';
            currentLocale = chatbotLocale;
            if (isSubmittingChat) return;
            chatbotCity = resolveSelectedCity();

            if (!chatbotCity && chatbotHasCityChoices()) {
                if (!hasCityPromptVisible) {
                    appendMessage('bot', 'Please select your city so I can show relevant nearby results.');
                    hasCityPromptVisible = true;
                }
                return;
            }
            hasCityPromptVisible = false;
            setCityStorage(chatbotCity);
            syncCityDropdowns(chatbotCity);

            const input = document.getElementById('chatbot-input');
            if (input) input.value = '';
            appendMessage('user', message);

            const loadingDiv = document.getElementById('chatbot-loading');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');
            loadingDiv.classList.remove('hidden');
            if (sendBtn) sendBtn.disabled = true;
            isSubmittingChat = true;
            scrollToChatBottom();

            try {
                pendingChatAbortController = new AbortController();
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    signal: pendingChatAbortController.signal,
                    body: JSON.stringify({
                        session_token: chatbotSessionToken,
                        message: message,
                        city: chatbotCity,
                        locale: chatbotLocale,
                    }),
                });

                if (!res.ok) {
                    throw new Error(`chatbot_http_${res.status}`);
                }
                const data = await res.json();
                if (data.session_token) chatbotSessionToken = data.session_token;

                loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;

                if (data.history) {
                    // Re-render history
                    const messagesDiv = document.getElementById('chatbot-messages');
                    messagesDiv.innerHTML = '';
                    let lastBotMsgIndex = -1;
                    for (let i = data.history.length - 1; i >= 0; i--) {
                        if (data.history[i].sender === 'bot') {
                            lastBotMsgIndex = i;
                            break;
                        }
                    }
                    data.history.forEach((msg, idx) => {
                        appendMessageObj(msg, idx === lastBotMsgIndex, data.history, idx);
                    });
                    window.refreshLucideIcons();
                    scrollToChatBottom();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                reportChatbotFailure({
                    message,
                    failureType: 'submit_message_failed',
                    errorMessage: error?.message || 'unknown client fetch error',
                });
                loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;
                appendMessage('bot', 'We could not process your request due to a technical issue. Please try again or choose "Find Doctors".');
            }
        }

        function getLoadedResourcesInCurrentTurn(history, currentIndex) {
            const loaded = { doctors: false, hospitals: false, articles: false };
            for (let i = currentIndex; i >= 0; i--) {
                const msg = history[i];
                if (!msg) continue;
                if (msg.sender === 'user') {
                    break;
                }
                if (msg.doctors && msg.doctors.length > 0) loaded.doctors = true;
                if (msg.hospitals && msg.hospitals.length > 0) loaded.hospitals = true;
                if (msg.articles && msg.articles.length > 0) loaded.articles = true;
                if (msg.load_type) {
                    loaded[msg.load_type] = true;
                }
            }
            return loaded;
        }

        async function loadChatbotResource(type) {
            if (isSubmittingChat) return;
            chatbotCity = resolveSelectedCity();
            if (!chatbotCity) return;

            const loadingDiv = document.getElementById('chatbot-loading');
            const loadingText = document.getElementById('chatbot-loading-text');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');

            let loaderMsg = '';
            if (type === 'doctors') {
                loaderMsg = "Searching for best specialist near you...";
            } else if (type === 'hospitals') {
                loaderMsg = "Finding hospitals near you...";
            } else if (type === 'articles') {
                loaderMsg = "Finding helpful articles...";
            }

            if (loadingText) {
                loadingText.textContent = loaderMsg;
            }
            if (loadingDiv) {
                loadingDiv.classList.remove('hidden');
            }
            if (sendBtn) {
                sendBtn.disabled = true;
            }
            isSubmittingChat = true;
            scrollToChatBottom();

            try {
                pendingChatAbortController = new AbortController();
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    signal: pendingChatAbortController.signal,
                    body: JSON.stringify({
                        session_token: chatbotSessionToken,
                        message: '',
                        load_type: type,
                        city: chatbotCity,
                        locale: chatbotLocale,
                    }),
                });

                if (!res.ok) {
                    throw new Error(`chatbot_http_${res.status}`);
                }
                const data = await res.json();
                if (data.session_token) chatbotSessionToken = data.session_token;

                if (loadingDiv) loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;

                if (loadingText) {
                    loadingText.textContent = 'Swasthya AI is thinking...';
                }

                if (data.history) {
                    const messagesDiv = document.getElementById('chatbot-messages');
                    messagesDiv.innerHTML = '';
                    let lastBotMsgIndex = -1;
                    for (let i = data.history.length - 1; i >= 0; i--) {
                        if (data.history[i].sender === 'bot') {
                            lastBotMsgIndex = i;
                            break;
                        }
                    }
                    data.history.forEach((msg, idx) => {
                        appendMessageObj(msg, idx === lastBotMsgIndex, data.history, idx);
                    });
                    window.refreshLucideIcons();
                    scrollToChatBottom();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                reportChatbotFailure({
                    message: '',
                    failureType: 'load_resource_failed',
                    errorMessage: error?.message || 'unknown client fetch error',
                    meta: { load_type: type }
                });
                if (loadingDiv) loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;
                if (loadingText) {
                    loadingText.textContent = 'Swasthya AI is thinking...';
                }
                appendMessage('bot', 'We could not load options due to a technical issue. Please try again.');
            }
        }

        async function reportChatbotFailure({ message = '', failureType = 'client_fetch_failure', errorMessage = '', meta = {} } = {}) {
            try {
                await fetch('/api/chatbot/failure-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    keepalive: true,
                    body: JSON.stringify({
                        session_token: chatbotSessionToken,
                        city: chatbotCity,
                        locale: chatbotLocale,
                        message,
                        failure_type: failureType,
                        error_message: errorMessage,
                        meta,
                    }),
                });
            } catch (_) {
                // Intentionally swallow reporting errors to preserve chat UX.
            }
        }

        async function handleChatbotSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) {
                appendMessage('bot', chatbotLocale === 'hi' ? 'कृपया संदेश लिखें।' : 'Please enter a message.');
                return;
            }
            await submitChatbotMessage(message);
        }

        function appendMessage(sender, text) {
            appendMessageObj({
                sender,
                text
            }, true);
            window.refreshLucideIcons();
            scrollToChatBottom();
        }

        function appendMessageObj(msg, isLastBotMsg = true, history = [], idx = -1) {
            const messagesDiv = document.getElementById('chatbot-messages');
            const isUser = msg.sender === 'user';

            const spoken = String(msg.text || '').replace(/\*\*/g, '').replace(/[*_\-`]/g, '');
            const spokenEncoded = encodeURIComponent(spoken);
            const isWarning = !isUser && /(emergency|urgent|call|आपात|तुरंत|helpline)/i.test(String(msg.text || ''));
            let html = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200">
                    <div class="flex space-x-2 max-w-[85%] ${isUser ? 'flex-row-reverse space-x-reverse' : 'flex-row'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${isUser ? 'bg-cyan-600 text-white' : 'bg-teal-500 text-white'}">
                            <i data-lucide="${isUser ? 'user' : 'bot'}" class="w-4 h-4"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${isUser ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-tr-none border border-indigo-400/50' : isWarning ? 'bg-amber-50 dark:bg-amber-950/30 text-amber-900 dark:text-amber-100 border border-amber-200 dark:border-amber-900/60 rounded-tl-none' : 'bg-white/95 dark:bg-slate-900 text-slate-800 dark:text-slate-100 border border-slate-200/70 dark:border-slate-700 rounded-tl-none backdrop-blur-sm'}">
                                ${isUser ? escapeHtml(msg.text) : formatMessageText(msg.text)}
                            </div>
                            <div class="flex flex-wrap gap-2 items-center">
                                ${!isUser ? `<button type="button" onclick="toggleMessageListen('${spokenEncoded}', this)" class="inline-flex items-center gap-1 text-[11px] text-slate-600 dark:text-slate-300 hover:text-teal-700 dark:hover:text-cyan-300 text-left px-2.5 py-1 rounded-lg hover:bg-teal-50 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-900 transition-all">🔊 ${chatbotLocale === 'hi' ? 'सुनें' : 'Listen'}</button>` : ''}
                                ${(!isUser && msg.suggest_details && isLastBotMsg) ? `<button type="button" onclick="submitChatbotMessage('${chatbotLocale === 'hi' ? 'कृपया विस्तार से समझाएं' : 'Please explain in detail'}')" class="inline-flex items-center gap-1 text-[11px] font-semibold text-cyan-700 dark:text-cyan-300 hover:text-indigo-900 dark:hover:text-indigo-200 border border-indigo-200 dark:border-indigo-700 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-900 hover:bg-cyan-50 dark:hover:bg-slate-800 rounded-lg px-2.5 py-1 transition-all">💬 ${chatbotLocale === 'hi' ? 'विस्तार से समझाएं' : 'Explain in Detail'}</button>` : ''}
                            </div>
                            ${(!isUser && msg.show_options && isLastBotMsg && idx >= 0) ? (() => {
                                const loaded = getLoadedResourcesInCurrentTurn(history, idx);
                                let optionsHtml = '';
                                
                                if (!loaded.doctors) {
                                    optionsHtml += `
                                        <button type="button" onclick="loadChatbotResource('doctors')" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-teal-700 hover:text-teal-900 border border-teal-200 hover:border-teal-300 bg-white dark:bg-slate-900 dark:border-slate-800 dark:text-teal-400 dark:hover:bg-slate-800/80 hover:bg-teal-50 rounded-lg px-2.5 py-1.5 transition-all">
                                            <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-500 shrink-0"></i>
                                            <span>${chatbotLocale === 'hi' ? 'विशेषज्ञ डॉक्टर' : 'Specialist Doctors'}</span>
                                        </button>
                                    `;
                                }
                                if (!loaded.hospitals) {
                                    optionsHtml += `
                                        <button type="button" onclick="loadChatbotResource('hospitals')" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-cyan-700 hover:text-indigo-900 border border-indigo-200 hover:border-indigo-300 bg-white dark:bg-slate-900 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-slate-800/80 hover:bg-cyan-50 rounded-lg px-2.5 py-1.5 transition-all">
                                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-indigo-500 shrink-0"></i>
                                            <span>${chatbotLocale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics'}</span>
                                        </button>
                                    `;
                                }
                                if (!loaded.articles) {
                                    optionsHtml += `
                                        <button type="button" onclick="loadChatbotResource('articles')" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-700 hover:text-amber-900 border border-amber-200 hover:border-amber-300 bg-white dark:bg-slate-900 dark:border-slate-800 dark:text-amber-400 dark:hover:bg-slate-800/80 hover:bg-amber-50 rounded-lg px-2.5 py-1.5 transition-all">
                                            <i data-lucide="book-open" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i>
                                            <span>${chatbotLocale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles'}</span>
                                        </button>
                                    `;
                                }

                                if (optionsHtml !== '') {
                                    return `
                                        <div class="pt-2 flex flex-wrap gap-2 items-center">
                                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block w-full mb-1">
                                                ${chatbotLocale === 'hi' ? 'संबंधित विकल्प लोड करें:' : 'Load related options:'}
                                            </span>
                                            ${optionsHtml}
                                        </div>
                                    `;
                                }
                                return '';
                            })() : ''}
            `;

            if (!isUser) {
                // Render Department Recommendation directly under the response bubble if present
                if (msg.department_info) {
                    html += `
                        <div class="bg-teal-50/80 dark:bg-teal-950/25 border border-teal-100 dark:border-teal-900/60 p-3.5 rounded-2xl text-teal-950 dark:text-teal-100 text-xs shadow-xs mt-2 flex items-start space-x-2.5">
                            <i data-lucide="info" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5 animate-pulse"></i>
                            <div>
                                <span class="leading-relaxed font-medium">${msg.department_info}</span>
                            </div>
                        </div>
                    `;
                }

                // Collapsible detailed Q&A block if present
                const detailedAnswerRaw = chatbotLocale === 'hi' ?
                    (msg?.qa_answer?.detailed_answer_hi || msg?.qa_answer?.detailed_answer_en || '') :
                    (msg?.qa_answer?.detailed_answer_en || msg?.qa_answer?.detailed_answer_hi || '');
                const detailedAnswer = String(detailedAnswerRaw || '').trim();

                if (detailedAnswer !== '') {
                    chatbotDetailBlockCounter += 1;
                    const buttonId = `chatbot-detail-toggle-${chatbotDetailBlockCounter}`;
                    const contentId = `chatbot-detail-content-${chatbotDetailBlockCounter}`;
                    const moreLabel = chatbotLocale === 'hi' ? 'इसके बारे में और जानें' : 'Know more about this';
                    const lessLabel = chatbotLocale === 'hi' ? 'कम दिखाएं' : 'Show less';
                    html += `
                        <div class="pt-1">
                            <button
                                id="${buttonId}"
                                type="button"
                                aria-expanded="false"
                                aria-controls="${contentId}"
                                data-more-label="${moreLabel}"
                                data-less-label="${lessLabel}"
                                onclick="toggleChatbotDetails('${buttonId}','${contentId}')"
                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-cyan-700 dark:text-cyan-300 hover:text-indigo-900 dark:hover:text-indigo-200 border border-indigo-200 dark:border-indigo-700 hover:border-indigo-300 dark:hover:border-indigo-600 bg-white dark:bg-slate-900 hover:bg-cyan-50 dark:hover:bg-slate-800 rounded-full px-3 py-1 transition-all"
                            >
                                <i data-lucide="info" class="w-3 h-3"></i>
                                <span>${moreLabel}</span>
                            </button>
                            <div id="${contentId}" class="hidden mt-2 rounded-xl border border-cyan-100 dark:border-cyan-900/60 bg-cyan-50/70 dark:bg-slate-900 p-3 text-xs leading-relaxed text-slate-700 dark:text-slate-200">
                                ${formatDetailedAnswer(detailedAnswer)}
                            </div>
                        </div>
                    `;
                }

                // Render Doctors list directly (visible by default) if present
                if (msg.doctors && msg.doctors.length > 0) {
                    html += `
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 dark:border-slate-800 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'विशेषज्ञ डॉक्टर' : 'Specialist Doctors'}</span>
                            </h5>
                    `;
                    msg.doctors.forEach(doc => {
                        const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                        const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                        const emergencyPhone = doc.hospitals?.[0]?.phone_1 || doc.hospitals?.[0]?.phone_2 || doc.hospitals?.[0]?.phone || '';
                        const hospName = doc.hospitals?.[0] ? getLocalizedText(doc.hospitals[0].name) : '';

                        html += `
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-cyan-100 dark:border-slate-700 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800 dark:text-slate-100">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-sm text-indigo-950 dark:text-indigo-100 flex items-center space-x-1">
                                        <span>${fullName}</span>
                                        ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 px-2 py-0.5 rounded-lg font-medium shrink-0">
                                        ${doc.experience_years} ${currentLocale === 'hi' ? 'वर्ष अनुभव' : 'yrs exp'}
                                    </span>
                                </div>
                                <div class="mt-2 space-y-1 text-xs text-slate-600 dark:text-slate-300">
                                    <div class="flex items-center space-x-1">
                                        <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-600 shrink-0"></i>
                                        <span class="font-medium text-slate-700 dark:text-slate-200">${deptName}</span>
                                    </div>
                                    ${hospName ? `
                                        <div class="flex items-start space-x-1 pt-0.5">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                            <span>${hospName}</span>
                                        </div>
                                    ` : ''}
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 dark:bg-teal-950/40 hover:bg-teal-600 dark:hover:bg-teal-700 hover:text-white text-teal-700 dark:text-teal-300 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200">
                                            ${currentLocale === 'hi' ? 'कॉल करें' : 'Call Doctor'}
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    });

                    if (msg.see_all_doctors_url) {
                        html += `
                            <div class="pt-1 flex justify-end">
                                <a href="${msg.see_all_doctors_url}" class="inline-flex items-center space-x-1 text-xs bg-teal-50 dark:bg-teal-950/40 hover:bg-teal-600 dark:hover:bg-teal-700 hover:text-white text-teal-700 dark:text-teal-300 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
                                    <span>${currentLocale === 'hi' ? 'सभी डॉक्टर देखें' : 'See all doctors'}</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        `;
                    }
                    html += `</div>`;
                }

                // Render Hospitals list directly (visible by default) if present
                if (msg.hospitals && msg.hospitals.length > 0) {
                    html += `
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 dark:border-slate-800 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals & Clinics'}</span>
                            </h5>
                    `;
                    msg.hospitals.forEach(hosp => {
                        const hospName = getLocalizedText(hosp.name);
                        const emergencyPhone = hosp.phone_1 || hosp.phone_2 || hosp.phone || '';
                        const city = hosp.city || '';

                        html += `
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-teal-100 dark:border-slate-700 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800 dark:text-slate-100">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-sm text-teal-950 dark:text-teal-100 flex items-center space-x-1">
                                        <span>${hospName}</span>
                                        ${hosp.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline shrink-0"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider shrink-0">
                                        ${hosp.type || 'Hospital'}
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-start space-x-1 text-xs text-slate-600 dark:text-slate-300">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                                    <span class="line-clamp-2">${hosp.address || ''} ${city ? ', ' + city : ''}</span>
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 dark:bg-teal-950/40 hover:bg-teal-600 dark:hover:bg-teal-700 hover:text-white text-teal-700 dark:text-teal-300 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
                                            <i data-lucide="phone-call" class="w-3 h-3"></i>
                                            <span>${currentLocale === 'hi' ? 'कॉल करें' : 'Call Emergency'}</span>
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    });

                    if (msg.see_all_hospitals_url) {
                        html += `
                            <div class="pt-1 flex justify-end">
                                <a href="${msg.see_all_hospitals_url}" class="inline-flex items-center space-x-1 text-xs bg-cyan-50 dark:bg-cyan-950/40 hover:bg-cyan-600 dark:hover:bg-cyan-700 hover:text-white text-cyan-700 dark:text-cyan-300 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
                                    <span>${currentLocale === 'hi' ? 'सभी अस्पताल देखें' : 'See all hospitals'}</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        `;
                    }
                    html += `</div>`;
                }

                // Render Blood Banks list directly (visible by default) if present
                if (msg.blood_banks && msg.blood_banks.length > 0) {
                    html += `
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 dark:border-slate-800 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="droplet" class="w-3.5 h-3.5 text-rose-600"></i>
                                <span>${currentLocale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks'}</span>
                            </h5>
                    `;
                    msg.blood_banks.forEach(bank => {
                        const bankName = getLocalizedText(bank.name);
                        const emergencyPhone = bank.emergency_phone || bank.phone || '';
                        const city = bank.city || '';
                        const address = bank.address || '';

                        html += `
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-rose-100 dark:border-rose-900/40 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800 dark:text-slate-100">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-sm text-rose-950 dark:text-rose-100 flex items-center space-x-1">
                                        <span>${bankName}</span>
                                        ${bank.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-rose-600 inline shrink-0"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider shrink-0">
                                        ${currentLocale === 'hi' ? 'सत्यापित' : 'Verified'}
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-start space-x-1 text-xs text-slate-600 dark:text-slate-300">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                                    <span class="line-clamp-2">${address} ${city ? ', ' + city : ''}</span>
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-600 dark:hover:bg-rose-700 hover:text-white text-rose-700 dark:text-rose-300 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
                                            <i data-lucide="phone-call" class="w-3 h-3"></i>
                                            <span>${currentLocale === 'hi' ? 'कॉल करें' : 'Call Now'}</span>
                                        </a>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    });

                    if (msg.see_all_blood_banks_url) {
                        html += `
                            <div class="pt-1 flex justify-end">
                                <a href="${msg.see_all_blood_banks_url}" class="inline-flex items-center space-x-1 text-xs bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-600 dark:hover:bg-rose-700 hover:text-white text-rose-700 dark:text-rose-300 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
                                    <span>${currentLocale === 'hi' ? 'सभी ब्लड बैंक देखें' : 'See all blood banks'}</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        `;
                    }
                    html += `</div>`;
                }

                // Render Articles list directly (visible by default) if present
                if (msg.articles && msg.articles.length > 0) {
                    html += `
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 dark:border-slate-800 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="book-open" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles'}</span>
                            </h5>
                    `;
                    msg.articles.forEach(art => {
                        const artTitle = getLocalizedText(art.title);
                        const artExcerpt = getLocalizedText(art.excerpt) || (getLocalizedText(art.content) || '').substring(0, 80) + '...';

                        html += `
                            <div class="bg-white dark:bg-slate-900 p-3 rounded-2xl border border-cyan-100 dark:border-slate-700 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800 dark:text-slate-100">
                                <h4 class="font-bold text-sm text-slate-900 dark:text-slate-100 line-clamp-1">${artTitle}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 line-clamp-2">${artExcerpt}</p>
                                <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                                    <a href="/articles/${art.id}" target="_blank" class="text-xs bg-cyan-50 dark:bg-cyan-950/40 hover:bg-cyan-600 dark:hover:bg-cyan-700 hover:text-white text-cyan-700 dark:text-cyan-300 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
                                        <span>${currentLocale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article'}</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    if (msg.see_all_articles_url) {
                        html += `
                            <div class="pt-1 flex justify-end">
                                <a href="${msg.see_all_articles_url}" class="inline-flex items-center space-x-1 text-xs bg-cyan-50 dark:bg-cyan-950/40 hover:bg-cyan-600 dark:hover:bg-cyan-700 hover:text-white text-cyan-700 dark:text-cyan-300 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
                                    <span>${currentLocale === 'hi' ? 'सभी लेख देखें' : 'See all articles'}</span>
                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        `;
                    }
                    html += `</div>`;
                }
            }

            html += `
                        </div>
                    </div>
                </div>
            `;

            messagesDiv.insertAdjacentHTML('beforeend', html);
            if (!isUser) {
                speakText(msg.text);
            }
        }

        window.addEventListener('resize', () => {
            if (!chatbotOpen && window.innerWidth >= 640) setupFabHintCycle();
        });
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobile-nav-menu');
            const btn = document.getElementById('mobile-menu-toggle-btn');
            if (!menu || !btn || menu.classList.contains('hidden')) return;
            const target = e.target;
            if (!(target instanceof Node)) return;
            if (!menu.contains(target) && !btn.contains(target)) {
                closeMobileMenu();
            }
        });
        document.querySelectorAll('#mobile-nav-menu a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && chatbotOpen) {
                toggleChatbot();
            }
        });
        document.querySelectorAll('select[name="city"]').forEach(select => {
            select.addEventListener('change', function() {
                // Keep first-time flow city-pill-first inside chatbot.
                if (!hasCompletedCityOnboarding()) return;
                const value = normalizeCityValue(select.value);
                if (!value || value.toLowerCase() === 'all') return;
                chatbotCity = value;
                isCityLocked = true;
                hasCityPromptVisible = false;
                setCityStorage(value);
                refreshChatbotCityUI();
            });
        });
        document.getElementById('chatbot-language')?.addEventListener('change', function(e) {
            chatbotLocale = e.target.value === 'hi' ? 'hi' : 'en';
            currentLocale = chatbotLocale;
            refreshChatbotCityUI();

            const initialMsg = document.getElementById('chatbot-initial-message');
            if (initialMsg) {
                initialMsg.textContent = getInitialChatbotMessage();
            }
        });
        initializeChatbotCity();
        setupChatbotKeyboardHandlers();
        document.getElementById('chatbot-toggle-btn')?.classList.remove('chatbot-fab-hidden');
        document.getElementById('chatbot-window')?.classList.add('hidden');
        const initialMessagesDiv = document.getElementById('chatbot-messages');
        if (initialMessagesDiv) {
            chatbotInitialMessagesHtml = initialMessagesDiv.innerHTML;
        }
        setupFabHintCycle();

        document.getElementById('lead-capture-overlay')?.addEventListener('click', () => skipLeadCapture(3));
        document.getElementById('lead-capture-skip')?.addEventListener('click', () => skipLeadCapture(7));
        document.getElementById('lead-capture-close')?.addEventListener('click', () => skipLeadCapture(7));
        document.getElementById('lead-capture-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const msgEl = document.getElementById('lead-capture-message');
            const formData = new FormData(form);
            const payload = {
                name: String(formData.get('name') || '').trim(),
                email: String(formData.get('email') || '').trim(),
                mobile: String(formData.get('mobile') || '').trim(),
            };

            try {
                const res = await fetch('{{ route("lead.capture.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });
                if (!res.ok) throw new Error('Unable to submit');
                const data = await res.json();

                localStorage.setItem(LEAD_CAPTURE_DONE_KEY, '1');
                if (msgEl) {
                    msgEl.classList.remove('hidden');
                    msgEl.classList.remove('text-rose-600');
                    msgEl.classList.add('text-emerald-600');
                    msgEl.textContent = '{{ $locale === "hi" ? "धन्यवाद! आपकी जानकारी सफलतापूर्वक सेव हो गई।" : "Thanks! Your details were saved successfully." }}';
                }
                setTimeout(closeLeadCaptureModal, 800);
            } catch (err) {
                if (msgEl) {
                    msgEl.classList.remove('hidden');
                    msgEl.classList.remove('text-emerald-600');
                    msgEl.classList.add('text-rose-600');
                    msgEl.textContent = '{{ $locale === "hi" ? "कृपया सही विवरण भरें और दोबारा प्रयास करें।" : "Please check your details and try again." }}';
                }
            }
        });

        setTimeout(showLeadCaptureModal, 3500);
        document.getElementById('listing-report-overlay')?.addEventListener('click', closeListingReportModal);
        document.getElementById('listing-report-cancel')?.addEventListener('click', closeListingReportModal);
        document.getElementById('listing-report-form')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const msgEl = document.getElementById('listing-report-message');
            const formData = new FormData(form);
            const payload = {
                entity_type: String(formData.get('entity_type') || ''),
                entity_id: Number(formData.get('entity_id') || 0),
                entity_name: String(formData.get('entity_name') || ''),
                issue: String(formData.get('issue') || ''),
                details: String(formData.get('details') || '').trim(),
            };

            try {
                const res = await fetch('{{ route("listing.report.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });
                if (!res.ok) throw new Error('Unable to submit');
                const data = await res.json();
                applyVoteCountsFromResponse(payload.entity_type, payload.entity_id, data?.counts || null);
                if (msgEl) {
                    msgEl.classList.remove('hidden', 'text-rose-600');
                    msgEl.classList.add('text-emerald-600');
                    msgEl.textContent = '{{ $locale === "hi" ? "धन्यवाद! आपकी रिपोर्ट दर्ज कर ली गई है।" : "Thank you! Your report has been submitted." }}';
                }
                setTimeout(closeListingReportModal, 900);
            } catch (err) {
                if (msgEl) {
                    msgEl.classList.remove('hidden', 'text-emerald-600');
                    msgEl.classList.add('text-rose-600');
                    msgEl.textContent = '{{ $locale === "hi" ? "रिपोर्ट सबमिट नहीं हो सकी। कृपया दोबारा प्रयास करें।" : "Please add remarks and try again." }}';
                }
            }
        });

        function initializePageAnimations() {
            document.body.classList.add('motion-ready');
            document.body.classList.add('preload-anim');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    document.body.classList.add('page-loaded');
                });
            });

            const animateTargets = new Set();
            document.querySelectorAll('header, section.max-w-7xl, main > *, main article, main .grid > div, main .grid > a').forEach(el => {
                if (el.id === 'chatbot-container' || el.id === 'chatbot-window') return;
                animateTargets.add(el);
            });

            animateTargets.forEach((el, idx) => {
                el.classList.add('animate-on-scroll');
                el.style.setProperty('--reveal-delay', `${Math.min(idx * 35, 280)}ms`);
            });

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                animateTargets.forEach(el => el.classList.add('in-view'));
                return;
            }

            const isMobileView = window.innerWidth < 640;
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                });
            }, {
                threshold: isMobileView ? 0.02 : 0.12,
                rootMargin: isMobileView ? '0px 0px 18% 0px' : '0px 0px -8% 0px'
            });

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    animateTargets.forEach(el => observer.observe(el));
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializePageAnimations);
        } else {
            initializePageAnimations();
        }

        function initializeAutoFilterForms() {
            const forms = document.querySelectorAll('form[data-auto-filter]');
            if (!forms.length) return;

            forms.forEach(form => {
                let textDebounce;
                const textInputs = form.querySelectorAll('input[type="text"], input[type="search"]');
                const selects = form.querySelectorAll('select');

                selects.forEach(select => {
                    select.addEventListener('change', () => {
                        form.submit();
                    });
                });

                textInputs.forEach(input => {
                    input.addEventListener('input', () => {
                        clearTimeout(textDebounce);
                        textDebounce = setTimeout(() => form.submit(), 500);
                    });

                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            clearTimeout(textDebounce);
                            form.submit();
                        }
                    });
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAutoFilterForms);
        } else {
            initializeAutoFilterForms();
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadComparedHospitals();

            // Shift chatbot container up when footer is visible to prevent overlap
            const footer = document.querySelector('footer');
            const chatbotContainer = document.getElementById('chatbot-container');
            if (footer && chatbotContainer) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        chatbotContainer.classList.toggle('footer-visible', entry.isIntersecting);
                    });
                }, {
                    threshold: 0,
                    rootMargin: '0px'
                });
                observer.observe(footer);
            }
        });
    </script>
    <style>
        :root {
            --brand-teal: #0ea5a6;
            --brand-indigo: #4f46e5;
        }

        /* Smooth Dark/Light transition */
        body {
            transition: background-color 300ms ease, color 300ms ease, border-color 300ms ease;
        }

        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(14px) saturate(120%) !important;
            -webkit-backdrop-filter: blur(14px) saturate(120%) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }

        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.7) !important;
            backdrop-filter: blur(14px) saturate(120%) !important;
            -webkit-backdrop-filter: blur(14px) saturate(120%) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.8) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06) !important;
            border-color: rgba(79, 70, 229, 0.25) !important;
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
        }

        .dark .glass-card:hover {
            background: rgba(15, 23, 42, 0.7) !important;
            border-color: rgba(20, 184, 166, 0.25) !important;
        }

        /* Glow Blobs */
        .glow-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.8;
        }

        .indian-motif-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 20%, rgba(251, 191, 36, 0.16), transparent 28%),
                radial-gradient(circle at 82% 78%, rgba(56, 189, 248, 0.12), transparent 30%);
            pointer-events: none;
        }

        .modern-card {
            transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(2, 6, 23, 0.12);
        }

        .aurora-border::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(120deg, rgba(45, 212, 191, 0.45), rgba(99, 102, 241, 0.45), rgba(14, 165, 233, 0.35));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            opacity: 0.55;
        }

        .floating-slow {
            animation: floatingSlow 7s ease-in-out infinite;
        }

        .floating-delayed {
            animation: floatingSlow 8.5s ease-in-out infinite;
            animation-delay: 1.1s;
        }

        /* Pulse Animation */
        @keyframes custom-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.05);
            }
        }

        .animate-custom-pulse {
            animation: custom-pulse 2s infinite;
        }

        @keyframes floatingSlow {
            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* --- GLOBAL DARK MODE OVERRIDES --- */
        .dark body {
            background-color: #0b0f19 !important;
            /* deep slate-950 */
            color: #cbd5e1 !important;
            /* slate-300 */
        }

        /* Titles and Headings */
        .dark h1,
        .dark h2,
        .dark h3,
        .dark h4,
        .dark h5,
        .dark h6,
        .dark .text-slate-900,
        .dark .text-indigo-950,
        .dark .text-slate-800,
        .dark .text-teal-950,
        .dark .text-slate-950 {
            color: #f8fafc !important;
            /* slate-50 */
        }

        /* Descriptions, metadata and subtexts */
        .dark p,
        .dark .text-slate-600,
        .dark .text-slate-700,
        .dark .text-slate-500,
        .dark label,
        .dark .text-slate-600 *,
        .dark .text-slate-700 * {
            color: #94a3b8 !important;
            /* slate-400 */
        }

        /* White backgrounds (Pills, inputs, cards) */
        .dark .bg-white,
        .dark #omni-search-input,
        .dark .bg-white\/95 {
            background-color: #1e293b !important;
            /* slate-800 */
            color: #cbd5e1 !important;
        }

        /* Specific container cards & articles */
        .dark .bg-white.rounded-3xl,
        .dark .bg-white.rounded-2xl,
        .dark .bg-white.rounded-xl,
        .dark article.bg-white,
        .dark div.bg-white {
            background-color: #111827 !important;
            /* slate-900 */
            border-color: #1f2937 !important;
            /* slate-800 */
        }

        /* Slate sections */
        .dark .bg-slate-50,
        .dark .bg-slate-50\/70,
        .dark .bg-slate-100 {
            background-color: #1e293b !important;
            /* slate-800 */
            color: #cbd5e1 !important;
        }

        /* Form elements */
        .dark input,
        .dark textarea,
        .dark select {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b !important;
        }

        /* Keep homepage top hero visually stable across dark/light themes */
        .dark #home-hero p.text-slate-200 {
            color: #e2e8f0 !important;
        }

        .dark #home-hero #hero-search-shell {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        .dark #home-hero #omni-search-input {
            background: transparent !important;
            color: #ffffff !important;
        }

        .dark #home-hero #omni-search-input::placeholder {
            color: #cbd5e1 !important;
        }

        /* Global borders */
        .dark .border-slate-200,
        .dark .border-slate-200\/80,
        .dark .border-slate-100,
        .dark .border-slate-200\/60,
        .dark .border-slate-300\/80,
        .dark .border-slate-300\/90,
        .dark .border-cyan-100,
        .dark .border-teal-100,
        .dark .border-slate-200\/50,
        .dark .border-slate-100\/50 {
            border-color: #1e293b !important;
            /* slate-800 */
        }

        /* Notification boxes (Amber) */
        .dark .bg-amber-50,
        .dark .bg-amber-50\/80 {
            background-color: #451a03 !important;
            /* amber-950 */
            border-color: #78350f !important;
            color: #fef3c7 !important;
        }

        .dark .text-amber-900,
        .dark .text-amber-950 {
            color: #fcd34d !important;
            /* amber-300 */
        }

        /* Recommendations / Success boxes (Teal) */
        .dark .bg-teal-50,
        .dark .bg-teal-50\/80 {
            background-color: rgba(4, 47, 46, 0.45) !important;
            /* teal-950/45 */
            border-color: rgba(17, 94, 89, 0.4) !important;
            color: #ccfbf1 !important;
        }

        .dark .text-teal-950,
        .dark .text-teal-900,
        .dark .text-teal-700 {
            color: #2dd4bf !important;
        }

        /* Links / Info blocks (Indigo) */
        .dark .bg-cyan-50,
        .dark .bg-cyan-50\/70 {
            background-color: rgba(30, 27, 75, 0.45) !important;
            /* indigo-950/45 */
            border-color: rgba(49, 46, 129, 0.4) !important;
            color: #e0e7ff !important;
        }

        .dark .text-indigo-900,
        .dark .text-indigo-950,
        .dark .text-cyan-700 {
            color: #a5b4fc !important;
        }

        /* Switchers / Controls */
        .dark .bg-white.text-indigo-900 {
            background-color: #312e81 !important;
            color: #ffffff !important;
        }

        .dark #theme-toggle {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }

        .dark #theme-toggle:hover {
            background-color: #334155 !important;
        }

        .theme-toggle-light-label {
            display: none;
        }

        .dark .theme-toggle-dark-label {
            display: none;
        }

        @media (min-width: 640px) {
            .dark .theme-toggle-light-label {
                display: inline;
            }
        }

        /* --- MOBILE MENU DARK MODE --- */
        .dark .mobile-nav-list {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        .dark .mobile-nav-item {
            color: #cbd5e1 !important;
            border-bottom-color: #1f2937 !important;
        }

        .dark .mobile-nav-item:hover {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }

        .dark .mobile-nav-item.active {
            background: linear-gradient(90deg, rgba(20, 184, 166, 0.15), rgba(79, 70, 229, 0.15)) !important;
            color: #2dd4bf !important;
        }

        .dark .mobile-nav-item.active i {
            color: #2dd4bf !important;
        }

        /* --- CHATBOT WIDGET DARK MODE --- */
        .dark .chatbot-window {
            background: linear-gradient(180deg, #111827 0%, #030712 100%) !important;
            border-color: #1f2937 !important;
            color: #cbd5e1 !important;
        }

        .dark #chatbot-messages {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.6) 0%, rgba(3, 7, 18, 0.8) 100%) !important;
        }

        .dark .chatbot-chip {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
            color: #cbd5e1 !important;
            border-color: #374151 !important;
        }

        .dark .chatbot-chip:hover {
            background: linear-gradient(180deg, #115e59 0%, #312e81 100%) !important;
            border-color: #14b8a6 !important;
            color: #ffffff !important;
        }

        .dark .chatbot-chip-danger {
            background: linear-gradient(180deg, #4c0519 0%, #881337 100%) !important;
            border-color: #9f1239 !important;
            color: #ffe4e6 !important;
        }

        .dark .chatbot-chip-danger:hover {
            background: linear-gradient(180deg, #881337 0%, #be123c 100%) !important;
            border-color: #f43f5e !important;
            color: #ffffff !important;
        }

        .dark .chatbot-city-pill {
            background: #1f2937 !important;
            color: #cbd5e1 !important;
            border-color: #374151 !important;
        }

        .dark .chatbot-city-pill:hover {
            background: #374151 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }

        .dark .chatbot-city-pill.active {
            background: linear-gradient(120deg, #14b8a6, #4f46e5) !important;
            border-color: transparent !important;
            color: #ffffff !important;
        }

        .dark .chatbot-action-btn {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #a5b4fc !important;
        }

        .dark .chatbot-action-btn:hover {
            background: #374151 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
        }

        .dark .chatbot-action-btn.active {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
        }

        .lang-hi,
        .lang-hi button,
        .lang-hi input,
        .lang-hi textarea,
        .lang-hi select {
            font-family: 'Noto Sans Devanagari', 'Hind', 'Outfit', sans-serif;
            letter-spacing: 0;
        }

        .chatbot-window {
            box-shadow: 0 24px 64px rgba(15, 23, 42, 0.24);
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-color: #cbd5e1;
        }

        .chatbot-chip {
            background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%);
            color: #0f172a;
            border: 1px solid #bae6fd;
            border-radius: 9999px;
            padding: 0.36rem 0.72rem;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .chatbot-chip:hover {
            background: linear-gradient(180deg, #ecfeff 0%, #dbeafe 100%);
            border-color: #7dd3fc;
            color: #0c4a6e;
            transform: translateY(-1px);
        }

        .chatbot-chip-danger {
            background: linear-gradient(180deg, #fff1f2 0%, #ffe4e6 100%);
            border-color: #fda4af;
            color: #9f1239;
        }

        .chatbot-chip-danger:hover {
            background: linear-gradient(180deg, #ffe4e6 0%, #fecdd3 100%);
            border-color: #fb7185;
            color: #881337;
        }

        .chatbot-city-pill {
            background: #ffffff;
            color: #0f172a;
            border: 1px solid #94a3b8;
            border-radius: 9999px;
            padding: 0.3rem 0.75rem;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .chatbot-city-pill:hover {
            background: #f0f9ff;
            border-color: #38bdf8;
            color: #0c4a6e;
        }

        .chatbot-city-pill.active {
            background: linear-gradient(120deg, #14b8a6, #4f46e5);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        .chatbot-action-btn {
            background: #ffffff;
            border: 1px solid #c7d2fe;
            color: #3730a3;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            transition: all 0.2s ease;
        }

        .chatbot-action-btn:hover {
            background: #eef2ff;
            border-color: #a5b4fc;
            color: #312e81;
        }

        .chatbot-action-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #ffffff;
        }

        #chatbot-form input:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        #chatbot-form button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .typing-dots {
            display: inline-flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: var(--brand-teal);
            opacity: 0.35;
            animation: typingDot 1.1s infinite ease-in-out;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.15s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.3s;
        }

        .mobile-nav-list {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.9rem;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        .mobile-nav-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            padding: 0.78rem 0.9rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .mobile-nav-item:last-child {
            border-bottom: 0;
        }

        .mobile-nav-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .mobile-nav-item.active {
            background: linear-gradient(90deg, #ecfeff, #eef2ff);
            color: #0f766e;
        }

        .mobile-nav-item.active i {
            color: #0d9488;
        }

        @media (max-width: 420px) {
            .brand-wordmark {
                display: none;
            }
        }

        @keyframes typingDot {

            0%,
            80%,
            100% {
                transform: translateY(0);
                opacity: 0.35;
            }

            40% {
                transform: translateY(-4px);
                opacity: 1;
            }
        }

        #chatbot-messages::-webkit-scrollbar {
            width: 5px;
        }

        #chatbot-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        #chatbot-window .bg-gradient-to-r.from-slate-900.via-indigo-900.to-slate-900 {
            background-image: linear-gradient(90deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid #0ea5a6;
            outline-offset: 2px;
        }

        .motion-ready .animate-on-scroll {
            opacity: 0;
            transform: translateY(18px) scale(0.985);
            transition: opacity 520ms ease, transform 520ms ease;
            transition-delay: var(--reveal-delay, 0ms);
            will-change: opacity, transform;
        }

        .motion-ready .animate-on-scroll.in-view {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        @media (prefers-reduced-motion: reduce) {
            .motion-ready .animate-on-scroll {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }

            .floating-slow,
            .floating-delayed,
            .modern-card {
                animation: none !important;
                transition: none !important;
                transform: none !important;
            }

            body.preload-anim * {
                animation: none !important;
                transition: none !important;
            }
        }

        body.preload-anim nav,
        body.preload-anim header,
        body.preload-anim main,
        body.preload-anim footer {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 520ms ease, transform 520ms ease;
        }

        body.preload-anim.page-loaded nav {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 40ms;
        }

        body.preload-anim.page-loaded header {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 110ms;
        }

        body.preload-anim.page-loaded main {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 180ms;
        }

        body.preload-anim.page-loaded footer {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 250ms;
        }

        #chatbot-container {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #chatbot-container.footer-visible {
            transform: translateY(-85px);
        }

        @media (max-width: 639px) {
            #chatbot-container.footer-visible {
                transform: translateY(-70px);
            }
        }

        /* Chatbot FAB premium expand/contract transitions (Global) */
        .chatbot-fab {
            display: inline-flex !important;
            align-items: center;
            height: 56px;
            min-width: 56px;
            padding-left: 24px !important;
            padding-right: 28px !important;
            border-radius: 9999px;
            overflow: visible;
            white-space: nowrap;
            justify-content: flex-start !important;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition: width 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                padding 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                transform 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                box-shadow 0.4s ease,
                opacity 280ms ease,
                visibility 280ms step-end !important;
        }

        .chatbot-fab::before,
        .chatbot-fab::after {
            content: '';
            position: absolute;
            inset: -7px;
            border-radius: 9999px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 240ms ease, transform 320ms ease;
        }

        .chatbot-fab::before {
            z-index: -2;
            background: conic-gradient(from 0deg,
                    rgba(255, 255, 255, 0) 0deg,
                    rgba(34, 211, 238, 0.18) 52deg,
                    rgba(59, 130, 246, 0.56) 138deg,
                    rgba(20, 184, 166, 0.24) 210deg,
                    rgba(255, 255, 255, 0) 310deg,
                    rgba(255, 255, 255, 0) 360deg);
            filter: blur(0.35px);
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 9px), #000 calc(100% - 7px));
            mask: radial-gradient(farthest-side, transparent calc(100% - 9px), #000 calc(100% - 7px));
        }

        .chatbot-fab::after {
            z-index: -3;
            inset: -14px;
            background: radial-gradient(circle at center,
                    rgba(34, 211, 238, 0.24) 0%,
                    rgba(14, 165, 233, 0.16) 32%,
                    rgba(79, 70, 229, 0.08) 52%,
                    rgba(79, 70, 229, 0) 72%);
            filter: blur(8px);
            transform: scale(0.92);
        }

        .chatbot-fab.chatbot-fab-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(10px) scale(0.97);
            transition: width 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                padding 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                transform 280ms ease,
                box-shadow 0.4s ease,
                opacity 280ms ease,
                visibility 280ms step-start !important;
        }

        .chatbot-fab-label {
            display: inline-block;
            max-width: 280px;
            /* Enough to display 'Ask Swasthya Saathi' or 'स्वास्थ्य साथी से पूछें' */
            margin-left: 12px;
            opacity: 1;
            overflow: hidden;
            white-space: nowrap;
            transform: translateX(0);
            transition: max-width 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                margin-left 460ms cubic-bezier(0.22, 0.65, 0.22, 1),
                opacity 320ms cubic-bezier(0.22, 0.65, 0.22, 1),
                transform 460ms cubic-bezier(0.22, 0.65, 0.22, 1);
        }

        .chatbot-fab.fab-contracted {
            width: 56px;
            min-width: 56px;
            padding-left: 0 !important;
            padding-right: 0 !important;
            justify-content: center !important;
            gap: 0 !important;
        }

        .chatbot-fab.fab-contracted::before,
        .chatbot-fab.fab-contracted::after {
            opacity: 1;
        }

        .chatbot-fab.fab-contracted::before {
            animation: chatbotWhirlpoolSpin 5.8s linear infinite;
        }

        .chatbot-fab.fab-contracted::after {
            animation: chatbotWhirlpoolPulse 3.2s ease-in-out infinite;
        }

        .chatbot-fab.fab-contracted .chatbot-fab-label {
            max-width: 0;
            margin-left: 0;
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }

        .chatbot-fab.fab-contracted .chatbot-fab-icon {
            animation: none !important;
            margin: 0 auto !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chatbot-fab:hover::before,
        .chatbot-fab:focus-visible::before {
            animation-duration: 4.2s;
        }

        .chatbot-fab:hover::after,
        .chatbot-fab:focus-visible::after {
            transform: scale(1);
            opacity: 1;
        }

        @keyframes chatbotWhirlpoolSpin {
            0% {
                transform: rotate(0deg) scale(0.98);
            }

            50% {
                transform: rotate(180deg) scale(1.04);
            }

            100% {
                transform: rotate(360deg) scale(0.98);
            }
        }

        @keyframes chatbotWhirlpoolPulse {
            0%,
            100% {
                transform: scale(0.9);
                opacity: 0.52;
            }

            50% {
                transform: scale(1.08);
                opacity: 0.82;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .chatbot-fab::before,
            .chatbot-fab::after {
                animation: none !important;
                transition: none !important;
            }
        }

        @media (max-width: 639px) {
            #chatbot-container {
                right: 0.75rem;
                bottom: calc(0.75rem + env(safe-area-inset-bottom));
            }

            #chatbot-window {
                width: calc(100vw - 1rem);
                max-width: 430px;
                height: calc(100dvh - 1.5rem - env(safe-area-inset-bottom));
                max-height: calc(100dvh - 1.5rem - env(safe-area-inset-bottom));
                min-height: 0;
                border-bottom-right-radius: 0.5rem;
                border-bottom-left-radius: 0.5rem;
                position: fixed;
                left: 50%;
                right: auto;
                transform: translateX(-50%);
                top: 0.75rem;
                bottom: calc(0.75rem + env(safe-area-inset-bottom));
                margin: 0;
            }
        }



        /* Hospital Compare Custom CSS */
        #compare-dock {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 60;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.08);
        }

        #compare-dock.show {
            transform: translateY(0);
        }

        .dark #compare-dock {
            border-top-color: rgba(51, 65, 85, 0.8);
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.35);
        }

        .compare-thumb {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #14b8a6 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.8rem;
        }

        .compare-btn-card {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.5rem 0.875rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid rgba(226, 232, 240, 0.8);
            background: #ffffff;
            color: #475569;
        }

        .compare-btn-card:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .compare-btn-card.active {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .dark .compare-btn-card {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        .dark .compare-btn-card:hover {
            background: #334155;
            border-color: #475569;
        }

        .dark .compare-btn-card.active {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            color: #ffffff !important;
        }

    </style>
    @stack('scripts')
</body>

</html>





