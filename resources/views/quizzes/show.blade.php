@extends('layouts.public')

@php
    $isHindi = \App\Helpers\LocaleHelper::current() === 'hi';
    $quizTitle = $isHindi ? ($quiz->title_hi ?: $quiz->title_en) : ($quiz->title_en ?: $quiz->title_hi);
    $quizDescription = $isHindi ? ($quiz->description_hi ?: $quiz->intro_hi ?: $quiz->description_en ?: $quiz->intro_en) : ($quiz->description_en ?: $quiz->intro_en ?: $quiz->description_hi ?: $quiz->intro_hi);
@endphp

@section('title', $quizTitle . ' - Arogio')
@section('meta_title', ($isHindi ? ($quiz->meta_title_hi ?: ($quizTitle . ' | Arogio')) : ($quiz->meta_title_en ?: ($quizTitle . ' | Arogio'))))
@section('meta_description', \App\Support\Seo::cleanText($isHindi ? ($quiz->meta_description_hi ?: $quizDescription) : ($quiz->meta_description_en ?: $quizDescription), 160))
@section('meta_keywords', \App\Support\Seo::keywords([$quizTitle, 'health quiz', 'self assessment', 'wellness quiz', 'Arogio']))
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Quiz',
    'name' => $quizTitle,
    'description' => \App\Support\Seo::cleanText($quizDescription, 160),
    'url' => route('quizzes.show', $quiz->slug),
    'educationalUse' => 'self-assessment',
    'inLanguage' => $isHindi ? 'hi-IN' : 'en-IN',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection



@section('content')

@php

    $questionCount = count($quiz->questions_json ?? []);

@endphp



<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <section class="rounded-[2rem] border border-violet-100/80 bg-gradient-to-br from-white via-violet-50/40 to-cyan-50/60 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900/90 dark:via-slate-900/90 dark:to-slate-950 sm:p-8 lg:p-10">

        <div class="grid gap-8 xl:grid-cols-[1fr_360px]">

            <div>

                <div class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-700 dark:bg-violet-950/40 dark:text-violet-200">

                    {{ $quiz->category ?: ($isHindi ? 'प्रश्नोत्तरी' : 'Quiz') }}

                </div>

                <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-950 dark:text-white">

                    {{ $isHindi ? ($quiz->title_hi ?: $quiz->title_en) : ($quiz->title_en ?: $quiz->title_hi) }}

                </h1>

                <p class="mt-4 max-w-3xl text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">

                    {{ $isHindi ? ($quiz->intro_hi ?: $quiz->intro_en) : ($quiz->intro_en ?: $quiz->intro_hi) }}

                </p>



                <div class="mt-6 flex flex-wrap gap-3">

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">

                        <i data-lucide="sparkles" class="h-4 w-4 text-violet-600 dark:text-violet-300"></i>

                        {{ $isHindi ? 'कोई जल्दी नहीं' : 'No rush' }}

                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">

                        <i data-lucide="list-checks" class="h-4 w-4 text-cyan-600 dark:text-cyan-300"></i>

                        {{ $questionCount }} {{ $isHindi ? 'प्रश्न' : 'questions' }}

                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">

                        <i data-lucide="heart" class="h-4 w-4 text-rose-600 dark:text-rose-300"></i>

                        {{ $isHindi ? 'निर्णय-मुक्त प्रतिबिंब' : 'Judgment-free reflection' }}

                    </span>

                </div>

            </div>



            <aside class="rounded-[1.8rem] border border-white/80 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/90">

                <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-700 dark:text-violet-300">{{ $isHindi ? 'आपकी गति' : 'Your pace' }}</p>

                <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">

                    <div id="quiz-progress-bar" class="h-full w-0 rounded-full bg-gradient-to-r from-violet-500 to-cyan-500 transition-all duration-500"></div>

                </div>

                <div class="mt-4 flex items-center justify-between">

                    <div>

                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $isHindi ? 'प्रगति' : 'Progress' }}</p>

                        <p id="quiz-progress" class="mt-1 text-2xl font-extrabold text-slate-950 dark:text-white">1/{{ $questionCount }}</p>

                    </div>

                    <div class="rounded-2xl bg-violet-50 px-4 py-3 text-right dark:bg-violet-950/20">

                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-600 dark:text-violet-300">{{ $isHindi ? 'स्थिति' : 'Status' }}</p>

                        <p id="quiz-status" class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ $isHindi ? 'शुरू करना' : 'Getting started' }}</p>

                    </div>

                </div>



                <div class="mt-5 rounded-[1.4rem] border border-cyan-200/70 bg-cyan-50/80 p-4 dark:border-cyan-900/40 dark:bg-cyan-950/20">

                    <h2 class="text-sm font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'अगर यह भारी लगने लगे' : 'If it starts to feel heavy' }}</h2>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'रुकना ठीक है. आप श्वास खोल सकते हैं या ध्वनि को शांत कर सकते हैं और एक नरम क्षण के बाद वापस आ सकते हैं।' : 'It is okay to pause. You can open breathing or calm audio and come back after a softer moment.' }}</p>

                    <div class="mt-4 flex flex-col gap-2">

                        <a href="{{ route('activities.breathing') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-600 px-4 py-3 text-sm font-bold text-white">

                            <i data-lucide="wind" class="h-4 w-4"></i>

                            {{ $isHindi ? 'खुली साँस लेना' : 'Open breathing' }}

                        </a>

                        <a href="{{ route('activities.calm-audio') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">

                            <i data-lucide="headphones" class="h-4 w-4"></i>

                            {{ $isHindi ? 'शांत ऑडियो' : 'Calm audio' }}

                        </a>

                    </div>

                </div>

            </aside>

        </div>

    </section>



    <form id="quiz-form" action="{{ route('quizzes.result', $quiz->slug) }}" method="POST" class="mt-8">

        @csrf

        <div class="space-y-6">

            @foreach(($quiz->questions_json ?? []) as $qIndex => $question)

                <section class="quiz-step {{ $qIndex === 0 ? '' : 'hidden' }}" data-step="{{ $qIndex + 1 }}">

                    <div class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 sm:p-8">

                        <div class="flex items-center justify-between gap-3">

                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $isHindi ? 'सवाल' : 'Question' }} {{ $qIndex + 1 }}</span>

                            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $qIndex + 1 }}/{{ $questionCount }}</span>

                        </div>

                        <h2 class="mt-5 text-2xl font-extrabold text-slate-950 dark:text-white">

                            {{ $isHindi ? ($question['question_hi'] ?? $question['question_en']) : ($question['question_en'] ?? $question['question_hi']) }}

                        </h2>

                        <p class="mt-2 text-sm leading-7 text-slate-500 dark:text-slate-400">

                            {{ $isHindi ? 'वह विकल्प चुनें जो निकटतम लगे. आपको सटीक उत्तर की आवश्यकता नहीं है.' : 'Choose the option that feels closest. You do not need a perfect answer.' }}

                        </p>



                        <div class="mt-6 space-y-3">

                            @foreach(($question['options'] ?? []) as $oIndex => $option)

                                <label class="quiz-option flex items-start gap-3 sm:gap-4 rounded-[1.4rem] border border-slate-200 bg-slate-50/60 p-4 cursor-pointer transition hover:border-violet-200 hover:bg-violet-50/60 dark:border-slate-800 dark:bg-slate-950/60 dark:hover:bg-violet-950/20">

                                    <input type="radio" name="answers[{{ $qIndex }}]" value="{{ $oIndex }}" class="sr-only" required>

                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-300 bg-white text-xs font-extrabold text-slate-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ chr(65 + $oIndex) }}</span>

                                    <span class="text-sm leading-6 sm:leading-7 text-slate-700 dark:text-slate-200">{{ $isHindi ? ($option['label_hi'] ?? $option['label_en']) : ($option['label_en'] ?? $option['label_hi']) }}</span>

                                </label>

                            @endforeach

                        </div>



                        <div class="mt-4 rounded-2xl border border-violet-100 bg-violet-50/70 px-4 py-3 text-sm font-medium text-violet-800 dark:border-violet-900/40 dark:bg-violet-950/20 dark:text-violet-200">
                            Choose one option, then move to the next question.
                        </div>

                        <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <button type="button" class="quiz-back inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 {{ $qIndex === 0 ? 'sm:invisible' : '' }}">

                                <i data-lucide="arrow-left" class="h-4 w-4"></i>

                                {{ $isHindi ? 'पीछे' : 'Back' }}

                            </button>



                            @if($qIndex + 1 < $questionCount)

                                <button type="button" class="quiz-next inline-flex items-center justify-center gap-2 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/20 transition hover:bg-violet-700">

                                    {{ $isHindi ? 'अगला सवाल' : 'Next question' }}

                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>

                                </button>

                            @else

                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-violet-500/20 transition hover:bg-violet-700">

                                    {{ $isHindi ? 'मेरा परिणाम देखें' : 'See my result' }}

                                    <i data-lucide="sparkles" class="h-4 w-4"></i>

                                </button>

                            @endif

                        </div>

                    </div>

                </section>

            @endforeach

        </div>

    </form>

</main>



@push('scripts')

<script>

(() => {

    const steps = [...document.querySelectorAll('.quiz-step')];

    const progress = document.getElementById('quiz-progress');

    const progressBar = document.getElementById('quiz-progress-bar');

    const status = document.getElementById('quiz-status');

    const total = steps.length;

    let currentStep = 0;



    function answeredCount() {

        return new Set(

            [...document.querySelectorAll('input[type="radio"]:checked')].map((input) => input.name)

        ).size;

    }



    function updateSidebar() {

        const answered = answeredCount();

        progress.textContent = `${currentStep + 1}/${total}`;

        progressBar.style.width = `${((currentStep + 1) / total) * 100}%`;



        if (answered === 0) {

            status.textContent = @json($isHindi ? 'शुरू करना' : 'Getting started');

        } else if (answered < total) {

            status.textContent = @json($isHindi ? 'अच्छी प्रगति' : 'Nice progress');

        } else {

            status.textContent = @json($isHindi ? 'तैयार' : 'Ready');

        }

    }



    function showStep(index) {

        steps.forEach((step, stepIndex) => {

            step.classList.toggle('hidden', stepIndex !== index);

        });

        currentStep = index;

        updateSidebar();

        window.scrollTo({ top: 0, behavior: 'smooth' });

    }



    function currentAnswered() {

        return !!steps[currentStep].querySelector('input[type="radio"]:checked');

    }



    document.querySelectorAll('.quiz-option').forEach((label) => {

        const input = label.querySelector('input');

        input.addEventListener('change', () => {

            document.querySelectorAll(`input[name="${input.name}"]`).forEach((peer) => {

                peer.closest('.quiz-option').classList.remove('border-violet-400', 'bg-violet-50', 'dark:bg-violet-950/20');

            });



            label.classList.add('border-violet-400', 'bg-violet-50', 'dark:bg-violet-950/20');

            updateSidebar();

        });

    });



    document.querySelectorAll('.quiz-next').forEach((button) => {

        button.addEventListener('click', () => {

            if (!currentAnswered()) {

                status.textContent = @json($isHindi ? 'पहले एक उत्तर चुनें' : 'Choose one answer first');

                return;

            }



            showStep(Math.min(currentStep + 1, total - 1));

        });

    });



    document.querySelectorAll('.quiz-back').forEach((button) => {

        button.addEventListener('click', () => {

            showStep(Math.max(currentStep - 1, 0));

        });

    });



    updateSidebar();

})();

</script>

@endpush

@endsection


