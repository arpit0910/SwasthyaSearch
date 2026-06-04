@extends('layouts.public')

@section('title', ($locale === 'hi' ? '?????????? ??????' : 'Grounding Exercise') . ' - Arogio')

@section('content')
@php
    $isHindi = $locale === 'hi';
    $steps = [
        [
            'count' => '5',
            'short' => $isHindi ? '?????' : 'See',
            'title' => $isHindi ? '5 ?????? ?? ?? ??? ???? ???' : '5 things you can see',
            'hint' => $isHindi ? '???, ?????, ????, ???? — ?? notice ?????' : 'Notice colours, light, corners, or shapes around you.',
            'placeholder' => $isHindi ? '??????: ??????, ???? ????, ??????...' : 'Example: window, blue bottle, chair...',
        ],
        [
            'count' => '4',
            'short' => $isHindi ? '????? ????' : 'Feel',
            'title' => $isHindi ? '4 ?????? ?? ?? ????? ?? ???? ???' : '4 things you can feel',
            'hint' => $isHindi ? '?????? ?? ??????, ?????? ?? ?????, ???, ??? ????? ???' : 'Notice your clothes, the chair, the air, or your feet on the floor.',
            'placeholder' => $isHindi ? '??????: ?????? ???? ??, ??? ????? ?? ???...' : 'Example: cool chair, feet on the floor...',
        ],
        [
            'count' => '3',
            'short' => $isHindi ? '?????' : 'Hear',
            'title' => $isHindi ? '3 ?????? ?? ?? ??? ???? ???' : '3 things you can hear',
            'hint' => $isHindi ? '??? ?? ?? ??? ?? ????? ??????? ??????' : 'Count both nearby and distant sounds.',
            'placeholder' => $isHindi ? '??????: ????, ??? ?? ?????, ???? ?? ?????...' : 'Example: fan, distant traffic, someone talking...',
        ],
        [
            'count' => '2',
            'short' => $isHindi ? '??????' : 'Smell',
            'title' => $isHindi ? '2 ?????? ?? ?? ???? ???? ???' : '2 things you can smell',
            'hint' => $isHindi ? '??? ??? ???? ???? ??, ?? ?? ??? ?? ??? ??? ?????' : 'If nothing is obvious, just notice the smell of the room or air.',
            'placeholder' => $isHindi ? '??????: ???, ?????, ???? ?? ???...' : 'Example: tea, soap, fresh room air...',
        ],
        [
            'count' => '1',
            'short' => $isHindi ? '?????' : 'Taste',
            'title' => $isHindi ? '1 ???? ?? ?? ????? ??? ????? ?? ???? ???' : '1 thing you can taste',
            'hint' => $isHindi ? '???? ??? ??? ?????, ????, ??? ?? ?? neutral taste ?? ??????' : 'Notice any remaining taste in your mouth, water, tea, or even a neutral taste.',
            'placeholder' => $isHindi ? '??????: ????, ??????, ??????? ?????...' : 'Example: water, mint, neutral taste...',
        ],
    ];
@endphp
<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10">
        <div class="grid gap-8 xl:grid-cols-[0.85fr_1.15fr]">
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'Grounding' : 'Grounding' }}</p>
                    <h1 class="mt-3 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? '??-?? step ??? present moment ??? ?????' : 'Return to the present one gentle step at a time' }}</h1>
                    <p class="mt-4 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? '???? ???? ?? ??? ????? ?? ????, ?? version ???? ?? focused step ?? ???? ??? ???? overwhelmed feeling ?? ???? ?? ?? ????? ?????? ???? ?? ???? ???' : 'Instead of showing everything at once, this version keeps you on one focused step at a time. That reduces overwhelm and makes it easier to settle your attention.' }}</p>
                </div>

                <div class="rounded-[1.6rem] border border-cyan-100 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-bold text-cyan-900 dark:text-cyan-100">{{ $isHindi ? 'Progress' : 'Progress' }}</p>
                        <span id="grounding-progress-text" class="text-sm font-semibold text-cyan-800 dark:text-cyan-200">1 / 5</span>
                    </div>
                    <div class="mt-3 h-2 rounded-full bg-white/80 dark:bg-slate-800 overflow-hidden">
                        <div id="grounding-progress-bar" class="h-full w-[20%] rounded-full bg-gradient-to-r from-cyan-500 to-teal-500 transition-all duration-300"></div>
                    </div>
                    <p id="grounding-progress-label" class="mt-4 text-sm font-semibold leading-6 text-cyan-900 dark:text-cyan-100">{{ $isHindi ? '????-???? ??? ?????? ????? ???? ?? ?????? ???? ???' : 'Move slowly. There is no need to rush this.' }}</p>
                </div>

                <div class="grid gap-3">
                    @foreach($steps as $index => $step)
                        <button type="button" data-grounding-step="{{ $index }}" class="grounding-step-btn flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/30 px-4 py-3 text-left transition">
                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white dark:bg-slate-900 text-sm font-extrabold text-cyan-700 dark:text-cyan-300 shadow-sm">{{ $step['count'] }}</span>
                            <span>
                                <span class="block text-sm font-bold text-slate-900 dark:text-white">{{ $step['short'] }}</span>
                                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $step['title'] }}</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                <div class="rounded-[1.8rem] border border-slate-200 dark:border-slate-800 bg-gradient-to-br from-white to-cyan-50/70 dark:from-slate-900 dark:to-slate-950 p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p id="grounding-current-count" class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">5 · See</p>
                            <h2 id="grounding-current-title" class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $steps[0]['title'] }}</h2>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 font-extrabold text-lg shadow-sm" id="grounding-current-badge">{{ $steps[0]['count'] }}</div>
                    </div>

                    <p id="grounding-current-hint" class="mt-4 rounded-2xl bg-cyan-50 dark:bg-cyan-950/30 px-4 py-3 text-sm leading-6 text-cyan-900 dark:text-cyan-100">{{ $steps[0]['hint'] }}</p>

                    <label class="mt-5 block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? '?? notice ?? ??? ??, ??? ?????' : 'Write down what you notice' }}</label>
                    <textarea id="grounding-textarea" class="mt-3 w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-4 text-sm leading-7 text-slate-900 dark:text-slate-100 focus:border-cyan-400 focus:ring-cyan-400" rows="5" placeholder="{{ $steps[0]['placeholder'] }}"></textarea>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <button id="grounding-clear-step" type="button" class="rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? '?? step ?? ??? ????' : 'Clear this step' }}</button>
                        <div class="flex gap-3">
                            <button id="grounding-prev" type="button" class="rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? '????' : 'Back' }}</button>
                            <button id="grounding-next" type="button" class="rounded-[1.1rem] bg-cyan-600 px-5 py-3 text-sm font-bold text-white hover:bg-cyan-700">{{ $isHindi ? '????' : 'Next' }}</button>
                        </div>
                    </div>
                </div>

                <div id="grounding-complete-card" class="hidden rounded-[1.6rem] border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/90 dark:bg-emerald-950/30 p-5">
                    <p class="text-sm font-bold text-emerald-900 dark:text-emerald-100">{{ $isHindi ? '????? ?????' : 'Nicely done.' }}</p>
                    <p class="mt-2 text-sm leading-6 text-emerald-900 dark:text-emerald-100">{{ $isHindi ? '?? ?? ?? ????? ?? ????? ?? ????, ????, ?? jaw ????? ???? ????? ?? ??? ??? ?? ?????' : 'Pause for a moment and notice whether your breath, shoulders, or jaw feel even a little calmer now.' }}</p>
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
(() => {
    const steps = @json($steps);
    const isHindi = @json($isHindi);
    const storageKey = 'grounding-answers-v2';
    const textarea = document.getElementById('grounding-textarea');
    const countEl = document.getElementById('grounding-current-count');
    const titleEl = document.getElementById('grounding-current-title');
    const hintEl = document.getElementById('grounding-current-hint');
    const badgeEl = document.getElementById('grounding-current-badge');
    const progressTextEl = document.getElementById('grounding-progress-text');
    const progressBarEl = document.getElementById('grounding-progress-bar');
    const progressLabelEl = document.getElementById('grounding-progress-label');
    const completeCard = document.getElementById('grounding-complete-card');
    const nextButton = document.getElementById('grounding-next');
    const prevButton = document.getElementById('grounding-prev');
    const clearButton = document.getElementById('grounding-clear-step');
    const stepButtons = [...document.querySelectorAll('[data-grounding-step]')];

    let answers = {};
    let currentStep = 0;

    try {
        answers = JSON.parse(sessionStorage.getItem(storageKey) || '{}');
    } catch (error) {
        answers = {};
    }

    const saveAnswers = () => {
        sessionStorage.setItem(storageKey, JSON.stringify(answers));
    };

    const completionCount = () => steps.filter((_, index) => (answers[index] || '').trim().length > 0).length;

    const updateProgress = () => {
        const completed = completionCount();
        progressTextEl.textContent = `${currentStep + 1} / ${steps.length}`;
        progressBarEl.style.width = `${((currentStep + 1) / steps.length) * 100}%`;
        progressLabelEl.textContent = completed === 0
            ? (isHindi ? '????-???? ??? ?????? ????? ???? ?? ?????? ???? ???' : 'Move slowly. There is no need to rush this.')
            : completed === steps.length
                ? (isHindi ? '??? steps ???? ?? ??? ?? ???? ??? ????? softness notice ?????' : 'All steps are done. Now notice any softness in your body.')
                : (isHindi ? '?? focused step ?? ???? ?? grounding ?? ?????? ???' : 'Staying with one focused step is part of grounding itself.');
        completeCard.classList.toggle('hidden', completed !== steps.length);
    };

    const updateStepButtons = () => {
        stepButtons.forEach((button, index) => {
            button.classList.toggle('border-cyan-400', index === currentStep);
            button.classList.toggle('bg-cyan-50', index === currentStep);
            button.classList.toggle('dark:bg-cyan-950/30', index === currentStep);
        });
    };

    const render = () => {
        const step = steps[currentStep];
        countEl.textContent = `${step.count} · ${step.short}`;
        titleEl.textContent = step.title;
        hintEl.textContent = step.hint;
        badgeEl.textContent = step.count;
        textarea.placeholder = step.placeholder;
        textarea.value = answers[currentStep] || '';
        prevButton.disabled = currentStep === 0;
        prevButton.classList.toggle('opacity-50', currentStep === 0);
        nextButton.textContent = currentStep === steps.length - 1
            ? (isHindi ? '???? ????' : 'Finish')
            : (isHindi ? '????' : 'Next');
        updateStepButtons();
        updateProgress();
    };

    textarea.addEventListener('input', () => {
        answers[currentStep] = textarea.value;
        saveAnswers();
        updateProgress();
    });

    nextButton.addEventListener('click', () => {
        answers[currentStep] = textarea.value;
        saveAnswers();
        if (currentStep < steps.length - 1) {
            currentStep += 1;
            render();
        } else {
            updateProgress();
            completeCard.classList.remove('hidden');
        }
    });

    prevButton.addEventListener('click', () => {
        answers[currentStep] = textarea.value;
        saveAnswers();
        if (currentStep > 0) {
            currentStep -= 1;
            render();
        }
    });

    clearButton.addEventListener('click', () => {
        answers[currentStep] = '';
        textarea.value = '';
        saveAnswers();
        updateProgress();
    });

    stepButtons.forEach((button) => {
        button.addEventListener('click', () => {
            answers[currentStep] = textarea.value;
            saveAnswers();
            currentStep = Number(button.dataset.groundingStep || 0);
            render();
        });
    });

    render();
})();
</script>
@endpush
@endsection
