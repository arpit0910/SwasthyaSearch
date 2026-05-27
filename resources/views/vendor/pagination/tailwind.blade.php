@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        $pages = collect([1, 2, 3, $last - 1, $last])
            ->filter(fn ($p) => $p >= 1 && $p <= $last)
            ->push($current)
            ->unique()
            ->sort()
            ->values();
    @endphp

    <nav role="navigation" aria-label="Pagination Navigation" class="mt-8">
        <div class="hidden sm:flex items-center justify-between gap-4">
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Page <span class="font-bold text-slate-900 dark:text-white">{{ $current }}</span>
                of <span class="font-bold text-slate-900 dark:text-white">{{ $last }}</span>
            </p>

            <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-1.5 shadow-sm">
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 rounded-xl text-xs font-bold text-slate-400 cursor-not-allowed">Prev</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Prev</a>
                @endif

                @php $prevPage = null; @endphp
                @foreach ($pages as $page)
                    @if (!is_null($prevPage) && ($page - $prevPage) > 1)
                        <span class="px-2 py-2 text-xs font-bold text-slate-400">...</span>
                    @endif

                    @if ($page == $current)
                        <span aria-current="page" class="min-w-9 px-3 py-2 rounded-xl text-xs font-extrabold bg-gradient-to-tr from-teal-500 to-cyan-600 text-white text-center shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="min-w-9 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-center transition-colors">
                            {{ $page }}
                        </a>
                    @endif

                    @php $prevPage = $page; @endphp
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Next</a>
                @else
                    <span class="px-3 py-2 rounded-xl text-xs font-bold text-slate-400 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>

        <div class="sm:hidden flex items-center justify-between gap-2">
            @if ($paginator->onFirstPage())
                <span class="w-1/3 text-center px-3 py-2 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-1/3 text-center px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">Prev</a>
            @endif

            <span class="w-1/3 text-center text-xs font-bold text-slate-700 dark:text-slate-200">
                {{ $current }} / {{ $last }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-1/3 text-center px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">Next</a>
            @else
                <span class="w-1/3 text-center px-3 py-2 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 dark:bg-slate-800">Next</span>
            @endif
        </div>
    </nav>
@endif

