import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { HeartPulse, ShieldCheck, Cpu, HeartHandshake, Users, Sparkles } from 'lucide-react';

export default function About() {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'हमारे बारे में | स्वास्थ्या सर्च' : 'About Us | SwasthyaSearch'} />
            <Navbar />

            {/* Hero Section */}
            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-20 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]" />
                <div className="max-w-5xl mx-auto text-center relative z-10">
                    <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
                        {locale === 'hi' ? 'हमारा मिशन और विजन' : 'Our Mission & Vision'}
                    </span>
                    <h1 className="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent leading-tight">
                        {locale === 'hi' ? 'स्वास्थ्य सेवा को सुलभ, पारदर्शी और मुफ़्त बनाना' : 'Empowering Patients with Free, Transparent Healthcare'}
                    </h1>
                    <p className="max-w-3xl mx-auto text-slate-300 text-lg sm:text-xl leading-relaxed">
                        {locale === 'hi'
                            ? 'स्वास्थ्या सर्च भारत की पहली पूर्णतः निःशुल्क, विज्ञापन-मुक्त और शून्य-कमीशन स्वास्थ्य निर्देशिका है। हम मरीजों को सीधे सत्यापित डॉक्टरों और अस्पतालों से जोड़ते हैं।'
                            : 'SwasthyaSearch is India’s premier 100% free, ad-free, and zero-commission healthcare directory. We bridge the gap between patients and verified medical experts without commercial intermediaries.'}
                    </p>
                </div>
            </header>

            {/* Core Values / Features Grid */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex-1 w-full">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    {/* Value 1 */}
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                        <div className="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                            <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                                <ShieldCheck className="w-8 h-8 text-teal-400" />
                            </div>
                        </div>
                        <h3 className="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-teal-600 transition-colors duration-200">
                            {locale === 'hi' ? '100% सत्यापित व प्रामाणिक' : '100% Verified & Authentic'}
                        </h3>
                        <p className="text-slate-600 text-base leading-relaxed flex-1">
                            {locale === 'hi'
                                ? 'हमारी निर्देशिका में सूचीबद्ध प्रत्येक डॉक्टर और अस्पताल की चिकित्सा परिषद पंजीकरण और प्रमाणन की कठोरता से जांच की जाती है। आपके स्वास्थ्य के लिए पूर्ण सुरक्षा।'
                                : 'Every doctor and hospital listed undergoes rigorous background credential and medical council verification. Ensuring complete trust and patient safety.'}
                        </p>
                    </div>

                    {/* Value 2 */}
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                        <div className="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                            <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                                <HeartHandshake className="w-8 h-8 text-indigo-400" />
                            </div>
                        </div>
                        <h3 className="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-indigo-600 transition-colors duration-200">
                            {locale === 'hi' ? 'शून्य कमीशन व कोई विज्ञापन नहीं' : 'Zero Commission & Ad-Free'}
                        </h3>
                        <p className="text-slate-600 text-base leading-relaxed flex-1">
                            {locale === 'hi'
                                ? 'हम डॉक्टरों से कोई छिपा हुआ कमीशन या शुल्क नहीं लेते हैं। हमारा मंच पूरी तरह से विज्ञापन-मुक्त है, यह सुनिश्चित करते हुए कि आपको पक्षपात रहित चिकित्सा सलाह मिले।'
                                : 'We do not charge hidden commissions from healthcare providers or display intrusive advertisements. Ensuring you receive purely unbiased medical guidance.'}
                        </p>
                    </div>

                    {/* Value 3 */}
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                        <div className="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md mb-6 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                            <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                                <Cpu className="w-8 h-8 text-teal-400" />
                            </div>
                        </div>
                        <h3 className="text-2xl font-extrabold text-slate-900 mb-4 group-hover:text-teal-600 transition-colors duration-200">
                            {locale === 'hi' ? 'अत्याधुनिक एआई तकनीक' : 'State-of-the-Art AI'}
                        </h3>
                        <p className="text-slate-600 text-base leading-relaxed flex-1">
                            {locale === 'hi'
                                ? 'हमारा उन्नत एआई चैटबॉट आपके लक्षणों का तुरंत विश्लेषण करता है और आपको सही चिकित्सा विभाग और निकटतम विशेषज्ञ से मिलाता है। स्वास्थ्य खोज को बेहद आसान बनाना।'
                                : 'Our advanced AI Chatbot instantly analyzes your symptoms using semantic vector embeddings, matching you with the correct medical department and specialist seamlessly.'}
                        </p>
                    </div>
                </div>

                {/* Story Section */}
                <section className="mt-20 bg-gradient-to-br from-white via-slate-50 to-teal-50/30 rounded-3xl border border-slate-200/80 p-10 sm:p-16 shadow-sm flex flex-col lg:flex-row items-center gap-12">
                    <div className="flex-1 space-y-6">
                        <div className="flex items-center space-x-2 text-teal-600 font-extrabold text-sm uppercase tracking-wider bg-teal-50 border border-teal-100 px-4 py-1.5 rounded-full inline-flex shadow-2xs">
                            <Sparkles className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'हमारी कहानी' : 'Our Story'}</span>
                        </div>
                        <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-snug">
                            {locale === 'hi'
                                ? 'स्वास्थ्या सर्च की शुरुआत क्यों हुई?'
                                : 'Why We Started SwasthyaSearch'}
                        </h2>
                        <p className="text-slate-600 text-base sm:text-lg leading-relaxed">
                            {locale === 'hi'
                                ? 'वर्तमान डिजिटल स्वास्थ्य सेवा में विज्ञापनों और सशुल्क लिस्टिंग की भरमार है, जहां सबसे अधिक भुगतान करने वाले डॉक्टरों को ही सबसे ऊपर दिखाया जाता है, न कि सबसे योग्य डॉक्टरों को। हमने मरीजों को एक ऐसा मंच देने के लिए स्वास्थ्या सर्च बनाया जो 100% मुफ़्त, निष्पक्ष और पारदर्शी हो।'
                                : 'Modern digital healthcare is crowded with sponsored listings and aggregator commissions, where providers who pay the most receive top visibility rather than those who are most qualified. We established SwasthyaSearch to give patients a platform that is 100% free, unbiased, and fully transparent.'}
                        </p>
                        <p className="text-slate-600 text-base sm:text-lg leading-relaxed">
                            {locale === 'hi'
                                ? 'हमारा लक्ष्य भारत के हर नागरिक को बिना किसी आर्थिक बाधा या भ्रम के बेहतरीन चिकित्सा विशेषज्ञों तक पहुँच प्रदान करना है।'
                                : 'Our ultimate goal is to provide every citizen with direct, barrier-free access to elite medical professionals without financial friction or confusion.'}
                        </p>
                    </div>

                    <div className="w-full lg:w-96 bg-gradient-to-tr from-teal-500 via-indigo-600 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex flex-col justify-center items-center text-center space-y-6 relative overflow-hidden">
                        <div className="absolute inset-0 bg-black/10 backdrop-blur-xs" />
                        <div className="relative z-10 space-y-4">
                            <Users className="w-16 h-16 text-teal-300 mx-auto animate-bounce" />
                            <h4 className="text-2xl font-extrabold tracking-tight">
                                {locale === 'hi' ? 'मरीजों के लिए पूर्णतः मुफ़्त' : '100% Free For Patients'}
                            </h4>
                            <p className="text-slate-200 text-sm leading-relaxed">
                                {locale === 'hi'
                                    ? 'हमारा वादा है कि स्वास्थ्या सर्च मरीजों और डॉक्टरों के लिए हमेशा मुफ़्त रहेगा।'
                                    : 'We pledge that SwasthyaSearch will remain permanently free for patients and healthcare providers.'}
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            <Chatbot />

            {/* Footer */}
            <footer className="bg-slate-900 text-white border-t border-slate-800 py-12 mt-auto">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div className="flex items-center space-x-3">
                        <div className="p-2 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl shadow-md">
                            <HeartPulse className="w-6 h-6 text-white" />
                        </div>
                        <span className="text-xl font-bold tracking-tight">
                            Swasthya<span className="text-teal-400">Search</span>
                        </span>
                    </div>

                    <p className="text-sm text-slate-400 text-center md:text-left">
                        {locale === 'hi'
                            ? '© 2026 स्वास्थ्या सर्च। मरीजों के लिए पूर्णतः निःशुल्क और विज्ञापन-मुक्त स्वास्थ्य निर्देशिका।'
                            : '© 2026 SwasthyaSearch. 100% free, ad-free healthcare directory connecting patients directly to providers.'}
                    </p>

                    <div className="flex space-x-6 text-sm text-slate-400">
                        <a href="/privacy-policy" className="hover:text-white transition-colors">{locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy'}</a>
                        <a href="/terms-of-service" className="hover:text-white transition-colors">{locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service'}</a>
                    </div>
                </div>
            </footer>
        </div>
    );
}
