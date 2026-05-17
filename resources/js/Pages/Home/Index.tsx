import React, { useState } from 'react';
import { usePage, router } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { Search, Stethoscope, MapPin, CheckCircle2, Phone, Mail, Award, Sparkles, HeartPulse, User, BookOpen, Clock, X, ChevronDown, Globe, Navigation, Languages, FileText, Trophy } from 'lucide-react';

export default function Index({ departments, doctors: initialDoctors, articles, faqs }: { departments: any[], doctors: any[], articles: any[], faqs: any[] }) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';

    const [query, setQuery] = useState('');
    const [doctors, setDoctors] = useState<any[]>(initialDoctors || []);
    const [matchedDepartment, setMatchedDepartment] = useState<string | null>(null);
    const [matchedDisease, setMatchedDisease] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);
    const [openFaq, setOpenFaq] = useState<number | null>(null);

    const handleSearch = async (searchQuery: string) => {
        setQuery(searchQuery);
        setLoading(true);

        try {
            const res = await fetch(`/api/search?q=${encodeURIComponent(searchQuery)}`);
            const data = await res.json();
            setDoctors(data.doctors || []);
            setMatchedDepartment(data.matched_department || null);
            setMatchedDisease(data.matched_disease || null);
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            setLoading(false);
        }
    };

    const quickSymptoms = locale === 'hi' ? [
        'बुखार और खांसी', 'हड्डी का टूटना', 'छाती में दर्द', 'त्वचा पर चकत्ते या मुँहासे', 'पेट दर्द', 'गुर्दे की पथरी'
    ] : [
        'Fever and Cough', 'Bone Fracture', 'Chest Pain', 'Skin Rash or Acne', 'Stomach Ache', 'Kidney Stones'
    ];

    const getLocalizedText = (field: any, fallback = '') => {
        if (!field) return fallback;
        if (typeof field === 'string') return field;
        return field[locale] || field.en || fallback;
    };

    return (
        <div className="min-h-screen bg-[#F8FAFC] flex flex-col selection:bg-teal-500 selection:text-white">
            <Navbar />

            {/* Hero Section */}
            <header className="relative overflow-hidden py-20 lg:py-28 bg-gradient-to-b from-indigo-900 via-indigo-950 to-slate-900 text-white">
                <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]"></div>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                    <div className="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 mb-8 animate-fade-in shadow-inner">
                        <Sparkles className="w-4 h-4 text-teal-400 animate-spin" />
                        <span className="text-xs font-semibold tracking-wider uppercase">
                            {locale === 'hi' ? 'स्मार्ट लक्षण-आधारित खोज' : 'Smart Symptom-Based Search'}
                        </span>
                    </div>

                    <h1 className="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-[1.4] sm:leading-[1.3] pt-4 pb-2 text-white drop-shadow-md">
                        {locale === 'hi' ? (
                            <>अपनी बीमारी के <span className="text-teal-400 inline-block">लक्षणों</span> से सही डॉक्टर खोजें</>
                        ) : (
                            <>Find the Right Specialist by Your <span className="text-teal-400 inline-block">Symptoms</span></>
                        )}
                    </h1>

                    <p className="mt-6 text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
                        {locale === 'hi'
                            ? 'अब जटिल मेडिकल शब्दों की चिंता नहीं। अपनी परेशानी या बीमारी का नाम लिखें और तुरंत सही डॉक्टर का पता लगाएं।'
                            : 'Skip the medical jargon. Simply type what is bothering you, and our intelligent directory will connect you with the right verified healthcare providers instantly.'}
                    </p>

                    {/* Omni-Search Box */}
                    <div className="mt-12 max-w-3xl mx-auto px-4 sm:px-0">
                        <div className="relative flex items-center bg-white rounded-3xl shadow-2xl p-2 sm:p-3 border border-slate-200/80 focus-within:ring-4 focus-within:ring-teal-500/20 transition-all duration-300">
                            <Search className="absolute left-6 w-6 h-6 text-slate-400 pointer-events-none" />
                            <input
                                type="text"
                                value={query}
                                onChange={(e) => handleSearch(e.target.value)}
                                placeholder={locale === 'hi' ? 'खोजें: "पेट दर्द", "हड्डी का टूटना", या डॉक्टर का नाम...' : 'Search: "Stomach ache", "Bone fracture", or Doctor name...'}
                                className="w-full pl-14 pr-4 py-4 text-slate-800 bg-transparent text-base sm:text-lg font-medium placeholder:text-slate-400 focus:outline-none"
                            />
                            {query && (
                                <button
                                    onClick={() => handleSearch('')}
                                    className="mr-3 px-3 py-1.5 text-xs text-slate-400 hover:text-slate-600 bg-slate-100 rounded-xl transition-all duration-200"
                                >
                                    Clear
                                </button>
                            )}
                        </div>

                        {/* Quick Symptom Tags */}
                        <div className="mt-6 flex flex-wrap justify-center gap-2 items-center text-sm text-slate-300">
                            <span className="text-xs font-semibold uppercase tracking-wider text-slate-400 mr-2">
                                {locale === 'hi' ? 'सामान्य खोजें:' : 'Popular Searches:'}
                            </span>
                            {quickSymptoms.map((symp) => (
                                <button
                                    key={symp}
                                    onClick={() => handleSearch(symp)}
                                    className="px-4 py-1.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-xs font-medium transition-all duration-200 backdrop-blur-sm"
                                >
                                    {symp}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>
            </header>

            {/* Main Content Area */}
            <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
                {/* Search Match Indicators */}
                {(matchedDisease || matchedDepartment) && query && (
                    <div className="mb-12 bg-gradient-to-r from-teal-500/10 via-indigo-500/10 to-transparent p-6 rounded-3xl border border-teal-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                        <div className="flex items-center space-x-4">
                            <div className="p-3 bg-teal-500 text-white rounded-2xl shadow-md">
                                <Stethoscope className="w-6 h-6" />
                            </div>
                            <div>
                                <h3 className="text-lg font-bold text-slate-800">
                                    {locale === 'hi' ? 'लक्षण विश्लेषण और विभाग मिलान' : 'Symptom Analysis & Department Match'}
                                </h3>
                                <p className="text-sm text-slate-600 mt-0.5">
                                    {matchedDisease && (
                                        <span>{locale === 'hi' ? `लक्षण: "${matchedDisease}"` : `Symptom: "${matchedDisease}"`} • </span>
                                    )}
                                    {matchedDepartment && (
                                        <span className="font-semibold text-teal-700">
                                            {locale === 'hi' ? `अनुशंसित विभाग: ${matchedDepartment}` : `Recommended Department: ${matchedDepartment}`}
                                        </span>
                                    )}
                                </p>
                            </div>
                        </div>
                        <span className="text-xs font-semibold bg-white text-teal-700 px-3 py-1.5 rounded-xl shadow-sm border border-teal-100">
                            {locale === 'hi' ? 'एआई द्वारा सत्यापित' : 'AI Verified Match'}
                        </span>
                    </div>
                )}

                {query ? (
                    /* Search Results View */
                    <div>
                        <div className="flex justify-between items-end mb-8">
                            <div>
                                <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                    {locale === 'hi' ? 'खोज परिणाम' : 'Search Results'}
                                </h2>
                                <p className="text-sm text-slate-500 mt-1">
                                    {locale === 'hi' ? 'बिना किसी विज्ञापन या मध्यस्थ के सीधे संपर्क करें' : 'Direct contact details with zero ads or intermediaries'}
                                </p>
                            </div>
                            <span className="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100">
                                {doctors.length} {locale === 'hi' ? 'डॉक्टर मिले' : 'Doctors Found'}
                            </span>
                        </div>

                        {loading ? (
                            <div className="py-20 text-center text-slate-400 font-medium flex flex-col items-center justify-center space-y-3">
                                <div className="w-10 h-10 border-4 border-teal-500 border-t-transparent rounded-full animate-spin"></div>
                                <span>{locale === 'hi' ? 'डॉक्टर खोजे जा रहे हैं...' : 'Searching healthcare providers...'}</span>
                            </div>
                        ) : doctors.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {doctors.map((doc) => {
                                    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                                    const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                                    const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';

                                    return (
                                        <div
                                            key={doc.id}
                                            className="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-indigo-200 transition-all duration-300 flex flex-col overflow-hidden group"
                                        >
                                            <div className="p-6 pb-4 border-b border-slate-100 flex justify-between items-start bg-gradient-to-b from-slate-50/50 to-transparent">
                                                <div className="flex items-center space-x-3">
                                                    <div className="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-teal-500 text-white flex items-center justify-center font-bold text-xl shadow-md group-hover:scale-105 transition-all duration-300">
                                                        {doc.first_name ? doc.first_name.charAt(0) : <User className="w-6 h-6" />}
                                                    </div>
                                                    <div>
                                                        <h3 className="font-bold text-lg text-slate-900 group-hover:text-indigo-600 transition-colors flex items-center space-x-1.5">
                                                            <span>{fullName}</span>
                                                            {doc.is_verified && (
                                                                <CheckCircle2 className="w-4 h-4 text-teal-600 shrink-0" title="Verified Provider" />
                                                            )}
                                                        </h3>
                                                        <p className="text-xs font-semibold text-teal-600 flex items-center space-x-1 mt-0.5">
                                                            <Stethoscope className="w-3.5 h-3.5" />
                                                            <span>{deptName}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="p-6 flex-1 flex flex-col space-y-4">
                                                {/* Highlights Row: Experience & Consultation Fee */}
                                                <div className="grid grid-cols-2 gap-2 text-xs font-semibold">
                                                    <div className="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                                        <Award className="w-4 h-4 text-indigo-500 shrink-0" />
                                                        <div className="truncate">
                                                            <span className="text-slate-400 block text-[10px] uppercase">{locale === 'hi' ? 'अनुभव' : 'Experience'}</span>
                                                            <span className="text-slate-900 font-bold">{doc.experience_years} {locale === 'hi' ? 'वर्ष' : 'Years'}</span>
                                                        </div>
                                                    </div>
                                                    <div className="bg-slate-50 p-3 rounded-2xl border border-slate-100/80 flex items-center space-x-2 shadow-2xs">
                                                        <FileText className="w-4 h-4 text-teal-500 shrink-0" />
                                                        <div className="truncate">
                                                            <span className="text-slate-400 block text-[10px] uppercase">{locale === 'hi' ? 'परामर्श शुल्क' : 'Fee'}</span>
                                                            <span className="text-slate-900 font-bold">₹{doc.consultation_fee || 500}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Registration & Medical Council */}
                                                {doc.registration_number && (
                                                    <div className="flex items-center justify-between text-xs text-slate-500 px-1 pt-1 border-t border-slate-100">
                                                        <span>{locale === 'hi' ? 'पंजीकरण संख्या:' : 'Reg No:'}</span>
                                                        <span className="font-mono font-semibold text-slate-700">{doc.registration_number} {doc.medical_council ? `(${doc.medical_council})` : ''}</span>
                                                    </div>
                                                )}

                                                {/* Languages Spoken */}
                                                {doc.languages_spoken && doc.languages_spoken.length > 0 && (
                                                    <div className="flex items-center space-x-2 text-xs text-slate-600 px-1">
                                                        <Languages className="w-3.5 h-3.5 text-indigo-400 shrink-0" />
                                                        <span className="text-slate-400 text-[11px]">{locale === 'hi' ? 'भाषाएँ:' : 'Languages:'}</span>
                                                        <span className="font-medium text-slate-700">{doc.languages_spoken.join(', ')}</span>
                                                    </div>
                                                )}

                                                {/* About / Specialization Summary */}
                                                <div className="text-slate-600 text-xs leading-relaxed bg-white flex-1 space-y-2">
                                                    <p className="line-clamp-3">{getLocalizedText(doc.about)}</p>
                                                    {doc.specialization_summary && (
                                                        <p className="text-[11px] text-slate-500 border-l-2 border-teal-500 pl-2 py-0.5 bg-slate-50/50 rounded-r-lg italic">
                                                            {doc.specialization_summary}
                                                        </p>
                                                    )}
                                                </div>

                                                {/* Awards & Recognitions */}
                                                {doc.awards_recognitions && doc.awards_recognitions.length > 0 && (
                                                    <div className="space-y-1 pt-2 border-t border-slate-100">
                                                        <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1">
                                                            <Trophy className="w-3 h-3 text-amber-500" />
                                                            <span>{locale === 'hi' ? 'पुरस्कार एवं सम्मान' : 'Awards & Recognitions'}</span>
                                                        </span>
                                                        <div className="text-[11px] text-slate-600 pl-4 list-disc space-y-0.5">
                                                            {doc.awards_recognitions.map((award: string, i: number) => (
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
                                                    {doc.hospitals?.map((hosp: any) => (
                                                        <div key={hosp.id} className="bg-slate-50 p-4 rounded-2xl border border-slate-100/80 space-y-2 text-xs hover:border-slate-200 transition-colors shadow-2xs">
                                                            <div className="font-bold text-slate-900 flex justify-between items-start gap-2">
                                                                <div>
                                                                    <span className="block text-sm text-indigo-950">{getLocalizedText(hosp.name)}</span>
                                                                    {hosp.type && <span className="text-[10px] font-semibold uppercase tracking-wider text-teal-600 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md inline-block mt-0.5">{hosp.type}</span>}
                                                                </div>
                                                                <span className="text-teal-700 shrink-0 font-extrabold bg-white px-2.5 py-1 rounded-xl border border-teal-100 shadow-2xs">
                                                                    ₹{hosp.pivot?.consultation_fee || doc.consultation_fee || 500}
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

                                            <div className="p-6 pt-0 flex items-center space-x-3">
                                                <a
                                                    href={`tel:${doc.phone || emergencyPhone || '+911412345678'}`}
                                                    className="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"
                                                >
                                                    <Phone className="w-4 h-4 text-teal-100" />
                                                    <span>{locale === 'hi' ? 'अभी कॉल करें' : 'Call Now'}</span>
                                                </a>
                                                {doc.website && (
                                                    <a
                                                        href={doc.website.startsWith('http') ? doc.website : `https://${doc.website}`}
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
                                    );
                                })}
                            </div>
                        ) : (
                            <div className="py-20 text-center bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm max-w-xl mx-auto space-y-4">
                                <div className="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                                    <Search className="w-8 h-8" />
                                </div>
                                <h3 className="text-xl font-bold text-slate-800">
                                    {locale === 'hi' ? 'कोई डॉक्टर नहीं मिला' : 'No Specialists Found'}
                                </h3>
                                <p className="text-slate-500 text-sm">
                                    {locale === 'hi'
                                        ? 'आपकी खोज से मेल खाने वाले कोई डॉक्टर या अस्पताल नहीं मिले। कृपया किसी अन्य लक्षण या विभाग से खोजें।'
                                        : 'We couldn\'t find any healthcare providers matching your exact criteria. Try searching with different symptom keywords.'}
                                </p>
                            </div>
                        )}
                    </div>
                ) : (
                    /* Default Homepage View: Health News & Articles */
                    <div className="space-y-16">
                        <div>
                            <div className="flex justify-between items-end mb-8">
                                <div>
                                    <div className="inline-flex items-center space-x-2 text-indigo-600 font-bold text-sm uppercase tracking-wider mb-2">
                                        <BookOpen className="w-4 h-4" />
                                        <span>{locale === 'hi' ? 'स्वास्थ्य ज्ञान और समाचार' : 'Health Knowledge & News'}</span>
                                    </div>
                                    <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                        {locale === 'hi' ? 'नवीनतम चिकित्सा लेख और स्वास्थ्य सुझाव' : 'Latest Medical Articles & Wellness Tips'}
                                    </h2>
                                    <p className="text-sm text-slate-500 mt-1">
                                        {locale === 'hi' ? 'विशेषज्ञ डॉक्टरों द्वारा प्रमाणित स्वास्थ्य सलाह और जीवनशैली मार्गदर्शन' : 'Expert-verified health advice and lifestyle guidance from top practitioners'}
                                    </p>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                                {articles && articles.map((article) => (
                                    <div
                                        key={article.id}
                                        className="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-teal-200 transition-all duration-300 flex flex-col overflow-hidden group cursor-pointer"
                                        onClick={() => router.get(`/articles/${article.id}`)}
                                    >
                                        <div className="p-6 pb-4 border-b border-slate-100 bg-gradient-to-b from-slate-50/50 to-transparent flex justify-between items-center">
                                            <span className="text-xs font-bold text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-100">
                                                {article.category}
                                            </span>
                                            <div className="flex items-center space-x-1 text-xs text-slate-400 font-medium">
                                                <Clock className="w-3.5 h-3.5" />
                                                <span>3 min read</span>
                                            </div>
                                        </div>

                                        <div className="p-6 flex-1 flex flex-col justify-between space-y-4">
                                            <div className="space-y-2">
                                                <h3 className="font-bold text-lg text-slate-900 group-hover:text-teal-600 transition-colors leading-snug">
                                                    {getLocalizedText(article.title)}
                                                </h3>
                                                <p className="text-sm text-slate-600 leading-relaxed line-clamp-3">
                                                    {getLocalizedText(article.excerpt)}
                                                </p>
                                            </div>

                                            <div className="pt-4 border-t border-slate-100 flex items-center justify-between">
                                                <span className="text-xs font-semibold text-slate-500 italic">
                                                    {locale === 'hi' ? 'लेखक' : 'By'}: {article.author_name}
                                                </span>
                                                <span className="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform flex items-center space-x-1">
                                                    <span>{locale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article'}</span>
                                                    <span>→</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* FAQs Section */}
                        {faqs && faqs.length > 0 && (
                            <div className="pt-12 border-t border-slate-200/80">
                                <div className="text-center max-w-2xl mx-auto mb-10">
                                    <h2 className="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                        {locale === 'hi' ? 'अक्सर पूछे जाने वाले प्रश्न' : 'Frequently Asked Questions'}
                                    </h2>
                                    <p className="text-sm text-slate-500 mt-2">
                                        {locale === 'hi' ? 'स्वास्थ्या सर्च के बारे में आपके सभी सवालों के जवाब' : 'Everything you need to know about SwasthyaSearch'}
                                    </p>
                                </div>

                                <div className="max-w-3xl mx-auto space-y-4">
                                    {faqs.map((faq, idx) => {
                                        const isOpen = openFaq === idx;
                                        return (
                                            <div
                                                key={faq.id}
                                                className="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden transition-all duration-200"
                                            >
                                                <button
                                                    onClick={() => setOpenFaq(isOpen ? null : idx)}
                                                    className="w-full p-5 text-left font-bold text-base text-slate-800 flex justify-between items-center hover:bg-slate-50/50 transition-colors"
                                                >
                                                    <span>{getLocalizedText(faq.question)}</span>
                                                    <ChevronDown className={`w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} />
                                                </button>
                                                {isOpen && (
                                                    <div className="p-5 pt-0 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/30">
                                                        {getLocalizedText(faq.answer)}
                                                    </div>
                                                )}
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </main>

            {/* Chatbot Floating Widget */}
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
