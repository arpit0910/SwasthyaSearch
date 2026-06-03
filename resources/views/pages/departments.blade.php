@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'चिकित्सा विभाग' : 'Medical Departments') . ' - Arogio')

@section('meta_title', $locale === 'hi' ? 'चिकित्सा विभाग निर्देशिका | Arogio' : 'Medical Departments Directory | Arogio')
@section('meta_description', $locale === 'hi'
    ? 'सभी प्रमुख चिकित्सा विभाग देखें और समझें कि कौन सा विभाग किन रोगों और लक्षणों के लिए उपयुक्त है।'
    : 'Browse major medical departments and understand which specialties are relevant for different diseases and symptoms.')
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-cyan-800 via-teal-700 to-emerald-700 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto">
        <div class="max-w-3xl">
            <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-2 mb-4 shadow-sm">
                <i data-lucide="stethoscope" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'विशेषज्ञता निर्देशिका' : 'Specialty Directory' }}</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
                {{ $locale === 'hi' ? 'सभी चिकित्सा विभाग' : 'All Medical Departments' }}
            </h1>
            <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                {{ $locale === 'hi' ? 'उपलब्ध विशेषज्ञताओं को देखें और समझें कि किस विभाग में कौन से रोग और लक्षण आते हैं।' : 'Browse available specialties and understand which departments handle different diseases and symptoms.' }}
            </p>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full py-12">
    <!-- Search Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 mb-8">
        <div class="relative">
            <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-slate-400"></i>
            <input
                type="text"
                id="department-search"
                placeholder="{{ $locale === 'hi' ? 'विभाग खोजें...' : 'Search departments...' }}"
                class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 font-medium transition-all"
            />
        </div>
    </div>

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="departments-grid">
        @foreach ($departments as $department)
            @php
                $dept = is_array($department) ? (object) $department : $department;
                $nameEn = is_array($dept->name) ? $dept->name['en'] : ($dept->name['en'] ?? $dept->name_en);
                $nameHi = is_array($dept->name) ? $dept->name['hi'] : ($dept->name['hi'] ?? $dept->name_hi);
                $name = $locale === 'hi' ? ($nameHi ?: $nameEn) : $nameEn;
                $descEn = is_array($dept->description) ? $dept->description['en'] : ($dept->description['en'] ?? $dept->description_en);
                $descHi = is_array($dept->description) ? $dept->description['hi'] : ($dept->description['hi'] ?? $dept->description_hi);
                $desc = $locale === 'hi' ? ($descHi ?: $descEn) : $descEn;
            @endphp
            <a
                href="{{ route('diseases.index', ['department' => $dept->id]) }}"
                class="department-card text-left bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:border-teal-200 transition-all duration-200 block group"
                data-search="{{ strtolower($nameEn . ' ' . $nameHi . ' ' . $descEn . ' ' . $descHi) }}"
            >
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="stethoscope" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-bold text-cyan-700 bg-cyan-50 border border-cyan-100 px-3 py-1 rounded-full shadow-2xs">
                        {{ $locale === 'hi' ? 'विभाग' : 'Department' }}
                    </span>
                </div>

                <h2 class="text-lg font-extrabold text-slate-900 mb-2 group-hover:text-teal-600 transition-colors">{{ $name }}</h2>
                <p class="text-sm text-slate-600 leading-relaxed min-h-[60px] line-clamp-3">{{ $desc }}</p>

                <div class="grid grid-cols-2 gap-3 mt-5 pt-5 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i data-lucide="activity" class="w-4 h-4 text-teal-600"></i>
                        <span class="font-semibold">{{ $dept->diseases_count ?? 0 }} {{ $locale === 'hi' ? 'रोग' : 'diseases' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i data-lucide="users" class="w-4 h-4 text-cyan-600"></i>
                        <span class="font-semibold">{{ $dept->doctors_count ?? 0 }} {{ $locale === 'hi' ? 'डॉक्टर' : 'doctors' }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</main>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('department-search');
    const cards = document.querySelectorAll('.department-card');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            cards.forEach(card => {
                const text = card.getAttribute('data-search');
                if (text.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
</script>
@endpush

