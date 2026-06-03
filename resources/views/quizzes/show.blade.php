@extends('layouts.public')

@section('title', (($locale === 'hi' ? $quiz->title_hi : $quiz->title_en) ?: $quiz->title_en) . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <div class="inline-flex rounded-full bg-indigo-100 dark:bg-indigo-950/40 px-3 py-1 text-xs font-bold text-indigo-700 dark:text-indigo-200">{{ $quiz->category ?: ($locale === 'hi' ? 'क्विज़' : 'Quiz') }}</div>
        <h1 class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? ($quiz->title_hi ?: $quiz->title_en) : ($quiz->title_en ?: $quiz->title_hi) }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? ($quiz->intro_hi ?: $quiz->intro_en) : ($quiz->intro_en ?: $quiz->intro_hi) }}</p>

        <form action="{{ route('quizzes.result', $quiz->slug) }}" method="POST" class="mt-8 space-y-6">
            @csrf
            @foreach(($quiz->questions_json ?? []) as $qIndex => $question)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? ($question['question_hi'] ?? $question['question_en']) : ($question['question_en'] ?? $question['question_hi']) }}</h2>
                    <div class="mt-4 space-y-3">
                        @foreach(($question['options'] ?? []) as $oIndex => $option)
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 cursor-pointer">
                                <input type="radio" name="answers[{{ $qIndex }}]" value="{{ $oIndex }}" class="mt-1" required>
                                <span class="text-sm text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? ($option['label_hi'] ?? $option['label_en']) : ($option['label_en'] ?? $option['label_hi']) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <button class="rounded-2xl bg-indigo-600 hover:bg-indigo-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'परिणाम देखें' : 'See Result' }}</button>
        </form>
    </section>
</main>
@endsection
