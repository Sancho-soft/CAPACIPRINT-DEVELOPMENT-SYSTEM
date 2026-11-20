@extends('layouts.app')

@section('body')
<div class="min-h-screen pt-0 bg-cyber-base flex flex-col md:flex-row font-sans text-cyber-main selection:bg-blue-600/20 selection:text-blue-700 dark:selection:text-blue-200" 
     x-data="{ 
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebar_collapsed', this.sidebarCollapsed);
        },
        isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
        toggleTheme() {
            document.documentElement.classList.add('theme-transitioning');
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark', 'dark-theme');
                document.documentElement.classList.remove('light-theme');
                localStorage.theme = 'dark';
                document.getElementById('meta-theme-color')?.setAttribute('content', '#0B0F17');
            } else {
                document.documentElement.classList.add('light-theme');
                document.documentElement.classList.remove('dark', 'dark-theme');
                localStorage.theme = 'light';
                document.getElementById('meta-theme-color')?.setAttribute('content', '#F8FAFC');
            }
            setTimeout(() => {
                document.documentElement.classList.remove('theme-transitioning');
            }, 400);
        }
     }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-cyber-card border-b border-cyber text-cyber-main px-4 py-3 flex items-center justify-between sticky top-0 z-40 print:hidden">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-8 w-auto object-contain shrink-0" onerror="this.onerror=null; this.src=''; this.classList.add('hidden');">
            <div>
                <span class="font-bold text-sm tracking-tight font-display text-cyber-main">CAPACIPRINT</span>
                <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-semibold block leading-tight">Client Portal</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="toggleTheme()" 
                    type="button"
                    class="relative w-10 h-5 p-0.5 rounded-full transition-colors duration-200 flex items-center focus:outline-none cursor-pointer"
                    :class="isDark ? 'bg-slate-800 border border-slate-700' : 'bg-slate-200 border border-slate-300'"
                    title="Toggle Light / Dark Theme">
                <div class="w-4 h-4 rounded-full transition-transform duration-200 transform flex items-center justify-center"
                     :class="isDark ? 'translate-x-5 bg-slate-900 text-blue-400 border border-slate-600' : 'translate-x-0 bg-white text-amber-500 border border-slate-300'">
                    <i :class="isDark ? 'fa-solid fa-moon text-[8px]' : 'fa-solid fa-sun text-[8px]'"></i>
                </div>
            </button>
            <button @click="sidebarOpen = !sidebarOpen" class="text-cyber-muted hover:text-cyber-main p-2 rounded-xl focus:outline-none">
                <i class="fa-solid fa-bars text-lg"></i>
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
         class="fixed inset-0 bg-black/60 z-40 md:hidden overscroll-contain" 
         x-cloak></div>

    {{-- Customer Collapsible Sidebar --}}
    <aside class="fixed md:sticky top-0 inset-y-0 left-0 z-50 shrink-0 bg-cyber-surface border-r border-cyber text-cyber-muted flex flex-col h-dvh overflow-hidden transition-all duration-200 ease-out overscroll-contain print:hidden"
           :class="{
               'w-60': !sidebarCollapsed,
               'w-[72px]': sidebarCollapsed,
               'translate-x-0': sidebarOpen,
               '-translate-x-full md:translate-x-0': !sidebarOpen
           }">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 h-14 px-4 border-b border-cyber flex items-center justify-between bg-cyber-card/40">
            <div class="flex items-center gap-3 min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <img src="{{ asset('images/caplogo.png') }}?v={{ time() }}" alt="CapaciPrint Logo" class="h-8 w-8 object-contain shrink-0">
                <div class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
                    <h1 class="font-bold text-cyber-main text-sm font-display tracking-tight leading-none truncate">CAPACIPRINT</h1>
                    <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-semibold block mt-1 truncate">Client Portal</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" aria-label="Close navigation" class="md:hidden text-cyber-muted hover:text-cyber-main p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        @php
            $navActive = 'bg-blue-600/10 dark:bg-blue-500/15 text-blue-600 dark:text-blue-400 font-semibold border border-blue-600/20 dark:border-blue-500/25';
            $navIdle   = 'text-cyber-muted hover:text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] font-medium border border-transparent';
        @endphp

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav class="flex-1 min-h-0 px-2.5 py-3 space-y-1 overflow-y-auto no-scrollbar text-[13px] font-sans">

            <p class="px-3 pt-1 pb-1.5 text-[11px] font-sans font-bold uppercase tracking-wider text-cyber-sub" x-show="!sidebarCollapsed" x-transition.opacity>Dashboard</p>
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.dashboard') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Dashboard Overview' : ''">
                <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Dashboard Overview</span>
            </a>

            <a href="{{ route('customer.print-requests.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.print-requests.create') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'New Print Request' : ''">
                <i class="fa-solid fa-file-circle-plus w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">New Print Request</span>
            </a>
            <a href="{{ route('customer.print-requests.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.print-requests.index') || request()->routeIs('customer.print-requests.show') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'My Requests' : ''">
                <i class="fa-solid fa-list-ul w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">My Requests</span>
            </a>

            <p class="px-3 pt-3 pb-1.5 text-[11px] font-sans font-bold uppercase tracking-wider text-cyber-sub" x-show="!sidebarCollapsed" x-transition.opacity>Finance</p>
            <a href="{{ route('customer.quotations.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.quotations.*') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Quotations' : ''">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Quotations</span>
                @php $quoteCount = auth()->user()->quotations()->where('status','pending')->count(); @endphp
                @if($quoteCount > 0)
                    <span x-show="!sidebarCollapsed" class="ml-auto text-[11px] font-sans font-bold px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30 tabular-nums">{{ $quoteCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.payments.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.payments.*') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'Payments' : ''">
                <i class="fa-solid fa-credit-card w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">Payments</span>
            </a>

            <p class="px-3 pt-3 pb-1.5 text-[11px] font-sans font-bold uppercase tracking-wider text-cyber-sub" x-show="!sidebarCollapsed" x-transition.opacity>Tracking &amp; Claiming</p>
            <a href="{{ route('customer.claiming.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('customer.claiming.*') ? $navActive : $navIdle }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : ''"
               :title="sidebarCollapsed ? 'QR / Claiming' : ''">
                <i class="fa-solid fa-qrcode w-5 text-center text-sm shrink-0"></i>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="truncate">QR / Claiming</span>
            </a>

        </nav>

        {{-- Desktop Sidebar Collapse Plate --}}
        <div class="shrink-0 p-2.5 border-t border-cyber bg-cyber-card/40 hidden md:flex items-center"
             :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            <span x-show="!sidebarCollapsed" x-transition.opacity class="text-[11px] font-sans font-semibold text-cyber-sub pl-1.5">Collapse Menu</span>
            <button @click="toggleSidebar()" 
                    type="button" 
                    aria-label="Toggle navigation sidebar"
                    class="h-8 w-8 rounded-lg bg-cyber-card hover:border-blue-600/40 dark:hover:border-blue-500/40 text-cyber-muted hover:text-cyber-main border border-cyber flex items-center justify-center transition cursor-pointer"
                    :title="sidebarCollapsed ? 'Expand Sidebar (Ctrl + B)' : 'Collapse Sidebar'">
                <i class="fa-solid text-xs transition-transform duration-200"
                   :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
            </button>
        </div>

    </aside>

    {{-- Main Content Container --}}
    <div class="flex-1 flex flex-col min-w-0 bg-cyber-base transition-colors duration-200">

        {{-- Top Command App Bar --}}
        <header class="h-14 bg-cyber-card border-b border-cyber px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 print:hidden">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Mobile toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" aria-label="Open navigation menu" class="md:hidden text-cyber-muted hover:text-cyber-main p-1.5 rounded-lg border border-cyber hover:bg-cyber-sub focus:outline-none transition">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <div class="hidden xl:flex items-center gap-2 text-xs font-sans font-medium text-cyber-muted tabular-nums pr-1">
                    <i class="fa-regular fa-calendar text-blue-600 dark:text-blue-400"></i>
                    <span>{{ now()->format('D, M j, Y') }}</span>
                </div>

                {{-- Clean Modern Theme Toggle Pill --}}
                <button @click="toggleTheme()" 
                        type="button" 
                        aria-label="Toggle Light or Dark Mode"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyber-sub border border-cyber text-xs font-sans font-semibold text-cyber-main hover:border-blue-600/40 dark:hover:border-blue-500/40 transition cursor-pointer"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i :class="isDark ? 'fa-solid fa-moon text-blue-400' : 'fa-solid fa-sun text-amber-500'" class="text-xs"></i>
                    <span x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
                </button>

                <a href="{{ route('customer.notifications.index') }}" aria-label="Notifications" class="relative h-8 w-8 text-cyber-muted hover:text-cyber-main bg-cyber-sub/60 hover:bg-cyber-sub border border-cyber rounded-xl transition flex items-center justify-center" title="Notifications">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                    @endif
                </a>

                {{-- Interactive User Profile Menu --}}
                <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" 
                            type="button"
                            class="flex items-center gap-2.5 p-1 pr-2.5 rounded-xl bg-cyber-card border border-cyber hover:border-blue-600/40 dark:hover:border-blue-500/40 text-cyber-main transition group focus:outline-none select-none cursor-pointer"
                            :class="profileOpen ? 'ring-1 ring-blue-600/30 border-blue-600/50' : ''">
                        <div class="h-8 w-8 rounded-lg bg-blue-600 text-white font-sans font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="text-xs font-semibold text-cyber-main block">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-medium block">Customer</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[11px] text-cyber-muted transition-transform duration-150 ml-1 hidden sm:inline" :class="profileOpen ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''"></i>
                    </button>

                    <div x-show="profileOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 mt-2 w-60 rounded-2xl bg-cyber-card border border-cyber shadow-lg z-50 p-1.5 text-xs space-y-1"
                         x-cloak>

                        <a href="{{ route('customer.profile.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] transition font-medium">
                            <i class="fa-solid fa-gear w-4 text-center text-cyber-muted"></i>
                            <span>Account Settings</span>
                        </a>

                        <a href="{{ route('customer.help') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] transition font-medium">
                            <i class="fa-solid fa-circle-question w-4 text-center text-cyber-muted"></i>
                            <span>Help &amp; Support</span>
                        </a>

                        <div class="border-t border-cyber-sub my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-500/10 transition font-semibold border border-transparent hover:border-rose-500/20">
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
            <div class="mx-6 mt-6 p-3.5 bg-emerald-500/10 border border-emerald-500/30 border-l-4 border-l-emerald-600 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                <span class="font-semibold">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-700/70 hover:text-emerald-900 dark:text-emerald-300/70 dark:hover:text-emerald-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-3.5 bg-rose-500/10 border border-rose-500/30 border-l-4 border-l-rose-600 rounded-xl text-xs text-rose-800 dark:text-rose-300 flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-sm"></i>
                <span class="font-semibold">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-rose-700/70 hover:text-rose-900 dark:text-rose-300/70 dark:hover:text-rose-100"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-4 sm:p-6 w-full">
            @yield('content')
        </main>

    </div>
</div>
@endsection
