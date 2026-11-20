@extends('layouts.customer')
@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="space-y-6 w-full">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">My Payments</h2>
        <p class="text-xs text-cyber-muted mt-1">Verify settled transactions, receipts, and balance statuses.</p>
    </div>

    @if($payments->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-14 text-center">
        <div class="h-14 w-14 mx-auto rounded-xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-credit-card"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base font-display">No payments yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Payment records are linked to accepted quotations and confirmed orders.</p>
    </div>
    @else
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyber-sub text-xs tabular-nums">
                <thead class="bg-cyber-sub border-b border-cyber">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Order No.</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Amount Due</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Payment Method</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Reference</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Date</th>
                        <th class="px-5 py-3 text-left text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Status</th>
                        <th class="px-5 py-3 text-right text-[11px] font-sans tabular-nums font-semibold text-cyber-muted">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-cyber-sub/50 transition-colors">
                        <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-cyber-main">#{{ $payment->order?->order_number ?? ('ORD-' . str_pad($payment->order_id ?? $payment->id, 3, '0', STR_PAD_LEFT)) }}</td>
                        <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-cyber-main">₱{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ str_replace([' (Over-the-Counter)', ' on Pickup'], '', $payment->payment_method ?? 'Cash') }}</td>
                        <td class="px-5 py-3.5 text-cyber-muted font-sans tabular-nums">{{ $payment->display_reference }}</td>
                        <td class="px-5 py-3.5 text-cyber-muted font-sans tabular-nums">{{ $payment->paid_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold border {{ $payment->status_badge_class }}">
                                {{ $payment->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('customer.payments.show', $payment) }}"
                               class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center"
                               title="View Payment Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-cyber">{{ $payments->links() }}</div>
    </div>
    @endif
</div>
@endsection
