import React, { useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { HeartPulse, Search, Filter, RotateCcw, Award, MapPin, Clock, CheckCircle2, User, Phone, Globe, Navigation, Mail, Languages, FileText, Trophy, GraduationCap } from 'lucide-react';

interface Doctor {
    id: number;
    first_name: string;
    last_name: string;
    department: { name: { en: string; hi: string } };
    registration_number: string;
    medical_council: string;
    education_degrees: string[];
    experience_years: number;
    about: { en: string; hi: string };
    is_verified: boolean;
    phone?: string;
    website?: string;
    email?: string;
    gender?: string;
    languages_spoken?: string[];
    consultation_fee?: number;
    specialization_summary?: string;
    awards_recognitions?: string[];
    membership_fellowships?: string[];
    hospitals: {
        id: number;
        name: { en: string; hi: string };
        type?: string;
        address?: string;
        city?: string;
        latitude?: number;
        longitude?: number;
        emergency_phone?: string;
        is_verified?: boolean;
        pivot: {
            days_of_week: string;
            start_time: string;
            end_time: string;
            consultation_fee: number;
        };
    }[];
}

interface Props {
    doctors: Doctor[];
    departments: { id: number; name: { en: string; hi: string } }[];
    cities: string[];
    filters: {
        department?: string;
        experience?: string;
        city?: string;
        search?: string;
    };
}

export default function DoctorsIndex({ doctors, departments, cities, filters }: Props) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const [search, setSearch] = useState(filters.search || '');
    const [department, setDepartment] = useState(filters.department || 'All');
    const [experience, setExperience] = useState(filters.experience || 'All');
    const [city, setCity] = useState(filters.city || 'All');

    const getLocalizedText = (obj: { en: string; hi: string } | string | undefined) => {
        if (!obj) return '';
        if (typeof obj === 'string') return obj;
        return locale === 'hi' ? obj.hi || obj.en : obj.en;
    };

    const applyFilters = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        router.get('/doctors', { department, experience, city, search }, { preserveState: true });
    };

    const resetFilters = () => {
        setSearch('');
        setDepartment('All');
        setExperience('All');
        setCity('All');
        router.get('/doctors', {}, { preserveState: true });
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'डॉक्टर निर्देशिका | स्वास्थ्या सर्च' : 'Doctors Directory | SwasthyaSearch'} />
            <Navbar />

            {/* Hero Section */}
            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]" />
                <div className="max-w-7xl mx-auto text-center relative z-10">
                    <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
                        {locale === 'hi' ? 'सत्यापित विशेषज्ञ' : 'Verified Medical Experts'}
                    </span>
                    <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                        {locale === 'hi' ? 'हमारे विशेषज्ञ डॉक्टरों से परामर्श लें' : 'Find & Consult Expert Doctors'}
                    </h1>
                    <p className="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
                        {locale === 'hi'
                            ? 'आपके स्वास्थ्य के लिए 100% सत्यापित, अनुभवी और शीर्ष चिकित्सा विशेषज्ञ। सीधे संपर्क करें, कोई छिपा शुल्क नहीं।'
                            : 'Explore our comprehensive directory of 100% verified, world-class healthcare professionals. Connect directly with zero commission.'}
                    </p>
                </div>
            </header>

            {/* Filter Bar */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
                <form onSubmit={applyFilters} className="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-5 sm:p-6 backdrop-blur-xl">
                    <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 items-stretch">
                        {/* Search Input */}
                        <div className="relative xl:col-span-2">
                            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                            <input
                                type="text"
                                placeholder={locale === 'hi' ? 'डॉक्टर का नाम या लक्षण खोजें...' : 'Search doctor name or keywords...'}
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="h-12 w-full pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                            />
                        </div>

                        {/* Department Filter */}
                        <div>
                            <select
                                value={department}
                                onChange={(e) => setDepartment(e.target.value)}
                                className="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                            >
                                <option value="All">{locale === 'hi' ? 'सभी विभाग' : 'All Departments'}</option>
                                {departments.map((dept) => (
                                    <option key={dept.id} value={dept.id}>
                                        {getLocalizedText(dept.name)}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {/* Experience Filter */}
                        <div>
                            <select
                                value={experience}
                                onChange={(e) => setExperience(e.target.value)}
                                className="h-12 w-full px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                            >
                                <option value="All">{locale === 'hi' ? 'सभी अनुभव' : 'All Experience'}</option>
                                <option value="5">{locale === 'hi' ? '5+ वर्ष' : '5+ Years'}</option>
                                <option value="10">{locale === 'hi' ? '10+ वर्ष' : '10+ Years'}</option>
                                <option value="15">{locale === 'hi' ? '15+ वर्ष' : '15+ Years'}</option>
                                <option value="20">{locale === 'hi' ? '20+ वर्ष' : '20+ Years'}</option>
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

            {/* Doctors Grid */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
                {doctors.length === 0 ? (
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
                        <div className="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                            <Search className="w-10 h-10" />
                        </div>
                        <h3 className="text-2xl font-bold text-slate-900 mb-2">
                            {locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Doctors Found'}
                        </h3>
                        <p className="text-slate-500 text-base mb-8 leading-relaxed">
                            {locale === 'hi'
                                ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई डॉक्टर नहीं मिला। कृपया अपनी खोज मानदंड बदलें।'
                                : 'We could not find any doctors matching your selected filters. Please try modifying your search criteria.'}
                        </p>
                        <button
                            onClick={resetFilters}
                            className="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'सभी डॉक्टर देखें' : 'View All Doctors'}</span>
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {doctors.map((doctor) => (
                            <div
                                key={doctor.id}
                                className="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1"
                            >
                                {/* Card Header */}
                                <div className="p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-start space-x-4">
                                    <div className="w-16 h-16 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform duration-300">
                                        <div className="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-white font-extrabold text-xl tracking-wider">
                                            {doctor.first_name[0]}{doctor.last_name[0]}
                                        </div>
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center space-x-1.5 mb-1">
                                            <h3 className="font-extrabold text-lg text-slate-900 truncate group-hover:text-teal-600 transition-colors duration-200">
                                                Dr. {doctor.first_name} {doctor.last_name}
                                            </h3>
                                            {doctor.is_verified && (
                                                <CheckCircle2 className="w-4 h-4 text-teal-500 shrink-0" />
                                            )}
                                        </div>
                                        <p className="text-xs font-bold text-teal-600 bg-teal-50 border border-teal-100/80 px-3 py-1 rounded-full inline-block mb-2 shadow-2xs truncate max-w-full">
                                            {getLocalizedText(doctor.department?.name)}
                                        </p>
                                        <div className="flex flex-wrap gap-1 text-slate-500 text-xs">
                                            {doctor.education_degrees?.join(', ')}
                                        </div>
                                    </div>
                                </div>

                                {/* Card Body */}
                                <div className="p-6 flex-1 flex flex-col space-y-4">
                                    {/* Highlights Row: Experience & Consultation Fee */}
                                    <div className="grid grid-cols-2 gap-2 text-xs font-semibold">
                                        <div className="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                            <Award className="w-4 h-4 text-indigo-500 shrink-0" />
                                            <div className="truncate">
                                                <span className="text-slate-400 block text-[10px] uppercase">{locale === 'hi' ? 'अनुभव' : 'Experience'}</span>
                                                <span className="text-slate-900 font-bold">{doctor.experience_years} {locale === 'hi' ? 'वर्ष' : 'Years'}</span>
                                            </div>
                                        </div>
                                        <div className="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                            <FileText className="w-4 h-4 text-teal-500 shrink-0" />
                                            <div className="truncate">
                                                <span className="text-slate-400 block text-[10px] uppercase">{locale === 'hi' ? 'परामर्श शुल्क' : 'Fee'}</span>
                                                <span className="text-slate-900 font-bold">₹{doctor.consultation_fee || 500}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Registration & Medical Council */}
                                    {doctor.registration_number && (
                                        <div className="flex items-center justify-between text-xs text-slate-500 px-1 pt-1 border-t border-slate-100">
                                            <span>{locale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:'}</span>
                                            <span className="font-mono font-semibold text-slate-700">{doctor.registration_number} {doctor.medical_council ? `(${doctor.medical_council})` : ''}</span>
                                        </div>
                                    )}

                                    {/* Languages Spoken */}
                                    {doctor.languages_spoken && doctor.languages_spoken.length > 0 && (
                                        <div className="flex items-center space-x-2 text-xs text-slate-600 px-1">
                                            <Languages className="w-3.5 h-3.5 text-indigo-400 shrink-0" />
                                            <span className="text-slate-400 text-[11px]">{locale === 'hi' ? 'भाषाएँ:' : 'Languages:'}</span>
                                            <span className="font-medium text-slate-700">{doctor.languages_spoken.join(', ')}</span>
                                        </div>
                                    )}

                                    {/* About / Specialization Summary */}
                                    <div className="text-slate-600 text-xs leading-relaxed bg-white flex-1 space-y-2">
                                        <p className="line-clamp-3">{getLocalizedText(doctor.about)}</p>
                                        {doctor.specialization_summary && (
                                            <p className="text-[11px] text-slate-500 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 rounded-r-lg italic">
                                                {doctor.specialization_summary}
                                            </p>
                                        )}
                                    </div>

                                    {/* Awards & Recognitions */}
                                    {doctor.awards_recognitions && doctor.awards_recognitions.length > 0 && (
                                        <div className="space-y-1 pt-2 border-t border-slate-100">
                                            <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1">
                                                <Trophy className="w-3 h-3 text-amber-500" />
                                                <span>{locale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions'}</span>
                                            </span>
                                            <div className="text-[11px] text-slate-600 pl-4 list-disc space-y-0.5">
                                                {doctor.awards_recognitions.map((award, i) => (
                                                    <div key={i} className="truncate">• {award}</div>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Hospitals List with Address & Map Directions */}
                                    <div className="pt-4 border-t border-slate-100 space-y-3">
                                        <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                                            <MapPin className="w-3.5 h-3.5 text-teal-500" />
                                            <span>{locale === 'hi' ? 'अभ्यास स्थल एवं पता' : 'Practicing At & Location'}</span>
                                        </h4>
                                        {doctor.hospitals?.map((hosp) => (
                                            <div key={hosp.id} className="bg-slate-50 p-4 rounded-2xl border border-slate-100/80 space-y-2 text-xs hover:border-slate-200 transition-colors shadow-2xs">
                                                <div className="font-bold text-slate-900 flex justify-between items-start gap-2">
                                                    <div>
                                                        <span className="block text-sm text-indigo-950">{getLocalizedText(hosp.name)}</span>
                                                        {hosp.type && <span className="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md inline-block mt-0.5">{hosp.type}</span>}
                                                    </div>
                                                    <span className="text-teal-700 shrink-0 font-extrabold bg-white px-2.5 py-1 rounded-xl border border-teal-100 shadow-2xs">
                                                        ₹{hosp.pivot?.consultation_fee || doctor.consultation_fee || 500}
                                                    </span>
                                                </div>

                                                {/* Full Address Display */}
                                                <p className="text-slate-600 text-[11px] leading-normal pt-1 border-t border-slate-200/60">
                                                    <span className="font-semibold text-slate-700">{locale === 'hi' ? 'पता:' : 'Address:'}</span> {hosp.address || 'Jaipur, Rajasthan'} {hosp.city ? `, ${hosp.city}` : ''}
                                                </p>

                                                {/* Timings */}
                                                <div className="flex items-center justify-between text-slate-500 text-[11px] pt-1">
                                                    <span className="flex items-center space-x-1 pr-1 truncate">
                                                        <Clock className="w-3 h-3 text-slate-400 shrink-0" />
                                                        <span className="truncate">{hosp.pivot?.days_of_week}</span>
                                                    </span>
                                                    <span className="font-semibold text-slate-600 shrink-0">
                                                        {hosp.pivot?.start_time} - {hosp.pivot?.end_time}
                                                    </span>
                                                </div>

                                                {/* Get Directions Link */}
                                                <div className="pt-2 mt-1 border-t border-slate-200/60 flex items-center justify-between gap-2">
                                                    <span className="text-[10px] text-slate-400 italic">
                                                        {hosp.emergency_phone ? `${locale === 'hi' ? 'संपर्क:' : 'Tel:'} ${hosp.emergency_phone}` : ''}
                                                    </span>
                                                    <a
                                                        href={`https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent((hosp.address || '') + ', ' + (hosp.city || 'Jaipur'))}`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="inline-flex items-center space-x-1.5 text-xs text-indigo-600 hover:text-indigo-700 font-bold bg-indigo-50 hover:bg-indigo-100/80 px-3 py-1.5 rounded-xl border border-indigo-100 transition-all shadow-2xs"
                                                    >
                                                        <Navigation className="w-3.5 h-3.5 text-indigo-500" />
                                                        <span>{locale === 'hi' ? 'नक्शा व दिशा-निर्देश' : 'Get Directions'}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* Card Footer */}
                                <div className="p-6 pt-0 bg-white flex items-center space-x-3">
                                    <a
                                        href={`tel:${doctor.phone || '+911412345678'}`}
                                        className="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                                    >
                                        <Phone className="w-4 h-4 text-teal-100" />
                                        <span>{locale === 'hi' ? 'अभी कॉल करें' : 'Call Now'}</span>
                                    </a>
                                    {doctor.website && (
                                        <a
                                            href={doctor.website.startsWith('http') ? doctor.website : `https://${doctor.website}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                                        >
                                            <Globe className="w-4 h-4 text-teal-400" />
                                            <span>{locale === 'hi' ? 'वेबसाइट देखें' : 'Visit Website'}</span>
                                        </a>
                                    )}
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
