@extends('layouts.public')

@php
    $hi = app()->getLocale() === 'hi';
    $catName = $category->getTranslation('name', app()->getLocale());
    $metaTitle = $category->getTranslation('meta_title', app()->getLocale()) ?: ($catName . ($hi ? ' के घरेलू नुस्खे | दादी-नानी के नुस्खे | Arogio' : ' Home Remedies | Dadi Nani Ke Nuskhe | Arogio'));
    $metaDesc = $category->getTranslation('meta_description', app()->getLocale()) ?: ($catName . ($hi ? ' के लिए पारंपरिक भारतीय घरेलू व आयुर्वेदिक नुस्खे देखें।' : ' traditional Indian home remedies and Ayurvedic treatments.'));
@endphp

@section('title', $metaTitle)
@section('meta_title', $metaTitle)
@section('meta_description', $metaDesc)
@section('meta_keywords', \App\Support\Seo::keywords([$catName, 'दादी नानी के नुस्खे', 'घरेलू नुस्खे', 'Home remedies', $category->getTranslation('name', 'en'), $category->getTranslation('name', 'hi')]))
@section('canonical_url', route('nani-dadi.category', $category->slug))

@section('content')
<main class="mx-auto max-w-6xl px-4 py-12">
    <a class="text-teal-700 font-medium hover:underline" href="{{route('nani-dadi.index')}}">← {{ $hi ? 'दादी-नानी के नुस्खे' : 'Nani Dadi Ke Nuskhe' }}</a>
    <h1 class="mt-5 text-4xl font-bold text-slate-900">{{e($catName)}}</h1>
    <p class="mt-3 max-w-3xl text-slate-600">{{e($category->getTranslation('description',app()->getLocale()))}}</p>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($remedies as $r)
        <a href="{{route('nani-dadi.show',$r->slug)}}" class="rounded-2xl border bg-white p-5 shadow-sm hover:shadow-md hover:border-teal-500 transition-all">
            <h2 class="text-xl font-semibold text-slate-900">{{e($r->getTranslation('title',app()->getLocale()))}}</h2>
            <p class="mt-2 text-slate-600 text-sm">{{e($r->getTranslation('short_description',app()->getLocale()))}}</p>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{$remedies->links()}}</div>
</main>
@endsection
