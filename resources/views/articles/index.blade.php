@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'स्वास्थ्य लेख व समाचार' : 'Health Articles & News') . ' - SwasthyaSearch')

@section('meta_title', 'Health Articles and Guides | SwasthyaSearch')
@section('meta_description', 'Read patient-friendly health articles and guides on symptoms, prevention, and healthcare access. This content is for general information and not a substitute for medical advice.')
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'विशेषज्ञ स्वास्थ्य ज्ञान' : 'Expert Medical Knowledge' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $locale === 'hi' ? 'नवीनतम स्वास्थ्य लेख और सुझाव' : 'Health Articles and Guides' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'शीर्ष डॉक्टरों और पोषण विशेषज्ञों द्वारा लिखे गए प्रामाणिक, शोध-आधारित स्वास्थ्य लेख। स्वस्थ जीवनशैली के लिए आवश्यक जानकारी।' : 'Explore evidence-based medical articles, nutritional advice, and fitness tips authored by accredited doctors and healthcare experts.' }}
        </p>
    </div>
</header>

<!-- Filter Bar -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
    <form action="{{ route('articles.index') }}" method="GET" data-auto-filter class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-12 gap-4 items-stretch">
            <!-- Search Input -->
            <div class="relative lg:col-span-3 xl:col-span-9">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="{{ $locale === 'hi' ? 'लेख का शीर्षक या विषय खोजें...' : 'Search article title or topics...' }}"
                    value="{{ request('search', $filters['search'] ?? '') }}"
                    class="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                />
            </div>

            <!-- Category Filter -->
            <div class="lg:col-span-1 xl:col-span-3">
                @php $catVal = request('category', $filters['category'] ?? 'All'); @endphp
                <select
                    name="category"
                    class="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                >
                    <option value="All" {{ $catVal === 'All' ? 'selected' : '' }}>{{ $locale === 'hi' ? 'सभी श्रेणियां' : 'All Categories' }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $catVal === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 mt-6 pt-6 border-t border-slate-100">
            <a
                href="{{ route('articles.index') }}"
                class="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'रीसेट करें' : 'Reset Filters' }}</span>
            </a>`r`n        </div>
    </form>
</section>

<!-- Articles Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
    @if (count($articles) === 0)
        <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                <i data-lucide="book-open" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">
                {{ $locale === 'hi' ? 'कोई लेख नहीं मिला' : 'No Articles Found' }}
            </h3>
            <p class="text-slate-500 text-base mb-8 leading-relaxed">
                {{ $locale === 'hi' ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई स्वास्थ्य लेख नहीं मिला। कृपया अपनी खोज मानदंड बदलें।' : 'We could not find any health articles matching your selected filters. Please try modifying your search criteria.' }}
            </p>
            <a
                href="{{ route('articles.index') }}"
                class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
            >
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'सभी लेख देखें' : 'View All Articles' }}</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $article)
                @php
                    $a = is_array($article) ? (object) $article : $article;
                    $titleEn = is_array($a->title) ? $a->title['en'] : ($a->title['en'] ?? $a->title_en);
                    $titleHi = is_array($a->title) ? $a->title['hi'] : ($a->title['hi'] ?? $a->title_hi);
                    $title = $locale === 'hi' ? ($titleHi ?: $titleEn) : $titleEn;
                    $excerptEn = is_array($a->excerpt) ? $a->excerpt['en'] : ($a->excerpt['en'] ?? $a->excerpt_en);
                    $excerptHi = is_array($a->excerpt) ? $a->excerpt['hi'] : ($a->excerpt['hi'] ?? $a->excerpt_hi);
                    $excerpt = $locale === 'hi' ? ($excerptHi ?: $excerptEn) : $excerptEn;
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                    <!-- Card Header / Category Banner -->
                    <div class="p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-extrabold text-teal-700 bg-teal-50 border border-teal-100 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">
                            {{ $a->category }}
                        </span>
                        <div class="flex items-center space-x-1.5 text-slate-400 text-xs font-semibold">
                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span>{{ is_array($a->comments) ? count($a->comments) : ($a->comments ? $a->comments->count() : 0) }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col space-y-4 bg-white">
                        <h3 class="font-extrabold text-xl text-slate-900 leading-snug group-hover:text-teal-600 transition-colors duration-200 line-clamp-2">
                            {{ $title }}
                        </h3>

                        <div class="flex items-center space-x-4 text-slate-500 text-xs font-medium pt-1 pb-2 border-b border-slate-100">
                            <div class="flex items-center space-x-1.5">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $a->author_name }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ \Carbon\Carbon::parse($a->created_at)->translatedFormat($locale === 'hi' ? 'd M Y' : 'M d, Y') }}</span>
                            </div>
                        </div>

                        <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 flex-1">
                            {{ $excerpt }}
                        </p>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-6 pt-0 bg-white">
                        <a
                            href="{{ route('articles.show', $a->id) }}"
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98"
                        >
                            <i data-lucide="book-open" class="w-4 h-4 text-teal-400"></i>
                            <span>{{ $locale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Full Article' }}</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($articles->hasPages())
        <div class="mt-12">
            {{ $articles->links('pagination::tailwind') }}
        </div>
    @endif
</main>
@endsection


