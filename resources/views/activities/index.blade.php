@extends('layouts.public')



@section('title', ($locale === 'hi' ? 'गतिविधियाँ' : 'Activities') . ' - Arogio')

@section('meta_title', $locale === 'hi' ? 'तनाव राहत गतिविधियाँ | श्वास, ग्राउंडिंग और शांत उपकरण' : 'Stress Relief Activities | Breathing, Grounding & Calm Tools')

@section('meta_description', $locale === 'hi' ? 'श्वास, ग्राउंडिंग, मूड चेक-इन, ऑडियो आराम, क्विज़ और सरल गेम सहित शांत गतिविधियों का उपयोग करें।' : 'Use calming activities including breathing, grounding, mood check-ins, audio comfort, quizzes, and simple games.')



@section('content')

@php

    $isHindi = $locale === 'hi';



    $primaryTools = [

        [

            'title' => $isHindi ? 'साँस लेने का व्यायाम' : 'Breathing Exercise',

            'description' => $isHindi ? 'आवाज के आधार पर शांत गति और कम घर्षण के साथ सांस लें, रोकें और छोड़ें।' : 'Voice-led inhale, hold, and exhale with calmer motion and less friction.',

            'url' => route('activities.breathing'),

            'icon' => 'wind',

            'tone' => 'teal',

            'badge' => $isHindi ? 'सबसे ज्यादा प्यारे' : 'Most loved',

            'cta' => $isHindi ? 'अब सांस लेना शुरू करें' : 'Start breathing now',

        ],

        [

            'title' => $isHindi ? 'शांत ऑडियो स्पेस' : 'Calm Audio Space',

            'description' => $isHindi ? 'मुक्त परिवेशीय ध्वनियाँ, सौम्य ध्वनि संकेत, उत्थानशील विचार और एक टाइमर-आधारित शांत सत्र।' : 'Free ambient sounds, gentle voice cues, uplifting thoughts, and a timer-based calm session.',

            'url' => route('activities.calm-audio'),

            'icon' => 'headphones',

            'tone' => 'emerald',

            'badge' => $isHindi ? 'नया' : 'New',

            'cta' => $isHindi ? 'ऑडियो स्पेस खोलें' : 'Open audio space',

        ],

        [

            'title' => $isHindi ? 'ग्राउंडिंग प्रवाह' : 'Grounding Flow',

            'description' => $isHindi ? 'चरण-दर-चरण ग्राउंडिंग जो ध्यान को कमरे और वर्तमान क्षण पर लौटने में मदद करती है।' : 'Step-by-step grounding that helps attention return to the room and the present moment.',

            'url' => route('activities.grounding'),

            'icon' => 'compass',

            'tone' => 'cyan',

            'badge' => $isHindi ? 'फोकस रीसेट' : 'Focus reset',

            'cta' => $isHindi ? 'खुली ग्राउंडिंग' : 'Open grounding',

        ],

    ];



    $supportTools = [

        [

            'title' => $isHindi ? 'मूड चेक-इन' : 'Mood Check-in',

            'description' => $isHindi ? 'इस पर विचार करें कि आप कैसा महसूस करते हैं और अगले सहायक कदम के लिए एक सौम्य सुझाव प्राप्त करें।' : 'Reflect on how you feel and get a gentle suggestion for the next helpful step.',

            'url' => route('activities.mood-check'),

            'icon' => 'heart',

            'tone' => 'indigo',

        ],

        [

            'title' => $isHindi ? 'स्वास्थ्य प्रश्नोत्तरी' : 'Health Quizzes',

            'description' => $isHindi ? 'कठोर ऊर्जा के बिना जागरूकता और आत्म-प्रतिबिंब के लिए डिज़ाइन की गई कम दबाव वाली क्विज़।' : 'Lower-pressure quizzes designed for awareness and self-reflection without harsh energy.',

            'url' => route('quizzes.index'),

            'icon' => 'brain',

            'tone' => 'violet',

        ],

        [

            'title' => $isHindi ? 'स्मृति विच्छेद' : 'Memory Break',

            'description' => $isHindi ? 'उन क्षणों के लिए एक हल्का रीसेट गेम जब आपके दिमाग को ध्यान के नरम बदलाव की आवश्यकता होती है।' : 'A light reset game for moments when your mind needs a softer shift of attention.',

            'url' => route('activities.games.memory'),

            'icon' => 'sparkles',

            'tone' => 'amber',

        ],

        [

            'title' => $isHindi ? 'ताल टैप' : 'Rhythm Tap',

            'description' => $isHindi ? 'बेचैन क्षणों के लिए एक सरल लय उपकरण जब आपके हाथों को शांत पैटर्न की आवश्यकता होती है।' : 'A simple rhythm tool for restless moments when your hands need a calmer pattern.',

            'url' => route('activities.games.calm-tap'),

            'icon' => 'hand',

            'tone' => 'rose',

        ],

        [

            'title' => $isHindi ? 'स्मार्ट तनाव जांच' : 'Smart Stress Check',

            'description' => $isHindi ? 'समर्थन संकेतों और स्पष्ट अगले चरणों के साथ चरण-दर-चरण तनाव-अनुकूल लक्षण प्रवाह।' : 'A step-by-step stress-friendly symptom flow with support cues and clear next steps.',

            'url' => route('symptom-test'),

            'icon' => 'activity',

            'tone' => 'sky',

        ],

        [

            'title' => $isHindi ? 'तत्काल सहायता' : 'Immediate Support',

            'description' => $isHindi ? 'यदि चीजें अभी बहुत भारी लगती हैं, तो बिना देर किए समर्थन-पहले विकल्प खोलें।' : 'If things feel too heavy right now, open support-first options without delay.',

            'url' => route('support.crisis'),

            'icon' => 'shield-heart',

            'tone' => 'red',

        ],

    ];

@endphp



<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <section class="relative overflow-hidden rounded-[2.25rem] border border-cyan-100/80 bg-gradient-to-br from-white via-cyan-50/50 to-emerald-50/70 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:p-8 lg:p-10">

        <div class="pointer-events-none absolute -top-20 right-0 h-64 w-64 rounded-full bg-cyan-300/15 blur-3xl"></div>

        <div class="pointer-events-none absolute -bottom-20 left-0 h-56 w-56 rounded-full bg-emerald-300/15 blur-3xl"></div>



        <div class="relative grid gap-8 xl:grid-cols-[1.1fr_0.9fr] xl:items-center">

            <div class="max-w-3xl">

                <p class="text-xs font-bold uppercase tracking-[0.24em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'कल्याण गतिविधियाँ' : 'Wellness Activities' }}</p>

                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-5xl">

                    {{ $isHindi ? 'ऐसे उपकरण जो तनाव कम करने में मदद करते हैं, न कि अधिक दबाव डालने में' : 'Tools that help stress come down, not pile on more pressure' }}

                </h1>

                <p class="mt-4 text-base leading-7 text-slate-600 dark:text-slate-300 sm:text-lg">

                    {{ $isHindi ? 'श्वास, क्विज़, चेक-इन और शांत करने वाले उपकरण अब एक नरम, अधिक आधुनिक प्रवाह में हैं, इसलिए मंच अभिभूत क्षणों में भी सहायक महसूस करता है।' : 'Breathing, quizzes, check-ins, and calming tools now sit in a softer, more modern flow so the platform feels supportive even in overwhelmed moments.' }}

                </p>

                <div class="mt-6 flex flex-wrap gap-3">

                    <a href="{{ route('activities.breathing') }}" class="inline-flex items-center justify-center rounded-[1.2rem] bg-teal-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-teal-500/20 transition hover:bg-teal-700">{{ $isHindi ? 'सांस लेने से शुरुआत करें' : 'Start with breathing' }}</a>

                    <a href="{{ route('activities.calm-audio') }}" class="inline-flex items-center justify-center rounded-[1.2rem] border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-slate-900 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">{{ $isHindi ? 'शांत ऑडियो खोलें' : 'Open calm audio' }}</a>

                </div>

            </div>



            <div class="grid gap-4 sm:grid-cols-3 xl:grid-cols-1">

                @foreach([

                    ['title' => $isHindi ? 'कम अधिभार' : 'Less overload', 'body' => $isHindi ? 'वन-स्टेप और कम-घर्षण प्रवाह वहां सबसे अधिक मदद करते हैं।' : 'One-step and low-friction flows where they help most.'],

                    ['title' => $isHindi ? 'स्पष्ट मार्गदर्शन' : 'Clear guidance', 'body' => $isHindi ? 'प्रत्येक स्क्रीन बिना शोर के अगला प्रयोग करने योग्य चरण दिखाती है।' : 'Each screen shows the next usable step without noise.'],

                    ['title' => $isHindi ? 'सौम्य ऊर्जा' : 'Gentler energy', 'body' => $isHindi ? 'दृश्य, प्रतिलिपि और नियंत्रण सभी में नरम भावनात्मक स्वर होता है।' : 'Visuals, copy, and controls all carry a softer emotional tone.'],

                ] as $point)

                    <div class="rounded-[1.5rem] border border-white/70 bg-white/80 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950/40">

                        <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $point['title'] }}</div>

                        <div class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $point['body'] }}</div>

                    </div>

                @endforeach

            </div>

        </div>

    </section>



    <section class="mt-8">

        <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">

            <h2 class="text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'सबसे पहले यहीं से शुरुआत करें' : 'Start here first' }}</h2>

            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ count($primaryTools) }} {{ $isHindi ? 'मुख्य उपकरण' : 'core tools' }}</span>

        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:gap-5 lg:grid-cols-3">

            @foreach($primaryTools as $tool)

                <a href="{{ $tool['url'] }}" class="group flex h-full flex-col rounded-[1.9rem] border border-slate-200/80 bg-white/90 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900/90 sm:p-6">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-{{ $tool['tone'] }}-100 text-{{ $tool['tone'] }}-700 dark:bg-{{ $tool['tone'] }}-950/40 dark:text-{{ $tool['tone'] }}-200">

                            <i data-lucide="{{ $tool['icon'] }}" class="h-5 w-5"></i>

                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $tool['badge'] }}</span>

                    </div>

                    <h3 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white sm:mt-5 sm:text-xl">{{ $tool['title'] }}</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300 sm:mt-3 sm:leading-7">{{ $tool['description'] }}</p>

                    <span class="mt-5 inline-flex text-sm font-bold text-teal-700 transition group-hover:translate-x-1 dark:text-teal-300">{{ $tool['cta'] }}</span>

                </a>

            @endforeach

        </div>

    </section>



    <section class="mt-10 rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 sm:p-8">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'अधिक समर्थन' : 'More support' }}</p>

                <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'विभिन्न मनोदशाओं और ऊर्जा स्तरों का समर्थन करने के और अधिक तरीके' : 'More ways to support different moods and energy levels' }}</h2>

            </div>

            <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">

                {{ count($supportTools) }} {{ $isHindi ? 'विकल्प' : 'options' }}

            </div>

        </div>



        <div class="mt-6 grid grid-cols-1 gap-4 sm:gap-5 md:grid-cols-2 xl:grid-cols-3">

            @foreach($supportTools as $tool)

                <a href="{{ $tool['url'] }}" class="group flex h-full flex-col rounded-[1.7rem] border border-slate-200/80 bg-slate-50/70 p-4 transition hover:-translate-y-0.5 hover:border-cyan-200 hover:bg-white dark:border-slate-800 dark:bg-slate-950/70 dark:hover:bg-slate-900 sm:p-5">

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-{{ $tool['tone'] }}-100 text-{{ $tool['tone'] }}-700 dark:bg-{{ $tool['tone'] }}-950/40 dark:text-{{ $tool['tone'] }}-200">

                        <i data-lucide="{{ $tool['icon'] }}" class="h-5 w-5"></i>

                    </div>

                    <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white sm:mt-4">{{ $tool['title'] }}</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300 sm:leading-7">{{ $tool['description'] }}</p>

                    <span class="mt-4 inline-flex text-sm font-bold text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'खुला' : 'Open' }}</span>

                </a>

            @endforeach

        </div>

    </section>

</main>

@endsection

