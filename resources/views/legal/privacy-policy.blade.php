<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }} - SwasthyaSearch</title>
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
                <span>{{ $locale === 'hi' ? 'गोपनीयता सुरक्षा' : 'Privacy Protected' }}</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12 space-y-8">
            <div class="border-b border-slate-100 pb-8 text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-normal py-2 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent">
                    {{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">
                    {{ $locale === 'hi' ? 'अंतिम अद्यतन: 17 मई, 2026' : 'Last Updated: May 17, 2026' }}
                </p>
            </div>

            <div class="space-y-6 text-slate-700 text-base leading-relaxed">
                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '1. परिचय' : '1. Introduction' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'स्वास्थ्या सर्च ("हम", "हमारा", या "मंच") आपकी निजता का सम्मान करता है। यह गोपनीयता नीति बताती है कि जब आप हमारी स्वास्थ्य निर्देशिका और एआई चैटबॉट का उपयोग करते हैं तो हम आपके डेटा की सुरक्षा कैसे करते हैं। हमारी सेवा पूरी तरह से विज्ञापन-मुक्त है और मरीजों के लिए निःशुल्क है।'
                            : 'SwasthyaSearch ("we", "our", or "platform") respects your privacy. This Privacy Policy explains how we protect your data when you use our healthcare directory and AI chatbot. Our service is completely ad-free and 100% free for patients.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '2. हम कौन सा डेटा एकत्र करते हैं' : '2. Data We Collect' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'हम कोई भी व्यक्तिगत पहचान योग्य चिकित्सा इतिहास या संवेदनशील स्वास्थ्य डेटा एकत्र या संग्रहीत नहीं करते हैं। हम केवल निम्नलिखित जानकारी एकत्र करते हैं:'
                            : 'We do not collect or store any personally identifiable medical history or sensitive health data. We only collect the following information:' }}
                    </p>
                    <ul class="list-disc pl-6 space-y-2 text-slate-600">
                        <li>
                            <strong>{{ $locale === 'hi' ? 'खोज क्वेरी और लक्षण:' : 'Search Queries & Symptoms:' }}</strong>
                            {{ $locale === 'hi'
                                ? 'सही डॉक्टर और विभाग से मिलान करने के लिए आपके द्वारा दर्ज किए गए लक्षण या डॉक्टर के नाम। इस डेटा का उपयोग केवल एआई एम्बेडिंग और खोज परिणामों के लिए किया जाता है।'
                                : 'Symptoms or doctor names you enter to match you with the right department and specialists. This data is used solely for AI embeddings and search results.' }}
                        </li>
                        <li>
                            <strong>{{ $locale === 'hi' ? 'चैटबॉट सत्र:' : 'Chatbot Sessions:' }}</strong>
                            {{ $locale === 'hi'
                                ? 'संवाद बनाए रखने के लिए अनाम सत्र टोकन। यह किसी भी उपयोगकर्ता खाते से जुड़ा नहीं है।'
                                : 'Anonymous session tokens to maintain conversational context. This is not linked to any user account.' }}
                        </li>
                        <li>
                            <strong>{{ $locale === 'hi' ? 'लेख टिप्पणियां:' : 'Article Comments:' }}</strong>
                            {{ $locale === 'hi'
                                ? 'यदि आप हमारे स्वास्थ्य लेखों पर टिप्पणी करना चुनते हैं तो आपके द्वारा प्रदान किया गया नाम और टिप्पणी।'
                                : 'The name and comment you provide if you choose to leave feedback on our health articles.' }}
                        </li>
                    </ul>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '3. हम आपके डेटा का उपयोग कैसे करते हैं' : '3. How We Use Your Data' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'एकत्र की गई जानकारी का उपयोग पूरी तरह से आपको सटीक चिकित्सा विशेषज्ञ और अस्पताल के विवरण प्रदान करने के fixed किया जाता है। हम किसी भी तीसरे पक्ष, विज्ञापनदाताओं या बीमा कंपनियों को आपका डेटा कभी नहीं बेचते, किराए पर नहीं देते या साझा नहीं करते हैं।'
                            : 'The information collected is used strictly to provide you with accurate medical specialist and hospital details. We never sell, rent, or share your data with third parties, advertisers, or insurance companies.' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '4. डेटा सुरक्षा और कुकीज़' : '4. Data Security & Cookies' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'हम आपके खोज सत्रों की सुरक्षा के लिए उद्योग-मानक एन्क्रिप्शन (HTTPS) का उपयोग करते हैं। हम ट्रैकिंग कुकीज़ या आक्रामक विज्ञापन लिपियों का उपयोग नहीं करते हैं। आपकी भाषा प्राथमिकता (अंग्रेजी/हिंदी) को याद रखने के लिए केवल आवश्यक स्थानीय कुकीज़ का उपयोग किया जाता है।'
                            : 'We use industry-standard encryption (HTTPS) to protect your search sessions. We do not use tracking cookies or invasive advertising scripts. Only essential local cookies are used to remember your language preference (English/Hindi).' }}
                    </p>
                </section>

                <section class="space-y-3">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                        {{ $locale === 'hi' ? '5. संपर्क करें' : '5. Contact Us' }}
                    </h2>
                    <p>
                        {{ $locale === 'hi'
                            ? 'यदि इस गोपनीयता नीति के बारे में आपके कोई प्रश्न हैं, तो कृपया हमसे privacy@swasthyasearch.com पर संपर्क करें।'
                            : 'If you have any questions about this Privacy Policy, please contact us at privacy@swasthyasearch.com.' }}
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
