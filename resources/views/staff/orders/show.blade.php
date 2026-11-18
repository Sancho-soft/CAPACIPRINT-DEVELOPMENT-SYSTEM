@extends('layouts.internal')
@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-box"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl font-bold text-cyber-main font-display tracking-tight tabular-nums">Order #{{ $order->order_number }}</h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-cyber-sub border border-cyber text-cyber-main">
                        {{ $order->status_label }}
                    </span>
                </div>
                <p class="text-xs text-cyber-muted tabular-nums mt-1">Placed on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        <a href="{{ route('staff.orders.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold transition inline-flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-cyber-card p-5 sm:p-6 rounded-2xl border border-cyber shadow-sm space-y-4 text-xs">
            <div class="flex items-center gap-2.5 border-b border-cyber-sub pb-3">
                <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="font-bold text-cyber-main font-display text-sm">Customer &amp; Payment Status</h3>
            </div>
            <div class="space-y-3">
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Customer Name</span>
                    <strong class="text-cyber-main font-semibold text-sm">{{ $order->user->name ?? '—' }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Payment Reference</span>
                    <strong class="text-cyber-main font-semibold font-sans tabular-nums">{{ $order->payment?->display_reference ?? '—' }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5 flex items-center justify-between gap-2">
                    <span class="text-cyber-muted">Payment Status</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ match($order->payment_status) { 'confirmed'=>'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30', 'submitted'=>'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/30', default=>'bg-slate-500/10 text-cyber-muted border-cyber' } }}">
                        {{ $order->payment_status_label }}
                    </span>
                </div>
            </div>

            @if($order->payment_status === 'submitted')
            <form method="POST" action="{{ route('staff.orders.confirm-payment', $order) }}" class="pt-1">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl text-xs shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-check mr-1"></i> Verify &amp; Confirm Payment
                </button>
            </form>
            @endif
        </div>

        <div class="bg-cyber-card p-5 sm:p-6 rounded-2xl border border-cyber shadow-sm space-y-4 text-xs">
            <div class="flex items-center gap-2.5 border-b border-cyber-sub pb-3">
                <div class="h-8 w-8 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shrink-0">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <h3 class="font-bold text-cyber-main font-display text-sm">Production &amp; Branch Assignment</h3>
            </div>
            <div class="space-y-3">
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Assigned Branch</span>
                    <strong class="text-blue-600 dark:text-blue-400 font-semibold text-sm">{{ $order->assigned_branch ?? 'Pending Branch Recommendation' }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Production Job Status</span>
                    <strong class="text-cyber-main font-semibold">{{ $order->productionJob->status_label ?? 'No active job' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm">
        <a href="{{ route('staff.orders.index') }}" class="text-xs font-semibold text-cyber-muted hover:text-cyber-main transition">&larr; Back to Orders</a>
    </div>
</div>
@endsection
