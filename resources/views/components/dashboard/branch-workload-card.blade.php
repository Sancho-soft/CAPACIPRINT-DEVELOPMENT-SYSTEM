@props([
    'branches' => [],
    'title' => 'Multi-Branch Capacity & Load Balancing',
    'subtitle' => 'Live press utilization and active job volume across printing network locations',
    'actionUrl' => null,
    'actionLabel' => 'Manage Branches',
])

<div class="bg-cyber-card border border-cyber rounded-2xl p-5 sm:p-6 space-y-4 h-full flex flex-col justify-between shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber-sub pb-4">
        <div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-blue-600 dark:text-blue-400 text-xs"></i>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            </div>
            @if(!empty($subtitle))
                <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($actionUrl)
            <a href="{{ $actionUrl }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 shrink-0">
                {{ $actionLabel }} <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1 flex-1">
        @forelse($branches as $b)
            @php
                $activeJobs = $b->active_jobs_count ?? ($b->active_jobs ?? 0);
                $maxJobs = max(1, $b->max_daily_jobs ?? 20);
                $pct = min(100, round(($activeJobs / $maxJobs) * 100));
                
                $statusColor = match(true) {
                    $pct >= 85 => [
                        'text'  => 'text-rose-700 dark:text-rose-400 font-semibold',
                        'bar'   => 'bg-rose-600 dark:bg-rose-500',
                        'dot'   => 'bg-rose-600',
                        'label' => 'High Load',
                    ],
                    $pct >= 50 => [
                        'text'  => 'text-amber-700 dark:text-amber-400 font-semibold',
                        'bar'   => 'bg-amber-600 dark:bg-amber-500',
                        'dot'   => 'bg-amber-500',
                        'label' => 'Moderate',
                    ],
                    default => [
                        'text'  => 'text-emerald-700 dark:text-emerald-400 font-semibold',
                        'bar'   => 'bg-emerald-600 dark:bg-emerald-500',
                        'dot'   => 'bg-emerald-500',
                        'label' => 'Optimal',
                    ],
                };

                $availMachines = $b->available_machines_count ?? ($b->machines ? $b->machines->where('status', 'available')->count() : 0);
                $totalMachines = $b->machines_count ?? ($b->machines ? $b->machines->count() : 0);
            @endphp

            <a href="{{ $actionUrl ?? route('management.branches.index') }}" class="p-4 rounded-xl border border-cyber bg-cyber-sub hover:border-blue-600/40 dark:hover:border-blue-500/40 transition-colors duration-150 flex flex-col justify-between space-y-3 group cursor-pointer block h-full">
                {{-- Header --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-cyber-main text-xs sm:text-sm font-display group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors flex items-start gap-2 leading-snug min-h-[2.25rem]">
                            <span class="w-2 h-2 rounded-full {{ $statusColor['dot'] }} inline-block shrink-0 mt-1.5"></span>
                            <span class="line-clamp-2" title="{{ $b->name }}">{{ $b->name }}</span>
                        </h4>
                        <span class="text-xs text-cyber-muted flex items-center gap-1.5 mt-1 font-sans">
                            <i class="fa-solid fa-location-dot text-[11px] text-blue-600 dark:text-blue-400"></i>
                            {{ $b->location ?? 'Hub' }}
                        </span>
                    </div>
                    <span class="text-[11px] font-sans font-semibold text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 shrink-0">
                        Inspect <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </span>
                </div>

                {{-- Key Spec Metrics --}}
                <div class="text-xs text-cyber-muted bg-cyber-card rounded-xl border border-cyber divide-y divide-cyber-sub tabular-nums overflow-hidden">
                    <div class="flex justify-between items-center text-xs px-3 py-2">
                        <span class="text-cyber-muted font-sans">Press Fleet</span>
                        <span class="font-sans font-semibold text-cyber-main text-right tabular-nums">
                            <strong class="text-blue-600 dark:text-blue-400 font-bold">{{ $availMachines }}</strong> / {{ $totalMachines }} online
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-xs px-3 py-2">
                        <span class="text-cyber-muted font-sans">Active Jobs</span>
                        <span class="font-sans font-semibold text-cyber-main text-right tabular-nums">{{ $activeJobs }} jobs</span>
                    </div>
                    <div class="flex justify-between items-center text-xs px-3 py-2">
                        <span class="text-cyber-muted font-sans">Rated Capacity</span>
                        <span class="font-sans font-semibold text-cyber-main text-right tabular-nums">{{ $b->max_daily_jobs ?? 25 }}/day</span>
                    </div>
                </div>

                {{-- Utilization Progress Bar --}}
                <div class="space-y-1.5 pt-1">
                    <div class="flex justify-between items-center text-xs font-sans font-semibold">
                        <span class="text-cyber-muted">Utilization</span>
                        <span class="{{ $statusColor['text'] }} tabular-nums text-xs">{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full overflow-hidden bg-black/10 dark:bg-white/10">
                        <div class="h-full rounded-full transition-all duration-300 ease-out {{ $statusColor['bar'] }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-6 text-cyber-muted text-xs">
                No active branches configured in the system.
            </div>
        @endforelse
    </div>
</div>
