@extends('layouts.public')

@section('title', 'Page Not Found - Arogio')
@section('meta_description', 'The page you are looking for could not be found. Continue your healthcare search with doctors, hospitals, blood banks, or support links.')
@section('meta_robots', 'noindex,follow')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex-1 w-full">
    <section class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12 text-center">
        <p class="text-sm font-semibold uppercase tracking-wider text-cyan-600 mb-3">404</p>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">Page Not Found</h1>
        <p class="text-slate-600 max-w-2xl mx-auto mb-8">
            The page may have moved or the URL may be incorrect. You can continue with trusted healthcare discovery below.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <a href="{{ route('home') }}" class="px-4 py-3 rounded-xl bg-slate-900 text-white font-semibold text-sm">Home</a>
            <a href="{{ route('doctors.index') }}" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm">Doctors</a>
            <a href="{{ route('hospitals.index') }}" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm">Hospitals</a>
            <a href="{{ route('blood_banks.index') }}" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm">Blood Banks</a>
            <a href="{{ route('contact') }}" class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 font-semibold text-sm">Contact</a>
        </div>
    </section>
</main>
@endsection

