@extends('layouts.internal')
@section('title', 'Operational Reports')
@section('page-title', 'Operational Reports')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: OPERATIONAL REPORTS (UNBOXED & CLEAN)        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-slate-900 dark:text-white">Branch Operations &amp; Performance Reports</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Cross-branch throughput, resource utilization, and operations analytics.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fa-solid fa-print text-blue-400 text-xs"></i> Print Report
            </button>
            <a href="{{ route('manager.capacity.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-calculator text-xs"></i> Capacity Matrix
            </a>
            <a href="{{ route('manager.production-planning.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-calendar-days text-emerald-700 dark:text-emerald-400 text-xs"></i> Schedule
            </a>
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
           class="bg-cyber-card p-6 sm:p-7 rounded-2xl border border-cyber shadow-sm hover:border-blue-500/40 hover:shadow-sm transition block space-y-5 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 dark:text-blue-400 group-hover:text-blue-500 transition-colors">
                    <span>Open Audit Report</span>
                    <i class="fa-solid fa-arrow-right text-[11px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">
                    Production Performance &amp; Job Status Logs
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Granular breakdown of in-progress shop-floor jobs, customer specifications, priority queues, and operator assignments.
                </p>
            </div>
            <div class="pt-4 border-t border-cyber flex items-center justify-between text-xs">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-sans tabular-nums font-bold bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-blue-500"></i>
                    <span>{{ $totalJobsCount }} Tracked Production Jobs</span>
                </span>
                <span class="text-[11px] text-cyber-muted font-medium">Real-time job auditing &rarr;</span>
            </div>
        </a>

        {{-- Capacity Utilization Report Card --}}
        <a href="{{ route('manager.reports.capacity') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-2xl border border-cyber shadow-sm hover:border-emerald-500/40 hover:shadow-sm transition block space-y-5 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-500 transition-colors">
                    <span>Open Utilization Matrix</span>
                    <i class="fa-solid fa-arrow-right text-[11px] transition-transform group-hover:translate-x-1"></i>
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
                <span class="px-2.5 py-1 rounded-full text-[11px] font-sans tabular-nums font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-network-wired text-emerald-500"></i>
                    <span>{{ $branchesCount }} Active Printing Branches</span>
                </span>
                <span class="text-[11px] text-cyber-muted font-medium">Daily load balancing &rarr;</span>
            </div>
        </a>
    </div>

</div>
@endsection
