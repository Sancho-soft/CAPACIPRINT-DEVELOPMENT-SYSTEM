@extends('layouts.customer')
@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Order #{{ $order->order_number }}</h2>
            <p class="text-sm text-slate-500 mt-1">Created {{ $order->created_at->format('F d, Y') }}</p>
        </div>
        <a href="{{ route('customer.orders.tracking', $order) }}"
           class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fa-solid fa-map-location-dot"></i> Track Order
        </a>
    </div>

    {{-- Ready for Pickup QR Claim Card --}}
    @if($order->claimReference || in_array($order->status, ['ready_for_pickup', 'completed', 'claimed']))
    <div class="bg-gradient-to-br from-navy-900 via-navy-950 to-slate-900 text-white rounded-2xl p-6 shadow-xl border border-navy-800 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3 py-1 bg-brand-500 text-white text-[10px] font-extrabold uppercase rounded-full tracking-wider">Order Ready for Collection</span>
            <h3 class="text-xl font-bold font-display text-white">Digital Claim QR Pass</h3>
            <p class="text-xs text-slate-300">Present this QR code or claim code at <strong>{{ $order->assigned_branch ?? 'Branch' }}</strong> to collect your printed order.</p>
            <div class="pt-2">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Claim Reference Code:</span>
                <span class="text-2xl font-black font-mono tracking-widest text-brand-400">{{ $order->claimReference->claim_code ?? ('CLM-' . strtoupper(substr($order->order_number, -6))) }}</span>
            </div>
        </div>
        <div class="bg-white p-3 rounded-2xl shadow-lg shrink-0 text-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($order->claimReference->claim_code ?? $order->order_number) }}" alt="QR Code" class="h-32 w-32">
            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest block mt-1">Scan at Counter</span>
        </div>
    </div>
    @endif

    {{-- Status + Payment --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Order Status</p>
            <span class="inline-block mt-2 px-3 py-1 text-xs font-bold rounded-full uppercase
                {{ match($order->status) {
                    'submitted'   => 'bg-blue-100 text-blue-800',
                    'quotation'   => 'bg-amber-100 text-amber-800',
                    'production'  => 'bg-cyan-100 text-cyan-800',
                    'completed'   => 'bg-green-100 text-green-800',
                    default       => 'bg-slate-100 text-slate-600',
                } }}">{{ $order->status_label }}</span>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Payment Status</p>
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
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-navy-900">Print Job Details</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <tbody class="bg-white divide-y divide-slate-100">
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50 w-1/3">Service</td><td class="px-6 py-3 font-bold text-navy-900">{{ $order->printRequest->service }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Quantity</td><td class="px-6 py-3">{{ $order->printRequest->quantity }} copies</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Size / Material</td><td class="px-6 py-3">{{ $order->printRequest->size }} &middot; {{ $order->printRequest->material }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Finishing</td><td class="px-6 py-3">{{ $order->printRequest->finishing }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Deadline</td><td class="px-6 py-3">{{ $order->printRequest->deadline?->format('F d, Y') ?? '—' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Assigned Branch</td><td class="px-6 py-3 font-semibold text-brand-700">{{ $order->assigned_branch ?? 'Pending routing...' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Est. Completion</td><td class="px-6 py-3">{{ $order->estimated_completion?->format('F d, Y') ?? '—' }}</td></tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Quotation Summary --}}
    @if($order->quotation)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Quotation</p>
                <p class="font-bold text-navy-900 mt-0.5">#{{ $order->quotation->quotation_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-black text-navy-900 font-display">₱{{ number_format($order->quotation->total_price, 2) }}</p>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $order->quotation->status_badge_class }}">{{ $order->quotation->status_label }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <a href="{{ route('customer.quotations.show', $order->quotation) }}"
               class="text-sm text-brand-500 hover:text-brand-700 font-semibold">View Quotation &rarr;</a>
        </div>
    </div>
    @endif

    {{-- Payment Summary --}}
    @if($order->payment)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Payment</p>
                <p class="font-bold text-navy-900 mt-0.5">Ref: {{ $order->payment->payment_reference ?? 'Not submitted' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-black text-navy-900 font-display">₱{{ number_format($order->payment->amount, 2) }}</p>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $order->payment->status_badge_class }}">{{ $order->payment->status_label }}</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <a href="{{ route('customer.payments.show', $order->payment) }}"
               class="text-sm text-brand-500 hover:text-brand-700 font-semibold">View Payment &rarr;</a>
        </div>
    </div>
    @endif

    <a href="{{ route('customer.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to My Orders
    </a>
</div>
@endsection
