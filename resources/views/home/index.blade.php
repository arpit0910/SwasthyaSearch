@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मुखपृष्ठ' : 'Home') . ' - SwasthyaSearch')

@section('content')
    @php
        $quickSymptoms =
            $locale === 'hi'
                ? ['बुखार और खांसी', 'हड्डी का टूटना', 'छाती में दर्द']
                : ['Fever and Cough', 'Bone Fracture', 'Chest Pain'];
    @endphp

    <!-- Hero Section -->
    <header
        class="relative overflow-hidden py-16 lg:py-24 mb-6 bg-gradient-to-b from-indigo-900 via-indigo-950 to-slate-900 text-white">
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div
                class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 mb-8 shadow-inner">
                <i data-lucide="sparkles" class="w-4 h-4 text-teal-400 animate-spin"></i>
                <span class="text-xs font-semibold tracking-wider uppercase">
                    {{ $locale === 'hi' ? 'स्मार्ट लक्षण-आधारित खोज' : 'Smart Symptom-Based Search' }}
                </span>
            </div>

            <h1
                class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto py-4 leading-[1.4] sm:leading-[1.35] text-white drop-shadow-md mb-6">
                @if ($locale === 'hi')
                    अपनी बीमारी के <span class="text-teal-400">लक्षणों</span> से सही डॉक्टर खोजें
                @else
                    Find the Right Specialist by Your <span class="text-teal-400">Symptoms</span>
                @endif
            </h1>

            <p class="text-lg sm:text-xl text-slate-300 max-w-3xl sm:max-w-4xl mx-auto font-normal leading-[1.7] sm:leading-[1.8] mb-8">
                {{ $locale === 'hi' ? 'अब जटिल मेडिकल शब्दों की चिंता नहीं। अपनी परेशानी या बीमारी का नाम लिखें और तुरंत सही डॉक्टर का पता लगाएं।' : 'Skip the medical jargon. Simply type what is bothering you, and our intelligent directory will connect you with the right verified healthcare providers instantly.' }}
            </p>

            <!-- Omni-Search Box -->
            <div class="mt-12 max-w-3xl mx-auto px-4 sm:px-0">
                <div
                    class="relative flex items-center bg-white rounded-3xl shadow-2xl p-2 sm:p-3 border border-slate-200/80 focus-within:ring-4 focus-within:ring-teal-500/20 transition-all duration-300">
                    <i data-lucide="search"
                        class="absolute left-4 sm:left-6 w-5 h-5 sm:w-6 sm:h-6 text-slate-400 pointer-events-none"></i>
                    <input type="text" id="omni-search-input" oninput="handleOmniSearch(this.value)"
                        placeholder="{{ $locale === 'hi' ? 'खोजें: \"पेट दर्द\", \"हड्डी का टूटना\", या डॉक्टर का नाम...' : 'Search: Bone fracture or Doctor name...' }}"
                        class="w-full pl-11 sm:pl-16 pr-4 py-3.5 sm:py-4 text-slate-800 bg-transparent text-base sm:text-lg font-medium placeholder:text-slate-400 focus:outline-none" />
                    <button id="clear-search-btn" onclick="clearOmniSearch()"
                        class="hidden mr-3 px-3 py-1.5 text-xs text-slate-400 hover:text-slate-600 bg-slate-100 rounded-xl transition-all duration-200">
                        Clear
                    </button>
                </div>

                <!-- Quick Specialty Shortcuts -->
                <div class="mt-8 pt-6 border-t border-white/10 max-w-4xl mx-auto text-center">
                    <p class="text-xs font-bold uppercase tracking-widest text-teal-300 mb-4">
                        {{ $locale === 'hi' ? 'प्रमुख चिकित्सा विभाग व विशेषज्ञ खोजें' : 'Explore Top Medical Specialties' }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-2.5 items-center">
                        @foreach ($departments->random(4) as $dept)
                            @php
                                $dName =
                                    $locale === 'hi'
                                        ? ($dept['name']['hi'] ?:
                                        $dept['name']['en'])
                                        : $dept['name']['en'];
                            @endphp
                            <button onclick="handleOmniSearch('{{ addslashes($dName) }}')"
                                class="px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 text-xs sm:text-sm font-semibold transition-all duration-200 backdrop-blur-md shadow-sm hover:shadow-md flex items-center space-x-1.5 group">
                                <i data-lucide="stethoscope"
                                    class="w-3.5 h-3.5 text-teal-400 group-hover:scale-110 transition-transform"></i>
                                <span>{{ $dName }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <!-- Search Match Indicators (Dynamic) -->
        <div id="search-match-container"
            class="hidden mb-12 bg-gradient-to-r from-teal-500/10 via-indigo-500/10 to-transparent p-6 rounded-3xl border border-teal-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-teal-500 text-white rounded-2xl shadow-md">
                    <i data-lucide="stethoscope" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $locale === 'hi' ? 'लक्षण विश्लेषण और विभाग मिलान' : 'Symptom Analysis & Department Match' }}
                    </h3>
                    <p class="text-sm text-slate-600 mt-0.5" id="search-match-text"></p>
                </div>
            </div>
            <span
                class="text-xs font-semibold bg-white text-teal-700 px-3 py-1.5 rounded-xl shadow-sm border border-teal-100">
                {{ $locale === 'hi' ? 'एआई द्वारा सत्यापित' : 'AI Verified Match' }}
            </span>
        </div>

        <!-- Search Results View (Dynamic) -->
        <div id="search-results-view" class="hidden">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                        {{ $locale === 'hi' ? 'खोज परिणाम' : 'Search Results' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $locale === 'hi' ? 'बिना किसी विज्ञापन या मध्यस्थ के सीधे संपर्क करें' : 'Direct contact details with zero ads or intermediaries' }}
                    </p>
                </div>
                <span
                    class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100"
                    id="doctors-count-badge">
                    0 {{ $locale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found' }}
                </span>
            </div>

            <!-- Loading Spinner -->
            <div id="search-loading"
                class="hidden py-20 text-center text-slate-400 font-medium flex flex-col items-center justify-center space-y-3">
                <div class="w-10 h-10 border-4 border-teal-500 border-t-transparent rounded-full animate-spin"></div>
                <span>{{ $locale === 'hi' ? 'डॉक्टर खोजे जा रहे हैं...' : 'Searching healthcare providers...' }}</span>
            </div>

            <!-- Doctors Grid -->
            <div id="doctors-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

            <!-- No Results -->
            <div id="no-results-container"
                class="hidden py-20 text-center bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm max-w-xl mx-auto space-y-4">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                    <i data-lucide="search" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">
                    {{ $locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Specialists Found' }}
                </h3>
                <p class="text-slate-500 text-sm">
                    {{ $locale === 'hi' ? 'आपकी खोज से मेल खाने वाले कोई डॉक्टर या अस्पताल नहीं मिले। कृपया किसी अन्य लक्षण या विभाग से खोजें।' : 'We couldn\'t find any healthcare providers matching your exact criteria. Try searching with different symptom keywords.' }}
                </p>
            </div>
        </div>

        <!-- Default Homepage View: Health News, Articles & FAQs -->
        <div id="default-view" class="space-y-16">
            <!-- Trust / Govt Schemes Banner -->
            <div
                class="bg-gradient-to-r from-teal-500/10 via-indigo-500/10 to-purple-500/10 p-6 sm:p-8 rounded-3xl border border-teal-500/20 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <div
                        class="inline-flex items-center space-x-1.5 bg-teal-500 text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>{{ $locale === 'hi' ? '100% नि:शुल्क व पारदर्शी' : '100% Free & Transparent' }}</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        {{ $locale === 'hi' ? 'आयुष्मान भारत व जन आधार कार्ड स्वीकार्य' : 'Ayushman Bharat & Jan Aadhaar Cards Accepted' }}
                    </h3>
                    <p class="text-slate-600 text-sm max-w-2xl leading-relaxed">
                        {{ $locale === 'hi' ? 'हमारा मंच आपको सीधे अस्पतालों और डॉक्टरों से जोड़ता है। कोई बिचौलिया नहीं, कोई छिपी हुई फीस नहीं।' : 'Connect directly with verified hospitals and doctors. Zero intermediaries, zero booking fees, and full support for government health schemes.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3 justify-center shrink-0">
                    <span
                        class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200/80 shadow-2xs text-xs font-bold text-slate-700 flex items-center space-x-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                        <span>{{ $locale === 'hi' ? 'आयुष्मान भारत' : 'Ayushman Bharat' }}</span>
                    </span>
                    <span
                        class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200/80 shadow-2xs text-xs font-bold text-slate-700 flex items-center space-x-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-blue-600"></i>
                        <span>{{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}</span>
                    </span>
                    <span
                        class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200/80 shadow-2xs text-xs font-bold text-slate-700 flex items-center space-x-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-purple-600"></i>
                        <span>{{ $locale === 'hi' ? 'कैशलेस बीमा' : 'Cashless Insurance' }}</span>
                    </span>
                </div>
            </div>

            <!-- Directory Statistics / Informative Overview -->
            <div>
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <div
                            class="inline-flex items-center space-x-2 text-teal-600 font-bold text-sm uppercase tracking-wider mb-2">
                            <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                            <span>{{ $locale === 'hi' ? 'स्वास्थ्या सर्च एक नज़र में' : 'SwasthyaSearch at a Glance' }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                            {{ $locale === 'hi' ? 'हमारा विस्तृत और प्रमाणित स्वास्थ्य नेटवर्क' : 'Our Extensive & Verified Healthcare Network' }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $locale === 'hi' ? 'राजस्थान के सर्वश्रेष्ठ डॉक्टरों और अस्पतालों की सम्पूर्ण जानकारी' : 'Comprehensive directory coverage across top healthcare institutions and specialists' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <!-- Cities Count -->
                    <div
                        class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                            <div
                                class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/20">
                                <i data-lucide="map-pin" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    {{ $stats['cities'] ?? 1 }}+</div>
                                <div class="text-indigo-100 text-xs sm:text-sm font-medium mt-1">
                                    {{ $locale === 'hi' ? 'शहर व क्षेत्र' : 'Cities & Regions' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctors Count -->
                    <div
                        class="bg-gradient-to-br from-teal-500 via-teal-600 to-teal-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                            <div
                                class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/20">
                                <i data-lucide="users" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    {{ $stats['doctors'] ?? 120 }}+</div>
                                <div class="text-teal-100 text-xs sm:text-sm font-medium mt-1">
                                    {{ $locale === 'hi' ? 'सत्यापित डॉक्टर' : 'Verified Specialists' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Departments Count -->
                    <div
                        class="bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                            <div
                                class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/20">
                                <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    {{ $stats['departments'] ?? 10 }}+</div>
                                <div class="text-amber-100 text-xs sm:text-sm font-medium mt-1">
                                    {{ $locale === 'hi' ? 'चिकित्सा विभाग' : 'Medical Departments' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hospitals Count -->
                    <div
                        class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                            <div
                                class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/20">
                                <i data-lucide="building-2" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    {{ $stats['hospitals'] ?? 20 }}+</div>
                                <div class="text-purple-100 text-xs sm:text-sm font-medium mt-1">
                                    {{ $locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Blood Banks Count -->
                    <div
                        class="bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                        </div>
                        <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                            <div
                                class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/20">
                                <i data-lucide="droplet" class="w-6 h-6 text-white fill-white"></i>
                            </div>
                            <div>
                                <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    {{ $stats['blood_banks'] ?? 8 }}+</div>
                                <div class="text-red-100 text-xs sm:text-sm font-medium mt-1">
                                    {{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Section -->
            <div>
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <div
                            class="inline-flex items-center space-x-2 text-indigo-600 font-bold text-sm uppercase tracking-wider mb-2">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span>{{ $locale === 'hi' ? 'स्वास्थ्य ज्ञान और समाचार' : 'Health Knowledge & News' }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                            {{ $locale === 'hi' ? 'नवीनतम चिकित्सा लेख और स्वास्थ्य सुझाव' : 'Latest Medical Articles & Wellness Tips' }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $locale === 'hi' ? 'विशेषज्ञ डॉक्टरों द्वारा प्रमाणित स्वास्थ्य सलाह और जीवनशैली मार्गदर्शन' : 'Expert-verified health advice and lifestyle guidance from top practitioners' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($articles as $article)
                        @php
                            $title = $locale === 'hi' ? $article->title_hi : $article->title_en;
                            $excerpt = $locale === 'hi' ? $article->excerpt_hi : $article->excerpt_en;
                        @endphp
                        <a href="{{ route('articles.show', $article->id) }}"
                            class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-teal-200 transition-all duration-300 flex flex-col overflow-hidden group">
                            <div
                                class="p-6 pb-4 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-transparent flex justify-between items-center">
                                <span
                                    class="text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">
                                    {{ $article->category }}
                                </span>
                                <div class="flex items-center space-x-1 text-xs text-slate-400 font-medium">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    <span>3 min read</span>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3
                                        class="font-bold text-lg text-slate-900 group-hover:text-teal-600 transition-colors leading-snug">
                                        {{ $title }}
                                    </h3>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $excerpt }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-500 italic">
                                        {{ $locale === 'hi' ? 'लेखक' : 'By' }}: {{ $article->author_name }}
                                    </span>
                                    <span
                                        class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform flex items-center space-x-1">
                                        <span>{{ $locale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article' }}</span>
                                        <span>→</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- FAQs Section -->
            @if ($faqs->isNotEmpty())
                <div class="pt-12 border-t border-slate-200/80">
                    <div class="text-center max-w-2xl mx-auto mb-10">
                        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                            {{ $locale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions' }}
                        </h2>
                        <p class="text-sm text-slate-500 mt-2">
                            {{ $locale === 'hi' ? 'स्वास्थ्या सर्च के बारे में आपके सभी सवालों के जवाब' : 'Everything you need to know about SwasthyaSearch' }}
                        </p>
                    </div>

                    <div class="max-w-3xl mx-auto space-y-4">
                        @foreach ($faqs as $idx => $faq)
                            @php
                                $question = $locale === 'hi' ? $faq->question_hi : $faq->question_en;
                                $answer = $locale === 'hi' ? $faq->answer_hi : $faq->answer_en;
                            @endphp
                            <div
                                class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden transition-all duration-200">
                                <button onclick="toggleFaq({{ $idx }})"
                                    class="w-full p-5 text-left font-bold text-base text-slate-800 flex justify-between items-center hover:bg-slate-50/50 transition-colors">
                                    <span>{{ $question }}</span>
                                    <i data-lucide="chevron-down" id="faq-icon-{{ $idx }}"
                                        class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200"></i>
                                </button>
                                <div id="faq-content-{{ $idx }}"
                                    class="hidden p-5 pt-0 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/30">
                                    {{ $answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- User Feedback Form Section -->
            <div class="pt-12 border-t border-slate-200/80">
                <div
                    class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden border border-slate-800">
                    <div
                        class="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]">
                    </div>
                    <div class="relative z-10 max-w-3xl mx-auto">
                        <div class="text-center mb-10">
                            <div
                                class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 mb-4 shadow-inner text-xs font-semibold uppercase tracking-wider">
                                <i data-lucide="message-square" class="w-4 h-4 text-teal-400"></i>
                                <span>{{ $locale === 'hi' ? 'आपकी राय महत्वपूर्ण है' : 'Your Opinion Matters' }}</span>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                                {{ $locale === 'hi' ? 'हमें अपना फीडबैक दें' : 'Share Your Feedback With Us' }}
                            </h2>
                            <p class="text-sm text-slate-300 mt-2 max-w-xl mx-auto leading-relaxed">
                                {{ $locale === 'hi' ? 'आपके सुझावों से हम स्वास्थ्या सर्च को और बेहतर बनाने के लिए निरंतर प्रयासरत हैं।' : 'Help us improve our healthcare directory. Tell us about your experience searching for doctors and hospitals.' }}
                            </p>
                        </div>

                        <form action="{{ route('feedback.submit') }}" method="POST"
                            class="space-y-6 bg-slate-800/50 backdrop-blur-md p-6 sm:p-8 rounded-2xl border border-slate-700/80 shadow-inner">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="feedback-name"
                                        class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                        {{ $locale === 'hi' ? 'आपका नाम' : 'Your Name' }} <span
                                            class="text-teal-400">*</span>
                                    </label>
                                    <input type="text" id="feedback-name" name="name" required
                                        placeholder="{{ $locale === 'hi' ? 'नाम दर्ज करें' : 'Enter your name' }}"
                                        class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                        {{ $locale === 'hi' ? 'रेटिंग' : 'Rating' }}
                                    </label>
                                    <input type="hidden" id="feedback-rating-input" name="rating" value="5" />
                                    <div class="flex items-center space-x-1.5 py-1.5" id="star-rating-container">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <button type="button" onclick="setFeedbackRating({{ $s }})"
                                                onmouseover="hoverFeedbackRating({{ $s }})"
                                                onmouseout="resetFeedbackRatingHover()"
                                                class="p-2 rounded-xl bg-slate-900/80 hover:bg-slate-900 border border-slate-700/80 hover:border-amber-400/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400/50 shadow-inner"
                                                title="{{ $s }} Star">
                                                <i data-lucide="star"
                                                    class="w-6 h-6 text-amber-400 fill-amber-400 transition-colors duration-200"
                                                    id="star-icon-{{ $s }}"></i>
                                            </button>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="feedback-category"
                                    class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                    {{ $locale === 'hi' ? 'फीडबैक श्रेणी' : 'Feedback Category' }} <span
                                        class="text-teal-400">*</span>
                                </label>
                                <div class="relative">
                                    <select id="feedback-category" name="category" required
                                        class="w-full pl-4 pr-10 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-white appearance-none focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm cursor-pointer">
                                        <option value="Doctor Search Experience">
                                            {{ $locale === 'hi' ? 'डॉक्टर खोजने का अनुभव' : 'Doctor Search Experience' }}
                                        </option>
                                        <option value="Hospital Information Accuracy">
                                            {{ $locale === 'hi' ? 'अस्पताल की जानकारी' : 'Hospital Information Accuracy' }}
                                        </option>
                                        <option value="Website Navigation & Speed">
                                            {{ $locale === 'hi' ? 'वेबसाइट की गति व उपयोग' : 'Website Navigation & Speed' }}
                                        </option>
                                        <option value="General Suggestion">
                                            {{ $locale === 'hi' ? 'सामान्य सुझाव' : 'General Suggestion' }}</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="feedback-comments"
                                    class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                                    {{ $locale === 'hi' ? 'आपके सुझाव या विचार' : 'Your Comments & Suggestions' }} <span
                                        class="text-teal-400">*</span>
                                </label>
                                <textarea id="feedback-comments" name="comments" rows="4" required
                                    placeholder="{{ $locale === 'hi' ? 'अपने विचार यहाँ लिखें...' : 'Please let us know how we can improve...' }}"
                                    class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-white placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm"></textarea>
                            </div>

                            <div class="text-center pt-2">
                                <button type="submit"
                                    class="px-8 py-3.5 bg-teal-500 hover:bg-teal-600 active:bg-teal-700 text-slate-900 hover:text-white font-extrabold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm uppercase tracking-wider flex items-center justify-center space-x-2 mx-auto">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    <span>{{ $locale === 'hi' ? 'फीडबैक सबमिट करें' : 'Submit Feedback' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        let searchTimeout = null;

        function toggleFaq(idx) {
            const content = document.getElementById(`faq-content-${idx}`);
            const icon = document.getElementById(`faq-icon-${idx}`);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        function clearOmniSearch() {
            const input = document.getElementById('omni-search-input');
            input.value = '';
            handleOmniSearch('');
        }

        function handleOmniSearch(val) {
            const input = document.getElementById('omni-search-input');
            if (input.value !== val) input.value = val;

            const clearBtn = document.getElementById('clear-search-btn');
            if (val.trim()) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            if (!val.trim()) {
                document.getElementById('default-view').classList.remove('hidden');
                document.getElementById('search-results-view').classList.add('hidden');
                document.getElementById('search-match-container').classList.add('hidden');
                return;
            }

            document.getElementById('default-view').classList.add('hidden');
            document.getElementById('search-results-view').classList.remove('hidden');
            document.getElementById('search-loading').classList.remove('hidden');
            document.getElementById('doctors-grid').innerHTML = '';
            document.getElementById('no-results-container').classList.add('hidden');
            document.getElementById('search-match-container').classList.add('hidden');

            if (searchTimeout) clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchSearchResults(val), 300);
        }

        async function fetchSearchResults(query) {
            try {
                const res = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                document.getElementById('search-loading').classList.add('hidden');
                const grid = document.getElementById('doctors-grid');
                grid.innerHTML = '';

                const countBadge = document.getElementById('doctors-count-badge');
                countBadge.innerText =
                    `${data.doctors ? data.doctors.length : 0} ${currentLocale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found'}`;

                if (data.matched_department || data.matched_disease) {
                    const matchContainer = document.getElementById('search-match-container');
                    const matchText = document.getElementById('search-match-text');
                    matchContainer.classList.remove('hidden');

                    let textParts = [];
                    if (data.matched_disease) {
                        textParts.push(`${currentLocale === 'hi' ? 'लक्षण:' : 'Symptom:'} "${data.matched_disease}"`);
                    }
                    if (data.matched_department) {
                        textParts.push(
                            `<span class="font-semibold text-teal-700">${currentLocale === 'hi' ? 'अनुशंसित विभाग:' : 'Recommended Department:'} ${data.matched_department}</span>`
                        );
                    }
                    matchText.innerHTML = textParts.join(' • ');
                }

                if (!data.doctors || data.doctors.length === 0) {
                    document.getElementById('no-results-container').classList.remove('hidden');
                    return;
                }

                data.doctors.forEach(doc => {
                    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                    const deptName = doc.department ? (currentLocale === 'hi' ? doc.department.name.hi : doc
                        .department.name.en) : '';
                    const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';
                    const aboutText = doc.about ? (currentLocale === 'hi' ? doc.about.hi : doc.about.en) : '';

                    let html = `
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 flex flex-col overflow-hidden group">
                        <div class="p-6 pb-4 border-b border-slate-100 flex justify-between items-start bg-gradient-to-b from-slate-50/50 to-transparent">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-teal-500 text-white flex items-center justify-center font-bold text-xl shadow-md group-hover:scale-105 transition-all duration-300">
                                    ${doc.first_name ? doc.first_name.charAt(0) : '<i data-lucide="user" class="w-6 h-6"></i>'}
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-indigo-600 transition-colors flex items-start space-x-1.5">
                                        <span class="line-clamp-3 leading-snug">${fullName}</span>
                                        ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 shrink-0 mt-1" title="Verified Provider"></i>' : ''}
                                    </h3>
                                    <p class="text-xs font-semibold text-teal-600 flex items-center space-x-1 mt-0.5">
                                        <i data-lucide="stethoscope" class="w-3.5 h-3.5"></i>
                                        <span>${deptName}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col space-y-4">
                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                    <i data-lucide="award" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                                    <div class="truncate">
                                        <span class="text-slate-400 block text-[10px] uppercase">${currentLocale === 'hi' ? 'अनुभव' : 'Experience'}</span>
                                        <span class="text-slate-900 font-bold">${doc.experience_years} ${currentLocale === 'hi' ? 'वर्ष' : 'Years'}</span>
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                    <i data-lucide="file-text" class="w-4 h-4 text-teal-500 shrink-0"></i>
                                    <div class="truncate">
                                        <span class="text-slate-400 block text-[10px] uppercase">${currentLocale === 'hi' ? 'परामर्श शुल्क' : 'Fee'}</span>
                                        <span class="text-slate-900 font-bold">₹${doc.consultation_fee || 500}</span>
                                    </div>
                                </div>
                            </div>
                `;

                    if (doc.registration_number) {
                        html += `
                        <div class="flex items-center justify-between text-xs text-slate-500 px-1 pt-1 border-t border-slate-100">
                            <span>${currentLocale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:'}</span>
                            <span class="font-mono font-semibold text-slate-700">${doc.registration_number} ${doc.medical_council ? `(${doc.medical_council})` : ''}</span>
                        </div>
                    `;
                    }

                    if (doc.languages_spoken && doc.languages_spoken.length > 0) {
                        html += `
                        <div class="flex items-center space-x-2 text-xs text-slate-600 px-1">
                            <i data-lucide="languages" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
                            <span class="text-slate-400 text-[11px]">${currentLocale === 'hi' ? 'भाषाएँ:' : 'Languages:'}</span>
                            <span class="font-medium text-slate-700">${doc.languages_spoken.join(', ')}</span>
                        </div>
                    `;
                    }

                    html += `
                    <div class="text-slate-600 text-xs leading-relaxed bg-white flex-1 space-y-2">
                        <p class="line-clamp-3">${aboutText}</p>
                        ${doc.specialization_summary ? `<p class="text-[11px] text-slate-500 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 rounded-r-lg italic">${doc.specialization_summary}</p>` : ''}
                    </div>
                `;

                    if (doc.awards_recognitions && doc.awards_recognitions.length > 0) {
                        html += `
                        <div class="space-y-1 pt-2 border-t border-slate-100">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1">
                                <i data-lucide="trophy" class="w-3 h-3 text-amber-500"></i>
                                <span>${currentLocale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions'}</span>
                            </span>
                            <div class="text-[11px] text-slate-600 pl-4 list-disc space-y-0.5">
                                ${doc.awards_recognitions.map(award => `<div class="truncate">• ${award}</div>`).join('')}
                            </div>
                        </div>
                    `;
                    }

                    if (doc.hospitals && doc.hospitals.length > 0) {
                        html += `
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500"></i>
                                <span>${currentLocale === 'hi' ? 'अभ्यास स्थल एवं पता' : 'Practicing At & Location'}</span>
                            </h4>
                    `;
                        doc.hospitals.forEach(hosp => {
                            const hospName = currentLocale === 'hi' ? hosp.name.hi : hosp.name.en;
                            let schemesHtml = '';
                            let schemesList = [];
                            if (hosp.accepts_ayushman) schemesList.push(currentLocale === 'hi' ?
                                'आयुष्मान भारत' : 'Ayushman Bharat');
                            if (hosp.accepts_janaadhaar) schemesList.push(currentLocale === 'hi' ?
                                'जन आधार' : 'Jan Aadhaar');
                            if (hosp.accepts_cghs) schemesList.push('CGHS');
                            if (hosp.rgahs_approved) schemesList.push('RGAHS');
                            if (hosp.is_cashless) schemesList.push(currentLocale === 'hi' ?
                                'कैशलेस सुविधा' : 'Cashless Facility');
                            if (hosp.cashless_schemes_list && hosp.cashless_schemes_list.length > 0) {
                                hosp.cashless_schemes_list.forEach(s => {
                                    if (!schemesList.includes(s)) schemesList.push(s);
                                });
                            }

                            if (schemesList.length > 0) {
                                schemesHtml = `
                                    <div class="pt-2 mt-1 border-t border-slate-200/60 space-y-1.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1">
                                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-teal-600"></i>
                                            <span>${currentLocale === 'hi' ? 'उपलब्ध स्वास्थ्य योजनाएं व सुविधाएं:' : 'Available Health Schemes & Facilities:'}</span>
                                        </span>
                                        <div class="flex flex-wrap gap-1">
                                            ${schemesList.map(scheme => `<span class="text-[10px] bg-teal-50 text-teal-700 border border-teal-200/80 px-2.5 py-0.5 rounded-lg font-semibold shadow-2xs">${scheme}</span>`).join('')}
                                        </div>
                                    </div>
                                `;
                            }

                            html += `
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100/80 space-y-2 text-xs hover:border-slate-200 transition-colors shadow-2xs">
                                <div class="font-bold text-slate-900 flex justify-between items-start gap-2">
                                    <div>
                                        <span class="block text-sm text-indigo-950">${hospName}</span>
                                        ${hosp.type ? `<span class="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md inline-block mt-0.5">${hosp.type}</span>` : ''}
                                    </div>
                                    <span class="text-teal-700 shrink-0 font-extrabold bg-white px-2.5 py-1 rounded-xl border border-teal-100 shadow-2xs">
                                        ₹${hosp.pivot?.consultation_fee || doc.consultation_fee || 500}
                                    </span>
                                </div>
                                <p class="text-slate-600 text-[11px] leading-normal pt-1 border-t border-slate-200/60">
                                    <span class="font-semibold text-slate-700">${currentLocale === 'hi' ? 'पता:' : 'Address:'}</span> ${(hosp.address || '')} ${hosp.city ? `, ${hosp.city}` : ''}
                                </p>
                                <div class="flex items-center justify-between text-slate-500 text-[11px] pt-1">
                                    <span class="flex items-center space-x-1 pr-1 truncate">
                                        <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                        <span class="truncate">${hosp.pivot?.days_of_week || 'Mon - Sat'}</span>
                                    </span>
                                    <span class="font-semibold text-slate-600 shrink-0">
                                        ${hosp.pivot?.start_time || '10:00 AM'} - ${hosp.pivot?.end_time || '05:00 PM'}
                                    </span>
                                </div>
                                ${schemesHtml}
                                <div class="pt-2 mt-1 border-t border-slate-200/60 flex items-center justify-between gap-2">
                                    <span class="text-[10px] text-slate-400 italic">
                                        ${hosp.emergency_phone ? `${currentLocale === 'hi' ? 'संपर्क:' : 'Tel:'} ${hosp.emergency_phone}` : ''}
                                    </span>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent((hosp.address || '') + ', ' + (hosp.city || 'Jaipur'))}" target="_blank" class="inline-flex items-center space-x-1.5 text-xs text-indigo-600 hover:text-indigo-700 font-bold bg-indigo-50 hover:bg-indigo-100/80 px-3 py-1.5 rounded-xl border border-indigo-100 transition-all shadow-2xs">
                                        <i data-lucide="navigation" class="w-3.5 h-3.5 text-indigo-500"></i>
                                        <span>${currentLocale === 'hi' ? 'नक्शा व दिशा-निर्देश' : 'Get Directions'}</span>
                                    </a>
                                </div>
                            </div>
                        `;
                        });
                        html += `</div>`;
                    }

                    html += `
                        </div>
                        <div class="p-6 pt-0 flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                            <a href="${(doc.phone || emergencyPhone) ? `tel:${doc.phone || emergencyPhone}` : '#'}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                                <span>${currentLocale === 'hi' ? 'अभी कॉल करें' : 'Call Now'}</span>
                            </a>
                            ${doc.website ? `
                                                                <a href="${doc.website.startsWith('http') ? doc.website : 'https://' + doc.website}" target="_blank" class="w-full sm:flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                                                    <i data-lucide="globe" class="w-4 h-4 text-teal-400"></i>
                                                                    <span>${currentLocale === 'hi' ? 'वेबसाइट देखें' : 'Visit Website'}</span>
                                                                </a>
                                                            ` : ''}
                        </div>
                    </div>
                `;

                    grid.insertAdjacentHTML('beforeend', html);
                });

                lucide.createIcons();
            } catch (error) {
                console.error('Search fetch error:', error);
                document.getElementById('search-loading').classList.add('hidden');
            }
        }

        let selectedRating = 5;

        function setFeedbackRating(stars) {
            selectedRating = stars;
            document.getElementById('feedback-rating-input').value = stars;
            updateStarsDisplay(stars);
        }

        function hoverFeedbackRating(stars) {
            updateStarsDisplay(stars, true);
        }

        function resetFeedbackRatingHover() {
            updateStarsDisplay(selectedRating);
        }

        function updateStarsDisplay(stars, isHover = false) {
            for (let s = 1; s <= 5; s++) {
                const icon = document.getElementById(`star-icon-${s}`);
                if (icon) {
                    if (s <= stars) {
                        icon.classList.remove('text-slate-600', 'fill-transparent');
                        icon.classList.add('text-amber-400', 'fill-amber-400');
                    } else {
                        icon.classList.remove('text-amber-400', 'fill-amber-400');
                        icon.classList.add('text-slate-600', 'fill-transparent');
                    }
                }
            }
            const label = document.getElementById('rating-label-text');
        }
    </script>
@endpush
