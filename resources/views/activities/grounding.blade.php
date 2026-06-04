@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'ग्राउंडिंग व्यायाम' : 'Grounding Exercise') . ' - Arogio')





@section('content')


@php


    $isHindi = $locale === 'hi';


    $steps = [


        [


            'count' => '5',


            'short' => $isHindi ? 'देखना' : 'See',


            'title' => $isHindi ? '5 चीजें जो आप देख सकते हैं' : '5 things you can see',


            'hint' => $isHindi ? 'अपने आस-पास के रंगों, रोशनी, कोनों या आकृतियों पर ध्यान दें।' : 'Notice colours, light, corners, or shapes around you.',


            'placeholder' => $isHindi ? 'उदाहरण: खिड़की, नीली बोतल, कुर्सी...' : 'Example: window, blue bottle, chair...',


        ],


        [


            'count' => '4',


            'short' => $isHindi ? 'अनुभव करना' : 'Feel',


            'title' => $isHindi ? '4 चीजें जिन्हें आप महसूस कर सकते हैं' : '4 things you can feel',


            'hint' => $isHindi ? 'अपने कपड़ों, कुर्सी, हवा, या फर्श पर अपने पैरों पर ध्यान दें।' : 'Notice your clothes, the chair, the air, or your feet on the floor.',


            'placeholder' => $isHindi ? 'उदाहरण: ठंडी कुर्सी, पैर फर्श पर...' : 'Example: cool chair, feet on the floor...',


        ],


        [


            'count' => '3',


            'short' => $isHindi ? 'सुनो' : 'Hear',


            'title' => $isHindi ? '3 चीजें जो आप सुन सकते हैं' : '3 things you can hear',


            'hint' => $isHindi ? 'पास की और दूर की दोनों ध्वनियों को गिनें।' : 'Count both nearby and distant sounds.',


            'placeholder' => $isHindi ? 'उदाहरण: पंखा, दूर का यातायात, कोई बात कर रहा है...' : 'Example: fan, distant traffic, someone talking...',


        ],


        [


            'count' => '2',


            'short' => $isHindi ? 'गंध' : 'Smell',


            'title' => $isHindi ? '2 चीजें जिन्हें आप सूंघ सकते हैं' : '2 things you can smell',


            'hint' => $isHindi ? 'यदि कुछ भी स्पष्ट नहीं है, तो बस कमरे या हवा की गंध पर ध्यान दें।' : 'If nothing is obvious, just notice the smell of the room or air.',


            'placeholder' => $isHindi ? 'उदाहरण: चाय, साबुन, कमरे की ताज़ी हवा...' : 'Example: tea, soap, fresh room air...',


        ],


        [


            'count' => '1',


            'short' => $isHindi ? 'स्वाद' : 'Taste',


            'title' => $isHindi ? '1 चीज़ जिसका आप स्वाद ले सकते हैं' : '1 thing you can taste',


            'hint' => $isHindi ? 'अपने मुँह में किसी बचे हुए स्वाद, पानी, चाय या यहाँ तक कि किसी तटस्थ स्वाद पर ध्यान दें।' : 'Notice any remaining taste in your mouth, water, tea, or even a neutral taste.',


            'placeholder' => $isHindi ? 'उदाहरण: पानी, पुदीना, तटस्थ स्वाद...' : 'Example: water, mint, neutral taste...',


        ],


    ];


@endphp


<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">


    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10">


        <div class="grid gap-8 xl:grid-cols-[0.85fr_1.15fr]">


            <div class="space-y-6">


                <div>


                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'ग्राउंडिंग' : 'Grounding' }}</p>


                    <h1 class="mt-3 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? 'एक-एक करके धीरे-धीरे वर्तमान में लौटें' : 'Return to the present one gentle step at a time' }}</h1>


                    <p class="mt-4 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'एक ही बार में सब कुछ दिखाने के बजाय, यह संस्करण आपको एक समय में एक ही केंद्रित कदम पर रखता है। इससे दबाव कम हो जाता है और आपका ध्यान केंद्रित करना आसान हो जाता है।' : 'Instead of showing everything at once, this version keeps you on one focused step at a time. That reduces overwhelm and makes it easier to settle your attention.' }}</p>


                </div>





                <div class="rounded-[1.6rem] border border-cyan-100 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 p-5">


                    <div class="flex items-center justify-between gap-3">


                        <p class="text-sm font-bold text-cyan-900 dark:text-cyan-100">{{ $isHindi ? 'प्रगति' : 'Progress' }}</p>


                        <span id="grounding-progress-text" class="text-sm font-semibold text-cyan-800 dark:text-cyan-200">1 / 5</span>


                    </div>


                    <div class="mt-3 h-2 rounded-full bg-white/80 dark:bg-slate-800 overflow-hidden">


                        <div id="grounding-progress-bar" class="h-full w-[20%] rounded-full bg-gradient-to-r from-cyan-500 to-teal-500 transition-all duration-300"></div>


                    </div>


                    <p id="grounding-progress-label" class="mt-4 text-sm font-semibold leading-6 text-cyan-900 dark:text-cyan-100">{{ $isHindi ? 'धीरे-धीरे आगे बढ़ें. इसमें जल्दबाजी करने की कोई जरूरत नहीं है.' : 'Move slowly. There is no need to rush this.' }}</p>


                </div>





                <div class="grid gap-3">


                    @foreach($steps as $index => $step)


                        <button type="button" data-grounding-step="{{ $index }}" class="grounding-step-btn flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/30 px-4 py-3 text-left transition">


                            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-white dark:bg-slate-900 text-sm font-extrabold text-cyan-700 dark:text-cyan-300 shadow-sm">{{ $step['count'] }}</span>


                            <span>


                                <span class="block text-sm font-bold text-slate-900 dark:text-white">{{ $step['short'] }}</span>


                                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $step['title'] }}</span>


                            </span>


                        </button>


                    @endforeach


                </div>


            </div>





            <div class="space-y-5">


                <div class="rounded-[1.8rem] border border-slate-200 dark:border-slate-800 bg-gradient-to-br from-white to-cyan-50/70 dark:from-slate-900 dark:to-slate-950 p-6 sm:p-7 shadow-sm">


                    <div class="flex items-center justify-between gap-3">


                        <div>


                            <p id="grounding-current-count" class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">5 · See</p>


                            <h2 id="grounding-current-title" class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-white">{{ $steps[0]['title'] }}</h2>


                        </div>


                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 font-extrabold text-lg shadow-sm" id="grounding-current-badge">{{ $steps[0]['count'] }}</div>


                    </div>





                    <p id="grounding-current-hint" class="mt-4 rounded-2xl bg-cyan-50 dark:bg-cyan-950/30 px-4 py-3 text-sm leading-6 text-cyan-900 dark:text-cyan-100">{{ $steps[0]['hint'] }}</p>





                    <label class="mt-5 block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'आप जो नोटिस करते हैं उसे लिख लें' : 'Write down what you notice' }}</label>


                    <textarea id="grounding-textarea" class="mt-3 w-full rounded-[1.25rem] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 px-4 py-4 text-sm leading-7 text-slate-900 dark:text-slate-100 focus:border-cyan-400 focus:ring-cyan-400" rows="5" placeholder="{{ $steps[0]['placeholder'] }}"></textarea>





                    <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">


                        <button id="grounding-clear-step" type="button" class="rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? 'इस चरण को साफ़ करें' : 'Clear this step' }}</button>


                        <div class="flex gap-3">


                            <button id="grounding-prev" type="button" class="rounded-[1.1rem] border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $isHindi ? 'पीछे' : 'Back' }}</button>


                            <button id="grounding-next" type="button" class="rounded-[1.1rem] bg-cyan-600 px-5 py-3 text-sm font-bold text-white hover:bg-cyan-700">{{ $isHindi ? 'अगला' : 'Next' }}</button>


                        </div>


                    </div>


                </div>





                <div id="grounding-complete-card" class="hidden rounded-[1.6rem] border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/90 dark:bg-emerald-950/30 p-5">


                    <p class="text-sm font-bold text-emerald-900 dark:text-emerald-100">{{ $isHindi ? 'अच्छी तरह से किया।' : 'Nicely done.' }}</p>


                    <p class="mt-2 text-sm leading-6 text-emerald-900 dark:text-emerald-100">{{ $isHindi ? 'एक पल के लिए रुकें और ध्यान दें कि क्या अब आपकी सांस, कंधे या जबड़ा थोड़ा शांत महसूस हो रहा है।' : 'Pause for a moment and notice whether your breath, shoulders, or jaw feel even a little calmer now.' }}</p>


                </div>


            </div>


        </div>


    </section>


</main>





@push('scripts')


<script>


(() => {


    const steps = @json($steps);


    const isHindi = @json($isHindi);


    const storageKey = 'grounding-answers-v2';


    const textarea = document.getElementById('grounding-textarea');


    const countEl = document.getElementById('grounding-current-count');


    const titleEl = document.getElementById('grounding-current-title');


    const hintEl = document.getElementById('grounding-current-hint');


    const badgeEl = document.getElementById('grounding-current-badge');


    const progressTextEl = document.getElementById('grounding-progress-text');


    const progressBarEl = document.getElementById('grounding-progress-bar');


    const progressLabelEl = document.getElementById('grounding-progress-label');


    const completeCard = document.getElementById('grounding-complete-card');


    const nextButton = document.getElementById('grounding-next');


    const prevButton = document.getElementById('grounding-prev');


    const clearButton = document.getElementById('grounding-clear-step');


    const stepButtons = [...document.querySelectorAll('[data-grounding-step]')];





    let answers = {};


    let currentStep = 0;





    try {


        answers = JSON.parse(sessionStorage.getItem(storageKey) || '{}');


    } catch (error) {


        answers = {};


    }





    const saveAnswers = () => {


        sessionStorage.setItem(storageKey, JSON.stringify(answers));


    };





    const completionCount = () => steps.filter((_, index) => (answers[index] || '').trim().length > 0).length;





    const updateProgress = () => {


        const completed = completionCount();


        progressTextEl.textContent = `${currentStep + 1} / ${steps.length}`;


        progressBarEl.style.width = `${((currentStep + 1) / steps.length) * 100}%`;


        progressLabelEl.textContent = completed === 0


            ? (isHindi ? 'धीरे-धीरे आगे बढ़ें. इसमें जल्दबाजी करने की कोई जरूरत नहीं है.' : 'Move slowly. There is no need to rush this.')


            : completed === steps.length


                ? (isHindi ? 'सभी चरण पूरे हो गए हैं. अब अपने शरीर में किसी भी प्रकार की कोमलता पर ध्यान दें।' : 'All steps are done. Now notice any softness in your body.')


                : (isHindi ? 'एक केंद्रित कदम के साथ रहना ग्राउंडिंग का ही हिस्सा है।' : 'Staying with one focused step is part of grounding itself.');


        completeCard.classList.toggle('hidden', completed !== steps.length);


    };





    const updateStepButtons = () => {


        stepButtons.forEach((button, index) => {


            button.classList.toggle('border-cyan-400', index === currentStep);


            button.classList.toggle('bg-cyan-50', index === currentStep);


            button.classList.toggle('dark:bg-cyan-950/30', index === currentStep);


        });


    };





    const render = () => {


        const step = steps[currentStep];


        countEl.textContent = `${step.count} · ${step.short}`;


        titleEl.textContent = step.title;


        hintEl.textContent = step.hint;


        badgeEl.textContent = step.count;


        textarea.placeholder = step.placeholder;


        textarea.value = answers[currentStep] || '';


        prevButton.disabled = currentStep === 0;


        prevButton.classList.toggle('opacity-50', currentStep === 0);


        nextButton.textContent = currentStep === steps.length - 1


            ? (isHindi ? 'खत्म करना' : 'Finish')


            : (isHindi ? 'अगला' : 'Next');


        updateStepButtons();


        updateProgress();


    };





    textarea.addEventListener('input', () => {


        answers[currentStep] = textarea.value;


        saveAnswers();


        updateProgress();


    });





    nextButton.addEventListener('click', () => {


        answers[currentStep] = textarea.value;


        saveAnswers();


        if (currentStep < steps.length - 1) {


            currentStep += 1;


            render();


        } else {


            updateProgress();


            completeCard.classList.remove('hidden');


        }


    });





    prevButton.addEventListener('click', () => {


        answers[currentStep] = textarea.value;


        saveAnswers();


        if (currentStep > 0) {


            currentStep -= 1;


            render();


        }


    });





    clearButton.addEventListener('click', () => {


        answers[currentStep] = '';


        textarea.value = '';


        saveAnswers();


        updateProgress();


    });





    stepButtons.forEach((button) => {


        button.addEventListener('click', () => {


            answers[currentStep] = textarea.value;


            saveAnswers();


            currentStep = Number(button.dataset.groundingStep || 0);


            render();


        });


    });





    render();


})();


</script>


@endpush


@endsection


