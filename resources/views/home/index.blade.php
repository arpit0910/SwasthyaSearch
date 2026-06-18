@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मुखपृष्ठ' : 'Home') . ' - Arogio')

@section('meta_title', 'Arogio - Find Doctors, Hospitals & Blood Banks Near You')
@section('meta_description', 'Search trusted doctors, hospitals, blood banks, and departments by city or symptoms. Connect directly with healthcare providers without ads or intermediaries.')
@section('content')
@php
$quickSymptoms =
$locale === 'hi'
? ['बुखार और खांसी', 'हड्डी का टूटना', 'छाती में दर्द']
: ['Fever and Cough', 'Bone Fracture', 'Chest Pain'];
@endphp

<!-- Hero Section -->
<style>
    .hero-brand-motif {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .hero-brand-cluster {
        position: absolute;
        left: 50%;
        top: 54%;
        width: min(28rem, 68vw);
        aspect-ratio: 1;
        transform: translate(-50%, -50%);
        opacity: 0.18;
        filter: drop-shadow(0 0 40px rgba(103, 232, 249, 0.16));
    }

    .hero-brand-ring,
    .hero-brand-ring::before,
    .hero-brand-ring::after {
        position: absolute;
        inset: 0;
        border-radius: 9999px;
    }

    .hero-brand-ring {
        border: 1px solid rgba(255, 255, 255, 0.18);
        animation: heroSpin 18s linear infinite;
    }

    .hero-brand-ring::before,
    .hero-brand-ring::after {
        content: "";
        inset: 12%;
        border: 1px dashed rgba(125, 211, 252, 0.25);
        animation: heroReverseSpin 24s linear infinite;
    }

    .hero-brand-ring::after {
        inset: 26%;
        border-style: solid;
        border-color: rgba(45, 212, 191, 0.24);
        animation-duration: 14s;
    }

    .hero-brand-core {
        position: absolute;
        inset: 34%;
        border-radius: 9999px;
        background:
            radial-gradient(circle at 30% 30%, rgba(255,255,255,0.9), rgba(255,255,255,0.16) 28%, transparent 58%),
            linear-gradient(135deg, rgba(45, 212, 191, 0.34), rgba(59, 130, 246, 0.24));
        backdrop-filter: blur(4px);
        animation: heroPulse 5s ease-in-out infinite;
    }

    .hero-brand-wave {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 36%;
        height: 12%;
        transform: translate(-50%, -50%);
        border-radius: 9999px;
        background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,0.65), rgba(255,255,255,0));
        filter: blur(1px);
        animation: heroWave 3.8s ease-in-out infinite;
    }

    .hero-brand-wave::before,
    .hero-brand-wave::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 34%;
        height: 100%;
        border-radius: 9999px;
        background: inherit;
    }

    .hero-brand-wave::before {
        left: -18%;
        transform: translateY(-50%) rotate(-36deg);
    }

    .hero-brand-wave::after {
        right: -18%;
        transform: translateY(-50%) rotate(36deg);
    }

    .hero-brand-dot {
        position: absolute;
        width: 0.65rem;
        height: 0.65rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 0 24px rgba(103, 232, 249, 0.32);
        animation: heroFloat 6s ease-in-out infinite;
    }

    .hero-brand-dot.dot-1 { left: 16%; top: 22%; animation-delay: 0s; }
    .hero-brand-dot.dot-2 { right: 18%; top: 28%; animation-delay: 1.4s; }
    .hero-brand-dot.dot-3 { left: 22%; bottom: 20%; animation-delay: 2.2s; }
    .hero-brand-dot.dot-4 { right: 24%; bottom: 16%; animation-delay: 3.1s; }
    .hero-brand-dot.dot-5 { left: 50%; top: 12%; animation-delay: 0.8s; }

    .hero-brand-beam {
        position: absolute;
        inset: auto 10% 14% 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        opacity: 0.6;
        animation: heroBeam 4.8s ease-in-out infinite;
    }

    @keyframes heroSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes heroReverseSpin {
        from { transform: rotate(360deg); }
        to { transform: rotate(0deg); }
    }

    @keyframes heroPulse {
        0%, 100% { transform: scale(0.96); opacity: 0.72; }
        50% { transform: scale(1.06); opacity: 1; }
    }

    @keyframes heroFloat {
        0%, 100% { transform: translateY(0px) scale(1); opacity: 0.7; }
        50% { transform: translateY(-12px) scale(1.18); opacity: 1; }
    }

    @keyframes heroWave {
        0%, 100% { transform: translate(-50%, -50%) scaleX(0.94); opacity: 0.48; }
        50% { transform: translate(-50%, -50%) scaleX(1.14); opacity: 0.92; }
    }

    @keyframes heroBeam {
        0%, 100% { opacity: 0.15; transform: scaleX(0.85); }
        50% { opacity: 0.72; transform: scaleX(1); }
    }

    @media (max-width: 767px) {
        .hero-brand-cluster {
            width: min(20rem, 92vw);
            top: 48%;
            opacity: 0.14;
        }

        .hero-brand-ring,
        .hero-brand-ring::before,
        .hero-brand-ring::after,
        .hero-brand-core,
        .hero-brand-wave,
        .hero-brand-dot,
        .hero-brand-beam {
            animation: none !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-brand-ring,
        .hero-brand-ring::before,
        .hero-brand-ring::after,
        .hero-brand-core,
        .hero-brand-wave,
        .hero-brand-dot,
        .hero-brand-beam {
            animation: none !important;
        }
    }
</style>
<header id="home-hero" class="relative overflow-hidden py-6 lg:py-9 mb-1 bg-gradient-to-b from-cyan-700 via-sky-700 to-blue-800 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950 text-white indian-motif-bg">
    <!-- Glowing background blobs -->
    <div class="glow-blob w-[400px] h-[400px] bg-teal-500/10 top-0 left-0"></div>
    <div class="glow-blob w-[500px] h-[500px] bg-cyan-500/10 bottom-0 right-0"></div>
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#67e8f9_1px,transparent_1px)] [background-size:18px_18px]"></div>
    <div class="hero-brand-motif" aria-hidden="true">
        <div class="hero-brand-cluster">
            <div class="hero-brand-ring"></div>
            <div class="hero-brand-core"></div>
            <div class="hero-brand-wave"></div>
            <span class="hero-brand-dot dot-1"></span>
            <span class="hero-brand-dot dot-2"></span>
            <span class="hero-brand-dot dot-3"></span>
            <span class="hero-brand-dot dot-4"></span>
            <span class="hero-brand-dot dot-5"></span>
            <div class="hero-brand-beam"></div>
        </div>
    </div>

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
        <section class="rounded-[2rem] border border-rose-200/70 dark:border-rose-900/40 bg-gradient-to-br from-rose-50 via-white to-cyan-50/70 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-rose-700 dark:text-rose-300">{{ $locale === 'hi' ? 'आपातकालीन सहायता' : 'Emergency Help' }}</p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'तुरंत मदद चाहिए?' : 'Need urgent help?' }}</h2>
                    <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'नज़दीकी अस्पताल और ब्लड बैंक जल्दी खोजें। जाने से पहले कॉल करें क्योंकि इमरजेंसी उपलब्धता, डॉक्टर, बेड और ब्लड स्टॉक बदल सकते हैं।' : 'Find nearby hospitals and blood banks quickly. Please call before visiting because emergency availability, doctors, beds, and blood stock can change quickly.' }}</p>
                    <p class="mt-3 text-xs sm:text-sm font-semibold text-rose-700 dark:text-rose-300">{{ $locale === 'hi' ? 'यदि यह जीवन-घातक आपातस्थिति है, तो तुरंत इमरजेंसी सेवाओं से संपर्क करें या नज़दीकी अस्पताल जाएँ।' : 'If this is a life-threatening emergency, contact emergency services or go to the nearest hospital immediately.' }}</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3 lg:w-[440px]">
                    <a href="{{ route('hospitals.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-rose-200 dark:border-rose-900/50 bg-white/90 dark:bg-slate-950 px-4 py-3 text-sm font-bold text-rose-700 dark:text-rose-200 shadow-sm transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-800 dark:hover:border-rose-800 dark:hover:bg-rose-950/30 dark:hover:text-rose-100 active:scale-[0.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'अस्पताल देखें' : 'View Hospitals' }}</a>
                    <a href="{{ route('blood_banks.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-red-200 dark:border-red-900/50 bg-white/90 dark:bg-slate-950 px-4 py-3 text-sm font-bold text-red-700 dark:text-red-200 shadow-sm transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-red-300 hover:bg-red-50 hover:text-red-800 dark:hover:border-red-800 dark:hover:bg-red-950/30 dark:hover:text-red-100 active:scale-[0.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'ब्लड बैंक देखें' : 'View Blood Banks' }}</a>
                    <button type="button" onclick="toggleChatbot()" class="inline-flex items-center justify-center rounded-2xl border border-slate-900 dark:border-cyan-500 bg-slate-900 dark:bg-cyan-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/10 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:bg-slate-800 dark:hover:bg-cyan-500 active:scale-[0.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'AI सहायक' : 'Ask Health Assistant' }}</button>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden rounded-[2rem] border border-emerald-200 dark:border-emerald-900/40 bg-gradient-to-br from-emerald-50 via-white to-cyan-50/80 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 p-6 sm:p-8 shadow-md shadow-emerald-100/60 dark:shadow-none">
            <div class="absolute -top-16 right-0 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-cyan-300/20 blur-3xl"></div>
            <div class="relative z-10 grid gap-6">
                <div class="grid gap-8 xl:grid-cols-[1.15fr,0.85fr] xl:items-center">
                    <div class="max-w-3xl">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">{{ $locale === 'hi' ? 'दवा जानकारी' : 'Medicine Information' }}</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'दवाओं को समझने का एक साफ़ तरीका' : 'A cleaner way to understand medicines' }}</h2>
                        <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'उपयोग, सावधानियाँ, सामान्य साइड इफेक्ट और जरूरी गाइडेंस को आसान, स्कैन-फ्रेंडली फॉर्मेट में देखें।' : 'Explore uses, precautions, common side effects, and key guidance in a simple, easy-to-scan format.' }}</p>
                        <div class="mt-5 flex flex-wrap gap-2.5">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/90 dark:bg-slate-900/80 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 border border-emerald-100 dark:border-emerald-900/50">
                                <i data-lucide="pill" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-300"></i>
                                {{ $locale === 'hi' ? 'उपयोग और खुराक नोट्स' : 'Uses and dosage notes' }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/90 dark:bg-slate-900/80 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 border border-cyan-100 dark:border-cyan-900/50">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-cyan-600 dark:text-cyan-300"></i>
                                {{ $locale === 'hi' ? 'सावधानियाँ और चेतावनियाँ' : 'Precautions and warnings' }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/90 dark:bg-slate-900/80 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 border border-amber-100 dark:border-amber-900/50">
                                <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-300"></i>
                                {{ $locale === 'hi' ? 'रिपोर्टिंग और अपडेट विकल्प' : 'Report and update options' }}
                            </span>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('medicines.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-emerald-600 bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-emerald-700 hover:bg-emerald-700 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'दवाएं देखें' : 'Browse Medicines' }}</a>
                            <button type="button" onclick="toggleChatbot()" class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 dark:border-emerald-900/50 bg-white/95 dark:bg-slate-950 px-5 py-3 text-sm font-bold text-emerald-700 dark:text-emerald-200 shadow-sm transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:border-emerald-800 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-100 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'किसी दवा के बारे में पूछें' : 'Ask About a Medicine' }}</button>
                        </div>
                    </div>

                    <div class="relative mx-auto w-full max-w-md xl:mx-0 xl:justify-self-end">
                        <div class="absolute -left-5 top-10 h-20 w-20 rounded-full bg-emerald-300/30 blur-2xl"></div>
                        <div class="absolute -right-4 bottom-8 h-24 w-24 rounded-full bg-cyan-300/30 blur-2xl"></div>
                        <a href="{{ route('medicines.index') }}" class="group relative block overflow-hidden rounded-[2rem] border border-emerald-200/70 dark:border-emerald-900/40 bg-gradient-to-br from-white via-emerald-50/70 to-cyan-50/70 dark:from-slate-900/95 dark:via-emerald-950/15 dark:to-slate-900/95 p-5 shadow-[0_18px_45px_rgba(16,185,129,0.10)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_26px_60px_rgba(16,185,129,0.16)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">
                            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.14),transparent_32%),radial-gradient(circle_at_bottom_left,rgba(34,211,238,0.12),transparent_34%)] opacity-80 transition-opacity duration-300 group-hover:opacity-90"></div>
                            <div class="relative">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="inline-flex rounded-full border border-emerald-200/80 bg-white/80 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-300">{{ $locale === 'hi' ? 'सरल दृश्य' : 'Simple View' }}</p>
                                        <h3 class="mt-3 text-xl font-bold text-slate-900 dark:text-white">Paracetamol 650</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'ज़रूरी दवा जानकारी को आसान, जल्दी समझ आने वाले ब्लॉक्स में देखें।' : 'Understand key medicine details in quick, easy-to-scan blocks.' }}</p>
                                    </div>
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 text-white shadow-lg transition-transform duration-300 group-hover:scale-105 group-hover:rotate-3">
                                        <i data-lucide="pill" class="h-7 w-7"></i>
                                    </div>
                                </div>
                                <div class="mt-5 space-y-3">
                                    <div class="rounded-2xl border border-emerald-100/90 dark:border-emerald-900/50 bg-white/75 dark:bg-emerald-950/20 p-4">
                                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">{{ $locale === 'hi' ? 'आमतौर पर उपयोग' : 'Commonly used for' }}</p>
                                        <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'बुखार, शरीर दर्द, सिरदर्द' : 'Fever, body ache, headache' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white/70 dark:bg-slate-950/70 p-4">
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'सावधानी' : 'Caution' }}</p>
                                            <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'लिवर की समस्या में डॉक्टर से पूछें' : 'Check with a doctor in liver disease' }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white/70 dark:bg-slate-950/70 p-4">
                                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'ध्यान दें' : 'Watch for' }}</p>
                                            <p class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'असामान्य एलर्जी या गलती से ज़्यादा खुराक' : 'Unusual allergy or accidental overuse' }}</p>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl border border-cyan-100/90 dark:border-cyan-900/50 bg-cyan-50/75 dark:bg-cyan-950/20 p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-500 text-white shadow-sm transition-transform duration-300 group-hover:scale-105">
                                                <i data-lucide="sparkles" class="h-4 w-4"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'साफ़, स्कैन-फ्रेंडली जानकारी' : 'Clear, scan-friendly information' }}</p>
                                                <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'भारी टेक्स्ट के बिना ज़रूरी गाइडेंस जल्दी पाएँ।' : 'Find useful guidance quickly without heavy text walls.' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between rounded-2xl border border-emerald-200/80 bg-white/80 px-4 py-3 text-sm font-bold text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/20 dark:text-emerald-200">
                                    <span>{{ $locale === 'hi' ? 'पूरी दवा जानकारी खोलें' : 'Open full medicine information' }}</span>
                                    <i data-lucide="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 xl:grid-cols-3">
                    <div class="xl:col-span-3 rounded-[1.5rem] border border-white/80 dark:border-slate-800 bg-white/75 dark:bg-slate-900/70 px-5 py-4">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'तेज़ पहुंच' : 'Quick Access' }}</p>
                        <h3 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'औषधि जानकारी के साथ जुड़ने वाले उपयोगी टूल्स' : 'Tools that pair well with medicine info' }}</h3>
                    </div>

                    <a href="{{ route('symptom-test') }}" class="flex items-start gap-4 rounded-2xl border border-teal-100 dark:border-teal-900/40 bg-white/90 dark:bg-teal-950/20 p-4 transition hover:-translate-y-0.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-teal-600 text-white shadow-md">
                            <i data-lucide="stethoscope" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'लक्षण परीक्षण' : 'Symptom Test' }}</p>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'संभावित समस्या समझें और सही विभाग तक पहुँचें।' : 'Understand a likely condition and reach the right department.' }}</p>
                        </div>
                    </a>

                    <button type="button" onclick="toggleChatbot()" class="flex w-full items-start gap-4 rounded-2xl border border-cyan-100 dark:border-cyan-900/40 bg-white/90 dark:bg-cyan-950/20 p-4 text-left transition hover:-translate-y-0.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-md">
                            <i data-lucide="bot" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'AI सहायक' : 'AI Health Assistant' }}</p>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'दवा, डॉक्टर और अगले कदमों पर तेज़ गाइडेंस पाएँ।' : 'Get quick guidance about medicines, doctors, and next steps.' }}</p>
                        </div>
                    </button>

                    <a href="{{ route('articles.index') }}" class="flex items-start gap-4 rounded-2xl border border-amber-100 dark:border-amber-900/40 bg-white/90 dark:bg-amber-950/20 p-4 transition hover:-translate-y-0.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md">
                            <i data-lucide="book-open" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles' }}</p>
                            <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'उपचार, दवाओं और बचाव पर आसान भाषा में लेख पढ़ें।' : 'Easy reading on treatments, medicines, and prevention.' }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="relative overflow-hidden rounded-[2rem] border border-cyan-200/70 dark:border-cyan-900/40 bg-gradient-to-br from-cyan-50 via-white to-indigo-50/70 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 p-5 sm:p-8 shadow-sm">
            <div class="pointer-events-none absolute right-0 top-0 h-44 w-44 rounded-full bg-cyan-300/15 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 left-0 h-36 w-36 rounded-full bg-indigo-300/15 blur-3xl"></div>
            <div class="relative z-10 mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-3xl min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'वेलनेस टूल्स' : 'Wellness Tools' }}</p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'गतिविधियां, क्विज़ और शांत अभ्यास' : 'Activities, Quizzes, and Calming Tools' }}</h2>
                    <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'तनाव राहत, आत्म-जागरूकता और छोटे शांत विराम के लिए सहायक टूल्स खोजें।' : 'Explore supportive tools for stress relief, self-awareness, and calmer daily pauses.' }}</p>
                </div>
                <a href="{{ route('activities.index') }}" class="z-20 inline-flex w-full sm:w-auto items-center justify-center rounded-2xl border border-cyan-200 dark:border-cyan-800/60 bg-white dark:bg-slate-950 px-4 py-3 text-sm font-bold text-cyan-800 dark:text-cyan-200 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-900 dark:hover:border-cyan-700 dark:hover:bg-cyan-950/30 dark:hover:text-cyan-100 active:scale-[0.99] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">{{ $locale === 'hi' ? 'सभी वेलनेस टूल्स देखें' : 'View All Wellness Tools' }}</a>
            </div>

            <div class="relative z-10 grid grid-cols-1 gap-4 lg:gap-5 lg:grid-cols-[0.95fr,1.05fr] lg:items-start">
                <div class="rounded-[1.75rem] border border-white/80 dark:border-slate-800 bg-white/92 dark:bg-slate-900/92 p-5 sm:p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-500 text-white shadow-lg">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">{{ $locale === 'hi' ? 'माइक्रो ब्रेक्स' : 'Micro Breaks' }}</p>
                            <h3 class="mt-2 text-lg sm:text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'छोटे टूल जो दिमाग को हल्का विराम देते हैं' : 'Small tools that give your mind a softer pause' }}</h3>
                            <p class="mt-2 text-sm leading-6 sm:leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'जब आपको खोज से थोड़ा ब्रेक चाहिए, ये टूल बिना अतिरिक्त दबाव के रीसेट करने में मदद करते हैं।' : 'When you need a short break from searching, these tools help you reset without adding pressure.' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-cyan-100 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 px-3 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-cyan-700 dark:text-cyan-300 shadow-sm text-sm font-bold">1</div>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'सांस' : 'Breathe' }}</p>
                        </div>
                        <div class="rounded-2xl border border-cyan-100 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 px-3 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-cyan-700 dark:text-cyan-300 shadow-sm text-sm font-bold">2</div>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'रुकें' : 'Pause' }}</p>
                        </div>
                        <div class="rounded-2xl border border-cyan-100 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 px-3 py-3 text-center">
                            <div class="mx-auto flex h-8 w-8 items-center justify-center rounded-xl bg-white dark:bg-slate-900 text-cyan-700 dark:text-cyan-300 shadow-sm text-sm font-bold">3</div>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'रीसेट' : 'Reset' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2.5">
                        <span class="inline-flex items-center rounded-full border border-cyan-100 dark:border-cyan-900/40 bg-white/80 dark:bg-slate-900/70 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'कम दबाव' : 'Low pressure' }}</span>
                        <span class="inline-flex items-center rounded-full border border-cyan-100 dark:border-cyan-900/40 bg-white/80 dark:bg-slate-900/70 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'छोटे कदम' : 'Short steps' }}</span>
                        <span class="inline-flex items-center rounded-full border border-cyan-100 dark:border-cyan-900/40 bg-white/80 dark:bg-slate-900/70 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'मोबाइल फ्रेंडली' : 'Mobile friendly' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2">
                    @foreach([
                        [
                            'title' => $locale === 'hi' ? 'श्वास और ग्राउंडिंग' : 'Breathing and Grounding',
                            'description' => $locale === 'hi' ? 'धीमी श्वास, ग्राउंडिंग और छोटे शांत अभ्यास आज़माएं।' : 'Try guided breathing, grounding, and short calming exercises.',
                            'url' => route('activities.index'),
                            'tone' => 'teal',
                            'icon' => 'wind',
                        ],
                        [
                            'title' => $locale === 'hi' ? 'मूड चेक-इन' : 'Mood Check-in',
                            'description' => $locale === 'hi' ? 'अपनी भावना पहचानें और जरूरत पड़ने पर सहायता मार्ग देखें।' : 'Reflect on how you feel and see support pathways when needed.',
                            'url' => route('activities.mood-check'),
                            'tone' => 'indigo',
                            'icon' => 'heart',
                        ],
                        [
                            'title' => $locale === 'hi' ? 'हेल्थ क्विज़' : 'Health Quizzes',
                            'description' => $locale === 'hi' ? 'सामान्य जागरूकता और आत्म-चिंतन के लिए छोटे क्विज़ लें।' : 'Take short quizzes for general awareness and self-reflection.',
                            'url' => route('quizzes.index'),
                            'tone' => 'cyan',
                            'icon' => 'brain',
                        ],
                        [
                            'title' => $locale === 'hi' ? 'कैल्म गेम्स' : 'Calming Games',
                            'description' => $locale === 'hi' ? 'मेमोरी गेम और टैप काउंटर जैसे हल्के शांत टूल्स उपयोग करें।' : 'Use light calming tools like the memory game and tap counter.',
                            'url' => route('activities.games.memory'),
                            'tone' => 'amber',
                            'icon' => 'zap',
                        ],
                    ] as $item)
                        <a href="{{ $item['url'] }}" class="flex h-full flex-col rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/92 dark:bg-slate-900/92 shadow-sm p-4 sm:p-5 hover:-translate-y-1 transition">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-{{ $item['tone'] }}-100 dark:bg-{{ $item['tone'] }}-950/40 text-{{ $item['tone'] }}-700 dark:text-{{ $item['tone'] }}-200 mb-4">
                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-6 sm:leading-7">{{ $item['description'] }}</p>
                            <span class="mt-4 inline-flex text-sm font-bold text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'खोलें' : 'Open' }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Directory Statistics / Informative Overview -->
        <div>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <div
                        class="inline-flex items-center space-x-2 text-teal-600 font-bold text-sm uppercase tracking-wider mb-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'Arogio एक नज़र में' : 'Arogio at a Glance' }}</span>
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
                    <p class="mt-1 text-sm font-semibold leading-6 text-slate-900 dark:text-white sm:text-base">
                        {{ $locale === 'hi' ? 'आयुष्मान भारत और जन आधार कार्ड स्वीकृत' : 'Ayushman Bharat & Jan Aadhaar Cards Accepted' }}
                    </p>
                    <p class="mt-1 text-xs leading-6 text-slate-600 dark:text-slate-300 sm:text-sm">
                        {{ $locale === 'hi' ? 'सत्यापित अस्पतालों और डॉक्टरों से सीधे जुड़ें। न बिचौलिया, न बुकिंग शुल्क, और सरकारी स्वास्थ्य योजनाओं का पूरा समर्थन।' : 'Connect directly with verified hospitals and doctors. Zero intermediaries, zero booking fees, and full support for government health schemes.' }}
                    </p>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-2.5">
                    <span class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300 sm:w-auto sm:justify-start">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ $locale === 'hi' ? 'आयुष्मान भारत' : 'Ayushman Bharat' }}
                    </span>
                    <span class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-bold text-sky-700 dark:border-sky-900 dark:bg-sky-950/30 dark:text-sky-300 sm:w-auto sm:justify-start">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> {{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}
                    </span>
                    <span class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50 px-3 py-2 text-xs font-bold text-violet-700 dark:border-violet-900 dark:bg-violet-950/30 dark:text-violet-300 sm:w-auto sm:justify-start">
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
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2">
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
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2">
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
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2">
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

        <section id="schedule-consultation" class="relative overflow-hidden rounded-[2rem] border border-cyan-200/80 dark:border-cyan-900/40 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(20,184,166,0.16),transparent_32%),linear-gradient(135deg,#ecfeff_0%,#ffffff_42%,#f0fdfa_100%)] dark:bg-[radial-gradient(circle_at_top_left,rgba(8,145,178,0.24),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(13,148,136,0.18),transparent_34%),linear-gradient(135deg,#0f172a_0%,#111827_45%,#052e2b_100%)] p-4 sm:p-6 lg:p-8 shadow-[0_20px_50px_rgba(14,165,233,0.10)] dark:shadow-none">
            <div class="absolute -left-10 top-10 h-36 w-36 rounded-full bg-cyan-300/20 blur-3xl"></div>
            <div class="absolute -right-8 bottom-4 h-40 w-40 rounded-full bg-teal-300/20 blur-3xl"></div>
            <div class="relative z-10 grid gap-6 xl:grid-cols-[0.9fr,1.1fr] xl:gap-8">
                <div class="flex flex-col gap-4 sm:gap-5">
                    <div>
                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.24em] text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'शेड्यूल्ड कंसल्टेशन' : 'Scheduled Consultation' }}</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold leading-tight text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'अपनी सुविधा के समय पर डॉक्टर से बात करने की रिक्वेस्ट भेजें' : 'Request a doctor consultation for a time that works for you' }}</h2>
                        <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'अपना कारण, संपर्क विवरण और पसंदीदा स्लॉट साझा करें। हमारी टीम उपलब्धता देखकर आपसे संपर्क करेगी।' : 'Share your reason, contact details, and preferred slot. Our team will review availability and get back to you.' }}</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-[1.4rem] border border-white/80 dark:border-slate-800/90 bg-white/85 dark:bg-slate-950/65 p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700 dark:bg-cyan-950/50 dark:text-cyan-300">
                                    <i data-lucide="clipboard-plus" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'क्या शामिल करें' : 'What to include' }}</p>
                                    <p class="mt-1 text-xs sm:text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'लक्षण, फॉलो-अप सवाल, मेडिकल राय या दूसरी सलाह की ज़रूरत।' : 'Symptoms, follow-up questions, second opinions, or general consultation needs.' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-[1.4rem] border border-white/80 dark:border-slate-800/90 bg-white/85 dark:bg-slate-950/65 p-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-950/50 dark:text-teal-300">
                                    <i data-lucide="calendar-check-2" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'आगे क्या होगा' : 'What happens next' }}</p>
                                    <p class="mt-1 text-xs sm:text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'उपलब्ध स्लॉट देखकर टीम ईमेल या फोन पर पुष्टि करेगी।' : 'Our team will confirm the best available slot by phone or email.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                        <div class="rounded-2xl border border-cyan-100/90 dark:border-cyan-900/60 bg-white/80 dark:bg-slate-950/55 px-4 py-3 text-center xl:text-left">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'स्टेप 1' : 'Step 1' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'समस्या लिखें' : 'Share your concern' }}</p>
                        </div>
                        <div class="rounded-2xl border border-cyan-100/90 dark:border-cyan-900/60 bg-white/80 dark:bg-slate-950/55 px-4 py-3 text-center xl:text-left">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'स्टेप 2' : 'Step 2' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'स्लॉट चुनें' : 'Pick date and time' }}</p>
                        </div>
                        <div class="rounded-2xl border border-cyan-100/90 dark:border-cyan-900/60 bg-white/80 dark:bg-slate-950/55 px-4 py-3 text-center xl:text-left">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'स्टेप 3' : 'Step 3' }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'कन्फर्मेशन पाएं' : 'Receive confirmation' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[1.7rem] border border-white/90 dark:border-slate-800/90 bg-white/92 dark:bg-slate-950/82 p-4 sm:p-5 lg:p-6 shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $locale === 'hi' ? 'कंसल्टेशन रिक्वेस्ट फॉर्म' : 'Consultation Request Form' }}</p>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'सभी फ़ील्ड भरें ताकि हम सही तरीके से संपर्क कर सकें।' : 'Fill in the details so we can coordinate the right slot.' }}</p>
                        </div>
                    </div>

                    @if (session('consultation_request_success'))
                        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-200">
                            {{ session('consultation_request_success') }}
                        </div>
                    @endif

                    <form action="{{ route('consultation-requests.submit') }}" method="POST" class="mt-5 grid gap-4 sm:gap-5">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="consultation-name" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'पूरा नाम' : 'Full name' }}</label>
                                <input id="consultation-name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900" placeholder="{{ $locale === 'hi' ? 'अपना नाम लिखें' : 'Enter your name' }}">
                                @error('name')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="consultation-email" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'ईमेल' : 'Email' }}</label>
                                <input id="consultation-email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900" placeholder="name@example.com">
                                @error('email')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <label for="consultation-phone" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'फोन' : 'Phone' }}</label>
                                <input id="consultation-phone" name="phone" type="text" value="{{ old('phone') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900" placeholder="{{ $locale === 'hi' ? 'मोबाइल नंबर' : 'Mobile number' }}">
                                @error('phone')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-1">
                                <label for="consultation-date" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'तारीख' : 'Preferred date' }}</label>
                                <input id="consultation-date" name="preferred_date" type="date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900">
                                @error('preferred_date')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-1">
                                <label for="consultation-time" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'समय' : 'Preferred time' }}</label>
                                <input id="consultation-time" name="preferred_time" type="time" value="{{ old('preferred_time') }}" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900">
                                @error('preferred_time')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="consultation-reason" class="mb-2 block text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'कंसल्टेशन का कारण' : 'Reason for consultation' }}</label>
                            <textarea id="consultation-reason" name="reason" rows="5" class="w-full rounded-[1.4rem] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-200 dark:focus:ring-cyan-900" placeholder="{{ $locale === 'hi' ? 'अपनी समस्या, लक्षण या सवाल लिखें' : 'Describe your symptoms, concern, or follow-up need' }}">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="gap-3 border-t border-slate-200/80 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                            <p class="max-w-xl text-xs sm:text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'यह एक शेड्यूल रिक्वेस्ट है। उपलब्धता और अंतिम समय की पुष्टि हमारी टीम करेगी।' : 'This is a scheduling request. Our team will confirm final availability and timing with you.' }}</p>
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-cyan-600 bg-cyan-600 px-5 py-3 mt-5 text-sm font-bold text-white shadow-lg shadow-cyan-600/20 transition-all duration-300 ease-out hover:-translate-y-0.5 hover:border-cyan-700 hover:bg-cyan-700 active:scale-[0.98] sm:w-auto">
                                {{ $locale === 'hi' ? 'रिक्वेस्ट भेजें' : 'Request consultation' }}
                            </button>
                        </div>
                    </form>
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
                    {{ $locale === 'hi' ? 'Arogio के बारे में आपके सभी सवालों के जवाब' : 'Everything you need to know about Arogio' }}
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
                            {{ $locale === 'hi' ? 'आपके सुझावों से हम Arogio को और बेहतर बनाने के लिए निरंतर प्रयासरत हैं।' : 'Help us improve Arogio. Tell us about your experience searching for doctors and hospitals.' }}
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
        const message = encodeURIComponent(`Hello ${docName}, I found your profile on Arogio and would like to inquire about consultation timings and availability.`);
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
            const res = await fetch(`{{ route('api.search') }}?q=${encodeURIComponent(query)}`);
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













