@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans text-slate-800" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-navy-900 text-white px-4 py-3 flex items-center justify-between shadow-md sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-white p-1 flex items-center justify-center shrink-0 shadow-sm">
                <img src="{{ asset('images/caplogo.png') }}" class="h-full w-full object-contain mix-blend-multiply" alt="Logo">
            </div>
            <span class="font-bold text-sm tracking-wide font-display text-white">CAPACIPRINT</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-300 hover:text-white p-2 rounded-lg focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    {{-- Customer Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-navy-900 text-slate-300 flex flex-col transform transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:inset-0 shadow-xl shrink-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Sidebar Brand --}}
        <div class="p-6 border-b border-navy-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white p-1 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('images/caplogo.png') }}" class="h-full w-full object-contain mix-blend-multiply" alt="CAPACIPRINT">
                </div>
                <div>
                    <h1 class="font-black text-white text-base font-display tracking-tight leading-none">CAPACIPRINT</h1>
                    <span class="text-[10px] text-brand-400 font-semibold tracking-wider uppercase block mt-1">Customer Portal</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Customer Navigation Links --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto text-sm font-medium">

            <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2 mt-1">Main</p>
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
            </a>

            <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2 mt-4">Orders</p>
            <a href="{{ route('customer.orders.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.orders.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> My Orders
                @php $orderCount = auth()->user()->orders()->whereNotIn('status', ['claimed'])->count(); @endphp
                @if($orderCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-400 text-navy-950">{{ $orderCount }}</span>
                @endif
            </a>

            <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2 mt-4">Print Request</p>
            <a href="{{ route('customer.print-requests.create') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.print-requests.create') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-file-circle-plus w-5 text-center"></i> New Print Request
            </a>
            <a href="{{ route('customer.print-requests.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.print-requests.index') || request()->routeIs('customer.print-requests.show') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-list-ul w-5 text-center"></i> My Requests
            </a>

            <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2 mt-4">Finance</p>
            <a href="{{ route('customer.quotations.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.quotations.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Quotations
                @php $quoteCount = auth()->user()->quotations()->where('status','pending')->count(); @endphp
                @if($quoteCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-400 text-navy-950">{{ $quoteCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.payments.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.payments.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-credit-card w-5 text-center"></i> Payments
            </a>

            <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2 mt-4">Account</p>
            <a href="{{ route('customer.notifications.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.notifications.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-bell w-5 text-center"></i> Notifications
                @php $unread = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-400 text-navy-950">{{ $unread }}</span>
                @endif
            </a>
            <a href="{{ route('customer.claiming.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.claiming.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-qrcode w-5 text-center"></i> QR / Claiming
            </a>
            <a href="{{ route('customer.profile.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.profile.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-circle-user w-5 text-center"></i> Profile
            </a>
            <a href="{{ route('customer.help') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('customer.help') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white text-slate-300' }}">
                <i class="fa-solid fa-circle-question w-5 text-center"></i> Help & Support
            </a>

        </nav>

        {{-- User Footer / Logout --}}
        <div class="p-4 border-t border-navy-800 bg-navy-950/40">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm text-red-500 hover:text-white hover:bg-red-600 transition shadow-sm border border-red-500/20 hover:border-red-600">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Content Container --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top App Bar --}}
        <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-navy-900 font-display">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500">CapaciPrint &middot; Capacity Planning System</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('customer.notifications.index') }}" class="relative p-2 text-slate-500 hover:text-navy-900 hover:bg-slate-100 rounded-xl transition flex items-center justify-center">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-500 ring-2 ring-white"></span>
                        </span>
                    @endif
                </a>
                <div class="hidden sm:flex items-center gap-3 pl-2 border-l border-slate-200">
                    <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold font-display text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="text-left">
                        <span class="text-xs font-bold text-slate-800 block leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-brand-600 font-bold uppercase tracking-wider bg-brand-50 px-2 py-0.5 rounded inline-block mt-0.5">Customer</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 flex items-center gap-3 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 flex items-center gap-3 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>
</div>
@endsection
