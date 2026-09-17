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
        {{-- Filters & Meta Bar --}}
        <form method="GET" action="{{ route('inventory.stock.index') }}" class="px-6 py-3.5 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub/20">
            <div class="flex flex-wrap items-center gap-2.5">
                <select name="branch_id" onchange="this.form.submit()" class="rounded-xl border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-cyan-500 cursor-pointer">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="rounded-xl border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-cyan-500 cursor-pointer">
                    <option value="">All Stock Statuses</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>

                @if(request('branch_id') || request('status'))
                    <a href="{{ route('inventory.stock.index') }}" class="text-xs text-rose-500 hover:underline flex items-center gap-1 font-semibold ml-1">
                        <i class="fa-solid fa-xmark text-[10px]"></i> Reset
                    </a>
                @endif
            </div>
            <div class="text-[11px] text-cyber-muted font-medium">
                Showing <span class="font-bold text-cyber-main">{{ $inventory->firstItem() ?? 0 }}–{{ $inventory->lastItem() ?? 0 }}</span> of <span class="font-bold text-cyber-main">{{ $inventory->total() }}</span> items
            </div>
        </form>

        <div class="overflow-x-auto">
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
                            <span class="font-bold text-cyber-main text-xs">{{ $inv->branch->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-cyber-main block text-xs leading-snug">{{ $inv->material->name ?? '—' }}</span>
                            @php
                                $matType = strtolower($inv->material->type ?? 'media');
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
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30">
                                        <i class="fa-solid fa-circle-xmark text-[8px]"></i> Out of Stock
                                    </span>
                                @elseif($qty <= $min)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30">
                                        <i class="fa-solid fa-triangle-exclamation text-[8px]"></i> Low
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-cyber-muted tabular-nums">min {{ (float)$inv->minimum_stock }} {{ $inv->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <form method="POST" action="{{ route('inventory.stock.update', $inv) }}" 
                                  x-data="{ 
                                      qty: {{ (float)$inv->quantity }},
                                      initialQty: {{ (float)$inv->quantity }},
                                      step: 1,
                                      decrement() { 
                                          this.qty = Math.max(0, Math.round((parseFloat(this.qty || 0) - this.step) * 10) / 10); 
                                      },
                                      increment() { 
                                          this.qty = Math.round((parseFloat(this.qty || 0) + this.step) * 10) / 10; 
                                      }
                                  }" 
                                  class="inline-flex items-center justify-end">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="minimum_stock" value="{{ $inv->minimum_stock }}">

                                <div class="inline-flex items-stretch rounded-xl border border-cyber bg-cyber-sub shadow-xs overflow-hidden transition-all focus-within:border-cyan-500 focus-within:ring-2 focus-within:ring-cyan-500/20">
                                    <!-- Decrement -->
                                    <button type="button" 
                                            @click="decrement()"
                                            class="px-2.5 py-1.5 text-cyber-muted hover:text-cyber-main hover:bg-slate-200/80 dark:hover:bg-slate-800/80 transition flex items-center justify-center cursor-pointer select-none text-[10px]"
                                            title="Decrease by 1">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <!-- Value Input -->
                                    <input type="number" 
                                           name="quantity" 
                                           x-model="qty"
                                           step="0.1" 
                                           min="0" 
                                           class="w-16 bg-transparent text-center font-bold text-xs tabular-nums text-cyber-main border-x border-cyber focus:outline-none px-1 py-1.5 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                    <!-- Increment -->
                                    <button type="button" 
                                            @click="increment()"
                                            class="px-2.5 py-1.5 text-cyber-muted hover:text-cyber-main hover:bg-slate-200/80 dark:hover:bg-slate-800/80 transition flex items-center justify-center cursor-pointer select-none text-[10px]"
                                            title="Increase by 1">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>

                                    <!-- Save Button -->
                                    <button type="submit" 
                                            class="px-3.5 py-1.5 font-bold text-xs transition-all flex items-center gap-1.5 cursor-pointer border-l border-cyber shrink-0"
                                            :class="qty != initialQty 
                                                ? 'bg-cyan-500 hover:bg-cyan-400 text-slate-950 shadow-[0_0_14px_rgba(6,182,212,0.5)] font-extrabold' 
                                                : 'bg-[#0E3386] hover:bg-[#1442a8] text-white dark:bg-cyan-500 dark:hover:bg-cyan-400 dark:text-slate-950 shadow-xs'">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span>Save</span>
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-cyber-muted text-xs">No inventory entries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-cyber/60">{{ $inventory->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
