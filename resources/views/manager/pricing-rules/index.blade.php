@extends('layouts.internal')
@section('title', 'Pricing Calculation Rules Matrix')
@section('page-title', 'Pricing Calculation Rules Matrix')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ showCreateModal: false }">

    {{-- Page Header --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#0E3386]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-[#0E3386]/15 border border-[#0E3386]/30 text-[#0E3386] dark:text-sky-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Pricing Calculation Rules Matrix</h2>
                    <p class="text-xs text-cyber-muted mt-1">Manage standard baseline rates, substrate costs, and finishing pricing multipliers across the network.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <button @click="showCreateModal = true" 
                        class="bg-[#0E3386] hover:bg-[#1442a8] text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-[#0E3386]/25 transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i> Add Pricing Rule
                </button>
            </div>
        </div>
    </div>

    {{-- Rules Table Card --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub/50">
            <h3 class="font-bold text-cyber-main text-xs uppercase tracking-wider">Automated Pricing Multipliers</h3>
            <span class="text-xs text-cyber-muted font-medium">{{ $rules->total() }} Rules Active</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-cyber-sub text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Service Type</th>
                        <th class="px-6 py-3.5">Paper / Dimension Size</th>
                        <th class="px-6 py-3.5">Base Rate</th>
                        <th class="px-6 py-3.5">Material Rate</th>
                        <th class="px-6 py-3.5">Finishing Rate</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber text-cyber-main">
                    @forelse($rules as $r)
                    <tr class="hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4 font-bold text-cyber-main">
                            <span class="text-sm font-semibold">{{ $r->service }}</span>
                        </td>
                        <td class="px-6 py-4 text-cyber-muted font-mono font-medium">{{ $r->size ?? 'Standard / Custom' }}</td>
                        <td class="px-6 py-4 font-mono font-bold text-cyber-main">₱{{ number_format($r->base_rate, 2) }}</td>
                        <td class="px-6 py-4 font-mono font-bold text-cyber-main">₱{{ number_format($r->material_rate, 2) }}</td>
                        <td class="px-6 py-4 font-mono font-bold text-cyber-main">₱{{ number_format($r->finishing_rate, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $r->is_active ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400' : 'bg-slate-500/15 text-slate-600 dark:text-slate-400' }}">
                                {{ $r->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('manager.pricing-rules.update', $r) }}" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="base_rate" value="{{ $r->base_rate }}">
                                <input type="hidden" name="material_rate" value="{{ $r->material_rate }}">
                                <input type="hidden" name="finishing_rate" value="{{ $r->finishing_rate }}">
                                <input type="hidden" name="is_active" value="{{ $r->is_active ? 0 : 1 }}">
                                <button type="submit" class="px-3 py-1 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main rounded-lg font-bold text-[10px] transition cursor-pointer">
                                    {{ $r->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-cyber-muted text-xs">No pricing rules found. Create your first rule above.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rules->hasPages())
            <div class="px-6 py-4 border-t border-cyber bg-cyber-sub/40">
                {{ $rules->links() }}
            </div>
        @endif
    </div>

    {{-- Create Modal --}}
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" 
         x-cloak>
        <div @click.outside="showCreateModal = false" 
             class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 max-w-lg w-full shadow-2xl space-y-5 animate-fade-in-up">
            
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="text-base font-black text-cyber-main font-display flex items-center gap-2">
                    <i class="fa-solid fa-plus text-[#0E3386] dark:text-sky-400"></i> New Pricing Calculation Rule
                </h3>
                <button @click="showCreateModal = false" class="text-cyber-muted hover:text-cyber-main text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('manager.pricing-rules.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Service Type</label>
                    <input type="text" name="service" required placeholder="e.g. Tarpaulin, Bookbinding, Vinyl Sticker" 
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Paper / Dimension Size</label>
                    <input type="text" name="size" placeholder="e.g. A4, A3, 2x3 ft, Standard" 
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-4 py-2.5 text-xs text-cyber-main placeholder-cyber-muted/60 focus:border-[#0E3386] focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Base Rate (₱)</label>
                        <input type="number" step="0.01" name="base_rate" required placeholder="0.00" 
                               class="w-full rounded-xl border border-cyber bg-cyber-sub px-3 py-2 text-xs font-mono text-cyber-main focus:border-[#0E3386] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Material (₱)</label>
                        <input type="number" step="0.01" name="material_rate" required placeholder="0.00" 
                               class="w-full rounded-xl border border-cyber bg-cyber-sub px-3 py-2 text-xs font-mono text-cyber-main focus:border-[#0E3386] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-muted uppercase tracking-wider mb-1.5">Finishing (₱)</label>
                        <input type="number" step="0.01" name="finishing_rate" required placeholder="0.00" 
                               class="w-full rounded-xl border border-cyber bg-cyber-sub px-3 py-2 text-xs font-mono text-cyber-main focus:border-[#0E3386] focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-cyber">
                    <button type="button" @click="showCreateModal = false" 
                            class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main text-xs font-bold transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-2 rounded-xl bg-[#0E3386] hover:bg-[#1442a8] text-white text-xs font-bold shadow-md shadow-[#0E3386]/20 transition">
                        Save Rule
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
