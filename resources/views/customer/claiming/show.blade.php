@extends('layouts.customer')
@section('title', 'Claim Reference — Order #' . $claim->order->order_number)
@section('page-title', 'QR Claim Reference')

@section('content')
<style>
@media print {
    /* Hide layout, navigation bar, sidebars, headers, titles, buttons, and links when printing */
    aside, header, nav, footer, .print\:hidden, button, a {
        display: none !important;
    }
    body, html, main {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .print-card-wrapper {
        margin: 1rem auto !important;
        max-width: 420px !important;
        width: 100% !important;
    }
    .print-card-box {
        border: 2px solid #0f172a !important;
        box-shadow: none !important;
        border-radius: 1rem !important;
        background: #ffffff !important;
        color: #0f172a !important;
    }
}
</style>

<div class="max-w-sm mx-auto space-y-6 print-card-wrapper">

    <div class="text-center print:hidden">
        <h2 class="text-2xl font-bold text-navy-900 font-display">QR Claim Reference</h2>
        <p class="text-sm text-slate-500 mt-1">Present this screen or print to claim your order.</p>
    </div>

    {{-- Claim Card --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-lg overflow-hidden print-card-box">
        {{-- Header --}}
        <div class="bg-navy-800 px-6 py-5 text-center">
            <img src="{{ asset('images/caplogo.png') }}" class="h-12 w-auto mx-auto brightness-0 invert mb-2" alt="CAPACIPRINT">
            <p class="text-brand-400 text-xs font-bold tracking-widest uppercase">Claiming Reference</p>
        </div>

        {{-- QR Code (via API) --}}
        <div class="flex justify-center py-6 bg-white">
            <div class="p-4 border-2 border-slate-100 rounded-xl bg-white shadow-inner">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($claim->claim_code) }}"
                     alt="QR Claim Code" class="w-44 h-44">
            </div>
        </div>

        {{-- Claim Info --}}
        <div class="px-6 pb-6 space-y-3">
            <div class="text-center">
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Claim Code</p>
                <p class="text-2xl font-black text-navy-900 font-display tracking-widest mt-0.5">{{ $claim->claim_code }}</p>
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Customer</span>
                    <span class="font-bold text-navy-900">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Order No.</span>
                    <span class="font-bold text-navy-900">#{{ $claim->order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Service</span>
                    <span class="font-bold text-navy-900">{{ $claim->order->printRequest->service ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Pickup Branch</span>
                    <span class="font-bold text-navy-900">{{ $claim->pickup_branch ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Completion</span>
                    <span class="font-bold text-navy-900">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Payment Method</span>
                    <span class="font-bold text-navy-900">{{ $claim->order->payment->payment_method ?? 'Cash on Pickup' }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="text-slate-500 font-medium">Status</span>
                    <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-full uppercase {{ $claim->is_claimed ? 'bg-slate-100 text-slate-600' : 'bg-teal-100 text-teal-700' }}">
                        {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                    </span>
                </div>
            </div>
        </div>

        @if(!$claim->is_claimed)
        <div class="bg-teal-50 border-t border-teal-100 px-6 py-4 text-center">
            <p class="text-xs text-teal-700 font-semibold">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Show this QR code and a valid ID to the branch staff to claim your order.
            </p>
        </div>
        @endif
    </div>

    {{-- Print button --}}
    <button onclick="window.print()"
            class="w-full border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-3 rounded-xl text-sm transition flex items-center justify-center gap-2 print:hidden">
        <i class="fa-solid fa-print"></i> Print Claim Reference
    </button>

    <a href="{{ route('customer.claiming.index') }}"
       class="block text-center text-sm text-slate-500 hover:text-navy-900 font-medium print:hidden">
        <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Claims
    </a>
</div>
@endsection
