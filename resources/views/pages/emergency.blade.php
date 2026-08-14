@extends('layouts.public')

@section('title', 'Emergency Help - Arogio')
@section('meta_title', 'Emergency Numbers and Urgent Help | Arogio')
@section('meta_description', 'Find ambulance, police, fire brigade, and mental health emergency contact details in one place. Know who to call and what to do next in an urgent situation.')

@section('content')
<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <section class="rounded-[2rem] border border-rose-200/80 dark:border-rose-900/40 bg-white/95 dark:bg-slate-950/95 shadow-sm p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-rose-700 dark:text-rose-300">Immediate Help</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-950 dark:text-white">Who to call in an emergency</h1>
        <p class="mt-4 max-w-3xl text-base leading-8 text-slate-700 dark:text-slate-300">
            If the situation is life-threatening, unsafe, or needs urgent support, call emergency services first.
            This page brings the most useful numbers together so people can act quickly.
        </p>

        <div class="mt-6 rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-950/30 p-4 text-sm leading-7 text-amber-900 dark:text-amber-100">
            If you remember only one number, call <strong>112</strong>. It is India's integrated emergency response number.
        </div>
    </section>

    <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            [
                'title' => 'All-in-one Emergency',
                'number' => '112',
                'href' => 'tel:112',
                'tone' => 'rose',
                'icon' => 'siren',
                'description' => 'Call first for police, fire, ambulance, or any immediate emergency.',
            ],
            [
                'title' => 'Police',
                'number' => '100',
                'href' => 'tel:100',
                'tone' => 'indigo',
                'icon' => 'shield',
                'description' => 'Use when you are unsafe, facing crime, violence, or an immediate threat.',
            ],
            [
                'title' => 'Fire Brigade',
                'number' => '101',
                'href' => 'tel:101',
                'tone' => 'orange',
                'icon' => 'flame',
                'description' => 'Call for fire, smoke, gas-related fire risk, or rescue support.',
            ],
            [
                'title' => 'Ambulance',
                'number' => '102',
                'href' => 'tel:102',
                'tone' => 'emerald',
                'icon' => 'ambulance',
                'description' => 'Use for urgent medical transport and emergency health assistance.',
            ],
            [
                'title' => 'Emergency Medical Response',
                'number' => '108',
                'href' => 'tel:108',
                'tone' => 'teal',
                'icon' => 'heart-pulse',
                'description' => 'Often used for emergency ambulance response and accident support.',
            ],
            [
                'title' => 'Mental Health Support',
                'number' => '14416',
                'href' => 'tel:+9114416',
                'tone' => 'cyan',
                'icon' => 'phone-call',
                'description' => 'Tele-MANAS mental health helpline for emotional distress and urgent support.',
            ],
        ] as $item)
            <article class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-{{ $item['tone'] }}-100 dark:bg-{{ $item['tone'] }}-950/40 text-{{ $item['tone'] }}-700 dark:text-{{ $item['tone'] }}-300">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <span class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-bold text-slate-700 dark:text-slate-200">{{ $item['number'] }}</span>
                </div>
                <h2 class="mt-4 text-xl font-bold text-slate-950 dark:text-white">{{ $item['title'] }}</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $item['description'] }}</p>
                <a href="{{ $item['href'] }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-slate-950 dark:bg-white px-4 py-3 text-sm font-bold text-white dark:text-slate-950">
                    Call Now: {{ $item['number'] }}
                </a>
            </article>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-[1.15fr,0.85fr]">
        <div class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Who To Call When</p>
            <div class="mt-5 space-y-4">
                @foreach ([
                    [
                        'title' => 'Life-threatening emergency',
                        'body' => 'Call 112 first. If possible, also go to the nearest hospital immediately.',
                    ],
                    [
                        'title' => 'Crime, violence, or feeling unsafe',
                        'body' => 'Call 100 or 112 for police help right away.',
                    ],
                    [
                        'title' => 'Fire, smoke, or rescue need',
                        'body' => 'Call 101 or 112 and move to a safer place if you can.',
                    ],
                    [
                        'title' => 'Medical emergency or ambulance needed',
                        'body' => 'Call 102, 108, or 112 depending on what connects fastest in your area.',
                    ],
                    [
                        'title' => 'Mental health crisis or emotional distress',
                        'body' => 'Call Tele-MANAS on 14416, or use emergency services if there is immediate danger.',
                    ],
                ] as $tip)
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $tip['title'] }}</h3>
                        <p class="mt-1 text-sm leading-7 text-slate-600 dark:text-slate-300">{{ $tip['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <section class="rounded-[1.75rem] border border-cyan-200/80 dark:border-cyan-900/40 bg-cyan-50/80 dark:bg-cyan-950/20 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white">What to do next</h2>
                <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-700 dark:text-slate-300">
                    <li>Share your exact location or a nearby landmark.</li>
                    <li>Stay calm and give short, clear details.</li>
                    <li>If safe, call a trusted person too.</li>
                    <li>If the situation worsens, stay on the line or call again.</li>
                </ul>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-950 dark:text-white">Quick Links</h2>
                <div class="mt-4 grid gap-3">
                    <a href="{{ route('hospitals.index', ['search' => 'emergency']) }}" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">View nearby hospitals</a>
                    <a href="{{ route('blood_banks.index') }}" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">View blood banks</a>
                    <a href="{{ route('support.crisis') }}" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">Open crisis support</a>
                    <a href="https://telemanas.mohfw.gov.in/home" target="_blank" rel="noopener noreferrer" class="rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 text-sm font-bold text-slate-900 dark:text-slate-100">Tele-MANAS website</a>
                </div>
            </section>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 p-5 text-sm leading-7 text-slate-600 dark:text-slate-300">
        These numbers were verified from public Government of India sources. This page was updated on August 14, 2026. Local availability can vary, so calling 112 is the safest default in a life-threatening emergency.
    </section>
</main>
@endsection
