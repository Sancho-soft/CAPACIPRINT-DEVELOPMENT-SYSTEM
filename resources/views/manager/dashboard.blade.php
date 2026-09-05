@extends('layouts.internal')
@section('title', 'Branch & Capacity Operations Hub')
@section('page-title', 'Branch & Capacity Operations Hub')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: BRANCH & CAPACITY COMMAND HUB --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Branch &amp; Capacity Operations Hub</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 font-mono">
                            Press Scheduling
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        Shop-floor press queue scheduling, machine line allocation, turnaround deadlines, and multi-branch load distribution.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('manager.capacity.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-xs"></i> Evaluate Capacity
                </a>
                <a href="{{ route('manager.production-planning.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-calendar-days text-xs text-cyan-400"></i> Plan Schedule
                </a>
                <a href="{{ route('manager.purchasing.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-boxes-packing text-xs text-amber-400"></i> Material Reorders
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 1: ACTIONABLE ATTENTION CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.attention-center 
        :items="$attentionItems"
        title="Production Floor Attention Center"
        subtitle="Stoppages, delay exceptions, rush deadlines, and material inventory warnings"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5 OPERATIONAL PRODUCTION METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-dashboard.kpi-card 
            title="ACTIVE JOBS ON FLOOR"
            :value="$totalActiveJobs"
            icon="fa-solid fa-gears"
            accent="cyan"
            trend="{{ $jobsDueToday }} due today"
            :trendType="$jobsDueToday > 0 ? 'warning' : 'up'"
            subtitle="Current press queue"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="DUE TODAY (DEADLINE)"
            :value="$jobsDueToday"
            icon="fa-solid fa-clock"
            accent="emerald"
            trend="{{ $jobsDueTomorrow }} due tomorrow"
            trendType="neutral"
            subtitle="Today's promised orders"
        />

        <x-dashboard.kpi-card 
            title="RUSH & URGENT RUNS"
            :value="$rushJobs"
            icon="fa-solid fa-bolt"
            accent="amber"
            trend="Priority queue"
            :trendType="$rushJobs > 0 ? 'warning' : 'neutral'"
            subtitle="High-turnaround jobs"
        />

        <x-dashboard.kpi-card 
            title="PRESS RUN DELAYS"
            :value="$delayedJobs"
            icon="fa-solid fa-triangle-exclamation"
            accent="rose"
            trend="{{ $delayedJobs > 0 ? 'Action required' : 'Clear run' }}"
            :trendType="$delayedJobs > 0 ? 'danger' : 'up'"
            subtitle="Production exceptions"
        />

        <x-dashboard.kpi-card 
            title="PRESS FLEET READY"
            :value="$availableMachines . '/' . $totalMachines"
            icon="fa-solid fa-print"
            accent="indigo"
            trend="{{ $inUseMachines }} actively running"
            trendType="up"
            subtitle="Operational equipment"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING WORKFLOW PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Lifecycle"
        subtitle="End-to-end production tracking from artwork proofing to shop-floor pressing and claiming"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: MULTI-BRANCH WORKLOAD & MACHINE UTILIZATION --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.branch-workload-card 
        :branches="$branches"
        title="Branch Capacity Utilization & Machine Workload"
        subtitle="Real-time daily machine capacity and active job load across all branches"
        :actionUrl="route('manager.capacity.index')"
        actionLabel="Capacity Evaluation Matrix"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: LIVE PRODUCTION JOBS DATA TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Active Production Jobs & Press Allocation"
        subtitle="Real-time shop-floor jobs, customer specifications, and assigned press operators"
        :viewAllUrl="route('manager.production-planning.index')"
        viewAllLabel="Full Production Schedule"
    />

</div>
@endsection
