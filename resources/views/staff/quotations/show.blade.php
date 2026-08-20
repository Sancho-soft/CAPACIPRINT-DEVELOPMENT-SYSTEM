@extends('layouts.internal')
@section('title', 'Quotation Details')
@section('page-title', 'Quotation #' . $quotation->quotation_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-navy-900 font-display">Quotation #{{ $quotation->quotation_number }}</h2>
            <p class="text-xs text-slate-500">Prepared for {{ $quotation->user->name ?? 'Customer' }} &middot; Created {{ $quotation->created_at->format('M d, Y') }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $quotation->status_badge_class }}">
            {{ $quotation->status_label }}
        </span>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4 text-xs">
        <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-2">Price Breakdown</h3>
        <div class="space-y-2">
            <div class="flex justify-between"><span>Base Printing Cost:</span><span class="font-bold text-slate-800">₱{{ number_format($quotation->base_cost, 2) }}</span></div>
            <div class="flex justify-between"><span>Material Cost:</span><span class="font-bold text-slate-800">₱{{ number_format($quotation->material_cost, 2) }}</span></div>
            <div class="flex justify-between"><span>Finishing &amp; Services:</span><span class="font-bold text-slate-800">₱{{ number_format($quotation->finishing_cost, 2) }}</span></div>
            <div class="flex justify-between border-t border-slate-200 pt-2 text-sm">
                <span class="font-bold text-navy-900">Total Price:</span>
                <span class="font-black text-navy-900 text-base">₱{{ number_format($quotation->total_price, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <a href="{{ route('staff.quotations.index') }}" class="text-xs font-semibold text-slate-500">&larr; Back to Quotations</a>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('staff.quotations.send', $quotation) }}">
                @csrf
                <button type="submit" class="bg-brand-50 hover:bg-brand-100 text-brand-600 font-bold px-4 py-2 rounded-xl text-xs">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Resend to Customer
                </button>
            </form>
            <a href="{{ route('staff.quotations.download', $quotation) }}" target="_blank" class="bg-navy-900 text-white font-bold px-4 py-2 rounded-xl text-xs">
                <i class="fa-solid fa-download mr-1"></i> Download PDF / Print
            </a>
        </div>
    </div>
</div>
@endsection
