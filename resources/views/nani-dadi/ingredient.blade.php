@extends('layouts.public')

@php
    $hi = app()->getLocale() === 'hi';
    $ingName = $ingredient->getTranslation('name', app()->getLocale());
    $metaTitle = $ingredient->getTranslation('meta_title', app()->getLocale()) ?: ($ingName . ($hi ? ' के फायदे व घरेलू नुस्खे | दादी-नानी के नुस्खे | Arogio' : ' Health Benefits & Home Remedies | Arogio'));
    $metaDesc = $ingredient->getTranslation('meta_description', app()->getLocale()) ?: ($ingName . ($hi ? ' से बनने वाले पारंपरिक भारतीय घरेलू नुस्खे और उनके स्वास्थ्य लाभ।' : ' traditional home remedies, health benefits, and Ayurvedic uses.'));
@endphp

@section('title', $metaTitle)
@section('meta_title', $metaTitle)
@section('meta_description', $metaDesc)
@section('meta_keywords', \App\Support\Seo::keywords([$ingName, 'दादी नानी के नुस्खे', 'घरेलू नुस्खे', 'Natural remedies', $ingredient->getTranslation('name', 'en'), $ingredient->getTranslation('name', 'hi')]))
@section('canonical_url', route('nani-dadi.ingredient', $ingredient->slug))

@section('content')
<main class="mx-auto max-w-6xl px-4 py-12">
    <a class="text-teal-700 font-medium hover:underline" href="{{route('nani-dadi.index')}}">← {{ $hi ? 'दादी-नानी के नुस्खे' : 'Nani Dadi Ke Nuskhe' }}</a>
    <h1 class="mt-5 text-4xl font-bold text-slate-900">{{e($ingName)}}</h1>
    <p class="mt-2 text-slate-500">{{e($ingredient->getTranslation('name',app()->getLocale()==='hi'?'en':'hi'))}}</p>
    <p class="mt-6 max-w-3xl text-slate-600">{{e($ingredient->getTranslation('description',app()->getLocale()))}}</p>
    <h2 class="mt-10 text-2xl font-bold text-slate-900">{{ $hi ? 'संबंधित नुस्खे' : 'Related Nuskhe' }}</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        @foreach($remedies as $r)
        <a class="rounded-xl border p-4 bg-white hover:border-teal-500 transition-colors shadow-xs" href="{{route('nani-dadi.show',$r->slug)}}">
            <h3 class="font-semibold text-slate-900">{{e($r->getTranslation('title',app()->getLocale()))}}</h3>
            <p class="mt-1 text-xs text-slate-500">{{e($r->getTranslation('short_description',app()->getLocale()))}}</p>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{$remedies->links()}}</div>
</main>
@endsection
