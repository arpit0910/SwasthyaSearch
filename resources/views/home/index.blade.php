@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मुखपृष्ठ' : 'Home') . ' - SwasthyaSearch')

@section('meta_title', 'SwasthyaSearch - Find Doctors, Hospitals & Blood Banks Near You')
@section('meta_description', 'Search trusted doctors, hospitals, blood banks, and departments by city or symptoms. Connect directly with healthcare providers without ads or intermediaries.')
@section('content')
@php
$quickSymptoms =
$locale === 'hi'
? ['बुखार और खांसी', 'हड्डी का टूटना', 'छाती में दर्द']
: ['Fever and Cough', 'Bone Fracture', 'Chest Pain'];
@endphp

<!-- Hero Section -->
<header id="home-hero" class="relative overflow-hidden py-6 lg:py-9 mb-1 bg-gradient-to-b from-cyan-700 via-sky-700 to-blue-800 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950 text-white indian-motif-bg">
    <!-- Glowing background blobs -->
    <div class="glow-blob w-[400px] h-[400px] bg-teal-500/10 top-0 left-0"></div>
    <div class="glow-blob w-[500px] h-[500px] bg-cyan-500/10 bottom-0 right-0"></div>
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#67e8f9_1px,transparent_1px)] [background-size:18px_18px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="flex flex-wrap justify-center items-center gap-3 mb-4">
            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-teal-400/10 border border-teal-300/30 text-teal-200 shadow-inner">
                <i data-lucide="shield-check" class="w-4 h-4 text-teal-300"></i>
                <span class="text-xs font-semibold tracking-wider uppercase">{{ $locale === 'hi' ? 'भरोसेमंद हेल्थकेयर खोज' : 'Trusted Healthcare Discovery' }}</span>
            </div>
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-5xl mx-auto py-2 leading-[1.3] text-white drop-shadow-md mb-3">
            {{ $locale === 'hi' ? 'अपने पास भरोसेमंद डॉक्टर, अस्पताल और ब्लड बैंक खोजें' : 'Find trusted doctors, hospitals, and blood banks near you' }}
        </h1>
        <p class="text-lg sm:text-xl text-slate-200 max-w-4xl mx-auto font-normal leading-[1.7] mb-4">
            {{ $locale === 'hi' ? 'लक्षण, विभाग, अस्पताल, ब्लड बैंक या शहर से खोजें। बिना विज्ञापन और बिना बिचौलियों के सीधे संपर्क करें।' : 'Search by symptoms, department, hospital, blood bank, or city. Connect directly without ads or intermediaries.' }}
        </p>

        <!-- Omni-Search Box -->
        <div class="mt-7 max-w-3xl mx-auto px-4 sm:px-0">
            <div
                id="hero-search-shell"
                class="relative flex items-center bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl p-2 sm:p-3 border border-white/20 focus-within:ring-4 focus-within:ring-teal-500/30 transition-all duration-300 focus-within:border-teal-400/50 overflow-hidden aurora-border">
                <i data-lucide="search"
                    class="absolute left-4 sm:left-6 w-5 h-5 sm:w-6 sm:h-6 text-slate-300 pointer-events-none"></i>
                <input type="text" id="omni-search-input" oninput="handleOmniSearch(this.value)"
                    placeholder="{{ $locale === 'hi' ? 'डॉक्टर, अस्पताल, ब्लड बैंक, लक्षण खोजें...' : 'Search doctors, hospitals, blood banks, symptoms...' }}"
                    class="w-full pl-11 sm:pl-16 pr-4 py-3.5 sm:py-4 text-white bg-transparent text-base sm:text-lg font-medium placeholder:text-slate-300 appearance-none border-0 shadow-none ring-0 focus:outline-none focus:ring-0 focus:border-0" />
                <button id="clear-search-btn" onclick="clearOmniSearch()"
                    class="hidden mr-3 px-3 py-1.5 text-xs text-slate-200 hover:text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-200 border border-white/10">
                    {{ $locale === 'hi' ? 'साफ़ करें' : 'Clear' }}
                </button>
            </div>

            <!-- Interactive Symptom Grid -->
            <div class="mt-5 pt-2">
                <p class="text-[11px] sm:text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">{{ $locale === 'hi' ? 'लक्षणों द्वारा त्वरित खोज' : 'Quick Search by Symptoms' }}</p>
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    @php
                    $symptoms = [
                    ['id' => 'fever', 'en' => 'Fever', 'hi' => 'बुखार', 'icon' => 'thermometer', 'color' => 'text-rose-450 bg-rose-500/10 border-rose-500/20'],
                    ['id' => 'cough', 'en' => 'Cough', 'hi' => 'खांसी', 'icon' => 'wind', 'color' => 'text-cyan-450 bg-cyan-500/10 border-cyan-500/20'],
                    ['id' => 'stomach-pain', 'en' => 'Stomach Pain', 'hi' => 'पेट दर्द', 'icon' => 'shield-alert', 'color' => 'text-amber-450 bg-amber-500/10 border-amber-500/20'],
                    ['id' => 'skin-rash', 'en' => 'Skin Rash', 'hi' => 'त्वचा चकत्ते', 'icon' => 'sparkles', 'color' => 'text-teal-450 bg-teal-500/10 border-teal-500/20'],
                    ['id' => 'joint-pain', 'en' => 'Joint Pain', 'hi' => 'जोड़ों का दर्द', 'icon' => 'activity', 'color' => 'text-indigo-450 bg-cyan-500/10 border-indigo-500/20'],
                    ['id' => 'headache', 'en' => 'Headache', 'hi' => 'सिरदर्द', 'icon' => 'brain', 'color' => 'text-fuchsia-450 bg-fuchsia-500/10 border-fuchsia-500/20']
                    ];
                    @endphp
                    @foreach($symptoms as $symptom)
                    <button type="button" onclick="selectSymptom('{{ $locale === 'hi' ? $symptom['hi'] : $symptom['en'] }}')" class="flex flex-col items-center p-3 rounded-2xl bg-white/5 hover:bg-white/15 border border-white/10 hover:border-white/30 hover:scale-105 transition-all duration-300 group shadow-md backdrop-blur-md">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2 {{ $symptom['color'] }} border group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="{{ $symptom['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-200 group-hover:text-white transition-colors truncate max-w-full">{{ $locale === 'hi' ? $symptom['hi'] : $symptom['en'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-white/10 max-w-4xl mx-auto text-center">
                <div class="flex flex-wrap justify-center gap-2.5 items-center">
                    <a href="{{ route('doctors.index') }}" class="px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 text-xs sm:text-sm font-semibold">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctor' }}</a>
                    <a href="{{ route('hospitals.index') }}" class="px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 text-xs sm:text-sm font-semibold">{{ $locale === 'hi' ? 'अस्पताल खोजें' : 'Find Hospital' }}</a>
                    <a href="{{ route('blood_banks.index') }}" class="px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 text-xs sm:text-sm font-semibold">{{ $locale === 'hi' ? 'ब्लड बैंक खोजें' : 'Find Blood Bank' }}</a>
                    <a href="{{ route('symptom-test') }}" class="px-4 py-2 rounded-2xl bg-teal-400 hover:bg-teal-300 border border-teal-300/50 text-xs sm:text-sm font-semibold text-slate-950">{{ $locale === 'hi' ? 'लक्षण परीक्षण' : 'Symptom Test' }}</a>
                    <button type="button" onclick="toggleChatbot()" class="px-4 py-2 rounded-2xl bg-cyan-400 hover:bg-cyan-300 dark:bg-cyan-700 dark:hover:bg-cyan-600 border border-cyan-300/50 dark:border-cyan-500/40 text-xs sm:text-sm font-semibold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'AI सहायक से पूछें' : 'Ask AI Assistant' }}</button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content Area -->
<main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 w-full">
    <!-- Search Match Indicators (Dynamic) -->
    <div id="search-match-container"
        class="hidden mb-12 bg-gradient-to-r from-teal-500/10 via-indigo-500/10 to-transparent p-5 sm:p-6 rounded-3xl border border-teal-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-teal-500 text-white rounded-2xl shadow-md">
                <i data-lucide="stethoscope" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    {{ $locale === 'hi' ? 'लक्षण विश्लेषण और विभाग मिलान' : 'Symptom Analysis & Department Match' }}
                </h3>
                <p class="text-sm text-slate-600 mt-0.5" id="search-match-text"></p>
            </div>
        </div>
        <span
            class="text-xs font-semibold bg-white dark:bg-slate-900 text-teal-700 dark:text-teal-300 px-3 py-1.5 rounded-xl shadow-sm border border-teal-100 dark:border-teal-900/70">
            {{ $locale === 'hi' ? 'एआई द्वारा सत्यापित' : 'AI Verified Match' }}
        </span>
    </div>

    <!-- Search Results View (Dynamic) -->
    <div id="search-results-view" class="hidden">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ $locale === 'hi' ? 'खोज परिणाम' : 'Search Results' }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ $locale === 'hi' ? 'बिना किसी विज्ञापन या मध्यस्थ के सीधे संपर्क करें' : 'Direct contact details with zero ads or intermediaries' }}
                </p>
            </div>
            <span
                class="text-sm font-semibold text-cyan-600 dark:text-indigo-400 bg-cyan-50 dark:bg-indigo-950/40 px-3 py-1.5 rounded-xl border border-cyan-100 dark:border-indigo-900"
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
            class="hidden py-20 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-8 shadow-sm max-w-xl mx-auto space-y-4">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="search" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">
                {{ $locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Specialists Found' }}
            </h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm">
                {{ $locale === 'hi' ? 'आपकी खोज से मेल खाने वाले कोई डॉक्टर या अस्पताल नहीं मिले। कृपया किसी अन्य लक्षण या विभाग से खोजें।' : 'We couldn\'t find any healthcare providers matching your exact criteria. Try searching with different symptom keywords.' }}
            </p>
        </div>
    </div>

    <!-- Default Homepage View: Health News, Articles & FAQs -->
    <div id="default-view" class="space-y-16">
        <!-- Directory Statistics / Informative Overview -->
        <div>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <div
                        class="inline-flex items-center space-x-2 text-teal-600 font-bold text-sm uppercase tracking-wider mb-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'स्वास्थ्या सर्च एक नज़र में' : 'SwasthyaSearch at a Glance' }}</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $locale === 'hi' ? 'हमारा विस्तृत और प्रमाणित स्वास्थ्य नेटवर्क' : 'Our Extensive & Verified Healthcare Network' }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $locale === 'hi' ? 'राजस्थान के सर्वश्रेष्ठ डॉक्टरों और अस्पतालों की सम्पूर्ण जानकारी' : 'Comprehensive directory coverage across top healthcare institutions and specialists' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Cities Count -->
                <a href="{{ route('hospitals.index') }}"
                    class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group block ring-1 ring-white/15 hover:ring-white/35 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300/80"
                    aria-label="{{ $locale === 'hi' ? 'शहर और क्षेत्रों के लिए अस्पताल खोजें' : 'Browse hospitals by cities and regions' }}">
                    <div
                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/15 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div
                            class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/25 shadow-lg">
                            <i data-lucide="map-pin" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                {{ $stats['cities'] ?? 1 }}+
                            </div>
                            <div class="text-indigo-100 text-xs sm:text-sm font-medium mt-1">
                                {{ $locale === 'hi' ? 'शहर व क्षेत्र' : 'Cities & Regions' }}
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Doctors Count -->
                <a href="{{ route('doctors.index') }}"
                    class="bg-gradient-to-br from-teal-500 via-teal-600 to-teal-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group block ring-1 ring-white/15 hover:ring-white/35 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-teal-300/80"
                    aria-label="{{ $locale === 'hi' ? 'सत्यापित डॉक्टर देखें' : 'Browse verified doctors' }}">
                    <div
                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/15 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div
                            class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/25 shadow-lg">
                            <i data-lucide="users" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                {{ $stats['doctors'] ?? 120 }}+
                            </div>
                            <div class="text-teal-100 text-xs sm:text-sm font-medium mt-1">
                                {{ $locale === 'hi' ? 'सत्यापित डॉक्टर' : 'Verified Specialists' }}
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Departments Count -->
                <a href="{{ route('departments.index') }}"
                    class="bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group block ring-1 ring-white/15 hover:ring-white/35 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-300/80"
                    aria-label="{{ $locale === 'hi' ? 'चिकित्सा विभाग देखें' : 'Browse medical departments' }}">
                    <div
                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/15 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div
                            class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/25 shadow-lg">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                {{ $stats['departments'] ?? 10 }}+
                            </div>
                            <div class="text-amber-100 text-xs sm:text-sm font-medium mt-1">
                                {{ $locale === 'hi' ? 'चिकित्सा विभाग' : 'Medical Departments' }}
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Hospitals Count -->
                <a href="{{ route('hospitals.index') }}"
                    class="bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group block ring-1 ring-white/15 hover:ring-white/35 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-300/80"
                    aria-label="{{ $locale === 'hi' ? 'अस्पताल और क्लीनिक देखें' : 'Browse hospitals and clinics' }}">
                    <div
                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/15 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div
                            class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/25 shadow-lg">
                            <i data-lucide="building-2" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                {{ $stats['hospitals'] ?? 20 }}+
                            </div>
                            <div class="text-purple-100 text-xs sm:text-sm font-medium mt-1">
                                {{ $locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics' }}
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Blood Banks Count -->
                <a href="{{ route('blood_banks.index') }}"
                    class="bg-gradient-to-br from-red-500 via-red-600 to-rose-700 p-6 rounded-3xl text-white shadow-xl relative overflow-hidden group block ring-1 ring-white/15 hover:ring-white/35 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-300/80"
                    aria-label="{{ $locale === 'hi' ? 'ब्लड बैंक देखें' : 'Browse blood banks' }}">
                    <div
                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500">
                    </div>
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/15 to-transparent"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full space-y-4">
                        <div
                            class="p-3 bg-white/20 rounded-2xl w-12 h-12 flex items-center justify-center backdrop-blur-md border border-white/25 shadow-lg">
                            <i data-lucide="droplet" class="w-6 h-6 text-white fill-white"></i>
                        </div>
                        <div>
                            <div class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                                {{ $stats['blood_banks'] ?? 8 }}+
                            </div>
                            <div class="text-red-100 text-xs sm:text-sm font-medium mt-1">
                                {{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Compact Trust & Schemes Strip -->
        <section class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white/80 dark:bg-slate-900/70 backdrop-blur-sm p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-teal-700 dark:text-teal-300">
                        {{ $locale === 'hi' ? '100% निःशुल्क और पारदर्शी' : '100% Free & Transparent' }}
                    </p>
                    <p class="text-sm sm:text-base font-semibold text-slate-900 dark:text-white mt-1">
                        {{ $locale === 'hi' ? 'आयुष्मान भारत और जन आधार कार्ड स्वीकृत' : 'Ayushman Bharat & Jan Aadhaar Cards Accepted' }}
                    </p>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-1">
                        {{ $locale === 'hi' ? 'सत्यापित अस्पतालों और डॉक्टरों से सीधे जुड़ें। न बिचौलिया, न बुकिंग शुल्क, और सरकारी स्वास्थ्य योजनाओं का पूरा समर्थन।' : 'Connect directly with verified hospitals and doctors. Zero intermediaries, zero booking fees, and full support for government health schemes.' }}
                    </p>
                </div>
                <div class="flex items-center gap-2.5 flex-nowrap overflow-x-auto whitespace-nowrap pb-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ $locale === 'hi' ? 'आयुष्मान भारत' : 'Ayushman Bharat' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-900 text-xs font-bold text-sky-700 dark:text-sky-300">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-950/30 border border-violet-200 dark:border-violet-900 text-xs font-bold text-violet-700 dark:text-violet-300">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ $locale === 'hi' ? 'कैशलेस बीमा' : 'Cashless Insurance' }}
                    </span>
                </div>
            </div>
        </section>
        
        <!-- How It Works Section -->
        <div>
            <div class="mb-8">
                <div
                    class="inline-flex items-center space-x-2 text-cyan-600 font-bold text-sm uppercase tracking-wider mb-2">
                    <i data-lucide="workflow" class="w-4 h-4"></i>
                    <span>{{ $locale === 'hi' ? 'कैसे काम करता है' : 'How It Works' }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ $locale === 'hi' ? 'सही डॉक्टर तक पहुँचने के 3 आसान चरण' : '3 Simple Steps to Reach the Right Doctor' }}
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <article class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm modern-card">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-100 text-cyan-700 flex items-center justify-center mb-4">
                        <i data-lucide="message-square-text" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ $locale === 'hi' ? '1. लक्षण लिखें' : '1. Enter Symptoms' }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ $locale === 'hi' ? 'अपनी समस्या सामान्य भाषा में लिखें, जैसे छाती में दर्द या बुखार।' : 'Type your concern in plain language like chest pain or fever.' }}
                    </p>
                </article>
                <article class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm modern-card">
                    <div class="w-11 h-11 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                        <i data-lucide="brain-circuit" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ $locale === 'hi' ? '2. विभाग सुझाव पाएँ' : '2. Get Department Match' }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ $locale === 'hi' ? 'AI आपके लक्षणों को संबंधित मेडिकल विभाग से जोड़ता है।' : 'AI maps your symptoms to the most relevant medical specialty.' }}
                    </p>
                </article>
                <article class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm modern-card">
                    <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                        <i data-lucide="phone-call" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        {{ $locale === 'hi' ? '3. डॉक्टर से जुड़ें' : '3. Connect with Doctors' }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ $locale === 'hi' ? 'अपने शहर के सत्यापित डॉक्टर/अस्पताल चुनें और तुरंत संपर्क करें।' : 'Choose verified doctors and hospitals in your city and contact them directly.' }}
                    </p>
                </article>
            </div>
        </div>
        
        <section class="rounded-3xl border border-rose-200/80 dark:border-rose-900/60 bg-gradient-to-r from-rose-50 via-white to-amber-50 dark:from-rose-950/25 dark:via-slate-900 dark:to-amber-950/25 p-5 sm:p-6 shadow-sm modern-card">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-[11px] uppercase tracking-wider text-rose-700 dark:text-rose-300 font-bold">{{ $locale === 'hi' ? 'आपातकालीन सहायता' : 'Emergency Quick Actions' }}</p>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $locale === 'hi' ? 'एक टैप में मदद पाएं' : 'Get Help in One Tap' }}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 w-full md:w-auto">
                    <a href="tel:108" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-md">
                        <i data-lucide="ambulance" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'एम्बुलेंस 108 कॉल करें' : 'Call Ambulance 108' }}</span>
                    </a>
                    <a href="{{ route('hospitals.index', ['search' => 'emergency']) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm font-semibold">
                        <i data-lucide="hospital" class="w-4 h-4 text-cyan-600 dark:text-indigo-400"></i>
                        <span>{{ $locale === 'hi' ? 'नजदीकी ER खोजें' : 'Nearest ER Search' }}</span>
                    </a>
                    <a href="{{ route('blood_banks.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-sm font-semibold">
                        <i data-lucide="droplet" class="w-4 h-4 text-red-600 dark:text-red-400"></i>
                        <span>{{ $locale === 'hi' ? 'नजदीकी ब्लड बैंक' : 'Blood Bank Nearby' }}</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-gradient-to-r from-teal-50/80 via-white to-indigo-50/80 dark:from-teal-950/25 dark:via-slate-900 dark:to-indigo-950/25 p-5 sm:p-6 shadow-sm modern-card">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                <div class="rounded-2xl border border-teal-200/70 dark:border-teal-900/70 bg-white/80 dark:bg-slate-900/70 p-4">
                    <p class="text-[11px] uppercase tracking-wider text-teal-700 dark:text-teal-300 font-bold">{{ $locale === 'hi' ? 'भारत के लिए बनाया गया' : 'Built for India' }}</p>
                    <p class="text-sm text-slate-800 dark:text-slate-100 font-semibold mt-1">{{ $locale === 'hi' ? '100% विज्ञापन-मुक्त स्वास्थ्य खोज' : '100% Ad-Free Healthcare Search' }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200/70 dark:border-emerald-900/70 bg-white/80 dark:bg-slate-900/70 p-4">
                    <div class="flex items-center gap-2 text-slate-800 dark:text-slate-100 text-sm font-semibold">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"></i>
                        <span>{{ $locale === 'hi' ? 'सत्यापित सूची' : 'Verified Listings' }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1.5">{{ $locale === 'hi' ? 'बिना पेड प्लेसमेंट के पारदर्शी रैंकिंग।' : 'Transparent ranking with no paid placements.' }}</p>
                </div>
                <div class="rounded-2xl border border-cyan-200/70 dark:border-cyan-900/70 bg-white/80 dark:bg-slate-900/70 p-4">
                    <div class="flex items-center gap-2 text-slate-800 dark:text-slate-100 text-sm font-semibold">
                        <i data-lucide="lock" class="w-4 h-4 text-cyan-600 dark:text-cyan-400"></i>
                        <span>{{ $locale === 'hi' ? 'गोपनीयता का सम्मान' : 'Privacy Respecting' }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1.5">{{ $locale === 'hi' ? 'कोई विज्ञापन नहीं, कोई ब्रोकर कॉल नहीं, सीधे प्रदाता से संपर्क।' : 'No ads, no broker calls, direct provider contact.' }}</p>
                </div>
            </div>
        </section>

        <!-- Articles Section -->
        <div>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <div
                        class="inline-flex items-center space-x-2 text-cyan-600 font-bold text-sm uppercase tracking-wider mb-2">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'स्वास्थ्य ज्ञान और समाचार' : 'Health Knowledge & News' }}</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        {{ $locale === 'hi' ? 'नवीनतम चिकित्सा लेख और स्वास्थ्य सुझाव' : 'Latest Medical Articles & Wellness Tips' }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        {{ $locale === 'hi' ? 'विशेषज्ञ डॉक्टरों द्वारा प्रमाणित स्वास्थ्य सलाह और जीवनशैली मार्गदर्शन' : 'Expert-verified health advice and lifestyle guidance from top practitioners' }}
                    </p>
                </div>
                <a href="{{ route('articles.index') }}"
                    class="hidden sm:inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span>{{ $locale === 'hi' ? 'सभी लेख देखें' : 'View All Articles' }}</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($articles->take(6) as $article)
                @php
                $title = $locale === 'hi' ? $article->title_hi : $article->title_en;
                $excerpt = $locale === 'hi' ? $article->excerpt_hi : $article->excerpt_en;
                @endphp
                <a href="{{ route('articles.show', $article->id) }}"
                    class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-teal-200 dark:hover:border-teal-700 transition-all duration-300 flex flex-col overflow-hidden group modern-card">
                    <div
                        class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-b from-slate-50/50 dark:from-slate-800/20 to-transparent flex justify-between items-center">
                        <span
                            class="text-xs font-bold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-900/30 px-3 py-1 rounded-full border border-teal-100 dark:border-teal-800">
                            {{ $article->category }}
                        </span>
                        <div class="flex items-center space-x-1 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            <span>{{ $locale === 'hi' ? '3 मिनट पढ़ें' : '3 min read' }}</span>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h3
                                class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors leading-snug">
                                {{ $title }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                                {{ $excerpt }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 italic">
                                {{ $locale === 'hi' ? 'लेखक' : 'By' }}: {{ $article->author_name }}
                            </span>
                            <span
                                class="text-xs font-bold text-cyan-600 group-hover:translate-x-1 transition-transform flex items-center space-x-1">
                                <span>{{ $locale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article' }}</span>
                                <span>→</span>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="mt-6 sm:hidden">
                <a href="{{ route('articles.index') }}"
                    class="w-full inline-flex items-center justify-center space-x-1.5 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <span>{{ $locale === 'hi' ? 'सभी लेख देखें' : 'View All Articles' }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- FAQs Section -->
        @if ($faqs->isNotEmpty())
        <div class="pt-12 border-t border-slate-200/80 dark:border-slate-800">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    {{ $locale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions' }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
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
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden transition-all duration-200">
                    <button onclick="toggleFaq({{ $idx }})"
                        class="w-full p-5 text-left font-bold text-base text-slate-800 dark:text-slate-100 flex justify-between items-center hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                        <span>{{ $question }}</span>
                        <i data-lucide="chevron-down" id="faq-icon-{{ $idx }}"
                            class="w-5 h-5 text-slate-400 dark:text-slate-500 shrink-0 transition-transform duration-200"></i>
                    </button>
                    <div id="faq-content-{{ $idx }}"
                        class="hidden p-5 pt-0 text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20">
                        {{ $answer }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- User Feedback Form Section -->
        <div class="pt-12 border-t border-slate-200/80 dark:border-slate-800">
            <div
                class="bg-gradient-to-br from-white via-slate-50 to-cyan-50/40 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/30 rounded-3xl p-7 sm:p-11 text-slate-900 dark:text-slate-100 shadow-xl relative overflow-hidden border border-slate-200/80 dark:border-slate-700/70 ring-1 ring-slate-100/80 dark:ring-slate-700/40">
                <div
                    class="absolute inset-0 opacity-[0.08] dark:opacity-[0.10] bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]">
                </div>
                <div class="relative z-10 max-w-3xl mx-auto">
                    <div class="text-center mb-10">
                        <div
                            class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-teal-500/10 dark:bg-teal-500/15 border border-teal-500/30 text-teal-700 dark:text-teal-300 mb-4 shadow-inner text-xs font-semibold uppercase tracking-wider">
                            <i data-lucide="message-square" class="w-4 h-4 text-teal-400"></i>
                            <span>{{ $locale === 'hi' ? 'आपकी राय महत्वपूर्ण है' : 'Your Opinion Matters' }}</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                            {{ $locale === 'hi' ? 'हमें अपना फीडबैक दें' : 'Share Your Feedback With Us' }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 max-w-xl mx-auto leading-relaxed">
                            {{ $locale === 'hi' ? 'आपके सुझावों से हम स्वास्थ्या सर्च को और बेहतर बनाने के लिए निरंतर प्रयासरत हैं।' : 'Help us improve our healthcare directory. Tell us about your experience searching for doctors and hospitals.' }}
                        </p>
                    </div>

                    <form action="{{ route('feedback.submit') }}" method="POST"
                        class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="feedback-name"
                                    class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">
                                    {{ $locale === 'hi' ? 'आपका नाम' : 'Your Name' }} <span
                                        class="text-teal-400">*</span>
                                </label>
                                <input type="text" id="feedback-name" name="name" required
                                    placeholder="{{ $locale === 'hi' ? 'नाम दर्ज करें' : 'Enter your name' }}"
                                    class="w-full px-4 py-3 bg-white/80 dark:bg-slate-900/70 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm" />
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">
                                    {{ $locale === 'hi' ? 'रेटिंग' : 'Rating' }}
                                </label>
                                <input type="hidden" id="feedback-rating-input" name="rating" value="" />
                                <div class="flex items-center space-x-1.5 py-1.5" id="star-rating-container">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <button type="button" onclick="setFeedbackRating({{ $s }})"
                                        class="p-2 rounded-xl bg-white dark:bg-slate-900/70 hover:bg-white dark:hover:bg-slate-900 border border-slate-300 dark:border-slate-700 hover:border-amber-400/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400/50 shadow-inner"
                                        title="{{ $s }} Star">
                                        <i data-lucide="star"
                                            class="w-6 h-6 text-slate-600 fill-transparent transition-colors duration-200"
                                            id="star-icon-{{ $s }}"></i>
                                        </button>
                                        @endfor
                                </div></div>
                        </div>

                        <div>
                            <label for="feedback-category"
                                class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ $locale === 'hi' ? 'फीडबैक श्रेणी' : 'Feedback Category' }} <span
                                    class="text-teal-400">*</span>
                            </label>
                            <div class="relative">
                                <select id="feedback-category" name="category" required
                                    class="w-full pl-4 pr-10 py-3 bg-white/80 dark:bg-slate-900/70 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white appearance-none focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm cursor-pointer">
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
                                        {{ $locale === 'hi' ? 'सामान्य सुझाव' : 'General Suggestion' }}
                                    </option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="feedback-comments"
                                class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">
                                {{ $locale === 'hi' ? 'आपके सुझाव या विचार' : 'Your Comments & Suggestions' }} <span
                                    class="text-teal-400">*</span>
                            </label>
                            <textarea id="feedback-comments" name="comments" rows="4" required
                                placeholder="{{ $locale === 'hi' ? 'अपने विचार यहाँ लिखें...' : 'Please let us know how we can improve...' }}"
                                class="w-full px-4 py-3 bg-white/80 dark:bg-slate-900/70 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors text-sm"></textarea>
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

    let allSearchDoctors = [];
    let activeFilters = {
        ayushman: false,
        cashless: false,
        verified: false
    };

    function selectSymptom(name) {
        const input = document.getElementById('omni-search-input');
        input.value = name;
        handleOmniSearch(name);
        const resultsView = document.getElementById('search-results-view');
        if (resultsView) {
            resultsView.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    function toggleQuickFilter(type) {
        activeFilters[type] = !activeFilters[type];

        const btn = document.getElementById('filter-' + type);
        if (activeFilters[type]) {
            btn.classList.add('bg-teal-50', 'dark:bg-teal-950/40', 'border-teal-500', 'text-teal-700', 'dark:text-teal-300');
            btn.classList.remove('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-300');
        } else {
            btn.classList.remove('bg-teal-50', 'dark:bg-teal-950/40', 'border-teal-500', 'text-teal-700', 'dark:text-teal-300');
            btn.classList.add('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-300');
        }

        applyFiltersAndRender();
    }

    function applyFiltersAndRender() {
        let filtered = allSearchDoctors;

        if (activeFilters.ayushman) {
            filtered = filtered.filter(doc => {
                return doc.hospitals && doc.hospitals.some(h => h.accepts_ayushman || (h.pivot && h.pivot.accepts_ayushman));
            });
        }

        if (activeFilters.cashless) {
            filtered = filtered.filter(doc => {
                return doc.hospitals && doc.hospitals.some(h => h.is_cashless || (h.pivot && h.pivot.is_cashless));
            });
        }

        if (activeFilters.verified) {
            filtered = filtered.filter(doc => doc.is_verified);
        }

        renderDoctorsList(filtered);
    }

    function getWhatsAppLink(phone, docName) {
        if (!phone) return '#';
        let cleanPhone = phone.replace(/\D/g, '');
        if (cleanPhone.length === 10) {
            cleanPhone = '91' + cleanPhone;
        }
        if (cleanPhone.length === 11 && cleanPhone.startsWith('0')) {
            cleanPhone = '91' + cleanPhone.substring(1);
        }
        const message = encodeURIComponent(`Hello ${docName}, I found your profile on SwasthyaSearch and would like to inquire about consultation timings and availability.`);
        return `https://wa.me/${cleanPhone}?text=${message}`;
    }

    function renderDoctorsList(doctors) {
        const grid = document.getElementById('doctors-grid');
        grid.innerHTML = '';

        const countBadge = document.getElementById('doctors-count-badge');
        countBadge.innerText =
            `${doctors ? doctors.length : 0} ${currentLocale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found'}`;

        if (!doctors || doctors.length === 0) {
            document.getElementById('no-results-container').classList.remove('hidden');
            return;
        } else {
            document.getElementById('no-results-container').classList.add('hidden');
        }

        doctors.forEach(doc => {
            const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
            const deptName = doc.department ? (currentLocale === 'hi' ? doc.department.name.hi : doc
                .department.name.en) : '';
            const emergencyPhone = doc.hospitals?.[0]?.phone_1 || doc.hospitals?.[0]?.phone_2 || doc.hospitals?.[0]?.phone || '';
            const doctorPhone = doc.phone_1 || doc.phone_2 || doc.phone || '';
            const aboutText = doc.about ? (currentLocale === 'hi' ? doc.about.hi : doc.about.en) : '';

            const detail2Text = doc.hospitals && doc.hospitals.length > 0 ? (currentLocale === 'hi' ? (doc.hospitals[0].name.hi || doc.hospitals[0].name.en) : doc.hospitals[0].name.en) : '';
            const expLabel = currentLocale === 'hi' ? 'वर्ष अनुभव' : 'Yrs Exp';
            const detail1Text = `${deptName} • ${doc.experience_years} ${expLabel}`;

            let html = `
                <div class="glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group relative">
                    <div class="p-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-start bg-gradient-to-b from-slate-50/50 to-transparent dark:from-slate-800/30">
                        <div class="flex items-center space-x-3">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-teal-500 text-white flex items-center justify-center font-bold text-xl shadow-md group-hover:scale-105 transition-all duration-300">
                                ${doc.first_name ? doc.first_name.charAt(0) : '<i data-lucide="user" class="w-6 h-6"></i>'}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-indigo-400 transition-colors flex items-start space-x-1.5">
                                    <span class="line-clamp-3 leading-snug">${fullName}</span>
                                    ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 dark:text-teal-400 shrink-0 mt-1" title="Verified Provider"></i>' : ''}
                                </h3>
                                <p class="text-xs font-semibold text-teal-600 dark:text-teal-400 flex items-center space-x-1 mt-0.5">
                                    <i data-lucide="stethoscope" class="w-3.5 h-3.5"></i>
                                    <span>${deptName}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col space-y-4">
                        <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                                <i data-lucide="award" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                                <div class="truncate">
                                    <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase">${currentLocale === 'hi' ? 'अनुभव' : 'Experience'}</span>
                                    <span class="text-slate-900 dark:text-white font-bold">${doc.experience_years} ${currentLocale === 'hi' ? 'वर्ष' : 'Years'}</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                                <i data-lucide="file-text" class="w-4 h-4 text-teal-500 shrink-0"></i>
                                <div class="truncate">
                                    <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase">${currentLocale === 'hi' ? 'परामर्श शुल्क' : 'Fee'}</span>
                                    <span class="text-slate-900 dark:text-white font-bold">Rs. ${doc.consultation_fee || 500}</span>
                                </div>
                            </div>
                        </div>
                `;

            const isPlaceholderReg = doc.registration_number && (
                doc.registration_number.startsWith('REG-') ||
                doc.registration_number.startsWith('RAJ-MC-') ||
                doc.registration_number.startsWith('MMC-') ||
                doc.registration_number.startsWith('DMC-') ||
                doc.registration_number.startsWith('JOD-') ||
                doc.registration_number.startsWith('KOT-')
            );

            if (doc.registration_number && !isPlaceholderReg) {
                html += `
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 px-1 pt-1 border-t border-slate-100 dark:border-slate-800">
                        <span>${currentLocale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:'}</span>
                        <span class="font-mono font-semibold text-slate-700 dark:text-slate-300">${doc.registration_number} ${doc.medical_council ? `(${doc.medical_council})` : ''}</span>
                    </div>
                `;
            }

            if (doc.languages_spoken && doc.languages_spoken.length > 0) {
                html += `
                    <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-350 px-1">
                        <i data-lucide="languages" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
                        <span class="text-slate-400 dark:text-slate-500 text-[11px]">${currentLocale === 'hi' ? 'भाषाएँ:' : 'Languages:'}</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">${doc.languages_spoken.join(', ')}</span>
                    </div>
                `;
            }

            html += `
                <div class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed flex-1 space-y-2">
                    <p class="line-clamp-3">${aboutText}</p>
                    ${doc.specialization_summary ? `<p class="text-[11px] text-slate-500 dark:text-slate-400 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 dark:bg-slate-800/40 rounded-r-lg italic">${doc.specialization_summary}</p>` : ''}
                </div>
            `;

            if (doc.awards_recognitions && doc.awards_recognitions.length > 0) {
                html += `
                    <div class="space-y-1 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1">
                            <i data-lucide="trophy" class="w-3 h-3 text-amber-500"></i>
                            <span>${currentLocale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions'}</span>
                        </span>
                        <div class="text-[11px] text-slate-600 dark:text-slate-400 pl-4 list-disc space-y-0.5">
                            ${doc.awards_recognitions.map(award => `<div class="truncate">• ${award}</div>`).join('')}
                        </div>
                    </div>
                `;
            }

            if (doc.hospitals && doc.hospitals.length > 0) {
                html += `
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
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
                                <div class="pt-2 mt-1 border-t border-slate-200/60 dark:border-slate-700/60 space-y-1.5">
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-teal-600 dark:text-teal-400"></i>
                                        <span>${currentLocale === 'hi' ? 'उपलब्ध स्वास्थ्य योजनाएं व सुविधाएं:' : 'Available Health Schemes & Facilities:'}</span>
                                    </span>
                                    <div class="flex flex-wrap gap-1">
                                        ${schemesList.map(scheme => `<span class="text-[10px] bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200/80 dark:border-teal-800/80 px-2.5 py-0.5 rounded-lg font-semibold shadow-2xs">${scheme}</span>`).join('')}
                                    </div>
                                </div>
                            `;
                    }

                    html += `
                        <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 space-y-2 text-xs hover:border-slate-200 dark:hover:border-slate-600 transition-colors shadow-2xs">
                            <div class="font-bold text-slate-900 dark:text-white flex justify-between items-start gap-2">
                                <div>
                                    <span class="block text-sm text-indigo-950 dark:text-indigo-200">${hospName}</span>
                                    ${hosp.type ? `<span class="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 dark:bg-teal-950/40 border border-teal-100 dark:border-teal-900/60 px-2 py-0.5 rounded-md inline-block mt-0.5">${hosp.type}</span>` : ''}
                                </div>
                                <span class="text-teal-700 dark:text-teal-400 shrink-0 font-extrabold bg-white dark:bg-slate-800 px-2.5 py-1 rounded-xl border border-teal-100 dark:border-teal-800 shadow-2xs">
                                    Rs. ${hosp.pivot?.consultation_fee || doc.consultation_fee || 500}
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-350 text-[11px] leading-normal pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">${currentLocale === 'hi' ? 'पता:' : 'Address:'}</span> ${(hosp.address || '')} ${hosp.city ? `, ${hosp.city}` : ''}
                            </p>
                            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 text-[11px] pt-1">
                                <span class="flex items-center space-x-1 pr-1 truncate">
                                    <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                    <span class="truncate">${hosp.pivot?.days_of_week || 'Mon - Sat'}</span>
                                </span>
                                <span class="font-semibold text-slate-600 dark:text-slate-300 shrink-0">
                                    ${hosp.pivot?.start_time || '10:00 AM'} - ${hosp.pivot?.end_time || '05:00 PM'}
                                </span>
                            </div>
                            ${schemesHtml}
                            <div class="pt-2 mt-1 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between gap-2">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">
                                    ${(hosp.phone_1 || hosp.phone_2 || hosp.phone) ? `${currentLocale === 'hi' ? 'संपर्क:' : 'Tel:'} ${[hosp.phone_1, hosp.phone_2, hosp.phone].filter(Boolean).join(', ')}` : ''}
                                </span>
                                <a href="${hosp.map_directions_url || '#'}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1.5 text-xs text-cyan-600 dark:text-indigo-400 hover:text-cyan-700 dark:hover:text-indigo-300 font-bold bg-cyan-50 dark:bg-indigo-950/40 hover:bg-indigo-100/80 dark:hover:bg-indigo-900/60 px-3 py-1.5 rounded-xl border border-cyan-100 dark:border-indigo-900 transition-all shadow-2xs ${hosp.map_directions_url ? '' : 'pointer-events-none opacity-50'}">
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
                    <div class="p-6 pt-0 flex flex-col gap-2.5">
                        <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                            <a href="${(doctorPhone || emergencyPhone) ? `tel:${doctorPhone || emergencyPhone}` : '#'}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                                <span>${currentLocale === 'hi' ? 'अभी कॉल करें' : 'Call Now'}</span>
                            </a>
                            ${(doctorPhone || emergencyPhone) ? `
                                <a href="${getWhatsAppLink(doctorPhone || emergencyPhone, fullName)}" target="_blank" class="w-full sm:flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                    <i data-lucide="message-circle" class="w-4 h-4 text-emerald-100"></i>
                                    <span>WhatsApp</span>
                                </a>
                            ` : ''}
                        </div>
                        ${doc.website ? `
                            <a href="${doc.website.startsWith('http') ? doc.website : 'https://' + doc.website}" target="_blank" class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-850 dark:hover:bg-slate-750 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 border border-slate-750/30">
                                <i data-lucide="globe" class="w-4 h-4 text-teal-400"></i>
                                <span>${currentLocale === 'hi' ? 'वेबसाइट देखें' : 'Visit Website'}</span>
                            </a>
                        ` : ''}
                    </div>
                </div>
            `;

            grid.insertAdjacentHTML('beforeend', html);
        });


    }

    async function fetchSearchResults(query) {
        try {
            const res = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            const data = await res.json();

            document.getElementById('search-loading').classList.add('hidden');

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
                        `<span class="font-semibold text-teal-700 dark:text-teal-450">${currentLocale === 'hi' ? 'अनुशंसित विभाग:' : 'Recommended Department:'} ${data.matched_department}</span>`
                    );
                }
                matchText.innerHTML = textParts.join(' • ');
            }

            allSearchDoctors = data.doctors || [];

            Object.keys(activeFilters).forEach(type => {
                activeFilters[type] = false;
                const btn = document.getElementById('filter-' + type);
                if (btn) {
                    btn.classList.remove('bg-teal-50', 'dark:bg-teal-950/40', 'border-teal-500', 'text-teal-700', 'dark:text-teal-300');
                    btn.classList.add('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-300');
                }
            });

            applyFiltersAndRender();
        } catch (error) {
            console.error('Search fetch error:', error);
            document.getElementById('search-loading').classList.add('hidden');
        }
    }

    let selectedRating = 0;

    function setFeedbackRating(stars) {
        selectedRating = stars;
        document.getElementById('feedback-rating-input').value = stars;
        updateStarsDisplay(stars);
    }

    function updateStarsDisplay(stars) {
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
    }

    const feedbackForm = document.querySelector('form[action="{{ route('feedback.submit') }}"]');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            const ratingValue = Number(document.getElementById('feedback-rating-input')?.value || 0);
            if (!ratingValue) {
                e.preventDefault();
                showSiteToast(currentLocale === 'hi' ? 'कृपया पहले स्टार रेटिंग चुनें।' :
                    'Please select a star rating before submitting.', 'warning');
            }
        });
    }

    updateStarsDisplay(0);
</script>
@endpush













