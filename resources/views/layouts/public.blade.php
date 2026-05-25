<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
    $appName = config('app.name', 'SwasthyaSearch');
    $siteUrl = rtrim(config('app.url', url('/')), '/');
    $currentUrl = url()->current();
    $hasQuery = request()->getQueryString() !== null;
    $locale = session('locale', app()->getLocale());
    $isHindi = $locale === 'hi';

    $defaultTitle = $isHindi
    ? 'SwasthyaSearch - डॉक्टर, अस्पताल और ब्लड बैंक खोजें'
    : 'SwasthyaSearch - Find Doctors, Hospitals, and Blood Banks';
    $defaultDescription = $isHindi
    ? 'SwasthyaSearch पर अपने शहर में सत्यापित डॉक्टर, अस्पताल, क्लिनिक और ब्लड बैंक खोजें।'
    : 'Find verified doctors, hospitals, clinics, blood banks, and health articles near you on SwasthyaSearch.';

    $routeName = request()->route()?->getName() ?? '';
    $routeSeo = [
    'home' => [
    'title' => $isHindi ? 'SwasthyaSearch - अपने पास विश्वसनीय स्वास्थ्य सेवा खोजें' : 'SwasthyaSearch - Trusted Healthcare Discovery Near You',
    'description' => $isHindi ? 'लक्षण, विभाग, शहर या नाम से डॉक्टर, अस्पताल और ब्लड बैंक खोजें।' : 'Search doctors, hospitals, blood banks, and departments by symptom, city, or keyword.',
    ],
    'doctors.index' => [
    'title' => $isHindi ? 'डॉक्टर्स डायरेक्टरी | SwasthyaSearch' : 'Doctors Directory | SwasthyaSearch',
    'description' => $isHindi ? 'अपने शहर में सत्यापित विशेषज्ञ डॉक्टर खोजें।' : 'Browse verified specialist doctors by city, department, and experience.',
    ],
    'hospitals.index' => [
    'title' => $isHindi ? 'अस्पताल और क्लिनिक डायरेक्टरी | SwasthyaSearch' : 'Hospitals & Clinics Directory | SwasthyaSearch',
    'description' => $isHindi ? 'अपने शहर के अस्पताल और क्लिनिक खोजें।' : 'Find verified hospitals and clinics with location and contact details.',
    ],
    'blood_banks.index' => [
    'title' => $isHindi ? 'ब्लड बैंक डायरेक्टरी | SwasthyaSearch' : 'Blood Banks Directory | SwasthyaSearch',
    'description' => $isHindi ? 'अपने शहर में ब्लड बैंक खोजें और उपलब्धता फोन पर पुष्टि करें।' : 'Find blood banks by city and blood group. Call to confirm current availability.',
    ],
    'articles.index' => [
    'title' => $isHindi ? 'स्वास्थ्य लेख | SwasthyaSearch' : 'Health Articles | SwasthyaSearch',
    'description' => $isHindi ? 'स्वास्थ्य, पोषण और वेलनेस पर उपयोगी लेख पढ़ें।' : 'Read useful health, wellness, and medical awareness articles.',
    ],
    'about' => [
    'title' => $isHindi ? 'हमारे बारे में | SwasthyaSearch' : 'About Us | SwasthyaSearch',
    'description' => $isHindi ? 'SwasthyaSearch का मिशन: भरोसेमंद हेल्थकेयर खोज को सरल बनाना।' : 'Learn about SwasthyaSearch and our mission for transparent healthcare discovery.',
    ],
    'contact' => [
    'title' => $isHindi ? 'संपर्क करें | SwasthyaSearch' : 'Contact Us | SwasthyaSearch',
    'description' => $isHindi ? 'SwasthyaSearch सहायता और प्रतिक्रिया के लिए संपर्क करें।' : 'Contact SwasthyaSearch for support, corrections, and feedback.',
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
    $metaKeywords = trim($__env->yieldContent('meta_keywords', 'doctors directory, hospitals directory, blood banks, healthcare search, medical specialists'));
    $ogImage = trim($__env->yieldContent('og_image', $siteUrl . '/favicon.ico'));
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
                'logo' => $siteUrl.
                '/favicon.ico',
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
    'doctors.index' => $isHindi ? 'डॉक्टर्स' : 'Doctors',
    'hospitals.index' => $isHindi ? 'अस्पताल' : 'Hospitals',
    'blood_banks.index' => $isHindi ? 'ब्लड बैंक' : 'Blood Banks',
    'articles.index' => $isHindi ? 'लेख' : 'Articles',
    'articles.show' => $isHindi ? 'लेख विवरण' : 'Article',
    'about' => $isHindi ? 'हमारे बारे में' : 'About',
    'contact' => $isHindi ? 'संपर्क' : 'Contact',
    'privacy.policy' => $isHindi ? 'गोपनीयता नीति' : 'Privacy Policy',
    'terms.service' => $isHindi ? 'सेवा शर्तें' : 'Terms of Service',
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
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-[#F8FAFC] dark:bg-slate-950 font-sans antialiased text-[#2D3748] dark:text-slate-100 min-h-screen flex flex-col selection:bg-teal-500 selection:text-white relative overflow-x-hidden {{ session('locale', app()->getLocale()) === 'hi' ? 'lang-hi' : '' }}">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full bg-gradient-to-tr from-teal-400/20 to-emerald-400/20 dark:from-teal-500/10 dark:to-emerald-500/10 blur-[100px] opacity-75"></div>
        <div class="absolute top-1/4 -right-40 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-indigo-400/20 to-purple-400/20 dark:from-indigo-500/10 dark:to-purple-500/10 blur-[120px] opacity-75"></div>
        <div class="absolute -bottom-40 left-1/4 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-cyan-400/15 to-teal-400/15 dark:from-cyan-500/5 dark:to-teal-500/5 blur-[100px] opacity-75"></div>
    </div>
    @php
    $locale = session('locale', app()->getLocale());
    $chatbotCities = \App\Models\Hospital::where('is_verified', true)
    ->whereNotNull('city')
    ->distinct()
    ->orderBy('city')
    ->pluck('city');
    $chatbotCityPills = $chatbotCities->take(18);
    @endphp

    <!-- Header Navbar -->
    <nav class="sticky top-0 z-50 glass-panel shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between min-h-16 py-2 items-center gap-2 sm:gap-4">
                <div class="flex items-center min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-1.5 sm:space-x-3 group mr-1 sm:mr-6 shrink min-w-0">
                        <div class="p-2 sm:p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5 shrink-0">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white animate-pulse"></i>
                        </div>
                        <span class="brand-wordmark text-lg sm:text-2xl font-bold bg-gradient-to-r from-slate-800 to-indigo-900 dark:from-slate-100 dark:to-indigo-300 bg-clip-text text-transparent tracking-tight py-1 leading-normal truncate">
                            Swasthya<span class="text-teal-600 dark:text-teal-400">Search</span>
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

                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-slate-200/60 dark:border-slate-700/60 shadow-inner">
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'en' ? 'bg-white dark:bg-slate-700 text-indigo-900 dark:text-indigo-200 shadow-sm font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">English</span><span class="sm:hidden font-bold">EN</span></span>
                            </button>
                        </form>
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="hi">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'hi' ? 'bg-white dark:bg-slate-700 text-indigo-900 dark:text-indigo-200 shadow-sm font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">हिंदी</span><span class="sm:hidden font-bold">HI</span></span>
                            </button>
                        </form>
                    </div>
                    <button id="theme-toggle" type="button" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 flex items-center justify-center shadow-xs" aria-label="Toggle dark mode">
                        <i id="theme-toggle-dark-icon" data-lucide="moon" class="w-4 h-4 hidden"></i>
                        <i id="theme-toggle-light-icon" data-lucide="sun" class="w-4 h-4 hidden"></i>
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
                        <span>{{ $locale === 'hi' ? 'संपर्क करें' : 'Contact' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <div id="chatbot-mobile-overlay" class="hidden fixed inset-0 bg-slate-950/45 backdrop-blur-[1px] z-[70] sm:hidden" onclick="toggleChatbot()"></div>
    <!-- Floating Chatbot Widget -->
    <div class="fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-[80]" id="chatbot-container">
        <!-- Chat Button -->
        <button id="chatbot-toggle-btn" aria-label="Open AI assistant" onclick="toggleChatbot()" class="chatbot-fab flex items-center gap-3 bg-gradient-to-tr from-teal-500 to-indigo-600 text-white px-6 py-3.5 rounded-full shadow-2xl hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300 transform group ring-1 ring-white/20">
            <div class="chatbot-fab-icon w-6 h-6 flex items-center justify-center shrink-0 animate-bounce group-hover:animate-none">
                <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
            </div>
            <span id="chatbot-fab-label" class="chatbot-fab-label font-bold text-base tracking-wide whitespace-nowrap leading-none pt-0.5">
                {{ $locale === 'hi' ? 'स्वास्थ्य साथी से पूछें' : 'Ask Swasthya Saathi' }}
            </span>
        </button>

        <!-- Chat Window -->
        <div id="chatbot-window" class="hidden w-[94vw] sm:w-[420px] h-[74vh] max-h-[680px] min-h-[520px] bg-white dark:bg-slate-950 rounded-3xl shadow-2xl border border-slate-300/90 dark:border-slate-800 flex flex-col overflow-hidden animate-in fade-in duration-300 chatbot-window">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 text-white p-4 flex justify-between items-start shadow-md">
                <div class="flex items-start space-x-3 min-w-0 pr-2">
                    <div class="p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30">
                        <i data-lucide="bot" class="w-6 h-6 text-teal-400"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[17px] leading-tight text-white">Swasthya Saathi</h3>
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
            </div>

            <div class="px-3.5 py-2 bg-slate-50/90 dark:bg-slate-900/90 border-t border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="flex items-center justify-between gap-2 min-h-[36px] w-full text-xs">
                    <!-- Dropdown Selection State (visible when not locked) -->
                    <div id="chatbot-city-select-wrapper" class="flex-1 flex items-center gap-1.5 min-w-0">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 shrink-0"></i>
                        <select id="chatbot-city-selector" onchange="selectChatbotCity(this.value)" class="w-full bg-white dark:bg-slate-800 border border-slate-350 dark:border-slate-700 rounded-lg px-2 py-1.5 text-slate-850 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold transition-all">
                            <option value="">{{ $locale === 'hi' ? 'शहर चुनें...' : 'Select city...' }}</option>
                            @foreach($chatbotCities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Locked Selected State (visible when locked) -->
                    <div id="chatbot-city-locked-wrapper" class="hidden flex-1 items-center justify-between min-w-0">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-600 shrink-0 animate-custom-pulse"></i>
                            <span class="text-slate-500 mr-1 shrink-0 font-medium">{{ $locale === 'hi' ? 'शहर:' : 'City:' }}</span>
                            <span id="chatbot-selected-city-label" class="font-extrabold text-slate-900 dark:text-slate-100 truncate"></span>
                        </div>
                        <button type="button" id="chatbot-change-city-btn" onclick="enableCitySelection()" class="shrink-0 text-[11px] font-bold text-indigo-700 hover:text-indigo-850 dark:text-indigo-400 dark:hover:text-indigo-350 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 rounded-lg px-2.5 py-1.5 leading-none transition-all">
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
                        <div id="chatbot-initial-message" class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed bg-white text-slate-800 border border-slate-200/60 rounded-tl-none">
                            {{ $locale === 'hi' ? 'नमस्ते, मैं Swasthya AI Assistant हूँ। मैं लक्षण, विभाग, डॉक्टर, अस्पताल और ब्लड बैंक खोजने में मदद करता हूँ।' : 'Hi, I’m Swasthya AI Assistant. I help you find departments, doctors, hospitals, and blood banks based on your needs.' }}
                        </div>
                    </div>
                </div>
                <div class="ml-9 max-w-[85%] rounded-xl border border-teal-100 bg-teal-50/80 px-3 py-2">
                    <p class="text-[11px] font-semibold text-teal-900">
                        {{ $locale === 'hi' ? 'उदाहरण: "Nearby में cardiologist", "नजदीकी hospital", "A+ blood bank"' : 'Try: "cardiologist near me", "nearby hospital", "A+ blood bank"' }}
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
                        <div class="rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2">
                            <p class="text-[11px] font-bold text-cyan-900 mb-1.5">
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
            <div id="chatbot-loading" class="hidden px-4 py-2.5 flex space-x-2.5 items-center text-slate-700 text-sm bg-indigo-50/70 border-y border-indigo-100">
                <div class="typing-dots" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <span id="chatbot-loading-text">{{ $locale === 'hi' ? 'स्वास्थ्य AI सोच रहा है...' : 'Swasthya AI is thinking...' }}</span>
            </div>
            <div class="px-3 pb-2 bg-amber-50 border-t border-amber-200">
                <button
                    type="button"
                    id="chatbot-important-toggle"
                    onclick="toggleChatbotImportant()"
                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-900 bg-white border border-amber-300 rounded-full px-2.5 py-1"
                    aria-label="Show important assistant details">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-amber-600 text-white text-[10px] font-bold">i</span>
                    <span>{{ $locale === 'hi' ? 'महत्वपूर्ण जानकारी' : 'Important details' }}</span>
                </button>
                <div id="chatbot-important-details" class="hidden mt-2 text-[11px] leading-relaxed text-amber-950 bg-white border border-amber-200 rounded-xl px-3 py-2">
                    {{ $locale === 'hi' ? 'यह सहायक निदान या उपचार नहीं देता। गंभीर या आपातकालीन लक्षण होने पर तुरंत नजदीकी अस्पताल जाएँ और योग्य चिकित्सा विशेषज्ञ से सलाह लें।' : 'This assistant does not provide diagnosis or treatment. For severe or urgent symptoms, visit the nearest hospital immediately and consult a qualified healthcare professional.' }}
                </div>
            </div>
            <!-- Input Footer -->
            <form id="chatbot-form" onsubmit="handleChatbotSubmit(event)" class="p-3 bg-slate-50 border-t border-slate-200/80 flex items-center space-x-2 shadow-lg">
                <input type="text" id="chatbot-input" placeholder="{{ $locale === 'hi' ? 'लक्षण लिखें या डॉक्टर, अस्पताल, ब्लड बैंक खोजें...' : 'Describe symptoms or search doctors, hospitals, blood banks...' }}" class="flex-1 bg-white border border-slate-300/80 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200">
                <button type="button" id="chatbot-voice-btn" onclick="toggleVoiceTyping()" class="bg-teal-600 hover:bg-teal-500 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95" title="{{ $locale === 'hi' ? 'वॉइस टाइपिंग चालू/बंद करें' : 'Start/Stop voice typing' }}">
                    <i data-lucide="mic" class="w-5 h-5"></i>
                </button>
                <button type="submit" aria-label="Send message" class="bg-indigo-600 hover:bg-indigo-500 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-950 text-white border-t border-slate-800 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="p-2 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl shadow-md">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight">Swasthya<span class="text-teal-400">Search</span></span>
                    </div>
                    <p class="text-sm text-slate-300">
                        {{ $locale === 'hi' ? 'SwasthyaSearch उपयोगकर्ताओं को स्वास्थ्य सेवा प्रदाता खोजने में मदद करता है। विवरण बदल सकते हैं, कृपया जाने से पहले कॉल करें।' : 'SwasthyaSearch helps users find healthcare providers. Please call before visiting as details may change.' }}
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'प्लेटफ़ॉर्म' : 'Platform' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('about') }}" class="block hover:text-white">About</a>
                        <a href="{{ route('contact') }}" class="block hover:text-white">Contact</a>
                        <a href="{{ route('articles.index') }}" class="block hover:text-white">Articles</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'हेल्थकेयर डायरेक्टरी' : 'Healthcare Directory' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('doctors.index') }}" class="block hover:text-white">Doctors</a>
                        <a href="{{ route('hospitals.index') }}" class="block hover:text-white">Hospitals</a>
                        <a href="{{ route('blood_banks.index') }}" class="block hover:text-white">Blood Banks</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-slate-100">{{ $locale === 'hi' ? 'सहायता व कानूनी' : 'Support & Legal' }}</h4>
                    <div class="space-y-2 text-sm text-slate-400">
                        <a href="{{ route('contact') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'सहायता' : 'Support' }}</a>
                        <a href="{{ route('privacy.policy') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}</a>
                        <a href="{{ route('terms.service') }}" class="block hover:text-white">{{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }}</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-800 text-xs text-slate-400 text-center md:text-left">
                {{ $locale === 'hi' ? '© 2026 SwasthyaSearch. मरीजों के लिए निःशुल्क, भरोसेमंद और विज्ञापन-मुक्त हेल्थकेयर खोज मंच।' : '© 2026 SwasthyaSearch. A free, trustworthy, ad-free healthcare discovery platform.' }}
            </div>
        </div>
    </footer>

    <!-- Compare Dock -->
    <div id="compare-dock" class="glass-panel py-4 px-6 z-[60] flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-650/10 dark:bg-indigo-400/15 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
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
            <button onclick="openCompareModal()" class="w-1/2 sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-lg hover:shadow-indigo-500/20 transition-all uppercase tracking-wider">
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
                    <div class="p-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-xl">
                        <i data-lucide="git-compare" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'विस्तृत तुलना मैट्रिक्स' : 'Detailed Comparison Matrix' }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'सर्वोत्तम विकल्प चुनने के लिए सुविधाओं की तुलना करें' : 'Compare metrics side-by-side to make the best choice' }}</p>
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
        lucide.createIcons();

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
                    alert(currentLocale === 'hi' ? 'आप एक बार में केवल 3 अस्पतालों की तुलना कर सकते हैं।' : 'You can only compare up to 3 hospitals at a time.');
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
                        textSpan.innerText = currentLocale === 'hi' ? 'हटाएं' : 'Added';
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
                lucide.createIcons();
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
            let headHtml = `<th class="p-4 text-left text-xs font-bold text-slate-450 uppercase tracking-wider bg-slate-50 dark:bg-slate-900/50">${currentLocale === 'hi' ? 'विशेषताएं' : 'Features'}</th>`;
            comparedHospitals.forEach(h => {
                headHtml += `
                    <th class="p-4 text-left bg-slate-50 dark:bg-slate-900/50 min-w-[200px]">
                        <div class="flex items-start gap-2.5">
                            <div class="compare-thumb shrink-0 shadow-2xs">${h.name.substring(0, 2).toUpperCase()}</div>
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white line-clamp-2">${h.name}</h4>
                                <span class="inline-block text-[10px] font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 px-2 py-0.5 rounded-md mt-1 uppercase tracking-wider">${h.type}</span>
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
                                    ${currentLocale === 'hi' ? 'हटाएं' : 'Remove'}
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
            lucide.createIcons();
        }

        function closeCompareModal() {
            const modal = document.getElementById('compare-modal');
            if (modal) modal.classList.add('hidden');
        }

        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function updateThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
                if (themeToggleDarkIcon) themeToggleDarkIcon.classList.add('hidden');
                if (themeToggleBtn) themeToggleBtn.setAttribute('aria-label', 'Switch to light mode');
            } else {
                if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
                if (themeToggleLightIcon) themeToggleLightIcon.classList.add('hidden');
                if (themeToggleBtn) themeToggleBtn.setAttribute('aria-label', 'Switch to dark mode');
            }
        }

        // Initialize UI icon based on current class
        updateThemeUI();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateThemeUI();
            });
        }

        // Chatbot Logic
        let chatbotOpen = false;
        let chatbotSessionToken = null;
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
        const CHATBOT_CITY_STORAGE_KEY = 'swasthya_selected_city';
        const LEGACY_CHATBOT_CITY_STORAGE_KEY = 'swasthyasearch_chatbot_city';
        const CHATBOT_CITY_ONBOARDED_KEY = 'swasthya_chatbot_city_onboarded';

        function hasCompletedCityOnboarding() {
            return localStorage.getItem(CHATBOT_CITY_ONBOARDED_KEY) === '1';
        }

        function markCityOnboardingComplete() {
            localStorage.setItem(CHATBOT_CITY_ONBOARDED_KEY, '1');
        }

        function normalizeCityValue(city) {
            return String(city || '').trim().replace(/\s+/g, ' ');
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
            localStorage.setItem(LEGACY_CHATBOT_CITY_STORAGE_KEY, normalized);
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
            const fromStorage = normalizeCityValue(localStorage.getItem(CHATBOT_CITY_STORAGE_KEY) || localStorage.getItem(LEGACY_CHATBOT_CITY_STORAGE_KEY));
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
            button.dataset.moreLabel = button.dataset.moreLabel || (chatbotLocale === 'hi' ? 'इसके बारे में और जानें' : 'Know more about this');
            button.dataset.lessLabel = button.dataset.lessLabel || (chatbotLocale === 'hi' ? 'कम दिखाएं' : 'Show less');
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

        function collapseMobileFab() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;
            if (window.innerWidth < 640) {
                btn.classList.add('fab-collapsed');
            } else {
                btn.classList.remove('fab-collapsed');
            }
        }

        function setupFabHintCycle() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;
            if (window.innerWidth >= 640) return;

            btn.classList.remove('fab-collapsed');
            setTimeout(() => {
                if (!chatbotOpen) collapseMobileFab();
            }, 2800);
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-nav-menu');
            if (!menu) return;
            menu.classList.toggle('hidden');
            updateMobileMenuIcon();
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

        function refreshChatbotCityUI() {
            const selectWrapper = document.getElementById('chatbot-city-select-wrapper');
            const lockedWrapper = document.getElementById('chatbot-city-locked-wrapper');
            const selectedLabel = document.getElementById('chatbot-selected-city-label');
            const citySelector = document.getElementById('chatbot-city-selector');
            const quickPromptsWrap = document.getElementById('chatbot-quick-prompts-wrap');
            const input = document.getElementById('chatbot-input');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            if (!selectWrapper || !lockedWrapper || !selectedLabel || !citySelector || !quickPromptsWrap) return;

            citySelector.value = chatbotCity;

            if (isCityLocked && chatbotCity) {
                selectedLabel.textContent = chatbotCity;
                selectWrapper.classList.add('hidden');
                lockedWrapper.classList.remove('hidden');
                lockedWrapper.classList.add('flex');
                quickPromptsWrap.classList.remove('hidden');
                if (input) {
                    input.disabled = false;
                    input.placeholder = chatbotLocale === 'hi' ? 'लक्षण बताएं या डॉक्टर, अस्पताल, ब्लड बैंक खोजें...' : 'Describe symptoms or search doctors, hospitals, blood banks...';
                }
                if (sendBtn) sendBtn.disabled = false;
                if (voiceBtn) voiceBtn.disabled = false;
                scrollToChatBottom();
            } else {
                selectedLabel.textContent = '';
                selectWrapper.classList.remove('hidden');
                selectWrapper.classList.add('flex');
                lockedWrapper.classList.add('hidden');
                quickPromptsWrap.classList.add('hidden');
                if (input) {
                    input.disabled = true;
                    input.placeholder = chatbotLocale === 'hi' ? 'पहले शहर चुनें...' : 'Select city first...';
                }
                if (sendBtn) sendBtn.disabled = true;
                if (voiceBtn) voiceBtn.disabled = true;
            }
        }
        function getInitialChatbotMessage() {
            if (chatbotCity) {
                return chatbotLocale === 'hi' ?
                    `वापस स्वागत है। आपका चुना हुआ शहर ${chatbotCity} है। आप क्या खोजना चाहते हैं?` :
                    `Welcome back. Your selected city is ${chatbotCity}. What would you like to find?`;
            }
            return chatbotLocale === 'hi'
                ? 'नमस्ते, मैं Swasthya AI Assistant हूँ। मैं आपकी जरूरत के आधार पर विभाग, डॉक्टर, अस्पताल और ब्लड बैंक ढूंढने में मदद करता हूँ। कृपया पहले अपना शहर चुनें।'
                : 'Hi, I\u2019m Swasthya AI Assistant. I help you find departments, doctors, hospitals, and blood banks based on your needs.';
        }
        function initializeChatbotCity() {
            const onboardingDone = hasCompletedCityOnboarding();
            const selectedCity = onboardingDone ? resolveSelectedCity() : '';
            if (selectedCity && onboardingDone) {
                chatbotCity = selectedCity;
                isCityLocked = true;
                setCityStorage(chatbotCity);
                syncCityDropdowns(chatbotCity);
            } else {
                chatbotCity = '';
                isCityLocked = false;
            }
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
                        lucide.createIcons();
                        scrollToChatBottom();
                    }
                } catch (e) {
                    console.error("Error loading chat history:", e);
                }
            }
        }

        function toggleChatbot() {
            chatbotOpen = !chatbotOpen;
            const btn = document.getElementById('chatbot-toggle-btn');
            const win = document.getElementById('chatbot-window');
            const overlay = document.getElementById('chatbot-mobile-overlay');
            if (chatbotOpen) {
                btn.classList.add('hidden');
                win.classList.remove('hidden');
                if (window.innerWidth < 640) {
                    overlay?.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
                document.getElementById('chatbot-input').focus();
                setTimeout(handleChatbotKeyboardViewport, 80);
                scrollToChatBottom();
            } else {
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
                btn.classList.remove('hidden');
                win.classList.add('hidden');
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
                    `चैट साफ की गई। आपका चुना हुआ शहर ${chatbotCity} है। बताइए क्या खोजना है?` :
                    `Chat cleared. Your selected city is ${chatbotCity}. What would you like to find?`) :
                (chatbotLocale === 'hi' ?
                    'चैट साफ की गई। कृपया शहर चुनें और आगे बढ़ें।' :
                    'Chat cleared. Please choose a city to continue.')
            );
            refreshChatbotCityUI();
        }

        function toggleChatbotImportant() {
            const details = document.getElementById('chatbot-important-details');
            if (!details) return;
            details.classList.toggle('hidden');
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
            const win = document.getElementById('chatbot-window');
            const viewport = window.visualViewport;
            if (!win || !viewport) return;

            const keyboardHeight = Math.max(0, window.innerHeight - viewport.height - viewport.offsetTop);
            if (keyboardHeight > 90) {
                const safeHeight = Math.max(340, Math.floor(viewport.height - 20));
                win.style.height = `${safeHeight}px`;
                win.style.maxHeight = `${safeHeight}px`;
                win.style.minHeight = `${Math.min(520, safeHeight)}px`;
                win.style.bottom = `${Math.max(8, keyboardHeight + 8)}px`;
            } else {
                win.style.height = '';
                win.style.maxHeight = '';
                win.style.minHeight = '';
                win.style.bottom = '';
            }
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

            input.addEventListener('blur', () => {
                setTimeout(() => {
                    const win = document.getElementById('chatbot-window');
                    if (win) {
                        win.style.height = '';
                        win.style.maxHeight = '';
                        win.style.minHeight = '';
                        win.style.bottom = '';
                    }
                }, 140);
            });

            if (window.visualViewport) {
                window.visualViewport.addEventListener('resize', handleChatbotKeyboardViewport);
                window.visualViewport.addEventListener('scroll', handleChatbotKeyboardViewport);
            }
            window.addEventListener('orientationchange', handleChatbotKeyboardViewport);
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
                btn.title = chatbotLocale === 'hi' ? 'वॉइस टाइपिंग रोकें' : 'Stop voice typing';
            } else {
                btn.classList.remove('bg-rose-600', 'hover:bg-rose-500');
                btn.classList.add('bg-teal-600', 'hover:bg-teal-500');
                btn.title = chatbotLocale === 'hi' ? 'वॉइस टाइपिंग चालू करें' : 'Start voice typing';
            }
        }

        function toggleVoiceTyping() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                appendMessage('bot', chatbotLocale === 'hi' ?
                    'आपके ब्राउज़र में वॉइस टाइपिंग समर्थित नहीं है। कृपया Chrome/Edge का उपयोग करें।' :
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

            if (!chatbotCity) {
                if (!hasCityPromptVisible) {
                    appendMessage('bot', chatbotLocale === 'hi' ? 'कृपया पहले शहर चुनें ताकि मैं नजदीकी सही परिणाम दिखा सकूं।' : 'Please select your city so I can show relevant nearby results.');
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
                    lucide.createIcons();
                    scrollToChatBottom();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;
                appendMessage('bot', chatbotLocale === 'hi' ? 'कुछ तकनीकी समस्या हुई। कृपया दोबारा प्रयास करें।' : 'Something went wrong. Please try again.');
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
                loaderMsg = chatbotLocale === 'hi'
                    ? "आपके पास के सर्वश्रेष्ठ विशेषज्ञों की खोज की जा रही है..."
                    : "Searching for best specialist near you...";
            } else if (type === 'hospitals') {
                loaderMsg = chatbotLocale === 'hi'
                    ? "आपके पास के अस्पतालों की खोज की जा रही है..."
                    : "Finding hospitals which is near to you...";
            } else if (type === 'articles') {
                loaderMsg = chatbotLocale === 'hi'
                    ? "आपकी सहायता कर सकने वाले लेखों की खोज की जा रही है..."
                    : "Finding articles which may help you...";
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

                const data = await res.json();
                if (data.session_token) chatbotSessionToken = data.session_token;

                if (loadingDiv) loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;

                if (loadingText) {
                    loadingText.textContent = chatbotLocale === 'hi' ? 'स्वास्थ्य AI सोच रहा है...' : 'Swasthya AI is thinking...';
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
                    lucide.createIcons();
                    scrollToChatBottom();
                }
            } catch (error) {
                if (error?.name === 'AbortError') return;
                if (loadingDiv) loadingDiv.classList.add('hidden');
                if (sendBtn) sendBtn.disabled = false;
                isSubmittingChat = false;
                pendingChatAbortController = null;
                if (loadingText) {
                    loadingText.textContent = chatbotLocale === 'hi' ? 'स्वास्थ्य AI सोच रहा है...' : 'Swasthya AI is thinking...';
                }
                appendMessage('bot', chatbotLocale === 'hi' ? 'कुछ तकनीकी समस्या हुई। कृपया दोबारा प्रयास करें।' : 'Something went wrong. Please try again.');
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
            lucide.createIcons();
            scrollToChatBottom();
        }

        function appendMessageObj(msg, isLastBotMsg = true, history = [], idx = -1) {
            const messagesDiv = document.getElementById('chatbot-messages');
            const isUser = msg.sender === 'user';

            const spoken = String(msg.text || '').replace(/\*\*/g, '').replace(/[*_\-`]/g, '').replace(/'/g, '&#39;').replace(/\"/g, '&quot;');
            const isWarning = !isUser && /(emergency|urgent|call|आपात|तुरंत|helpline)/i.test(String(msg.text || ''));
            let html = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200">
                    <div class="flex space-x-2 max-w-[85%] ${isUser ? 'flex-row-reverse space-x-reverse' : 'flex-row'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${isUser ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'}">
                            <i data-lucide="${isUser ? 'user' : 'bot'}" class="w-4 h-4"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${isUser ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-tr-none border border-indigo-400/50' : isWarning ? 'bg-amber-50 text-amber-900 border border-amber-200 rounded-tl-none' : 'bg-white/95 text-slate-800 border border-slate-200/70 rounded-tl-none backdrop-blur-sm'}">
                                ${isUser ? escapeHtml(msg.text) : formatMessageText(msg.text)}
                            </div>
                            <div class="flex flex-wrap gap-2 items-center">
                                ${!isUser ? `<button type="button" onclick="speakText('${spoken}')" class="inline-flex items-center gap-1 text-[11px] text-slate-500 hover:text-teal-600 text-left px-2.5 py-1 rounded-lg hover:bg-teal-50 border border-slate-200/60 bg-white transition-all">🔊 ${chatbotLocale === 'hi' ? 'सुनें' : 'Listen'}</button>` : ''}
                                ${(!isUser && msg.suggest_details && isLastBotMsg) ? `<button type="button" onclick="submitChatbotMessage('${chatbotLocale === 'hi' ? 'कृपया विस्तार से समझाएं' : 'Please explain in detail'}')" class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-700 hover:text-indigo-900 border border-indigo-200 hover:border-indigo-300 bg-white hover:bg-indigo-50 rounded-lg px-2.5 py-1 transition-all">💬 ${chatbotLocale === 'hi' ? 'विस्तार से समझाएं' : 'Explain in Detail'}</button>` : ''}
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
                                        <button type="button" onclick="loadChatbotResource('hospitals')" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-indigo-700 hover:text-indigo-900 border border-indigo-200 hover:border-indigo-300 bg-white dark:bg-slate-900 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-slate-800/80 hover:bg-indigo-50 rounded-lg px-2.5 py-1.5 transition-all">
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
                        <div class="bg-teal-50/80 border border-teal-100 p-3.5 rounded-2xl text-teal-950 text-xs shadow-xs mt-2 flex items-start space-x-2.5">
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
                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-700 hover:text-indigo-900 border border-indigo-200 hover:border-indigo-300 bg-white hover:bg-indigo-50 rounded-full px-3 py-1 transition-all"
                            >
                                <i data-lucide="info" class="w-3 h-3"></i>
                                <span>${moreLabel}</span>
                            </button>
                            <div id="${contentId}" class="hidden mt-2 rounded-xl border border-indigo-100 bg-indigo-50/70 p-3 text-xs leading-relaxed text-slate-700">
                                ${formatDetailedAnswer(detailedAnswer)}
                            </div>
                        </div>
                    `;
                }

                // Render Doctors list directly (visible by default) if present
                if (msg.doctors && msg.doctors.length > 0) {
                    html += `
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'विशेषज्ञ डॉक्टर' : 'Specialist Doctors'}</span>
                            </h5>
                    `;
                    msg.doctors.forEach(doc => {
                        const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                        const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                        const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';
                        const hospName = doc.hospitals?.[0] ? getLocalizedText(doc.hospitals[0].name) : '';

                        html += `
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-sm text-indigo-950 flex items-center space-x-1">
                                        <span>${fullName}</span>
                                        ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-lg font-medium shrink-0">
                                        ${doc.experience_years} ${currentLocale === 'hi' ? 'वर्ष अनुभव' : 'yrs exp'}
                                    </span>
                                </div>
                                <div class="mt-2 space-y-1 text-xs text-slate-600">
                                    <div class="flex items-center space-x-1">
                                        <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-600 shrink-0"></i>
                                        <span class="font-medium text-slate-700">${deptName}</span>
                                    </div>
                                    ${hospName ? `
                                        <div class="flex items-start space-x-1 pt-0.5">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                            <span>${hospName}</span>
                                        </div>
                                    ` : ''}
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200">
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
                                <a href="${msg.see_all_doctors_url}" class="inline-flex items-center space-x-1 text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
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
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals & Clinics'}</span>
                            </h5>
                    `;
                    msg.hospitals.forEach(hosp => {
                        const hospName = getLocalizedText(hosp.name);
                        const emergencyPhone = hosp.emergency_phone || '';
                        const city = hosp.city || '';

                        html += `
                            <div class="bg-white p-3 rounded-2xl border border-teal-100 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-sm text-teal-950 flex items-center space-x-1">
                                        <span>${hospName}</span>
                                        ${hosp.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline shrink-0"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-teal-50 text-teal-700 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider shrink-0">
                                        ${hosp.type || 'Hospital'}
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-start space-x-1 text-xs text-slate-600">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                                    <span class="line-clamp-2">${hosp.address || ''} ${city ? ', ' + city : ''}</span>
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
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
                                <a href="${msg.see_all_hospitals_url}" class="inline-flex items-center space-x-1 text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
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
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 mt-3">
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
                            <div class="bg-white p-3 rounded-2xl border border-rose-100 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-sm text-rose-950 flex items-center space-x-1">
                                        <span>${bankName}</span>
                                        ${bank.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-rose-600 inline shrink-0"></i>' : ''}
                                    </h4>
                                    <span class="text-[10px] bg-rose-50 text-rose-700 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider shrink-0">
                                        ${currentLocale === 'hi' ? 'सत्यापित' : 'Verified'}
                                    </span>
                                </div>
                                <div class="mt-1.5 flex items-start space-x-1 text-xs text-slate-600">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                                    <span class="line-clamp-2">${address} ${city ? ', ' + city : ''}</span>
                                </div>
                                ${emergencyPhone ? `
                                    <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                        <a href="tel:${emergencyPhone}" class="text-xs bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
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
                                <a href="${msg.see_all_blood_banks_url}" class="inline-flex items-center space-x-1 text-xs bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-700 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
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
                        <div class="space-y-2 pt-3 border-t border-slate-100/50 mt-3">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                <i data-lucide="book-open" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>${currentLocale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles'}</span>
                            </h5>
                    `;
                    msg.articles.forEach(art => {
                        const artTitle = getLocalizedText(art.title);
                        const artExcerpt = getLocalizedText(art.excerpt) || (getLocalizedText(art.content) || '').substring(0, 80) + '...';

                        html += `
                            <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-xs hover:shadow-md transition-all duration-200 text-slate-800">
                                <h4 class="font-bold text-sm text-slate-900 line-clamp-1">${artTitle}</h4>
                                <p class="text-xs text-slate-600 mt-1 line-clamp-2">${artExcerpt}</p>
                                <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                    <a href="/articles/${art.id}" target="_blank" class="text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1 rounded-xl shadow-xs transition-all duration-200 flex items-center space-x-1">
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
                                <a href="${msg.see_all_articles_url}" class="inline-flex items-center space-x-1 text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1.5 rounded-xl shadow-xs transition-all duration-200">
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

        window.addEventListener('resize', collapseMobileFab);
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
        setupFabHintCycle();
        collapseMobileFab();

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

        /* Global borders */
        .dark .border-slate-200,
        .dark .border-slate-200\/80,
        .dark .border-slate-100,
        .dark .border-slate-200\/60,
        .dark .border-slate-300\/80,
        .dark .border-slate-300\/90,
        .dark .border-indigo-100,
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
        .dark .bg-indigo-50,
        .dark .bg-indigo-50\/70 {
            background-color: rgba(30, 27, 75, 0.45) !important;
            /* indigo-950/45 */
            border-color: rgba(49, 46, 129, 0.4) !important;
            color: #e0e7ff !important;
        }

        .dark .text-indigo-900,
        .dark .text-indigo-950,
        .dark .text-indigo-700 {
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

        @media (max-width: 639px) {
            .chatbot-fab.fab-collapsed {
                width: 56px;
                height: 56px;
                padding: 0;
                border-radius: 9999px;
                justify-content: center;
            }

            .chatbot-fab.fab-collapsed .chatbot-fab-label {
                display: none;
            }

            .chatbot-fab.fab-collapsed .chatbot-fab-icon {
                animation: none;
            }

            #chatbot-container {
                right: 0.75rem;
                bottom: calc(0.75rem + env(safe-area-inset-bottom));
            }

            #chatbot-window {
                width: calc(100vw - 1rem);
                max-width: 430px;
                height: min(86vh, 760px);
                min-height: 520px;
                border-bottom-right-radius: 0.5rem;
                border-bottom-left-radius: 0.5rem;
                position: fixed;
                left: 50%;
                right: auto;
                transform: translateX(-50%);
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

        /* Targeted dark-mode UI fixes for homepage hero search and contrast */
        .dark #home-hero p.text-slate-200 {
            color: #e2e8f0 !important;
        }

        .dark #hero-search-shell {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.88), rgba(30, 41, 59, 0.8)) !important;
            border-color: rgba(148, 163, 184, 0.28) !important;
            box-shadow: 0 20px 44px rgba(2, 6, 23, 0.45) !important;
        }

        .dark #hero-search-shell #omni-search-input {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            color: #f8fafc !important;
        }

        .dark #hero-search-shell #omni-search-input::placeholder {
            color: #cbd5e1 !important;
        }

        .dark #hero-search-shell .aurora-border::after,
        .dark #hero-search-shell.aurora-border::after {
            opacity: 0.75;
        }

        .dark #home-hero #clear-search-btn {
            background-color: rgba(148, 163, 184, 0.14) !important;
            border-color: rgba(148, 163, 184, 0.24) !important;
            color: #e2e8f0 !important;
        }

        .dark #home-hero #clear-search-btn:hover {
            background-color: rgba(148, 163, 184, 0.22) !important;
            color: #ffffff !important;
        }

        .dark #home-hero .bg-white\/10 {
            border-color: rgba(148, 163, 184, 0.24) !important;
        }

        .dark #home-hero .bg-white\/10:hover {
            background-color: rgba(148, 163, 184, 0.2) !important;
        }

        .dark #home-hero .bg-white\/5 {
            background-color: rgba(148, 163, 184, 0.08) !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        .dark #home-hero .bg-white\/5:hover {
            background-color: rgba(148, 163, 184, 0.16) !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
        }

    </style>
    @stack('scripts')
</body>

</html>

