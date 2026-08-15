<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Arogio') }} | Coming Soon</title>
    <meta name="description" content="Arogio is launching soon with trusted healthcare discovery and wellness features.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/fav-icon.png') }}">
    @include('partials.google-analytics')
    <style>
        :root {
            --brand-cyan: #45cdda;
            --brand-mint: #7edfb8;
            --brand-lime: #c4e94d;
            --brand-deep: #071514;
            --surface: rgba(10, 34, 32, 0.78);
            --surface-soft: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.09);
            --text: #effdf8;
            --muted: rgba(239, 253, 248, 0.74);
            --shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(69, 205, 218, 0.24), transparent 32%),
                radial-gradient(circle at bottom right, rgba(196, 233, 77, 0.18), transparent 28%),
                linear-gradient(145deg, #041110 0%, #0a2321 55%, #0e2d2a 100%);
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 999px;
            filter: blur(24px);
            opacity: 0.5;
            pointer-events: none;
        }

        body::before {
            width: 15rem;
            height: 15rem;
            top: 4rem;
            left: -4rem;
            background: rgba(69, 205, 218, 0.24);
        }

        body::after {
            width: 18rem;
            height: 18rem;
            right: -5rem;
            bottom: 3rem;
            background: rgba(196, 233, 77, 0.22);
        }

        .page {
            width: min(980px, calc(100% - 1.5rem));
            margin: 0 auto;
            padding: 1.25rem 0 2rem;
        }

        .notice {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.9rem;
            border-radius: 999px;
            background: rgba(69, 205, 218, 0.12);
            border: 1px solid var(--border);
            color: var(--brand-mint);
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .notice::before {
            content: "";
            width: 0.55rem;
            height: 0.55rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand-cyan), var(--brand-lime));
            box-shadow: 0 0 0 0.32rem rgba(69, 205, 218, 0.12);
        }

        .hero {
            margin-top: 1rem;
            padding: 2rem;
            border-radius: 30px;
            background: linear-gradient(180deg, rgba(12, 42, 40, 0.9), rgba(7, 23, 22, 0.95));
            border: 1px solid rgba(196, 233, 77, 0.16);
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto auto -4rem 50%;
            transform: translateX(-50%);
            width: 18rem;
            height: 18rem;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 233, 77, 0.18), transparent 70%);
        }

        .logo-wrap {
            width: 5.75rem;
            height: 5.75rem;
            margin: 0 auto 1.2rem;
            padding: 0.85rem;
            border-radius: 1.6rem;
            background: linear-gradient(135deg, rgba(69, 205, 218, 0.18), rgba(196, 233, 77, 0.22));
            border: 1px solid var(--border);
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        h1 {
            margin: 0;
            font-size: clamp(2.4rem, 6vw, 4.8rem);
            line-height: 0.96;
            letter-spacing: -0.05em;
            position: relative;
            z-index: 1;
        }

        .subtitle {
            max-width: 42rem;
            margin: 1rem auto 0;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .highlight {
            display: inline-block;
            margin-top: 1.25rem;
            padding: 0.8rem 1rem;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(69, 205, 218, 0.12), rgba(196, 233, 77, 0.14));
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 0.96rem;
            position: relative;
            z-index: 1;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.2rem;
        }

        .feature {
            padding: 1.1rem 1rem;
            border-radius: 22px;
            background: linear-gradient(180deg, var(--surface), rgba(7, 23, 22, 0.9));
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .feature strong {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--brand-lime);
            font-size: 0.95rem;
        }

        .feature span {
            color: var(--muted);
            font-size: 0.93rem;
            line-height: 1.55;
        }

        @media (max-width: 860px) {
            .features {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .page {
                width: min(100% - 1rem, 100%);
                padding: 1rem 0 1.5rem;
            }

            .hero {
                padding: 1.4rem 1rem;
                border-radius: 24px;
            }

            .logo-wrap {
                width: 4.9rem;
                height: 4.9rem;
                border-radius: 1.3rem;
            }

            .subtitle {
                font-size: 0.96rem;
            }

            .highlight {
                width: 100%;
                border-radius: 16px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        $features = [
            ['title' => 'Doctors', 'text' => 'Trusted specialists and smarter discovery.'],
            ['title' => 'Hospitals', 'text' => 'Nearby care options in one place.'],
            ['title' => 'Medicines', 'text' => 'Useful medicine information, simplified.'],
            ['title' => 'Wellness', 'text' => 'Activities and tools for everyday support.'],
        ];
    @endphp

    <main class="page">
        <span class="notice">Coming Soon</span>

        <section class="hero">
            <div class="logo-wrap">
                <img src="{{ asset('img/fav-icon.png') }}" alt="{{ config('app.name', 'Arogio') }} logo">
            </div>

            <h1>Arogio is launching soon.</h1>
            <p class="subtitle">
                A fresh healthcare experience with trusted discovery, essential information, and wellness support.
            </p>

            <div class="highlight">Explore the key features arriving very soon.</div>
        </section>

        <section class="features">
            @foreach ($features as $feature)
                <article class="feature">
                    <strong>{{ $feature['title'] }}</strong>
                    <span>{{ $feature['text'] }}</span>
                </article>
            @endforeach
        </section>
    </main>
</body>

</html>
