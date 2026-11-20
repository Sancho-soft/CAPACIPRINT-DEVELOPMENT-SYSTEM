@extends('layouts.internal')
@section('title', 'Branch & Capacity Operations Hub')
@section('page-title', 'Branch & Capacity Operations Hub')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Branch &amp; Capacity Hub</h1>
            <p class="text-sm text-cyber-muted mt-1">Press load distribution, schedule deadlines, and shop-floor machine readiness.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('manager.capacity.index') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-calculator text-xs"></i>
                <span>Evaluate Capacity</span>
            </a>
            <a href="{{ route('manager.production-planning.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub text-cyber-main font-semibold text-xs border border-cyber transition inline-flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-xs text-[#2F7BFF]"></i>
                <span>Plan Schedule</span>
            </a>
            <a href="{{ route('manager.purchasing.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub text-cyber-main font-semibold text-xs border border-cyber transition inline-flex items-center gap-2">
                <i class="fa-solid fa-boxes-packing text-xs text-[#FFC531]"></i>
                <span>Material Reorders</span>
            </a>
        </div>
    </div>

    {{-- 5 OPERATIONAL PRODUCTION METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-dashboard.kpi-card 
            title="Active Floor Jobs"
            :value="$totalActiveJobs"
            accent="blue"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="Due Today"
            :value="$jobsDueToday"
            accent="emerald"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="Rush &amp; Urgent Runs"
            :value="$rushJobs"
            accent="amber"
            :link="route('manager.production-planning.index', ['priority' => 'rush_urgent'])"
        />

        <x-dashboard.kpi-card 
            title="Press Run Delays"
            :value="$delayedJobs"
            accent="amber"
            :link="route('manager.production-planning.index', ['status' => 'delayed'])"
        />

        <x-dashboard.kpi-card 
            title="Press Fleet Ready"
            :value="$availableMachines . '/' . $totalMachines"
            accent="blue"
            :link="route('manager.capacity.index')"
        />
    </div>

    {{-- COMMERCIAL PRINTING WORKFLOW PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Lifecycle"
        subtitle=""
    />

    {{-- MULTI-BRANCH WORKLOAD & FLOOR STATUS DONUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: MULTI-BRANCH CAPACITY --}}
        <div class="lg:col-span-2 flex flex-col h-full">
            <x-dashboard.branch-workload-card 
                :branches="$branches"
                title="Branch Capacity Utilization &amp; Machine Workload"
                subtitle=""
                :actionUrl="route('manager.capacity.index')"
                actionLabel="Capacity Evaluation Matrix"
            />
        </div>

        {{-- RIGHT 1 COL: FLOOR STATUS DONUT CHART --}}
        <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="border-b border-cyber pb-3">
                    <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Floor Status Health</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Active shop-floor job distribution</p>
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
                    $statusHexPalette = [
                        '#2F7BFF', // Primary Blue (In Production)
                        '#60A5FA', // Light Cyan Blue (Quality Checking)
                        '#FFC531', // Warm Yellow (Assigned / Queue)
                        '#E5337A', // CMYK Magenta (Delayed Runs)
                        '#10B981', // Live Green (Completed Today)
                    ];
                @endphp

                <div class="relative flex items-center justify-center my-3 h-44 w-full">
                    <canvas id="jobStatusDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold font-mono text-cyber-main leading-tight tabular-nums">{{ $totalStatusJobs }}</span>
                        <span class="text-xs font-sans font-medium text-cyber-muted">Total Jobs</span>
                    </div>
                </div>

                <div class="divide-y divide-cyber-sub pt-2 border-t border-cyber flex-1 overflow-y-auto tabular-nums">
                    @foreach($statusList as $stName => $stCount)
                        @php
                            $stPct = round(($stCount / $totalStatusJobs) * 100);
                            $dotHex = $statusHexPalette[$loop->index % count($statusHexPalette)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-2 px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-sm shrink-0" style="background-color: {{ $dotHex }}"></span>
                                <span class="text-cyber-main font-medium truncate">{{ $stName }}</span>
                            </div>
                            <span class="font-mono text-xs text-cyber-muted shrink-0 ml-2 font-semibold tabular-nums">
                                {{ $stCount }} <span class="font-normal text-cyber-sub">({{ $stPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- LIVE PRODUCTION JOBS TABLE --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Active Production Jobs &amp; Press Allocation"
        subtitle=""
        :viewAllUrl="route('manager.production-planning.index')"
        viewAllLabel="Full Production Schedule"
    />

    {{-- MACHINE MAINTENANCE SCHEDULE ALERTS --}}
    @if($maintenanceAlerts->isNotEmpty())
    <div class="bg-cyber-card border border-[#FFC531]/40 rounded-xl overflow-hidden">
        <div class="px-5 sm:px-6 py-4 bg-cyber-sub border-b border-cyber flex items-center justify-between">
            <div>
                <h3 class="font-bold text-cyber-main text-sm font-display">Machine Maintenance Alerts</h3>
                <p class="text-xs text-cyber-muted mt-0.5">{{ $maintenanceAlerts->count() }} machine(s) require attention</p>
            </div>
            <a href="{{ route('production.machines.index') }}" class="px-3.5 py-1.5 rounded-lg bg-cyber-card hover:border-[#2F7BFF] border border-cyber text-cyber-main font-semibold text-xs transition">
                View Logs &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-cyber">
            @foreach($maintenanceAlerts as $machine)
                @php
                    $isOverdue = $machine->maintenance_overdue;
                    $isDown    = in_array($machine->status, ['maintenance', 'offline']);
                    $toneText  = ($isOverdue || $isDown) ? 'text-amber-700 dark:text-[#FFC531]' : 'text-[#2F7BFF] dark:text-[#60A5FA]';
                    $toneDot   = ($isOverdue || $isDown) ? 'bg-[#FFC531]' : 'bg-[#2F7BFF]';
                    $badgeLabel = $isOverdue ? 'Overdue' : ($isDown ? ucfirst($machine->status) : 'Due Soon');
                @endphp
                <div class="p-4 sm:p-5 space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-semibold text-cyber-main text-xs truncate">{{ $machine->name }}</span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-mono font-semibold {{ $toneText }} shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $toneDot }}"></span>
                            {{ $badgeLabel }}
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted">
                        {{ $machine->branch->name ?? 'Branch' }} &middot; {{ $machine->type }}
                    </p>
                    @if($machine->next_maintenance_date)
                        <p class="text-xs font-mono tabular-nums {{ $isOverdue ? 'text-amber-700 dark:text-[#FFC531]' : 'text-cyber-muted' }}">
                            Next: {{ $machine->next_maintenance_date->format('M d, Y') }}
                        </p>
                    @else
                        <p class="text-xs text-amber-700 dark:text-[#FFC531]">No maintenance schedule set</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script type="application/json" id="manager-donut-chart-data">
{
    "labels": @json($statusLabels),
    "data": @json($statusValues)
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('jobStatusDonutChart');
        if (!canvas) return;

        const dataScript = document.getElementById('manager-donut-chart-data');
        const parsed = dataScript ? JSON.parse(dataScript.textContent) : { labels: [], data: [] };
        const labels = parsed.labels || [];
        const data = parsed.data || [];

        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');
        const colors = [
            '#2F7BFF',
            '#60A5FA',
            '#FFC531',
            '#E5337A',
            '#10B981'
        ];

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: isDark ? '#111827' : '#FFFFFF',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '64%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(17, 24, 39, 0.96)' : 'rgba(255, 255, 255, 0.96)',
                        titleColor: isDark ? '#F8FAFC' : '#0F172A',
                        bodyColor: isDark ? '#94A3B8' : '#475569',
                        borderColor: isDark ? '#1E293B' : '#E2E8F0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
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
                    duration: 800,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
</script>
@endsection
