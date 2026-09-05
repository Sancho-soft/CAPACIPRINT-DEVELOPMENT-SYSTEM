@extends('layouts.internal')
@section('title', 'Prepare Quotation')
@section('page-title', 'Prepare New Quotation')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">Prepare Quotation</h2>
            <p class="text-xs text-slate-500">Calculate pricing using predefined rules for customer request.</p>
        </div>

        <form method="POST" action="{{ route('staff.quotations.store') }}" class="space-y-5 text-xs" x-data="{
            baseCost: {{ $pricingRule ? $pricingRule->base_rate * ($printRequest->quantity ?? 1) : 0 }},
            materialCost: {{ $pricingRule ? $pricingRule->material_rate * ($printRequest->quantity ?? 1) : 0 }},
            finishingCost: {{ $pricingRule ? $pricingRule->finishing_rate * ($printRequest->quantity ?? 1) : 0 }},
            get total() { return (parseFloat(this.baseCost)||0) + (parseFloat(this.materialCost)||0) + (parseFloat(this.finishingCost)||0); }
        }">
            @csrf

            <div>
                <label class="block font-bold text-navy-900 mb-1">Select Print Request <span class="text-red-500">*</span></label>
                <select name="print_request_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                    <option value="">-- Choose Request --</option>
                    @foreach($openRequests as $req)
                    <option value="{{ $req->id }}" {{ (isset($printRequest) && $printRequest->id == $req->id) ? 'selected' : '' }}>
                        #PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }} - {{ $req->user->name ?? 'Customer' }} ({{ $req->service }} - {{ $req->quantity }} copies)
                    </option>
                    @endforeach
                </select>
            </div>

            @if($pricingRule)
            <div class="p-3 bg-brand-50/50 border border-brand-100 rounded-xl text-brand-800">
                <i class="fa-solid fa-calculator mr-1"></i> Predefined Rule Applied: <strong>{{ $pricingRule->service }}</strong> (Base: ₱{{ $pricingRule->base_rate }}/pc &middot; Mat: ₱{{ $pricingRule->material_rate }}/pc)
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Base Cost (₱)</label>
                    <input type="number" step="0.01" name="base_cost" x-model="baseCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Material Cost (₱)</label>
                    <input type="number" step="0.01" name="material_cost" x-model="materialCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Finishing Cost (₱)</label>
                    <input type="number" step="0.01" name="finishing_cost" x-model="finishingCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold focus:border-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="p-4 sm:p-5 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2.5">
                    <div class="h-9 w-9 rounded-xl bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-calculator text-base"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-sm block">Total Quoted Price</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Sum of Base, Material &amp; Finishing costs</span>
                    </div>
                </div>
                <span class="text-2xl sm:text-3xl font-black font-display text-cyan-600 dark:text-cyan-400 tracking-tight" x-text="'₱' + (parseFloat(total)||0).toFixed(2)">₱0.00</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Valid Until <span class="text-red-500">*</span></label>
                    <input type="date" name="valid_until" value="{{ now()->addDays(14)->format('Y-m-d') }}" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Special Notes / Conditions</label>
                    <input type="text" name="notes" placeholder="e.g. Includes standard proofing"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('staff.quotations.index') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 font-bold">Cancel</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Create &amp; Send Quotation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
