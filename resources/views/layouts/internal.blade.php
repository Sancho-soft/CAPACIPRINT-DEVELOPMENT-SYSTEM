@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans text-slate-800" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-navy-900 text-white px-4 py-3 flex items-center justify-between shadow-md sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-white p-1 flex items-center justify-center shrink-0 shadow-sm">
                <img src="{{ asset('images/caplogo.png') }}" class="h-full w-auto object-contain mix-blend-multiply" alt="Logo">
            </div>
            <span class="font-bold text-sm tracking-wide font-display">MORNING STAR</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-300 hover:text-white p-2 rounded-lg focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    {{-- Dynamic Role-Based Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-navy-900 text-slate-300 flex flex-col h-screen overflow-hidden transform transition-transform duration-200 ease-in-out md:translate-x-0 md:sticky md:top-0 shadow-xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 p-5 border-b border-navy-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-white p-1 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('images/caplogo.png') }}" class="h-full w-auto object-contain mix-blend-multiply" alt="CAPACIPRINT">
                </div>
                <div>
                    <h1 class="font-black text-white text-base font-display tracking-tight leading-none">CAPACIPRINT</h1>
                    <span class="text-[10px] text-brand-400 font-semibold tracking-wider uppercase block mt-1">Production Planning</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav class="flex-1 min-h-0 px-4 py-4 space-y-1 overflow-y-auto no-scrollbar text-sm font-medium">

            {{-- ROLE 7: SYSTEM ADMINISTRATOR — TOP --}}
            @if(auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">System Administration</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i> User &amp; Access Mgmt
                </a>
                <a href="{{ route('management.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('management.branches.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-network-wired w-5 text-center"></i> Branch Management
                </a>
                <a href="{{ route('management.audit-logs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('management.audit-logs.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center"></i> System Audit Logs
                </a>
            @endif

            {{-- ROLE 4: BRANCH MANAGER — TOP --}}
            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Branch &amp; Capacity</p>

                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('manager.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.branches.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-network-wired w-5 text-center"></i> Branch Management
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.purchasing.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center"></i> Purchase Requests
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.reports.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center"></i> Operational Reports
                </a>
            @endif

            {{-- ROLE 2: SALES / CUSTOMER SERVICE STAFF --}}
            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Sales &amp; Service</p>

                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> CS Dashboard
                </a>
                <a href="{{ route('staff.print-requests.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.print-requests.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center"></i> Customer Requests
                </a>
                <a href="{{ route('staff.quotations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.quotations.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Quotations
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.orders.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive w-5 text-center"></i> Orders
                </a>
                <a href="{{ route('staff.claim-scanner') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.claim-scanner') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center"></i> Claim &amp; QR Scanner
                </a>
                <a href="{{ route('staff.pricing-rules.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.pricing-rules.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center"></i> Pricing Rules Matrix
                </a>
                <a href="{{ route('staff.customers.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.customers.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i> Customers
                </a>
                <a href="{{ route('staff.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.notifications.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-bell w-5 text-center"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: PRODUCTION OFFICER --}}
            @if(auth()->user()->isProductionOfficer() || auth()->user()->isManager() || auth()->user()->isSuperAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Operations Planning</p>

                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('manager.production-planning.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.production-planning.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center"></i> Production Planning
                </a>
                <a href="{{ route('manager.capacity.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.capacity.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-calculator w-5 text-center"></i> Capacity Evaluation
                </a>
                <a href="{{ route('manager.recommendations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.recommendations.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-building-circle-check w-5 text-center"></i> Branch Recommendations
                </a>
                <a href="{{ route('manager.workload.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('manager.workload.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i> Workload Monitor
                </a>
            @endif

            {{-- ROLE 7: LAYOUT DESIGNER --}}
            @if(auth()->user()->isDesigner() || auth()->user()->isSuperAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Pre-Press &amp; Proofing</p>

                <a href="{{ route('staff.print-requests.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.print-requests.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-pen-ruler w-5 text-center"></i> Proof &amp; Layout Queue
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('staff.orders.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-pdf w-5 text-center"></i> Artwork Inspection
                </a>
            @endif

            {{-- ROLE: LAYOUT DESIGNER / PRE-PRESS --}}
            @if(auth()->user()->isDesigner() || auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Design &amp; Layout</p>

                <a href="{{ route('designer.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('designer.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center"></i> Design Workspace
                </a>
            @endif

            {{-- ROLE 4: PRODUCTION STAFF --}}
            @if(auth()->user()->isProduction() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Production</p>

                <a href="{{ route('production.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('production.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-industry w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('production.jobs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('production.jobs.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-list-check w-5 text-center"></i> My Production Jobs
                </a>
                <a href="{{ route('production.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('production.notifications.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-bell w-5 text-center"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: INVENTORY STAFF --}}
            @if(auth()->user()->isInventory() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Material Inventory</p>

                <a href="{{ route('inventory.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-warehouse w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('inventory.materials.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.materials.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> Materials Catalog
                </a>
                <a href="{{ route('inventory.stock.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.stock.index') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-cubes-stacked w-5 text-center"></i> Branch Stock Levels
                </a>
                <a href="{{ route('inventory.stock-movements.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.stock-movements.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-arrow-right-left w-5 text-center"></i> Stock Movements
                </a>
                <a href="{{ route('inventory.availability') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.availability') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-square-check w-5 text-center"></i> Material Availability
                </a>
                <a href="{{ route('inventory.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('inventory.reports.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i> Inventory Reports
                </a>
            @endif

            {{-- ROLE 6: OWNER / MANAGEMENT --}}
            @if(auth()->user()->isManagement() || auth()->user()->isAdmin())
                <p class="px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider mb-2">Executive</p>

                <a href="{{ route('management.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.dashboard') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Management Dashboard
                </a>
                <a href="{{ route('management.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.orders.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i> Orders Overview
                </a>
                <a href="{{ route('management.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.branches.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-store w-5 text-center"></i> Branch Performance
                </a>
                <a href="{{ route('management.capacity') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.capacity') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-5 text-center"></i> Capacity Monitor
                </a>
                <a href="{{ route('management.production.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.production.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-microchip w-5 text-center"></i> Production Overview
                </a>
                <a href="{{ route('management.inventory.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.inventory.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i> Inventory Overview
                </a>
                <a href="{{ route('management.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.reports.*') ? 'bg-brand-500 text-white font-bold shadow-md shadow-brand-500/20' : 'hover:bg-navy-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center"></i> Executive Reports
                </a>
            @endif


        </nav>

        {{-- User Footer / Logout — PINNED BOTTOM --}}
        <div class="shrink-0 p-4 border-t border-navy-800 bg-navy-950/40">
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
            <div></div>

            <div class="flex items-center gap-4">
                @php
                    $notifRoute = match(auth()->user()->role) {
                        'production' => Route::has('production.notifications.index') ? route('production.notifications.index') : null,
                        'staff' => Route::has('staff.notifications.index') ? route('staff.notifications.index') : null,
                        default => null
                    };
                @endphp
                @if($notifRoute)
                <a href="{{ $notifRoute }}" class="relative p-2 text-slate-500 hover:text-navy-900 hover:bg-slate-100 rounded-xl transition flex items-center justify-center" title="Notifications">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-500 ring-2 ring-white"></span>
                        </span>
                    @endif
                </a>
                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                @endif

                <div class="hidden sm:flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold font-display text-xs shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="text-left">
                        <span class="text-xs font-bold text-slate-800 block leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-brand-600 font-bold uppercase tracking-wider bg-brand-50 px-2 py-0.5 rounded inline-block mt-0.5">{{ auth()->user()->role_label }}</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Session Flash Alert --}}
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>
</div>
@endsection
