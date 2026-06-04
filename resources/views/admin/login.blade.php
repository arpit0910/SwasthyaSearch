<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Arogio</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/fav-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        soft: '0 30px 80px rgba(15, 23, 42, 0.28)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(45, 212, 191, 0.16), transparent 32%),
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.14), transparent 34%),
                radial-gradient(circle at bottom center, rgba(14, 165, 233, 0.14), transparent 36%),
                linear-gradient(135deg, #082f49 0%, #0f172a 42%, #111827 100%);
        }

        .glass-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.84));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .brand-orbit,
        .brand-orbit::before,
        .brand-orbit::after {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
        }

        .brand-orbit {
            border: 1px solid rgba(255, 255, 255, 0.28);
            animation: spinSlow 18s linear infinite;
        }

        .brand-orbit::before,
        .brand-orbit::after {
            content: "";
            inset: 14%;
            border: 1px dashed rgba(94, 234, 212, 0.26);
        }

        .brand-orbit::before {
            animation: spinReverse 14s linear infinite;
        }

        .brand-orbit::after {
            inset: 28%;
            border-style: solid;
            border-color: rgba(125, 211, 252, 0.26);
            animation: pulseSoft 4.8s ease-in-out infinite;
        }

        .brand-core {
            position: absolute;
            inset: 36%;
            border-radius: 9999px;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.95), rgba(255,255,255,0.18) 34%, transparent 68%), linear-gradient(135deg, rgba(45,212,191,0.46), rgba(59,130,246,0.34));
            box-shadow: 0 0 40px rgba(45, 212, 191, 0.22);
            animation: pulseSoft 5s ease-in-out infinite;
        }

        .brand-dot {
            position: absolute;
            width: .65rem;
            height: .65rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 0 24px rgba(125, 211, 252, 0.34);
            animation: floatSoft 6s ease-in-out infinite;
        }

        .brand-dot.dot-1 { left: 18%; top: 20%; animation-delay: 0s; }
        .brand-dot.dot-2 { right: 16%; top: 30%; animation-delay: 1s; }
        .brand-dot.dot-3 { left: 22%; bottom: 18%; animation-delay: 2s; }
        .brand-dot.dot-4 { right: 20%; bottom: 16%; animation-delay: 3s; }

        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes spinReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes pulseSoft {
            0%, 100% { transform: scale(0.96); opacity: .78; }
            50% { transform: scale(1.04); opacity: 1; }
        }

        @keyframes floatSoft {
            0%, 100% { transform: translateY(0) scale(1); opacity: .74; }
            50% { transform: translateY(-10px) scale(1.14); opacity: 1; }
        }

        @media (prefers-reduced-motion: reduce) {
            .brand-orbit,
            .brand-orbit::before,
            .brand-orbit::after,
            .brand-core,
            .brand-dot {
                animation: none !important;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden text-slate-100 font-sans">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 h-80 w-80 rounded-full bg-teal-400/20 blur-3xl"></div>
        <div class="absolute top-1/4 -right-24 h-96 w-96 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 h-80 w-80 rounded-full bg-cyan-300/10 blur-3xl"></div>
    </div>

    <main class="relative z-10 min-h-screen px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-6xl items-center justify-center">
            <div class="grid w-full gap-6 overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 shadow-soft lg:grid-cols-[1.05fr_.95fr]">
                <section class="relative hidden overflow-hidden bg-gradient-to-br from-cyan-700 via-sky-700 to-blue-900 p-10 lg:flex lg:flex-col lg:justify-between">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.14),transparent_32%)]"></div>
                    <div class="relative z-10">
                        <h1 class="mt-6 max-w-md text-4xl font-extrabold leading-tight text-white">
                            Manage healthcare discovery with the same calm, modern Arogio feel.
                        </h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-slate-200/90">
                            Access doctors, hospitals, blood banks, articles, and platform tools from one trusted operations space.
                        </p>
                    </div>

                    <div class="relative z-10 mt-10 flex items-end gap-6">
                        <div class="relative h-56 w-56 shrink-0 rounded-full">
                            <div class="brand-orbit"></div>
                            <div class="brand-core"></div>
                            <span class="brand-dot dot-1"></span>
                            <span class="brand-dot dot-2"></span>
                            <span class="brand-dot dot-3"></span>
                            <span class="brand-dot dot-4"></span>
                        </div>
                        <div class="space-y-3 pb-2">
                            <div class="rounded-3xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur-md">
                                <div class="text-sm leading-6 text-white">
                                    Verified directory operations, calmer workflows, and a dashboard aligned with the public Arogio experience.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="glass-card flex items-center p-5 sm:p-8 lg:p-10">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-8 text-center lg:text-left">
                            <img src="{{ asset('img/arogio-logo.png') }}" alt="Arogio" class="mx-auto h-14 w-auto lg:mx-0">
                            <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                                Welcome back
                            </h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Sign in to manage directory records, platform content, and admin workflows.
                            </p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                    </span>
                                    <div>
                                        <div class="font-semibold">Sign-in issue</div>
                                        <div class="mt-1">{{ $errors->first() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email Address</label>
                                <div class="group flex h-14 items-center rounded-2xl border border-slate-200 bg-white px-4 shadow-sm transition-all duration-200 focus-within:border-teal-400 focus-within:ring-4 focus-within:ring-teal-500/10">
                                    <i class="fa-solid fa-envelope text-slate-400 transition-colors duration-200 group-focus-within:text-teal-600"></i>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        placeholder="admin@arogio.com"
                                        class="h-full w-full border-0 bg-transparent px-3 text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                <div class="group flex h-14 items-center rounded-2xl border border-slate-200 bg-white px-4 shadow-sm transition-all duration-200 focus-within:border-teal-400 focus-within:ring-4 focus-within:ring-teal-500/10">
                                    <i class="fa-solid fa-lock text-slate-400 transition-colors duration-200 group-focus-within:text-teal-600"></i>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        placeholder="••••••••"
                                        class="h-full w-full border-0 bg-transparent px-3 text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400">
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <label for="remember" class="inline-flex cursor-pointer items-center gap-3 text-sm text-slate-600">
                                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                    <span class="font-medium">Keep me signed in</span>
                                </label>
                            </div>

                            <button type="submit" class="group inline-flex h-14 w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-500 via-cyan-500 to-sky-600 px-5 text-sm font-semibold text-white shadow-lg shadow-cyan-600/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-cyan-600/30 active:translate-y-0">
                                <i class="fa-solid fa-right-to-bracket text-sm transition-transform duration-200 group-hover:translate-x-0.5"></i>
                                <span>Sign In to Dashboard</span>
                            </button>
                        </form>

                        <div class="mt-6 border-t border-slate-200 pt-4 text-sm text-slate-500">
                            Secure admin access for directory records, content tools, and platform operations.
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>

</html>
