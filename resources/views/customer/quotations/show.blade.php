@extends('layouts.customer')
@section('title', 'Quotation #' . $quotation->quotation_number)
@section('page-title', 'Quotation Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Quotation #{{ $quotation->quotation_number }}</h2>
            <p class="text-sm text-slate-500 mt-1">Generated {{ $quotation->created_at->format('F d, Y') }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $quotation->status_badge_class }}">
            {{ $quotation->status_label }}
        </span>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-navy-900">Price Breakdown</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <tbody class="bg-white divide-y divide-slate-100">
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50 w-1/2">Print Service</td><td class="px-6 py-3 font-bold text-navy-900">{{ $quotation->printRequest->service ?? '—' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Quantity</td><td class="px-6 py-3 text-slate-800">{{ $quotation->printRequest->quantity ?? '—' }} copies</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Base Cost</td><td class="px-6 py-3 text-slate-800">₱{{ number_format($quotation->base_cost, 2) }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Material Cost</td><td class="px-6 py-3 text-slate-800">₱{{ number_format($quotation->material_cost, 2) }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Finishing Cost</td><td class="px-6 py-3 text-slate-800">₱{{ number_format($quotation->finishing_cost, 2) }}</td></tr>
                <tr>
                    <td class="px-6 py-4 font-bold text-navy-900 bg-brand-50 text-sm">Total Price (VAT Inc.)</td>
                    <td class="px-6 py-4 font-black text-xl text-navy-900 bg-brand-50 font-display">₱{{ number_format($quotation->total_price, 2) }}</td>
                </tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Valid Until</td><td class="px-6 py-3 text-slate-800">{{ $quotation->valid_until?->format('F d, Y') ?? 'N/A' }}</td></tr>
            </tbody>
        </table>
    </div>

    @if($quotation->notes)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-5">
        <h3 class="font-bold text-navy-900 mb-2">Notes</h3>
        <p class="text-sm text-slate-600">{{ $quotation->notes }}</p>
    </div>
    @endif

    {{-- Actions --}}
    @if($quotation->status === 'pending')
    <div class="flex gap-4">
        <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="flex-1">
            @csrf
            <button type="submit" onclick="return confirm('Decline this quotation?')"
                    class="w-full border border-slate-200 hover:bg-slate-100 text-slate-700 font-semibold py-3 rounded-xl text-sm transition">
                <i class="fa-solid fa-times mr-2"></i> Decline Quotation
            </button>
        </form>
        <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="flex-1">
            @csrf
            <button type="submit"
                    class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 rounded-xl text-sm transition shadow-lg shadow-brand-500/20">
                <i class="fa-solid fa-check mr-2"></i> Confirm &amp; Proceed to Payment
            </button>
        </form>
    </div>
    @endif

    <div class="flex items-center justify-between">
        <a href="{{ route('customer.quotations.index') }}"
           class="text-sm text-slate-500 hover:text-navy-900 font-medium flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Quotations
        </a>
        <a href="{{ route('customer.quotations.download', $quotation) }}"
           target="_blank" class="text-sm text-brand-500 hover:text-brand-700 font-semibold flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Download
        </a>
    </div>
</div>
@endsection
