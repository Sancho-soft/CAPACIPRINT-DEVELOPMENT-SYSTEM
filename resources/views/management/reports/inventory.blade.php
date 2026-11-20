@extends('layouts.internal')
@section('title', 'Executive Inventory Stock Valuation')
@section('page-title', 'Inventory & Materials Stock Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-cyber-card text-cyber-main border border-cyber p-6 sm:p-8 rounded-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display text-cyber-main">Inventory &amp; Materials Report</h2>
                <p class="text-xs sm:text-sm text-cyber-muted">Multi-branch stock availability, reorder thresholds, and material valuation audit.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-file-csv text-sm"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm">
        <form method="GET" action="{{ route('management.reports.inventory') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-5">
                <label class="block font-bold text-cyber-muted mb-1">Filter Branch</label>
                <select name="branch_id" class="w-full px-3.5 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($byBranch as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block font-bold text-cyber-muted mb-1">Stock Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock (Below Reorder)</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['branch_id', 'status']))
                    <a href="{{ route('management.reports.inventory') }}" class="py-2 px-3 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-cyber-card rounded-2xl border border-cyber shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-muted tabular-nums">
                <thead class="bg-cyber-sub text-[11px] font-extrabold text-cyber-muted border-b border-cyber">
                    <tr>
                        <th class="py-3.5 px-5">Branch</th>
                        <th class="py-3.5 px-4">Material Name</th>
                        <th class="py-3.5 px-4">Category / Spec</th>
                        <th class="py-3.5 px-4">Current Stock</th>
                        <th class="py-3.5 px-4">Reorder Level</th>
                        <th class="py-3.5 px-5 text-right">Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($inventory as $inv)
                        <tr class="hover:bg-cyber-sub/60 transition">
                            <td class="py-4 px-5 font-bold text-cyber-main font-display">{{ $inv->branch->name ?? '—' }}</td>
                            <td class="py-4 px-4 font-semibold text-cyber-main">{{ $inv->material->name ?? '—' }}</td>
                            <td class="py-4 px-4 text-cyber-muted">{{ $inv->material->specification ?? 'Standard' }}</td>
                            <td class="py-4 px-4 font-bold text-cyber-main text-sm">
                                {{ number_format($inv->current_stock, 0) }} {{ $inv->material->unit ?? 'units' }}
                            </td>
                            <td class="py-4 px-4 text-cyber-muted">
                                {{ $inv->reorder_level }} {{ $inv->material->unit ?? 'units' }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $inv->current_stock <= $inv->reorder_level ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $inv->current_stock <= 0 ? 'Out of Stock' : ($inv->current_stock <= $inv->reorder_level ? 'Low Stock' : 'Optimal') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-cyber-muted">
                                <i class="fa-solid fa-boxes-stacked text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">No inventory records found for the selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
