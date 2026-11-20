@extends('layouts.internal')
@section('title', 'Branch Stock Levels')
@section('page-title', 'Branch Inventory & Stock Levels')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-cyber-main font-display tracking-tight">Branch Stock Levels &amp; Buffer Limits</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Real-time physical inventory counts and threshold monitoring per branch.</p>
            </div>
        </div>
        <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition flex items-center gap-2 shrink-0 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i> Record Movement
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        {{-- Filters & Meta Bar --}}
        <form method="GET" action="{{ route('inventory.stock.index') }}" class="px-5 py-3.5 border-b border-cyber flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-cyber-sub">
            <div class="flex flex-wrap items-center gap-2.5">
                <select name="branch_id" onchange="this.form.submit()" class="rounded-lg border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-brand-600 cursor-pointer">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-cyber bg-cyber-card text-cyber-main text-xs font-semibold px-3 py-1.5 focus:outline-none focus:border-brand-600 cursor-pointer">
                    <option value="">All Stock Statuses</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>

                @if(request('branch_id') || request('status'))
                    <a href="{{ route('inventory.stock.index') }}" class="text-xs text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1 font-semibold ml-1">
                        <i class="fa-solid fa-xmark text-[11px]"></i> Reset
                    </a>
                @endif
            </div>
            <div class="text-[11px] font-sans tabular-nums text-cyber-muted tabular-nums">
                Showing <span class="font-bold text-cyber-main">{{ $inventory->firstItem() ?? 0 }}–{{ $inventory->lastItem() ?? 0 }}</span> of <span class="font-bold text-cyber-main">{{ $inventory->total() }}</span> items
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub/60 text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Branch</th>
                        <th class="px-5 py-3">Material</th>
                        <th class="px-5 py-3">Current Stock</th>
                        <th class="px-5 py-3">Safety Buffer</th>
                        <th class="px-5 py-3 text-right">Quick Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @forelse($inventory as $inv)
                    <tr class="hover:bg-cyber-sub/40 transition-colors">
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-semibold text-cyber-main text-xs">{{ $inv->branch->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-cyber-main block text-xs leading-snug">{{ $inv->material->name ?? '—' }}</span>
                            @php
                                $matType = strtolower($inv->material->type ?? 'media');
                            @endphp
                            <span class="text-xs font-semibold uppercase tracking-wider block mt-0.5 {{ match($matType) {
                                'paper' => 'text-blue-600 dark:text-blue-400',
                                'ink'   => 'text-indigo-600 dark:text-indigo-400',
                                'media' => 'text-amber-600 dark:text-amber-400',
                                default => 'text-cyber-muted',
                            } }}">
                                {{ $matType }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $qty = (float)$inv->quantity;
                                $min = (float)$inv->minimum_stock;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="font-sans tabular-nums font-bold text-sm {{ $qty <= 0 ? 'text-rose-600 dark:text-rose-400' : ($qty <= $min ? 'text-amber-600 dark:text-amber-400' : 'text-cyber-main') }}">
                                    {{ $qty }}
                                </span>
                                <span class="text-xs text-cyber-muted">{{ $inv->material->unit ?? 'units' }}</span>

                                @if($qty <= 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-500/25">
                                        <i class="fa-solid fa-circle-xmark text-[11px]"></i> Out of Stock
                                    </span>
                                @elseif($qty <= $min)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold bg-amber-500/10 text-amber-800 dark:text-amber-400 border border-amber-500/25">
                                        <i class="fa-solid fa-triangle-exclamation text-[11px]"></i> Low
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-sans tabular-nums font-semibold text-cyber-muted">min {{ (float)$inv->minimum_stock }} {{ $inv->material->unit ?? 'units' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
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

                                <div class="inline-flex items-stretch rounded-lg border border-cyber bg-cyber-sub overflow-hidden transition-colors focus-within:border-brand-600">
                                    <button type="button" 
                                            @click="decrement()"
                                            aria-label="Decrease stock quantity"
                                            class="px-2.5 py-1.5 text-cyber-muted hover:text-cyber-main hover:bg-cyber-card transition flex items-center justify-center cursor-pointer select-none text-[11px]"
                                            title="Decrease by 1">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <input type="number" 
                                           name="quantity" 
                                           x-model="qty"
                                           step="0.1" 
                                           min="0" 
                                           class="w-16 bg-transparent text-center font-sans tabular-nums font-bold text-xs text-cyber-main border-x border-cyber focus:outline-none px-1 py-1.5 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                    <button type="button" 
                                            @click="increment()"
                                            aria-label="Increase stock quantity"
                                            class="px-2.5 py-1.5 text-cyber-muted hover:text-cyber-main hover:bg-cyber-card transition flex items-center justify-center cursor-pointer select-none text-[11px]"
                                            title="Increase by 1">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>

                                    <button type="submit" 
                                            class="px-3.5 py-1.5 font-semibold text-xs transition-colors flex items-center gap-1.5 cursor-pointer border-l border-cyber shrink-0"
                                            :class="qty != initialQty 
                                                ? 'bg-emerald-600 hover:bg-emerald-700 text-white' 
                                                : 'bg-brand-600 hover:bg-brand-700 text-white'">
                                        <i class="fa-solid fa-check text-[11px]"></i>
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
        <div class="p-4 border-t border-cyber">{{ $inventory->appends(request()->query())->links() }}</div>
    </div>
</div>
@endsection
