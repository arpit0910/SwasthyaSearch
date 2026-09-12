@extends('layouts.public')

@php
    $hi = app()->getLocale() === 'hi';
    $remedyTitle = $remedy->getTranslation('title', app()->getLocale());
    $metaTitle = $remedy->getTranslation('meta_title', app()->getLocale()) ?: ($remedyTitle . ($hi ? ' - दादी-नानी के नुस्खे | Arogio' : ' - Dadi Nani Ke Nuskhe | Arogio'));
    $metaDesc = $remedy->getTranslation('meta_description', app()->getLocale()) ?: \App\Support\Seo::cleanText($remedy->getTranslation('short_description', app()->getLocale()), 160);
@endphp

@section('title', $metaTitle)
@section('meta_title', $metaTitle)
@section('meta_description', $metaDesc)
@section('meta_keywords', \App\Support\Seo::keywords([$remedy->getTranslation('title', 'en'), $remedy->getTranslation('title', 'hi'), 'दादी नानी के नुस्खे', 'घरेलू नुस्खे', 'Dadi nani ke nuskhe', 'Ayurvedic home remedies', $remedy->category?->getTranslation('name', 'en'), $remedy->category?->getTranslation('name', 'hi')]))
@section('canonical_url', route('nani-dadi.show', $remedy->slug))
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'HowTo',
    'name' => $remedyTitle,
    'description' => $metaDesc,
    'url' => route('nani-dadi.show', $remedy->slug),
    'inLanguage' => $hi ? 'hi-IN' : 'en-IN',
    'supply' => $remedy->ingredients->map(fn ($ing) => [
        '@type' => 'HowToSupply',
        'name' => $ing->getTranslation('name', app()->getLocale()),
    ])->all(),
    'step' => array_values(array_filter([
        $remedy->getTranslation('preparation', app()->getLocale()) ? [
            '@type' => 'HowToStep',
            'name' => $hi ? 'तैयारी' : 'Preparation',
            'text' => $remedy->getTranslation('preparation', app()->getLocale()),
        ] : null,
        $remedy->getTranslation('steps', app()->getLocale()) ? [
            '@type' => 'HowToStep',
            'name' => $hi ? 'विधि' : 'Method',
            'text' => $remedy->getTranslation('steps', app()->getLocale()),
        ] : null,
        $remedy->getTranslation('usage_instructions', app()->getLocale()) ? [
            '@type' => 'HowToStep',
            'name' => $hi ? 'उपयोग का तरीका' : 'Usage Instructions',
            'text' => $remedy->getTranslation('usage_instructions', app()->getLocale()),
        ] : null,
    ])),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<main class="mx-auto max-w-5xl px-4 py-10">
    <nav class="text-sm text-teal-700" aria-label="Breadcrumb">
        <a href="{{route('nani-dadi.index')}}">{{ $hi ? 'दादी-नानी के नुस्खे' : 'Nani Dadi Ke Nuskhe' }}</a> / 
        @if($remedy->category)
        <a href="{{ route('nani-dadi.category', $remedy->category->slug) }}">{{e($remedy->category->getTranslation('name',app()->getLocale()))}}</a>
        @endif
    </nav>
    <h1 class="mt-5 text-4xl font-bold">{{e($remedyTitle)}}</h1>
    <p class="mt-2 text-slate-500">{{e($remedy->getTranslation('title',$hi?'en':'hi'))}}</p>
    <div class="mt-4 inline-block rounded-full bg-teal-50 px-3 py-1 text-teal-800">{{$remedy->evidence_label}}</div>
    <p class="mt-6 text-lg text-slate-700">{{e($remedy->getTranslation('short_description',app()->getLocale()))}}</p>
    <div class="mt-8 rounded-2xl border-l-4 border-amber-500 bg-amber-50 p-5">
        <strong>{{ $hi?'महत्वपूर्ण सुरक्षा सूचना':'Important safety information' }}</strong>
        <p class="mt-2">{{e($remedy->getTranslation('medical_disclaimer',app()->getLocale()) ?: "These traditional practices are not a substitute for diagnosis, prescribed medicines or professional treatment.")}}</p>
    </div>
    <div class="mt-10 grid gap-8 md:grid-cols-2">
        @foreach(['description'=>'About','ingredients'=>'Ingredients','preparation'=>'Preparation','steps'=>'Step-by-step method','usage_instructions'=>'How to use','traditional_benefit'=>'Why it is traditionally used','how_it_may_help'=>'How it may help','suitable_for'=>'Who can use it','not_suitable_for'=>'Who should avoid it','child_warning'=>'Child safety','pregnancy_warning'=>'Pregnancy safety','elderly_warning'=>'Elderly safety','medical_condition_warning'=>'Medical condition warning','medicine_interactions'=>'Medicine interactions','possible_side_effects'=>'Possible side effects','red_flags'=>'When to stop / red flags','when_to_see_doctor'=>'When to see a doctor'] as $field=>$label) 
            @if($remedy->getTranslation($field,app()->getLocale()))
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-xl font-bold">{{ $hi ? ['description'=>'विवरण','ingredients'=>'सामग्री','preparation'=>'तैयारी','steps'=>'चरण-दर-चरण विधि','usage_instructions'=>'कैसे उपयोग करें','traditional_benefit'=>'पारंपरिक उपयोग','how_it_may_help'=>'यह कैसे मदद कर सकता है','suitable_for'=>'कौन उपयोग कर सकता है','not_suitable_for'=>'किसे बचना चाहिए','child_warning'=>'बच्चों की सुरक्षा','pregnancy_warning'=>'गर्भावस्था सुरक्षा','elderly_warning'=>'बुजुर्गों की सुरक्षा','medical_condition_warning'=>'चिकित्सा चेतावनी','medicine_interactions'=>'दवा परस्पर क्रिया','possible_side_effects'=>'संभावित दुष्प्रभाव','red_flags'=>'कब रोकें / खतरे के संकेत','when_to_see_doctor'=>'डॉक्टर से कब मिलें'][$field] : $label }}</h2>
                <div class="mt-3 whitespace-pre-line text-slate-700">{{e($remedy->getTranslation($field,app()->getLocale()))}}</div>
            </section>
            @endif 
        @endforeach
    </div>
    @if($remedy->speciality)
    <a class="mt-8 inline-block rounded-xl bg-teal-700 px-5 py-3 text-white" href="{{route('doctors.index',['department'=>$remedy->speciality->id])}}">Find a {{e($remedy->speciality->name_en)}} →</a>
    @endif
    <h2 class="mt-12 text-2xl font-bold">{{ $hi ? 'संबंधित नुस्खे' : 'Related Nuskhe' }}</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-4">
        @foreach($related as $r)
        <a class="rounded-xl border p-4 hover:border-teal-500 transition-colors" href="{{route('nani-dadi.show',$r->slug)}}">
            <h3 class="font-semibold text-slate-900">{{e($r->getTranslation('title',app()->getLocale()))}}</h3>
            <p class="mt-1 text-xs text-slate-500">{{e($r->getTranslation('short_description',app()->getLocale()))}}</p>
        </a>
        @endforeach
    </div>
</main>
@endsection
