@extends('layouts.internal')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- EXECUTIVE HEADER (UNBOXED & CLEAN)                        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
            <p class="text-xs text-cyber-muted mt-1">Multi-branch enterprise performance telemetry and high-level operations control.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('management.reports.index') }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-xs"></i> Executive Reports
            </a>
            <a href="{{ route('management.purchasing.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-file-invoice-dollar text-xs text-amber-600 dark:text-amber-400"></i> Procurement Queue
            </a>
            <a href="{{ route('management.branches.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-xs text-blue-600 dark:text-blue-400"></i> Branch Network
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY EXECUTIVE METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="TOTAL PRINT ORDERS"
            :value="$totalOrders"
            icon="fa-solid fa-box-archive"
            accent="cyan"
            :link="route('management.orders.index')"
        />

        <x-dashboard.kpi-card 
            title="CONFIRMED REVENUE"
            :value="'₱' . number_format($totalRevenue, 2)"
            icon="fa-solid fa-coins"
            accent="emerald"
            :link="route('management.reports.index')"
        />

        <x-dashboard.kpi-card 
            title="ON PRESS FLOOR"
            :value="$inProduction"
            icon="fa-solid fa-industry"
            accent="indigo"
            :link="route('management.production.index')"
        />

        <x-dashboard.kpi-card 
            title="INVENTORY BOTTLENECKS"
            :value="$lowStockCount"
            icon="fa-solid fa-boxes-stacked"
            accent="amber"
            :link="route('management.inventory.index')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING LIFECYCLE PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Enterprise Commercial Printing Pipeline"
        subtitle=""
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 3: MULTI-BRANCH LOAD & SERVICES BREAKDOWN --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: MULTI-BRANCH WORKLOAD --}}
        <div class="lg:col-span-2 flex flex-col h-full">
            <x-dashboard.branch-workload-card 
                :branches="$branches"
                title="Branch Capacity & Equipment Utilization"
                subtitle=""
                :actionUrl="route('management.branches.index')"
                actionLabel="Branch Directory"
            />
        </div>

        {{-- RIGHT 1 COL: PRINT SERVICES MIX PIE / DONUT CHART --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl p-5 sm:p-6 flex flex-col justify-between h-full shadow-sm">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber-sub pb-3">
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Print Services Mix</h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Order volume distribution across categories</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-blue-600/10 dark:bg-blue-500/15 border border-blue-600/20 dark:border-blue-500/25 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>

                @php
                    $serviceList = !empty($serviceBreakdown) ? $serviceBreakdown : [
                        'Tarpaulin Printing' => 4,
                        'Flyers & Brochures' => 3,
                        'Document Printing'  => 2,
                        'Stickers & Labels'  => 2,
                    ];
                    $totalSvc = max(1, array_sum($serviceList));
                    $chartLabels = array_keys($serviceList);
                    $chartValues = array_values($serviceList);
                    $colorPalette = [
                        '#2563EB', // Primary Blue
                        '#6366F1', // Indigo
                        '#059669', // Process Emerald
                        '#D97706', // Press Amber
                        '#DC2626', // Crimson
                        '#0284C7', // Cerulean
                        '#4B5563', // Slate Ink
                    ];
                @endphp

                {{-- Chart Canvas Container with Centered Metric --}}
                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="servicesPieChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold font-display text-cyber-main leading-tight tabular-nums">{{ $totalSvc }}</span>
                        <span class="text-[11px] font-sans font-semibold text-cyber-sub">Orders</span>
                    </div>
                </div>

                {{-- Clean Grid Legend --}}
                <div class="space-y-1 pt-2 border-t border-cyber-sub flex-1 overflow-y-auto pr-1 tabular-nums">
                    @foreach($serviceList as $sName => $sCount)
                        @php
                            $sPct = round(($sCount / $totalSvc) * 100);
                            $colorHex = $colorPalette[$loop->index % count($colorPalette)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-black/[0.025] dark:hover:bg-white/[0.03] transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-sm shrink-0" style="background-color: {{ $colorHex }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $sName }}</span>
                            </div>
                            <span class="font-sans text-[11px] text-cyber-muted shrink-0 ml-2 font-semibold tabular-nums">
                                {{ $sCount }} <span class="text-[11px] font-normal text-cyber-sub">({{ $sPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: RECENT CUSTOMER ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Recent Customer Orders & Routing Status"
        subtitle=""
        :viewAllUrl="route('management.orders.index')"
        viewAllLabel="All Client Orders"
    />

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('servicesPieChart');
        if (!canvas) return;

        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');
        const labels = @json($chartLabels);
        const data = @json($chartValues);
        const colors = [
            '#2563EB', // Primary Blue
            '#6366F1', // Indigo
            '#059669', // Process Emerald
            '#D97706', // Press Amber
            '#DC2626', // Crimson
            '#0284C7', // Cerulean
            '#4B5563'  // Slate Ink
        ];

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: isDark ? '#151922' : '#FFFFFF',
                    hoverOffset: 4
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
                                return ` ${context.label}: ${val} orders (${pct}%)`;
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
