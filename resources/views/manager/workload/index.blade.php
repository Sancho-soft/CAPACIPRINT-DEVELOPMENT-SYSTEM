@extends('layouts.internal')
@section('title', 'Workload Monitor — Real-Time Queue & Branch Utilization')
@section('page-title', 'Workload Monitor')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER & LIVE INDICATOR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display tracking-tight">Workload Monitor</h1>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 shadow-[0_0_15px_rgba(52,211,153,0.15)]">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>LIVE</span>
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TOP 4 SUMMARY METRIC KPI CARDS (FUNCTIONAL & CLICKABLE) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Active Job Load (Cyan) --}}
        <x-dashboard.kpi-card 
            title="ACTIVE JOB LOAD"
            :value="$totalActiveJobs"
            icon="fa-solid fa-layer-group"
            accent="cyan"
            :link="route('manager.workload.index', request('branch_id') ? ['branch_id' => request('branch_id')] : [])"
            :active="!request('filter')"
        />

        {{-- Card 2: Delayed Jobs (Rose) --}}
        <x-dashboard.kpi-card 
            title="DELAYED JOBS"
            :value="$totalDelayedJobs"
            icon="fa-solid fa-clock-rotate-left"
            accent="rose"
            :link="route('manager.workload.index', ['filter' => 'delayed'] + (request('branch_id') ? ['branch_id' => request('branch_id')] : []))"
            :active="request('filter') === 'delayed'"
        />

        {{-- Card 3: Rush / Urgent (Amber) --}}
        <x-dashboard.kpi-card 
            title="RUSH / URGENT"
            :value="$totalRushJobs"
            icon="fa-solid fa-bolt"
            accent="amber"
            :link="route('manager.workload.index', ['filter' => 'rush'] + (request('branch_id') ? ['branch_id' => request('branch_id')] : []))"
            :active="request('filter') === 'rush'"
        />

        {{-- Card 4: Avg Utilization (Emerald) --}}
        <x-dashboard.kpi-card 
            title="AVG UTILIZATION"
            :value="number_format($avgUtilization, 1) . '%'"
            icon="fa-solid fa-gauge-high"
            accent="emerald"
            :link="route('manager.capacity.index')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- BRANCH WORKLOAD CARDS GRID (FUNCTIONAL CLICK-TO-FILTER) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($branches as $index => $b)
        @php
            $util = $b->workload_percent;
            if ($b->active_job_count == 0) {
                $topBorder = 'border-t-2 border-t-cyan-500/40';
                $barGradient = 'bg-cyan-500';
                $waveColor = '#06b6d4';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35';
                $fillPoints = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35 320,40 0,40';
            } elseif ($util < 50) {
                $topBorder = 'border-t-2 border-t-cyan-400';
                $barGradient = 'bg-gradient-to-r from-cyan-400 to-emerald-400';
                $waveColor = '#22d3ee';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34';
                $fillPoints = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34 320,40 0,40';
            } elseif ($util < 80) {
                $topBorder = 'border-t-2 border-t-amber-400';
                $barGradient = 'bg-amber-400';
                $waveColor = '#f59e0b';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8';
                $fillPoints = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8 320,40 0,40';
            } else {
                $topBorder = 'border-t-2 border-t-red-500';
                $barGradient = 'bg-red-500';
                $waveColor = '#ef4444';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,30 40,18 80,8 140,4 200,6 260,4 320,4';
                $fillPoints = '0,30 40,18 80,8 140,4 200,6 260,4 320,4 320,40 0,40';
            }
            $isBranchSelected = ($branchFilter == $b->id);
            $toggleBranchUrl = $isBranchSelected 
                ? route('manager.workload.index', request('filter') ? ['filter' => request('filter')] : []) 
                : route('manager.workload.index', ['branch_id' => $b->id] + (request('filter') ? ['filter' => request('filter')] : []));
        @endphp

        <a href="{{ $toggleBranchUrl }}" 
           class="bg-cyber-card rounded-3xl border {{ $isBranchSelected ? 'ring-2 ring-cyan-500/60 border-cyan-500 shadow-lg' : 'border-cyber hover:border-slate-300 dark:hover:border-slate-700' }} {{ $topBorder }} p-6 shadow-xl flex flex-col justify-between space-y-6 hover:shadow-2xl transition group cursor-pointer"
           title="{{ $isBranchSelected ? 'Click to show all branches' : 'Click to filter queue by ' . $b->name }}">
            
            {{-- Branch Card Header --}}
            <div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-black text-cyber-main font-display text-base tracking-tight truncate group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $b->name }}</h3>
                            @if($b->rush_count > 0)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30 flex items-center gap-1 shadow-sm font-mono">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> {{ $b->rush_count }} RUSH
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-cyber-muted mt-1 truncate">
                            {{ $b->location ?? 'Metro Hub' }} · {{ $b->manager_name ?? 'Branch Supervisor' }}
                        </p>
                    </div>

                    @if($isBranchSelected)
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-cyan-500/15 text-cyan-700 dark:text-cyan-400 border border-cyan-500/30 shrink-0 font-mono">
                            Selected
                        </span>
                    @endif
                </div>

                {{-- Key Numerical Rows --}}
                <div class="space-y-3 mt-6 pt-4 border-t border-cyber/60 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Active Job Load</span>
                        <strong class="font-mono font-bold text-cyber-main text-xs">{{ $b->active_job_count }} {{ Str::plural('job', $b->active_job_count) }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Delayed Jobs</span>
                        <strong class="font-mono font-bold text-xs {{ $b->delayed_count > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-cyber-muted' }}">{{ $b->delayed_count }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Rush / Urgent</span>
                        <span class="font-mono font-bold text-xs flex items-center gap-1.5 {{ $b->rush_count > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-cyber-muted' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $b->rush_count > 0 ? 'bg-amber-500 animate-pulse' : 'bg-transparent' }}"></span>
                            {{ $b->rush_count }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Utilization Gauge Bar --}}
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">UTILIZATION</span>
                    <strong class="font-mono font-black {{ $b->active_job_count == 0 ? 'text-cyan-600 dark:text-cyan-400' : ($util >= 80 ? 'text-rose-600 dark:text-rose-400' : ($util >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-cyan-600 dark:text-cyan-400')) }}">
                        {{ number_format($util, 1) }}%
                    </strong>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden border border-cyber/60">
                    <div class="h-full {{ $barGradient }} rounded-full transition-all duration-700" style="width: {{ min(max($util, 4), 100) }}%"></div>
                </div>

                {{-- Milestone Markers --}}
                <div class="flex justify-between text-[9px] font-mono text-cyber-muted font-semibold px-0.5">
                    <span>0%</span>
                    <span>40%</span>
                    <span>70%</span>
                    <span>100%</span>
                </div>
            </div>

            {{-- Sparkline Load Trend Wave --}}
            <div class="pt-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted block mb-2 font-mono">TODAY'S LOAD TREND</span>
                <div class="w-full h-12 relative overflow-hidden rounded-xl bg-slate-50 dark:bg-[#0D1520] border border-cyber/60 flex items-end">
                    <svg class="w-full h-full" viewBox="0 0 320 40" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="{{ $waveGradientId }}" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="{{ $waveColor }}" stop-opacity="0.3" />
                                <stop offset="100%" stop-color="{{ $waveColor }}" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        {{-- Filled Wave Area --}}
                        <polygon points="{{ $fillPoints }}" fill="url(#{{ $waveGradientId }})" />
                        {{-- Stroke Curve --}}
                        <polyline points="{{ $points }}" fill="none" stroke="{{ $waveColor }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

        </a>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LIVE ACTIVE PRODUCTION QUEUE TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col mt-8">
        <div class="px-6 py-4 border-b border-cyber/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Active Production Queue</h3>
            </div>
            
            {{-- Branch & Queue Filters --}}
            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                @if(request()->hasAny(['filter', 'branch_id']))
                    <a href="{{ route('manager.workload.index') }}" 
                       class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 inline-flex items-center gap-1.5 transition"
                       title="Clear all filters">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        <span>Reset Filters</span>
                    </a>
                @endif

                <form action="{{ route('manager.workload.index') }}" method="GET" class="flex items-center gap-2">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <select name="branch_id" onchange="this.form.submit()" class="bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs text-cyber-main font-semibold focus:border-cyan-500 focus:outline-none w-full sm:w-auto">
                        <option value="">All Branches ({{ $branches->count() }})</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $branchFilter == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px] bg-slate-50/50 dark:bg-transparent">
                    <tr>
                        <th class="px-6 py-3.5">Job Code</th>
                        <th class="px-6 py-3.5">Branch Assigned</th>
                        <th class="px-6 py-3.5">Customer / Order</th>
                        <th class="px-6 py-3.5">Priority</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Started At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-cyan-600 dark:text-cyan-400">
                            #{{ $job->job_number ?? ($job->job_code ?? ('JOB-' . str_pad($job->id, 5, '0', STR_PAD_LEFT))) }}
                        </td>
                        <td class="px-6 py-4 font-bold text-cyber-main">{{ $job->branch->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold block text-cyber-main">{{ $job->order->user->name ?? 'Demo Customer' }}</span>
                            <span class="text-[10px] text-cyber-muted">{{ $job->order->order_number ?? 'ORD-REF' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(in_array($job->priority, ['rush', 'urgent']))
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30 font-mono">
                                    {{ strtoupper($job->priority) }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 font-mono">
                                    STANDARD
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusBadge = match($job->status) {
                                    'in_production', 'running' => 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-400 border-cyan-500/30',
                                    'delayed'                  => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                                    'quality_checking'         => 'bg-purple-500/15 text-purple-700 dark:text-purple-400 border-purple-500/30',
                                    default                    => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border-slate-500/30',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusBadge }} font-mono">
                                {{ str_replace('_', ' ', strtoupper($job->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-cyber-muted font-mono text-[11px] text-right whitespace-nowrap">
                            {{ $job->created_at->format('M d, Y · h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-cyber-muted">
                            <x-dashboard.empty-state 
                                title="No Jobs in Workload Queue"
                                description="No active production jobs match your filter criteria."
                                icon="fa-solid fa-circle-check"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
        <div class="px-6 py-4 border-t border-cyber/60">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
