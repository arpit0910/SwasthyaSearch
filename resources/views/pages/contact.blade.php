@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'जयपुर हेल्थकेयर सहायता से संपर्क करें' : 'Contact Jaipur Healthcare Support') . ' - Arogio')
@section('meta_title', $locale === 'hi' ? 'Arogio संपर्क | Support, Listing Corrections और Feedback' : 'Contact Arogio | Support, Listing Corrections & Feedback')
@section('meta_description', $locale === 'hi' ? 'Arogio से support, doctor या hospital listing corrections, update requests और feedback के लिए संपर्क करें।' : 'Contact Arogio for support, doctor or hospital listing corrections, update requests, and feedback.')
@section('meta_keywords', 'Arogio contact, listing correction, doctor update, hospital update, support')

@section('content')
<header class="bg-gradient-to-r from-cyan-800 via-teal-700 to-emerald-700 text-white py-14 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4">{{ $locale === 'hi' ? 'सहायता केंद्र' : 'Support Center' }}</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">{{ $locale === 'hi' ? 'जयपुर हेल्थकेयर सहायता से संपर्क करें' : 'Contact Jaipur Healthcare Support' }}</h1>
        <p class="max-w-3xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">{{ $locale === 'hi' ? 'डॉक्टर, अस्पताल या ब्लड बैंक की जानकारी गलत हो, या प्लेटफ़ॉर्म सहायता चाहिए, तो हमें लिखें।' : 'If doctor, hospital, or blood bank information is incorrect, or you need help using the platform, write to us.' }}</p>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 flex-1 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="space-y-5 lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    {{ $locale === 'hi' ? 'यह फॉर्म चिकित्सा आपातस्थिति के लिए नहीं है। तुरंत मदद चाहिए तो नज़दीकी अस्पताल जाएँ या आपातकालीन सेवाओं से संपर्क करें।' : 'This contact form is not for medical emergencies. If you need urgent medical help, go to the nearest hospital or contact emergency services immediately.' }}
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-3">Contact Options</h3>
                <div class="space-y-3 text-sm text-slate-700">
                    <p><strong>General Support:</strong> <a class="text-teal-700 font-semibold hover:underline" href="mailto:support@arogio.com">support@arogio.com</a></p>
                    <p><strong>Privacy:</strong> <a class="text-teal-700 font-semibold hover:underline" href="mailto:privacy@arogio.com">privacy@arogio.com</a></p>
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

                <button type="submit" class="w-full bg-gradient-to-tr from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-all text-sm uppercase tracking-wider inline-flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send Message</span>
                </button>
            </form>
        </div>
    </div>
</main>
@endsection

