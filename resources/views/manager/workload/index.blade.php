@extends('layouts.internal')
@section('title', 'Workload Monitor — Real-Time Queue & Branch Utilization')
@section('page-title', 'Workload Monitor')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display tracking-tight">Workload Monitor</h1>
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
    {{-- MULTI-BRANCH WORKLOAD & QUEUE PRIORITY VISUAL ANALYTICS (SPLIT GRID) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: MULTI-BRANCH WORKLOAD VS CAPACITY BAR GRAPH --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber-sub pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-lg bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Multi-Branch Workload Distribution</h3>
                        </div>
                        <p class="text-[11px] sm:text-xs text-cyber-muted mt-1">Live active floor job volume across network printing facilities</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded" style="background-color: #2563EB;"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Morning Star Press</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded-full" style="background-color: #2563EB;"></span>
                            <span class="text-cyber-main text-xs font-semibold">Morning Star Network</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-2.5 rounded" style="background-color: #059669;"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Green Heart Hub</span>
                        </div>
                    </div>
                </div>

                {{-- Bar Chart Canvas --}}
                <div class="relative h-64 sm:h-72 w-full pt-3">
                    <canvas id="branchCapacityBarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT 1 COL: QUEUE PRIORITY DISTRIBUTION DONUT CHART --}}
        <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber-sub pb-3">
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Queue Priority Mix</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Floor urgency &amp; queue pressure</p>
                    </div>
                    <div class="h-8 w-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                </div>

                @php
                    $pList = $priorityBreakdown ?? [
                        'Rush / Urgent'    => 6,
                        'High Priority'    => 12,
                        'Standard Run'     => 28,
                        'Quality Checking' => 5,
                    ];
                    $totalPJobs = max(1, array_sum($pList));
                    $pLabels = array_keys($pList);
                    $pValues = array_values($pList);
                    $pColors = [
                        '#f43f5e', // Rose for Rush / Urgent
                        '#f59e0b', // Amber for High Priority
                        '#2563EB', // Cyan for Standard Run
                        '#8b5cf6', // Purple for Quality Checking
                    ];
                @endphp

                {{-- Donut Canvas Container with Centered Metric --}}
                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="queuePriorityDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black font-display text-cyber-main leading-tight">{{ $totalPJobs }}</span>
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-cyber-muted">Floor Runs</span>
                    </div>
                </div>

                {{-- Clean Priority Legend --}}
                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($pList as $pName => $pCount)
                        @php
                            $pPct = round(($pCount / $totalPJobs) * 100);
                            $pDot = $pColors[$loop->index % count($pColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-cyber-sub/50 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0 shadow-sm" style="background-color: {{ $pDot }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $pName }}</span>
                            </div>
                            <span class="font-sans tabular-nums text-[11px] text-cyber-muted shrink-0 ml-2 font-bold">
                                {{ $pCount }} <span class="text-[11px] font-normal text-cyber-sub">({{ $pPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- BRANCH WORKLOAD CARDS GRID (FUNCTIONAL CLICK-TO-FILTER) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($branches as $index => $b)
        @php
            $util = $b->workload_percent;
            if ($b->active_job_count == 0) {
                $topBorder = 'border-t-2 border-t-brand-600/40 dark:border-t-blue-500/40';
                $barGradient = 'bg-brand-600 dark:bg-blue-500';
                $waveColor = '#2563EB';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35';
                $fillPoints = '0,35 40,35 80,35 120,35 160,35 200,35 240,35 280,35 320,35 320,40 0,40';
            } elseif ($util < 50) {
                $topBorder = 'border-t-2 border-t-emerald-600';
                $barGradient = 'bg-emerald-600';
                $waveColor = '#059669';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34';
                $fillPoints = '0,32 40,30 70,22 100,14 130,26 180,32 240,33 320,34 320,40 0,40';
            } elseif ($util < 80) {
                $topBorder = 'border-t-2 border-t-amber-600';
                $barGradient = 'bg-amber-600';
                $waveColor = '#d97706';
                $waveGradientId = 'wave-grad-' . $b->id;
                $points = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8';
                $fillPoints = '0,34 40,30 80,20 120,12 180,8 240,8 280,8 320,8 320,40 0,40';
            } else {
                $topBorder = 'border-t-2 border-t-rose-600';
                $barGradient = 'bg-rose-600';
                $waveColor = '#dc2626';
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
           class="bg-cyber-card rounded-xl border {{ $isBranchSelected ? 'ring-1 ring-brand-600/50 border-brand-600' : 'border-cyber hover:border-brand-600/40 dark:hover:border-blue-500/40' }} {{ $topBorder }} p-5 flex flex-col justify-between space-y-5 transition-colors group cursor-pointer"
           title="{{ $isBranchSelected ? 'Click to show all branches' : 'Click to filter queue by ' . $b->name }}">
            
            {{-- Branch Card Header --}}
            <div>
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-cyber-main font-display text-base tracking-tight truncate group-hover:text-brand-600 dark:group-hover:text-blue-400 transition-colors">{{ $b->name }}</h3>
                            @if($b->rush_count > 0)
                                <span class="px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30 flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation text-[11px]"></i> {{ $b->rush_count }} RUSH
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-cyber-muted mt-1 truncate">
                            {{ $b->location ?? 'Metro Hub' }} · {{ $b->manager_name ?? 'Branch Supervisor' }}
                        </p>
                    </div>

                    @if($isBranchSelected)
                        <span class="px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider bg-brand-600/10 text-brand-700 dark:text-blue-400 border border-brand-600/25 shrink-0">
                            Selected
                        </span>
                    @endif
                </div>

                {{-- Key Numerical Rows --}}
                <div class="space-y-2.5 mt-5 pt-4 border-t border-cyber-sub text-xs tabular-nums">
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Active Job Load</span>
                        <strong class="font-sans tabular-nums font-bold text-cyber-main text-xs">{{ $b->active_job_count }} {{ Str::plural('job', $b->active_job_count) }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Delayed Jobs</span>
                        <strong class="font-sans tabular-nums font-bold text-xs {{ $b->delayed_count > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-cyber-muted' }}">{{ $b->delayed_count }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-cyber-muted font-medium">Rush / Urgent</span>
                        <span class="font-sans tabular-nums font-bold text-xs flex items-center gap-1.5 {{ $b->rush_count > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-cyber-muted' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $b->rush_count > 0 ? 'bg-amber-500' : 'bg-transparent' }}"></span>
                            {{ $b->rush_count }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Utilization Gauge Bar --}}
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between text-xs tabular-nums">
                    <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted">UTILIZATION</span>
                    <strong class="font-sans tabular-nums font-bold {{ $b->active_job_count == 0 ? 'text-brand-600 dark:text-blue-400' : ($util >= 80 ? 'text-rose-600 dark:text-rose-400' : ($util >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')) }}">
                        {{ number_format($util, 1) }}%
                    </strong>
                </div>
                
                {{-- Progress Bar --}}
                <div class="w-full h-2 bg-cyber-sub rounded overflow-hidden border border-cyber">
                    <div class="h-full {{ $barGradient }} transition-all duration-700" style="width: {{ min(max($util, 4), 100) }}%"></div>
                </div>

                {{-- Milestone Markers --}}
                <div class="flex justify-between text-[11px] font-sans tabular-nums text-cyber-muted px-0.5 tabular-nums">
                    <span>0%</span>
                    <span>40%</span>
                    <span>70%</span>
                    <span>100%</span>
                </div>
            </div>

            {{-- Sparkline Load Trend Wave --}}
            <div class="pt-2">
                <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted block mb-2">TODAY'S LOAD TREND</span>
                <div class="w-full h-12 relative overflow-hidden rounded-lg bg-cyber-sub border border-cyber flex items-end">
                    <svg class="w-full h-full" viewBox="0 0 320 40" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="{{ $waveGradientId }}" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="{{ $waveColor }}" stop-opacity="0.25" />
                                <stop offset="100%" stop-color="{{ $waveColor }}" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $fillPoints }}" fill="url(#{{ $waveGradientId }})" />
                        <polyline points="{{ $points }}" fill="none" stroke="{{ $waveColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

        </a>
        @endforeach
    </div>

    {{-- LIVE ACTIVE PRODUCTION QUEUE TABLE --}}
    <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col mt-6">
        <div class="px-5 py-4 border-b border-cyber bg-cyber-sub flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Active Production Queue</h3>
            </div>
            
            {{-- Branch & Queue Filters --}}
            <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                @if(request()->hasAny(['filter', 'branch_id']))
                    <a href="{{ route('manager.workload.index') }}" 
                       class="px-2.5 py-1.5 rounded-lg bg-cyber-card hover:bg-cyber-sub text-cyber-muted hover:text-cyber-main text-xs font-semibold border border-cyber inline-flex items-center gap-1.5 transition"
                       title="Clear all filters">
                        <i class="fa-solid fa-rotate-left text-[11px]"></i>
                        <span>Reset Filters</span>
                    </a>
                @endif

                <form action="{{ route('manager.workload.index') }}" method="GET" class="flex items-center gap-2">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <select name="branch_id" onchange="this.form.submit()" class="bg-cyber-card border border-cyber rounded-lg px-3 py-1.5 text-xs text-cyber-main font-semibold focus:border-brand-600 focus:outline-none w-full sm:w-auto">
                        <option value="">All Branches ({{ $branches->count() }})</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $branchFilter == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main tabular-nums">
                <thead class="bg-cyber-sub/60 text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Job Code</th>
                        <th class="px-5 py-3">Branch Assigned</th>
                        <th class="px-5 py-3">Customer / Order</th>
                        <th class="px-5 py-3">Priority</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Started At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-cyber-sub/40 transition-colors">
                        <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-brand-600 dark:text-blue-400">
                            #{{ $job->job_number ?? ($job->job_code ?? ('JOB-' . str_pad($job->id, 5, '0', STR_PAD_LEFT))) }}
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $job->branch->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold block text-cyber-main">{{ $job->order->user->name ?? 'Demo Customer' }}</span>
                            <span class="text-[11px] font-sans tabular-nums text-cyber-muted">{{ $job->order->order_number ?? 'ORD-REF' }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if(in_array($job->priority, ['rush', 'urgent']))
                                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">
                                    {{ strtoupper($job->priority) }}
                                </span>
                            @else
                                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                    STANDARD
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $statusTextColor = match($job->status) {
                                    'in_production', 'running' => 'text-blue-600 dark:text-blue-400',
                                    'delayed'                  => 'text-rose-600 dark:text-rose-400',
                                    'quality_checking'         => 'text-indigo-600 dark:text-indigo-400',
                                    default                    => 'text-slate-600 dark:text-slate-400',
                                };
                            @endphp
                            <span class="text-xs font-semibold {{ $statusTextColor }}">
                                {{ str_replace('_', ' ', strtoupper($job->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-cyber-muted font-sans tabular-nums text-[11px] text-right whitespace-nowrap">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');

        // 1. Multi-Branch Workload Bar Chart (Straight Flat Columns with Exact Sample Colors)
        const barCanvas = document.getElementById('branchCapacityBarChart');
        if (barCanvas) {
            const labels = @json($branchChartLabels);
            const activeData = @json($branchActiveData);
            const capacityData = @json($branchCapacityData);

            // Exact color codes sampled from user's reference:
            // Teal (#2563EB), Deep Navy (#4F46E5), Lime Green (#059669)
            const sampleColors = ['#2563EB', '#4F46E5', '#059669'];
            const sampleHoverColors = ['#00b4b8', '#02569c', '#8ee568'];
            const sampleBorders = isDark 
                ? ['#00c4c8', '#1e6bb8', '#9ef578'] 
                : ['#007a7e', '#012c52', '#6bc244'];

            new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Active Floor Jobs',
                            data: activeData,
                            backgroundColor: sampleColors,
                            hoverBackgroundColor: sampleHoverColors,
                            borderColor: sampleBorders,
                            borderWidth: 1.5,
                            borderRadius: 0,          // 100% STRAIGHT (flat top, no rounded curves)
                            borderSkipped: false,
                            maxBarThickness: 56,      // Substantial straight rectangular columns
                            barPercentage: 0.8,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                            cornerRadius: 6,
                            boxPadding: 4,
                            callbacks: {
                                label: function(context) {
                                    const val = context.raw || 0;
                                    const idx = context.dataIndex;
                                    const cap = capacityData[idx] || 50;
                                    const util = Math.round((val / cap) * 100);
                                    return [
                                        ` Active Load: ${val} jobs`,
                                        ` Daily Rated Capacity: ${cap} jobs/day`,
                                        ` Branch Utilization: ${util}%`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Plus Jakarta Sans, sans-serif',
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Plus Jakarta Sans, sans-serif',
                                    size: 10
                                }
                            },
                            title: {
                                display: true,
                                text: 'Jobs Volume',
                                color: isDark ? '#64748b' : '#94a3b8',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // 2. Queue Priority Mix Donut Chart
        const donutCanvas = document.getElementById('queuePriorityDonutChart');
        if (donutCanvas) {
            const pLabels = @json($pLabels);
            const pData = @json($pValues);
            const pColors = ['#f43f5e', '#f59e0b', '#2563EB', '#8b5cf6'];

            new Chart(donutCanvas, {
                type: 'doughnut',
                data: {
                    labels: pLabels,
                    datasets: [{
                        data: pData,
                        backgroundColor: pColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#151922' : '#ffffff',
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
                                    return ` ${context.label}: ${val} runs (${pct}%)`;
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
        }
    });
</script>
@endsection
