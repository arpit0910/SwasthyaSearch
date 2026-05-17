@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy') . ' - SwasthyaSearch')

@section('content')
<main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>{{ $locale === 'hi' ? 'मुखपृष्ठ पर लौटें' : 'Back to Home' }}</span>
        </a>
        <div class="flex items-center space-x-1.5 text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1.5 rounded-full border border-teal-100 shadow-2xs">
            <i data-lucide="shield-check" class="w-4 h-4 text-teal-600"></i>
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
                    {{ $locale === 'hi' ? 'स्वास्थ्या सर्च ("हम", "हमारा", या "मंच") आपकी निजता का सम्मान करता है। यह गोपनीयता नीति बताती है कि जब आप हमारी स्वास्थ्य निर्देशिका और एआई चैटबॉट का उपयोग करते हैं तो हम आपके डेटा की सुरक्षा कैसे करते हैं। हमारी सेवा पूरी तरह से विज्ञापन-मुक्त है और मरीजों के लिए निःशुल्क है।' : 'SwasthyaSearch ("we", "our", or "platform") respects your privacy. This Privacy Policy explains how we protect your data when you use our healthcare directory and AI chatbot. Our service is completely ad-free and 100% free for patients.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '2. हम कौन सा डेटा एकत्र करते हैं' : '2. Data We Collect' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'हम कोई भी व्यक्तिगत पहचान योग्य चिकित्सा इतिहास या संवेदनशील स्वास्थ्य डेटा एकत्र या संग्रहीत नहीं करते हैं। हम केवल निम्नलिखित जानकारी एकत्र करते हैं:' : 'We do not collect or store any personally identifiable medical history or sensitive health data. We only collect the following information:' }}
                </p>
                <ul class="list-disc pl-6 space-y-2 text-slate-600">
                    <li>
                        <strong>{{ $locale === 'hi' ? 'खोज क्वेरी और लक्षण:' : 'Search Queries & Symptoms:' }}</strong>
                        {{ $locale === 'hi' ? 'सही डॉक्टर और विभाग से मिलान करने के लिए आपके द्वारा दर्ज किए गए लक्षण या डॉक्टर के नाम। इस डेटा का उपयोग केवल एआई एम्बेडिंग और खोज परिणामों के लिए किया जाता है।' : 'Symptoms or doctor names you enter to match you with the right department and specialists. This data is used solely for AI embeddings and search results.' }}
                    </li>
                    <li>
                        <strong>{{ $locale === 'hi' ? 'चैटबॉट सत्र:' : 'Chatbot Sessions:' }}</strong>
                        {{ $locale === 'hi' ? 'संवाद बनाए रखने के लिए अनाम सत्र टोकन। यह किसी भी उपयोगकर्ता खाते से जुड़ा नहीं है।' : 'Anonymous session tokens to maintain conversational context. This is not linked to any user account.' }}
                    </li>
                    <li>
                        <strong>{{ $locale === 'hi' ? 'लेख टिप्पणियां:' : 'Article Comments:' }}</strong>
                        {{ $locale === 'hi' ? 'यदि आप हमारे स्वास्थ्य लेखों पर टिप्पणी करना चुनते हैं तो आपके द्वारा प्रदान किया गया नाम और टिप्पणी।' : 'The name and comment you provide if you choose to leave feedback on our health articles.' }}
                    </li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '3. हम आपके डेटा का उपयोग कैसे करते हैं' : '3. How We Use Your Data' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'एकत्र की गई जानकारी का उपयोग पूरी तरह से आपको सटीक चिकित्सा विशेषज्ञ और अस्पताल के विवरण प्रदान करने के लिए किया जाता है। हम किसी भी तीसरे पक्ष, विज्ञापनदाताओं या बीमा कंपनियों को आपका डेटा कभी नहीं बेचते, किराए पर नहीं देते या साझा नहीं करते हैं।' : 'The information collected is used strictly to provide you with accurate medical specialist and hospital details. We never sell, rent, or share your data with third parties, advertisers, or insurance companies.' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '4. डेटा सुरक्षा और कुकीज़' : '4. Data Security & Cookies' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'हम आपके खोज सत्रों की सुरक्षा के लिए उद्योग-मानक एन्क्रिप्शन (HTTPS) का उपयोग करते हैं। हम ट्रैकिंग कुकीज़ या आक्रामक विज्ञापन लिपियों का उपयोग नहीं करते हैं। आपकी भाषा प्राथमिकता (अंग्रेजी/हिंदी) को याद रखने के लिए केवल आवश्यक स्थानीय कुकीज़ का उपयोग किया जाता है।' : 'We use industry-standard encryption (HTTPS) to protect your search sessions. We do not use tracking cookies or invasive advertising scripts. Only essential local cookies are used to remember your language preference (English/Hindi).' }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-slate-900 border-l-4 border-teal-500 pl-3 py-0.5 bg-teal-50/40 rounded-r-lg">
                    {{ $locale === 'hi' ? '5. संपर्क करें' : '5. Contact Us' }}
                </h2>
                <p>
                    {{ $locale === 'hi' ? 'यदि इस गोपनीयता नीति के बारे में आपके कोई प्रश्न हैं, तो कृपया हमसे privacy@swasthyasearch.com पर संपर्क करें।' : 'If you have any questions about this Privacy Policy, please contact us at privacy@swasthyasearch.com.' }}
                </p>
            </section>
        </div>
    </div>
</main>
@endsection
