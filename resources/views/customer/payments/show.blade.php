@extends('layouts.customer')
@section('title', 'Payment Details - ' . ($payment->order->order_number ?? 'Transaction'))
@section('page-title', 'Payment Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display tracking-tight">
                Payment Details
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Transaction Voucher &amp; Settlement Record
            </p>
        </div>
        <div class="self-start sm:self-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm
                {{ match($payment->status) {
                    'confirmed', 'verified' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/50',
                    'submitted'             => 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800/50',
                    'pending'               => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/50',
                    'rejected'              => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800/50',
                    default                 => 'bg-slate-50 text-slate-700 border border-slate-200 dark:bg-slate-900 dark:text-slate-400 dark:border-slate-800',
                } }}">
                <span class="w-2 h-2 rounded-full
                    {{ match($payment->status) {
                        'confirmed', 'verified' => 'bg-emerald-500',
                        'submitted'             => 'bg-blue-500',
                        'pending'               => 'bg-amber-500',
                        'rejected'              => 'bg-rose-500',
                        default                 => 'bg-slate-400',
                    } }}"></span>
                {{ $payment->status_label }}
            </span>
        </div>
    </div>

    {{-- Digital Payment Receipt / Voucher Card --}}
    <div class="bg-white dark:bg-[#151922] border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        
        {{-- Receipt Hero Amount --}}
        <div class="p-6 sm:p-8 text-center bg-gradient-to-b from-slate-50/80 to-white dark:from-slate-900/50 dark:to-[#151922] border-b border-slate-100 dark:border-slate-800">
            @if(in_array($payment->status, ['confirmed', 'verified']))
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/50 mb-2">
                    Payment Confirmed &amp; Verified
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">
                    ₱{{ number_format($payment->amount, 2) }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Amount Paid via {{ str_replace([' (Over-the-Counter)', ' on Pickup'], '', $payment->payment_method ?? 'Cash') }}
                </p>
            @elseif($payment->status === 'submitted')
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800/50 mb-2">
                    Payment Submitted &amp; Awaiting Verification
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">
                    ₱{{ number_format($payment->amount, 2) }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Under verification by accounts desk
                </p>
            @else
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/50 mb-2">
                    Awaiting Payment Settlement
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-display tracking-tight">
                    ₱{{ number_format($payment->amount, 2) }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Total Amount Due
                </p>
            @endif
        </div>

        {{-- Transaction Breakdown Grid --}}
        <div class="p-6 sm:p-7 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Order Reference</span>
                <p class="font-bold text-slate-900 dark:text-white text-sm">
                    #{{ $payment->order->order_number ?? '—' }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Payment Method</span>
                <p class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    @if(str_contains(strtolower($payment->payment_method ?? ''), 'gcash'))
                        <i class="fa-solid fa-mobile-screen-button text-blue-500 text-xs"></i>
                    @elseif(str_contains(strtolower($payment->payment_method ?? ''), 'bank'))
                        <i class="fa-solid fa-building-columns text-indigo-500 text-xs"></i>
                    @else
                        <i class="fa-solid fa-money-bill-wave text-emerald-500 text-xs"></i>
                    @endif
                    {{ str_replace([' (Over-the-Counter)', ' on Pickup'], '', $payment->payment_method ?? 'Cash') }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Payment Reference</span>
                <p class="font-sans tabular-nums font-bold text-slate-900 dark:text-white text-xs sm:text-sm">
                    {{ $payment->display_reference ?? '—' }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Date &amp; Timestamp</span>
                <p class="font-bold text-slate-900 dark:text-white">
                    {{ $payment->paid_at?->format('F d, Y · h:i A') ?? ($payment->created_at?->format('F d, Y · h:i A') ?? '—') }}
                </p>
            </div>
        </div>

        {{-- Notes Callout (if present) --}}
        @if($payment->notes)
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-800">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Additional Notes</span>
                <p class="text-xs text-slate-600 dark:text-slate-300 italic">&ldquo;{{ $payment->notes }}&rdquo;</p>
            </div>
        @endif

        {{-- Status Notification Strip --}}
        @if($payment->status === 'confirmed')
            <div class="px-6 py-4 bg-emerald-50/70 dark:bg-emerald-950/20 border-t border-emerald-100 dark:border-emerald-800/40 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                <p class="text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                    Payment confirmed! Your order is now being processed for production.
                </p>
            </div>
        @elseif($payment->status === 'submitted')
            <div class="px-6 py-4 bg-blue-50/70 dark:bg-blue-950/20 border-t border-blue-100 dark:border-blue-800/40 flex items-center gap-3">
                <i class="fa-solid fa-clock text-blue-600 dark:text-blue-400 shrink-0"></i>
                <p class="text-xs text-blue-800 dark:text-blue-300 font-medium">
                    Your payment reference has been submitted and is awaiting verification by our team. You will be notified once confirmed.
                </p>
            </div>
        @endif
    </div>

    {{-- Interactive Form for Pending Payment Settlement --}}
    @if($payment->status === 'pending')
    <div class="bg-white dark:bg-[#151922] border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm p-6 space-y-4" x-data="{ method: '{{ old('payment_method', 'GCash') }}' }">
        <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-credit-card text-blue-500"></i> Submit Payment Details
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Select your preferred payment method below. For online payments, enter your transaction reference number. For cash, you can pay directly upon claiming your order at the counter.
        </p>

        <form method="POST" action="{{ route('customer.payments.submit', $payment) }}" class="space-y-4 pt-2">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Payment Method <span class="text-red-500">*</span>
                </label>
                <select name="payment_method" required x-model="method"
                        class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
                    <option value="Cash">Cash on Pickup / Over-the-Counter</option>
                    <option value="GCash">GCash</option>
                    <option value="Maya">Maya</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            {{-- Reference for Electronic / Online Payments --}}
            <div x-show="method !== 'Cash'" x-cloak>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Payment Reference / Transaction ID <span class="text-red-500">*</span>
                </label>
                <input type="text" name="payment_reference" :required="method !== 'Cash'" 
                       :placeholder="method === 'GCash' ? 'e.g. Ref No. 987654321012345' : (method === 'Bank Transfer' ? 'e.g. PAY-2026-847291' : 'e.g. Ref No. 987654321012345')"
                       class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
            </div>

            {{-- Cash Explanatory Banner --}}
            <div x-show="method === 'Cash'" class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-xs text-blue-900 dark:text-blue-300 flex items-start gap-2.5">
                <i class="fa-solid fa-circle-info mt-0.5 text-blue-600 dark:text-blue-400 shrink-0"></i>
                <div>
                    <p class="font-bold">No Reference Code Required for Cash</p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-0.5">Please prepare the exact payment amount (₱{{ number_format($payment->amount, 2) }}) upon claiming your printed order at the counter.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Additional Notes</label>
                <textarea name="notes" rows="2" placeholder="Any additional information..."
                          class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 px-3.5 py-2.5 text-sm bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition resize-none"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-sm shadow-blue-600/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Submit Payment Details
            </button>
        </form>
    </div>
    @endif

    {{-- Bottom Action Bar --}}
    <div class="flex items-center justify-between pt-2">
        <a href="{{ route('customer.payments.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm shadow-blue-600/20">
            <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Payments
        </a>

        @if($payment->order)
            <a href="{{ route('customer.orders.tracking', $payment->order) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-map-location-dot text-[11px]"></i> Track Order
            </a>
        @endif
    </div>

</div>
@endsection
