@extends('layouts.internal')
@section('title', 'Material Inventory Reports')
@section('page-title', 'Material Inventory & Stock Audit Reports')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER & AUDIT ACTIONS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl p-6 sm:p-7 shadow-xs dark:shadow-2xl overflow-hidden print:border-none print:shadow-none print:p-0">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <div class="flex items-center flex-wrap sm:flex-nowrap gap-2.5">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-navy-900 dark:text-cyber-main">Material Stock Audit &amp; Health Reports</h2>
                        <span class="whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black font-mono uppercase bg-cyan-500/15 text-cyan-700 dark:text-cyan-400 border border-cyan-500/30">
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                            Live Audit
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-cyber-muted mt-1">Multi-branch substrate availability, reorder thresholds, and material transaction audit log.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 print:hidden">
                <button onclick="window.print()" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-cyber-sub dark:hover:bg-cyber-card border border-slate-200 dark:border-cyber text-navy-900 dark:text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-xs cursor-pointer">
                    <i class="fa-solid fa-print text-cyan-600 dark:text-cyan-400 text-xs"></i> Print Audit Sheet
                </button>
                <a href="{{ route('inventory.stock-movements.create') }}" class="px-3.5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-xs shadow-sm shadow-cyan-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Record Movement
                </a>
                <a href="{{ route('inventory.materials.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-cyber-sub dark:hover:bg-cyber-card border border-slate-200 dark:border-cyber text-navy-900 dark:text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-boxes-stacked text-amber-500 text-xs"></i> Catalog
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. 4 CORE INVENTORY KPI METRIC CARDS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Active Materials --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Active Catalog Substrates</span>
                <div class="text-2xl font-black text-navy-900 dark:text-cyber-main">{{ $totalMaterials }}</div>
                <div class="text-[11px] text-cyan-600 dark:text-cyan-400 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-check-double text-[10px]"></i> Standardized SKUs
                </div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        {{-- Card 2: Stock Health Rate --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Stock Health Index</span>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $healthRate }}%</div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                    {{ $availableCount }} location slots optimal
                </div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
        </div>

        {{-- Card 3: Low Stock Warnings --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Low Stock Warnings</span>
                <div class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $lowStockCount }}</div>
                <div class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Requires Reorder
                </div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        {{-- Card 4: Out of Stock Deficit --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Out of Stock Depleted</span>
                <div class="text-2xl font-black {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
                    {{ $outOfStockCount }}
                </div>
                <div class="text-[11px] {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400 font-semibold' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ $outOfStockCount > 0 ? 'Immediate action required' : 'Zero depleted items' }}
                </div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. SUBSTRATE CATEGORIES BREAKDOWN --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                <i class="fa-solid fa-tags text-cyan-600 dark:text-cyan-400"></i> Material Distribution by Category
            </h3>
            <span class="text-[11px] text-slate-400 font-mono">{{ $totalMaterials }} Catalog Items</span>
        </div>

        {{-- Proportional Category Breakdown Bar --}}
        @if($totalMaterials > 0)
        <div class="mb-4 space-y-2">
            <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden flex shadow-inner">
                @php
                    $catColors = [
                        'paper'      => 'bg-cyan-500',
                        'ink'        => 'bg-indigo-500',
                        'lamination' => 'bg-teal-500',
                        'binding'    => 'bg-amber-500',
                        'other'      => 'bg-slate-400',
                    ];
                @endphp
                @foreach($categories as $catKey => $cat)
                    @if($cat['count'] > 0)
                    @php $pct = round(($cat['count'] / $totalMaterials) * 100, 1); @endphp
                    <div class="{{ $catColors[$catKey] ?? 'bg-slate-400' }} h-full transition-all duration-500" 
                         style="width: {{ $pct }}%" 
                         title="{{ $cat['label'] }}: {{ $cat['count'] }} ({{ $pct }}%)"></div>
                    @endif
                @endforeach
            </div>
            <div class="flex flex-wrap items-center gap-4 text-[11px] text-slate-500 dark:text-slate-400">
                @foreach($categories as $catKey => $cat)
                    @if($cat['count'] > 0)
                    @php $pct = round(($cat['count'] / $totalMaterials) * 100); @endphp
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full {{ $catColors[$catKey] ?? 'bg-slate-400' }}"></span>
                        <span class="font-bold text-navy-900 dark:text-white">{{ $cat['label'] }}</span>
                        <span class="font-mono text-slate-400">({{ $cat['count'] }} &bull; {{ $pct }}%)</span>
                    </span>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            @foreach($categories as $catKey => $cat)
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/70 dark:border-slate-700/60 flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid {{ $cat['icon'] }}"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ $cat['label'] }}</p>
                    <p class="text-sm font-black text-navy-900 dark:text-white font-mono">{{ $cat['count'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. CRITICAL REORDER & RESTOCK WARNINGS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-cyber/80 flex items-center justify-between bg-slate-50/50 dark:bg-cyber-dark/40">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-cyber-main font-display">Urgent Stock Replenishment &amp; Reorder List</h3>
                    <p class="text-[11px] text-slate-500 dark:text-cyber-muted">Branch inventory items operating at or below the safety minimum threshold.</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono {{ $reorderItems->count() > 0 ? 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30' : 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30' }}">
                {{ $reorderItems->count() }} Items Flagged
            </span>
        </div>

        @if($reorderItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-cyber-dark text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-cyber">
                    <tr>
                        <th class="px-6 py-3.5">Material Specification</th>
                        <th class="px-6 py-3.5">Branch Location</th>
                        <th class="px-6 py-3.5">Current Balance</th>
                        <th class="px-6 py-3.5">Safety Minimum</th>
                        <th class="px-6 py-3.5">Replenishment Deficit</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right print:hidden">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @foreach($reorderItems as $item)
                    @php
                        $deficit = max(0, $item->minimum_stock - $item->quantity);
                    @endphp
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-navy-900 dark:text-white">{{ $item->material->name ?? 'Unknown Material' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $item->material->type_label ?? 'Consumable' }}</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $item->branch->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 font-mono font-black text-sm {{ $item->status === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ number_format($item->quantity, 2) }} {{ $item->material->unit ?? 'units' }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-800 dark:text-slate-200">
                            {{ number_format($item->minimum_stock, 2) }} {{ $item->material->unit ?? 'units' }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-rose-600 dark:text-rose-400">
                            +{{ number_format($deficit, 2) }} {{ $item->material->unit ?? 'units' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right print:hidden">
                            <a href="{{ route('inventory.stock-movements.create', [
                                   'material_id'   => $item->material_id,
                                   'branch_id'     => $item->branch_id,
                                   'movement_type' => 'stock_in',
                                   'quantity'      => $deficit
                               ]) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white shadow-xs transition text-xs font-bold"
                               title="Initiate Stock In replenishment for {{ $item->material->name ?? 'material' }}">
                                <i class="fa-solid fa-plus text-[10px]"></i> Restock
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center space-y-2">
            <div class="h-12 w-12 rounded-full bg-emerald-500/10 text-emerald-500 mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="font-bold text-navy-900 dark:text-white text-sm">All Inventory Levels are Healthy</p>
            <p class="text-xs text-slate-400">No substrates or media are currently below the required safety threshold.</p>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. MULTI-BRANCH STOCK DISTRIBUTION MATRIX --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-navy-900 dark:text-cyber-main font-display">Multi-Branch Stock Allocation Matrix</h3>
                <p class="text-xs text-slate-500 dark:text-cyber-muted">Substrate health and balance breakdown per active facility.</p>
            </div>
            <a href="{{ route('inventory.stock.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                <span>Manage Stock Balances</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($byBranch as $branch)
            @php
                $invRecords = $branch->inventory;
                $bTotal = $invRecords->count();
                $bAvailable = $invRecords->where('status', 'available')->count();
                $bLow = $invRecords->where('status', 'low_stock')->count();
                $bOut = $invRecords->where('status', 'out_of_stock')->count();
                $bPct = $bTotal > 0 ? round(($bAvailable / $bTotal) * 100) : 100;
            @endphp
            <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-bold text-navy-900 dark:text-white text-sm">{{ $branch->name }}</h4>
                        <p class="text-[11px] text-slate-400">{{ $branch->location ?? 'Main Press Hub' }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono {{ $bPct >= 80 ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30' : ($bPct >= 50 ? 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30') }}">
                        {{ $bPct }}% Optimal
                    </span>
                </div>

                {{-- Visual Health Bar --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500 font-medium">Stock Fulfillment</span>
                        <span class="font-bold font-mono text-navy-900 dark:text-white">{{ $bAvailable }} / {{ $bTotal }} items</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden flex">
                        @if($bTotal > 0)
                            <div class="bg-emerald-500 h-full" style="width: {{ ($bAvailable / $bTotal) * 100 }}%"></div>
                            <div class="bg-amber-500 h-full" style="width: {{ ($bLow / $bTotal) * 100 }}%"></div>
                            <div class="bg-rose-500 h-full" style="width: {{ ($bOut / $bTotal) * 100 }}%"></div>
                        @else
                            <div class="bg-slate-300 dark:bg-slate-700 h-full w-full"></div>
                        @endif
                    </div>
                </div>

                {{-- Counts grid --}}
                <div class="grid grid-cols-3 gap-2 text-center pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                    <div class="p-2 rounded-lg bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-500/20">
                        <span class="text-[10px] text-emerald-700 dark:text-emerald-400 block font-bold">In Stock</span>
                        <strong class="text-sm font-mono text-emerald-800 dark:text-emerald-300">{{ $bAvailable }}</strong>
                    </div>
                    <div class="p-2 rounded-lg bg-amber-50/60 dark:bg-amber-950/20 border border-amber-500/20">
                        <span class="text-[10px] text-amber-700 dark:text-amber-400 block font-bold">Low</span>
                        <strong class="text-sm font-mono text-amber-800 dark:text-amber-300">{{ $bLow }}</strong>
                    </div>
                    <div class="p-2 rounded-lg bg-rose-50/60 dark:bg-rose-950/20 border border-rose-500/20">
                        <span class="text-[10px] text-rose-700 dark:text-rose-400 block font-bold">Depleted</span>
                        <strong class="text-sm font-mono text-rose-800 dark:text-rose-300">{{ $bOut }}</strong>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 p-8 text-center text-slate-400">No active branches found.</div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 6. RECENT STOCK MOVEMENTS & AUDIT LOG TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-cyber/80 flex items-center justify-between bg-slate-50/50 dark:bg-cyber-dark/40">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-cyber-main font-display">Recent Stock Movements Audit Trail</h3>
                    <p class="text-[11px] text-slate-500 dark:text-cyber-muted">Latest 15 material consumption, restock, and scrap adjustment logs.</p>
                </div>
            </div>
            <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                <span>View Full Log</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-cyber-dark text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-cyber">
                    <tr>
                        <th class="px-6 py-3.5">Date &amp; Time</th>
                        <th class="px-6 py-3.5">Material</th>
                        <th class="px-6 py-3.5">Branch</th>
                        <th class="px-6 py-3.5">Transaction Type</th>
                        <th class="px-6 py-3.5">Quantity</th>
                        <th class="px-6 py-3.5">Reference / Job</th>
                        <th class="px-6 py-3.5 text-right">Logged By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($recentMovements as $mov)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4 font-mono text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $mov->movement_date?->format('M d, Y') ?? $mov->created_at->format('M d, Y') }}
                            <span class="block text-[10px] text-slate-400">{{ $mov->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-navy-900 dark:text-white">
                            {{ $mov->material->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $mov->branch->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $mov->movement_type_badge_class }}">
                                {{ $mov->movement_type_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-black text-navy-900 dark:text-white">
                            {{ number_format($mov->quantity, 2) }} {{ $mov->material->unit ?? 'units' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono">
                            {{ $mov->reference ?? 'Manual' }}
                        </td>
                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400">
                            <span class="inline-flex items-center gap-1 font-semibold text-navy-900 dark:text-white">
                                <i class="fa-solid fa-user-check text-[10px] text-cyan-500"></i>
                                {{ $mov->user->name ?? 'Elena Stock' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400 space-y-2">
                            <i class="fa-solid fa-inbox text-2xl text-slate-300 dark:text-slate-600 block mb-1"></i>
                            <p class="font-bold text-slate-600 dark:text-slate-400 text-xs">No stock movement transactions recorded yet.</p>
                            <p class="text-[11px] text-slate-400">Restocks and production consumption events will automatically appear in this audit log.</p>
                            <a href="{{ route('inventory.stock-movements.create') }}" class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-xs shadow-xs transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> Record First Stock Movement
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
