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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
<body class="bg-[#F8FAFC] font-sans antialiased text-[#2D3748] min-h-screen flex flex-col selection:bg-teal-500 selection:text-white">
    @php
        $locale = session('locale', app()->getLocale());
    @endphp

    <!-- Header Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition-all duration-300">
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
                            {{ $locale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals' }}
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

                <div class="flex items-center shrink-0">
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
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Floating Chatbot Widget -->
    <div class="fixed bottom-6 right-6 z-50" id="chatbot-container">
        <!-- Chat Button -->
        <button id="chatbot-toggle-btn" onclick="toggleChatbot()" class="flex items-center gap-3 bg-gradient-to-tr from-teal-500 to-indigo-600 text-white px-6 py-3.5 rounded-full shadow-2xl hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300 transform group">
            <div class="w-6 h-6 flex items-center justify-center shrink-0 animate-bounce group-hover:animate-none">
                <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
            </div>
            <span class="font-bold text-base tracking-wide whitespace-nowrap leading-none pt-0.5">
                {{ $locale === 'hi' ? 'स्वास्थ्या एआई से पूछें' : 'Ask Swasthya AI' }}
            </span>
        </button>

        <!-- Chat Window -->
        <div id="chatbot-window" class="hidden w-[90vw] sm:w-[420px] h-[550px] bg-white rounded-3xl shadow-2xl border border-slate-200/80 flex flex-col overflow-hidden animate-in fade-in duration-300">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-900 to-indigo-900 text-white p-4 flex justify-between items-center shadow-md">
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
                <button onclick="toggleChatbot()" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Messages Body -->
            <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50/50">
                <!-- Initial Bot Message -->
                <div class="flex justify-start">
                    <div class="flex space-x-2 max-w-[85%] flex-row">
                        <div class="w-7 h-7 rounded-full bg-teal-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="bot" class="w-4 h-4"></i>
                        </div>
                        <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed bg-white text-slate-800 border border-slate-200/60 rounded-tl-none">
                            {{ $locale === 'hi' ? 'नमस्ते! मैं स्वास्थ्या एआई हूँ। आप अपनी बीमारी के लक्षण (जैसे "पेट दर्द" या "बुखार") या डॉक्टर का नाम बता सकते हैं।' : 'Hello! I am Swasthya AI. You can tell me your symptoms (e.g. "stomach ache" or "fever") or a doctor\'s name, and I will find the right specialist for you.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="chatbot-loading" class="hidden px-4 py-2 flex space-x-2 items-center text-slate-400 text-sm italic bg-slate-50/50">
                <i data-lucide="bot" class="w-5 h-5 text-teal-500 animate-spin"></i>
                <span>{{ $locale === 'hi' ? 'स्वास्थ्या एआई सोच रहा है...' : 'Swasthya AI is thinking...' }}</span>
            </div>

            <!-- Input Footer -->
            <form id="chatbot-form" onsubmit="handleChatbotSubmit(event)" class="p-3 bg-white border-t border-slate-200/80 flex items-center space-x-2 shadow-lg">
                <input type="text" id="chatbot-input" placeholder="{{ $locale === 'hi' ? 'लक्षण या डॉक्टर का नाम लिखें...' : 'Type a symptom or doctor name...' }}" class="flex-1 bg-slate-100 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200">
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
                {{ $locale === 'hi' ? '© 2026 स्वास्थ्या सर्च। मरीजों के लिए पूर्णतः निःशुल्क और विज्ञापन-मुक्त स्वास्थ्य निर्देशिका।' : '© 2026 SwasthyaSearch. 100% free, ad-free healthcare directory connecting patients directly to providers.' }}
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
            }
        }

        function scrollToChatBottom() {
            const messagesDiv = document.getElementById('chatbot-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function getLocalizedText(field, fallback = '') {
            if (!field) return fallback;
            if (typeof field === 'string') return field;
            return field[currentLocale] || field.en || fallback;
        }

        async function handleChatbotSubmit(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            input.value = '';
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
                appendMessage('bot', currentLocale === 'hi' ? 'क्षमा करें, कोई तकनीकी समस्या आ गई है।' : 'Sorry, a technical error occurred.');
            }
        }

        function appendMessage(sender, text) {
            appendMessageObj({ sender, text });
            lucide.createIcons();
            scrollToChatBottom();
        }

        function appendMessageObj(msg) {
            const messagesDiv = document.getElementById('chatbot-messages');
            const isUser = msg.sender === 'user';
            
            let html = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-in fade-in duration-200">
                    <div class="flex space-x-2 max-w-[85%] ${isUser ? 'flex-row-reverse space-x-reverse' : 'flex-row'}">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${isUser ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'}">
                            <i data-lucide="${isUser ? 'user' : 'bot'}" class="w-4 h-4"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${isUser ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-slate-800 border border-slate-200/60 rounded-tl-none'}">
                                ${msg.text}
                            </div>
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
                html += `<div class="space-y-2 pt-2 border-t border-slate-100"><h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">${currentLocale === 'hi' ? 'अस्पताल व क्लीनिक' : 'Hospitals & Clinics'}</h5>`;
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
                html += `</div>`;
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
        }
    </script>
    @stack('scripts')
</body>
</html>
