@extends('layouts.internal')
@section('title', 'Pricing Rules Matrix')
@section('page-title', 'Pricing Rules Matrix')

@section('content')
<div class="space-y-6 max-w-7xl" x-data="{ showCreateModal: false }">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-navy-900 font-display">Pricing Calculation Rules</h2>
            <p class="text-xs text-slate-500">Configure base rates, material rates, and finishing rates for print quotation automation.</p>
        </div>
        <button @click="showCreateModal = true" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-brand-500/20 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add Pricing Rule
        </button>
    </div>

    {{-- Rules Table --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
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
            <tbody class="divide-y divide-slate-100">
                @forelse($rules as $r)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $r->service }}</td>
                    <td class="px-6 py-4 text-slate-600 font-semibold">{{ $r->size ?? 'Standard' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">₱{{ number_format($r->base_rate, 2) }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">₱{{ number_format($r->material_rate, 2) }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">₱{{ number_format($r->finishing_rate, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $r->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
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
                            <button type="submit" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-[10px]">
                                {{ $r->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">No pricing rules found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create Modal --}}
    <div x-show="showCreateModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-navy-900 text-base">New Pricing Rule</h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="POST" action="{{ route('staff.pricing-rules.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Service</label>
                    <input type="text" name="service" required placeholder="e.g. Document Printing" class="w-full rounded-xl border border-slate-200 p-2.5 text-xs font-medium focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Size</label>
                    <input type="text" name="size" placeholder="e.g. A4, A3, 3x4 ft" class="w-full rounded-xl border border-slate-200 p-2.5 text-xs font-medium focus:border-brand-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 mb-1">Base Rate (₱)</label>
                        <input type="number" step="0.01" name="base_rate" required value="5.00" class="w-full rounded-xl border border-slate-200 p-2 text-xs font-medium focus:border-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 mb-1">Material Rate (₱)</label>
                        <input type="number" step="0.01" name="material_rate" required value="2.50" class="w-full rounded-xl border border-slate-200 p-2 text-xs font-medium focus:border-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 mb-1">Finishing Rate (₱)</label>
                        <input type="number" step="0.01" name="finishing_rate" required value="1.00" class="w-full rounded-xl border border-slate-200 p-2 text-xs font-medium focus:border-brand-500 focus:outline-none">
                    </div>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-500 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-500/20">Save Rule</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
