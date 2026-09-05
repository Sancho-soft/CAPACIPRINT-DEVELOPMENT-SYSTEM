@props([
    'branches' => [],
    'title' => 'Multi-Branch Capacity & Load Balancing',
    'subtitle' => 'Live press utilization and active job volume across printing network locations',
    'actionUrl' => null,
    'actionLabel' => 'Manage Branches',
])

<div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber/80 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-cyan-400 text-sm"></i>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">{{ $title }}</h3>
            </div>
            <p class="text-[11px] sm:text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
        </div>
        @if($actionUrl)
            <a href="{{ $actionUrl }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1 shrink-0">
                {{ $actionLabel }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
        @forelse($branches as $b)
            @php
                $activeJobs = $b->active_jobs_count ?? ($b->active_jobs ?? 0);
                $maxJobs = max(1, $b->max_daily_jobs ?? 20);
                $pct = min(100, round(($activeJobs / $maxJobs) * 100));
                
                $statusColor = match(true) {
                    $pct >= 85 => [
                        'text'  => 'text-rose-600 dark:text-rose-400 font-bold',
                        'bar'   => 'bg-rose-500',
                        'badge' => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30',
                        'label' => 'High Load',
                    ],
                    $pct >= 50 => [
                        'text'  => 'text-amber-600 dark:text-amber-400 font-bold',
                        'bar'   => 'bg-amber-400',
                        'badge' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                        'label' => 'Moderate',
                    ],
                    default => [
                        'text'  => 'text-emerald-600 dark:text-emerald-400 font-bold',
                        'bar'   => 'bg-emerald-400',
                        'badge' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
                        'label' => 'Optimal',
                    ],
                };

                $availMachines = $b->available_machines_count ?? ($b->machines ? $b->machines->where('status', 'available')->count() : 0);
                $totalMachines = $b->machines_count ?? ($b->machines ? $b->machines->count() : 0);
            @endphp

            <div class="p-4 sm:p-5 rounded-2xl border border-cyber bg-cyber-sub/60 hover:border-cyan-500/30 transition-all duration-200 flex flex-col justify-between space-y-3 group">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h4 class="font-black text-cyber-main text-sm truncate font-display group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">
                            {{ $b->name }}
                        </h4>
                        <span class="text-[10px] text-cyber-muted flex items-center gap-1 mt-0.5">
                            <i class="fa-solid fa-location-dot text-[9px] text-cyan-600 dark:text-cyan-400"></i>
                            {{ $b->location ?? 'Hub' }}
                        </span>
                    </div>
                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded border {{ $statusColor['badge'] }} shrink-0 font-mono">
                        {{ $statusColor['label'] }}
                    </span>
                </div>

                {{-- Key Spec Metrics --}}
                <div class="space-y-1.5 text-xs text-cyber-muted font-medium bg-cyber-card/60 p-3 rounded-xl border border-cyber/50">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Press Machines:</span>
                        <span class="font-mono font-bold text-cyber-main">
                            <strong class="text-cyan-600 dark:text-cyan-400 font-extrabold">{{ $availMachines }}</strong> / {{ $totalMachines }} operational
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Active Jobs on Floor:</span>
                        <span class="font-mono font-bold text-cyber-main">{{ $activeJobs }} jobs</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Daily Rated Capacity:</span>
                        <span class="font-mono font-bold text-slate-600 dark:text-slate-300">{{ $b->max_daily_jobs ?? 25 }} jobs/day</span>
                    </div>
                </div>

                {{-- Utilization Progress Bar --}}
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-wider">
                        <span class="text-cyber-muted">Capacity Utilization</span>
                        <span class="{{ $statusColor['text'] }} font-mono text-xs">{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 bg-cyber-base rounded-full overflow-hidden border border-cyber/60 p-0.5">
                        <div class="h-full rounded-full transition-all duration-500 ease-out {{ $statusColor['bar'] }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-6 text-cyber-muted text-xs">
                No active branches configured in the system.
            </div>
        @endforelse
    </div>
</div>
