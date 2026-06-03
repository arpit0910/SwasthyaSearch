@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'क्विज़' : 'Quizzes') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'हेल्थ क्विज़ | सामान्य स्वास्थ्य जागरूकता' : 'Health Quizzes | General Health Awareness')
@section('meta_description', $locale === 'hi' ? 'सामान्य स्वास्थ्य जागरूकता और आत्म-चिंतन के लिए क्विज़ लें।' : 'Take health awareness and self-reflection quizzes.')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">{{ $locale === 'hi' ? 'जागरूकता क्विज़' : 'Awareness Quizzes' }}</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'हेल्थ और वेलनेस क्विज़' : 'Health and Wellness Quizzes' }}</h1>
        <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'ये क्विज़ सामान्य जागरूकता और आत्म-चिंतन के लिए हैं। ये निदान नहीं करते और चिकित्सा सलाह का विकल्प नहीं हैं।' : 'These quizzes are for general awareness and self-reflection. They do not diagnose any condition and do not replace medical advice.' }}</p>
    </section>

    <section class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($quizzes as $quiz)
            <a href="{{ route('quizzes.show', $quiz->slug) }}" class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 hover:-translate-y-1 transition">
                <div class="inline-flex rounded-full bg-indigo-100 dark:bg-indigo-950/40 px-3 py-1 text-xs font-bold text-indigo-700 dark:text-indigo-200">{{ $quiz->category ?: ($locale === 'hi' ? 'क्विज़' : 'Quiz') }}</div>
                <h2 class="mt-4 text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? ($quiz->title_hi ?: $quiz->title_en) : ($quiz->title_en ?: $quiz->title_hi) }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? ($quiz->description_hi ?: $quiz->description_en) : ($quiz->description_en ?: $quiz->description_hi) }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-indigo-700 dark:text-indigo-300">{{ $locale === 'hi' ? 'क्विज़ शुरू करें' : 'Start Quiz' }}</span>
            </a>
        @endforeach
    </section>

    <div class="mt-8">{{ $quizzes->links() }}</div>
</main>
@endsection
