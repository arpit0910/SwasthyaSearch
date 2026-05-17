<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }} - SwasthyaSearch</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#F8FAFC] font-['Outfit',sans-serif] antialiased text-[#2D3748] min-h-screen flex flex-col selection:bg-teal-500 selection:text-white">

    <!-- Header Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl text-white shadow-md shadow-teal-500/20 group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                    Swasthya<span class="text-teal-600">Search</span>
                </span>
            </a>

            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-sm font-bold text-slate-600 hover:text-teal-600 transition-colors py-2 px-4 rounded-xl hover:bg-slate-50">
                    {{ $locale === 'hi' ? 'मुखपृष्ठ' : 'Home' }}
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>{{ $locale === 'hi' ? 'मुखपृष्ठ पर लौटें' : 'Back to Home' }}</span>
            </a>
            <div class="flex items-center space-x-1.5 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-full border border-teal-100">
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
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
                        {{ $locale === 'hi'
                            ? 'स्वास्थ्या सर्च ("मंच") का उपयोग करके, आप इन सेवा की शर्तों से बाध्य होने के लिए सहमत हैं। यदि आप इन शर्तों के किसी भी भाग से सहमत नहीं हैं, तो कृपया हमारी निर्देशिका या एआई चैटबॉट का उपयोग न करें।'
                            : 'By accessing and using SwasthyaSearch ("platform"), you agree to be bound by these Terms of Service. If you do not agree with any part of these terms, please do not use our directory or AI chatbot.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '2. कोई चिकित्सा सलाह नहीं (अस्वीकरण)' : '2. No Medical Advice (Disclaimer)' }}
                    </h2>
                    <p class="p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900 rounded-r-xl font-medium">
                        {{ $locale === 'hi'
                            ? 'स्वास्थ्या सर्च एक स्वास्थ्य निर्देशिका और एआई-संचालित खोज उपकरण है। मंच पर प्रदान की गई कोई भी जानकारी, लेख या चैटबॉट प्रतिक्रिया पेशेवर चिकित्सा सलाह, निदान या उपचार का विकल्प नहीं है। किसी भी चिकित्सा स्थिति के संबंध में हमेशा एक योग्य चिकित्सक या स्वास्थ्य सेवा प्रदाता की सलाह लें। आपातकाल की स्थिति में, तुरंत अपने नजदीकी अस्पताल से संपर्क करें।'
                            : 'SwasthyaSearch is a healthcare directory and AI-powered search tool. No information, article, or chatbot response provided on the platform constitutes professional medical advice, diagnosis, or treatment. Always seek the advice of a qualified physician or healthcare provider regarding any medical condition. In case of a medical emergency, contact your nearest hospital immediately.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '3. निर्देशिका सटीकता और सत्यापन' : '3. Directory Accuracy & Verification' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'हम यह सुनिश्चित करने का हर संभव प्रयास करते हैं कि डॉक्टरों के पंजीकरण नंबर, अस्पताल के पते और फोन नंबर सटीक और सत्यापित हों। हालांकि, हम समय के साथ क्लिनिक के समय या फोन नंबरों में बदलाव की गारंटी नहीं दे सकते। मरीजों को यात्रा करने से पहले सीधे अस्पताल या डॉक्टर से पुष्टि करने की सलाह दी जाती है।'
                            : 'We make every effort to ensure that doctor registration numbers, hospital addresses, and phone numbers are accurate and verified. However, we cannot guarantee changes in clinic timings or phone numbers over time. Patients are advised to confirm appointments directly with the hospital or doctor before visiting.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '4. उपयोगकर्ता आचरण और टिप्पणियां' : '4. User Conduct & Comments' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'हमारे स्वास्थ्य लेखों पर टिप्पणी करते समय, आप सम्मानजनक भाषा का उपयोग करने के लिए सहमत होते हैं। हम किसी भी ऐसी टिप्पणी को हटाने का अधिकार सुरक्षित रखते हैं जो अपमानजनक, भ्रामक, स्पैम या प्रचार सामग्री हो।'
                            : 'When leaving comments on our health articles, you agree to use respectful language. We reserve the right to remove any comments that are abusive, misleading, spam, or promotional in nature.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '5. सेवा में संशोधन' : '5. Modifications to Service' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'हम बिना किसी पूर्व सूचना के किसी भी समय मंच या उसके किसी भी भाग को संशोधित या बंद करने का अधिकार सुरक्षित रखते हैं। हम मंच के किसी भी संशोधन, निलंबन या बंद होने के लिए आपके या किसी तीसरे पक्ष के प्रति उत्तरदायी नहीं होंगे।'
                            : 'We reserve the right to modify or discontinue the platform or any part of it at any time without prior notice. We shall not be liable to you or any third party for any modification, suspension, or discontinuance of the platform.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '6. शासी कानून' : '6. Governing Law' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'ये शर्तें भारत के कानूनों के अनुसार शासित और तैयार की जाएंगी। मंच के उपयोग से उत्पन्न होने वाला कोई भी विवाद जयपुर, राजस्थान के न्यायालयों के विशेष अधिकार क्षेत्र के अधीन होगा।'
                            : 'These terms shall be governed by and construed in accordance with the laws of India. Any disputes arising from the use of the platform shall be subject to the exclusive jurisdiction of the courts in Jaipur, Rajasthan.' }}
                    </p>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white border-t border-slate-800 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight">
                    Swasthya<span class="text-teal-400">Search</span>
                </span>
            </div>

            <p class="text-sm text-slate-400 text-center md:text-left">
                {{ $locale === 'hi'
                    ? '© 2026 स्वास्थ्या सर्च। मरीजों के लिए पूर्णतः निःशुल्क और विज्ञापन-मुक्त स्वास्थ्य निर्देशिका।'
                    : '© 2026 SwasthyaSearch. 100% free, ad-free healthcare directory connecting patients directly to providers.' }}
            </p>

            <div class="flex space-x-6 text-sm text-slate-400">
                <a href="{{ route('privacy.policy') }}" class="hover:text-white transition-colors">{{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}</a>
                <a href="{{ route('terms.service') }}" class="hover:text-white transition-colors">{{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }}</a>
            </div>
        </div>
    </footer>
</body>
</html>
