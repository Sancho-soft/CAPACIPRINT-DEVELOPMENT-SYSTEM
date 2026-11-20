@extends('layouts.app')

@section('body')
<div class="min-h-screen pt-0 bg-cyber-base flex flex-col md:flex-row font-sans text-cyber-main selection:bg-[#2F7BFF]/20 selection:text-blue-700 dark:selection:text-blue-200" 
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
                document.getElementById('meta-theme-color')?.setAttribute('content', '#0A0F1C');
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
                <div class="flex items-center gap-2">
                    <span class="font-bold text-sm tracking-tight font-display text-cyber-main">CAPACIPRINT</span>
                    <span class="inline-flex items-center gap-0.5" aria-hidden="true">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2F7BFF]"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#E5337A]"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FFC531]"></span>
                    </span>
                </div>
                <span class="text-[12px] font-sans text-cyber-muted font-medium block leading-tight">Press Operations</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="toggleTheme()" 
                    type="button"
                    aria-label="Toggle Light or Dark Theme"
                    class="h-9 w-9 rounded-lg bg-cyber-sub border border-cyber flex items-center justify-center text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF]/50 transition cursor-pointer"
                    :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                <i :class="isDark ? 'fa-solid fa-sun text-[#FFC531]' : 'fa-solid fa-moon text-slate-600'" class="text-sm"></i>
            </button>
            <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Menu" class="text-cyber-muted hover:text-cyber-main p-2 rounded-lg focus:outline-none">
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
                    <div class="flex items-center gap-2">
                        <h1 class="font-bold text-cyber-main text-sm font-display tracking-tight leading-none truncate">CAPACIPRINT</h1>
                        <span class="inline-flex items-center gap-0.5 shrink-0" aria-hidden="true">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#2F7BFF]"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#E5337A]"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FFC531]"></span>
                        </span>
                    </div>
                    <span class="text-[12px] font-sans text-cyber-muted font-medium block mt-0.5 truncate">Press Operations</span>
                </div>
            </div>
            <button @click="sidebarOpen = false" aria-label="Close navigation" class="md:hidden text-cyber-muted hover:text-cyber-main p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        @php
            $navActive = 'bg-[#2F7BFF]/12 text-[#2F7BFF] dark:text-[#60A5FA] font-semibold border border-[#2F7BFF]/25';
            $navIdle   = 'text-cyber-muted hover:text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.04] font-medium border border-transparent';
        @endphp

        {{-- Role Navigation Links — SCROLLABLE MIDDLE --}}
        <nav id="sidebar-nav" class="flex-1 min-h-0 px-2.5 py-3 space-y-0.5 overflow-y-auto no-scrollbar text-[13px] font-sans">

            {{-- ADMIN UNIFIED NAVIGATION (Clean, non-duplicated sidebar) --}}
            @if(auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">System Administration</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('admin.dashboard') }}"
                   title="Overview"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-table-cells-large w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Overview</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                   title="Staff &amp; Users"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.users.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-users-gear w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Staff &amp; Users</span>
                </a>
                <a href="{{ route('admin.branches.index') }}"
                   title="Press Plants &amp; Branches"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.branches.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-network-wired w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Press Plants</span>
                </a>
                <a href="{{ route('admin.employees.index') }}"
                   title="Employees &amp; Crew"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.employees.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-id-card-clip w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Employees &amp; Crew</span>
                </a>
                <a href="{{ route('management.audit-logs.index') }}"
                   title="System Audit Logs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.audit-logs.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">System Audit Logs</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   title="Account Settings"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.settings.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-sliders w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Account Settings</span>
                </a>

                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-4 pb-1.5 text-xs font-semibold text-cyber-muted">Shop Floor Portals</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('manager.dashboard') }}"
                   title="Branch &amp; Capacity Hub"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.dashboard', 'manager.capacity.*', 'manager.recommendations.*', 'manager.workload.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch &amp; Capacity Hub</span>
                </a>
                <a href="{{ route('manager.production-planning.index') }}"
                   title="Production Scheduling"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.production-planning.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-calendar-check w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Production Scheduling</span>
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   title="Material &amp; Roll Purchasing"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.purchasing.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Material Purchasing</span>
                </a>
                <a href="{{ route('staff.dashboard') }}"
                   title="Customer Service &amp; Intake"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-headset w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Customer Service &amp; Orders</span>
                </a>
                <a href="{{ route('designer.index') }}"
                   title="Pre-Press &amp; Proofing"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('designer.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Pre-Press &amp; Proofing</span>
                </a>
                <a href="{{ route('production.dashboard') }}"
                   title="Press Floor Queue"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('production.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-industry w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Press Floor Queue</span>
                </a>
                <a href="{{ route('inventory.dashboard') }}"
                   title="Paper &amp; Roll Inventory"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-warehouse w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Paper &amp; Roll Inventory</span>
                </a>
                <a href="{{ route('management.dashboard') }}"
                   title="Executive Analytics"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.dashboard', 'management.orders.*', 'management.branches.*', 'management.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Executive Analytics</span>
                </a>
            @endif

            {{-- ROLE: BRANCH MANAGER (Non-Admin) --}}
            @if(auth()->user()->isManager() && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">Branch &amp; Capacity</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('manager.dashboard') }}"
                   title="Branch Dashboard"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Dashboard</span>
                </a>
                <a href="{{ route('manager.purchasing.index') }}"
                   title="Purchase Requests"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.purchasing.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Purchase Requests</span>
                </a>
                <a href="{{ route('manager.pricing-rules.index') }}"
                   title="Pricing Rules Matrix"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.pricing-rules.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Pricing Rules</span>
                </a>
                <a href="{{ route('manager.reports.index') }}"
                   title="Operational Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Operational Reports</span>
                </a>
            @endif

            {{-- ROLE: SALES / CUSTOMER SERVICE STAFF (Non-Admin) --}}
            @if(auth()->user()->isStaff() && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">Sales &amp; Intake</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('staff.dashboard') }}"
                   title="Overview"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-message w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Overview</span>
                </a>
                <a href="{{ route('staff.print-requests.index') }}"
                   title="Customer Requests"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.print-requests.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-signature w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Customer Requests</span>
                </a>
                <a href="{{ route('designer.index') }}"
                   title="Pre-Press &amp; Proofs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('designer.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-wand-magic-sparkles w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Pre-Press &amp; Proofs</span>
                </a>
                <a href="{{ route('staff.quotations.index') }}"
                   title="Quotations"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.quotations.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Quotations</span>
                </a>
                <a href="{{ route('staff.orders.index') }}"
                   title="Orders"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.orders.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-box-archive w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Orders</span>
                </a>
                <a href="{{ route('staff.claim-scanner') }}"
                   title="Claim &amp; QR Scanner"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.claim-scanner') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-qrcode w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Claim &amp; QR Scanner</span>
                </a>
                <a href="{{ route('staff.customers.index') }}"
                   title="Customers"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('staff.customers.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Customers</span>
                </a>
            @endif

            {{-- ROLE: PRODUCTION OFFICER / MANAGER PLANNING (Non-Admin) --}}
            @if((auth()->user()->isProductionOfficer() || auth()->user()->isManager()) && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-3 pb-1.5 text-xs font-semibold text-cyber-muted">Operations Planning</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('manager.production-planning.index') }}"
                   title="Production Schedule"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.production-planning.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Production Schedule</span>
                </a>
                <a href="{{ route('manager.capacity.index') }}"
                   title="Capacity Evaluation"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.capacity.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-calculator w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Capacity Evaluation</span>
                </a>
                <a href="{{ route('manager.recommendations.index') }}"
                   title="Branch Routing"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.recommendations.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-building-circle-check w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Routing</span>
                </a>
                <a href="{{ route('manager.workload.index') }}"
                   title="Workload Monitor"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('manager.workload.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-line w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Workload Monitor</span>
                </a>
            @endif

            {{-- ROLE: PRODUCTION OPERATOR (Non-Admin) --}}
            @if(auth()->user()->isProduction() && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">Press Floor</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('production.dashboard') }}"
                   title="Overview"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('production.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-industry w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Overview</span>
                </a>
                <a href="{{ route('production.jobs.index') }}"
                   title="My Production Jobs"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('production.jobs.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-list-check w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">My Production Jobs</span>
                </a>
            @endif

            {{-- ROLE: INVENTORY STAFF (Non-Admin) --}}
            @if(auth()->user()->isInventory() && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">Paper &amp; Inventory</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('inventory.dashboard') }}"
                   title="Overview"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-warehouse w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Overview</span>
                </a>
                <a href="{{ route('inventory.materials.index') }}"
                   title="Materials Catalog"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.materials.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Materials Catalog</span>
                </a>
                <a href="{{ route('inventory.stock.index') }}"
                   title="Branch Stock Levels"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.stock.index') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-cubes-stacked w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Stock Levels</span>
                </a>
                <a href="{{ route('inventory.stock-movements.index') }}"
                   title="Stock Movements"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.stock-movements.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-right-left w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Stock Movements</span>
                </a>
                <a href="{{ route('inventory.reports.index') }}"
                   title="Inventory Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('inventory.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-file-lines w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Inventory Reports</span>
                </a>
            @endif

            {{-- ROLE: OWNER / MANAGEMENT (Non-Admin) --}}
            @if(auth()->user()->isManagement() && !auth()->user()->isAdmin())
                <p x-show="!sidebarCollapsed" x-cloak class="px-3 pt-1 pb-1.5 text-xs font-semibold text-cyber-muted">Executive</p>
                <div x-show="sidebarCollapsed" x-cloak class="w-full border-t border-cyber my-2"></div>

                <a href="{{ route('management.dashboard') }}"
                   title="Overview"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.dashboard') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Overview</span>
                </a>
                <a href="{{ route('management.orders.index') }}"
                   title="Order Management"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.orders.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Order Management</span>
                </a>
                <a href="{{ route('management.branches.index') }}"
                   title="Branch Performance"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.branches.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-store w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Branch Performance</span>
                </a>
                <a href="{{ route('management.reports.index') }}"
                   title="Executive Reports"
                   :class="sidebarCollapsed ? 'justify-center px-0' : ''"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('management.reports.*') ? $navActive : $navIdle }}">
                    <i class="fa-solid fa-chart-column w-5 text-center text-sm shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">Executive Reports</span>
                </a>
            @endif

        </nav>

        {{-- Sidebar Footer Plate — PINNED BOTTOM --}}
        <div class="shrink-0 border-t border-cyber p-2.5 bg-cyber-card/40 flex items-center justify-between gap-2" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div x-show="!sidebarCollapsed" x-transition.opacity class="min-w-0 pl-1">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-[#10B981] shrink-0"></span>
                    <span class="text-xs font-sans font-semibold text-cyber-main truncate">{{ auth()->user()->role_label }}</span>
                </div>
                <p class="text-[12px] font-sans text-cyber-muted truncate mt-0.5">
                    {{ auth()->user()->branch->name ?? 'Network Hub' }}
                </p>
            </div>
            <button @click="toggleSidebar()"
                    type="button"
                    aria-label="Toggle navigation sidebar"
                    class="h-8 w-8 shrink-0 rounded-lg bg-cyber-card hover:border-[#2F7BFF]/50 text-cyber-muted hover:text-cyber-main border border-cyber flex items-center justify-center transition cursor-pointer"
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
                <button @click="toggleSidebar()" aria-label="Collapse or expand sidebar" class="hidden md:flex items-center justify-center h-9 w-9 text-cyber-muted hover:text-cyber-main hover:bg-cyber-sub rounded-lg border border-transparent hover:border-cyber transition cursor-pointer shrink-0"
                        :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                    <i class="fa-solid text-xs transition-transform duration-200" :class="sidebarCollapsed ? 'fa-indent text-[#2F7BFF]' : 'fa-outdent'"></i>
                </button>
            </div>

            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                {{-- Live Operational Date --}}
                <div class="hidden xl:flex items-center gap-2 text-xs font-mono font-medium text-cyber-muted tabular-nums pr-1">
                    <span>{{ now()->format('D, M j, Y') }}</span>
                </div>

                {{-- Clean Icon-Only Theme Toggle --}}
                <button @click="toggleTheme()" 
                        type="button"
                        aria-label="Toggle Light or Dark Mode"
                        class="hidden sm:inline-flex items-center justify-center h-9 w-9 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF]/50 transition cursor-pointer"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                    <i :class="isDark ? 'fa-solid fa-sun text-[#FFC531]' : 'fa-solid fa-moon text-slate-600'" class="text-sm"></i>
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
                <a href="{{ $notifRoute }}" aria-label="Notifications" class="relative h-9 w-9 text-cyber-muted hover:text-cyber-main bg-cyber-sub hover:border-[#2F7BFF]/50 border border-cyber rounded-lg transition flex items-center justify-center" title="Notifications">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @php $bellCount = auth()->user()->notifications()->where('is_read', false)->count(); @endphp
                    @if($bellCount > 0)
                        <span class="absolute top-1.5 right-1.5 inline-flex rounded-full h-2 w-2 bg-[#FFC531]"></span>
                    @endif
                </a>
                @endif

                {{-- Interactive User Profile Menu --}}
                <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" 
                            type="button"
                            class="flex items-center gap-2.5 p-1 pr-2.5 rounded-lg bg-cyber-card border border-cyber hover:border-[#2F7BFF]/50 text-cyber-main transition group focus:outline-none select-none cursor-pointer"
                            :class="profileOpen ? 'ring-1 ring-[#2F7BFF]/30 border-[#2F7BFF]/50' : ''">
                        <div class="h-7 w-7 rounded-md bg-[#2F7BFF] text-white font-sans font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="text-left leading-tight hidden sm:block">
                            <span class="text-xs font-semibold text-cyber-main block">{{ auth()->user()->name }}</span>
                            <span class="text-[12px] font-sans text-cyber-muted block">{{ auth()->user()->role_label }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[11px] text-cyber-muted transition-transform duration-150 ml-1 hidden sm:inline" :class="profileOpen ? 'rotate-180 text-[#2F7BFF]' : ''"></i>
                    </button>

                    <div x-show="profileOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 mt-2 w-56 rounded-xl bg-cyber-card border border-cyber shadow-lg z-50 p-1.5 text-xs space-y-1"
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
                                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-[#FFC531] hover:bg-amber-500/10 transition font-semibold border border-transparent cursor-pointer">
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
                 class="mx-6 mt-6 p-3.5 bg-emerald-500/10 border border-emerald-500/30 border-l-4 border-l-[#10B981] rounded-lg text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-[#10B981] text-sm"></i>
                <p class="font-semibold flex-1">{{ session('success') }}</p>
                <button type="button" @click="show = false" class="text-emerald-700/70 hover:text-emerald-900 dark:text-emerald-300/70 dark:hover:text-emerald-100 transition p-1 cursor-pointer">
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
                 class="mx-6 mt-6 p-3.5 bg-amber-500/10 border border-amber-500/30 border-l-4 border-l-[#FFC531] rounded-lg text-xs text-amber-800 dark:text-amber-300 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-[#FFC531] text-sm"></i>
                <p class="font-semibold flex-1">{{ session('error') }}</p>
                <button type="button" @click="show = false" class="text-amber-700/70 hover:text-amber-900 dark:text-amber-300/70 dark:hover:text-amber-100 transition p-1 cursor-pointer">
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

    if (nav) {
        const saved = sessionStorage.getItem(NAV_KEY);
        if (saved !== null) {
            nav.scrollTop = parseInt(saved, 10);
        }
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
