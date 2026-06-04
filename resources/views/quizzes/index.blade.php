@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'क्विज़' : 'Quizzes') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'हेल्थ क्विज़ | सामान्य स्वास्थ्य जागरूकता' : 'Health Quizzes | General Health Awareness')
@section('meta_description', $locale === 'hi' ? 'सामान्य स्वास्थ्य जागरूकता और आत्म-चिंतन के लिए क्विज़ लें।' : 'Take health awareness and self-reflection quizzes.')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-gradient-to-br from-white via-indigo-50/50 to-cyan-50/50 dark:from-slate-900/90 dark:to-slate-950 shadow-sm p-6 sm:p-8 lg:p-10">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">{{ $locale === 'hi' ? 'अवेयरनेस क्विज़' : 'Awareness Quizzes' }}</p>
                <h1 class="mt-3 text-3xl sm:text-5xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'छोटे, आसान और साफ़ क्विज़' : 'Short, simple, clearer quizzes' }}</h1>
                <p class="mt-4 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'हर क्विज़ सामान्य जागरूकता और आत्म-चिंतन के लिए है। यह निदान नहीं करता और चिकित्सा सलाह का विकल्प नहीं है।' : 'Each quiz is for general awareness and self-reflection. It does not diagnose any condition and does not replace medical advice.' }}</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-[1.5rem] bg-white/80 dark:bg-slate-950/40 p-5">
                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'बिना झंझट' : 'Low friction' }}</div>
                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'एक स्क्रीन पर साफ़ विकल्प' : 'Clear options on each screen' }}</div>
                </div>
                <div class="rounded-[1.5rem] bg-white/80 dark:bg-slate-950/40 p-5">
                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'आसान शुरुआत' : 'Easy start' }}</div>
                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'किसी भी क्विज़ पर सीधे जाएँ' : 'Jump straight into any quiz' }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($quizzes as $quiz)
            <a href="{{ route('quizzes.show', $quiz->slug) }}" class="group flex h-full flex-col rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 transition hover:-translate-y-1 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-2 sm:gap-3">
                    <div class="inline-flex rounded-full bg-indigo-100 dark:bg-indigo-950/40 px-3 py-1 text-xs font-bold text-indigo-700 dark:text-indigo-200">{{ $quiz->category ?: ($locale === 'hi' ? 'क्विज़' : 'Quiz') }}</div>
                    <span class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-bold text-slate-600 dark:text-slate-300">{{ count($quiz->questions_json ?? []) }} {{ $locale === 'hi' ? 'प्रश्न' : 'questions' }}</span>
                </div>
                <h2 class="mt-4 text-lg font-bold text-slate-950 dark:text-white sm:text-xl">{{ $locale === 'hi' ? ($quiz->title_hi ?: $quiz->title_en) : ($quiz->title_en ?: $quiz->title_hi) }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300 sm:leading-7">{{ $locale === 'hi' ? ($quiz->description_hi ?: $quiz->description_en) : ($quiz->description_en ?: $quiz->description_hi) }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-indigo-700 dark:text-indigo-300 transition group-hover:translate-x-0.5 sm:mt-5">{{ $locale === 'hi' ? 'क्विज़ शुरू करें' : 'Start quiz' }}</span>
            </a>
        @endforeach
    </section>

    <div class="mt-8">{{ $quizzes->links() }}</div>
</main>
@endsection
