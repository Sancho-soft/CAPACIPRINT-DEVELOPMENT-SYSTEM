@extends('layouts.internal')
@section('title', 'Press Floor Operator Cockpit')
@section('page-title', 'Press Floor Operator Cockpit')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER & SHIFT TARGET BANNER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0 font-display font-black">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Press Floor Operator Cockpit</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 font-mono">
                            Shift Active
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        Operator: <strong class="text-cyber-main">{{ auth()->user()->name }}</strong> &middot; You have <strong class="text-cyan-400 font-mono">{{ $inProductionCount }} active run(s)</strong> running on your assigned press lines.
                    </p>
                </div>
            </div>

            {{-- Shift Target Efficiency Meter --}}
            <div class="bg-cyber-sub/80 border border-cyber rounded-2xl p-4 min-w-[280px] w-full lg:w-auto shadow-sm">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-cyber-muted font-semibold">Today's Shift Output Target</span>
                    <span class="text-cyan-400 font-bold font-mono">{{ $completedCount }} / {{ max(1, $assignedCount + $completedCount) }} Runs</span>
                </div>
                @php 
                    $totalShiftJobs = max(1, $assignedCount + $completedCount);
                    $pct = min(100, round(($completedCount / $totalShiftJobs) * 100));
                @endphp
                <div class="w-full bg-cyber-base rounded-full h-2 overflow-hidden border border-cyber/60">
                    <div class="bg-gradient-to-r from-cyan-400 to-emerald-400 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-cyber-muted mt-2 font-medium">
                    <span>Shift Completion Progress</span>
                    <span class="text-emerald-400 font-bold font-mono">{{ $pct }}% Complete</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 1: ACTIONABLE ATTENTION CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.attention-center 
        :items="$attentionItems"
        title="Press Line Exceptions & Priority Runs"
        subtitle="Delayed jobs requiring remediation and rush priority jobs needing setup"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 OPERATIONAL SHIFT METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="ASSIGNED RUNS"
            :value="$assignedCount"
            icon="fa-solid fa-list-check"
            accent="cyan"
            trend="{{ $inProductionCount }} currently on press"
            trendType="neutral"
            subtitle="Queue for your shift"
            :link="route('production.jobs.index')"
        />

        <x-dashboard.kpi-card 
            title="ACTIVELY ON PRESS"
            :value="$inProductionCount"
            icon="fa-solid fa-industry"
            accent="indigo"
            trend="Press running"
            trendType="up"
            subtitle="Cylinder & ink engaged"
        />

        <x-dashboard.kpi-card 
            title="DUE TODAY (DEADLINE)"
            :value="$dueTodayCount"
            icon="fa-solid fa-clock"
            accent="amber"
            trend="{{ $dueTodayCount > 0 ? 'Priority run' : 'On schedule' }}"
            :trendType="$dueTodayCount > 0 ? 'warning' : 'up'"
            subtitle="SLA turnaround targets"
        />

        <x-dashboard.kpi-card 
            title="COMPLETED THIS SHIFT"
            :value="$completedCount"
            icon="fa-solid fa-circle-check"
            accent="emerald"
            trend="{{ $delayedCount > 0 ? $delayedCount . ' delayed' : 'Zero stoppages' }}"
            :trendType="$delayedCount > 0 ? 'warning' : 'up'"
            subtitle="Handed over to QC"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: SHOP FLOOR PRODUCTION PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Shop Floor Production Lifecycle"
        subtitle="Real-time jobs moving through pre-press, press run, quality check, and completion"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: ASSIGNED PRODUCTION QUEUE TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$myJobs"
        title="Press Line Production Queue (Prioritized)"
        subtitle="Jobs prioritized by urgency (Urgent -> Rush -> Normal). Click details to advance job status or report delays."
        :viewAllUrl="route('production.jobs.index')"
        viewAllLabel="Full Floor Queue"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 3: PRESS EQUIPMENT FLEET STATUS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(isset($pressMachines) && $pressMachines->isNotEmpty())
    <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Press Equipment Status</h3>
                <p class="text-[11px] text-cyber-muted mt-0.5">Assigned branch machinery and operational availability</p>
            </div>
            <i class="fa-solid fa-print text-cyan-400 text-sm"></i>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-1">
            @foreach($pressMachines as $m)
                @php
                    $mBadge = match($m->status) {
                        'available'   => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                        'in_use'      => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                        'maintenance' => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                        default       => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                    };
                @endphp
                <div class="p-4 rounded-2xl border border-cyber bg-cyber-sub/60 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-bold text-cyber-main text-xs truncate max-w-[150px]">{{ $m->name }}</span>
                        <span class="px-1.5 py-0.2 rounded text-[8px] font-black uppercase tracking-wider border {{ $mBadge }} font-mono shrink-0">
                            {{ ucfirst(str_replace('_', ' ', $m->status)) }}
                        </span>
                    </div>
                    <p class="text-[10px] text-cyber-muted font-mono">{{ $m->type }} &middot; {{ $m->model }}</p>
                    <div class="text-[10px] text-cyber-sub flex justify-between pt-1 border-t border-cyber/40">
                        <span>Capacity:</span>
                        <span class="font-mono text-cyber-main">{{ $m->jobs_per_day_capacity ?? 10 }} jobs/day</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
