@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'स्मृति खेल' : 'Memory Game') . ' - Arogio')

@section('content')
    @php($isHindi = \App\Helpers\LocaleHelper::current() === 'hi')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <section
            class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-5 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">
                        {{ $isHindi ? 'मस्तिष्क रीसेट' : 'Brain Reset' }}</p>
                    <h1 class="mt-2 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-950 dark:text-white">
                        {{ $isHindi ? 'अतिरिक्त दबाव के बिना एक संक्षिप्त स्मृति विराम' : 'A short memory break without extra pressure' }}
                    </h1>
                    <p class="mt-3 text-sm sm:text-base leading-7 text-slate-600 dark:text-slate-300">
                        {{ $isHindi ? 'यह खेल प्रतिस्पर्धा के बारे में नहीं है। यह बस आपका ध्यान स्थानांतरित करने, अपने दिमाग को आराम देने और तनाव की तीव्रता को एक पल के लिए कम करने के लिए है।' : 'This game is not about competition. It is simply here to shift your attention, give your mind a break, and reduce the intensity of stress for a moment.' }}
                    </p>
                </div>
                <button id="memory-reset" type="button"
                    class="rounded-[1.15rem] bg-cyan-600 hover:bg-cyan-700 px-6 py-3 text-sm font-bold text-white">{{ $isHindi ? 'फिर से चालू करें' : 'Play again' }}</button>
            </div>

            <div class="mt-6 flex flex-wrap gap-2" id="memory-levels"></div>

            <div class="mt-8 grid gap-4 sm:grid-cols-4">
                <div class="rounded-[1.4rem] bg-cyan-50 dark:bg-cyan-950/30 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-cyan-700 dark:text-cyan-300">
                        {{ $isHindi ? 'चालें' : 'Moves' }}</p>
                    <p id="memory-moves" class="mt-2 text-3xl font-extrabold text-cyan-900 dark:text-cyan-100">0</p>
                </div>
                <div class="rounded-[1.4rem] bg-sky-50 dark:bg-sky-950/30 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-sky-700 dark:text-sky-300">
                        {{ $isHindi ? 'मैच' : 'Matches' }}</p>
                    <p id="memory-matches" class="mt-2 text-3xl font-extrabold text-sky-900 dark:text-sky-100">0 / 4</p>
                </div>
                <div class="rounded-[1.4rem] bg-slate-50 dark:bg-slate-950/40 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                        {{ $isHindi ? 'समय' : 'Time' }}</p>
                    <p id="memory-timer" class="mt-2 text-3xl font-extrabold text-slate-950 dark:text-white">00:00</p>
                </div>
                <div class="rounded-[1.4rem] bg-violet-50 dark:bg-violet-950/30 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-violet-700 dark:text-violet-300">
                        {{ $isHindi ? 'लेवल' : 'Level' }}</p>
                    <p id="memory-level" class="mt-2 text-3xl font-extrabold text-violet-900 dark:text-violet-100">1</p>
                </div>
            </div>

            <div id="memory-grid" class="mt-8 grid grid-cols-4 gap-3 sm:gap-4"></div>

            <div id="memory-status"
                class="mt-6 rounded-[1.4rem] border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/30 px-4 py-4 text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ $isHindi ? 'दो कार्ड पलटें और एक मेल खाता जोड़ा ढूंढने का प्रयास करें।' : 'Flip two cards and try to find a matching pair.' }}
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            (() => {
                const levelConfigs = [{
                        level: 1,
                        tiles: 8,
                        cols: 'grid-cols-4'
                    },
                    {
                        level: 2,
                        tiles: 10,
                        cols: 'grid-cols-5'
                    },
                    {
                        level: 3,
                        tiles: 12,
                        cols: 'grid-cols-4 sm:grid-cols-6'
                    },
                    {
                        level: 4,
                        tiles: 14,
                        cols: 'grid-cols-4 sm:grid-cols-7'
                    },
                    {
                        level: 5,
                        tiles: 16,
                        cols: 'grid-cols-4'
                    },
                    {
                        level: 6,
                        tiles: 20,
                        cols: 'grid-cols-4 sm:grid-cols-5'
                    },
                ];

                const tileIcons = ['heart', 'star', 'sun', 'moon-star', 'cloud', 'leaf', 'flame', 'sparkles', 'pill',
                    'apple', 'flower-2', 'umbrella', 'bird', 'trees'
                ];

                const grid = document.getElementById('memory-grid');
                const levelsEl = document.getElementById('memory-levels');
                const status = document.getElementById('memory-status');
                const movesEl = document.getElementById('memory-moves');
                const matchesEl = document.getElementById('memory-matches');
                const timerEl = document.getElementById('memory-timer');
                const levelEl = document.getElementById('memory-level');
                const isHindi = @json($isHindi);

                let cards = [];
                let open = [];
                let matched = 0;
                let moves = 0;
                let seconds = 0;
                let timer = null;
                let currentLevelIndex = 0;

                const shuffle = (arr) => [...arr].sort(() => Math.random() - 0.5);

                const refreshIcons = () => {
                    if (window.refreshLucideIcons) {
                        window.refreshLucideIcons();
                        return;
                    }
                    if (window.lucide && typeof window.lucide.createIcons === 'function') {
                        window.lucide.createIcons();
                    }
                };

                const getActiveLevel = () => levelConfigs[currentLevelIndex];

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

                const buildDeck = () => {
                    const activeLevel = getActiveLevel();
                    const pairCount = activeLevel.tiles / 2;
                    const selectedIcons = tileIcons.slice(0, pairCount);
                    return shuffle([...selectedIcons, ...selectedIcons]).map((value, index) => ({
                        value,
                        index,
                        done: false
                    }));
                };

                const setGridColumns = () => {
                    const activeLevel = getActiveLevel();
                    grid.className = `mt-8 grid ${activeLevel.cols} gap-3 sm:gap-4`;
                };

                const renderLevels = () => {
                    levelsEl.innerHTML = '';
                    levelConfigs.forEach((config, index) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className =
                            `rounded-full border px-3 py-2 text-xs font-bold transition ${index === currentLevelIndex ? 'border-violet-500 bg-violet-600 text-white shadow-lg shadow-violet-500/20' : 'border-slate-200 bg-white text-slate-700 hover:border-violet-300 hover:text-violet-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-violet-500 dark:hover:text-violet-300'}`;
                        button.textContent = `${isHindi ? 'लेवल' : 'Level'} ${config.level} ? ${config.tiles}`;
                        button.addEventListener('click', () => {
                            currentLevelIndex = index;
                            render();
                        });
                        levelsEl.appendChild(button);
                    });
                };

                const finishGame = () => {
                    clearInterval(timer);
                    const activeLevel = getActiveLevel();
                    status.textContent = isHindi ?
                        `बहुत अच्छा। आपने लेवल ${activeLevel.level} को ${moves} चालों में पूरा किया।` :
                        `Nice work. You finished level ${activeLevel.level} in ${moves} moves. Take one deeper breath.`;
                };

                const renderCardBack = (button) => {
                    button.innerHTML =
                        '<span class="flex h-full w-full items-center justify-center rounded-[1.3rem] bg-white/60 dark:bg-slate-900/40"><i data-lucide="help-circle" class="h-8 w-8 text-cyan-600 dark:text-cyan-300"></i></span>';
                };

                const renderCardFront = (button, iconName) => {
                    button.innerHTML =
                        `<span class="flex h-full w-full items-center justify-center rounded-[1.3rem] bg-cyan-100 text-cyan-700 dark:bg-cyan-950/40 dark:text-cyan-200"><i data-lucide="${iconName}" class="h-8 w-8 sm:h-9 sm:w-9"></i></span>`;
                };

                const render = () => {
                    clearInterval(timer);
                    seconds = 0;
                    matched = 0;
                    moves = 0;
                    open = [];
                    cards = buildDeck();

                    const activeLevel = getActiveLevel();

                    updateTimer();
                    movesEl.textContent = '0';
                    matchesEl.textContent = `0 / ${activeLevel.tiles / 2}`;
                    levelEl.textContent = String(activeLevel.level);
                    status.textContent = isHindi ? 'दो कार्ड पलटें और एक मेल खाता जोड़ा ढूंढने का प्रयास करें।' :
                        'Flip two cards and try to find a matching pair.';

                    grid.innerHTML = '';
                    setGridColumns();
                    renderLevels();

                    cards.forEach((card) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className =
                            'group aspect-square rounded-[1.5rem] border border-slate-200 dark:border-slate-700 bg-gradient-to-br from-white to-cyan-50 dark:from-slate-800 dark:to-slate-900 text-slate-800 dark:text-slate-100 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-300 dark:hover:border-cyan-700';
                        renderCardBack(button);
                        button.addEventListener('click', () => flip(card.index, button));
                        grid.appendChild(button);
                    });

                    refreshIcons();
                    startTimer();
                };

                const flip = (index, button) => {
                    const card = cards[index];
                    if (card.done || open.includes(index) || open.length >= 2) return;

                    renderCardFront(button, card.value);
                    button.classList.add('border-cyan-300', 'dark:border-cyan-700');
                    refreshIcons();
                    open.push(index);

                    if (open.length === 2) {
                        moves += 1;
                        movesEl.textContent = String(moves);

                        const [a, b] = open;
                        const buttons = grid.querySelectorAll('button');
                        const activeLevel = getActiveLevel();

                        if (cards[a].value === cards[b].value) {
                            cards[a].done = true;
                            cards[b].done = true;
                            buttons[a].classList.add('border-emerald-400');
                            buttons[b].classList.add('border-emerald-400');
                            open = [];
                            matched += 1;
                            matchesEl.textContent = `${matched} / ${activeLevel.tiles / 2}`;
                            status.textContent = isHindi ? 'अच्छा। एक और जोड़ी ढूंढो।' : 'Good. Find one more pair.';
                            if (matched === activeLevel.tiles / 2) finishGame();
                        } else {
                            status.textContent = isHindi ? 'कोई बात नहीं। कोई दूसरा जोड़ा आज़माएँ।' :
                                'No problem. Try another pair.';
                            setTimeout(() => {
                                renderCardBack(buttons[a]);
                                renderCardBack(buttons[b]);
                                buttons[a].classList.remove('border-cyan-300', 'dark:border-cyan-700');
                                buttons[b].classList.remove('border-cyan-300', 'dark:border-cyan-700');
                                refreshIcons();
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
