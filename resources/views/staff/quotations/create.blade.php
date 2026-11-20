@extends('layouts.internal')
@section('title', 'Prepare Quotation')
@section('page-title', 'Prepare New Quotation')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-cyber-card text-cyber-main p-6 sm:p-8 rounded-2xl border border-cyber shadow-sm space-y-6">
        
        {{-- Header with Morning Star Logo & Identity --}}
        <div class="flex items-center justify-between border-b border-cyber-sub pb-5">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/morning-star-logo.png') }}" 
                     alt="Morning Star Logo" 
                     class="h-12 w-12 object-contain bg-white p-1 rounded-xl border border-cyber shrink-0">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-cyber-main font-display tracking-tight">
                        Morning Star Printing Press Co.
                    </h2>
                </div>
            </div>
            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-600/10 px-3 py-1 rounded-full border border-blue-600/20">
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
                return this.total * 0.50;
            },
            get balancePayment() {
                return this.total * 0.50;
            }
        }">
            @csrf

            {{-- Request Selection --}}
            <div>
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">
                    Select Print Request
                </label>
                <select name="print_request_id" 
                        x-model="selectedId" 
                        @change="onRequestChange()"
                        required 
                        class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs text-cyber-main font-medium focus:border-blue-600 focus:outline-none transition">
                    <option value="">-- Choose Client Print Request --</option>
                    @foreach($openRequests as $req)
                    <option value="{{ $req->id }}" {{ (isset($printRequest) && $printRequest->id == $req->id) ? 'selected' : '' }}>
                        #PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }} &middot; {{ $req->user->name ?? 'Customer' }} ({{ $req->service }} &middot; {{ number_format($req->quantity) }} copies)
                    </option>
                    @endforeach
                </select>
            </div>

            @if($pricingRule)
            <div class="p-3.5 bg-blue-600/10 border border-blue-600/20 rounded-xl text-blue-700 dark:text-blue-400 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-blue-600 dark:text-blue-400"></i>
                <span class="tabular-nums">Predefined Rule Applied: <strong>{{ $pricingRule->service }}</strong> (Base: ₱{{ $pricingRule->base_rate }}/pc &middot; Mat: ₱{{ $pricingRule->material_rate }}/pc)</span>
            </div>
            @endif

            {{-- 3-Part Cost Inputs --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Base Cost (₱)</label>
                    <input type="number" step="0.01" name="base_cost" x-model="baseCost" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs font-semibold text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Material Cost (₱)</label>
                    <input type="number" step="0.01" name="material_cost" x-model="materialCost" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs font-semibold text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Finishing Cost (₱)</label>
                    <input type="number" step="0.01" name="finishing_cost" x-model="finishingCost" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs font-semibold text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none transition">
                </div>
            </div>

            {{-- Live Quotation Summary Card (Matches Physical Accounting & 50/50 Terms) --}}
            <div class="p-5 rounded-2xl bg-cyber-sub border border-cyber space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cyber pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-calculator text-base"></i>
                        </div>
                        <div>
                            <span class="font-bold text-cyber-main text-sm block">Total Quoted Price</span>
                            <span class="text-xs text-cyber-muted tabular-nums">Unit Price: <strong class="text-cyber-main" x-text="'₱' + unitPrice.toFixed(2) + ' / copy'">₱0.00 / copy</strong> (Quantity: <span x-text="quantity">1</span> copies)</span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold font-display text-blue-600 dark:text-blue-400 tracking-tight tabular-nums" x-text="'₱' + (parseFloat(total)||0).toFixed(2)">₱0.00</span>
                </div>

                {{-- Official Morning Star Terms Breakdown (50% Downpayment, 50% after completion) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 bg-cyber-card rounded-xl border border-cyber">
                        <span class="text-cyber-muted text-xs font-semibold block">50% Downpayment</span>
                        <span class="text-base font-bold text-cyber-main font-sans tabular-nums mt-0.5 block" x-text="'₱' + downPayment.toFixed(2)">₱0.00</span>
                        <span class="text-[11px] text-cyber-sub block mt-0.5">Required before press floor start</span>
                    </div>
                    <div class="p-3.5 bg-cyber-card rounded-xl border border-cyber">
                        <span class="text-cyber-muted text-xs font-semibold block">50% After Completion</span>
                        <span class="text-base font-bold text-cyber-main font-sans tabular-nums mt-0.5 block" x-text="'₱' + balancePayment.toFixed(2)">₱0.00</span>
                        <span class="text-[11px] text-cyber-sub block mt-0.5">Settled upon claiming / delivery</span>
                    </div>
                </div>
            </div>

            {{-- Valid Until & Notes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Due Date / Valid Until</label>
                    <input type="date" name="valid_until" value="{{ now()->addDays(14)->format('Y-m-d') }}" required
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none transition font-medium">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Special Notes / Conditions</label>
                    <input type="text" name="notes" placeholder="e.g. Includes standard proofing &amp; lamination"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2.5 text-xs text-cyber-main placeholder-cyber-muted focus:border-blue-600 focus:outline-none transition font-medium">
                </div>
            </div>

            {{-- Form Footer Actions --}}
            <div class="pt-4 border-t border-cyber-sub flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-xs text-cyber-muted font-medium">
                    Prepared by: <strong class="text-cyber-main">{{ auth()->user()->name ?? 'Alex Beniga' }}</strong> (Customer Service)
                </span>

                <div class="flex items-center gap-2.5">
                    <a href="{{ route('staff.quotations.index') }}" class="px-4 py-2.5 bg-cyber-sub hover:bg-cyber-card border border-cyber rounded-2xl shadow-sm text-cyber-main font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Create &amp; Send Quotation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection