@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'लक्षण परीक्षण' : 'Symptom Test') . ' - Arogio')


@section('meta_title', $locale === 'hi' ? 'लक्षण परीक्षण | अरोगियो' : 'Symptom Test | Arogio')


@section('meta_description', $locale === 'hi'


    ? 'आयु, लिंग और लक्षण चरण दर चरण चुनें। संभावित स्थितियाँ, संबंधित विभाग और पूछने के लिए अगले लक्षण देखें।'


    : 'Choose age, gender, and symptoms step by step. See likely conditions, the relevant department, and the next symptoms to ask about.')





@section('content')


@php


    $isHindi = \App\Helpers\LocaleHelper::current() === 'hi';


    $stepLabels = [


        1 => $isHindi ? 'बुनियादी विवरण' : 'Basic details',


        2 => $isHindi ? 'लक्षण चुनें' : 'Choose symptoms',


        3 => $isHindi ? 'समीक्षा करें और विश्लेषण करें' : 'Review & analyze',


    ];


@endphp





<main class="py-8 sm:py-12">


    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


        <section class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-gradient-to-br from-white via-slate-50 to-cyan-50/70 p-6 shadow-xl dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/30 sm:p-8 lg:p-10">


            <div class="absolute inset-0 opacity-[0.08] dark:opacity-[0.12] bg-[radial-gradient(#14b8a6_1px,transparent_1px)] [background-size:18px_18px]"></div>


            <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">


                <div class="max-w-3xl">


                    <div class="inline-flex items-center gap-2 rounded-full border border-teal-200/80 bg-white/90 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.24em] text-teal-700 shadow-sm dark:border-teal-900/50 dark:bg-slate-950/80 dark:text-teal-300">


                        <i data-lucide="activity" class="h-4 w-4"></i>


                        {{ $locale === 'hi' ? 'स्मार्ट लक्षण परीक्षण' : 'Smart symptom test' }}


                    </div>


                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-5xl">


                        {{ $locale === 'hi' ? 'तेजी से सही दिशा में पहुंचें' : 'Get to the right direction faster' }}


                    </h1>


                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300 sm:text-lg">


                        {{ $locale === 'hi'


                            ? 'तीन सरल चरणों में आयु, लिंग और लक्षण दर्ज करें। हम संभावित निदान, संबंधित चिकित्सा विभाग और पूछने के लिए सर्वोत्तम अनुवर्ती लक्षण दिखाते हैं।'


                            : 'Enter age, gender, and symptoms in three simple steps. We show likely diagnoses, the relevant medical department, and the best follow-up symptoms to ask about.' }}


                    </p>


                    <div class="mt-6 flex flex-wrap gap-3">


                        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/70 dark:text-slate-200">


                            <i data-lucide="scan-search" class="h-4 w-4 text-teal-600 dark:text-teal-300"></i>


                            {{ $locale === 'hi' ? 'चरण-दर-चरण प्रवाह' : 'Step-by-step flow' }}


                        </div>


                        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/70 dark:text-slate-200">


                            <i data-lucide="stethoscope" class="h-4 w-4 text-teal-600 dark:text-teal-300"></i>


                            {{ $locale === 'hi' ? 'विभाग मार्गदर्शन' : 'Department guidance' }}


                        </div>


                        <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-950/70 dark:text-slate-200">


                            <i data-lucide="shield-alert" class="h-4 w-4 text-teal-600 dark:text-teal-300"></i>


                            {{ $locale === 'hi' ? 'अंतिम निदान नहीं' : 'Not a final diagnosis' }}


                        </div>


                    </div>


                </div>





                <div class="rounded-3xl border border-slate-200/80 bg-white/85 p-5 shadow-lg backdrop-blur dark:border-slate-800 dark:bg-slate-950/80 sm:p-6">


                    <div class="flex items-start gap-4">


                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-md">


                            <i data-lucide="brain-circuit" class="h-6 w-6"></i>


                        </div>


                        <div>


                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? 'आपको क्या मिलता है' : 'What you get' }}</p>


                            <h2 class="mt-1 text-xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'स्पष्ट और प्रयोग योग्य परिणाम' : 'Clear and usable results' }}</h2>


                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">


                                {{ $locale === 'hi'


                                    ? 'शीर्ष संभावित स्थिति, संबंधित विभाग, मिलान किए गए लक्षण और अगले अनुवर्ती लक्षण एक ही स्थान पर।'


                                    : 'Top likely condition, related department, matched symptoms, and next follow-up symptoms in one place.' }}


                            </p>


                        </div>


                    </div>


                </div>


            </div>


        </section>



        <section class="mt-6 grid gap-4 lg:grid-cols-[1fr_1fr_1fr]">

            <a href="{{ route('activities.breathing') }}" class="rounded-[1.6rem] border border-teal-200/80 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 dark:border-teal-900/40 dark:bg-slate-900/90">

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 dark:bg-teal-950/40 dark:text-teal-200">

                    <i data-lucide="wind" class="h-5 w-5"></i>

                </div>

                <h2 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'पहले एक शांत साँस चाहिए?' : 'Need one calm breath first?' }}</h2>

                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'यदि यह स्क्रीन थोड़ी भारी लगती है, तो 60 सेकंड के लिए निर्देशित श्वास रोकें और वापस आएँ।' : 'If this screen feels a little heavy, take a 60 second guided breathing pause and come back.' }}</p>

            </a>



            <a href="{{ route('activities.calm-audio') }}" class="rounded-[1.6rem] border border-emerald-200/80 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 dark:border-emerald-900/40 dark:bg-slate-900/90">

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-200">

                    <i data-lucide="headphones" class="h-5 w-5"></i>

                </div>

                <h2 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'निःशुल्क शांत ऑडियो' : 'Free calm audio' }}</h2>

                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'तनाव को एक पायदान नीचे लाने के लिए परिवेशीय ध्वनियों, ध्वनि संकेतों और छोटे उत्थानकारी विचारों का उपयोग करें।' : 'Use ambient sounds, voice cues, and short uplifting thoughts to bring stress down a notch.' }}</p>

            </a>



            <div class="rounded-[1.6rem] border border-cyan-200/80 bg-gradient-to-br from-cyan-50 to-white p-5 shadow-sm dark:border-cyan-900/40 dark:from-cyan-950/20 dark:to-slate-900">

                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700 dark:bg-cyan-950/40 dark:text-cyan-200">

                    <i data-lucide="shield-check" class="h-5 w-5"></i>

                </div>

                <h2 class="mt-4 text-lg font-extrabold text-slate-950 dark:text-white">{{ $isHindi ? 'सज्जन अनुस्मारक' : 'Gentle reminder' }}</h2>

                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'यह अंतिम निदान नहीं है. इसे एक दिशा उपकरण के रूप में उपयोग करें, दबाव परीक्षण के रूप में नहीं।' : 'This is not a final diagnosis. Use it as a direction tool, not as a pressure test.' }}</p>

            </div>

        </section>



        <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_380px]">

            <section class="glass-card overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white/95 shadow-xl dark:border-slate-800 dark:bg-slate-950/95">


                <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50/90 via-white to-cyan-50/60 px-4 py-5 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900 sm:px-8">


                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">


                        <div>


                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? 'चरण प्रवाह' : 'Step flow' }}</p>


                            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'लक्षण स्क्रीनिंग प्रपत्र' : 'Symptom screening form' }}</h2>


                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'प्रत्येक चरण को पूरा करें और आत्मविश्वास के साथ आगे बढ़ें।' : 'Complete each step and move forward with confidence.' }}</p>


                        </div>


                        <div class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-950/80">


                            <span class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">{{ $locale === 'hi' ? 'कदम' : 'Step' }}</span>


                            <span id="step-counter" class="text-lg font-extrabold text-slate-950 dark:text-white">1/3</span>


                        </div>


                    </div>





                    <div class="mt-5 flex gap-2 overflow-x-auto pb-1 md:grid md:grid-cols-3 md:overflow-visible md:pb-0">


                        @foreach([1, 2, 3] as $stepNumber)


                            <div class="step-indicator min-w-[220px] md:min-w-0 flex items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-semibold transition-all {{ $stepNumber === 1 ? 'बॉर्डर-स्लेट-200 बीजी-सफ़ेद टेक्स्ट-स्लेट-500 डार्क: बॉर्डर-स्लेट-800 डार्क: बीजी-स्लेट-900 डार्क: टेक्स्ट-स्लेट-400' : 'border-slate-200 bg-white text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400' }}" data-step-indicator="{{ $stepNumber }}">


                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-extrabold {{ $stepNumber === 1 ? 'बीजी-स्लेट-100 टेक्स्ट-स्लेट-500 डार्क: बीजी-स्लेट-800 डार्क: टेक्स्ट-स्लेट-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300' }}">{{ $stepNumber }}</span>


                                <span class="leading-tight">{{ $stepLabels[$stepNumber] }}</span>


                            </div>


                        @endforeach


                    </div>


                </div>





                <form id="symptom-test-form" class="space-y-6 px-4 py-6 sm:px-8 sm:py-8">


                    <input type="hidden" id="selected-symptom-ids" name="selected_symptom_ids" value="">





                    <div id="selection-summary-card" class="hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-white via-slate-50 to-teal-50/50 p-4 shadow-sm ring-1 ring-slate-100/80 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-teal-950/20 dark:ring-slate-800/70">


                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">


                            <div class="min-w-0">


                                <div class="inline-flex items-center gap-2 rounded-full border border-teal-200/80 bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-teal-700 shadow-sm dark:border-teal-900/50 dark:bg-slate-950/80 dark:text-teal-300">


                                    <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i>


                                    {{ $locale === 'hi' ? 'आपका चयन' : 'Your selection' }}


                                </div>


                                <div id="selection-summary-symptoms" class="mt-3 flex flex-wrap gap-2"></div>


                            </div>


                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-3 xl:min-w-[300px]">


                                <div class="rounded-2xl border border-slate-200/80 bg-white/90 px-3 py-3 text-center shadow-sm dark:border-slate-800 dark:bg-slate-950/80">


                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'आयु' : 'Age' }}</p>


                                    <p id="summary-age" class="mt-1 text-sm font-extrabold text-slate-950 dark:text-white">—</p>


                                </div>


                                <div class="rounded-2xl border border-slate-200/80 bg-white/90 px-3 py-3 text-center shadow-sm dark:border-slate-800 dark:bg-slate-950/80">


                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'लिंग' : 'Gender' }}</p>


                                    <p id="summary-gender" class="mt-1 truncate text-sm font-extrabold text-slate-950 dark:text-white">—</p>


                                </div>


                                <div class="col-span-2 sm:col-span-1 rounded-2xl border border-teal-200/80 bg-gradient-to-br from-teal-50 to-cyan-50 px-3 py-3 text-center shadow-sm dark:border-teal-900/40 dark:from-teal-950/30 dark:to-cyan-950/20">


                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? 'लक्षण' : 'Symptoms' }}</p>


                                    <p id="summary-symptom-count" class="mt-1 text-sm font-extrabold text-slate-950 dark:text-white">0</p>


                                </div>


                            </div>


                        </div>


                    </div>





                    <div data-step-panel="1" class="space-y-6">


                        <div class="grid gap-5 md:grid-cols-2">


                            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                                <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'आयु' : 'Age' }}</label>


                                <input id="age-input" name="age" type="number" min="0" max="120" required inputmode="numeric" placeholder="28" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3.5 text-slate-950 placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">


                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'सटीक उम्र परिणाम को बेहतर बनाने में मदद करती है।' : 'Accurate age helps improve the result.' }}</p>


                            </div>


                            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                                <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'लिंग' : 'Gender' }}</label>


                                <select id="gender-select" name="gender" required class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3.5 text-slate-950 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">


                                    <option value="">{{ $locale === 'hi' ? 'चुनना' : 'Select' }}</option>


                                    <option value="male">{{ $locale === 'hi' ? 'पुरुष' : 'Male' }}</option>


                                    <option value="female">{{ $locale === 'hi' ? 'महिला' : 'Female' }}</option>


                                    <option value="other">{{ $locale === 'hi' ? 'अन्य' : 'Other' }}</option>


                                    <option value="unknown">{{ $locale === 'hi' ? 'नहीं कहना पसंद करते हैं' : 'Prefer not to say' }}</option>


                                </select>


                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'कुछ स्थितियाँ मिलानों को रैंक करने के लिए लिंग-आधारित संकेतों का उपयोग करती हैं।' : 'Some conditions use gender-based signals to rank matches.' }}</p>


                            </div>


                        </div>





                        <div class="hidden justify-end sm:flex">


                            <button type="button" data-next-step class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200 sm:w-auto">


                                {{ $locale === 'hi' ? 'अगला कदम' : 'Next step' }}


                                <i data-lucide="arrow-right" class="h-4 w-4"></i>


                            </button>


                        </div>


                    </div>





                    <div data-step-panel="2" class="hidden space-y-6">


                        <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'लक्षण खोजें' : 'Search symptoms' }}</label>


                            <input id="symptom-filter" type="text" placeholder="{{ $locale === 'hi' ? 'बुखार, खांसी, सिरदर्द का प्रयास करें...' : 'Try fever, cough, headache...' }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3.5 text-slate-950 placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white">


                        </div>





                        <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">


                                <div>


                                    <h3 class="text-base font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'लक्षण चुनें' : 'Choose symptoms' }}</h3>


                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'कम से कम एक लक्षण चुनें.' : 'Pick at least one symptom.' }}</p>


                                </div>


                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'मोबाइल-अनुकूल चयन' : 'Mobile-friendly selection' }}</p>


                            </div>


                            <div id="symptom-grid" class="mt-4 grid gap-2 sm:grid-cols-2"></div>


                        </div>





                        <div class="rounded-3xl border border-slate-200/80 bg-gradient-to-r from-white to-teal-50/40 p-5 shadow-sm dark:border-slate-800 dark:from-slate-950 dark:to-teal-950/10">


                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">


                                <div>


                                    <h3 class="text-base font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'चयनित लक्षण' : 'Selected symptoms' }}</h3>


                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'किसी भी लक्षण को हटाने के लिए उस पर टैप करें।' : 'Tap any symptom to remove it.' }}</p>


                                </div>


                                <p id="selected-symptoms-count" class="inline-flex items-center rounded-full border border-teal-200 bg-white px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-teal-700 shadow-sm dark:border-teal-900/40 dark:bg-slate-950 dark:text-teal-300">0 {{ $locale === 'hi' ? 'चयनित' : 'selected' }}</p>


                            </div>


                            <div id="selected-symptoms" class="mt-4 min-h-[72px] rounded-2xl border border-dashed border-slate-300 bg-white/80 p-3 dark:border-slate-700 dark:bg-slate-900/80"></div>


                        </div>





                        <div class="hidden flex-col-reverse gap-3 sm:flex sm:flex-row sm:items-center sm:justify-between">


                            <button type="button" data-prev-step class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 sm:w-auto">


                                <i data-lucide="arrow-left" class="h-4 w-4"></i>


                                {{ $locale === 'hi' ? 'पीछे' : 'Back' }}


                            </button>


                            <button type="button" data-next-step class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200 sm:w-auto">


                                {{ $locale === 'hi' ? 'अगला कदम' : 'Next step' }}


                                <i data-lucide="arrow-right" class="h-4 w-4"></i>


                            </button>


                        </div>


                    </div>





                    <div data-step-panel="3" class="hidden space-y-6">


                        <div class="rounded-3xl border border-slate-200/80 bg-gradient-to-r from-slate-50 via-white to-cyan-50/60 p-5 shadow-sm dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900">


                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">


                                <div class="min-w-0">


                                    <h3 class="text-lg font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'विश्लेषण से पहले समीक्षा करें' : 'Review before analysis' }}</h3>


                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $locale === 'hi' ? 'विश्लेषण चलाने से पहले यहां अपने विवरण की पुष्टि करें।' : 'Confirm your details here before running the analysis.' }}</p>


                                </div>


                            </div>


                            <dl class="mt-4 grid gap-4 sm:grid-cols-2">


                                <div class="rounded-2xl border border-slate-200/80 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                                    <dt class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'आयु' : 'Age' }}</dt>


                                    <dd id="review-age" class="mt-1 text-lg font-bold text-slate-950 dark:text-white">—</dd>


                                </div>


                                <div class="rounded-2xl border border-slate-200/80 bg-white px-4 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                                    <dt class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'लिंग' : 'Gender' }}</dt>


                                    <dd id="review-gender" class="mt-1 text-lg font-bold text-slate-950 dark:text-white">—</dd>


                                </div>


                            </dl>


                            <div class="mt-4">


                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $locale === 'hi' ? 'चयनित लक्षण' : 'Selected symptoms' }}</p>


                                <div id="review-symptoms" class="mt-2 flex flex-wrap gap-2"></div>


                            </div>


                        </div>





                        <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                            <label class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">{{ $locale === 'hi' ? 'अतिरिक्त टिप्पणी' : 'Additional notes' }}</label>


                            <textarea id="symptom-text" name="symptom_text" rows="4" placeholder="{{ $locale === 'hi' ? 'उदाहरण के लिए: कब तक, बदतर होता जा रहा है, कोई अतिरिक्त विवरण?' : 'For example: how long, getting worse, any extra detail?' }}" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3.5 text-slate-950 placeholder:text-slate-400 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-white"></textarea>


                        </div>





                        <div class="hidden flex-col-reverse gap-3 sm:flex sm:flex-row sm:items-center sm:justify-between">


                            <button type="button" data-prev-step class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-5 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 sm:w-auto">


                                <i data-lucide="arrow-left" class="h-4 w-4"></i>


                                {{ $locale === 'hi' ? 'पीछे' : 'Back' }}


                            </button>


                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-600 to-cyan-600 px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-teal-600/20 transition hover:from-teal-700 hover:to-cyan-700 sm:w-auto">


                                <i data-lucide="search" class="h-4 w-4"></i>


                                {{ $locale === 'hi' ? 'विश्लेषण चलाएँ' : 'Run analysis' }}


                            </button>


                        </div>


                    </div>


                </form>


            </section>





            <aside id="results-section" class="order-last space-y-4 xl:sticky xl:top-24 xl:order-none xl:self-start">


                <div class="rounded-[2rem] border border-slate-200/80 bg-white/95 p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950/95">


                    <div class="flex items-center gap-3">


                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-white shadow-md">


                            <i data-lucide="brain-circuit" class="h-5 w-5"></i>


                        </div>


                        <div>


                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-teal-700 dark:text-teal-300">{{ $locale === 'hi' ? 'परिणाम क्षेत्र' : 'Results area' }}</p>


                            <h2 class="mt-1 text-lg font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'निदान एवं मार्गदर्शन' : 'Diagnosis & guidance' }}</h2>


                        </div>


                    </div>





                    <div id="results-panel" class="mt-6 space-y-4">


                        <div class="rounded-3xl border border-dashed border-slate-300 bg-gradient-to-b from-slate-50 to-cyan-50/60 p-5 dark:border-slate-700 dark:from-slate-900 dark:to-slate-900">


                            <h3 class="text-base font-bold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'शुरू करने के लिए तैयार' : 'Ready to begin' }}</h3>


                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">


                                {{ $locale === 'hi'


                                    ? 'आयु, लिंग और कम से कम एक लक्षण चुनें। फिर हम संभावित निदान, संबंधित विभाग और अगले अनुवर्ती लक्षण दिखाएंगे।'


                                    : 'Choose age, gender, and at least one symptom. Then we will show likely diagnoses, the related department, and the next follow-up symptoms.' }}


                            </p>


                        </div>


                    </div>


                </div>





                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">


                    <div class="rounded-[1.5rem] border border-slate-200/80 bg-white/95 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950/95">


                        <h3 class="font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'यह कैसे मदद करता है' : 'How it helps' }}</h3>


                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'आपका लक्षण चयन जितना बेहतर होगा, संभावित दिशा और विभाग मार्गदर्शन उतना ही अधिक उपयोगी होगा।' : 'The better your symptom selection, the more useful the likely direction and department guidance becomes.' }}</p>


                    </div>


                    <div class="rounded-[1.5rem] border border-slate-200/80 bg-white/95 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950/95">


                        <h3 class="font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'महत्वपूर्ण' : 'Important' }}</h3>


                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'यह एक मेडिकल स्क्रीनिंग टूल है, अंतिम निदान नहीं। यदि लक्षण गंभीर हों तो तुरंत डॉक्टर से संपर्क करें।' : 'This is a medical screening tool, not a final diagnosis. If symptoms are serious, contact a doctor immediately.' }}</p>


                    </div>


                </div>


            </aside>


        </div>


    </div>


</main>





<div class="sm:hidden fixed inset-x-0 bottom-0 z-40 border-t border-slate-200/80 bg-white/95 px-4 py-3 shadow-[0_-12px_30px_rgba(15,23,42,0.08)] backdrop-blur dark:border-slate-800 dark:bg-slate-950/95">


    <div class="flex items-center gap-3">


        <button type="button" id="mobile-prev-button" class="hidden min-w-[96px] items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">


            <i data-lucide="arrow-left" class="h-4 w-4"></i>


            {{ $locale === 'hi' ? 'पीछे' : 'Back' }}


        </button>


        <button type="button" id="mobile-results-button" class="hidden min-w-[96px] items-center justify-center gap-2 rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-bold text-teal-700 dark:border-teal-900/40 dark:bg-teal-950/20 dark:text-teal-300">


            <i data-lucide="sparkles" class="h-4 w-4"></i>


            {{ $locale === 'hi' ? 'परिणाम' : 'Results' }}


        </button>


        <button type="button" id="mobile-next-button" class="flex-1 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white dark:bg-white dark:text-slate-950">


            <span id="mobile-next-label">{{ $locale === 'hi' ? 'अगला कदम' : 'Next step' }}</span>


            <i data-lucide="arrow-right" class="h-4 w-4" id="mobile-next-icon"></i>


        </button>


    </div>


</div>





<script>


    const symptomCatalog = @json($symptoms);


    const locale = @json($locale);


    const isHindi = locale === 'hi';


    const selectedSymptoms = new Map();


    const symptomGrid = document.getElementById('symptom-grid');


    const selectedSymptomsContainer = document.getElementById('selected-symptoms');


    const symptomFilter = document.getElementById('symptom-filter');


    const symptomForm = document.getElementById('symptom-test-form');


    const resultsPanel = document.getElementById('results-panel');


    const selectedSymptomsIdsInput = document.getElementById('selected-symptom-ids');


    const ageInput = document.getElementById('age-input');


    const genderSelect = document.getElementById('gender-select');


    const symptomTextInput = document.getElementById('symptom-text');


    const stepCounter = document.getElementById('step-counter');


    const stepIndicators = document.querySelectorAll('[data-step-indicator]');


    const stepPanels = document.querySelectorAll('[data-step-panel]');


    const summaryAge = document.getElementById('summary-age');


    const summaryGender = document.getElementById('summary-gender');


    const summarySymptomCount = document.getElementById('summary-symptom-count');


    const summarySymptomsWrap = document.getElementById('selection-summary-symptoms');


    const selectionSummaryCard = document.getElementById('selection-summary-card');


    const selectedSymptomsCount = document.getElementById('selected-symptoms-count');


    const resultsSection = document.getElementById('results-section');


    const mobilePrevButton = document.getElementById('mobile-prev-button');


    const mobileNextButton = document.getElementById('mobile-next-button');


    const mobileNextLabel = document.getElementById('mobile-next-label');


    const mobileResultsButton = document.getElementById('mobile-results-button');


    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';


    const doctorsIndexUrl = @json(route('doctors.index'));


    let currentStep = 1;





    function translate(en, hi) {


        return isHindi ? hi : en;


    }





    function getSymptomLabel(symptom) {


        return isHindi ? (symptom.name?.hi || symptom.name?.en || '') : (symptom.name?.en || '');


    }





    function renderSymptomGrid() {


        symptomGrid.innerHTML = symptomCatalog.map((symptom) => {


            const label = getSymptomLabel(symptom);


            const searchKey = `${symptom.name?.en || ''} ${symptom.name?.hi || ''}`.toLowerCase();





            return `


                <button


                    type="button"


                    class="symptom-chip group flex w-full items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-teal-300 hover:bg-teal-50/60 hover:shadow-md dark:border-slate-800 dark:bg-slate-950 dark:hover:border-teal-700 dark:hover:bg-teal-500/10"


                    data-symptom-id="${symptom.id}"


                    data-symptom-name="${label.replace(/"/g, '&quot;')}"


                    data-symptom-name-en="${(symptom.name?.en || '').replace(/"/g, '&quot;')}"


                    data-search-key="${searchKey}"


                    aria-pressed="false">


                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-teal-700 group-hover:bg-teal-600 group-hover:text-white dark:bg-slate-900 dark:text-teal-300">


                        <i data-lucide="sparkles" class="h-4 w-4"></i>


                    </span>


                    <span class="min-w-0 flex-1">


                        <span class="block truncate text-sm font-bold text-slate-950 dark:text-white">${label}</span>


                        <span class="block text-[11px] text-slate-500">${symptom.diseases_count} ${translate('conditions', 'रोग')}</span>


                    </span>


                    <span data-selected-icon class="hidden h-8 w-8 shrink-0 items-center justify-center rounded-full bg-teal-600 text-white">


                        <i data-lucide="check" class="h-4 w-4"></i>


                    </span>


                </button>


            `;


        }).join('');





        window.refreshLucideIcons();


    }





    function updateReview() {


        const selectedGenderLabel = genderSelect.value ? (genderSelect.options[genderSelect.selectedIndex]?.text || '—') : '—';


        document.getElementById('review-age').textContent = ageInput.value || '—';


        document.getElementById('review-gender').textContent = selectedGenderLabel;


        summaryAge.textContent = ageInput.value || '—';


        summaryGender.textContent = selectedGenderLabel;


        summarySymptomCount.textContent = selectedSymptoms.size;





        const reviewWrap = document.getElementById('review-symptoms');


        reviewWrap.innerHTML = '';


        summarySymptomsWrap.innerHTML = '';





        if (selectedSymptoms.size === 0) {


            const emptyLabel = `<span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">${translate('No symptoms selected yet.', 'अभी कोई लक्षण नहीं चुना गया।')}</span>`;


            reviewWrap.innerHTML = `<span class="text-sm text-slate-500">${translate('Please pick at least one symptom.', 'कृपया कम से कम 1 लक्षण चुनें।')}</span>`;


            summarySymptomsWrap.innerHTML = emptyLabel;


            return;


        }





        selectedSymptoms.forEach((symptom) => {


            const reviewPill = document.createElement('span');


            reviewPill.className = 'inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200';


            reviewPill.textContent = symptom.label;


            reviewWrap.appendChild(reviewPill);





            const summaryPill = document.createElement('span');


            summaryPill.className = 'inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-800 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200';


            summaryPill.textContent = symptom.label;


            summarySymptomsWrap.appendChild(summaryPill);


        });


    }





    function renderSelectedSymptoms() {


        selectedSymptomsContainer.innerHTML = '';


        selectedSymptomsCount.textContent = `${selectedSymptoms.size} ${translate('selected', 'चुने गए')}`;





        if (selectedSymptoms.size === 0) {


            selectedSymptomsContainer.innerHTML = `<span class="text-sm text-slate-500">${translate('No symptoms selected yet.', 'अभी कोई लक्षण नहीं चुना गया।')}</span>`;


            selectedSymptomsIdsInput.value = '';


            updateReview();


            return;


        }





        selectedSymptoms.forEach((symptom) => {


            const button = document.createElement('button');


            button.type = 'button';


            button.className = 'inline-flex items-center gap-2 rounded-full border border-teal-200 bg-teal-50 px-3 py-2 text-sm font-semibold text-teal-800 transition hover:border-teal-300 hover:bg-teal-100 dark:border-teal-500/20 dark:bg-teal-500/10 dark:text-teal-200';


            button.innerHTML = `<span class="max-w-[180px] truncate sm:max-w-none">${symptom.label}</span><span class="rounded-full bg-white px-2 py-0.5 text-[10px] font-bold text-teal-700 dark:bg-slate-900 dark:text-teal-200">×</span>`;


            button.addEventListener('click', () => {


                selectedSymptoms.delete(symptom.id);


                syncSelectedState();


            });


            selectedSymptomsContainer.appendChild(button);


        });





        selectedSymptomsIdsInput.value = JSON.stringify(Array.from(selectedSymptoms.keys()));


        updateReview();


    }





    function syncSelectedState() {


        document.querySelectorAll('.symptom-chip').forEach((chip) => {


            const symptomId = chip.dataset.symptomId;


            const isActive = selectedSymptoms.has(symptomId);


            chip.classList.toggle('border-teal-500', isActive);


            chip.classList.toggle('bg-teal-50/80', isActive);


            chip.classList.toggle('ring-2', isActive);


            chip.classList.toggle('ring-teal-500/10', isActive);


            chip.classList.toggle('shadow-sm', isActive);


            chip.setAttribute('aria-pressed', isActive ? 'असत्य' : 'false');


            chip.querySelector('[data-selected-icon]')?.classList.toggle('hidden', !isActive);


            chip.querySelector('[data-selected-icon]')?.classList.toggle('flex', isActive);


        });





        renderSelectedSymptoms();


    }





    function filterSymptoms() {


        const query = (symptomFilter.value || '').trim().toLowerCase();


        document.querySelectorAll('.symptom-chip').forEach((chip) => {


            const searchKey = chip.dataset.searchKey || '';


            chip.classList.toggle('hidden', query !== '' && !searchKey.includes(query));


        });


    }





    function updateMobileActionBar() {


        if (!mobileNextButton || !mobilePrevButton || !mobileNextLabel || !mobileResultsButton) return;





        mobilePrevButton.classList.toggle('hidden', currentStep === 1);


        mobilePrevButton.classList.toggle('flex', currentStep > 1);





        mobileResultsButton.classList.toggle('hidden', currentStep !== 3);


        mobileResultsButton.classList.toggle('flex', currentStep === 3);





        if (currentStep === 3) {


            mobileNextLabel.textContent = translate('Run analysis', 'जांच शुरू करें');


        } else {


            mobileNextLabel.textContent = translate('Next step', 'अगला चरण');


        }


    }





    function updateSelectionSummaryVisibility() {


        if (!selectionSummaryCard) return;





        const shouldShow = currentStep > 1 || selectedSymptoms.size > 0;


        selectionSummaryCard.classList.toggle('hidden', !shouldShow);


    }





    function setStep(step) {


        currentStep = Math.min(3, Math.max(1, step));


        stepCounter.textContent = `${currentStep}/3`;





        stepIndicators.forEach((indicator) => {


            const indicatorStep = Number(indicator.dataset.stepIndicator);


            const active = indicatorStep === currentStep;


            const done = indicatorStep < currentStep;





            indicator.classList.toggle('border-teal-500', active);


            indicator.classList.toggle('bg-teal-50', active);


            indicator.classList.toggle('text-teal-800', active);


            indicator.classList.toggle('dark:border-teal-500/40', active);


            indicator.classList.toggle('dark:bg-teal-950/30', active);


            indicator.classList.toggle('dark:text-teal-200', active);





            indicator.classList.toggle('border-slate-200', !active && !done);


            indicator.classList.toggle('bg-white', !active && !done);


            indicator.classList.toggle('text-slate-500', !active && !done);


            indicator.classList.toggle('dark:border-slate-800', !active && !done);


            indicator.classList.toggle('dark:bg-slate-900', !active && !done);


            indicator.classList.toggle('dark:text-slate-400', !active && !done);





            indicator.classList.toggle('bg-emerald-50', done);


            indicator.classList.toggle('border-emerald-200', done);


            indicator.classList.toggle('text-emerald-700', done);


        });





        stepPanels.forEach((panel) => {


            panel.classList.toggle('hidden', Number(panel.dataset.stepPanel) !== currentStep);


        });





        updateReview();


        updateSelectionSummaryVisibility();


        updateMobileActionBar();


    }





    function validateStep(step) {


        if (step === 1) {


            if (!ageInput.value || !genderSelect.value) {


                alert(translate('Please enter age and select gender before continuing.', 'आगे बढ़ने से पहले उम्र और लिंग चुनें।'));


                return false;


            }


        }





        if (step === 2 && selectedSymptoms.size === 0) {


            alert(translate('Please choose at least one symptom.', 'कृपया कम से कम 1 लक्षण चुनें।'));


            return false;


        }





        return true;


    }





    function scrollResultsIntoView() {


        resultsSection?.scrollIntoView({ behavior: 'smooth', block: 'start' });


    }





    function renderResultsLoading() {


        resultsPanel.innerHTML = `


            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900">


                <div class="flex items-center gap-3">


                    <span class="h-10 w-10 rounded-2xl border-2 border-teal-500 border-t-transparent animate-spin"></span>


                    <div>


                        <h3 class="text-base font-bold text-slate-950 dark:text-white">${translate('Running analysis', 'विश्लेषण चल रहा है')}</h3>


                        <p class="text-sm text-slate-600 dark:text-slate-300">${translate('Calculating likely conditions and follow-up symptoms.', 'संभावित रोग और अगले लक्षणों की गणना हो रही है।')}</p>


                    </div>


                </div>


            </div>


        `;


        window.refreshLucideIcons();


        scrollResultsIntoView();


    }





    function renderError(message) {


        resultsPanel.innerHTML = `


            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-5 text-rose-800 dark:border-rose-900/50 dark:bg-rose-950/20 dark:text-rose-100">


                <h3 class="text-base font-bold">${message}</h3>


            </div>


        `;


        window.refreshLucideIcons();


        scrollResultsIntoView();


    }





    async function submitAnalysis() {


        if (selectedSymptoms.size === 0) {


            alert(translate('Please choose at least one symptom.', 'कृपया कम से कम 1 लक्षण चुनें।'));


            setStep(2);


            return;


        }





        renderResultsLoading();





        try {


            const response = await fetch(@json(route('symptom-test.analyze')), {


                method: 'POST',


                headers: {


                    'Content-Type': 'application/json',


                    'Accept': 'application/json',


                    'X-CSRF-TOKEN': csrfToken,


                },


                body: JSON.stringify({


                    age: ageInput.value,


                    gender: genderSelect.value,


                    symptom_text: symptomTextInput.value,


                    symptoms: Array.from(selectedSymptoms.values()).map((symptom) => symptom.englishName),


                }),


            });





            const data = await response.json();





            if (!response.ok && response.status !== 422) {


                throw new Error(data.message || 'Unexpected error');


            }





            if (response.status === 422) {


                renderError(data.message || translate('Please add a few symptoms.', 'कृपया कुछ लक्षण जोड़ें।'));


                return;


            }





            renderResults(data);


        } catch (error) {


            renderError(error.message || translate('Something went wrong.', 'कुछ गलत हो गया।'));


        }


    }





    function renderResults(data) {


        const conditions = data.likely_conditions || [];


        const nextSymptoms = data.next_symptoms || [];


        const topCondition = conditions[0] || null;


        const recommendedDepartment = data.recommended_department || topCondition?.department || null;


        const doctorLink = recommendedDepartment?.id


            ? `${doctorsIndexUrl}?department=${encodeURIComponent(recommendedDepartment.id)}`


            : doctorsIndexUrl;





        if (!conditions.length) {


            renderError(data.message || translate('No close match found.', 'कोई सटीक मिलान नहीं मिला।'));


            return;


        }





        resultsPanel.innerHTML = `


            <div class="rounded-3xl border border-teal-200 bg-gradient-to-br from-teal-50 to-cyan-50 p-5 dark:border-teal-900/40 dark:bg-teal-500/10">


                <div class="flex items-start justify-between gap-4">


                    <div class="min-w-0">


                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-teal-700 dark:text-teal-300">${translate('Top diagnosis', 'शीर्ष संभावना')}</p>


                        <h3 class="mt-1 text-xl font-extrabold text-slate-950 dark:text-white">${topCondition?.name?.[isHindi ? 'एन' : 'en'] || topCondition?.name?.en || ''}</h3>


                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">${data.message || translate('Analysis complete', 'विश्लेषण पूरा हुआ')}</p>


                    </div>


                    <div class="rounded-2xl bg-slate-950 px-4 py-3 text-right text-white dark:bg-white dark:text-slate-950">


                        <p class="text-[11px] uppercase tracking-[0.2em] text-slate-300 dark:text-slate-500">${translate('Match', 'मिलान')}</p>


                        <p class="text-2xl font-extrabold">${topCondition?.coverage ?? 0}%</p>


                    </div>


                </div>


                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">


                    <div class="rounded-2xl bg-white/80 px-4 py-3 dark:bg-slate-950/70">


                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Department', 'विभाग')}</p>


                        <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${recommendedDepartment ? (recommendedDepartment.name?.[isHindi ? 'एन' : 'en'] || recommendedDepartment.name?.en || '') : translate('General evaluation', 'सामान्य जांच')}</p>


                    </div>


                    <div class="rounded-2xl bg-white/80 px-4 py-3 dark:bg-slate-950/70">


                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Matched symptoms', 'मिलते लक्षण')}</p>


                        <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${(topCondition?.matched_symptoms || []).length}</p>


                    </div>


                    <div class="rounded-2xl bg-white/80 px-4 py-3 dark:bg-slate-950/70">


                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Confidence score', 'स्कोर')}</p>


                        <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${topCondition?.score ?? 0}</p>


                    </div>


                </div>


                <div class="mt-4 flex flex-col gap-3 sm:flex-row">


                    <a href="${doctorLink}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200 sm:w-auto">


                        <i data-lucide="stethoscope" class="h-4 w-4"></i>


                        ${recommendedDepartment ? translate('Find doctors in this department', 'इस विभाग के डॉक्टर देखें') : translate('Find doctors', 'डॉक्टर देखें')}


                    </a>


                    <button type="button" id="refine-results-button" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-teal-200 bg-white px-4 py-3 text-sm font-bold text-teal-700 transition hover:border-teal-300 hover:bg-teal-50 dark:border-teal-900/40 dark:bg-slate-950 dark:text-teal-200 sm:w-auto">


                        <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>


                        ${translate('Refine with more symptoms', 'और लक्षण जोड़कर सुधारें')}


                    </button>


                </div>


                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">${data.disclaimer || ''}</p>


            </div>





            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                <h4 class="text-sm font-extrabold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">${translate('What you selected', 'आपने क्या चुना')}</h4>


                <div class="mt-3 flex flex-wrap gap-2">


                    ${(data.selected_symptoms || []).map((symptom) => `


                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">


                            ${symptom.name?.[isHindi ? 'एन' : 'en'] || symptom.name?.en || ''}


                        </span>


                    `).join('')}


                </div>


            </div>





            <div class="space-y-4">


                ${conditions.map((condition, index) => `


                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">


                        <div class="flex items-start justify-between gap-3">


                            <div class="min-w-0">


                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-teal-600 dark:text-teal-300">${index === 0 ? translate('Most likely', 'सबसे संभावित') : translate('Other match', 'अन्य संभावना')}</p>


                                <h4 class="mt-1 text-lg font-extrabold text-slate-950 dark:text-white">${condition.name?.[isHindi ? 'एन' : 'en'] || condition.name?.en || ''}</h4>


                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">${condition.department ? (condition.department.name?.[isHindi ? 'एन' : 'en'] || condition.department.name?.en || '') : translate('General evaluation', 'सामान्य जांच')}</p>


                            </div>


                            <div class="rounded-2xl bg-slate-950 px-4 py-2 text-right text-white dark:bg-white dark:text-slate-950">


                                <p class="text-[11px] uppercase tracking-[0.2em] text-slate-300 dark:text-slate-500">${translate('Score', 'स्कोर')}</p>


                                <p class="text-2xl font-extrabold">${condition.score}</p>


                            </div>


                        </div>





                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">


                            <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-900">


                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Coverage', 'कवरेज')}</p>


                                <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${condition.coverage}%</p>


                            </div>


                            <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-900">


                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Matched', 'मिले')}</p>


                                <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${(condition.matched_symptoms || []).length}</p>


                            </div>


                            <div class="col-span-2 rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-900 sm:col-span-1">


                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">${translate('Need to confirm', 'पुष्टि के लिए')}</p>


                                <p class="mt-1 text-sm font-bold text-slate-950 dark:text-white">${(condition.remaining_symptoms || []).length}</p>


                            </div>


                        </div>





                        <div class="mt-4">


                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">${translate('Symptoms that match', 'मिलते हुए लक्षण')}</p>


                            <div class="flex flex-wrap gap-2">


                                ${(condition.matched_symptoms || []).map((symptom) => `


                                    <span class="inline-flex items-center rounded-full border border-teal-200 bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-800 dark:border-teal-500/20 dark:bg-teal-500/10 dark:text-teal-200">


                                        ${symptom.name?.[isHindi ? 'एन' : 'en'] || symptom.name?.en || ''}


                                    </span>


                                `).join('')}


                            </div>


                        </div>





                        ${(condition.remaining_symptoms || []).length ? `


                            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">


                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">${translate('Next symptoms to ask about', 'अगले पूछने वाले लक्षण')}</p>


                                <div class="mt-3 flex flex-wrap gap-2">


                                    ${(condition.remaining_symptoms || []).map((symptom) => `


                                        <button type="button" class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:text-teal-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" data-follow-up-symptom="${symptom.id}" data-follow-up-name="${(symptom.name?.[isHindi ? 'एन' : 'en'] || symptom.name?.en || '').replace(/"/g, '&quot;')}" data-follow-up-name-en="${(symptom.name?.en || '').replace(/"/g, '&quot;')}">


                                            ${symptom.name?.[isHindi ? 'एन' : 'en'] || symptom.name?.en || ''}


                                        </button>


                                    `).join('')}


                                </div>


                            </div>


                        ` : ''}


                    </article>


                `).join('')}


            </div>


            ${(nextSymptoms || []).length ? `


                <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-5 dark:border-cyan-900/40 dark:bg-cyan-500/10">


                    <h4 class="text-sm font-extrabold uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-200">${translate('Add more symptoms', 'और लक्षण जोड़ें')}</h4>


                    <div class="mt-3 flex flex-wrap gap-2">


                        ${(nextSymptoms || []).map((symptom) => `


                            <button type="button" class="rounded-full border border-cyan-200 bg-white px-3 py-1.5 text-xs font-semibold text-cyan-800 transition hover:border-cyan-300 hover:text-cyan-900 dark:border-cyan-900/40 dark:bg-slate-950 dark:text-cyan-200" data-follow-up-symptom="${symptom.id}" data-follow-up-name="${(symptom.label || '').replace(/"/g, '&quot;')}" data-follow-up-name-en="${(symptom.name?.en || '').replace(/"/g, '&quot;')}">


                                ${symptom.label}


                            </button>


                        `).join('')}


                    </div>


                </div>


            ` : ''}


        `;


        window.refreshLucideIcons();


        scrollResultsIntoView();





        document.getElementById('refine-results-button')?.addEventListener('click', () => {


            setStep(2);


            symptomFilter.focus();


            window.scrollTo({ top: symptomFilter.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });


        });





        document.querySelectorAll('[data-follow-up-symptom]').forEach((button) => {


            button.addEventListener('click', () => {


                const symptomId = button.dataset.followUpSymptom;


                const label = button.dataset.followUpName || '';


                const englishName = button.dataset.followUpNameEn || '';





                if (!selectedSymptoms.has(symptomId)) {


                    selectedSymptoms.set(symptomId, {


                        id: symptomId,


                        label,


                        englishName,


                    });


                    syncSelectedState();


                }





                setStep(2);


                symptomFilter.focus();


                window.scrollTo({ top: symptomFilter.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });


            });


        });


    }





    symptomGrid.addEventListener('click', (event) => {


        const chip = event.target.closest('.symptom-chip');


        if (!chip) return;





        const symptomId = chip.dataset.symptomId;


        const label = chip.dataset.symptomName || '';


        const englishName = chip.dataset.symptomNameEn || '';





        if (!selectedSymptoms.has(symptomId)) {


            selectedSymptoms.set(symptomId, { id: symptomId, label, englishName });


        } else {


            selectedSymptoms.delete(symptomId);


        }





        syncSelectedState();


    });





    symptomFilter.addEventListener('input', filterSymptoms);


    ageInput.addEventListener('input', updateReview);


    genderSelect.addEventListener('change', updateReview);





    document.querySelectorAll('[data-next-step]').forEach((button) => {


        button.addEventListener('click', () => {


            if (!validateStep(currentStep)) return;


            setStep(currentStep + 1);


        });


    });





    document.querySelectorAll('[data-prev-step]').forEach((button) => {


        button.addEventListener('click', () => {


            setStep(currentStep - 1);


        });


    });





    mobilePrevButton?.addEventListener('click', () => {


        setStep(currentStep - 1);


    });





    mobileNextButton?.addEventListener('click', () => {


        if (currentStep === 3) {


            submitAnalysis();


            return;


        }





        if (!validateStep(currentStep)) return;


        setStep(currentStep + 1);


    });





    mobileResultsButton?.addEventListener('click', () => {


        scrollResultsIntoView();


    });





    symptomForm.addEventListener('submit', async (event) => {


        event.preventDefault();


        submitAnalysis();


    });





    renderSymptomGrid();


    renderSelectedSymptoms();


    setStep(1);


    filterSymptoms();


    updateSelectionSummaryVisibility();


    updateMobileActionBar();


    window.refreshLucideIcons();


</script>


@endsection


