@extends('layouts.customer')
@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="space-y-6 w-full">
    <div>
        <h2 class="text-2xl font-black text-cyber-main font-display">My Payments</h2>
        <p class="text-sm text-cyber-muted mt-1">Track payment status and submit your transaction references here.</p>
    </div>

    @if($payments->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl p-16 text-center shadow-xl">
        <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-credit-card"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base">No payments yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Payment records are linked to accepted quotations and confirmed orders.</p>
    </div>
    @else
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Amount Due</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900">#{{ $payment->order->order_number ?? '—' }}</td>
                        <td class="px-6 py-4 font-bold text-navy-900">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $payment->payment_reference ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $payment->paid_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded uppercase {{ $payment->status_badge_class }}">
                                {{ $payment->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.payments.show', $payment) }}"
                               class="text-brand-500 hover:text-brand-700 font-bold text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">{{ $payments->links() }}</div>
    </div>
    @endif
</div>
@endsection
