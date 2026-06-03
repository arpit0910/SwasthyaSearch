@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'संकट सहायता' : 'Crisis Support') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'तत्काल मानसिक स्वास्थ्य सहायता | जयपुर आपातकालीन मदद' : 'Immediate Mental Health Support | Jaipur Emergency Help')
@section('meta_description', $locale === 'hi' ? 'यदि आप असुरक्षित महसूस कर रहे हैं, तो जयपुर में तुरंत सहायता विकल्प देखें।' : 'If you feel unsafe, see immediate support options and emergency help in Jaipur.')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-rose-200/80 dark:border-rose-900/40 bg-white/95 dark:bg-slate-950/95 shadow-sm p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-rose-700 dark:text-rose-300">{{ $locale === 'hi' ? 'तत्काल सहायता' : 'Immediate Support' }}</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'आप तुरंत सहायता के योग्य हैं' : 'You deserve immediate support' }}</h1>
        <p class="mt-4 text-base leading-8 text-slate-700 dark:text-slate-300">
            {{ $locale === 'hi' ? 'यदि आपको लगता है कि आप स्वयं को या किसी और को नुकसान पहुंचा सकते हैं, तो अभी मदद लें। स्थानीय इमरजेंसी सेवाओं से संपर्क करें, जयपुर के नज़दीकी अस्पताल जाएं, या किसी भरोसेमंद व्यक्ति को तुरंत साथ रहने के लिए बुलाएं।' : 'If you feel you may harm yourself or someone else, seek urgent help now. Contact local emergency services, go to the nearest hospital in Jaipur, or call a trusted person who can stay with you.' }}
        </p>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('hospitals.index') }}" class="rounded-2xl bg-rose-600 hover:bg-rose-700 px-5 py-4 text-sm font-bold text-white text-center">{{ $locale === 'hi' ? 'जयपुर के इमरजेंसी अस्पताल देखें' : 'View Emergency Hospitals in Jaipur' }}</a>
            <a href="{{ route('doctors.index') }}" class="rounded-2xl bg-slate-950 hover:bg-slate-800 px-5 py-4 text-sm font-bold text-white text-center">{{ $locale === 'hi' ? 'जयपुर में मनोचिकित्सक खोजें' : 'Find Psychiatrist in Jaipur' }}</a>
            <a href="tel:" onclick="alert('{{ $locale === 'hi' ? 'कृपया अपने भरोसेमंद व्यक्ति को अभी कॉल करें।' : 'Please call your trusted person now.' }}'); return false;" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-5 py-4 text-sm font-bold text-slate-900 dark:text-slate-100 text-center">{{ $locale === 'hi' ? 'भरोसेमंद व्यक्ति को कॉल करें' : 'Call Trusted Person' }}</a>
            <button type="button" onclick="document.getElementById('safety-plan').classList.toggle('hidden')" class="rounded-2xl border border-cyan-200 dark:border-cyan-800/60 bg-cyan-50 dark:bg-cyan-950/30 px-5 py-4 text-sm font-bold text-cyan-900 dark:text-cyan-100">{{ $locale === 'hi' ? 'सुरक्षा कदम देखें' : 'Open Safety Steps' }}</button>
        </div>

        <div id="safety-plan" class="hidden mt-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-5">
            <h2 class="text-lg font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'अभी के लिए छोटे सुरक्षा कदम' : 'Small safety steps for right now' }}</h2>
            <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-700 dark:text-slate-300">
                <li>{{ $locale === 'hi' ? 'अकेले न रहें। किसी भरोसेमंद व्यक्ति के पास जाएं या उन्हें बुलाएं।' : 'Do not stay alone. Go to a trusted person or ask them to come to you.' }}</li>
                <li>{{ $locale === 'hi' ? 'यदि संभव हो तो हानिकारक चीज़ों को अपने आसपास से दूर करें।' : 'Move harmful items away from you if possible.' }}</li>
                <li>{{ $locale === 'hi' ? 'इमरजेंसी सहायता, अस्पताल या किसी सहायक व्यक्ति से संपर्क करें।' : 'Contact emergency help, a hospital, or a supportive person immediately.' }}</li>
            </ul>
        </div>

        <div class="mt-6 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-4 text-sm text-amber-900 dark:text-amber-100">
            {{ $locale === 'hi' ? 'ये गतिविधियां पेशेवर मानसिक स्वास्थ्य देखभाल का विकल्प नहीं हैं। यदि आपको लगता है कि स्थिति असुरक्षित है, तो सामान्य गतिविधियों के बजाय तुरंत सहायता लें।' : 'These activities are not a replacement for professional mental health care. If the situation feels unsafe, seek urgent help instead of relying on normal activities.' }}
        </div>
    </section>
</main>
@endsection
