import React, { useState } from 'react';
import { usePage, Link } from '@inertiajs/react';
import Navbar from '../../Components/Navbar';
import Chatbot from '../../Components/Chatbot';
import { ArrowLeft, CheckCircle2, HeartPulse, User, BookOpen, Clock, Calendar, MessageSquare, Share2, Bookmark } from 'lucide-react';

export default function Show({ article: initialArticle }: { article: any }) {
    const { props } = usePage();
    const locale = (props.locale as string) || 'en';

    const [article, setArticle] = useState(initialArticle);
    const [commentName, setCommentName] = useState('');
    const [commentText, setCommentText] = useState('');
    const [submittingComment, setSubmittingComment] = useState(false);
    const [commentSuccess, setCommentSuccess] = useState(false);

    const getLocalizedText = (field: any, fallback = '') => {
        if (!field) return fallback;
        if (typeof field === 'string') return field;
        return field[locale] || field.en || fallback;
    };

    const handleCommentSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!commentName.trim() || !commentText.trim() || !article) return;

        setSubmittingComment(true);
        try {
            const res = await fetch(`/api/articles/${article.id}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_name: commentName,
                    comment: commentText,
                }),
            });

            const data = await res.json();
            if (data.success) {
                setArticle({
                    ...article,
                    comments: [...(article.comments || []), data.comment],
                });
                setCommentName('');
                setCommentText('');
                setCommentSuccess(true);
                setTimeout(() => setCommentSuccess(false), 4000);
            }
        } catch (error) {
            console.error('Comment submit error:', error);
        } finally {
            setSubmittingComment(false);
        }
    };

    return (
        <div className="min-h-screen bg-[#F8FAFC] flex flex-col selection:bg-teal-500 selection:text-white">
            <Navbar />

            {/* Header Banner */}
            <header className="relative overflow-hidden py-12 bg-gradient-to-b from-indigo-900 via-indigo-950 to-slate-900 text-white">
                <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]"></div>

                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <Link
                        href="/"
                        className="inline-flex items-center space-x-2 text-sm font-medium text-teal-400 hover:text-teal-300 transition-colors mb-6 group bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10"
                    >
                        <ArrowLeft className="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
                        <span>{locale === 'hi' ? 'मुखपृष्ठ पर वापस जाएं' : 'Back to Home'}</span>
                    </Link>

                    <div className="flex flex-wrap items-center gap-3 mb-4">
                        <span className="text-xs font-bold text-teal-300 bg-teal-500/20 px-3.5 py-1.5 rounded-full border border-teal-500/30 shadow-inner">
                            {article.category}
                        </span>
                        <div className="flex items-center space-x-1 text-xs text-slate-300 font-medium bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                            <Clock className="w-3.5 h-3.5 text-teal-400" />
                            <span>3 min read</span>
                        </div>
                    </div>

                    <h1 className="text-3xl sm:text-5xl font-extrabold tracking-tight leading-[1.3] text-white drop-shadow-md mb-6">
                        {getLocalizedText(article.title)}
                    </h1>

                    <div className="flex items-center space-x-4 pt-4 border-t border-white/10 text-sm text-slate-300">
                        <div className="w-10 h-10 rounded-full bg-gradient-to-tr from-teal-500 to-indigo-500 flex items-center justify-center font-bold text-white shadow-md">
                            {article.author_name ? article.author_name.charAt(0) : <User className="w-5 h-5" />}
                        </div>
                        <div>
                            <p className="font-semibold text-white">{article.author_name}</p>
                            <p className="text-xs text-slate-400">
                                {article.created_at ? new Date(article.created_at).toLocaleDateString(locale === 'hi' ? 'hi-IN' : 'en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : ''}
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            {/* Main Article Content */}
            <main className="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
                <article className="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10 mb-12">
                    {/* Excerpt Box */}
                    <div className="mb-8 p-6 bg-gradient-to-r from-teal-50/50 to-indigo-50/50 rounded-2xl border-l-4 border-teal-500 border border-slate-100 shadow-2xs">
                        <p className="font-medium text-slate-800 text-lg leading-relaxed italic">
                            "{getLocalizedText(article.excerpt)}"
                        </p>
                    </div>

                    {/* Main Content */}
                    <div className="prose prose-slate max-w-none text-slate-700 text-base sm:text-lg leading-relaxed space-y-6 whitespace-pre-line">
                        {getLocalizedText(article.content)}
                    </div>
                </article>

                {/* Comments Section */}
                <section className="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
                    <div className="border-b border-slate-100 pb-6 mb-8 flex items-center justify-between">
                        <h2 className="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                            <MessageSquare className="w-6 h-6 text-teal-600" />
                            <span>{locale === 'hi' ? 'पाठक प्रतिक्रियाएं' : 'Reader Comments'}</span>
                        </h2>
                        <span className="text-sm font-semibold bg-indigo-50 text-indigo-700 px-3.5 py-1.5 rounded-full border border-indigo-100">
                            {(article.comments || []).length} {locale === 'hi' ? 'टिप्पणियां' : 'Comments'}
                        </span>
                    </div>

                    {/* Existing Comments List */}
                    <div className="space-y-4 mb-10">
                        {(article.comments || []).length > 0 ? (
                            (article.comments || []).map((comm: any, i: number) => (
                                <div key={comm.id || i} className="bg-slate-50 p-5 rounded-2xl border border-slate-100/80 space-y-2 shadow-2xs hover:shadow-sm transition-shadow duration-200">
                                    <div className="flex items-center justify-between text-xs text-slate-400 border-b border-slate-200/60 pb-2">
                                        <span className="font-bold text-slate-800 text-sm flex items-center space-x-1.5">
                                            <User className="w-4 h-4 text-slate-400" />
                                            <span>{comm.user_name}</span>
                                        </span>
                                        <span>{comm.created_at ? new Date(comm.created_at).toLocaleDateString(locale === 'hi' ? 'hi-IN' : 'en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'Just now'}</span>
                                    </div>
                                    <p className="text-slate-600 text-sm leading-relaxed pl-1">{comm.comment}</p>
                                </div>
                            ))
                        ) : (
                            <div className="text-center py-12 bg-slate-50/50 rounded-2xl border border-slate-100/60 space-y-3">
                                <MessageSquare className="w-10 h-10 text-slate-300 mx-auto" />
                                <p className="text-sm text-slate-500 font-medium">
                                    {locale === 'hi' ? 'कोई टिप्पणी नहीं। पहली टिप्पणी करने वाले बनें!' : 'No comments yet. Be the first to share your thoughts!'}
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Add Comment Form */}
                    <form onSubmit={handleCommentSubmit} className="bg-gradient-to-tr from-slate-50 via-white to-indigo-50/30 p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
                        <h3 className="font-bold text-slate-900 text-lg flex items-center space-x-2">
                            <span>{locale === 'hi' ? 'अपनी प्रतिक्रिया साझा करें' : 'Leave a Comment'}</span>
                        </h3>
                        {commentSuccess && (
                            <div className="p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-2xl text-sm font-medium animate-fade-in flex items-center space-x-2 shadow-2xs">
                                <CheckCircle2 className="w-5 h-5 text-teal-600 shrink-0" />
                                <span>{locale === 'hi' ? 'आपकी प्रतिक्रिया सफलतापूर्वक दर्ज कर ली गई है!' : 'Your comment has been posted successfully!'}</span>
                            </div>
                        )}
                        <div className="space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                    {locale === 'hi' ? 'आपका नाम' : 'Your Name'}
                                </label>
                                <input
                                    type="text"
                                    value={commentName}
                                    onChange={(e) => setCommentName(e.target.value)}
                                    placeholder={locale === 'hi' ? 'आपका नाम...' : 'Your name...'}
                                    required
                                    className="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-base placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all duration-200 shadow-2xs"
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                    {locale === 'hi' ? 'आपकी टिप्पणी' : 'Your Comment'}
                                </label>
                                <textarea
                                    value={commentText}
                                    onChange={(e) => setCommentText(e.target.value)}
                                    placeholder={locale === 'hi' ? 'इस लेख पर आपके विचार...' : 'Your thoughts on this article...'}
                                    rows={4}
                                    required
                                    className="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-base placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all duration-200 resize-none shadow-2xs"
                                />
                            </div>
                        </div>
                        <div className="flex justify-end pt-2">
                            <button
                                type="submit"
                                disabled={submittingComment}
                                className="bg-gradient-to-tr from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm tracking-wider uppercase disabled:opacity-50 flex items-center space-x-2 transform active:scale-98"
                            >
                                {submittingComment ? (
                                    <span>{locale === 'hi' ? 'दर्ज किया जा रहा है...' : 'Posting...'}</span>
                                ) : (
                                    <span>{locale === 'hi' ? 'टिप्पणी करें' : 'Post Comment'}</span>
                                )}
                            </button>
                        </div>
                    </form>
                </section>
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
