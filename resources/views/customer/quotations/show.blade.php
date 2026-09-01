@extends('layouts.customer')
@section('title', 'Quotation #' . $quotation->quotation_number)
@section('page-title', 'Quotation Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-cyber-main font-display">Quotation #{{ $quotation->quotation_number }}</h2>
            <p class="text-sm text-cyber-muted mt-1">Generated {{ $quotation->created_at->format('F d, Y') }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-black rounded-lg uppercase tracking-wider {{ $quotation->status_badge_class }}">
            {{ $quotation->status_label }}
        </span>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 bg-cyber-sub/40 border-b border-cyber">
            <h3 class="font-bold text-cyber-main">Price Breakdown</h3>
        </div>
        <table class="min-w-full divide-y divide-cyber text-sm">
            <tbody class="divide-y divide-cyber">
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30 w-1/2">Print Service</td><td class="px-6 py-3.5 font-bold text-cyber-main">{{ $quotation->printRequest->service ?? '—' }}</td></tr>
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30">Quantity</td><td class="px-6 py-3.5 text-cyber-main font-medium">{{ $quotation->printRequest->quantity ?? '—' }} copies</td></tr>
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30">Base Cost</td><td class="px-6 py-3.5 text-cyber-main font-medium">₱{{ number_format($quotation->base_cost, 2) }}</td></tr>
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30">Material Cost</td><td class="px-6 py-3.5 text-cyber-main font-medium">₱{{ number_format($quotation->material_cost, 2) }}</td></tr>
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30">Finishing Cost</td><td class="px-6 py-3.5 text-cyber-main font-medium">₱{{ number_format($quotation->finishing_cost, 2) }}</td></tr>
                <tr class="bg-cyan-500/10">
                    <td class="px-6 py-4 font-black text-cyber-main text-sm">Total Price (VAT Inc.)</td>
                    <td class="px-6 py-4 font-black text-2xl text-cyan-400 font-display">₱{{ number_format($quotation->total_price, 2) }}</td>
                </tr>
                <tr class="bg-cyber-card"><td class="px-6 py-3.5 font-bold text-cyber-muted bg-cyber-sub/30">Valid Until</td><td class="px-6 py-3.5 text-cyber-main font-medium">{{ $quotation->valid_until?->format('F d, Y') ?? 'N/A' }}</td></tr>
            </tbody>
        </table>
    </div>

    @if($quotation->notes)
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl p-6">
        <h3 class="font-bold text-cyber-main mb-2">Notes &amp; Special Handling</h3>
        <p class="text-xs text-cyber-muted leading-relaxed">{{ $quotation->notes }}</p>
    </div>
    @endif

    {{-- Actions --}}
    @if($quotation->status === 'pending')
    <div class="flex gap-4">
        <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="flex-1">
            @csrf
            <button type="submit" onclick="return confirm('Decline this quotation?')"
                    class="w-full border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-red-400 font-bold py-3 rounded-xl text-xs uppercase tracking-wider transition">
                <i class="fa-solid fa-times mr-1.5"></i> Decline Quotation
            </button>
        </form>
        <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="flex-1">
            @csrf
            <button type="submit"
                    class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                <i class="fa-solid fa-check mr-1.5"></i> Confirm &amp; Proceed to Payment
            </button>
        </form>
    </div>
    @endif

    <div class="flex items-center justify-between pt-2">
        <a href="{{ route('customer.quotations.index') }}" class="text-xs text-cyber-muted hover:text-cyan-400 font-bold flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Quotations
        </a>
        <a href="{{ route('customer.quotations.download', $quotation) }}"
           target="_blank" class="text-xs text-cyber-muted hover:text-cyan-400 font-bold flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Download PDF
        </a>
    </div>
</div>
@endsection
