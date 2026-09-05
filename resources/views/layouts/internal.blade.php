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
        }
     }">

    {{-- Mobile Overlay & Sidebar Toggle --}}
    <div class="md:hidden bg-white dark:bg-cyber-surface border-b border-cyber text-cyber-main px-4 py-3 flex items-center justify-between shadow-lg sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/caplogo.png') }}" alt="CapaciPrint Logo" class="h-8 w-auto object-contain shrink-0" onerror="this.onerror=null; this.src=''; this.classList.add('hidden');">
            <div>
                <span class="font-black text-sm tracking-wide font-display text-cyber-main">CAPACIPRINT</span>
                <span class="text-[9px] text-cyan-500 font-bold tracking-wider uppercase block">Production Planning</span>
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

    {{-- Dynamic Role-Based Collapsible Sidebar --}}
    <aside class="fixed md:sticky top-0 inset-y-0 left-0 z-50 shrink-0 bg-[#111A24] border-r border-slate-800/80 text-slate-400 flex flex-col h-screen overflow-hidden transition-all duration-300 ease-in-out shadow-2xl"
           :class="{
               'w-64': !sidebarCollapsed,
               'w-20': sidebarCollapsed,
               'translate-x-0': sidebarOpen,
               '-translate-x-full md:translate-x-0': !sidebarOpen
           }">

        {{-- Sidebar Brand — PINNED TOP --}}
        <div class="shrink-0 h-16 px-4 border-b border-slate-800/80 flex items-center justify-between bg-transparent">
            <div class="flex items-center gap-3 min-w-0" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <img src="{{ asset('images/caplogo.png') }}?v={{ time() }}" alt="CapaciPrint Logo" class="h-10 w-10 object-contain shrink-0 drop-shadow-[0_0_12px_rgba(6,182,212,0.35)]">
                <div class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity>
                    <h1 class="font-black text-white text-base font-display tracking-tight leading-none truncate">CAPACIPRINT</h1>
                    <span class="text-[10px] text-cyan-400 font-extrabold tracking-wider uppercase block mt-1 truncate">Production Planning</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav class="flex-1 min-h-0 px-3 py-3 space-y-0.5 overflow-y-auto no-scrollbar text-xs font-medium">

            {{-- ROLE 7: SYSTEM ADMINISTRATOR --}}
            @if(auth()->user()->isAdmin())
                <p class="px-3 pt-1 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">System Administration</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-table-cells-large w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-sm"></i> User &amp; Access Mgmt
                </a>
                <a href="{{ route('admin.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.branches.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-network-wired w-5 text-center text-sm"></i> Branch Management
                </a>
                <a href="{{ route('management.audit-logs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('management.audit-logs.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center text-sm"></i> System Audit Logs
                </a>
            @endif

            {{-- ROLE 4: BRANCH MANAGER --}}
            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Branch &amp; Capacity</p>

                <a href="{{ route('manager.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.purchasing.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-sm"></i> Purchase Requests
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm"></i> Operational Reports
                </a>
            @endif

            {{-- ROLE 2: SALES / CUSTOMER SERVICE STAFF --}}
            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Sales &amp; Service</p>

                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-message w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('staff.print-requests.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.print-requests.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-sm"></i> Customer Requests
                </a>
                <a href="{{ route('staff.quotations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.quotations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm"></i> Quotations
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.orders.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-box-archive w-5 text-center text-sm"></i> Orders
                </a>
                <a href="{{ route('staff.claim-scanner') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.claim-scanner') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-sm"></i> Claim &amp; QR Scanner
                </a>
                <a href="{{ route('staff.pricing-rules.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.pricing-rules.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-dollar-sign w-5 text-center text-sm"></i> Pricing Rules
                </a>
                <a href="{{ route('staff.customers.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.customers.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm"></i> Customers
                </a>
                <a href="{{ route('staff.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('staff.notifications.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-bell w-5 text-center text-sm"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: PRODUCTION OFFICER --}}
            @if(auth()->user()->isProductionOfficer() || auth()->user()->isManager() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Operations Planning</p>

                <a href="{{ route('manager.production-planning.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.production-planning.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center text-sm"></i> Production Planning
                </a>
                <a href="{{ route('manager.capacity.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.capacity.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm"></i> Capacity Evaluation
                </a>
                <a href="{{ route('manager.recommendations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.recommendations.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-building-circle-check w-5 text-center text-sm"></i> Branch Recommendations
                </a>
                <a href="{{ route('manager.workload.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('manager.workload.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center text-sm"></i> Workload Monitor
                </a>
            @endif

            {{-- ROLE 7: LAYOUT DESIGNER / PRE-PRESS --}}
            @if(auth()->user()->isDesigner() || auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Design &amp; Layout</p>

                <a href="{{ route('designer.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('designer.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center text-sm"></i> Design Workspace
                </a>
            @endif

            {{-- ROLE 4: PRODUCTION STAFF --}}
            @if(auth()->user()->isProduction() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Production</p>

                <a href="{{ route('production.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-industry w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('production.jobs.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.jobs.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-list-check w-5 text-center text-sm"></i> My Production Jobs
                </a>
                <a href="{{ route('production.notifications.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('production.notifications.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-bell w-5 text-center text-sm"></i> Notifications
                </a>
            @endif

            {{-- ROLE 5: INVENTORY STAFF --}}
            @if(auth()->user()->isInventory() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Material Inventory</p>

                <a href="{{ route('inventory.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-warehouse w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('inventory.materials.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.materials.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm"></i> Materials Catalog
                </a>
                <a href="{{ route('inventory.stock.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock.index') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-cubes-stacked w-5 text-center text-sm"></i> Branch Stock Levels
                </a>
                <a href="{{ route('inventory.stock-movements.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock-movements.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-right-left w-5 text-center text-sm"></i> Stock Movements
                </a>
                <a href="{{ route('inventory.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition {{ request()->routeIs('inventory.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-file-lines w-5 text-center text-sm"></i> Inventory Reports
                </a>
            @endif

            {{-- ROLE 6: OWNER / MANAGEMENT --}}
            @if(auth()->user()->isManagement() || auth()->user()->isAdmin())
                <p class="px-3 pt-1.5 text-[10px] uppercase font-bold text-cyber-sub tracking-wider mb-1">Executive</p>

                <a href="{{ route('management.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.dashboard') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm"></i> Dashboard
                </a>
                <a href="{{ route('management.orders.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.orders.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm"></i> Orders Overview
                </a>
                <a href="{{ route('management.branches.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.branches.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-store w-5 text-center text-sm"></i> Branch Performance
                </a>
                <a href="{{ route('management.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2 rounded-xl transition whitespace-nowrap {{ request()->routeIs('management.reports.*') ? 'bg-cyan-500/15 text-cyan-400 font-bold shadow-[0_0_15px_rgba(6,182,212,0.15)]' : 'text-cyber-muted hover:bg-slate-800/60 hover:text-slate-100' }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm"></i> Executive Reports
                </a>
            @endif

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
        <header class="h-16 bg-cyber-surface/90 backdrop-blur-md border-b border-cyber px-6 flex items-center justify-between sticky top-0 z-30 shadow-md">
            <div class="flex items-center gap-3">
                {{-- Mobile toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-cyber-muted hover:text-cyber-main p-1.5 rounded-lg border border-cyber hover:bg-cyber-sub focus:outline-none transition">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>

            <div class="flex items-center gap-4 sm:gap-5">
                @php
                    $notifRoute = match(auth()->user()->role) {
                        'production' => Route::has('production.notifications.index') ? route('production.notifications.index') : null,
                        'staff' => Route::has('staff.notifications.index') ? route('staff.notifications.index') : null,
                        default => null
                    };
                @endphp
                @if($notifRoute)
                <a href="{{ $notifRoute }}" class="relative p-2 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-xl transition flex items-center justify-center border border-cyber/50" title="Notifications">
                    <i class="fa-solid fa-bell text-base"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500 ring-2 ring-[#0D1520]"></span>
                        </span>
                    @endif
                </a>
                @endif

                {{-- Interactive User Profile Menu (Slide-out Dropdown with Adaptive Dark/Light Mode) --}}
                <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                    {{-- Trigger Pill Button --}}
                    <button @click="profileOpen = !profileOpen" 
                            type="button"
                            class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl bg-cyber-sub border border-cyber hover:border-cyan-500/40 text-cyber-main transition shadow-sm group focus:outline-none select-none cursor-pointer"
                            :class="profileOpen ? 'ring-2 ring-cyan-500/30 border-cyan-500/50' : ''">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 text-slate-950 font-black flex items-center justify-center text-xs shadow-[0_0_12px_rgba(6,182,212,0.35)] shrink-0 border border-cyan-300 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="text-xs font-bold text-cyber-main block group-hover:text-cyan-400 transition-colors">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-cyan-600 dark:text-cyan-400 font-extrabold uppercase tracking-wider block">{{ auth()->user()->role_label }}</span>
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

                        {{-- Settings Option --}}
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/70 hover:text-slate-900 dark:hover:text-white transition font-medium">
                            <i class="fa-solid fa-gear w-4 text-center text-slate-400"></i>
                            <span>Settings</span>
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

