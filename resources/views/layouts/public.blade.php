<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $appName,
            'url' => $siteUrl,
            'logo' => $siteUrl . '/favicon.ico',
            'sameAs' => [],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $appName,
            'url' => $siteUrl,
            'inLanguage' => [$isHindi ? 'hi-IN' : 'en-IN', $isHindi ? 'en-IN' : 'hi-IN'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $siteUrl . '/doctors?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
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
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
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
            '@context' => 'https://schema.org',
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
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
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
<body class="bg-[#F8FAFC] font-sans antialiased text-[#2D3748] min-h-screen flex flex-col selection:bg-teal-500 selection:text-white relative overflow-x-hidden {{ session('locale', app()->getLocale()) === 'hi' ? 'lang-hi' : '' }}">
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-28 -left-24 w-80 h-80 rounded-full bg-teal-300/25 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 w-80 h-80 rounded-full bg-indigo-300/25 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 rounded-full bg-cyan-300/20 blur-3xl"></div>
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
    <nav class="sticky top-0 z-50 bg-white/85 backdrop-blur-xl border-b border-slate-200/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center gap-4">
                <div class="flex items-center min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 group mr-2 sm:mr-6 shrink-0">
                        <div class="p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white animate-pulse"></i>
                        </div>
                        <span class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-slate-800 to-indigo-900 bg-clip-text text-transparent tracking-tight py-1 leading-normal">
                            Swasthya<span class="text-teal-600">Search</span>
                        </span>
                    </a>

                    <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                        <a href="{{ route('doctors.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('doctors.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors' }}
                        </a>
                        <a href="{{ route('hospitals.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('hospitals.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals' }}
                        </a>
                        <a href="{{ route('blood_banks.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('blood_banks.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}
                        </a>
                        <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('articles.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles' }}
                        </a>
                        <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'हमारे बारे में' : 'About Us' }}
                        </a>
                        <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60 shadow-inner">
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'en' ? 'bg-white text-indigo-900 shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">English</span><span class="sm:hidden font-bold">EN</span></span>
                            </button>
                        </form>
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="hi">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'hi' ? 'bg-white text-indigo-900 shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">हिंदी</span><span class="sm:hidden font-bold">HI</span></span>
                            </button>
                        </form>
                    </div>
                    <button type="button" onclick="toggleMobileMenu()"
                        id="mobile-menu-toggle-btn"
                        class="md:hidden p-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                        aria-label="Open navigation menu">
                        <i data-lucide="menu" id="mobile-menu-icon" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-nav-menu" class="md:hidden hidden pb-3 pt-2 border-t border-slate-200/80">
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
                {{ $locale === 'hi' ? 'स्वास्थ्य AI से पूछें' : 'Ask Swasthya AI' }}
            </span>
        </button>

        <!-- Chat Window -->
        <div id="chatbot-window" class="hidden w-[94vw] sm:w-[420px] h-[74vh] max-h-[680px] min-h-[520px] bg-white rounded-3xl shadow-2xl border border-slate-300/90 flex flex-col overflow-hidden animate-in fade-in duration-300 chatbot-window">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 text-white p-4 flex justify-between items-start shadow-md">
                <div class="flex items-start space-x-3 min-w-0 pr-2">
                    <div class="p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30">
                        <i data-lucide="bot" class="w-6 h-6 text-teal-400"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[17px] leading-tight text-white">Swasthya AI Assistant</h3>
                        <p class="text-xs sm:text-[12px] text-teal-200 leading-snug mt-1 break-words">
                            {{ $locale === 'hi' ? 'डॉक्टर, अस्पताल, ब्लड बैंक या विभाग खोजें' : 'Find doctors, hospitals, blood banks, or departments' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <select id="chatbot-language" class="text-xs bg-white text-slate-900 border border-white/40 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-teal-400/60">
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

            <div class="px-3 pb-2 bg-white border-t border-slate-200/80">
                <div class="flex items-center gap-1.5 mb-2">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-600"></i>
                    <label class="text-[12px] font-semibold text-slate-700 leading-none">{{ $locale === 'hi' ? 'शहर चुनें' : 'Select your city' }}</label>
                </div>
                <div class="flex items-center gap-2 mb-2 min-h-[34px]">
                    <div id="chatbot-city-selected-card" class="hidden items-center rounded-full border border-teal-300 bg-teal-100 px-3 py-1.5 w-fit max-w-[72%]">
                        <div class="flex items-center gap-2 text-sm text-teal-900 min-w-0">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-700 shrink-0"></i>
                            <span id="chatbot-selected-city-label" class="font-semibold truncate"></span>
                        </div>
                    </div>
                    <button type="button" id="chatbot-change-city-btn" onclick="enableCitySelection()" class="hidden shrink-0 text-[11px] font-semibold text-indigo-700 hover:text-indigo-800 bg-indigo-50 border border-indigo-200 rounded-full px-2.5 py-1 leading-none">
                        {{ $locale === 'hi' ? 'शहर बदलें' : 'Change City' }}
                    </button>
                </div>
                <div id="chatbot-city-pill-wrap" class="flex flex-wrap gap-1.5 max-h-20 overflow-y-auto pr-1">
                    @foreach($chatbotCityPills as $city)
                        <button type="button" class="chatbot-city-pill" data-city="{{ $city }}" onclick="selectChatbotCity('{{ addslashes($city) }}')">
                            {{ $city }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Messages Body -->
            <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gradient-to-b from-cyan-50/50 via-white to-indigo-50/30">
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
                        {{ $locale === 'hi' ? 'उदाहरण: "Jaipur में cardiologist", "नजदीकी hospital", "A+ blood bank"' : 'Try: "cardiologist in Jaipur", "nearby hospital", "A+ blood bank"' }}
                    </p>
                </div>

                <div class="hidden" id="chatbot-quick-prompts-wrap">
                    <div class="ml-9 max-w-[85%]">
                        <div class="mb-2 rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-2">
                            <p class="text-[11px] font-bold text-cyan-900">
                                {{ $locale === 'hi' ? 'त्वरित रोग/लक्षण विकल्प' : 'Quick Disease/Symptom Options' }}
                            </p>
                            <p class="text-[10px] text-cyan-800 mt-0.5">
                                {{ $locale === 'hi' ? 'नीचे विकल्प चुनें। यह सहायक निदान नहीं करता, केवल खोज में मदद करता है।' : 'Choose an option below. This assistant does not diagnose; it helps with healthcare discovery.' }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5" id="chatbot-quick-prompts">
                            <button type="button" onclick="handleQuickAction('doctors')" class="chatbot-chip">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctors' }}</button>
                            <button type="button" onclick="handleQuickAction('hospitals')" class="chatbot-chip">{{ $locale === 'hi' ? 'अस्पताल खोजें' : 'Find Hospitals' }}</button>
                            <button type="button" onclick="handleQuickAction('blood_banks')" class="chatbot-chip">{{ $locale === 'hi' ? 'ब्लड बैंक खोजें' : 'Find Blood Banks' }}</button>
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'मेरे लक्षणों के आधार पर विभाग बताएं' : 'Help me find department by symptoms' }}">{{ $locale === 'hi' ? 'लक्षण से खोजें' : 'Search Symptoms' }}</button>
                            <button type="button" onclick="enableCitySelection()" class="chatbot-chip">{{ $locale === 'hi' ? 'शहर बदलें' : 'Change City' }}</button>
                            <button type="button" onclick="clearChatConversation()" class="chatbot-chip">{{ $locale === 'hi' ? 'चैट साफ करें' : 'Clear Chat' }}</button>
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip chatbot-chip-danger" data-message="{{ $locale === 'hi' ? 'मुझे आपातकालीन मदद चाहिए' : 'I need emergency help' }}">{{ $locale === 'hi' ? 'आपातकालीन मदद' : 'Emergency Help' }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="chatbot-loading" class="hidden px-4 py-2.5 flex space-x-2.5 items-center text-slate-700 text-sm bg-indigo-50/70 border-y border-indigo-100">
                <div class="typing-dots" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <span>{{ $locale === 'hi' ? 'स्वास्थ्य AI सोच रहा है...' : 'Swasthya AI is thinking...' }}</span>
            </div>
            <div class="px-3 pb-2 bg-amber-50 border-t border-amber-200">
                <button
                    type="button"
                    id="chatbot-important-toggle"
                    onclick="toggleChatbotImportant()"
                    class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-amber-900 bg-white border border-amber-300 rounded-full px-2.5 py-1"
                    aria-label="Show important assistant details"
                >
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

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Chatbot Logic
        let chatbotOpen = false;
        let chatbotSessionToken = null;
        const currentLocale = "{{ $locale }}";
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
                    select.dispatchEvent(new Event('change', { bubbles: true }));
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
            return escapeHtml(value).replace(/\n/g, '<br>');
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
            const icon = document.getElementById('mobile-menu-icon');
            const btn = document.getElementById('mobile-menu-toggle-btn');
            if (!menu || !icon || !btn) return;
            const isOpen = !menu.classList.contains('hidden');
            icon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            btn.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            lucide.createIcons();
        }
        function selectChatbotCity(city) {
            const previousCity = chatbotCity;
            chatbotCity = normalizeCityValue(city);
            if (!chatbotCity) return;
            setCityStorage(chatbotCity);
            syncCityDropdowns(chatbotCity);
            isCityLocked = true;
            hasCityPromptVisible = false;
            refreshChatbotCityUI();
            if (chatbotCity !== previousCity) {
                appendMessage(
                    'bot',
                    chatbotLocale === 'hi'
                        ? `बहुत बढ़िया, आपने ${chatbotCity} चुना है। अब लक्षण लिखें या नीचे दिए गए विकल्प चुनें।`
                        : `Great, you've selected ${chatbotCity}. Now type your symptoms or use the quick options below.`
                );
            }
        }
        function enableCitySelection() {
            isCityLocked = false;
            refreshChatbotCityUI();
        }
        function refreshChatbotCityUI() {
            const selectedCard = document.getElementById('chatbot-city-selected-card');
            const selectedLabel = document.getElementById('chatbot-selected-city-label');
            const changeBtn = document.getElementById('chatbot-change-city-btn');
            const pillWrap = document.getElementById('chatbot-city-pill-wrap');
            const quickPromptsWrap = document.getElementById('chatbot-quick-prompts-wrap');
            const input = document.getElementById('chatbot-input');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            if (!selectedCard || !selectedLabel || !changeBtn || !pillWrap || !quickPromptsWrap) return;

            Array.from(pillWrap.querySelectorAll('.chatbot-city-pill')).forEach(btn => {
                const city = (btn.dataset.city || '').trim();
                btn.classList.toggle('active', city === chatbotCity);
            });

            if (isCityLocked && chatbotCity) {
                selectedLabel.textContent = chatbotCity;
                selectedCard.classList.remove('hidden');
                selectedCard.classList.add('flex');
                pillWrap.classList.add('hidden');
                changeBtn.classList.remove('hidden');
                quickPromptsWrap.classList.remove('hidden');
                if (input) {
                    input.disabled = false;
                    input.placeholder = chatbotLocale === 'hi' ? 'लक्षण बताएं या डॉक्टर, अस्पताल, ब्लड बैंक खोजें...' : 'Describe symptoms or search doctors, hospitals, blood banks...';
                }
                if (sendBtn) sendBtn.disabled = false;
                if (voiceBtn) voiceBtn.disabled = false;
                scrollToChatBottom();
            } else {
                selectedCard.classList.add('hidden');
                selectedCard.classList.remove('flex');
                pillWrap.classList.remove('hidden');
                changeBtn.classList.add('hidden');
                quickPromptsWrap.classList.add('hidden');
                if (input) {
                    input.disabled = true;
                    input.placeholder = chatbotLocale === 'hi' ? 'पहले शहर चुनें...' : 'Select city first...';
                }
                if (sendBtn) sendBtn.disabled = true;
                if (voiceBtn) voiceBtn.disabled = true;
            }
        }
        function initializeChatbotCity() {
            const selectedCity = resolveSelectedCity();
            if (selectedCity) {
                chatbotCity = selectedCity;
                isCityLocked = true;
                setCityStorage(chatbotCity);
                syncCityDropdowns(chatbotCity);
            }
            const initialMessage = document.getElementById('chatbot-initial-message');
            if (initialMessage && chatbotCity) {
                initialMessage.textContent = chatbotLocale === 'hi'
                    ? `वापस स्वागत है। आपका चुना हुआ शहर ${chatbotCity} है। आप क्या खोजना चाहते हैं?`
                    : `Welcome back. Your selected city is ${chatbotCity}. What would you like to find?`;
            }
            refreshChatbotCityUI();
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
                chatbotCity
                    ? (chatbotLocale === 'hi'
                        ? `चैट साफ की गई। आपका चुना हुआ शहर ${chatbotCity} है। बताइए क्या खोजना है?`
                        : `Chat cleared. Your selected city is ${chatbotCity}. What would you like to find?`)
                    : (chatbotLocale === 'hi'
                        ? 'चैट साफ की गई। कृपया शहर चुनें और आगे बढ़ें।'
                        : 'Chat cleared. Please choose a city to continue.')
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
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
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
                appendMessage('bot', chatbotLocale === 'hi'
                    ? 'आपके ब्राउज़र में वॉइस टाइपिंग समर्थित नहीं है। कृपया Chrome/Edge का उपयोग करें।'
                    : 'Voice typing is not supported in your browser. Please use Chrome or Edge.');
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
                    data.history.forEach(msg => {
                        appendMessageObj(msg);
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
            appendMessageObj({ sender, text });
            lucide.createIcons();
            scrollToChatBottom();
        }

        function appendMessageObj(msg) {
            const messagesDiv = document.getElementById('chatbot-messages');
            const isUser = msg.sender === 'user';
            
            const spoken = String(msg.text || '').replace(/'/g, '&#39;').replace(/\"/g, '&quot;');
            const isWarning = !isUser && /(emergency|urgent|call|आपात|तुरंत|helpline)/i.test(String(msg.text || ''));
            let html = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200">
                    <div class="flex space-x-2 max-w-[85%] ${isUser ? 'flex-row-reverse space-x-reverse' : 'flex-row'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${isUser ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'}">
                            <i data-lucide="${isUser ? 'user' : 'bot'}" class="w-4 h-4"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${isUser ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-tr-none border border-indigo-400/50' : isWarning ? 'bg-amber-50 text-amber-900 border border-amber-200 rounded-tl-none' : 'bg-white/95 text-slate-800 border border-slate-200/70 rounded-tl-none backdrop-blur-sm'}">
                                ${msg.text}
                            </div>
                            ${!isUser ? `<button type="button" onclick="speakText('${spoken}')" class="inline-flex items-center gap-1 text-[11px] text-slate-500 hover:text-teal-600 text-left px-2 py-1 rounded-lg hover:bg-teal-50 transition-all">🔊 ${chatbotLocale === 'hi' ? 'सुनें' : 'Listen'}</button>` : ''}
            `;

            const detailedAnswerRaw = !isUser
                ? (chatbotLocale === 'hi'
                    ? (msg?.qa_answer?.detailed_answer_hi || msg?.qa_answer?.detailed_answer_en || '')
                    : (msg?.qa_answer?.detailed_answer_en || msg?.qa_answer?.detailed_answer_hi || ''))
                : '';
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

            if (msg.department_info) {
                html += `
                    <div class="bg-teal-50 border border-teal-200 p-3.5 rounded-2xl text-teal-950 text-xs shadow-2xs mt-2 flex items-start space-x-2.5">
                        <i data-lucide="info" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5 animate-pulse"></i>
                        <div>
                            <span class="font-extrabold block text-teal-900 mb-0.5">${currentLocale === 'hi' ? 'अनुशंसित विभाग / परामर्श:' : 'Recommended Department / Action:'}</span>
                            <span class="leading-relaxed font-medium">${msg.department_info}</span>
                        </div>
                    </div>
                `;
            }

            if (msg.doctors && msg.doctors.length > 0) {
                html += `<div class="space-y-2 pt-2"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'विशेषज्ञ डॉक्टर' : 'Specialist Doctors'}</h5>`;
                msg.doctors.forEach(doc => {
                    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                    const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                    const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';
                    const hospName = doc.hospitals?.[0] ? getLocalizedText(doc.hospitals[0].name) : '';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-sm text-indigo-950 flex items-center space-x-1">
                                    <span>${fullName}</span>
                                    ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline"></i>' : ''}
                                </h4>
                                <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-lg font-medium">
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
                                    <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                                        ${currentLocale === 'hi' ? 'कॉल करें' : 'Call Doctor'}
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
                html += `</div>`;
            }

            if (msg.hospitals && msg.hospitals.length > 0) {
                html += `<div class="space-y-2 pt-2 border-t border-slate-100"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals & Clinics'}</h5>`;
                msg.hospitals.forEach(hosp => {
                    const hospName = getLocalizedText(hosp.name);
                    const emergencyPhone = hosp.emergency_phone || '';
                    const city = hosp.city || '';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-teal-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
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
                                    <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200 flex items-center space-x-1">
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
                        <div class="pt-1">
                            <a href="${msg.see_all_hospitals_url}" class="inline-flex items-center space-x-1 text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                                <span>${currentLocale === 'hi' ? 'सभी अस्पताल देखें' : 'See all hospitals'}</span>
                            </a>
                        </div>
                    `;
                }
                html += `</div>`;
            }

            if (msg.doctors && msg.doctors.length > 0 && msg.see_all_doctors_url) {
                html += `
                    <div class="pt-1">
                        <a href="${msg.see_all_doctors_url}" class="inline-flex items-center space-x-1 text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                            <span>${currentLocale === 'hi' ? 'सभी डॉक्टर देखें' : 'See all doctors'}</span>
                        </a>
                    </div>
                `;
            }

            if (msg.articles && msg.articles.length > 0) {
                html += `<div class="space-y-2 pt-2 border-t border-slate-100"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles'}</h5>`;
                msg.articles.forEach(art => {
                    const artTitle = getLocalizedText(art.title);
                    const artExcerpt = getLocalizedText(art.excerpt) || (getLocalizedText(art.content) || '').substring(0, 80) + '...';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
                            <h4 class="font-bold text-sm text-slate-900 line-clamp-1">${artTitle}</h4>
                            <p class="text-xs text-slate-600 mt-1 line-clamp-2">${artExcerpt}</p>
                            <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                <a href="/articles/${art.id}" target="_blank" class="text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200 flex items-center space-x-1">
                                    <span>${currentLocale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article'}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </div>
                        </div>
                    `;
                });
                html += `</div>`;
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
        initializeChatbotCity();
        setupFabHintCycle();
        collapseMobileFab();
    </script>
    <style>
        :root {
            --brand-teal: #0ea5a6;
            --brand-indigo: #4f46e5;
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
        @keyframes typingDot {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.35; }
            40% { transform: translateY(-4px); opacity: 1; }
        }
        #chatbot-messages::-webkit-scrollbar {
            width: 8px;
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
                width: calc(100vw - 0.75rem);
                height: min(86vh, 760px);
                min-height: 520px;
                border-bottom-right-radius: 0.5rem;
                border-bottom-left-radius: 0.5rem;
                margin-right: 0.35rem;
            }
        }
    </style>
    @stack('scripts')
</body>
</html>
