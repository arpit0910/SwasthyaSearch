import React, { useMemo, useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { Activity, HeartPulse, Search, Stethoscope, Users } from 'lucide-react';

interface LocalizedText {
    en: string;
    hi: string;
}

interface Department {
    id: number;
    name: LocalizedText;
    description: LocalizedText;
    diseases_count: number;
    doctors_count: number;
}

interface Props {
    departments: Department[];
}

export default function Departments({ departments }: Props) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const [search, setSearch] = useState('');

    const text = (value?: LocalizedText | string) => {
        if (!value) return '';
        if (typeof value === 'string') return value;
        return locale === 'hi' ? value.hi || value.en : value.en;
    };

    const filteredDepartments = useMemo(() => {
        const keyword = search.trim().toLowerCase();

        if (!keyword) return departments;

        return departments.filter((department) => {
            return [department.name.en, department.name.hi, department.description.en, department.description.hi]
                .filter(Boolean)
                .some((value) => value.toLowerCase().includes(keyword));
        });
    }, [departments, search]);

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'चिकित्सा विभाग | SwasthyaSearch' : 'Medical Departments | SwasthyaSearch'} />
            <Navbar />

            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
                <div className="max-w-7xl mx-auto">
                    <div className="max-w-3xl">
                        <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-2 mb-4">
                            <Stethoscope className="w-4 h-4" />
                            {locale === 'hi' ? 'विशेषज्ञता निर्देशिका' : 'Specialty Directory'}
                        </span>
                        <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                            {locale === 'hi' ? 'सभी चिकित्सा विभाग' : 'All Medical Departments'}
                        </h1>
                        <p className="text-slate-300 text-base sm:text-lg leading-relaxed">
                            {locale === 'hi'
                                ? 'उपलब्ध विशेषज्ञताओं को देखें और समझें कि किस विभाग में कौन से रोग और लक्षण आते हैं।'
                                : 'Browse available specialties and understand which departments handle different diseases and symptoms.'}
                        </p>
                    </div>
                </div>
            </header>

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full py-12">
                <div className="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 mb-8">
                    <div className="relative">
                        <Search className="absolute left-4 top-3.5 w-5 h-5 text-slate-400" />
                        <input
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder={locale === 'hi' ? 'विभाग खोजें...' : 'Search departments...'}
                            className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                        />
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {filteredDepartments.map((department) => (
                        <button
                            key={department.id}
                            onClick={() => router.get('/diseases', { department: department.id })}
                            className="text-left bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg hover:border-teal-200 transition-all duration-200"
                        >
                            <div className="flex items-start justify-between gap-4 mb-4">
                                <div className="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center justify-center shrink-0">
                                    <Stethoscope className="w-6 h-6" />
                                </div>
                                <span className="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">
                                    {locale === 'hi' ? 'विभाग' : 'Department'}
                                </span>
                            </div>

                            <h2 className="text-lg font-extrabold text-slate-900 mb-2">{text(department.name)}</h2>
                            <p className="text-sm text-slate-600 leading-relaxed min-h-[60px]">{text(department.description)}</p>

                            <div className="grid grid-cols-2 gap-3 mt-5 pt-5 border-t border-slate-100">
                                <div className="flex items-center gap-2 text-xs text-slate-600">
                                    <Activity className="w-4 h-4 text-teal-600" />
                                    <span className="font-semibold">{department.diseases_count} {locale === 'hi' ? 'रोग' : 'diseases'}</span>
                                </div>
                                <div className="flex items-center gap-2 text-xs text-slate-600">
                                    <Users className="w-4 h-4 text-indigo-600" />
                                    <span className="font-semibold">{department.doctors_count} {locale === 'hi' ? 'डॉक्टर' : 'doctors'}</span>
                                </div>
                            </div>
                        </button>
                    ))}
                </div>
            </main>

            <Chatbot />
            <footer className="bg-slate-900 text-white border-t border-slate-800 py-10 mt-auto">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                    <div className="flex items-center space-x-3">
                        <div className="p-2 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl">
                            <HeartPulse className="w-6 h-6 text-white" />
                        </div>
                        <span className="text-xl font-bold">Swasthya<span className="text-teal-400">Search</span></span>
                    </div>
                </div>
            </footer>
        </div>
    );
}
