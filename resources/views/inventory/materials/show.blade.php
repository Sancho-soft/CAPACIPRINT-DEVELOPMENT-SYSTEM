@extends('layouts.internal')
@section('title', 'Material Details — ' . $material->name)
@section('page-title', 'Material Specification & Stock Allocation')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Header Banner --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('inventory.materials.index') }}" 
                   class="h-11 w-11 rounded-2xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main flex items-center justify-center transition shadow-sm shrink-0" 
                   title="Back to Materials Catalog">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">{{ $material->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 font-mono">
                            {{ $material->type_label }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $material->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-slate-500/10 text-slate-400 border border-slate-500/30' }} font-mono">
                            {{ $material->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1">
                        Base Stocking Unit: <strong class="text-cyber-main">{{ $material->unit }}</strong> &middot; Registered {{ $material->created_at ? $material->created_at->format('M d, Y') : 'Recently' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('inventory.materials.edit', $material) }}" class="px-3.5 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 border border-amber-500/30 font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-xs"></i> Edit Material
                </a>
                <a href="{{ route('inventory.stock-movements.create') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Record Movement
                </a>
            </div>
        </div>
    </div>

    {{-- Description Box (if present) --}}
    @if($material->description)
        <div class="bg-cyber-card border border-cyber rounded-2xl p-5 shadow-sm">
            <h4 class="text-xs font-black uppercase tracking-wider text-cyber-muted mb-1">Specifications & Description</h4>
            <p class="text-xs text-cyber-main leading-relaxed">{{ $material->description }}</p>
        </div>
    @endif

    {{-- Multi-Branch Stock Allocation Table --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Multi-Branch Inventory Balances</h3>
                <p class="text-[11px] text-cyber-muted mt-0.5">Physical stocking levels and safety buffers across all facilities</p>
            </div>
            <span class="text-xs font-mono font-bold text-cyber-main">
                Total Available: <span class="text-cyan-400">{{ $material->branchInventory->sum('quantity') }}</span> {{ $material->unit }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Branch Location</th>
                        <th class="px-6 py-3.5">Current Balance</th>
                        <th class="px-6 py-3.5">Safety Buffer Minimum</th>
                        <th class="px-6 py-3.5">Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($material->branchInventory as $inv)
                        @php
                            $statusClass = match($inv->status) {
                                'available'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                'low_stock'   => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                'out_of_stock'=> 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                default       => 'bg-slate-500/10 text-slate-400 border-slate-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-cyber-sub/40 transition">
                            <td class="px-6 py-4 font-bold text-cyber-main">
                                {{ $inv->branch->name ?? 'Branch' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-cyber-main text-sm">
                                {{ number_format($inv->quantity, 2) }} <span class="text-xs font-normal text-cyber-muted">{{ $material->unit }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-cyber-muted">
                                {{ number_format($inv->minimum_stock, 2) }} {{ $material->unit }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider border {{ $statusClass }} font-mono">
                                    {{ str_replace('_', ' ', $inv->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-cyber-muted">No branch inventory allocations initialized yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Material Stock Movements --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Movement History</h3>
                <p class="text-[11px] text-cyber-muted mt-0.5">Audit log of replenishments and press floor auto-deductions</p>
            </div>
            <a href="{{ route('inventory.stock-movements.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                All Logs <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Timestamp</th>
                        <th class="px-6 py-3.5">Branch</th>
                        <th class="px-6 py-3.5">Movement Type</th>
                        <th class="px-6 py-3.5">Quantity</th>
                        <th class="px-6 py-3.5">Reference / Notes</th>
                        <th class="px-6 py-3.5">Recorded By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($recentMovements as $mv)
                        @php
                            $mvBadge = match($mv->movement_type) {
                                'stock_in'   => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                'stock_out'  => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                default      => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-cyber-sub/40 transition">
                            <td class="px-6 py-4 font-mono text-cyber-muted text-[11px]">
                                {{ $mv->created_at ? $mv->created_at->format('M d, Y H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-cyber-main">
                                {{ $mv->branch->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border {{ $mvBadge }} font-mono">
                                    {{ str_replace('_', ' ', $mv->movement_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold whitespace-nowrap {{ $mv->movement_type === 'stock_out' ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $mv->movement_type === 'stock_out' ? '-' : '+' }}{{ $mv->quantity }} {{ $material->unit }}
                            </td>
                            <td class="px-6 py-4 font-mono text-cyber-sub text-[11px] truncate max-w-[160px]">
                                {{ $mv->reference ?? ($mv->reason ?: 'Auto Deduction') }}
                            </td>
                            <td class="px-6 py-4 text-cyber-muted">
                                {{ $mv->user->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-cyber-muted">No stock movements recorded for this material yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
