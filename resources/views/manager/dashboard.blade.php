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
    {{-- LEVEL 2 & 3: MULTI-BRANCH WORKLOAD & PRODUCTION FLOOR STATUS DONUT CHART --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: MULTI-BRANCH CAPACITY --}}
        <div class="lg:col-span-2 flex flex-col h-full">
            <x-dashboard.branch-workload-card 
                :branches="$branches"
                title="Branch Capacity Utilization & Machine Workload"
                subtitle=""
                :actionUrl="route('manager.capacity.index')"
                actionLabel="Capacity Evaluation Matrix"
            />
        </div>

        {{-- RIGHT 1 COL: PRODUCTION FLOOR STATUS DONUT CHART --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Floor Status Health</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Active shop-floor job distribution</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs shadow-xs shrink-0">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>

                @php
                    $statusList = $jobStatusBreakdown ?? [
                        'In Production'    => 5,
                        'Quality Checking' => 2,
                        'Assigned / Queue' => 3,
                        'Delayed Runs'     => 1,
                        'Completed Today'  => 4,
                    ];
                    $totalStatusJobs = max(1, array_sum($statusList));
                    $statusLabels = array_keys($statusList);
                    $statusValues = array_values($statusList);
                    $statusColors = [
                        '#06b6d4', // Cyan for In Production
                        '#8b5cf6', // Purple for Quality Checking
                        '#f59e0b', // Amber for Assigned / Queue
                        '#f43f5e', // Rose for Delayed Runs
                        '#10b981', // Emerald for Completed Today
                    ];
                @endphp

                {{-- Chart Canvas Container with Centered Metric --}}
                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="jobStatusDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black font-display text-cyber-main leading-tight">{{ $totalStatusJobs }}</span>
                        <span class="text-[9px] font-black uppercase tracking-wider text-cyber-muted">Total Jobs</span>
                    </div>
                </div>

                {{-- Clean Status Legend --}}
                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($statusList as $stName => $stCount)
                        @php
                            $stPct = round(($stCount / $totalStatusJobs) * 100);
                            $dotColor = $statusColors[$loop->index % count($statusColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-cyber-sub/50 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $dotColor }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $stName }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-cyber-muted shrink-0 ml-2 font-bold">
                                {{ $stCount }} <span class="text-[10px] font-normal text-cyber-sub">({{ $stPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

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

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MACHINE MAINTENANCE SCHEDULE ALERTS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($maintenanceAlerts->isNotEmpty())
    <div class="bg-cyber-card border border-amber-500/30 rounded-3xl shadow-xl p-6 sm:p-7 space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <h3 class="font-black text-cyber-main text-sm font-display">Machine Maintenance Alerts</h3>
                    <p class="text-[10px] text-cyber-muted">{{ $maintenanceAlerts->count() }} machine(s) require attention</p>
                </div>
            </div>
            <a href="{{ route('production.machines.index') }}" class="px-3 py-1.5 rounded-lg bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-[10px] transition">
                View Logs →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($maintenanceAlerts as $machine)
                @php
                    $isOverdue  = $machine->maintenance_overdue;
                    $isDueSoon  = $machine->maintenance_due_soon;
                    $isDown     = in_array($machine->status, ['maintenance', 'offline']);

                    $borderClass = $isOverdue ? 'border-rose-500/40' : ($isDown ? 'border-amber-500/40' : 'border-cyan-500/30');
                    $iconColor   = $isOverdue ? 'text-rose-400' : ($isDown ? 'text-amber-400' : 'text-cyan-400');
                    $badgeClass  = $isOverdue ? 'bg-rose-500/15 text-rose-400' : ($isDown ? 'bg-amber-500/15 text-amber-400' : 'bg-cyan-500/15 text-cyan-400');
                    $badgeLabel  = $isOverdue ? 'OVERDUE' : ($isDown ? strtoupper($machine->status) : 'DUE SOON');
                @endphp
                <div class="bg-cyber-sub/50 rounded-xl border {{ $borderClass }} p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-print {{ $iconColor }} text-xs"></i>
                            <span class="font-bold text-cyber-main text-xs truncate max-w-[140px]">{{ $machine->name }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </div>
                    <p class="text-[10px] text-cyber-muted">
                        <i class="fa-solid fa-location-dot mr-0.5"></i> {{ $machine->branch->name ?? 'Branch' }}
                        · {{ $machine->type }}
                    </p>
                    @if($machine->next_maintenance_date)
                        <p class="text-[10px] font-mono {{ $isOverdue ? 'text-rose-400' : 'text-cyber-muted' }}">
                            <i class="fa-solid fa-calendar-day mr-0.5"></i>
                            Next maintenance: {{ $machine->next_maintenance_date->format('M d, Y') }}
                            @if($isOverdue)
                                ({{ $machine->next_maintenance_date->diffForHumans() }})
                            @endif
                        </p>
                    @else
                        <p class="text-[10px] text-amber-400 font-mono">
                            <i class="fa-solid fa-triangle-exclamation mr-0.5"></i> No maintenance schedule set
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('jobStatusDonutChart');
        if (!canvas) return;

        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');
        const labels = @json($statusLabels);
        const data = @json($statusValues);
        const colors = [
            '#06b6d4', // Cyan (In Production)
            '#8b5cf6', // Purple (Quality Checking)
            '#f59e0b', // Amber (Assigned / Queue)
            '#f43f5e', // Rose (Delayed Runs)
            '#10b981'  // Emerald (Completed Today)
        ];

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: isDark ? '#111A24' : '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                        titleColor: isDark ? '#f8fafc' : '#0f172a',
                        bodyColor: isDark ? '#94a3b8' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        boxPadding: 4,
                        callbacks: {
                            label: function(context) {
                                const val = context.raw || 0;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                return ` ${context.label}: ${val} jobs (${pct}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1000
                }
            }
        });
    });
</script>
@endsection
