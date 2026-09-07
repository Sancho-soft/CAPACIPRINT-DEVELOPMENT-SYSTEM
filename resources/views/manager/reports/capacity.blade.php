@extends('layouts.internal')
@section('title', 'Branch Capacity Report')
@section('page-title', 'Branch Capacity Report')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: CAPACITY REPORT --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('manager.reports.index') }}" class="h-12 w-12 rounded-2xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main flex items-center justify-center text-lg shadow-sm transition hover:scale-105 shrink-0" title="Back to Reports Hub">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">NETWORK TELEMETRY</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Branch Capacity Utilization &amp; Fleet Workload</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-print text-emerald-400 text-xs"></i> Print Matrix
                </button>
                <a href="{{ route('manager.capacity.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-calculator text-xs"></i> Evaluate Capacity
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MULTI-BRANCH CAPACITY CARDS GRID --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @forelse($branches as $b)
            @php
                $activeJobs = $b->production_jobs_count ?? 0;
                $maxJobs = max(1, $b->max_daily_jobs ?? 20);
                $pct = min(100, round(($activeJobs / $maxJobs) * 100));
                
                $statusColor = match(true) {
                    $pct >= 85 => [
                        'text'  => 'text-rose-600 dark:text-rose-400 font-bold',
                        'bar'   => 'bg-rose-500',
                    ],
                    $pct >= 50 => [
                        'text'  => 'text-amber-600 dark:text-amber-400 font-bold',
                        'bar'   => 'bg-amber-400',
                    ],
                    default => [
                        'text'  => 'text-emerald-600 dark:text-emerald-400 font-bold',
                        'bar'   => 'bg-emerald-400',
                    ],
                };

                $availMachines = $b->available_machines_count ?? ($b->machines ? $b->machines->where('status', 'available')->count() : 0);
                $totalMachines = $b->machines_count ?? 0;
            @endphp

            <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl space-y-4 hover:border-emerald-500/30 transition flex flex-col justify-between group">
                <div>
                    {{-- Branch Title & Location --}}
                    <div class="flex items-start justify-between gap-2 border-b border-cyber/60 pb-3">
                        <div class="min-w-0">
                            <h3 class="font-black text-cyber-main text-base font-display truncate group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                                {{ $b->name }}
                            </h3>
                            <span class="text-[11px] text-cyber-muted flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-location-dot text-[10px] text-emerald-500"></i>
                                {{ $b->location ?? 'Hub' }}
                            </span>
                        </div>
                    </div>

                    {{-- Spec Metric Details --}}
                    <div class="space-y-2 text-xs text-cyber-muted font-medium bg-cyber-sub/50 p-3.5 rounded-2xl border border-cyber/50 mt-4">
                        <div class="flex justify-between items-center">
                            <span>Press Machines:</span>
                            <span class="font-mono font-bold text-cyber-main">
                                <strong class="text-emerald-600 dark:text-emerald-400 font-extrabold">{{ $availMachines }}</strong> / {{ $totalMachines }} operational
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Active Jobs on Floor:</span>
                            <span class="font-mono font-bold text-cyber-main">{{ $activeJobs }} jobs</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Daily Limit Capacity:</span>
                            <span class="font-mono font-bold text-slate-600 dark:text-slate-300">{{ $b->max_daily_jobs ?? 25 }} jobs/day</span>
                        </div>
                    </div>

                    {{-- Workload Utilization Bar --}}
                    <div class="space-y-1.5 pt-4">
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-wider">
                            <span class="text-cyber-muted">Capacity Utilization</span>
                            <span class="{{ $statusColor['text'] }} font-mono text-xs">{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-cyber-base rounded-full overflow-hidden border border-cyber/60 p-0.5">
                            <div class="h-full rounded-full transition-all duration-500 ease-out {{ $statusColor['bar'] }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Action Link --}}
                <div class="pt-3 border-t border-cyber/60 flex items-center justify-between text-xs">
                    <a href="{{ route('manager.workload.index', ['branch_id' => $b->id]) }}" class="text-sky-600 dark:text-cyan-400 hover:underline font-bold inline-flex items-center gap-1 text-[11px]">
                        <span>View Workload Queue</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-10 text-cyber-muted text-xs bg-cyber-card border border-cyber rounded-3xl">
                No active branches configured in the system.
            </div>
        @endforelse
    </div>

</div>
@endsection
