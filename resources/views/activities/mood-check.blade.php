@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मूड चेक-इन' : 'Mood Check-in') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <h1 class="text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'मूड चेक-इन' : 'Mood Check-in' }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'हम यह जानकारी सर्वर पर स्टोर नहीं करते। यदि जवाब असुरक्षित स्थिति दिखाते हैं, तो हम सीधे संकट सहायता पर ले जाएंगे।' : 'We do not store these answers on the server. If your responses suggest an unsafe situation, we will route you directly to crisis support.' }}</p>

        <form id="mood-check-form" class="mt-6 space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'आप अभी कैसा महसूस कर रहे हैं?' : 'How are you feeling right now?' }}</label>
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach(['Calm', 'Sad', 'Angry', 'Anxious', 'Stressed', 'Tired', 'Hopeless', 'Unsafe'] as $option)
                        <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                            <input type="radio" name="feeling" value="{{ strtolower($option) }}" class="mr-2"> {{ $option }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'यह भावना कितनी तीव्र है?' : 'How intense is this feeling?' }}</label>
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach(['mild' => 'Mild', 'moderate' => 'Moderate', 'severe' => 'Severe'] as $value => $label)
                        <label class="rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                            <input type="radio" name="intensity" value="{{ $value }}" class="mr-2"> {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'क्या आपको लगता है कि आप स्वयं को या किसी और को नुकसान पहुंचा सकते हैं?' : 'Do you feel like you may harm yourself or someone else?' }}</label>
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach(['no' => 'No', 'not_sure' => 'Not sure', 'yes' => 'Yes'] as $value => $label)
                        <label class="rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                            <input type="radio" name="safety" value="{{ $value }}" class="mr-2"> {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            <button class="rounded-2xl bg-indigo-600 hover:bg-indigo-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'जारी रखें' : 'Continue' }}</button>
        </form>

        <div id="mood-safe-result" class="hidden mt-6 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/90 dark:bg-emerald-950/30 p-4 text-sm text-emerald-900 dark:text-emerald-100">
            {{ $locale === 'hi' ? 'धन्यवाद। यदि आप चाहें, तो श्वास अभ्यास या ग्राउंडिंग अभ्यास आज़मा सकते हैं। यदि स्थिति बिगड़े या असुरक्षित लगे, तो तुरंत सहायता लें।' : 'Thank you. You can try the breathing or grounding exercise next. If things worsen or feel unsafe, seek immediate help.' }}
        </div>
    </section>
</main>
@push('scripts')
<script>
    document.getElementById('mood-check-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const feeling = document.querySelector('input[name="feeling"]:checked')?.value || '';
        const intensity = document.querySelector('input[name="intensity"]:checked')?.value || '';
        const safety = document.querySelector('input[name="safety"]:checked')?.value || '';
        const needsCrisis = feeling === 'unsafe'
            || (feeling === 'hopeless' && intensity === 'severe')
            || safety === 'yes'
            || safety === 'not_sure';

        if (needsCrisis) {
            window.location.href = @json(route('support.crisis'));
            return;
        }

        document.getElementById('mood-safe-result').classList.remove('hidden');
    });
</script>
@endpush
@endsection
