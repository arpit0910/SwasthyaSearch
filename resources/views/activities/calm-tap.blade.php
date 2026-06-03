@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'कैल्म टैप' : 'Calm Tap Counter') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-teal-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8 text-center">
        <h1 class="text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'कैल्म टैप काउंटर' : 'Calm Tap Counter' }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'धीरे-धीरे टैप करें और अपनी सांस पर ध्यान दें।' : 'Tap slowly and pair it with a gentle breath.' }}</p>
        <div id="tap-count" class="mt-8 text-6xl font-extrabold text-teal-700 dark:text-teal-300">0</div>
        <button id="tap-button" class="mt-8 w-48 h-48 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 text-white text-xl font-bold shadow-2xl hover:scale-105 transition">{{ $locale === 'hi' ? 'टैप' : 'Tap' }}</button>
        <div class="mt-6">
            <button id="tap-reset" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'रीसेट' : 'Reset' }}</button>
        </div>
    </section>
</main>
@push('scripts')
<script>
(() => {
    let count = 0;
    const countEl = document.getElementById('tap-count');
    document.getElementById('tap-button').addEventListener('click', () => {
        count += 1;
        countEl.textContent = count;
    });
    document.getElementById('tap-reset').addEventListener('click', () => {
        count = 0;
        countEl.textContent = count;
    });
})();
</script>
@endpush
@endsection
