@extends('layouts.public')





@section('title', ($locale === 'hi' ? 'स्मृति खेल' : 'Memory Game') . ' - Arogio')





@section('content')


@php($isHindi = $locale === 'hi')


<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">


    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10">


        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">


            <div>


                <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'मस्तिष्क रीसेट' : 'Brain Reset' }}</p>


                <h1 class="mt-2 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">{{ $isHindi ? 'अतिरिक्त दबाव के बिना एक संक्षिप्त स्मृति विराम' : 'A short memory break without extra pressure' }}</h1>


                <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'यह खेल प्रतिस्पर्धा के बारे में नहीं है. यह बस आपका ध्यान स्थानांतरित करने, अपने दिमाग को आराम देने और तनाव की तीव्रता को एक पल के लिए कम करने के लिए है।' : 'This game is not about competition. It is simply here to shift your attention, give your mind a break, and reduce the intensity of stress for a moment.' }}</p>


            </div>


            <button id="memory-reset" class="rounded-[1.15rem] bg-cyan-600 hover:bg-cyan-700 px-6 py-3 text-sm font-bold text-white">{{ $isHindi ? 'फिर से चालू करें' : 'Play again' }}</button>


        </div>





        <div class="mt-8 grid gap-4 sm:grid-cols-3">


            <div class="rounded-[1.4rem] bg-cyan-50 dark:bg-cyan-950/30 p-4">


                <p class="text-xs uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">{{ $isHindi ? 'चालें' : 'Moves' }}</p>


                <p id="memory-moves" class="mt-2 text-3xl font-extrabold text-cyan-900 dark:text-cyan-100">0</p>


            </div>


            <div class="rounded-[1.4rem] bg-sky-50 dark:bg-sky-950/30 p-4">


                <p class="text-xs uppercase tracking-[0.2em] text-sky-700 dark:text-sky-300">{{ $isHindi ? 'माचिस' : 'Matches' }}</p>


                <p id="memory-matches" class="mt-2 text-3xl font-extrabold text-sky-900 dark:text-sky-100">0 / 4</p>


            </div>


            <div class="rounded-[1.4rem] bg-slate-50 dark:bg-slate-950/40 p-4">


                <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ $isHindi ? 'समय' : 'Time' }}</p>


                <p id="memory-timer" class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">00:00</p>


            </div>


        </div>





        <div id="memory-grid" class="mt-8 grid grid-cols-4 gap-3 sm:gap-4"></div>





        <div id="memory-status" class="mt-6 rounded-[1.4rem] border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/30 px-4 py-4 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $isHindi ? 'दो कार्ड पलटें और एक मेल खाता जोड़ा ढूंढने का प्रयास करें।' : 'Flip two cards and try to find a matching pair.' }}</div>


    </section>


</main>





@push('scripts')


<script>


(() => {


    const values = ['??','??','??','??','??','??','??','??'];


    const grid = document.getElementById('memory-grid');


    const status = document.getElementById('memory-status');


    const movesEl = document.getElementById('memory-moves');


    const matchesEl = document.getElementById('memory-matches');


    const timerEl = document.getElementById('memory-timer');


    const isHindi = @json($isHindi);


    let cards = [];


    let open = [];


    let matched = 0;


    let moves = 0;


    let seconds = 0;


    let timer = null;





    const shuffle = (arr) => [...arr].sort(() => Math.random() - 0.5);





    const updateTimer = () => {


        const mins = String(Math.floor(seconds / 60)).padStart(2, '0');


        const secs = String(seconds % 60).padStart(2, '0');


        timerEl.textContent = `${mins}:${secs}`;


    };





    const startTimer = () => {


        clearInterval(timer);


        timer = setInterval(() => {


            seconds += 1;


            updateTimer();


        }, 1000);


    };





    const finishGame = () => {


        clearInterval(timer);


        status.textContent = isHindi


            ? `?? ?? ?? ${moves} moves ? game ?? ? ?? ?? ?? ?? ??`


            : `Nice work. You finished the game in ${moves} moves. Now take one deeper breath.`;


    };





    const render = () => {


        clearInterval(timer);


        seconds = 0;


        updateTimer();


        matched = 0;


        moves = 0;


        open = [];


        cards = shuffle(values).map((value, index) => ({ value, index, done: false }));


        movesEl.textContent = '0';


        matchesEl.textContent = '0 / 4';


        status.textContent = isHindi ? 'दो कार्ड पलटें और एक मेल खाता जोड़ा ढूंढने का प्रयास करें।' : 'Flip two cards and try to find a matching pair.';


        grid.innerHTML = '';


        cards.forEach((card) => {


            const button = document.createElement('button');


            button.className = 'aspect-square rounded-[1.5rem] border border-slate-200 dark:border-slate-700 bg-gradient-to-br from-white to-cyan-50 dark:from-slate-800 dark:to-slate-900 text-3xl font-bold text-slate-800 dark:text-slate-100 shadow-sm transition hover:-translate-y-0.5';


            button.textContent = '•';


            button.addEventListener('click', () => flip(card.index, button));


            grid.appendChild(button);


        });


        startTimer();


    };





    const flip = (index, button) => {


        const card = cards[index];


        if (card.done || open.includes(index) || open.length >= 2) return;


        button.textContent = card.value;


        button.classList.add('bg-cyan-100', 'dark:bg-cyan-950/40');


        open.push(index);





        if (open.length === 2) {


            moves += 1;


            movesEl.textContent = String(moves);


            const [a, b] = open;


            const buttons = grid.querySelectorAll('button');


            if (cards[a].value === cards[b].value) {


                cards[a].done = true;


                cards[b].done = true;


                buttons[a].classList.add('border-emerald-400');


                buttons[b].classList.add('border-emerald-400');


                open = [];


                matched += 1;


                matchesEl.textContent = `${matched} / 4`;


                status.textContent = isHindi ? 'अच्छा। एक और जोड़ी ढूंढो.' : 'Good. Find one more pair.';


                if (matched === 4) finishGame();


            } else {


                status.textContent = isHindi ? 'कोई बात नहीं। कोई दूसरा जोड़ा आज़माएँ.' : 'No problem. Try another pair.';


                setTimeout(() => {


                    buttons[a].textContent = '•';


                    buttons[b].textContent = '•';


                    buttons[a].classList.remove('bg-cyan-100', 'dark:bg-cyan-950/40');


                    buttons[b].classList.remove('bg-cyan-100', 'dark:bg-cyan-950/40');


                    open = [];


                }, 700);


            }


        }


    };





    document.getElementById('memory-reset').addEventListener('click', render);


    render();


})();


</script>


@endpush


@endsection


