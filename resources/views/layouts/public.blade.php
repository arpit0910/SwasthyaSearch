<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'SwasthyaSearch'))</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Hind:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F8FAFC] font-sans antialiased text-[#2D3748] min-h-screen flex flex-col selection:bg-teal-500 selection:text-white relative overflow-x-hidden {{ session('locale', app()->getLocale()) === 'hi' ? 'lang-hi' : '' }}">
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-28 -left-24 w-80 h-80 rounded-full bg-teal-300/25 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 w-80 h-80 rounded-full bg-indigo-300/25 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 rounded-full bg-cyan-300/20 blur-3xl"></div>
    </div>
    @php
        $locale = session('locale', app()->getLocale());
        $chatbotCities = \App\Models\Hospital::where('is_verified', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
        $chatbotCityPills = $chatbotCities->take(18);
    @endphp

    <!-- Header Navbar -->
    <nav class="sticky top-0 z-50 bg-white/85 backdrop-blur-xl border-b border-slate-200/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center gap-4">
                <div class="flex items-center min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 group mr-2 sm:mr-6 shrink-0">
                        <div class="p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5">
                            <i data-lucide="heart-pulse" class="w-6 h-6 text-white animate-pulse"></i>
                        </div>
                        <span class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-slate-800 to-indigo-900 bg-clip-text text-transparent tracking-tight py-1 leading-normal">
                            Swasthya<span class="text-teal-600">Search</span>
                        </span>
                    </a>

                    <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                        <a href="{{ route('doctors.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('doctors.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors' }}
                        </a>
                        <a href="{{ route('hospitals.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('hospitals.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals' }}
                        </a>
                        <a href="{{ route('blood_banks.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('blood_banks.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}
                        </a>
                        <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('articles.*') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles' }}
                        </a>
                        <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('about') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'हमारे बारे में' : 'About Us' }}
                        </a>
                        <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            {{ $locale === 'hi' ? 'संपर्क करें' : 'Contact Us' }}
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60 shadow-inner">
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'en' ? 'bg-white text-indigo-900 shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">English</span><span class="sm:hidden font-bold">EN</span></span>
                            </button>
                        </form>
                        <form action="{{ route('switch.locale') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="locale" value="hi">
                            <button type="submit" class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 {{ $locale === 'hi' ? 'bg-white text-indigo-900 shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900' }}">
                                <i data-lucide="globe" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-teal-600"></i>
                                <span><span class="hidden sm:inline">हिंदी</span><span class="sm:hidden font-bold">HI</span></span>
                            </button>
                        </form>
                    </div>
                    <button type="button" onclick="toggleMobileMenu()"
                        id="mobile-menu-toggle-btn"
                        class="md:hidden p-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors"
                        aria-label="Open navigation menu">
                        <i data-lucide="menu" id="mobile-menu-icon" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-nav-menu" class="md:hidden hidden pb-3 pt-2 border-t border-slate-200/80">
                <div class="mobile-nav-list">
                    <a href="{{ route('doctors.index') }}" class="mobile-nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i data-lucide="stethoscope" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'डॉक्टर खोजें' : 'Doctors' }}</span>
                    </a>
                    <a href="{{ route('hospitals.index') }}" class="mobile-nav-item {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals' }}</span>
                    </a>
                    <a href="{{ route('blood_banks.index') }}" class="mobile-nav-item {{ request()->routeIs('blood_banks.*') ? 'active' : '' }}">
                        <i data-lucide="droplet" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'ब्लड बैंक' : 'Blood Banks' }}</span>
                    </a>
                    <a href="{{ route('articles.index') }}" class="mobile-nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                        <i data-lucide="book-open" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'स्वास्थ्य लेख' : 'Articles' }}</span>
                    </a>
                    <a href="{{ route('about') }}" class="mobile-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'हमारे बारे में' : 'About Us' }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="mobile-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>{{ $locale === 'hi' ? 'संपर्क करें' : 'Contact' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Floating Chatbot Widget -->
    <div class="fixed bottom-5 right-4 sm:bottom-6 sm:right-6 z-50" id="chatbot-container">
        <!-- Chat Button -->
        <button id="chatbot-toggle-btn" onclick="toggleChatbot()" class="chatbot-fab flex items-center gap-3 bg-gradient-to-tr from-teal-500 to-indigo-600 text-white px-6 py-3.5 rounded-full shadow-2xl hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300 transform group ring-1 ring-white/20">
            <div class="chatbot-fab-icon w-6 h-6 flex items-center justify-center shrink-0 animate-bounce group-hover:animate-none">
                <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
            </div>
            <span id="chatbot-fab-label" class="chatbot-fab-label font-bold text-base tracking-wide whitespace-nowrap leading-none pt-0.5">
                {{ $locale === 'hi' ? 'स्वास्थ्य AI से पूछें' : 'Ask Swasthya AI' }}
            </span>
        </button>

        <!-- Chat Window -->
        <div id="chatbot-window" class="hidden w-[94vw] sm:w-[430px] h-[76vh] max-h-[640px] min-h-[500px] bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden animate-in fade-in duration-300 chatbot-window">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 text-white p-4 flex justify-between items-center shadow-md">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30">
                        <i data-lucide="bot" class="w-6 h-6 text-teal-400"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg leading-tight">Swasthya AI Assistant</h3>
                        <p class="text-xs text-teal-300 flex items-center space-x-1 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $locale === 'hi' ? 'लक्षण से डॉक्टर खोजें' : 'Symptom-to-Doctor AI' }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <select id="chatbot-language" class="text-xs bg-white text-slate-900 border border-white/40 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-teal-400/60">
                        <option value="en" {{ $locale === 'en' ? 'selected' : '' }}>EN</option>
                        <option value="hi" {{ $locale === 'hi' ? 'selected' : '' }}>HI</option>
                    </select>
                    <button type="button" onclick="toggleSpeakEnabled()" id="chatbot-speak-toggle" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200" title="Speak replies">
                        <i data-lucide="volume-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <button onclick="toggleChatbot()" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="px-3 pb-2 bg-white border-t border-slate-200/80">
                <div class="flex flex-nowrap items-center justify-between gap-3 mb-2 min-h-[22px]">
                    <label class="block text-[11px] font-semibold text-slate-500 leading-none whitespace-nowrap">{{ $locale === 'hi' ? 'शहर चुनें' : 'Select your city' }}</label>
                    <button type="button" id="chatbot-change-city-btn" onclick="enableCitySelection()" class="hidden shrink-0 text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap leading-none">
                        {{ $locale === 'hi' ? 'शहर बदलें' : 'Change city' }}
                    </button>
                </div>
                <div id="chatbot-city-selected-card" class="hidden items-center rounded-full border border-teal-200 bg-teal-50/70 px-3 py-1.5 w-fit max-w-full">
                    <div class="flex items-center gap-2 text-sm text-teal-900">
                        <i data-lucide="map-pin" class="w-4 h-4 text-teal-700"></i>
                        <span id="chatbot-selected-city-label" class="font-semibold truncate"></span>
                    </div>
                </div>
                <div id="chatbot-city-pill-wrap" class="flex flex-wrap gap-1.5 max-h-20 overflow-y-auto pr-1">
                    @foreach($chatbotCityPills as $city)
                        <button type="button" class="chatbot-city-pill" data-city="{{ $city }}" onclick="selectChatbotCity('{{ addslashes($city) }}')">
                            {{ $city }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Messages Body -->
            <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gradient-to-b from-slate-50/60 to-white">
                <!-- Initial Bot Message -->
                <div class="flex justify-start">
                    <div class="flex space-x-2 max-w-[85%] flex-row">
                        <div class="w-7 h-7 rounded-full bg-teal-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="bot" class="w-4 h-4"></i>
                        </div>
                        <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed bg-white text-slate-800 border border-slate-200/60 rounded-tl-none">
                            {{ $locale === 'hi' ? 'मैं आपकी मदद के लिए तैयार हूँ। कृपया पहले शहर चुनें, उसके बाद मैं आगे मार्गदर्शन करूँगा।' : 'I am ready to help. Please select your city first, then I will guide you further.' }}
                        </div>
                    </div>
                </div>

                <div class="hidden" id="chatbot-quick-prompts-wrap">
                    <div class="ml-9 max-w-[85%]">
                        <div class="mb-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-3 py-2">
                            <p class="text-[11px] font-bold text-indigo-900">
                                {{ $locale === 'hi' ? 'त्वरित रोग/लक्षण विकल्प' : 'Quick Disease/Symptom Options' }}
                            </p>
                            <p class="text-[10px] text-indigo-700 mt-0.5">
                                {{ $locale === 'hi' ? 'नीचे किसी विकल्प पर टैप करें, मैं तुरंत जवाब दूँगा।' : 'Tap any option below and I will reply instantly.' }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-1.5" id="chatbot-quick-prompts">
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'छाती में दर्द' : 'Chest pain' }}">{{ $locale === 'hi' ? 'छाती में दर्द' : 'Chest pain' }}</button>
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'बुखार और खांसी' : 'Fever and cough' }}">{{ $locale === 'hi' ? 'बुखार और खांसी' : 'Fever and cough' }}</button>
                            <button type="button" onclick="useQuickPrompt(this)" class="chatbot-chip" data-message="{{ $locale === 'hi' ? 'त्वचा एलर्जी' : 'Skin allergy' }}">{{ $locale === 'hi' ? 'त्वचा एलर्जी' : 'Skin allergy' }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="chatbot-loading" class="hidden px-4 py-2.5 flex space-x-2.5 items-center text-slate-500 text-sm bg-slate-50/80 border-y border-slate-100">
                <div class="typing-dots" aria-hidden="true">
                    <span></span><span></span><span></span>
                </div>
                <span>{{ $locale === 'hi' ? 'स्वास्थ्य AI सोच रहा है...' : 'Swasthya AI is thinking...' }}</span>
            </div>
            <!-- Input Footer -->
            <form id="chatbot-form" onsubmit="handleChatbotSubmit(event)" class="p-3 bg-white border-t border-slate-200/80 flex items-center space-x-2 shadow-lg">
                <input type="text" id="chatbot-input" placeholder="{{ $locale === 'hi' ? 'लक्षण या डॉक्टर का नाम लिखें...' : 'Type a symptom or doctor name...' }}" class="flex-1 bg-slate-100 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200">
                <button type="button" id="chatbot-voice-btn" onclick="toggleVoiceTyping()" class="bg-teal-600 hover:bg-teal-500 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95" title="{{ $locale === 'hi' ? 'वॉइस टाइपिंग चालू/बंद करें' : 'Start/Stop voice typing' }}">
                    <i data-lucide="mic" class="w-5 h-5"></i>
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white border-t border-slate-800 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-xl shadow-md">
                    <i data-lucide="heart-pulse" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">
                    Swasthya<span class="text-teal-400">Search</span>
                </span>
            </div>

            <p class="text-sm text-slate-400 text-center md:text-left">
                {{ $locale === 'hi' ? '© 2026 स्वास्थ्य सर्च। मरीजों के लिए पूर्णतः निःशुल्क और विज्ञापन-मुक्त स्वास्थ्य निर्देशिका।' : '© 2026 SwasthyaSearch. 100% free, ad-free healthcare directory connecting patients directly to providers.' }}
            </p>

            <div class="flex space-x-6 text-sm text-slate-400">
                <a href="{{ route('privacy.policy') }}" class="hover:text-white transition-colors">{{ $locale === 'hi' ? 'गोपनीयता नीति' : 'Privacy Policy' }}</a>
                <a href="{{ route('terms.service') }}" class="hover:text-white transition-colors">{{ $locale === 'hi' ? 'सेवा की शर्तें' : 'Terms of Service' }}</a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Chatbot Logic
        let chatbotOpen = false;
        let chatbotSessionToken = null;
        const currentLocale = "{{ $locale }}";
        let chatbotLocale = currentLocale === 'hi' ? 'hi' : 'en';
        let chatbotCity = '';
        let isCityLocked = false;
        let speechRecognition = null;
        let isVoiceTyping = false;
        let speakEnabled = false;
        const CHATBOT_CITY_STORAGE_KEY = 'swasthyasearch_chatbot_city';

        function collapseMobileFab() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;
            if (window.innerWidth < 640) {
                btn.classList.add('fab-collapsed');
            } else {
                btn.classList.remove('fab-collapsed');
            }
        }

        function setupFabHintCycle() {
            const btn = document.getElementById('chatbot-toggle-btn');
            if (!btn) return;
            if (window.innerWidth >= 640) return;

            btn.classList.remove('fab-collapsed');
            setTimeout(() => {
                if (!chatbotOpen) collapseMobileFab();
            }, 2800);
        }
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-nav-menu');
            if (!menu) return;
            menu.classList.toggle('hidden');
            updateMobileMenuIcon();
        }
        function closeMobileMenu() {
            const menu = document.getElementById('mobile-nav-menu');
            if (!menu) return;
            menu.classList.add('hidden');
            updateMobileMenuIcon();
        }
        function updateMobileMenuIcon() {
            const menu = document.getElementById('mobile-nav-menu');
            const icon = document.getElementById('mobile-menu-icon');
            const btn = document.getElementById('mobile-menu-toggle-btn');
            if (!menu || !icon || !btn) return;
            const isOpen = !menu.classList.contains('hidden');
            icon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            btn.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            lucide.createIcons();
        }
        function selectChatbotCity(city) {
            const previousCity = chatbotCity;
            chatbotCity = (city || '').trim();
            if (!chatbotCity) return;
            localStorage.setItem(CHATBOT_CITY_STORAGE_KEY, chatbotCity);
            isCityLocked = true;
            refreshChatbotCityUI();
            if (chatbotCity !== previousCity) {
                appendMessage(
                    'bot',
                    chatbotLocale === 'hi'
                        ? `बहुत बढ़िया, आपने ${chatbotCity} चुना है। अब लक्षण लिखें या नीचे दिए गए विकल्प चुनें।`
                        : `Great, you've selected ${chatbotCity}. Now type your symptoms or use the quick options below.`
                );
            }
        }
        function enableCitySelection() {
            isCityLocked = false;
            refreshChatbotCityUI();
        }
        function refreshChatbotCityUI() {
            const selectedCard = document.getElementById('chatbot-city-selected-card');
            const selectedLabel = document.getElementById('chatbot-selected-city-label');
            const changeBtn = document.getElementById('chatbot-change-city-btn');
            const pillWrap = document.getElementById('chatbot-city-pill-wrap');
            const quickPromptsWrap = document.getElementById('chatbot-quick-prompts-wrap');
            const input = document.getElementById('chatbot-input');
            const sendBtn = document.querySelector('#chatbot-form button[type="submit"]');
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            if (!selectedCard || !selectedLabel || !changeBtn || !pillWrap || !quickPromptsWrap) return;

            Array.from(pillWrap.querySelectorAll('.chatbot-city-pill')).forEach(btn => {
                const city = (btn.dataset.city || '').trim();
                btn.classList.toggle('active', city === chatbotCity);
            });

            if (isCityLocked && chatbotCity) {
                selectedLabel.textContent = chatbotCity;
                selectedCard.classList.remove('hidden');
                selectedCard.classList.add('flex');
                pillWrap.classList.add('hidden');
                changeBtn.classList.remove('hidden');
                quickPromptsWrap.classList.remove('hidden');
                if (input) {
                    input.disabled = false;
                    input.placeholder = chatbotLocale === 'hi' ? 'लक्षण या डॉक्टर का नाम लिखें...' : 'Type a symptom or doctor name...';
                }
                if (sendBtn) sendBtn.disabled = false;
                if (voiceBtn) voiceBtn.disabled = false;
                scrollToChatBottom();
            } else {
                selectedCard.classList.add('hidden');
                selectedCard.classList.remove('flex');
                pillWrap.classList.remove('hidden');
                changeBtn.classList.add('hidden');
                quickPromptsWrap.classList.add('hidden');
                if (input) {
                    input.disabled = true;
                    input.placeholder = chatbotLocale === 'hi' ? 'पहले शहर चुनें...' : 'Select city first...';
                }
                if (sendBtn) sendBtn.disabled = true;
                if (voiceBtn) voiceBtn.disabled = true;
            }
        }
        function initializeChatbotCity() {
            const savedCity = (localStorage.getItem(CHATBOT_CITY_STORAGE_KEY) || '').trim();
            if (savedCity) {
                chatbotCity = savedCity;
                isCityLocked = true;
            }
            refreshChatbotCityUI();
        }

        function toggleChatbot() {
            chatbotOpen = !chatbotOpen;
            const btn = document.getElementById('chatbot-toggle-btn');
            const win = document.getElementById('chatbot-window');
            if (chatbotOpen) {
                btn.classList.add('hidden');
                win.classList.remove('hidden');
                document.getElementById('chatbot-input').focus();
                scrollToChatBottom();
            } else {
                btn.classList.remove('hidden');
                win.classList.add('hidden');
                setupFabHintCycle();
            }
        }
        async function useQuickPrompt(btn) {
            const msg = btn?.dataset?.message || '';
            if (!msg) return;
            await submitChatbotMessage(msg);
        }

        function scrollToChatBottom() {
            const messagesDiv = document.getElementById('chatbot-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function getLocalizedText(field, fallback = '') {
            if (!field) return fallback;
            if (typeof field === 'string') return field;
            const lang = chatbotLocale === 'hi' ? 'hi' : 'en';
            return field[lang] || field.en || fallback;
        }
        function speakText(text) {
            if (!('speechSynthesis' in window) || !speakEnabled || !text) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(String(text).replace(/<[^>]*>/g, ''));
            utterance.lang = chatbotLocale === 'hi' ? 'hi-IN' : 'en-US';
            window.speechSynthesis.speak(utterance);
        }
        function toggleSpeakEnabled() {
            speakEnabled = !speakEnabled;
            const btn = document.getElementById('chatbot-speak-toggle');
            if (!btn) return;
            btn.classList.toggle('text-teal-300', speakEnabled);
        }
        function getSpeechLocale() {
            return chatbotLocale === 'hi' ? 'hi-IN' : 'en-US';
        }

        function updateVoiceButtonState() {
            const btn = document.getElementById('chatbot-voice-btn');
            if (!btn) return;

            if (isVoiceTyping) {
                btn.classList.remove('bg-teal-600', 'hover:bg-teal-500');
                btn.classList.add('bg-rose-600', 'hover:bg-rose-500');
                btn.title = chatbotLocale === 'hi' ? 'वॉइस टाइपिंग रोकें' : 'Stop voice typing';
            } else {
                btn.classList.remove('bg-rose-600', 'hover:bg-rose-500');
                btn.classList.add('bg-teal-600', 'hover:bg-teal-500');
                btn.title = chatbotLocale === 'hi' ? 'वॉइस टाइपिंग चालू करें' : 'Start voice typing';
            }
        }

        function toggleVoiceTyping() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (!SpeechRecognition) {
                appendMessage('bot', chatbotLocale === 'hi'
                    ? 'आपके ब्राउज़र में वॉइस टाइपिंग समर्थित नहीं है। कृपया Chrome/Edge का उपयोग करें।'
                    : 'Voice typing is not supported in your browser. Please use Chrome or Edge.');
                return;
            }

            if (!speechRecognition) {
                speechRecognition = new SpeechRecognition();
                speechRecognition.lang = getSpeechLocale();
                speechRecognition.interimResults = true;
                speechRecognition.continuous = false;

                speechRecognition.onresult = function(event) {
                    let transcript = '';
                    for (let i = event.resultIndex; i < event.results.length; i++) {
                        transcript += event.results[i][0].transcript;
                    }
                    document.getElementById('chatbot-input').value = transcript.trim();
                };

                speechRecognition.onerror = function() {
                    isVoiceTyping = false;
                    updateVoiceButtonState();
                };

                speechRecognition.onend = function() {
                    isVoiceTyping = false;
                    updateVoiceButtonState();
                };
            }

            if (isVoiceTyping) {
                speechRecognition.stop();
                isVoiceTyping = false;
                updateVoiceButtonState();
                return;
            }

            speechRecognition.lang = getSpeechLocale();
            speechRecognition.start();
            isVoiceTyping = true;
            updateVoiceButtonState();
        }

        async function submitChatbotMessage(message) {
            const languageInput = document.getElementById('chatbot-language');
            chatbotLocale = languageInput?.value === 'hi' ? 'hi' : 'en';

            if (!chatbotCity) {
                appendMessage('bot', chatbotLocale === 'hi' ? 'कृपया पहले शहर चुनें।' : 'Please select city first.');
                return;
            }

            const input = document.getElementById('chatbot-input');
            if (input) input.value = '';
            appendMessage('user', message);
            
            const loadingDiv = document.getElementById('chatbot-loading');
            loadingDiv.classList.remove('hidden');
            scrollToChatBottom();

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        session_token: chatbotSessionToken,
                        message: message,
                        city: chatbotCity,
                        locale: chatbotLocale,
                    }),
                });

                const data = await res.json();
                if (data.session_token) chatbotSessionToken = data.session_token;
                
                loadingDiv.classList.add('hidden');

                if (data.history) {
                    // Re-render history
                    const messagesDiv = document.getElementById('chatbot-messages');
                    messagesDiv.innerHTML = '';
                    data.history.forEach(msg => {
                        appendMessageObj(msg);
                    });
                    lucide.createIcons();
                    scrollToChatBottom();
                }
            } catch (error) {
                console.error('Chatbot error:', error);
                loadingDiv.classList.add('hidden');
                appendMessage('bot', chatbotLocale === 'hi' ? 'क्षमा करें, तकनीकी समस्या आ गई है।' : 'Sorry, a technical error occurred.');
            }
        }
        async function handleChatbotSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;
            await submitChatbotMessage(message);
        }

        function appendMessage(sender, text) {
            appendMessageObj({ sender, text });
            lucide.createIcons();
            scrollToChatBottom();
        }

        function appendMessageObj(msg) {
            const messagesDiv = document.getElementById('chatbot-messages');
            const isUser = msg.sender === 'user';
            
            const spoken = String(msg.text || '').replace(/'/g, '&#39;').replace(/\"/g, '&quot;');
            let html = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200">
                    <div class="flex space-x-2 max-w-[85%] ${isUser ? 'flex-row-reverse space-x-reverse' : 'flex-row'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${isUser ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'}">
                            <i data-lucide="${isUser ? 'user' : 'bot'}" class="w-4 h-4"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${isUser ? 'bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-tr-none border border-indigo-400/50' : 'bg-white/95 text-slate-800 border border-slate-200/70 rounded-tl-none backdrop-blur-sm'}">
                                ${msg.text}
                            </div>
                            ${!isUser ? `<button type="button" onclick="speakText('${spoken}')" class="inline-flex items-center gap-1 text-[11px] text-slate-500 hover:text-teal-600 text-left px-2 py-1 rounded-lg hover:bg-teal-50 transition-all">🔊 ${chatbotLocale === 'hi' ? 'सुनें' : 'Listen'}</button>` : ''}
            `;

            if (msg.department_info) {
                html += `
                    <div class="bg-teal-50 border border-teal-200 p-3.5 rounded-2xl text-teal-950 text-xs shadow-2xs mt-2 flex items-start space-x-2.5">
                        <i data-lucide="info" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5 animate-pulse"></i>
                        <div>
                            <span class="font-extrabold block text-teal-900 mb-0.5">${currentLocale === 'hi' ? 'अनुशंसित विभाग / परामर्श:' : 'Recommended Department / Action:'}</span>
                            <span class="leading-relaxed font-medium">${msg.department_info}</span>
                        </div>
                    </div>
                `;
            }

            if (msg.doctors && msg.doctors.length > 0) {
                html += `<div class="space-y-2 pt-2"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'विशेषज्ञ डॉक्टर' : 'Specialist Doctors'}</h5>`;
                msg.doctors.forEach(doc => {
                    const fullName = `Dr. ${doc.first_name} ${doc.last_name}`;
                    const deptName = doc.department ? getLocalizedText(doc.department.name) : '';
                    const emergencyPhone = doc.hospitals?.[0]?.emergency_phone || '';
                    const hospName = doc.hospitals?.[0] ? getLocalizedText(doc.hospitals[0].name) : '';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-sm text-indigo-950 flex items-center space-x-1">
                                    <span>${fullName}</span>
                                    ${doc.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline"></i>' : ''}
                                </h4>
                                <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-lg font-medium">
                                    ${doc.experience_years} ${currentLocale === 'hi' ? 'वर्ष अनुभव' : 'yrs exp'}
                                </span>
                            </div>
                            <div class="mt-2 space-y-1 text-xs text-slate-600">
                                <div class="flex items-center space-x-1">
                                    <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-teal-600 shrink-0"></i>
                                    <span class="font-medium text-slate-700">${deptName}</span>
                                </div>
                                ${hospName ? `
                                    <div class="flex items-start space-x-1 pt-0.5">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"></i>
                                        <span>${hospName}</span>
                                    </div>
                                ` : ''}
                            </div>
                            ${emergencyPhone ? `
                                <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                    <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                                        ${currentLocale === 'hi' ? 'कॉल करें' : 'Call Doctor'}
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
                html += `</div>`;
            }

            if (msg.hospitals && msg.hospitals.length > 0) {
                html += `<div class="space-y-2 pt-2 border-t border-slate-100"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'अस्पताल व क्लिनिक' : 'Hospitals & Clinics'}</h5>`;
                msg.hospitals.forEach(hosp => {
                    const hospName = getLocalizedText(hosp.name);
                    const emergencyPhone = hosp.emergency_phone || '';
                    const city = hosp.city || '';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-teal-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="font-bold text-sm text-teal-950 flex items-center space-x-1">
                                    <span>${hospName}</span>
                                    ${hosp.is_verified ? '<i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-teal-600 inline shrink-0"></i>' : ''}
                                </h4>
                                <span class="text-[10px] bg-teal-50 text-teal-700 px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider shrink-0">
                                    ${hosp.type || 'Hospital'}
                                </span>
                            </div>
                            <div class="mt-1.5 flex items-start space-x-1 text-xs text-slate-600">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5"></i>
                                <span class="line-clamp-2">${hosp.address || ''} ${city ? ', ' + city : ''}</span>
                            </div>
                            ${emergencyPhone ? `
                                <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                    <a href="tel:${emergencyPhone}" class="text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200 flex items-center space-x-1">
                                        <i data-lucide="phone-call" class="w-3 h-3"></i>
                                        <span>${currentLocale === 'hi' ? 'कॉल करें' : 'Call Emergency'}</span>
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    `;
                });
                if (msg.see_all_hospitals_url) {
                    html += `
                        <div class="pt-1">
                            <a href="${msg.see_all_hospitals_url}" class="inline-flex items-center space-x-1 text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                                <span>${currentLocale === 'hi' ? 'सभी अस्पताल देखें' : 'See all hospitals'}</span>
                            </a>
                        </div>
                    `;
                }
                html += `</div>`;
            }

            if (msg.doctors && msg.doctors.length > 0 && msg.see_all_doctors_url) {
                html += `
                    <div class="pt-1">
                        <a href="${msg.see_all_doctors_url}" class="inline-flex items-center space-x-1 text-xs bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200">
                            <span>${currentLocale === 'hi' ? 'सभी डॉक्टर देखें' : 'See all doctors'}</span>
                        </a>
                    </div>
                `;
            }

            if (msg.articles && msg.articles.length > 0) {
                html += `<div class="space-y-2 pt-2 border-t border-slate-100"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'स्वास्थ्य लेख' : 'Health Articles'}</h5>`;
                msg.articles.forEach(art => {
                    const artTitle = getLocalizedText(art.title);
                    const artExcerpt = getLocalizedText(art.excerpt) || (getLocalizedText(art.content) || '').substring(0, 80) + '...';

                    html += `
                        <div class="bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200 text-slate-800">
                            <h4 class="font-bold text-sm text-slate-900 line-clamp-1">${artTitle}</h4>
                            <p class="text-xs text-slate-600 mt-1 line-clamp-2">${artExcerpt}</p>
                            <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                <a href="/articles/${art.id}" target="_blank" class="text-xs bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200 flex items-center space-x-1">
                                    <span>${currentLocale === 'hi' ? 'पूरा लेख पढ़ें' : 'Read Article'}</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </div>
                        </div>
                    `;
                });
                html += `</div>`;
            }

            html += `
                        </div>
                    </div>
                </div>
            `;

            messagesDiv.insertAdjacentHTML('beforeend', html);
            if (!isUser) {
                speakText(msg.text);
            }
        }

        window.addEventListener('resize', collapseMobileFab);
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobile-nav-menu');
            const btn = document.getElementById('mobile-menu-toggle-btn');
            if (!menu || !btn || menu.classList.contains('hidden')) return;
            const target = e.target;
            if (!(target instanceof Node)) return;
            if (!menu.contains(target) && !btn.contains(target)) {
                closeMobileMenu();
            }
        });
        document.querySelectorAll('#mobile-nav-menu a').forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
        initializeChatbotCity();
        setupFabHintCycle();
        collapseMobileFab();
    </script>
    <style>
        :root {
            --brand-teal: #0ea5a6;
            --brand-indigo: #4f46e5;
        }
        .lang-hi,
        .lang-hi button,
        .lang-hi input,
        .lang-hi textarea,
        .lang-hi select {
            font-family: 'Noto Sans Devanagari', 'Hind', 'Outfit', sans-serif;
            letter-spacing: 0;
        }
        .chatbot-window {
            box-shadow: 0 24px 64px rgba(15, 23, 42, 0.24);
        }
        .chatbot-chip {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0.3rem 0.65rem;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .chatbot-chip:hover {
            background: #e0f2fe;
            border-color: #7dd3fc;
            color: #0f172a;
        }
        .chatbot-city-pill {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0.28rem 0.7rem;
            font-size: 11px;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .chatbot-city-pill:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #1e1b4b;
        }
        .chatbot-city-pill.active {
            background: linear-gradient(120deg, #14b8a6, #4f46e5);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }
        #chatbot-form input:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        #chatbot-form button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
        .typing-dots {
            display: inline-flex;
            gap: 4px;
        }
        .typing-dots span {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: var(--brand-teal);
            opacity: 0.35;
            animation: typingDot 1.1s infinite ease-in-out;
        }
        .typing-dots span:nth-child(2) {
            animation-delay: 0.15s;
        }
        .typing-dots span:nth-child(3) {
            animation-delay: 0.3s;
        }
        .mobile-nav-list {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.9rem;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }
        .mobile-nav-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            padding: 0.78rem 0.9rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .mobile-nav-item:last-child {
            border-bottom: 0;
        }
        .mobile-nav-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .mobile-nav-item.active {
            background: linear-gradient(90deg, #ecfeff, #eef2ff);
            color: #0f766e;
        }
        .mobile-nav-item.active i {
            color: #0d9488;
        }
        @keyframes typingDot {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.35; }
            40% { transform: translateY(-4px); opacity: 1; }
        }
        #chatbot-messages::-webkit-scrollbar {
            width: 8px;
        }
        #chatbot-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        @media (max-width: 639px) {
            .chatbot-fab.fab-collapsed {
                width: 56px;
                height: 56px;
                padding: 0;
                border-radius: 9999px;
                justify-content: center;
            }
            .chatbot-fab.fab-collapsed .chatbot-fab-label {
                display: none;
            }
            .chatbot-fab.fab-collapsed .chatbot-fab-icon {
                animation: none;
            }
            #chatbot-container {
                right: 0.75rem;
                bottom: calc(0.75rem + env(safe-area-inset-bottom));
            }
            #chatbot-window {
                width: calc(100vw - 1.2rem);
                height: min(78vh, 640px);
                min-height: 500px;
            }
        }
    </style>
    @stack('scripts')
</body>
</html>
