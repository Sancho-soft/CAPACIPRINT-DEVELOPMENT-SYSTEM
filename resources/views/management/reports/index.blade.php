@extends('layouts.internal')
@section('title', 'Executive Reports & Analytics')
@section('page-title', 'Executive Reports & Analytics Hub')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: EXECUTIVE REPORTS HUB --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Executive Analytics &amp; Reports</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-print text-cyan-400 text-xs"></i> Print Summary
                </button>
                <a href="{{ route('management.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-chart-line text-xs text-indigo-400"></i> Executive Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 UNIFIED KPI METRIC CARDS (RIGHT-ALIGNED NUMBERS) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="LIFETIME ORDERS"
            :value="$totalOrdersCount"
            icon="fa-solid fa-layer-group"
            accent="cyan"
            :link="route('management.reports.orders')"
        />

        <x-dashboard.kpi-card 
            title="TOTAL QUOTED REVENUE"
            :value="'₱' . number_format($totalRevenue, 2)"
            icon="fa-solid fa-coins"
            accent="emerald"
            :link="route('management.reports.orders')"
        />

        <x-dashboard.kpi-card 
            title="FULFILLED ORDERS"
            :value="$completedOrdersCount"
            icon="fa-solid fa-circle-check"
            accent="indigo"
            :link="route('management.reports.orders', ['status' => 'completed'])"
        />

        <x-dashboard.kpi-card 
            title="ACTIVE BRANCHES"
            :value="$activeBranchesCount"
            icon="fa-solid fa-shop"
            accent="amber"
            :link="route('management.reports.capacity')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 PRIMARY EXECUTIVE REPORT SUITES (2x2 GRID) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 1. Orders & Financial Report --}}
        <a href="{{ route('management.reports.orders') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-emerald-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-500 transition-colors">
                    <span>Open Audit Report</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                    Orders &amp; Financial Audit Report
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Complete transaction logs, customer payment verifications, revenue breakdowns, and custom date range filters.
                </p>
            </div>
        </a>

        {{-- 2. Production Output & Efficiency --}}
        <a href="{{ route('management.reports.production') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-amber-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-500 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 group-hover:text-amber-500 transition-colors">
                    <span>Open Efficiency Logs</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors">
                    Production Output &amp; Machine Efficiency
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Shop-floor throughput telemetry, press runtimes, delay exceptions, and technician workload allocations.
                </p>
            </div>
        </a>

        {{-- 3. Inventory & Material Valuation --}}
        <a href="{{ route('management.reports.inventory') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-cyan-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 dark:text-cyan-400 group-hover:text-cyan-500 transition-colors">
                    <span>Open Stock Valuation</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">
                    Inventory Valuation &amp; Material Stock
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Branch stock valuation, low-stock reorder warnings, unit consumption rates, and replenishment triggers.
                </p>
            </div>
        </a>

        {{-- 4. Branch Capacity Analysis --}}
        <a href="{{ route('management.reports.capacity') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-indigo-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 group-hover:text-indigo-500 transition-colors">
                    <span>Open Capacity Matrix</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                    Multi-Branch Capacity &amp; Load Matrix
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Equipment uptime benchmarks, operational bottlenecks, daily capacity thresholds, and network saturation.
                </p>
            </div>
        </a>

    </div>

</div>
@endsection
