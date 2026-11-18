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
                <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-semibold block leading-tight">Production Planning</span>
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

    {{-- Dynamic Role-Based Collapsible Sidebar --}}
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
                    <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-semibold block mt-1 truncate">Production ERP</span>
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
        <nav id="sidebar-nav" class="flex-1 min-h-0 px-2.5 py-3 space-y-0.5 overflow-y-auto no-scrollbar text-[13px] font-sans">

            {{-- ROLE 7: SYSTEM ADMINISTRATOR --}}
            @if(auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">System Administration</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('admin.dashboard') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-table-cells-large w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   title="User &amp; Access Mgmt"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">User &amp; Access Mgmt</span>
                </a>
                <a href="{{ route('admin.branches.index') }}"
                   title="Branch Management"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.branches.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-network-wired w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Management</span>
                </a>
                <a href="{{ route('management.audit-logs.index') }}"
                   title="System Audit Logs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('management.audit-logs.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">System Audit Logs</span>
                </a>
            @endif

            {{-- ROLE 4: BRANCH MANAGER --}}
            @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Branch &amp; Capacity</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('manager.dashboard') }}"
                   title="Branch Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Dashboard</span>
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   title="Purchase Requests"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.purchasing.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Purchase Requests</span>
                </a>
                <a href="{{ route('manager.pricing-rules.index') }}"
                   title="Pricing Rules Matrix"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.pricing-rules.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Pricing Rules</span>
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   title="Operational Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Operational Reports</span>
                </a>
            @endif

            {{-- ROLE 2: SALES / CUSTOMER SERVICE STAFF --}}
            @if(auth()->user()->isStaff() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Sales &amp; Service</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('staff.dashboard') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-message w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('staff.print-requests.index') }}"
                   title="Customer Requests"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.print-requests.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Customer Requests</span>
                </a>
                <a href="{{ route('designer.index') }}"
                   title="Artwork &amp; Proofs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('designer.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Artwork &amp; Proofs</span>
                </a>
                <a href="{{ route('staff.quotations.index') }}"
                   title="Quotations"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.quotations.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Quotations</span>
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   title="Orders"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.orders.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-box-archive w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Orders</span>
                </a>
                <a href="{{ route('staff.claim-scanner') }}"
                   title="Claim &amp; QR Scanner"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.claim-scanner') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Claim &amp; QR Scanner</span>
                </a>
                <a href="{{ route('staff.customers.index') }}"
                   title="Customers"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('staff.customers.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Customers</span>
                </a>
            @endif

            {{-- ROLE 5: PRODUCTION OFFICER --}}
            @if(auth()->user()->isProductionOfficer() || auth()->user()->isManager() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Operations Planning</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('manager.production-planning.index') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.production-planning.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('manager.capacity.index') }}"
                   title="Capacity Evaluation"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.capacity.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Capacity Evaluation</span>
                </a>
                <a href="{{ route('manager.recommendations.index') }}"
                   title="Branch Recommendations"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.recommendations.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-building-circle-check w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Recommendations</span>
                </a>
                <a href="{{ route('manager.workload.index') }}"
                   title="Workload Monitor"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('manager.workload.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-line w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Workload Monitor</span>
                </a>
            @endif



            {{-- ROLE 4: PRODUCTION STAFF --}}
            @if(auth()->user()->isProduction() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Production</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('production.dashboard') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('production.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-industry w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('production.jobs.index') }}"
                   title="My Production Jobs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('production.jobs.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-list-check w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">My Production Jobs</span>
                </a>
            @endif

            {{-- ROLE 5: INVENTORY STAFF --}}
            @if(auth()->user()->isInventory() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Material Inventory</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('inventory.dashboard') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('inventory.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-warehouse w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('inventory.materials.index') }}"
                   title="Materials Catalog"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('inventory.materials.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Materials Catalog</span>
                </a>
                <a href="{{ route('inventory.stock.index') }}"
                   title="Branch Stock Levels"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock.index') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-cubes-stacked w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Stock Levels</span>
                </a>
                <a href="{{ route('inventory.stock-movements.index') }}"
                   title="Stock Movements"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('inventory.stock-movements.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-right-left w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Stock Movements</span>
                </a>
                <a href="{{ route('inventory.reports.index') }}"
                   title="Inventory Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('inventory.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-lines w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Inventory Reports</span>
                </a>
            @endif

            {{-- ROLE 6: OWNER / MANAGEMENT --}}
            @if(auth()->user()->isManagement() || auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-[11px] font-sans uppercase font-bold text-cyber-sub tracking-wider">Executive</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('management.dashboard') }}"
                   title="Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('management.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>
                <a href="{{ route('management.orders.index') }}"
                   title="Order Management"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('management.orders.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Order Management</span>
                </a>
                <a href="{{ route('management.branches.index') }}"
                   title="Branch Performance"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('management.branches.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-store w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Performance</span>
                </a>
                <a href="{{ route('management.reports.index') }}"
                   title="Executive Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('management.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Executive Reports</span>
                </a>
            @endif

        </nav>

        {{-- Sidebar Footer Plate — PINNED BOTTOM --}}
        <div class="shrink-0 border-t border-cyber p-2.5 bg-cyber-card/40 flex items-center justify-between gap-2" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div x-show="!sidebarCollapsed" x-transition.opacity class="min-w-0 pl-1">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-xs font-sans font-semibold text-cyber-main truncate">{{ auth()->user()->role_label }}</span>
                </div>
                <p class="text-[11px] font-sans text-cyber-sub truncate mt-0.5">
                    {{ auth()->user()->branch->name ?? 'Network Hub' }}
                </p>
            </div>
            <button @click="toggleSidebar()"
                    type="button"
                    aria-label="Toggle navigation sidebar"
                    class="h-8 w-8 shrink-0 rounded-lg bg-cyber-card hover:border-blue-600/40 dark:hover:border-blue-500/40 text-cyber-muted hover:text-cyber-main border border-cyber flex items-center justify-center transition cursor-pointer"
                    :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                <i class="fa-solid text-xs transition-transform duration-200" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
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
                {{-- Desktop collapse toggle in header --}}
                <button @click="toggleSidebar()" aria-label="Collapse or expand sidebar" class="hidden md:flex items-center justify-center h-8 w-8 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-lg border border-transparent hover:border-cyber transition cursor-pointer shrink-0"
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                    <i class="fa-solid text-xs transition-transform duration-200" :class="sidebarCollapsed ? 'fa-indent text-blue-600 dark:text-blue-400' : 'fa-outdent'"></i>
                </button>

                {{-- Breadcrumb & Active Branch Plate --}}
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="hidden lg:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-cyber-sub border border-cyber text-xs font-sans font-semibold text-cyber-main shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        @if(auth()->user()->branch)
                            {{ auth()->user()->branch->name }}
                        @else
                            Network Hub
                        @endif
                    </span>
                    <span class="hidden lg:inline text-cyber-sub text-xs">/</span>
                    <span class="text-xs sm:text-sm font-display font-bold text-cyber-main truncate tracking-tight">
                        @yield('page-title', 'Operations Workspace')
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                {{-- Live Operational Date --}}
                <div class="hidden xl:flex items-center gap-2 text-xs font-sans font-medium text-cyber-muted tabular-nums pr-1">
                    <i class="fa-regular fa-calendar text-blue-600 dark:text-blue-400 text-xs"></i>
                    <span>{{ now()->format('D, M j, Y') }}</span>
                </div>

                {{-- Segmented Theme Toggle Pill --}}
                <button @click="toggleTheme()" 
                        type="button"
                        aria-label="Toggle Light or Dark Mode"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyber-sub border border-cyber text-xs font-sans font-semibold text-cyber-main hover:border-blue-600/40 dark:hover:border-blue-500/40 transition cursor-pointer"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i :class="isDark ? 'fa-solid fa-moon text-blue-400' : 'fa-solid fa-sun text-amber-500'" class="text-xs"></i>
                    <span x-text="isDark ? 'Dark Mode' : 'Light Mode'"></span>
                </button>
                @php
                    $notifRoute = match(auth()->user()->role) {
                        'production' => Route::has('production.notifications.index') ? route('production.notifications.index') : null,
                        'staff' => Route::has('staff.notifications.index') ? route('staff.notifications.index') : null,
                        'designer' => Route::has('designer.index') ? route('designer.index', ['status' => 'revision_requested']) : null,
                        default => null
                    };
                @endphp
                @if($notifRoute)
                <a href="{{ $notifRoute }}" aria-label="Notifications" class="relative h-8 w-8 text-cyber-muted hover:text-cyber-main bg-cyber-sub/60 hover:bg-cyber-sub border border-cyber rounded-lg transition flex items-center justify-center" title="Notifications">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                    @endif
                </a>
                @endif

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
                            <span class="text-[11px] font-sans text-blue-600 dark:text-blue-400 font-medium block">{{ auth()->user()->role_label }}</span>
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
                         class="absolute right-0 mt-2 w-60 rounded-xl bg-cyber-card border border-cyber shadow-lg z-50 p-1.5 text-xs space-y-1"
                         x-cloak>

                        @if(Route::has('admin.settings.index') && auth()->user()->isAdmin())
                        <a href="{{ route('admin.settings.index') }}" 
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] transition font-medium">
                            <i class="fa-solid fa-gear w-4 text-center text-cyber-muted"></i>
                            <span>Settings</span>
                        </a>
                        @endif

                        <div class="border-t border-cyber-sub my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-500/10 transition font-semibold border border-transparent hover:border-rose-500/20">
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
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 3500)"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mx-6 mt-6 p-3.5 bg-emerald-500/10 border border-emerald-500/30 border-l-4 border-l-emerald-600 rounded-lg text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                <p class="font-semibold flex-1">{{ session('success') }}</p>
                <button type="button" @click="show = false" class="text-emerald-700/70 hover:text-emerald-900 dark:text-emerald-300/70 dark:hover:text-emerald-100 transition p-1">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mx-6 mt-6 p-3.5 bg-rose-500/10 border border-rose-500/30 border-l-4 border-l-rose-600 rounded-lg text-xs text-rose-800 dark:text-rose-300 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400 text-sm"></i>
                <p class="font-semibold flex-1">{{ session('error') }}</p>
                <button type="button" @click="show = false" class="text-rose-700/70 hover:text-rose-900 dark:text-rose-300/70 dark:hover:text-rose-100 transition p-1">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6 md:p-8">
            @yield('content')
        </main>

    </div>
</div>
<script>
(function () {
    const NAV_KEY = 'sidebar_scroll_pos';
    const nav = document.getElementById('sidebar-nav');

    // ── Restore position immediately (before first paint if possible) ──
    if (nav) {
        const saved = sessionStorage.getItem(NAV_KEY);
        if (saved !== null) {
            nav.scrollTop = parseInt(saved, 10);
        }
    }

    // ── Save position whenever any sidebar link is clicked ──
    if (nav) {
        nav.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]');
            if (link) {
                sessionStorage.setItem(NAV_KEY, nav.scrollTop);
            }
        });
    }
})();
</script>
@endsection

