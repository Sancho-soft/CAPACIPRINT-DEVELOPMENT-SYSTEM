
@extends('layouts.internal')
@section('title', 'Dashboard Overview - Inventory')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: INVENTORY & MATERIALS CENTER (UNBOXED & CLEAN) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 block mb-1">Material &amp; Stock Control</span>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
            <p class="text-xs text-cyber-muted mt-1">Real-time inventory telemetry, buffer limits, and raw material health across facilities.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('inventory.stock-movements.create') }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Record Movement
            </a>
            <a href="{{ route('inventory.materials.create') }}" class="px-3.5 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-box text-xs text-blue-600 dark:text-blue-400"></i> New Material
            </a>
            <a href="{{ route('inventory.reports.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-file-waveform text-xs text-amber-600 dark:text-amber-400"></i> Stock Reports
            </a>
        </div>
    </div>



    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY INVENTORY METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="ACTIVE MEDIA CATALOG"
            :value="$totalMaterials"
            icon="fa-solid fa-box-open"
            accent="cyan"
            trend="Active media"
            trendType="neutral"
            subtitle="Paper, toner & vinyl types"
            :link="route('inventory.materials.index')"
        />

        <x-dashboard.kpi-card 
            title="HEALTHY INVENTORY NODES"
            :value="$availableCount"
            icon="fa-solid fa-circle-check"
            accent="emerald"
            trend="Optimal levels"
            trendType="up"
            subtitle="Above safety thresholds"
            :link="route('inventory.stock.index')"
        />

        <x-dashboard.kpi-card 
            title="BELOW REORDER LEVEL"
            :value="$lowStockCount"
            icon="fa-solid fa-triangle-exclamation"
            accent="amber"
            trend="{{ $lowStockCount > 0 ? 'Restock recommended' : 'Supplies adequate' }}"
            :trendType="$lowStockCount > 0 ? 'warning' : 'up'"
            subtitle="Warning thresholds met"
        />

        <x-dashboard.kpi-card 
            title="CRITICAL DEPLETIONS"
            :value="$outOfStockCount"
            icon="fa-solid fa-circle-xmark"
            accent="rose"
            trend="{{ $outOfStockCount > 0 ? 'Immediate action' : 'Zero depleted' }}"
            :trendType="$outOfStockCount > 0 ? 'danger' : 'up'"
            subtitle="Halt risk on press"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: INVENTORY CONSUMPTION PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Raw Materials & Media Consumption Pipeline"
        subtitle="Tracking inventory health from stock-in and safety reserves to press-floor auto-deduction"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: INVENTORY VISUAL ANALYTICS (SPLIT GRID) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        {{-- LEFT 2 COLS: STOCK FLOW COMPARISON (STOCK-IN VS STOCK-OUT) --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-2xl p-5 sm:p-6 flex flex-col justify-between h-full shadow-sm">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber-sub pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Multi-Branch Stock Inflow vs. Floor Consumption</h3>
                        </div>
                        <p class="text-xs text-cyber-muted mt-1">Incoming raw material restocking compared against press floor deductions</p>
                    </div>

                    <div class="flex items-center gap-3.5 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-sm bg-emerald-600"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Stock In</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2.5 w-4 rounded-sm bg-rose-600"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Stock Out</span>
                        </div>
                    </div>
                </div>

                {{-- Bar Chart Canvas --}}
                <div class="relative h-64 sm:h-72 w-full pt-3">
                    <canvas id="stockFlowBarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- RIGHT 1 COL: MATERIAL STOCK HEALTH DISTRIBUTION DONUT --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl p-5 sm:p-6 flex flex-col justify-between h-full shadow-sm">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber-sub pb-3">
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Stock Buffer Health</h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Threshold health distribution</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>

                @php
                    $totalHealthCount = max(1, array_sum($stockHealthBreakdown));
                    $healthLabels = array_keys($stockHealthBreakdown);
                    $healthValues = array_values($stockHealthBreakdown);
                    $healthColors = ['#059669', '#D97706', '#DC2626'];
                @endphp

                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="stockHealthDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold font-display text-cyber-main leading-tight tabular-nums">{{ $totalHealthCount }}</span>
                        <span class="text-[11px] font-sans font-semibold text-cyber-sub">Stock Items</span>
                    </div>
                </div>

                <div class="space-y-1 pt-2 border-t border-cyber-sub flex-1 overflow-y-auto pr-1 tabular-nums">
                    @foreach($stockHealthBreakdown as $hLabel => $hVal)
                        @php
                            $hPct = round(($hVal / $totalHealthCount) * 100);
                            $hDot = $healthColors[$loop->index % count($healthColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1.5 px-2 rounded-lg hover:bg-black/[0.025] dark:hover:bg-white/[0.03] transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-sm shrink-0" style="background-color: {{ $hDot }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $hLabel }}</span>
                            </div>
                            <span class="font-sans text-[11px] text-cyber-muted shrink-0 ml-2 font-semibold tabular-nums">
                                {{ $hVal }} <span class="text-[11px] font-normal text-cyber-sub">({{ $hPct }}%)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: CRITICAL STOCK & RECENT MOVEMENTS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: CRITICAL LOW STOCK ITEMS --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden flex flex-col shadow-sm">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber-sub flex items-center justify-between bg-cyber-sub">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Critical Stock Warnings</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Supplies falling below safety buffer limits</p>
                    </div>
                </div>
                <a href="{{ route('inventory.stock.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1.5 transition">
                    Manage Stock <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="text-cyber-muted font-sans font-bold border-b border-cyber text-xs bg-cyber-sub">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Material</th>
                            <th class="px-4 sm:px-5 py-3">Branch</th>
                            <th class="px-4 sm:px-5 py-3">Balance</th>
                            <th class="px-4 sm:px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main">
                        @forelse($lowStockItems as $lItem)
                            <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-semibold text-cyber-main block text-xs leading-tight">{{ $lItem->material->name ?? 'Material' }}</span>
                                    <span class="text-[11px] text-cyber-muted inline-flex items-center gap-1.5 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                                        {{ ucfirst($lItem->material->type ?? 'media') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-xs whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 font-medium text-cyber-main">
                                        <i class="fa-solid fa-building text-[11px] text-cyber-muted shrink-0"></i>
                                        <span>{{ $lItem->branch->name ?? 'Branch' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-baseline gap-1 text-xs font-sans tabular-nums">
                                        <span class="font-bold {{ ((float)$lItem->quantity == 0 || $lItem->status === 'out_of_stock') ? 'text-rose-600 dark:text-rose-400' : 'text-amber-700 dark:text-amber-400' }}">
                                            {{ (float)$lItem->quantity }}
                                        </span>
                                        <span class="text-[11px] text-cyber-muted">
                                            / min {{ (float)$lItem->minimum_stock }} {{ $lItem->material->unit }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($lItem->status === 'out_of_stock' || (float)$lItem->quantity == 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/25">
                                            <i class="fa-solid fa-circle-xmark text-[11px]"></i> Out of Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-500/25">
                                            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i> Low Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg mb-1 block"></i>
                                    All raw material stocks are within safe operational buffers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: RECENT STOCK MOVEMENTS --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden flex flex-col shadow-sm">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber-sub flex items-center justify-between bg-cyber-sub">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-600/10 dark:bg-blue-500/15 border border-blue-600/20 dark:border-blue-500/25 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Stock Movements</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Automated job deductions and replenishment entries</p>
                    </div>
                </div>
                <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1.5 transition">
                    All Movements <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="text-cyber-muted font-sans font-bold border-b border-cyber text-xs bg-cyber-sub">
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
                                    <span class="text-[11px] text-cyber-muted block mt-0.5">{{ $mv->branch->name ?? 'Branch Hub' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($mv->movement_type === 'stock_in')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/25">
                                            <i class="fa-solid fa-arrow-down text-[11px]"></i> Stock In
                                        </span>
                                    @elseif($mv->movement_type === 'stock_out')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/25">
                                            <i class="fa-solid fa-arrow-up text-[11px]"></i> Stock Out
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold bg-blue-600/10 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-600/25 dark:border-blue-500/30">
                                            <i class="fa-solid fa-sliders text-[11px]"></i> {{ ucwords(str_replace('_', ' ', $mv->movement_type)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @php
                                        $qtyDisplay = (float)$mv->quantity;
                                    @endphp
                                    <div class="flex items-baseline gap-1 text-xs font-sans tabular-nums">
                                        @if($mv->movement_type === 'stock_out')
                                            <span class="font-bold text-rose-600 dark:text-rose-400">
                                                -{{ $qtyDisplay }}
                                            </span>
                                        @else
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                                +{{ $qtyDisplay }}
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-cyber-muted">{{ $mv->material->unit ?? 'units' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-cyber-sub border border-cyber text-cyber-main font-sans tabular-nums text-[11px] font-medium">
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

        // 1. Stock Inflow vs. Floor Consumption Bar Chart (Straight Columns)
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
                            backgroundColor: isDark
                                ? 'rgba(16, 185, 129, 0.82)'
                                : 'rgba(16, 185, 129, 0.88)',
                            hoverBackgroundColor: '#10b981',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 5,
                            borderSkipped: 'bottom',
                            maxBarThickness: 40,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62
                        },
                        {
                            label: 'Stock Out',
                            data: stockOutData,
                            backgroundColor: isDark
                                ? 'rgba(244, 63, 94, 0.78)'
                                : 'rgba(244, 63, 94, 0.84)',
                            hoverBackgroundColor: '#f43f5e',
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 5,
                            borderSkipped: 'bottom',
                            maxBarThickness: 40,
                            barPercentage: 0.72,
                            categoryPercentage: 0.62
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
                                    return ` ${context.dataset.label}: ${val} units`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            border: {
                                color: isDark ? 'rgba(51,65,85,0.4)' : 'rgba(203,213,225,0.7)',
                            },
                            ticks: {
                                color: isDark ? '#9BA3B0' : '#475569',
                                font: {
                                    family: 'Plus Jakarta Sans, sans-serif',
                                    size: 11,
                                    weight: '600'
                                },
                                padding: 6
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(17,19,21,0.08)',
                                lineWidth: 1,
                            },
                            border: {
                                dash: [4, 4],
                                color: 'transparent',
                            },
                            ticks: {
                                color: isDark ? '#9BA3B0' : '#475569',
                                font: {
                                    family: 'Plus Jakarta Sans, sans-serif',
                                    size: 11
                                },
                                padding: 8
                            },
                            title: {
                                display: true,
                                text: 'Quantity Volume',
                                color: isDark ? '#6B7280' : '#6E7681',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },
                                padding: { bottom: 6 }
                            }
                        }
                    },
                    animation: {
                        duration: 600,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // 2. Stock Health Distribution Donut Chart
        const healthCanvas = document.getElementById('stockHealthDonutChart');
        if (healthCanvas) {
            const hLabels = @json($healthLabels);
            const hData = @json($healthValues);
            const hColors = ['#059669', '#D97706', '#DC2626'];

            new Chart(healthCanvas, {
                type: 'doughnut',
                data: {
                    labels: hLabels,
                    datasets: [{
                        data: hData,
                        backgroundColor: hColors,
                        borderWidth: 2,
                        borderColor: isDark ? '#151922' : '#FFFFFF',
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
                                    return ` ${context.label}: ${val} items (${pct}%)`;
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
