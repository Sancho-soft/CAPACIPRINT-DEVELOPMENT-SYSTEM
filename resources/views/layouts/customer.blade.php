@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-cyber-base flex flex-col md:flex-row font-sans text-cyber-main selection:bg-cyan-500/30 selection:text-cyan-200" 
     x-data="{ 
        sidebarOpen: false,
        isDark: document.documentElement.classList.contains('dark-theme'),
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark-theme');
                document.documentElement.classList.remove('light-theme');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.add('light-theme');
                document.documentElement.classList.remove('dark-theme');
                localStorage.theme = 'light';
            }
        }
     }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-white dark:bg-cyber-surface border-b border-cyber text-cyber-main px-4 py-3 flex items-center justify-between shadow-lg sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-8 w-auto object-contain shrink-0" onerror="this.onerror=null; this.src=''; this.classList.add('hidden');">
            <div>
                <span class="font-black text-sm tracking-wide font-display text-cyber-main">CAPACIPRINT</span>
                <span class="text-[9px] text-cyan-500 font-bold tracking-wider uppercase block">Customer Portal</span>
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

    {{-- Customer Sidebar --}}
    <aside class="fixed md:sticky top-0 inset-y-0 left-0 z-50 w-64 shrink-0 bg-cyber-surface border-r border-cyber text-cyber-muted flex flex-col h-screen overflow-hidden transition-transform duration-200 ease-in-out shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 h-16 px-5 border-b border-cyber flex items-center justify-between bg-white dark:bg-cyber-base/50">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-9 w-auto object-contain shrink-0 drop-shadow-[0_0_12px_rgba(6,182,212,0.3)]">
                <div>
                    <h1 class="font-black text-cyber-main text-base font-display tracking-tight leading-none">CAPACIPRINT</h1>
                    <span class="text-[10px] text-cyan-500 font-extrabold tracking-wider uppercase block mt-1">Customer Portal</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Customer Navigation Links --}}
        <nav class="flex-1 min-h-0 px-3 py-3 space-y-0.5 overflow-y-auto no-scrollbar text-xs font-medium">

            <p class="px-3 pt-1 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Main</p>
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-table-cells-large w-5 text-center text-sm"></i> Dashboard
            </a>

            <p class="px-3 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Orders</p>
            <a href="{{ route('customer.orders.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.orders.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm"></i> My Orders
                @php $orderCount = auth()->user()->orders()->whereNotIn('status', ['claimed'])->count(); @endphp
                @if($orderCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">{{ $orderCount }}</span>
                @endif
            </a>

            <p class="px-3 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Print Request</p>
            <a href="{{ route('customer.print-requests.create') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.print-requests.create') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-file-circle-plus w-5 text-center text-sm"></i> New Print Request
            </a>
            <a href="{{ route('customer.print-requests.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.print-requests.index') || request()->routeIs('customer.print-requests.show') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-list-ul w-5 text-center text-sm"></i> My Requests
            </a>

            <p class="px-3 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Finance</p>
            <a href="{{ route('customer.quotations.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.quotations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm"></i> Quotations
                @php $quoteCount = auth()->user()->quotations()->where('status','pending')->count(); @endphp
                @if($quoteCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">{{ $quoteCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.payments.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.payments.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-credit-card w-5 text-center text-sm"></i> Payments
            </a>

            <p class="px-3 pt-2 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Account</p>
            <a href="{{ route('customer.notifications.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.notifications.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-bell w-5 text-center text-sm"></i> Notifications
                @php $unread = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">{{ $unread }}</span>
                @endif
            </a>
            <a href="{{ route('customer.claiming.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.claiming.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-qrcode w-5 text-center text-sm"></i> QR / Claiming
            </a>
            <a href="{{ route('customer.profile.index') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.profile.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-circle-user w-5 text-center text-sm"></i> Profile
            </a>
            <a href="{{ route('customer.help') }}"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('customer.help') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                <i class="fa-solid fa-circle-question w-5 text-center text-sm"></i> Help &amp; Support
            </a>

        </nav>

        {{-- User Footer / Logout — PINNED BOTTOM --}}
        <div class="shrink-0 p-4 border-t border-cyber bg-white dark:bg-cyber-base/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-start gap-3 px-3 py-2 rounded-xl font-bold text-xs text-red-400 hover:text-white hover:bg-red-500/20 transition border border-transparent hover:border-red-500/30">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Content Container --}}
    <div class="flex-1 flex flex-col min-w-0 bg-cyber-base transition-colors duration-200">

        {{-- Top App Bar --}}
        <header class="h-16 bg-white dark:bg-cyber-surface/90 backdrop-blur-md border-b border-cyber px-6 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div>
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-cyber-muted hover:text-cyber-main p-1.5 rounded-lg border border-cyber hover:bg-cyber-sub focus:outline-none transition">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

            <div class="flex items-center gap-4 sm:gap-5">
                {{-- Theme Switch Button (Light / Dark Mode Toggle Switch) --}}
                <button @click="toggleTheme()" 
                        type="button"
                        class="flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-cyber-sub border border-cyber text-cyber-main hover:border-cyan-500/40 transition shadow-sm group focus:outline-none select-none cursor-pointer"
                        title="Toggle Light / Dark Theme">
                    <span class="flex items-center gap-1.5 text-xs font-bold font-display">
                        <i :class="isDark ? 'fa-solid fa-moon text-cyan-400' : 'fa-solid fa-sun text-amber-500'" class="text-xs transition-colors"></i>
                        <span class="hidden sm:inline text-[11px] font-extrabold uppercase tracking-wider" :class="isDark ? 'text-cyan-400' : 'text-slate-700'" x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
                    </span>
                    {{-- Toggle Pill Switch Track --}}
                    <div class="relative w-10 h-5 rounded-full transition-colors duration-300 p-0.5 flex items-center shadow-inner"
                         :class="isDark ? 'bg-slate-900 border border-slate-700' : 'bg-slate-300 border border-slate-300/80'">
                        {{-- Sliding Knob --}}
                        <div class="w-4 h-4 rounded-full transition-transform duration-300 transform flex items-center justify-center shadow-md"
                             :class="isDark ? 'translate-x-5 bg-slate-800 text-cyan-400 border border-slate-600' : 'translate-x-0 bg-white text-amber-500 border border-slate-200'">
                            <i :class="isDark ? 'fa-solid fa-moon text-[8px]' : 'fa-solid fa-sun text-[8px]'"></i>
                        </div>
                    </div>
                </button>

                <a href="{{ route('customer.notifications.index') }}" class="relative p-2 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-xl transition flex items-center justify-center">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500 ring-2 ring-cyber-surface"></span>
                        </span>
                    @endif
                </a>
                <div class="hidden sm:flex items-center gap-3 pl-2 border-l border-cyber">
                    <div class="text-right leading-tight">
                        <span class="text-xs font-bold text-cyber-main block">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider block">Customer</span>
                    </div>
                    <div class="h-9 w-9 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 font-bold flex items-center justify-center text-sm shadow-[0_0_12px_rgba(6,182,212,0.2)] shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
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
