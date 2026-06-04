@extends('layouts.public')



@section('title', ($locale === 'hi' ? 'प्रश्नोत्तरी परिणाम' : 'Quiz Result') . ' - Arogio')



@section('content')

@php

    $isHindi = $locale === 'hi';

@endphp



<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <section class="rounded-[2rem] border border-violet-100/80 bg-gradient-to-br from-white via-violet-50/40 to-cyan-50/50 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900/90 dark:via-slate-900/90 dark:to-slate-950 sm:p-8">

        <div class="grid gap-6 lg:grid-cols-[1fr_320px] lg:items-start">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.22em] text-violet-700 dark:text-violet-300">{{ $isHindi ? 'आपका परिणाम' : 'Your result' }}</p>

                <h1 class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'इसे सौम्य दृष्टि से पढ़ें' : 'Read this with a gentle lens' }}</h1>

                <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">

                    {{ $isHindi ? 'यह आत्म-चिंतन के लिए है, आपको आंकने के लिए नहीं। जो उपयोगी लगे उसे ले लो और जो उपयोगी न लगे उसे छोड़ दो।' : 'This is for self-reflection, not for judging you. Take what feels useful and leave what does not.' }}

                </p>

            </div>



            <div class="rounded-[1.6rem] border border-white/80 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/90">

                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $isHindi ? 'अंक' : 'Score' }}</div>

                <div class="mt-2 text-5xl font-extrabold text-violet-700 dark:text-violet-300">{{ $score }}</div>

                <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">

                    <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-cyan-500" style="width: {{ min(100, max(12, $score)) }}%"></div>

                </div>

            </div>

        </div>



        <div class="mt-6 rounded-[1.75rem] border border-slate-200/80 bg-white/90 p-5 dark:border-slate-800 dark:bg-slate-900/90 sm:p-6">

            @if($result)

                <h2 class="text-2xl font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? ($result['title_hi'] ?? $result['title_en']) : ($result['title_en'] ?? $result['title_hi']) }}</h2>

                <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? ($result['message_hi'] ?? $result['message_en']) : ($result['message_en'] ?? $result['message_hi']) }}</p>

            @endif

        </div>



        <div class="mt-6 grid gap-4 sm:gap-5 md:grid-cols-3">

            @foreach([

                [

                    'title' => $isHindi ? 'एक मिनट श्वास' : 'One minute breathing',

                    'description' => $isHindi ? 'अगर इसे पढ़कर आपका दिमाग दौड़ने लगा है, तो पहले खुली सांस लें।' : 'If reading this made your mind race, open breathing first.',

                    'url' => route('activities.breathing'),

                    'icon' => 'wind',

                    'tone' => 'teal',

                ],

                [

                    'title' => $isHindi ? 'शांत ऑडियो स्थान' : 'Calm audio space',

                    'description' => $isHindi ? 'परिवेशीय ध्वनि और धीमी आवाज-निर्देशित विराम के साथ समझौता करें।' : 'Settle with ambient sound and a softer voice-guided pause.',

                    'url' => route('activities.calm-audio'),

                    'icon' => 'headphones',

                    'tone' => 'emerald',

                ],

                [

                    'title' => $isHindi ? 'मूड चेक-इन' : 'Mood check-in',

                    'description' => $isHindi ? 'यदि आप थोड़ी अधिक स्पष्टता चाहते हैं, तो आगे मूड चेक-इन का प्रयास करें।' : 'If you want a little more clarity, try the mood check-in next.',

                    'url' => route('activities.mood-check'),

                    'icon' => 'heart',

                    'tone' => 'indigo',

                ],

            ] as $card)

                <a href="{{ $card['url'] }}" class="flex h-full flex-col rounded-[1.5rem] border border-slate-200/80 bg-white/90 p-4 shadow-sm transition hover:-translate-y-0.5 dark:border-slate-800 dark:bg-slate-900/90 sm:p-5">

                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-{{ $card['tone'] }}-100 text-{{ $card['tone'] }}-700 dark:bg-{{ $card['tone'] }}-950/40 dark:text-{{ $card['tone'] }}-200">

                        <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>

                    </div>

                    <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white sm:mt-4">{{ $card['title'] }}</h3>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300 sm:leading-7">{{ $card['description'] }}</p>

                    <span class="mt-4 inline-flex text-sm font-bold text-violet-700 dark:text-violet-300">{{ $isHindi ? 'खुला' : 'Open' }}</span>

                </a>

            @endforeach

        </div>



        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50/90 p-4 text-sm leading-7 text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100">

            {{ $isHindi ? ($quiz->disclaimer_hi ?: $quiz->disclaimer_en) : ($quiz->disclaimer_en ?: $quiz->disclaimer_hi) }}

        </div>



        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">

            <a href="{{ route('quizzes.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-900 dark:border-slate-700 dark:text-slate-100">{{ $isHindi ? 'अधिक क्विज़ खोजें' : 'Explore more quizzes' }}</a>

            <a href="{{ route('support.crisis') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">{{ $isHindi ? 'यदि आप असुरक्षित महसूस करते हैं तो सहायता प्राप्त करें' : 'Get support if you feel unsafe' }}</a>

        </div>

    </section>

</main>

@endsection

