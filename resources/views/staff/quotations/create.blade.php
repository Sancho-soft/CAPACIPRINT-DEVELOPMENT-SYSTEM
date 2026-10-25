@extends('layouts.internal')
@section('title', 'Prepare Quotation')
@section('page-title', 'Prepare New Quotation')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white text-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        
        {{-- Header with Morning Star Logo & Identity --}}
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/morning-star-logo.png') }}" 
                     alt="Morning Star Logo" 
                     class="h-12 w-12 object-contain shrink-0">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-950 font-display uppercase tracking-tight">
                        Morning Star Printing Press Co.
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">Prepare Official Quotation for Client Print Request</p>
                </div>
            </div>
            <span class="text-xs font-black uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                Quotation Form
            </span>
        </div>

        @php
            $selectedQty = $printRequest->quantity ?? 1;
            $reqMap = [];
            foreach ($openRequests as $req) {
                $reqMap[$req->id] = [
                    'quantity' => $req->quantity ?: 1,
                    'service'  => $req->service,
                    'customer' => $req->user->name ?? 'Customer',
                    'size'     => $req->size ?? 'Standard',
                    'material' => $req->material ?? 'Standard',
                ];
            }
        @endphp

        <form method="POST" action="{{ route('staff.quotations.store') }}" class="space-y-6 text-xs" x-data="{
            requests: {{ json_encode($reqMap) }},
            selectedId: '{{ $printRequest->id ?? '' }}',
            quantity: {{ $selectedQty }},
            baseCost: {{ $pricingRule ? $pricingRule->base_rate * $selectedQty : 0 }},
            materialCost: {{ $pricingRule ? $pricingRule->material_rate * $selectedQty : 0 }},
            finishingCost: {{ $pricingRule ? $pricingRule->finishing_rate * $selectedQty : 0 }},
            onRequestChange() {
                if (this.selectedId && this.requests[this.selectedId]) {
                    this.quantity = this.requests[this.selectedId].quantity || 1;
                }
            },
            get total() { 
                return (parseFloat(this.baseCost)||0) + (parseFloat(this.materialCost)||0) + (parseFloat(this.finishingCost)||0); 
            },
            get unitPrice() {
                const q = parseInt(this.quantity) || 1;
                return q > 0 ? (this.total / q) : this.total;
            },
            get downPayment() {
                return this.total * 0.80;
            },
            get balancePayment() {
                return this.total * 0.20;
            }
        }">
            @csrf

            {{-- Request Selection --}}
            <div>
                <label class="block font-bold text-slate-800 mb-1.5">
                    Select Print Request <span class="text-red-500">*</span>
                </label>
                <select name="print_request_id" 
                        x-model="selectedId" 
                        @change="onRequestChange()"
                        required 
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:outline-none transition">
                    <option value="">-- Choose Client Print Request --</option>
                    @foreach($openRequests as $req)
                    <option value="{{ $req->id }}" {{ (isset($printRequest) && $printRequest->id == $req->id) ? 'selected' : '' }}>
                        #PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }} &middot; {{ $req->user->name ?? 'Customer' }} ({{ $req->service }} &middot; {{ number_format($req->quantity) }} copies)
                    </option>
                    @endforeach
                </select>
            </div>

            @if($pricingRule)
            <div class="p-3.5 bg-blue-50/70 border border-blue-100 rounded-xl text-blue-900 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-blue-500"></i>
                <span>Predefined Rule Applied: <strong>{{ $pricingRule->service }}</strong> (Base: ₱{{ $pricingRule->base_rate }}/pc &middot; Mat: ₱{{ $pricingRule->material_rate }}/pc)</span>
            </div>
            @endif

            {{-- 3-Part Cost Inputs --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Base Cost (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="base_cost" x-model="baseCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold text-slate-900 focus:border-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Material Cost (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="material_cost" x-model="materialCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold text-slate-900 focus:border-blue-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Finishing Cost (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="finishing_cost" x-model="finishingCost" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold text-slate-900 focus:border-blue-500 focus:outline-none transition">
                </div>
            </div>

            {{-- Live Quotation Summary Card (Matches Physical Accounting & 80/20 Terms) --}}
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-calculator text-base"></i>
                        </div>
                        <div>
                            <span class="font-black text-slate-900 text-sm block">Total Quoted Price</span>
                            <span class="text-[11px] text-slate-500">Unit Price: <strong class="text-slate-800" x-text="'₱' + unitPrice.toFixed(2) + ' / copy'">₱0.00 / copy</strong> (Quantity: <span x-text="quantity">1</span> copies)</span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-black font-display text-blue-600 tracking-tight" x-text="'₱' + (parseFloat(total)||0).toFixed(2)">₱0.00</span>
                </div>

                {{-- Official Morning Star Terms Breakdown --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Official Terms: Down Payment (80%)</span>
                        <span class="text-base font-black text-slate-900 font-mono" x-text="'₱' + downPayment.toFixed(2)">₱0.00</span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Required before press floor start</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Official Terms: Upon Completion (20%)</span>
                        <span class="text-base font-black text-slate-900 font-mono" x-text="'₱' + balancePayment.toFixed(2)">₱0.00</span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Settled upon claiming / delivery</span>
                    </div>
                </div>
            </div>

            {{-- Valid Until & Notes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Due Date / Valid Until <span class="text-red-500">*</span></label>
                    <input type="date" name="valid_until" value="{{ now()->addDays(14)->format('Y-m-d') }}" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-blue-500 focus:outline-none transition font-medium">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Special Notes / Conditions</label>
                    <input type="text" name="notes" placeholder="e.g. Includes standard proofing &amp; lamination"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-blue-500 focus:outline-none transition font-medium">
                </div>
            </div>

            {{-- Form Footer Actions --}}
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[11px] text-slate-500 font-medium">
                    Prepared by: <strong class="text-slate-800">{{ auth()->user()->name ?? 'Alex Beniga' }}</strong> (Customer Service)
                </span>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('staff.quotations.index') }}" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 rounded-xl text-slate-600 font-bold transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2.5 rounded-xl shadow-md shadow-blue-600/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Create &amp; Send Quotation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection