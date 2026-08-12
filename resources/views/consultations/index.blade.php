@extends('layouts.public')

@section('meta_title', 'Arogio Video Consultation | Private Browser-Based Care')
@section('meta_keywords', 'video consultation, online consultation, browser-based consultation, Arogio')

@php
    $isHi = \App\Helpers\LocaleHelper::current() === 'hi';
@endphp

@section('title', $isHi ? 'वीडियो परामर्श | Arogio' : 'Video Consulting | Arogio')
@section('meta_description', $isHi ? 'Arogio के साथ एक निजी ब्राउज़र-आधारित वीडियो परामर्श शुरू करें।' : 'Start a private browser-based video consultation with Arogio.')

@section('content')
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.18),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(49,46,129,0.14),_transparent_32%)]"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-8 items-center">
            <div class="space-y-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-teal-200 bg-white/80 px-4 py-2 text-sm font-semibold text-teal-700 shadow-sm">
                    {{ $isHi ? 'निजी ब्राउज़र-टू-ब्राउज़र परामर्श' : 'Private browser-to-browser consultation' }}
                </span>
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ $isHi ? 'एक क्लिक में सुरक्षित वीडियो परामर्श शुरू करें।' : 'Start a secure video consultation in one click.' }}
                    </h1>
                    <p class="max-w-2xl text-lg text-slate-600 dark:text-slate-300">
                        {{ $isHi 
                            ? 'आपका कैमरा और माइक्रोफ़ोन ब्राउज़र कॉल के अंदर रहता है। हम कनेक्शन हैंडशेक के लिए नेटिव WebRTC और हमारे अपने ऐप डेटाबेस का उपयोग करते हैं।' 
                            : 'Your camera and microphone stay inside the browser call. We use native WebRTC and our own app database for connection handshakes.' 
                        }}
                    </p>
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $isHi ? 'कोई बाहरी मीटिंग ऐप नहीं' : 'No third-party meeting apps' }}
                        </div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ $isHi ? 'कोई Meet नहीं, कोई Zoom नहीं, कोई अलग कॉल सेवा नहीं।' : 'No Meet, no Zoom, no separate call service.' }}
                        </p>
                    </div>
                    <div class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $isHi ? 'वास्तविक समय में डॉक्टर जॉइन' : 'Real-time admin pickup' }}
                        </div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ $isHi ? 'व्यवस्थापक डैशबोर्ड लंबित कॉल में तुरंत शामिल हो सकता है।' : 'The admin dashboard can join pending calls instantly.' }}
                        </p>
                    </div>
                    <div class="rounded-3xl border border-white/70 bg-white/90 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900/80">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $isHi ? 'सभी आधुनिक ब्राउज़रों में काम करता है' : 'Works in modern browsers' }}
                        </div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ $isHi ? 'संकेत दिए जाने पर कैमरा और माइक्रोफ़ोन एक्सेस की अनुमति दें।' : 'Allow camera and microphone access when prompted.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/70 bg-white/90 p-6 sm:p-8 shadow-xl dark:border-slate-800 dark:bg-slate-900/85">
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ $isHi ? 'परामर्श कक्ष में प्रवेश करें' : 'Enter the consultation room' }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ $isHi ? 'हम तुरंत आपका रूम बना देंगे और आपका स्थानीय वीडियो पूर्वावलोकन तैयार करेंगे।' : 'We’ll create your room immediately and prepare your local video preview.' }}
                    </p>
                </div>

                <form action="{{ route('consultations.store') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label for="patient_name" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $isHi ? 'मरीज का नाम' : 'Patient name' }}
                        </label>
                        <input
                            id="patient_name"
                            name="patient_name"
                            type="text"
                            value="{{ old('patient_name') }}"
                            required
                            maxlength="255"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-teal-400 focus:ring-4 focus:ring-teal-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:focus:ring-teal-900/30"
                            placeholder="{{ $isHi ? 'अपना नाम दर्ज करें' : 'Enter your name' }}"
                        >
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-800/70 dark:text-slate-300">
                        {{ $isHi 
                            ? 'जारी रखने से पहले, अपने ब्राउज़र टैब को खुला रखें और सुनिश्चित करें कि आपका कैमरा और माइक्रोफ़ोन अवरुद्ध नहीं हैं।' 
                            : 'Before you continue, keep your browser tab open and make sure your camera and microphone are not blocked.' 
                        }}
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-500 to-cyan-600 px-5 py-3 font-semibold text-white shadow-lg shadow-cyan-900/15 transition hover:-translate-y-0.5 hover:shadow-xl">
                        {{ $isHi ? 'वीडियो परामर्श शुरू करें' : 'Start video consultation' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
