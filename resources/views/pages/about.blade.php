@extends('layouts.public')

@section('title', ($locale === 'hi' ? 'हमारे बारे में' : 'About Us') . ' - SwasthyaSearch')

@section('meta_title', $locale === 'hi' ? 'हमारे बारे में | SwasthyaSearch' : 'About SwasthyaSearch | Mission, Trust & Transparency')
@section('meta_description', $locale === 'hi'
    ? 'SwasthyaSearch के मिशन, 100% विज्ञापन-मुक्त मॉडल, पारदर्शी सूचीकरण और मरीज-केंद्रित स्वास्थ्य खोज दृष्टि के बारे में जानें।'
    : 'Learn about SwasthyaSearch mission, 100% ad-free model, transparent listings, and patient-first healthcare discovery approach.')
@section('content')
<!-- Hero Section -->
<header class="bg-gradient-to-r from-cyan-800 via-teal-700 to-emerald-700 text-white py-20 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
            {{ $locale === 'hi' ? 'हमारा मिशन और विजन' : 'Our Mission & Vision' }}
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">
            {{ $locale === 'hi' ? 'स्वास्थ्य सेवा को सुलभ, पारदर्शी और मुफ़्त बनाना' : 'Empowering Patients with Free, Transparent Healthcare' }}
        </h1>
        <p class="max-w-3xl mx-auto text-slate-300 text-lg sm:text-xl leading-relaxed">
            {{ $locale === 'hi' ? 'स्वास्थ्या सर्च भारत की पहली पूर्णतः निःशुल्क, विज्ञापन-मुक्त और शून्य-कमीशन स्वास्थ्य निर्देशिका है। हम मरीजों को सीधे सत्यापित डॉक्टरों और अस्पतालों से जोड़ते हैं।' : 'SwasthyaSearch is India’s premier 100% free, ad-free, and zero-commission healthcare directory. We bridge the gap between patients and verified medical experts without commercial intermediaries.' }}
        </p>
    </div>
</header>

<!-- Core Values / Features Grid -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex-1 w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <!-- Value 1 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
            <div class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-cyan-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                    <i data-lucide="shield-check" class="w-8 h-8 text-teal-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-teal-600 transition-colors duration-200">
                {{ $locale === 'hi' ? '100% सत्यापित व प्रामाणिक' : '100% Verified & Authentic' }}
            </h3>
            <p class="text-slate-600 text-base leading-relaxed flex-1">
                {{ $locale === 'hi' ? 'हम उपलब्ध सार्वजनिक स्रोतों और समय-समय पर समीक्षा के माध्यम से प्रदाता जानकारी को सटीक और उपयोगी बनाए रखने का प्रयास करते हैं। समय और संपर्क विवरण बदल सकते हैं, इसलिए जाने से पहले सीधे कॉल करें।' : 'We work to keep provider information accurate and useful through available public sources and periodic review. Timings and contact details may change, so please call providers directly before visiting.' }}
            </p>
        </div>

        <!-- Value 2 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
            <div class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-cyan-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                    <i data-lucide="heart-handshake" class="w-8 h-8 text-indigo-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-cyan-600 transition-colors duration-200">
                {{ $locale === 'hi' ? 'शून्य कमीशन व कोई विज्ञापन नहीं' : 'Zero Commission & Ad-Free' }}
            </h3>
            <p class="text-slate-600 text-base leading-relaxed flex-1">
                {{ $locale === 'hi' ? 'हम डॉक्टरों से कोई छिपा हुआ कमीशन या शुल्क नहीं लेते हैं। हमारा मंच पूरी तरह से विज्ञापन-मुक्त है, यह सुनिश्चित करते हुए कि आपको पक्षपात रहित चिकित्सा सलाह मिले।' : 'We do not charge hidden commissions from healthcare providers or display intrusive advertisements. Ensuring you receive purely unbiased medical guidance.' }}
            </p>
        </div>

        <!-- Value 3 -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
            <div class="w-16 h-16 bg-gradient-to-tr from-teal-500 to-cyan-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                    <i data-lucide="cpu" class="w-8 h-8 text-teal-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-teal-600 transition-colors duration-200">
                {{ $locale === 'hi' ? 'अत्याधुनिक एआई तकनीक' : 'State-of-the-Art AI' }}
            </h3>
            <p class="text-slate-600 text-base leading-relaxed flex-1">
                {{ $locale === 'hi' ? 'हमारा एआई असिस्टेंट आपकी बात को सरल भाषा में समझकर संबंधित विभाग या प्रदाता श्रेणी सुझाता है। यह निदान या दवा की सलाह नहीं देता। चिकित्सकीय सलाह के लिए योग्य डॉक्टर से परामर्श करें।' : 'Our AI assistant understands your symptoms in simple language and suggests relevant departments or provider categories. It does not diagnose or prescribe treatment. Please consult a qualified healthcare professional for medical advice.' }}
            </p>
        </div>
    </div>

    <!-- Story Section -->
    <section class="mt-20 bg-gradient-to-br from-white via-slate-50 to-teal-50/30 dark:from-slate-900 dark:via-slate-850 dark:to-teal-950/20 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 p-10 sm:p-16 shadow-sm flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 space-y-6">
            <div class="flex items-center space-x-2 text-teal-600 font-extrabold text-sm uppercase tracking-wider bg-teal-50 border border-teal-100 px-4 py-1.5 rounded-full inline-flex shadow-2xs">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>{{ $locale === 'hi' ? 'हमारी कहानी' : 'Our Story' }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-normal py-0.5">
                {{ $locale === 'hi' ? 'स्वास्थ्या सर्च की शुरुआत क्यों हुई?' : 'Why We Started SwasthyaSearch' }}
            </h2>
            <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                {{ $locale === 'hi' ? 'वर्तमान डिजिटल स्वास्थ्य सेवा में विज्ञापनों और सशुल्क लिस्टिंग की भरमार है, जहां सबसे अधिक भुगतान करने वाले डॉक्टरों को ही सबसे ऊपर दिखाया जाता है, न कि सबसे योग्य डॉक्टरों को। हमने मरीजों को एक ऐसा मंच देने के लिए स्वास्थ्या सर्च बनाया जो 100% मुफ़्त, निष्पक्ष और पारदर्शी हो।' : 'Modern digital healthcare is crowded with sponsored listings and aggregator commissions, where providers who pay the most receive top visibility rather than those who are most qualified. We established SwasthyaSearch to give patients a platform that is 100% free, unbiased, and fully transparent.' }}
            </p>
            <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                {{ $locale === 'hi' ? 'हमारा लक्ष्य भारत के हर नागरिक को बिना किसी आर्थिक बाधा या भ्रम के बेहतरीन चिकित्सा विशेषज्ञों तक पहुँच प्रदान करना है।' : 'Our ultimate goal is to provide every citizen with direct, barrier-free access to elite medical professionals without financial friction or confusion.' }}
            </p>
        </div>

        <div class="w-full lg:w-96 bg-gradient-to-tr from-teal-500 via-indigo-600 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex flex-col justify-center items-center text-center space-y-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-black/10 backdrop-blur-xs"></div>
            <div class="relative z-10 space-y-4">
                <i data-lucide="users" class="w-16 h-16 text-teal-300 mx-auto animate-bounce"></i>
                <h4 class="text-2xl font-extrabold tracking-tight">
                    {{ $locale === 'hi' ? 'मरीजों के लिए पूर्णतः मुफ़्त' : '100% Free For Patients' }}
                </h4>
                <p class="text-slate-200 text-sm leading-relaxed">
                    {{ $locale === 'hi' ? 'हमारा वादा है कि स्वास्थ्या सर्च मरीजों और डॉक्टरों के लिए हमेशा मुफ़्त रहेगा।' : 'We pledge that SwasthyaSearch will remain permanently free for patients and healthcare providers.' }}
                </p>
            </div>
        </div>
    </section>
</main>
@endsection

