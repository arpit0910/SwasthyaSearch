@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'श्वास अभ्यास' : 'Breathing Exercise') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-teal-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8 text-center">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? 'शांत अभ्यास' : 'Calming Exercise' }}</p>
        <h1 class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'श्वास अभ्यास' : 'Breathing Exercise' }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'यदि इस अभ्यास से चक्कर या असहजता हो, तो रुकें और सामान्य रूप से सांस लें।' : 'If this exercise makes you dizzy or uncomfortable, stop and breathe normally.' }}</p>

        <div class="mt-8 flex justify-center">
            <div id="breathing-circle" class="w-44 h-44 sm:w-56 sm:h-56 rounded-full bg-gradient-to-br from-teal-400 to-cyan-500 text-white flex items-center justify-center text-xl font-bold shadow-2xl transition-all duration-1000">
                <span id="breathing-phase">{{ $locale === 'hi' ? 'तैयार' : 'Ready' }}</span>
            </div>
        </div>

        <div class="mt-5 text-sm text-slate-600 dark:text-slate-300">
            <div id="breathing-instruction">{{ $locale === 'hi' ? 'धीरे से सांस लें' : 'Breathe in slowly' }}</div>
            <div class="mt-2 font-bold text-slate-950 dark:text-white" id="breathing-timer">00:00</div>
        </div>

        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <button id="breathing-start" class="rounded-2xl bg-teal-600 hover:bg-teal-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'शुरू करें' : 'Start' }}</button>
            <button id="breathing-stop" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'रोकें' : 'Stop' }}</button>
            <button id="breathing-reset" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'फिर से शुरू' : 'Restart' }}</button>
        </div>
    </section>
</main>
@push('scripts')
<script>
    (() => {
        const phases = [
            { key: 'inhale', seconds: 4, label: @json($locale === 'hi' ? 'सांस लें' : 'Inhale'), instruction: @json($locale === 'hi' ? 'धीरे से सांस लें' : 'Breathe in slowly'), scale: 'scale-110' },
            { key: 'hold', seconds: 4, label: @json($locale === 'hi' ? 'रोकें' : 'Hold'), instruction: @json($locale === 'hi' ? 'रोकें' : 'Hold'), scale: 'scale-110' },
            { key: 'exhale', seconds: 6, label: @json($locale === 'hi' ? 'सांस छोड़ें' : 'Exhale'), instruction: @json($locale === 'hi' ? 'धीरे से सांस छोड़ें' : 'Breathe out gently'), scale: 'scale-95' },
            { key: 'rest', seconds: 2, label: @json($locale === 'hi' ? 'आराम' : 'Rest'), instruction: @json($locale === 'hi' ? 'आराम' : 'Rest'), scale: 'scale-100' },
        ];

        const circle = document.getElementById('breathing-circle');
        const phaseEl = document.getElementById('breathing-phase');
        const instructionEl = document.getElementById('breathing-instruction');
        const timerEl = document.getElementById('breathing-timer');
        let phaseIndex = 0;
        let phaseRemaining = phases[0].seconds;
        let elapsed = 0;
        let interval = null;

        const render = () => {
            const phase = phases[phaseIndex];
            phaseEl.textContent = phase.label;
            instructionEl.textContent = phase.instruction;
            circle.classList.remove('scale-95', 'scale-100', 'scale-110');
            circle.classList.add(phase.scale);
            const mins = String(Math.floor(elapsed / 60)).padStart(2, '0');
            const secs = String(elapsed % 60).padStart(2, '0');
            timerEl.textContent = `${mins}:${secs}`;
        };

        const tick = () => {
            elapsed += 1;
            phaseRemaining -= 1;
            if (phaseRemaining <= 0) {
                phaseIndex = (phaseIndex + 1) % phases.length;
                phaseRemaining = phases[phaseIndex].seconds;
            }
            render();
        };

        document.getElementById('breathing-start').addEventListener('click', () => {
            if (interval) return;
            render();
            interval = setInterval(tick, 1000);
        });

        document.getElementById('breathing-stop').addEventListener('click', () => {
            clearInterval(interval);
            interval = null;
        });

        document.getElementById('breathing-reset').addEventListener('click', () => {
            clearInterval(interval);
            interval = null;
            phaseIndex = 0;
            phaseRemaining = phases[0].seconds;
            elapsed = 0;
            render();
        });

        render();
    })();
</script>
@endpush
@endsection
