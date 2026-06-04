@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'कैल्म ऑडियो स्पेस' : 'Calm Audio Space') . ' - Arogio')

@section('content')
@php
    $isHindi = $locale === 'hi';
@endphp

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="relative overflow-hidden rounded-[2rem] border border-emerald-100/80 bg-gradient-to-br from-white via-emerald-50/70 to-cyan-50/70 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:p-8 lg:p-10">
        <div class="pointer-events-none absolute -right-16 top-0 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-cyan-300/20 blur-3xl"></div>

        <div class="relative grid gap-8 xl:grid-cols-[1.05fr_0.95fr] xl:items-center">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200/80 bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.24em] text-emerald-700 shadow-sm dark:border-emerald-900/50 dark:bg-slate-950/80 dark:text-emerald-300">
                    <i data-lucide="headphones" class="h-4 w-4"></i>
                    {{ $isHindi ? 'ऑडियो कम्फर्ट' : 'Audio Comfort' }}
                </div>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-5xl">
                    {{ $isHindi ? 'फ्री शांत संगीत, सुकून भरी आवाज़ें और छोटे सहायक विचार' : 'Free calming sounds, gentle voices, and short uplifting thoughts' }}
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300 sm:text-lg">
                    {{ $isHindi ? 'जब मन भारी लगे, यहाँ एक शांत जगह मिले। हल्की ambient sound, guided voice cues, grounding reminders और छोटे wisdom playlists के साथ थोड़ी राहत लें।' : 'When the mind feels heavy, this gives you a softer place to land. Mix ambient sounds, guided voice cues, grounding reminders, and short wisdom playlists for a gentler reset.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button" data-play-all class="inline-flex items-center justify-center gap-2 rounded-[1.25rem] bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-700">
                        <i data-lucide="play" class="h-4 w-4"></i>
                        {{ $isHindi ? 'शांत सत्र शुरू करें' : 'Start calm session' }}
                    </button>
                    <button type="button" data-stop-all class="inline-flex items-center justify-center gap-2 rounded-[1.25rem] border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-900 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                        <i data-lucide="square" class="h-4 w-4"></i>
                        {{ $isHindi ? 'रोकें' : 'Stop' }}
                    </button>
                </div>
                <div class="mt-6 flex flex-wrap gap-3 text-sm">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="music-4" class="h-4 w-4 text-emerald-600 dark:text-emerald-300"></i>
                        {{ $isHindi ? 'फ्री ब्राउज़र-बेस्ड साउंड' : 'Free browser-based sound' }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="mic-2" class="h-4 w-4 text-cyan-600 dark:text-cyan-300"></i>
                        {{ $isHindi ? 'गाइडेड आवाज़ें' : 'Guided voice cues' }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="timer-reset" class="h-4 w-4 text-indigo-600 dark:text-indigo-300"></i>
                        {{ $isHindi ? '5, 10, 20 मिनट टाइमर' : '5, 10, 20 minute timer' }}
                    </span>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/80 bg-white/85 p-5 shadow-xl backdrop-blur dark:border-slate-800 dark:bg-slate-950/80 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">{{ $isHindi ? 'अभी चल रहा है' : 'Now playing' }}</p>
                        <h2 id="session-title" class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'शांत शुरुआत' : 'Calm start' }}</h2>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">
                        <span id="timer-status">05:00</span>
                    </div>
                </div>

                <div class="mt-6 relative flex h-64 items-center justify-center overflow-hidden rounded-[1.75rem] bg-[radial-gradient(circle_at_center,rgba(16,185,129,0.18),rgba(255,255,255,0.92)_45%,rgba(236,253,245,0.9)_100%)] dark:bg-[radial-gradient(circle_at_center,rgba(16,185,129,0.24),rgba(15,23,42,0.98)_48%,rgba(2,6,23,0.98)_100%)]">
                    <div class="absolute inset-0" id="calm-wave-field"></div>
                    <div class="relative z-10 flex h-40 w-40 items-center justify-center rounded-full border border-white/70 bg-white/70 shadow-2xl shadow-emerald-400/10 backdrop-blur dark:border-white/10 dark:bg-slate-900/70">
                        <div class="text-center">
                            <div id="voice-phase" class="text-xs font-bold uppercase tracking-[0.3em] text-emerald-700 dark:text-emerald-300">{{ $isHindi ? 'सुनें' : 'Listen' }}</div>
                            <div id="voice-caption" class="mt-3 text-xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'एक कोमल विराम लें' : 'Take a gentle pause' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @foreach([
                        ['label' => $isHindi ? 'साउंड' : 'Sound', 'value' => $isHindi ? 'रेन + ड्रोन' : 'Rain + Drone'],
                        ['label' => $isHindi ? 'वॉइस' : 'Voice', 'value' => $isHindi ? 'सुकून भरे विचार' : 'Gentle thoughts'],
                        ['label' => $isHindi ? 'मोड' : 'Mode', 'value' => $isHindi ? 'कम उत्तेजना' : 'Low stimulation'],
                    ] as $stat)
                        <div class="rounded-2xl border border-slate-200/80 bg-white/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">{{ $stat['label'] }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'अपना मिश्रण बनाएँ' : 'Build your mix' }}</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'वातावरण, आवाज़ और टाइमर चुनें' : 'Choose your ambient layer, voice, and timer' }}</h2>
                </div>
                <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                    <input id="voice-enabled" type="checkbox" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    {{ $isHindi ? 'आवाज़ चालू' : 'Voice on' }}
                </label>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach([
                    ['id' => 'rain', 'title' => $isHindi ? 'रेन हश' : 'Rain Hush', 'description' => $isHindi ? 'लगातार बारिश जैसी मुलायम शोर परत।' : 'A soft, steady rain-like wash for overstimulated moments.', 'icon' => 'cloud-rain'],
                    ['id' => 'drone', 'title' => $isHindi ? 'डीप ड्रोन' : 'Deep Drone', 'description' => $isHindi ? 'धीमा warm tone जो शरीर को settle करने में मदद करे।' : 'A slow warm tone bed that can help the body feel more settled.', 'icon' => 'waves'],
                    ['id' => 'chimes', 'title' => $isHindi ? 'सॉफ्ट चाइम्स' : 'Soft Chimes', 'description' => $isHindi ? 'हल्की अंतराल वाली चमकती टोन।' : 'Gentle periodic chimes for light uplift without rush.', 'icon' => 'bell-ring'],
                    ['id' => 'brown', 'title' => $isHindi ? 'ब्राउन नॉइज़' : 'Brown Noise', 'description' => $isHindi ? 'लो-फ्रिक्वेंसी mask जो बाहरी अव्यवस्था कम करे।' : 'Low-frequency masking sound for noisy surroundings.', 'icon' => 'audio-lines'],
                ] as $scene)
                    <button type="button" class="sound-card text-left rounded-[1.6rem] border border-slate-200/80 bg-slate-50/70 p-5 transition hover:-translate-y-0.5 hover:border-emerald-200 dark:border-slate-800 dark:bg-slate-950/70" data-sound-card="{{ $scene['id'] }}">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">
                                <i data-lucide="{{ $scene['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ $scene['title'] }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $scene['description'] }}</p>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-[1fr_0.9fr]">
                <div class="rounded-[1.6rem] border border-slate-200/80 bg-white p-5 dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-center justify-between gap-3">
                        <label for="master-volume" class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'वॉल्यूम' : 'Volume' }}</label>
                        <span id="volume-value" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">60%</span>
                    </div>
                    <input id="master-volume" type="range" min="0" max="100" value="60" class="mt-4 w-full accent-emerald-600">

                    <div class="mt-5 flex flex-wrap gap-3">
                        @foreach([5, 10, 20] as $minutes)
                            <button type="button" class="timer-chip inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-emerald-200 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200" data-minutes="{{ $minutes }}">
                                {{ $minutes }} {{ $isHindi ? 'मिनट' : 'min' }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[1.6rem] border border-cyan-200/80 bg-gradient-to-br from-cyan-50 to-emerald-50 p-5 dark:border-cyan-900/40 dark:from-cyan-950/20 dark:to-emerald-950/20">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'जल्दी मदद' : 'Quick help' }}</p>
                    <h3 class="mt-2 text-lg font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'बहुत बेचैनी लगे तो यह करें' : 'If anxiety spikes, try this' }}</h3>
                    <ol class="mt-4 space-y-3 text-sm leading-7 text-slate-700 dark:text-slate-200">
                        <li>1. {{ $isHindi ? 'रेन हश या ब्राउन नॉइज़ चुनें।' : 'Choose Rain Hush or Brown Noise.' }}</li>
                        <li>2. {{ $isHindi ? 'वॉइस ऑन रखें और 5 मिनट टाइमर चुनें।' : 'Keep voice on and choose the 5 minute timer.' }}</li>
                        <li>3. {{ $isHindi ? 'धीमे से बैठें, कंधे ढीले छोड़ें, और बस सुनें।' : 'Sit softly, drop your shoulders, and just listen.' }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <section class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">{{ $isHindi ? 'वॉइस प्लेलिस्ट' : 'Voice playlists' }}</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'जो सुनना चाहें, वह चुनें' : 'Pick the tone you need right now' }}</h2>
                <div class="mt-5 space-y-3">
                    @foreach([
                        ['id' => 'affirmations', 'title' => $isHindi ? 'सॉफ्ट अफर्मेशन' : 'Soft affirmations', 'description' => $isHindi ? 'धीरे और आश्वस्त करने वाले छोटे वाक्य।' : 'Short reassuring lines for tender moments.', 'icon' => 'heart-handshake'],
                        ['id' => 'grounding', 'title' => $isHindi ? 'ग्राउंडिंग वॉइस' : 'Grounding voice', 'description' => $isHindi ? 'शरीर और कमरे में वापस आने में मदद।' : 'Helps you come back to the room and your body.', 'icon' => 'compass'],
                        ['id' => 'wisdom', 'title' => $isHindi ? 'गुरु ज्ञान लाइट' : 'Gentle wisdom', 'description' => $isHindi ? 'छोटे विचार जो दबाव नहीं, सहारा दें।' : 'Short reflective thoughts that comfort without preaching.', 'icon' => 'sparkles'],
                    ] as $playlist)
                        <button type="button" class="playlist-card flex w-full items-start gap-4 rounded-[1.4rem] border border-slate-200/80 bg-slate-50/70 p-4 text-left transition hover:border-indigo-200 dark:border-slate-800 dark:bg-slate-950/70" data-playlist="{{ $playlist['id'] }}">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-200">
                                <i data-lucide="{{ $playlist['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-950 dark:text-white">{{ $playlist['title'] }}</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $playlist['description'] }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[2rem] border border-amber-200/80 bg-amber-50/80 p-6 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white">
                        <i data-lucide="shield-heart" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'नरम नोट' : 'Gentle note' }}</h2>
                        <p class="mt-2 text-sm leading-7 text-slate-700 dark:text-slate-200">
                            {{ $isHindi ? 'यह स्पेस आराम और हल्का सहारा देने के लिए है। यह professional care का विकल्प नहीं है। अगर आप खुद को unsafe महसूस कर रहे हैं, तुरंत support पेज खोलें।' : 'This space is here for comfort and light support. It is not a replacement for professional care. If you feel unsafe, please open support right away.' }}
                        </p>
                        <a href="{{ route('support.crisis') }}" class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">
                            <i data-lucide="life-buoy" class="h-4 w-4"></i>
                            {{ $isHindi ? 'सपोर्ट खोलें' : 'Open support' }}
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </section>
</main>

@push('scripts')
<script>
(() => {
    const voiceLines = {
        affirmations: @json($isHindi ? [
            'आप अभी सुरक्षित हैं।',
            'धीरे चलना भी प्रगति है।',
            'आपको अभी सब ठीक करना जरूरी नहीं है।',
            'एक साँस, फिर अगला छोटा कदम।',
            'थकान का मतलब हार नहीं होता।',
        ] : [
            'You are safe in this moment.',
            'Going slowly still counts as progress.',
            'You do not have to solve everything right now.',
            'One breath, then the next small step.',
            'Feeling tired does not mean you are failing.',
        ]),
        grounding: @json($isHindi ? [
            'अपने पैरों का ज़मीन से लगना महसूस करें।',
            'जबड़ा ढीला छोड़ें, कंधे नीचे आने दें।',
            'कमरे में तीन शांत चीज़ें देखें।',
            'साँस को खींचना नहीं है, बस नोटिस करना है।',
            'आप इस पल में वापस आ रहे हैं।',
        ] : [
            'Feel your feet making contact with the ground.',
            'Let your jaw soften and your shoulders drop.',
            'Look around and notice three steady things.',
            'You do not need to force the breath, only notice it.',
            'You are returning to this moment.',
        ]),
        wisdom: @json($isHindi ? [
            'मन को हर बार धक्का नहीं, कभी-कभी सहारा चाहिए।',
            'शांति अक्सर धीमेपन में मिलती है, जल्दबाज़ी में नहीं।',
            'आज का छोटा आराम भी महत्वपूर्ण है।',
            'कठिन दिन आपका पूरा सच नहीं बताते।',
            'रुकना टूटना नहीं है, रुकना संभलना भी हो सकता है।',
        ] : [
            'The mind often needs support, not more pressure.',
            'Calm usually arrives through softness, not force.',
            'A small rest today still matters.',
            'A hard day does not describe your whole story.',
            'Pausing is not breaking down. Pausing can be repair.',
        ]),
    };

    const soundFactories = {};
    const soundStates = new Map();
    let audioContext = null;
    let globalGain = null;
    let timerInterval = null;
    let voiceInterval = null;
    let timeRemaining = 300;
    let currentPlaylist = 'affirmations';
    let voiceIndex = 0;
    let voiceEnabled = true;

    const waveField = document.getElementById('calm-wave-field');
    const sessionTitle = document.getElementById('session-title');
    const timerStatus = document.getElementById('timer-status');
    const voicePhase = document.getElementById('voice-phase');
    const voiceCaption = document.getElementById('voice-caption');
    const volumeInput = document.getElementById('master-volume');
    const volumeValue = document.getElementById('volume-value');
    const voiceToggle = document.getElementById('voice-enabled');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function ensureAudio() {
        if (audioContext) return;
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
        globalGain = audioContext.createGain();
        globalGain.gain.value = Number(volumeInput.value) / 100;
        globalGain.connect(audioContext.destination);
    }

    function createNoiseBuffer(durationSeconds = 2) {
        const frameCount = audioContext.sampleRate * durationSeconds;
        const buffer = audioContext.createBuffer(1, frameCount, audioContext.sampleRate);
        const data = buffer.getChannelData(0);
        let last = 0;

        for (let index = 0; index < frameCount; index += 1) {
            const white = (Math.random() * 2) - 1;
            last = (last + (0.02 * white)) / 1.02;
            data[index] = last * 3.5;
        }

        return buffer;
    }

    function activateVisual(tone = 'emerald') {
        waveField.innerHTML = '';

        if (prefersReducedMotion) {
            return;
        }

        for (let index = 0; index < 12; index += 1) {
            const dot = document.createElement('span');
            dot.className = 'absolute rounded-full opacity-70';
            dot.style.width = `${8 + (index % 3) * 4}px`;
            dot.style.height = dot.style.width;
            dot.style.left = `${10 + ((index * 7) % 78)}%`;
            dot.style.top = `${12 + ((index * 9) % 72)}%`;
            dot.style.background = tone === 'indigo'
                ? 'rgba(99, 102, 241, 0.26)'
                : tone === 'cyan'
                    ? 'rgba(6, 182, 212, 0.24)'
                    : 'rgba(16, 185, 129, 0.24)';
            dot.animate([
                { transform: 'translate3d(0, 0, 0) scale(1)', opacity: 0.35 },
                { transform: `translate3d(${(index % 2 ? -18 : 18)}px, ${index % 3 ? -22 : 22}px, 0) scale(1.25)`, opacity: 0.8 },
                { transform: 'translate3d(0, 0, 0) scale(1)', opacity: 0.35 },
            ], {
                duration: 3500 + (index * 180),
                iterations: Infinity,
                easing: 'ease-in-out',
            });
            waveField.appendChild(dot);
        }
    }

    soundFactories.rain = () => {
        const source = audioContext.createBufferSource();
        source.buffer = createNoiseBuffer();
        source.loop = true;

        const filter = audioContext.createBiquadFilter();
        filter.type = 'lowpass';
        filter.frequency.value = 900;

        const gain = audioContext.createGain();
        gain.gain.value = 0.18;

        source.connect(filter);
        filter.connect(gain);
        gain.connect(globalGain);
        source.start();

        return { stop: () => source.stop(), gain };
    };

    soundFactories.brown = () => {
        const source = audioContext.createBufferSource();
        source.buffer = createNoiseBuffer();
        source.loop = true;

        const filter = audioContext.createBiquadFilter();
        filter.type = 'lowpass';
        filter.frequency.value = 320;

        const gain = audioContext.createGain();
        gain.gain.value = 0.16;

        source.connect(filter);
        filter.connect(gain);
        gain.connect(globalGain);
        source.start();

        return { stop: () => source.stop(), gain };
    };

    soundFactories.drone = () => {
        const oscillator = audioContext.createOscillator();
        const harmonic = audioContext.createOscillator();
        const gain = audioContext.createGain();

        oscillator.type = 'sine';
        harmonic.type = 'triangle';
        oscillator.frequency.value = 136.1;
        harmonic.frequency.value = 204.15;
        gain.gain.value = 0.05;

        oscillator.connect(gain);
        harmonic.connect(gain);
        gain.connect(globalGain);
        oscillator.start();
        harmonic.start();

        return {
            stop: () => {
                oscillator.stop();
                harmonic.stop();
            },
            gain,
        };
    };

    soundFactories.chimes = () => {
        const nodes = [];
        const interval = window.setInterval(() => {
            const osc = audioContext.createOscillator();
            const gain = audioContext.createGain();
            osc.type = 'sine';
            osc.frequency.value = [523.25, 659.25, 783.99][Math.floor(Math.random() * 3)];
            gain.gain.setValueAtTime(0.0001, audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.05, audioContext.currentTime + 0.03);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioContext.currentTime + 1.6);
            osc.connect(gain);
            gain.connect(globalGain);
            osc.start();
            osc.stop(audioContext.currentTime + 1.7);
            nodes.push(osc);
        }, 3800);

        return {
            stop: () => {
                window.clearInterval(interval);
                nodes.forEach((osc) => {
                    try { osc.stop(); } catch (error) {}
                });
            },
        };
    };

    function toggleSound(soundId) {
        ensureAudio();

        if (soundStates.has(soundId)) {
            const existing = soundStates.get(soundId);
            existing.stop();
            soundStates.delete(soundId);
        } else {
            soundStates.set(soundId, soundFactories[soundId]());
        }

        document.querySelectorAll('[data-sound-card]').forEach((card) => {
            const active = soundStates.has(card.dataset.soundCard);
            card.classList.toggle('border-emerald-300', active);
            card.classList.toggle('bg-emerald-50', active);
            card.classList.toggle('dark:bg-emerald-950/20', active);
        });
    }

    function stopAllSounds() {
        soundStates.forEach((state) => state.stop());
        soundStates.clear();
        document.querySelectorAll('[data-sound-card]').forEach((card) => {
            card.classList.remove('border-emerald-300', 'bg-emerald-50', 'dark:bg-emerald-950/20');
        });
    }

    function formatTime(seconds) {
        const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        return `${mins}:${secs}`;
    }

    function stopVoicePlayback() {
        window.clearInterval(voiceInterval);
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
    }

    function speakNextLine() {
        if (!voiceEnabled || !('speechSynthesis' in window)) return;
        const lines = voiceLines[currentPlaylist] || voiceLines.affirmations;
        const line = lines[voiceIndex % lines.length];
        voiceIndex += 1;
        voicePhase.textContent = @json($isHindi ? 'सुनें' : 'Listen');
        voiceCaption.textContent = line;

        const utterance = new SpeechSynthesisUtterance(line);
        utterance.rate = 0.9;
        utterance.pitch = 1;
        utterance.volume = 0.95;
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    }

    function startVoiceLoop() {
        stopVoicePlayback();
        speakNextLine();
        voiceInterval = window.setInterval(speakNextLine, 12000);
    }

    function startTimer(minutes = 5) {
        window.clearInterval(timerInterval);
        timeRemaining = minutes * 60;
        timerStatus.textContent = formatTime(timeRemaining);

        timerInterval = window.setInterval(() => {
            timeRemaining -= 1;
            timerStatus.textContent = formatTime(Math.max(timeRemaining, 0));
            if (timeRemaining <= 0) {
                stopSession();
            }
        }, 1000);
    }

    function stopSession() {
        window.clearInterval(timerInterval);
        stopVoicePlayback();
        stopAllSounds();
        timerStatus.textContent = formatTime(timeRemaining);
        sessionTitle.textContent = @json($isHindi ? 'शांत विराम पूरा हुआ' : 'Calm pause complete');
        voicePhase.textContent = @json($isHindi ? 'आराम' : 'Rest');
        voiceCaption.textContent = @json($isHindi ? 'धीरे वापस आएँ' : 'Come back slowly');
        activateVisual('cyan');
    }

    function startSession() {
        sessionTitle.textContent = @json($isHindi ? 'शांत सत्र चल रहा है' : 'Calm session in progress');
        currentPlaylist = currentPlaylist || 'affirmations';
        activateVisual('emerald');
        if (!soundStates.has('rain')) toggleSound('rain');
        if (!soundStates.has('drone')) toggleSound('drone');
        startTimer(Number(document.querySelector('.timer-chip.border-emerald-300')?.dataset.minutes || 5));
        if (voiceEnabled) {
            startVoiceLoop();
        } else {
            voicePhase.textContent = @json($isHindi ? 'साउंड' : 'Sound');
            voiceCaption.textContent = @json($isHindi ? 'बस ध्वनि के साथ आराम करें' : 'Rest with sound only');
        }
    }

    document.querySelectorAll('[data-sound-card]').forEach((card) => {
        card.addEventListener('click', () => {
            toggleSound(card.dataset.soundCard);
            activateVisual(card.dataset.soundCard === 'chimes' ? 'indigo' : card.dataset.soundCard === 'brown' ? 'cyan' : 'emerald');
        });
    });

    document.querySelectorAll('.timer-chip').forEach((chip, index) => {
        if (index === 0) {
            chip.classList.add('border-emerald-300', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-950/20');
        }
        chip.addEventListener('click', () => {
            document.querySelectorAll('.timer-chip').forEach((item) => {
                item.classList.remove('border-emerald-300', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-950/20');
            });
            chip.classList.add('border-emerald-300', 'bg-emerald-50', 'text-emerald-700', 'dark:bg-emerald-950/20');
            timerStatus.textContent = formatTime(Number(chip.dataset.minutes) * 60);
        });
    });

    document.querySelectorAll('[data-playlist]').forEach((card, index) => {
        if (index === 0) {
            card.classList.add('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/20');
        }
        card.addEventListener('click', () => {
            currentPlaylist = card.dataset.playlist;
            voiceIndex = 0;
            document.querySelectorAll('[data-playlist]').forEach((item) => {
                item.classList.remove('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/20');
            });
            card.classList.add('border-indigo-300', 'bg-indigo-50', 'dark:bg-indigo-950/20');
            sessionTitle.textContent = card.querySelector('h3').textContent;
            if (voiceEnabled) {
                startVoiceLoop();
            }
        });
    });

    volumeInput.addEventListener('input', () => {
        const volume = Number(volumeInput.value) / 100;
        volumeValue.textContent = `${volumeInput.value}%`;
        if (globalGain) {
            globalGain.gain.value = volume;
        }
    });

    voiceToggle.addEventListener('change', () => {
        voiceEnabled = voiceToggle.checked;
        if (!voiceEnabled) {
            stopVoicePlayback();
            voicePhase.textContent = @json($isHindi ? 'साउंड' : 'Sound');
            voiceCaption.textContent = @json($isHindi ? 'आवाज़ बंद है' : 'Voice is off');
            return;
        }

        startVoiceLoop();
    });

    document.querySelector('[data-play-all]').addEventListener('click', startSession);
    document.querySelector('[data-stop-all]').addEventListener('click', stopSession);

    activateVisual('emerald');
    volumeValue.textContent = `${volumeInput.value}%`;
})();
</script>
@endpush
@endsection
