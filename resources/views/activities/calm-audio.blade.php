@extends('layouts.public')

@section('content')
    @php
        // Define decodeUnicode helper if not already defined
        if (!function_exists('decodeUnicode')) {
            function decodeUnicode($str)
            {
                return preg_replace_callback(
                    '/\\\\u([0-9a-fA-F]{4})/',
                    function ($matches) {
                        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16BE');
                    },
                    $str,
                );
            }
        }
        $isHindi = \App\Helpers\LocaleHelper::current() === 'hi';
        $hi = \Lang::get('activities.calm_audio');
        $voiceLines = [
            'affirmations' => [
                'You are safe in this moment.',
                'Going slowly still counts as progress.',
                'You do not have to solve everything right now.',
                'One breath, then the next small step.',
                'Feeling tired does not mean you are failing.',
            ],
            'grounding' => [
                'Feel your feet making contact with the ground.',
                'Let your jaw soften and your shoulders drop.',
                'Look around and notice three steady things.',
                'You do not need to force the breath, only notice it.',
                'You are returning to this moment.',
            ],
            'wisdom' => [
                'The mind often needs support, not more pressure.',
                'Calm usually arrives through softness, not force.',
                'A small rest today still matters.',
                'A hard day does not describe your whole story.',
                'Pausing is not breaking down. Pausing can be repair.',
            ],
        ];

        $hi = [
            'page_title' => 'शांत ऑडियो स्पेस',
            'audio_comfort' => 'ऑडियो कम्फर्ट',
            'hero_title' => 'मुफ्त शांत साउंड्स, सुखद आवाजें और प्रेरणादायक विचार',
            'hero_copy' =>
                'जब मन भारी महसूस हो, तो यह आपको एक शांत स्थान देता है। एम्बिएंट साउंड्स, गाइडेड वॉइस क्यूज, ग्राउंडिंग रिमाइंडर्स और शॉर्ट विजडम प्लेलिस्ट को मिलाकर एक बेहतर महसूस करें।',
            'start_session' => 'शांत सेशन शुरू करें',
            'stop' => 'रोकें',
            'free_browser_sound' => 'मुफ्त ब्राउज़र साउंड',
            'guided_voice_cues' => 'गाइडेड वॉइस क्यूज',
            'minute_timer' => decodeUnicode('5, 10, 20 \u092e\u093f\u0928\u091f \u091f\u093e\u0907\u092e\u0930'),
            'now_playing' => decodeUnicode('\u0905\u092d\u0940 \u091a\u0932 \u0930\u0939\u093e \u0939\u0948'),
            'calm_start' => decodeUnicode('\u0936\u093e\u0902\u0924 \u0936\u0941\u0930\u0941\u0906\u0924'),
            'listen' => decodeUnicode('\u0938\u0941\u0928\u0947\u0902'),
            'take_pause' => decodeUnicode(
                '\u090f\u0915 \u0915\u094b\u092e\u0932 \u0935\u093f\u0930\u093e\u092e \u0932\u0947\u0902',
            ),
            'sound' => decodeUnicode('\u0938\u093e\u0909\u0902\u0921'),
            'rain_drone' => decodeUnicode('\u0930\u0947\u0928 + \u0921\u094d\u0930\u094b\u0928'),
            'voice' => decodeUnicode('\u0935\u0949\u0907\u0938'),
            'gentle_thoughts' => decodeUnicode(
                '\u0938\u0941\u0915\u0942\u0928 \u092d\u0930\u0947 \u0935\u093f\u091a\u093e\u0930',
            ),
            'mode' => decodeUnicode('\u092e\u094b\u0921'),
            'low_stimulation' => decodeUnicode('\u0915\u092e \u0909\u0924\u094d\u0924\u0947\u091c\u0928\u093e'),
            'build_mix' => decodeUnicode(
                '\u0905\u092a\u0928\u093e \u092e\u093f\u0936\u094d\u0930\u0923 \u092c\u0928\u093e\u090f\u0901',
            ),
            'choose_mix' => decodeUnicode(
                '\u0935\u093e\u0924\u093e\u0935\u0930\u0923, \u0906\u0935\u093e\u095b \u0914\u0930 \u091f\u093e\u0907\u092e\u0930 \u091a\u0941\u0928\u0947\u0902',
            ),
            'voice_on' => decodeUnicode('\u0906\u0935\u093e\u095b \u091a\u093e\u0932\u0942'),
            'rain_hush' => decodeUnicode('\u0930\u0947\u0928 \u0939\u0936'),
            'rain_hush_desc' => decodeUnicode(
                '\u0932\u0917\u093e\u0924\u093e\u0930 \u092c\u093e\u0930\u093f\u0936 \u091c\u0948\u0938\u0940 \u092e\u0941\u0932\u093e\u092f\u092e \u0927\u094d\u0935\u0928\u093f \u092a\u0930\u0924\u0964',
            ),
            'deep_drone' => decodeUnicode('\u0921\u0940\u092a \u0921\u094d\u0930\u094b\u0928'),
            'deep_drone_desc' => decodeUnicode(
                '\u0927\u0940\u092e\u093e warm tone \u091c\u094b \u0936\u0930\u0940\u0930 \u0915\u094b settle \u0915\u0930\u0928\u0947 \u092e\u0947\u0902 \u092e\u0926\u0926 \u0915\u0930\u0947\u0964',
            ),
            'soft_chimes' => decodeUnicode('\u0938\u0949\u092b\u094d\u091f \u091a\u093e\u0907\u092e\u094d\u0938'),
            'soft_chimes_desc' => decodeUnicode(
                '\u0939\u0932\u094d\u0915\u0940 \u0905\u0902\u0924\u0930\u093e\u0932 \u0935\u093e\u0932\u0940 \u091a\u092e\u0915\u0924\u0940 \u091f\u094b\u0928\u0964',
            ),
            'brown_noise' => decodeUnicode('\u092c\u094d\u0930\u093e\u0909\u0928 \u0928\u0949\u0907\u095b'),
            'brown_noise_desc' => decodeUnicode(
                '\u0932\u094b-\u092b\u094d\u0930\u093f\u0915\u094d\u0935\u0947\u0902\u0938\u0940 mask \u091c\u094b \u092c\u093e\u0939\u0930\u0940 \u0905\u0935\u094d\u092f\u0935\u0938\u094d\u0925\u093e \u0915\u092e \u0915\u0930\u0947\u0964',
            ),
            'volume' => decodeUnicode('\u0935\u0949\u0932\u094d\u092f\u0942\u092e'),
            'minute' => decodeUnicode('\u092e\u093f\u0928\u091f'),
            'quick_help' => decodeUnicode('\u091c\u0932\u094d\u0926\u0940 \u092e\u0926\u0926'),
            'anxiety_spikes' => decodeUnicode(
                '\u092c\u0939\u0941\u0924 \u092c\u0947\u091a\u0948\u0928\u0940 \u0932\u0917\u0947 \u0924\u094b \u092f\u0939 \u0915\u0930\u0947\u0902',
            ),
            'quick_step_1' => decodeUnicode(
                '\u0930\u0947\u0928 \u0939\u0936 \u092f\u093e \u092c\u094d\u0930\u093e\u0909\u0928 \u0928\u0949\u0907\u095b \u091a\u0941\u0928\u0947\u0902\u0964',
            ),
            'quick_step_2' => decodeUnicode(
                '\u0935\u0949\u0907\u0938 \u0911\u0928 \u0930\u0916\u0947\u0902 \u0914\u0930 5 \u092e\u093f\u0928\u091f \u091f\u093e\u0907\u092e\u0930 \u091a\u0941\u0928\u0947\u0902\u0964',
            ),
            'quick_step_3' => decodeUnicode(
                '\u0927\u0940\u092e\u0947 \u0938\u0947 \u092c\u0948\u0920\u0947\u0902, \u0915\u0902\u0927\u0947 \u0922\u0940\u0932\u0947 \u091b\u094b\u0921\u093c\u0947\u0902, \u0914\u0930 \u092c\u0938 \u0938\u0941\u0928\u0947\u0902\u0964',
            ),
            'voice_playlists' => decodeUnicode(
                '\u0935\u0949\u0907\u0938 \u092a\u094d\u0932\u0947\u0932\u093f\u0938\u094d\u091f',
            ),
            'pick_tone' => decodeUnicode(
                '\u091c\u094b \u0938\u0941\u0928\u0928\u093e \u091a\u093e\u0939\u0947\u0902, \u0935\u0939 \u091a\u0941\u0928\u0947\u0902',
            ),
            'soft_affirmations' => decodeUnicode(
                '\u0938\u0949\u092b\u094d\u091f \u0905\u092b\u0930\u094d\u092e\u0947\u0936\u0928',
            ),
            'soft_affirmations_desc' => decodeUnicode(
                '\u0927\u0940\u0930\u0947 \u0914\u0930 \u0906\u0936\u094d\u0935\u0938\u094d\u0924 \u0915\u0930\u0928\u0947 \u0935\u093e\u0932\u0947 \u091b\u094b\u091f\u0947 \u0935\u093e\u0915\u094d\u092f\u0964',
            ),
            'grounding_voice' => decodeUnicode(
                '\u0917\u094d\u0930\u093e\u0909\u0902\u0921\u093f\u0902\u0917 \u0935\u0949\u0907\u0938',
            ),
            'grounding_voice_desc' => decodeUnicode(
                '\u0936\u0930\u0940\u0930 \u0914\u0930 \u0915\u092e\u0930\u0947 \u092e\u0947\u0902 \u0935\u093e\u092a\u0938 \u0906\u0928\u0947 \u092e\u0947\u0902 \u092e\u0926\u0926\u0964',
            ),
            'gentle_wisdom' => decodeUnicode(
                '\u0917\u0941\u0930\u0941 \u091c\u094d\u091e\u093e\u0928 \u0932\u093e\u0907\u091f',
            ),
            'gentle_wisdom_desc' => decodeUnicode(
                '\u091b\u094b\u091f\u0947 \u0935\u093f\u091a\u093e\u0930 \u091c\u094b \u0926\u092c\u093e\u0935 \u0928\u0939\u0940\u0902, \u0938\u0939\u093e\u0930\u093e \u0926\u0947\u0902\u0964',
            ),
            'gentle_note' => decodeUnicode('\u0928\u0930\u092e \u0928\u094b\u091f'),
            'support_note' => decodeUnicode(
                '\u092f\u0939 \u0938\u094d\u092a\u0947\u0938 \u0906\u0930\u093e\u092e \u0914\u0930 \u0939\u0932\u094d\u0915\u093e \u0938\u0939\u093e\u0930\u093e \u0926\u0947\u0928\u0947 \u0915\u0947 \u0932\u093f\u090f \u0939\u0948\u0964 \u092f\u0939 professional care \u0915\u093e \u0935\u093f\u0915\u0932\u094d\u092a \u0928\u0939\u0940\u0902 \u0939\u0948\u0964 \u0905\u0917\u0930 \u0906\u092a \u0916\u0941\u0926 \u0915\u094b unsafe \u092e\u0939\u0938\u0942\u0938 \u0915\u0930 \u0930\u0939\u0947 \u0939\u0948\u0902, \u0924\u0941\u0930\u0902\u0924 support \u092a\u0947\u091f\u091c \u0916\u094b\u0932\u0947\u0902\u0964',
            ),
            'open_support' => decodeUnicode('\u0938\u092a\u094b\u0930\u094d\u091f \u0916\u094b\u0932\u0947\u0902'),
            'voice_lines' => [
                'affirmations' => [
                    decodeUnicode(
                        '\u0906\u092a \u0905\u092d\u0940 \u0938\u0941\u0930\u0915\u094d\u0937\u093f\u0924 \u0939\u0948\u0902\u0964',
                    ),
                    decodeUnicode(
                        '\u0927\u0940\u0930\u0947 \u091a\u0932\u0928\u093e \u092d\u0940 \u092a\u094d\u0930\u0917\u0924\u093f \u0939\u0948\u0964',
                    ),
                    decodeUnicode(
                        '\u0906\u092a\u0915\u094b \u0905\u092d\u0940 \u0938\u092c \u0920\u0940\u0915 \u0915\u0930\u0928\u093e \u095b\u0930\u0942\u0930\u0940 \u0928\u0939\u0940\u0902 \u0939\u0948\u0964',
                    ),
                    decodeUnicode(
                        '\u090f\u0915 \u0938\u093e\u0901\u0938, \u092b\u093f\u0930 \u0905\u0917\u0932\u093e \u091b\u094b\u091f\u093e \u0915\u0926\u092e\u0964',
                    ),
                    decodeUnicode(
                        '\u0925\u0915\u093e\u0928 \u0915\u093e \u092e\u0924\u0932\u092c \u0939\u093e\u0930 \u0928\u0939\u0940\u0902 \u0939\u094b\u0924\u093e\u0964',
                    ),
                ],
                'grounding' => [
                    decodeUnicode(
                        '\u0905\u092a\u0928\u0947 \u092a\u0948\u0930\u094b\u0902 \u0915\u093e \u095b\u092e\u0940\u0928 \u0938\u0947 \u0932\u0917\u0928\u093e \u092e\u0939\u0938\u0942\u0938 \u0915\u0930\u0947\u0902\u0964',
                    ),
                    decodeUnicode(
                        '\u091c\u092c\u0921\u093c\u093e \u0922\u0940\u0932\u093e \u091b\u094b\u0921\u093c\u0947\u0902, \u0915\u0902\u0927\u0947 \u0928\u0940\u091a\u0947 \u0906\u0928\u0947 \u0926\u0947\u0902\u0964',
                    ),
                    decodeUnicode(
                        '\u0915\u092e\u0930\u0947 \u092e\u0947\u0902 \u0924\u0940\u0928 \u0936\u093e\u0902\u0924 \u091a\u0940\u095b\u0947\u0902 \u0926\u0947\u0916\u0947\u0902\u0964',
                    ),
                    decodeUnicode(
                        '\u0938\u093e\u0901\u0938 \u0915\u094b \u0916\u0940\u0902\u091a\u0928\u093e \u0928\u0939\u0940\u0902 \u0939\u0948, \u092c\u0938 \u0928\u094b\u091f\u093f\u0938 \u0915\u0930\u0928\u093e \u0939\u0948\u0964',
                    ),
                    decodeUnicode(
                        '\u0906\u092a \u0907\u0938 \u092a\u0932 \u092e\u0947\u0902 \u0935\u093e\u092a\u0938 \u0906 \u0930\u0939\u0947 \u0939\u0948\u0902\u0964',
                    ),
                ],
                'wisdom' => [
                    decodeUnicode(
                        '\u092e\u0928 \u0915\u094b \u0939\u0930 \u092c\u093e\u0930 \u0927\u0915\u094d\u0915\u093e \u0928\u0939\u0940\u0902, \u0915\u092d\u0940-\u0915\u092d\u0940 \u0938\u0939\u093e\u0930\u093e \u091a\u093e\u0939\u093f\u090f\u0964',
                    ),
                    decodeUnicode(
                        '\u0936\u093e\u0902\u0924\u093f \u0905\u0915\u094d\u0938\u0930 \u0927\u0940\u092e\u0947\u092a\u0928 \u092e\u0947\u0902 \u092e\u093f\u0932\u0924\u0940 \u0939\u0948, \u091c\u0932\u094d\u0926\u092c\u093e\u095b\u0940 \u092e\u0947\u0902 \u0928\u0939\u0940\u0902\u0964',
                    ),
                    decodeUnicode(
                        '\u0906\u091c \u0915\u093e \u091b\u094b\u091f\u093e \u0906\u0930\u093e\u092e \u092d\u0940 \u092e\u0939\u0924\u094d\u0924\u094d\u0935\u092a\u0942\u0930\u094d\u0923 \u0939\u0948\u0964',
                    ),
                    decodeUnicode(
                        '\u0915\u0920\u093f\u0928 \u0926\u093f\u0928 \u0906\u092a\u0915\u093e \u092a\u0942\u0930\u093e \u0938\u091a \u0928\u0939\u0940\u0902 \u092c\u0924\u093e\u0924\u0947\u0964',
                    ),
                    decodeUnicode(
                        '\u0930\u0941\u0915\u0928\u093e \u091f\u0942\u091f\u0928\u093e \u0928\u0939\u0940\u0902 \u0939\u0948, \u0930\u0941\u0915\u0928\u093e \u0938\u0902\u092d\u0932\u0928\u093e \u092d\u0940 \u0939\u094b \u0938\u0915\u0924\u093e \u0939\u0948\u0964',
                    ),
                ],
            ],
        ];
    @endphp

@section('title', ($isHindi ? $hi['page_title'] : 'Calm Audio Space') . ' - Arogio')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section
        class="relative overflow-hidden rounded-[2rem] border border-emerald-100/80 bg-gradient-to-br from-white via-emerald-50/70 to-cyan-50/70 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:p-8 lg:p-10">
        <div class="pointer-events-none absolute -right-16 top-0 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl">
        </div>
        <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-cyan-300/20 blur-3xl"></div>

        <div class="relative grid gap-8 xl:grid-cols-[1.05fr_0.95fr] xl:items-center">
            <div class="max-w-3xl">
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200/80 bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.24em] text-emerald-700 shadow-sm dark:border-emerald-900/50 dark:bg-slate-950/80 dark:text-emerald-300">
                    <i data-lucide="headphones" class="h-4 w-4"></i>
                    {{ $isHindi ? $hi['audio_comfort'] : 'Audio Comfort' }}
                </div>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-5xl">
                    {{ $isHindi ? $hi['hero_title'] : 'Free calming sounds, gentle voices, and short uplifting thoughts' }}
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300 sm:text-lg">
                    {{ $isHindi ? $hi['hero_copy'] : 'When the mind feels heavy, this gives you a softer place to land. Mix ambient sounds, guided voice cues, grounding reminders, and short wisdom playlists for a gentler reset.' }}
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <button type="button" data-play-all
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-[1.25rem] bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-700">
                        <i data-lucide="play" class="h-4 w-4"></i>
                        {{ $isHindi ? $hi['start_session'] : 'Start calm session' }}
                    </button>
                    <button type="button" data-stop-all
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-[1.25rem] border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-900 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                        <i data-lucide="square" class="h-4 w-4"></i>
                        {{ $isHindi ? $hi['stop'] : 'Stop' }}
                    </button>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/80">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                            {{ $isHindi ? 'साउंड' : 'Sound' }}</p>
                        <p id="selected-sounds" class="mt-1 text-sm font-bold text-slate-950 dark:text-white">
                            {{ $isHindi ? 'कोई नहीं चुना' : 'None selected' }}</p>
                    </div>
                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/80">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                            {{ $isHindi ? 'वॉइस' : 'Voice' }}</p>
                        <p id="selected-voice" class="mt-1 text-sm font-bold text-slate-950 dark:text-white">
                            {{ $isHindi ? 'सॉफ्ट अफर्मेशन' : 'Soft affirmations' }}</p>
                    </div>
                    <div
                        class="rounded-2xl border border-slate-200/80 bg-white/90 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/80">
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                            {{ $isHindi ? 'टाइमर' : 'Timer' }}</p>
                        <p id="selected-timer" class="mt-1 text-sm font-bold text-slate-950 dark:text-white">5
                            {{ $isHindi ? $hi['minute'] : 'min' }}</p>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-3 text-sm">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="music" class="h-4 w-4 text-emerald-600 dark:text-emerald-300"></i>
                        {{ $isHindi ? $hi['free_browser_sound'] : 'Free browser-based sound' }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="mic" class="h-4 w-4 text-cyan-600 dark:text-cyan-300"></i>
                        {{ $isHindi ? $hi['guided_voice_cues'] : 'Guided voice cues' }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-white/80 bg-white/85 px-4 py-2 font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/80 dark:text-slate-200">
                        <i data-lucide="timer" class="h-4 w-4 text-indigo-600 dark:text-indigo-300"></i>
                        {{ $isHindi ? $hi['minute_timer'] : '5, 10, 20 minute timer' }}
                    </span>
                </div>
            </div>

            <div
                class="rounded-[2rem] border border-white/80 bg-white/85 p-5 shadow-xl backdrop-blur dark:border-slate-800 dark:bg-slate-950/80 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">
                            {{ $isHindi ? $hi['now_playing'] : 'Now playing' }}</p>
                        <h2 id="session-title" class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">
                            {{ $isHindi ? $hi['calm_start'] : 'Calm start' }}</h2>
                    </div>
                    <div
                        class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">
                        <span id="timer-status">05:00</span>
                    </div>
                </div>

                <div
                    class="mt-6 relative flex h-64 sm:h-80 items-center justify-center overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_center,rgba(16,185,129,0.18),rgba(255,255,255,0.92)_45%,rgba(236,253,245,0.9)_100%)] dark:bg-[radial-gradient(circle_at_center,rgba(16,185,129,0.24),rgba(15,23,42,0.98)_48%,rgba(2,6,23,0.98)_100%)]">
                    <div class="absolute inset-0" id="calm-wave-field"></div>
                    <div
                        class="relative z-10 flex h-52 w-52 sm:h-64 sm:w-64 items-center justify-center rounded-full border border-white/85 bg-white/90 p-6 shadow-2xl shadow-emerald-400/20 backdrop-blur dark:border-white/10 dark:bg-slate-900/90">
                        <div class="text-center w-full px-2">
                            <div id="voice-phase"
                                class="text-xs font-bold uppercase tracking-[0.3em] text-emerald-700 dark:text-emerald-300">
                                {{ $isHindi ? $hi['listen'] : 'Listen' }}</div>
                            <div id="voice-caption"
                                class="mt-4 text-base sm:text-lg font-extrabold text-slate-950 dark:text-white leading-relaxed break-words">
                                {{ $isHindi ? $hi['take_pause'] : 'Take a gentle pause' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @foreach ([['label' => $isHindi ? $hi['sound'] : 'Sound', 'value' => $isHindi ? $hi['rain_drone'] : 'Rain + Drone'], ['label' => $isHindi ? $hi['voice'] : 'Voice', 'value' => $isHindi ? $hi['gentle_thoughts'] : 'Gentle thoughts'], ['label' => $isHindi ? $hi['mode'] : 'Mode', 'value' => $isHindi ? $hi['low_stimulation'] : 'Low stimulation']] as $stat)
                        <div
                            class="rounded-2xl border border-slate-200/80 bg-white/80 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/70">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                {{ $stat['label'] }}</p>
                            <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div
            class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">
                        {{ $isHindi ? $hi['build_mix'] : 'Build your mix' }}</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">
                        {{ $isHindi ? $hi['choose_mix'] : 'Choose your ambient layer, voice, and timer' }}</h2>
                </div>
                <label
                    class="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 cursor-pointer select-none">
                    <input id="voice-enabled" type="checkbox" checked class="peer sr-only">
                    <span id="voice-toggle-bg"
                        class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full bg-emerald-500 transition dark:bg-emerald-500">
                        <span id="voice-toggle-dot"
                            class="absolute left-1 h-5 w-5 rounded-full bg-white shadow-sm transition-transform translate-x-5"></span>
                    </span>
                    <span id="voice-label-text">{{ $isHindi ? $hi['voice_on'] : 'Voice on' }}</span>
                </label>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ([['id' => 'rain', 'title' => $isHindi ? $hi['rain_hush'] : 'Rain Hush', 'description' => $isHindi ? $hi['rain_hush_desc'] : 'A soft, steady rain-like wash for overstimulated moments.', 'icon' => 'cloud-rain'], ['id' => 'drone', 'title' => $isHindi ? $hi['deep_drone'] : 'Deep Drone', 'description' => $isHindi ? $hi['deep_drone_desc'] : 'A slow warm tone bed that can help the body feel more settled.', 'icon' => 'waves'], ['id' => 'chimes', 'title' => $isHindi ? $hi['soft_chimes'] : 'Soft Chimes', 'description' => $isHindi ? $hi['soft_chimes_desc'] : 'Gentle periodic chimes for light uplift without rush.', 'icon' => 'bell-ring'], ['id' => 'brown', 'title' => $isHindi ? $hi['brown_noise'] : 'Brown Noise', 'description' => $isHindi ? $hi['brown_noise_desc'] : 'Low-frequency masking sound for noisy surroundings.', 'icon' => 'audio-lines']] as $scene)
                    <button type="button"
                        class="sound-card text-left rounded-[1.6rem] border border-slate-200/80 bg-slate-50/70 p-5 transition hover:-translate-y-0.5 hover:border-emerald-200 dark:border-slate-800 dark:bg-slate-950/70"
                        data-sound-card="{{ $scene['id'] }}">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">
                                <i data-lucide="{{ $scene['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-950 dark:text-white">{{ $scene['title'] }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                    {{ $scene['description'] }}</p>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-[1fr_0.9fr]">
                <div
                    class="rounded-[1.6rem] border border-slate-200/80 bg-white p-5 dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-center justify-between gap-3">
                        <label for="master-volume"
                            class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? $hi['volume'] : 'Volume' }}</label>
                        <span id="volume-value"
                            class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">60%</span>
                    </div>
                    <input id="master-volume" type="range" min="0" max="100" value="60"
                        class="mt-4 w-full accent-emerald-600">

                    <div class="mt-5 flex flex-wrap gap-3">
                        @foreach ([5, 10, 20] as $minutes)
                            <button type="button"
                                class="timer-chip inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-emerald-200 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                data-minutes="{{ $minutes }}">
                                {{ $minutes }} {{ $isHindi ? $hi['minute'] : 'min' }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div
                    class="rounded-[1.6rem] border border-cyan-200/80 bg-gradient-to-br from-cyan-50 to-emerald-50 p-5 dark:border-cyan-900/40 dark:from-cyan-950/20 dark:to-emerald-950/20">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">
                        {{ $isHindi ? $hi['quick_help'] : 'Quick help' }}</p>
                    <h3 class="mt-2 text-lg font-extrabold text-slate-950 dark:text-white">
                        {{ $isHindi ? $hi['anxiety_spikes'] : 'If anxiety spikes, try this' }}</h3>
                    <ol class="mt-4 space-y-3 text-sm leading-7 text-slate-700 dark:text-slate-200">
                        <li>1. {{ $isHindi ? $hi['quick_step_1'] : 'Choose Rain Hush or Brown Noise.' }}</li>
                        <li>2. {{ $isHindi ? $hi['quick_step_2'] : 'Keep voice on and choose the 5 minute timer.' }}
                        </li>
                        <li>3.
                            {{ $isHindi ? $hi['quick_step_3'] : 'Sit softly, drop your shoulders, and just listen.' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <section
                class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">
                    {{ $isHindi ? $hi['voice_playlists'] : 'Voice playlists' }}</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">
                    {{ $isHindi ? $hi['pick_tone'] : 'Pick the tone you need right now' }}</h2>
                <div class="mt-5 space-y-3">
                    @foreach ([['id' => 'affirmations', 'title' => $isHindi ? $hi['soft_affirmations'] : 'Soft affirmations', 'description' => $isHindi ? $hi['soft_affirmations_desc'] : 'Short reassuring lines for tender moments.', 'icon' => 'heart-handshake'], ['id' => 'grounding', 'title' => $isHindi ? $hi['grounding_voice'] : 'Grounding voice', 'description' => $isHindi ? $hi['grounding_voice_desc'] : 'Helps you come back to the room and your body.', 'icon' => 'compass'], ['id' => 'wisdom', 'title' => $isHindi ? $hi['gentle_wisdom'] : 'Gentle wisdom', 'description' => $isHindi ? $hi['gentle_wisdom_desc'] : 'Short reflective thoughts that comfort without preaching.', 'icon' => 'sparkles']] as $playlist)
                        <button type="button"
                            class="playlist-card flex w-full items-start gap-4 rounded-[1.4rem] border border-slate-200/80 bg-slate-50/70 p-4 text-left transition hover:border-indigo-200 dark:border-slate-800 dark:bg-slate-950/70"
                            data-playlist="{{ $playlist['id'] }}">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-200">
                                <i data-lucide="{{ $playlist['icon'] }}" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-950 dark:text-white">
                                    {{ $playlist['title'] }}</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                    {{ $playlist['description'] }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </section>

            <section
                class="rounded-[2rem] border border-amber-200/80 bg-amber-50/80 p-6 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/20">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-white">
                        <i data-lucide="shield-heart" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-950 dark:text-white">
                            {{ $isHindi ? $hi['gentle_note'] : 'Gentle note' }}</h2>
                        <p class="mt-2 text-sm leading-7 text-slate-700 dark:text-slate-200">
                            {{ $isHindi ? $hi['support_note'] : 'This space is here for comfort and light support. It is not a replacement for professional care. If you feel unsafe, please open support right away.' }}
                        </p>
                        <a href="{{ route('support.crisis') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">
                            <i data-lucide="life-buoy" class="h-4 w-4"></i>
                            {{ $isHindi ? $hi['open_support'] : 'Open support' }}
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
            const voiceLines = @json($isHindi ? $hi['voice_lines'] : $voiceLines);
            const soundFactories = {};
            const soundStates = new Map();
            let audioContext = null;
            let globalGain = null;
            let timerInterval = null;
            let voiceInterval = null;
            let selectedDuration = 5;
            let timeRemaining = selectedDuration * 60;
            let currentPlaylist = 'affirmations';
            let voiceIndex = 0;
            let voiceEnabled = true;
            let sessionActive = false;

            const waveField = document.getElementById('calm-wave-field');
            const sessionTitle = document.getElementById('session-title');
            const timerStatus = document.getElementById('timer-status');
            const voicePhase = document.getElementById('voice-phase');
            const voiceCaption = document.getElementById('voice-caption');
            const volumeInput = document.getElementById('master-volume');
            const volumeValue = document.getElementById('volume-value');
            const voiceToggle = document.getElementById('voice-enabled');
            const selectedSoundsEl = document.getElementById('selected-sounds');
            const selectedVoiceEl = document.getElementById('selected-voice');
            const selectedTimerEl = document.getElementById('selected-timer');
            const playAllButton = document.querySelector('[data-play-all]');
            const stopAllButton = document.querySelector('[data-stop-all]');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const playlistTitles = new Map([...document.querySelectorAll('[data-playlist]')].map((card) => [card.dataset
                .playlist, card.querySelector('h3')?.textContent?.trim() || ''
            ]));
            const soundTitles = new Map([...document.querySelectorAll('[data-sound-card]')].map((card) => [card.dataset
                .soundCard, card.querySelector('h3')?.textContent?.trim() || ''
            ]));

            function ensureAudio() {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    globalGain = audioContext.createGain();
                    globalGain.gain.value = Number(volumeInput.value) / 100;
                    globalGain.connect(audioContext.destination);
                }
                if (audioContext.state === 'suspended') {
                    return audioContext.resume();
                }
                return Promise.resolve();
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
                if (prefersReducedMotion) return;
                for (let index = 0; index < 12; index += 1) {
                    const dot = document.createElement('span');
                    dot.className = 'absolute rounded-full opacity-70';
                    dot.style.width = `${8 + (index % 3) * 4}px`;
                    dot.style.height = dot.style.width;
                    dot.style.left = `${10 + ((index * 7) % 78)}%`;
                    dot.style.top = `${12 + ((index * 9) % 72)}%`;
                    dot.style.background = tone === 'indigo' ? 'rgba(99, 102, 241, 0.26)' : tone === 'cyan' ?
                        'rgba(6, 182, 212, 0.24)' : 'rgba(16, 185, 129, 0.24)';
                    dot.animate([{
                            transform: 'translate3d(0, 0, 0) scale(1)',
                            opacity: 0.35
                        },
                        {
                            transform: `translate3d(${(index % 2 ? -18 : 18)}px, ${index % 3 ? -22 : 22}px, 0) scale(1.25)`,
                            opacity: 0.8
                        },
                        {
                            transform: 'translate3d(0, 0, 0) scale(1)',
                            opacity: 0.35
                        },
                    ], {
                        duration: 3500 + (index * 180),
                        iterations: Infinity,
                        easing: 'ease-in-out'
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
                return {
                    stop: () => source.stop(),
                    gain
                };
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
                return {
                    stop: () => source.stop(),
                    gain
                };
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
                    gain
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
                        clearInterval(interval);
                        nodes.forEach((osc) => {
                            try {
                                osc.stop();
                            } catch (error) {}
                        });
                    }
                };
            };

            function formatTime(seconds) {
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                return `${mins}:${secs}`;
            }

            function updateSelectionSummary() {
                const soundNames = [...soundStates.keys()].map((soundId) => soundTitles.get(soundId)).filter(Boolean);
                if (selectedSoundsEl) selectedSoundsEl.textContent = soundNames.length ? soundNames.join(', ') :
                    @json($isHindi ? 'कोई नहीं चुना' : 'None selected');
                if (selectedVoiceEl) selectedVoiceEl.textContent = voiceEnabled ? (playlistTitles.get(
                    currentPlaylist) || @json($isHindi ? 'सॉफ्ट अफर्मेशन' : 'Soft affirmations')) : @json($isHindi ? 'आवाज़ बंद' : 'Voice off');
                if (selectedTimerEl) selectedTimerEl.textContent =
                `${selectedDuration} ${@json($isHindi ? $hi['minute'] : 'min')}`;
            }

            function updateSessionButtons() {
                if (!playAllButton || !stopAllButton) return;
                playAllButton.innerHTML = sessionActive ?
                    `<i data-lucide="play" class="h-4 w-4"></i>${@json($isHindi ? 'सत्र जारी है' : 'Session running')}` :
                    `<i data-lucide="play" class="h-4 w-4"></i>${@json($isHindi ? $hi['start_session'] : 'Start calm session')}`;
                stopAllButton.disabled = !sessionActive;
                stopAllButton.classList.toggle('opacity-50', !sessionActive);
                stopAllButton.classList.toggle('cursor-not-allowed', !sessionActive);
                window.refreshLucideIcons?.();
            }

            async function toggleSound(soundId) {
                await ensureAudio();
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
                updateSelectionSummary();
            }

            function stopAllSounds() {
                soundStates.forEach((state) => state.stop());
                soundStates.clear();
                document.querySelectorAll('[data-sound-card]').forEach((card) => {
                    card.classList.remove('border-emerald-300', 'bg-emerald-50', 'dark:bg-emerald-950/20');
                });
                updateSelectionSummary();
            }

            function stopVoicePlayback() {
                clearInterval(voiceInterval);
                if ('speechSynthesis' in window) window.speechSynthesis.cancel();
            }

            function speakNextLine() {
                if (!voiceEnabled || !('speechSynthesis' in window)) return;
                const lines = voiceLines[currentPlaylist] || voiceLines.affirmations;
                const line = lines[voiceIndex % lines.length];
                voiceIndex += 1;
                voicePhase.textContent = @json($isHindi ? $hi['listen'] : 'Listen');
                voiceCaption.textContent = line;
                const utterance = new SpeechSynthesisUtterance(line);
                utterance.lang = @json($isHindi ? 'hi-IN' : 'en-US');
                utterance.rate = 0.85;
                utterance.pitch = 1.0;
                utterance.volume = 0.95;

                // Try to pick a proper native voice
                if ('speechSynthesis' in window) {
                    const voices = window.speechSynthesis.getVoices();
                    const targetLang = @json($isHindi ? 'hi' : 'en');
                    const matchingVoice = voices.find(v => v.lang.startsWith(targetLang));
                    if (matchingVoice) utterance.voice = matchingVoice;
                }

                window.speechSynthesis.cancel();
                window.speechSynthesis.speak(utterance);
            }

            function startVoiceLoop() {
                stopVoicePlayback();
                speakNextLine();
                voiceInterval = setInterval(speakNextLine, 12000);
            }

            function startTimer(minutes = selectedDuration) {
                clearInterval(timerInterval);
                timeRemaining = minutes * 60;
                timerStatus.textContent = formatTime(timeRemaining);
                timerInterval = setInterval(() => {
                    timeRemaining -= 1;
                    timerStatus.textContent = formatTime(Math.max(timeRemaining, 0));
                    if (timeRemaining <= 0) stopSession();
                }, 1000);
            }

            function stopSession() {
                sessionActive = false;
                clearInterval(timerInterval);
                stopVoicePlayback();
                stopAllSounds();
                timeRemaining = selectedDuration * 60;
                timerStatus.textContent = formatTime(timeRemaining);
                sessionTitle.textContent = @json($isHindi ? 'शांत विराम समाप्त' : 'Calm pause complete');
                voicePhase.textContent = @json($isHindi ? 'आराम' : 'Rest');
                voiceCaption.textContent = @json($isHindi ? 'धीरे-धीरे वापस आएं' : 'Come back slowly');
                activateVisual('cyan');
                updateSelectionSummary();
                updateSessionButtons();
            }

            async function startSession() {
                await ensureAudio();
                sessionActive = true;
                sessionTitle.textContent = @json($isHindi ? 'शांत सत्र जारी है' : 'Calm session in progress');
                activateVisual('emerald');
                if (soundStates.size == 0) await toggleSound('rain');
                startTimer(selectedDuration);
                if (voiceEnabled) {
                    startVoiceLoop();
                } else {
                    voicePhase.textContent = @json($isHindi ? 'ध्वनि' : 'Sound');
                    voiceCaption.textContent = @json($isHindi ? 'केवल ध्वनि के साथ आराम करें' : 'Rest with sound only');
                }
                updateSelectionSummary();
                updateSessionButtons();
            }

            document.querySelectorAll('[data-sound-card]').forEach((card) => {
                card.addEventListener('click', async () => {
                    await toggleSound(card.dataset.soundCard);
                    activateVisual(card.dataset.soundCard === 'chimes' ? 'indigo' : card.dataset
                        .soundCard === 'brown' ? 'cyan' : 'emerald');
                });
            });

            // Toggle element styles helper
            function setElementActive(element, isActive, activeClasses, inactiveClasses) {
                activeClasses.forEach(c => {
                    if (isActive) element.classList.add(c);
                    else element.classList.remove(c);
                });
                inactiveClasses.forEach(c => {
                    if (isActive) element.classList.remove(c);
                    else element.classList.add(c);
                });
            }

            const timerActiveClasses = ['border-emerald-500', 'bg-emerald-50', 'text-emerald-700',
                'dark:bg-emerald-950/40', 'dark:text-emerald-300'
            ];
            const timerInactiveClasses = ['border-slate-200', 'bg-slate-50', 'text-slate-700', 'dark:border-slate-700',
                'dark:bg-slate-900', 'dark:text-slate-200'
            ];

            const playlistActiveClasses = ['border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-950/40'];
            const playlistInactiveClasses = ['border-slate-200/80', 'bg-slate-50/70', 'dark:border-slate-800',
                'dark:bg-slate-950/70'
            ];

            const voiceToggleBg = document.getElementById('voice-toggle-bg');
            const voiceToggleDot = document.getElementById('voice-toggle-dot');
            const voiceLabelText = document.getElementById('voice-label-text');

            function updateVoiceToggleUI() {
                if (voiceEnabled) {
                    voiceToggleBg.className =
                        "relative inline-flex h-7 w-12 shrink-0 items-center rounded-full bg-emerald-500 transition dark:bg-emerald-500";
                    voiceToggleDot.className =
                        "absolute left-1 h-5 w-5 rounded-full bg-white shadow-sm transition-transform translate-x-5";
                    voiceLabelText.textContent = @json($isHindi ? $hi['voice_on'] ?? 'आवाज़ चालू' : 'Voice on');
                } else {
                    voiceToggleBg.className =
                        "relative inline-flex h-7 w-12 shrink-0 items-center rounded-full bg-slate-200 transition dark:bg-slate-700";
                    voiceToggleDot.className =
                        "absolute left-1 h-5 w-5 rounded-full bg-white shadow-sm transition-transform translate-x-0";
                    voiceLabelText.textContent = @json($isHindi ? 'आवाज़ बंद' : 'Voice off');
                }
            }

            document.querySelectorAll('.timer-chip').forEach((chip, index) => {
                setElementActive(chip, index === 0, timerActiveClasses, timerInactiveClasses);
                chip.addEventListener('click', () => {
                    document.querySelectorAll('.timer-chip').forEach((item) => {
                        setElementActive(item, false, timerActiveClasses, timerInactiveClasses);
                    });
                    setElementActive(chip, true, timerActiveClasses, timerInactiveClasses);
                    selectedDuration = Number(chip.dataset.minutes) || 5;
                    if (!sessionActive) timerStatus.textContent = formatTime(selectedDuration * 60);
                    updateSelectionSummary();
                });
            });

            document.querySelectorAll('[data-playlist]').forEach((card, index) => {
                setElementActive(card, index === 0, playlistActiveClasses, playlistInactiveClasses);
                card.addEventListener('click', () => {
                    currentPlaylist = card.dataset.playlist;
                    voiceIndex = 0;
                    document.querySelectorAll('[data-playlist]').forEach((item) => {
                        setElementActive(item, false, playlistActiveClasses,
                            playlistInactiveClasses);
                    });
                    setElementActive(card, true, playlistActiveClasses, playlistInactiveClasses);
                    sessionTitle.textContent = card.querySelector('h3')?.textContent || sessionTitle
                        .textContent;
                    updateSelectionSummary();
                    if (voiceEnabled && sessionActive) startVoiceLoop();
                });
            });

            volumeInput.addEventListener('input', () => {
                const volume = Number(volumeInput.value) / 100;
                volumeValue.textContent = `${volumeInput.value}%`;
                if (globalGain) globalGain.gain.value = volume;
            });

            voiceToggle.addEventListener('change', () => {
                voiceEnabled = voiceToggle.checked;
                updateVoiceToggleUI();
                if (!voiceEnabled) {
                    stopVoicePlayback();
                    voicePhase.textContent = @json($isHindi ? 'ध्वनि' : 'Sound');
                    voiceCaption.textContent = @json($isHindi ? 'आवाज़ बंद है' : 'Voice is off');
                    updateSelectionSummary();
                    return;
                }
                updateSelectionSummary();
                if (sessionActive) startVoiceLoop();
            });

            playAllButton?.addEventListener('click', startSession);
            stopAllButton?.addEventListener('click', stopSession);

            // Initial Setup
            activateVisual('emerald');
            volumeValue.textContent = `${volumeInput.value}%`;
            updateVoiceToggleUI();
            updateSelectionSummary();
            updateSessionButtons();
            if (window.lucide) window.lucide.createIcons();
            else window.refreshLucideIcons?.();
        })();
    </script>
@endpush
@endsection
