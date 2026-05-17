import React, { useState } from 'react';
import { Head, usePage, router } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { HeartPulse, Mail, Phone, MapPin, Send, CheckCircle2, AlertCircle } from 'lucide-react';

export default function Contact() {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const successMessage = props.flash?.success || props.success || null;

    const [form, setForm] = useState({
        name: '',
        email: '',
        subject: '',
        message: '',
    });

    const [errors, setErrors] = useState<{ [key: string]: string }>({});
    const [submitting, setSubmitting] = useState(false);

    const validate = () => {
        const newErrors: { [key: string]: string } = {};
        if (!form.name.trim()) newErrors.name = locale === 'hi' ? 'नाम आवश्यक है' : 'Name is required';
        if (!form.email.trim()) {
            newErrors.email = locale === 'hi' ? 'ईमेल आवश्यक है' : 'Email is required';
        } else if (!/\S+@\S+\.\S+/.test(form.email)) {
            newErrors.email = locale === 'hi' ? 'अमान्य ईमेल पता' : 'Invalid email address';
        }
        if (!form.subject.trim()) newErrors.subject = locale === 'hi' ? 'विषय आवश्यक है' : 'Subject is required';
        if (!form.message.trim()) newErrors.message = locale === 'hi' ? 'संदेश आवश्यक है' : 'Message is required';
        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!validate()) return;

        setSubmitting(true);
        router.post('/contact', form, {
            preserveState: true,
            onSuccess: () => {
                setForm({ name: '', email: '', subject: '', message: '' });
                setSubmitting(false);
            },
            onError: (err) => {
                setErrors(err);
                setSubmitting(false);
            },
        });
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'संपर्क करें | स्वास्थ्या सर्च' : 'Contact Us | SwasthyaSearch'} />
            <Navbar />

            {/* Hero Section */}
            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]" />
                <div className="max-w-5xl mx-auto text-center relative z-10">
                    <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
                        {locale === 'hi' ? 'हम आपकी सहायता के लिए यहाँ हैं' : 'We Are Here To Help'}
                    </span>
                    <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                        {locale === 'hi' ? 'हमसे संपर्क करें' : 'Get In Touch With Us'}
                    </h1>
                    <p className="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
                        {locale === 'hi'
                            ? 'क्या आपके पास कोई प्रश्न, सुझाव या प्रतिक्रिया है? हमारी सहायता टीम से संपर्क करें, हम 24 घंटे के भीतर जवाब देंगे।'
                            : 'Have questions, feedback, or need support? Reach out to our dedicated team and we will respond within 24 hours.'}
                    </p>
                </div>
            </header>

            {/* Main Content */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-1 w-full">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                    {/* Contact Info Cards */}
                    <div className="space-y-6 lg:col-span-1">
                        <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                            <div className="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 mb-6 border border-teal-100 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                                <Mail className="w-6 h-6" />
                            </div>
                            <h3 className="text-lg font-extrabold text-slate-900 mb-2">
                                {locale === 'hi' ? 'ईमेल समर्थन' : 'Email Support'}
                            </h3>
                            <p className="text-slate-500 text-sm mb-4 leading-relaxed">
                                {locale === 'hi' ? 'सामान्य पूछताछ और तकनीकी सहायता के लिए।' : 'For general inquiries and technical assistance.'}
                            </p>
                            <a href="mailto:support@swasthyasearch.com" className="text-teal-600 font-extrabold text-sm hover:underline flex items-center space-x-1">
                                <span>support@swasthyasearch.com</span>
                            </a>
                        </div>

                        <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                            <div className="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 border border-indigo-100 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                                <Phone className="w-6 h-6" />
                            </div>
                            <h3 className="text-lg font-extrabold text-slate-900 mb-2">
                                {locale === 'hi' ? 'फ़ोन हेल्पलाइन' : 'Phone Helpline'}
                            </h3>
                            <p className="text-slate-500 text-sm mb-4 leading-relaxed">
                                {locale === 'hi' ? 'सोमवार से शनिवार, सुबह 9:00 बजे से शाम 6:00 बजे तक।' : 'Mon-Sat from 9:00 AM to 6:00 PM IST.'}
                            </p>
                            <a href="tel:+919876543210" className="text-indigo-600 font-extrabold text-sm hover:underline flex items-center space-x-1">
                                <span>+91 98765 43210</span>
                            </a>
                        </div>

                        <div className="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-2xs hover:shadow-xl transition-all duration-300 flex flex-col group">
                            <div className="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-700 mb-6 border border-slate-200 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                                <MapPin className="w-6 h-6" />
                            </div>
                            <h3 className="text-lg font-extrabold text-slate-900 mb-2">
                                {locale === 'hi' ? 'हमारा कार्यालय' : 'Our Office'}
                            </h3>
                            <p className="text-slate-500 text-sm leading-relaxed">
                                SwasthyaSearch Healthcare Directory,<br />
                                45 Park Street, Connaught Place,<br />
                                New Delhi - 110001, India
                            </p>
                        </div>
                    </div>

                    {/* Contact Form */}
                    <div className="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-sm">
                        <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
                            {locale === 'hi' ? 'हमें एक संदेश भेजें' : 'Send Us A Message'}
                        </h2>
                        <p className="text-slate-500 text-sm mb-8 leading-relaxed">
                            {locale === 'hi'
                                ? 'नीचे दिया गया फ़ॉर्म भरें और हमारी ग्राहक सहायता टीम जल्द ही आपसे संपर्क करेगी।'
                                : 'Fill out the form below and our customer support team will get back to you promptly.'}
                        </p>

                        {successMessage && (
                            <div className="mb-8 bg-teal-50 border border-teal-200 text-teal-800 p-6 rounded-2xl flex items-start space-x-4 shadow-2xs animate-fade-in">
                                <CheckCircle2 className="w-6 h-6 text-teal-600 shrink-0 mt-0.5" />
                                <div className="flex-1">
                                    <h4 className="font-extrabold text-base text-teal-900 mb-1">
                                        {locale === 'hi' ? 'संदेश सफलतापूर्वक भेजा गया!' : 'Message Sent Successfully!'}
                                    </h4>
                                    <p className="text-sm text-teal-700 leading-relaxed">{successMessage}</p>
                                </div>
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {/* Name */}
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        {locale === 'hi' ? 'आपका पूरा नाम *' : 'Your Full Name *'}
                                    </label>
                                    <input
                                        type="text"
                                        value={form.name}
                                        onChange={(e) => setForm({ ...form, name: e.target.value })}
                                        placeholder={locale === 'hi' ? 'उदा. राहुल शर्मा' : 'e.g. Rahul Sharma'}
                                        className={`w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium ${
                                            errors.name
                                                ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30'
                                                : 'border-slate-200 focus:border-teal-500 focus:ring-teal-500/20'
                                        }`}
                                    />
                                    {errors.name && (
                                        <p className="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                            <AlertCircle className="w-3.5 h-3.5" />
                                            <span>{errors.name}</span>
                                        </p>
                                    )}
                                </div>

                                {/* Email */}
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                        {locale === 'hi' ? 'ईमेल पता *' : 'Email Address *'}
                                    </label>
                                    <input
                                        type="email"
                                        value={form.email}
                                        onChange={(e) => setForm({ ...form, email: e.target.value })}
                                        placeholder={locale === 'hi' ? 'उदा. rahul@example.com' : 'e.g. rahul@example.com'}
                                        className={`w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium ${
                                            errors.email
                                                ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30'
                                                : 'border-slate-200 focus:border-teal-500 focus:ring-teal-500/20'
                                        }`}
                                    />
                                    {errors.email && (
                                        <p className="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                            <AlertCircle className="w-3.5 h-3.5" />
                                            <span>{errors.email}</span>
                                        </p>
                                    )}
                                </div>
                            </div>

                            {/* Subject */}
                            <div>
                                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    {locale === 'hi' ? 'विषय *' : 'Subject *'}
                                </label>
                                <input
                                    type="text"
                                    value={form.subject}
                                    onChange={(e) => setForm({ ...form, subject: e.target.value })}
                                    placeholder={locale === 'hi' ? 'उदा. अस्पताल लिस्टिंग के बारे में' : 'e.g. Inquiry regarding hospital listing'}
                                    className={`w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium ${
                                        errors.subject
                                            ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30'
                                            : 'border-slate-200 focus:border-teal-500 focus:ring-teal-500/20'
                                    }`}
                                />
                                {errors.subject && (
                                    <p className="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                        <AlertCircle className="w-3.5 h-3.5" />
                                        <span>{errors.subject}</span>
                                    </p>
                                )}
                            </div>

                            {/* Message */}
                            <div>
                                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    {locale === 'hi' ? 'आपका संदेश *' : 'Your Message *'}
                                </label>
                                <textarea
                                    rows={6}
                                    value={form.message}
                                    onChange={(e) => setForm({ ...form, message: e.target.value })}
                                    placeholder={locale === 'hi' ? 'अपना संदेश यहाँ विस्तार से लिखें...' : 'Write your message here in detail...'}
                                    className={`w-full px-4 py-3 bg-slate-50 border rounded-xl text-sm focus:outline-none focus:ring-2 transition-all duration-200 font-medium ${
                                        errors.message
                                            ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 bg-rose-50/30'
                                            : 'border-slate-200 focus:border-teal-500 focus:ring-teal-500/20'
                                    }`}
                                />
                                {errors.message && (
                                    <p className="mt-1.5 text-xs text-rose-500 flex items-center space-x-1 font-semibold">
                                        <AlertCircle className="w-3.5 h-3.5" />
                                        <span>{errors.message}</span>
                                    </p>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={submitting}
                                className="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98 disabled:opacity-50"
                            >
                                <Send className="w-4 h-4 text-white" />
                                <span>{submitting ? (locale === 'hi' ? 'भेजा जा रहा है...' : 'Sending Message...') : (locale === 'hi' ? 'संदेश भेजें' : 'Send Message')}</span>
                            </button>
                        </form>
                    </div>
                </div>
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
