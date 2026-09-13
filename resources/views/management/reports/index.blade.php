@extends('layouts.internal')
@section('title', 'Executive Reports & Analytics')
@section('page-title', 'Executive Reports & Analytics Hub')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: EXECUTIVE REPORTS HUB --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Executive Analytics &amp; Reports</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-print text-cyan-400 text-xs"></i> Print Summary
                </button>
                <a href="{{ route('management.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-chart-line text-xs text-indigo-400"></i> Executive Dashboard
                </a>
            </div>
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
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber/80 pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-xl bg-teal-500/10 border border-teal-500/20 text-[#009498] flex items-center justify-center text-xs shadow-xs">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">6-Month Revenue &amp; Order Trends</h3>
                        </div>
                        <p class="text-[11px] sm:text-xs text-cyber-muted mt-1">Multi-branch financial growth trajectory and completed order volume</p>
                    </div>

                    <div class="flex items-center gap-3.5 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-3 w-3 shadow-xs" style="background-color: #009498;"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Revenue (₱K)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-3 w-3 shadow-xs" style="background-color: #013F73;"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Orders Count</span>
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
        <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Branch Revenue Share</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Commercial sales contribution</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs shadow-xs shrink-0">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>

                @php
                    $totalShare = max(1, array_sum($branchRevenueShare));
                    $shareLabels = array_keys($branchRevenueShare);
                    $shareValues = array_values($branchRevenueShare);
                    $shareColors = ['#009498', '#013F73', '#7DD956'];
                @endphp

                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="branchRevenueDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-black font-display text-cyber-main leading-tight">₱{{ number_format($totalShare / 1000, 0) }}k</span>
                        <span class="text-[9px] font-black uppercase tracking-wider text-cyber-muted">Total Sales</span>
                    </div>
                </div>

                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($branchRevenueShare as $bTitle => $bVal)
                        @php
                            $bPct = round(($bVal / $totalShare) * 100);
                            $bDot = $shareColors[$loop->index % count($shareColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-cyber-sub/50 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $bDot }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $bTitle }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-cyber-muted shrink-0 ml-2 font-bold">
                                ₱{{ number_format($bVal, 0) }} <span class="text-[10px] font-normal text-cyber-sub">({{ $bPct }}%)</span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 1. Orders & Financial Report --}}
        <a href="{{ route('management.reports.orders') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-emerald-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-500 transition-colors">
                    <span>Open Audit Report</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">
                    Orders &amp; Financial Audit Report
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Complete transaction logs, customer payment verifications, revenue breakdowns, and custom date range filters.
                </p>
            </div>
        </a>

        {{-- 2. Production Output & Efficiency --}}
        <a href="{{ route('management.reports.production') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-amber-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-500 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 group-hover:text-amber-500 transition-colors">
                    <span>Open Efficiency Logs</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors">
                    Production Output &amp; Machine Efficiency
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Shop-floor throughput telemetry, press runtimes, delay exceptions, and technician workload allocations.
                </p>
            </div>
        </a>

        {{-- 3. Inventory & Material Valuation --}}
        <a href="{{ route('management.reports.inventory') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-cyan-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 dark:text-cyan-400 group-hover:text-cyan-500 transition-colors">
                    <span>Open Stock Valuation</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-cyan-500 dark:group-hover:text-cyan-400 transition-colors">
                    Inventory Valuation &amp; Material Stock
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
                    Branch stock valuation, low-stock reorder warnings, unit consumption rates, and replenishment triggers.
                </p>
            </div>
        </a>

        {{-- 4. Branch Capacity Analysis --}}
        <a href="{{ route('management.reports.capacity') }}" 
           class="bg-cyber-card p-6 sm:p-7 rounded-3xl border border-cyber shadow-xl hover:border-indigo-500/40 hover:shadow-2xl transition-all duration-200 block space-y-4 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 group-hover:text-indigo-500 transition-colors">
                    <span>Open Capacity Matrix</span>
                    <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
            <div>
                <h3 class="font-black text-cyber-main text-lg font-display group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                    Multi-Branch Capacity &amp; Load Matrix
                </h3>
                <p class="text-xs text-cyber-muted mt-2 leading-relaxed">
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
                            backgroundColor: '#009498',
                            hoverBackgroundColor: '#00b4b8',
                            borderColor: isDark ? '#00c4c8' : '#007a7e',
                            borderWidth: 1.5,
                            borderRadius: 0,          // 100% STRAIGHT (flat top, no rounded curves)
                            borderSkipped: false,
                            maxBarThickness: 36,
                            barPercentage: 0.75,
                            categoryPercentage: 0.65,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Order Volume',
                            data: orders,
                            backgroundColor: '#013F73',
                            hoverBackgroundColor: '#02569c',
                            borderColor: isDark ? '#1e6bb8' : '#012c52',
                            borderWidth: 1.5,
                            borderRadius: 0,          // 100% STRAIGHT (flat top, no rounded curves)
                            borderSkipped: false,
                            maxBarThickness: 36,
                            barPercentage: 0.75,
                            categoryPercentage: 0.65,
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
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 10
                                },
                                callback: function(val) {
                                    return '₱' + val + 'k';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Revenue (₱)',
                                color: isDark ? '#64748b' : '#94a3b8',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 10
                                }
                            },
                            title: {
                                display: true,
                                text: 'Orders',
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

        // 2. Branch Revenue Contribution Donut Chart
        const shareCanvas = document.getElementById('branchRevenueDonutChart');
        if (shareCanvas) {
            const sLabels = @json($shareLabels);
            const sData = @json($shareValues);
            const sColors = ['#009498', '#013F73', '#7DD956'];

            new Chart(shareCanvas, {
                type: 'doughnut',
                data: {
                    labels: sLabels,
                    datasets: [{
                        data: sData,
                        backgroundColor: sColors,
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
