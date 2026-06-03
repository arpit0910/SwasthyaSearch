@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'क्विज़ परिणाम' : 'Quiz Result') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <h1 class="text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'क्विज़ परिणाम' : 'Quiz Result' }}</h1>
        <div class="mt-5 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'स्कोर' : 'Score' }}</div>
            <div class="mt-1 text-4xl font-extrabold text-indigo-700 dark:text-indigo-300">{{ $score }}</div>
            @if($result)
                <h2 class="mt-4 text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? ($result['title_hi'] ?? $result['title_en']) : ($result['title_en'] ?? $result['title_hi']) }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? ($result['message_hi'] ?? $result['message_en']) : ($result['message_en'] ?? $result['message_hi']) }}</p>
            @endif
        </div>
        <div class="mt-6 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-4 text-sm text-amber-900 dark:text-amber-100">
            {{ $locale === 'hi' ? ($quiz->disclaimer_hi ?: $quiz->disclaimer_en) : ($quiz->disclaimer_en ?: $quiz->disclaimer_hi) }}
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('activities.index') }}" class="rounded-2xl bg-teal-600 hover:bg-teal-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'गतिविधियां आज़माएं' : 'Try Activities' }}</a>
            <a href="{{ route('support.crisis') }}" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'यदि असुरक्षित लगे तो सहायता लें' : 'Get Support If You Feel Unsafe' }}</a>
        </div>
    </section>
</main>
@endsection
