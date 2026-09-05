<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CAPACIPRINT — Intelligent Print Routing</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite with CDN Fallback for safety) -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for interactive state -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Hide Microsoft Edge native password reveal icon to prevent duplicate icons */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50:  '#f2f5f8',
                            100: '#dde5ed',
                            200: '#bccadb',
                            300: '#92a9be',
                            400: '#6685a0',
                            500: '#4e6a84',   // Logo mid-slate
                            600: '#3d5068',   // Logo primary dark (outer ring)
                            700: '#2e3d50',
                            800: '#1f2c3a',   // Deep slate
                            900: '#141c26',   // Darkest panel bg
                            950: '#0b1016',
                        },
                        brand: {
                            50:  '#e8f7fd',
                            100: '#c3ebf9',
                            200: '#8bd9f5',
                            300: '#4dc6ef',
                            400: '#29bce8',   // Logo sky-blue accent (bright)
                            500: '#15a9d6',   // Slightly deeper for hover
                            600: '#0e8db5',
                            700: '#0c7296',
                            800: '#0b5a76',
                            900: '#0a4a60',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Outfit', sans-serif; }
        .font-sans { font-family: 'Inter', sans-serif; }

        /* ── Brand color variables from logo ── */
        :root {
            --brand-dark:   #1f2c3a;   /* deep slate panel bg          */
            --brand-mid:    #3d5068;   /* logo outer ring / nav        */
            --brand-blue:   #29bce8;   /* logo sky-blue accent         */
            --brand-blue-d: #15a9d6;   /* darker sky-blue hover        */
        }

        /* Login page animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(41,188,232,0); }
            50%       { box-shadow: 0 0 32px 10px rgba(41,188,232,0.18); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        .animate-fade-in-up   { animation: fadeInUp 0.6s ease both; }
        .animate-float        { animation: float 4s ease-in-out infinite; }
        .animate-pulse-glow   { animation: pulseGlow 3s ease-in-out infinite; }

        /* Left panel: slate-steel navy matching logo outer ring */
        .login-panel-left {
            background: linear-gradient(145deg, #141c26 0%, #2e3d50 45%, #1f2c3a 100%);
        }
        .login-dot-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* Feature badges: sky-blue tint from logo accent */
        .feature-badge {
            background: rgba(41,188,232,0.10);
            border: 1px solid rgba(41,188,232,0.22);
            backdrop-filter: blur(4px);
        }
        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #29bce8;
            box-shadow: 0 0 0 3px rgba(41,188,232,0.14);
            outline: none;
        }

        /* CTA button: logo sky-blue gradient */
        .btn-primary {
            background: linear-gradient(135deg, #15a9d6 0%, #29bce8 100%);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0e8db5 0%, #15a9d6 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 22px rgba(41,188,232,0.40);
        }
        .btn-primary:active { transform: translateY(0); }
        .demo-btn {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s;
        }
        .demo-btn:hover {
            border-color: #29bce8;
            background: rgba(41,188,232,0.06);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(41,188,232,0.12);
        }

        /* Sidebar uses brand-mid slate matching logo outer ring */
        aside, .mobile-sidebar {
            background-color: var(--brand-mid) !important;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="appState()" x-cloak>

    <!-- 1. LOGIN SCREEN — Centered Card Layout -->
    <div x-show="!isLoggedIn" class="flex min-h-screen items-center justify-center bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-50/50 via-slate-50 to-slate-100 relative overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Centered Login Card -->
        <div class="w-full max-w-md bg-white rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100/60 p-8 sm:p-10 relative z-10 animate-fade-in-up">
            
            <!-- Logo & Branding -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT Logo" class="h-36 w-auto object-contain mix-blend-multiply brightness-[1.08] contrast-[1.15]">
            </div>

            <!-- Error Alert -->
            <div x-show="authError" class="mb-6 flex items-start gap-3 p-3.5 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-red-500"></i>
                <span x-text="authError"></span>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="handleLogin" class="space-y-6" autocomplete="off">
                
                <!-- Username -->
                <div>
                    <label for="usr_field" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                    <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-4 focus-within:ring-brand-400/15 transition-all bg-white shadow-sm">
                        <span class="flex items-center justify-center w-12 border-r border-slate-200 text-slate-600 bg-white shrink-0">
                            <i class="fa-solid fa-user text-lg"></i>
                        </span>
                        <input id="usr_field" name="usr_fake_name" type="text" x-model="loginUsername" required placeholder="" onfocus="this.placeholder='Enter your username'" onblur="this.placeholder=''" autocomplete="off" data-lpignore="true" class="flex-1 py-3 px-4 text-sm font-medium text-slate-800 placeholder-slate-400 bg-transparent border-none focus:ring-0 focus:outline-none">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="pwd_field" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-4 focus-within:ring-brand-400/15 transition-all shadow-sm" x-data="{ show: false }">
                        <span class="flex items-center justify-center w-12 border-r border-slate-200 text-slate-600 bg-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="11" width="16" height="10" rx="2.5"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input id="pwd_field" name="pwd_fake_name" :type="show ? 'text' : 'password'" x-model="loginPassword" required placeholder="" onfocus="this.placeholder='Enter your password'" onblur="this.placeholder=''" autocomplete="new-password" data-lpignore="true" class="flex-1 py-3 px-4 text-sm font-medium text-slate-800 placeholder-slate-400 bg-slate-50 border-none focus:ring-0 focus:outline-none">
                        <button type="button" @click="show = !show" class="flex items-center justify-center w-12 text-slate-500 hover:text-brand-500 transition-colors bg-white border-l border-slate-200 shrink-0">
                            <i class="fa-solid text-lg" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" :disabled="isLoading" class="w-full flex justify-center items-center gap-2 bg-gradient-to-r from-brand-400 to-brand-500 hover:from-brand-500 hover:to-brand-600 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-brand-500/25 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
                    <template x-if="isLoading">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-notch animate-spin"></i>
                            <span x-text="loadingText">Signing in...</span>
                        </span>
                    </template>
                    <template x-if="!isLoading">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            Sign In
                        </span>
                    </template>
                </button>
            </form>

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
                    <span class="text-[9px] text-brand-400 tracking-widest font-semibold uppercase">Routing System</span>
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
                            <span class="ml-auto inline-block py-0.5 px-2 text-[10px] font-bold rounded-full bg-brand-400 text-navy-950" x-text="customerOrdersCount">0</span>
                        </button>
                        <button @click="currentTab = 'customer-quotations'" :class="currentTab === 'customer-quotations' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-file-invoice-dollar w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Quotations</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[10px] font-bold rounded-full bg-amber-500 text-navy-950" x-text="customerQuotesCount">0</span>
                        </button>
                        <button @click="currentTab = 'customer-notifications'" :class="currentTab === 'customer-notifications' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-bell w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Notifications</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[10px] font-bold rounded-full bg-brand-400 text-navy-950" x-show="unreadCount > 0" x-text="unreadCount">0</span>
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
                            <span class="ml-auto inline-block py-0.5 px-2 text-[10px] font-bold rounded-full bg-brand-400 text-navy-950" x-text="adminOrdersCount">0</span>
                        </button>
                        <button @click="currentTab = 'admin-production'" :class="currentTab === 'admin-production' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-industry w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Production</span>
                        </button>
                        <button @click="currentTab = 'admin-planning'" :class="currentTab === 'admin-planning' ? 'bg-brand-500/20 text-brand-400' : 'text-slate-300 hover:bg-navy-700 hover:text-white'" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-md transition-all">
                            <i class="fa-solid fa-brain w-5 mr-3 text-lg text-slate-400 group-hover:text-white transition"></i>
                            <span>Capacity Planning</span>
                            <span class="ml-auto inline-block py-0.5 px-2 text-[10px] font-bold rounded-full bg-brand-400 text-navy-950">AI</span>
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
                            <span class="text-[9px] text-brand-400 tracking-widest font-semibold uppercase">Routing System</span>
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
                        <i class="fa-solid fa-chevron-right text-[10px] mx-2 text-slate-300"></i>
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
                                <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="bg-cyan-500 hover:bg-cyan-600 text-navy-950 font-bold px-5 py-2.5 rounded-lg text-sm transition-all shadow flex items-center gap-2">
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
                            <div class="h-12 w-12 bg-cyan-50 text-cyan-600 rounded-lg flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-boxes-stacked"></i></div>
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
                            <h3 class="font-bold text-navy-900 flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-cyan-600"></i> Track Latest Order Progress</h3>
                            <span class="text-xs text-slate-400 font-medium">Order ID: <strong x-text="latestOrder.id"></strong></span>
                        </div>
                        <div class="p-6">
                            <!-- Empty state if no orders -->
                            <div x-show="!latestOrder.id" class="text-center py-8">
                                <i class="fa-solid fa-circle-question text-slate-300 text-4xl mb-2"></i>
                                <h4 class="font-bold text-navy-900">No active orders</h4>
                                <p class="text-sm text-slate-500 mt-1">You currently have no orders in production.</p>
                                <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="mt-4 bg-cyan-600 hover:bg-cyan-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Submit Print Request</button>
                            </div>

                            <div x-show="latestOrder.id" class="space-y-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                                    <div>
                                        <h4 class="text-lg font-bold text-navy-900" x-text="latestOrder.service"></h4>
                                        <p class="text-xs text-slate-500 mt-1" x-text="'Specs: ' + latestOrder.quantity + ' copies · ' + latestOrder.specs"></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-slate-500">Routing Branch:</span>
                                        <span class="px-2.5 py-1 text-xs font-bold rounded bg-cyan-100 text-cyan-800" x-text="latestOrder.branch || 'Pending Routing'"></span>
                                    </div>
                                </div>

                                <!-- Tracker Progress Bar -->
                                <div class="py-4">
                                    <div class="relative flex items-center justify-between">
                                        <!-- Connection Line -->
                                        <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 z-0"></div>
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-cyan-500 z-0 transition-all duration-500" :style="'width: ' + getProgressPercent(latestOrder.status) + '%'"></div>

                                        <!-- Step 1 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 1)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-file-arrow-up"></i>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold text-navy-900 mt-2">Submitted</span>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 2)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold text-navy-900 mt-2">Quote Ready</span>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 3)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-credit-card"></i>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold text-navy-900 mt-2">Paid</span>
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 4)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-gears animate-spin" x-show="latestOrder.status === 'in_production'"></i>
                                                <i class="fa-solid fa-industry" x-show="latestOrder.status !== 'in_production'"></i>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold text-navy-900 mt-2">Producing</span>
                                        </div>

                                        <!-- Step 5 -->
                                        <div class="relative z-10 flex flex-col items-center">
                                            <div :class="getStatusStepClass(latestOrder.status, 5)" class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all">
                                                <i class="fa-solid fa-truck-ramp-box"></i>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold text-navy-900 mt-2">Ready</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Status Description Card -->
                                <div class="mt-4 p-4 rounded-lg bg-slate-50 border border-slate-100 flex items-start gap-4">
                                    <div class="p-2.5 bg-white border border-slate-150 rounded-lg text-lg text-cyan-600 shadow-sm shrink-0">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <div class="flex-grow space-y-1">
                                        <h5 class="text-sm font-bold text-navy-900">
                                            Current Status: <span class="uppercase text-cyan-600" x-text="getStatusLabel(latestOrder.status)"></span>
                                        </h5>
                                        <p class="text-xs text-slate-500" x-text="getStatusDescription(latestOrder.status)"></p>
                                        <div class="pt-2 text-[11px] font-semibold text-navy-800 flex flex-wrap gap-x-6 gap-y-1">
                                            <span x-show="latestOrder.estimated_completion"><i class="fa-regular fa-calendar-check text-cyan-600 mr-1"></i> Est. Completion: <span class="text-slate-600 font-normal" x-text="latestOrder.estimated_completion"></span></span>
                                            <span x-show="latestOrder.branch"><i class="fa-solid fa-building-circle-check text-cyan-600 mr-1"></i> Location: <span class="text-slate-600 font-normal" x-text="latestOrder.branch"></span></span>
                                        </div>
                                    </div>
                                    <!-- Action button inside tracker if payment is pending -->
                                    <div class="shrink-0" x-show="latestOrder.status === 'quotation_ready'">
                                        <button @click="currentTab = 'customer-quotations'" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-3 py-1.5 rounded text-xs transition font-semibold">Approve & Pay</button>
                                    </div>
                                    <!-- Claiming Info if ready -->
                                    <div class="shrink-0" x-show="latestOrder.status === 'ready_for_pickup'">
                                        <button @click="showClaimInstructions(latestOrder)" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-3 py-1.5 rounded text-xs transition font-semibold">Claim Instructions</button>
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
                        <div :class="printStep >= 1 ? 'text-cyan-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 1 ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : (printStep > 1 ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">1</span>
                            <span class="hidden sm:inline">Service</span>
                        </div>
                        <div :class="printStep >= 2 ? 'text-cyan-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 2 ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : (printStep > 2 ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">2</span>
                            <span class="hidden sm:inline">Specifications</span>
                        </div>
                        <div :class="printStep >= 3 ? 'text-cyan-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 3 ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : (printStep > 3 ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">3</span>
                            <span class="hidden sm:inline">Artwork</span>
                        </div>
                        <div :class="printStep >= 4 ? 'text-cyan-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 4 ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : (printStep > 4 ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-100 text-slate-400')" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">4</span>
                            <span class="hidden sm:inline">Schedule</span>
                        </div>
                        <div :class="printStep >= 5 ? 'text-cyan-600' : ''" class="flex flex-col items-center">
                            <span :class="printStep === 5 ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : 'bg-slate-100 text-slate-400'" class="h-8 w-8 rounded-full flex items-center justify-center mb-1 text-sm border font-bold">5</span>
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
                                    <div @click="formService = service.name; formServiceId = service.id" :class="formService === service.name ? 'border-cyan-500 bg-cyan-50/20 shadow-md ring-1 ring-cyan-500' : 'border-slate-200 hover:border-slate-300'" class="border p-5 rounded-xl cursor-pointer transition flex items-start gap-4">
                                        <div class="h-10 w-10 bg-cyan-500/10 text-cyan-500 border border-cyan-500/20 rounded-lg flex items-center justify-center text-lg shrink-0">
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
                                    <input type="number" x-model.number="formQuantity" @input="validateStep2" min="1" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                    <p x-show="quantityError" class="text-red-600 text-xs mt-1.5 font-semibold" x-text="quantityError"></p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Size / Dimensions <span class="text-red-500">*</span></label>
                                    <select x-model="formSize" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                        <option value="A4">A4 (210 x 297 mm)</option>
                                        <option value="A5">A5 (148 x 210 mm)</option>
                                        <option value="Letter">Letter (8.5 x 11 in)</option>
                                        <option value="Poster Size">Poster size (18 x 24 in)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Paper / Material Type <span class="text-red-500">*</span></label>
                                    <select x-model="formPaper" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                        <option value="Matte 100gsm">Matte 100gsm (Lightweight)</option>
                                        <option value="Glossy 250gsm">Glossy 250gsm (Premium Cover)</option>
                                        <option value="Cardstock 300gsm">Cardstock 300gsm (Heavyweight)</option>
                                        <option value="Vinyl Sheet">Vinyl Sheet (Weatherproof Banners)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Finishing Options</label>
                                    <select x-model="formFinish" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
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
                            <div class="border-2 border-dashed border-slate-200 hover:border-cyan-400 rounded-xl p-8 text-center transition bg-slate-50/50 cursor-pointer relative" @click="$refs.fileInput.click()">
                                <input type="file" x-ref="fileInput" class="hidden" @change="handleFileUpload">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-3 block"></i>
                                <p class="text-sm font-bold text-navy-900">Drag & drop your files here, or <span class="text-cyan-600">browse</span></p>
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
                                    <input type="date" x-model="formTargetDate" @change="validateStep4" :min="minDate" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                                    <p x-show="dateError" class="text-red-600 text-xs mt-1.5 font-semibold" x-text="dateError"></p>
                                    <p class="text-[10px] text-slate-400 mt-1.5">Minimum 2-day production window required for standard jobs.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Collection Mode <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-3 mt-1">
                                        <label :class="formDeliveryMode === 'pickup' ? 'border-cyan-500 bg-cyan-50/20' : 'border-slate-200'" class="border p-3 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="formDeliveryMode" value="pickup" class="sr-only">
                                            <i class="fa-solid fa-store text-navy-800 mb-1"></i>
                                            <span class="block text-xs font-bold text-navy-900">Branch Pickup</span>
                                        </label>
                                        <label :class="formDeliveryMode === 'shipping' ? 'border-cyan-500 bg-cyan-50/20' : 'border-slate-200'" class="border p-3 rounded-lg text-center cursor-pointer block hover:border-slate-300">
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
                            <div class="p-4 bg-cyan-50/50 border border-cyan-150 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-tags text-cyan-600 text-lg"></i>
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

                            <button @click="nextStep" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-6 py-2 rounded-lg text-sm transition-all shadow flex items-center gap-2">
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
                        <button @click="currentTab = 'new-print-request'; resetPrintForm()" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow flex items-center gap-2">
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
                                                <button @click="showOrderDetails(order)" class="text-cyan-600 hover:text-cyan-800 font-bold text-xs">View Order Details</button>
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
                                        <span :class="getStatusBadgeClass(order.status)" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" x-text="getStatusLabel(order.status)"></span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-800" x-text="order.service"></h4>
                                        <p class="text-xs text-slate-500 mt-0.5" x-text="'Qty: ' + order.quantity + ' · Date: ' + order.target_date"></p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-100/50 text-xs">
                                        <span class="text-slate-400" x-text="'Branch: ' + (order.branch || 'Routing...')"></span>
                                        <button @click="showOrderDetails(order)" class="text-cyan-600 hover:text-cyan-800 font-bold">View Order Details</button>
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
                                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Quotation No.</span>
                                            <h4 class="text-base font-bold text-navy-900" x-text="'QT-' + order.id.slice(3)"></h4>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black bg-amber-100 text-amber-800 uppercase">Awaiting Action</span>
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
                                    <button @click="approveAndPay(order)" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 rounded text-xs transition shadow">Approve & Pay</button>
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
                        <button @click="markAllAsRead" x-show="unreadCount > 0" class="text-xs text-cyan-600 hover:text-cyan-800 font-semibold font-semibold">Mark all as read</button>
                    </div>

                    <div class="bg-white border border-slate-150 rounded-xl overflow-hidden shadow-sm divide-y divide-slate-100">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div :class="notif.read ? 'bg-white' : 'bg-cyan-50/10'" class="p-4 flex items-start gap-4 hover:bg-slate-50/50 transition">
                                <div class="p-2 bg-slate-50 text-cyan-600 rounded-lg shrink-0">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-sm font-bold text-navy-900" x-text="notif.title"></h4>
                                        <span class="text-[10px] text-slate-400" x-text="notif.time"></span>
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
                            <div class="h-16 w-16 bg-cyan-600 rounded-full flex items-center justify-center text-white text-2xl font-bold font-display" x-text="getInitials(userName)"></div>
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
                            <div class="flex justify-between text-slate-400"><span class="text-[10px] font-bold uppercase tracking-wider">Today's Orders</span><i class="fa-solid fa-calendar-day"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display" x-text="adminOrdersCount">0</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1">
                            <div class="flex justify-between text-slate-400"><span class="text-[10px] font-bold uppercase tracking-wider">Active Production</span><i class="fa-solid fa-gears text-cyan-600"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display">12</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1 border-l-4 border-l-red-500">
                            <div class="flex justify-between text-slate-400"><span class="text-[10px] font-bold uppercase tracking-wider text-red-600">Rush Orders</span><i class="fa-solid fa-fire-flame-curved text-red-500"></i></div>
                            <h3 class="text-2xl font-bold text-red-600 font-display">3</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1 border-l-4 border-l-amber-500">
                            <div class="flex justify-between text-slate-400"><span class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Delayed Jobs</span><i class="fa-solid fa-triangle-exclamation text-amber-500"></i></div>
                            <h3 class="text-2xl font-bold text-amber-600 font-display">1</h3>
                        </div>
                        <div class="bg-white p-5 border border-slate-100 rounded-xl shadow-sm space-y-1">
                            <div class="flex justify-between text-slate-400"><span class="text-[10px] font-bold uppercase tracking-wider">Branch Capacity</span><i class="fa-solid fa-chart-pie"></i></div>
                            <h3 class="text-2xl font-bold text-navy-900 font-display">64%</h3>
                        </div>
                    </div>

                    <!-- Priority Actions Section -->
                    <div class="bg-white border border-slate-150 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/60 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-navy-900 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Action Queue (Requires Attention)</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Critical Priority</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <!-- Shortage Alert -->
                            <div class="p-4 flex items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-9 w-9 bg-red-50 text-red-600 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-box-open"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm text-navy-900">Inventory Supply Shortage <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-red-100 text-red-800 uppercase ml-1.5">AT RISK</span></h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Glossy 250gsm Cover stock volume at Branch 1 is down to 25 sheets (Limited Capacity).</p>
                                    </div>
                                </div>
                                <button @click="currentTab = 'admin-inventory'" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded text-xs transition shadow-sm shrink-0">Reorder Stock</button>
                            </div>

                            <!-- Unrouted Order Alert -->
                            <div class="p-4 flex items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="h-9 w-9 bg-cyan-50 text-cyan-600 rounded-lg flex items-center justify-center shrink-0"><i class="fa-solid fa-route"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm text-navy-900">Pending Route Validation <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-cyan-100 text-cyan-800 uppercase ml-1.5">RECOMMENDED</span></h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Incoming order from Nieressa requires final validation and branch confirmation.</p>
                                    </div>
                                </div>
                                <button @click="currentTab = 'admin-orders'" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-3 py-1.5 rounded text-xs transition shadow shrink-0">Evaluate Route</button>
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
                                            <div :class="branch.workload > 80 ? 'bg-red-500' : (branch.workload > 50 ? 'bg-amber-500' : 'bg-cyan-500')" class="h-full rounded-full transition-all duration-500" :style="'width: ' + branch.workload + '%'"></div>
                                        </div>
                                        <div class="flex gap-x-4 text-[10px] text-slate-400 font-medium">
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
                                        <path class="text-cyan-500" stroke-width="3" stroke-dasharray="96, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="text-center">
                                        <span class="text-3xl font-black text-navy-900 font-display">96%</span>
                                        <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Reliability</span>
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
                                                <span class="font-bold text-cyan-600" x-text="order.branch || getAutoRecommendation(order)"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span :class="getStatusBadgeClass(order.status)" class="px-2 py-0.5 rounded text-[11px] font-bold uppercase" x-text="getStatusLabel(order.status)"></span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <!-- Action states -->
                                                <button x-show="order.status === 'submitted'" @click="routeOrderModal(order)" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-3 py-1.5 rounded text-xs transition shadow font-semibold">Evaluate Routing</button>
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
                                                                <div class="bg-cyan-500 h-full rounded-full" :style="'width: ' + getProductionPercent(job.status) + '%'"></div>
                                                            </div>
                                                            <span class="text-xs text-slate-500" x-text="getProductionPercent(job.status) + '%'"></span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span :class="getStatusBadgeClass(job.status)" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" x-text="getStatusLabel(job.status)"></span>
                                                    </td>
                                                    <td class="px-6 py-4 text-right">
                                                        <button @click="advanceJobStatus(job)" class="bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200 font-bold px-3 py-1.5 rounded text-xs transition">
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
                            <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-3 flex items-center gap-2"><i class="fa-solid fa-sliders text-cyan-600"></i> Simulator Constraints</h3>
                            
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
                                        <label :class="simUrgent ? 'border-cyan-500 bg-cyan-50/20' : 'border-slate-200'" class="border p-2 rounded-lg text-center cursor-pointer block hover:border-slate-300">
                                            <input type="radio" x-model="simUrgent" :value="true" class="sr-only">
                                            <span class="text-xs font-semibold text-navy-900">RUSH (48 hrs)</span>
                                        </label>
                                        <label :class="!simUrgent ? 'border-cyan-500 bg-cyan-50/20' : 'border-slate-200'" class="border p-2 rounded-lg text-center cursor-pointer block hover:border-slate-300">
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
                                <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-3 flex items-center gap-2"><i class="fa-solid fa-microchip text-cyan-600"></i> AI Engine Routing Results</h3>
                                
                                <div class="mt-6 flex flex-col sm:flex-row items-center gap-6 p-5 bg-cyan-50/40 border border-cyan-100 rounded-xl">
                                    <div class="h-16 w-16 bg-cyan-600 rounded-full flex items-center justify-center text-white text-3xl font-display font-bold shadow shrink-0">
                                        <span x-text="getRecommendation().branchId"></span>
                                    </div>
                                    <div class="text-center sm:text-left space-y-1">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Primary System Recommendation</span>
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
                                            <span :class="getRecommendation().machineAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().machineAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Machines available (low queue)</span>
                                        </div>

                                        <!-- Materials Available -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().materialsAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().materialsAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Required paper materials in stock</span>
                                        </div>

                                        <!-- Employees Available -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().employeesAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().employeesAvailable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Employees staffed & available</span>
                                        </div>

                                        <!-- Workload Acceptable -->
                                        <div class="flex items-center gap-3">
                                            <span :class="getRecommendation().workloadAcceptable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().workloadAcceptable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Workload within thresholds</span>
                                        </div>

                                        <!-- Deadline Achievable -->
                                        <div class="flex items-center gap-3 sm:col-span-2">
                                            <span :class="getRecommendation().deadlineAchievable ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'" class="h-6 w-6 rounded-full flex items-center justify-center text-[10px] shrink-0 font-black">
                                                <i class="fa-solid" :class="getRecommendation().deadlineAchievable ? 'fa-check' : 'fa-xmark'"></i>
                                            </span>
                                            <span class="text-xs text-slate-600">Target deadline achievable at this branch</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[10px] text-slate-400 pt-4 border-t border-slate-100">Adjust the sliders and requirement switches on the left menu to observe real-time rerouting behaviors of the CAPACIPRINT Engine.</p>
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
                                            <button @click="reorderStock(item)" class="text-cyan-600 hover:text-cyan-800 font-bold text-xs">Reorder Stock</button>
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
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-50 text-cyan-600 uppercase" x-text="branch.location"></span>
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
                            <button @click="triggerToast('Report Center', 'Operational Report CSV downloaded.')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2.5 px-5 rounded-lg text-xs shadow transition">Download CSV</button>
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
                                        <span class="text-[10px] text-slate-400" x-text="notif.time"></span>
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
                                <p class="text-[10px] text-slate-400 mt-1.5">Maximum workload load before routing triggers bypass alerts.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wider mb-1.5">Standard Safety Material Threshold (Copies)</label>
                                <input type="number" value="500" class="block w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-800">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button @click="triggerToast('System Settings', 'Threshold weight rules updated.')" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-lg text-xs shadow transition">Save Settings</button>
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
                                    <span :class="getStatusBadgeClass((activeOrderDetails || {}).status)" class="px-2 py-0.5 rounded font-bold uppercase inline-block text-[10px]" x-text="getStatusLabel((activeOrderDetails || {}).status)"></span>
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
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition" :class="simBranchChoice === 'Branch 1' ? 'border-cyan-500 bg-cyan-50/10' : 'border-slate-200'">
                                        <div class="flex items-start gap-4">
                                            <input type="radio" x-model="simBranchChoice" value="Branch 1" class="sr-only">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs shrink-0" :class="simBranchChoice === 'Branch 1' ? 'border-cyan-500 text-cyan-600 font-black' : 'border-slate-200 text-slate-400'">1</div>
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
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition bg-cyan-50/20 border-cyan-500 ring-1 ring-cyan-500">
                                        <input type="radio" x-model="simBranchChoice" value="Branch 2" class="sr-only">
                                        <div class="flex items-start gap-4">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs border-cyan-500 text-cyan-600 font-black shrink-0">2</div>
                                            <div class="flex-grow space-y-1">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span class="font-bold text-navy-900">Branch 2 — Sydney CBD Hub (Recommended)</span>
                                                    <span class="text-cyan-600 font-black">Queue: 28% load</span>
                                                </div>
                                                <p class="text-[11px] text-emerald-600 font-semibold"><i class="fa-solid fa-circle-check mr-1"></i> Safe routing path. Materials and machine queue ready.</p>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Branch 3 Option -->
                                    <label class="block border p-4 rounded-xl cursor-pointer hover:bg-slate-50/50 transition" :class="simBranchChoice === 'Branch 3' ? 'border-cyan-500 bg-cyan-50/10' : 'border-slate-200'">
                                        <div class="flex items-start gap-4">
                                            <input type="radio" x-model="simBranchChoice" value="Branch 3" class="sr-only">
                                            <div class="h-8 w-8 rounded-full border bg-white flex items-center justify-center font-bold text-xs shrink-0" :class="simBranchChoice === 'Branch 3' ? 'border-cyan-500 text-cyan-600 font-black' : 'border-slate-200 text-slate-400'">3</div>
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
                                
                                <button @click="isEvaluatingRoute = true; setTimeout(() => { executeRouting(activeRouteEvaluation, simBranchChoice); isEvaluatingRoute = false; activeRouteEvaluation = null; }, 1500)" :disabled="isEvaluatingRoute" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold px-5 py-2 rounded-lg text-xs shadow transition flex items-center gap-2">
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

                // Validations
                quantityError: '',
                fileError: '',
                dateError: '',

                ordersFilter: 'all',

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
                        this.loginPassword = 'password123';
                    } else {
                        this.loginUsername = 'admin@capaciprint.com';
                        this.loginPassword = 'password123';
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
                        return 'bg-cyan-500 border-cyan-600 text-white'; // Completed steps
                    } else if (currentProgress === stepPercent) {
                        if (currentStatus === 'quotation_ready') return 'bg-amber-500 border-amber-600 text-white ring-4 ring-amber-100';
                        return 'bg-cyan-500 border-cyan-600 text-white ring-4 ring-cyan-100'; // Active step
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
                        case 'in_production': return 'bg-cyan-100 text-cyan-800 border border-cyan-200';
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
    </script>
</body>
</html>
