@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'Contact Us' : 'Contact Us') . ' - SwasthyaSearch')
@section('meta_title', 'Contact SwasthyaSearch | Support & Corrections')
@section('meta_description', 'Contact SwasthyaSearch for support, listing corrections, and general questions.')

@section('content')
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-14 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4">Support Center</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">How Can We Help You?</h1>
        <p class="max-w-3xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">If any doctor/hospital details are incorrect, or you need help using the platform, write to us.</p>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 flex-1 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="space-y-5 lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-900 mb-3">Contact Options</h3>
                <div class="space-y-3 text-sm text-slate-700">
                    <p><strong>General Support:</strong> <a class="text-teal-700 font-semibold hover:underline" href="mailto:support@swasthyasearch.com">support@swasthyasearch.com</a></p>
                    <p><strong>Privacy:</strong> <a class="text-teal-700 font-semibold hover:underline" href="mailto:privacy@swasthyasearch.com">privacy@swasthyasearch.com</a></p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-extrabold text-slate-900 mb-3">Send These Details for Faster Resolution</h3>
                <ul class="list-disc pl-5 text-sm text-slate-700 space-y-1.5">
                    <li>Doctor/Hospital/Blood bank name</li>
                    <li>City and state</li>
                    <li>What is incorrect (phone, address, hours, etc.)</li>
                    <li>Correct info (if available)</li>
                </ul>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-sm">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Send a Message</h2>
            <p class="text-slate-500 text-sm mb-6">We usually respond within 1 business day.</p>

            @if (session('success'))
                <div class="mb-6 bg-teal-50 border border-teal-200 text-teal-800 p-4 rounded-xl text-sm">{{ session('success') }}</div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm @error('name') border-rose-300 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" />
                        @error('name')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm @error('email') border-rose-300 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" />
                        @error('email')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm @error('subject') border-rose-300 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500" />
                    @error('subject')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Message *</label>
                    <textarea rows="6" name="message" required class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm @error('message') border-rose-300 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-rose-500">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-all text-sm uppercase tracking-wider inline-flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send Message</span>
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
