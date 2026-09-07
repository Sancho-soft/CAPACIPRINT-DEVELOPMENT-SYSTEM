@extends('layouts.internal')
@section('title', 'Operational Reports')
@section('page-title', 'Operational Reports')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: OPERATIONAL REPORTS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Branch Operations &amp; Performance Reports</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-print text-cyan-400 text-xs"></i> Print Report
                </button>
                <a href="{{ route('manager.capacity.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-xs"></i> Capacity Matrix
                </a>
                <a href="{{ route('manager.production-planning.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-calendar-days text-emerald-400 text-xs"></i> Schedule
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 UNIFIED KPI SUMMARY CARDS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-dashboard.kpi-card 
            title="ACTIVE FLOOR RUNS"
            :value="$activeOrders"
            icon="fa-solid fa-gears"
            accent="cyan"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="FULFILLED ORDERS"
            :value="$completedOrders"
            icon="fa-solid fa-circle-check"
            accent="emerald"
            :link="route('manager.reports.production', ['status' => 'completed'])"
        />

        <x-dashboard.kpi-card 
            title="DELAYED RUNS"
            :value="$delayedJobs"
            icon="fa-solid fa-triangle-exclamation"
            accent="rose"
            :link="route('manager.production-planning.index', ['status' => 'delayed'])"
        />

        <x-dashboard.kpi-card 
            title="TOTAL ORDER INTAKE"
            :value="$totalOrders"
            icon="fa-solid fa-layer-group"
            accent="indigo"
            :link="route('manager.reports.production')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2 PRIMARY AUDIT & REPORT WORKSPACES --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Production Performance Report Card --}}
        <a href="{{ route('manager.reports.production') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-cyan-500/40 hover:shadow-2xl transition block space-y-5 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 dark:text-cyan-400 group-hover:text-cyan-500 transition-colors">
                    <span>Open Audit Report</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">
                    Production Performance &amp; Job Status Logs
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Granular breakdown of in-progress shop-floor jobs, customer specifications, priority queues, and operator assignments.
                </p>
            </div>
            <div class="pt-4 border-t border-cyber flex items-center justify-between text-xs">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-cyan-500/10 text-cyan-700 dark:text-cyan-300 border border-cyan-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-cyan-500"></i>
                    <span>{{ $totalJobsCount }} Tracked Production Jobs</span>
                </span>
                <span class="text-[11px] text-cyber-muted font-medium">Real-time job auditing &rarr;</span>
            </div>
        </a>

        {{-- Capacity Utilization Report Card --}}
        <a href="{{ route('manager.reports.capacity') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-emerald-500/40 hover:shadow-2xl transition block space-y-5 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-500 transition-colors">
                    <span>Open Utilization Matrix</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                    Branch Capacity Utilization &amp; Fleet Workload
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Cross-branch machine press uptime comparisons, daily throughput quotas, equipment load distribution, and bottlenecks.
                </p>
            </div>
            <div class="pt-4 border-t border-cyber flex items-center justify-between text-xs">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-network-wired text-emerald-500"></i>
                    <span>{{ $branchesCount }} Active Printing Branches</span>
                </span>
                <span class="text-[11px] text-cyber-muted font-medium">Daily load balancing &rarr;</span>
            </div>
        </a>
    </div>

</div>
@endsection
