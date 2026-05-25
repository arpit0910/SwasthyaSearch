@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics') . ' - SwasthyaSearch')

@php
$seoCityInput = request('city');
$seoCity = is_array($seoCityInput) ? ($seoCityInput[0] ?? null) : $seoCityInput;
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
    <div class="text-center">
        <p class="text-sm text-slate-600 max-w-5xl mx-auto mb-4">
            Compare hospitals by city, facility type, and health benefits. Please call the hospital before visiting to confirm services, timings, and emergency availability.
        </p>
    </div>
</section>

<section class="lg:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 sm:-mt-8 relative z-20 w-full mb-6">
    <form action="{{ route('hospitals.index') }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-4 sm:p-5 backdrop-blur-xl">
        @csrf
        @foreach ((array) request('type', []) as $typeVal)
        <input type="hidden" name="type[]" value="{{ $typeVal }}">
        @endforeach
        @foreach ((array) request('city', []) as $cityVal)
        <input type="hidden" name="city[]" value="{{ $cityVal }}">
        @endforeach
        @foreach ((array) request('benefit', []) as $benefitVal)
        <input type="hidden" name="benefit[]" value="{{ $benefitVal }}">
        @endforeach
        <input type="hidden" name="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="relative">
            <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
            <input type="text" name="search"
                placeholder="Search hospitals, services, location..."
                value="{{ request('search', $filters['search'] ?? '') }}"
                class="h-12 w-full pl-10 pr-14 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            <button type="button" onclick="openMobileFilters()" class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg border border-teal-200 bg-white text-teal-700 hover:bg-teal-50 flex items-center justify-center" title="Filters">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</section>

<!-- Filter Sidebar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full mb-2 lg:mb-8">
    <!-- Mobile Filter Button (visible on screens smaller than lg) -->

    <!-- Filter Sidebar (Mobile) - Hidden by default -->
    <div id="mobile-filter-sidebar" class="fixed inset-0 z-50 hidden lg:hidden">
        <!-- Backdrop -->
        <div id="mobile-filter-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <!-- Sidebar -->
        <div class="absolute top-0 right-0 h-full w-full max-w-sm bg-white shadow-2xl overflow-y-auto">
            <!-- Header -->
            <div class="sticky top-0 p-3 sm:p-4 border-b border-slate-200 bg-white flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">{{ $locale === 'hi' ? 'फ़िल्टर' : 'Filters' }}</h2>
                <button type="button" id="mobile-filter-close" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-600"></i>
                </button>
            </div>

            <!-- Filter Form (Mobile) -->
            <form action="{{ route('hospitals.index') }}" method="POST" class="p-3 sm:p-4 space-y-4" id="mobile-filter-form">
                @csrf
                <input type="hidden" name="user_lat" id="user_lat_mobile" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
                <input type="hidden" name="user_lng" id="user_lng_mobile" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">

                <!-- Search Input -->
                <div class="hidden">
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'खोज' : 'Search' }}</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
                        <input type="text" name="search"
                            placeholder="{{ $locale === 'hi' ? 'खोजें...' : 'Search...' }}"
                            value="{{ request('search', $filters['search'] ?? '') }}"
                            class="h-12 w-full pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
                    </div>
                </div>
                <input type="hidden" name="search" value="{{ request('search', $filters['search'] ?? '') }}">

                <!-- Type Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'प्रकार' : 'Facility Type' }}</label>
                    @php 
                        $selectedTypes = is_array(request('type')) ? request('type') : (request('type') && request('type') !== 'All' ? [request('type')] : []);
                    @endphp
                    <select name="type[]" multiple
                        class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($types as $t)
                        <option value="{{ $t }}" {{ in_array($t, $selectedTypes) ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- City Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'शहर' : 'City' }}</label>
                    @php 
                        $selectedCities = is_array(request('city')) ? request('city') : (request('city') && request('city') !== 'All' ? [request('city')] : []);
                    @endphp
                    <select name="city[]" multiple
                        class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($cities as $c)
                        <option value="{{ $c }}" {{ in_array($c, $selectedCities) ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Benefit Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'स्वास्थ्य योजनाएं' : 'Health Benefits' }}</label>
                    @php 
                        $selectedBenefits = is_array(request('benefit')) ? request('benefit') : (request('benefit') && request('benefit') !== 'All' ? [request('benefit')] : []);
                    @endphp
                    <select name="benefit[]" multiple
                        class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        <option value="ayushman" {{ in_array('ayushman', $selectedBenefits) ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? 'आयुष्मान कार्ड' : 'Ayushman Card' }}
                        </option>
                        <option value="janaadhaar" {{ in_array('janaadhaar', $selectedBenefits) ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}
                        </option>
                        <option value="cghs" {{ in_array('cghs', $selectedBenefits) ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? 'सीजीएचएस (CGHS)' : 'CGHS Govt' }}
                        </option>
                        <option value="cashless" {{ in_array('cashless', $selectedBenefits) ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? 'कैशलेस सुविधा' : 'Cashless Facility' }}
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="space-y-2.5 pt-3 border-t border-slate-200">
                    <button type="button" onclick="setUserLocationAndSubmitMobile()"
                        class="w-full h-11 px-4 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="locate-fixed" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'मेरे नजदीक' : 'Show Nearby' }}</span>
                    </button>
                    <button type="submit" class="w-full h-11 px-4 rounded-xl border border-indigo-300 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
                    </button>
                    <a href="{{ route('hospitals.index') }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset' }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop Filter Form (lg and above) -->
    <form action="{{ route('hospitals.index') }}" method="POST"
        class="hidden lg:block bg-white rounded-2xl shadow-xl border border-slate-200/80 ring-1 ring-slate-200/70 p-5 sm:p-6 backdrop-blur-xl" id="filter-form">
        @csrf
        <input type="hidden" name="user_lat" id="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" id="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative">
                <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
                <input type="text" name="search"
                    placeholder="{{ $locale === 'hi' ? 'अस्पताल, क्लिनिक, पता या शहर खोजें...' : 'Search hospitals, clinics, address, city...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            </div>

            <!-- Type Filter - Multiselect -->
            <div>
                @php 
                    $selectedTypes = is_array(request('type')) ? request('type') : (request('type') && request('type') !== 'All' ? [request('type')] : []);
                @endphp
                <select name="type[]" multiple
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($types as $t)
                    <option value="{{ $t }}" {{ in_array($t, $selectedTypes) ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter - Multiselect -->
            <div>
                @php 
                    $selectedCities = is_array(request('city')) ? request('city') : (request('city') && request('city') !== 'All' ? [request('city')] : []);
                @endphp
                <select name="city[]" multiple
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($cities as $c)
                    <option value="{{ $c }}" {{ in_array($c, $selectedCities) ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Benefit Filter - Multiselect -->
            <div>
                @php 
                    $selectedBenefits = is_array(request('benefit')) ? request('benefit') : (request('benefit') && request('benefit') !== 'All' ? [request('benefit')] : []);
                @endphp
                <select name="benefit[]" multiple
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="ayushman" {{ in_array('ayushman', $selectedBenefits) ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'आयुष्मान कार्ड' : 'Ayushman Card' }}
                    </option>
                    <option value="janaadhaar" {{ in_array('janaadhaar', $selectedBenefits) ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}
                    </option>
                    <option value="cghs" {{ in_array('cghs', $selectedBenefits) ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सीजीएचएस (CGHS)' : 'CGHS Govt' }}
                    </option>
                    <option value="cashless" {{ in_array('cashless', $selectedBenefits) ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'कैशलेस सुविधा' : 'Cashless Facility' }}
                    </option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-6 pt-6 border-t border-slate-100">
            <button type="button" onclick="setUserLocationAndSubmit(this.form)"
                class="h-12 px-5 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="locate-fixed" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
            </button>
            <button type="submit"
                class="h-12 px-5 rounded-xl border border-indigo-300 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
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
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full mt-0 lg:mt-4 pb-20">
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
                    class="w-12 h-12 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                    <div
                        class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center text-white">
                        <i data-lucide="building-2" class="w-6 h-6 text-teal-400"></i>
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
                            class="text-[11px] font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 px-2 py-0.5 rounded-lg shadow-2xs uppercase tracking-wider">
                            {{ $h->type }}
                        </span>
                        @if ($h->distance_km !== null)
                        <span
                            class="text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-lg shadow-2xs">
                            {{ $h->distance_km }} km {{ $locale === 'hi' ? 'दूर' : 'away' }}
                        </span>
                        @endif
                        <span
                            class="text-[11px] font-bold text-slate-600 dark:text-slate-350 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-lg shadow-2xs truncate max-w-[120px]">
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
    function enhanceMultiSelectDropdown(selectEl) {
        if (!selectEl || selectEl.dataset.enhanced === '1') return;

        selectEl.classList.add('hidden');

        const wrapper = document.createElement('div');
        wrapper.className = 'relative multi-select-dropdown';

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'h-12 w-full px-4 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 flex items-center justify-between';
        trigger.innerHTML = '<span class="multi-select-label truncate text-left">Select options</span><i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>';

        const panel = document.createElement('div');
        panel.className = 'hidden absolute z-50 mt-2 w-full max-h-64 overflow-auto rounded-xl border border-slate-200 bg-white shadow-lg p-2 space-y-1';

        Array.from(selectEl.options).forEach((opt, idx) => {
            const row = document.createElement('button');
            row.type = 'button';
            row.dataset.index = String(idx);
            row.className = `w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${
                opt.selected ? 'bg-teal-100 text-teal-800 font-semibold' : 'text-slate-700 hover:bg-slate-50'
            }`;
            row.textContent = opt.textContent.trim();
            panel.appendChild(row);
        });

        selectEl.insertAdjacentElement('afterend', wrapper);
        wrapper.appendChild(trigger);
        wrapper.appendChild(panel);
        wrapper.insertAdjacentElement('afterend', selectEl);

        const updateLabel = () => {
            const selected = Array.from(selectEl.selectedOptions).map(o => o.textContent.trim()).filter(Boolean);
            const label = wrapper.querySelector('.multi-select-label');
            label.textContent = selected.length ? selected.join(', ') : 'Select options';
        };

        trigger.addEventListener('click', () => {
            panel.classList.toggle('hidden');
            if (window.lucide) lucide.createIcons();
        });

        panel.querySelectorAll('button[data-index]').forEach((rowBtn) => {
            rowBtn.addEventListener('click', () => {
                const optionIndex = Number(rowBtn.dataset.index);
                if (selectEl.options[optionIndex]) {
                    const nextState = !selectEl.options[optionIndex].selected;
                    selectEl.options[optionIndex].selected = nextState;
                    rowBtn.className = `w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${
                        nextState ? 'bg-teal-100 text-teal-800 font-semibold' : 'text-slate-700 hover:bg-slate-50'
                    }`;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                }
                updateLabel();
            });
        });

        if (!document.body.dataset.multiSelectOutsideBound) {
            document.addEventListener('click', (e) => {
                document.querySelectorAll('.multi-select-dropdown').forEach((dd) => {
                    if (!dd.contains(e.target)) {
                        dd.querySelector('div.absolute')?.classList.add('hidden');
                    }
                });
            });
            document.body.dataset.multiSelectOutsideBound = '1';
        }

        updateLabel();
        selectEl.dataset.enhanced = '1';
        if (window.lucide) lucide.createIcons();
    }

    function renderMultiSelectBadges(selectEl) {
        if (!selectEl) return;
        let badgeWrap = selectEl.parentElement.querySelector('.selected-badges');
        if (!badgeWrap) {
            badgeWrap = document.createElement('div');
            badgeWrap.className = 'selected-badges mt-2 flex flex-wrap gap-1.5';
            selectEl.parentElement.appendChild(badgeWrap);
        }

        const selected = Array.from(selectEl.selectedOptions).map(opt => opt.textContent.trim()).filter(Boolean);
        if (selected.length === 0) {
            badgeWrap.innerHTML = '<span class="text-[11px] text-slate-400 italic">No filters selected</span>';
            return;
        }

        badgeWrap.innerHTML = Array.from(selectEl.selectedOptions).map(opt =>
            `<button type="button" data-remove-value="${opt.value}" class="inline-flex items-center gap-1 rounded-full border border-teal-200 bg-teal-50 px-2.5 py-1 text-[11px] font-semibold text-teal-700 hover:bg-teal-100">${opt.textContent.trim()} <span class="text-teal-900">x</span></button>`
        ).join('');

        if (!badgeWrap.dataset.removeBound) {
            badgeWrap.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-remove-value]');
                if (!btn) return;
                const val = btn.getAttribute('data-remove-value');
                const option = Array.from(selectEl.options).find((o) => o.value === val);
                if (!option) return;
                option.selected = false;
                const panelBtn = selectEl.parentElement.querySelector(`.multi-select-dropdown button[data-index="${Array.from(selectEl.options).indexOf(option)}"]`);
                if (panelBtn) {
                    panelBtn.className = 'w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors text-slate-700 hover:bg-slate-50';
                }
                selectEl.dispatchEvent(new Event('change', { bubbles: true }));
            });
            badgeWrap.dataset.removeBound = '1';
        }
    }

    function initMultiSelectBadges(scope = document) {
        const selects = scope.querySelectorAll('select[multiple]');
        selects.forEach((selectEl) => {
            enhanceMultiSelectDropdown(selectEl);
            renderMultiSelectBadges(selectEl);
            if (!selectEl.dataset.badgeBound) {
                selectEl.addEventListener('change', () => renderMultiSelectBadges(selectEl));
                selectEl.dataset.badgeBound = '1';
            }
        });
    }

    function openMobileFilters() {
        const sidebar = document.getElementById('mobile-filter-sidebar');
        if (!sidebar) return;
        sidebar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        initMultiSelectBadges(sidebar);
    }

    // Mobile Filter Sidebar Toggle
    document.getElementById('mobile-filter-close')?.addEventListener('click', function() {
        document.getElementById('mobile-filter-sidebar').classList.add('hidden');
        document.body.style.overflow = '';
    });

    document.getElementById('mobile-filter-backdrop')?.addEventListener('click', function() {
        document.getElementById('mobile-filter-sidebar').classList.add('hidden');
        document.body.style.overflow = '';
    });

    document.addEventListener('DOMContentLoaded', function() {
        initMultiSelectBadges(document);
    });

    function setUserLocationAndSubmitMobile() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported on this device/browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('user_lat_mobile').value = position.coords.latitude.toFixed(6);
            document.getElementById('user_lng_mobile').value = position.coords.longitude.toFixed(6);
            document.getElementById('mobile-filter-form').submit();
        }, function() {
            alert('Unable to fetch your location. Please enable location permission.');
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        });
    }

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

