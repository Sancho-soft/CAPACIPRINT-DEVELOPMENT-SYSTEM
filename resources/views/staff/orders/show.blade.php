@extends('layouts.internal')
@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-navy-900 font-display">Order #{{ $order->order_number }}</h2>
            <p class="text-xs text-slate-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase bg-slate-100 text-slate-800">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3 text-xs">
            <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-2">Customer &amp; Payment Status</h3>
            <p><span class="text-slate-400 block">Customer Name</span><strong class="text-slate-800 text-sm">{{ $order->user->name ?? '—' }}</strong></p>
            <p><span class="text-slate-400 block">Payment Reference</span><strong class="text-slate-800">{{ $order->payment->payment_reference ?? 'Not submitted' }}</strong></p>
            <p><span class="text-slate-400 block">Payment Status</span>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($order->payment_status) { 'confirmed'=>'bg-emerald-100 text-emerald-800', 'submitted'=>'bg-amber-100 text-amber-800', default=>'bg-slate-100 text-slate-600' } }}">
                    {{ $order->payment_status_label }}
                </span>
            </p>

            @if($order->payment_status === 'submitted')
            <form method="POST" action="{{ route('staff.orders.confirm-payment', $order) }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 rounded-xl text-xs">
                    <i class="fa-solid fa-check mr-1"></i> Verify &amp; Confirm Payment
                </button>
            </form>
            @endif
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3 text-xs">
            <h3 class="font-bold text-navy-900 border-b border-slate-100 pb-2">Production &amp; Branch Assignment</h3>
            <p><span class="text-slate-400 block">Assigned Branch</span><strong class="text-brand-600 text-sm">{{ $order->assigned_branch ?? 'Pending Branch Recommendation' }}</strong></p>
            <p><span class="text-slate-400 block">Production Job Status</span>
                <strong class="text-slate-800">{{ $order->productionJob->status_label ?? 'No active job' }}</strong>
            </p>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <a href="{{ route('staff.orders.index') }}" class="text-xs font-semibold text-slate-500">&larr; Back to Orders</a>
    </div>
</div>
@endsection
