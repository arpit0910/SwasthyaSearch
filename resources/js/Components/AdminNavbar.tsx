import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { ShieldCheck, Users, Activity, Stethoscope, LogOut, LayoutDashboard, Globe } from 'lucide-react';

export default function AdminNavbar() {
    const { url } = usePage();

    const handleLogout = () => {
        import('@inertiajs/react').then(({ router }) => {
            router.post('/admin/logout');
        });
    };

    const navItems = [
        { name: 'Dashboard', href: '/admin', icon: LayoutDashboard },
        { name: 'Doctors', href: '/admin/doctors', icon: Users },
        { name: 'Departments', href: '/admin/specialties', icon: Stethoscope },
        { name: 'Disease Taxonomy', href: '/admin/diseases', icon: Activity },
    ];

    return (
        <nav className="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-50 shadow-md">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-16 items-center">
                    <div className="flex items-center space-x-8">
                        <Link href="/admin" className="flex items-center space-x-3 group">
                            <div className="p-2 bg-indigo-600 rounded-xl shadow-inner group-hover:bg-indigo-500 transition-all duration-200">
                                <ShieldCheck className="w-6 h-6 text-white" />
                            </div>
                            <span className="text-xl font-bold tracking-tight bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                                Swasthya<span className="text-indigo-400">Admin</span>
                            </span>
                        </Link>

                        <div className="hidden md:flex items-center space-x-1">
                            {navItems.map((item) => {
                                const Icon = item.icon;
                                const isActive = url === item.href || (item.href !== '/admin' && url.startsWith(item.href));
                                return (
                                    <Link
                                        key={item.name}
                                        href={item.href}
                                        className={`flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 ${
                                            isActive
                                                ? 'bg-indigo-600 text-white shadow-sm font-semibold'
                                                : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                                        }`}
                                    >
                                        <Icon className={`w-4 h-4 ${isActive ? 'text-white' : 'text-slate-400'}`} />
                                        <span>{item.name}</span>
                                    </Link>
                                );
                            })}
                        </div>
                    </div>

                    <div className="flex items-center space-x-4">
                        <Link
                            href="/"
                            target="_blank"
                            className="flex items-center space-x-1.5 px-3 py-2 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700/60 transition-all duration-200"
                        >
                            <Globe className="w-4 h-4 text-teal-400" />
                            <span>Public Website</span>
                        </Link>

                        <button
                            onClick={handleLogout}
                            className="flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium text-rose-300 bg-rose-500/10 hover:bg-rose-500 hover:text-white border border-rose-500/30 transition-all duration-200 shadow-sm"
                        >
                            <LogOut className="w-4 h-4" />
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    );
}
