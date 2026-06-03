@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'मेमोरी गेम' : 'Memory Game') . ' - Arogio')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-cyan-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-sm p-6 sm:p-8">
        <h1 class="text-3xl font-extrabold text-slate-950 dark:text-white">{{ $locale === 'hi' ? 'सरल मेमोरी गेम' : 'Simple Memory Game' }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $locale === 'hi' ? 'एक छोटा शांत गेम। कोई संवेदनशील डेटा स्टोर नहीं होता।' : 'A small calming game. No sensitive data is stored.' }}</p>
        <div id="memory-grid" class="mt-8 grid grid-cols-4 gap-3"></div>
        <div class="mt-6 flex items-center gap-3">
            <button id="memory-reset" class="rounded-2xl bg-cyan-600 hover:bg-cyan-700 px-5 py-3 text-sm font-bold text-white">{{ $locale === 'hi' ? 'फिर से खेलें' : 'Play Again' }}</button>
            <span id="memory-status" class="text-sm text-slate-600 dark:text-slate-300"></span>
        </div>
    </section>
</main>
@push('scripts')
<script>
(() => {
    const values = ['A','A','B','B','C','C','D','D'];
    const grid = document.getElementById('memory-grid');
    const status = document.getElementById('memory-status');
    let cards = [];
    let open = [];
    let matched = 0;

    function shuffle(arr) {
        return [...arr].sort(() => Math.random() - 0.5);
    }

    function render() {
        matched = 0;
        open = [];
        cards = shuffle(values).map((value, index) => ({ value, index, revealed: false, done: false }));
        grid.innerHTML = '';
        status.textContent = '';
        cards.forEach((card) => {
            const button = document.createElement('button');
            button.className = 'aspect-square rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-xl font-bold text-slate-800 dark:text-slate-100';
            button.textContent = '?';
            button.addEventListener('click', () => flip(card.index, button));
            grid.appendChild(button);
        });
    }

    function flip(index, button) {
        const card = cards[index];
        if (card.done || open.includes(index) || open.length >= 2) return;
        card.revealed = true;
        button.textContent = card.value;
        open.push(index);
        if (open.length === 2) {
            const [a, b] = open;
            const buttons = grid.querySelectorAll('button');
            if (cards[a].value === cards[b].value) {
                cards[a].done = cards[b].done = true;
                open = [];
                matched += 1;
                if (matched === 4) status.textContent = @json($locale === 'hi' ? 'बहुत बढ़िया! आपने सभी जोड़े खोज लिए।' : 'Nice work! You found all pairs.');
            } else {
                setTimeout(() => {
                    cards[a].revealed = cards[b].revealed = false;
                    buttons[a].textContent = '?';
                    buttons[b].textContent = '?';
                    open = [];
                }, 700);
            }
        }
    }

    document.getElementById('memory-reset').addEventListener('click', render);
    render();
})();
</script>
@endpush
@endsection
