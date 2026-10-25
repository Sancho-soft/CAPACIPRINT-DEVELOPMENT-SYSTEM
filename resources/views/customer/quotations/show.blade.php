@extends('layouts.customer')
@section('title', 'Quotation #' . $quotation->quotation_number)
@section('page-title', 'Quotation Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Top Minimalist Navigation & Actions Toolbar (Option A: Clean, No Redundancy) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-cyber-card border border-cyber px-5 py-3.5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.quotations.index') }}" class="px-3.5 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main hover:text-cyan-400 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Back to Quotations</span>
            </a>
            <span class="text-cyber-muted/40 text-xs">|</span>
            @php
                $statusColor = match(strtolower($quotation->status)) {
                    'confirmed' => 'text-emerald-500',
                    'pending'   => 'text-amber-500',
                    'rejected', 'declined' => 'text-rose-500',
                    default     => 'text-cyber-muted',
                };
            @endphp
            <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider {{ $statusColor }}">
                <span class="inline-block w-2 h-2 rounded-full bg-current"></span>
                <span>{{ $quotation->status_label }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 w-full sm:w-auto justify-end">
            <a href="{{ route('customer.quotations.download', $quotation) }}" target="_blank" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-cyan-500/20">
                <i class="fa-solid fa-download text-xs"></i>
                <span>Download PDF / Print</span>
            </a>
        </div>
    </div>

    {{-- Official Morning Star Quotation Sheet --}}
    @php
        $qty = max(1, (int)($quotation->printRequest->quantity ?? 1));
        $unitPrice = $quotation->total_price / $qty;
        $downPayment = $quotation->total_price * 0.80;
        $balancePayment = $quotation->total_price * 0.20;
    @endphp

    <div class="bg-white text-slate-900 p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-2xl space-y-7">
        
        {{-- Header: Logo + Company Info (Left) | QUOTATION (Right) --}}
        <div class="flex items-start justify-between gap-4 border-b border-slate-900 pb-5">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/morning-star-logo.png') }}" 
                     alt="Morning Star Printing Press Co." 
                     class="h-16 w-16 sm:h-20 sm:w-20 object-contain shrink-0">
                <div>
                    <h1 class="text-lg sm:text-2xl font-black text-slate-950 tracking-tight font-display uppercase leading-tight">
                        MORNING STAR
                    </h1>
                    <h2 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider">
                        PRINTING PRESS CO.
                    </h2>
                    <p class="text-[11px] text-slate-700 font-medium mt-1">G/F La Fontana Bldg., Bolton Street, Davao City</p>
                    <p class="text-[11px] text-slate-700 font-medium">315-1064 &bull; 0917 685 0444 &bull; 0933 859 8980</p>
                </div>
            </div>

            <div class="text-right shrink-0">
                <h2 class="text-2xl sm:text-4xl font-black text-slate-950 font-display tracking-tight uppercase">
                    QUOTATION
                </h2>
                <span class="inline-block mt-1 font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded border border-slate-300">
                    #{{ $quotation->quotation_number }}
                </span>
            </div>
        </div>

        {{-- Customer & Transaction Meta (Clean Softened Underlines) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3.5 text-xs">
            <div class="space-y-3">
                <div class="flex items-baseline">
                    <span class="font-bold text-slate-900 w-32 shrink-0">Customer Name:</span>
                    <span class="border-b border-slate-300 flex-1 font-semibold text-slate-950 px-2 min-h-[22px]">
                        {{ $quotation->user->name ?? 'Valued Customer' }}
                    </span>
                </div>
                <div class="flex items-baseline">
                    <span class="font-bold text-slate-900 w-32 shrink-0">Address:</span>
                    <span class="border-b border-slate-300 flex-1 text-slate-800 px-2 min-h-[22px]">
                        {{ $quotation->user->address ?? 'Davao City' }}
                    </span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-baseline">
                    <span class="font-bold text-slate-900 w-28 shrink-0">Date:</span>
                    <span class="border-b border-slate-300 flex-1 font-semibold text-slate-950 px-2 min-h-[22px]">
                        {{ $quotation->created_at->format('F d, Y') }}
                    </span>
                </div>
                <div class="flex items-baseline">
                    <span class="font-bold text-slate-900 w-28 shrink-0">Contact No.:</span>
                    <span class="border-b border-slate-300 flex-1 text-slate-800 px-2 min-h-[22px]">
                        {{ $quotation->user->phone ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Accounting Table (Seamless & Compact, No Artificial Void) --}}
        <div class="overflow-hidden border border-slate-900 rounded-lg">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 font-bold uppercase text-[11px] text-slate-900 border-b border-slate-900">
                        <th class="py-2.5 px-3 w-[15%] text-center border-r border-slate-900">Quantity</th>
                        <th class="py-2.5 px-4 w-[55%] border-r border-slate-900">Description</th>
                        <th class="py-2.5 px-3 w-[15%] text-right border-r border-slate-900">Unit Price</th>
                        <th class="py-2.5 px-4 w-[15%] text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr class="align-top">
                        <td class="py-3 px-3 text-center font-bold text-slate-950 border-r border-slate-900">
                            {{ number_format($qty) }} {{ $qty > 1 ? 'pcs' : 'pc' }}
                        </td>
                        <td class="py-3 px-4 space-y-1 border-r border-slate-900">
                            <span class="font-black text-sm text-slate-950 block">
                                {{ $quotation->printRequest->service ?? 'Custom Print Service' }}
                            </span>
                            <div class="text-[11px] text-slate-700 space-y-0.5">
                                <p><strong>Size:</strong> {{ $quotation->printRequest->size ?? 'Standard Specification' }}</p>
                                <p><strong>Material:</strong> {{ $quotation->printRequest->material ?? 'Standard Media' }}</p>
                                @if($quotation->printRequest->finishing)
                                    <p><strong>Finishing / Binding:</strong> {{ $quotation->printRequest->finishing }}</p>
                                @endif
                                @if($quotation->notes)
                                    <p class="italic text-slate-600 mt-1"><strong>Special Notes:</strong> {{ $quotation->notes }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-900 border-r border-slate-900">
                            ₱{{ number_format($unitPrice, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-950">
                            ₱{{ number_format($quotation->total_price, 2) }}
                        </td>
                    </tr>

                    {{-- Total Summary Row directly connected --}}
                    <tr class="font-bold text-sm bg-slate-50 border-t border-slate-900">
                        <td colspan="2" class="py-2.5 px-4 text-slate-600 text-xs italic border-r border-slate-900">
                            Official pricing inclusive of standard materials, pre-press setup &amp; processing.
                        </td>
                        <td class="py-2.5 px-3 text-right uppercase tracking-wider font-black text-slate-900 border-r border-slate-900">
                            Total:
                        </td>
                        <td class="py-2.5 px-4 text-right font-black font-mono text-base text-slate-950">
                            ₱{{ number_format($quotation->total_price, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Terms & Sign-off --}}
        <div class="pt-2 flex flex-col sm:flex-row items-start justify-between gap-8 text-xs">
            <div class="space-y-2 min-w-[280px]">
                <div>
                    <span class="font-black text-slate-950 uppercase tracking-wider underline block mb-1">Terms:</span>
                    <div class="pl-4 space-y-1 text-slate-800 font-medium">
                        <p>: 80% down payment <span class="font-bold text-slate-950 font-mono">(₱{{ number_format($downPayment, 2) }})</span></p>
                        <p>: 20% after completion <span class="font-bold text-slate-950 font-mono">(₱{{ number_format($balancePayment, 2) }})</span></p>
                    </div>
                </div>
                
                <div class="pt-2 flex items-baseline gap-2">
                    <span class="font-bold text-slate-950">Due Date:</span>
                    <span class="border-b border-slate-300 px-2 font-bold text-slate-950 min-w-[160px]">
                        {{ $quotation->valid_until ? $quotation->valid_until->format('F d, Y') : now()->addDays(7)->format('F d, Y') }}
                    </span>
                </div>
            </div>

            <div class="text-center sm:text-right shrink-0 w-full sm:w-auto">
                <p class="font-bold text-slate-900 mb-8 text-left sm:text-right">Prepared by:</p>
                <div class="inline-block text-center min-w-[220px]">
                    <div class="border-b border-slate-900 pb-1">
                        <span class="font-bold text-slate-950 block text-xs">Alex Beniga</span>
                    </div>
                    <span class="font-black text-slate-950 uppercase tracking-wider text-[11px] block mt-1">
                        Customer Service
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Customer Confirmation Actions --}}
    @if($quotation->status === 'pending')
    <div class="bg-cyber-card border border-cyber p-6 rounded-2xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h4 class="font-black text-cyber-main text-sm font-display">Confirm &amp; Proceed with this Quotation</h4>
            <p class="text-xs text-cyber-muted mt-0.5">Upon confirmation, your order is routed to production and initial 80% down payment invoice is generated.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}">
                @csrf
                <button type="submit" onclick="return confirm('Decline this quotation?')"
                        class="px-4 py-2.5 border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-red-400 font-bold rounded-xl text-xs transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Decline
                </button>
            </form>
            <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}">
                @csrf
                <button type="submit"
                        class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-cyan-500/20">
                    <i class="fa-solid fa-check mr-1.5"></i> Confirm &amp; Pay Down Payment
                </button>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection