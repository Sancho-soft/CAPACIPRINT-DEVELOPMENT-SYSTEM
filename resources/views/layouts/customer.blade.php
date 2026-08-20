@extends('layouts.app')

@section('body')
<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- ══════════════════════════════════════════════ --}}
    {{-- DESKTOP SIDEBAR --}}
    {{-- ══════════════════════════════════════════════ --}}
    <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-navy-800 text-white border-r border-navy-900">

        {{-- Logo --}}
        <div class="flex h-16 items-center px-5 border-b border-navy-700 gap-3 shrink-0">
            <img src="{{ asset('images/caplogo.png') }}" class="h-9 w-auto brightness-0 invert" alt="CAPACIPRINT Logo">
            <div>
                <p class="font-display font-bold text-sm tracking-wider leading-none">CAPACIPRINT</p>
                <span class="text-[9px] text-brand-400 tracking-widest font-semibold uppercase">Customer Portal</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            <p class="sidebar-section">Main</p>
            <a href="{{ route('customer.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
            </a>

            <p class="sidebar-section">Orders</p>
            <a href="{{ route('customer.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> My Orders
                @php $orderCount = auth()->user()->orders()->whereNotIn('status', ['claimed'])->count(); @endphp
                @if($orderCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-400 text-navy-950">{{ $orderCount }}</span>
                @endif
            </a>

            <p class="sidebar-section">Print Request</p>
            <a href="{{ route('customer.print-requests.create') }}"
               class="sidebar-link {{ request()->routeIs('customer.print-requests.create') ? 'active' : '' }}">
                <i class="fa-solid fa-file-circle-plus w-5 text-center"></i> New Print Request
            </a>
            <a href="{{ route('customer.print-requests.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.print-requests.index') || request()->routeIs('customer.print-requests.show') ? 'active' : '' }}">
                <i class="fa-solid fa-list-ul w-5 text-center"></i> My Requests
            </a>

            <p class="sidebar-section">Finance</p>
            <a href="{{ route('customer.quotations.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.quotations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Quotations
                @php $quoteCount = auth()->user()->quotations()->where('status','pending')->count(); @endphp
                @if($quoteCount > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-400 text-navy-950">{{ $quoteCount }}</span>
                @endif
            </a>
            <a href="{{ route('customer.payments.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-credit-card w-5 text-center"></i> Payments
            </a>

            <p class="sidebar-section">Account</p>
            <a href="{{ route('customer.notifications.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.notifications.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bell w-5 text-center"></i> Notifications
                @php $unread = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-brand-400 text-navy-950">{{ $unread }}</span>
                @endif
            </a>
            <a href="{{ route('customer.claiming.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.claiming.*') ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode w-5 text-center"></i> QR / Claiming
            </a>
            <a href="{{ route('customer.profile.index') }}"
               class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-user w-5 text-center"></i> Profile
            </a>
            <a href="{{ route('customer.help') }}"
               class="sidebar-link {{ request()->routeIs('customer.help') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-question w-5 text-center"></i> Help & Support
            </a>
        </nav>

        {{-- Sidebar Footer --}}
        <div class="border-t border-navy-700 p-4 bg-navy-900/50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">Customer</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 p-1 transition" title="Sign Out">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- MOBILE SIDEBAR DRAWER --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-cloak>
        <div class="fixed inset-0 bg-slate-900/80" @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-0 flex">
            <div class="relative flex w-72 flex-col bg-navy-800 text-white"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">

                <div class="flex h-16 items-center px-5 border-b border-navy-700 gap-3 shrink-0">
                    <img src="{{ asset('images/caplogo.png') }}" class="h-9 w-auto brightness-0 invert" alt="CAPACIPRINT Logo">
                    <div class="flex-1">
                        <p class="font-display font-bold text-sm tracking-wider leading-none">CAPACIPRINT</p>
                        <span class="text-[9px] text-brand-400 tracking-widest font-semibold uppercase">Customer Portal</span>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
                    <p class="sidebar-section">Main</p>
                    <a href="{{ route('customer.dashboard') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                    </a>
                    <p class="sidebar-section">Orders</p>
                    <a href="{{ route('customer.orders.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> My Orders
                    </a>
                    <p class="sidebar-section">Print Request</p>
                    <a href="{{ route('customer.print-requests.create') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.print-requests.create') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-circle-plus w-5 text-center"></i> New Print Request
                    </a>
                    <a href="{{ route('customer.print-requests.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.print-requests.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-list-ul w-5 text-center"></i> My Requests
                    </a>
                    <p class="sidebar-section">Finance</p>
                    <a href="{{ route('customer.quotations.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.quotations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Quotations
                    </a>
                    <a href="{{ route('customer.payments.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-credit-card w-5 text-center"></i> Payments
                    </a>
                    <p class="sidebar-section">Account</p>
                    <a href="{{ route('customer.notifications.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.notifications.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-bell w-5 text-center"></i> Notifications
                    </a>
                    <a href="{{ route('customer.claiming.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.claiming.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-qrcode w-5 text-center"></i> QR / Claiming
                    </a>
                    <a href="{{ route('customer.profile.index') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-user w-5 text-center"></i> Profile
                    </a>
                    <a href="{{ route('customer.help') }}" @click="sidebarOpen=false"
                       class="sidebar-link {{ request()->routeIs('customer.help') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question w-5 text-center"></i> Help & Support
                    </a>
                </nav>

                <div class="border-t border-navy-700 p-4 bg-navy-900/50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT AREA --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- 1-Click Demo Role Switcher Bar --}}
        <div class="bg-navy-950 text-slate-300 text-xs py-1.5 px-6 flex flex-wrap items-center justify-between gap-2 border-b border-navy-800 shrink-0">
            <div class="flex items-center gap-2">
                <span class="font-bold text-brand-400"><i class="fa-solid fa-vial mr-1"></i> DEMO ROLE SWITCHER:</span>
                <span class="text-slate-400">Current Active: <strong class="text-white bg-navy-800 px-2 py-0.5 rounded">{{ auth()->user()->role_label }}</strong></span>
            </div>
            <div class="flex items-center gap-1.5 overflow-x-auto">
                <a href="{{ route('demo.switch-role', 'customer') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isCustomer() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">1. Customer</a>
                <a href="{{ route('demo.switch-role', 'staff') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isStaff() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">2. Sales Staff</a>
                <a href="{{ route('demo.switch-role', 'manager') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isManager() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">3. Manager</a>
                <a href="{{ route('demo.switch-role', 'production') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isProduction() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">4. Production</a>
                <a href="{{ route('demo.switch-role', 'inventory') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isInventory() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">5. Inventory</a>
                <a href="{{ route('demo.switch-role', 'management') }}" class="px-2.5 py-1 rounded text-[10px] font-bold transition {{ auth()->user()->isManagement() ? 'bg-brand-500 text-white shadow' : 'bg-navy-800 hover:bg-navy-700 text-slate-300' }}">6. Executive</a>
            </div>
        </div>

        {{-- Top Navbar --}}
        <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-slate-100 z-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-900 p-1.5 rounded-lg hover:bg-slate-100 transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                {{-- Breadcrumb --}}
                <div class="flex items-center text-sm font-medium text-slate-500 gap-2">
                    <span class="text-slate-400 uppercase tracking-wider text-[11px] font-semibold hidden sm:inline">CAPACIPRINT</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 hidden sm:inline"></i>
                    <span class="text-navy-900 font-bold">@yield('page-title', 'Dashboard')</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Notification Bell --}}
                <a href="{{ route('customer.notifications.index') }}" class="relative p-2 text-slate-500 hover:text-navy-900 hover:bg-slate-50 rounded-full transition">
                    <i class="fa-regular fa-bell text-lg"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-brand-400 ring-2 ring-white"></span>
                    @endif
                </a>
                <div class="h-6 w-px bg-slate-200"></div>
                <span class="text-xs text-slate-500 hidden sm:inline-block">
                    Logged in as: <strong class="text-navy-900">{{ auth()->user()->name }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-red-500 transition font-medium">Logout</button>
                </form>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="fa-solid fa-circle-check text-green-500 text-base"></i>
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-base"></i>
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
        @if(session('info'))
            <div class="mx-6 mt-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl text-sm flex items-center gap-3"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i class="fa-solid fa-circle-info text-blue-500 text-base"></i>
                <span>{{ session('info') }}</span>
                <button @click="show = false" class="ml-auto text-blue-400 hover:text-blue-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 py-8">
            @yield('content')
        </main>
    </div>

</div>
@endsection
