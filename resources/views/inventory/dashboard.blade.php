
@extends('layouts.internal')
@section('title', 'Dashboard Overview - Inventory')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: INVENTORY & MATERIALS CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/8 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-500/8 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Dashboard Overview</h2>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('inventory.stock-movements.create') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Record Movement
                </a>
                <a href="{{ route('inventory.materials.create') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-box text-xs text-cyan-400"></i> New Material
                </a>
                <a href="{{ route('inventory.reports.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-file-waveform text-xs text-amber-400"></i> Stock Reports
                </a>
            </div>
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
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber/80 pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-xl bg-teal-500/10 border border-teal-500/20 text-[#009498] flex items-center justify-center text-xs shadow-xs">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Multi-Branch Stock Inflow vs. Floor Consumption</h3>
                        </div>
                        <p class="text-[11px] sm:text-xs text-cyber-muted mt-1">Incoming raw material restocking compared against press floor deductions</p>
                    </div>

                    <div class="flex items-center gap-3.5 text-xs font-medium shrink-0">
                        <div class="flex items-center gap-1.5">
                            <span class="h-3 w-5 rounded-sm shadow-xs" style="background: linear-gradient(135deg, #10b981, #34d399);"></span>
                            <span class="text-cyber-main text-[11px] font-semibold">Stock In</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="h-3 w-5 rounded-sm shadow-xs" style="background: linear-gradient(135deg, #f43f5e, #fb7185);"></span>
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
        <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col justify-between h-full">
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Stock Buffer Health</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Threshold health distribution</p>
                    </div>
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs shadow-xs shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>

                @php
                    $totalHealthCount = max(1, array_sum($stockHealthBreakdown));
                    $healthLabels = array_keys($stockHealthBreakdown);
                    $healthValues = array_values($stockHealthBreakdown);
                    $healthColors = ['#10b981', '#f59e0b', '#f43f5e'];
                @endphp

                <div class="relative flex items-center justify-center my-2 h-44 w-full">
                    <canvas id="stockHealthDonutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-black font-display text-cyber-main leading-tight">{{ $totalHealthCount }}</span>
                        <span class="text-[9px] font-black uppercase tracking-widest" style="color: #94A3B8; letter-spacing: 0.12em;">Stock Items</span>
                    </div>
                </div>

                <div class="space-y-1.5 pt-2 border-t border-cyber/60 flex-1 overflow-y-auto pr-1">
                    @foreach($stockHealthBreakdown as $hLabel => $hVal)
                        @php
                            $hPct = round(($hVal / $totalHealthCount) * 100);
                            $hDot = $healthColors[$loop->index % count($healthColors)];
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1.5 px-2 rounded-lg transition" style="transition: background 0.15s;" onmouseenter="this.style.background='rgba(255,255,255,0.06)'" onmouseleave="this.style.background='transparent'">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2.5 w-2.5 rounded-full shrink-0 shadow-xs" style="background-color: {{ $hDot }}"></span>
                                <span class="text-cyber-main font-medium truncate text-[11px]">{{ $hLabel }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-cyber-muted shrink-0 ml-2 font-bold">
                                {{ $hVal }} <span class="text-[10px] font-normal text-cyber-sub">({{ $hPct }}%)</span>
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
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500 flex items-center justify-center text-sm shadow-xs shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Critical Stock Warnings</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Supplies falling below safety buffer limits</p>
                    </div>
                </div>
                <a href="{{ route('inventory.stock.index') }}" class="text-xs font-bold text-cyan-500 hover:text-cyan-400 dark:text-cyan-400 dark:hover:text-cyan-300 flex items-center gap-1.5 transition px-2.5 py-1 rounded-lg hover:bg-cyan-500/10">
                    Manage Stock <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]" style="background: rgba(13,21,32,0.75);">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Material</th>
                            <th class="px-4 sm:px-5 py-3">Branch</th>
                            <th class="px-4 sm:px-5 py-3">Balance</th>
                            <th class="px-4 sm:px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @forelse($lowStockItems as $lItem)
                            <tr class="transition-colors" onmouseenter="this.style.background='rgba(30,41,59,0.4)'" onmouseleave="this.style.background=''">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-bold text-cyber-main block text-xs leading-tight">{{ $lItem->material->name ?? 'Material' }}</span>
                                    <span class="text-[10px] text-cyber-muted inline-flex items-center gap-1 mt-0.5 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                                        {{ ucfirst($lItem->material->type ?? 'media') }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-xs whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 font-medium text-cyber-main">
                                        <i class="fa-solid fa-building text-[10px] text-cyber-muted shrink-0"></i>
                                        <span>{{ $lItem->branch->name ?? 'Branch' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-baseline gap-1 text-xs">
                                        <span class="font-black tabular-nums {{ ((float)$lItem->quantity == 0 || $lItem->status === 'out_of_stock') ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                                            {{ (float)$lItem->quantity }}
                                        </span>
                                        <span class="text-[11px] text-cyber-muted font-medium">
                                            / min {{ (float)$lItem->minimum_stock }} {{ $lItem->material->unit }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($lItem->status === 'out_of_stock' || (float)$lItem->quantity == 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-xs">
                                            <i class="fa-solid fa-circle-xmark text-[9px]"></i> Out of Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 shadow-xs">
                                            <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Low Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">
                                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg mb-1 block"></i>
                                    All raw material stocks are within safe operational buffers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: RECENT STOCK MOVEMENTS --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-500 flex items-center justify-center text-sm shadow-xs shrink-0">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Stock Movements</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Automated job deductions and replenishment entries</p>
                    </div>
                </div>
                <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-bold text-cyan-500 hover:text-cyan-400 dark:text-cyan-400 dark:hover:text-cyan-300 flex items-center gap-1.5 transition px-2.5 py-1 rounded-lg hover:bg-cyan-500/10">
                    All Movements <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]" style="background: rgba(13,21,32,0.75);">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Material</th>
                            <th class="px-4 sm:px-5 py-3">Type</th>
                            <th class="px-4 sm:px-5 py-3">Quantity</th>
                            <th class="px-4 sm:px-5 py-3">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @forelse($recentMovements as $mv)
                            <tr class="transition-colors" onmouseenter="this.style.background='rgba(30,41,59,0.4)'" onmouseleave="this.style.background=''">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-bold text-cyber-main block text-xs leading-tight">{{ $mv->material->name ?? 'Material' }}</span>
                                    <span class="text-[10px] text-cyber-muted block font-medium mt-0.5">{{ $mv->branch->name ?? 'Branch Hub' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @if($mv->movement_type === 'stock_in')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 shadow-xs">
                                            <i class="fa-solid fa-arrow-down text-[9px]"></i> Stock In
                                        </span>
                                    @elseif($mv->movement_type === 'stock_out')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-xs">
                                            <i class="fa-solid fa-arrow-up text-[9px]"></i> Stock Out
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-cyan-100 dark:bg-cyan-500/20 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30 shadow-xs">
                                            <i class="fa-solid fa-sliders text-[9px]"></i> {{ ucwords(str_replace('_', ' ', $mv->movement_type)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    @php
                                        $qtyDisplay = (float)$mv->quantity;
                                    @endphp
                                    <div class="flex items-baseline gap-1 text-xs">
                                        @if($mv->movement_type === 'stock_out')
                                            <span class="font-black tabular-nums text-rose-600 dark:text-rose-400">
                                                -{{ $qtyDisplay }}
                                            </span>
                                        @else
                                            <span class="font-black tabular-nums text-emerald-600 dark:text-emerald-400">
                                                +{{ $qtyDisplay }}
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-cyber-muted font-medium">{{ $mv->material->unit ?? 'units' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-mono text-[10px] font-medium tracking-wide shadow-2xs">
                                        {{ $mv->reference ?? ($mv->reason ?: 'Auto Deduct') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">
                                    <i class="fa-solid fa-inbox text-cyber-muted text-lg mb-1 block"></i>
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
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 11,
                                    weight: '600'
                                },
                                padding: 6
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255,255,255,0.04)' : 'rgba(148,163,184,0.18)',
                                lineWidth: 1,
                            },
                            border: {
                                dash: [4, 4],
                                color: 'transparent',
                            },
                            ticks: {
                                color: isDark ? '#94a3b8' : '#64748b',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 10
                                },
                                padding: 8
                            },
                            title: {
                                display: true,
                                text: 'Quantity Volume',
                                color: isDark ? '#475569' : '#94a3b8',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },
                                padding: { bottom: 6 }
                            }
                        }
                    },
                    animation: {
                        duration: 900,
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
            const hColors = ['#10b981', '#f59e0b', '#f43f5e'];

            new Chart(healthCanvas, {
                type: 'doughnut',
                data: {
                    labels: hLabels,
                    datasets: [{
                        data: hData,
                        backgroundColor: hColors,
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
