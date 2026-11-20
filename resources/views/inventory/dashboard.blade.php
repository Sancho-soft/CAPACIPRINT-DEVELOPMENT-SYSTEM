@extends('layouts.internal')
@section('title', 'Paper & Roll Inventory')
@section('page-title', 'Paper & Roll Inventory')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Paper &amp; Roll Inventory</h1>
            <p class="text-sm text-cyber-muted mt-1">Roll media stock levels, safety thresholds, and press-floor material consumption.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Record Movement</span>
            </a>
            <a href="{{ route('inventory.materials.create') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-box text-xs text-[#2F7BFF]"></i>
                <span>New Material</span>
            </a>
            <a href="{{ route('inventory.reports.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-file-waveform text-xs text-[#FFC531]"></i>
                <span>Stock Reports</span>
            </a>
        </div>
    </div>

    {{-- 4 KEY INVENTORY METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="Active Media Catalog"
            :value="$totalMaterials"
            accent="blue"
            trend="Media SKU"
            trendType="neutral"
            subtitle="Paper, ink, and vinyl stock"
            :link="route('inventory.materials.index')"
        />

        <x-dashboard.kpi-card 
            title="Healthy Stock Nodes"
            :value="$availableCount"
            accent="emerald"
            trend="Optimal"
            trendType="up"
            subtitle="Above minimum buffer"
            :link="route('inventory.stock.index')"
        />

        <x-dashboard.kpi-card 
            title="Below Reorder Level"
            :value="$lowStockCount"
            accent="amber"
            trend="{{ $lowStockCount > 0 ? 'Reorder soon' : 'Adequate' }}"
            :trendType="$lowStockCount > 0 ? 'warning' : 'up'"
            subtitle="Warning thresholds reached"
        />

        <x-dashboard.kpi-card 
            title="Out of Stock"
            :value="$outOfStockCount"
            accent="amber"
            trend="{{ $outOfStockCount > 0 ? 'Urgent' : 'Zero depleted' }}"
            :trendType="$outOfStockCount > 0 ? 'danger' : 'up'"
            subtitle="Press stop risk"
        />
    </div>

    {{-- INVENTORY CONSUMPTION PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Raw Materials &amp; Media Consumption Pipeline"
        subtitle="Stock-in receipts, safety buffers, and press-floor auto-deduction"
    />

    {{-- INVENTORY ANALYTICS SPLIT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: STOCK FLOW COMPARISON --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber pb-4">
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Multi-Branch Stock Inflow vs. Floor Consumption</h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Incoming raw material restocking compared against press floor deductions</p>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-sm bg-[#10B981]"></span>
                            <span class="text-cyber-main font-semibold">Stock In</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-sm bg-[#FFC531]"></span>
                            <span class="text-cyber-main font-semibold">Stock Out</span>
                        </div>
                    </div>
                </div>

                <div class="relative h-64 sm:h-72 w-full pt-4">
                    <canvas id="stockFlowBarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT 1 COL: MATERIAL STOCK HEALTH DONUT --}}
        <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="border-b border-cyber pb-3">
                    <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Stock Buffer Health</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Threshold health distribution</p>
                </div>

                @php
                    $totalHealthCount = max(1, array_sum($stockHealthBreakdown));
                    $healthLabels = array_keys($stockHealthBreakdown);
                    $healthValues = array_values($stockHealthBreakdown);
                    $healthColors = ['#10B981', '#2F7BFF', '#FFC531'];
                @endphp

                <div class="relative flex items-center justify-center my-3 h-44 w-full">
                    <canvas id="stockHealthDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold font-mono text-cyber-main leading-tight tabular-nums">{{ $totalHealthCount }}</span>
                        <span class="text-xs font-sans font-medium text-cyber-muted">Stock Items</span>
                    </div>
                </div>

                <div class="divide-y divide-cyber-sub pt-2 border-t border-cyber flex-1 overflow-y-auto tabular-nums">
                    @foreach($stockHealthBreakdown as $hLabel => $hVal)
                        @php
                            $hPct = round(($hVal / $totalHealthCount) * 100);
                            $hDot = $healthColors[$loop->index % count($healthColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-2 px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-sm shrink-0" style="background-color: {{ $hDot }}"></span>
                                <span class="text-cyber-main font-medium truncate">{{ $hLabel }}</span>
                            </div>
                            <span class="font-mono text-xs text-cyber-muted shrink-0 ml-2 font-semibold tabular-nums">
                                {{ $hVal }} <span class="font-normal text-cyber-sub">({{ $hPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- CRITICAL STOCK & RECENT MOVEMENTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: CRITICAL LOW STOCK ITEMS --}}
        <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
                <div>
                    <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Critical Stock Warnings</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Supplies falling below safety buffer limits</p>
                </div>
                <a href="{{ route('inventory.stock.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5 shrink-0">
                    <span>Manage Stock</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="text-cyber-muted font-sans font-semibold border-b border-cyber text-xs bg-cyber-sub">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Material</th>
                            <th class="px-4 sm:px-5 py-3">Branch</th>
                            <th class="px-4 sm:px-5 py-3">Balance</th>
                            <th class="px-4 sm:px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main">
                        @forelse($lowStockItems as $lItem)
                            @php
                                $isOut = ($lItem->status === 'out_of_stock' || (float)$lItem->quantity == 0);
                            @endphp
                            <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-semibold text-cyber-main block text-xs leading-tight">{{ $lItem->material->name ?? 'Material' }}</span>
                                    <span class="text-xs text-cyber-muted block mt-0.5">{{ ucfirst($lItem->material->type ?? 'media') }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-xs whitespace-nowrap font-medium text-cyber-main">
                                    {{ $lItem->branch->name ?? 'Branch' }}
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap font-mono">
                                    <span class="font-bold text-amber-700 dark:text-[#FFC531]">{{ (float)$lItem->quantity }}</span>
                                    <span class="text-xs text-cyber-muted">/ {{ (float)$lItem->minimum_stock }} {{ $lItem->material->unit }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-[#FFC531]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#FFC531]"></span>
                                        {{ $isOut ? 'Out of Stock' : 'Low Stock' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">
                                    <i class="fa-solid fa-circle-check text-[#10B981] text-lg mb-1 block"></i>
                                    All raw material stocks are within safe operational buffers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: RECENT STOCK MOVEMENTS --}}
        <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
                <div>
                    <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Stock Movements</h3>
                    <p class="text-xs text-cyber-muted mt-0.5">Automated job deductions and replenishment entries</p>
                </div>
                <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5 shrink-0">
                    <span>All Movements</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="text-cyber-muted font-sans font-semibold border-b border-cyber text-xs bg-cyber-sub">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Material</th>
                            <th class="px-4 sm:px-5 py-3">Type</th>
                            <th class="px-4 sm:px-5 py-3">Quantity</th>
                            <th class="px-4 sm:px-5 py-3">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main">
                        @forelse($recentMovements as $mv)
                            <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-semibold text-cyber-main block text-xs leading-tight">{{ $mv->material->name ?? 'Material' }}</span>
                                    <span class="text-xs text-cyber-muted block mt-0.5">{{ $mv->branch->name ?? 'Branch Hub' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($mv->movement_type === 'stock_in')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-[#10B981]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                            Stock In
                                        </span>
                                    @elseif($mv->movement_type === 'stock_out')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-[#FFC531]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#FFC531]"></span>
                                            Stock Out
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2F7BFF]"></span>
                                            {{ ucwords(str_replace('_', ' ', $mv->movement_type)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap font-mono">
                                    @php $qtyDisplay = (float)$mv->quantity; @endphp
                                    @if($mv->movement_type === 'stock_out')
                                        <span class="font-bold text-amber-700 dark:text-[#FFC531]">-{{ $qtyDisplay }}</span>
                                    @else
                                        <span class="font-bold text-emerald-700 dark:text-[#10B981]">+{{ $qtyDisplay }}</span>
                                    @endif
                                    <span class="text-xs text-cyber-muted ml-0.5">{{ $mv->material->unit ?? 'units' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs text-cyber-main font-medium">
                                        {{ $mv->reference ?? ($mv->reason ?: 'Auto Deduct') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">
                                    <i class="fa-solid fa-inbox text-cyber-sub text-lg mb-1 block"></i>
                                    No recent inventory movements recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('dark-theme');

        const flowCanvas = document.getElementById('stockFlowBarChart');
        if (flowCanvas) {
            const flowLabels = @json($flowBranchLabels);
            const stockInData = @json($stockInData);
            const stockOutData = @json($stockOutData);

            new Chart(flowCanvas, {
                type: 'bar',
                data: {
                    labels: flowLabels,
                    datasets: [
                        {
                            label: 'Stock In',
                            data: stockInData,
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            hoverBackgroundColor: '#10B981',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 4,
                            borderSkipped: 'bottom',
                            maxBarThickness: 38,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62
                        },
                        {
                            label: 'Stock Out',
                            data: stockOutData,
                            backgroundColor: 'rgba(255, 197, 49, 0.85)',
                            hoverBackgroundColor: '#FFC531',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 4,
                            borderSkipped: 'bottom',
                            maxBarThickness: 38,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                                    return ` ${context.dataset.label}: ${context.raw || 0} units`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { color: isDark ? '#1E293B' : '#CBD5E1' },
                            ticks: {
                                color: isDark ? '#94A3B8' : '#475569',
                                font: { family: 'Plus Jakarta Sans, sans-serif', size: 12, weight: '500' },
                                padding: 6
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(15,23,42,0.06)',
                                lineWidth: 1,
                            },
                            border: { dash: [4, 4], color: 'transparent' },
                            ticks: {
                                color: isDark ? '#94A3B8' : '#475569',
                                font: { family: 'IBM Plex Mono, monospace', size: 11 },
                                padding: 8
                            }
                        }
                    },
                    animation: { duration: 600, easing: 'easeOutQuart' }
                }
            });
        }

        const healthCanvas = document.getElementById('stockHealthDonutChart');
        if (healthCanvas) {
            const hLabels = @json($healthLabels);
            const hData = @json($healthValues);
            const hColors = ['#10B981', '#2F7BFF', '#FFC531'];

            new Chart(healthCanvas, {
                type: 'doughnut',
                data: {
                    labels: hLabels,
                    datasets: [{
                        data: hData,
                        backgroundColor: hColors,
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
                                    return ` ${context.label}: ${val} items (${pct}%)`;
                                }
                            }
                        }
                    },
                    animation: { animateScale: true, animateRotate: true, duration: 800 }
                }
            });
        }
    });
</script>
@endsection
