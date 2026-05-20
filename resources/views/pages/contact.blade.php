@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'संपर्क करें' : 'Contact Us') . ' - SwasthyaSearch')

@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'हम आपकी सहायता के लिए यहाँ हैं' : 'We Are Here To Help' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $locale === 'hi' ? 'हमसे संपर्क करें' : 'Get In Touch With Us' }}
        </h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
            {{ $locale === 'hi' ? 'क्या आपके पास कोई प्रश्न, सुझाव या प्रतिक्रिया है? हमारी सहायता टीम से संपर्क करें, हम 24 घंटे के भीतर जवाब देंगे।' : 'Have questions, feedback, or need support? Reach out to our dedicated team and we will respond within 24 hours.' }}
        </p>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-1 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
        <!-- Contact Info Cards -->
        <div class="space-y-6 lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 mb-6 border border-teal-100 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                    <i data-lucide="mail" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">
                    {{ $locale === 'hi' ? 'ईमेल समर्थन' : 'Email Support' }}
                </h3>
                <p class="text-slate-500 text-sm mb-4 leading-relaxed">
                    {{ $locale === 'hi' ? 'सामान्य पूछताछ और तकनीकी सहायता के लिए।' : 'For general inquiries and technical assistance.' }}
                </p>
                <a href="mailto:support@swasthyasearch.com" class="text-teal-600 font-extrabold text-sm hover:underline flex items-center space-x-1">
                    <span>support@swasthyasearch.com</span>
                </a>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 border border-indigo-100 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                    <i data-lucide="phone" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">
                    {{ $locale === 'hi' ? 'फ़ोन हेल्पलाइन' : 'Phone Helpline' }}
                </h3>
                <p class="text-slate-500 text-sm mb-4 leading-relaxed">
                    {{ $locale === 'hi' ? 'सोमवार से शनिवार, सुबह 9:00 बजे से शाम 6:00 बजे तक।' : 'Mon-Sat from 9:00 AM to 6:00 PM IST.' }}
                </p>
                <a href="tel:+919876543210" class="text-indigo-600 font-extrabold text-sm hover:underline flex items-center space-x-1">
                    <span>+91 98765 43210</span>
                </a>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-700 mb-6 border border-slate-200 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">
                    {{ $locale === 'hi' ? 'हमारा कार्यालय' : 'Our Office' }}
                </h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    SwasthyaSearch Healthcare Directory,<br />
                    45 Park Street, Connaught Place,<br />
                    New Delhi - 110001, India
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                {{ $locale === 'hi' ? 'हमें एक संदेश भेजें' : 'Send Us A Message' }}
            </h2>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">
                {{ $locale === 'hi' ? 'नीचे दिया गया फ़ॉर्म भरें और हमारी ग्राहक सहायता टीम जल्द ही आपसे संपर्क करेगी।' : 'Fill out the form below and our customer support team will get back to you promptly.' }}
            </p>

            @if (session('success'))
                <div class="mb-8 bg-teal-50 border border-teal-200 text-teal-800 p-6 rounded-2xl flex items-start space-x-4 shadow-2xs animate-fade-in">
                    <i data-lucide="check-circle-2" class="w-6 h-6 text-teal-600 shrink-0 mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="font-extrabold text-base text-teal-900 mb-1">
                            {{ $locale === 'hi' ? 'संदेश सफलतापूर्वक भेजा गया!' : 'Message Sent Successfully!' }}
                        </h4>
                        <p class="text-sm text-teal-700 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            {{ $locale === 'hi' ? 'आपका पूरा नाम *' : 'Your Full Name *' }}
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="{{ $locale === 'hi' ? 'उदा. राहुल शर्मा' : 'e.g. Rahul Sharma' }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium @error('name') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30 @else border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 @enderror"
                        />
                        @error('name')
                            <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            {{ $locale === 'hi' ? 'ईमेल पता *' : 'Email Address *' }}
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="{{ $locale === 'hi' ? 'उदा. rahul@example.com' : 'e.g. rahul@example.com' }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium @error('email') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30 @else border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 @enderror"
                        />
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        {{ $locale === 'hi' ? 'विषय *' : 'Subject *' }}
                    </label>
                    <input
                        type="text"
                        name="subject"
                        value="{{ old('subject') }}"
                        placeholder="{{ $locale === 'hi' ? 'उदा. अस्पताल लिस्टिंग के बारे में' : 'e.g. Inquiry regarding hospital listing' }}"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium @error('subject') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30 @else border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 @enderror"
                    />
                    @error('subject')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        {{ $locale === 'hi' ? 'आपका संदेश *' : 'Your Message *' }}
                    </label>
                    <textarea
                        rows="6"
                        name="message"
                        placeholder="{{ $locale === 'hi' ? 'अपना संदेश यहाँ विस्तार से लिखें...' : 'Write your message here in detail...' }}"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium @error('message') border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30 @else border-slate-200 focus:border-teal-500 focus:ring-teal-500/20 @enderror"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98"
                >
                    <i data-lucide="send" class="w-4 h-4 text-white"></i>
                    <span>{{ $locale === 'hi' ? 'संदेश भेजें' : 'Send Message' }}</span>
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
