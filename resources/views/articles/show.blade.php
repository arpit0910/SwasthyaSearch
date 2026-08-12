@extends('layouts.public')

@php
    $titleEn = is_array($article->title) ? $article->title['en'] : ($article->title['en'] ?? $article->title_en);
    $titleHi = is_array($article->title) ? $article->title['hi'] : ($article->title['hi'] ?? $article->title_hi);
    $title = $locale === 'hi' ? ($titleHi ?: $titleEn) : $titleEn;
    $excerptEn = is_array($article->excerpt) ? $article->excerpt['en'] : ($article->excerpt['en'] ?? $article->excerpt_en);
    $excerptHi = is_array($article->excerpt) ? $article->excerpt['hi'] : ($article->excerpt['hi'] ?? $article->excerpt_hi);
    $excerpt = $locale === 'hi' ? ($excerptHi ?: $excerptEn) : $excerptEn;
    $contentEn = is_array($article->content) ? $article->content['en'] : ($article->content['en'] ?? $article->content_en);
    $contentHi = is_array($article->content) ? $article->content['hi'] : ($article->content['hi'] ?? $article->content_hi);
    $content = $locale === 'hi' ? ($contentHi ?: $contentEn) : $contentEn;
@endphp

@section('title', $title . ' - Arogio')
@section('meta_title', $title . ' | Arogio')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($excerpt ?: $content), 160, '...'))
@section('meta_keywords', \App\Support\Seo::keywords([$title, $article->category, 'health article', 'patient education', 'Arogio']))
@section('meta_author', $article->author_name ?: 'Arogio Team')
@section('article_published_time', optional($article->created_at)->toIso8601String())
@section('article_modified_time', optional($article->updated_at)->toIso8601String())
@section('og_type', 'article')
@section('canonical_url', route('articles.show', $article))
@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $title,
    'description' => \Illuminate\Support\Str::limit(strip_tags($excerpt ?: $content), 200, '...'),
    'author' => !empty($article->author_name) ? [
        '@type' => 'Person',
        'name' => $article->author_name,
    ] : null,
    'datePublished' => optional($article->created_at)->toIso8601String(),
    'dateModified' => optional($article->updated_at)->toIso8601String(),
    'mainEntityOfPage' => route('articles.show', $article->id),
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Arogio',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<!-- Header Banner -->
<header class="relative overflow-hidden py-12 bg-gradient-to-b from-indigo-900 via-indigo-950 to-slate-900 text-white">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#4A90E2_1px,transparent_1px)] [background-size:16px_16px]"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a
            href="{{ route('articles.index') }}"
            class="inline-flex items-center space-x-2 text-sm font-medium text-teal-400 hover:text-teal-300 transition-colors mb-6 group bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10"
        >
            <i data-lucide="arrow-left" class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform"></i>
            <span>{{ $locale === 'hi' ? 'लेख सूची पर वापस जाएं' : 'Back to Articles' }}</span>
        </a>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="text-xs font-bold text-teal-300 bg-teal-500/20 px-3.5 py-1.5 rounded-full border border-teal-500/30 shadow-inner">
                {{ $article->category }}
            </span>
            <div class="flex items-center space-x-1 text-xs text-slate-300 font-medium bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-teal-400"></i>
                <span>3 min read</span>
            </div>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-[1.3] text-white drop-shadow-md mb-6">
            {{ $title }}
        </h1>

        <div class="flex items-center space-x-4 pt-4 border-t border-white/10 text-sm text-slate-300">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-teal-500 to-indigo-500 flex items-center justify-center font-bold text-white shadow-md">
                {{ $article->author_name ? substr($article->author_name, 0, 1) : '<i data-lucide="user" class="w-5 h-5"></i>' }}
            </div>
            <div>
                <p class="font-semibold text-white">{{ $article->author_name }}</p>
                <p class="text-xs text-slate-400">
                    {{ \Carbon\Carbon::parse($article->created_at)->translatedFormat($locale === 'hi' ? 'd M Y' : 'M d, Y') }}
                </p>
            </div>
        </div>
    </div>
</header>

<!-- Main Article Content -->
<main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
    <article class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 shadow-sm p-6 sm:p-10 mb-12">
        <!-- Excerpt Box -->
        <div class="mb-8 p-6 bg-gradient-to-r from-teal-50/50 to-indigo-50/50 dark:from-teal-950/20 dark:to-indigo-950/20 rounded-2xl border-l-4 border-teal-500 border border-slate-100 dark:border-slate-700 shadow-2xs">
            <p class="font-medium text-slate-800 dark:text-slate-200 text-lg leading-relaxed italic">
                "{{ $excerpt }}"
            </p>
        </div>

        <!-- Main Content -->
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 text-base sm:text-lg leading-relaxed space-y-6 whitespace-pre-line">
            {{ $content }}
        </div>
        <div class="mt-8 rounded-2xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 px-4 py-3 text-sm text-amber-900 dark:text-amber-200">
            This article is for general information only and is not a substitute for professional medical advice, diagnosis, or treatment.
        </div>
    </article>

    <!-- Comments Section -->
    <section class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 shadow-sm p-6 sm:p-10">
        <div class="border-b border-slate-100 dark:border-slate-700 pb-6 mb-8 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 flex items-center space-x-2">
                <i data-lucide="message-square" class="w-6 h-6 text-teal-600"></i>
                <span>{{ $locale === 'hi' ? 'पाठक प्रतिक्रियाएं' : 'Reader Comments' }}</span>
            </h2>
            <span class="text-sm font-semibold bg-cyan-50 dark:bg-indigo-950/40 text-cyan-700 dark:text-indigo-300 px-3.5 py-1.5 rounded-full border border-cyan-100 dark:border-indigo-800" id="comments-count-badge">
                {{ is_array($article->comments) ? count($article->comments) : ($article->comments ? $article->comments->count() : 0) }} {{ $locale === 'hi' ? 'टिप्पणियां' : 'Comments' }}
            </span>
        </div>

        <!-- Existing Comments List -->
        <div class="space-y-4 mb-10" id="comments-list">
            @php
                $comments = is_array($article->comments) ? $article->comments : ($article->comments ?? []);
            @endphp
            @if (count($comments) > 0)
                @foreach ($comments as $comm)
                    @php $c = is_array($comm) ? (object) $comm : $comm; @endphp
                    <div class="bg-slate-50 dark:bg-slate-800/70 p-5 rounded-2xl border border-slate-100/80 dark:border-slate-700/70 space-y-2 shadow-2xs hover:shadow-sm transition-shadow duration-200">
                        <div class="flex items-center justify-between text-xs text-slate-400 dark:text-slate-500 border-b border-slate-200/60 dark:border-slate-700 pb-2">
                            <span class="font-bold text-slate-800 dark:text-slate-100 text-sm flex items-center space-x-1.5">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400 dark:text-slate-500"></i>
                                <span>{{ $c->user_name }}</span>
                            </span>
                            <span>{{ \Carbon\Carbon::parse($c->created_at)->translatedFormat($locale === 'hi' ? 'd M Y' : 'M d, Y') }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed pl-1">{{ $c->comment }}</p>
                    </div>
                @endforeach
            @else
                <div id="no-comments-placeholder" class="text-center py-12 bg-slate-50/50 dark:bg-slate-800/50 rounded-2xl border border-slate-100/60 dark:border-slate-700/60 space-y-3">
                    <i data-lucide="message-square" class="w-10 h-10 text-slate-300 dark:text-slate-500 mx-auto"></i>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                        {{ $locale === 'hi' ? 'कोई टिप्पणी नहीं। पहली टिप्पणी करने वाले बनें!' : 'No comments yet. Be the first to share your thoughts!' }}
                    </p>
                </div>
            @endif
        </div>

        <!-- Add Comment Form -->
        <form onsubmit="handleCommentSubmit(event)" class="bg-gradient-to-tr from-slate-50 via-white to-indigo-50/30 dark:from-slate-800 dark:via-slate-900 dark:to-indigo-950/20 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 shadow-sm space-y-5">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-lg flex items-center space-x-2">
                <span>{{ $locale === 'hi' ? 'अपनी प्रतिक्रिया साझा करें' : 'Leave a Comment' }}</span>
            </h3>

            <div id="comment-success" class="hidden p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-2xl text-sm font-medium animate-fade-in flex items-center space-x-2 shadow-2xs">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-teal-600 shrink-0"></i>
                <span>{{ $locale === 'hi' ? 'आपकी प्रतिक्रिया सफलतापूर्वक दर्ज कर ली गई है!' : 'Your comment has been posted successfully!' }}</span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        {{ $locale === 'hi' ? 'आपका नाम' : 'Your Name' }}
                    </label>
                    <input
                        type="text"
                        id="comment-name"
                        placeholder="{{ $locale === 'hi' ? 'आपका नाम...' : 'Your name...' }}"
                        required
                        class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-base text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all duration-200 shadow-2xs"
                    />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        {{ $locale === 'hi' ? 'आपकी टिप्पणी' : 'Your Comment' }}
                    </label>
                    <textarea
                        id="comment-text"
                        placeholder="{{ $locale === 'hi' ? 'इस लेख पर आपके विचार...' : 'Your thoughts on this article...' }}"
                        rows="4"
                        required
                        class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-base text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all duration-200 resize-none shadow-2xs"
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    id="comment-submit-btn"
                    class="bg-gradient-to-tr from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-bold px-8 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm tracking-wider uppercase disabled:opacity-50 flex items-center space-x-2 transform active:scale-98"
                >
                    <span>{{ $locale === 'hi' ? 'टिप्पणी करें' : 'Post Comment' }}</span>
                </button>
            </div>
        </form>
    </section>
</main>
@endsection

@push('scripts')
<script>
    let currentCommentsCount = {{ is_array($article->comments) ? count($article->comments) : ($article->comments ? $article->comments->count() : 0) }};

    async function handleCommentSubmit(e) {
        e.preventDefault();
        const nameInput = document.getElementById('comment-name');
        const textInput = document.getElementById('comment-text');
        const submitBtn = document.getElementById('comment-submit-btn');

        if (!nameInput.value.trim() || !textInput.value.trim()) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span>{{ $locale === 'hi' ? 'दर्ज किया जा रहा है...' : 'Posting...' }}</span>`;

        try {
            const res = await fetch('{{ route('api.articles.comments.store', $article->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    user_name: nameInput.value.trim(),
                    comment: textInput.value.trim(),
                }),
            });

            const data = await res.json();
            if (data.success) {
                const noComments = document.getElementById('no-comments-placeholder');
                if (noComments) noComments.remove();

                const commentsList = document.getElementById('comments-list');
                const html = `
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100/80 space-y-2 shadow-2xs hover:shadow-sm transition-shadow duration-200 animate-in fade-in">
                        <div class="flex items-center justify-between text-xs text-slate-400 border-b border-slate-200/60 pb-2">
                            <span class="font-bold text-slate-800 text-sm flex items-center space-x-1.5">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                <span>${data.comment.user_name}</span>
                            </span>
                            <span>Just now</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed pl-1">${data.comment.comment}</p>
                    </div>
                `;

                commentsList.insertAdjacentHTML('beforeend', html);
                window.refreshLucideIcons();

                currentCommentsCount++;
                document.getElementById('comments-count-badge').innerText = `${currentCommentsCount} {{ $locale === 'hi' ? 'टिप्पणियां' : 'Comments' }}`;

                nameInput.value = '';
                textInput.value = '';

                const successDiv = document.getElementById('comment-success');
                successDiv.classList.remove('hidden');
                setTimeout(() => successDiv.classList.add('hidden'), 4000);
            }
        } catch (error) {
            console.error('Comment submit error:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>{{ $locale === 'hi' ? 'टिप्पणी करें' : 'Post Comment' }}</span>`;
        }
    }
</script>
@endpush

