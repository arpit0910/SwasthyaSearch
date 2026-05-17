@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics') . ' - SwasthyaSearch')

@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'सत्यापित स्वास्थ्य केंद्र' : 'Verified Healthcare Centers' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
            {{ $locale === 'hi' ? 'शीर्ष अस्पताल और क्लीनिक खोजें' : 'Explore Top Hospitals & Clinics' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'आपातकालीन संपर्क नंबरों और पूर्ण पते के साथ आपके शहर में 100% सत्यापित और विश्वसनीय चिकित्सा सुविधाएं।' : 'Discover accredited hospitals and specialized healthcare clinics near you. Complete with verified emergency contacts and locations.' }}
        </p>
    </div>
</header>

<!-- Filter Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
    <form action="{{ route('hospitals.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative xl:col-span-2">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $locale === 'hi' ? 'अस्पताल का नाम या पता खोजें...' : 'Search hospital name or address...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-50-border focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                />
            </div>

            <!-- Type Filter -->
            <div>
                @php $typeVal = request('type', $filters['type'] ?? 'All'); @endphp
                <select
                    name="type"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All" {{ $typeVal === 'All' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सभी प्रकार' : 'All Facility Types' }}</option>
                    @foreach ($types as $t)
                        <option value="{{ $t }}" {{ $typeVal === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- City Filter -->
            <div>
                @php $cityVal = request('city', $filters['city'] ?? 'All'); @endphp
                <select
                    name="city"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All" {{ $cityVal === 'All' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सभी शहर' : 'All Cities' }}</option>
                    @foreach ($cities as $c)
                        <option value="{{ $c }}" {{ $cityVal === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-100">
            <a
                href="{{ route('hospitals.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>

            <button
                type="submit"
                class="h-12 bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold px-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm flex items-center justify-center space-x-2 transform active:scale-98 uppercase tracking-wider"
            >
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
            </button>
        </div>
    </form>
</section>

<!-- Hospitals Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
    @if (count($hospitals) === 0)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                <i data-lucide="building-2" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">
                {{ $locale === 'hi' ? 'कोई अस्पताल नहीं मिला' : 'No Hospitals Found' }}
            </h3>
            <p class="text-slate-500 text-base mb-8 leading-relaxed">
                {{ $locale === 'hi' ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई अस्पताल या क्लीनिक नहीं मिला। कृपया अपनी खोज मानदंड बदलें।' : 'We could not find any healthcare facilities matching your selected filters. Please try modifying your search criteria.' }}
            </p>
            <a
                href="{{ route('hospitals.index') }}"
                class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'सभी अस्पताल देखें' : 'View All Hospitals' }}</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($hospitals as $hospital)
                @php
                    $h = is_array($hospital) ? (object) $hospital : $hospital;
                    $hNameEn = is_array($h->name) ? $h->name['en'] : ($h->name['en'] ?? $h->name_en);
                    $hNameHi = is_array($h->name) ? $h->name['hi'] : ($h->name['hi'] ?? $h->name_hi);
                    $hName = $locale === 'hi' ? ($hNameHi ?: $hNameEn) : $hNameEn;
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                    <!-- Card Header -->
                    <div class="p-6 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                            <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                                <i data-lucide="building-2" class="w-8 h-8 text-teal-400"></i>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-1.5 mb-1.5">
                                <h3 class="font-extrabold text-lg text-slate-900 truncate group-hover:text-teal-600 transition-colors duration-200">
                                    {{ $hName }}
                                </h3>
                                @if ($h->is_verified)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500 shrink-0"></i>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">
                                    {{ $h->type }}
                                </span>
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full shadow-2xs truncate max-w-[120px]">
                                    {{ $h->city }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col space-y-5 bg-white">
                        <div class="flex items-start space-x-3 text-slate-600 text-xs leading-relaxed bg-slate-50/80 p-4 rounded-2xl border border-slate-100 shadow-2xs">
                            <i data-lucide="map-pin" class="w-4 h-4 text-teal-500 shrink-0 mt-0.5"></i>
                            <span class="flex-1">{{ $h->address }}, {{ $h->city }}</span>
                        </div>

                        <div class="flex-1 flex flex-col justify-end space-y-3 pt-2">
                            <div class="flex items-center justify-between text-xs p-3.5 bg-teal-50/50 rounded-2xl border border-teal-100">
                                <div class="flex items-center space-x-2 text-teal-900 font-bold">
                                    <i data-lucide="phone-call" class="w-4 h-4 text-teal-600 animate-pulse"></i>
                                    <span>{{ $locale === 'hi' ? 'आपातकालीन फ़ोन:' : 'Emergency Phone:' }}</span>
                                </div>
                                <span class="text-slate-900 font-extrabold tracking-wide select-all">
                                    {{ $h->emergency_phone }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-6 pt-0 bg-white">
                        <a
                            href="tel:{{ $h->emergency_phone }}"
                            class="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98"
                        >
                            <i data-lucide="phone-call" class="w-4 h-4 text-white"></i>
                            <span>{{ $locale === 'hi' ? 'तुरंत कॉल करें' : 'Call Emergency Now' }}</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
