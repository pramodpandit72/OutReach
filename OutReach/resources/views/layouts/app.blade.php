<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="OutReach - Social Media Based P2P Customer Outreach Platform. Grow your business through referral marketing and peer-to-peer sharing.">
    <title>@yield('title', 'OutReach - Social Referral Platform')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Lucide Icons CDN --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 min-h-screen transition-colors duration-300">

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 dark:bg-gray-950/80 border-b border-gray-200/50 dark:border-gray-800/50" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 group" id="nav-logo">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/25 group-hover:shadow-violet-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">OutReach</span>
                </a>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-all" id="nav-home">Home</a>
                    <a href="{{ route('campaigns') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-all" id="nav-campaigns">Campaigns</a>
                    <a href="{{ route('leaderboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-all" id="nav-leaderboard">Leaderboard</a>
                    <a href="{{ route('reviews') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-all" id="nav-reviews">Reviews</a>
                </div>

                {{-- Right Side --}}
                <div class="hidden md:flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center justify-center" id="dark-mode-toggle" aria-label="Toggle dark mode">
                        <span x-show="darkMode" class="flex items-center"><i data-lucide="sun" class="w-5 h-5"></i></span>
                        <span x-show="!darkMode" class="flex items-center"><i data-lucide="moon" class="w-5 h-5"></i></span>
                    </button>

                    @auth
                        <!-- Premium Interactive Profile Dropdown -->
                        <div class="relative flex items-center" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950 rounded-full transition-all group p-0.5" id="nav-profile-toggle" aria-expanded="false" :aria-expanded="open.toString()">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-violet-500/30 group-hover:ring-violet-500 transition-all">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-violet-500/20 group-hover:from-violet-600 group-hover:to-indigo-600 transition-all">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150" 
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                                 class="absolute right-0 mt-2 w-56 rounded-2xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border border-gray-150 dark:border-gray-800 shadow-2xl py-2.5 z-50 overflow-hidden" 
                                 style="display: none;">
                                
                                <!-- User Header -->
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/20">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                    <span class="inline-flex mt-2 items-center gap-1 px-2 py-0.5 rounded-full text-xxs font-medium bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-400 border border-violet-100 dark:border-violet-900/30">
                                        <i data-lucide="shield" class="w-3 h-3"></i>
                                        {{ Auth::user()->isBusiness() ? 'Business Account' : 'Customer Account' }}
                                    </span>
                                </div>
                                
                                <!-- Links -->
                                <div class="px-1.5 py-1.5 space-y-0.5">
                                    @if(Auth::user()->isBusiness())
                                        <a href="{{ route('business.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 rounded-xl hover:bg-violet-50/50 dark:hover:bg-violet-950/30 transition-all font-medium">
                                            <i data-lucide="layout-dashboard" class="w-4 h-4 opacity-70"></i>
                                            Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 rounded-xl hover:bg-violet-50/50 dark:hover:bg-violet-950/30 transition-all font-medium">
                                            <i data-lucide="layout-dashboard" class="w-4 h-4 opacity-70"></i>
                                            Dashboard
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 rounded-xl hover:bg-violet-50/50 dark:hover:bg-violet-950/30 transition-all font-medium">
                                        <i data-lucide="user-cog" class="w-4 h-4 opacity-70"></i>
                                        Edit Profile
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-100 dark:border-gray-800 my-1.5"></div>
                                
                                <!-- Logout -->
                                <div class="px-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center gap-2.5 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-all font-medium text-left">
                                            <i data-lucide="log-out" class="w-4 h-4 opacity-80"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/50 transition-all" id="nav-login">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all" id="nav-register">Get Started</a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 flex items-center justify-center" id="mobile-menu-toggle">
                    <span x-show="!mobileOpen" class="flex items-center"><i data-lucide="menu" class="w-6 h-6"></i></span>
                    <span x-show="mobileOpen" class="flex items-center"><i data-lucide="x" class="w-6 h-6"></i></span>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="md:hidden border-t border-gray-200/50 dark:border-gray-800/50 bg-white/95 dark:bg-gray-950/95 backdrop-blur-xl">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-950/50">Home</a>
                <a href="{{ route('campaigns') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-950/50">Campaigns</a>
                <a href="{{ route('leaderboard') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-950/50">Leaderboard</a>
                <a href="{{ route('reviews') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-950/50">Reviews</a>
                <div class="pt-2 border-t border-gray-200 dark:border-gray-800">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="flex items-center gap-2 w-full px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        <span x-show="darkMode" class="flex items-center"><i data-lucide="sun" class="w-4 h-4"></i></span>
                        <span x-show="!darkMode" class="flex items-center"><i data-lucide="moon" class="w-4 h-4"></i></span>
                        <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                    </button>
                </div>
                @auth
                    @if(Auth::user()->isBusiness())
                        <a href="{{ route('business.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/50">Dashboard</a>
                    @else
                        <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-950/50">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-violet-50 dark:hover:bg-violet-950/50">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 text-center">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed top-20 right-4 z-50 max-w-sm">
            <div class="bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed top-20 right-4 z-50 max-w-sm">
            <div class="bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 shrink-0"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">OutReach</span>
                    </a>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">Enhancing customer outreach through social media and peer-to-peer networking. Grow your business with the power of referral marketing.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Platform</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('campaigns') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Campaigns</a></li>
                        <li><a href="{{ route('leaderboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Leaderboard</a></li>
                        <li><a href="{{ route('reviews') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Reviews</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                {{-- Get Started / Account --}}
                <div>
                    @guest
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Get Started</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('register') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Create Account</a></li>
                            <li><a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Sign In</a></li>
                        </ul>
                    @else
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Account</h4>
                        <ul class="space-y-2">
                            @if(Auth::user()->isBusiness())
                                <li><a href="{{ route('business.dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('customer.dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Dashboard</a></li>
                            @endif
                            <li><a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 transition-colors">Edit Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors bg-transparent border-0 p-0 cursor-pointer">Logout</button>
                                </form>
                            </li>
                        </ul>
                    @endguest
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} OutReach. All rights reserved.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Social Media Based P2P Customer Outreach Platform</p>
            </div>
        </div>
    </footer>

    <!-- Floating Interactive Bot Widget -->
    <div x-data="{ 
        open: false,
        messages: [],
        inputValue: '',
        isLoading: false,
        
        init() {
            // Add initial welcome message
            this.messages.push({
                sender: 'bot',
                text: '🚀 *Welcome to OutReach Assistant!* 🚀\n\nI am your automated peer-to-peer customer referral assistant. Ask me anything about referral marketing, check your stats, or get customized advice powered by Gemini AI.\n\nType `/start` or click a quick option below to begin!',
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
        },
        
        async sendMessage(textToSend = null) {
            const query = (textToSend !== null ? textToSend : this.inputValue).trim();
            if (!query) return;
            
            // Add user message to history
            this.messages.push({
                sender: 'user',
                text: query,
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            
            if (textToSend === null) {
                this.inputValue = '';
            }
            
            this.isLoading = true;
            this.scrollToBottom();
            
            try {
                const response = await fetch('{{ route('bot.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: query })
                });
                
                const data = await response.json();
                
                if (data.status === 'success' || data.reply) {
                    this.messages.push({
                        sender: 'bot',
                        text: data.reply,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } else {
                    this.messages.push({
                        sender: 'bot',
                        text: '⚠️ Sorry, I encountered an issue processing that. Please try again.',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                }
            } catch (error) {
                console.error('Chat error:', error);
                this.messages.push({
                    sender: 'bot',
                    text: '⚠️ Network error. Please check your connection and try again.',
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            }
        },
        
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
        
        formatMarkdown(text) {
            if (!text) return '';
            let escaped = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Bold **text** or *text*
            escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            escaped = escaped.replace(/\*(.*?)\*/g, '<strong>$1</strong>');

            // Italics _text_
            escaped = escaped.replace(/_(.*?)_/g, '<em>$1</em>');

            // Code `text`
            escaped = escaped.replace(/`(.*?)`/g, '<code class=\'px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700/50 rounded-md font-mono text-xs text-violet-600 dark:text-violet-400\'>$1</code>');

            // Newlines
            escaped = escaped.replace(/\n/g, '<br>');

            return escaped;
        }
    }" 
    class="fixed bottom-6 right-6 z-50 w-10 h-10">
        <!-- Popup Card -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="absolute bottom-14 right-0 w-[calc(100vw-3rem)] sm:w-96 h-[500px] max-h-[calc(100vh-6rem)] bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border border-gray-200/80 dark:border-gray-800/80 rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             style="display: none;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 p-4 text-white flex items-center justify-between shrink-0 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm relative">
                        <i data-lucide="bot" class="w-6 h-6 animate-pulse"></i>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-violet-600 rounded-full" title="OutReach Bot is online and active in real-time"></span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">OutReach Bot</h4>
                        <p class="text-[10px] text-violet-100 flex items-center gap-1">
                            AI growth consultant online
                        </p>
                    </div>
                </div>
                <button @click="open = false" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Chat Container -->
            <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50 dark:bg-gray-950/20 scroll-smooth">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender === 'user' 
                            ? 'bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-2xl rounded-tr-none p-3.5 text-xs max-w-[85%] shadow-md shadow-violet-500/10' 
                            : 'bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 rounded-2xl rounded-tl-none p-3.5 text-xs max-w-[85%] border border-gray-150 dark:border-gray-800/80 shadow-sm'"
                        >
                            <div class="prose dark:prose-invert max-w-none text-xs break-words" x-html="formatMarkdown(msg.text)"></div>
                            <span :class="msg.sender === 'user' ? 'text-violet-200 text-[9px] mt-1 block text-right' : 'text-gray-400 dark:text-gray-500 text-[9px] mt-1 block'" x-text="msg.time"></span>
                        </div>
                    </div>
                </template>
                
                <!-- Typing Indicator -->
                <div x-show="isLoading" class="flex justify-start">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl rounded-tl-none p-3.5 max-w-[70px] border border-gray-150 dark:border-gray-800/80 shadow-sm flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-violet-600 dark:bg-violet-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-violet-600 dark:bg-violet-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 bg-violet-600 dark:bg-violet-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                </div>
            </div>

            <!-- Command Quick Chips -->
            <div class="px-3 py-2 bg-gray-50/50 dark:bg-gray-950/20 border-t border-gray-100 dark:border-gray-800 flex items-center gap-2 overflow-x-auto shrink-0 select-none scrollbar-none" style="scrollbar-width: none; -ms-overflow-style: none;">
                <button @click="sendMessage('/stats')" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-500 rounded-full text-xxs font-semibold text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition-all shadow-sm">
                    <i data-lucide="bar-chart-3" class="w-3 h-3 text-violet-500"></i>
                    /stats
                </button>
                <button @click="sendMessage('/link')" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-500 rounded-full text-xxs font-semibold text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition-all shadow-sm">
                    <i data-lucide="link" class="w-3 h-3 text-emerald-500"></i>
                    /link
                </button>
                <button @click="sendMessage('/start')" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 hover:border-violet-500 dark:hover:border-violet-500 rounded-full text-xxs font-semibold text-gray-700 dark:text-gray-300 hover:text-violet-600 dark:hover:text-violet-400 transition-all shadow-sm">
                    <i data-lucide="help-circle" class="w-3 h-3 text-indigo-500"></i>
                    /start
                </button>
                <a href="https://t.me/{{ env('TELEGRAM_BOT_USERNAME', 'collabstack_bot') }}" target="_blank" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 dark:bg-sky-950/40 border border-sky-100 dark:border-sky-900/30 hover:border-sky-500 rounded-full text-xxs font-semibold text-sky-700 dark:text-sky-400 transition-all shadow-sm">
                    <i data-lucide="send" class="w-3 h-3"></i>
                    Telegram Bot
                </a>
            </div>
            
            <!-- Footer Input -->
            <form @submit.prevent="sendMessage()" class="p-3 bg-white dark:bg-gray-900 border-t border-gray-150 dark:border-gray-800/80 flex items-center gap-2 shrink-0">
                <input 
                    type="text" 
                    x-model="inputValue" 
                    placeholder="Type a message..." 
                    class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-500 dark:text-white"
                    :disabled="isLoading"
                >
                <button 
                    type="submit" 
                    class="w-8 h-8 rounded-xl bg-violet-600 hover:bg-violet-700 text-white flex items-center justify-center transition-all shrink-0 shadow-md shadow-violet-500/25 disabled:opacity-50"
                    :disabled="!inputValue.trim() || isLoading"
                >
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
        
        <!-- Floating Button -->
        <button @click="open = !open; if(open) { scrollToBottom(); }" class="w-full h-full rounded-full bg-gradient-to-tr from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all scale-100 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950 relative" aria-label="Toggle Bot Widget">
            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 border-2 border-white dark:border-gray-950 rounded-full animate-ping" title="AI Bot is online and ready to chat"></span>
            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 border-2 border-white dark:border-gray-950 rounded-full" title="AI Bot is online and ready to chat"></span>
            <i data-lucide="message-square" x-show="!open" class="w-4 h-4"></i>
            <i data-lucide="x" x-show="open" class="w-4 h-4" style="display: none;"></i>
        </button>
    </div>

    {{-- Re-initialize Lucide icons after Alpine updates --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
        document.addEventListener('alpine:initialized', () => { lucide.createIcons(); });
    </script>

    @yield('scripts')
</body>
</html>
