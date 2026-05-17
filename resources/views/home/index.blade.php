@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मुखपृष्ठ' : 'Home') . ' - SwasthyaSearch')

@section('content')
@php
    $quickSymptoms = $locale === 'hi' ? [
        'बुखार और खांसी', 'हड्डी का टूटना', 'छाती में दर्द', 'त्वचा पर चकत्ते या मुँहासे', 'पेट दर्द', 'गुर्दे की पथरी'
    ] : [
        'Fever and Cough', 'Bone Fracture', 'Chest Pain', 'Skin Rash or Acne', 'Stomach Ache', 'Kidney Stones'
    ];
@endphp

<!-- Hero Section -->
<header class="relative overflow-hidden py-20 lg:py-28 bg-gradient-to-b from-indigo-900 via-indigo-950 to-slate-900 text-white">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 mb-8 shadow-inner">
            <i data-lucide="sparkles" class="w-4 h-4 text-teal-400 animate-spin"></i>
            <span class="text-xs font-semibold tracking-wider uppercase">
                {{ $locale === 'hi' ? 'स्मार्ट लक्षण-आधारित खोज' : 'Smart Symptom-Based Search' }}
            </span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-[1.4] sm:leading-[1.3] pt-4 pb-2 text-white drop-shadow-md">
            @if ($locale === 'hi')
                अपनी बीमारी के <span class="text-teal-400 inline-block">लक्षणों</span> से सही डॉक्टर खोजें
            @else
                Find the Right Specialist by Your <span class="text-teal-400 inline-block">Symptoms</span>
            @endif
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
            {{ $locale === 'hi' ? 'अब जटिल मेडिकल शब्दों की चिंता नहीं। अपनी परेशानी या बीमारी का नाम लिखें और तुरंत सही डॉक्टर का पता लगाएं।' : 'Skip the medical jargon. Simply type what is bothering you, and our intelligent directory will connect you with the right verified healthcare providers instantly.' }}
        </p>

        <!-- Omni-Search Box -->
        <div class="mt-12 max-w-3xl mx-auto px-4 sm:px-0">
            <div class="relative flex items-center bg-white rounded-3xl shadow-2xl p-2 sm:p-3 border border-slate-200/80 focus-within:ring-4 focus-within:ring-teal-500/20 transition-all duration-300">
                <i data-lucide="search" class="absolute left-6 w-6 h-6 text-slate-400 pointer-events-none"></i>
                <input
                    type="text"
                    id="omni-search-input"
                    oninput="handleOmniSearch(this.value)"
                    placeholder="{{ $locale === 'hi' ? 'खोजें: \"पेट दर्द\", \"हड्डी का टूटना\", या डॉक्टर का नाम...' : 'Search: \"Stomach ache\", \"Bone fracture\", or Doctor name...' }}"
                    class="w-full pl-14 pr-4 py-4 text-slate-800 bg-transparent text-base sm:text-lg font-medium placeholder:text-slate-400 focus:outline-none"
                />
                <button
                    id="clear-search-btn"
                    onclick="clearOmniSearch()"
                    class="hidden mr-3 px-3 py-1.5 text-xs text-slate-400 hover:text-slate-600 bg-slate-100 rounded-xl transition-all duration-200"
                >
                    Clear
                </button>
            </div>

            <!-- Quick Symptom Tags -->
            <div class="mt-6 flex flex-wrap justify-center gap-2 items-center text-sm text-slate-300">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 mr-2">
                    {{ $locale === 'hi' ? 'सामान्य खोजें:' : 'Popular Searches:' }}
                </span>
                @foreach ($quickSymptoms as $symp)
                    <button
                        onclick="handleOmniSearch('{{ $symp }}')"
                        class="px-4 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-xs font-medium transition-all duration-200 backdrop-blur-sm"
                    >
                        {{ $symp }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</header>

<!-- Main Content Area -->
<main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
    <!-- Search Match Indicators (Dynamic) -->
    <div id="search-match-container" class="hidden mb-12 bg-gradient-to-r from-teal-500/10 via-indigo-500/10 to-transparent p-6 rounded-3xl border border-teal-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
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
        <span class="text-xs font-semibold bg-white text-teal-700 px-3 py-1.5 rounded-xl shadow-sm border border-teal-100">
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
            <span class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100" id="doctors-count-badge">
                0 {{ $locale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found' }}
            </span>
        </div>

        <!-- Loading Spinner -->
        <div id="search-loading" class="hidden py-20 text-center text-slate-400 font-medium flex flex-col items-center justify-center space-y-3">
            <div class="w-10 h-10 border-4 border-teal-500 border-t-transparent rounded-full animate-spin"></div>
            <span>{{ $locale === 'hi' ? 'डॉक्टर खोजे जा रहे हैं...' : 'Searching healthcare providers...' }}</span>
        </div>

        <!-- Doctors Grid -->
        <div id="doctors-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

        <!-- No Results -->
        <div id="no-results-container" class="hidden py-20 text-center bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm max-w-xl mx-auto space-y-4">
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
        <!-- Articles Section -->
        <div>
            <div class="flex justify-between items-end mb-8">
                <div>
                    <div class="inline-flex items-center space-x-2 text-indigo-600 font-bold text-sm uppercase tracking-wider mb-2">
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
                    <a href="{{ route('articles.show', $article->id) }}" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-teal-200 transition-all duration-300 flex flex-col overflow-hidden group">
                        <div class="p-6 pb-4 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-transparent flex justify-between items-center">
                            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">
                                {{ $article->category }}
                            </span>
                            <div class="flex items-center space-x-1 text-xs text-slate-400 font-medium">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>3 min read</span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h3 class="font-bold text-lg text-slate-900 group-hover:text-teal-600 transition-colors leading-snug">
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
                                <span class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform flex items-center space-x-1">
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
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden transition-all duration-200">
                            <button
                                onclick="toggleFaq({{ $idx }})"
                                class="w-full p-5 text-left font-bold text-base text-slate-800 flex justify-between items-center hover:bg-slate-50/50 transition-colors"
                            >
                                <span>{{ $question }}</span>
                                <i data-lucide="chevron-down" id="faq-icon-{{ $idx }}" class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200"></i>
                            </button>
                            <div id="faq-content-{{ $idx }}" class="hidden p-5 pt-0 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/30">
                                {{ $answer }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
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
            countBadge.innerText = `${data.doctors ? data.doctors.length : 0} ${currentLocale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found'}`;

            if (data.matched_department || data.matched_disease) {
                const matchContainer = document.getElementById('search-match-container');
                const matchText = document.getElementById('search-match-text');
                matchContainer.classList.remove('hidden');
                
                let textParts = [];
                if (data.matched_disease) {
                    textParts.push(`${currentLocale === 'hi' ? 'लक्षण:' : 'Symptom:'} "${data.matched_disease}"`);
                }
                if (data.matched_department) {
                    textParts.push(`<span class="font-semibold text-teal-700">${currentLocale === 'hi' ? 'अनुशंसित विभाग:' : 'Recommended Department:'} ${data.matched_department}</span>`);
                }
                matchText.innerHTML = textParts.join(' • ');
            }

            if (!data.doctors || data.doctors.length === 0) {
                document.getElementById('no-results-container').classList.remove('hidden');
                return;
            }

            data.doctors.forEach(doc => {
                const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                const deptName = doc.department ? (currentLocale === 'hi' ? doc.department.name.hi : doc.department.name.en) : '';
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
                                    <h3 class="font-bold text-lg text-slate-900 group-hover:text-indigo-600 transition-colors flex items-center space-x-1.5">
                                        <span>${fullName}</span>
                                        ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600 shrink-0" title="Verified Provider"></i>' : ''}
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
                                    <span class="font-semibold text-slate-700">${currentLocale === 'hi' ? 'पता:' : 'Address:'}</span> ${hosp.address || 'Jaipur, Rajasthan'} ${hosp.city ? `, ${hosp.city}` : ''}
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
                        <div class="p-6 pt-0 flex items-center space-x-3">
                            <a href="tel:${doc.phone || emergencyPhone || '+911412345678'}" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                                <span>${currentLocale === 'hi' ? 'अभी कॉल करें' : 'Call Now'}</span>
                            </a>
                            ${doc.website ? `
                                <a href="${doc.website.startsWith('http') ? doc.website : 'https://' + doc.website}" target="_blank" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
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
</script>
@endpush
