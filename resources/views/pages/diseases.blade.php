@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'रोग और लक्षण' : 'Diseases & Symptoms') . ' - SwasthyaSearch')

@section('meta_title', $locale === 'hi' ? 'रोग और लक्षण निर्देशिका | SwasthyaSearch' : 'Diseases & Symptoms Directory | SwasthyaSearch')
@section('meta_description', $locale === 'hi'
    ? 'रोग या लक्षण खोजें और संबंधित चिकित्सा विभाग देखें। सही विशेषज्ञ डॉक्टर तक पहुंचने के लिए उपयोगी स्वास्थ्य निर्देशिका।'
    : 'Search diseases and symptoms, then discover the relevant medical departments to reach the right specialist doctors.')
@section('content')
    <!-- Hero Section -->
    <header
        class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
        <div class="max-w-7xl mx-auto">
            <div class="max-w-3xl">
                <span
                    class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-2 mb-4 shadow-sm">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                    <span>{{ $locale === 'hi' ? 'रोग वर्गीकरण' : 'Disease Taxonomy' }}</span>
                </span>
                <h1
                    class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
                    {{ $locale === 'hi' ? 'रोग और लक्षण निर्देशिका' : 'Diseases & Symptoms Directory' }}
                </h1>
                <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                    {{ $locale === 'hi' ? 'रोग या लक्षण खोजें और उससे संबंधित चिकित्सा विभाग देखें।' : 'Search diseases or symptoms and see the medical department associated with each one.' }}
                </p>
            </div>
        </div>
    </header>

    <!-- Filter Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 sm:-mt-8 relative z-20 w-full mb-10">
        <form action="{{ route('diseases.index') }}" method="GET" data-auto-filter
            class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6 backdrop-blur-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative md:col-span-2">
                    <i data-lucide="search" class="absolute left-4 top-3.5 w-5 h-5 text-slate-400"></i>
                    <input type="text" name="search"
                        placeholder="{{ $locale === 'hi' ? 'रोग या लक्षण खोजें...' : 'Search disease or symptom...' }}"
                        value="{{ request('search', $filters['search'] ?? '') }}"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 font-medium" />
                </div>

                @php $deptVal = request('department', $filters['department'] ?? 'All'); @endphp
                <select name="department"
                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 font-medium text-slate-700">
                    <option value="All" {{ $deptVal === 'All' ? 'selected' : '' }}>
                        {{ $locale === 'hi' ? 'सभी विभाग' : 'All Departments' }}</option>
                    @foreach ($departments as $item)
                        @php
                            $i = is_array($item) ? (object) $item : $item;
                            $iNameEn = is_array($i->name) ? $i->name['en'] : $i->name['en'] ?? $i->name_en;
                            $iNameHi = is_array($i->name) ? $i->name['hi'] : $i->name['hi'] ?? $i->name_hi;
                            $iName = $locale === 'hi' ? ($iNameHi ?: $iNameEn) : $iNameEn;
                        @endphp
                        <option value="{{ $i->id }}" {{ $deptVal == $i->id ? 'selected' : '' }}>{{ $iName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-wrap justify-end gap-3 mt-5 pt-5 border-t border-slate-100">
                <a href="{{ route('diseases.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm flex items-center gap-2 shadow-2xs">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset' }}</span>
                </a>
            </div>
        </form>
    </section>

    <!-- Diseases Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
        @if (count($diseases) === 0)
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm max-w-2xl mx-auto">
                <i data-lucide="search" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                <h2 class="text-xl font-bold text-slate-900">
                    {{ $locale === 'hi' ? 'कोई रोग नहीं मिला' : 'No diseases found' }}</h2>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($diseases as $disease)
                    @php
                        $d = is_array($disease) ? (object) $disease : $disease;
                        $dNameEn = is_array($d->name) ? $d->name['en'] : $d->name['en'] ?? $d->name_en;
                        $dNameHi = is_array($d->name) ? $d->name['hi'] : $d->name['hi'] ?? $d->name_hi;
                        $dName = $locale === 'hi' ? ($dNameHi ?: $dNameEn) : $dNameEn;
                        $dept = $d->department
                            ? (is_array($d->department)
                                ? (object) $d->department
                                : $d->department)
                            : null;
                        if ($dept) {
                            $deptNameEn = is_array($dept->name)
                                ? $dept->name['en']
                                : $dept->name['en'] ?? $dept->name_en;
                            $deptNameHi = is_array($dept->name)
                                ? $dept->name['hi']
                                : $dept->name['hi'] ?? $dept->name_hi;
                            $deptName = $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;
                        }
                    @endphp
                    <div
                        class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs hover:shadow-lg hover:border-teal-200 transition-all group">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                <i data-lucide="activity" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="font-extrabold text-slate-900 leading-snug">{{ $dName }}</h2>
                                @if ($dept)
                                    <a href="{{ route('doctors.index', ['department' => $dept->id]) }}"
                                        class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100 hover:bg-teal-100 px-3 py-1 rounded-full transition-colors shadow-2xs">
                                        <i data-lucide="stethoscope" class="w-3.5 h-3.5"></i>
                                        <span>{{ $deptName }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
@endsection
