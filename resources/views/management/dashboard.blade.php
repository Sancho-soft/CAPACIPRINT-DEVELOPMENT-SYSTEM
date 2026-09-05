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
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Executive Management Dashboard</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/30 font-mono">
                            Enterprise Direct
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        High-level commercial printing telemetry: multi-branch throughput, revenue health, capacity utilization, and order fulfillment.
                    </p>
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
    {{-- LEVEL 1: ACTIONABLE ATTENTION CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.attention-center 
        :items="$attentionItems"
        title="Executive Attention Center"
        subtitle="Purchase requisitions awaiting signoff, delayed press runs, and critical inventory warnings"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY EXECUTIVE METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="TOTAL PRINT ORDERS"
            :value="$totalOrders"
            icon="fa-solid fa-box-archive"
            accent="cyan"
            trend="{{ $activeOrders }} active runs"
            trendType="neutral"
            subtitle="Lifetime order intake"
            :link="route('management.orders.index')"
        />

        <x-dashboard.kpi-card 
            title="CONFIRMED REVENUE"
            :value="'₱' . number_format($totalRevenue, 2)"
            icon="fa-solid fa-coins"
            accent="emerald"
            trend="₱{{ number_format($pendingPayments, 2) }} pending"
            trendType="up"
            subtitle="Verified client transactions"
        />

        <x-dashboard.kpi-card 
            title="ON PRESS FLOOR"
            :value="$inProduction"
            icon="fa-solid fa-industry"
            accent="indigo"
            trend="{{ $readyForPickup }} ready for pickup"
            trendType="up"
            subtitle="Active job execution"
            :link="route('management.production.index')"
        />

        <x-dashboard.kpi-card 
            title="INVENTORY BOTTLENECKS"
            :value="$lowStockCount"
            icon="fa-solid fa-boxes-stacked"
            accent="amber"
            trend="{{ $lowStockCount > 0 ? 'Requires restock' : 'Supplies healthy' }}"
            :trendType="$lowStockCount > 0 ? 'warning' : 'up'"
            subtitle="Materials below threshold"
            :link="route('management.inventory.index')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING LIFECYCLE PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Enterprise Commercial Printing Pipeline"
        subtitle="End-to-end commercial printing flow across customer intake, quote matrix, press run, and pickup"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 3: MULTI-BRANCH LOAD & SERVICES BREAKDOWN --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT 2 COLS: MULTI-BRANCH WORKLOAD --}}
        <div class="lg:col-span-2">
            <x-dashboard.branch-workload-card 
                :branches="$branches"
                title="Branch Capacity & Equipment Utilization"
                subtitle="Live daily job volume versus rated threshold across all locations"
                :actionUrl="route('management.branches.index')"
                actionLabel="Branch Directory"
            />
        </div>

        {{-- RIGHT 1 COL: PRINT SERVICES MIX --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-cyber/80 pb-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Print Services Mix</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Order distribution by category</p>
                    </div>
                    <i class="fa-solid fa-chart-pie text-cyan-400 text-sm"></i>
                </div>

                <div class="pt-4 space-y-3">
                    @php
                        $serviceList = !empty($serviceBreakdown) ? $serviceBreakdown : [
                            'Document Printing' => 4,
                            'Flyers & Brochures' => 6,
                            'Tarpaulin Banner' => 3,
                            'Calling Cards' => 2,
                        ];
                        $totalSvc = max(1, array_sum($serviceList));
                    @endphp

                    @foreach($serviceList as $sName => $sCount)
                        @php
                            $sPct = round(($sCount / $totalSvc) * 100);
                            $sColor = match($loop->index % 4) {
                                0 => 'bg-cyan-400',
                                1 => 'bg-indigo-400',
                                2 => 'bg-emerald-400',
                                default => 'bg-amber-400',
                            };
                        @endphp
                        <div class="space-y-1">
                            <div class="flex justify-between items-center text-xs font-medium">
                                <span class="text-cyber-main font-semibold truncate max-w-[170px]">{{ $sName }}</span>
                                <span class="text-cyber-muted font-mono">{{ $sCount }} ({{ $sPct }}%)</span>
                            </div>
                            <div class="w-full h-2 bg-cyber-base rounded-full overflow-hidden border border-cyber/50">
                                <div class="h-full rounded-full {{ $sColor }}" style="width: {{ $sPct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-cyber/80 flex items-center justify-between text-[11px] text-cyber-muted">
                <span>Total Request Categories</span>
                <span class="font-mono font-bold text-cyan-400">{{ count($serviceList) }} Services</span>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: RECENT CUSTOMER ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Recent Customer Orders & Routing Status"
        subtitle="Live client transactions, routing destination, and current fulfillment stage"
        :viewAllUrl="route('management.orders.index')"
        viewAllLabel="All Client Orders"
    />

</div>
@endsection
