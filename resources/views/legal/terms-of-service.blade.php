@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service') . ' - SwasthyaSearch')

@section('content')
<main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'मुखपृष्ठ पर लौटें' : 'Back to Home' }}</span>
        </a>
        <div class="flex items-center space-x-1.5 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-full border border-teal-100 shadow-2xs">
            <i data-lucide="shield-check" class="w-4 h-4 text-teal-600"></i>
            <span>{{ $locale === 'hi' ? 'उपयोगकर्ता समझौता' : 'User Agreement' }}</span>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12 space-y-8">
        <div class="border-b border-slate-100 pb-8 text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-normal py-2 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                {{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }}
            </h1>
            <p class="text-sm text-slate-500 mt-2 font-medium">
                {{ $locale === 'hi' ? 'अंतिम अद्यतन: 17 मई, 2026' : 'Last Updated: May 17, 2026' }}
            </p>
        </div>

        <div class="space-y-6 text-slate-700 text-base leading-relaxed">
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '1. समझौते की स्वीकृति' : '1. Acceptance of Terms' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'स्वास्थ्या सर्च ("मंच") का उपयोग करके, आप इन सेवा की शर्तों से बाध्य होने के लिए सहमत हैं। यदि आप इन शर्तों के किसी भी भाग से सहमत नहीं हैं, तो कृपया हमारी निर्देशिका या एआई चैटबॉट का उपयोग न करें।' : 'By accessing and using SwasthyaSearch ("platform"), you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our directory or AI chatbot.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '2. कोई चिकित्सा सलाह नहीं (अस्वीकरण)' : '2. No Medical Advice (Disclaimer)' }}
                </h2>
                <p class="p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900 rounded-r-xl font-medium shadow-2xs">
                    {{ $locale === 'hi' ? 'स्वास्थ्या सर्च एक स्वास्थ्य निर्देशिका और एआई-संचालित खोज उपकरण है। मंच पर प्रदान की गई कोई भी जानकारी, लेख या चैटबॉट प्रतिक्रिया पेशेवर चिकित्सा सलाह, निदान या उपचार का विकल्प नहीं है। किसी भी चिकित्सा स्थिति के संबंध में हमेशा एक योग्य चिकित्सक या स्वास्थ्य सेवा प्रदाता की सलाह लें। आपातकाल की स्थिति में, तुरंत अपने नजदीकी अस्पताल से संपर्क करें।' : 'SwasthyaSearch is a healthcare directory and AI-powered search tool. No information, article, or chatbot response provided on the platform constitutes professional medical advice, diagnosis, or treatment. Always seek the advice of a qualified physician or healthcare provider regarding any medical condition. In case of a medical emergency, contact your nearest hospital immediately.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '3. निर्देशिका सटीकता और सत्यापन' : '3. Directory Accuracy & Verification' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'हम यह सुनिश्चित करने का हर संभव प्रयास करते हैं कि डॉक्टरों के पंजीकरण नंबर, अस्पताल के पते और फोन नंबर सटीक और सत्यापित हों। हालांकि, हम समय के साथ क्लिनिक के समय या फोन नंबरों में बदलाव की गारंटी नहीं दे सकते। मरीजों को यात्रा करने से पहले सीधे अस्पताल या डॉक्टर से पुष्टि करने की सलाह दी जाती है।' : 'We make every effort to ensure that doctor registration numbers, hospital addresses, and phone numbers are accurate and verified. However, we cannot guarantee changes in clinic timings or phone numbers over time. Patients are advised to confirm appointments directly with the hospital or doctor before visiting.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '4. उपयोगकर्ता आचरण और टिप्पणियां' : '4. User Conduct & Comments' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'हमारे स्वास्थ्य लेखों पर टिप्पणी करते समय, आप सम्मानजनक भाषा का उपयोग करने के fixed होते हैं। हम किसी भी ऐसी टिप्पणी को हटाने का अधिकार सुरक्षित रखते हैं जो अपमानजनक, भ्रामक, स्पैम या प्रचार सामग्री हो।' : 'When leaving comments on our health articles, you agree to use respectful language. We reserve the right to remove any comments that are abusive, misleading, spam, or promotional in nature.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '5. सेवा में संशोधन' : '5. Modifications to Service' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'हम बिना किसी पूर्व सूचना के किसी भी समय मंच या उसके किसी भी भाग को संशोधित या बंद करने का अधिकार सुरक्षित रखते हैं। हम मंच के किसी भी संशोधन, निलंबन या बंद होने के लिए आपके या किसी तीसरे पक्ष के प्रति उत्तरदायी नहीं होंगे।' : 'We reserve the right to modify or discontinue the platform or any part of it at any time without prior notice. We shall not be liable to you or any third party for any modification, suspension, or discontinuance of the platform.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '6. शासी कानून' : '6. Governing Law' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'ये शर्तें भारत के कानूनों के अनुसार शासित और तैयार की जाएंगी। मंच के उपयोग से उत्पन्न होने वाला कोई भी विवाद जयपुर, राजस्थान के न्यायालयों के विशेष अधिकार क्षेत्र के अधीन होगा।' : 'These terms shall be governed by and construed in accordance with the laws of India. Any disputes arising from the use of the platform shall be subject to the exclusive jurisdiction of the courts of Rajasthan.' }}
                </p>
            </section>
        </div>
    </div>
</main>
@endsection



