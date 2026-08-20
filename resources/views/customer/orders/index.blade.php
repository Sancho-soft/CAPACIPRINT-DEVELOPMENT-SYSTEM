@extends('layouts.customer')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-6 max-w-6xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">My Orders</h2>
            <p class="text-sm text-slate-500 mt-1">Monitor all your print orders and their production status.</p>
        </div>
        <a href="{{ route('customer.print-requests.create') }}"
           class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Request
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        @if($orders->isEmpty())
        <div class="p-12 text-center">
            <i class="fa-solid fa-box-open text-slate-300 text-4xl mb-3"></i>
            <h4 class="font-bold text-navy-900">No orders yet</h4>
            <p class="text-sm text-slate-500 mt-1">Submit a print request and your orders will appear here.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Est. Completion</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900">#{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $order->printRequest->service ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->assigned_branch ?? 'Routing...' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->estimated_completion?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 text-[11px] font-bold rounded uppercase {{ $order->payment_status === 'confirmed' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded uppercase
                                {{ match($order->status) {
                                    'submitted'   => 'bg-blue-100 text-blue-800',
                                    'quotation'   => 'bg-amber-100 text-amber-800',
                                    'payment'     => 'bg-orange-100 text-orange-800',
                                    'production'  => 'bg-cyan-100 text-cyan-800',
                                    'completed'   => 'bg-green-100 text-green-800',
                                    'ready_for_pickup' => 'bg-teal-100 text-teal-800',
                                    'claimed'     => 'bg-slate-100 text-slate-600',
                                    default       => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('customer.orders.tracking', $order) }}"
                               class="text-brand-500 hover:text-brand-700 font-bold text-xs">Track</a>
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="text-navy-600 hover:text-navy-900 font-bold text-xs">Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
