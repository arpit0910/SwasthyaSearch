@extends('layouts.public')

@php
    $hospitalName = $locale === 'hi' ? ($hospital['name_hi'] ?: $hospital['name_en']) : $hospital['name_en'];
    $hospitalCity = $locale === 'hi' ? ($hospital['city_hi'] ?? $hospital['city']) : $hospital['city'];
    $hospitalState = $locale === 'hi' ? ($hospital['state_hi'] ?? $hospital['state']) : $hospital['state'];
    $hospitalAddressLine1 = $locale === 'hi' ? ($hospital['address_line1_hi'] ?? $hospital['address_line1']) : $hospital['address_line1'];
    $hospitalAddressLine2 = $locale === 'hi' ? ($hospital['address_line2_hi'] ?? $hospital['address_line2']) : $hospital['address_line2'];
@endphp

@section('title', ($locale === 'hi' ? 'अस्पताल के डॉक्टर' : 'Hospital Doctors') . ' - Arogio')
@section('meta_title', "{$hospitalName} Doctors | Arogio")
@section('meta_description', "View doctors associated with {$hospital['name_en']} in {$hospital['city']}. Check specialty, experience, and contact details before visiting.")

@section('content')
<header class="bg-gradient-to-r from-cyan-700 via-teal-700 to-sky-700 dark:from-slate-900 dark:via-cyan-900 dark:to-slate-900 text-white py-14 px-4 sm:px-6 lg:px-8 border-b border-cyan-800 dark:border-slate-700 ring-1 ring-black/10 dark:ring-white/15 shadow-xl dark:shadow-black/50 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)] dark:opacity-0"></div>
    <div class="max-w-7xl mx-auto relative z-10">
        <a href="{{ route('hospitals.index') }}" class="inline-flex items-center gap-2 mb-4 text-teal-300 hover:text-teal-200 text-sm font-semibold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'अस्पताल सूची पर वापस' : 'Back to Hospitals' }}</span>
        </a>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            {{ $hospitalName }}
        </h1>
        <p class="text-slate-200 mt-2">
            {{ $locale === 'hi' ? 'इस अस्पताल से जुड़े डॉक्टर देखें' : 'View doctors associated with this hospital' }}
        </p>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-8">
        <div class="flex flex-wrap gap-3 text-sm text-slate-700">
            <span class="inline-flex items-center gap-1.5 bg-cyan-50 border border-indigo-200 px-3 py-1 rounded-full font-semibold">
                <i data-lucide="building-2" class="w-4 h-4 text-cyan-600"></i>{{ $hospital['type'] }}
            </span>
            <span class="inline-flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-3 py-1 rounded-full font-semibold">
                <i data-lucide="map-pin" class="w-4 h-4 text-teal-600"></i>{{ $hospitalCity }}
            </span>
            @php $hospitalPrimaryPhone = $hospital['phone_1'] ?? ($hospital['phone_2'] ?? ($hospital['phone'] ?? null)); @endphp
            @if(!empty($hospitalPrimaryPhone))
                <a href="tel:{{ $hospitalPrimaryPhone }}" class="inline-flex items-center gap-1.5 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full font-semibold text-teal-800">
                    <i data-lucide="phone-call" class="w-4 h-4"></i>{{ $locale === 'hi' ? 'कॉल करें' : 'Call Hospital' }}
                </a>
            @endif
        </div>
        <div class="mt-3 text-sm text-slate-600">
            {{ trim($hospitalAddressLine1 . (!empty($hospitalAddressLine2) ? ', ' . $hospitalAddressLine2 : '') . ', ' . $hospitalCity . ', ' . $hospitalState . (!empty($hospital['pincode']) ? ' - ' . $hospital['pincode'] : '')) }}
        </div>
    </div>

    @if($doctors->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center">
            <i data-lucide="stethoscope" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
            <h2 class="text-xl font-bold text-slate-900">{{ $locale === 'hi' ? 'डॉक्टर उपलब्ध नहीं' : 'No Doctors Listed' }}</h2>
            <p class="text-slate-600 mt-2">{{ $locale === 'hi' ? 'इस अस्पताल के लिए फिलहाल कोई सत्यापित डॉक्टर सूचीबद्ध नहीं है।' : 'No verified doctors are currently listed for this hospital.' }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctors as $doc)
                @php
                    $deptName = $doc['department']
                        ? ($locale === 'hi' ? ($doc['department']['name_hi'] ?: $doc['department']['name_en']) : $doc['department']['name_en'])
                        : ($locale === 'hi' ? 'विभाग उपलब्ध नहीं' : 'Department N/A');
                    $fullName = 'Dr. ' . trim($doc['first_name'] . ' ' . $doc['last_name']);
                @endphp
                <article class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                    <h3 class="font-bold text-slate-900 text-lg">{{ $fullName }}</h3>
                    <p class="text-sm text-teal-700 font-semibold mt-1">{{ $deptName }}</p>
                    <p class="text-xs text-slate-600 mt-2">{{ $doc['experience_years'] }}+ {{ $locale === 'hi' ? 'वर्ष अनुभव' : 'years experience' }}</p>
                    @if(!empty($doc['phone']))
                        <a href="tel:{{ $doc['phone'] }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold bg-cyan-600 text-white px-3 py-2 rounded-xl">
                            <i data-lucide="phone" class="w-4 h-4"></i>{{ $locale === 'hi' ? 'कॉल डॉक्टर' : 'Call Doctor' }}
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</main>
@endsection

