@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'मूड चेक-इन' : 'Mood Check-in') . ' - Arogio')





@section('content')


@php($isHindi = \App\Helpers\LocaleHelper::current() === 'hi')


<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">


    <section class="rounded-[2rem] border border-indigo-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10">


        <div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">


            <div class="space-y-6">


                <div>


                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-indigo-700 dark:text-indigo-300">{{ $isHindi ? 'मूड सपोर्ट' : 'Mood Support' }}</p>


                    <h1 class="mt-3 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? 'दो मिनट का चेक-इन दबाव नहीं बढ़ाता' : 'A two-minute check-in that does not add pressure' }}</h1>


                    <p class="mt-4 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'यह निदान के लिए नहीं है. यहां बस यह समझना है कि इस समय कौन सा अगला कदम आपके लिए सबसे सौम्य और सबसे उपयोगी हो सकता है।' : 'This is not for diagnosis. It is simply here to understand which next step might be the gentlest and most helpful for you right now.' }}</p>


                </div>





                <div class="rounded-[1.6rem] border border-indigo-100 dark:border-indigo-900/40 bg-indigo-50/80 dark:bg-indigo-950/20 p-5">


                    <div class="flex items-center justify-between gap-3">


                        <p class="text-sm font-bold text-indigo-900 dark:text-indigo-100">{{ $isHindi ? 'प्रगति' : 'Progress' }}</p>


                        <span id="mood-progress" class="text-sm font-semibold text-indigo-800 dark:text-indigo-200">0/3</span>


                    </div>


                    <div class="mt-3 h-2 rounded-full bg-white/80 dark:bg-slate-800 overflow-hidden">


                        <div id="mood-progress-bar" class="h-full w-0 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 transition-all duration-300"></div>


                    </div>


                    <p class="mt-4 text-sm leading-6 text-indigo-900 dark:text-indigo-100">{{ $isHindi ? 'आपके उत्तर सर्वर पर संग्रहीत नहीं हैं. यदि कोई सुरक्षा संबंधी चिंता है, तो हम सीधे आपकी सहायता लेंगे।' : 'Your answers are not stored on the server. If there is a safety concern, we will take you straight to support.' }}</p>


                </div>





                <div id="mood-guidance" class="rounded-[1.6rem] border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/30 p-5">


                    <p class="text-sm font-bold text-slate-950 dark:text-white">{{ $isHindi ? 'लाइव मार्गदर्शन' : 'Live guidance' }}</p>


                    <p id="mood-guidance-text" class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'जैसे ही आप अपना उत्तर चुनेंगे, हम शांत अगला कदम सुझाएंगे।' : 'As you choose your answers, we will suggest a calm next step.' }}</p>


                </div>


            </div>





            <div>


                <form id="mood-check-form" class="space-y-5">


                    <div class="rounded-[1.6rem] border border-slate-200 dark:border-slate-800 p-5 sm:p-6">


                        <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'अभी कौन सा एहसास सबसे करीब महसूस होता है?' : 'Which feeling feels closest right now?' }}</label>


                        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">


                            @foreach(['Calm', 'Sad', 'Angry', 'Anxious', 'Stressed', 'Tired', 'Hopeless', 'Unsafe'] as $option)


                                <label class="mood-option flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 cursor-pointer transition text-center">


                                    <input type="radio" name="feeling" value="{{ strtolower($option) }}" class="sr-only">


                                    <span>{{ $option }}</span>


                                </label>


                            @endforeach


                        </div>


                    </div>





                    <div class="rounded-[1.6rem] border border-slate-200 dark:border-slate-800 p-5 sm:p-6">


                        <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'वह भावना कितनी प्रबल है?' : 'How strong is that feeling?' }}</label>


                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">


                            @foreach(['mild' => 'Mild', 'moderate' => 'Moderate', 'severe' => 'Severe'] as $value => $label)


                                <label class="mood-option flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-4 text-sm font-semibold text-slate-700 dark:text-slate-200 cursor-pointer transition text-center">


                                    <input type="radio" name="intensity" value="{{ $value }}" class="sr-only">


                                    <span>{{ $label }}</span>


                                </label>


                            @endforeach


                        </div>


                    </div>





                    <div class="rounded-[1.6rem] border border-slate-200 dark:border-slate-800 p-5 sm:p-6">


                        <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'क्या आपको ऐसा लगता है कि आप खुद को या किसी और को नुकसान पहुंचा सकते हैं?' : 'Do you feel like you may harm yourself or someone else?' }}</label>


                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">


                            @foreach(['no' => 'No', 'not_sure' => 'Not sure', 'yes' => 'Yes'] as $value => $label)


                                <label class="mood-option flex items-center justify-center rounded-2xl border border-slate-200 dark:border-slate-800 px-4 py-4 text-sm font-semibold text-slate-700 dark:text-slate-200 cursor-pointer transition text-center">


                                    <input type="radio" name="safety" value="{{ $value }}" class="sr-only">


                                    <span>{{ $label }}</span>


                                </label>


                            @endforeach


                        </div>


                    </div>





                    <div class="flex flex-wrap gap-3">


                        <button class="rounded-[1.15rem] bg-indigo-600 hover:bg-indigo-700 px-6 py-3 text-sm font-bold text-white">{{ $isHindi ? 'अगला उपयोगी चरण देखें' : 'See the next helpful step' }}</button>


                        <a href="{{ route('activities.breathing') }}" class="rounded-[1.15rem] border border-slate-200 dark:border-slate-700 px-6 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">{{ $isHindi ? 'सीधे साँस लेने पर जाएँ' : 'Go straight to breathing' }}</a>


                    </div>


                </form>





                <div id="mood-safe-result" class="hidden mt-6 rounded-[1.6rem] border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/90 dark:bg-emerald-950/30 p-5">


                    <p class="text-sm font-bold text-emerald-900 dark:text-emerald-100">{{ $isHindi ? 'अगला कदम सुझाया' : 'Suggested next step' }}</p>


                    <p id="mood-safe-copy" class="mt-2 text-sm leading-6 text-emerald-900 dark:text-emerald-100">{{ $isHindi ? 'साँस लेने या ग्राउंडिंग गतिविधि से आपको अभी सबसे अधिक मदद मिल सकती है।' : 'A breathing or grounding activity may help you most right now.' }}</p>


                    <div class="mt-4 flex flex-wrap gap-3">


                        <a href="{{ route('activities.breathing') }}" class="rounded-[1.05rem] bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">{{ $isHindi ? 'सांस लेना शुरू करें' : 'Start breathing' }}</a>


                        <a href="{{ route('activities.grounding') }}" class="rounded-[1.05rem] border border-emerald-300 dark:border-emerald-800 px-5 py-3 text-sm font-bold text-emerald-900 dark:text-emerald-100">{{ $isHindi ? 'ग्राउंडिंग का प्रयास करें' : 'Try grounding' }}</a>


                    </div>


                </div>


            </div>


        </div>


    </section>


</main>





@push('scripts')


<script>


(() => {


    const form = document.getElementById('mood-check-form');


    const progress = document.getElementById('mood-progress');


    const progressBar = document.getElementById('mood-progress-bar');


    const guidance = document.getElementById('mood-guidance-text');


    const result = document.getElementById('mood-safe-result');


    const resultCopy = document.getElementById('mood-safe-copy');


    const isHindi = @json($isHindi);





    const guidanceMap = {


        calm: isHindi ? 'आप अपेक्षाकृत स्थिर लग रहे हैं. प्रकाश प्रतिबिंब या शांत करने वाली गतिविधि उपयोगी हो सकती है।' : 'You seem relatively steady. A light reflection or calming activity may be useful.',


        sad: isHindi ? 'एक नरम, ग्राउंडिंग गतिविधि अभी मदद कर सकती है।' : 'A softer, grounding activity may help right now.',


        angry: isHindi ? 'पहले शरीर को धीमा करने से अक्सर मदद मिलती है - एक लंबी साँस छोड़ना एक अच्छा अगला कदम है।' : 'Slowing the body first often helps — a long exhale is a good next step.',


        anxious: isHindi ? 'निर्देशित श्वास और वर्तमान क्षण पर ध्यान अक्सर चिंता से निपटने में मदद करता है।' : 'Guided breathing and present-moment focus often help with anxiety.',


        stressed: isHindi ? 'तनाव अधिक होने पर छोटे शांत विराम अक्सर सबसे अधिक मदद करते हैं।' : 'Small calm pauses often help most when stress is high.',


        tired: isHindi ? 'आपके शरीर को धीमी गति और थोड़े समय के रीसेट की आवश्यकता हो सकती है।' : 'Your body may need a gentler pace and a short reset.',


        hopeless: isHindi ? 'यदि यह भावना भारी है, तो समर्थन की ओर जल्दी बढ़ना बेहतर है।' : 'If this feeling is heavy, moving toward support early is better.',


        unsafe: isHindi ? 'सुरक्षा पहले - प्रत्यक्ष समर्थन सबसे महत्वपूर्ण अगला कदम है।' : 'Safety first — direct support is the most important next step.',


    };





    const updateProgress = () => {


        const answered = ['feeling', 'intensity', 'safety'].filter((name) => form.querySelector(`input[name="${name}"]:checked`)).length;


        progress.textContent = `${answered}/3`;


        progressBar.style.width = `${(answered / 3) * 100}%`;


    };





    const updateGuidance = () => {


        const feeling = form.querySelector('input[name="feeling"]:checked')?.value || '';


        const intensity = form.querySelector('input[name="intensity"]:checked')?.value || '';


        const safety = form.querySelector('input[name="safety"]:checked')?.value || '';





        if (!feeling && !intensity && !safety) {


            guidance.textContent = isHindi


                ? 'जैसे ही आप अपना उत्तर चुनेंगे, हम शांत अगला कदम सुझाएंगे।'


                : 'As you choose your answers, we will suggest a calm next step.';


            return;


        }





        if (safety === 'yes' || safety === 'not_sure' || feeling === 'unsafe') {


            guidance.textContent = isHindi


                ? 'यहां सबसे कोमल और सुरक्षित अगला कदम प्रत्यक्ष समर्थन है।'


                : 'The gentlest and safest next step here is direct support.';


            return;


        }





        if (feeling === 'hopeless' && intensity === 'severe') {


            guidance.textContent = isHindi


                ? 'यह भावना अभी बहुत भारी लग रही है - समर्थन बेहतर होगा।'


                : 'This feeling sounds very heavy right now — support would be better.';


            return;


        }





        guidance.textContent = guidanceMap[feeling] || (isHindi ? 'चेक-इन धीरे-धीरे पूरा करें.' : 'Complete the check-in slowly.');


    };





    form.querySelectorAll('.mood-option').forEach((label) => {


        const input = label.querySelector('input');


        input.addEventListener('change', () => {


            form.querySelectorAll(`input[name="${input.name}"]`).forEach((peer) => {


                peer.closest('.mood-option').classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-950/30', 'text-indigo-900', 'dark:text-indigo-100');


            });


            label.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-950/30', 'text-indigo-900', 'dark:text-indigo-100');


            updateProgress();


            updateGuidance();


        });


    });





    form.addEventListener('submit', (event) => {


        event.preventDefault();


        const feeling = form.querySelector('input[name="feeling"]:checked')?.value || '';


        const intensity = form.querySelector('input[name="intensity"]:checked')?.value || '';


        const safety = form.querySelector('input[name="safety"]:checked')?.value || '';


        const needsCrisis = feeling === 'unsafe' || (feeling === 'hopeless' && intensity === 'severe') || safety === 'yes' || safety === 'not_sure';





        if (needsCrisis) {


            window.location.href = @json(route('support.crisis'));


            return;


        }





        result.classList.remove('hidden');


        resultCopy.textContent = feeling === 'anxious' || feeling === 'stressed' || feeling === 'angry'


            ? (isHindi ? 'निर्देशित श्वास इस समय आपके लिए सबसे उपयोगी पहला कदम हो सकता है।' : 'Guided breathing may be the most helpful first step for you right now.')


            : (isHindi ? 'ग्राउंडिंग या सांस लेने की गतिविधि इस समय आपकी सबसे अधिक मदद कर सकती है।' : 'A grounding or breathing activity may help you most right now.');


    });





    updateProgress();


    updateGuidance();


})();


</script>


@endpush


@endsection


