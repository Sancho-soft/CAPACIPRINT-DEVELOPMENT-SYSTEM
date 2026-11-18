@extends('layouts.internal')
@section('title', 'Stock Movements History')
@section('page-title', 'Stock Movements History')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-cyber-main font-display tracking-tight">Stock Movement Records</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Track all material inflows, outflows, and adjustments across branches.</p>
            </div>
        </div>
        <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i> Record Stock Movement
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden">
        {{-- Filters & Meta Bar --}}
        <form method="GET" action="{{ route('inventory.stock-movements.index') }}" class="px-5 py-3.5 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub">
            <div class="flex flex-wrap items-center gap-2.5">
                <select name="branch_id" onchange="this.form.submit()" class="rounded-lg border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-brand-600 cursor-pointer">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <select name="type" onchange="this.form.submit()" class="rounded-lg border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-brand-600 cursor-pointer">
                    <option value="">All Movement Types</option>
                    <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                    <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>

                @if(request('branch_id') || request('type'))
                    <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1 font-semibold ml-1">
                        <i class="fa-solid fa-xmark text-[11px]"></i> Reset
                    </a>
                @endif
            </div>
            <div class="text-[11px] font-sans tabular-nums text-cyber-muted tabular-nums">
                Showing <span class="font-bold text-cyber-main">{{ $movements->firstItem() ?? 0 }}–{{ $movements->lastItem() ?? 0 }}</span> of <span class="font-bold text-cyber-main">{{ $movements->total() }}</span> records
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub/60 text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Branch</th>
                        <th class="px-5 py-3">Material</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Quantity</th>
                        <th class="px-5 py-3">Reference / Reason</th>
                        <th class="px-5 py-3">Recorded By</th>
                        <th class="px-5 py-3 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @forelse($movements as $m)
                    <tr class="hover:bg-cyber-sub/40 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-semibold text-cyber-main text-xs">{{ $m->branch->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-cyber-main block text-xs leading-snug">{{ $m->material->name ?? '—' }}</span>
                            @php
                                $matType = strtolower($m->material->type ?? 'media');
                                $typeBadge = match($matType) {
                                    'paper' => 'bg-blue-500/10 text-brand-700 dark:text-blue-400 border-blue-500/25',
                                    'ink'   => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border-indigo-500/25',
                                    'media' => 'bg-amber-500/10 text-amber-800 dark:text-amber-400 border-amber-500/25',
                                    default => 'bg-cyber-sub text-cyber-muted border-cyber',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider border {{ $typeBadge }}">
                                {{ $matType }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($m->movement_type === 'stock_in')
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/25">
                                    <i class="fa-solid fa-arrow-down text-[8px]"></i> Stock In
                                </span>
                            @elseif($m->movement_type === 'stock_out')
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-500/25">
                                    <i class="fa-solid fa-arrow-up text-[8px]"></i> Stock Out
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold bg-blue-500/10 text-brand-700 dark:text-blue-400 border border-blue-500/25">
                                    <i class="fa-solid fa-sliders text-[8px]"></i> {{ ucwords(str_replace('_', ' ', $m->movement_type)) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $qty = (float)$m->quantity;
                            @endphp
                            <div class="flex items-baseline gap-1 text-xs">
                                @if($m->movement_type === 'stock_out')
                                    <span class="font-sans tabular-nums font-bold text-sm text-rose-600 dark:text-rose-400">
                                        -{{ $qty }}
                                    </span>
                                @else
                                    <span class="font-sans tabular-nums font-bold text-sm text-emerald-600 dark:text-emerald-400">
                                        +{{ $qty }}
                                    </span>
                                @endif
                                <span class="text-[11px] text-cyber-muted">{{ $m->material->unit ?? 'units' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-cyber-sub border border-cyber text-cyber-main font-sans tabular-nums text-[11px] font-semibold tracking-wide">
                                {{ $m->reference ?? ($m->reason ?: 'Auto Deduct') }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-semibold text-cyber-main text-xs">{{ $m->user->name ?? 'System' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap text-xs font-sans tabular-nums text-cyber-muted">
                            {{ $m->movement_date?->format('M d, Y') ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted text-xs">No stock movements recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-cyber">{{ $movements->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
