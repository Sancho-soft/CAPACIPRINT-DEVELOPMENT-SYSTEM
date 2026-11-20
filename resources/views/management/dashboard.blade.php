@extends('layouts.internal')
@section('title', 'Executive Analytics')
@section('page-title', 'Executive Analytics')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Executive Analytics</h1>
            <p class="text-sm text-cyber-muted mt-1">Confirmed order volume, print revenue, press utilization, and material bottlenecks across plants.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('management.reports.index') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-xs"></i>
                <span>Executive Reports</span>
            </a>
            <a href="{{ route('management.purchasing.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-file-invoice-dollar text-xs text-[#FFC531]"></i>
                <span>Procurement Queue</span>
            </a>
            <a href="{{ route('management.branches.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-xs text-[#2F7BFF]"></i>
                <span>Branch Network</span>
            </a>
        </div>
    </div>

    {{-- 4 KEY EXECUTIVE METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="Total Print Orders"
            :value="$totalOrders"
            accent="blue"
            :link="route('management.orders.index')"
        />

        <x-dashboard.kpi-card 
            title="Confirmed Revenue"
            :value="'₱' . number_format($totalRevenue, 2)"
            accent="emerald"
            :link="route('management.reports.index')"
        />

        <x-dashboard.kpi-card 
            title="On Press Floor"
            :value="$inProduction"
            accent="blue"
            :link="route('management.production.index')"
        />

        <x-dashboard.kpi-card 
            title="Inventory Bottlenecks"
            :value="$lowStockCount"
            accent="amber"
            :link="route('management.inventory.index')"
        />
    </div>

    {{-- COMMERCIAL PRINTING LIFECYCLE PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Enterprise Commercial Printing Pipeline"
        subtitle=""
    />

    {{-- MULTI-BRANCH LOAD & SERVICES BREAKDOWN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: MULTI-BRANCH WORKLOAD --}}
        <div class="lg:col-span-2 flex flex-col h-full">
            <x-dashboard.branch-workload-card 
                :branches="$branches"
                title="Branch Capacity &amp; Equipment Utilization"
                subtitle=""
                :actionUrl="route('management.branches.index')"
                actionLabel="Branch Directory"
            />
        </div>

        {{-- RIGHT 1 COL: PRINT SERVICES MIX DONUT --}}
        <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="border-b border-cyber pb-3">
                    <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Print Services Mix</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Order volume distribution across categories</p>
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
                        '#2F7BFF', // Process Blue
                        '#10B981', // Operational Green
                        '#FFC531', // CMYK Yellow
                        '#E5337A', // CMYK Magenta
                        '#60A5FA', // Cyan Light
                        '#94A3B8', // Muted Slate
                        '#475569', // Charcoal Slate
                    ];
                @endphp

                <div class="relative flex items-center justify-center my-3 h-44 w-full">
                    <canvas id="servicesPieChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold font-mono text-cyber-main leading-tight tabular-nums">{{ $totalSvc }}</span>
                        <span class="text-xs font-sans font-medium text-cyber-muted">Orders</span>
                    </div>
                </div>

                <div class="divide-y divide-cyber-sub pt-2 border-t border-cyber flex-1 overflow-y-auto tabular-nums">
                    @foreach($serviceList as $sName => $sCount)
                        @php
                            $sPct = round(($sCount / $totalSvc) * 100);
                            $colorHex = $colorPalette[$loop->index % count($colorPalette)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-2 px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-sm shrink-0" style="background-color: {{ $colorHex }}"></span>
                                <span class="text-cyber-main font-medium truncate">{{ $sName }}</span>
                            </div>
                            <span class="font-mono text-xs text-cyber-muted shrink-0 ml-2 font-semibold tabular-nums">
                                {{ $sCount }} <span class="font-normal text-cyber-sub">({{ $sPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- RECENT CUSTOMER ORDERS TABLE --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Recent Customer Orders &amp; Routing Status"
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
            '#2F7BFF',
            '#10B981',
            '#FFC531',
            '#E5337A',
            '#60A5FA',
            '#94A3B8',
            '#475569'
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
                cutout: '70%',
                plugins: {
                    legend: { display: false },
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
                                return ` ${context.label}: ${val} orders (${pct}%)`;
                            }
                        }
                    }
                },
                animation: { animateScale: true, animateRotate: true, duration: 900 }
            }
        });
    });
</script>
@endsection
