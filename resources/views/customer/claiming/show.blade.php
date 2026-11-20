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
        <h2 class="text-2xl font-bold text-cyber-main font-display tracking-tight">QR Claim Reference</h2>
        <p class="text-sm text-cyber-muted mt-1">Present this screen or print to claim your order.</p>
    </div>

    {{-- Claim Card --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden print-card-box">
        {{-- Header --}}
        <div class="bg-slate-900 dark:bg-[#101319] border-b border-cyber px-6 py-5 text-center">
            <img src="{{ asset('images/caplogo.png') }}" class="h-12 w-auto mx-auto brightness-0 invert mb-2" alt="CAPACIPRINT">
            <p class="text-blue-400 text-xs font-semibold">Claiming Reference</p>
        </div>

        {{-- QR Code (via QRious) --}}
        <div class="flex justify-center py-6 bg-cyber-sub/40 border-b border-cyber-sub">
            <div class="p-4 border border-cyber rounded-xl bg-white shadow-sm">
                <canvas id="qr-code-claim" class="w-44 h-44"></canvas>
            </div>
        </div>

        {{-- Claim Info --}}
        <div class="px-6 py-5 space-y-3">
            <div class="text-center">
                <p class="text-xs font-semibold text-cyber-muted">Claim Code</p>
                <p class="text-2xl font-black text-cyber-main font-display tracking-wider tabular-nums mt-0.5">{{ $claim->claim_code }}</p>
            </div>

            <div class="border-t border-cyber-sub pt-4 space-y-2.5 text-sm">
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Customer</span>
                    <span class="font-semibold text-cyber-main text-right">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Order No.</span>
                    <span class="font-semibold text-cyber-main tabular-nums text-right">#{{ $claim->order->order_number }}</span>
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Service</span>
                    <span class="font-semibold text-cyber-main text-right">{{ $claim->order->printRequest->service ?? '—' }}</span>
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Pickup Branch</span>
                    <span class="font-semibold text-cyber-main text-right">{{ $claim->pickup_branch ?? '—' }}</span>
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Completion</span>
                    <span class="font-semibold text-cyber-main tabular-nums text-right">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</span>
                </div>
                <div class="flex justify-between gap-3">
                    <span class="text-cyber-muted font-medium">Payment Method</span>
                    <span class="font-semibold text-cyber-main text-right">{{ $claim->order->payment->payment_method ?? 'Cash on Pickup' }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="text-cyber-muted font-medium">Status</span>
                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full {{ $claim->is_claimed ? 'bg-slate-500/15 text-cyber-muted border border-cyber' : 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30' }}">
                        {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                    </span>
                </div>
            </div>
        </div>

        @if(!$claim->is_claimed)
        <div class="bg-emerald-500/10 border-t border-emerald-500/20 px-6 py-4 text-center">
            <p class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Show this QR code and a valid ID to the branch staff to claim your order.
            </p>
        </div>
        @endif
    </div>

    {{-- Print button --}}
    <button onclick="window.print()"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-2 cursor-pointer print:hidden">
        <i class="fa-solid fa-print"></i> Print Claim Reference
    </button>

    <a href="{{ route('customer.claiming.index') }}"
       class="block text-center text-sm text-cyber-muted hover:text-cyber-main font-medium print:hidden">
        <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Claims
    </a>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/qrious.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new QRious({
            element: document.getElementById('qr-code-claim'),
            value: '{{ $claim->claim_code }}',
            size: 180,
            level: 'H'
        });
    });
</script>
@endsection
