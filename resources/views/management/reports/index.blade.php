@extends('layouts.internal')
@section('title', 'Executive Reports & Analytics')
@section('page-title', 'Executive Reports & Analytics Hub')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: EXECUTIVE REPORTS HUB (UNBOXED & CLEAN)     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Executive Analytics &amp; Reports</h1>
            <p class="text-xs text-cyber-muted mt-1">High-level enterprise reports, operational throughput, and cross-branch performance.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2 cursor-pointer shadow-sm">
                <i class="fa-solid fa-print text-brand-600 dark:text-blue-400 text-xs"></i> Print Summary
            </button>
            <a href="{{ route('management.dashboard') }}" class="px-4 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-chart-line text-xs text-indigo-600 dark:text-indigo-400"></i> Executive Dashboard
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 UNIFIED KPI METRIC CARDS (RIGHT-ALIGNED NUMBERS) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="LIFETIME ORDERS"
            :value="$totalOrdersCount"
            icon="fa-solid fa-layer-group"
            accent="cyan"
            :link="route('management.reports.orders')"
        />

        <x-dashboard.kpi-card 
            title="TOTAL QUOTED REVENUE"
            :value="'₱' . number_format($totalRevenue, 2)"
            icon="fa-solid fa-coins"
            accent="emerald"
            :link="route('management.reports.orders')"
        />

        <x-dashboard.kpi-card 
            title="FULFILLED ORDERS"
            :value="$completedOrdersCount"
            icon="fa-solid fa-circle-check"
            accent="indigo"
            :link="route('management.reports.orders', ['status' => 'completed'])"
        />

        <x-dashboard.kpi-card 
            title="ACTIVE BRANCHES"
            :value="$activeBranchesCount"
            icon="fa-solid fa-shop"
            accent="amber"
            :link="route('management.reports.capacity')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- EXECUTIVE REPORTS VISUAL ANALYTICS (SPLIT GRID) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: 6-MONTH REVENUE & ORDER TRENDS BAR GRAPH --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber-sub pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-9 w-9 rounded-xl bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">6-Month Revenue &amp; Order Trends</h3>
                        </div>
                        <p class="text-xs text-cyber-muted mt-1">Multi-branch financial growth trajectory and completed order volume</p>
                    </div>

                    <div class="flex items-center gap-3.5 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-full bg-brand-600 dark:bg-blue-500"></span>
                            <span class="text-cyber-main text-xs font-semibold">Revenue (₱K)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-full bg-indigo-500"></span>
                            <span class="text-cyber-main text-xs font-semibold">Orders Count</span>
                        </div>
                    </div>
                </div>

                {{-- Bar Chart Canvas --}}
                <div class="relative h-64 sm:h-72 w-full pt-3">
                    <canvas id="executiveRevenueTrendsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT 1 COL: BRANCH REVENUE CONTRIBUTION DONUT --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber-sub pb-3">
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Branch Revenue Share</h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Commercial sales contribution</p>
                    </div>
                    <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>

                @php
                    $totalShare = max(1, array_sum($branchRevenueShare));
                    $shareLabels = array_keys($branchRevenueShare);
                    $shareValues = array_values($branchRevenueShare);
                    $shareColors = ['#2563EB', '#4F46E5', '#059669'];
                @endphp

                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="branchRevenueDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-black font-display text-cyber-main tabular-nums leading-tight">₱{{ number_format($totalShare / 1000, 0) }}k</span>
                        <span class="text-[11px] font-semibold text-cyber-muted mt-0.5">Total Sales</span>
                    </div>
                </div>

                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($branchRevenueShare as $bTitle => $bVal)
                        @php
                            $bPct = round(($bVal / $totalShare) * 100);
                            $bDot = $shareColors[$loop->index % count($shareColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1.5 px-2.5 rounded-xl hover:bg-cyber-sub transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0" style="background-color: {{ $bDot }}"></span>
                                <span class="text-cyber-main font-medium truncate text-xs">{{ $bTitle }}</span>
                            </div>
                            <span class="font-sans tabular-nums text-xs text-cyber-muted shrink-0 ml-2 font-bold">
                                ₱{{ number_format($bVal, 0) }} <span class="text-[11px] font-normal text-cyber-sub">({{ $bPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 PRIMARY EXECUTIVE REPORT SUITES (2x2 GRID) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- 1. Orders & Financial Report --}}
        <a href="{{ route('management.reports.orders') }}" 
           class="bg-cyber-card p-6 rounded-2xl shadow-sm border border-cyber hover:border-emerald-600/50 transition-colors block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-sans font-semibold text-emerald-700 dark:text-emerald-400">
                    <span>Open Audit Report</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </span>
            </div>
            <div>
                <h3 class="font-bold text-cyber-main text-lg font-display group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">
                    Orders &amp; Financial Audit Report
                </h3>
                <p class="text-xs text-cyber-muted mt-1.5 leading-relaxed">
                    Complete transaction logs, customer payment verifications, revenue breakdowns, and custom date range filters.
                </p>
            </div>
        </a>

        {{-- 2. Production Output & Efficiency --}}
        <a href="{{ route('management.reports.production') }}" 
           class="bg-cyber-card p-6 rounded-2xl shadow-sm border border-cyber hover:border-amber-600/50 transition-colors block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-11 w-11 rounded-xl bg-amber-500/10 border border-amber-500/25 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-sans font-semibold text-amber-700 dark:text-amber-400">
                    <span>Open Efficiency Logs</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </span>
            </div>
            <div>
                <h3 class="font-bold text-cyber-main text-lg font-display group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">
                    Production Output &amp; Machine Efficiency
                </h3>
                <p class="text-xs text-cyber-muted mt-1.5 leading-relaxed">
                    Shop-floor throughput telemetry, press runtimes, delay exceptions, and technician workload allocations.
                </p>
            </div>
        </a>

        {{-- 3. Inventory & Material Valuation --}}
        <a href="{{ route('management.reports.inventory') }}" 
           class="bg-cyber-card p-6 rounded-2xl shadow-sm border border-cyber hover:border-brand-600/50 dark:hover:border-blue-500/50 transition-colors block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-11 w-11 rounded-xl bg-brand-600/10 border border-brand-600/25 text-brand-600 dark:text-blue-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-sans font-semibold text-brand-600 dark:text-blue-400">
                    <span>Open Stock Valuation</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </span>
            </div>
            <div>
                <h3 class="font-bold text-cyber-main text-lg font-display group-hover:text-brand-600 dark:group-hover:text-blue-400 transition-colors">
                    Inventory Valuation &amp; Material Stock
                </h3>
                <p class="text-xs text-cyber-muted mt-1.5 leading-relaxed">
                    Branch stock valuation, low-stock reorder warnings, unit consumption rates, and replenishment triggers.
                </p>
            </div>
        </a>

        {{-- 4. Branch Capacity Analysis --}}
        <a href="{{ route('management.reports.capacity') }}" 
           class="bg-cyber-card p-6 rounded-2xl shadow-sm border border-cyber hover:border-indigo-500/50 transition-colors block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-11 w-11 rounded-xl bg-indigo-500/10 border border-indigo-500/25 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-sans font-semibold text-indigo-600 dark:text-indigo-400">
                    <span>Open Capacity Matrix</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </span>
            </div>
            <div>
                <h3 class="font-bold text-cyber-main text-lg font-display group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    Multi-Branch Capacity &amp; Load Matrix
                </h3>
                <p class="text-xs text-cyber-muted mt-1.5 leading-relaxed">
                    Equipment uptime benchmarks, operational bottlenecks, daily capacity thresholds, and network saturation.
                </p>
            </div>
        </a>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');

        // 1. Executive Revenue & Order Trends Bar Chart (Straight Columns)
        const trendsCanvas = document.getElementById('executiveRevenueTrendsChart');
        if (trendsCanvas) {
            const months = @json($trendMonths);
            const revenues = @json($revenueTrends);
            const orders = @json($orderTrends);

            new Chart(trendsCanvas, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Revenue (₱K)',
                            data: revenues,
                            backgroundColor: isDark ? 'rgba(6,182,212,0.82)' : 'rgba(6,182,212,0.88)',
                            hoverBackgroundColor: '#2563EB',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 5,
                            borderSkipped: 'bottom',
                            maxBarThickness: 36,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Order Volume',
                            data: orders,
                            backgroundColor: isDark ? 'rgba(99,102,241,0.78)' : 'rgba(99,102,241,0.84)',
                            hoverBackgroundColor: '#6366f1',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 5,
                            borderSkipped: 'bottom',
                            maxBarThickness: 36,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62,
                            yAxisID: 'y1'
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
                                    if (context.datasetIndex === 0) {
                                        return ` Revenue: ₱${(val * 1000).toLocaleString()}`;
                                    }
                                    return ` Orders: ${val} fulfilled`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: {
                                color: isDark ? 'rgba(51,65,85,0.4)' : 'rgba(203,213,225,0.7)',
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 11, weight: '600' },
                                padding: 6
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255,255,255,0.04)' : 'rgba(148,163,184,0.18)',
                                lineWidth: 1,
                            },
                            border: { dash: [4, 4], color: 'transparent' },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 },
                                padding: 8,
                                callback: function(val) { return '₱' + val + 'k'; }
                            },
                            title: {
                                display: true,
                                text: 'Revenue (₱)',
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 11, weight: 'bold' },
                                padding: { bottom: 6 }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                            border: { color: 'transparent' },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 11 },
                                padding: 8
                            },
                            title: {
                                display: true,
                                text: 'Orders',
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 11, weight: 'bold' },
                                padding: { bottom: 6 }
                            }
                        }
                    },
                    animation: { duration: 900, easing: 'easeOutQuart' }
                }
            });
        }

        // 2. Branch Revenue Contribution Donut Chart
        const shareCanvas = document.getElementById('branchRevenueDonutChart');
        if (shareCanvas) {
            const sLabels = @json($shareLabels);
            const sData = @json($shareValues);
            const sColors = ['#2563EB', '#4F46E5', '#059669'];

            new Chart(shareCanvas, {
                type: 'doughnut',
                data: {
                    labels: sLabels,
                    datasets: [{
                        data: sData,
                        backgroundColor: sColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
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
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                    return ` ${context.label}: ₱${val.toLocaleString()} (${pct}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 800
                    }
                }
            });
        }
    });
</script>
@endsection
