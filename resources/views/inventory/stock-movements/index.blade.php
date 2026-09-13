@extends('layouts.internal')
@section('title', 'Stock Movements History')
@section('page-title', 'Stock Movements History')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-500 flex items-center justify-center text-lg shrink-0 shadow-xs">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-cyber-main font-display">Stock Movement Records</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Track all material inflows, outflows, and adjustments across branches.</p>
            </div>
        </div>
        <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.3)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus text-xs"></i> Record Stock Movement
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Quantity</th>
                    <th class="px-6 py-3.5">Reference / Reason</th>
                    <th class="px-6 py-3.5">Recorded By</th>
                    <th class="px-6 py-3.5">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber/60 text-cyber-main">
                @forelse($movements as $m)
                <tr class="hover:bg-cyber-hover/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2 font-bold text-cyber-main">
                            <i class="fa-solid fa-building text-[11px] text-cyber-muted"></i>
                            <span>{{ $m->branch->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-cyber-main block text-xs leading-tight">{{ $m->material->name ?? '—' }}</span>
                        <span class="text-[10px] text-cyber-muted font-medium">{{ ucfirst($m->material->type ?? 'media') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($m->movement_type === 'stock_in')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 shadow-xs">
                                <i class="fa-solid fa-arrow-down text-[9px]"></i> Stock In
                            </span>
                        @elseif($m->movement_type === 'stock_out')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 shadow-xs">
                                <i class="fa-solid fa-arrow-up text-[9px]"></i> Stock Out
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-cyan-100 dark:bg-cyan-500/20 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30 shadow-xs">
                                <i class="fa-solid fa-sliders text-[9px]"></i> {{ ucwords(str_replace('_', ' ', $m->movement_type)) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $qty = (float)$m->quantity;
                        @endphp
                        <div class="flex items-baseline gap-1 text-xs">
                            @if($m->movement_type === 'stock_out')
                                <span class="font-black tabular-nums text-rose-600 dark:text-rose-400">
                                    -{{ $qty }}
                                </span>
                            @else
                                <span class="font-black tabular-nums text-emerald-600 dark:text-emerald-400">
                                    +{{ $qty }}
                                </span>
                            @endif
                            <span class="text-[11px] text-cyber-muted font-medium">{{ $m->material->unit ?? 'units' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-mono text-[10px] font-medium tracking-wide shadow-2xs">
                            {{ $m->reference ?? ($m->reason ?: 'Auto Deduct') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1.5 text-xs text-cyber-muted font-medium">
                            <i class="fa-solid fa-user text-[10px] text-cyan-500"></i>
                            <span>{{ $m->user->name ?? 'System' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-cyber-muted font-medium">
                        {{ $m->movement_date?->format('M d, Y') ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted text-xs">No stock movements recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-cyber/60">{{ $movements->links() }}</div>
    </div>
</div>
@endsection
