@extends('layouts.internal')
@section('title', 'Workload Monitor — Real-Time Queue & Branch Utilization')
@section('page-title', 'Workload Monitor')

@section('content')
<div class="space-y-6 w-full">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER & LIVE INDICATOR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display tracking-tight">Workload Monitor</h1>
            <p class="text-xs text-cyber-muted mt-0.5">Live view of active job load, utilization, and rush flags across all branches.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 shadow-[0_0_15px_rgba(52,211,153,0.15)]">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>LIVE</span>
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TOP 4 SUMMARY METRIC KPI CARDS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Active Job Load (Cyan) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-layer-group text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">ACTIVE JOB LOAD</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalActiveJobs }}</div>
                <div class="text-[10px] text-slate-400 font-medium mt-0.5">Across branches</div>
            </div>
        </div>

        {{-- Card 2: Delayed Jobs (Red) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-red-500 p-5 flex items-center justify-between shadow-lg hover:border-red-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-red-400 transition-all"></i>
                <div class="text-[11px] font-black text-red-400 uppercase tracking-wider leading-tight max-w-[110px]">DELAYED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black {{ $totalDelayedJobs > 0 ? 'text-red-400' : 'text-white' }} font-display">{{ $totalDelayedJobs }}</div>
                <div class="text-[10px] {{ $totalDelayedJobs > 0 ? 'text-red-400' : 'text-slate-400' }} font-medium mt-0.5">{{ $totalDelayedJobs > 0 ? 'Needs attention' : 'Zero delays' }}</div>
            </div>
        </div>

        {{-- Card 3: Rush / Urgent (Amber) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-bolt text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">RUSH / URGENT</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display flex items-center justify-end gap-1.5">
                    <span>{{ $totalRushJobs }}</span>
                    @if($totalRushJobs > 0)
                        <span class="h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                    @endif
                </div>
                <div class="text-[10px] text-amber-400 font-medium mt-0.5">{{ $totalRushJobs > 0 ? 'Escalated queue' : 'No rush orders' }}</div>
            </div>
        </div>

        {{-- Card 4: Avg Utilization (Emerald) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-gauge-high text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">AVG UTILIZATION</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ number_format($avgUtilization, 1) }}%</div>
                <div class="text-[10px] text-emerald-400 font-medium mt-0.5">{{ $avgUtilization >= 80 ? 'Heavy Load' : ($avgUtilization >= 50 ? 'Moderate' : 'Optimal') }}</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- BRANCH WORKLOAD CARDS GRID --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($branches as $index => $b)
        @php
            $util = $b->workload_percent;
            if ($b->active_job_count == 0) {
                $statusLabel = 'IDLE';
                $badgeStyle = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
                $topBorder = 'border-t-2 border-t-cyan-500/40';
                $barGradient = 'bg-cyan-500';
                $waveColor = '#06b6d4';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35';
                $fillPoints = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35 320,40 0,40';
            } elseif ($util < 50) {
                $statusLabel = 'OPTIMAL';
                $badgeStyle = 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30';
                $topBorder = 'border-t-2 border-t-cyan-400';
                $barGradient = 'bg-gradient-to-r from-cyan-400 to-emerald-400';
                $waveColor = '#22d3ee';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34';
                $fillPoints = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34 320,40 0,40';
            } elseif ($util < 80) {
                $statusLabel = 'MODERATE';
                $badgeStyle = 'bg-amber-500/15 text-amber-400 border-amber-500/30';
                $topBorder = 'border-t-2 border-t-amber-400';
                $barGradient = 'bg-amber-400';
                $waveColor = '#f59e0b';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8';
                $fillPoints = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8 320,40 0,40';
            } else {
                $statusLabel = 'OVERLOAD';
                $badgeStyle = 'bg-red-500/15 text-red-400 border-red-500/30';
                $topBorder = 'border-t-2 border-t-red-500';
                $barGradient = 'bg-red-500';
                $waveColor = '#ef4444';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,30 40,18 80,8 140,4 200,6 260,4 320,4';
                $fillPoints = '0,30 40,18 80,8 140,4 200,6 260,4 320,4 320,40 0,40';
            }
        @endphp

        <div class="bg-cyber-card rounded-3xl border border-cyber {{ $topBorder }} p-6 shadow-xl flex flex-col justify-between space-y-6 hover:shadow-2xl transition group">
            
            {{-- Branch Card Header --}}
            <div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-black text-cyber-main font-display text-base tracking-tight truncate">{{ $b->name }}</h3>
                            @if($b->rush_count > 0)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/30 flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> {{ $b->rush_count }} RUSH
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-cyber-muted mt-1 truncate">
                            {{ $b->location ?? 'Metro Hub' }} · {{ $b->manager_name ?? 'Branch Supervisor' }}
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $badgeStyle }} shrink-0">
                        {{ $statusLabel }}
                    </span>
                </div>

                {{-- Key Numerical Rows --}}
                <div class="space-y-3 mt-6 pt-4 border-t border-cyber text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Active Job Load</span>
                        <strong class="font-mono font-bold text-cyber-main text-xs">{{ $b->active_job_count }} {{ Str::plural('job', $b->active_job_count) }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Delayed Jobs</span>
                        <strong class="font-mono font-bold text-xs {{ $b->delayed_count > 0 ? 'text-red-400' : 'text-cyber-muted' }}">{{ $b->delayed_count }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Rush / Urgent</span>
                        <span class="font-mono font-bold text-xs flex items-center gap-1.5 {{ $b->rush_count > 0 ? 'text-amber-400' : 'text-cyber-muted' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $b->rush_count > 0 ? 'bg-amber-400 animate-pulse' : 'bg-transparent' }}"></span>
                            {{ $b->rush_count }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Utilization Gauge Bar --}}
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-muted">UTILIZATION</span>
                    <strong class="font-mono font-black {{ $b->active_job_count == 0 ? 'text-cyan-400' : ($util >= 80 ? 'text-red-400' : ($util >= 50 ? 'text-amber-400' : 'text-cyan-400')) }}">
                        {{ number_format($util, 1) }}%
                    </strong>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full h-2 bg-cyber-sub rounded-full overflow-hidden border border-cyber">
                    <div class="h-full {{ $barGradient }} rounded-full transition-all duration-700" style="width: {{ min(max($util, 4), 100) }}%"></div>
                </div>

                {{-- Milestone Markers --}}
                <div class="flex justify-between text-[9px] font-mono text-cyber-sub font-semibold px-0.5">
                    <span>0%</span>
                    <span>40%</span>
                    <span>70%</span>
                    <span>100%</span>
                </div>
            </div>

            {{-- Sparkline Load Trend Wave --}}
            <div class="pt-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-cyber-sub block mb-2 font-mono">TODAY'S LOAD TREND</span>
                <div class="w-full h-12 relative overflow-hidden rounded-xl bg-cyber-sub/40 border border-cyber/50 flex items-end">
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

        </div>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LIVE ACTIVE PRODUCTION QUEUE TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col mt-8">
        <div class="px-6 py-4 border-b border-cyber flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-cyber-sub">
            <div>
                <h3 class="font-black text-cyber-main text-sm">Active Production Queue</h3>
                <p class="text-[11px] text-cyber-muted mt-0.5">Real-time status of orders currently queued or printing across branches</p>
            </div>
            
            {{-- Branch Filter --}}
            <form action="{{ route('manager.workload.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                <select name="branch_id" onchange="this.form.submit()" class="bg-cyber-card border border-cyber rounded-xl px-3 py-1.5 text-xs text-cyber-main font-semibold focus:ring-2 focus:ring-cyan-500 focus:outline-none w-full sm:w-auto">
                    <option value="">All Branches ({{ $branches->count() }})</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $branchFilter == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main">
                <thead class="bg-cyber-sub text-cyber-muted uppercase font-bold text-[11px] tracking-wider border-b border-cyber">
                    <tr>
                        <th class="px-6 py-3.5">Job Code</th>
                        <th class="px-6 py-3.5">Branch Assigned</th>
                        <th class="px-6 py-3.5">Customer / Order</th>
                        <th class="px-6 py-3.5">Priority</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Started At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-cyber-sub/60 transition">
                        <td class="px-6 py-4 font-mono font-bold text-cyan-400">
                            {{ $job->job_code ?? $job->job_ticket ?? ('JOB-' . str_pad($job->id, 5, '0', STR_PAD_LEFT)) }}
                        </td>
                        <td class="px-6 py-4 font-bold text-cyber-main">{{ $job->branch->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold block text-cyber-main">{{ $job->order->user->name ?? 'Demo Customer' }}</span>
                            <span class="text-[10px] text-cyber-muted">{{ $job->order->order_number ?? 'ORD-REF' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if(in_array($job->priority, ['rush', 'urgent']))
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                    <i class="fa-solid fa-bolt text-[9px] mr-0.5"></i> {{ strtoupper($job->priority) }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-500/10 text-cyber-muted border border-cyber">
                                    STANDARD
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusBadge = match($job->status) {
                                    'in_production', 'running' => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                                    'delayed'                  => 'bg-red-500/15 text-red-400 border-red-500/30',
                                    'paused'                   => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                    default                    => 'bg-blue-500/15 text-blue-400 border-blue-500/30',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $statusBadge }}">
                                {{ str_replace('_', ' ', strtoupper($job->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-cyber-muted font-mono text-[11px]">
                            {{ $job->created_at->format('M d, Y · H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-cyber-muted">
                            <i class="fa-solid fa-circle-check text-emerald-400 text-2xl mb-2 block"></i>
                            <span>No pending jobs currently in the active workload queue.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
        <div class="px-6 py-4 border-t border-cyber">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
