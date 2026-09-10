@extends('layouts.internal')
@section('title', 'Executive Management Dashboard')
@section('page-title', 'Executive Management Dashboard')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- EXECUTIVE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Executive Management Dashboard</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('management.reports.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-xs"></i> Executive Reports
                </a>
                <a href="{{ route('management.purchasing.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-invoice-dollar text-xs text-amber-400"></i> Procurement Queue
                </a>
                <a href="{{ route('management.branches.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-network-wired text-xs text-cyan-400"></i> Branch Network
                </a>
            </div>
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
        <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Print Services Mix</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Order volume distribution across categories</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs shadow-xs shrink-0">
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
                        '#06b6d4', // Cyan
                        '#6366f1', // Indigo
                        '#10b981', // Emerald
                        '#f59e0b', // Amber
                        '#ec4899', // Pink
                        '#8b5cf6', // Purple
                        '#3b82f6', // Blue
                    ];
                @endphp

                {{-- Chart Canvas Container with Centered Metric --}}
                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="servicesPieChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black font-display text-cyber-main leading-tight">{{ $totalSvc }}</span>
                        <span class="text-[9px] font-black uppercase tracking-wider text-cyber-muted">Orders</span>
                    </div>
                </div>

                {{-- Clean Grid Legend --}}
                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($serviceList as $sName => $sCount)
                        @php
                            $sPct = round(($sCount / $totalSvc) * 100);
                            $colorHex = $colorPalette[$loop->index % count($colorPalette)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg hover:bg-cyber-sub/50 transition">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $colorHex }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $sName }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-cyber-muted shrink-0 ml-2 font-bold">
                                {{ $sCount }} <span class="text-[10px] font-normal text-cyber-sub">({{ $sPct }}%)</span>
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
            '#06b6d4', // Cyan
            '#6366f1', // Indigo
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#ec4899', // Pink
            '#8b5cf6', // Purple
            '#3b82f6'  // Blue
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
