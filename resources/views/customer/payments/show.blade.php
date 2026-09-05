@extends('layouts.customer')
@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Payment Details</h2>
            <p class="text-sm text-slate-500 mt-1">Order #{{ $payment->order->order_number ?? '—' }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $payment->status_badge_class }}">
            {{ $payment->status_label }}
        </span>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-navy-900">Payment Information</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <tbody class="bg-white divide-y divide-slate-100">
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50 w-1/3">Order No.</td><td class="px-6 py-3 font-bold text-navy-900">#{{ $payment->order->order_number ?? '—' }}</td></tr>
                <tr>
                    <td class="px-6 py-4 font-bold text-navy-900 bg-brand-50 text-sm">Amount Due</td>
                    <td class="px-6 py-4 font-black text-xl text-navy-900 bg-brand-50 font-display">₱{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Payment Method</td><td class="px-6 py-3 font-bold text-navy-900">{{ $payment->payment_method ?? 'Cash on Pickup' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Payment Reference</td><td class="px-6 py-3 text-slate-800">{{ $payment->payment_reference ?? 'Not yet submitted' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Date Submitted</td><td class="px-6 py-3 text-slate-800">{{ $payment->paid_at?->format('F d, Y h:i A') ?? '—' }}</td></tr>
                @if($payment->notes)<tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Notes</td><td class="px-6 py-3 text-slate-800">{{ $payment->notes }}</td></tr>@endif
            </tbody>
        </table>
    </div>

    @if($payment->status === 'pending')
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6 space-y-4">
        <h3 class="font-bold text-navy-900">Submit Payment Reference</h3>
        <p class="text-sm text-slate-500">After transferring the amount, select your payment method and enter your transaction/reference number below. Our team will verify and confirm.</p>

        <form method="POST" action="{{ route('customer.payments.submit', $payment) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" required
                        class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition bg-white">
                    <option value="Cash on Pickup">Cash on Pickup (Over-the-Counter)</option>
                    <option value="GCash">GCash</option>
                    <option value="Maya">Maya</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Payment Reference / Transaction ID <span class="text-red-500">*</span></label>
                <input type="text" name="payment_reference" required placeholder="e.g. GCash Ref #12345678 or Cash OTC"
                       class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Additional Notes</label>
                <textarea name="notes" rows="2" placeholder="Any additional information..."
                          class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition resize-none"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 rounded-xl text-sm transition shadow">
                <i class="fa-solid fa-paper-plane mr-2"></i> Submit Payment Reference
            </button>
        </form>
    </div>
    @elseif($payment->status === 'submitted')
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-800 flex items-start gap-3">
        <i class="fa-solid fa-clock mt-0.5 shrink-0"></i>
        <p>Your payment reference has been submitted and is awaiting verification by our team. You will be notified once confirmed.</p>
    </div>
    @elseif($payment->status === 'confirmed')
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800 flex items-start gap-3">
        <i class="fa-solid fa-circle-check mt-0.5 shrink-0"></i>
        <p>Payment confirmed! Your order is now being processed for production.</p>
    </div>
    @endif

    <a href="{{ route('customer.payments.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Payments
    </a>
</div>
@endsection
