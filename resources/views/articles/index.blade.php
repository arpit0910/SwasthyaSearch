@extends('layouts.public')

@section('title', 'Health Articles & News - SwasthyaSearch')

@section('meta_title', 'Health Articles and Guides | SwasthyaSearch')
@section('meta_description', 'Read patient-friendly health articles and guides on symptoms, prevention, and healthcare access. This content is for general information and not a substitute for medical advice.')
@php
$hasActiveMobileFilters = !empty(array_filter((array) request('category', [])));
@endphp
@section('content')
<header class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white py-16 px-4 sm:px-6 lg:px-8 border-b border-slate-800 shadow-xl relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(20,184,166,0.15),transparent_50%)]"></div>
    <div class="max-w-7xl mx-auto text-center relative z-10">
        <span class="bg-teal-500/20 text-teal-300 border border-teal-500/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase inline-block mb-4 shadow-sm">Expert Medical Knowledge</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent py-2 leading-normal">Health Articles and Guides</h1>
        <p class="max-w-2xl mx-auto text-slate-300 text-base sm:text-lg leading-relaxed">Explore evidence-based medical articles, nutritional advice, and fitness tips authored by accredited doctors and healthcare experts.</p>
    </div>
</header>

<section class="lg:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 sm:-mt-8 relative z-20 w-full mb-6">
    <form action="{{ route('articles.index') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700/70 p-4 sm:p-5 backdrop-blur-xl">
        @csrf
        @foreach ((array) request('category', []) as $categoryVal)
            <input type="hidden" name="category[]" value="{{ $categoryVal }}">
        @endforeach
        <div class="relative">
            <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400 dark:text-slate-500"></i>
            <input type="text" name="search" placeholder="Search article title or topics..." value="{{ request('search', $filters['search'] ?? '') }}" class="h-12 w-full pl-10 pr-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            <button type="button" data-open-mobile-filters onclick="openMobileFilters()" aria-label="Open filters" class="lg:hidden absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg border flex items-center justify-center transition-all duration-200 {{ $hasActiveMobileFilters ? 'border-teal-600 bg-teal-600 text-white shadow-md shadow-teal-500/30' : 'border-teal-200 dark:border-teal-800 bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-300 hover:bg-teal-50 dark:hover:bg-teal-950/40' }}" title="Filters">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 sm:-mt-8 relative z-20 w-full mb-2 lg:mb-8">
    <div id="mobile-filter-sidebar" class="fixed inset-0 z-[120] hidden lg:hidden" aria-hidden="true">
        <div id="mobile-filter-backdrop" onclick="closeMobileFilters()" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 ease-out"></div>
        <div id="mobile-filter-drawer" class="absolute top-0 right-0 h-full w-full max-w-sm bg-white dark:bg-slate-900 shadow-2xl overflow-y-auto transform translate-x-full transition-transform duration-300 ease-out">
            <div class="sticky top-0 p-3 sm:p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Filters</h2>
                <button type="button" id="mobile-filter-close" onclick="closeMobileFilters()" aria-label="Close filters" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                </button>
            </div>
            <form action="{{ route('articles.index') }}" method="POST" class="p-3 sm:p-4 space-y-4" id="mobile-filter-form">
                @csrf
                <input type="hidden" name="search" value="{{ request('search', $filters['search'] ?? '') }}">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2 uppercase tracking-wider">Category</label>
                    @php
                        $selectedCats = is_array(request('category')) ? request('category') : (request('category') && request('category') !== 'All' ? [request('category')] : []);
                    @endphp
                    <select name="category[]" multiple data-placeholder="Select categories" class="h-12 w-full px-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-100 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ in_array($cat, $selectedCats) ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2.5 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="w-full h-11 px-4 rounded-xl border border-indigo-300 dark:border-indigo-700 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"><i data-lucide="filter" class="w-4 h-4"></i><span>Apply Filters</span></button>
                    <a href="{{ route('articles.index') }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"><i data-lucide="rotate-ccw" class="w-4 h-4"></i><span>Reset</span></a>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('articles.index') }}" method="POST" class="hidden lg:block bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700/70 ring-1 ring-slate-200/70 dark:ring-slate-700/50 p-5 sm:p-6 backdrop-blur-xl" id="filter-form">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
            <div class="relative lg:col-span-2">
                <i data-lucide="search" class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none w-4 h-4 text-slate-400 dark:text-slate-500"></i>
                <input type="text" name="search" placeholder="Search article title or topics..." value="{{ request('search', $filters['search'] ?? '') }}" class="h-12 w-full pl-10 pr-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium" />
            </div>
            <div>
                @php
                    $selectedCats = is_array(request('category')) ? request('category') : (request('category') && request('category') !== 'All' ? [request('category')] : []);
                @endphp
                <select name="category[]" multiple data-placeholder="Select categories" class="h-12 w-full px-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-100 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all duration-200 font-medium">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ in_array($cat, $selectedCats) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="submit" class="h-12 px-4 rounded-xl border border-teal-300 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"><i data-lucide="filter" class="w-4 h-4"></i><span>Apply</span></button>
                <a href="{{ route('articles.index') }}" class="h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-600 dark:text-slate-300 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-sm transition-all duration-200 flex items-center justify-center space-x-2 shadow-2xs"><i data-lucide="rotate-ccw" class="w-4 h-4"></i><span>Reset</span></a>
            </div>
        </div>
    </form>
</div>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 w-full pb-20">
    @if (count($articles) === 0)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 p-16 text-center shadow-sm max-w-2xl mx-auto">
            <div class="w-20 h-20 bg-teal-50 dark:bg-teal-950/40 rounded-full flex items-center justify-center mx-auto mb-6 text-teal-600 dark:text-teal-300 border border-teal-100 dark:border-teal-800 shadow-inner"><i data-lucide="book-open" class="w-10 h-10"></i></div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">No Articles Found</h3>
            <p class="text-slate-500 dark:text-slate-400 text-base mb-8 leading-relaxed">We could not find any health articles matching your selected filters. Please try modifying your search criteria.</p>
            <a href="{{ route('articles.index') }}" class="bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold px-8 py-3 rounded-xl shadow transition-all duration-200 text-sm inline-flex items-center space-x-2"><i data-lucide="rotate-ccw" class="w-4 h-4"></i><span>View All Articles</span></a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($articles as $article)
                @php
                    $a = is_array($article) ? (object) $article : $article;
                    $titleEn = is_array($a->title) ? $a->title['en'] : ($a->title['en'] ?? $a->title_en);
                    $titleHi = is_array($a->title) ? $a->title['hi'] : ($a->title['hi'] ?? $a->title_hi);
                    $title = $locale === 'hi' ? ($titleHi ?: $titleEn) : $titleEn;
                    $excerptEn = is_array($a->excerpt) ? $a->excerpt['en'] : ($a->excerpt['en'] ?? $a->excerpt_en);
                    $excerptHi = is_array($a->excerpt) ? $a->excerpt['hi'] : ($a->excerpt['hi'] ?? $a->excerpt_hi);
                    $excerpt = $locale === 'hi' ? ($excerptHi ?: $excerptEn) : $excerptEn;
                @endphp
                <article class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-700/70 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group hover:-translate-y-1">
                    <a href="{{ route('articles.show', $a->id) }}" class="absolute inset-0 z-10" aria-label="{{ $title }}"></a>
                    <div class="relative z-0 p-6 pb-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 dark:from-slate-800 dark:via-slate-900 dark:to-slate-800 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-extrabold text-teal-700 dark:text-teal-300 bg-teal-50 dark:bg-teal-950/40 border border-teal-100 dark:border-teal-800 px-3 py-1 rounded-full shadow-2xs uppercase tracking-wider">{{ $a->category }}</span>
                        <div class="flex items-center space-x-1.5 text-slate-500 dark:text-slate-400 text-xs font-semibold"><i data-lucide="message-square" class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-300"></i><span>{{ is_array($a->comments) ? count($a->comments) : ($a->comments ? $a->comments->count() : 0) }}</span></div>
                    </div>
                    <div class="relative z-0 p-6 flex-1 flex flex-col space-y-4 bg-white dark:bg-slate-900">
                        <h3 class="font-extrabold text-xl text-slate-900 dark:text-slate-100 leading-snug group-hover:text-teal-600 dark:group-hover:text-teal-300 transition-colors duration-200 line-clamp-2">{{ $title }}</h3>
                        <div class="flex items-center space-x-4 text-slate-500 dark:text-slate-400 text-xs font-medium pt-1 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center space-x-1.5"><i data-lucide="user" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"></i><span>{{ $a->author_name }}</span></div>
                            <div class="flex items-center space-x-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"></i><span>{{ \Carbon\Carbon::parse($a->created_at)->translatedFormat($locale === 'hi' ? 'd M Y' : 'M d, Y') }}</span></div>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed line-clamp-3 flex-1">{{ $excerpt }}</p>
                    </div>
                    <div class="relative z-0 p-6 pt-0 bg-white dark:bg-slate-900">
                        <div class="w-full bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-xs uppercase tracking-wider flex items-center justify-center space-x-2"><i data-lucide="book-open" class="w-4 h-4 text-teal-400 dark:text-teal-300"></i><span>Read Full Article</span></div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    @if ($articles->hasPages())
        <div class="mt-12">{{ $articles->links('pagination::tailwind') }}</div>
    @endif
</main>
@endsection

@push('scripts')
<script>
function enhanceMultiSelectDropdown(selectEl){if(!selectEl||selectEl.dataset.enhanced==='1')return;selectEl.classList.add('hidden');const wrapper=document.createElement('div');wrapper.className='relative multi-select-dropdown';const trigger=document.createElement('button');trigger.type='button';trigger.className='h-12 w-full px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-100 flex items-center justify-between';const placeholder=selectEl.dataset.placeholder||'Select options';trigger.innerHTML=`<span class="multi-select-label truncate text-left">${placeholder}</span><i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>`;const panel=document.createElement('div');panel.className='hidden absolute z-50 mt-2 w-full max-h-64 overflow-auto rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-lg p-2 space-y-1';Array.from(selectEl.options).forEach((opt,idx)=>{const row=document.createElement('button');row.type='button';row.dataset.index=String(idx);row.className=`w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${opt.selected?'bg-teal-100 dark:bg-teal-950/40 text-teal-800 dark:text-teal-300 font-semibold':'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700'}`;row.textContent=opt.textContent.trim();panel.appendChild(row);});selectEl.insertAdjacentElement('afterend',wrapper);wrapper.appendChild(trigger);wrapper.appendChild(panel);wrapper.insertAdjacentElement('afterend',selectEl);trigger.addEventListener('click',()=>{panel.classList.toggle('hidden');if(window.lucide)lucide.createIcons();});panel.querySelectorAll('button[data-index]').forEach((rowBtn)=>{rowBtn.addEventListener('click',()=>{const optionIndex=Number(rowBtn.dataset.index);if(selectEl.options[optionIndex]){const nextState=!selectEl.options[optionIndex].selected;selectEl.options[optionIndex].selected=nextState;rowBtn.className=`w-full text-left px-3 py-2 rounded-md cursor-pointer text-sm transition-colors ${nextState?'bg-teal-100 dark:bg-teal-950/40 text-teal-800 dark:text-teal-300 font-semibold':'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700'}`;selectEl.dispatchEvent(new Event('change',{bubbles:true}));}});});if(!document.body.dataset.multiSelectOutsideBound){document.addEventListener('click',(e)=>{document.querySelectorAll('.multi-select-dropdown').forEach((dd)=>{if(!dd.contains(e.target)){dd.querySelector('div.absolute')?.classList.add('hidden');}});});document.body.dataset.multiSelectOutsideBound='1';}selectEl.dataset.enhanced='1';if(window.lucide)lucide.createIcons();}
function renderMultiSelectBadges(selectEl){if(!selectEl)return;let badgeWrap=selectEl.parentElement.querySelector('.selected-badges');if(!badgeWrap){badgeWrap=document.createElement('div');badgeWrap.className='selected-badges mt-2 flex flex-wrap gap-1.5';selectEl.parentElement.appendChild(badgeWrap);}const selected=Array.from(selectEl.selectedOptions).filter(Boolean);if(selected.length===0){badgeWrap.innerHTML='';return;}badgeWrap.innerHTML=selected.map(opt=>`<button type="button" data-remove-value="${opt.value}" class="inline-flex items-center gap-1 rounded-full border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-950/40 px-2.5 py-1 text-[11px] font-semibold text-teal-700 dark:text-teal-300 hover:bg-teal-100 dark:hover:bg-teal-900/50">${opt.textContent.trim()} <span class="text-teal-900 dark:text-teal-200">x</span></button>`).join('');if(!badgeWrap.dataset.removeBound){badgeWrap.addEventListener('click',(e)=>{const btn=e.target.closest('button[data-remove-value]');if(!btn)return;const val=btn.getAttribute('data-remove-value');const option=Array.from(selectEl.options).find((o)=>o.value===val);if(!option)return;option.selected=false;selectEl.dispatchEvent(new Event('change',{bubbles:true}));});badgeWrap.dataset.removeBound='1';}}
function initMultiSelectBadges(scope=document){const selects=scope.querySelectorAll('select[multiple]');selects.forEach((selectEl)=>{enhanceMultiSelectDropdown(selectEl);renderMultiSelectBadges(selectEl);if(!selectEl.dataset.badgeBound){selectEl.addEventListener('change',()=>renderMultiSelectBadges(selectEl));selectEl.dataset.badgeBound='1';}});}
function openMobileFilters(){const sidebar=document.getElementById('mobile-filter-sidebar');const drawer=document.getElementById('mobile-filter-drawer');const backdrop=document.getElementById('mobile-filter-backdrop');if(!sidebar)return;sidebar.classList.remove('hidden');sidebar.setAttribute('aria-hidden','false');document.body.style.overflow='hidden';requestAnimationFrame(()=>{drawer?.classList.remove('translate-x-full');backdrop?.classList.remove('opacity-0');});initMultiSelectBadges(sidebar);}
function closeMobileFilters(){const sidebar=document.getElementById('mobile-filter-sidebar');const drawer=document.getElementById('mobile-filter-drawer');const backdrop=document.getElementById('mobile-filter-backdrop');if(!sidebar)return;drawer?.classList.add('translate-x-full');backdrop?.classList.add('opacity-0');sidebar.setAttribute('aria-hidden','true');setTimeout(()=>{sidebar.classList.add('hidden');},300);document.body.style.overflow='';}
document.addEventListener('DOMContentLoaded',function(){initMultiSelectBadges(document);document.querySelectorAll('[data-open-mobile-filters]').forEach((btn)=>btn.addEventListener('click',openMobileFilters));document.getElementById('mobile-filter-close')?.addEventListener('click',closeMobileFilters);document.getElementById('mobile-filter-backdrop')?.addEventListener('click',closeMobileFilters);document.addEventListener('keydown',(event)=>{if(event.key==='Escape')closeMobileFilters();});});
</script>
@endpush
