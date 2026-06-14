@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'दवा जानकारी' : 'Medicine Information') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'दवा जानकारी | उपयोग, दुष्प्रभाव और सावधानियां' : 'Medicine Information | Uses, Side Effects & Precautions')
@section('meta_description', $locale === 'hi' ? 'दवाओं के उपयोग, दुष्प्रभाव, सावधानियां और चेतावनियों की सामान्य शैक्षणिक जानकारी खोजें।' : 'Search educational medicine information including uses, side effects, precautions, and warnings.')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/85 dark:bg-slate-900/85 shadow-sm p-6 sm:p-8">
        <div class="max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $locale === 'hi' ? 'दवा खोज' : 'Medicine Search' }}</p>
            <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'दवा जानकारी खोजें' : 'Search Medicine Information' }}</h1>
            <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">
                {{ $locale === 'hi' ? 'दवाओं के उपयोग, दुष्प्रभाव, सावधानियां, इंटरैक्शन और चेतावनियों की सामान्य शैक्षणिक जानकारी देखें। कोई भी दवा शुरू, बंद या बदलने से पहले डॉक्टर या फार्मासिस्ट से सलाह लें।' : 'Find educational information about medicine uses, side effects, precautions, interactions, and warnings. Always consult a doctor or pharmacist before starting, stopping, or changing a medicine.' }}
            </p>
        </div>

        <form class="mt-6" method="GET" action="{{ route('medicines.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" value="{{ $search }}" class="flex-1 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white" placeholder="{{ $locale === 'hi' ? 'दवा, ब्रांड, जेनेरिक नाम या कंपोजिशन खोजें' : 'Search medicine, brand, generic name, or composition' }}">
                <button class="rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold px-5 py-3 text-sm">{{ $locale === 'hi' ? 'खोजें' : 'Search' }}</button>
            </div>
        </form>

        <div class="mt-5 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-4 text-sm text-amber-900 dark:text-amber-100">
            <div class="font-bold">{{ $locale === 'hi' ? 'दवा सुरक्षा अस्वीकरण' : 'Medicine Safety Disclaimer' }}</div>
            <p class="mt-1">{{ $locale === 'hi' ? 'यह जानकारी केवल शैक्षणिक उद्देश्य के लिए है। यह स्व-निदान, प्रिस्क्रिप्शन या उपचार का विकल्प नहीं है।' : 'This information is for educational purposes only. It is not a substitute for diagnosis, prescription, or treatment.' }}</p>
        </div>
    </section>

    <section class="mt-8">
        @if($medicines->count())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($medicines as $medicine)
                    <a href="{{ route('medicines.show', $medicine->slug) }}" class="block group h-full">
                        <article class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 flex flex-col h-full hover:border-teal-400 dark:hover:border-teal-500 hover:shadow-md transition-all duration-300 cursor-pointer">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-950 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-300 transition-colors">
                                        {{ $medicine->name }}
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $medicine->generic_name ?: ($locale === 'hi' ? 'जेनेरिक नाम उपलब्ध नहीं' : 'Generic name not available') }}</p>
                                </div>
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold {{ $medicine->prescription_required ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200' }}">
                                    {{ $medicine->prescription_required ? ($locale === 'hi' ? 'प्रिस्क्रिप्शन' : 'Prescription') : ($locale === 'hi' ? 'सामान्य जानकारी' : 'General Info') }}
                                </span>
                            </div>

                            <div class="mt-4 flex-1 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                @if($medicine->composition)
                                    <p><span class="font-semibold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'कंपोजिशन:' : 'Composition:' }}</span> {{ $medicine->composition }}</p>
                                @endif
                                @if($medicine->category)
                                    <p><span class="font-semibold text-slate-900 dark:text-slate-100">{{ $locale === 'hi' ? 'उपयोग:' : 'Purpose:' }}</span> {{ $medicine->category }}</p>
                                @endif
                                @if(filled($medicine->getTranslation('purpose', $locale)))
                                    <p class="line-clamp-3">{{ $medicine->getTranslation('purpose', $locale) }}</p>
                                @endif
                            </div>

                            @if(!empty($medicine->brand_names))
                                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-wrap gap-2">
                                    @foreach(array_slice($medicine->brand_names, 0, 4) as $brand)
                                        <span class="rounded-full border border-cyan-200 dark:border-cyan-900/60 bg-cyan-50 dark:bg-cyan-950/40 px-3 py-1 text-xs font-semibold text-cyan-700 dark:text-cyan-200">{{ $brand }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $medicines->links('pagination::tailwind') }}
            </div>
        @else
            <div class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-8 text-center">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'कोई दवा नहीं मिली' : 'No medicine found' }}</h2>
                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'कृपया स्पेलिंग जांचें या डॉक्टर/फार्मासिस्ट से सलाह लें। अज्ञात दवा खुद से न लें।' : 'Please check the spelling or consult a doctor/pharmacist. Do not take an unknown medicine without professional advice.' }}</p>
            </div>
        @endif
    </section>
</main>
@endsection
