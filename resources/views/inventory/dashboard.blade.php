@extends('layouts.internal')
@section('title', 'Material & Inventory Dashboard')
@section('page-title', 'Material & Inventory Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Material &amp; Inventory Dashboard</h2>
            <p class="text-xs text-slate-400 mt-1">Raw material stock levels, threshold alerts, and real-time inventory adjustments.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Record Stock Movement
            </a>
        </div>
    </div>

    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Materials Catalog --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">MATERIALS CATALOG</p>
                <h3 class="text-3xl font-black text-white font-display mt-1">{{ $totalMaterials }}</h3>
                <p class="text-[11px] text-slate-500 font-semibold mt-1">Active items</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        {{-- Sufficient Stock --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">SUFFICIENT STOCK</p>
                <h3 class="text-3xl font-black text-white font-display mt-1">{{ $availableCount }}</h3>
                <p class="text-[11px] text-emerald-400 font-semibold mt-1">Healthy buffer</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-square-check"></i>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="min-w-0">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">LOW STOCK ALERTS</p>
                <h3 class="text-3xl font-black text-amber-400 font-display mt-1">{{ $lowStockCount }}</h3>
                <p class="text-[11px] text-amber-400 font-semibold mt-1">Restock required</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        {{-- Out of Stock --}}
                <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-red-500 p-5 flex items-center justify-between shadow-lg hover:border-red-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-circle-xmark text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-red-500 transition-all"></i>
                <div class="text-[11px] font-black text-red-500 uppercase tracking-wider leading-tight max-w-[110px]">OUT OF STOCK</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-red-400 font-display">{{ $outOfStockCount }}</div>
            </div>
        </div>
    </div>

    {{-- Low Stock & Out of Stock Alerts --}}
    @if($lowStockItems->isNotEmpty())
    <div class="bg-[#111A24] border border-amber-500/30 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 bg-amber-500/10 border-b border-amber-500/20 flex items-center justify-between">
            <h3 class="font-bold text-amber-300 text-sm flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-400"></i> Low Stock Warning Items
            </h3>
            <a href="{{ route('inventory.stock-movements.create') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-3 py-1.5 rounded-xl text-xs transition">
                + Record Stock In
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                    <tr>
                        <th class="px-5 py-3.5">Branch</th>
                        <th class="px-5 py-3.5">Material</th>
                        <th class="px-5 py-3.5">Current Stock</th>
                        <th class="px-5 py-3.5">Minimum Stock</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @foreach($lowStockItems as $inv)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5 font-bold text-white">{{ $inv->branch->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-semibold text-slate-200">{{ $inv->material->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-black text-red-400 font-mono">{{ number_format($inv->quantity, 2) }} {{ $inv->material->unit ?? 'units' }}</td>
                        <td class="px-5 py-3.5 text-slate-400 font-mono">{{ number_format($inv->minimum_stock, 2) }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $inv->status_badge_class }}">
                                {{ $inv->status_label }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Recent Stock Movements --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
            <div>
                <h3 class="font-black text-white text-base">Recent Stock Movements</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Audit log of inventory stock-ins and deductions</p>
            </div>
            <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                    <tr>
                        <th class="px-5 py-3.5">Branch</th>
                        <th class="px-5 py-3.5">Material</th>
                        <th class="px-5 py-3.5">Movement</th>
                        <th class="px-5 py-3.5">Quantity</th>
                        <th class="px-5 py-3.5">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($recentMovements as $mov)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5 font-bold text-white">{{ $mov->branch->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-slate-300 font-medium">{{ $mov->material->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $mov->movement_type_badge_class }}">
                                {{ $mov->movement_type_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-black text-cyan-400 font-mono">{{ number_format($mov->quantity, 2) }} {{ $mov->material->unit ?? 'units' }}</td>
                        <td class="px-5 py-3.5 text-slate-400">{{ $mov->movement_date?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">No recent stock movements.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

