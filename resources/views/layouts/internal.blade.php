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
    <div class="md:hidden bg-cyber-surface border-b border-cyber text-cyber-main px-4 py-3 flex items-center justify-between shadow-lg sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-8 w-auto object-contain shrink-0" onerror="this.onerror=null; this.src=''; this.classList.add('hidden');">
            <div>
                <span class="font-black text-sm tracking-wide font-display text-cyber-main">CAPACIPRINT</span>
                <span class="text-[9px] text-cyan-500 font-bold tracking-wider uppercase block">Production Planning</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="toggleTheme()" class="p-2 rounded-lg text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub transition" title="Toggle Theme">
                <i :class="isDark ? 'fa-solid fa-moon text-cyan-400' : 'fa-solid fa-sun text-amber-500'" class="text-base"></i>
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

    {{-- Dynamic Role-Based Sidebar --}}
    <aside class="fixed md:sticky top-0 inset-y-0 left-0 z-50 w-64 shrink-0 bg-cyber-surface border-r border-cyber text-cyber-muted flex flex-col h-screen overflow-hidden transition-transform duration-200 ease-in-out shadow-2xl"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 p-5 border-b border-cyber flex items-center justify-between bg-cyber-base/50">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-9 w-auto object-contain shrink-0 drop-shadow-[0_0_12px_rgba(6,182,212,0.3)]">
                <div>
                    <h1 class="font-black text-cyber-main text-base font-display tracking-tight leading-none">CAPACIPRINT</h1>
                    <span class="text-[10px] text-cyan-500 font-extrabold tracking-wider uppercase block mt-1">Production Planning</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav class="flex-1 min-h-0 px-3 py-3 space-y-0.5 overflow-y-auto no-scrollbar text-xs font-medium">

            {{-- ROLE 7: SYSTEM ADMINISTRATOR --}}
            @if(auth()->user()->isAdmin())
                <p class="px-3 pt-1 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">System Administration</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-table-cells-large w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-sm"></i> User &amp; Access Mgmt
                </a>
                <a href="{{ route('admin.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.branches.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-network-wired w-5 text-center text-sm"></i> Branch Management
                </a>
                <a href="{{ route('management.audit-logs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('management.audit-logs.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center text-sm"></i> System Audit Logs
                </a>
            @endif

            {{-- ROLE 4: BRANCH MANAGER --}}
            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Branch &amp; Capacity</p>

                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.purchasing.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-sm"></i> Purchase Requests
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm"></i> Operational Reports
                </a>
            @endif

            {{-- ROLE 2: SALES / CUSTOMER SERVICE STAFF --}}
            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Sales &amp; Service</p>

                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-message w-5 text-center text-sm"></i> CS Dashboard
                </a>
                <a href="{{ route('staff.print-requests.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.print-requests.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-sm"></i> Customer Requests
                </a>
                <a href="{{ route('staff.quotations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.quotations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm"></i> Quotations
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.orders.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-box-archive w-5 text-center text-sm"></i> Orders
                </a>
                <a href="{{ route('staff.claim-scanner') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.claim-scanner') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-sm"></i> Claim &amp; QR Scanner
                </a>
                <a href="{{ route('staff.pricing-rules.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.pricing-rules.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-dollar-sign w-5 text-center text-sm"></i> Pricing Rules
                </a>
                <a href="{{ route('staff.customers.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.customers.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm"></i> Customers
                </a>
                <a href="{{ route('staff.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.notifications.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-bell w-5 text-center text-sm"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: PRODUCTION OFFICER --}}
            @if(auth()->user()->isProductionOfficer() || auth()->user()->isManager() || auth()->user()->isSuperAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Operations Planning</p>

                <a href="{{ route('manager.production-planning.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.production-planning.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center text-sm"></i> Production Planning
                </a>
                <a href="{{ route('manager.capacity.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.capacity.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm"></i> Capacity Evaluation
                </a>
                <a href="{{ route('manager.recommendations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.recommendations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-building-circle-check w-5 text-center text-sm"></i> Branch Recommendations
                </a>
                <a href="{{ route('manager.workload.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.workload.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center text-sm"></i> Workload Monitor
                </a>
            @endif

            {{-- ROLE 7: LAYOUT DESIGNER / PRE-PRESS --}}
            @if(auth()->user()->isDesigner() || auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Design &amp; Layout</p>

                <a href="{{ route('designer.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('designer.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center text-sm"></i> Design Workspace
                </a>
            @endif

            {{-- ROLE 4: PRODUCTION STAFF --}}
            @if(auth()->user()->isProduction() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Production</p>

                <a href="{{ route('production.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-industry w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('production.jobs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.jobs.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-list-check w-5 text-center text-sm"></i> My Production Jobs
                </a>
                <a href="{{ route('production.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.notifications.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-bell w-5 text-center text-sm"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: INVENTORY STAFF --}}
            @if(auth()->user()->isInventory() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Material Inventory</p>

                <a href="{{ route('inventory.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-warehouse w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('inventory.materials.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.materials.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm"></i> Materials Catalog
                </a>
                <a href="{{ route('inventory.stock.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock.index') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-cubes-stacked w-5 text-center text-sm"></i> Branch Stock Levels
                </a>
                <a href="{{ route('inventory.stock-movements.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock-movements.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-right-left w-5 text-center text-sm"></i> Stock Movements
                </a>
                <a href="{{ route('inventory.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center text-sm"></i> Inventory Reports
                </a>
            @endif

            {{-- ROLE 6: OWNER / MANAGEMENT --}}
            @if(auth()->user()->isManagement() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Executive</p>

                <a href="{{ route('management.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm"></i> Management Dashboard
                </a>
                <a href="{{ route('management.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.orders.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm"></i> Orders Overview
                </a>
                <a href="{{ route('management.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.branches.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-store w-5 text-center text-sm"></i> Branch Performance
                </a>
                <a href="{{ route('management.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm"></i> Executive Reports
                </a>
            @endif

        </nav>

        {{-- User Footer / Logout — PINNED BOTTOM --}}
        <div class="shrink-0 p-4 border-t border-cyber bg-cyber-base/50">
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
        <header class="bg-cyber-surface/90 backdrop-blur-md border-b border-cyber px-6 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div></div>

            <div class="flex items-center gap-4 sm:gap-5">
                {{-- Theme Switch Button (Light / Dark Mode) --}}
                <button @click="toggleTheme()" 
                        class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-cyber-sub border border-cyber text-cyber-main hover:border-cyan-500/40 text-xs font-bold transition shadow-sm group"
                        title="Toggle Light / Dark Theme">
                    <span class="flex items-center gap-1.5" :class="isDark ? 'text-cyan-400' : 'text-amber-500'">
                        <i :class="isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun'" class="text-sm"></i>
                        <span class="hidden sm:inline text-[11px]" x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
                    </span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-cyber-card border border-cyber text-cyber-muted uppercase tracking-wider font-mono group-hover:text-cyber-main">Toggle</span>
                </button>

                {{-- Live System Status Indicator --}}
                <div class="hidden md:flex items-center gap-3 px-3.5 py-1.5 rounded-xl bg-cyber-sub border border-cyber text-xs shadow-inner"
                     title="Total Network Active Jobs: {{ $globalActiveJobs ?? 0 }} / Max Daily Cap: {{ $globalTotalCapacity ?? 0 }}">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-cyber-muted uppercase tracking-wider">System Load</span>
                        <span class="text-xs font-black font-display font-mono {{ ($globalSystemLoad ?? 0) >= 80 ? 'text-red-400' : (($globalSystemLoad ?? 0) >= 50 ? 'text-amber-400' : 'text-cyan-400') }}">
                            {{ number_format($globalSystemLoad ?? 0, 1) }}%
                        </span>
                        <span class="flex items-center gap-1 text-[9px] font-black uppercase px-1.5 py-0.5 rounded border {{ ($globalSystemLoad ?? 0) >= 80 ? 'bg-red-500/10 text-red-400 border-red-500/20' : (($globalSystemLoad ?? 0) >= 50 ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20') }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ ($globalSystemLoad ?? 0) >= 80 ? 'bg-red-400' : (($globalSystemLoad ?? 0) >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }} animate-pulse"></span>
                            {{ ($globalSystemLoad ?? 0) >= 80 ? 'HIGH' : (($globalSystemLoad ?? 0) >= 50 ? 'MODERATE' : 'OPTIMAL') }}
                        </span>
                    </div>
                    {{-- Dynamic Mini Sparkline Bar / Gauge --}}
                    <div class="w-12 h-1.5 bg-cyber-card rounded-full overflow-hidden border border-cyber">
                        <div class="h-full {{ ($globalSystemLoad ?? 0) >= 80 ? 'bg-red-500' : (($globalSystemLoad ?? 0) >= 50 ? 'bg-amber-400' : 'bg-gradient-to-r from-cyan-400 to-emerald-400') }} rounded-full"
                             style="width: {{ min(max($globalSystemLoad ?? 0, 8), 100) }}%"></div>
                    </div>
                </div>

                @php
                    $notifRoute = match(auth()->user()->role) {
                        'production' => Route::has('production.notifications.index') ? route('production.notifications.index') : null,
                        'staff' => Route::has('staff.notifications.index') ? route('staff.notifications.index') : null,
                        default => null
                    };
                @endphp
                @if($notifRoute)
                <a href="{{ $notifRoute }}" class="relative p-2 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-xl transition flex items-center justify-center" title="Notifications">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500 ring-2 ring-[#0D1520]"></span>
                        </span>
                    @endif
                </a>
                <div class="h-6 w-px bg-cyber hidden sm:block"></div>
                @endif

                {{-- User Profile Chip --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <span class="text-xs font-bold text-cyber-main block leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-cyan-500 font-bold uppercase tracking-wider block mt-0.5">{{ auth()->user()->role_label }}</span>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-400 to-cyan-600 text-slate-950 font-black flex items-center justify-center text-xs shadow-[0_0_15px_rgba(6,182,212,0.35)] shrink-0 border border-cyan-300">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Session Flash Alert --}}
        @if(session('success'))
            <div class="mx-6 mt-6 p-4 bg-emerald-950/40 border border-emerald-500/30 rounded-2xl text-xs text-emerald-300 flex items-center gap-3 shadow-lg backdrop-blur-md">
                <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 p-4 bg-red-950/40 border border-red-500/30 rounded-2xl text-xs text-red-300 flex items-center gap-3 shadow-lg backdrop-blur-md">
                <i class="fa-solid fa-circle-exclamation text-red-400 text-base"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6 md:p-8">
            @yield('content')
        </main>

    </div>
</div>
@endsection

