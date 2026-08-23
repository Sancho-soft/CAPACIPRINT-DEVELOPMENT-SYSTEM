@extends('layouts.internal')
@section('title', 'Executive Inventory Stock Valuation')
@section('page-title', 'Inventory & Materials Stock Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-teal-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Inventory &amp; Materials Report</h2>
                <p class="text-xs sm:text-sm text-slate-300">Multi-branch stock availability, reorder thresholds, and material valuation audit.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold text-xs shadow-md shadow-teal-500/20 transition">
                <i class="fa-solid fa-file-csv text-sm"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('management.reports.inventory') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-5">
                <label class="block font-bold text-slate-600 mb-1">Filter Branch</label>
                <select name="branch_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($byBranch as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-5">
                <label class="block font-bold text-slate-600 mb-1">Stock Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock (Below Reorder)</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-navy-800 text-white rounded-xl font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['branch_id', 'status']))
                    <a href="{{ route('management.reports.inventory') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Branch</th>
                        <th class="py-3.5 px-4">Material Name</th>
                        <th class="py-3.5 px-4">Category / Spec</th>
                        <th class="py-3.5 px-4">Current Stock</th>
                        <th class="py-3.5 px-4">Reorder Level</th>
                        <th class="py-3.5 px-5 text-right">Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventory as $inv)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-navy-900 font-display">{{ $inv->branch->name ?? '—' }}</td>
                            <td class="py-4 px-4 font-semibold text-slate-800">{{ $inv->material->name ?? '—' }}</td>
                            <td class="py-4 px-4 text-slate-500">{{ $inv->material->specification ?? 'Standard' }}</td>
                            <td class="py-4 px-4 font-bold text-slate-900 text-sm">
                                {{ number_format($inv->current_stock, 0) }} {{ $inv->material->unit ?? 'units' }}
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $inv->reorder_level }} {{ $inv->material->unit ?? 'units' }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $inv->current_stock <= $inv->reorder_level ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $inv->current_stock <= 0 ? 'Out of Stock' : ($inv->current_stock <= $inv->reorder_level ? 'Low Stock' : 'Optimal') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
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
