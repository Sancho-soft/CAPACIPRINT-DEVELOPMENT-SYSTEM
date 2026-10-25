@extends('layouts.customer')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="space-y-6 w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-cyber-main font-display">My Orders</h2>
            <p class="text-sm text-cyber-muted mt-1">Monitor all your print orders and their live production progress.</p>
        </div>
        <a href="{{ route('customer.print-requests.create') }}"
           class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i> New Request
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('customer.orders.index') }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') && !request('filter') ? 'bg-cyan-500 text-slate-950 shadow-md shadow-cyan-500/20' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            All Orders
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'active']) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('status') === 'active' || request('filter') === 'active' ? 'bg-cyan-500 text-slate-950 shadow-md shadow-cyan-500/20' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            <span class="w-2 h-2 rounded-full bg-cyan-400"></span> Active Orders
        </a>
        <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}"
           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('status') === 'completed' || request('filter') === 'completed' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main' }}">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Completed Orders
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden">
        @if($orders->isEmpty())
        <div class="p-16 text-center">
            <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h4 class="font-bold text-cyber-main text-base">No orders yet</h4>
            <p class="text-xs text-cyber-muted mt-1">Submit a print request and approved jobs will appear here.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Est. Completion</th>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Action</th>
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
                            <span class="text-xs font-bold uppercase
                                {{ match($order->status) {
                                    'submitted'   => 'text-blue-600 dark:text-blue-400',
                                    'quotation'   => 'text-amber-600 dark:text-amber-400',
                                    'payment'     => 'text-orange-600 dark:text-orange-400',
                                    'production'  => 'text-cyan-600 dark:text-cyan-400',
                                    'completed'   => 'text-emerald-600 dark:text-emerald-400',
                                    'ready_for_pickup' => 'text-teal-600 dark:text-teal-400',
                                    'claimed'     => 'text-slate-500 dark:text-slate-400',
                                    default       => 'text-slate-500 dark:text-slate-400',
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
