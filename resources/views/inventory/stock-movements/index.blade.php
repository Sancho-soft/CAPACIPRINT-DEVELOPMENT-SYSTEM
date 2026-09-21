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
        {{-- Filters & Meta Bar --}}
        <form method="GET" action="{{ route('inventory.stock-movements.index') }}" class="px-6 py-3.5 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub/20">
            <div class="flex flex-wrap items-center gap-2.5">
                <select name="branch_id" onchange="this.form.submit()" class="rounded-xl border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-cyan-500 cursor-pointer">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <select name="type" onchange="this.form.submit()" class="rounded-xl border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-cyan-500 cursor-pointer">
                    <option value="">All Movement Types</option>
                    <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                    <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>

                @if(request('branch_id') || request('type'))
                    <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs text-rose-500 hover:underline flex items-center gap-1 font-semibold ml-1">
                        <i class="fa-solid fa-xmark text-[10px]"></i> Reset
                    </a>
                @endif
            </div>
            <div class="text-[11px] text-cyber-muted font-medium">
                Showing <span class="font-bold text-cyber-main">{{ $movements->firstItem() ?? 0 }}–{{ $movements->lastItem() ?? 0 }}</span> of <span class="font-bold text-cyber-main">{{ $movements->total() }}</span> records
            </div>
        </form>

        <div class="overflow-x-auto">
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
                            <span class="font-bold text-cyber-main text-xs">{{ $m->branch->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-cyber-main block text-xs leading-snug">{{ $m->material->name ?? '—' }}</span>
                            @php
                                $matType = strtolower($m->material->type ?? 'media');
                                $typeBadge = match($matType) {
                                    'paper' => 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/30',
                                    'ink'   => 'bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/30',
                                    'media' => 'bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-500/30',
                                    default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/30',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-md text-[9px] font-bold uppercase tracking-wider border {{ $typeBadge }}">
                                {{ $matType }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($m->movement_type === 'stock_in')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 shadow-2xs">
                                    <i class="fa-solid fa-arrow-down text-[8px]"></i> Stock In
                                </span>
                            @elseif($m->movement_type === 'stock_out')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-500/30 shadow-2xs">
                                    <i class="fa-solid fa-arrow-up text-[8px]"></i> Stock Out
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 border border-cyan-500/30 shadow-2xs">
                                    <i class="fa-solid fa-sliders text-[8px]"></i> {{ ucwords(str_replace('_', ' ', $m->movement_type)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $qty = (float)$m->quantity;
                            @endphp
                            <div class="flex items-baseline gap-1 text-xs">
                                @if($m->movement_type === 'stock_out')
                                    <span class="font-black tabular-nums text-sm text-rose-600 dark:text-rose-400">
                                        -{{ $qty }}
                                    </span>
                                @else
                                    <span class="font-black tabular-nums text-sm text-emerald-600 dark:text-emerald-400">
                                        +{{ $qty }}
                                    </span>
                                @endif
                                <span class="text-[11px] text-cyber-muted font-medium">{{ $m->material->unit ?? 'units' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-cyber-sub border border-cyber text-cyber-main font-mono text-[11px] font-semibold tracking-wide shadow-2xs">
                                {{ $m->reference ?? ($m->reason ?: 'Auto Deduct') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-cyber-main text-xs">{{ $m->user->name ?? 'System' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-cyber-muted font-medium tabular-nums">
                            {{ $m->movement_date?->format('M d, Y') ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted text-xs">No stock movements recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-cyber/60">{{ $movements->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
