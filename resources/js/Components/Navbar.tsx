import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { HeartPulse, Globe } from 'lucide-react';

export default function Navbar() {
    const { url, props } = usePage();
    const locale = (props.locale as string) || 'en';

    const switchLanguage = (newLocale: string) => {
        import('@inertiajs/react').then(({ router }) => {
            router.post('/switch-locale', { locale: newLocale });
        });
    };

    const navLinkClass = (path: string) => `px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 ${
        url.startsWith(path)
            ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs'
            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
    }`;

    return (
        <nav className="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition-all duration-300">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-16 items-center gap-4">
                    <div className="flex items-center min-w-0">
                        <Link href="/" className="flex items-center space-x-3 group mr-6 shrink-0">
                            <div className="p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5">
                                <HeartPulse className="w-6 h-6 text-white animate-pulse" />
                            </div>
                            <span className="text-2xl font-bold bg-gradient-to-r from-slate-800 to-indigo-900 bg-clip-text text-transparent tracking-tight">
                                Swasthya<span className="text-teal-600">Search</span>
                            </span>
                        </Link>

                        <div className="hidden md:flex items-center space-x-1 lg:space-x-2">
                            <Link href="/doctors" className={navLinkClass('/doctors')}>
                                {locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors'}
                            </Link>
                            <Link href="/hospitals" className={navLinkClass('/hospitals')}>
                                {locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals'}
                            </Link>
                            <Link href="/articles" className={navLinkClass('/articles')}>
                                {locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles'}
                            </Link>
                            <Link href="/about" className={navLinkClass('/about')}>
                                {locale === 'hi' ? 'हमारे बारे में' : 'About Us'}
                            </Link>
                            <Link href="/contact" className={navLinkClass('/contact')}>
                                {locale === 'hi' ? 'संपर्क करें' : 'Contact Us'}
                            </Link>
                        </div>
                    </div>

                    <div className="flex items-center shrink-0">
                        <div className="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60 shadow-inner">
                            <button
                                onClick={() => switchLanguage('en')}
                                className={`flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ${
                                    locale === 'en'
                                        ? 'bg-white text-indigo-900 shadow-sm font-semibold'
                                        : 'text-slate-600 hover:text-slate-900'
                                }`}
                            >
                                <Globe className="w-4 h-4 text-teal-600" />
                                <span>English</span>
                            </button>
                            <button
                                onClick={() => switchLanguage('hi')}
                                className={`flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ${
                                    locale === 'hi'
                                        ? 'bg-white text-indigo-900 shadow-sm font-semibold'
                                        : 'text-slate-600 hover:text-slate-900'
                                }`}
                            >
                                <Globe className="w-4 h-4 text-teal-600" />
                                <span>हिंदी</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    );
}
