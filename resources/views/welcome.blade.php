<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark bg-[#0B0F17] overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>CAPACIPRINT — Intelligent Print Routing</title>

    <!-- Fonts: Plus Jakarta Sans everywhere across the system -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" onerror="this.onerror=null; this.href='{{ asset('assets/fontsource/inter/index.css') }}';">

    <!-- Tailwind CSS (via Vite with CDN Fallback for safety) -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com" onerror="this.onerror=null; this.src='{{ asset('assets/js/tailwindcss.js') }}';"></script>
    
    <!-- Alpine.js for interactive state (CDN with Local Fallback) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js" onerror="this.onerror=null; this.src='{{ asset('assets/js/alpine-mask.min.js') }}';"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" onerror="this.onerror=null; this.src='{{ asset('assets/js/alpine.min.js') }}';"></script>

    <!-- FontAwesome for icons (CDN with Local Fallback) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onerror="this.onerror=null; this.href='{{ asset('assets/fontawesome/css/all.min.css') }}';">

    <style>
        /* Hide Microsoft Edge native password reveal icon to prevent duplicate icons */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50:  '#f2f5f8',
                            100: '#dde5ed',
                            200: '#bccadb',
                            300: '#92a9be',
                            400: '#6685a0',
                            500: '#4e6a84',
                            600: '#3d5068',
                            700: '#2e3d50',
                            800: '#1f2c3a',
                            900: '#141c26',
                            950: '#0b1016',
                        },
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        mono: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script>
        (function () {
            document.documentElement.classList.add('dark');
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        html, body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        .font-display { font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important; letter-spacing: -0.025em; }
        .font-sans { font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important; }
        .font-mono { font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important; font-variant-numeric: tabular-nums; }

        /* ── Brand color variables ── */
        :root {
            --brand-dark:   #151922;
            --brand-mid:    #3f4652;
            --brand-blue:   #2563EB;
            --brand-blue-d: #1D4ED8;
        }

        /* Mechanical UI transitions */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.45s cubic-bezier(0.2, 0, 0, 1) both; }

        /* Left panel: dark press-room slate */
        .login-panel-left {
            background: #101319;
        }
        .login-dot-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* Feature badges */
        .feature-badge {
            background: rgba(37,99,235,0.14);
            border: 1px solid rgba(59,130,246,0.28);
        }
        .input-field {
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .input-field:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.14);
            outline: none;
        }

        /* CTA button: Clean Modern Blue */
        .btn-primary {
            background: #2563EB;
            transition: background-color 0.15s;
        }
        .btn-primary:hover {
            background: #1D4ED8;
        }
        .btn-primary:active { background: #1E40AF; }
        .demo-btn {
            border: 1px solid #E2E8F0;
            transition: border-color 0.15s, background-color 0.15s;
        }
        .demo-btn:hover {
            border-color: #2563EB;
            background: rgba(37,99,235,0.05);
        }

        /* Sidebar uses brand-mid slate matching logo outer ring */
        aside, .mobile-sidebar {
            background-color: var(--brand-mid) !important;
        }
    </style>
</head>
<body class="h-full font-sans antialiased bg-white dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100" x-data="appState()" x-cloak>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 1. CAPACIPRINT PUBLIC LANDING PAGE                         -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div x-show="!isLoggedIn"
         class="min-h-screen bg-[#0B0F17] text-slate-100 flex flex-col justify-between selection:bg-blue-600 selection:text-white font-sans antialiased overflow-x-hidden w-full max-w-full">

        <!-- Clean Sticky Navigation Bar -->
        <header id="main-header" x-data="{ mobileNavOpen: false }" class="sticky top-0 z-50 bg-[#0B0F17]/95 backdrop-blur-md border-b border-slate-800/90 transition-shadow duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-4">

                {{-- Brand Identity --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-3 shrink-0 group">
                    <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT Logo" class="h-10 w-auto object-contain">
                    <div class="leading-tight">
                        <span class="font-bold text-lg sm:text-xl text-white tracking-tight block leading-none">
                            CAPACI<span class="text-blue-400">PRINT</span>
                        </span>
                        <span class="text-[11px] font-medium text-slate-400 block mt-0.5">
                            Multi-Branch Print Network
                        </span>
                    </div>
                </a>

                {{-- Desktop Navigation Menu --}}
                <nav class="hidden lg:flex items-center gap-1.5">
                    <a href="#hero" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">Overview</a>
                    <a href="#services" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">Services</a>
                    <a href="#decision-engine" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">How It Works</a>
                    <a href="#problem" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">Why Us</a>
                    <a href="#branches" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">Branches</a>
                    <a href="#tracking" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800/80 transition-colors">Track Order</a>
                </nav>

                {{-- Right Actions: Customer Sign In + Mobile Hamburger --}}
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors cursor-pointer whitespace-nowrap">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Customer Sign In</span>
                    </a>

                    <button type="button"
                            @click="mobileNavOpen = !mobileNavOpen"
                            aria-label="Toggle navigation menu"
                            class="lg:hidden h-10 w-10 rounded-xl text-slate-200 border border-slate-800 bg-slate-900 hover:bg-slate-800 flex items-center justify-center transition-colors focus:outline-none">
                        <i :class="mobileNavOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-base"></i>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown Drawer --}}
            <div x-show="mobileNavOpen"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 @click.away="mobileNavOpen = false"
                 class="lg:hidden border-t border-slate-800 bg-[#0B0F17] px-4 py-4 space-y-1 text-sm font-semibold text-slate-200"
                 x-cloak>
                <a @click="mobileNavOpen = false" href="#hero" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">Overview</a>
                <a @click="mobileNavOpen = false" href="#services" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">Services</a>
                <a @click="mobileNavOpen = false" href="#decision-engine" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">How It Works</a>
                <a @click="mobileNavOpen = false" href="#problem" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">Why Us</a>
                <a @click="mobileNavOpen = false" href="#branches" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">Branches</a>
                <a @click="mobileNavOpen = false" href="#tracking" class="block py-2.5 px-3 rounded-xl hover:bg-slate-800 hover:text-blue-400 transition-colors">Track Order</a>
                <div class="pt-3 border-t border-slate-800">
                    <a href="{{ route('customer.portal') }}" class="block text-center py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm">
                        Open Customer Portal &rarr;
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-full overflow-x-hidden">

            <!-- ── HERO SECTION: CLEAN MODERN SPLIT LAYOUT ── -->
            <section id="hero" x-data="heroVideoController()" class="py-14 sm:py-20 lg:py-24 bg-gradient-to-b from-slate-50/80 to-white dark:from-[#0B0F17] dark:to-[#0F172A]/60 border-b border-slate-200/70 dark:border-slate-800/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                        {{-- Left 6 Columns: Friendly Bold Copy + CTAs + Key Stats --}}
                        <div class="lg:col-span-6 space-y-6 animate-fade-in-up">
                            <div class="inline-flex items-center gap-2.5 pl-1.5 pr-3.5 py-1 rounded-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-600 text-white text-[11px] font-bold">
                                    <i class="fa-solid fa-print text-[11px]"></i>
                                    <span>3 Branches</span>
                                </span>
                                <span>Smart Multi-Branch Commercial Printing</span>
                            </div>

                            <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-slate-900 dark:text-white tracking-tight leading-[1.12]">
                                Commercial printing,
                                <span class="block text-blue-600 dark:text-blue-400 mt-1">always on schedule.</span>
                            </h1>

                            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-xl">
                                Submit your print job online, review your quote, and let our system automatically route your order to the branch with available machines and materials in stock.
                            </p>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-1">
                                <a href="#services" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm sm:text-base shadow-sm transition cursor-pointer">
                                    <span>Explore Print Services</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                                <a href="#tracking" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 font-semibold text-sm sm:text-base transition">
                                    <i class="fa-solid fa-magnifying-glass text-blue-600 dark:text-blue-400 text-xs"></i>
                                    <span>Track an Order</span>
                                </a>
                            </div>

                            {{-- Clean 3-Stat Card Row --}}
                            <div id="stats-banner" class="grid grid-cols-3 gap-3 sm:gap-4 pt-3">
                                <div class="p-3.5 sm:p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm">
                                    <div class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tabular-nums whitespace-nowrap">
                                        <span class="counter-item" data-target="3" data-suffix=" Branches">3 Branches</span>
                                    </div>
                                    <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 block">Active locations</span>
                                </div>
                                <div class="p-3.5 sm:p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm">
                                    <div class="text-xl sm:text-2xl font-extrabold text-blue-600 dark:text-blue-400 tabular-nums whitespace-nowrap">
                                        <span class="counter-item" data-target="5500" data-suffix="+">5,500+</span>
                                    </div>
                                    <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 block">Daily sq.ft output</span>
                                </div>
                                <div class="p-3.5 sm:p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm">
                                    <div class="text-xl sm:text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 tabular-nums whitespace-nowrap">
                                        <span class="counter-item" data-target="99" data-suffix="%">99%</span>
                                    </div>
                                    <span class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 block">On-time delivery</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right 6 Columns: Bright Clean Production Floor Video Showcase Card --}}
                        <div class="lg:col-span-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                            <div class="bg-white dark:bg-slate-900 p-3 sm:p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm">
                                <div class="rounded-xl aspect-[16/10] overflow-hidden relative bg-slate-900">
                                    <video id="hero-video-1"
                                           class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-700 ease-in-out"
                                           :class="currentFeed === 1 ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                                           autoplay muted playsinline preload="auto">
                                        <source src="{{ asset('videos/mstar.mp4') }}" type="video/mp4">
                                    </video>

                                    <video id="hero-video-2"
                                           class="absolute inset-0 w-full h-full object-cover object-center transition-opacity duration-700 ease-in-out"
                                           :class="currentFeed === 2 ? 'opacity-100 z-10' : 'opacity-0 z-0'"
                                           muted playsinline preload="auto">
                                        <source src="{{ asset('videos/mstarnet.mp4') }}" type="video/mp4">
                                    </video>
                                </div>

                                <div class="pt-3 px-1 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">Live from our production floor</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" @click="setFeed(1)"
                                                :class="currentFeed === 1 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3 py-1 rounded-lg border text-xs font-semibold transition-colors cursor-pointer">
                                            Press 1
                                        </button>
                                        <button type="button" @click="setFeed(2)"
                                                :class="currentFeed === 2 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:text-slate-900 dark:hover:text-white'"
                                                class="px-3 py-1 rounded-lg border text-xs font-semibold transition-colors cursor-pointer">
                                            Press 2
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ── PRINT SERVICES CATALOG ── -->
            <section id="services" class="py-20 lg:py-24 bg-slate-50/70 dark:bg-slate-900/40 border-b border-slate-200/70 dark:border-slate-800/80"
                     x-data="{
                        active: 0,
                        total: 4,
                        timer: null,
                        touchStartX: 0,
                        next() { this.active = (this.active + 1) % this.total; },
                        prev() { this.active = (this.active - 1 + this.total) % this.total; },
                        startAuto() { this.timer = setInterval(() => { this.next(); }, 6000); },
                        stopAuto() { if (this.timer) clearInterval(this.timer); }
                     }"
                     x-init="startAuto()">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center max-w-2xl mx-auto mb-10 reveal-on-scroll">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-2">Print Services</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            What would you like to print?
                        </h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400 text-base sm:text-lg leading-relaxed">
                            Browse our full catalog of commercial printing services for businesses, events, schools, and teams.
                        </p>
                    </div>

                    {{-- Category Tabs --}}
                    <div class="flex flex-wrap items-center justify-center gap-2.5 mb-8 reveal-on-scroll">
                        <button type="button" @click="active = 0; stopAuto()"
                                :class="active === 0 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400'"
                                class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors cursor-pointer">
                            Tarpaulin &amp; Offset
                        </button>
                        <button type="button" @click="active = 1; stopAuto()"
                                :class="active === 1 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400'"
                                class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors cursor-pointer">
                            Posters, Stickers &amp; IDs
                        </button>
                        <button type="button" @click="active = 2; stopAuto()"
                                :class="active === 2 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400'"
                                class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors cursor-pointer">
                            Business Cards &amp; Merch
                        </button>
                        <button type="button" @click="active = 3; stopAuto()"
                                :class="active === 3 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-blue-400'"
                                class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors cursor-pointer">
                            Standees &amp; Sintra Boards
                        </button>
                    </div>

                    {{-- Clean Centered Showcase Card --}}
                    <div class="relative max-w-2xl mx-auto reveal-on-scroll"
                         @mouseenter="stopAuto()"
                         @mouseleave="startAuto()">
                        <div class="bg-white dark:bg-slate-900 p-3 sm:p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm">
                            <div class="bg-white rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-700 relative select-none"
                                 @touchstart="touchStartX = $event.changedTouches[0].screenX"
                                 @touchend="
                                    if ($event.changedTouches[0].screenX < touchStartX - 40) next();
                                    if ($event.changedTouches[0].screenX > touchStartX + 40) prev();
                                 ">
                                <div class="flex transition-transform duration-500 ease-out"
                                     :style="`transform: translateX(-${active * 100}%);`">
                                    <div class="w-full shrink-0 bg-white">
                                        <img src="{{ asset('images/services/service-slide-1.jpg') }}" 
                                             alt="Commercial Print Services - Tarpaulin, Laser, T-Shirt, and Offset Printing" 
                                             class="w-full h-auto block select-none" draggable="false" loading="eager">
                                    </div>
                                    <div class="w-full shrink-0 bg-white">
                                        <img src="{{ asset('images/services/service-slide-2.jpg') }}" 
                                             alt="Commercial Print Services - Posters, Stickers, Company ID, and Risograph" 
                                             class="w-full h-auto block select-none" draggable="false" loading="lazy">
                                    </div>
                                    <div class="w-full shrink-0 bg-white">
                                        <img src="{{ asset('images/services/service-slide-3.jpg') }}" 
                                             alt="Commercial Print Services - Business Cards, Flyers, Lanyards, and Magic Mugs" 
                                             class="w-full h-auto block select-none" draggable="false" loading="lazy">
                                    </div>
                                    <div class="w-full shrink-0 bg-white">
                                        <img src="{{ asset('images/services/service-slide-4.jpg') }}" 
                                             alt="Commercial Print Services - Sintra Board, Pull-Up Banners, Button Pins, and Menus" 
                                             class="w-full h-auto block select-none" draggable="false" loading="lazy">
                                    </div>
                                </div>
                            </div>

                            {{-- Carousel Controls --}}
                            <div class="flex items-center justify-between pt-3.5 px-1">
                                <div class="flex items-center gap-2">
                                    <button @click="active = 0" type="button" aria-label="Slide 1"
                                            :class="active === 0 ? 'bg-blue-600 w-7' : 'bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 w-2.5'"
                                            class="h-2 rounded-full transition-all duration-200 cursor-pointer"></button>
                                    <button @click="active = 1" type="button" aria-label="Slide 2"
                                            :class="active === 1 ? 'bg-blue-600 w-7' : 'bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 w-2.5'"
                                            class="h-2 rounded-full transition-all duration-200 cursor-pointer"></button>
                                    <button @click="active = 2" type="button" aria-label="Slide 3"
                                            :class="active === 2 ? 'bg-blue-600 w-7' : 'bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 w-2.5'"
                                            class="h-2 rounded-full transition-all duration-200 cursor-pointer"></button>
                                    <button @click="active = 3" type="button" aria-label="Slide 4"
                                            :class="active === 3 ? 'bg-blue-600 w-7' : 'bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 w-2.5'"
                                            class="h-2 rounded-full transition-all duration-200 cursor-pointer"></button>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button @click="prev()" type="button" aria-label="Previous Slide"
                                            class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-blue-600 text-slate-700 dark:text-slate-200 hover:text-white border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-colors cursor-pointer">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </button>
                                    <button @click="next()" type="button" aria-label="Next Slide"
                                            class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-blue-600 text-slate-700 dark:text-slate-200 hover:text-white border border-slate-200 dark:border-slate-700 flex items-center justify-center transition-colors cursor-pointer">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <a href="{{ route('customer.portal') }}" 
                               class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm sm:text-base shadow-sm transition-colors cursor-pointer">
                                <span>Request a Custom Quote</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2.5">Upload artwork online &middot; Review official quotes &middot; Track pickup status</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── 02 SECTION: HOW SMART ROUTING WORKS ── -->
            <section id="decision-engine" class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#0F1523] border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center max-w-3xl mx-auto mb-14 reveal-on-scroll">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-semibold mb-4">
                            <i class="fa-solid fa-route text-[11px]"></i>
                            Smart Order Routing
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-slate-900 dark:text-white font-display tracking-tight leading-tight">
                            How CapaciPrint picks the right branch for every job
                        </h2>
                        <p class="mt-3.5 text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                            Instead of guessing which shop is free, every order runs through three automatic checks so your prints finish on schedule.
                        </p>
                    </div>

                    {{-- 3 Step Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

                        {{-- Step 1 --}}
                        <div class="p-6 sm:p-7 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-6 reveal-on-scroll">
                            <div class="space-y-3">
                                <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 dark:text-blue-400">
                                    <span class="w-6 h-6 rounded-full bg-blue-50 dark:bg-blue-500/15 flex items-center justify-center font-bold">1</span>
                                    <span>Equipment &amp; Material Check</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Matches the right machines</h3>
                                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 mt-1">
                                        <i class="fa-solid fa-circle-check text-[11px]"></i> Automatic capability filter
                                    </p>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Filters out any branch that doesn&apos;t have the exact press size, material stock, trained operators, or shift hours needed.
                                </p>
                            </div>
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-sm text-slate-600 dark:text-slate-300 space-y-2 tabular-nums">
                                <div class="flex items-center justify-between"><span>Press width</span> <strong class="text-emerald-600 dark:text-emerald-400 font-semibold">Fits job size</strong></div>
                                <div class="flex items-center justify-between"><span>Material stock</span> <strong class="text-emerald-600 dark:text-emerald-400 font-semibold">Available in-house</strong></div>
                                <div class="flex items-center justify-between"><span>Lead time</span> <strong class="text-emerald-600 dark:text-emerald-400 font-semibold">Meets cutoff</strong></div>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="p-6 sm:p-7 rounded-2xl bg-white dark:bg-slate-900 border-2 border-blue-600/80 dark:border-blue-500/70 shadow-sm flex flex-col justify-between space-y-6 reveal-on-scroll" style="transition-delay: 80ms;">
                            <div class="space-y-3">
                                <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 dark:text-blue-400">
                                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">2</span>
                                    <span>Weighted Scoring</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Balances shop workload</h3>
                                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 mt-1">
                                        <i class="fa-solid fa-sliders text-[11px]"></i> Real-time priority score
                                    </p>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Scores every eligible branch across machine readiness, staffing, queue load, and safety buffer before your deadline.
                                </p>
                            </div>
                            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800 tabular-nums">
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5"><span>Machine Availability</span><span class="text-blue-600 dark:text-blue-400">40%</span></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-blue-600 dark:bg-blue-500 rounded-full w-[40%]"></div></div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5"><span>Assigned Staff &amp; Skill</span><span class="text-blue-600 dark:text-blue-400">20%</span></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-blue-400 rounded-full w-[20%]"></div></div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5"><span>Current Queue Load</span><span class="text-amber-600 dark:text-amber-400">20%</span></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-amber-500 rounded-full w-[20%]"></div></div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-semibold text-slate-700 dark:text-slate-200 mb-1.5"><span>Deadline Safety Buffer</span><span class="text-emerald-600 dark:text-emerald-400">20%</span></div>
                                    <div class="h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-emerald-500 rounded-full w-[20%]"></div></div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="p-6 sm:p-7 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-6 reveal-on-scroll" style="transition-delay: 160ms;">
                            <div class="space-y-3">
                                <div class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 dark:text-blue-400">
                                    <span class="w-6 h-6 rounded-full bg-blue-50 dark:bg-blue-500/15 flex items-center justify-center font-bold">3</span>
                                    <span>Floor Dispatch</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display">Starts production right away</h3>
                                    <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 mt-1">
                                        <i class="fa-solid fa-tablet-screen-button text-[11px]"></i> Live shop queue sync
                                    </p>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Sends approved jobs straight to press operators sorted by earliest deadline and grouped by matching material rolls.
                                </p>
                            </div>
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-sm text-slate-600 dark:text-slate-300 space-y-2">
                                <div class="flex items-center justify-between"><span>Queue order</span> <strong class="text-slate-900 dark:text-white font-semibold">Earliest Due Date</strong></div>
                                <div class="flex items-center justify-between"><span>Batching</span> <strong class="text-slate-900 dark:text-white font-semibold">Grouped by material</strong></div>
                                <div class="flex items-center justify-between"><span>Status updates</span> <strong class="text-emerald-600 dark:text-emerald-400 font-semibold">Live in portal</strong></div>
                            </div>
                        </div>

                    </div>

                    {{-- Real-World Example Card --}}
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm p-6 sm:p-8 reveal-on-scroll">
                        <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-slate-200/80 dark:border-slate-800 gap-4">
                            <div>
                                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 block mb-1">Real-World Example</span>
                                <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white font-display">Tarpaulin Banner (3 &times; 5 ft &middot; 20 Units)</h3>
                                <div class="flex flex-wrap items-center gap-2.5 mt-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-medium">
                                        <i class="fa-regular fa-clock text-amber-500"></i> Due: <strong class="font-semibold">Today, 4:00 PM</strong>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-500/15 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                        <i class="fa-solid fa-bolt text-rose-500"></i> Rush Order
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-medium tabular-nums">
                                        <i class="fa-solid fa-layer-group text-blue-500"></i> Total area: <strong class="font-semibold">300 sq.ft</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs sm:text-sm font-semibold">
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                    Auto-Routed to South Press Plant
                                </span>
                            </div>
                        </div>

                        {{-- 3-Branch Comparison Grid --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 pt-6">

                            {{-- Branch 1: Unavailable --}}
                            <div class="p-5 sm:p-6 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-rose-200 dark:border-rose-500/30 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200/80 dark:border-slate-700">
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-white text-base font-display">Central Hub</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Branch 01 &middot; Morning Star</p>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                            Full Queue
                                        </span>
                                    </div>
                                    <div class="space-y-2.5 text-sm tabular-nums">
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Press Size</span>
                                            <span class="text-slate-900 dark:text-white font-semibold">3.2m Solvent</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Stock</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">In Stock</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Queue Load</span>
                                            <span class="text-rose-600 dark:text-rose-400 font-semibold">95% Busy</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Est. Finish</span>
                                            <span class="text-rose-600 dark:text-rose-400 font-bold">5:30 PM (Late)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t border-slate-200/80 dark:border-slate-700">
                                    <div class="flex items-center justify-between text-sm mb-2.5">
                                        <span class="text-slate-500 dark:text-slate-400">Branch Score</span>
                                        <span class="text-rose-600 dark:text-rose-400 font-bold tabular-nums">0 / 100</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-xs text-rose-700 dark:text-rose-300 font-medium flex items-center gap-2">
                                        <i class="fa-solid fa-ban shrink-0"></i>
                                        <span>Would miss the 4:00 PM rush deadline</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Branch 2: Selected Match --}}
                            <div class="p-5 sm:p-6 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border-2 border-blue-600 dark:border-blue-500 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-blue-200/70 dark:border-blue-800/60">
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-white text-base font-display">South Press Plant</h4>
                                            <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Branch 02 &middot; Selected Route</p>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-xs font-semibold flex items-center gap-1">
                                            <i class="fa-solid fa-check text-[11px]"></i> Best Match
                                        </span>
                                    </div>
                                    <div class="space-y-2.5 text-sm tabular-nums">
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Press Size</span>
                                            <span class="text-slate-900 dark:text-white font-semibold">3.2m Solvent</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Stock</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">1,200 sq.ft Ready</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Queue Load</span>
                                            <span class="text-blue-600 dark:text-blue-400 font-semibold">61% (Optimal)</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Est. Finish</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">2:30 PM (+1.5h buffer)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t border-blue-200/70 dark:border-blue-800/60">
                                    <div class="flex items-center justify-between text-sm mb-2.5">
                                        <span class="text-slate-600 dark:text-slate-300">Branch Score</span>
                                        <span class="text-blue-600 dark:text-blue-400 font-bold tabular-nums">96 / 100</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/15 text-xs text-emerald-700 dark:text-emerald-300 font-semibold flex items-center gap-2">
                                        <i class="fa-solid fa-circle-check shrink-0"></i>
                                        <span>Best capacity &amp; fastest turnaround</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Branch 3: High Capacity --}}
                            <div class="p-5 sm:p-6 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/90 dark:border-slate-700 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200/80 dark:border-slate-700">
                                        <div>
                                            <h4 class="font-bold text-slate-900 dark:text-white text-base font-display">North Workshop</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Branch 03 &middot; Green Heart</p>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 text-xs font-semibold">
                                            Busy Queue
                                        </span>
                                    </div>
                                    <div class="space-y-2.5 text-sm tabular-nums">
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Press Size</span>
                                            <span class="text-slate-900 dark:text-white font-semibold">3.2m Solvent</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Stock</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">In Stock</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Queue Load</span>
                                            <span class="text-amber-600 dark:text-amber-400 font-semibold">88% (Heavy)</span>
                                        </div>
                                        <div class="flex items-center justify-between text-slate-600 dark:text-slate-300">
                                            <span>Est. Finish</span>
                                            <span class="text-amber-600 dark:text-amber-400 font-bold">4:10 PM (At Risk)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t border-slate-200/80 dark:border-slate-700">
                                    <div class="flex items-center justify-between text-sm mb-2.5">
                                        <span class="text-slate-500 dark:text-slate-400">Branch Score</span>
                                        <span class="text-amber-600 dark:text-amber-400 font-bold tabular-nums">63 / 100</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-amber-50 dark:bg-amber-500/15 text-xs text-amber-800 dark:text-amber-300 font-medium flex items-center gap-2">
                                        <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
                                        <span>Tight queue may delay afternoon pickup</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </section>

            <!-- ── 03 SECTION: WHY CUSTOMERS CHOOSE CAPACIPRINT ── -->
            <section id="problem" class="py-16 lg:py-24 bg-white dark:bg-[#0B0F17] border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center max-w-3xl mx-auto mb-12 reveal-on-scroll">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-semibold mb-4">
                            Why CapaciPrint
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-slate-900 dark:text-white font-display tracking-tight leading-tight">
                            Why traditional print shops miss deadlines
                            <span class="block mt-1 text-blue-600 dark:text-blue-400">&mdash; and how we prevent them</span>
                        </h2>
                        <p class="mt-3.5 text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                            Most printing delays happen before a machine even starts. Shared live capacity replaces guesswork with reliable pickup times.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-stretch">

                        {{-- Problem Side --}}
                        <div class="p-6 sm:p-8 rounded-2xl bg-slate-50/70 dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 flex flex-col justify-between reveal-on-scroll">
                            <div>
                                <div class="flex items-center justify-between gap-3 border-b border-slate-200/80 dark:border-slate-800 pb-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-rose-50 dark:bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center text-base shrink-0">
                                            <i class="fa-solid fa-phone-slash"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">Traditional Print Shops</h3>
                                            <span class="text-xs text-rose-600 dark:text-rose-400 font-medium">Manual calls &bull; Paper sheets</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 text-xs font-semibold shrink-0">
                                        Old Way
                                    </span>
                                </div>

                                <ul class="space-y-4">
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Endless phone follow-ups</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Staff have to call around just to find out which wide-format press is free.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Surprise stockouts</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Materials tracked on paper lead to mid-job pauses when rolls run out.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Backlogged main branch</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Orders pile up at one shop while nearby sister branches sit idle.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-xmark"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">No clear order status</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Customers keep calling to ask whether their prints are ready for pickup.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-6 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200/80 dark:border-rose-500/25 text-sm text-rose-800 dark:text-rose-200 flex items-center gap-2.5">
                                <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 shrink-0"></i>
                                <span><strong class="font-semibold">Result:</strong> Unpredictable turnaround and missed event deadlines.</span>
                            </div>
                        </div>

                        {{-- Solution Side --}}
                        <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border-2 border-blue-600/80 dark:border-blue-500/70 shadow-sm flex flex-col justify-between reveal-on-scroll" style="transition-delay: 80ms;">
                            <div>
                                <div class="flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-blue-50 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                                            <i class="fa-solid fa-network-wired"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display">The CapaciPrint Network</h3>
                                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Smart routing &bull; Live tracking</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-semibold shrink-0 flex items-center gap-1">
                                        <i class="fa-solid fa-check text-[11px]"></i> CapaciPrint Way
                                    </span>
                                </div>

                                <ul class="space-y-4">
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Instant press compatibility check</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Verifies print dimensions and deadline feasibility before your job enters production.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Real-time material tracking</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Monitors vinyl, tarpaulin, paper, and ink inventory across all 3 branches.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Automatic load balancing</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Routes rush orders to whichever plant can finish fastest with room to spare.</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="h-6 w-6 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 text-xs">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <div>
                                            <strong class="text-slate-900 dark:text-white font-semibold block text-sm sm:text-base">Live progress &amp; QR claim slips</strong>
                                            <span class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Track every production step online and pick up smoothly with a QR code.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-6 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-sm text-emerald-800 dark:text-emerald-200 flex items-center gap-2.5">
                                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                                <span><strong class="font-semibold">Result:</strong> Reliable pricing, balanced shops, and on-time pickup.</span>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ── 04 SECTION: LIVE BRANCH CAPACITY ── -->
            <section id="branches" class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#0F1523] border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center max-w-3xl mx-auto mb-12 reveal-on-scroll">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-semibold mb-4">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Live Branch Capacity
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-slate-900 dark:text-white font-display tracking-tight leading-tight">
                            Real-time shop workload across our 3 branches
                        </h2>
                        <p class="mt-3.5 text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                            Continuous press capacity monitoring keeps turnaround estimates honest across every facility.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Facility 1 --}}
                        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200/90 dark:border-slate-800 shadow-sm hover:border-blue-400/60 dark:hover:border-blue-500/50 transition-colors space-y-5 reveal-on-scroll group">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white font-display text-lg group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Central Hub</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Morning Star Press</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Operational
                                </span>
                            </div>
                            <div class="space-y-3.5 text-sm tabular-nums">
                                <div>
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300 mb-1.5"><span>Current Workload</span><span class="font-bold text-blue-600 dark:text-blue-400">68%</span></div>
                                    <div class="h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-blue-600 dark:bg-blue-500 rounded-full w-[68%]"></div></div>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/80 rounded-xl divide-y divide-slate-200/70 dark:divide-slate-700/70 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                    <div class="flex justify-between px-4 py-2.5"><span>Presses</span><strong class="text-slate-900 dark:text-white font-semibold">4 Solvent Roll</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Operators</span><strong class="text-slate-900 dark:text-white font-semibold">6 On Shift</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Daily Capacity</span><strong class="text-slate-900 dark:text-white font-semibold">2,500 sq.ft</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Active Queue</span><strong class="text-slate-900 dark:text-white font-semibold">8 Orders</strong></div>
                                </div>
                            </div>
                        </div>

                        {{-- Facility 2 --}}
                        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200/90 dark:border-slate-800 shadow-sm hover:border-emerald-400/60 dark:hover:border-emerald-500/50 transition-colors space-y-5 reveal-on-scroll group" style="transition-delay: 80ms;">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white font-display text-lg group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">South Press Plant</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Morning Star Network</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Optimal
                                </span>
                            </div>
                            <div class="space-y-3.5 text-sm tabular-nums">
                                <div>
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300 mb-1.5"><span>Current Workload</span><span class="font-bold text-emerald-600 dark:text-emerald-400">52%</span></div>
                                    <div class="h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-emerald-500 rounded-full w-[52%]"></div></div>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/80 rounded-xl divide-y divide-slate-200/70 dark:divide-slate-700/70 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                    <div class="flex justify-between px-4 py-2.5"><span>Presses</span><strong class="text-slate-900 dark:text-white font-semibold">3 UV &amp; Solvent</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Operators</span><strong class="text-slate-900 dark:text-white font-semibold">4 On Shift</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Daily Capacity</span><strong class="text-slate-900 dark:text-white font-semibold">1,800 sq.ft</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Active Queue</span><strong class="text-slate-900 dark:text-white font-semibold">4 Orders</strong></div>
                                </div>
                            </div>
                        </div>

                        {{-- Facility 3 --}}
                        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200/90 dark:border-slate-800 shadow-sm hover:border-amber-400/60 dark:hover:border-amber-500/50 transition-colors space-y-5 reveal-on-scroll group" style="transition-delay: 160ms;">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white font-display text-lg group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">North Workshop</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Green Heart Hub</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-500/15 text-amber-800 dark:text-amber-300 text-xs font-semibold">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Active Load
                                </span>
                            </div>
                            <div class="space-y-3.5 text-sm tabular-nums">
                                <div>
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300 mb-1.5"><span>Current Workload</span><span class="font-bold text-amber-600 dark:text-amber-400">74%</span></div>
                                    <div class="h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden"><div class="h-full bg-amber-500 rounded-full w-[74%]"></div></div>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/80 rounded-xl divide-y divide-slate-200/70 dark:divide-slate-700/70 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                    <div class="flex justify-between px-4 py-2.5"><span>Presses</span><strong class="text-slate-900 dark:text-white font-semibold">2 DTF &amp; Duplicator</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Operators</span><strong class="text-slate-900 dark:text-white font-semibold">3 On Shift</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Daily Capacity</span><strong class="text-slate-900 dark:text-white font-semibold">1,200 pcs</strong></div>
                                    <div class="flex justify-between px-4 py-2.5"><span>Active Queue</span><strong class="text-slate-900 dark:text-white font-semibold">7 Orders</strong></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ── SECTION: 4-POINT QUALITY GUARANTEE ── -->
            <section class="py-16 lg:py-24 bg-white dark:bg-[#0B0F17] border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center max-w-3xl mx-auto mb-12 reveal-on-scroll">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-semibold mb-4">
                            Quality Guarantee
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-slate-900 dark:text-white font-display tracking-tight leading-tight">
                            Every order passes a 4-point shop check
                        </h2>
                        <p class="mt-3.5 text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
                            No print leaves the floor without verified operator inspection. Every batch clears four quality checks before your pickup claim slip turns green.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-stretch">

                        {{-- Left Card: 4-Point Shop Inspection --}}
                        <div class="bg-slate-50/90 dark:bg-slate-900 p-6 sm:p-8 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between gap-5 reveal-on-scroll">
                            <div class="space-y-5">
                                <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                                    <div>
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Inspection Steps</span>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mt-0.5">4-Point Shop Inspection</h3>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                                        Mandatory Sign-Off
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div class="px-4 py-3 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 flex items-center gap-3.5">
                                        <span class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">01</span>
                                        <div class="min-w-0">
                                            <strong class="text-sm font-semibold text-slate-900 dark:text-white block truncate">Color &amp; Density Check</strong>
                                            <span class="text-xs text-slate-600 dark:text-slate-400 block truncate">Matches proof colors against approved artwork.</span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 flex items-center gap-3.5">
                                        <span class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">02</span>
                                        <div class="min-w-0">
                                            <strong class="text-sm font-semibold text-slate-900 dark:text-white block truncate">Trim &amp; Cut Alignment</strong>
                                            <span class="text-xs text-slate-600 dark:text-slate-400 block truncate">Verifies clean bleeds and sharp contour cuts.</span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 flex items-center gap-3.5">
                                        <span class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">03</span>
                                        <div class="min-w-0">
                                            <strong class="text-sm font-semibold text-slate-900 dark:text-white block truncate">Lamination &amp; Finishing</strong>
                                            <span class="text-xs text-slate-600 dark:text-slate-400 block truncate">Checks grommet spacing, folds, and heat sealing.</span>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 flex items-center gap-3.5">
                                        <span class="h-8 w-8 rounded-lg bg-blue-50 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">04</span>
                                        <div class="min-w-0">
                                            <strong class="text-sm font-semibold text-slate-900 dark:text-white block truncate">Final Count &amp; QR Packaging</strong>
                                            <span class="text-xs text-slate-600 dark:text-slate-400 block truncate">Confirms piece count and attaches pickup QR tag.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 text-xs sm:text-sm text-slate-600 dark:text-slate-300 flex items-center justify-between">
                                <span>Stage: <strong class="text-slate-900 dark:text-white font-semibold">Pre-packaging verification</strong></span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 tabular-nums">4 of 4 required</span>
                            </div>
                        </div>

                        {{-- Right Card: Operator Quality Checklist --}}
                        <div class="bg-slate-50/90 dark:bg-slate-900 p-6 sm:p-8 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between gap-5 reveal-on-scroll" style="transition-delay: 80ms;">
                            <div class="space-y-5">
                                <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                                    <div>
                                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Operator Sign-Off</span>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-display mt-0.5">Operator Quality Checklist</h3>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                        All Checks Passed
                                    </span>
                                </div>

                                <div class="space-y-3 text-sm tabular-nums">
                                    <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                                        <span class="text-slate-700 dark:text-slate-200 font-medium">Ink Color Accuracy (Delta E &lt; 2.0)</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs sm:text-sm">Passed (0.8)</span>
                                    </div>
                                    <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                                        <span class="text-slate-700 dark:text-slate-200 font-medium">Cut Registration</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs sm:text-sm">Exact (0.1mm)</span>
                                    </div>
                                    <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                                        <span class="text-slate-700 dark:text-slate-200 font-medium">Grommet Spacing</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs sm:text-sm">24 in Spec OK</span>
                                    </div>
                                    <div class="flex items-center justify-between px-4 py-3.5 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                                        <span class="text-slate-700 dark:text-slate-200 font-medium">Verified Count</span>
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs sm:text-sm">20 / 20 Packed</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80 text-xs sm:text-sm text-slate-600 dark:text-slate-300 flex items-center justify-between">
                                <span>Verified by: <strong class="text-slate-900 dark:text-white font-semibold">J. Santos (Lead Tech)</strong></span>
                                <span class="font-semibold text-blue-600 dark:text-blue-400 tabular-nums">3:42 PM PHT</span>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ── 05 SECTION: PUBLIC ORDER TRACKING ── -->
            <section id="tracking" class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#0F1523] border-b border-slate-200/80 dark:border-slate-800/80">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                    <div class="mb-8 reveal-on-scroll">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 text-xs font-semibold mb-4">
                            Order Lookup
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight">
                            Track your order progress
                        </h2>
                        <p class="text-base text-slate-600 dark:text-slate-300 mt-2.5">Enter your order reference number to see your assigned branch and current production stage.</p>
                    </div>

                    {{-- Tracking Search Box --}}
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-sm rounded-2xl p-6 sm:p-7 space-y-4 text-left reveal-on-scroll">
                        <form @submit.prevent="lookupPublicOrder()" class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fa-solid fa-qrcode text-sm"></i>
                                </span>
                                <input type="text" x-model="trackQuery" required placeholder="Enter order number (e.g. ORD-001)..."
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 dark:focus:border-blue-500 transition-colors">
                            </div>
                            <button type="submit" :disabled="isTracking"
                                    class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 shrink-0">
                                <template x-if="isTracking">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-circle-notch animate-spin"></i> Checking...
                                    </span>
                                </template>
                                <template x-if="!isTracking">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-magnifying-glass"></i> Track Order
                                    </span>
                                </template>
                            </button>
                        </form>

                        {{-- Quick Sample Pill --}}
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span>Try sample order:</span>
                            <button type="button" @click="trackQuery = 'ORD-001'; lookupPublicOrder()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 text-slate-800 dark:text-slate-200 font-sans tabular-nums font-semibold text-xs transition-colors cursor-pointer">
                                ORD-001
                            </button>
                        </div>

                        {{-- Tracking Error --}}
                        <div x-show="trackError" class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-xs sm:text-sm text-rose-700 dark:text-rose-300 font-medium flex items-start gap-2.5" x-cloak>
                            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                            <span x-text="trackError"></span>
                        </div>

                        {{-- Tracking Result --}}
                        <div x-show="trackResult" class="p-5 sm:p-6 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 space-y-4" x-cloak>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200/80 dark:border-slate-700 pb-3 gap-2">
                                <div>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Order Reference</span>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-sans tabular-nums" x-text="'#' + trackResult?.order_number"></h3>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white w-fit" x-text="trackResult?.status_label"></span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-sm">
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs block">Service</span>
                                    <strong class="text-slate-900 dark:text-white font-semibold" x-text="trackResult?.service"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs block">Quantity &amp; Material</span>
                                    <strong class="text-slate-900 dark:text-white font-semibold" x-text="trackResult?.quantity + ' pcs &bull; ' + trackResult?.material"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs block">Assigned Branch</span>
                                    <strong class="text-blue-600 dark:text-blue-400 font-semibold" x-text="trackResult?.assigned_branch"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs block">Estimated Completion</span>
                                    <strong class="text-slate-900 dark:text-white font-semibold" x-text="trackResult?.estimated_completion"></strong>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="pt-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 tabular-nums">
                                    <span>Production Progress</span>
                                    <span class="text-blue-600 dark:text-blue-400" x-text="trackResult?.progress_percent + '%'"></span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-700" :style="'width: ' + trackResult?.progress_percent + '%'"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>

        <!-- ── FOOTER ── -->
        <footer class="bg-white dark:bg-[#0B0F17] text-slate-500 dark:text-slate-400 text-xs py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/caplogo.png') }}" class="h-8 w-auto object-contain" alt="CAPACIPRINT">
                        <div>
                            <span class="font-display font-extrabold text-slate-900 dark:text-white tracking-tight text-base block leading-none">
                                Capaci<span class="text-blue-600 dark:text-blue-400">Print</span>
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 block font-medium">Multi-Branch Print Network</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-6 text-slate-600 dark:text-slate-300 font-medium text-sm">
                        <a href="#services" class="hover:text-slate-900 dark:hover:text-white transition-colors">Services</a>
                        <a href="#decision-engine" class="hover:text-slate-900 dark:hover:text-white transition-colors">How It Works</a>
                        <a href="#problem" class="hover:text-slate-900 dark:hover:text-white transition-colors">Why CapaciPrint</a>
                        <a href="#branches" class="hover:text-slate-900 dark:hover:text-white transition-colors">Branches</a>
                        <a href="#tracking" class="hover:text-slate-900 dark:hover:text-white transition-colors">Track Order</a>
                        <a href="{{ route('customer.portal') }}" class="text-blue-600 dark:text-blue-400 hover:underline transition-colors flex items-center gap-1.5 font-semibold">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i> Customer Portal
                        </a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <p>&copy; {{ date('Y') }} CapaciPrint Multi-Branch Commercial Printing Network. All rights reserved.</p>
                </div>
            </div>
        </footer>

    </div>

    <!-- ── SCROLL REVEAL & TELEMETRY COUNTERS ── -->
    <style>
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.45s cubic-bezier(0.16, 1, 0.3, 1), transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-on-scroll.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealElements = document.querySelectorAll('.reveal-on-scroll');
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });

                revealElements.forEach(el => revealObserver.observe(el));
            } else {
                revealElements.forEach(el => el.classList.add('is-revealed'));
            }

            const statsSection = document.getElementById('stats-banner');
            let counted = false;
            if (statsSection && 'IntersectionObserver' in window) {
                const statsObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !counted) {
                            counted = true;
                            const counters = document.querySelectorAll('.counter-item');
                            counters.forEach(counter => {
                                const target = +counter.getAttribute('data-target');
                                const suffix = counter.getAttribute('data-suffix') || '';
                                const duration = 900;
                                const stepTime = 20;
                                const steps = duration / stepTime;
                                const increment = target / steps;
                                let current = 0;

                                const timer = setInterval(() => {
                                    current += increment;
                                    if (current >= target) {
                                        counter.textContent = target.toLocaleString() + suffix;
                                        clearInterval(timer);
                                    } else {
                                        counter.textContent = Math.floor(current).toLocaleString() + suffix;
                                    }
                                }, stepTime);
                            });
                        }
                    });
                }, { threshold: 0.3 });
                statsObserver.observe(statsSection);
            }

            const header = document.getElementById('main-header');
            if (header) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 24) {
                        header.classList.add('shadow-sm');
                    } else {
                        header.classList.remove('shadow-sm');
                    }
                }, { passive: true });
            }
        });
    </script>

        <!-- CUSTOMER PORTAL SIGN IN MODAL (MAIN LANDING PAGE) -->
        <div x-show="showLoginModal" x-cloak 
             x-init="@if($errors->any()) showLoginModal = true; @endif"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 dark:bg-black/75 backdrop-blur-sm p-4 animate-fade-in-up overscroll-contain">
            <div @click.away="showLoginModal = false" class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-800 p-7 sm:p-8 relative">
                
                {{-- Close Button --}}
                <button type="button" @click="showLoginModal = false" aria-label="Close Sign In Modal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

                <div class="text-center mb-6">
                    <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-12 w-auto object-contain mx-auto mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/70 text-blue-700 dark:text-blue-300 text-xs font-semibold border border-blue-200/70 dark:border-blue-800/60">
                        <i class="fa-solid fa-user-check"></i> Customer Portal
                    </span>
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white mt-2.5 tracking-tight">Customer Sign In</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Access active print jobs, approve quotations, and inspect digital proofs.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-4 flex items-start gap-2.5 p-3.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 rounded-xl text-xs text-rose-700 dark:text-rose-300 font-medium leading-relaxed">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div x-show="authError" class="mb-4 flex items-start gap-2.5 p-3.5 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 rounded-xl text-xs text-rose-700 dark:text-rose-300 font-medium leading-relaxed" x-cloak>
                    <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                    <span x-text="authError"></span>
                </div>

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="portal_type" value="customer">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </span>
                            <input type="email" name="email" required x-model="loginUsername" placeholder="customer@capaciprint.com"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>
                    </div>

                    <div x-data="{ showPass: false }">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password" required x-model="loginPassword" placeholder="••••••••"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-blue-600 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                            <button type="button" @click="showPass = !showPass" aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer">
                                <i class="fa-solid text-xs" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 text-slate-600 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 dark:border-slate-600 text-blue-600"> Remember me
                        </label>
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">Create Account</a>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors flex items-center justify-center gap-2 mt-2 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Customer Portal
                    </button>
                </form>

                <div class="mt-5 pt-4 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Demo Access:</span>
                    <button type="button" @click="fillLogin('customer')" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold cursor-pointer">
                        <i class="fa-solid fa-bolt text-amber-500 mr-1"></i> Fill Customer Demo
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- 2. MAIN APPLICATION LAYOUT -->
    <div x-show="isLoggedIn" class="flex min-h-screen bg-slate-50" x-data="{ sidebarOpen: false }">
        
        <!-- SIDEBAR CONTAINER (DESKTOP) -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 bg-navy-800 text-white border-r border-navy-900">
            <div class="flex h-16 items-center px-6 border-b border-navy-700 gap-3">
                <img src="{{ asset('images/caplogo.png') }}" class="h-9 w-auto brightness-0 invert" alt="CAPACIPRINT Logo">
                <div>
                    <h1 class="font-display font-bold text-sm tracking-wider leading-none">CAPACIPRINT</h1>
                    <span class="text-[11px] text-brand-400 tracking-wide font-semibold">Routing System</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 space-y-1 px-4 py-4 overflow-y-auto">
                
                <!-- CUSTOMER SIDEBAR LINKS -->
                <template x-if="userRole === 'customer'">
                    <div class="space-y-1">
                        <button @click="currentTab = 'customer-dashboard'" :class="currentTab === 'customer-dashboard' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-gauge w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Dashboard</span>
                        </button>
                        <button @click="currentTab = 'new-print-request'; resetPrintForm()" :class="currentTab === 'new-print-request' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-file-circle-plus w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>New Print Request</span>
                        </button>
                        <button @click="currentTab = 'customer-orders'" :class="currentTab === 'customer-orders' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-boxes-stacked w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>My Orders</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[11px] font-bold rounded-full bg-brand-400 text-navy-950" x-text="customerOrdersCount">0</span>
                        </button>
                        <button @click="currentTab = 'customer-quotations'" :class="currentTab === 'customer-quotations' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-file-invoice-dollar w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Quotations</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[11px] font-bold rounded-full bg-amber-500 text-navy-950" x-text="customerQuotesCount">0</span>
                        </button>
                        <button @click="currentTab = 'customer-notifications'" :class="currentTab === 'customer-notifications' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-bell w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Notifications</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[11px] font-bold rounded-full bg-brand-400 text-navy-950" x-show="unreadCount > 0" x-text="unreadCount">0</span>
                        </button>
                        <button @click="currentTab = 'customer-profile'" :class="currentTab === 'customer-profile' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-circle-user w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Profile</span>
                        </button>
                    </div>
                </template>

                <!-- STAFF / ADMIN SIDEBAR LINKS -->
                <template x-if="userRole === 'admin'">
                    <div class="space-y-1">
                        <button @click="currentTab = 'admin-dashboard'" :class="currentTab === 'admin-dashboard' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-chart-line w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Dashboard</span>
                        </button>
                        <button @click="currentTab = 'admin-orders'" :class="currentTab === 'admin-orders' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-receipt w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Orders</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[11px] font-bold rounded-full bg-brand-400 text-navy-950" x-text="adminOrdersCount">0</span>
                        </button>
                        <button @click="currentTab = 'admin-production'" :class="currentTab === 'admin-production' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-industry w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Production</span>
                        </button>
                        <button @click="currentTab = 'admin-planning'" :class="currentTab === 'admin-planning' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-brain w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Capacity Planning</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[11px] font-bold rounded-full bg-brand-400 text-navy-950">AI</span>
                        </button>
                        <button @click="currentTab = 'admin-inventory'" :class="currentTab === 'admin-inventory' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-warehouse w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Inventory</span>
                        </button>
                        <button @click="currentTab = 'admin-branches'" :class="currentTab === 'admin-branches' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-code-branch w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Branches</span>
                        </button>
                        <button @click="currentTab = 'admin-reports'" :class="currentTab === 'admin-reports' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-file-contract w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Reports</span>
                        </button>
                        <button @click="currentTab = 'admin-notifications'" :class="currentTab === 'admin-notifications' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-bell w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Notifications</span>
                        </button>
                        <button @click="currentTab = 'admin-settings'" :class="currentTab === 'admin-settings' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-sliders w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Settings</span>
                        </button>
                    </div>
                </template>
            </nav>

            <!-- Sidebar User Profile (Bottom) -->
            <div class="border-t border-navy-700 p-4 bg-navy-900/60">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold" x-text="getInitials(userName)"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate" x-text="userName"></p>
                        <p class="text-xs text-slate-400 capitalize truncate" x-text="userRole"></p>
                    </div>
                    <button @click="confirmSignout = true" class="text-slate-400 hover:text-red-400 p-1" title="Sign Out">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- MOBILE SIDEBAR DRAWER (SLIDE OVER) -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/80" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="fixed inset-0 flex">
                <div class="relative flex w-full max-w-xs flex-1 flex-col bg-navy-800 text-white" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <i class="fa-solid fa-xmark text-white text-xl"></i>
                        </button>
                    </div>

                    <div class="flex h-16 items-center px-6 border-b border-navy-700 gap-3 shrink-0">
                        <img src="{{ asset('images/caplogo.png') }}" class="h-9 w-auto brightness-0 invert" alt="CAPACIPRINT Logo">
                        <div>
                            <h1 class="font-display font-bold text-sm tracking-wider leading-none">CAPACIPRINT</h1>
                            <span class="text-[11px] text-brand-400 tracking-wide font-semibold">Routing System</span>
                        </div>
                    </div>

                    <!-- Navigation items (Mobile) -->
                    <nav class="flex-1 space-y-1 px-4 py-4 overflow-y-auto">
                        <template x-if="userRole === 'customer'">
                            <div class="space-y-1">
                                <button @click="currentTab = 'customer-dashboard'; sidebarOpen = false" :class="currentTab === 'customer-dashboard' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-gauge w-5 mr-3 text-lg"></i>
                                    <span>Dashboard</span>
                                </button>
                                <button @click="currentTab = 'new-print-request'; sidebarOpen = false; resetPrintForm()" :class="currentTab === 'new-print-request' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-file-circle-plus w-5 mr-3 text-lg"></i>
                                    <span>New Print Request</span>
                                </button>
                                <button @click="currentTab = 'customer-orders'; sidebarOpen = false" :class="currentTab === 'customer-orders' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-boxes-stacked w-5 mr-3 text-lg"></i>
                                    <span>My Orders</span>
                                </button>
                                <button @click="currentTab = 'customer-quotations'; sidebarOpen = false" :class="currentTab === 'customer-quotations' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-file-invoice-dollar w-5 mr-3 text-lg"></i>
                                    <span>Quotations</span>
                                </button>
                                <button @click="currentTab = 'customer-notifications'; sidebarOpen = false" :class="currentTab === 'customer-notifications' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-bell w-5 mr-3 text-lg"></i>
                                    <span>Notifications</span>
                                </button>
                                <button @click="currentTab = 'customer-profile'; sidebarOpen = false" :class="currentTab === 'customer-profile' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-circle-user w-5 mr-3 text-lg"></i>
                                    <span>Profile</span>
                                </button>
                            </div>
                        </template>

                        <template x-if="userRole === 'admin'">
                            <div class="space-y-1">
                                <button @click="currentTab = 'admin-dashboard'; sidebarOpen = false" :class="currentTab === 'admin-dashboard' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-chart-line w-5 mr-3 text-lg"></i>
                                    <span>Dashboard</span>
                                </button>
                                <button @click="currentTab = 'admin-orders'; sidebarOpen = false" :class="currentTab === 'admin-orders' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-receipt w-5 mr-3 text-lg"></i>
                                    <span>Orders</span>
                                </button>
                                <button @click="currentTab = 'admin-production'; sidebarOpen = false" :class="currentTab === 'admin-production' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-industry w-5 mr-3 text-lg"></i>
                                    <span>Production</span>
                                </button>
                                <button @click="currentTab = 'admin-planning'; sidebarOpen = false" :class="currentTab === 'admin-planning' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-brain w-5 mr-3 text-lg"></i>
                                    <span>Capacity Planning</span>
                                </button>
                                <button @click="currentTab = 'admin-inventory'; sidebarOpen = false" :class="currentTab === 'admin-inventory' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-warehouse w-5 mr-3 text-lg"></i>
                                    <span>Inventory</span>
                                </button>
                                <button @click="currentTab = 'admin-branches'; sidebarOpen = false" :class="currentTab === 'admin-branches' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-code-branch w-5 mr-3 text-lg"></i>
                                    <span>Branches</span>
                                </button>
                                <button @click="currentTab = 'admin-reports'; sidebarOpen = false" :class="currentTab === 'admin-reports' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-file-contract w-5 mr-3 text-lg"></i>
                                    <span>Reports</span>
                                </button>
                                <button @click="currentTab = 'admin-notifications'; sidebarOpen = false" :class="currentTab === 'admin-notifications' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-bell w-5 mr-3 text-lg"></i>
                                    <span>Notifications</span>
                                </button>
                                <button @click="currentTab = 'admin-settings'; sidebarOpen = false" :class="currentTab === 'admin-settings' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2.5 text-sm font-medium rounded-md">
                                    <i class="fa-solid fa-sliders w-5 mr-3 text-lg"></i>
                                    <span>Settings</span>
                                </button>
                            </div>
                        </template>
                    </nav>

                    <!-- Mobile Sidebar profile (bottom) -->
                    <div class="border-t border-navy-700 p-4 bg-navy-900/60 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold" x-text="getInitials(userName)"></div>
                            <div class="flex-grow min-w-0">
                                <p class="text-sm font-bold text-white truncate" x-text="userName"></p>
                                <p class="text-xs text-slate-400 capitalize truncate" x-text="userRole"></p>
                            </div>
                            <button @click="confirmSignout = true; sidebarOpen = false" class="text-slate-400 hover:text-red-400 p-1">
                                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            
            <!-- TOP APP BAR -->
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-slate-100 z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <!-- Screen Breadcrumbs / Context -->
                    <div class="flex items-center text-sm font-medium text-slate-500">
                        <span class="text-slate-400 uppercase tracking-wider text-[11px] font-semibold">CAPACIPRINT</span>
                        <i class="fa-solid fa-chevron-right text-[11px] mx-2 text-slate-300"></i>
                        <span class="text-navy-900 font-bold capitalize" x-text="getTabTitle(currentTab)"></span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button @click="userRole === 'customer' ? currentTab = 'customer-notifications' : currentTab = 'admin-notifications'" class="p-2 text-slate-500 hover:text-navy-900 hover:bg-slate-50 rounded-full transition relative">
                            <i class="fa-regular fa-bell text-lg"></i>
                            <span x-show="unreadCount > 0" class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-brand-400 ring-2 ring-white"></span>
                        </button>
                    </div>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <span class="text-xs text-slate-500 hidden sm:inline-block">Logged in as: <strong class="text-navy-900" x-text="userName"></strong></span>
                </div>
            </header>

            <!-- TAB MAIN CONTENTS CONTAINER -->
            <div class="flex-1 overflow-y-auto px-6 py-8 relative">

                <!-- Toast Notifications -->
                <div class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm pointer-events-none">
                    <template x-for="toast in toasts" :key="toast.id">
                        <div class="p-4 bg-white border-l-4 rounded-lg shadow-lg border-brand-400 text-slate-800 pointer-events-auto flex items-start gap-3 transition-transform transform duration-300" x-init="setTimeout(() => removeToast(toast.id), 4000)">
                            <i class="fa-solid fa-circle-check text-brand-500 mt-0.5"></i>
                            <div>
                                <h4 class="font-bold text-sm text-navy-900" x-text="toast.title"></h4>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="toast.message"></p>
                            </div>
                            <button @click="removeToast(toast.id)" class="ml-auto text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- ============================================== -->
                <!-- A. CUSTOMER PORTAL PAGES                       -->
                <!-- ============================================== -->

                <!-- 1. CUSTOMER DASHBOARD -->
                <div x-show="currentTab === 'customer-dashboard' && userRole === 'customer'" class="space-y-8">
                    <!-- Dashboard Greeting -->
                    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white rounded-2xl p-6 md:p-8 shadow-md border border-navy-950 relative overflow-hidden">
                        <div class="absolute inset-0 bg-[linear-gradient(to_right,#0e1e38_1px,transparent_1px),linear-gradient(to_bottom,#0e1e38_1px,transparent_1px)] bg-[size:3rem_3rem] opacity-30"></div>
                        <div class="relative z-10 max-w-xl">
                            <h2 class="text-2xl md:text-3xl font-bold font-display" x-text="'Welcome back, ' + userName + '!'"></h2>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition-all shadow flex items-center gap-2">
                                    <i class="fa-solid fa-plus"></i> Submit Print Request
                                </button>
                                <button @click="currentTab = 'customer-orders'" class="border border-navy-300 hover:bg-navy-800 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass"></i> View Order Details
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Stats Panel -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center gap-4">
                            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-boxes-stacked"></i></div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Active Orders</p>
                                <h3 class="text-2xl font-bold text-navy-900 font-display" x-text="customerActiveOrdersCount">0</h3>
                            </div>
                        </div>
                        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center gap-4">
                            <div class="h-12 w-12 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pending Quotations</p>
                                <h3 class="text-2xl font-bold text-navy-900 font-display" x-text="customerQuotesCount">0</h3>
                            </div>
                        </div>
                        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center gap-4">
                            <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-clipboard-check"></i></div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Completed Orders</p>
                                <h3 class="text-2xl font-bold text-navy-900 font-display" x-text="customerCompletedOrdersCount">0</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information Block (Timeline Tracking & Priority Attention) -->
                    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-navy-900 flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-blue-600"></i> Track Latest Order Progress</h3>
                            <span class="text-xs text-slate-400 font-medium">Order ID: <strong x-text="latestOrder.id"></strong></span>
                        </div>
                        <div class="p-6">
                            <!-- Empty state if no orders -->
                            <div x-show="!latestOrder.id" class="text-center py-8">
                                <i class="fa-solid fa-circle-question text-slate-300 text-4xl mb-2"></i>
                                <h4 class="font-bold text-navy-900">No active orders</h4>
                                <p class="text-sm text-slate-500 mt-1">You currently have no orders in production.</p>
                                <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Submit Print Request</button>
                            </div>

                            <div x-show="latestOrder.id" class="space-y-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                                    <div>
                                        <h4 class="text-lg font-bold text-navy-900" x-text="latestOrder.service"></h4>
                                        <p class="text-xs text-slate-500 mt-1" x-text="'Specs: ' + latestOrder.quantity + ' copies · ' + latestOrder.specs"></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-500">Routing Branch:</span>
                                        <span class="px-2.5 py-1 text-xs font-bold rounded bg-blue-100 text-blue-800" x-text="latestOrder.branch || 'Pending Routing'"></span>
                                    </div>
                                </div>

                                <!-- Tracker Progress Bar -->
                                <div class="py-4">
                                    <div class="relative flex items-center justify-between">
                                        <!-- Connection Line -->
                                        <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 z-0"></div>
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-500 z-0 transition-all duration-500" :style="'width: ' + getProgressPercent(latestOrder.status) + '%'"></div>

                                        <!-- Step 1 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 1)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-file-arrow-up"></i>
                                            </div>
                                            <span class="text-[11px] md:text-xs font-bold text-navy-900 mt-2">Submitted</span>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 2)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </div>
                                            <span class="text-[11px] md:text-xs font-bold text-navy-900 mt-2">Quote Ready</span>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 3)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-credit-card"></i>
                                            </div>
                                            <span class="text-[11px] md:text-xs font-bold text-navy-900 mt-2">Paid</span>
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 4)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-gears animate-spin" x-show="latestOrder.status === 'in_production'"></i>
                                                <i class="fa-solid fa-industry" x-show="latestOrder.status !== 'in_production'"></i>
                                            </div>
                                            <span class="text-[11px] md:text-xs font-bold text-navy-900 mt-2">Producing</span>
                                        </div>

                                        <!-- Step 5 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 5)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-truck-ramp-box"></i>
                                            </div>
                                            <span class="text-[11px] md:text-xs font-bold text-navy-900 mt-2">Ready</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status Description Card -->
                                <div class="mt-4 p-4 rounded-lg bg-slate-50 border border-slate-100 flex items-start gap-4">
                                    <div class="p-2.5 bg-white border border-slate-150 rounded-lg text-lg text-blue-600 shadow-sm shrink-0">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <div class="flex-grow space-y-1">
                                        <h5 class="text-sm font-bold text-navy-900">
                                            Current Status: <span class="uppercase text-blue-600" x-text="getStatusLabel(latestOrder.status)"></span>
                                        </h5>
                                        <p class="text-xs text-slate-500" x-text="getStatusDescription(latestOrder.status)"></p>
                                        <div class="pt-2 text-[11px] font-semibold text-navy-800 flex flex-wrap gap-x-6 gap-y-1">
                                            <span x-show="latestOrder.estimated_completion"><i class="fa-regular fa-calendar-check text-blue-600 mr-1"></i> Est. Completion: <span class="text-slate-600 font-normal" x-text="latestOrder.estimated_completion"></span></span>
                                            <span x-show="latestOrder.branch"><i class="fa-solid fa-building-circle-check text-blue-600 mr-1"></i> Location: <span class="text-slate-600 font-normal" x-text="latestOrder.branch"></span></span>
                                        </div>
                                    </div>
                                    <!-- Action button inside tracker if payment is pending -->
                                    <div class="shrink-0" x-show="latestOrder.status === 'quotation_ready'">
                                        <button @click="currentTab = 'customer-quotations'" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded text-xs transition font-semibold">Approve & Pay</button>
                                    </div>
                                    <!-- Claiming Info if ready -->
                                    <div class="shrink-0" x-show="latestOrder.status === 'ready_for_pickup'">
                                        <button @click="showClaimInstructions(latestOrder)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded text-xs transition font-semibold">Claim Instructions</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. CUSTOMER NEW PRINT REQUEST (5-STEP FORM) -->
                <div x-show="currentTab === 'new-print-request' && userRole === 'customer'" class="max-w-3xl mx-auto space-y-8">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Submit Print Request</h2>
                        <p class="text-sm text-slate-500 mt-1">Configure your specifications, upload files, and preview routing capacity.</p>
                    </div>

                    <!-- Step Header Indicators -->
                    <div class="grid grid-cols-5 gap-2 text-center text-xs font-semibold text-slate-400">
                        <div :class="printStep >= 1 ? 'text-blue-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 1 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : (printStep > 1 ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">1</span>
                            <span class="hidden sm:inline">Service</span>
                        </div>
                        <div :class="printStep >= 2 ? 'text-blue-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 2 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : (printStep > 2 ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">2</span>
                            <span class="hidden sm:inline">Specifications</span>
                        </div>
                        <div :class="printStep >= 3 ? 'text-blue-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 3 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : (printStep > 3 ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">3</span>
                            <span class="hidden sm:inline">Artwork</span>
                        </div>
                        <div :class="printStep >= 4 ? 'text-blue-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 4 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : (printStep > 4 ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">4</span>
                            <span class="hidden sm:inline">Schedule</span>
                        </div>
                        <div :class="printStep >= 5 ? 'text-blue-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 5 ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 'bg-slate-100 text-slate-400'" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">5</span>
                            <span class="hidden sm:inline">Review</span>
                        </div>
                    </div>

                    <!-- Step Panels -->
                    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6 sm:p-8">
                        
                        <!-- STEP 1: SERVICE -->
                        <div x-show="printStep === 1" class="space-y-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Select Print Service</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="service in availableServices" :key="service.id">
                                    <div @click="formService = service.name; formServiceId = service.id" :class="formService === service.name ? 'border-blue-500 bg-blue-50/20 shadow-md ring-1 ring-blue-500' : 'border-slate-200 hover:border-slate-300'" class="border p-5 rounded-xl cursor-pointer transition flex items-start gap-4">
                                        <div class="h-10 w-10 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-lg flex items-center justify-center text-lg shrink-0">
                                            <i :class="service.icon"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-navy-900" x-text="service.name"></h4>
                                            <p class="text-xs text-slate-500 mt-1 leading-relaxed" x-text="service.description"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- STEP 2: SPECIFICATIONS -->
                        <div x-show="printStep === 2" class="space-y-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Configure Specifications</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" x-model.number="formQuantity" @input="validateStep2" min="1" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <p x-show="quantityError" class="text-red-600 text-xs mt-1.5 font-semibold" x-text="quantityError"></p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Size / Dimensions <span class="text-red-500">*</span></label>
                                    <select x-model="formSize" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="A4">A4 (210 x 297 mm)</option>
                                        <option value="A5">A5 (148 x 210 mm)</option>
                                        <option value="Letter">Letter (8.5 x 11 in)</option>
                                        <option value="Poster Size">Poster size (18 x 24 in)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Paper / Material Type <span class="text-red-500">*</span></label>
                                    <select x-model="formPaper" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="Matte 100gsm">Matte 100gsm (Lightweight)</option>
                                        <option value="Glossy 250gsm">Glossy 250gsm (Premium Cover)</option>
                                        <option value="Cardstock 300gsm">Cardstock 300gsm (Heavyweight)</option>
                                        <option value="Vinyl Sheet">Vinyl Sheet (Weatherproof Banners)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Finishing Options</label>
                                    <select x-model="formFinish" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="None">None (Standard Cut)</option>
                                        <option value="Gloss Lamination">Gloss Lamination</option>
                                        <option value="Matte Lamination">Matte Lamination</option>
                                        <option value="Saddle Stitch Binding">Saddle Stitch Binding</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: ARTWORK -->
                        <div x-show="printStep === 3" class="space-y-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Upload Artwork File</h3>
                            <div class="border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl p-8 text-center transition bg-slate-50/50 cursor-pointer relative" @click="$refs.fileInput.click()">
                                <input type="file" x-ref="fileInput" class="hidden" @change="handleFileUpload">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-3 block"></i>
                                <p class="text-sm font-bold text-navy-900">Drag & drop your files here, or <span class="text-blue-600">browse</span></p>
                                <p class="text-xs text-slate-400 mt-1">Accepts PDF, EPS, TIFF, high-res JPG (Max file size: 50MB)</p>
                            </div>

                            <div x-show="formFilename" class="p-4 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-bold text-navy-900 truncate max-w-xs" x-text="formFilename"></p>
                                        <p class="text-xs text-slate-400" x-text="formFilesize"></p>
                                    </div>
                                </div>
                                <button @click.stop="formFilename = ''; formFilesize = ''" class="text-slate-400 hover:text-red-500 p-1"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                            <p x-show="fileError" class="text-red-600 text-xs font-semibold" x-text="fileError"></p>
                        </div>

                        <!-- STEP 4: SCHEDULE & DISTRIBUTION -->
                        <div x-show="printStep === 4" class="space-y-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Schedule & Delivery</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Target Completion Date <span class="text-red-500">*</span></label>
                                    <input type="date" x-model="formTargetDate" @change="validateStep4" :min="minDate" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <p x-show="dateError" class="text-red-600 text-xs mt-1.5 font-semibold" x-text="dateError"></p>
                                    <p class="text-[11px] text-slate-400 mt-1.5">Minimum 2-day production window required for standard jobs.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Collection Mode <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-3 mt-1">
                                        <label :class="formDeliveryMode === 'pickup' ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200'" class="border p-3 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="formDeliveryMode" value="pickup" class="sr-only">
                                            <i class="fa-solid fa-store text-navy-800 mb-1"></i>
                                            <span class="block text-xs font-bold text-navy-900">Branch Pickup</span>
                                        </label>
                                        <label :class="formDeliveryMode === 'shipping' ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200'" class="border p-3 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="formDeliveryMode" value="shipping" class="sr-only">
                                            <i class="fa-solid fa-truck text-navy-800 mb-1"></i>
                                            <span class="block text-xs font-bold text-navy-900">Courier Shipping</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 5: REVIEW -->
                        <div x-show="printStep === 5" class="space-y-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-2">Review Print Request Details</h3>
                            
                            <div class="border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                                <table class="min-w-full divide-y divide-slate-100 text-sm">
                                    <tbody class="bg-white divide-y divide-slate-100">
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50 w-1/3">Print Service</td>
                                            <td class="px-6 py-3 font-bold text-navy-900" x-text="formService"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50">Quantity & Size</td>
                                            <td class="px-6 py-3 text-slate-800" x-text="formQuantity + ' copies · ' + formSize"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50">Paper & Finish</td>
                                            <td class="px-6 py-3 text-slate-800" x-text="formPaper + ' (' + formFinish + ')'"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50">Artwork File</td>
                                            <td class="px-6 py-3 text-slate-800" x-text="formFilename || 'No file selected'"></td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50">Target Completion</td>
                                            <td class="px-6 py-3 text-slate-800" x-text="formTargetDate + ' (' + (formDeliveryMode === 'pickup' ? 'Store Pickup' : 'Courier Delivery') + ')'"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Cost Estimate Alert -->
                            <div class="p-4 bg-blue-50/50 border border-blue-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-tags text-blue-600 text-lg"></i>
                                    <div>
                                        <h4 class="font-bold text-navy-900">Estimated Cost Range</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Formal quotation generated immediately upon routing analysis.</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-black text-navy-900 font-display" x-text="'$' + getEstimatedCost() + '.00'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-8 border-t border-slate-100 pt-6 flex justify-between items-center">
                            <button @click="prevStep" x-show="printStep > 1" class="border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </button>
                            <div x-show="printStep === 1"></div> <!-- spacer -->

                            <button @click="nextStep" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded-lg text-sm transition-all shadow flex items-center gap-2">
                                <span x-text="printStep === 5 ? 'Submit Print Request' : 'Continue'"></span>
                                <i class="fa-solid fa-arrow-right" x-show="printStep < 5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. CUSTOMER MY ORDERS -->
                <div x-show="currentTab === 'customer-orders' && userRole === 'customer'" class="space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-navy-900 font-display">My Orders</h2>
                            <p class="text-sm text-slate-500 mt-1">Review all your printing requests and active operations.</p>
                        </div>
                        <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Submit Print Request
                        </button>
                    </div>

                    <!-- Filter buttons -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                        <button @click="ordersFilter = 'all'" :class="ordersFilter === 'all' ? 'bg-navy-900 text-white font-bold' : 'bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-medium'" class="px-3.5 py-1.5 rounded-full text-xs transition shrink-0">All Orders</button>
                        <button @click="ordersFilter = 'submitted'" :class="ordersFilter === 'submitted' ? 'bg-navy-900 text-white font-bold' : 'bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-medium'" class="px-3.5 py-1.5 rounded-full text-xs transition shrink-0">Submitted</button>
                        <button @click="ordersFilter = 'quotation_ready'" :class="ordersFilter === 'quotation_ready' ? 'bg-navy-900 text-white font-bold' : 'bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-medium'" class="px-3.5 py-1.5 rounded-full text-xs transition shrink-0">Quotes Pending</button>
                        <button @click="ordersFilter = 'in_production'" :class="ordersFilter === 'in_production' ? 'bg-navy-900 text-white font-bold' : 'bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-medium'" class="px-3.5 py-1.5 rounded-full text-xs transition shrink-0">In Production</button>
                        <button @click="ordersFilter = 'ready_for_pickup'" :class="ordersFilter === 'ready_for_pickup' ? 'bg-navy-900 text-white font-bold' : 'bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-medium'" class="px-3.5 py-1.5 rounded-full text-xs transition shrink-0">Ready for Claim</button>
                    </div>

                    <!-- Orders List (Cards for Mobile, Table for Desktop) -->
                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                        <!-- Desktop Table -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Job Service</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Target Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Assigned Branch</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    <template x-for="order in getFilteredOrders(ordersFilter)" :key="order.id">
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-6 py-4 font-bold text-navy-900" x-text="order.id"></td>
                                            <td class="px-6 py-4 font-semibold text-slate-800" x-text="order.service"></td>
                                            <td class="px-6 py-4 text-slate-600" x-text="order.quantity"></td>
                                            <td class="px-6 py-4 text-slate-600" x-text="order.target_date"></td>
                                            <td class="px-6 py-4 text-slate-600">
                                                <span class="font-medium" x-text="order.branch || 'Analyzing Capacity...'"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span :class="getStatusBadgeClass(order.status)" class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase" x-text="getStatusLabel(order.status)"></span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button @click="showOrderDetails(order)" class="text-blue-600 hover:text-blue-800 font-bold text-xs">View Order Details</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="getFilteredOrders(ordersFilter).length === 0">
                                        <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                            <i class="fa-solid fa-box-open text-3xl text-slate-300 mb-2 block"></i>
                                            No orders match the selected filter.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile List -->
                        <div class="block sm:hidden divide-y divide-slate-100">
                            <template x-for="order in getFilteredOrders(ordersFilter)" :key="order.id">
                                <div class="p-4 space-y-3 hover:bg-slate-50/50 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-navy-900" x-text="order.id"></span>
                                        <span :class="getStatusBadgeClass(order.status)" class="px-2 py-0.5 rounded text-[11px] font-bold uppercase" x-text="getStatusLabel(order.status)"></span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-800" x-text="order.service"></h4>
                                        <p class="text-xs text-slate-500 mt-0.5" x-text="'Qty: ' + order.quantity + ' · Date: ' + order.target_date"></p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-100/50 text-xs">
                                        <span class="text-slate-400" x-text="'Branch: ' + (order.branch || 'Routing...')"></span>
                                        <button @click="showOrderDetails(order)" class="text-blue-600 hover:text-blue-800 font-bold">View Order Details</button>
                                    </div>
                                </div>
                            </template>
                            <div x-show="getFilteredOrders(ordersFilter).length === 0" class="p-8 text-center text-slate-500">
                                No orders found.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. CUSTOMER QUOTATIONS -->
                <div x-show="currentTab === 'customer-quotations' && userRole === 'customer'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Quotations & Payments</h2>
                        <p class="text-sm text-slate-500 mt-1">Review active prices generated by local routing rules and verify deposits.</p>
                    </div>

                    <!-- Quotations Lists -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="order in getQuotes()" :key="order.id">
                            <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                                <div class="p-6 space-y-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Quotation No.</span>
                                            <h4 class="text-base font-bold text-navy-900" x-text="'QT-' + order.id.slice(3)"></h4>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded text-[11px] font-black bg-amber-100 text-amber-800 uppercase">Awaiting Action</span>
                                    </div>

                                    <div class="space-y-2 text-xs">
                                        <p class="text-slate-500">Job Service: <strong class="text-navy-900" x-text="order.service"></strong></p>
                                        <p class="text-slate-500">Specifications: <span class="text-slate-800" x-text="order.quantity + ' copies · ' + order.specs"></span></p>
                                        <p class="text-slate-500">Routing Branch: <span class="text-slate-800" x-text="order.branch"></span></p>
                                    </div>

                                    <!-- Price details breakdown -->
                                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                                        <span class="text-xs text-slate-400">Total Price (GST Inc.):</span>
                                        <span class="text-lg font-black text-navy-900 font-display" x-text="'$' + getPrice(order) + '.00'"></span>
                                    </div>
                                </div>

                                <div class="bg-slate-50/60 border-t border-slate-100 px-6 py-4 flex gap-3">
                                    <button @click="rejectQuote(order)" class="flex-1 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-medium py-2 rounded text-xs transition">Reject Quote</button>
                                    <button @click="approveAndPay(order)" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded text-xs transition shadow">Approve & Pay</button>
                                </div>
                            </div>
                        </template>

                        <div x-show="getQuotes().length === 0" class="col-span-2 bg-white border border-slate-100 rounded-xl p-10 text-center">
                            <i class="fa-solid fa-receipt text-4xl text-slate-205 mb-2"></i>
                            <h4 class="font-bold text-navy-900">No active quotations</h4>
                            <p class="text-sm text-slate-500 mt-1">Submit a new request or check under "My Orders" for existing jobs.</p>
                        </div>
                    </div>
                </div>

                <!-- 5. CUSTOMER NOTIFICATIONS -->
                <div x-show="currentTab === 'customer-notifications' && userRole === 'customer'" class="space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-navy-900 font-display">Notifications</h2>
                            <p class="text-sm text-slate-500 mt-1">Stay updated with status modifications and actions required.</p>
                        </div>
                        <button @click="markAllAsRead" x-show="unreadCount > 0" class="text-xs text-blue-600 hover:text-blue-800 font-semibold font-semibold">Mark all as read</button>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm divide-y divide-slate-100">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div :class="notif.read ? 'bg-white' : 'bg-blue-50/10'" class="p-4 flex items-start gap-4 hover:bg-slate-50/50 transition">
                                <div class="p-2 bg-slate-50 text-blue-600 rounded-lg shrink-0">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-sm font-bold text-navy-900" x-text="notif.title"></h4>
                                        <span class="text-[11px] text-slate-400" x-text="notif.time"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1" x-text="notif.body"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="notifications.length === 0" class="p-8 text-center text-slate-500">
                            No notifications.
                        </div>
                    </div>
                </div>

                <!-- 6. CUSTOMER PROFILE -->
                <div x-show="currentTab === 'customer-profile' && userRole === 'customer'" class="max-w-2xl mx-auto space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">My Profile</h2>
                        <p class="text-sm text-slate-500 mt-1">Review contact information and delivery settings.</p>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm p-6 space-y-6">
                        <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                            <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold font-display" x-text="getInitials(userName)"></div>
                            <div>
                                <h3 class="text-lg font-bold text-navy-900" x-text="userName"></h3>
                                <p class="text-xs text-slate-500" x-text="'Customer Account · ID: CAP-' + Math.floor(Math.random()*9000 + 1000)"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Email Address</span>
                                <span class="text-slate-800 font-medium">customer@capaciprint.com</span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Phone Number</span>
                                <span class="text-slate-800 font-medium">+61 491 570 156</span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Default Shipping Address</span>
                                <span class="text-slate-800 font-medium">Level 3, 100 St Georges Terrace, Perth WA 6000, Australia</span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ============================================== -->
                <!-- B. STAFF / ADMIN PORTAL PAGES                  -->
                <!-- ============================================== -->

                <!-- 1. ADMIN DASHBOARD -->
                <div x-show="currentTab === 'admin-dashboard' && userRole === 'admin'" class="space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1">
                            <div class="flex justify-between text-slate-400"><span class="text-[11px] font-bold uppercase tracking-wider">Today's Orders</span><i class="fa-solid fa-calendar-day"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display" x-text="adminOrdersCount">0</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1">
                            <div class="flex justify-between text-slate-400"><span class="text-[11px] font-bold uppercase tracking-wider">Active Production</span><i class="fa-solid fa-gears text-blue-600"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display">12</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1 border-l-4 border-l-red-500">
                            <div class="flex justify-between text-slate-400"><span class="text-[11px] font-bold uppercase tracking-wider text-red-600">Rush Orders</span><i class="fa-solid fa-fire-flame-curved text-red-500"></i></div>
                            <h3 class="text-2xl font-bold text-red-600 font-display">3</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1 border-l-4 border-l-amber-500">
                            <div class="flex justify-between text-slate-400"><span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Delayed Jobs</span><i class="fa-solid fa-triangle-exclamation text-amber-500"></i></div>
                            <h3 class="text-2xl font-bold text-amber-600 font-display">1</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1">
                            <div class="flex justify-between text-slate-400"><span class="text-[11px] font-bold uppercase tracking-wider">Branch Capacity</span><i class="fa-solid fa-chart-pie"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display">64%</h3>
                        </div>
                    </div>

                    <!-- Priority Actions Section -->
                    <div class="bg-white border border-slate-150 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-navy-900 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Action Queue (Requires Attention)</h3>
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">Critical Priority</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <!-- Shortage Alert -->
                            <div class="p-4 flex items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-9 w-9 bg-red-50 text-red-600 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-box-open"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm text-navy-900">Inventory Supply Shortage <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800 ml-1.5">At Risk</span></h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Glossy 250gsm Cover stock volume at Branch 1 is down to 25 sheets (Limited Capacity).</p>
                                    </div>
                                </div>
                                <button @click="currentTab = 'admin-inventory'" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg text-xs transition shadow-sm shrink-0">Reorder Stock</button>
                            </div>

                            <!-- Unrouted Order Alert -->
                            <div class="p-4 flex items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-9 w-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-route"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm text-navy-900">Pending Route Validation <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 ml-1.5">Recommended</span></h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Incoming order from Nieressa requires final validation and branch confirmation.</p>
                                    </div>
                                </div>
                                <button @click="currentTab = 'admin-orders'" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded text-xs transition shadow shrink-0">Evaluate Route</button>
                            </div>
                        </div>
                    </div>

                    <!-- Layout of Branch Overviews -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Branch Workloads -->
                        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-xl shadow-sm p-6 space-y-6">
                            <h3 class="font-bold text-navy-900 text-base">Production Branch Output Workloads</h3>
                            <div class="space-y-4">
                                <template x-for="branch in branches" :key="branch.id">
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-navy-900" x-text="branch.name + ' (' + branch.location + ')'"></span>
                                            <span class="font-bold text-slate-600" x-text="branch.workload + '% Workload'"></span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div :class="branch.workload > 80 ? 'bg-red-500' : (branch.workload > 50 ? 'bg-amber-500' : 'bg-blue-500')" class="h-full rounded-full transition-all duration-500" :style="'width: ' + branch.workload + '%'"></div>
                                        </div>
                                        <div class="flex gap-x-4 text-[11px] text-slate-400 font-medium">
                                            <span x-text="'Machines Active: ' + branch.machines_active + '/' + branch.total_machines"></span>
                                            <span x-text="'Staff Staffed: ' + branch.staff_active"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Routing metrics breakdown -->
                        <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6 space-y-6 flex flex-col justify-between">
                            <h3 class="font-bold text-navy-900 text-base">Capacity Routing Accuracy</h3>
                            <div class="py-4 flex justify-center">
                                <!-- Mock circular gauge in SVG -->
                                <div class="relative h-32 w-32 flex items-center justify-center">
                                    <svg class="absolute inset-0 h-full w-full transform -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="text-blue-500" stroke-width="3" stroke-dasharray="96, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="text-center">
                                        <span class="text-3xl font-black text-navy-900 font-display">96%</span>
                                        <span class="block text-[11px] text-slate-400 font-semibold">Reliability</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 text-center leading-relaxed">System automates routing recommendations based on machine queue metrics, material stock sheets, and delivery constraints.</p>
                        </div>
                    </div>
                </div>

                <!-- 2. ADMIN ORDERS (INCOMING QUEUE) -->
                <div x-show="currentTab === 'admin-orders' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Customer Print Orders Queue</h2>
                        <p class="text-sm text-slate-500 mt-1">Review raw print requests, evaluate branch workloads, and trigger routings.</p>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Service Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Target Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Recommended Routing</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    <template x-for="order in orders" :key="order.id">
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-6 py-4 font-bold text-navy-900" x-text="order.id"></td>
                                            <td class="px-6 py-4 font-semibold text-slate-800" x-text="order.customer"></td>
                                            <td class="px-6 py-4 text-slate-600" x-text="order.service"></td>
                                            <td class="px-6 py-4 text-slate-600" x-text="order.quantity"></td>
                                            <td class="px-6 py-4 text-slate-600" x-text="order.target_date"></td>
                                            <td class="px-6 py-4 text-slate-600">
                                                <span class="font-bold text-blue-600" x-text="order.branch || getAutoRecommendation(order)"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span :class="getStatusBadgeClass(order.status)" class="px-2 py-0.5 rounded text-[11px] font-bold uppercase" x-text="getStatusLabel(order.status)"></span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <!-- Action states -->
                                                <button x-show="order.status === 'submitted'" @click="routeOrderModal(order)" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1.5 rounded text-xs transition shadow font-semibold">Evaluate Routing</button>
                                                <button x-show="order.status !== 'submitted'" @click="showAdminOrderDetails(order)" class="text-slate-500 hover:text-navy-900 font-semibold text-xs">Manage Details</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. ADMIN PRODUCTION (ACTIVE WORKLOADS) -->
                <div x-show="currentTab === 'admin-production' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Active Print Production Operations</h2>
                        <p class="text-sm text-slate-500 mt-1">Review machinery queues and update work ticket milestones.</p>
                    </div>

                    <!-- Production list grouped by branch -->
                    <div class="grid grid-cols-1 gap-8">
                        <template x-for="branch in branches" :key="branch.id">
                            <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                                <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between">
                                    <h3 class="font-bold text-navy-900" x-text="branch.name + ' — Active Tickets (' + getJobsCountAtBranch(branch.name) + ')'"></h3>
                                    <span class="text-xs text-slate-400 font-semibold" x-text="'Workload Capacity: ' + branch.workload + '%'"></span>
                                </div>
                                <div class="p-0">
                                    <!-- Empty state if no jobs -->
                                    <div x-show="getJobsCountAtBranch(branch.name) === 0" class="p-8 text-center text-slate-500">
                                        <i class="fa-solid fa-folder-open text-3xl text-slate-200 mb-1.5 block"></i>
                                        There are no production jobs assigned to this branch.
                                    </div>

                                    <table x-show="getJobsCountAtBranch(branch.name) > 0" class="min-w-full divide-y divide-slate-100 text-sm">
                                        <thead class="bg-slate-50/30">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Ticket ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Job Details</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Progress</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Current Phase</th>
                                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <template x-for="job in getJobsAtBranch(branch.name)" :key="job.id">
                                                <tr class="hover:bg-slate-50/20">
                                                    <td class="px-6 py-4 font-bold text-navy-900" x-text="job.id"></td>
                                                    <td class="px-6 py-4">
                                                        <h5 class="font-bold text-slate-800" x-text="job.service"></h5>
                                                        <p class="text-xs text-slate-400 mt-0.5" x-text="'Qty: ' + job.quantity + ' · Size: ' + job.specs"></p>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-24 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                                                <div class="bg-blue-500 h-full rounded-full" :style="'width: ' + getProductionPercent(job.status) + '%'"></div>
                                                            </div>
                                                            <span class="text-xs text-slate-500" x-text="getProductionPercent(job.status) + '%'"></span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span :class="getStatusBadgeClass(job.status)" class="px-2 py-0.5 rounded text-[11px] font-bold uppercase" x-text="getStatusLabel(job.status)"></span>
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <button @click="advanceJobStatus(job)" class="bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold px-3 py-1.5 rounded text-xs transition">
                                                            <span x-text="job.status === 'paid' ? 'Begin Production' : 'Advance Milestone'"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 4. ADMIN CAPACITY PLANNING (ROUTING ENGINE SIMULATOR) -->
                <div x-show="currentTab === 'admin-planning' && userRole === 'admin'" class="space-y-6" x-data="planningSimulator()">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Intelligent Print Routing Capacity Engine</h2>
                        <p class="text-sm text-slate-500 mt-1">Configure simulator constraints to analyze system branch routing load balances.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Simulation Controls -->
                        <div class="bg-white border border-slate-150 rounded-xl p-6 shadow-sm space-y-6">
                            <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-3 flex items-center gap-2"><i class="fa-solid fa-sliders text-blue-600"></i> Simulator Constraints</h3>
                            
                            <!-- Simulated workload sliders -->
                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-navy-950 uppercase tracking-wide">Adjust Simulated Branch Workloads</h4>
                                <template x-for="(w, idx) in simWorkloads" :key="idx">
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span class="font-semibold text-slate-700" x-text="'Branch ' + (idx+1) + ' Current Load'"></span>
                                            <strong class="text-navy-900" x-text="w + '%'"></strong>
                                        </div>
                                        <input type="range" min="0" max="100" x-model.number="simWorkloads[idx]" class="w-full accent-cyan-500 h-1.5 bg-slate-100 rounded-lg cursor-pointer">
                                    </div>
                                </template>
                            </div>

                            <!-- Simulator order constraints -->
                            <div class="space-y-4 border-t border-slate-100 pt-4">
                                <h4 class="text-xs font-bold text-navy-950 uppercase tracking-wide">Simulation Job Requirements</h4>
                                
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Paper Stock Required</label>
                                    <select x-model="simPaper" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-xs text-slate-800">
                                        <option value="glossy">Glossy 250gsm (Branch 1 Stock AT RISK)</option>
                                        <option value="cardstock">Cardstock 300gsm (Fully Available)</option>
                                        <option value="matte">Matte 100gsm (Fully Available)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Target Production Urgency</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label :class="simUrgent ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200'" class="border p-2 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="simUrgent" :value="true" class="sr-only">
                                            <span class="text-xs font-semibold text-navy-900">RUSH (48 hrs)</span>
                                        </label>
                                        <label :class="!simUrgent ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200'" class="border p-2 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="simUrgent" :value="false" class="sr-only">
                                            <span class="text-xs font-semibold text-navy-900">Standard (5 Days)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Capacity Planning Recommendation Output -->
                        <div class="lg:col-span-2 bg-white border border-slate-150 rounded-xl p-6 shadow-sm flex flex-col justify-between space-y-6">
                            <div>
                                <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-3 flex items-center gap-2"><i class="fa-solid fa-microchip text-blue-600"></i> AI Engine Routing Results</h3>
                                
                                <div class="mt-6 flex flex-col sm:flex-row items-center gap-6 p-5 bg-blue-50/40 border border-blue-100 rounded-xl">
                                    <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-display font-bold shadow shrink-0">
                                        <span x-text="getRecommendation().branchId"></span>
                                    </div>
                                    <div class="text-center sm:text-left space-y-1">
                                        <span class="text-[11px] uppercase font-bold text-slate-400 tracking-wider">Primary System Recommendation</span>
                                        <h4 class="text-xl font-bold text-navy-900" x-text="'Recommended Branch: Branch ' + getRecommendation().branchId"></h4>
                                        <p class="text-xs text-slate-500" x-text="'Engine identified Branch ' + getRecommendation().branchId + ' as the optimal routing path with the highest probability of on-time delivery.'"></p>
                                    </div>
                                </div>

                                <!-- Explanation Why checklist -->
                                <div class="mt-8 space-y-4">
                                    <h4 class="text-xs font-bold text-navy-950 uppercase tracking-wide">Why is this branch recommended?</h4>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Machine Available -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().machineAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[11px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().machineAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Machines available (low queue)</span>
                                        </div>

                                        <!-- Materials Available -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().materialsAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[11px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().materialsAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Required paper materials in stock</span>
                                        </div>

                                        <!-- Employees Available -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().employeesAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[11px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().employeesAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Employees staffed & available</span>
                                        </div>

                                        <!-- Workload Acceptable -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().workloadAcceptable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[11px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().workloadAcceptable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Workload within thresholds</span>
                                        </div>

                                        <!-- Deadline Achievable -->
                                        <div class="flex items-center gap-3 sm:col-span-2">
                                            <span :class="getRecommendation().deadlineAchievable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[11px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().deadlineAchievable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Target deadline achievable at this branch</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[11px] text-slate-400 pt-4 border-t border-slate-100">Adjust the sliders and requirement switches on the left menu to observe real-time rerouting behaviors of the CAPACIPRINT Engine.</p>
                        </div>
                    </div>
                </div>

                <!-- 5. ADMIN INVENTORY -->
                <div x-show="currentTab === 'admin-inventory' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Paper & Ink Material Stocks</h2>
                        <p class="text-sm text-slate-500 mt-1">Monitor paper stocks, plates, and ink cartridges across warehouse centers.</p>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Stock Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Item Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Branch Warehouse</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Safety Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="item in inventory" :key="item.code">
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-navy-900" x-text="item.code"></td>
                                        <td class="px-6 py-4 font-semibold text-slate-800" x-text="item.name"></td>
                                        <td class="px-6 py-4 text-slate-600" x-text="item.branch"></td>
                                        <td class="px-6 py-4 text-slate-600" x-text="item.qty"></td>
                                        <td class="px-6 py-4">
                                            <span :class="getInventoryStatusClass(item.status)" class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase" x-text="item.status"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button @click="reorderStock(item)" class="text-blue-600 hover:text-blue-800 font-bold text-xs">Reorder Stock</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 6. ADMIN BRANCHES -->
                <div x-show="currentTab === 'admin-branches' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Branch Performance & Analytics</h2>
                        <p class="text-sm text-slate-500 mt-1">Review operational capacities and machine speeds for routed locations.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="branch in branches" :key="branch.id">
                            <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm p-6 space-y-4">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <h3 class="font-bold text-navy-900" x-text="branch.name"></h3>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-600 uppercase" x-text="branch.location"></span>
                                </div>
                                <div class="space-y-2 text-xs text-slate-500">
                                    <div class="flex justify-between"><span>Workload Capacity:</span><strong class="text-navy-900" x-text="branch.workload + '%'"></strong></div>
                                    <div class="flex justify-between"><span>Active Machines:</span><strong class="text-navy-900" x-text="branch.machines_active + ' / ' + branch.total_machines"></strong></div>
                                    <div class="flex justify-between"><span>Active Personnel:</span><strong class="text-navy-900" x-text="branch.staff_active"></strong></div>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex gap-2">
                                    <button @click="simulateBranchMaint(branch)" class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 py-1.5 rounded text-xs transition">Maintain Machine</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 7. ADMIN REPORTS -->
                <div x-show="currentTab === 'admin-reports' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">System Reports & Metrics</h2>
                        <p class="text-sm text-slate-500 mt-1">Review operational performance charts, routing balances, and financial summaries.</p>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-xl p-8 text-center max-w-lg mx-auto shadow-sm">
                        <i class="fa-solid fa-file-invoice-dollar text-4xl text-slate-200 mb-3 block"></i>
                        <h3 class="font-bold text-navy-900 text-lg">Operational Reports Center</h3>
                        <p class="text-slate-500 text-sm mt-1 leading-relaxed">Financial spreadsheets and daily branch utilization ratios can be downloaded as standard CSV or printed layouts.</p>
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                            <button @click="triggerToast('Report Center', 'Operational Report CSV downloaded.')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg text-xs shadow transition">Download CSV</button>
                            <button @click="triggerToast('Report Center', 'PDF printing summary generated.')" class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-5 rounded-lg text-xs transition">Generate PDF Summary</button>
                        </div>
                    </div>
                </div>

                <!-- 8. ADMIN NOTIFICATIONS -->
                <div x-show="currentTab === 'admin-notifications' && userRole === 'admin'" class="space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">Operations Notifications</h2>
                        <p class="text-sm text-slate-500 mt-1">Track alerts regarding resource depletion and routing machine delays.</p>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm divide-y divide-slate-100">
                        <template x-for="notif in adminNotifications" :key="notif.id">
                            <div class="p-4 flex items-start gap-4 hover:bg-slate-50/50 transition">
                                <div class="p-2 bg-slate-50 text-amber-600 rounded-lg shrink-0">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-sm font-bold text-navy-900" x-text="notif.title"></h4>
                                        <span class="text-[11px] text-slate-400" x-text="notif.time"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1" x-text="notif.body"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 9. ADMIN SETTINGS -->
                <div x-show="currentTab === 'admin-settings' && userRole === 'admin'" class="max-w-2xl mx-auto space-y-6">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-bold text-navy-900 font-display">System Settings</h2>
                        <p class="text-sm text-slate-500 mt-1">Configure threshold rules for the capacity engine routing weights.</p>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl p-6 shadow-sm space-y-6">
                        <h3 class="font-bold text-navy-900 text-base border-b border-slate-100 pb-3">Routing Engine Threshold Weightings</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Machine Queue Safety Threshold (%)</label>
                                <input type="number" value="85" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800">
                                <p class="text-[11px] text-slate-400 mt-1.5">Maximum workload load before routing triggers bypass alerts.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Standard Safety Material Threshold (Copies)</label>
                                <input type="number" value="500" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button @click="triggerToast('System Settings', 'Threshold weight rules updated.')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-xs shadow transition">Save Settings</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- 3. DIALOG MODALS & BACKDROPS -->
        
        <!-- SIGN OUT CONFIRMATION MODAL -->
        <div x-show="confirmSignout" class="relative z-50" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 space-y-6 border border-slate-100">
                        <div class="sm:flex sm:items-start gap-4">
                            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-base font-bold text-navy-900">Sign Out of CAPACIPRINT</h3>
                                <p class="text-sm text-slate-500 mt-2">Are you sure you want to end your current session and exit the dashboard?</p>
                            </div>
                        </div>
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 pt-4">
                            <button @click="confirmSignout = false" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-4 py-2 rounded-lg text-xs transition">Cancel</button>
                            <button @click="signOut" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-xs shadow transition">Sign Out Session</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUSTOMER ORDER DETAIL MODAL -->
        <div x-show="activeOrderDetails" class="relative z-50" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 space-y-6 border border-slate-100" @click.outside="activeOrderDetails = null">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-navy-900" x-text="'Order Detail: ' + (activeOrderDetails || {}).id"></h3>
                            <button @click="activeOrderDetails = null" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Service Option</span>
                                    <span class="text-sm font-bold text-navy-900" x-text="(activeOrderDetails || {}).service"></span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Status Badge</span>
                                    <span :class="getStatusBadgeClass((activeOrderDetails || {}).status)" class="px-2 py-0.5 rounded font-bold uppercase inline-block text-[11px]" x-text="getStatusLabel((activeOrderDetails || {}).status)"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-3">
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Quantity Requested</span>
                                    <span class="text-slate-800 font-medium" x-text="(activeOrderDetails || {}).quantity + ' copies'"></span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Stock Material Size</span>
                                    <span class="text-slate-800 font-medium" x-text="(activeOrderDetails || {}).specs"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-3">
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Scheduled Finish</span>
                                    <span class="text-slate-800 font-medium" x-text="(activeOrderDetails || {}).target_date"></span>
                                </div>
                                <div>
                                    <span class="block text-slate-400 font-semibold mb-1">Assigned Factory</span>
                                    <span class="text-slate-800 font-medium" x-text="(activeOrderDetails || {}).branch || 'Evaluating...'"></span>
                                </div>
                            </div>

                            <!-- Claim / Pickup Info if ready -->
                            <template x-if="(activeOrderDetails || {}).status === 'ready_for_pickup'">
                                <div class="p-4 bg-emerald-50 border border-emerald-150 rounded-xl space-y-2 mt-4">
                                    <h4 class="font-bold text-emerald-800 flex items-center gap-2"><i class="fa-solid fa-clipboard-check"></i> Pickup Claiming Instructions</h4>
                                    <p class="text-xs text-emerald-700 leading-relaxed">This job is ready! Visit the claims counter at <strong>Branch 2 (Sydney CBD)</strong>. Quote your Order ID <strong><span x-text="(activeOrderDetails || {}).id"></span></strong> and show a valid ID to claim your physical prints.</p>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                            <button @click="activeOrderDetails = null" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-4 py-2 rounded-lg text-xs transition">Close Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADMIN ROUTE EVALUATOR MODAL -->
        <div x-show="activeRouteEvaluation" class="relative z-50" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity"></div>
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl p-6 space-y-6 border border-slate-100" @click.outside="activeRouteEvaluation = null">
                        
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-base font-bold text-navy-900">Intelligent Routing Route Evaluation</h3>
                            <button @click="activeRouteEvaluation = null" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                        </div>

                        <div class="space-y-4" x-data="{ localSimWorkload: [35, 68, 12], isEvaluatingRoute: false }">
                            <div class="bg-slate-50 p-4 rounded-xl space-y-2 text-xs">
                                <h4 class="font-bold text-navy-900">Evaluating Order constraints:</h4>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-slate-500">
                                    <span>Client: <strong class="text-slate-800" x-text="(activeRouteEvaluation || {}).customer"></strong></span>
                                    <span>Job: <strong class="text-slate-800" x-text="(activeRouteEvaluation || {}).service"></strong></span>
                                    <span>Volume: <strong class="text-slate-800" x-text="(activeRouteEvaluation || {}).quantity + ' copies'"></strong></span>
                                    <span>Due Date: <strong class="text-slate-800" x-text="(activeRouteEvaluation || {}).target_date"></strong></span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-navy-950 uppercase tracking-wide">Evaluate Branch Allocation Path</h4>
                                
                                <div class="space-y-3">
                                    <!-- Branch 1 Option -->
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition" :class="simBranchChoice === 'Branch 1' ? 'border-blue-500 bg-blue-50/10' : 'border-slate-200'">
                                        <div class="flex items-start gap-4">
                                            <input type="radio" x-model="simBranchChoice" value="Branch 1" class="sr-only">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs shrink-0" :class="simBranchChoice === 'Branch 1' ? 'border-blue-500 text-blue-600 font-black' : 'border-slate-200 text-slate-400'">1</div>
                                            <div class="flex-grow space-y-1">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span class="font-bold text-navy-900">Branch 1 — Melbourne Warehouse</span>
                                                    <span class="text-slate-400 font-semibold">Queue: 35% load</span>
                                                </div>
                                                <p class="text-[11px] text-red-600 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i> Stock levels: Glossy 250gsm Paper Stock is AT RISK (Only 25 sheets remaining).</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Branch 2 Option -->
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition bg-blue-50/20 border-blue-500 ring-1 ring-blue-500">
                                        <input type="radio" x-model="simBranchChoice" value="Branch 2" class="sr-only">
                                        <div class="flex items-start gap-4">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs border-blue-500 text-blue-600 font-black shrink-0">2</div>
                                            <div class="flex-grow space-y-1">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span class="font-bold text-navy-900">Branch 2 — Sydney CBD Hub (Recommended)</span>
                                                    <span class="text-blue-600 font-black">Queue: 28% load</span>
                                                </div>
                                                <p class="text-[11px] text-emerald-600 font-semibold"><i class="fa-solid fa-circle-check mr-1"></i> Safe routing path. Materials and machine queue ready.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Branch 3 Option -->
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition" :class="simBranchChoice === 'Branch 3' ? 'border-blue-500 bg-blue-50/10' : 'border-slate-200'">
                                        <div class="flex items-start gap-4">
                                            <input type="radio" x-model="simBranchChoice" value="Branch 3" class="sr-only">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs shrink-0" :class="simBranchChoice === 'Branch 3' ? 'border-blue-500 text-blue-600 font-black' : 'border-slate-200 text-slate-400'">3</div>
                                            <div class="flex-grow space-y-1">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span class="font-bold text-navy-900">Branch 3 — Brisbane Factory</span>
                                                    <span class="text-slate-400 font-semibold">Queue: 12% load</span>
                                                </div>
                                                <p class="text-[11px] text-slate-500">Materials and machinery operational. Target date achievable with standard transit delays.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 pt-4">
                                <button @click="activeRouteEvaluation = null" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-4 py-2 rounded-lg text-xs transition">Cancel</button>
                                
                                <button @click="isEvaluatingRoute = true; setTimeout(() => { executeRouting(activeRouteEvaluation, simBranchChoice); isEvaluatingRoute = false; activeRouteEvaluation = null; }, 1500)" :disabled="isEvaluatingRoute" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2 rounded-lg text-xs shadow transition flex items-center gap-2">
                                    <span x-show="isEvaluatingRoute" class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-notch animate-spin"></i> Assigning to Branch...
                                    </span>
                                    <span x-show="!isEvaluatingRoute" x-text="'Assign to ' + simBranchChoice"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function appState() {
            return {
                isLoggedIn: false,
                isLoading: false,
                loadingText: 'Signing in...',
                userName: '',
                userRole: '',
                loginUsername: '',
                loginPassword: '',
                authError: '',
                currentTab: 'customer-dashboard',

                // Active Dialog Details
                activeOrderDetails: null,
                activeRouteEvaluation: null,
                confirmSignout: false,

                // Simulated Branch Choice in Admin Routing
                simBranchChoice: 'Branch 2',

                // Global Lists (Holding State in Client Memory for instantaneous updates)
                availableServices: [
                    { id: 'sc-1', name: 'Flyers & Brochures', description: 'Double-sided glossy promotional items.', icon: 'fa-regular fa-paper-plane' },
                    { id: 'sc-2', name: 'Business Cards', description: 'Matte premium cardstock business cards.', icon: 'fa-regular fa-address-card' },
                    { id: 'sc-3', name: 'Banners & Posters', description: 'Weatherproof vinyl banners and wall posters.', icon: 'fa-regular fa-image' },
                    { id: 'sc-4', name: 'Booklets & Catalogues', description: 'Multi-page booklets with saddle stitch binding.', icon: 'fa-solid fa-book-open' }
                ],

                orders: [
                    { id: 'ORD-8512', customer: 'Morning Star Co.', service: 'Flyers & Brochures', quantity: 250, specs: 'A4 · Glossy 250gsm', target_date: '2026-08-25', branch: 'Branch 2', status: 'ready_for_pickup', estimated_completion: 'Completed' },
                    { id: 'ORD-8513', customer: 'Zenith Logistics', service: 'Business Cards', quantity: 500, specs: 'Standard Cardstock', target_date: '2026-08-28', branch: 'Branch 1', status: 'in_production', estimated_completion: '2026-08-27' },
                    { id: 'ORD-8514', customer: 'Alpha Corp.', service: 'Banners & Posters', quantity: 5, specs: 'Poster size · Vinyl Sheet', target_date: '2026-08-30', branch: 'Branch 3', status: 'paid', estimated_completion: '2026-08-29' }
                ],

                notifications: [
                    { id: 'nt-1', title: 'Quotation Approved', body: 'Payment confirmed for Order ORD-8512. Routed to Branch 2.', time: '2 hours ago', read: false },
                    { id: 'nt-2', title: 'Route Complete', body: 'Order ORD-8512 production is completed and ready for pickup at Branch 2.', time: '1 day ago', read: true }
                ],

                adminNotifications: [
                    { id: 'nt-a1', title: 'Ink Depletion Risk', body: 'Cyan toner supply at Branch 1 has fallen below safety thresholds.', time: '3 hours ago' },
                    { id: 'nt-a2', title: 'Branch Load Threshold', body: 'Branch 2 Sydney hub has exceeded 70% production load due to rush booklets queue.', time: '1 day ago' }
                ],

                inventory: [
                    { code: 'ST-GL250', name: 'Glossy Cover 250gsm (A4)', branch: 'Branch 1', qty: 25, status: 'LIMITS AT RISK' },
                    { code: 'ST-CS300', name: 'Matte Cardstock 300gsm (A4)', branch: 'Branch 2', qty: 2400, status: 'AVAILABLE' },
                    { code: 'ST-VN800', name: 'Heavy Vinyl Sheets (Rolls)', branch: 'Branch 3', qty: 12, status: 'LIMITED' }
                ],

                branches: [
                    { id: 1, name: 'Branch 1', location: 'Melbourne', workload: 35, total_machines: 4, machines_active: 3, staff_active: 8 },
                    { id: 2, name: 'Branch 2', location: 'Sydney CBD', workload: 28, total_machines: 6, machines_active: 4, staff_active: 12 },
                    { id: 3, name: 'Branch 3', location: 'Brisbane', workload: 12, total_machines: 3, machines_active: 1, staff_active: 4 }
                ],

                toasts: [],

                // New Print Form state variables
                printStep: 1,
                formService: 'Flyers & Brochures',
                formServiceId: 'sc-1',
                formQuantity: 100,
                formSize: 'A4',
                formPaper: 'Glossy 250gsm',
                formFinish: 'None',
                formFilename: '',
                formFilesize: '',
                formTargetDate: '',
                formDeliveryMode: 'pickup',
                minDate: '',
                quantityError: '',
                fileError: '',
                dateError: '',
                ordersFilter: 'all',

                // CRM Landing Page State
                showLoginModal: false,
                trackQuery: '',
                isTracking: false,
                trackResult: null,
                trackError: '',
                calcService: 'Tarpaulin Printing',
                calcSize: 'Standard',
                calcQuantity: 100,

                get computedEstimate() {
                    let base = 35; // Default Tarpaulin base
                    if (this.calcService.includes('Sticker')) base = 45;
                    else if (this.calcService.includes('DTF') || this.calcService.includes('T-Shirt')) base = 180;
                    else if (this.calcService.includes('Calling Card')) base = 3.5;
                    else if (this.calcService.includes('Receipt')) base = 25;
                    else if (this.calcService.includes('Bookbinding')) base = 85;
                    else if (this.calcService.includes('Flyer')) base = 8;

                    let sizeMult = 1.0;
                    if (this.calcSize === 'A4') sizeMult = 1.1;
                    else if (this.calcSize === 'A3') sizeMult = 1.6;
                    else if (this.calcSize === '2x3 ft') sizeMult = 2.0;
                    else if (this.calcSize === '3x5 ft') sizeMult = 3.8;

                    return Math.max(150, Math.round(base * sizeMult * (this.calcQuantity || 1)));
                },

                lookupPublicOrder() {
                    if (!this.trackQuery) return;
                    this.isTracking = true;
                    this.trackError = '';
                    this.trackResult = null;

                    fetch('/track-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ order_number: this.trackQuery })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isTracking = false;
                        if (data.success) {
                            this.trackResult = data.order;
                        } else {
                            this.trackError = data.message || 'Order not found. Please verify your order number.';
                        }
                    })
                    .catch(() => {
                        this.isTracking = false;
                        this.trackError = 'Unable to check order status at this moment. Please try again.';
                    });
                },

                init() {
                    const today = new Date();
                    today.setDate(today.getDate() + 2); // Minimum 2 days target window
                    this.minDate = today.toISOString().split('T')[0];
                    this.formTargetDate = this.minDate;
                },

                // Auth Management
                fillLogin(role) {
                    if (role === 'customer') {
                        this.loginUsername = 'customer@capaciprint.com';
                        this.loginPassword = 'password';
                    } else {
                        this.loginUsername = 'admin@capaciprint.com';
                        this.loginPassword = 'password';
                    }
                    this.authError = '';
                },

                handleLogin() {
                    this.authError = '';
                    
                    if (this.loginUsername === 'customer@capaciprint.com' && this.loginPassword === 'password123') {
                        this.triggerAuthSim('Nieressa', 'customer', 'customer-dashboard');
                    } else if (this.loginUsername === 'admin@capaciprint.com' && this.loginPassword === 'password123') {
                        this.triggerAuthSim('Admin Manager', 'admin', 'admin-dashboard');
                    } else {
                        this.authError = 'Invalid username or password. Please try again.';
                    }
                },

                triggerAuthSim(name, role, defaultTab) {
                    this.isLoading = true;
                    this.loadingText = 'Signing in...';
                    
                    setTimeout(() => {
                        this.isLoggedIn = true;
                        this.isLoading = false;
                        this.userName = name;
                        this.userRole = role;
                        this.currentTab = defaultTab;
                        this.triggerToast('Welcome Back', 'Log in session established successfully.');
                    }, 1000);
                },

                signOut() {
                    this.isLoading = true;
                    this.loadingText = 'Signing out...';
                    setTimeout(() => {
                        this.isLoggedIn = false;
                        this.isLoading = false;
                        this.confirmSignout = false;
                        this.loginPassword = '';
                        this.authError = '';
                    }, 800);
                },

                getInitials(name) {
                    if (!name) return 'US';
                    const parts = name.split(' ');
                    return parts.map(p => p[0]).join('').toUpperCase();
                },

                // Toast alerts
                triggerToast(title, message) {
                    const id = Date.now();
                    this.toasts.push({ id, title, message });
                },

                removeToast(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                },

                // Tracker timeline calculations
                getlatestOrder() {
                    if (this.userRole === 'customer') {
                        const custOrders = this.orders.filter(o => o.customer === 'Morning Star Co.');
                        return custOrders[custOrders.length - 1] || {};
                    }
                    return {};
                },

                get latestOrder() {
                    return this.getlatestOrder();
                },

                getProgressPercent(status) {
                    switch (status) {
                        case 'submitted': return 0;
                        case 'quotation_ready': return 25;
                        case 'paid': return 50;
                        case 'in_production': return 75;
                        case 'ready_for_pickup': return 100;
                        default: return 0;
                    }
                },

                getStatusStepClass(currentStatus, stepNum) {
                    const currentProgress = this.getProgressPercent(currentStatus);
                    let stepPercent = 0;
                    if (stepNum === 1) stepPercent = 0;
                    else if (stepNum === 2) stepPercent = 25;
                    else if (stepNum === 3) stepPercent = 50;
                    else if (stepNum === 4) stepPercent = 75;
                    else if (stepNum === 5) stepPercent = 100;

                    if (currentProgress > stepPercent) {
                        return 'bg-blue-500 border-blue-600 text-white'; // Completed steps
                    } else if (currentProgress === stepPercent) {
                        if (currentStatus === 'quotation_ready') return 'bg-amber-500 border-amber-600 text-white ring-4 ring-amber-100';
                        return 'bg-blue-500 border-blue-600 text-white ring-4 ring-blue-100'; // Active step
                    } else {
                        return 'bg-slate-100 border-slate-200 text-slate-400'; // Upcoming steps
                    }
                },

                getStatusLabel(status) {
                    switch (status) {
                        case 'submitted': return 'Submitted';
                        case 'quotation_ready': return 'Quotation Ready';
                        case 'paid': return 'Payment Confirmed';
                        case 'in_production': return 'In Production';
                        case 'ready_for_pickup': return 'Ready for Pickup';
                        default: return 'Pending';
                    }
                },

                getStatusBadgeClass(status) {
                    switch (status) {
                        case 'submitted': return 'bg-sky-100 text-sky-800 border border-sky-200';
                        case 'quotation_ready': return 'bg-amber-100 text-amber-800 border border-amber-200';
                        case 'paid': return 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                        case 'in_production': return 'bg-blue-100 text-blue-800 border border-blue-200';
                        case 'ready_for_pickup': return 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                        default: return 'bg-slate-100 text-slate-800';
                    }
                },

                getStatusDescription(status) {
                    switch (status) {
                        case 'submitted': return 'Your print request is currently undergoing automated routing and resource capacity analysis.';
                        case 'quotation_ready': return 'A formal quotation has been generated. Please review itemized pricing and approve to initiate printing.';
                        case 'paid': return 'Deposit verified. Production ticket has been routed to the assigned branch queue.';
                        case 'in_production': return 'Machines are active. Printing and cutting operations are underway.';
                        case 'ready_for_pickup': return 'Quality check passed. Your items are boxed and ready at the claims counter.';
                        default: return '';
                    }
                },

                // Counts
                get customerOrdersCount() {
                    return this.orders.filter(o => o.customer === 'Morning Star Co.').length;
                },

                get customerQuotesCount() {
                    return this.orders.filter(o => o.customer === 'Morning Star Co.' && o.status === 'quotation_ready').length;
                },

                get customerActiveOrdersCount() {
                    return this.orders.filter(o => o.customer === 'Morning Star Co.' && o.status !== 'ready_for_pickup' && o.status !== 'completed').length;
                },

                get customerCompletedOrdersCount() {
                    return this.orders.filter(o => o.customer === 'Morning Star Co.' && o.status === 'ready_for_pickup').length; // ready is treated as claiming state
                },

                get adminOrdersCount() {
                    return this.orders.length;
                },

                getUnreadCount() {
                    return this.notifications.filter(n => !n.read).length;
                },

                get unreadCount() {
                    return this.getUnreadCount();
                },

                markAllAsRead() {
                    this.notifications.forEach(n => n.read = true);
                    this.triggerToast('Notifications', 'All messages marked as read.');
                },

                // Form management
                resetPrintForm() {
                    this.printStep = 1;
                    this.formService = 'Flyers & Brochures';
                    this.formServiceId = 'sc-1';
                    this.formQuantity = 100;
                    this.formSize = 'A4';
                    this.formPaper = 'Glossy 250gsm';
                    this.formFinish = 'None';
                    this.formFilename = '';
                    this.formFilesize = '';
                    const today = new Date();
                    today.setDate(today.getDate() + 2);
                    this.formTargetDate = today.toISOString().split('T')[0];
                    this.formDeliveryMode = 'pickup';
                    
                    this.quantityError = '';
                    this.fileError = '';
                    this.dateError = '';
                },

                validateStep2() {
                    this.quantityError = '';
                    if (!this.formQuantity || this.formQuantity < 10) {
                        this.quantityError = 'Please enter a valid quantity of at least 10 copies.';
                        return false;
                    }
                    return true;
                },

                validateStep3() {
                    this.fileError = '';
                    if (!this.formFilename) {
                        this.fileError = 'Please upload an artwork PDF or image file before continuing.';
                        return false;
                    }
                    return true;
                },

                validateStep4() {
                    this.dateError = '';
                    if (!this.formTargetDate) {
                        this.dateError = 'Please select a target delivery date.';
                        return false;
                    }
                    const selected = new Date(this.formTargetDate);
                    const min = new Date(this.minDate);
                    if (selected < min) {
                        this.dateError = 'Selected date violates minimal 48-hour production requirements.';
                        return false;
                    }
                    return true;
                },

                handleFileUpload(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.formFilename = file.name;
                        this.formFilesize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        this.fileError = '';
                        this.triggerToast('File Uploaded', 'Artwork attachment added successfully.');
                    }
                },

                getEstimatedCost() {
                    let baseRate = 0.5; // per copy A4
                    if (this.formSize === 'Letter') baseRate = 0.45;
                    else if (this.formSize === 'A5') baseRate = 0.35;
                    else if (this.formSize === 'Poster Size') baseRate = 5.0;

                    if (this.formPaper.includes('Glossy')) baseRate *= 1.25;
                    else if (this.formPaper.includes('Cardstock')) baseRate *= 1.5;
                    else if (this.formPaper.includes('Vinyl')) baseRate *= 2.0;

                    return Math.ceil(this.formQuantity * baseRate);
                },

                prevStep() {
                    if (this.printStep > 1) this.printStep--;
                },

                nextStep() {
                    if (this.printStep === 2 && !this.validateStep2()) return;
                    if (this.printStep === 3 && !this.validateStep3()) return;
                    if (this.printStep === 4 && !this.validateStep4()) return;

                    if (this.printStep < 5) {
                        this.printStep++;
                    } else {
                        // Submit logic
                        this.submitPrintRequest();
                    }
                },

                submitPrintRequest() {
                    this.isLoading = true;
                    this.loadingText = 'Evaluating capacity & routing...';
                    
                    // Simulate routing engine analysis
                    setTimeout(() => {
                        const newId = 'ORD-' + Math.floor(Math.random() * 9000 + 1000);
                        const newOrder = {
                            id: newId,
                            customer: 'Morning Star Co.',
                            service: this.formService,
                            quantity: this.formQuantity,
                            specs: this.formSize + ' · ' + this.formPaper + ' · ' + this.formFinish,
                            target_date: this.formTargetDate,
                            branch: 'Branch 2', // Auto-routed Sydney
                            status: 'quotation_ready',
                            estimated_completion: this.formTargetDate
                        };
                        
                        this.orders.push(newOrder);
                        
                        this.notifications.unshift({
                            id: 'nt-' + Date.now(),
                            title: 'New Quotation Generated',
                            body: `Your request ${newId} was auto-routed to Branch 2. Quotation ready for approval.`,
                            time: 'Just now',
                            read: false
                        });

                        this.isLoading = false;
                        this.currentTab = 'customer-dashboard';
                        this.triggerToast('Request Submitted', 'Print request routed successfully.');
                    }, 1500);
                },

                // Quote actions
                getQuotes() {
                    return this.orders.filter(o => o.customer === 'Morning Star Co.' && o.status === 'quotation_ready');
                },

                getPrice(order) {
                    // Quick calculation based on quantity
                    return Math.ceil(order.quantity * 0.75);
                },

                approveAndPay(order) {
                    this.isLoading = true;
                    this.loadingText = 'Processing deposit confirmation...';
                    setTimeout(() => {
                        order.status = 'paid';
                        this.isLoading = false;
                        this.triggerToast('Payment Confirmed', 'Production ticket dispatched to branch.');
                    }, 1200);
                },

                rejectQuote(order) {
                    if (confirm('Are you sure you want to reject this quotation? The request will be cancelled.')) {
                        this.orders = this.orders.filter(o => o.id !== order.id);
                        this.triggerToast('Quotation Cancelled', 'Request removed from system.');
                    }
                },

                // Filters
                getFilteredOrders(filter) {
                    const custOrders = this.orders.filter(o => o.customer === 'Morning Star Co.');
                    if (filter === 'all') return custOrders;
                    return custOrders.filter(o => o.status === filter);
                },

                // Claims and Pickup popup
                showClaimInstructions(order) {
                    this.activeOrderDetails = order;
                },

                showOrderDetails(order) {
                    this.activeOrderDetails = order;
                },

                // Admin helpers
                getAutoRecommendation(order) {
                    // Sydney CBD holds highest performance rating for Brochures / Cards
                    if (order.service.includes('Banners')) return 'Branch 3 (Brisbane)';
                    return 'Branch 2 (Sydney)';
                },

                routeOrderModal(order) {
                    this.activeRouteEvaluation = order;
                    this.simBranchChoice = 'Branch 2';
                },

                executeRouting(order, selectedBranch) {
                    order.branch = selectedBranch;
                    order.status = 'quotation_ready';
                    this.triggerToast('Routing Engine', `${order.id} allocated to ${selectedBranch}.`);
                },

                showAdminOrderDetails(order) {
                    this.triggerToast('Order Information', `Details for ${order.id} loaded.`);
                },

                // Production ticket milestone advances
                getJobsCountAtBranch(branchName) {
                    return this.orders.filter(o => o.branch.includes(branchName) && o.status !== 'ready_for_pickup' && o.status !== 'completed').length;
                },

                getJobsAtBranch(branchName) {
                    return this.orders.filter(o => o.branch.includes(branchName) && o.status !== 'ready_for_pickup' && o.status !== 'completed');
                },

                getProductionPercent(status) {
                    switch (status) {
                        case 'paid': return 10;
                        case 'in_production': return 65;
                        default: return 0;
                    }
                },

                advanceJobStatus(job) {
                    this.isLoading = true;
                    this.loadingText = 'Advancing work ticket...';
                    
                    setTimeout(() => {
                        this.isLoading = false;
                        if (job.status === 'paid') {
                            job.status = 'in_production';
                            this.triggerToast('Production', `Job ticket ${job.id} shifted to print line.`);
                        } else if (job.status === 'in_production') {
                            job.status = 'ready_for_pickup';
                            this.triggerToast('Production', `Job ticket ${job.id} completed. Claims counter notified.`);
                        }
                    }, 1000);
                },

                reorderStock(item) {
                    this.isLoading = true;
                    this.loadingText = 'Submitting purchase order...';
                    setTimeout(() => {
                        item.qty += 1000;
                        item.status = 'AVAILABLE';
                        this.isLoading = false;
                        this.triggerToast('Inventory Management', `Stock reordered for ${item.code}. Quantity replenished.`);
                    }, 1200);
                },

                simulateBranchMaint(branch) {
                    this.triggerToast('Branch Operations', `Triggered diagnostics routines on active machinery queue at ${branch.name}.`);
                },

                getInventoryStatusClass(status) {
                    if (status.includes('LIMITS') || status.includes('RISK')) return 'bg-red-100 text-red-800 border border-red-200';
                    if (status.includes('LIMITED')) return 'bg-amber-100 text-amber-800 border border-amber-200';
                    return 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                },

                getTabTitle(tab) {
                    return tab.replace('customer-', '').replace('admin-', '').replace('-', ' ');
                }
            };
        }

        // Dedicated state controller for Planning Capacity simulator to prevent reactive locks
        function planningSimulator() {
            return {
                simWorkloads: [35, 28, 12],
                simPaper: 'cardstock',
                simUrgent: false,

                getRecommendation() {
                    const w1 = this.simWorkloads[0];
                    const w2 = this.simWorkloads[1];
                    const w3 = this.simWorkloads[2];

                    // Heuristics routing engine
                    let recommendedIdx = 2; // Default to lowest workload Brisbane (Branch 3)
                    
                    if (this.simPaper === 'glossy') {
                        // Branch 1 is short on Glossy, bypass it!
                        if (w2 <= w3) recommendedIdx = 1;
                        else recommendedIdx = 2;
                    } else {
                        // Load balance check
                        if (w1 <= w2 && w1 <= w3) recommendedIdx = 0;
                        else if (w2 <= w1 && w2 <= w3) recommendedIdx = 1;
                        else recommendedIdx = 2;
                    }

                    // Compute checks for recommended branch
                    const activeWorkload = this.simWorkloads[recommendedIdx];
                    const paperOk = !(this.simPaper === 'glossy' && recommendedIdx === 0);
                    
                    return {
                        branchId: recommendedIdx + 1,
                        machineAvailable: activeWorkload < 80,
                        materialsAvailable: paperOk,
                        employeesAvailable: activeWorkload < 90,
                        workloadAcceptable: activeWorkload < 75,
                        deadlineAchievable: this.simUrgent ? (activeWorkload < 50) : true
                    };
                }
            };
        }

        // Dual-Feed High-Resolution Background Video Controller
        function heroVideoController() {
            return {
                currentFeed: 1,
                isTransitioning: false,

                init() {
                    this.$nextTick(() => {
                        const v1 = document.getElementById('hero-video-1');
                        const v2 = document.getElementById('hero-video-2');

                        if (v1) {
                            v1.muted = true;
                            const playPromise = v1.play();
                            if (playPromise !== undefined) {
                                playPromise.catch(() => {
                                    v1.muted = true;
                                    v1.play().catch(() => {});
                                });
                            }

                            // Trigger crossfade when video 1 reaches end
                            v1.addEventListener('ended', () => {
                                this.setFeed(2);
                            });

                            // Smooth fallback crossfade 0.35s before video completes
                            v1.addEventListener('timeupdate', () => {
                                if (v1.duration && v1.currentTime >= v1.duration - 0.35) {
                                    if (this.currentFeed === 1 && !this.isTransitioning) {
                                        this.setFeed(2);
                                    }
                                }
                            });
                        }

                        if (v2) {
                            v2.muted = true;
                            // Pre-buffer video 2 so transition is instantaneous
                            v2.load();

                            // Trigger crossfade when video 2 reaches end
                            v2.addEventListener('ended', () => {
                                this.setFeed(1);
                            });

                            // Smooth fallback crossfade 0.35s before video completes
                            v2.addEventListener('timeupdate', () => {
                                if (v2.duration && v2.currentTime >= v2.duration - 0.35) {
                                    if (this.currentFeed === 2 && !this.isTransitioning) {
                                        this.setFeed(1);
                                    }
                                }
                            });
                        }
                    });
                },

                setFeed(feedNumber) {
                    if (this.currentFeed === feedNumber && !this.isTransitioning) return;
                    this.currentFeed = feedNumber;
                    this.isTransitioning = true;

                    const targetVid = document.getElementById(feedNumber === 1 ? 'hero-video-1' : 'hero-video-2');
                    if (targetVid) {
                        targetVid.currentTime = 0;
                        const p = targetVid.play();
                        if (p !== undefined) {
                            p.catch(() => {});
                        }
                    }

                    setTimeout(() => {
                        this.isTransitioning = false;
                    }, 1100);
                }
            };
        }
    </script>
</body>
</html>
