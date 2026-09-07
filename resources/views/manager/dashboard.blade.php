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
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Branch &amp; Capacity Operations Hub</h2>
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
    {{-- 5 OPERATIONAL PRODUCTION METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-dashboard.kpi-card 
            title="ACTIVE JOBS ON FLOOR"
            :value="$totalActiveJobs"
            icon="fa-solid fa-gears"
            accent="cyan"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="DUE TODAY (DEADLINE)"
            :value="$jobsDueToday"
            icon="fa-solid fa-clock"
            accent="emerald"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="RUSH & URGENT RUNS"
            :value="$rushJobs"
            icon="fa-solid fa-bolt"
            accent="amber"
            :link="route('manager.production-planning.index', ['priority' => 'rush_urgent'])"
        />

        <x-dashboard.kpi-card 
            title="PRESS RUN DELAYS"
            :value="$delayedJobs"
            icon="fa-solid fa-triangle-exclamation"
            accent="rose"
            :link="route('manager.production-planning.index', ['status' => 'delayed'])"
        />

        <x-dashboard.kpi-card 
            title="PRESS FLEET READY"
            :value="$availableMachines . '/' . $totalMachines"
            icon="fa-solid fa-print"
            accent="indigo"
            :link="route('manager.capacity.index')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING WORKFLOW PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Lifecycle"
        subtitle=""
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: MULTI-BRANCH WORKLOAD & MACHINE UTILIZATION --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.branch-workload-card 
        :branches="$branches"
        title="Branch Capacity Utilization & Machine Workload"
        subtitle=""
        :actionUrl="route('manager.capacity.index')"
        actionLabel="Capacity Evaluation Matrix"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: LIVE PRODUCTION JOBS DATA TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Active Production Jobs & Press Allocation"
        subtitle=""
        :viewAllUrl="route('manager.production-planning.index')"
        viewAllLabel="Full Production Schedule"
    />

</div>
@endsection
