@extends('layouts.internal')
@section('title', 'Inventory Staff Dashboard')
@section('page-title', 'Material & Inventory Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Materials Catalog</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $totalMaterials }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Sufficient Stock Items</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $availableCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Low Stock Alerts</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $lowStockCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Out of Stock</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $outOfStockCount }}</h3>
        </div>
    </div>

    {{-- Low Stock & Out of Stock Alerts --}}
    @if($lowStockItems->isNotEmpty())
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-amber-50/50 border-b border-amber-100 flex items-center justify-between">
            <h3 class="font-bold text-amber-900 text-sm"><i class="fa-solid fa-triangle-exclamation text-amber-600 mr-2"></i> Low Stock Warning Items</h3>
            <a href="{{ route('inventory.stock-movements.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs">
                + Record Stock In
            </a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Branch</th>
                    <th class="px-6 py-3">Material</th>
                    <th class="px-6 py-3">Current Stock</th>
                    <th class="px-6 py-3">Minimum Stock</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($lowStockItems as $inv)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3 font-bold text-navy-900">{{ $inv->branch->name ?? '—' }}</td>
                    <td class="px-6 py-3 font-semibold text-slate-800">{{ $inv->material->name ?? '—' }}</td>
                    <td class="px-6 py-3 font-black text-red-600">{{ number_format($inv->quantity, 2) }} {{ $inv->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-3 text-slate-500">{{ number_format($inv->minimum_stock, 2) }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $inv->status_badge_class }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Recent Stock Movements --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Recent Stock Movements</h3>
            <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">View All &rarr;</a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Branch</th>
                    <th class="px-6 py-3">Material</th>
                    <th class="px-6 py-3">Movement</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentMovements as $mov)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3.5 font-bold text-navy-900">{{ $mov->branch->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-slate-800 font-semibold">{{ $mov->material->name ?? '—' }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $mov->movement_type_badge_class }}">
                            {{ $mov->movement_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 font-bold text-slate-900">{{ number_format($mov->quantity, 2) }} {{ $mov->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-3.5 text-slate-500">{{ $mov->movement_date?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No recent stock movements.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
