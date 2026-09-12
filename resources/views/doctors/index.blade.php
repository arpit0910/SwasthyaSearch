@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'डॉक्टर निर्देशिका' : 'Doctors Directory') . ' - Arogio')

@php
$seoCityInput = request('city');
$seoCity = $activeCity ?? config('healthcare.active_city', 'Jaipur');
$hasCity = true;
$pageTitle = "Doctors Directory | Find Specialists & Clinics | Arogio";
$pageDescription = $hasCity
? "Find doctors in {$seoCity} by specialty, department, clinic, or symptoms. Call providers directly and confirm timings before visiting."
: 'Search doctors by specialty, department, or symptoms. Find contact details, clinic information, and healthcare providers near you.';
$hasActiveMobileFilters = !empty(array_filter((array) request('department', [])))
    || !empty(array_filter((array) request('experience', [])))
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
$selectedDepartmentIds = collect((array) request('department', []))
    ->filter(fn ($value) => filled($value) && $value !== 'All')
    ->map(fn ($value) => (string) $value)
    ->values();
$selectedDepartmentNames = collect($departments ?? [])
    ->filter(function ($dept) use ($selectedDepartmentIds) {
        $deptId = (string) (is_array($dept) ? ($dept['id'] ?? '') : ($dept->id ?? ''));
        return $selectedDepartmentIds->contains($deptId);
    })
    ->map(function ($dept) use ($locale) {
        $deptNameEn = is_array($dept) ? ($dept['name']['en'] ?? '') : ($dept->name_en ?? '');
        $deptNameHi = is_array($dept) ? ($dept['name']['hi'] ?? '') : ($dept->name_hi ?? '');
        return $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;
    })
    ->filter()
    ->values()
    ->all();
$searchTerm = trim((string) request('search', ''));
$departmentPhrase = \App\Support\Seo::toPhrase(array_slice($selectedDepartmentNames, 0, 3));
$pageTitle = filled($searchTerm)
    ? "Doctors for {$searchTerm} in {$selectedCity} | Arogio"
    : (!empty($selectedDepartmentNames)
        ? "{$departmentPhrase} Doctors in {$selectedCity} | Arogio"
        : ($locale === 'hi' ? "{$selectedCity} में डॉक्टर - सत्यापित विशेषज्ञ निर्देशिका | Arogio" : "Doctors Near Me in {$selectedCity} - Verified Specialists Directory | Arogio"));
$pageDescription = filled($searchTerm)
    ? "Find doctors in {$selectedCity} related to {$searchTerm}. Check specialties, clinic details, and contact information before visiting."
    : (!empty($selectedDepartmentNames)
        ? "Browse verified {$departmentPhrase} doctors in {$selectedCity}. Compare experience, hospitals, and direct contact information."
        : "Find verified doctors near you in {$selectedCity} by specialty, symptoms, department, and experience. Contact hospitals and clinics directly through Arogio.");
@endphp
@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('meta_keywords', \App\Support\Seo::keywords(['Doctor near me', 'Doctors near me', 'Doctors in ' . $selectedCity, 'Specialist doctors in ' . $selectedCity, 'Clinics near me', 'Healthcare directory', $selectedCity]))
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $pageTitle,
    'description' => \App\Support\Seo::cleanText($pageDescription, 160),
    'url' => route('doctors.index'),
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => collect($doctors instanceof \Illuminate\Pagination\AbstractPaginator ? $doctors->items() : $doctors)
            ->take(10)
            ->values()
            ->map(fn ($doctor, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Physician',
                    'name' => trim('Dr. ' . ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '')),
                    'medicalSpecialty' => $doctor['department']['name']['en'] ?? null,
                    'areaServed' => $selectedCity,
                ],
            ])
            ->all(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-cyan-700 via-teal-700 to-sky-700 dark:from-slate-900 dark:via-cyan-900 dark:to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-cyan-800 dark:border-slate-700 ring-1 ring-black/10 dark:ring-white/15 shadow-xl dark:shadow-black/50 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)] dark:opacity-0"></div>
    <!-- Glowing background blobs -->
    <div class="glow-blob w-[300px] h-[300px] bg-teal-500/10 dark:opacity-0 top-0 left-0 absolute rounded-full blur-3xl"></div>
    <div class="glow-blob w-[400px] h-[400px] bg-cyan-500/10 dark:opacity-0 bottom-0 right-0 absolute rounded-full blur-3xl"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'सत्यापित विशेषज्ञ' : 'Verified Medical Experts' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctors' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'आपके स्वास्थ्य के लिए 100% सत्यापित, अनुभवी और शीर्ष चिकित्सा विशेषज्ञ। सीधे संपर्क करें, कोई छिपा शुल्क नहीं।' : 'Explore our comprehensive directory of 100% verified, world-class healthcare professionals. Connect directly with zero commission.' }}
        </p>
    </div>
</header>

<section class="lg:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 relative z-20 w-full mb-6">
    <form action="{{ route('doctors.index') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700/70 p-4 sm:p-5 backdrop-blur-xl">
        @csrf
        @foreach ((array) request('department', []) as $deptVal)
        <input type="hidden" name="department[]" value="{{ $deptVal }}">
        @endforeach
        @foreach ((array) request('experience', []) as $expVal)
        <input type="hidden" name="experience[]" value="{{ $expVal }}">
        @endforeach
        <input type="hidden" name="user_lat" value="{{ request('user_lat', $filters['user_lat'] ?? '') }}">
        <input type="hidden" name="user_lng" value="{{ request('user_lng', $filters['user_lng'] ?? '') }}">
        <div class="relative">
            <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400"></i>
            <input type="text" name="search"
                placeholder="{{ $locale === 'hi' ? 'डॉक्टर, विभाग, लक्षण खोजें...' : 'Search doctors, departments, symptoms...' }}"
                value="{{ request('search', $filters['search'] ?? '') }}"
                class="h-12 w-full pl-10 pr-12 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            <button type="button" data-open-mobile-filters onclick="openMobileFilters()" aria-label="{{ $locale === 'hi' ? 'फ़िल्टर खोलें' : 'Open filters' }}" class="lg:hidden absolute right-1.5 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg border flex items-center justify-center transition-all duration-200 {{ $hasActiveMobileFilters ? 'border-teal-600 bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'border-teal-200 bg-white text-teal-700 hover:bg-teal-50' }}" title="{{ $locale === 'hi' ? 'फ़िल्टर' : 'Filters' }}">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </div>
        <button type="button" onclick="toggleNearby(this)" data-nearby-toggle data-nearby-theme="teal" class="mt-3 w-full h-11 px-4 rounded-xl border font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs {{ $isNearbyActive ? 'border-teal-600 dark:border-teal-500 bg-teal-600 dark:bg-teal-600 text-white' : 'border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-950/35 hover:bg-teal-100 dark:hover:bg-teal-900/40 text-teal-700 dark:text-teal-300' }}">
            <i data-lucide="locate-fixed" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
        </button>
    </form>
</section>
<!-- Filter Bar & Mobile Toggle -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 relative z-20 w-full mb-2 lg:mb-8">
    <!-- Mobile Filter Button (visible on screens smaller than lg) -->

    <!-- Filter Sidebar (Mobile) - Hidden by default -->
    <div id="mobile-filter-sidebar" class="fixed inset-0 z-[120] hidden lg:hidden" aria-hidden="true">
        <!-- Backdrop -->
        <div id="mobile-filter-backdrop" onclick="closeMobileFilters()" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-out"></div>
        <!-- Sidebar -->
        <div id="mobile-filter-drawer" class="absolute top-0 right-0 h-full w-full max-w-sm bg-white dark:bg-slate-900 shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300 ease-out">
            <!-- Header -->
            <div class="sticky top-0 p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'फ़िल्टर' : 'Filters' }}</h2>
                <button type="button" id="mobile-filter-close" onclick="closeMobileFilters()" aria-label="{{ $locale === 'hi' ? 'फ़िल्टर बंद करें' : 'Close filters' }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                </button>
            </div>

            <!-- Filter Form (Mobile) -->
            <form action="{{ route('doctors.index') }}" method="POST" class="p-3 sm:p-4 space-y-4" id="mobile-filter-form">
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

                <!-- Department Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'विभाग' : 'Department' }}</label>
                    @php
                        $selectedDepts = is_array(request('department')) ? request('department') : (request('department') && request('department') !== 'All' ? [request('department')] : []);
                    @endphp
                    <select
                        name="department[]"
                        multiple
                        data-placeholder="{{ $locale === 'hi' ? 'विभाग चुनें' : 'Select departments' }}"
                        class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($departments as $dept)
                        @php
                        $deptId = is_array($dept) ? $dept['id'] : $dept->id;
                        $deptNameEn = is_array($dept) ? $dept['name']['en'] : ($dept->name['en'] ?? $dept->name_en);
                        $deptNameHi = is_array($dept) ? $dept['name']['hi'] : ($dept->name['hi'] ?? $dept->name_hi);
                        @endphp
                        <option value="{{ $deptId }}" {{ in_array($deptId, $selectedDepts) ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Experience Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'अनुभव' : 'Experience' }}</label>
                    @php 
                        $selectedExps = is_array(request('experience')) ? request('experience') : (request('experience') && request('experience') !== 'All' ? [request('experience')] : []);
                    @endphp
                    <select
                        name="experience[]"
                        multiple
                        data-placeholder="{{ $locale === 'hi' ? 'अनुभव चुनें' : 'Select experience' }}"
                        class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        <option value="5" {{ in_array('5', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '5+ वर्ष' : '5+ Years' }}</option>
                        <option value="10" {{ in_array('10', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '10+ वर्ष' : '10+ Years' }}</option>
                        <option value="15" {{ in_array('15', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '15+ वर्ष' : '15+ Years' }}</option>
                        <option value="20" {{ in_array('20', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '20+ वर्ष' : '20+ Years' }}</option>
                    </select>
                </div>

                <!-- City Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 uppercase tracking-wider">{{ $locale === 'hi' ? 'शहर' : 'City' }}</label>
                    <select name="city[]" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                        @foreach ($cityOptions as $cityOption)
                        <option value="{{ $cityOption }}" {{ $selectedCity === $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="space-y-2.5 pt-3 border-t border-slate-200">
                    <button type="submit" class="w-full h-11 px-4 rounded-xl border border-indigo-300 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
                    </button>
                    <a href="{{ route('doctors.index') }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset' }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop Filter Form (lg and above) -->
    <form action="{{ route('doctors.index') }}" method="POST" class="hidden lg:block bg-white rounded-2xl shadow-xl border border-slate-200/80 ring-1 ring-slate-200/70 p-5 sm:p-6 backdrop-blur-xl" id="filter-form">
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
                    placeholder="{{ $locale === 'hi' ? 'डॉक्टर, विभाग, लक्षण खोजें...' : 'Search doctors, departments, symptoms...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            </div>

            <!-- Department Filter - Multiselect -->
            <div>
                @php
                    $selectedDepts = is_array(request('department')) ? request('department') : (request('department') && request('department') !== 'All' ? [request('department')] : []);
                @endphp
                <select
                    name="department[]"
                    multiple
                    data-placeholder="{{ $locale === 'hi' ? 'विभाग चुनें' : 'Select departments' }}"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($departments as $dept)
                    @php
                    $deptId = is_array($dept) ? $dept['id'] : $dept->id;
                    $deptNameEn = is_array($dept) ? $dept['name']['en'] : ($dept->name['en'] ?? $dept->name_en);
                    $deptNameHi = is_array($dept) ? $dept['name']['hi'] : ($dept->name['hi'] ?? $dept->name_hi);
                    @endphp
                    <option value="{{ $deptId }}" {{ in_array($deptId, $selectedDepts) ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Experience Filter - Multiselect -->
            <div>
                @php 
                    $selectedExps = is_array(request('experience')) ? request('experience') : (request('experience') && request('experience') !== 'All' ? [request('experience')] : []);
                @endphp
                <select
                    name="experience[]"
                    multiple
                    data-placeholder="{{ $locale === 'hi' ? 'अनुभव चुनें' : 'Select experience' }}"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    <option value="5" {{ in_array('5', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '5+ वर्ष' : '5+ Years' }}</option>
                    <option value="10" {{ in_array('10', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '10+ वर्ष' : '10+ Years' }}</option>
                    <option value="15" {{ in_array('15', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '15+ वर्ष' : '15+ Years' }}</option>
                    <option value="20" {{ in_array('20', $selectedExps) ? 'selected' : '' }}>{{ $locale === 'hi' ? '20+ वर्ष' : '20+ Years' }}</option>
                </select>
            </div>
            <!-- City Filter -->
            <div>
                <select name="city[]" class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700">
                    @foreach ($cityOptions as $cityOption)
                    <option value="{{ $cityOption }}" {{ $selectedCity === $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-6 pt-6 border-t border-slate-100">
            <button type="button" onclick="toggleNearby(this)" data-nearby-toggle data-nearby-theme="teal"
                class="h-12 px-5 rounded-xl border font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs {{ $isNearbyActive ? 'border-teal-600 bg-teal-600 text-white' : 'border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700' }}">
                <i data-lucide="locate-fixed" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'मेरे नजदीक दिखाएँ' : 'Show Nearby' }}</span>
            </button>
            <button type="submit"
                class="h-12 px-5 rounded-xl border border-indigo-300 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
            </button>
            <a
                href="{{ route('doctors.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>
        </div>

    </form>
</div>
<!-- Doctors Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full mt-0 lg:mt-4 pb-20">
    @if (count($doctors) === 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
        <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
            <i data-lucide="search" class="w-10 h-10"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">
            {{ $locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Doctors Found' }}
        </h3>
        <p class="text-slate-500 text-base mb-8 leading-relaxed">
            {{ $locale === 'hi' ? 'कोई परिणाम नहीं मिला। कृपया विभाग, क्षेत्र या खोज शब्द बदलकर देखें।' : 'No results found. Try another department, locality, or keyword.' }}
        </p>
        <a
            href="{{ route('doctors.index') }}"
            class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'सभी डॉक्टर देखें' : 'View All Doctors' }}</span>
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($doctors as $doctor)
        @php
        $doc = is_array($doctor) ? (object) $doctor : $doctor;
        $fullName = "Dr. {$doc->first_name} {$doc->last_name}";
        $dept = is_array($doc->department) ? (object) $doc->department : $doc->department;
        $deptNameData = $dept->name ?? null;
        $deptNameEn = is_array($deptNameData) ? ($deptNameData['en'] ?? '') : ($deptNameData['en'] ?? ($dept->name_en ?? ''));
        $deptNameHi = is_array($deptNameData) ? ($deptNameData['hi'] ?? '') : ($deptNameData['hi'] ?? ($dept->name_hi ?? ''));
        $deptName = $locale === 'hi'
            ? ($deptNameHi ?: ($deptNameEn ?: 'विभाग उपलब्ध नहीं'))
            : ($deptNameEn ?: 'Department not available');
        $aboutEn = is_array($doc->about) ? $doc->about['en'] : ($doc->about['en'] ?? $doc->about_en);
        $aboutHi = is_array($doc->about) ? $doc->about['hi'] : ($doc->about['hi'] ?? $doc->about_hi);
        $about = $locale === 'hi' ? ($aboutHi ?: $aboutEn) : $aboutEn;

        $hasAyushman = false;
        $hasCashless = false;
        if (!empty($doc->hospitals)) {
        foreach ($doc->hospitals as $hospTemp) {
        $hT = is_array($hospTemp) ? (object) $hospTemp : $hospTemp;
        if (!empty($hT->accepts_ayushman)) $hasAyushman = true;
        if (!empty($hT->is_cashless)) $hasCashless = true;
        }
        }

        $doctorPhone = !empty($doc->phone_1) ? $doc->phone_1 : (!empty($doc->phone_2) ? $doc->phone_2 : (!empty($doc->phone) ? $doc->phone : ''));
        $hospitalPhone = '';
        if (!empty($doc->hospitals) && count($doc->hospitals) > 0) {
        foreach ($doc->hospitals as $hospForPhone) {
        $hfp = is_array($hospForPhone) ? (object) $hospForPhone : $hospForPhone;
        $candidate = !empty($hfp->phone_1) ? $hfp->phone_1 : (!empty($hfp->phone_2) ? $hfp->phone_2 : (!empty($hfp->phone) ? $hfp->phone : ''));
        if (!empty($candidate)) {
        $hospitalPhone = $candidate;
        break;
        }
        }
        }
        $hasDoctorPhone = !empty($doctorPhone);
        $hasHospitalPhone = !empty($hospitalPhone);

        $whatsappLink = '';
        $targetPhoneForWA = $doctorPhone ?: $hospitalPhone;
        if (!empty($targetPhoneForWA)) {
        $cleanPhone = preg_replace('/\D/', '', $targetPhoneForWA);
        if (strlen($cleanPhone) === 10) {
        $cleanPhone = '91' . $cleanPhone;
        } elseif (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '91' . substr($cleanPhone, 1);
        }
        $whatsappMessage = rawurlencode("Hello Dr. {$doc->first_name} {$doc->last_name}, I found your profile on Arogio and would like to inquire about consultation timings and availability.");
        $whatsappLink = "https://wa.me/{$cleanPhone}?text={$whatsappMessage}";
        }

        $firstHospName = '';
        if (!empty($doc->hospitals) && count($doc->hospitals) > 0) {
        $h0 = is_array($doc->hospitals[0]) ? (object) $doc->hospitals[0] : $doc->hospitals[0];
        $h0NameData = $h0->name ?? null;
        $h0NameEn = is_array($h0NameData) ? ($h0NameData['en'] ?? '') : ($h0NameData['en'] ?? ($h0->name_en ?? ''));
        $h0NameHi = is_array($h0NameData) ? ($h0NameData['hi'] ?? '') : ($h0NameData['hi'] ?? ($h0->name_hi ?? ''));
        $firstHospName = $locale === 'hi' ? ($h0NameHi ?: $h0NameEn) : $h0NameEn;
        }
        @endphp
        <div class="glass-card rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1 relative" data-ayushman="{{ $hasAyushman ? 'true' : 'false' }}" data-cashless="{{ $hasCashless ? 'true' : 'false' }}" data-verified="{{ $doc->is_verified ? 'true' : 'false' }}">

            <!-- Card Header -->
            <div class="p-6 pb-4 bg-gradient-to-br from-slate-50/50 via-white/50 to-slate-50/50 dark:from-slate-800/30 dark:via-transparent dark:to-slate-800/30 border-b border-slate-100 dark:border-slate-800/60 flex items-start space-x-4">
                <div class="w-10 h-10 bg-gradient-to-tr from-teal-500 to-cyan-600 rounded-xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center text-white font-extrabold text-xs tracking-wide">
                        {{ substr($doc->first_name, 0, 1) }}{{ substr($doc->last_name, 0, 1) }}
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h3 class="font-extrabold text-lg text-slate-900 dark:text-white line-clamp-3 leading-snug group-hover:text-teal-600 transition-colors duration-200 min-w-0">
                            {{ $fullName }}
                        </h3>
                        <div class="shrink-0 flex items-center gap-1.5 mt-0.5 relative">
                            @if ($doc->is_verified)
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500"></i>
                            @endif
                            <button
                                type="button"
                                onclick="toggleListingActionMenu(event, 'doctor-actions-{{ $doc->id }}')"
                                aria-label="{{ $locale === 'hi' ? 'और विकल्प' : 'More actions' }}"
                                aria-expanded="false"
                                class="listing-action-trigger w-8 h-8 inline-flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white/95 dark:bg-slate-900/90 hover:bg-slate-100 dark:hover:bg-slate-800 shadow-sm transition-all duration-200">
                                <i data-lucide="ellipsis" class="w-4 h-4"></i>
                            </button>
                            <div id="doctor-actions-{{ $doc->id }}" class="listing-action-menu hidden absolute right-0 top-10 z-20 w-52 rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white/95 dark:bg-slate-900/95 shadow-xl p-2 backdrop-blur">
                                <button
                                    type="button"
                                    onclick="submitUsefulVoteAndClose('doctor', {{ $doc->id }}, @js($fullName), 'doctor-actions-{{ $doc->id }}')"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/30">
                                    <i data-lucide="thumbs-up" class="w-4 h-4 text-emerald-600 dark:text-emerald-300"></i>
                                    <span>{{ $locale === 'hi' ? 'उपयोगी चिह्नित करें' : 'Mark useful' }}</span>
                                </button>
                                <button
                                    type="button"
                                    onclick="openListingReportFromMenu('doctor', {{ $doc->id }}, @js($fullName), 'doctor-actions-{{ $doc->id }}')"
                                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-rose-950/30">
                                    <i data-lucide="triangle-alert" class="w-4 h-4 text-rose-600 dark:text-rose-300"></i>
                                    <span>{{ $locale === 'hi' ? 'गलत जानकारी रिपोर्ट करें' : 'Report incorrect details' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/40 border border-teal-100/80 dark:border-teal-900/50 px-2 py-0.5 rounded-xl inline-block mb-2 shadow-2xs line-clamp-2 max-w-full">
                        {{ $deptName }}
                    </p>
                    @if ($doc->distance_km !== null)
                    <p class="text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-xl inline-block mb-2 shadow-2xs">
                        {{ $doc->distance_km }} km {{ $locale === 'hi' ? 'दूर' : 'away' }}
                    </p>
                    @endif
                    <div class="flex flex-wrap gap-1 text-slate-500 dark:text-slate-400 text-xs">
                        {{ is_array($doc->education_degrees) ? implode(', ', $doc->education_degrees) : $doc->education_degrees }}
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 flex-1 flex flex-col space-y-4">
                <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                    @if(($doc->experience_years ?? 0) > 0)
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                        <i data-lucide="award" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                        <div class="truncate">
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase">{{ $locale === 'hi' ? 'अनुभव' : 'Experience' }}</span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $doc->experience_years }} {{ $locale === 'hi' ? 'वर्ष' : 'Years' }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 flex items-center space-x-2 shadow-2xs">
                        <i data-lucide="file-text" class="w-4 h-4 text-teal-500 shrink-0"></i>
                        <div class="truncate">
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase">{{ $locale === 'hi' ? 'परामर्श शुल्क' : 'Fee' }}</span>
                            <span class="text-slate-900 dark:text-white font-bold">₹{{ $doc->consultation_fee ?: 500 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mobile Accordion Toggle Button -->
                <button type="button" onclick="toggleMobileAccordion('doc-{{ $doc->id }}')" class="md:hidden w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 transition-all duration-200">
                    <span class="flex items-center space-x-2">
                        <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                        <span id="doc-{{ $doc->id }}-text">{{ $locale === 'hi' ? 'अतिरिक्त विवरण देखें' : 'View Additional Details' }}</span>
                    </span>
                    <i data-lucide="chevron-down" id="doc-{{ $doc->id }}-icon" class="w-4 h-4 text-slate-500 transition-transform duration-300"></i>
                </button>

                <!-- Collapsible Content -->
                <div id="doc-{{ $doc->id }}-content" class="hidden md:flex flex-col space-y-4 flex-1">
                    @php
                    $isPlaceholderReg = !empty($doc->registration_number) && (
                    str_starts_with($doc->registration_number, 'REG-') ||
                    str_starts_with($doc->registration_number, 'RAJ-MC-') ||
                    str_starts_with($doc->registration_number, 'MMC-') ||
                    str_starts_with($doc->registration_number, 'DMC-') ||
                    str_starts_with($doc->registration_number, 'JOD-') ||
                    str_starts_with($doc->registration_number, 'KOT-')
                    );
                    @endphp
                    @if (!empty($doc->registration_number) && !$isPlaceholderReg)
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 px-1 pt-1 border-t border-slate-100 dark:border-slate-800">
                        <span>{{ $locale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:' }}</span>
                        <span class="font-mono font-semibold text-slate-700 dark:text-slate-300">{{ $doc->registration_number }} {{ !empty($doc->medical_council) ? "({$doc->medical_council})" : '' }}</span>
                    </div>
                    @endif

                    @if (!empty($doc->languages_spoken) && count($doc->languages_spoken) > 0)
                    <div class="flex items-center space-x-2 text-xs text-slate-650 dark:text-slate-350 px-1">
                        <i data-lucide="languages" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
                        <span class="text-slate-400 dark:text-slate-500 text-[11px]">{{ $locale === 'hi' ? 'भाषाएँ:' : 'Languages:' }}</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ implode(', ', $doc->languages_spoken) }}</span>
                    </div>
                    @endif

                    <div class="text-slate-655 dark:text-slate-300 text-xs leading-relaxed flex-1 space-y-2">
                        <p class="line-clamp-3">{{ $about }}</p>
                        @if (!empty($doc->specialization_summary))
                        <p class="text-[11px] text-slate-500 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 dark:bg-slate-800/40 rounded-r-lg italic">
                            {{ $doc->specialization_summary }}
                        </p>
                        @endif
                    </div>

                    @if (!empty($doc->awards_recognitions) && count($doc->awards_recognitions) > 0)
                    <div class="space-y-1 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1">
                            <i data-lucide="trophy" class="w-3 h-3 text-amber-500"></i>
                            <span>{{ $locale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions' }}</span>
                        </span>
                        <div class="text-[11px] text-slate-600 dark:text-slate-400 pl-4 list-disc space-y-0.5">
                            @foreach ($doc->awards_recognitions as $award)
                            <div class="truncate">• {{ $award }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if (!empty($doc->hospitals) && count($doc->hospitals) > 0)
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500"></i>
                            <span>{{ $locale === 'hi' ? 'अभ्यास स्थल एवं पता' : 'Practicing At & Location' }}</span>
                        </h4>
                        @foreach ($doc->hospitals as $hosp)
                        @php
                        $h = is_array($hosp) ? (object) $hosp : $hosp;
                        $hNameData = $h->name ?? null;
                        $hNameEn = is_array($hNameData) ? ($hNameData['en'] ?? '') : ($hNameData['en'] ?? ($h->name_en ?? ''));
                        $hNameHi = is_array($hNameData) ? ($hNameData['hi'] ?? '') : ($hNameData['hi'] ?? ($h->name_hi ?? ''));
                        $hName = $locale === 'hi'
                            ? ($hNameHi ?: ($hNameEn ?: 'अस्पताल उपलब्ध नहीं'))
                            : ($hNameEn ?: 'Hospital not available');
                        $pivot = is_array($h->pivot) ? (object) $h->pivot : $h->pivot;
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100/80 dark:border-slate-700/80 space-y-2 text-xs hover:border-slate-200 dark:hover:border-slate-600 transition-colors shadow-2xs">
                            <div class="font-bold text-slate-900 dark:text-white flex justify-between items-start gap-2">
                                <div>
                                    <span class="block text-sm text-indigo-950 dark:text-indigo-200">{{ $hName }}</span>
                                    @if (!empty($h->type))
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 dark:bg-teal-950/40 border border-teal-100 dark:border-teal-900/60 px-2 py-0.5 rounded-md inline-block mt-0.5">{{ $h->type }}</span>
                                    @endif
                                </div>
                                <span class="text-teal-700 dark:text-teal-400 shrink-0 font-extrabold bg-white dark:bg-slate-850 px-2.5 py-1 rounded-xl border border-teal-100 dark:border-teal-800 shadow-2xs">
                                    ₹{{ $pivot->consultation_fee ?? ($doc->consultation_fee ?: 500) }}
                                </span>
                            </div>
                            @if ($h->distance_km !== null)
                            <div class="text-[11px] text-emerald-700 dark:text-emerald-300 font-bold">
                                {{ $h->distance_km }} km {{ $locale === 'hi' ? 'दूर' : 'away' }}
                            </div>
                            @endif

                            <p class="text-slate-600 dark:text-slate-350 text-[11px] leading-normal pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $locale === 'hi' ? 'पता:' : 'Address:' }}</span> {{ !empty($h->address_line1) ? $h->address_line1 . ', ' . (!empty($h->address_line2) ? $h->address_line2 . ', ' : '') . $h->city . ', ' . $h->state . (!empty($h->pincode) ? ' - ' . $h->pincode : '') : ($h->address ?? '') }}
                            </p>

                            <div class="pt-1 flex flex-wrap gap-1">
                                @if(!empty($h->accepts_ayushman))
                                <span class="min-w-0 max-w-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-700/70 px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="shield-check" class="w-3 h-3 text-emerald-600"></i>
                                    <span>{{ $locale === 'hi' ? 'आयुष्मान' : 'Ayushman' }}</span>
                                </span>
                                @endif
                                @if(!empty($h->accepts_janaadhaar))
                                <span class="min-w-0 max-w-full bg-sky-50 dark:bg-sky-900/30 text-sky-800 dark:text-sky-200 border border-sky-200 dark:border-sky-700/70 px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="award" class="w-3 h-3 text-blue-600"></i>
                                    <span>{{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}</span>
                                </span>
                                @endif
                                @if(!empty($h->accepts_cghs))
                                <span class="min-w-0 max-w-full bg-violet-50 dark:bg-violet-900/30 text-violet-800 dark:text-violet-200 border border-violet-200 dark:border-violet-700/70 px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="check-badge" class="w-3 h-3 text-purple-600"></i>
                                    <span>{{ $locale === 'hi' ? 'सीजीएचएस' : 'CGHS' }}</span>
                                </span>
                                @endif
                                @if(!empty($h->accepts_esic))
                                <span class="min-w-0 max-w-full bg-amber-50 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 border border-amber-200 dark:border-amber-700/70 px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="briefcase-medical" class="w-3 h-3 text-amber-600"></i>
                                    <span>{{ $locale === 'hi' ? 'ईएसआईसी' : 'ESIC' }}</span>
                                </span>
                                @endif
                                @if(!empty($h->is_cashless))
                                <span class="min-w-0 max-w-full bg-teal-50 dark:bg-teal-900/30 text-teal-800 dark:text-teal-200 border border-teal-200 dark:border-teal-700/70 px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="credit-card" class="w-3 h-3 text-teal-600"></i>
                                    <span>{{ $locale === 'hi' ? 'कैशलेस' : 'Cashless' }}</span>
                                </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 text-[11px] pt-1 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="flex items-center space-x-1 pr-1 truncate">
                                    <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                    <span class="truncate">{{ $pivot->days_of_week ?? 'Mon - Sat' }}</span>
                                </span>
                                <span class="font-semibold text-slate-600 dark:text-slate-350 shrink-0">
                                    {{ $pivot->start_time ?? '10:00 AM' }} - {{ $pivot->end_time ?? '05:00 PM' }}
                                </span>
                            </div>

                            <div class="pt-2 mt-1 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between gap-2">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">{{ $locale === 'hi' ? 'दिशा-निर्देश उपलब्ध' : 'Directions available' }}</span>
                                <a
                                    href="{{ $h->map_directions_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center space-x-1.5 text-xs text-cyan-600 dark:text-indigo-400 hover:text-cyan-700 dark:hover:text-indigo-300 font-bold bg-cyan-50 dark:bg-indigo-950/40 hover:bg-indigo-100/80 dark:hover:bg-indigo-900/60 px-3 py-1.5 rounded-xl border border-cyan-100 dark:border-indigo-900 transition-all shadow-2xs">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5 text-indigo-500"></i>
                                    <span>{{ $locale === 'hi' ? 'नक्शा व दिशा-निर्देश' : 'Get Directions' }}</span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Footer -->
            <div class="p-6 pt-0 flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                @if($hasDoctorPhone && $hasHospitalPhone)
                <a href="tel:{{ $doctorPhone }}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                    <span>Call Doctor</span>
                </a>
                @if(!empty($whatsappLink))
                <a href="{{ $whatsappLink }}" target="_blank" class="w-full sm:flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="w-4 h-4 text-emerald-100 fill-current">
                        <path d="M12 2A10 10 0 0 0 3.4 17.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.6c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.6.1l-.4.5c-.1.2-.3.2-.5.1-1.2-.6-2.2-1.6-2.8-2.8-.1-.2 0-.4.1-.5l.4-.4c.1-.1.2-.3.1-.5l-.7-1.6c-.1-.2-.3-.3-.5-.3h-.4c-.2 0-.4.1-.6.3-.5.5-.8 1.1-.8 1.8 0 .2.1.9.9 2 .9 1.2 2.2 2.3 3.8 2.9 1.6.6 1.6.4 1.9.4.3 0 1-.4 1.2-.8.1-.4.1-.8 0-.9Z" />
                    </svg>
                    <span>WhatsApp</span>
                </a>
                @endif
                @elseif($hasDoctorPhone)
                <a href="tel:{{ $doctorPhone }}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                    <span>Call Doctor</span>
                </a>
                @if(!empty($whatsappLink))
                <a href="{{ $whatsappLink }}" target="_blank" class="w-full sm:flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="w-4 h-4 text-emerald-100 fill-current">
                        <path d="M12 2A10 10 0 0 0 3.4 17.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.6c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.6.1l-.4.5c-.1.2-.3.2-.5.1-1.2-.6-2.2-1.6-2.8-2.8-.1-.2 0-.4.1-.5l.4-.4c.1-.1.2-.3.1-.5l-.7-1.6c-.1-.2-.3-.3-.5-.3h-.4c-.2 0-.4.1-.6.3-.5.5-.8 1.1-.8 1.8 0 .2.1.9.9 2 .9 1.2 2.2 2.3 3.8 2.9 1.6.6 1.6.4 1.9.4.3 0 1-.4 1.2-.8.1-.4.1-.8 0-.9Z" />
                    </svg>
                    <span>WhatsApp</span>
                </a>
                @endif
                @elseif($hasHospitalPhone)
                <a href="tel:{{ $hospitalPhone }}" class="w-full sm:flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="phone-call" class="w-4 h-4 text-indigo-100"></i>
                    <span>Call Hospital</span>
                </a>
                @if(!empty($whatsappLink))
                <a href="{{ $whatsappLink }}" target="_blank" class="w-full sm:flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="w-4 h-4 text-emerald-100 fill-current">
                        <path d="M12 2A10 10 0 0 0 3.4 17.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1 1 12 20Zm4.5-5.6c-.2-.1-1.3-.6-1.5-.7-.2-.1-.4-.1-.6.1l-.4.5c-.1.2-.3.2-.5.1-1.2-.6-2.2-1.6-2.8-2.8-.1-.2 0-.4.1-.5l.4-.4c.1-.1.2-.3.1-.5l-.7-1.6c-.1-.2-.3-.3-.5-.3h-.4c-.2 0-.4.1-.6.3-.5.5-.8 1.1-.8 1.8 0 .2.1.9.9 2 .9 1.2 2.2 2.3 3.8 2.9 1.6.6 1.6.4 1.9.4.3 0 1-.4 1.2-.8.1-.4.1-.8 0-.9Z" />
                    </svg>
                    <span>WhatsApp</span>
                </a>
                @endif
                @else
                <button
                    type="button"
                    onclick="alert('{{ $locale === 'hi' ? 'सीधे डॉक्टर या क्लिनिक का नंबर सूचीबद्ध नहीं है। कृपया ऊपर सूचीबद्ध अस्पताल संपर्क नंबरों का उपयोग करें।' : 'Direct doctor or clinic number is unlisted. Please use the hospital contact numbers listed above.' }}')"
                    class="w-full sm:flex-1 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold py-3 px-4 rounded-xl border border-slate-200 dark:border-slate-700 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 cursor-not-allowed">
                    <i data-lucide="phone-off" class="w-4 h-4 text-slate-400"></i>
                    <span>{{ $locale === 'hi' ? 'नंबर उपलब्ध नहीं' : 'Doctor Tel Unlisted' }}</span>
                </button>
                @endif
                @if (!empty($doc->website))
                <a
                    href="{{ str_starts_with($doc->website, 'http') ? $doc->website : 'https://' . $doc->website }}"
                    target="_blank"
                    class="w-full sm:flex-1 bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 dark:hover:bg-slate-750 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                    <i data-lucide="globe" class="w-4 h-4 text-teal-400"></i>
                    <span>{{ $locale === 'hi' ? 'वेबसाइट देखें' : 'View Website' }}</span>
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $doctors->links('pagination::tailwind') }}
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
            `<button type="button" data-remove-value="${opt.value}" class="inline-flex items-center gap-1 rounded-full border border-teal-200 bg-teal-50 px-2.5 py-1 text-[11px] font-semibold text-teal-700 hover:bg-teal-100 dark:border-teal-700/70 dark:bg-teal-900/30 dark:text-teal-200 dark:hover:bg-teal-900/45">${opt.textContent.trim()} <span class="text-teal-900 dark:text-teal-100">x</span></button>`
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

    function applyUserLocationToForm(form, lat, lng) {
        const latInput = form?.querySelector('input[name="user_lat"]')
            || document.getElementById('user_lat')
            || document.getElementById('user_lat_mobile');
        const lngInput = form?.querySelector('input[name="user_lng"]')
            || document.getElementById('user_lng')
            || document.getElementById('user_lng_mobile');

        if (!latInput || !lngInput) {
            alert('Nearby location fields are missing. Please refresh and try again.');
            return false;
        }

        latInput.value = lat;
        lngInput.value = lng;
        return true;
    }

    function setNearbyButtonState(button, isActive) {
        if (!button) return;
        const activeClasses = ['border-teal-600', 'dark:border-teal-500', 'bg-teal-600', 'dark:bg-teal-600', 'text-white'];
        const inactiveClasses = ['border-teal-200', 'dark:border-teal-800', 'bg-teal-50', 'dark:bg-teal-950/35', 'hover:bg-teal-100', 'dark:hover:bg-teal-900/40', 'text-teal-700', 'dark:text-teal-300'];
        button.classList.remove(...activeClasses, ...inactiveClasses);
        button.classList.add(...(isActive ? activeClasses : inactiveClasses));
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
            const lat = position.coords.latitude.toFixed(6);
            const lng = position.coords.longitude.toFixed(6);
            const locationApplied = applyUserLocationToForm(form, lat, lng);
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

