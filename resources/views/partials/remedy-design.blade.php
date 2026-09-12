@php
    $locale = app()->getLocale();
    $remedyItems = isset($remedies) ? collect($remedies->items()) : collect($related ?? []);
    $featured = $remedy ?? (isset($remedies) && $remedies->currentPage() === 1 ? $remedyItems->first() : null);
    $remedyDesign = [
        'detail' => isset($remedy),
        'featured' => $featured ? \App\Support\RemedyDesign::item($featured) : null,
        'items' => $remedyItems->map(fn ($r) => \App\Support\RemedyDesign::item($r))->values(),
        'categories' => ($categories ?? collect())->map(fn ($c) => ['name' => $c->getTranslation('name', $locale), 'url' => route('nani-dadi.category', $c->slug)])->values(),
        'ingredients' => ($ingredients ?? collect())->map(fn ($i) => ['name' => $i->getTranslation('name', $locale), 'url' => route('nani-dadi.ingredient', $i->slug)])->values(),
        'context' => isset($category) ? $category->getTranslation('name', $locale) : (isset($ingredient) ? $ingredient->getTranslation('name', $locale) : null),
        'contextDescription' => isset($ingredient) ? strip_tags($ingredient->getTranslation('precautions', $locale) ?: '') : null,
        'query' => $q ?? '',
        'page' => isset($remedies) ? $remedies->currentPage() : 1,
        'pages' => isset($remedies) ? $remedies->lastPage() : 1,
        'total' => isset($remedies) ? $remedies->total() : $remedyItems->count(),
    ];
@endphp
<script>window.arogioRemedies = {{ Illuminate\Support\Js::from($remedyDesign) }};</script>
<div id="arogio-remedies"><section class="design-fallback"><h1>{{ $locale === 'hi' ? 'नानी दादी के नुस्खे' : 'Nani Dadi Ke Nuskhe' }}</h1>
    <p>{{ $locale === 'hi' ? 'पारंपरिक नुस्खे, प्रमाण और सुरक्षा जानकारी के साथ।' : 'Traditional remedies, with evidence and safety information.' }}</p>
    @if ($featured)<article><h2>{{ $featured->getTranslation('title', $locale) }}</h2><p>{{ strip_tags($featured->getTranslation('short_description', $locale) ?: '') }}</p></article>@endif
    @foreach ($remedyItems as $r)<a href="{{ route('nani-dadi.show', $r->slug) }}">{{ $r->getTranslation('title', $locale) }}</a>@endforeach
    @if (isset($remedies)){{ $remedies->links() }}@endif
</section></div>
