@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'डॉक्टर निर्देशिका' : 'Doctors Directory') . ' - SwasthyaSearch')

@php
    $seoCity = request('city');
    $hasCity = !empty($seoCity) && $seoCity !== 'All';
    $pageTitle = $hasCity
        ? "Doctors in {$seoCity} | Find Specialists & Clinics | SwasthyaSearch"
        : 'Find Doctors Near You | SwasthyaSearch';
    $pageDescription = $hasCity
        ? "Find doctors in {$seoCity} by specialty, department, clinic, or symptoms. Call providers directly and confirm timings before visiting."
        : 'Search doctors by city, specialty, department, or symptoms. Find contact details, clinic information, and healthcare providers near you.';
@endphp
@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'सत्यापित विशेषज्ञ' : 'Verified Medical Experts' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $hasCity ? "Find Doctors in {$seoCity}" : 'Find Doctors Near You' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'आपके स्वास्थ्य के लिए 100% सत्यापित, अनुभवी और शीर्ष चिकित्सा विशेषज्ञ। सीधे संपर्क करें, कोई छिपा शुल्क नहीं।' : 'Explore our comprehensive directory of 100% verified, world-class healthcare professionals. Connect directly with zero commission.' }}
        </p>
    </div>
</header>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    <p class="text-sm text-slate-600">
        Search by doctor name, specialty, department, symptoms, and city. Please call before visiting as timings and availability may change.
    </p>
</section>

<!-- Filter Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
    <form action="{{ route('doctors.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $locale === 'hi' ? 'डॉक्टर, विभाग, लक्षण खोजें...' : 'Search doctors, departments, symptoms...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                />
            </div>

            <!-- Department Filter -->
            <div>
                <select
                    name="department"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All">{{ $locale === 'hi' ? 'सभी विभाग' : 'All Departments' }}</option>
                    @foreach ($departments as $dept)
                        @php
                            // Handle both array/object or model attribute
                            $deptId = is_array($dept) ? $dept['id'] : $dept->id;
                            $deptNameEn = is_array($dept) ? $dept['name']['en'] : ($dept->name['en'] ?? $dept->name_en);
                            $deptNameHi = is_array($dept) ? $dept['name']['hi'] : ($dept->name['hi'] ?? $dept->name_hi);
                            $deptVal = request('department', $filters['department'] ?? 'All');
                        @endphp
                        <option value="{{ $deptId }}" {{ $deptVal == $deptId ? 'selected' : '' }}>
                            {{ $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Experience Filter -->
            <div>
                @php $expVal = request('experience', $filters['experience'] ?? 'All'); @endphp
                <select
                    name="experience"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All" {{ $expVal === 'All' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सभी अनुभव' : 'All Experience' }}</option>
                    <option value="5" {{ $expVal === '5' ? 'selected' : '' }}>{{ $locale === 'hi' ? '5+ वर्ष' : '5+ Years' }}</option>
                    <option value="10" {{ $expVal === '10' ? 'selected' : '' }}>{{ $locale === 'hi' ? '10+ वर्ष' : '10+ Years' }}</option>
                    <option value="15" {{ $expVal === '15' ? 'selected' : '' }}>{{ $locale === 'hi' ? '15+ वर्ष' : '15+ Years' }}</option>
                    <option value="20" {{ $expVal === '20' ? 'selected' : '' }}>{{ $locale === 'hi' ? '20+ वर्ष' : '20+ Years' }}</option>
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
                href="{{ route('doctors.index') }}"
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

<!-- Doctors Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
    @if (count($doctors) === 0)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                <i data-lucide="search" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">
                {{ $locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Doctors Found' }}
            </h3>
                <p class="text-slate-500 text-base mb-8 leading-relaxed">
                    {{ $locale === 'hi' ? 'कोई परिणाम नहीं मिला। कृपया शहर, विभाग या खोज शब्द बदलकर देखें।' : 'No results found. Try another city, department, or keyword.' }}
                </p>
            <a
                href="{{ route('doctors.index') }}"
                class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
            >
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
                    $deptNameEn = is_array($dept->name) ? $dept->name['en'] : ($dept->name['en'] ?? $dept->name_en);
                    $deptNameHi = is_array($dept->name) ? $dept->name['hi'] : ($dept->name['hi'] ?? $dept->name_hi);
                    $deptName = $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;
                    $aboutEn = is_array($doc->about) ? $doc->about['en'] : ($doc->about['en'] ?? $doc->about_en);
                    $aboutHi = is_array($doc->about) ? $doc->about['hi'] : ($doc->about['hi'] ?? $doc->about_hi);
                    $about = $locale === 'hi' ? ($aboutHi ?: $aboutEn) : $aboutEn;
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                    <!-- Card Header -->
                    <div class="p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                            <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white font-extrabold text-xl tracking-wider">
                                {{ substr($doc->first_name, 0, 1) }}{{ substr($doc->last_name, 0, 1) }}
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start space-x-1.5 mb-1">
                                <h3 class="font-extrabold text-lg text-slate-900 line-clamp-3 leading-snug group-hover:text-teal-600 transition-colors duration-200">
                                    {{ $fullName }}
                                </h3>
                                @if ($doc->is_verified)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500 shrink-0 mt-1"></i>
                                @endif
                            </div>
                            <p class="text-xs font-bold text-teal-600 bg-teal-50 border border-teal-100/80 px-3 py-1 rounded-2xl inline-block mb-2 shadow-2xs line-clamp-2 max-w-full">
                                {{ $deptName }}
                            </p>
                            <div class="flex flex-wrap gap-1 text-slate-500 text-xs">
                                {{ is_array($doc->education_degrees) ? implode(', ', $doc->education_degrees) : $doc->education_degrees }}
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col space-y-4">
                        <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                <i data-lucide="award" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                                <div class="truncate">
                                    <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'अनुभव' : 'Experience' }}</span>
                                    <span class="text-slate-900 font-bold">{{ $doc->experience_years }} {{ $locale === 'hi' ? 'वर्ष' : 'Years' }}</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                <i data-lucide="file-text" class="w-4 h-4 text-teal-500 shrink-0"></i>
                                <div class="truncate">
                                    <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'परामर्श शुल्क' : 'Fee' }}</span>
                                    <span class="text-slate-900 font-bold">₹{{ $doc->consultation_fee ?: 500 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Accordion Toggle Button -->
                        <button type="button" onclick="toggleMobileAccordion('doc-{{ $doc->id }}')" class="md:hidden w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 transition-all duration-200">
                            <span class="flex items-center space-x-2">
                                <i data-lucide="info" class="w-4 h-4 text-teal-600"></i>
                                <span id="doc-{{ $doc->id }}-text">{{ $locale === 'hi' ? 'अतिरिक्त विवरण देखें' : 'View Additional Details' }}</span>
                            </span>
                            <i data-lucide="chevron-down" id="doc-{{ $doc->id }}-icon" class="w-4 h-4 text-slate-500 transition-transform duration-300"></i>
                        </button>

                        <!-- Collapsible Content (Hidden on Mobile by default, visible on MD+) -->
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
                            <div class="flex items-center justify-between text-xs text-slate-500 px-1 pt-1 border-t border-slate-100">
                                <span>{{ $locale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:' }}</span>
                                <span class="font-mono font-semibold text-slate-700">{{ $doc->registration_number }} {{ !empty($doc->medical_council) ? "({$doc->medical_council})" : '' }}</span>
                            </div>
                        @endif

                        @if (!empty($doc->languages_spoken) && count($doc->languages_spoken) > 0)
                            <div class="flex items-center space-x-2 text-xs text-slate-600 px-1">
                                <i data-lucide="languages" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
                                <span class="text-slate-400 text-[11px]">{{ $locale === 'hi' ? 'भाषाएँ:' : 'Languages:' }}</span>
                                <span class="font-medium text-slate-700">{{ implode(', ', $doc->languages_spoken) }}</span>
                            </div>
                        @endif

                        <div class="text-slate-600 text-xs leading-relaxed bg-white flex-1 space-y-2">
                            <p class="line-clamp-3">{{ $about }}</p>
                            @if (!empty($doc->specialization_summary))
                                <p class="text-[11px] text-slate-500 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 rounded-r-lg italic">
                                    {{ $doc->specialization_summary }}
                                </p>
                            @endif
                        </div>

                        @if (!empty($doc->awards_recognitions) && count($doc->awards_recognitions) > 0)
                            <div class="space-y-1 pt-2 border-t border-slate-100">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1">
                                    <i data-lucide="trophy" class="w-3 h-3 text-amber-500"></i>
                                    <span>{{ $locale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions' }}</span>
                                </span>
                                <div class="text-[11px] text-slate-600 pl-4 list-disc space-y-0.5">
                                    @foreach ($doc->awards_recognitions as $award)
                                        <div class="truncate">• {{ $award }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (!empty($doc->hospitals) && count($doc->hospitals) > 0)
                            <div class="pt-4 border-t border-slate-100 space-y-3">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-500"></i>
                                    <span>{{ $locale === 'hi' ? 'अभ्यास स्थल एवं पता' : 'Practicing At & Location' }}</span>
                                </h4>
                                @foreach ($doc->hospitals as $hosp)
                                    @php
                                        $h = is_array($hosp) ? (object) $hosp : $hosp;
                                        $hNameEn = is_array($h->name) ? $h->name['en'] : ($h->name['en'] ?? $h->name_en);
                                        $hNameHi = is_array($h->name) ? $h->name['hi'] : ($h->name['hi'] ?? $h->name_hi);
                                        $hName = $locale === 'hi' ? ($hNameHi ?: $hNameEn) : $hNameEn;
                                        $pivot = is_array($h->pivot) ? (object) $h->pivot : $h->pivot;
                                    @endphp
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100/80 space-y-2 text-xs hover:border-slate-200 transition-colors shadow-2xs">
                                        <div class="font-bold text-slate-900 flex justify-between items-start gap-2">
                                            <div>
                                                <span class="block text-sm text-indigo-950">{{ $hName }}</span>
                                                @if (!empty($h->type))
                                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md inline-block mt-0.5">{{ $h->type }}</span>
                                                @endif
                                            </div>
                                            <span class="text-teal-700 shrink-0 font-extrabold bg-white px-2.5 py-1 rounded-xl border border-teal-100 shadow-2xs">
                                                ₹{{ $pivot->consultation_fee ?? ($doc->consultation_fee ?: 500) }}
                                            </span>
                                        </div>

                                        <p class="text-slate-600 text-[11px] leading-normal pt-1 border-t border-slate-200/60">
                                            <span class="font-semibold text-slate-700">{{ $locale === 'hi' ? 'पता:' : 'Address:' }}</span> {{ !empty($h->address_line1) ? $h->address_line1 . ', ' . (!empty($h->address_line2) ? $h->address_line2 . ', ' : '') . $h->city . ', ' . $h->state . (!empty($h->pincode) ? ' - ' . $h->pincode : '') : ($h->address ?? '') }}
                                        </p>

                                        <!-- Govt Schemes & Cashless Facilities -->
                                        <div class="pt-1 flex flex-wrap gap-1">
                                            @if(!empty($h->accepts_ayushman))
                                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded text-[10px] font-bold flex items-center space-x-1 shadow-2xs">
                                                    <i data-lucide="shield-check" class="w-3 h-3 text-emerald-600"></i>
                                                    <span>{{ $locale === 'hi' ? 'आयुष्मान' : 'Ayushman' }}</span>
                                                </span>
                                            @endif
                                            @if(!empty($h->accepts_janaadhaar))
                                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded text-[10px] font-bold flex items-center space-x-1 shadow-2xs">
                                                    <i data-lucide="award" class="w-3 h-3 text-blue-600"></i>
                                                    <span>{{ $locale === 'hi' ? 'जन आधार' : 'Jan Aadhaar' }}</span>
                                                </span>
                                            @endif
                                            @if(!empty($h->accepts_cghs))
                                                <span class="bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded text-[10px] font-bold flex items-center space-x-1 shadow-2xs">
                                                    <i data-lucide="check-badge" class="w-3 h-3 text-purple-600"></i>
                                                    <span>{{ $locale === 'hi' ? 'सीजीएचएस' : 'CGHS' }}</span>
                                                </span>
                                            @endif
                                            @if(!empty($h->is_cashless))
                                                <span class="bg-teal-50 text-teal-700 border border-teal-200 px-2 py-0.5 rounded text-[10px] font-bold flex items-center space-x-1 shadow-2xs">
                                                    <i data-lucide="credit-card" class="w-3 h-3 text-teal-600"></i>
                                                    <span>{{ $locale === 'hi' ? 'कैशलेस' : 'Cashless' }}</span>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center justify-between text-slate-500 text-[11px] pt-1 border-t border-slate-200/60">
                                            <span class="flex items-center space-x-1 pr-1 truncate">
                                                <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                                <span class="truncate">{{ $pivot->days_of_week ?? 'Mon - Sat' }}</span>
                                            </span>
                                            <span class="font-semibold text-slate-600 shrink-0">
                                                {{ $pivot->start_time ?? '10:00 AM' }} - {{ $pivot->end_time ?? '05:00 PM' }}
                                            </span>
                                        </div>

                                        <div class="pt-2 mt-1 border-t border-slate-200/60 flex items-center justify-between gap-2">
                                            <span class="text-[10px] text-slate-400 italic">{{ $locale === 'hi' ? 'दिशा-निर्देश उपलब्ध' : 'Directions available' }}</span>
                                            <a
                                                href="https://www.google.com/maps/dir/?api=1&destination={{ !empty($h->latitude) ? $h->latitude . ',' . $h->longitude : urlencode(($h->address ?? '') . ', ' . ($h->city ?? 'Jaipur')) }}"
                                                target="_blank"
                                                class="inline-flex items-center space-x-1.5 text-xs text-indigo-600 hover:text-indigo-700 font-bold bg-indigo-50 hover:bg-indigo-100/80 px-3 py-1.5 rounded-xl border border-indigo-100 transition-all shadow-2xs"
                                            >
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
                    <div class="p-6 pt-0 bg-white flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                        @php
                            $doctorPhone = !empty($doc->phone) ? $doc->phone : '';
                            $hospitalPhone = '';
                            if (!empty($doc->hospitals) && count($doc->hospitals) > 0) {
                                foreach ($doc->hospitals as $hospForPhone) {
                                    $hfp = is_array($hospForPhone) ? (object) $hospForPhone : $hospForPhone;
                                    $candidate = !empty($hfp->emergency_phone) ? $hfp->emergency_phone : (!empty($hfp->phone) ? $hfp->phone : '');
                                    if (!empty($candidate)) {
                                        $hospitalPhone = $candidate;
                                        break;
                                    }
                                }
                            }
                            $hasDoctorPhone = !empty($doctorPhone);
                            $hasHospitalPhone = !empty($hospitalPhone);
                        @endphp

                        @if($hasDoctorPhone && $hasHospitalPhone)
                            <a href="tel:{{ $doctorPhone }}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                                <span>Call Doctor</span>
                            </a>
                            <a href="tel:{{ $hospitalPhone }}" class="w-full sm:flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone-call" class="w-4 h-4 text-indigo-100"></i>
                                <span>Call Hospital</span>
                            </a>
                        @elseif($hasDoctorPhone)
                            <a href="tel:{{ $doctorPhone }}" class="w-full sm:flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone" class="w-4 h-4 text-teal-100"></i>
                                <span>Call Doctor</span>
                            </a>
                        @elseif($hasHospitalPhone)
                            <a href="tel:{{ $hospitalPhone }}" class="w-full sm:flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2">
                                <i data-lucide="phone-call" class="w-4 h-4 text-indigo-100"></i>
                                <span>Call Hospital</span>
                            </a>
                        @else
                            <button
                                type="button"
                                onclick="alert('{{ $locale === 'hi' ? '?????? ?? ??????? ?? ???? ???? ?????? ???? ??? ????? ??? ??? ?? ??????? ?????? ?????? ?? ????? ?????' : 'Direct doctor or clinic number is unlisted. Please use the hospital contact numbers listed above.' }}')"
                                class="w-full sm:flex-1 bg-slate-100 text-slate-400 font-bold py-3 px-4 rounded-xl border border-slate-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 cursor-not-allowed"
                            >
                                <i data-lucide="phone-off" class="w-4 h-4 text-slate-400"></i>
                                <span>{{ $locale === 'hi' ? '?????? ?????? ?????? ????' : 'Doctor Tel Unlisted' }}</span>
                            </button>
                        @endif
@if (!empty($doc->website))
                            <a
                                href="{{ str_starts_with($doc->website, 'http') ? $doc->website : 'https://' . $doc->website }}"
                                target="_blank"
                                class="w-full sm:flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                            >
                                <i data-lucide="globe" class="w-4 h-4 text-teal-400"></i>
                                <span>{{ $locale === 'hi' ? 'वेबसाइट देखें' : 'Visit Website' }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10 rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 shadow-sm">
            {{ $doctors->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
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
</script>
@endpush

