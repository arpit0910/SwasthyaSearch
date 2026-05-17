import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { HeartPulse, Search, Filter, RotateCcw, Calendar, User, MessageSquare, BookOpen } from 'lucide-react';

interface Article {
    id: number;
    title: { en: string; hi: string };
    excerpt: { en: string; hi: string };
    category: string;
    author_name: string;
    created_at: string;
    comments: any[];
}

interface Props {
    articles: Article[];
    categories: string[];
    filters: {
        category?: string;
        search?: string;
    };
    locale?: string;
}

export default function ArticlesIndex({ articles, categories, filters, locale = 'en' }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [category, setCategory] = useState(filters.category || 'All');

    const getLocalizedText = (obj: { en: string; hi: string } | string | undefined) => {
        if (!obj) return '';
        if (typeof obj === 'string') return obj;
        return locale === 'hi' ? obj.hi || obj.en : obj.en;
    };

    const applyFilters = (e?: React.FormEvent) => {
        if (e) e.preventDefault();
        router.get('/articles', { category, search }, { preserveState: true });
    };

    const resetFilters = () => {
        setSearch('');
        setCategory('All');
        router.get('/articles', {}, { preserveState: true });
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 selection:bg-teal-500 selection:text-white">
            <Head title={locale === 'hi' ? 'स्वास्थ्य लेख व समाचार | स्वास्थ्या सर्च' : 'Health Articles & News | SwasthyaSearch'} />
            <Navbar />

            {/* Hero Section */}
            <header className="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]" />
                <div className="max-w-7xl mx-auto text-center relative z-10">
                    <span className="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">
                        {locale === 'hi' ? 'विशेषज्ञ स्वास्थ्य ज्ञान' : 'Expert Medical Knowledge'}
                    </span>
                    <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                        {locale === 'hi' ? 'नवीनतम स्वास्थ्य लेख और सुझाव' : 'Latest Health Articles & Wellness Tips'}
                    </h1>
                    <p className="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">
                        {locale === 'hi'
                            ? 'शीर्ष डॉक्टरों और पोषण विशेषज्ञों द्वारा लिखे गए प्रामाणिक, शोध-आधारित स्वास्थ्य लेख। स्वस्थ जीवनशैली के लिए आवश्यक जानकारी।'
                            : 'Explore evidence-based medical articles, nutritional advice, and fitness tips authored by accredited doctors and healthcare experts.'}
                    </p>
                </div>
            </header>

            {/* Filter Bar */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 w-full mb-12">
                <form onSubmit={applyFilters} className="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6 backdrop-blur-xl">
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        {/* Search Input */}
                        <div className="relative sm:col-span-2">
                            <Search className="absolute left-4 top-3.5 w-5 h-5 text-slate-400" />
                            <input
                                type="text"
                                placeholder={locale === 'hi' ? 'लेख का शीर्षक या विषय खोजें...' : 'Search article title or topics...'}
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium"
                            />
                        </div>

                        {/* Category Filter */}
                        <div>
                            <select
                                value={category}
                                onChange={(e) => setCategory(e.target.value)}
                                className="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium text-slate-700"
                            >
                                <option value="All">{locale === 'hi' ? 'सभी श्रेणियां' : 'All Categories'}</option>
                                {categories.map((cat) => (
                                    <option key={cat} value={cat}>
                                        {cat}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="flex flex-wrap justify-end gap-3 mt-6 pt-6 border-t border-slate-100">
                        <button
                            type="button"
                            onClick={resetFilters}
                            className="px-5 py-2.5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-bold text-sm transition-all duration-200 flex items-center space-x-2 shadow-2xs"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'रीसेट करें' : 'Reset Filters'}</span>
                        </button>

                        <button
                            type="submit"
                            className="bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold px-8 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm flex items-center space-x-2 transform active:scale-98 uppercase tracking-wider"
                        >
                            <Filter className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'फ़िल्टर लागू करें' : 'Apply Filters'}</span>
                        </button>
                    </div>
                </form>
            </section>

            {/* Articles Grid */}
            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
                {articles.length === 0 ? (
                    <div className="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm max-w-2xl mx-auto">
                        <div className="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 border border-teal-100 shadow-inner">
                            <BookOpen className="w-10 h-10" />
                        </div>
                        <h3 className="text-2xl font-bold text-slate-900 mb-2">
                            {locale === 'hi' ? 'कोई लेख नहीं मिला' : 'No Articles Found'}
                        </h3>
                        <p className="text-slate-500 text-base mb-8 leading-relaxed">
                            {locale === 'hi'
                                ? 'आपके द्वारा चुने गए फ़िल्टर से मेल खाने वाला कोई स्वास्थ्य लेख नहीं मिला। कृपया अपनी खोज मानदंड बदलें।'
                                : 'We could not find any health articles matching your selected filters. Please try modifying your search criteria.'}
                        </p>
                        <button
                            onClick={resetFilters}
                            className="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"
                        >
                            <RotateCcw className="w-4 h-4" />
                            <span>{locale === 'hi' ? 'सभी लेख देखें' : 'View All Articles'}</span>
                        </button>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {articles.map((article) => (
                            <div
                                key={article.id}
                                className="bg-white rounded-3xl border border-slate-200/80 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1"
                            >
                                {/* Card Header / Category Banner */}
                                <div className="p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-slate-100 flex items-center justify-between">
                                    <span className="text-xs font-extrabold text-teal-700 bg-teal-50 border border-teal-100 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">
                                        {article.category}
                                    </span>
                                    <div className="flex items-center space-x-1.5 text-slate-400 text-xs font-semibold">
                                        <MessageSquare className="w-3.5 h-3.5 text-indigo-500" />
                                        <span>{article.comments?.length || 0}</span>
                                    </div>
                                </div>

                                {/* Card Body */}
                                <div className="p-6 flex-1 flex flex-col space-y-4 bg-white">
                                    <h3 className="font-extrabold text-xl text-slate-900 leading-snug group-hover:text-teal-600 transition-colors duration-200 line-clamp-2">
                                        {getLocalizedText(article.title)}
                                    </h3>

                                    <div className="flex items-center space-x-4 text-slate-500 text-xs font-medium pt-1 pb-2 border-b border-slate-100">
                                        <div className="flex items-center space-x-1.5">
                                            <User className="w-3.5 h-3.5 text-slate-400" />
                                            <span>{article.author_name}</span>
                                        </div>
                                        <div className="flex items-center space-x-1.5">
                                            <Calendar className="w-3.5 h-3.5 text-slate-400" />
                                            <span>{new Date(article.created_at).toLocaleDateString(locale === 'hi' ? 'hi-IN' : 'en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                        </div>
                                    </div>

                                    <p className="text-slate-600 text-sm leading-relaxed line-clamp-3 flex-1">
                                        {getLocalizedText(article.excerpt)}
                                    </p>
                                </div>

                                {/* Card Footer */}
                                <div className="p-6 pt-0 bg-white">
                                    <Link
                                        href={`/articles/${article.id}`}
                                        className="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2 transform active:scale-98"
                                    >
                                        <BookOpen className="w-4 h-4 text-teal-400" />
                                        <span>{locale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Full Article'}</span>
                                    </Link>
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
