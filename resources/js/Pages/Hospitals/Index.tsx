import React, { useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { HeartPulse, Search, Filter, RotateCcw, MapPin, PhoneCall, CheckCircle2, Building2 } from 'lucide-react';

interface Hospital {
    id: number;
    name: { en: string; hi: string };
    type: string;
    address: string;
    city: string;
    emergency_phone: string;
    is_verified: boolean;
}

interface Props {
    hospitals: Hospital[];
    cities: string[];
    types: string[];
    filters: {
        type?: string;
        city?: string;
        search?: string;
    };
}

export default function HospitalsIndex({ hospitals, cities, types, filters }: Props) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const [search, setSearch] = useState(filters.search || '');
    const [type, setType] = useState(filters.type || 'All');
    const [city, setCity] = useState(filters.city || 'All');

    const getLocalizedText = (obj: { en: string; hi: string } | string | undefined) => {
        if (!obj) return '';
        if (typeof obj === 'string') return obj;
        return locale === 'hi' ? obj.hi || obj.en : obj.en;
    };

    const applyFilters = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        router.get('/hospitals', { type, city, search }, { preserveState: true });
    };

    const resetFilters = () => {
        setSearch('');
        setType('All');
        setCity('All');
        router.get('/hospitals', {}, { preserveState: true });
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'अस्पताल व क्लीनिक | स्वास्थ्या सर्च' : 'Hospitals & Clinics | SwasthyaSearch'} />
            <Navbar />

            {/* Hero Section */}
            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]" />
                <div className="max-w-7xl mx-auto text-center relative z-10">
                    <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
                        {locale === 'hi' ? 'सत्यापित स्वास्थ्य केंद्र' : 'Verified Healthcare Centers'}
                    </span>
                    <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                        {locale === 'hi' ? 'शीर्ष अस्पताल और क्लीनिक खोजें' : 'Explore Top Hospitals & Clinics'}
                    </h1>
                    <p className="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
                        {locale === 'hi'
                            ? 'आपातकालीन संपर्क नंबरों और पूर्ण पते के साथ आपके शहर में 100% सत्यापित और विश्वसनीय चिकित्सा सुविधाएं।'
                            : 'Discover accredited hospitals and specialized healthcare clinics near you. Complete with verified emergency contacts and locations.'}
                    </p>
                </div>
            </header>

            {/* Filter Bar */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
                <form onSubmit={applyFilters} className="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
                    <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 items-stretch">
                        {/* Search Input */}
                        <div className="relative xl:col-span-2">
                            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                            <input
                                type="text"
                                placeholder={locale === 'hi' ? 'अस्पताल का नाम या पता खोजें...' : 'Search hospital name or address...'}
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                            />
                        </div>

                        {/* Type Filter */}
                        <div>
                            <select
                                value={type}
                                onChange={(e) => setType(e.target.value)}
                                className="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                            >
                                <option value="All">{locale === 'hi' ? 'सभी प्रकार' : 'All Facility Types'}</option>
                                {types.map((t) => (
                                    <option key={t} value={t}>
                                        {t}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {/* City Filter */}
                        <div>
                            <select
                                value={city}
                                onChange={(e) => setCity(e.target.value)}
                                className="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                            >
                                <option value="All">{locale === 'hi' ? 'सभी शहर' : 'All Cities'}</option>
                                {cities.map((c) => (
                                    <option key={c} value={c}>
                                        {c}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6 pt-6 border-t border-slate-100">
                        <button
                            type="button"
                            onClick={resetFilters}
                            className="h-12 px-5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'रीसेट करें' : 'Reset Filters'}</span>
                        </button>

                        <button
                            type="submit"
                            className="h-12 bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold px-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm flex items-center justify-center space-x-2 transform active:scale-98 uppercase tracking-wider"
                        >
                            <Filter className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters'}</span>
                        </button>
                    </div>
                </form>
            </section>

            {/* Hospitals Grid */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
                {hospitals.length === 0 ? (
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
                        <div className="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                            <Building2 className="w-10 h-10" />
                        </div>
                        <h3 className="text-2xl font-bold text-slate-900 mb-2">
                            {locale === 'hi' ? 'कोई अस्पताल नहीं मिला' : 'No Hospitals Found'}
                        </h3>
                        <p className="text-slate-500 text-base mb-8 leading-relaxed">
                            {locale === 'hi'
                                ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई अस्पताल या क्लीनिक नहीं मिला। कृपया अपनी खोज मानदंड बदलें।'
                                : 'We could not find any healthcare facilities matching your selected filters. Please try modifying your search criteria.'}
                        </p>
                        <button
                            onClick={resetFilters}
                            className="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'सभी अस्पताल देखें' : 'View All Hospitals'}</span>
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {hospitals.map((hospital) => (
                            <div
                                key={hospital.id}
                                className="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1"
                            >
                                {/* Card Header */}
                                <div className="p-6 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-start space-x-4">
                                    <div className="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300 flex items-center justify-center">
                                        <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white">
                                            <Building2 className="w-8 h-8 text-teal-400" />
                                        </div>
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center space-x-1.5 mb-1.5">
                                            <h3 className="font-extrabold text-lg text-slate-900 truncate group-hover:text-teal-600 transition-colors duration-200">
                                                {getLocalizedText(hospital.name)}
                                            </h3>
                                            {hospital.is_verified && (
                                                <CheckCircle2 className="w-4 h-4 text-teal-500 shrink-0" />
                                            )}
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            <span className="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">
                                                {hospital.type}
                                            </span>
                                            <span className="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full shadow-2xs truncate max-w-[120px]">
                                                {hospital.city}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {/* Card Body */}
                                <div className="p-6 flex-1 flex flex-col space-y-5 bg-white">
                                    <div className="flex items-start space-x-3 text-slate-600 text-xs leading-relaxed bg-slate-50/80 p-4 rounded-2xl border border-slate-100 shadow-2xs">
                                        <MapPin className="w-4 h-4 text-teal-500 shrink-0 mt-0.5" />
                                        <span className="flex-1">{hospital.address}, {hospital.city}</span>
                                    </div>

                                    <div className="flex-1 flex flex-col justify-end space-y-3 pt-2">
                                        <div className="flex items-center justify-between text-xs p-3.5 bg-teal-50/50 rounded-2xl border border-teal-100">
                                            <div className="flex items-center space-x-2 text-teal-900 font-bold">
                                                <PhoneCall className="w-4 h-4 text-teal-600 animate-pulse" />
                                                <span>{locale === 'hi' ? 'आपातकालीन फ़ोन:' : 'Emergency Phone:'}</span>
                                            </div>
                                            <span className="text-slate-900 font-extrabold tracking-wide select-all">
                                                {hospital.emergency_phone}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {/* Card Footer */}
                                <div className="p-6 pt-0 bg-white">
                                    <a
                                        href={`tel:${hospital.emergency_phone}`}
                                        className="w-full bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98"
                                    >
                                        <PhoneCall className="w-4 h-4 text-white" />
                                        <span>{locale === 'hi' ? 'तुरंत कॉल करें' : 'Call Emergency Now'}</span>
                                    </a>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
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
