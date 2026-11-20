@extends('layouts.customer')
@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-cyber-main font-display">Order #{{ $order->order_number }}</h2>
            <p class="text-sm text-cyber-muted mt-1">Created {{ $order->created_at->format('F d, Y') }}</p>
        </div>
        <a href="{{ route('customer.orders.tracking', $order) }}"
           class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot"></i> Track Order
        </a>
    </div>

    {{-- Ready for Pickup QR Claim Card --}}
    @if($order->claimReference || in_array($order->status, ['ready_for_pickup', 'completed', 'claimed']))
    <div class="bg-cyber-card text-cyber-main rounded-2xl p-6 shadow-sm border border-cyber flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3 py-1 bg-blue-600/15 text-blue-600 dark:text-blue-400 border border-blue-600/25 text-[11px] font-semibold rounded-full">Order Ready for Collection</span>
            <h3 class="text-xl font-bold font-display text-cyber-main">Digital Claim QR Pass</h3>
            <p class="text-xs text-cyber-muted">Present this QR code or claim code at <strong class="text-cyber-main">{{ $order->assigned_branch ?? 'Branch' }}</strong> to collect your printed order.</p>
            <div class="pt-2">
                <span class="text-xs text-cyber-muted font-medium block">Claim Reference Code</span>
                <span class="text-2xl font-bold font-sans tabular-nums tracking-wide text-blue-600 dark:text-blue-400">{{ $order->claimReference->claim_code ?? ('CLM-' . strtoupper(substr($order->order_number, -6))) }}</span>
            </div>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-cyber shadow-sm shrink-0 text-center">
            <canvas id="qr-code-order" class="h-32 w-32"></canvas>
            <span class="text-[11px] font-semibold text-cyber-muted block mt-1.5">Scan at Counter</span>
        </div>
    </div>
    @endif

    {{-- Status + Payment --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 shadow-sm">
            <p class="text-xs font-semibold text-cyber-muted">Order Status</p>
            <span class="inline-block mt-2 text-xs font-bold uppercase
                {{ match($order->status) {
                    'submitted'   => 'text-blue-600 dark:text-blue-400',
                    'quotation'   => 'text-amber-600 dark:text-amber-400',
                    'production'  => 'text-blue-600 dark:text-blue-400',
                    'completed'   => 'text-emerald-600 dark:text-emerald-400',
                    default       => 'text-cyber-muted',
                } }}">{{ $order->status_label }}</span>
        </div>
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 shadow-sm">
            <p class="text-xs font-semibold text-cyber-muted">Payment Status</p>
            <span class="inline-block mt-2 px-3 py-1 text-xs font-bold rounded-full uppercase
                {{ match($order->payment_status) {
                    'confirmed' => 'bg-green-100 text-green-800',
                    'submitted' => 'bg-blue-100 text-blue-800',
                    'rejected'  => 'bg-red-100 text-red-800',
                    default     => 'bg-amber-100 text-amber-800',
                } }}">{{ $order->payment_status_label }}</span>
        </div>
    </div>

    {{-- Print Job Info --}}
    @if($order->printRequest)
    <div class="bg-cyber-card border border-cyber rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-cyber-sub border-b border-cyber">
            <h3 class="font-bold text-cyber-main">Print Job Details</h3>
        </div>
        <table class="min-w-full divide-y divide-cyber-sub text-sm tabular-nums">
            <tbody class="bg-cyber-card divide-y divide-cyber-sub">
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60 w-1/3">Service</td><td class="px-6 py-3 font-bold text-cyber-main">{{ $order->printRequest->service }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Quantity</td><td class="px-6 py-3">{{ $order->printRequest->quantity }} copies</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Size / Material</td><td class="px-6 py-3">{{ $order->printRequest->size }} &middot; {{ $order->printRequest->material }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Finishing</td><td class="px-6 py-3">{{ $order->printRequest->finishing }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Deadline</td><td class="px-6 py-3">{{ $order->printRequest->deadline?->format('F d, Y') ?? '—' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Assigned Branch</td><td class="px-6 py-3 font-semibold text-brand-700">{{ $order->assigned_branch ?? 'Pending routing...' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-cyber-muted bg-cyber-sub/60">Est. Completion</td><td class="px-6 py-3">{{ $order->estimated_completion?->format('F d, Y') ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Quotation Summary --}}
    @if($order->quotation)
    <div class="bg-cyber-card border border-cyber rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-cyber-muted">Quotation</p>
                <p class="font-bold text-cyber-main mt-0.5">#{{ $order->quotation->formatted_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-black text-cyber-main font-display">₱{{ number_format($order->quotation->total_price, 2) }}</p>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $order->quotation->status_badge_class }}">{{ $order->quotation->status_label }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-cyber">
            <a href="{{ route('customer.quotations.show', $order->quotation) }}"
               class="text-sm text-brand-500 hover:text-brand-700 font-semibold">View Quotation &rarr;</a>
        </div>
    </div>
    @endif

    {{-- Payment Summary --}}
    @if($order->payment)
    <div class="bg-cyber-card border border-cyber rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-cyber-muted">Payment</p>
                <p class="font-bold text-cyber-main mt-0.5">
                    {{ str_replace([' (Over-the-Counter)', ' on Pickup'], '', $order->payment->payment_method ?? 'Cash') }}
                    @if($order->payment->display_reference !== '—')
                        &middot; Ref: <span class="font-sans tabular-nums text-xs">{{ $order->payment->display_reference }}</span>
                    @endif
                </p>
            </div>
            <div class="text-right">
                <p class="text-xl font-black text-cyber-main font-display">₱{{ number_format($order->payment->amount, 2) }}</p>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $order->payment->status_badge_class }}">{{ $order->payment->status_label }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-cyber">
            <a href="{{ route('customer.payments.show', $order->payment) }}"
               class="text-sm text-brand-500 hover:text-brand-700 font-semibold">View Payment &rarr;</a>
        </div>
    </div>
    @endif

    <a href="{{ route('customer.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm text-cyber-muted hover:text-cyber-main font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to My Orders
    </a>
</div>
@endsection

@section('scripts')
@if($order->claimReference || in_array($order->status, ['ready_for_pickup', 'completed', 'claimed']))
<script src="{{ asset('assets/js/qrious.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new QRious({
            element: document.getElementById('qr-code-order'),
            value: '{{ $order->claimReference->claim_code ?? $order->order_number }}',
            size: 140,
            level: 'H'
        });
    });
</script>
@endif
@endsection
