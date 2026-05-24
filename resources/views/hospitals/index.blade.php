@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics') . ' - SwasthyaSearch')

@php
$seoCity = request('city');
$hasCity = !empty($seoCity) && $seoCity !== 'All';
$pageTitle = $hasCity
? "Hospitals in {$seoCity} | Find Services & Contacts | SwasthyaSearch"
: 'Find Hospitals Near You | SwasthyaSearch';
$pageDescription = $hasCity
? "Find hospitals in {$seoCity} by service, department, and location. Call hospitals directly to confirm services and timings before visiting."
: 'Search hospitals by city, department, or service. Find hospital contact details, locations, and direct access to healthcare providers.';
@endphp
@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('content')
<!-- Hero Section -->
<header
    class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <!-- Glowing background blobs -->
    <div class="glow-blob w-[300px] h-[300px] bg-teal-500/10 top-0 left-0 absolute rounded-full blur-3xl"></div>
    <div class="glow-blob w-[400px] h-[400px] bg-indigo-500/10 bottom-0 right-0 absolute rounded-full blur-3xl"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span
            class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'सत्यापित स्वास्थ्य केंद्र' : 'Verified Healthcare Centers' }}
        </span>
        <h1
            class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $hasCity ? "Find Hospitals in {$seoCity}" : 'Find Hospitals Near You' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'आपातकालीन संपर्क नंबरों और पूर्ण पते के साथ आपके शहर में 100% सत्यापित और विश्वसनीय चिकित्सा सुविधाएं।' : 'Discover accredited hospitals and specialized healthcare clinics near you. Complete with verified emergency contacts and locations.' }}
        </p>
    </div>
</header>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <p class="text-sm text-slate-600">
        Compare hospitals by city, facility type, and health benefits. Please call the hospital before visiting to confirm services, timings, and emergency availability.
    </p>
</section>

<!-- Filter Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
    <form action="{{ route('hospitals.index') }}" method="GET" data-auto-filter
        class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
        <input type="hidden" name="user_lat" id="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" id="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input type="text" name="search"
                    placeholder="{{ $locale === 'hi' ? 'अस्पताल, क्लिनिक, पता या शहर खोजें...' : 'Search hospitals, clinics, address, city...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            </div>

            <!-- Type Filter -->
            <div>
                @php $typeVal = request('type', $filters['type'] ?? 'All'); @endphp
                <select name="type"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="All" {{ $typeVal === 'All' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सभी प्रकार' : 'All Facility Types' }}
                    </option>
                    @foreach ($types as $t)
                    <option value="{{ $t }}" {{ $typeVal === $t ? 'selected' : '' }}>{{ $t }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter -->
            <div>
                @php $cityVal = request('city', $filters['city'] ?? 'All'); @endphp
                <select name="city"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="All" {{ $cityVal === 'All' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सभी शहर' : 'All Cities' }}
                    </option>
                    @foreach ($cities as $c)
                    <option value="{{ $c }}" {{ $cityVal === $c ? 'selected' : '' }}>
                        {{ $c }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Benefit Filter -->
            <div>
                @php $benefitVal = request('benefit', $filters['benefit'] ?? 'All'); @endphp
                <select name="benefit"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="All" {{ $benefitVal === 'All' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सभी स्वास्थ्य योजनाएं' : 'All Health Benefits' }}
                    </option>
                    <option value="ayushman" {{ $benefitVal === 'ayushman' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'आयुष्मान कार्ड' : 'Ayushman Card' }}
                    </option>
                    <option value="janaadhaar" {{ $benefitVal === 'janaadhaar' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}
                    </option>
                    <option value="cghs" {{ $benefitVal === 'cghs' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सीजीएचएस (CGHS)' : 'CGHS Govt' }}
                    </option>
                    <option value="cashless" {{ $benefitVal === 'cashless' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'कैशलेस सुविधा' : 'Cashless Facility' }}
                    </option>
                </select>
            </div>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-100">
            <button type="button" onclick="setUserLocationAndSubmit(this.form)"
                class="h-12 px-5 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="locate-fixed" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
            </button>
            <a href="{{ route('hospitals.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>

        </div>
    </form>
</section>

<!-- Hospitals Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
    @if (count($hospitals) === 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
        <div
            class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
            <i data-lucide="building-2" class="w-10 h-10"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">
            {{ $locale === 'hi' ? 'कोई अस्पताल नहीं मिला' : 'No Hospitals Found' }}
        </h3>
        <p class="text-slate-500 text-base mb-8 leading-relaxed">
            {{ $locale === 'hi' ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई अस्पताल या क्लीनिक नहीं मिला। कृपया अपनी खोज मानदंड बदलें।' : 'We could not find any healthcare facilities matching your selected filters. Please try modifying your search criteria.' }}
        </p>
        <a href="{{ route('hospitals.index') }}"
            class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'सभी अस्पताल देखें' : 'View All Hospitals' }}</span>
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($hospitals as $hospital)
        @php
        $h = is_array($hospital) ? (object) $hospital : $hospital;
        $hNameEn = is_array($h->name) ? $h->name['en'] : $h->name['en'] ?? $h->name_en;
        $hNameHi = is_array($h->name) ? $h->name['hi'] : $h->name['hi'] ?? $h->name_hi;
        $hName = $locale === 'hi' ? ($hNameHi ?: $hNameEn) : $hNameEn;
        $hAddressLine1 = $locale === 'hi' ? ($h->address_line1_hi ?? $h->address_line1) : $h->address_line1;
        $hAddressLine2 = $locale === 'hi' ? ($h->address_line2_hi ?? $h->address_line2) : $h->address_line2;
        $hCity = $locale === 'hi' ? ($h->city_hi ?? $h->city) : $h->city;
        $hState = $locale === 'hi' ? ($h->state_hi ?? $h->state) : $h->state;
        $hAddress = $locale === 'hi' ? ($h->address_hi ?? $h->address) : $h->address;
        @endphp
        <div
            class="glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1 relative"
            data-ayushman="{{ !empty($h->accepts_ayushman) ? 'true' : 'false' }}"
            data-cashless="{{ !empty($h->is_cashless) ? 'true' : 'false' }}"
            data-verified="{{ $h->is_verified ? 'true' : 'false' }}">

            <!-- Card Header -->
            <div
                class="p-6 bg-gradient-to-br from-slate-50/50 via-white/50 to-slate-50/50 dark:from-slate-800/30 dark:via-transparent dark:to-slate-800/30 border-b border-slate-100 dark:border-slate-800/60 flex items-start space-x-4">
                <div
                    class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                    <div
                        class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                        <i data-lucide="building-2" class="w-8 h-8 text-teal-400"></i>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start space-x-1.5 mb-1.5">
                        <h3
                            class="font-extrabold text-lg text-slate-900 dark:text-white line-clamp-3 leading-snug group-hover:text-teal-600 transition-colors duration-200">
                            {{ $hName }}
                        </h3>
                        @if ($h->is_verified)
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500 shrink-0 mt-1"></i>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-xs font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">
                            {{ $h->type }}
                        </span>
                        @if (!empty($h->distance_km))
                        <span
                            class="text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-3 py-1 rounded-full shadow-2xs">
                            {{ $h->distance_km }} km {{ $locale === 'hi' ? 'दूर' : 'away' }}
                        </span>
                        @endif
                        <span
                            class="text-xs font-bold text-slate-600 dark:text-slate-350 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1 rounded-full shadow-2xs truncate max-w-[120px]">
                            {{ $hCity }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col space-y-5">
                <!-- Mobile Accordion Toggle Button -->
                <button type="button" onclick="toggleMobileAccordion('hosp-{{ $h->id }}')"
                    class="md:hidden w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 transition-all duration-200">
                    <span class="flex items-center space-x-2">
                        <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                        <span
                            id="hosp-{{ $h->id }}-text">{{ $locale === 'hi' ? 'अतिरिक्त विवरण देखें' : 'View Additional Details' }}</span>
                    </span>
                    <i data-lucide="chevron-down" id="hosp-{{ $h->id }}-icon"
                        class="w-4 h-4 text-slate-500 transition-transform duration-300"></i>
                </button>

                <!-- Collapsible Content (Hidden on Mobile by default, visible on MD+) -->
                <div id="hosp-{{ $h->id }}-content" class="hidden md:flex flex-col space-y-5 flex-1">
                    <div
                        class="flex items-start space-x-3 text-slate-600 dark:text-slate-350 text-xs leading-relaxed bg-slate-50/80 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-750 shadow-2xs">
                        <i data-lucide="map-pin" class="w-4 h-4 text-teal-500 shrink-0 mt-0.5"></i>
                        <div class="flex-1 space-y-1">
                            <div>
                                {{ !empty($hAddressLine1) ? $hAddressLine1 . ', ' . (!empty($hAddressLine2) ? $hAddressLine2 . ', ' : '') . $hCity . ', ' . $hState . ' - ' . $h->pincode : $hAddress . ', ' . $hCity }}
                            </div>
                            @if (!empty($h->latitude) && !empty($h->longitude))
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $h->latitude }},{{ $h->longitude }}"
                                target="_blank"
                                class="inline-flex items-center space-x-1 text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-bold mt-1 bg-teal-50/80 dark:bg-teal-950/40 px-2.5 py-1 rounded-lg border border-teal-100 dark:border-teal-900 transition-colors">
                                <i data-lucide="navigation" class="w-3 h-3"></i>
                                <span>{{ $locale === 'hi' ? 'नक्शे पर दिशा व दूरी देखें' : 'View Map & Directions' }}</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Govt Schemes & Cashless Facilities -->
                    @if (
                    !empty($h->accepts_ayushman) ||
                    !empty($h->accepts_janaadhaar) ||
                    !empty($h->accepts_cghs) ||
                    !empty($h->is_cashless) ||
                    (!empty($h->cashless_schemes_list) &&
                    is_array($h->cashless_schemes_list) &&
                    count($h->cashless_schemes_list) > 0))
                    <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span
                            class="text-xs font-bold text-slate-700 dark:text-slate-350 block">{{ $locale === 'hi' ? 'उपलब्ध स्वास्थ्य योजनाएं व सुविधाएं:' : 'Available Health Schemes & Facilities:' }}</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if (!empty($h->accepts_ayushman))
                            <span
                                class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-350 border border-emerald-200 dark:border-emerald-900/60 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center space-x-1 shadow-2xs">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <span>{{ $locale === 'hi' ? 'आयुष्मान कार्ड' : 'Ayushman Card' }}</span>
                            </span>
                            @endif
                            @if (!empty($h->accepts_janaadhaar))
                            <span
                                class="bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-350 border border-blue-200 dark:border-blue-900/60 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center space-x-1 shadow-2xs">
                                <i data-lucide="award" class="w-3.5 h-3.5 text-blue-600"></i>
                                <span>{{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}</span>
                            </span>
                            @endif
                            @if (!empty($h->accepts_cghs))
                            <span
                                class="bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-350 border border-purple-200 dark:border-purple-900/60 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center space-x-1 shadow-2xs">
                                <i data-lucide="check-badge" class="w-3.5 h-3.5 text-purple-600"></i>
                                <span>{{ $locale === 'hi' ? 'सीजीएचएस (CGHS)' : 'CGHS Govt' }}</span>
                            </span>
                            @endif
                            @if (!empty($h->is_cashless))
                            <span
                                class="bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-350 border border-teal-200 dark:border-teal-900/60 px-2.5 py-1 rounded-lg text-xs font-bold flex items-center space-x-1 shadow-2xs">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5 text-teal-600"></i>
                                <span>{{ $locale === 'hi' ? 'कैशलेस सुविधा' : 'Cashless Facility' }}</span>
                            </span>
                            @endif
                        </div>
                        @if (!empty($h->cashless_schemes_list) && is_array($h->cashless_schemes_list) && count($h->cashless_schemes_list) > 0)
                        <div
                            class="mt-2 bg-slate-50 dark:bg-slate-800/65 p-2.5 rounded-xl border border-slate-200/60 dark:border-slate-700/60 text-xs text-slate-600 dark:text-slate-350">
                            <span
                                class="font-bold text-slate-700 dark:text-slate-300 block mb-1">{{ $locale === 'hi' ? 'पैनल में शामिल बीमा/योजनाएं:' : 'Empanelled Insurance/Schemes:' }}</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($h->cashless_schemes_list as $scheme)
                                <span
                                    class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-md text-[11px] font-medium text-slate-700 dark:text-slate-300 shadow-2xs">{{ $scheme }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="flex-grow flex flex-col justify-end space-y-3 pt-2">
                        <div
                            class="flex items-center justify-between text-xs p-3.5 bg-teal-50/50 dark:bg-teal-950/20 rounded-2xl border border-teal-100 dark:border-teal-900/60">
                            <div class="flex items-center space-x-2 text-teal-900 dark:text-teal-350 font-bold">
                                <i data-lucide="phone-call" class="w-4 h-4 text-teal-600 dark:text-teal-400 animate-pulse"></i>
                                <span>{{ $locale === 'hi' ? 'आपातकालीन फ़ोन:' : 'Emergency Phone:' }}</span>
                            </div>
                            <span class="text-slate-900 dark:text-slate-100 font-extrabold tracking-wide select-all">
                                {{ $h->emergency_phone }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="p-6 pt-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <a href="{{ route('hospitals.doctors', $h->id) }}"
                        class="w-full bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 dark:hover:bg-slate-750 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98">
                        <i data-lucide="users" class="w-4 h-4 text-teal-300"></i>
                        <span>{{ $locale === 'hi' ? 'डॉक्टर देखें' : 'View Doctors' }}</span>
                    </a>
                    <a href="tel:{{ $h->emergency_phone }}"
                        class="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98">
                        <i data-lucide="phone-call" class="w-4 h-4 text-white"></i>
                        <span>{{ $locale === 'hi' ? 'अभी कॉल करें' : 'Call Now' }}</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-10">
        {{ $hospitals->links('pagination::tailwind') }}
    </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
    function setUserLocationAndSubmit(form) {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported on this device/browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('user_lat').value = position.coords.latitude.toFixed(6);
            document.getElementById('user_lng').value = position.coords.longitude.toFixed(6);
            form.submit();
        }, function() {
            alert('Unable to fetch your location. Please enable location permission.');
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        });
    }

    function toggleMobileAccordion(id) {
        const content = document.getElementById(id + '-content');
        const icon = document.getElementById(id + '-icon');
        const text = document.getElementById(id + '-text');
        const isHi = "{{ $locale }}" === "hi";

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.classList.add('flex');
            icon.classList.add('rotate-180');
            text.innerText = isHi ? 'विवरण छुपाएं' : 'Hide Details';
        } else {
            content.classList.add('hidden');
            content.classList.remove('flex');
            icon.classList.remove('rotate-180');
            text.innerText = isHi ? 'अतिरिक्त विवरण देखें' : 'View Additional Details';
        }
    }

    // Client-side quick filtering logic
    let activeClientFilters = {
        ayushman: false,
        cashless: false,
        verified: false
    };

    function toggleClientFilter(type) {
        activeClientFilters[type] = !activeClientFilters[type];

        const btn = document.getElementById('client-filter-' + type);
        if (btn) {
            if (activeClientFilters[type]) {
                btn.classList.add('bg-teal-50', 'dark:bg-teal-950/40', 'border-teal-500', 'text-teal-700', 'dark:text-teal-300');
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-350');
            } else {
                btn.classList.remove('bg-teal-50', 'dark:bg-teal-950/40', 'border-teal-500', 'text-teal-700', 'dark:text-teal-300');
                btn.classList.add('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-350');
            }
        }

        applyClientFilters();
    }

    function applyClientFilters() {
        const cards = document.querySelectorAll('[data-ayushman]');
        cards.forEach(card => {
            let show = true;
            if (activeClientFilters.ayushman && card.getAttribute('data-ayushman') !== 'true') {
                show = false;
            }
            if (activeClientFilters.cashless && card.getAttribute('data-cashless') !== 'true') {
                show = false;
            }
            if (activeClientFilters.verified && card.getAttribute('data-verified') !== 'true') {
                show = false;
            }

            if (show) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }


</script>
@endpush

