@extends('layouts.internal')
@section('title', 'Branch Stock Levels')
@section('page-title', 'Branch Inventory & Stock Levels')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-500 flex items-center justify-center text-lg shrink-0 shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-cyber-main font-display">Branch Stock Levels &amp; Buffer Limits</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Real-time physical inventory counts and threshold monitoring per branch.</p>
            </div>
        </div>
        <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.3)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus text-xs"></i> Record Movement
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Current Stock</th>
                    <th class="px-6 py-3.5">Safety Buffer</th>
                    <th class="px-6 py-3.5 text-right">Quick Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber/60 text-cyber-main">
                @forelse($inventory as $inv)
                <tr class="hover:bg-cyber-hover/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2 font-bold text-cyber-main">
                            <i class="fa-solid fa-building text-[11px] text-cyber-muted"></i>
                            <span>{{ $inv->branch->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-cyber-main block text-xs">{{ $inv->material->name ?? '—' }}</span>
                        <span class="text-[10px] text-cyber-muted font-medium">{{ ucfirst($inv->material->type ?? 'media') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $qty = (float)$inv->quantity;
                            $min = (float)$inv->minimum_stock;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="font-black tabular-nums text-sm {{ $qty <= 0 ? 'text-rose-600 dark:text-rose-400' : ($qty <= $min ? 'text-amber-600 dark:text-amber-400' : 'text-cyber-main') }}">
                                {{ $qty }}
                            </span>
                            <span class="text-xs text-cyber-muted font-medium">{{ $inv->material->unit ?? 'units' }}</span>

                            @if($qty <= 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-2xs">
                                    <i class="fa-solid fa-circle-xmark text-[8px]"></i> Out of Stock
                                </span>
                            @elseif($qty <= $min)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30 shadow-2xs">
                                    <i class="fa-solid fa-triangle-exclamation text-[8px]"></i> Low
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-semibold text-cyber-muted tabular-nums">min {{ (float)$inv->minimum_stock }} {{ $inv->material->unit ?? 'units' }}</span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <form method="POST" action="{{ route('inventory.stock.update', $inv) }}" class="inline-flex items-center justify-end gap-2">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ (float)$inv->quantity }}" step="0.1" min="0" 
                                   class="w-20 rounded-xl bg-cyber-sub border border-cyber px-2.5 py-1 text-xs font-bold text-right text-cyber-main focus:outline-none focus:border-cyan-400">
                            <input type="hidden" name="minimum_stock" value="{{ $inv->minimum_stock }}">
                            <button type="submit" class="bg-cyan-500/15 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 font-bold px-3 py-1 rounded-xl text-xs border border-cyan-500/30 hover:border-cyan-500 transition shadow-xs cursor-pointer">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-cyber-muted text-xs">No inventory entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-cyber/60">{{ $inventory->links() }}</div>
    </div>
</div>
@endsection
