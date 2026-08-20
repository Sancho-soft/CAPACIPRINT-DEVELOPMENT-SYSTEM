@extends('layouts.internal')
@section('title', 'Manage Orders')
@section('page-title', 'Order Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Customer Orders</h2>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Order #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Payment</th>
                    <th class="px-6 py-3.5">Order Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $ord)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $ord->order_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $ord->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $ord->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($ord->payment_status) { 'confirmed'=>'bg-emerald-100 text-emerald-800', 'submitted'=>'bg-amber-100 text-amber-800', default=>'bg-slate-100 text-slate-600' } }}">
                            {{ $ord->payment_status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($ord->status) { 'production'=>'bg-cyan-100 text-cyan-800', 'ready_for_pickup'=>'bg-emerald-100 text-emerald-800', default=>'bg-slate-100 text-slate-600' } }}">
                            {{ $ord->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('staff.orders.show', $ord) }}" class="text-brand-600 font-bold hover:underline">Manage &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No customer orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
