import React, { useState } from 'react';
import { Head, router, usePage } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { Activity, Filter, HeartPulse, RotateCcw, Search, Stethoscope } from 'lucide-react';

interface LocalizedText {
    en: string;
    hi: string;
}

interface Department {
    id: number;
    name: LocalizedText;
}

interface Disease {
    id: number;
    name: LocalizedText;
    department: Department | null;
}

interface Props {
    departments: Department[];
    diseases: Disease[];
    filters: {
        department?: string;
        search?: string;
    };
}

export default function Diseases({ departments, diseases, filters }: Props) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const [department, setDepartment] = useState(filters.department || 'All');
    const [search, setSearch] = useState(filters.search || '');

    const text = (value?: LocalizedText | string) => {
        if (!value) return '';
        if (typeof value === 'string') return value;
        return locale === 'hi' ? value.hi || value.en : value.en;
    };

    const applyFilters = (event?: React.FormEvent) => {
        if (event) event.preventDefault();

        router.get('/diseases', {
            department: department === 'All' ? undefined : department,
            search: search || undefined,
        }, { preserveState: true });
    };

    const resetFilters = () => {
        setDepartment('All');
        setSearch('');
        router.get('/diseases', {}, { preserveState: true });
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'रोग और लक्षण | SwasthyaSearch' : 'Diseases & Symptoms | SwasthyaSearch'} />
            <Navbar />

            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
                <div className="max-w-7xl mx-auto">
                    <div className="max-w-3xl">
                        <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-2 mb-4">
                            <Activity className="w-4 h-4" />
                            {locale === 'hi' ? 'रोग वर्गीकरण' : 'Disease Taxonomy'}
                        </span>
                        <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4">
                            {locale === 'hi' ? 'रोग और लक्षण निर्देशिका' : 'Diseases & Symptoms Directory'}
                        </h1>
                        <p className="text-slate-300 text-base sm:text-lg leading-relaxed">
                            {locale === 'hi'
                                ? 'रोग या लक्षण खोजें और उससे संबंधित चिकित्सा विभाग देखें।'
                                : 'Search diseases or symptoms and see the medical department associated with each one.'}
                        </p>
                    </div>
                </div>
            </header>

            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-10">
                <form onSubmit={applyFilters} className="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="relative md:col-span-2">
                            <Search className="absolute left-4 top-3.5 w-5 h-5 text-slate-400" />
                            <input
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                                placeholder={locale === 'hi' ? 'रोग या लक्षण खोजें...' : 'Search disease or symptom...'}
                                className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                            />
                        </div>

                        <select
                            value={department}
                            onChange={(event) => setDepartment(event.target.value)}
                            className="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                        >
                            <option value="All">{locale === 'hi' ? 'सभी विभाग' : 'All Departments'}</option>
                            {departments.map((item) => (
                                <option key={item.id} value={item.id}>{text(item.name)}</option>
                            ))}
                        </select>
                    </div>

                    <div className="flex flex-wrap justify-end gap-3 mt-5 pt-5 border-t border-slate-100">
                        <button
                            type="button"
                            onClick={resetFilters}
                            className="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-sm flex items-center gap-2"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'रीसेट करें' : 'Reset'}</span>
                        </button>
                        <button
                            type="submit"
                            className="bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-md text-sm flex items-center gap-2"
                        >
                            <Filter className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters'}</span>
                        </button>
                    </div>
                </form>
            </section>

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
                {diseases.length === 0 ? (
                    <div className="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                        <Search className="w-12 h-12 text-slate-300 mx-auto mb-4" />
                        <h2 className="text-xl font-bold text-slate-900">{locale === 'hi' ? 'कोई रोग नहीं मिला' : 'No diseases found'}</h2>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        {diseases.map((disease) => (
                            <div key={disease.id} className="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-lg hover:border-teal-200 transition-all">
                                <div className="flex items-start gap-4">
                                    <div className="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                                        <Activity className="w-5 h-5" />
                                    </div>
                                    <div className="min-w-0">
                                        <h2 className="font-extrabold text-slate-900 leading-snug">{text(disease.name)}</h2>
                                        {disease.department && (
                                            <button
                                                onClick={() => router.get('/doctors', { department: disease.department?.id })}
                                                className="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100 px-3 py-1 rounded-full"
                                            >
                                                <Stethoscope className="w-3.5 h-3.5" />
                                                {text(disease.department.name)}
                                            </button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
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
