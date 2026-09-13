@extends('layouts.internal')
@section('title', 'Material Inventory Reports')
@section('page-title', 'Material Inventory & Stock Audit Reports')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER & AUDIT ACTIONS (EXPORT & PRINT)           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl p-6 sm:p-7 shadow-xs dark:shadow-2xl print:border-none print:shadow-none print:p-0 z-30">
        {{-- Decorative blur layer safely isolated with overflow-hidden --}}
        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-navy-900 dark:text-cyber-main">Material Stock Audit &amp; Health Reports</h2>
                </div>
            </div>

            {{-- Audit Actions: Multi-CSV Export Dropdown --}}
            <div class="flex flex-wrap items-center gap-2.5 shrink-0 print:hidden">

                {{-- Export CSV Dropdown Menu (Emerald Green Theme) --}}
                <div class="relative z-50" x-data="{ exportOpen: false }" @click.outside="exportOpen = false">
                    <button @click="exportOpen = !exportOpen" 
                            type="button" 
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/25 transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-file-csv text-sm"></i>
                        <span>Export CSV</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="exportOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="exportOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 mt-2 w-64 rounded-2xl bg-white dark:bg-cyber-card border border-slate-200 dark:border-cyber shadow-2xl z-[100] p-1.5 space-y-1"
                         style="display: none;">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'stock_csv']) }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-cyber-sub transition">
                            <div class="h-7 w-7 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div>
                                <div class="font-bold text-navy-900 dark:text-white">Full Stock Balances</div>
                                <div class="text-[10px] text-slate-400">All substrates and quantities</div>
                            </div>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'reorder_csv']) }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-cyber-sub transition">
                            <div class="h-7 w-7 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <div class="font-bold text-navy-900 dark:text-white">Urgent Reorder List</div>
                                <div class="text-[10px] text-slate-400">Items below minimum stock</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. 4 CORE INVENTORY KPI METRIC CARDS                      --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Active Materials --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between gap-3">
            <div class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="flex-1 text-center px-2 min-w-0">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block leading-tight">
                    Active Catalog Substrates
                </span>
            </div>
            <div class="shrink-0 text-right">
                <span class="text-2xl sm:text-3xl font-black text-navy-900 dark:text-cyber-main font-display">
                    {{ $totalMaterials }}
                </span>
            </div>
        </div>

        {{-- Card 2: Stock Health Rate --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between gap-3">
            <div class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
            <div class="flex-1 text-center px-2 min-w-0">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block leading-tight">
                    Stock Health Index
                </span>
            </div>
            <div class="shrink-0 text-right">
                <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 font-display">
                    {{ $healthRate }}%
                </span>
            </div>
        </div>

        {{-- Card 3: Low Stock Warnings --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between gap-3">
            <div class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1 text-center px-2 min-w-0">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block leading-tight">
                    Low Stock Warnings
                </span>
            </div>
            <div class="shrink-0 text-right">
                <span class="text-2xl sm:text-3xl font-black text-amber-600 dark:text-amber-400 font-display">
                    {{ $lowStockCount }}
                </span>
            </div>
        </div>

        {{-- Card 4: Out of Stock Deficit --}}
        <div class="bg-white dark:bg-cyber-card p-5 rounded-2xl border border-slate-200/80 dark:border-cyber shadow-xs dark:shadow-xl flex items-center justify-between gap-3">
            <div class="h-11 w-11 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <div class="flex-1 text-center px-2 min-w-0">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block leading-tight">
                    Out of Stock Depleted
                </span>
            </div>
            <div class="shrink-0 text-right">
                <span class="text-2xl sm:text-3xl font-black {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }} font-display">
                    {{ $outOfStockCount }}
                </span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. INTERACTIVE REPORTING FILTER BAR                       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-2xl p-4 sm:p-5 shadow-xs print:hidden">
        <form method="GET" action="{{ route('inventory.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end text-xs">
            {{-- Search Input --}}
            <div class="lg:col-span-4">
                <label class="block font-bold text-slate-600 dark:text-slate-400 text-[11px] mb-1 uppercase tracking-wider">Search Specification</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="e.g. Cyan Toner, Tarpaulin..." 
                           class="w-full pl-9 pr-3.5 py-2 rounded-xl bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber text-navy-900 dark:text-white text-xs focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400">
                </div>
            </div>

            {{-- Branch Selector --}}
            <div class="lg:col-span-3">
                <label class="block font-bold text-slate-600 dark:text-slate-400 text-[11px] mb-1 uppercase tracking-wider">Branch Facility</label>
                <select name="branch_id" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber text-navy-900 dark:text-white text-xs focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="">All Facilities (Network Wide)</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category Selector --}}
            <div class="lg:col-span-2">
                <label class="block font-bold text-slate-600 dark:text-slate-400 text-[11px] mb-1 uppercase tracking-wider">Category</label>
                <select name="category" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber text-navy-900 dark:text-white text-xs focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="">All Categories</option>
                    <option value="paper" {{ request('category') === 'paper' ? 'selected' : '' }}>Paper / Media</option>
                    <option value="ink" {{ request('category') === 'ink' ? 'selected' : '' }}>Inks &amp; Toners</option>
                    <option value="lamination" {{ request('category') === 'lamination' ? 'selected' : '' }}>Lamination Films</option>
                    <option value="binding" {{ request('category') === 'binding' ? 'selected' : '' }}>Binding Materials</option>
                    <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other Consumables</option>
                </select>
            </div>

            {{-- Stock Status Filter --}}
            <div class="lg:col-span-2">
                <label class="block font-bold text-slate-600 dark:text-slate-400 text-[11px] mb-1 uppercase tracking-wider">Stock Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber text-navy-900 dark:text-white text-xs focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>In Stock / Optimal</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock Warning</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock Depleted</option>
                </select>
            </div>

            {{-- Filter & Reset Actions --}}
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-xs transition shadow-xs flex items-center justify-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-filter text-[10px]"></i> Filter
                </button>
                @if(request()->hasAny(['branch_id', 'category', 'status', 'search']))
                    <a href="{{ route('inventory.reports.index') }}" class="py-2 px-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition font-bold" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. CRITICAL REORDER & RESTOCK WARNINGS TABLE              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-cyber/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50 dark:bg-cyber-dark/40">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-cyber-main font-display">Urgent Stock Replenishment &amp; Reorder List</h3>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 print:hidden">
                @if($reorderItems->count() > 0)
                <a href="{{ request()->fullUrlWithQuery(['export' => 'reorder_csv']) }}" 
                   class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/25 transition flex items-center gap-2 cursor-pointer"
                   title="Download Urgent Reorder List as CSV for purchasing">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </a>
                @endif
            </div>
        </div>

        @if($reorderItems->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-cyber-dark text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-cyber">
                    <tr>
                        <th class="px-6 py-3.5">Material Specification</th>
                        <th class="px-6 py-3.5">Branch Location</th>
                        <th class="px-6 py-3.5 text-right">Current Balance</th>
                        <th class="px-6 py-3.5 text-right">Safety Minimum</th>
                        <th class="px-6 py-3.5 text-right">Replenishment Deficit</th>
                        <th class="px-6 py-3.5 text-center">Status</th>
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
                        <td class="px-6 py-4 font-mono font-black text-sm text-right {{ $item->status === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ number_format($item->quantity, 2) }} <span class="text-xs font-normal text-slate-400">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-right text-slate-800 dark:text-slate-200">
                            {{ number_format($item->minimum_stock, 2) }} <span class="text-xs font-normal text-slate-400">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-right text-rose-600 dark:text-rose-400">
                            +{{ number_format($deficit, 2) }} <span class="text-xs font-normal text-slate-400">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status_badge_class }}">
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
            <div class="h-12 w-12 rounded-full bg-emerald-500/10 text-emerald-500 mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="font-bold text-navy-900 dark:text-white text-sm">All Inventory Levels are Healthy</p>
            <p class="text-xs text-slate-400">No substrates or media are currently below the required safety threshold.</p>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. COMPREHENSIVE STOCK INVENTORY & AUDIT LEDGER (7 COLS)  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-cyber-card border border-slate-200/80 dark:border-cyber rounded-3xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-cyber/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50 dark:bg-cyber-dark/40">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-navy-900 dark:text-cyber-main font-display">Comprehensive Stock Inventory &amp; Audit Ledger</h3>
                </div>
            </div>
            <div class="flex items-center gap-2.5 shrink-0 print:hidden">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'stock_csv']) }}" 
                   class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm shadow-emerald-600/25 transition flex items-center gap-2 cursor-pointer"
                   title="Export all stock inventory items to CSV">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        @if($inventory->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-cyber-dark text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-cyber">
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
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @foreach($inventory as $item)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900 dark:text-white">
                            {{ $item->branch->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-navy-900 dark:text-white">{{ $item->material->name ?? 'Unknown Material' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $item->material->description ? \Illuminate\Support\Str::limit($item->material->description, 35) : 'Standard substrate' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->material->type_label ?? 'Consumable' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-black text-sm text-right {{ $item->status === 'out_of_stock' ? 'text-rose-600 dark:text-rose-400' : ($item->status === 'low_stock' ? 'text-amber-600 dark:text-amber-400' : 'text-navy-900 dark:text-white') }}">
                            {{ number_format($item->quantity, 2) }} <span class="text-xs font-normal text-slate-400">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-right text-slate-700 dark:text-slate-300">
                            {{ number_format($item->minimum_stock, 2) }} <span class="text-xs font-normal text-slate-400">{{ $item->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-slate-400 text-[11px]">
                            {{ $item->last_updated ? $item->last_updated->format('M d, Y h:i A') : ($item->updated_at ? $item->updated_at->format('M d, Y') : '—') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Controls (Every 7 Items Per Page) --}}
        @if($inventory->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-cyber/80 bg-slate-50/40 dark:bg-cyber-dark/30">
            {{ $inventory->links() }}
        </div>
        @endif

        @else
        <div class="p-8 text-center space-y-2">
            <div class="h-12 w-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <p class="font-bold text-navy-900 dark:text-white text-sm">No Inventory Records Found</p>
            <p class="text-xs text-slate-400">No stock items match your current filter parameters.</p>
            @if(request()->hasAny(['branch_id', 'category', 'status', 'search']))
            <a href="{{ route('inventory.reports.index') }}" class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold text-xs transition">
                <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filters
            </a>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection
