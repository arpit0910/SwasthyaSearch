ï»¿@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'साँस लेने का व्यायाम' : 'Breathing Exercise') . ' - Arogio')





@section('content')


@php


    $isHindi = $locale === 'hi';


@endphp


<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">


    <style>


        .breath-shell {


            position: relative;


            overflow: hidden;


            background:


                radial-gradient(circle at 20% 20%, rgba(45, 212, 191, 0.18), transparent 30%),


                radial-gradient(circle at 80% 25%, rgba(96, 165, 250, 0.16), transparent 28%),


                linear-gradient(135deg, rgba(255,255,255,0.98), rgba(240,249,255,0.96));


        }





        .dark .breath-shell {


            background:


                radial-gradient(circle at 20% 20%, rgba(45, 212, 191, 0.14), transparent 30%),


                radial-gradient(circle at 80% 25%, rgba(96, 165, 250, 0.12), transparent 28%),


                linear-gradient(135deg, rgba(15,23,42,0.97), rgba(2,132,199,0.08));


        }





        .breath-stage-wrap {


            position: relative;


            width: min(29rem, 86vw);


            aspect-ratio: 1;


            margin-inline: auto;


        }





        .breath-progress-ring {


            position: absolute;


            inset: 0;


            border-radius: 9999px;


            background: conic-gradient(from -90deg, rgba(20,184,166,0.92) calc(var(--progress, 0) * 1%), rgba(226,232,240,0.65) 0);


            padding: 14px;


            box-shadow: 0 24px 70px rgba(20, 184, 166, 0.18);


        }





        .dark .breath-progress-ring {


            background: conic-gradient(from -90deg, rgba(45,212,191,0.9) calc(var(--progress, 0) * 1%), rgba(30,41,59,0.9) 0);


            box-shadow: 0 24px 70px rgba(8, 145, 178, 0.16);


        }





        .breath-progress-ring::before {


            content: "";


            position: absolute;


            inset: 14px;


            border-radius: inherit;


            background: rgba(255,255,255,0.62);


            backdrop-filter: blur(10px);


        }





        .dark .breath-progress-ring::before {


            background: rgba(2, 6, 23, 0.72);


        }





        .breath-stage {


            position: absolute;


            inset: 40px;


            border-radius: 9999px;


            background:


                radial-gradient(circle at 34% 28%, rgba(255,255,255,0.14), rgba(255,255,255,0.03) 18%, transparent 38%),


                linear-gradient(145deg, #14b8a6 0%, #0f766e 42%, #0369a1 100%);


            display: flex;


            align-items: center;


            justify-content: center;


            text-align: center;


            color: white;


            overflow: hidden;


            transition: transform 1.2s ease, box-shadow 0.9s ease, filter 0.9s ease;


            box-shadow: 0 24px 70px rgba(15, 118, 110, 0.28);


        }





        .breath-stage::before,


        .breath-stage::after {


            content: "";


            position: absolute;


            border-radius: 9999px;


            border: 1px solid rgba(255,255,255,0.22);


            inset: 10%;


        }





        .breath-stage::after {


            inset: 21%;


            border-style: dashed;


            opacity: 0.65;


        }





        .breath-stage.is-inhale {


            transform: scale(1.04);


            box-shadow: 0 34px 90px rgba(34, 211, 238, 0.28);


        }





        .breath-stage.is-hold {


            transform: scale(1.07);


            filter: saturate(1.08);


            box-shadow: 0 34px 90px rgba(59, 130, 246, 0.24);


        }





        .breath-stage.is-exhale {


            transform: scale(0.92);


            box-shadow: 0 22px 55px rgba(14, 116, 144, 0.18);


        }





        .breath-orbit,


        .breath-orbit-inner {


            position: absolute;


            inset: 0;


            border-radius: 9999px;


        }





        .breath-orbit {


            animation: breathSpin 18s linear infinite;


        }





        .breath-orbit-inner {


            animation: breathSpinReverse 14s linear infinite;


        }





        .breath-dot {


            position: absolute;


            width: 10px;


            height: 10px;


            border-radius: 9999px;


            background: rgba(255,255,255,0.96);


            box-shadow: 0 0 24px rgba(255,255,255,0.45);


            transition: transform 1s ease, opacity 0.7s ease;


        }





        .breath-wave {


            position: absolute;


            width: 54%;


            height: 54%;


            border-radius: 9999px;


            background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 68%);


            animation: breathPulse 5s ease-in-out infinite;


        }





        .breath-center-copy {


            position: relative;


            max-width: 16.5rem;


            margin-inline: auto;


            padding: 1.3rem 1.15rem;


            border-radius: 1.5rem;


            background: linear-gradient(180deg, rgba(2, 6, 23, 0.34), rgba(15, 23, 42, 0.46));


            backdrop-filter: blur(12px);


            box-shadow: inset 0 1px 0 rgba(255,255,255,0.12), 0 16px 34px rgba(2, 6, 23, 0.22);


        }





        .breath-center-copy::before {


            content: "";


            position: absolute;


            inset: 0;


            border-radius: inherit;


            border: 1px solid rgba(255,255,255,0.16);


            pointer-events: none;


        }





        .breath-center-text {


            color: rgba(255,255,255,0.99);


            text-shadow: 0 3px 24px rgba(2, 6, 23, 0.5);


        }








        .breath-panel {


            background: rgba(255,255,255,0.72);


            backdrop-filter: blur(12px);


        }





        .dark .breath-panel {


            background: rgba(15, 23, 42, 0.7);


        }





        @keyframes breathSpin {


            from { transform: rotate(0deg); }


            to { transform: rotate(360deg); }


        }





        @keyframes breathSpinReverse {


            from { transform: rotate(360deg); }


            to { transform: rotate(0deg); }


        }





        @keyframes breathPulse {


            0%, 100% { transform: scale(0.9); opacity: 0.45; }


            50% { transform: scale(1.12); opacity: 0.95; }


        }





        @media (prefers-reduced-motion: reduce) {


            .breath-orbit,


            .breath-orbit-inner,


            .breath-wave {


                animation: none !important;


            }


        }


    </style>





    <section class="breath-shell rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 shadow-sm p-5 sm:p-8 lg:p-10">


        <div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr] xl:items-center">


            <div class="space-y-6">


                <div>


                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-700 dark:text-teal-300">{{ $isHindi ? 'शांत श्वास' : 'Calm Breathing' }}</p>


                    <h1 class="mt-3 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? 'एक निर्देशित श्वास सत्र जो वास्तव में शांत महसूस कराता है' : 'A guided breathing session that actually feels calming' }}</h1>


                    <p class="mt-4 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'बस प्रारंभ दबाएँ. स्क्रीन, गति और आवाज के संकेत धीरे-धीरे आपको कम अव्यवस्था और कम दबाव के साथ सांस लेने, पकड़ने और छोड़ने में मार्गदर्शन करते हैं।' : 'Just press start. The screen, motion, and voice cues then gently guide you through inhale, hold, and exhale with less clutter and less pressure.' }}</p>


                </div>





                <div class="grid gap-3 sm:grid-cols-3">


                    <div class="breath-panel rounded-[1.5rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-teal-700 dark:text-teal-300">{{ $isHindi ? 'साँस' : 'Inhale' }}</p>


                        <p class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">4s</p>


                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $isHindi ? 'नाक से धीरे-धीरे सांस अंदर लें' : 'Slow breath in through the nose' }}</p>


                    </div>


                    <div class="breath-panel rounded-[1.5rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-700 dark:text-sky-300">{{ $isHindi ? 'पकड़ना' : 'Hold' }}</p>


                        <p class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">4s</p>


                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $isHindi ? 'शरीर को मुलायम रखें' : 'Keep the body soft' }}</p>


                    </div>


                    <div class="breath-panel rounded-[1.5rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'साँस छोड़ें' : 'Exhale' }}</p>


                        <p class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">6s</p>


                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $isHindi ? 'लंबी, धीमी रिलीज' : 'Long, slow release' }}</p>


                    </div>


                </div>





                <div class="breath-panel rounded-[1.6rem] border border-white/80 dark:border-slate-700/80 p-5">


                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">


                        <div>


                            <p class="text-sm font-bold text-slate-950 dark:text-white">{{ $isHindi ? 'सत्र नोट्स' : 'Session notes' }}</p>


                            <p id="breathing-support-copy" class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'यदि आपको चक्कर आ रहा है, घबराहट हो रही है, या असहजता महसूस हो रही है, तो अपनी सामान्य श्वास पर लौट आएं।' : 'If you feel dizzy, panicky, or uncomfortable, return to your normal breathing.' }}</p>


                        </div>


                        <div class="flex flex-wrap gap-3">


                            <button id="breathing-start" class="inline-flex items-center justify-center rounded-[1.25rem] bg-teal-600 px-7 py-4 text-base font-extrabold text-white shadow-lg shadow-teal-600/20 transition hover:-translate-y-0.5 hover:bg-teal-700">{{ $isHindi ? 'शुरू करें' : 'Start now' }}</button>


                            <button id="breathing-voice" class="rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? 'आवाज चालू' : 'Voice On' }}</button>


                            <button id="breathing-restart" class="hidden rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? 'पुनः आरंभ करें' : 'Restart' }}</button>


                        </div>


                    </div>


                </div>


            </div>





            <div class="space-y-5">


                <div class="breath-stage-wrap">


                    <div id="breathing-progress-ring" class="breath-progress-ring" style="--progress: 0"></div>


                    <div id="breathing-stage" class="breath-stage">


                        <div class="breath-orbit" id="breathing-orbit"></div>


                        <div class="breath-orbit-inner" id="breathing-orbit-inner"></div>


                        <div class="breath-wave"></div>


                        <div class="relative z-10 px-8">


                            <div class="breath-center-copy">


                                <div id="breathing-phase-chip" class="breath-center-text inline-flex rounded-full bg-slate-950/26 px-4 py-2 text-xs font-bold uppercase tracking-[0.24em]">{{ $isHindi ? 'तैयार' : 'Ready' }}</div>


                                <div id="breathing-phase" class="breath-center-text mt-4 text-4xl sm:text-5xl font-extrabold">{{ $isHindi ? 'शुरू' : 'Start' }}</div>


                                <div id="breathing-instruction" class="breath-center-text mt-3 text-sm sm:text-base font-medium leading-7">{{ $isHindi ? 'एक शांत, निर्देशित श्वास चक्र के लिए प्रारंभ दबाएँ।' : 'Press start for one calm, guided breathing loop.' }}</div>


                            </div>


                            <div class="mt-6 flex items-center justify-center gap-3">


                                <div class="rounded-full bg-slate-950/28 px-4 py-2 text-sm font-bold text-white shadow-sm"><span id="breathing-countdown">4</span>s</div>


                                <div id="breathing-cycle" class="rounded-full bg-slate-950/28 px-4 py-2 text-sm font-bold text-white shadow-sm">{{ $isHindi ? 'राउंड 0/5' : 'Round 0/5' }}</div>


                            </div>


                        </div>


                    </div>


                </div>





                <div class="grid gap-4 sm:grid-cols-3">


                    <div class="breath-panel rounded-[1.4rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'अब' : 'Now' }}</p>


                        <p id="breathing-status" class="mt-2 text-sm font-semibold leading-6 text-slate-900 dark:text-slate-100">{{ $isHindi ? 'जब आप तैयार महसूस करें तो स्टार्ट दबाएँ।' : 'Press start when you feel ready.' }}</p>


                    </div>


                    <div class="breath-panel rounded-[1.4rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'बीत गया' : 'Elapsed' }}</p>


                        <p id="breathing-timer" class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">00:00</p>


                    </div>


                    <div class="breath-panel rounded-[1.4rem] border border-white/80 dark:border-slate-700/80 p-4">


                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'फ़ायदा' : 'Benefit' }}</p>


                        <p id="breathing-benefit" class="mt-2 text-sm font-semibold leading-6 text-slate-900 dark:text-slate-100">{{ $isHindi ? 'लंबी सांस छोड़ने से तंत्रिका तंत्र को नरम करने में मदद मिलती है।' : 'A longer exhale helps soften the nervous system.' }}</p>


                    </div>


                </div>


            </div>


        </div>


    </section>


</main>





@push('scripts')


<script>


(() => {


    const isHindi = @json($isHindi);


    const totalRounds = 5;


    const phases = [


        {


            key: 'inhale',


            seconds: 4,


            label: isHindi ? 'साँस' : 'Inhale',


            chip: isHindi ? 'साँस' : 'Inhale',


            instruction: isHindi ? 'अपनी नाक से धीरे-धीरे सांस लें।' : 'Breathe in slowly through your nose.',


            status: isHindi ? 'धीरे से सांस भरें. अपने कंधों को मुलायम रखें.' : 'Fill the breath gently. Keep your shoulders soft.',


            benefit: isHindi ? 'इनहेल आपको ज़मीनी और वर्तमान महसूस करने में मदद करता है।' : 'Inhale helps you feel grounded and present.',


            scale: 1.04,


        },


        {


            key: 'hold',


            seconds: 4,


            label: isHindi ? 'पकड़ना' : 'Hold',


            chip: isHindi ? 'पकड़ना' : 'Hold',


            instruction: isHindi ? 'शरीर को ज्यादा टाइट किए बिना सांस को रोककर रखें।' : 'Hold the breath without tightening the body.',


            status: isHindi ? 'विराम। अपने जबड़े और माथे को आराम दें।' : 'Pause. Relax your jaw and forehead.',


            benefit: isHindi ? 'एक छोटी सी पकड़ आपके ध्यान को व्यवस्थित करने में मदद करती है।' : 'A short hold helps your attention settle.',


            scale: 1.08,


        },


        {


            key: 'exhale',


            seconds: 6,


            label: isHindi ? 'साँस छोड़ें' : 'Exhale',


            chip: isHindi ? 'साँस छोड़ें' : 'Exhale',


            instruction: isHindi ? 'सांस को धीरे-धीरे और पूरी तरह बाहर छोड़ें।' : 'Let the breath out slowly and completely.',


            status: isHindi ? 'धीरे-धीरे छोड़ें. यह वह हिस्सा है जो शरीर को सबसे अधिक शांत करता है।' : 'Release slowly. This is the part that calms the body most.',


            benefit: isHindi ? 'लंबी सांस छोड़ने से तनाव प्रतिक्रिया धीमी हो सकती है।' : 'A longer exhale can slow the stress response.',


            scale: 0.92,


        },


    ];





    const texts = {


        readyPhase: isHindi ? 'शुरू' : 'Start',


        readyChip: 'Ready',


        readyInstruction: isHindi ? 'एक शांत, निर्देशित श्वास चक्र के लिए प्रारंभ दबाएँ।' : 'Press start for one calm, guided breathing loop.',


        readyStatus: isHindi ? 'जब आप तैयार महसूस करें तो स्टार्ट दबाएँ।' : 'Press start when you feel ready.',


        readyBenefit: isHindi ? 'लंबी सांस छोड़ने से तंत्रिका तंत्र को नरम करने में मदद मिलती है।' : 'A longer exhale helps soften the nervous system.',


        completePhase: isHindi ? 'बहुत अच्छा' : 'Well done',


        completeInstruction: isHindi ? 'सत्र पूरा हो गया है. अपनी सामान्य सांस पर लौटें और देखें कि क्या आपका शरीर थोड़ा भी हल्का महसूस करता है।' : 'The session is complete. Return to your normal breath and notice whether your body feels even a little lighter.',


        completeStatus: isHindi ? 'आपने 5 निर्देशित राउंड पूरे कर लिए हैं।' : 'You completed 5 guided rounds.',


        completeBenefit: isHindi ? 'पूरे दिन दोहराए जाने पर छोटे-छोटे शांत विराम सबसे अधिक मदद करते हैं।' : 'Small calm breaks help most when repeated through the day.',


        voiceOn: 'Voice On',


        voiceOff: 'Voice Off',


        supportDefault: isHindi ? 'यदि आपको चक्कर आ रहा है, घबराहट हो रही है, या असहजता महसूस हो रही है, तो अपनी सामान्य श्वास पर लौट आएं।' : 'If you feel dizzy, panicky, or uncomfortable, return to your normal breathing.',


        supportComplete: isHindi ? 'यदि आप चाहें, तो आप एक और दौर कर सकते हैं - बिना दबाव के।' : 'If you want, you can do another round — without pressure.',


        roundLabel: 'Round',


    };





    const stage = document.getElementById('breathing-stage');


    const ring = document.getElementById('breathing-progress-ring');


    const orbit = document.getElementById('breathing-orbit');


    const orbitInner = document.getElementById('breathing-orbit-inner');


    const startButton = document.getElementById('breathing-start');


    const restartButton = document.getElementById('breathing-restart');


    const voiceButton = document.getElementById('breathing-voice');


    const phaseEl = document.getElementById('breathing-phase');


    const chipEl = document.getElementById('breathing-phase-chip');


    const instructionEl = document.getElementById('breathing-instruction');


    const countdownEl = document.getElementById('breathing-countdown');


    const cycleEl = document.getElementById('breathing-cycle');


    const statusEl = document.getElementById('breathing-status');


    const timerEl = document.getElementById('breathing-timer');


    const benefitEl = document.getElementById('breathing-benefit');


    const supportCopyEl = document.getElementById('breathing-support-copy');





    let voiceEnabled = true;


    let interval = null;


    let phaseIndex = 0;


    let phaseRemaining = phases[0].seconds;


    let round = 0;


    let elapsed = 0;


    let phaseElapsed = 0;


    const speechReady = 'speechSynthesis' in window;





    const makeDots = (container, count, radiusBase) => {


        Array.from({ length: count }).forEach((_, index) => {


            const dot = document.createElement('span');


            dot.className = 'breath-dot';


            dot.dataset.index = index;


            dot.dataset.radiusBase = radiusBase;


            container.appendChild(dot);


        });


    };





    makeDots(orbit, 10, 132);


    makeDots(orbitInner, 6, 94);





    const positionDots = (phaseKey) => {


        document.querySelectorAll('.breath-dot').forEach((dot) => {


            const index = Number(dot.dataset.index || 0);


            const total = dot.parentElement === orbit ? 10 : 6;


            const base = Number(dot.dataset.radiusBase || 100);


            const angle = (Math.PI * 2 * index) / total;


            let radius = base;


            let opacity = 0.5;





            if (phaseKey === 'inhale') {


                radius = base + 10;


                opacity = 0.95;


            } else if (phaseKey === 'hold') {


                radius = base + 16;


                opacity = 0.78;


            } else if (phaseKey === 'exhale') {


                radius = base - 22;


                opacity = 0.34;


            }





            const x = Math.cos(angle) * radius;


            const y = Math.sin(angle) * radius;


            dot.style.left = '50%';


            dot.style.top = '50%';


            dot.style.opacity = opacity;


            dot.style.transform = `translate(${x}px, ${y}px)`;


        });


    };





    const setProgress = (currentPhase) => {


        const phase = phases[currentPhase];


        const ratio = phase.seconds ? (phaseElapsed / phase.seconds) : 0;


        const totalProgress = (((round * phases.length) + currentPhase + ratio) / (totalRounds * phases.length)) * 100;


        ring.style.setProperty('--progress', totalProgress.toFixed(2));


    };





    const updateStageState = (phase) => {


        stage.classList.remove('is-inhale', 'is-hold', 'is-exhale');


        stage.classList.add(`is-${phase.key}`);


        stage.style.transform = `scale(${phase.scale})`;


        positionDots(phase.key);


    };





    const speak = (text) => {


        if (!voiceEnabled || !speechReady) return;


        window.speechSynthesis.cancel();


        const utterance = new SpeechSynthesisUtterance(text);


        utterance.lang = isHindi ? 'एन-IN' : 'en-IN';


        utterance.rate = 0.92;


        utterance.pitch = 1;


        window.speechSynthesis.speak(utterance);


    };





    const renderPhase = () => {


        const phase = phases[phaseIndex];


        phaseEl.textContent = phase.label;


        chipEl.textContent = phase.chip;


        instructionEl.textContent = phase.instruction;


        statusEl.textContent = phase.status;


        benefitEl.textContent = phase.benefit;


        countdownEl.textContent = Math.max(phaseRemaining, 0);


        cycleEl.textContent = `${texts.roundLabel} ${Math.min(round + 1, totalRounds)}/${totalRounds}`;


        updateStageState(phase);


        setProgress(phaseIndex);


        const mins = String(Math.floor(elapsed / 60)).padStart(2, '0');


        const secs = String(elapsed % 60).padStart(2, '0');


        timerEl.textContent = `${mins}:${secs}`;


    };





    const finishSession = () => {


        clearInterval(interval);


        interval = null;


        if (speechReady) {


            window.speechSynthesis.cancel();


        }


        ring.style.setProperty('--progress', '100');


        stage.classList.remove('is-inhale', 'is-hold', 'is-exhale');


        stage.style.transform = 'scale(1)';


        phaseEl.textContent = texts.completePhase;


        chipEl.textContent = 'Complete';


        instructionEl.textContent = texts.completeInstruction;


        statusEl.textContent = texts.completeStatus;


        benefitEl.textContent = texts.completeBenefit;


        supportCopyEl.textContent = texts.supportComplete;


        countdownEl.textContent = '0';


        cycleEl.textContent = `${texts.roundLabel} ${totalRounds}/${totalRounds}`;


        startButton.classList.add('hidden');


        restartButton.classList.remove('hidden');


        speak(texts.completePhase);


    };





    const advancePhase = () => {


        phaseIndex += 1;


        phaseElapsed = 0;





        if (phaseIndex >= phases.length) {


            phaseIndex = 0;


            round += 1;


        }





        if (round >= totalRounds) {


            finishSession();


            return;


        }





        phaseRemaining = phases[phaseIndex].seconds;


        renderPhase();


        speak(phases[phaseIndex].label);


    };





    const tick = () => {


        elapsed += 1;


        phaseElapsed += 1;


        phaseRemaining -= 1;


        renderPhase();


        if (phaseRemaining <= 0) {


            advancePhase();


        }


    };





    const resetSession = () => {


        clearInterval(interval);


        interval = null;


        if (speechReady) {


            window.speechSynthesis.cancel();


        }


        phaseIndex = 0;


        phaseRemaining = phases[0].seconds;


        phaseElapsed = 0;


        round = 0;


        elapsed = 0;


        ring.style.setProperty('--progress', '0');


        phaseEl.textContent = texts.readyPhase;


        chipEl.textContent = texts.readyChip;


        instructionEl.textContent = texts.readyInstruction;


        statusEl.textContent = texts.readyStatus;


        benefitEl.textContent = texts.readyBenefit;


        supportCopyEl.textContent = texts.supportDefault;


        countdownEl.textContent = phases[0].seconds;


        cycleEl.textContent = `${texts.roundLabel} 0/${totalRounds}`;


        timerEl.textContent = '00:00';


        stage.classList.remove('is-inhale', 'is-hold', 'is-exhale');


        stage.style.transform = 'scale(1)';


        positionDots('exhale');


        startButton.classList.remove('hidden');


        restartButton.classList.add('hidden');


    };





    startButton.addEventListener('click', () => {


        if (interval) return;


        supportCopyEl.textContent = texts.supportDefault;


        startButton.classList.add('hidden');


        restartButton.classList.remove('hidden');


        phaseIndex = 0;


        phaseRemaining = phases[0].seconds;


        phaseElapsed = 0;


        round = 0;


        elapsed = 0;


        renderPhase();


        speak(phases[0].label);


        interval = setInterval(tick, 1000);


    });





    restartButton.addEventListener('click', resetSession);





    voiceButton.addEventListener('click', () => {


        voiceEnabled = !voiceEnabled;


        voiceButton.textContent = voiceEnabled ? texts.voiceOn : texts.voiceOff;


        if (!voiceEnabled && speechReady) {


            window.speechSynthesis.cancel();


        }


    });





    voiceButton.textContent = texts.voiceOn;


    resetSession();


})();


</script>


@endpush


@endsection


