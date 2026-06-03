@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'गतिविधियां' : 'Activities') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'तनाव राहत गतिविधियां | श्वास, ग्राउंडिंग और वेलनेस टूल्स' : 'Stress Relief Activities | Breathing, Grounding & Wellness Tools')
@section('meta_description', $locale === 'hi' ? 'तनाव राहत, ग्राउंडिंग, मूड चेक-इन, क्विज़ और शांत गेम्स का उपयोग करें।' : 'Use calming activities including breathing, grounding, mood check-ins, quizzes, and simple games.')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @php
        $fallbackCoreActivities = collect([
            (object) ['title_en' => 'Breathing Exercise', 'title_hi' => 'श्वास अभ्यास', 'description_en' => 'Use a calm 4-4-6-2 breathing pattern to slow down and settle your body.', 'description_hi' => 'शरीर को शांत करने के लिए 4-4-6-2 पैटर्न के साथ धीमी श्वास का अभ्यास करें।', 'route_name' => 'activities.breathing', 'tone' => 'teal', 'cta_en' => 'Start', 'cta_hi' => 'शुरू करें'],
            (object) ['title_en' => 'Grounding Exercise', 'title_hi' => 'ग्राउंडिंग अभ्यास', 'description_en' => 'Use the 5-4-3-2-1 method to return attention to the present moment.', 'description_hi' => '5-4-3-2-1 तकनीक से ध्यान को वर्तमान क्षण में वापस लाएं।', 'route_name' => 'activities.grounding', 'tone' => 'cyan', 'cta_en' => 'Start', 'cta_hi' => 'शुरू करें'],
            (object) ['title_en' => 'Mood Check-in', 'title_hi' => 'मूड चेक-इन', 'description_en' => 'Name how you feel, reflect briefly, and get pointed toward the next helpful step.', 'description_hi' => 'अपनी भावना पहचानें, थोड़ा रुककर सोचें और अगला मददगार कदम देखें।', 'route_name' => 'activities.mood-check', 'tone' => 'indigo', 'cta_en' => 'Start', 'cta_hi' => 'शुरू करें'],
            (object) ['title_en' => 'Crisis Support', 'title_hi' => 'संकट सहायता', 'description_en' => 'Open urgent support guidance immediately if you feel unsafe or overwhelmed.', 'description_hi' => 'यदि आप असुरक्षित या बहुत परेशान महसूस कर रहे हैं, तो तुरंत सहायता मार्गदर्शन खोलें।', 'route_name' => 'support.crisis', 'tone' => 'rose', 'cta_en' => 'Open', 'cta_hi' => 'खोलें'],
        ]);

        $fallbackExtraActivities = collect([
            (object) ['title_en' => 'Health Quizzes', 'title_hi' => 'हेल्थ क्विज़', 'description_en' => 'Take short awareness and self-reflection quizzes for stress and everyday health myths.', 'description_hi' => 'तनाव और रोज़मर्रा की हेल्थ जागरूकता के लिए छोटे क्विज़ लें।', 'route_name' => 'quizzes.index', 'tone' => 'indigo', 'cta_en' => 'Open', 'cta_hi' => 'खोलें'],
            (object) ['title_en' => 'Memory Game', 'title_hi' => 'मेमोरी गेम', 'description_en' => 'Play a small card-match game designed to give your mind a gentle break.', 'description_hi' => 'मन को हल्का विराम देने के लिए छोटा कार्ड-मैच गेम खेलें।', 'route_name' => 'activities.games.memory', 'tone' => 'cyan', 'cta_en' => 'Open', 'cta_hi' => 'खोलें'],
            (object) ['title_en' => 'Calm Tap Counter', 'title_hi' => 'कैल्म टैप काउंटर', 'description_en' => 'Tap gently, count your rhythm, and pair the motion with slow breathing.', 'description_hi' => 'धीरे-धीरे टैप करें, अपनी लय गिनें और इसे शांत श्वास के साथ जोड़ें।', 'route_name' => 'activities.games.calm-tap', 'tone' => 'teal', 'cta_en' => 'Open', 'cta_hi' => 'खोलें'],
        ]);

        $coreActivities = ($activityGroups ?? collect())->get('core', $fallbackCoreActivities);
        $extraActivities = ($activityGroups ?? collect())->get('extras', $fallbackExtraActivities);
    @endphp

    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'वेलनेस गतिविधियां' : 'Wellness Activities' }}</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'तनाव राहत और मानसिक वेलनेस गतिविधियां' : 'Stress Relief and Mental Wellness Activities' }}</h1>
        <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">
            {{ $locale === 'hi' ? 'ये गतिविधियां आराम, आत्म-जागरूकता और छोटे शांत विराम में मदद कर सकती हैं। ये पेशेवर मानसिक स्वास्थ्य देखभाल का विकल्प नहीं हैं। यदि आप असुरक्षित महसूस करते हैं, तो तुरंत मदद लें।' : 'These activities may help with relaxation, self-awareness, and calmer pauses. They are not a replacement for professional mental health care. If you feel unsafe, seek help immediately.' }}
        </p>
    </section>

    <section class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach($coreActivities as $card)
            <a href="{{ route($card->route_name) }}" class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 hover:-translate-y-1 transition">
                <div class="w-12 h-12 rounded-2xl bg-{{ $card->tone }}-100 dark:bg-{{ $card->tone }}-950/40 flex items-center justify-center text-{{ $card->tone }}-700 dark:text-{{ $card->tone }}-200 font-bold">•</div>
                <h2 class="mt-4 text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? $card->title_hi : $card->title_en }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? $card->description_hi : $card->description_en }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? $card->cta_hi : $card->cta_en }}</span>
            </a>
        @endforeach
    </section>

    <section class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($extraActivities as $card)
            <a href="{{ route($card->route_name) }}" class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 hover:-translate-y-1 transition">
                <div class="w-12 h-12 rounded-2xl bg-{{ $card->tone }}-100 dark:bg-{{ $card->tone }}-950/40 flex items-center justify-center text-{{ $card->tone }}-700 dark:text-{{ $card->tone }}-200 font-bold">•</div>
                <h2 class="mt-4 text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? $card->title_hi : $card->title_en }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? $card->description_hi : $card->description_en }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? $card->cta_hi : $card->cta_en }}</span>
            </a>
        @endforeach
    </section>
</main>
@endsection
