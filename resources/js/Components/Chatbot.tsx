import React, { useState, useEffect, useRef } from 'react';
import { MessageSquare, Send, X, Bot, User, Stethoscope, MapPin, Award, CheckCircle2 } from 'lucide-react';
import { usePage } from '@inertiajs/react';

export default function Chatbot() {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';
    const [isOpen, setIsOpen] = useState(false);
    const [message, setMessage] = useState('');
    const [sessionToken, setSessionToken] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);
    const [history, setHistory] = useState<any[]>([
        {
            sender: 'bot',
            text: locale === 'hi'
                ? 'नमस्ते! मैं स्वास्थ्या एआई हूँ। आप अपनी बीमारी के लक्षण (जैसे "पेट दर्द" या "बुखार") या डॉक्टर का नाम बता सकते हैं।'
                : 'Hello! I am Swasthya AI. You can tell me your symptoms (e.g. "stomach ache" or "fever") or a doctor\'s name, and I will find the right specialist for you.',
            timestamp: new Date().toISOString(),
        }
    ]);

    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [history, isOpen]);

    const handleSend = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!message.trim() || loading) return;

        const userMsg = message;
        setMessage('');
        setHistory((prev) => [...prev, { sender: 'user', text: userMsg, timestamp: new Date().toISOString() }]);
        setLoading(true);

        try {
            const res = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
                },
                body: JSON.stringify({
                    session_token: sessionToken,
                    message: userMsg,
                }),
            });

            const data = await res.json();
            if (data.session_token) setSessionToken(data.session_token);
            if (data.history) {
                setHistory(data.history);
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            setHistory((prev) => [
                ...prev,
                {
                    sender: 'bot',
                    text: locale === 'hi' ? 'क्षमा करें, कोई तकनीकी समस्या आ गई है।' : 'Sorry, a technical error occurred.',
                    timestamp: new Date().toISOString(),
                }
            ]);
        } finally {
            setLoading(false);
        }
    };

    const getLocalizedText = (field: any, fallback = '') => {
        if (!field) return fallback;
        if (typeof field === 'string') return field;
        return field[locale] || field.en || fallback;
    };

    return (
        <div className="fixed bottom-6 right-6 z-50">
            {/* Chat Button */}
            {!isOpen && (
                <button
                    onClick={() => setIsOpen(true)}
                    className="flex items-center gap-3 bg-gradient-to-tr from-teal-500 to-indigo-600 text-white px-6 py-3.5 rounded-full shadow-2xl hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300 transform group"
                >
                    <div className="w-6 h-6 flex items-center justify-center shrink-0 animate-bounce group-hover:animate-none">
                        <MessageSquare className="w-6 h-6 text-white" />
                    </div>
                    <span className="font-bold text-base tracking-wide whitespace-nowrap leading-none pt-0.5">
                        {locale === 'hi' ? 'स्वास्थ्या एआई से पूछें' : 'Ask Swasthya AI'}
                    </span>
                </button>
            )}

            {/* Chat Window */}
            {isOpen && (
                <div className="w-[90vw] sm:w-[420px] h-[550px] bg-white rounded-3xl shadow-2xl border border-slate-200/80 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-5 duration-300">
                    {/* Header */}
                    <div className="bg-gradient-to-r from-slate-900 to-indigo-900 text-white p-4 flex justify-between items-center shadow-md">
                        <div className="flex items-center space-x-3">
                            <div className="p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30">
                                <Bot className="w-6 h-6 text-teal-400" />
                            </div>
                            <div>
                                <h3 className="font-bold text-lg leading-tight">Swasthya AI Assistant</h3>
                                <p className="text-xs text-teal-300 flex items-center space-x-1 mt-0.5">
                                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>{locale === 'hi' ? 'लक्षण से डॉक्टर खोजें' : 'Symptom-to-Doctor AI'}</span>
                                </p>
                            </div>
                        </div>
                        <button
                            onClick={() => setIsOpen(false)}
                            className="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200"
                        >
                            <X className="w-5 h-5" />
                        </button>
                    </div>

                    {/* Messages Body */}
                    <div className="flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50/50">
                        {history.map((msg, index) => (
                            <div
                                key={index}
                                className={`flex ${msg.sender === 'user' ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200`}
                            >
                                <div className={`flex space-x-2 max-w-[85%] ${msg.sender === 'user' ? 'flex-row-reverse space-x-reverse' : 'flex-row'}`}>
                                    <div className={`w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${
                                        msg.sender === 'user' ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'
                                    }`}>
                                        {msg.sender === 'user' ? <User className="w-4 h-4" /> : <Bot className="w-4 h-4" />}
                                    </div>

                                    <div className="space-y-2">
                                        <div className={`p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${
                                            msg.sender === 'user'
                                                ? 'bg-indigo-600 text-white rounded-tr-none'
                                                : 'bg-white text-slate-800 border border-slate-200/60 rounded-tl-none'
                                        }`}>
                                            {msg.text}
                                        </div>

                                        {/* Doctor Cards in Bot Reply */}
                                        {msg.doctors && msg.doctors.length > 0 && (
                                            <div className="space-y-2 pt-1">
                                                {msg.doctors.map((doc: any) => {
                                                    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                                                    const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                                                    const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';

                                                    return (
                                                        <div key={doc.id} className="bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200">
                                                            <div className="flex justify-between items-start">
                                                                <h4 className="font-bold text-sm text-indigo-950 flex items-center space-x-1">
                                                                    <span>{fullName}</span>
                                                                    {doc.is_verified && <CheckCircle2 className="w-3.5 h-3.5 text-teal-600 inline" />}
                                                                </h4>
                                                                <span className="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-lg font-medium">
                                                                    {doc.experience_years} {locale === 'hi' ? 'वर्ष अनुभव' : 'yrs exp'}
                                                                </span>
                                                            </div>

                                                            <div className="mt-2 space-y-1 text-xs text-slate-600">
                                                                <div className="flex items-center space-x-1">
                                                                    <Stethoscope className="w-3.5 h-3.5 text-teal-600 shrink-0" />
                                                                    <span className="font-medium text-slate-700">
                                                                        {deptName}
                                                                    </span>
                                                                </div>

                                                                {doc.hospitals && doc.hospitals.length > 0 && (
                                                                    <div className="flex items-start space-x-1 pt-0.5">
                                                                        <MapPin className="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5" />
                                                                        <span>{getLocalizedText(doc.hospitals[0].name)}</span>
                                                                    </div>
                                                                )}
                                                            </div>

                                                            {emergencyPhone && (
                                                                <div className="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                                                    <a
                                                                        href={`tel:${emergencyPhone}`}
                                                                        className="text-xs bg-teal-50 hover:bg-teal-600 text-white font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200"
                                                                    >
                                                                        {locale === 'hi' ? 'कॉल करें' : 'Call Doctor'}
                                                                    </a>
                                                                </div>
                                                            )}
                                                        </div>
                                                    );
                                                })}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}

                        {loading && (
                            <div className="flex space-x-2 items-center text-slate-400 text-sm italic">
                                <Bot className="w-5 h-5 text-teal-500 animate-spin" />
                                <span>{locale === 'hi' ? 'स्वास्थ्या एआई सोच रहा है...' : 'Swasthya AI is thinking...'}</span>
                            </div>
                        )}

                        <div ref={messagesEndRef} />
                    </div>

                    {/* Input Footer */}
                    <form onSubmit={handleSend} className="p-3 bg-white border-t border-slate-200/80 flex items-center space-x-2 shadow-lg">
                        <input
                            type="text"
                            value={message}
                            onChange={(e) => setMessage(e.target.value)}
                            placeholder={locale === 'hi' ? 'लक्षण या डॉक्टर का नाम लिखें...' : 'Type a symptom or doctor name...'}
                            className="flex-1 bg-slate-100 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200"
                        />
                        <button
                            type="submit"
                            disabled={loading || !message.trim()}
                            className="bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-300 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95"
                        >
                            <Send className="w-5 h-5" />
                        </button>
                    </form>
                </div>
            )}
        </div>
    );
}
