@extends('layouts.internal')
@section('title', 'Pricing Rules Matrix')
@section('page-title', 'Pricing Rules Matrix')

@section('content')
<div class="space-y-6 max-w-7xl" x-data="{ showCreateModal: false }">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-cyber-main font-display">Pricing Calculation Rules</h2>
            <p class="text-xs text-cyber-muted">Configure base rates, material rates, and finishing rates for print quotation automation.</p>
        </div>
        <button @click="showCreateModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-sm transition flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus"></i> Add Pricing Rule
        </button>
    </div>

    {{-- Rules Table --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-semibold border-b border-cyber text-xs">
                <tr>
                    <th class="px-6 py-3.5">Service Type</th>
                    <th class="px-6 py-3.5">Paper Size</th>
                    <th class="px-6 py-3.5">Base Rate</th>
                    <th class="px-6 py-3.5">Material Rate</th>
                    <th class="px-6 py-3.5">Finishing Rate</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub">
                @forelse($rules as $r)
                <tr class="hover:bg-cyber-sub/60 transition">
                    <td class="px-6 py-4 font-bold text-cyber-main">{{ $r->service }}</td>
                    <td class="px-6 py-4 text-cyber-muted font-semibold">{{ $r->size ?? 'Standard' }}</td>
                    <td class="px-6 py-4 font-bold text-cyber-main tabular-nums">₱{{ number_format($r->base_rate, 2) }}</td>
                    <td class="px-6 py-4 font-bold text-cyber-main tabular-nums">₱{{ number_format($r->material_rate, 2) }}</td>
                    <td class="px-6 py-4 font-bold text-cyber-main tabular-nums">₱{{ number_format($r->finishing_rate, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $r->is_active ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30' : 'bg-slate-500/10 text-cyber-muted border-cyber' }}">
                            {{ $r->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('staff.pricing-rules.update', $r) }}" class="inline-flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="base_rate" value="{{ $r->base_rate }}">
                            <input type="hidden" name="material_rate" value="{{ $r->material_rate }}">
                            <input type="hidden" name="finishing_rate" value="{{ $r->finishing_rate }}">
                            <input type="hidden" name="is_active" value="{{ $r->is_active ? 0 : 1 }}">
                            <button type="submit" class="px-3 py-1.5 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main rounded-xl font-semibold text-xs transition cursor-pointer">
                                {{ $r->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted">No pricing rules found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Create Modal --}}
    <div x-show="showCreateModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overscroll-contain" x-cloak>
        <div class="bg-cyber-card border border-cyber rounded-2xl p-6 max-w-md w-full shadow-sm space-y-4 text-cyber-main">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main text-base">New Pricing Rule</h3>
                <button type="button" @click="showCreateModal = false" aria-label="Close modal" title="Close modal" class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main flex items-center justify-center transition cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('staff.pricing-rules.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-cyber-main mb-1">Service</label>
                    <input type="text" name="service" required placeholder="e.g. Document Printing" class="w-full rounded-xl bg-cyber-sub border border-cyber p-2.5 text-xs text-cyber-main font-medium focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-cyber-main mb-1">Size</label>
                    <input type="text" name="size" placeholder="e.g. A4, A3, 3x4 ft" class="w-full rounded-xl bg-cyber-sub border border-cyber p-2.5 text-xs text-cyber-main font-medium focus:border-blue-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-main mb-1">Base Rate (₱)</label>
                        <input type="number" step="0.01" name="base_rate" required value="5.00" class="w-full rounded-xl bg-cyber-sub border border-cyber p-2 text-xs text-cyber-main font-medium focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-main mb-1">Material Rate (₱)</label>
                        <input type="number" step="0.01" name="material_rate" required value="2.50" class="w-full rounded-xl bg-cyber-sub border border-cyber p-2 text-xs text-cyber-main font-medium focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-cyber-main mb-1">Finishing Rate (₱)</label>
                        <input type="number" step="0.01" name="finishing_rate" required value="1.00" class="w-full rounded-xl bg-cyber-sub border border-cyber p-2 text-xs text-cyber-main font-medium focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main rounded-xl text-xs font-semibold transition cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition cursor-pointer">Save Rule</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
