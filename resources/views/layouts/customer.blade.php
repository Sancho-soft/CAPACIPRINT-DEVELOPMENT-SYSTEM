@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-cyber-base flex flex-col md:flex-row font-sans text-cyber-main selection:bg-cyan-500/30 selection:text-cyan-200" 
     x-data="{ 
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed);
        },
        isDark: document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme'),
        toggleTheme() {
            document.documentElement.classList.add('theme-transitioning');
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark', 'dark-theme');
                document.documentElement.classList.remove('light-theme');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.add('light-theme');
                document.documentElement.classList.remove('dark', 'dark-theme');
                localStorage.theme = 'light';
            }
            setTimeout(() => {
                document.documentElement.classList.remove('theme-transitioning');
            }, 400);
        }
     }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-white dark:bg-cyber-surface border-b border-cyber text-cyber-main px-4 py-3 flex items-center justify-between shadow-lg sticky top-0 z-40 print:hidden">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-8 w-auto object-contain shrink-0" onerror="this.onerror=null; this.src=''; this.classList.add('hidden');">
            <div>
                <span class="font-black text-sm tracking-wide font-display text-cyber-main">CAPACIPRINT</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="toggleTheme()" 
                    type="button"
                    class="relative w-10 h-5 p-0.5 rounded-full transition-colors duration-300 flex items-center shadow-inner focus:outline-none cursor-pointer"
                    :class="isDark ? 'bg-slate-900 border border-slate-700' : 'bg-slate-300 border border-slate-300/80'"
                    title="Toggle Light / Dark Theme">
                <div class="w-4 h-4 rounded-full transition-transform duration-300 transform flex items-center justify-center shadow-md"
                     :class="isDark ? 'translate-x-5 bg-slate-800 text-cyan-400 border border-slate-600' : 'translate-x-0 bg-white text-amber-500 border border-slate-200'">
                    <i :class="isDark ? 'fa-solid fa-moon text-[8px]' : 'fa-solid fa-sun text-[8px]'"></i>
                </div>
            </button>
            <button @click="sidebarOpen = !sidebarOpen" class="text-cyber-muted hover:text-cyber-main p-2 rounded-lg focus:outline-none">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Backdrop Overlay --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 md:hidden" 
         x-cloak></div>

    {{-- Customer Collapsible Sidebar --}}
    <aside class="fixed md:sticky top-0 inset-y-0 left-0 z-50 shrink-0 bg-[#111A24] border-r border-slate-800/80 text-slate-400 flex flex-col h-screen overflow-hidden transition-all duration-300 ease-in-out shadow-2xl print:hidden"
           :class="{
               'w-64': !sidebarCollapsed,
               'w-20': sidebarCollapsed,
               'translate-x-0': sidebarOpen,
               '-translate-x-full md:translate-x-0': !sidebarOpen
           }">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 h-16 px-4 border-b border-slate-800/80 flex items-center justify-between bg-transparent">
            <div class="flex items-center gap-3 min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <img src="{{ asset('images/caplogo.png') }}?v={{ time() }}" alt="CapaciPrint Logo" class="h-10 w-10 object-contain shrink-0 drop-shadow-sm">
                <div class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
                    <h1 class="font-black text-white text-base font-display tracking-tight leading-none truncate">CAPACIPRINT</h1>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav class="flex-1 min-h-0 px-2.5 py-3 space-y-1 overflow-y-auto no-scrollbar text-xs font-medium">

            <p class="px-2.5 pt-1 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1" x-show="!sidebarCollapsed" x-transition.opacity>Dashboard</p>
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Dashboard Overview' : ''">
                <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Dashboard Overview</span>
            </a>

            <a href="{{ route('customer.print-requests.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.print-requests.create') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'New Print Request' : ''">
                <i class="fa-solid fa-file-circle-plus w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">New Print Request</span>
            </a>
            <a href="{{ route('customer.print-requests.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.print-requests.index') || request()->routeIs('customer.print-requests.show') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'My Requests' : ''">
                <i class="fa-solid fa-list-ul w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">My Requests</span>
            </a>

            <p class="px-2.5 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1" x-show="!sidebarCollapsed" x-transition.opacity>Finance</p>
            <a href="{{ route('customer.quotations.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.quotations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Quotations' : ''">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Quotations</span>
                @php $quoteCount = auth()->user()->quotations()->where('status','pending')->count(); @endphp
                @if($quoteCount > 0)
                    <span x-show="!sidebarCollapsed" class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">{{ $quoteCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.payments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.payments.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Payments' : ''">
                <i class="fa-solid fa-credit-card w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Payments</span>
            </a>

            <p class="px-2.5 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1" x-show="!sidebarCollapsed" x-transition.opacity>Tracking &amp; Claiming</p>
            <a href="{{ route('customer.claiming.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('customer.claiming.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-sky-500 rounded-l-none' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'QR / Claiming' : ''">
                <i class="fa-solid fa-qrcode w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">QR / Claiming</span>
            </a>

        </nav>

        {{-- Desktop Sidebar Collapse / Expand Toggle Button --}}
        <div class="hidden md:flex shrink-0 p-3 border-t border-slate-800/80 bg-transparent items-center"
             :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            <span x-show="!sidebarCollapsed" x-transition.opacity class="text-[11px] font-bold text-slate-500 uppercase tracking-wider pl-2">Collapse Menu</span>
            <button @click="toggleSidebar()" 
                    type="button" 
                    class="h-8 w-8 rounded-xl bg-slate-800/80 hover:bg-cyan-500/20 text-slate-400 hover:text-cyan-400 border border-slate-700/80 hover:border-cyan-500/40 flex items-center justify-center transition shadow-sm cursor-pointer"
                    :title="sidebarCollapsed ? 'Expand Sidebar (Ctrl + B)' : 'Collapse Sidebar'">
                <i class="fa-solid text-xs transition-transform duration-300"
                   :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            </button>
        </div>

    </aside>

    {{-- Main Content Container --}}
    <div class="flex-1 flex flex-col min-w-0 bg-cyber-base transition-colors duration-200">

        {{-- Top App Bar --}}
        <header class="h-16 bg-cyber-surface/90 backdrop-blur-md border-b border-cyber px-6 flex items-center justify-between sticky top-0 z-30 shadow-md print:hidden">
            <div class="flex items-center gap-3">
                {{-- Mobile toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-cyber-muted hover:text-cyber-main p-1.5 rounded-lg border border-cyber hover:bg-cyber-sub focus:outline-none transition">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Direct Quick Theme Toggle Button --}}
                <button @click="toggleTheme()" 
                        type="button" 
                        class="hidden sm:flex items-center justify-center h-9 w-9 text-cyber-muted hover:text-cyan-400 hover:bg-cyber-sub rounded-xl border border-cyber transition cursor-pointer"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i :class="isDark ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-slate-700'" class="text-sm"></i>
                </button>

                <a href="{{ route('customer.notifications.index') }}" class="relative p-2 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-xl transition flex items-center justify-center border border-cyber/50" title="Notifications">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500 ring-2 ring-cyber-surface"></span>
                        </span>
                    @endif
                </a>

                {{-- Interactive User Profile Menu (Slide-out Dropdown with Adaptive Dark/Light Mode) --}}
                <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                    {{-- Trigger Pill Button --}}
                    <button @click="profileOpen = !profileOpen" 
                            type="button"
                            class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl bg-cyber-sub border border-cyber hover:border-cyan-500/40 text-cyber-main transition shadow-sm group focus:outline-none select-none cursor-pointer"
                            :class="profileOpen ? 'ring-2 ring-cyan-500/30 border-cyan-500/50' : ''">
                        <div class="h-9 w-9 rounded-xl bg-slate-800 text-sky-400 font-bold flex items-center justify-center text-sm shrink-0 border border-slate-700 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="text-xs font-bold text-cyber-main block group-hover:text-cyan-400 transition-colors">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-cyan-600 dark:text-cyan-400 font-extrabold uppercase tracking-wider block">Customer</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-cyber-muted transition-transform duration-200 ml-1 hidden sm:inline" :class="profileOpen ? 'rotate-180 text-cyan-400' : ''"></i>
                    </button>

                    {{-- Slideable Interactive Dropdown Menu --}}
                    <div x-show="profileOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-2.5 w-64 rounded-2xl bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 shadow-2xl z-50 p-2 text-xs space-y-1 backdrop-blur-xl"
                         x-cloak>
                        
                        {{-- Slideable Dark Mode Interactive Toggle --}}
                        <div class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/60 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <i :class="isDark ? 'fa-solid fa-moon text-cyan-400' : 'fa-solid fa-sun text-amber-500'" class="text-sm transition-colors"></i>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200" x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
                            </div>
                            <button @click="toggleTheme()" 
                                    type="button"
                                    class="relative w-11 h-6 rounded-full transition-colors duration-300 p-0.5 flex items-center shadow-inner cursor-pointer focus:outline-none"
                                    :class="isDark ? 'bg-cyan-950 border border-cyan-500/50' : 'bg-slate-300 border border-slate-400/80'">
                                <div class="w-5 h-5 rounded-full transition-transform duration-300 transform flex items-center justify-center shadow-md text-[9px]"
                                     :class="isDark ? 'translate-x-5 bg-cyan-400 text-slate-950 font-bold' : 'translate-x-0 bg-white text-amber-500'">
                                    <i :class="isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun'"></i>
                                </div>
                            </button>
                        </div>

                        {{-- Settings / Profile Link --}}
                        <a href="{{ route('customer.profile.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white transition font-medium">
                            <i class="fa-solid fa-gear w-4 text-center text-slate-400"></i>
                            <span>Account Settings</span>
                        </a>

                        {{-- Help & Support --}}
                        <a href="{{ route('customer.help') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white transition font-medium">
                            <i class="fa-solid fa-circle-question w-4 text-center text-slate-400"></i>
                            <span>Help &amp; Support</span>
                        </a>

                        <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>

                        {{-- Logout Button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-red-500 dark:text-red-400 hover:bg-red-500/10 dark:hover:bg-red-500/20 transition font-bold border border-transparent hover:border-red-500/20">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-sm text-emerald-400 flex items-center gap-3 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-400/60 hover:text-emerald-400"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-sm text-red-400 flex items-center gap-3 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                <i class="fa-solid fa-circle-exclamation text-red-400 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400/60 hover:text-red-400"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-4 sm:p-6 w-full">
            @yield('content')
        </main>

    </div>
</div>
@endsection
