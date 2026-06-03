@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'Privacy Policy' : 'Privacy Policy') . ' - Arogio')
@section('meta_title', 'Privacy Policy | Arogio')
@section('meta_description', 'Learn what data Arogio collects, why it is collected, and how your privacy is protected.')

@section('content')
<main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 w-full">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 sm:px-10 py-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-cyan-700 hover:text-indigo-900">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Back to Home</span>
                </a>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-full border border-teal-100">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>Your Privacy Matters</span>
                </span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Privacy Policy</h1>
            <p class="text-sm text-slate-500 mt-2">Last Updated: May 25, 2026</p>
        </div>

        <div class="px-6 sm:px-10 py-8 sm:py-10 space-y-8 text-slate-700 leading-relaxed">
            <section class="rounded-2xl border border-teal-100 bg-teal-50/60 p-5">
                <h2 class="text-base font-bold text-teal-900 mb-2">Quick Summary</h2>
                <ul class="list-disc pl-5 space-y-1.5 text-sm text-teal-900">
                    <li>We only use the minimum data needed to run the service.</li>
                    <li>We do not sell your data to advertisers.</li>
                    <li>This platform is not a substitute for medical diagnosis.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">1. What Data We Collect</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Search inputs:</strong> Symptoms, department, city, or keywords you search.</li>
                    <li><strong>Chat session token:</strong> Temporary technical identifier to maintain chat continuity.</li>
                    <li><strong>Contact form data:</strong> Name, email, subject, and message (only when you submit it).</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">2. How We Use Data</h2>
                <p>Data is used to provide search results, chat assistance, and improve platform quality. We do not use it for ad profiling.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">3. Data Sharing</h2>
                <p>We do not sell or rent your personal data. Limited sharing may occur only when legally required.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">4. Security and Cookies</h2>
                <p>We use secure transport (HTTPS). Essential cookies/local storage may be used for language and basic experience.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">5. Your Choices and Rights</h2>
                <p>You may contact support for privacy questions and request updates/removal of contact-form submissions where applicable.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">6. Contact</h2>
                <p>For privacy-related questions: <a href="mailto:privacy@arogio.com" class="text-cyan-700 font-semibold hover:underline">privacy@arogio.com</a></p>
            </section>
        </div>
    </div>
</main>
@endsection

