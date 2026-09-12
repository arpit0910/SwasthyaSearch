@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'डॉक्टर निर्देशिका' : 'Doctors Directory') . ' - Arogio')

@php
$seoCityInput = request('city');
$seoCity = $activeCity ?? config('healthcare.active_city', 'Jaipur');
$hasCity = true;
$pageTitle = $hasCity
    ? ($locale === 'hi' ? "{$seoCity} में डॉक्टर - Doctor Near Me | Arogio" : "Doctor Near Me in {$seoCity} - Find Top Specialists | Arogio")
    : ($locale === 'hi' ? 'मेरे पास डॉक्टर - Doctor Near Me | Arogio' : 'Doctor Near Me - Find Verified Doctors & Specialists | Arogio');
$pageDescription = $hasCity
    ? ($locale === 'hi' ? "{$seoCity} में विशेषज्ञ डॉक्टर खोजें (Doctor near me)। विभाग, अनुभव, परामर्श शुल्क और क्लिनिक विवरण के अनुसार सत्यापित डॉक्टरों से सीधे संपर्क करें।" : "Find verified doctors near me in {$seoCity} by clinical specialty, experience, clinic address, and direct contact number.")
    : ($locale === 'hi' ? 'अपने पास विशेषज्ञ डॉक्टर खोजें (Doctor near me)। क्लिनिक और परामर्श शुल्क अनुसार संपर्क करें।' : 'Find verified doctors and specialists near me. Browse by medical department, clinic, and contact details.');
@endphp

@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('meta_keywords', $locale === 'hi' ? "Doctor near me, Doctors near me, Doctors Near Me, डॉक्टर मेरे पास, {$seoCity} में डॉक्टर, best doctors near me, विशेषज्ञ डॉक्टर, क्लिनिक, Arogio" : "Doctor near me, Doctors near me, Doctors Near Me, find doctor near me, best doctors in {$seoCity}, specialist clinic, verified doctors, Arogio")

@php
$hasActiveMobileFilters = !empty(array_filter((array) request('department', [])))
    || !empty(array_filter((array) request('experience', [])))
    || !empty(array_filter((array) request('city', [])));
$isNearbyActive = filled(request('user_lat')) && filled(request('user_lng'));
$normalizeCityOption = function ($city): string {
    if (is_array($city)) {
        $city = $city['name'] ?? $city['city'] ?? $city['label'] ?? $city['value'] ?? reset($city);
    }

    return is_scalar($city) ? trim((string) $city) : '';
};

$cityOptions = collect((array) ($cities ?? []))
    ->map($normalizeCityOption)
    ->filter()
    ->unique(fn ($city) => strtolower(preg_replace('/\s+/', ' ', $city)))
    ->values();
if ($cityOptions->isEmpty() && filled($seoCity)) {
    $cityOptions = collect([$seoCity]);
}
$selectedCity = request('city');
$selectedCity = is_array($selectedCity) ? ($selectedCity[0] ?? null) : $selectedCity;
$selectedCity = filled($selectedCity) ? trim((string) $selectedCity) : ($cityOptions->first() ?? $seoCity);
$selectedDepartmentIds = collect((array) request('department', []))
    ->filter(fn ($value) => filled($value) && $value !== 'All')
    ->map(fn ($value) => (string) $value)
    ->values();
$selectedDepartmentNames = collect($departments ?? [])
    ->filter(function ($dept) use ($selectedDepartmentIds) {
        $deptId = (string) (is_array($dept) ? ($dept['id'] ?? '') : ($dept->id ?? ''));
        return $selectedDepartmentIds->contains($deptId);
    })
    ->map(function ($dept) use ($locale) {
        $deptNameEn = is_array($dept) ? ($dept['name']['en'] ?? '') : ($dept->name_en ?? '');
        $deptNameHi = is_array($dept) ? ($dept['name']['hi'] ?? '') : ($dept->name_hi ?? '');
        return $locale === 'hi' ? ($deptNameHi ?: $deptNameEn) : $deptNameEn;
    })
    ->filter()
    ->values()
    ->all();
$searchTerm = trim((string) request('search', ''));
$departmentPhrase = \App\Support\Seo::toPhrase(array_slice($selectedDepartmentNames, 0, 3));
$pageTitle = filled($searchTerm)
    ? "Doctors for {$searchTerm} in {$selectedCity} | Arogio"
    : (!empty($selectedDepartmentNames)
        ? "{$departmentPhrase} Doctors in {$selectedCity} | Arogio"
        : "Doctors Directory in {$selectedCity} | Verified Specialists | Arogio");
$pageDescription = filled($searchTerm)
    ? "Find verified doctors in {$selectedCity} related to {$searchTerm}. Check specialties, clinic details, and contact information before visiting."
    : (!empty($selectedDepartmentNames)
        ? "Browse verified {$departmentPhrase} doctors in {$selectedCity}. Compare experience, hospitals, and direct contact information."
        : "Find verified doctors in {$selectedCity} by specialty, symptoms, department, and experience. Contact hospitals and clinics directly through Arogio.");
@endphp
@section('meta_title', $pageTitle)
@section('meta_description', $pageDescription)
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $pageTitle,
    'description' => \App\Support\Seo::cleanText($pageDescription, 160),
    'url' => route('doctors.index'),
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => collect($doctors instanceof \Illuminate\Pagination\AbstractPaginator ? $doctors->items() : $doctors)
            ->take(10)
            ->values()
            ->map(fn ($doctor, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Physician',
                    'name' => trim('Dr. ' . ($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? '')),
                    'medicalSpecialty' => $doctor['department']['name']['en'] ?? null,
                    'areaServed' => $selectedCity,
                ],
            ])
            ->all(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection
@section('content')
@include('partials.directory-design')
@endsection
