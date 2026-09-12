@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मुखपृष्ठ' : 'Home') . ' - Arogio')

@section('meta_title', $locale === 'hi' ? 'Arogio | Doctors, Hospitals, Blood Banks, Articles और Health Tools' : 'Arogio | Doctors, Hospitals, Blood Banks, Articles & Health Tools')
@section('meta_description', $locale === 'hi' ? 'Arogio पर अपने शहर में trusted doctors, hospitals, blood banks, health articles, symptom tools और wellness resources खोजें।' : 'Find trusted doctors, hospitals, blood banks, health articles, symptom tools, and wellness resources in your city with Arogio.')
@section('meta_keywords', $locale === 'hi' ? 'Arogio, डॉक्टर, अस्पताल, ब्लड बैंक, स्वास्थ्य लेख, symptom test, wellness tools' : 'Arogio, doctors, hospitals, blood banks, health articles, symptom test, wellness tools')
@section('content')
@php
    $homeDesign = [
        'departments' => $departments,
        'symptomUrl' => route('symptom-test'),
        'articles' => $articles->map(fn ($article) => [
            'title' => $article->getTranslation('title', $locale) ?: $article->title_en,
            'excerpt' => strip_tags($article->getTranslation('excerpt', $locale) ?: $article->excerpt_en ?: ''),
            'category' => $article->category,
            'author' => $article->author_name,
            'url' => route('articles.show', $article->id),
        ])->values(),
        'faqs' => $faqs->map(fn ($faq) => [
            'question' => $locale === 'hi' ? ($faq->question_hi ?: $faq->question_en) : $faq->question_en,
            'answer' => strip_tags(($locale === 'hi' ? ($faq->answer_hi ?: $faq->answer_en) : $faq->answer_en) ?: ''),
        ])->values(),
        'remedy' => $featuredRemedy ? [
            'title' => $featuredRemedy->getTranslation('title', $locale),
            'description' => strip_tags($featuredRemedy->getTranslation('short_description', $locale) ?: ''),
            'evidence' => $featuredRemedy->evidence_label,
            'url' => route('nani-dadi.show', $featuredRemedy->slug),
        ] : null,
        'errors' => $errors->messages(),
        'old' => collect(old())->only(['name', 'email', 'phone', 'preferred_date', 'preferred_time', 'reason', 'rating', 'category', 'comments'])->all(),
    ];
@endphp
<script>window.arogioHome = {{ Illuminate\Support\Js::from($homeDesign) }};</script>
<div id="arogio-home">
    {{-- Semantic content remains available before JavaScript loads and to non-JS clients. --}}
    <main id="main-content" class="design-fallback" tabindex="-1">
        <h1>{{ $locale === 'hi' ? 'सही स्वास्थ्य सेवा खोजें, अपने आस-पास।' : 'Find the right care, closer to you.' }}</h1>
        <p>{{ $locale === 'hi' ? 'भारत में डॉक्टर, अस्पताल, ब्लड बैंक और स्वास्थ्य जानकारी खोजें।' : 'Discover doctors, hospitals, blood banks, and health information across India.' }}</p>
        <form action="{{ route('doctors.index') }}" method="get"><label for="fallback-search">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Search doctors' }}</label><input id="fallback-search" name="search"><button type="submit">{{ $locale === 'hi' ? 'खोजें' : 'Search' }}</button></form>
        <nav><a href="{{ route('doctors.index') }}">Doctors</a><a href="{{ route('hospitals.index') }}">Hospitals & Clinics</a><a href="{{ route('blood_banks.index') }}">Blood Banks</a><a href="{{ route('symptom-test') }}">Symptom Check</a><a href="{{ route('medicines.index') }}">Medicines</a><a href="{{ route('nani-dadi.index') }}">Nani Dadi Ke Nuskhe</a></nav>
        <h2>{{ $locale === 'hi' ? 'चिकित्सा विभाग' : 'Medical departments' }}</h2>
        @foreach ($departments as $department)<a href="{{ route('doctors.index', ['department' => [$department['id']]]) }}">{{ $department['name'][$locale] ?: $department['name']['en'] }}</a> @endforeach
        <h2>{{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Health articles' }}</h2>
        @foreach ($articles as $article)<article><h3><a href="{{ route('articles.show', $article->id) }}">{{ $article->getTranslation('title', $locale) ?: $article->title_en }}</a></h3><p>{{ strip_tags($article->getTranslation('excerpt', $locale) ?: $article->excerpt_en ?: '') }}</p></article>@endforeach
        <h2>{{ $locale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions' }}</h2>
        @foreach ($homeDesign['faqs'] as $faq)<details><summary>{{ $faq['question'] }}</summary><p>{{ $faq['answer'] }}</p></details>@endforeach
        <a href="{{ route('consultations.index') }}">Video consultation</a>
    </main>
</div>
@endsection
