@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'ब्लड बैंक निर्देशिका' : 'Blood Banks Directory') . ' - SwasthyaSearch')

@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-slate-900 via-red-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(239,68,68,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-red-500/20 text-red-300 border border-red-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'सत्यापित रक्त केंद्र' : 'Verified Blood Centers' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-tight">
            {{ $locale === 'hi' ? 'आपातकालीन ब्लड बैंक व रक्त समूह खोजें' : 'Find Emergency Blood Banks & Availability' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'सत्यापित और लाइसेंस प्राप्त ब्लड बैंक खोजें। रक्त उपलब्धता तेजी से बदल सकती है, इसलिए जाने से पहले कॉल करके पुष्टि करें।' : 'Find verified, licensed blood banks. Blood availability can change quickly, so please call to confirm before visiting.' }}
        </p>
    </div>
</header>

<!-- Filter Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
    <form action="{{ route('blood_banks.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-12 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative xl:col-span-4">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $locale === 'hi' ? 'ब्लड बैंक का नाम या स्थान खोजें...' : 'Search blood bank name or location...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium"
                />
            </div>

            <!-- Blood Group Filter -->
            <div class="xl:col-span-2">
                @php $bgVal = request('blood_group', $filters['blood_group'] ?? 'All'); @endphp
                <select
                    name="blood_group"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All">{{ $locale === 'hi' ? 'सभी रक्त समूह' : 'All Blood Groups' }}</option>
                    @foreach ($bloodGroups as $bg)
                        <option value="{{ $bg }}" {{ $bgVal === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Facility Filter -->
            <div class="xl:col-span-3">
                @php $facVal = request('facility', $filters['facility'] ?? 'All'); @endphp
                <select
                    name="facility"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All" {{ $facVal === 'All' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सभी सुविधाएं' : 'All Facilities' }}</option>
                    <option value="24x7" {{ $facVal === '24x7' ? 'selected' : '' }}>{{ $locale === 'hi' ? '24x7 उपलब्ध' : '24x7 Available' }}</option>
                    <option value="Government" {{ $facVal === 'Government' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सरकारी ब्लड बैंक' : 'Government Blood Bank' }}</option>
                    <option value="Component" {{ $facVal === 'Component' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'रक्त घटक (प्लेटलेट्स आदि)' : 'Blood Components' }}</option>
                    <option value="Apheresis" {{ $facVal === 'Apheresis' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'एफेरेसिस (एसडीपी)' : 'Apheresis (SDP)' }}</option>
                </select>
            </div>

            <!-- City Filter -->
            <div class="xl:col-span-3">
                @php $cityVal = request('city', $filters['city'] ?? 'All'); @endphp
                <select
                    name="city"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all duration-200 font-medium text-slate-700"
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
                href="{{ route('blood_banks.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>

            <button
                type="submit"
                class="h-12 bg-gradient-to-tr from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold px-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm flex items-center justify-center space-x-2 transform active:scale-98 uppercase tracking-wider"
            >
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters' }}</span>
            </button>
        </div>
    </form>
</section>

<!-- Blood Banks Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
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
                class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
            >
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
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                    <!-- Card Header -->
                    <div class="p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-start space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-tr from-red-500 to-rose-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                            <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white font-extrabold text-xl tracking-wider">
                                <i data-lucide="droplet" class="w-8 h-8 text-red-500 fill-red-500"></i>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start space-x-1.5 mb-1">
                                <h3 class="font-extrabold text-lg text-slate-900 line-clamp-3 leading-snug group-hover:text-red-600 transition-colors duration-200">
                                    {{ $bName }}
                                </h3>
                                @if ($bank->is_verified)
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-teal-500 shrink-0 mt-1"></i>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-1 mb-2">
                                @if ($bank->is_24_7)
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md shadow-2xs">
                                        {{ $locale === 'hi' ? '24x7 उपलब्ध' : '24x7 Open' }}
                                    </span>
                                @endif
                                @if ($bank->is_government)
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md shadow-2xs">
                                        {{ $locale === 'hi' ? 'सरकारी' : 'Govt' }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-md shadow-2xs">
                                        {{ $locale === 'hi' ? 'प्राइवेट' : 'Private' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col space-y-4">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100/80 space-y-3 shadow-2xs">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                                <i data-lucide="activity" class="w-3.5 h-3.5 text-red-500"></i>
                                <span>{{ $locale === 'hi' ? 'उपलब्ध रक्त समूह' : 'Available Blood Groups' }}</span>
                            </h4>
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                @if (!empty($bank->available_blood_groups) && count($bank->available_blood_groups) > 0)
                                    @foreach ($bank->available_blood_groups as $bg)
                                        <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-xl text-xs font-extrabold shadow-2xs flex items-center space-x-1">
                                            <i data-lucide="droplet" class="w-3 h-3 text-red-500 fill-red-500"></i>
                                            <span>{{ $bg }}</span>
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-xs text-slate-400 italic">{{ $locale === 'hi' ? 'जानकारी उपलब्ध नहीं' : 'Stock info unavailable' }}</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-amber-800 bg-amber-50 border border-amber-200 rounded-xl px-2.5 py-1.5">
                                {{ $locale === 'hi' ? 'रक्त उपलब्धता तेजी से बदल सकती है। कृपया जाने से पहले कॉल से पुष्टि करें।' : 'Blood availability can change quickly. Please call to confirm before visiting.' }}
                            </p>
                        </div>

                        <!-- Mobile Accordion Toggle Button -->
                        <button type="button" onclick="toggleMobileAccordion('bank-{{ $bank->id }}')" class="md:hidden w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 transition-all duration-200">
                            <span class="flex items-center space-x-2">
                                <i data-lucide="info" class="w-4 h-4 text-red-600"></i>
                                <span id="bank-{{ $bank->id }}-text">{{ $locale === 'hi' ? 'सुविधाएं व विवरण देखें' : 'View Facilities & Details' }}</span>
                            </span>
                            <i data-lucide="chevron-down" id="bank-{{ $bank->id }}-icon" class="w-4 h-4 text-slate-500 transition-transform duration-300"></i>
                        </button>

                        <!-- Collapsible Content -->
                        <div id="bank-{{ $bank->id }}-content" class="hidden md:flex flex-col space-y-4 flex-1">
                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                    <i data-lucide="layers" class="w-4 h-4 {{ $bank->component_facility ? 'text-emerald-500' : 'text-slate-300' }} shrink-0"></i>
                                    <div class="truncate">
                                        <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'रक्त घटक' : 'Components' }}</span>
                                        <span class="{{ $bank->component_facility ? 'text-slate-900 font-bold' : 'text-slate-400 font-normal' }}">
                                            {{ $bank->component_facility ? ($locale === 'hi' ? 'उपलब्ध' : 'Available') : ($locale === 'hi' ? 'अनुपलब्ध' : 'N/A') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                    <i data-lucide="repeat" class="w-4 h-4 {{ $bank->apheresis_facility ? 'text-teal-500' : 'text-slate-300' }} shrink-0"></i>
                                    <div class="truncate">
                                        <span class="text-slate-400 block text-[10px] uppercase">{{ $locale === 'hi' ? 'एफेरेसिस (एसडीपी)' : 'Apheresis' }}</span>
                                        <span class="{{ $bank->apheresis_facility ? 'text-slate-900 font-bold' : 'text-slate-400 font-normal' }}">
                                            {{ $bank->apheresis_facility ? ($locale === 'hi' ? 'उपलब्ध' : 'Available') : ($locale === 'hi' ? 'अनुपलब्ध' : 'N/A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if (!empty($addr))
                                <div class="flex items-start space-x-1.5 text-xs text-slate-600 px-1 pt-1 border-t border-slate-100">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-500 shrink-0 mt-0.5"></i>
                                    <div>
                                        <span class="font-semibold text-slate-800 block">{{ $addr }}</span>
                                        @if (!empty($bank->city) || !empty($bank->state))
                                            <span class="text-slate-500 text-[11px]">{{ implode(', ', array_filter([$bank->city, $bank->state, $bank->pincode])) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (!empty($bank->last_updated_stock_at))
                                <div class="flex items-center space-x-1.5 text-[11px] text-slate-400 px-1 pt-1 border-t border-slate-100 italic">
                                    <i data-lucide="clock" class="w-3 h-3 text-slate-400 shrink-0"></i>
                                    <span>{{ $locale === 'hi' ? 'स्टॉक अपडेट:' : 'Stock Updated:' }} {{ \Carbon\Carbon::parse($bank->last_updated_stock_at)->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-6 pt-0 bg-white flex flex-col sm:flex-row gap-2.5 sm:gap-3">
                        <a
                            href="{{ !empty($bank->phone) ? 'tel:' . (($bank->country_code ?? '') . $bank->phone) : '#' }}"
                            class="w-full sm:flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                        >
                            <i data-lucide="phone" class="w-4 h-4 text-red-100"></i>
                            <span>{{ $locale === 'hi' ? 'अभी कॉल करें' : 'Call Now' }}</span>
                        </a>
                        @if (!empty($bank->website))
                            <a
                                href="{{ str_starts_with($bank->website, 'http') ? $bank->website : 'https://' . $bank->website }}"
                                target="_blank"
                                class="w-full sm:flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                            >
                                <i data-lucide="globe" class="w-4 h-4 text-red-400"></i>
                                <span>{{ $locale === 'hi' ? 'वेबसाइट देखें' : 'Visit Website' }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
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
            text.innerText = isHi ? 'सुविधाएं व विवरण देखें' : 'View Facilities & Details';
        }
    }
</script>
@endpush
