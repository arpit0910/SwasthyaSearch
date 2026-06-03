@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'ग्राउंडिंग अभ्यास' : 'Grounding Exercise') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <h1 class="text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? '5-4-3-2-1 ग्राउंडिंग अभ्यास' : '5-4-3-2-1 Grounding Exercise' }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'अपने आस-पास की चीज़ों को नोटिस करें। ये जवाब आपके ब्राउज़र में ही रहेंगे।' : 'Notice what is around you. These responses stay only in your browser.' }}</p>

        <div class="mt-6 space-y-4">
            @foreach([
                $locale === 'hi' ? '5 चीज़ें जो आप देख सकते हैं' : '5 things you can see',
                $locale === 'hi' ? '4 चीज़ें जो आप महसूस कर सकते हैं' : '4 things you can feel',
                $locale === 'hi' ? '3 चीज़ें जो आप सुन सकते हैं' : '3 things you can hear',
                $locale === 'hi' ? '2 चीज़ें जो आप सूंघ सकते हैं' : '2 things you can smell',
                $locale === 'hi' ? '1 चीज़ जो आप स्वाद में महसूस कर सकते हैं' : '1 thing you can taste',
            ] as $index => $label)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                    <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $label }}</label>
                    <textarea class="grounding-input mt-3 w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm" rows="2" data-key="grounding-{{ $index }}"></textarea>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button id="grounding-save" class="rounded-2xl bg-cyan-600 hover:bg-cyan-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'पूरा करें' : 'Complete' }}</button>
            <button id="grounding-clear" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $locale === 'hi' ? 'साफ करें' : 'Clear' }}</button>
        </div>

        <div id="grounding-message" class="hidden mt-6 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/90 dark:bg-emerald-950/30 p-4 text-sm text-emerald-900 dark:text-emerald-100">
            {{ $locale === 'hi' ? 'आपने ग्राउंडिंग अभ्यास पूरा किया। देखें क्या आपका शरीर थोड़ा शांत महसूस कर रहा है। यदि परेशानी बनी रहे या असुरक्षित लगे, तो किसी भरोसेमंद व्यक्ति या मानसिक स्वास्थ्य पेशेवर से संपर्क करें।' : 'You completed the grounding exercise. Notice whether your body feels even slightly calmer. If distress continues or feels unsafe, consider contacting a trusted person or mental health professional.' }}
        </div>
    </section>
</main>
@push('scripts')
<script>
    document.querySelectorAll('.grounding-input').forEach((input) => {
        const key = input.dataset.key;
        input.value = sessionStorage.getItem(key) || '';
        input.addEventListener('input', () => sessionStorage.setItem(key, input.value));
    });
    document.getElementById('grounding-save').addEventListener('click', () => {
        document.getElementById('grounding-message').classList.remove('hidden');
    });
    document.getElementById('grounding-clear').addEventListener('click', () => {
        document.querySelectorAll('.grounding-input').forEach((input) => {
            sessionStorage.removeItem(input.dataset.key);
            input.value = '';
        });
        document.getElementById('grounding-message').classList.add('hidden');
    });
</script>
@endpush
@endsection
