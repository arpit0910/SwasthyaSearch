@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'Terms of Service' : 'Terms of Service') . ' - Arogio')
@section('meta_title', 'Terms of Service | Arogio')
@section('meta_description', 'Read the rules, responsibilities, limits, and disclaimers for using Arogio.')

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
                    <i data-lucide="file-check-2" class="w-4 h-4"></i>
                    <span>Usage Terms</span>
                </span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Terms of Service</h1>
            <p class="text-sm text-slate-500 mt-2">Last Updated: May 25, 2026</p>
        </div>

        <div class="px-6 sm:px-10 py-8 sm:py-10 space-y-8 text-slate-700 leading-relaxed">
            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <h2 class="text-base font-bold text-amber-900 mb-2">Important Disclaimer</h2>
                <p class="text-sm text-amber-900">Arogio is a discovery/directory platform. It does not replace professional medical advice, diagnosis, or treatment. In emergencies, contact your nearest hospital immediately.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">1. Acceptance of Terms</h2>
                <p>By using the platform, you agree to these terms. If you do not agree, please do not use the service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">2. Purpose of Service</h2>
                <p>The platform helps users discover doctors, hospitals, blood banks, and related healthcare information. It is not the final authority for medical decisions.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">3. Listing Accuracy</h2>
                <p>We aim to keep information updated, but timings, fees, availability, and contact details may change. You are responsible for confirming directly before visiting.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">4. User Conduct</h2>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Do not post abusive, misleading, or harmful content.</li>
                    <li>Avoid spam, promotional abuse, or irrelevant submissions.</li>
                    <li>Use the platform lawfully and responsibly.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">5. Limitation of Liability</h2>
                <p>The platform has limited liability for temporary downtime, data inaccuracies, or changes made by third-party providers.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">6. Changes to Terms</h2>
                <p>We may update these terms from time to time. Continued use after updates means acceptance of the revised terms.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-2">7. Governing Law</h2>
                <p>These terms are governed by the laws of India.</p>
            </section>
        </div>
    </div>
</main>
@endsection

