@php
    $directoryDesign = [
        'doctors' => $doctors->items(),
        'departments' => $departments,
        'cities' => $cities,
        'types' => $types,
        'filters' => $filters,
        'page' => $doctors->currentPage(),
        'pages' => $doctors->lastPage(),
        'total' => $doctors->total(),
        'from' => $doctors->firstItem(),
        'to' => $doctors->lastItem(),
    ];
@endphp
<script>window.arogioDirectory = {{ Illuminate\Support\Js::from($directoryDesign) }};</script>
<div id="arogio-directory">
    <section class="design-fallback">
        <h1>{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Find Doctors' }} — {{ $activeCity }}</h1>
        <p>{{ $locale === 'hi' ? 'जाने से पहले कॉल करके उपलब्धता की पुष्टि करें।' : 'Browse specialists and clinics. Always call to confirm availability before visiting.' }}</p>
        <form action="{{ route('doctors.index') }}" method="get"><label for="directory-fallback-search">{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Search doctors' }}</label><input id="directory-fallback-search" name="search" value="{{ $filters['search'] }}"><button type="submit">{{ $locale === 'hi' ? 'खोजें' : 'Search' }}</button></form>
        @forelse ($doctors as $doctor)<article><h2>{{ $doctor['first_name'] }} {{ $doctor['last_name'] }}</h2><p>{{ $doctor['department']['name'][$locale] ?? $doctor['department']['name']['en'] ?? '' }}</p></article>@empty<p>{{ $locale === 'hi' ? 'कोई मिलान नहीं मिला।' : 'No matching doctors found.' }}</p>@endforelse
        {{ $doctors->links() }}
    </section>
</div>
