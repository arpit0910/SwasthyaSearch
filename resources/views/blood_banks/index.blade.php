@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'ब्लड बैंक निर्देशिका' : 'Blood Banks Directory') . ' - Arogio')

@php
$seoCityInput = request('city');
$seoCity = $activeCity ?? config('healthcare.active_city', 'Jaipur');
$hasCity = true;
$pageTitle = "Blood Banks | Emergency Contacts | Arogio";
$pageDescription = $hasCity
? "Find blood banks in {$seoCity} and contact them directly to confirm current blood availability before visiting."
: 'Find blood banks and contact them directly to confirm current blood availability before visiting.';
$hasActiveMobileFilters = !empty(array_filter((array) request('blood_group', [])))
    || !empty(array_filter((array) request('facility', [])))
    || !empty(array_filter((array) request('city', [])));
$isNearbyActive = filled(request('user_lat')) && filled(request('user_lng'));
$normalizeCityOption = function ($city): string {
    if (is_array($city)) {
        $city = $city['name'] ?? $city['city'] ?? $city['label'] ?? $city['value'] ?? reset($city);
    }

    return is_scalar($city) ? trim((string) $city) : '';
};

$cityOptions = collect((array) ($cities ?? []))
    ->map($normalizeCityOption)
    ->filter()
    ->unique(fn ($city) => strtolower(preg_replace('/\s+/', ' ', $city)))
    ->values();
if ($cityOptions->isEmpty() && filled($seoCity)) {
    $cityOptions = collect([$seoCity]);
}
$selectedCity = request('city');
$selectedCity = is_array($selectedCity) ? ($selectedCity[0] ?? null) : $selectedCity;
$selectedCity = filled($selectedCity) ? trim((string) $selectedCity) : ($cityOptions->first() ?? $seoCity);
@endphp
@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-rose-600 via-rose-700 to-red-700 dark:from-slate-900 dark:via-red-900 dark:to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-rose-800 dark:border-slate-700 ring-1 ring-black/10 dark:ring-white/15 shadow-xl dark:shadow-black/50 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(251,113,133,0.18),transparent_55%)] dark:opacity-0"></div>
    <!-- Glowing background blobs -->
    <div class="glow-blob w-[300px] h-[300px] bg-rose-300/15 dark:opacity-0 top-0 left-0 absolute rounded-full blur-3xl"></div>
    <div class="glow-blob w-[400px] h-[400px] bg-red-300/15 dark:opacity-0 bottom-0 right-0 absolute rounded-full blur-3xl"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-white/10 text-rose-100 border border-white/25 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm backdrop-blur-sm">
            {{ $locale === 'hi' ? 'सत्यापित रक्त केंद्र' : 'Verified Blood Centers' }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-tight">
            {{ $locale === 'hi' ? 'ब्लड बैंक खोजें' : 'Find Blood Banks' }}
        </h1>
        <p class="max-w-4xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'सत्यापित और लाइसेंस प्राप्त ब्लड बैंक खोजें। रक्त उपलब्धता तेजी से बदल सकती है, इसलिए जाने से पहले कॉल करके पुष्टि करें।' : 'Find verified, licensed blood banks. Blood availability can change quickly, so please call to confirm before visiting.' }}
        </p>
    </div>
</header>

<section class="lg:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 relative z-20 w-full mb-6">
    <form action="{{ route('blood_banks.index') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700/70 p-4 sm:p-5 backdrop-blur-xl">
        @csrf
        @foreach ((array) request('blood_group', []) as $bgVal)
        <input type="hidden" name="blood_group[]" value="{{ $bgVal }}">
        @endforeach
        @foreach ((array) request('facility', []) as $facVal)
        <input type="hidden" name="facility[]" value="{{ $facVal }}">
        @endforeach
        <input type="hidden" name="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="relative">
            <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
            <input type="text" name="search"
                placeholder="{{ $locale === 'hi' ? 'ब्लड बैंक का नाम या स्थान खोजें...' : 'Search blood bank name or location...' }}"
                value="{{ request('search', $filters['search'] ?? '') }}"
                class="h-12 w-full pl-10 pr-12 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium" />
            <button type="button" data-open-mobile-filters onclick="openMobileFilters()" aria-label="Open filters" class="lg:hidden absolute right-1.5 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg border flex items-center justify-center transition-all duration-200 {{ $hasActiveMobileFilters ? 'border-red-600 bg-red-600 text-white shadow-md shadow-red-500/30' : 'border-red-200 bg-white text-red-700 hover:bg-red-50' }}" title="Filters">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </div>
        <button type="button" onclick="toggleNearby(this)" data-nearby-toggle data-nearby-theme="red" class="mt-3 w-full h-11 px-4 rounded-xl border font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs {{ $isNearbyActive ? 'border-red-600 dark:border-red-500 bg-red-600 dark:bg-red-600 text-white' : 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/35 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-700 dark:text-red-300' }}">
            <i data-lucide="locate-fixed" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
        </button>
    </form>
</section>

<!-- Mobile Filter Sidebar -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full mb-2 lg:mb-8">
    <div id="mobile-filter-sidebar" class="fixed inset-0 z-[120] hidden lg:hidden" aria-hidden="true">
        <div id="mobile-filter-backdrop" onclick="closeMobileFilters()" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-out"></div>
        <div id="mobile-filter-drawer" class="absolute top-0 right-0 h-full w-full max-w-sm bg-white dark:bg-slate-900 shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300 ease-out">
            <div class="sticky top-0 p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'फ़िल्टर' : 'Filters' }}</h2>
                <button type="button" id="mobile-filter-close" onclick="closeMobileFilters()" aria-label="Close filters" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                </button>
            </div>
            <form action="{{ route('blood_banks.index') }}" method="POST" class="p-3 sm:p-4 space-y-4" id="mobile-filter-form">
                @csrf
                <input type="hidden" name="search" value="{{ request('search', $filters['search'] ?? '') }}">
                <input type="hidden" name="user_lat" id="user_lat_mobile" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
                <input type="hidden" name="user_lng" id="user_lng_mobile" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'रक्त समूह' : 'Blood Group' }}</label>
                    @php $selectedBG = is_array(request('blood_group')) ? request('blood_group') : (request('blood_group') && request('blood_group') !== 'All' ? [request('blood_group')] : []); @endphp
                    <select name="blood_group[]" multiple data-placeholder="{{ $locale === 'hi' ? 'ब्लड ग्रुप चुनें' : 'Select blood groups' }}" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($bloodGroups as $bg)
                        <option value="{{ $bg }}" {{ in_array($bg, $selectedBG) ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="hidden">
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'सुविधाएं' : 'Facilities' }}</label>
                    @php $selectedFac = is_array(request('facility')) ? request('facility') : (request('facility') && request('facility') !== 'All' ? [request('facility')] : []); @endphp
                    <select name="facility[]" multiple data-placeholder="{{ $locale === 'hi' ? 'सुविधा चुनें' : 'Select facilities' }}" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                        <option value="24x7" {{ in_array('24x7', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? '24x7 उपलब्ध' : '24x7 Available' }}</option>
                        <option value="Government" {{ in_array('Government', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सरकारी ब्लड बैंक' : 'Government Blood Bank' }}</option>
                        <option value="Private" {{ in_array('Private', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'निजी ब्लड बैंक' : 'Private Blood Bank' }}</option>
                        <option value="Component" {{ in_array('Component', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'रक्त घटक' : 'Blood Components' }}</option>
                        <option value="Apheresis" {{ in_array('Apheresis', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'एफेरेसिस' : 'Apheresis (SDP)' }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'शहर' : 'City' }}</label>
                    <select name="city[]" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($cityOptions as $cityOption)
                        <option value="{{ $cityOption }}" {{ $selectedCity === $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2.5 pt-3 border-t border-slate-200">
                    <button type="submit" class="w-full h-11 px-4 rounded-xl border border-red-300 bg-red-600 hover:bg-red-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
                    </button>
                    <a href="{{ route('blood_banks.index') }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset' }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Desktop Filter Panel -->
<section id="filter-panel" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 w-full mb-2 hidden lg:block">
    <form action="{{ route('blood_banks.index') }}" method="POST" class="bg-white rounded-2xl shadow-xl border border-slate-200/80 ring-1 ring-slate-200/70 p-5 sm:p-6 backdrop-blur-xl" id="filter-form">
        @csrf
        <input type="hidden" name="user_lat" id="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" id="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative">
                <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $locale === 'hi' ? 'ब्लड बैंक का नाम या स्थान खोजें...' : 'Search blood bank name or location...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium" />
            </div>

            <!-- Blood Group Filter - Multiselect -->
            <div>
                @php 
                    $selectedBG = is_array(request('blood_group')) ? request('blood_group') : (request('blood_group') && request('blood_group') !== 'All' ? [request('blood_group')] : []);
                @endphp
                <select
                    name="blood_group[]"
                    multiple
                    data-placeholder="{{ $locale === 'hi' ? 'ब्लड ग्रुप चुनें' : 'Select blood groups' }}"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($bloodGroups as $bg)
                    <option value="{{ $bg }}" {{ in_array($bg, $selectedBG) ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Facility Filter - Multiselect -->
            <div>
                @php 
                    $selectedFac = is_array(request('facility')) ? request('facility') : (request('facility') && request('facility') !== 'All' ? [request('facility')] : []);
                @endphp
                <select
                    name="facility[]"
                    multiple
                    data-placeholder="{{ $locale === 'hi' ? 'सुविधा चुनें' : 'Select facilities' }}"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="24x7" {{ in_array('24x7', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? '24x7 उपलब्ध' : '24x7 Available' }}</option>
                    <option value="Government" {{ in_array('Government', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सरकारी ब्लड बैंक' : 'Government Blood Bank' }}</option>
                    <option value="Private" {{ in_array('Private', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'निजी ब्लड बैंक' : 'Private Blood Bank' }}</option>
                    <option value="Component" {{ in_array('Component', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'रक्त घटक (प्लेटलेट्स आदि)' : 'Blood Components' }}</option>
                    <option value="Apheresis" {{ in_array('Apheresis', $selectedFac) ? 'selected' : '' }}>{{ $locale === 'hi' ? 'एफेरेसिस (एसडीपी)' : 'Apheresis (SDP)' }}</option>
                </select>
            </div>

            <!-- City Filter -->
            <div>
                <select name="city[]" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($cityOptions as $cityOption)
                    <option value="{{ $cityOption }}" {{ $selectedCity === $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-6 pt-6 border-t border-slate-100">
            <button type="button" onclick="toggleNearby(this)" data-nearby-toggle data-nearby-theme="red"
                class="h-12 px-5 rounded-xl border font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs {{ $isNearbyActive ? 'border-red-600 bg-red-600 text-white' : 'border-red-200 bg-red-50 hover:bg-red-100 text-red-700' }}">
                <i data-lucide="locate-fixed" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
            </button>
            <button type="submit"
                class="h-12 px-5 rounded-xl border border-red-300 bg-red-600 hover:bg-red-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
            </button>
            <a
                href="{{ route('blood_banks.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>
        </div>
    </form>
</section>

<!-- Blood Banks Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full mt-0 lg:mt-4 pb-20">
    @if (count($bloodBanks) === 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 border border-red-100 shadow-inner">
            <i data-lucide="search" class="w-10 h-10"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">
            {{ $locale === 'hi' ? 'कोई ब्लड बैंक नहीं मिला' : 'No Blood Banks Found' }}
        </h3>
        <p class="text-slate-500 text-base mb-8 leading-relaxed">
            {{ $locale === 'hi' ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई ब्लड बैंक नहीं मिला। कृपया अपनी खोज मानदंड बदलें।' : 'We could not find any blood banks matching your selected filters. Please try modifying your search criteria.' }}
        </p>
        <a
            href="{{ route('blood_banks.index') }}"
            class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'सभी ब्लड बैंक देखें' : 'View All Blood Banks' }}</span>
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($bloodBanks as $bankObj)
        @php
        $bank = is_array($bankObj) ? (object) $bankObj : $bankObj;
        $bNameEn = is_array($bank->name) ? $bank->name['en'] : ($bank->name['en'] ?? $bank->name_en);
        $bNameHi = is_array($bank->name) ? $bank->name['hi'] : ($bank->name['hi'] ?? $bank->name_hi);
        $bName = $locale === 'hi' ? ($bNameHi ?: $bNameEn) : $bNameEn;
        $addrEn = is_array($bank->address) ? $bank->address['en'] : ($bank->address['en'] ?? $bank->address_en);
        $addrHi = is_array($bank->address) ? $bank->address['hi'] : ($bank->address['hi'] ?? $bank->address_hi);
        $addr = $locale === 'hi' ? ($addrHi ?: $addrEn) : $addrEn;
        $mapDirectionsUrl = $bank->map_directions_url;
        @endphp
        <div class="glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1 relative"
            data-twentyfour="{{ !empty($bank->is_24_7) ? 'true' : 'false' }}"
            data-govt="{{ !empty($bank->is_government) ? 'true' : 'false' }}"
            data-component="{{ !empty($bank->component_facility) ? 'true' : 'false' }}">

            <!-- Card Header -->
            <div class="p-6 pb-4 bg-gradient-to-br from-slate-50/50 via-white/50 to-slate-50/50 dark:from-slate-800/30 dark:via-transparent dark:to-slate-800/30 border-b border-slate-100 dark:border-slate-800/60 flex items-start space-x-4">
                <div class="w-10 h-10 bg-gradient-to-tr from-red-500 to-rose-600 rounded-xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center text-white font-extrabold text-sm tracking-wider">
                        <i data-lucide="droplet" class="w-5 h-5 text-red-500 fill-red-500"></i>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white line-clamp-3 leading-snug group-hover:text-red-600 transition-colors duration-200">
                            {{ $bName }}
                        </h3>
                        <div class="shrink-0 flex items-center gap-1.5 mt-0.5 relative">
                            @if ($bank->is_verified)
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500"></i>
                            @endif
                            <button
                                type="button"
                                onclick="toggleListingActionMenu(event, 'blood_bank-actions-{{ $bank->id }}')"
                                aria-label="{{ $locale === 'hi' ? 'और विकल्प' : 'More actions' }}"
                                aria-expanded="false"
                                class="listing-action-trigger w-8 h-8 inline-flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white/95 dark:bg-slate-900/90 hover:bg-slate-100 dark:hover:bg-slate-800 shadow-sm transition-all duration-200">
                                <i data-lucide="ellipsis" class="w-4 h-4"></i>
                            </button>
                            <div id="blood_bank-actions-{{ $bank->id }}" class="listing-action-menu hidden absolute right-0 top-10 z-20 w-52 rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 shadow-xl p-2 backdrop-blur">
                                <button
                                    type="button"
                                    onclick="submitUsefulVoteAndClose('blood_bank', {{ $bank->id }}, @js($bName), 'blood_bank-actions-{{ $bank->id }}')"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/30">
                                    <i data-lucide="thumbs-up" class="w-4 h-4 text-emerald-600 dark:text-emerald-300"></i>
                                    <span>{{ $locale === 'hi' ? 'उपयोगी चिह्नित करें' : 'Mark useful' }}</span>
                                </button>
                                <button
                                    type="button"
                                    onclick="openListingReportFromMenu('blood_bank', {{ $bank->id }}, @js($bName), 'blood_bank-actions-{{ $bank->id }}')"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                    <i data-lucide="triangle-alert" class="w-4 h-4 text-rose-600 dark:text-rose-300"></i>
                                    <span>{{ $locale === 'hi' ? 'गलत जानकारी रिपोर्ट करें' : 'Report incorrect details' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-1 mb-2">
                        @if ($bank->distance_km !== null)
                        <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-1.5 py-0.5 rounded-md shadow-2xs">
                            {{ $bank->distance_km }} km {{ $locale === 'hi' ? 'दूर' : 'away' }}
                        </span>
                        @endif
                        @if ($bank->is_24_7)
                        <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-1.5 py-0.5 rounded-md shadow-2xs">
                            {{ $locale === 'hi' ? '24x7 उपलब्ध' : '24x7 Open' }}
                        </span>
                        @endif
                        @if ($bank->is_government)
                        <span class="text-[9px] font-bold uppercase tracking-wider text-cyan-600 dark:text-indigo-300 bg-cyan-50 dark:bg-indigo-950/40 border border-cyan-100 dark:border-indigo-900/60 px-1.5 py-0.5 rounded-md shadow-2xs">
                            {{ $locale === 'hi' ? 'सरकारी' : 'Govt' }}
                        </span>
                        @else
                        <span class="text-[9px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/60 px-1.5 py-0.5 rounded-md shadow-2xs">
                            {{ $locale === 'hi' ? 'प्राइवेट' : 'Private' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col space-y-4">
                <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 space-y-3 shadow-2xs">
                    <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-red-500"></i>
                        <span>{{ $locale === 'hi' ? 'उपलब्ध रक्त समूह' : 'Available Blood Groups' }}</span>
                    </h4>
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        @if (!empty($bank->available_blood_groups) && count($bank->available_blood_groups) > 0)
                        @foreach ($bank->available_blood_groups as $bg)
                        <span class="bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-900/60 px-2.5 py-1 rounded-xl text-xs font-extrabold shadow-2xs flex items-center space-x-1">
                            <i data-lucide="droplet" class="w-3 h-3 text-red-500 fill-red-500"></i>
                            <span>{{ $bg }}</span>
                        </span>
                        @endforeach
                        @else
                        <span class="text-xs text-slate-400 dark:text-slate-500 italic">{{ $locale === 'hi' ? 'जानकारी उपलब्ध नहीं' : 'Stock info unavailable' }}</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 rounded-xl px-2.5 py-1.5">
                        {{ $locale === 'hi' ? 'रक्त उपलब्धता तेजी से बदल सकती है। कृपया जाने से पहले कॉल से पुष्टि करें।' : 'Blood availability can change quickly. Please call to confirm before visiting.' }}
                    </p>
                </div>

                <!-- Mobile Accordion Toggle Button -->
                <button type="button" onclick="toggleMobileAccordion('bank-{{ $bank->id }}')" class="md:hidden w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 transition-all duration-200">
                    <span class="flex items-center space-x-2">
                        <i data-lucide="info" class="w-4 h-4 text-red-600"></i>
                        <span id="bank-{{ $bank->id }}-text">{{ $locale === 'hi' ? 'पता और सुविधाएं देखें' : 'View Address & Facilities' }}</span>
                    </span>
                    <i data-lucide="chevron-down" id="bank-{{ $bank->id }}-icon" class="w-4 h-4 text-slate-500 transition-transform duration-300"></i>
                </button>

                <!-- Collapsible Content -->
                <div id="bank-{{ $bank->id }}-content" class="hidden md:flex flex-col space-y-4 flex-1">
                    <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                        <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                            <i data-lucide="layers" class="w-4 h-4 {{ $bank->component_facility ? 'text-emerald-500' : 'text-slate-300' }} shrink-0"></i>
                            <div class="truncate">
                                <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'रक्त घटक' : 'Components' }}</span>
                                <span class="{{ $bank->component_facility ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-400 font-normal' }}">
                                    {{ $bank->component_facility ? ($locale === 'hi' ? 'उपलब्ध' : 'Available') : ($locale === 'hi' ? 'अनुपलब्ध' : 'N/A') }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/40 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                            <i data-lucide="repeat" class="w-4 h-4 {{ $bank->apheresis_facility ? 'text-teal-500' : 'text-slate-300' }} shrink-0"></i>
                            <div class="truncate">
                                <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'एफेरेसिस (एसडीपी)' : 'Apheresis' }}</span>
                                <span class="{{ $bank->apheresis_facility ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-400 font-normal' }}">
                                    {{ $bank->apheresis_facility ? ($locale === 'hi' ? 'उपलब्ध' : 'Available') : ($locale === 'hi' ? 'अनुपलब्ध' : 'N/A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (!empty($addr))
                    <div class="flex items-start space-x-3 text-slate-600 dark:text-slate-350 text-xs leading-relaxed bg-slate-50/80 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-750 shadow-2xs">
                        <i data-lucide="map-pin" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                        <div class="flex-1 space-y-1">
                            <div>
                                {{ $addr }}
                                @if (!empty($bank->city) || !empty($bank->state))
                                <span class="text-slate-500 text-[11px] block">{{ implode(', ', array_filter([$bank->city, $bank->state, $bank->pincode])) }}</span>
                                @endif
                            </div>
                            @if (!empty($mapDirectionsUrl))
                            <a href="{{ $mapDirectionsUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hidden md:inline-flex items-center space-x-1 text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 font-bold mt-1 bg-teal-50/80 dark:bg-teal-950/40 px-2.5 py-1 rounded-lg border border-teal-100 dark:border-teal-900 transition-colors">
                                <i data-lucide="navigation" class="w-3 h-3"></i>
                                <span>{{ $locale === 'hi' ? 'नक्शा व दिशा-निर्देश' : 'Get Directions' }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if (!empty($bank->last_updated_stock_at))
                    <div class="flex items-center space-x-1.5 text-[11px] text-slate-400 dark:text-slate-500 px-1 pt-1 border-t border-slate-100 dark:border-slate-800 italic">
                        <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                        <span>{{ $locale === 'hi' ? 'स्टॉक अपडेट:' : 'Stock Updated:' }} {{ \Carbon\Carbon::parse($bank->last_updated_stock_at)->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Footer -->
            <div class="p-6 pt-0 flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                @if (!empty($mapDirectionsUrl))
                <a
                    href="{{ $mapDirectionsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="md:hidden w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="navigation" class="w-4 h-4 text-teal-100"></i>
                    <span>{{ $locale === 'hi' ? 'नक्शा व दिशा-निर्देश' : 'Get Directions' }}</span>
                </a>
                @endif
                <a
                    href="{{ !empty($bank->phone) ? 'tel:' . (($bank->country_code ?? '') . $bank->phone) : '#' }}"
                    class="w-full sm:flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="phone" class="w-4 h-4 text-red-100"></i>
                    <span>{{ $locale === 'hi' ? 'अभी कॉल करें' : 'Call Now' }}</span>
                </a>
                @if (!empty($bank->website))
                <a
                    href="{{ str_starts_with($bank->website, 'http') ? $bank->website : 'https://' . $bank->website }}"
                    target="_blank"
                    class="w-full sm:flex-1 bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 dark:hover:bg-slate-750 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="globe" class="w-4 h-4 text-red-400"></i>
                    <span>{{ $locale === 'hi' ? 'वेबसाइट देखें' : 'View Website' }}</span>
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $bloodBanks->links('pagination::tailwind') }}
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
        const placeholder = selectEl.dataset.placeholder || 'Select options';
        trigger.innerHTML = `<span class="multi-select-label truncate text-left">${placeholder}</span><i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>`;

        const panel = document.createElement('div');
        panel.className = 'hidden absolute z-50 mt-2 w-full max-h-64 overflow-auto rounded-xl border border-slate-200 bg-white shadow-lg p-2 space-y-1';

        Array.from(selectEl.options).forEach((opt, idx) => {
            const row = document.createElement('button');
            row.type = 'button';
            row.dataset.index = String(idx);
            row.className = `w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${
                opt.selected ? 'bg-red-100 text-red-800 font-semibold' : 'text-slate-700 hover:bg-slate-50'
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
            label.textContent = placeholder;
        };

        trigger.addEventListener('click', () => {
            panel.classList.toggle('hidden');
            if (window.lucide) window.refreshLucideIcons();
        });

        panel.querySelectorAll('button[data-index]').forEach((rowBtn) => {
            rowBtn.addEventListener('click', () => {
                const optionIndex = Number(rowBtn.dataset.index);
                if (selectEl.options[optionIndex]) {
                    const nextState = !selectEl.options[optionIndex].selected;
                    selectEl.options[optionIndex].selected = nextState;
                    rowBtn.className = `w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${
                        nextState ? 'bg-red-100 text-red-800 font-semibold' : 'text-slate-700 hover:bg-slate-50'
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
        if (window.lucide) window.refreshLucideIcons();
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
            badgeWrap.innerHTML = '';
            return;
        }

        badgeWrap.innerHTML = Array.from(selectEl.selectedOptions).map(opt =>
            `<button type="button" data-remove-value="${opt.value}" class="inline-flex items-center gap-1 rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-700 hover:bg-red-100">${opt.textContent.trim()} <span class="text-red-900">x</span></button>`
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
        const drawer = document.getElementById('mobile-filter-drawer');
        const backdrop = document.getElementById('mobile-filter-backdrop');
        if (!sidebar) return;
        sidebar.classList.remove('hidden');
        sidebar.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            drawer?.classList.remove('translate-x-full');
            backdrop?.classList.remove('opacity-0');
        });
        initMultiSelectBadges(sidebar);
    }

    function closeMobileFilters() {
        const sidebar = document.getElementById('mobile-filter-sidebar');
        const drawer = document.getElementById('mobile-filter-drawer');
        const backdrop = document.getElementById('mobile-filter-backdrop');
        if (!sidebar) return;
        drawer?.classList.add('translate-x-full');
        backdrop?.classList.add('opacity-0');
        sidebar.setAttribute('aria-hidden', 'true');
        setTimeout(() => {
            sidebar.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        initMultiSelectBadges(document);
        document.querySelectorAll('[data-open-mobile-filters]').forEach((btn) => {
            btn.addEventListener('click', openMobileFilters);
        });
        document.getElementById('mobile-filter-close')?.addEventListener('click', closeMobileFilters);
        document.getElementById('mobile-filter-backdrop')?.addEventListener('click', closeMobileFilters);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMobileFilters();
            }
        });
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

    function setNearbyButtonState(button, isActive) {
        if (!button) return;
        const activeClasses = ['border-red-600', 'dark:border-red-500', 'bg-red-600', 'dark:bg-red-600', 'text-white'];
        const inactiveClasses = ['border-red-200', 'dark:border-red-800', 'bg-red-50', 'dark:bg-red-950/35', 'hover:bg-red-100', 'dark:hover:bg-red-900/40', 'text-red-700', 'dark:text-red-300'];
        button.classList.remove(...activeClasses, ...inactiveClasses);
        button.classList.add(...(isActive ? activeClasses : inactiveClasses));
    }

    function applyUserLocationToForm(form, lat, lng) {
        const latInput = form?.querySelector('input[name="user_lat"]');
        const lngInput = form?.querySelector('input[name="user_lng"]');
        if (!latInput || !lngInput) {
            alert('Nearby location fields are missing. Please refresh and try again.');
            return false;
        }
        latInput.value = lat;
        lngInput.value = lng;
        return true;
    }

    function clearNearbyAndSubmit(form) {
        const latInput = form?.querySelector('input[name="user_lat"]');
        const lngInput = form?.querySelector('input[name="user_lng"]');
        if (latInput) latInput.value = '';
        if (lngInput) lngInput.value = '';
        form.submit();
    }

    function toggleNearby(button) {
        const form = button?.form;
        if (!form) return;
        const latInput = form.querySelector('input[name="user_lat"]');
        const lngInput = form.querySelector('input[name="user_lng"]');
        const isActive = Boolean(latInput?.value && lngInput?.value);
        if (isActive) {
            setNearbyButtonState(button, false);
            clearNearbyAndSubmit(form);
            return;
        }
        setNearbyButtonState(button, true);
        setUserLocationAndSubmit(form, button);
    }

    function setUserLocationAndSubmit(form, button = null) {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported on this device/browser.');
            setNearbyButtonState(button, false);
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const locationApplied = applyUserLocationToForm(form, position.coords.latitude.toFixed(6), position.coords.longitude.toFixed(6));
            if (!locationApplied) {
                setNearbyButtonState(button, false);
                return;
            }
            form.submit();
        }, function() {
            alert('Unable to fetch your location. Please enable location permission.');
            setNearbyButtonState(button, false);
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
            text.innerText = isHi ? 'विवरण छुपाएं' : 'Hide Address & Facilities';
        } else {
            content.classList.add('hidden');
            content.classList.remove('flex');
            icon.classList.remove('rotate-180');
            text.innerText = isHi ? 'पता और सुविधाएं देखें' : 'View Address & Facilities';
        }
    }

    // Client-side quick filtering logic
    let activeClientFilters = {
        twentyfour: false,
        govt: false,
        component: false
    };

    function toggleClientFilter(type) {
        activeClientFilters[type] = !activeClientFilters[type];

        const btn = document.getElementById('client-filter-' + type);
        if (btn) {
            if (activeClientFilters[type]) {
                btn.classList.add('bg-red-50', 'dark:bg-red-950/40', 'border-red-500', 'text-red-700', 'dark:text-red-300');
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-350');
            } else {
                btn.classList.remove('bg-red-50', 'dark:bg-red-950/40', 'border-red-500', 'text-red-700', 'dark:text-red-300');
                btn.classList.add('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700', 'text-slate-700', 'dark:text-slate-350');
            }
        }

        applyClientFilters();
    }

    function applyClientFilters() {
        const cards = document.querySelectorAll('[data-twentyfour]');
        cards.forEach(card => {
            let show = true;
            if (activeClientFilters.twentyfour && card.getAttribute('data-twentyfour') !== 'true') {
                show = false;
            }
            if (activeClientFilters.govt && card.getAttribute('data-govt') !== 'true') {
                show = false;
            }
            if (activeClientFilters.component && card.getAttribute('data-component') !== 'true') {
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



