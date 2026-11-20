@props([
    'branches' => [],
    'title' => 'Multi-Branch Capacity & Load Balancing',
    'subtitle' => 'Live press utilization and active job volume across printing network locations',
    'actionUrl' => null,
    'actionLabel' => 'Manage Branches',
])

<div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col h-full">
    <div class="px-5 sm:px-6 py-4 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-cyber-sub">
        <div>
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">{{ $title }}</h3>
            @if(!empty($subtitle))
                <p class="text-xs text-cyber-muted mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if($actionUrl)
            <a href="{{ $actionUrl }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline inline-flex items-center gap-1.5 shrink-0">
                <span>{{ $actionLabel }}</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-cyber flex-1">
        @forelse($branches as $b)
            @php
                $activeJobs = $b->active_jobs_count ?? ($b->active_jobs ?? 0);
                $maxJobs = max(1, $b->max_daily_jobs ?? 20);
                $pct = min(100, round(($activeJobs / $maxJobs) * 100));

                // Strict 3-status color limit: Warm Yellow (High Load), Brand Blue (Active), Live Green (Optimal)
                $statusColor = match(true) {
                    $pct >= 80 => [
                        'text'  => 'text-amber-600 dark:text-[#FFC531] font-semibold',
                        'bar'   => 'bg-[#FFC531]',
                        'dot'   => 'bg-[#FFC531]',
                        'label' => 'Near Capacity',
                    ],
                    $pct >= 40 => [
                        'text'  => 'text-[#2F7BFF] dark:text-[#60A5FA] font-semibold',
                        'bar'   => 'bg-[#2F7BFF]',
                        'dot'   => 'bg-[#2F7BFF]',
                        'label' => 'Active Load',
                    ],
                    default => [
                        'text'  => 'text-emerald-600 dark:text-[#10B981] font-semibold',
                        'bar'   => 'bg-[#10B981]',
                        'dot'   => 'bg-[#10B981]',
                        'label' => 'Available',
                    ],
                };

                $availMachines = $b->available_machines_count ?? ($b->machines ? $b->machines->where('status', 'available')->count() : 0);
                $totalMachines = $b->machines_count ?? ($b->machines ? $b->machines->count() : 0);
            @endphp

            <a href="{{ $actionUrl ?? route('management.branches.index') }}" class="p-5 flex flex-col justify-between gap-4 hover:bg-cyber-sub/50 transition-colors group block">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h4 class="font-bold text-cyber-main text-sm font-display group-hover:text-[#2F7BFF] dark:group-hover:text-[#60A5FA] transition-colors flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $statusColor['dot'] }} shrink-0"></span>
                                <span class="truncate" title="{{ $b->name }}">{{ $b->name }}</span>
                            </h4>
                            <p class="text-xs text-cyber-muted mt-0.5 pl-4">{{ $b->location ?? 'Press Hub' }}</p>
                        </div>
                        <span class="font-mono text-xs tabular-nums {{ $statusColor['text'] }} shrink-0">{{ $pct }}%</span>
                    </div>

                    <div class="w-full h-1.5 rounded-full overflow-hidden bg-black/10 dark:bg-white/10">
                        <div class="h-full rounded-full transition-all duration-300 {{ $statusColor['bar'] }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>

                <div class="divide-y divide-cyber-sub border-t border-cyber text-xs tabular-nums pt-1">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-cyber-muted">Press Fleet</span>
                        <span class="font-mono font-semibold text-cyber-main">{{ $availMachines }}/{{ $totalMachines }} online</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-cyber-muted">Active Jobs</span>
                        <span class="font-mono font-semibold text-cyber-main">{{ $activeJobs }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 pb-0">
                        <span class="text-cyber-muted">Daily Capacity</span>
                        <span class="font-mono font-semibold text-cyber-main">{{ $b->max_daily_jobs ?? 25 }}/day</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-8 text-cyber-muted text-xs">
                No active branches configured in the system.
            </div>
        @endforelse
    </div>
</div>
