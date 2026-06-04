@extends('layouts.public')

@section('title', ($locale === 'hi' ? '????? ???' : 'Calm Tap Counter') . ' - Arogio')

@section('content')
@php($isHindi = $locale === 'hi')
<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <style>
        .tap-stage {
            position: relative;
            width: min(25rem, 86vw);
            aspect-ratio: 1;
            margin-inline: auto;
        }

        .tap-aura,
        .tap-aura::before,
        .tap-aura::after {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
        }

        .tap-aura {
            background: radial-gradient(circle, rgba(45,212,191,0.22), transparent 60%);
        }

        .tap-aura::before,
        .tap-aura::after {
            content: "";
            inset: 14%;
            border: 1px solid rgba(20,184,166,0.22);
        }

        .tap-aura::after {
            inset: 28%;
            border-style: dashed;
        }

        .tap-button-shell {
            position: absolute;
            inset: 20%;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #14b8a6 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 26px 80px rgba(20,184,166,0.26);
            transition: transform 0.18s ease, box-shadow 0.25s ease;
        }

        .tap-button-shell.is-active {
            transform: scale(0.96);
            box-shadow: 0 16px 46px rgba(8,145,178,0.18);
        }
    </style>

    <section class="rounded-[2rem] border border-teal-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10 text-center">
        <div class="max-w-4xl mx-auto">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-teal-700 dark:text-teal-300">{{ $isHindi ? 'Rhythm Reset' : 'Rhythm Reset' }}</p>
            <h1 class="mt-2 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? '???, ???? ?? rhythm ?? ?? ??? ????' : 'Bring tapping, breath, and rhythm together' }}</h1>
            <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? '?? ?? irritated ?? restless ??, ?? simple rhythm ??? ???? ??? Tap ???? ?? prompt ?? ??? ???? pace ?? soften ???? ????' : 'When your mind feels irritated or restless, a simple rhythm can help. Tap and let the prompts soften your pace.' }}</p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-[1.4rem] bg-teal-50 dark:bg-teal-950/30 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-teal-700 dark:text-teal-300">{{ $isHindi ? 'Count' : 'Count' }}</p>
                <p id="tap-count" class="mt-2 text-3xl font-extrabold text-teal-900 dark:text-teal-100">0</p>
            </div>
            <div class="rounded-[1.4rem] bg-cyan-50 dark:bg-cyan-950/30 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'Pace' : 'Pace' }}</p>
                <p id="tap-rhythm" class="mt-2 text-2xl font-extrabold text-cyan-900 dark:text-cyan-100">{{ $isHindi ? 'Slow' : 'Slow' }}</p>
            </div>
            <div class="rounded-[1.4rem] bg-slate-50 dark:bg-slate-950/40 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'Cue' : 'Cue' }}</p>
                <p id="tap-prompt" class="mt-2 text-sm font-semibold leading-6 text-slate-900 dark:text-white">{{ $isHindi ? '??? ???? ?? ???? exhale ?????' : 'Tap and think of a long exhale' }}</p>
            </div>
        </div>

        <div class="tap-stage mt-8">
            <div class="tap-aura"></div>
            <button id="tap-button" class="tap-button-shell">
                <div>
                    <div class="text-xs uppercase tracking-[0.24em] text-white/80">{{ $isHindi ? 'Calm Tap' : 'Calm Tap' }}</div>
                    <div class="mt-3 text-3xl sm:text-4xl font-extrabold">{{ $isHindi ? 'Tap' : 'Tap' }}</div>
                </div>
            </button>
        </div>

        <div id="tap-status" class="mx-auto mt-6 max-w-2xl rounded-[1.4rem] border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/30 px-4 py-4 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $isHindi ? '??? perfect speed ???? ??? ?? ????? softer pace ???????' : 'There is no perfect speed. Just look for a slightly softer pace.' }}</div>

        <div class="mt-6">
            <button id="tap-reset" class="rounded-[1.15rem] border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? '?????' : 'Reset' }}</button>
        </div>
    </section>
</main>

@push('scripts')
<script>
(() => {
    let count = 0;
    let lastTapAt = null;
    const countEl = document.getElementById('tap-count');
    const rhythmEl = document.getElementById('tap-rhythm');
    const promptEl = document.getElementById('tap-prompt');
    const statusEl = document.getElementById('tap-status');
    const tapButton = document.getElementById('tap-button');
    const isHindi = @json($isHindi);

    tapButton.addEventListener('click', () => {
        const now = Date.now();
        const diff = lastTapAt ? now - lastTapAt : 0;
        lastTapAt = now;
        count += 1;
        countEl.textContent = count;
        tapButton.classList.add('is-active');
        setTimeout(() => tapButton.classList.remove('is-active'), 140);

        if (navigator.vibrate) {
            navigator.vibrate(10);
        }

        if (!diff || diff > 1800) {
            rhythmEl.textContent = isHindi ? 'Slow' : 'Slow';
            promptEl.textContent = isHindi ? '??? ???? ?? ???? exhale ?????' : 'Tap and think of a long exhale';
            statusEl.textContent = isHindi ? '?????? ???? ???? ???? nervous system ?? ??? ???? ???? ???' : 'Good. Starting slowly is easier on the nervous system.';
        } else if (diff > 900) {
            rhythmEl.textContent = isHindi ? 'Steady' : 'Steady';
            promptEl.textContent = isHindi ? '??? rhythm ????' : 'Keep this rhythm';
            statusEl.textContent = isHindi ? '?? pace ????? ??? ?? 2-3 taps ?? shoulders ???? ?????' : 'This pace is good. Relax your shoulders every 2–3 taps.';
        } else {
            rhythmEl.textContent = isHindi ? 'Softer' : 'Softer';
            promptEl.textContent = isHindi ? '?? pace ????? ?? ????' : 'Now soften the pace';
            statusEl.textContent = isHindi ? '??? tapping ??? ?? ??? ??, ?? ???? tap ????? ??? ??? ?????' : 'If the tapping is getting fast, let the next tap arrive a little later.';
        }
    });

    document.getElementById('tap-reset').addEventListener('click', () => {
        count = 0;
        lastTapAt = null;
        countEl.textContent = '0';
        rhythmEl.textContent = isHindi ? 'Slow' : 'Slow';
        promptEl.textContent = isHindi ? '??? ???? ?? ???? exhale ?????' : 'Tap and think of a long exhale';
        statusEl.textContent = isHindi ? '??? perfect speed ???? ??? ?? ????? softer pace ???????' : 'There is no perfect speed. Just look for a slightly softer pace.';
    });
})();
</script>
@endpush
@endsection
