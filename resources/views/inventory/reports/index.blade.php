@extends('layouts.internal')
@section('title', 'Material Inventory Reports')
@section('page-title', 'Material Inventory & Stock Audit Reports')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER & AUDIT ACTIONS (UNBOXED & CLEAN)          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Material Stock Audit &amp; Health Reports</h1>
            <p class="text-xs text-cyber-muted mt-1">Cross-branch substrate balances, reorder threshold alerts, and inventory valuation.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            {{-- Export CSV Dropdown Menu --}}
            <div class="relative z-50" x-data="{ exportOpen: false }" @click.outside="exportOpen = false">
                <button @click="exportOpen = !exportOpen" 
                        type="button" 
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                    <i class="fa-solid fa-chevron-down text-[11px] transition-transform duration-200" :class="exportOpen ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="exportOpen" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 mt-2 w-64 rounded-2xl bg-cyber-card border border-cyber shadow-lg z-[100] p-1.5 space-y-1"
                     style="display: none;">
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'stock_csv']) }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-cyber-main hover:bg-cyber-sub transition">
                        <div class="h-8 w-8 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <div class="font-bold text-cyber-main">Full Stock Balances</div>
                            <div class="text-[11px] text-cyber-muted">All substrates and quantities</div>
                        </div>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'reorder_csv']) }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-cyber-main hover:bg-cyber-sub transition">
                        <div class="h-8 w-8 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="font-bold text-cyber-main">Urgent Reorder List</div>
                            <div class="text-[11px] text-cyber-muted">Items below minimum stock</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 4 CORE INVENTORY KPI METRIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm flex items-center justify-between gap-3">
            <div class="h-10 w-10 rounded-xl bg-blue-600/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="flex-1 px-2 min-w-0">
                <span class="text-xs font-sans tabular-nums font-semibold text-cyber-muted block leading-tight">
                    Active Catalog Substrates
                </span>
            </div>
            <div class="shrink-0 text-right tabular-nums">
                <span class="text-2xl sm:text-3xl font-bold text-cyber-main font-display">
                    {{ $totalMaterials }}
                </span>
            </div>
        </div>

        <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm flex items-center justify-between gap-3">
            <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
            <div class="flex-1 px-2 min-w-0">
                <span class="text-xs font-sans tabular-nums font-semibold text-cyber-muted block leading-tight">
                    Stock Health Index
                </span>
            </div>
            <div class="shrink-0 text-right tabular-nums">
                <span class="text-2xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 font-display">
                    {{ $healthRate }}%
                </span>
            </div>
        </div>

        <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm flex items-center justify-between gap-3">
            <div class="h-10 w-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1 px-2 min-w-0">
                <span class="text-xs font-sans tabular-nums font-semibold text-cyber-muted block leading-tight">
                    Low Stock Warnings
                </span>
            </div>
            <div class="shrink-0 text-right tabular-nums">
                <span class="text-2xl sm:text-3xl font-bold text-amber-600 dark:text-amber-400 font-display">
                    {{ $lowStockCount }}
                </span>
            </div>
        </div>

        <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm flex items-center justify-between gap-3">
            <div class="h-10 w-10 rounded-xl {{ $outOfStockCount > 0 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-400' : 'bg-cyber-sub text-cyber-muted' }} flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <div class="flex-1 px-2 min-w-0">
                <span class="text-xs font-sans tabular-nums font-semibold text-cyber-muted block leading-tight">
                    Out of Stock Depleted
                </span>
            </div>
            <div class="shrink-0 text-right tabular-nums">
                <span class="text-2xl sm:text-3xl font-bold {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-cyber-main' }} font-display">
                    {{ $outOfStockCount }}
                </span>
            </div>
        </div>
    </div>

    {{-- INTERACTIVE REPORTING FILTER BAR --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-4 sm:p-5 print:hidden">
        <form method="GET" action="{{ route('inventory.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end text-xs">
            <div class="lg:col-span-4">
                <label class="block font-sans tabular-nums font-semibold text-cyber-main text-xs mb-1.5">Search Specification</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="e.g. Cyan Toner, Tarpaulin..." 
                           class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-cyber-sub border border-cyber text-cyber-main text-xs focus:border-blue-600 focus:outline-none placeholder-cyber-muted">
                </div>
            </div>

            <div class="lg:col-span-3">
                <label class="block font-sans tabular-nums font-semibold text-cyber-main text-xs mb-1.5">Branch Facility</label>
                <select name="branch_id" class="w-full px-3 py-2.5 rounded-xl bg-cyber-sub border border-cyber text-cyber-main text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">All Facilities (Network Wide)</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block font-sans tabular-nums font-semibold text-cyber-main text-xs mb-1.5">Category</label>
                <select name="category" class="w-full px-3 py-2.5 rounded-xl bg-cyber-sub border border-cyber text-cyber-main text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">All Categories</option>
                    <option value="paper" {{ request('category') === 'paper' ? 'selected' : '' }}>Paper / Media</option>
                    <option value="ink" {{ request('category') === 'ink' ? 'selected' : '' }}>Inks &amp; Toners</option>
                    <option value="lamination" {{ request('category') === 'lamination' ? 'selected' : '' }}>Lamination Films</option>
                    <option value="binding" {{ request('category') === 'binding' ? 'selected' : '' }}>Binding Materials</option>
                    <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other Consumables</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block font-sans tabular-nums font-semibold text-cyber-main text-xs mb-1.5">Stock Status</label>
                <select name="status" class="w-full px-3 py-2.5 rounded-xl bg-cyber-sub border border-cyber text-cyber-main text-xs focus:border-blue-600 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>In Stock / Optimal</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock Warning</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock Depleted</option>
                </select>
            </div>

            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-filter text-[11px]"></i> Filter
                </button>
                @if(request()->hasAny(['branch_id', 'category', 'status', 'search']))
                    <a href="{{ route('inventory.reports.index') }}" class="py-2.5 px-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted transition font-semibold" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 4. CRITICAL REORDER & RESTOCK WARNINGS TABLE --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-cyber-main font-display">Urgent Stock Replenishment &amp; Reorder List</h3>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 print:hidden">
                @if($reorderItems->count() > 0)
                <a href="{{ request()->fullUrlWithQuery(['export' => 'reorder_csv']) }}" 
                   class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition flex items-center gap-2 cursor-pointer"
                   title="Download Urgent Reorder List as CSV for purchasing">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </a>
                @endif
            </div>
        </div>

        @if($reorderItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub/60 text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Material Specification</th>
                        <th class="px-5 py-3">Branch Location</th>
                        <th class="px-5 py-3 text-right">Current Balance</th>
                        <th class="px-5 py-3 text-right">Safety Minimum</th>
                        <th class="px-5 py-3 text-right">Replenishment Deficit</th>
                        <th class="px-5 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @foreach($reorderItems as $item)
                    @php
                        $deficit = max(0, $item->minimum_stock - $item->quantity);
                    @endphp
                    <tr class="hover:bg-cyber-sub/40 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-cyber-main">{{ $item->material->name ?? 'Unknown Material' }}</div>
                            <div class="text-[11px] text-cyber-muted">{{ $item->material->type_label ?? 'Consumable' }}</div>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-cyber-main">
                            {{ $item->branch->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-sm text-right {{ $item->status === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ number_format($item->quantity, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-sans tabular-nums font-semibold text-right text-cyber-main">
                            {{ number_format($item->minimum_stock, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-sans tabular-nums font-semibold text-right text-rose-600 dark:text-rose-400">
                            +{{ number_format($deficit, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-sans tabular-nums font-semibold {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center space-y-2">
            <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="font-bold text-cyber-main font-display text-sm">All Inventory Levels are Healthy</p>
            <p class="text-xs text-cyber-muted">No substrates or media are currently below the required safety threshold.</p>
        </div>
        @endif
    </div>

    {{-- 5. COMPREHENSIVE STOCK INVENTORY & AUDIT LEDGER --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-cyber-main font-display">Comprehensive Stock Inventory &amp; Audit Ledger</h3>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 print:hidden">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'stock_csv']) }}" 
                   class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition flex items-center gap-2 cursor-pointer"
                   title="Export all stock inventory items to CSV">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        @if($inventory->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-semibold border-b border-cyber text-xs">
                    <tr>
                        <th class="px-6 py-3.5">Branch Facility</th>
                        <th class="px-6 py-3.5">Material Specification</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5 text-right">Current Balance</th>
                        <th class="px-6 py-3.5 text-right">Safety Minimum</th>
                        <th class="px-6 py-3.5 text-center">Stock Status</th>
                        <th class="px-6 py-3.5 text-right">Last Audit / Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main">
                    @foreach($inventory as $item)
                    <tr class="hover:bg-cyber-sub/50 transition">
                        <td class="px-6 py-4 font-bold text-cyber-main">
                            {{ $item->branch->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-cyber-main">{{ $item->material->name ?? 'Unknown Material' }}</div>
                            <div class="text-[11px] text-cyber-muted font-sans tabular-nums">{{ $item->material->description ? \Illuminate\Support\Str::limit($item->material->description, 35) : 'Standard substrate' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-cyber-sub text-cyber-main border border-cyber">
                                {{ $item->material->type_label ?? 'Consumable' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-sans tabular-nums font-bold text-sm text-right {{ $item->status === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : ($item->status === 'low_stock' ? 'text-amber-600 dark:text-amber-400' : 'text-cyber-main') }}">
                            {{ number_format($item->quantity, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 font-sans tabular-nums font-semibold text-right text-cyber-main">
                            {{ number_format($item->minimum_stock, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-sans tabular-nums text-cyber-muted text-[11px]">
                            {{ $item->last_updated ? $item->last_updated->format('M d, Y h:i A') : ($item->updated_at ? $item->updated_at->format('M d, Y') : '—') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls (Every 7 Items Per Page) --}}
        @if($inventory->hasPages())
        <div class="px-6 py-4 border-t border-cyber bg-cyber-sub/50">
            {{ $inventory->links() }}
        </div>
        @endif

        @else
        <div class="p-8 text-center space-y-2">
            <div class="h-12 w-12 rounded-2xl bg-cyber-sub border border-cyber text-cyber-muted mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <p class="font-bold text-cyber-main font-display text-sm">No Inventory Records Found</p>
            <p class="text-xs text-cyber-muted">No stock items match your current filter parameters.</p>
            @if(request()->hasAny(['branch_id', 'category', 'status', 'search']))
            <a href="{{ route('inventory.reports.index') }}" class="inline-flex items-center gap-1.5 mt-2 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition">
                <i class="fa-solid fa-rotate-left text-[11px]"></i> Reset Filters
            </a>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection
