@extends('layouts.internal')
@section('title', 'Manage Orders')
@section('page-title', 'Order Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Customer Orders</h1>
            <p class="text-xs text-cyber-muted mt-1">Search and manage client job orders, payments, and fulfillment.</p>
        </div>

        <form id="order-search-form" method="GET" action="{{ route('staff.orders.index') }}" class="flex items-center">
            <div class="relative flex items-center w-full sm:w-96 rounded-lg border border-cyber bg-cyber-card p-1 focus-within:border-brand-600 transition">
                <div class="pl-3 pr-1 flex items-center pointer-events-none text-cyber-muted">
                    <i id="order-search-icon" class="fa-solid fa-magnifying-glass text-xs text-brand-600 dark:text-blue-400"></i>
                </div>
                <input type="text" 
                       id="order-search-input"
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by Order ID or Customer Name..."
                       autocomplete="off"
                       class="w-full bg-transparent border-0 px-2.5 py-1.5 text-xs font-medium text-cyber-main placeholder-cyber-muted focus:outline-none focus:ring-0">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-1.5 rounded-md text-xs font-semibold transition shrink-0 cursor-pointer">
                    Search
                </button>
            </div>
        </form>
    </div>

    <div id="orders-table-card" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Order #</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Service</th>
                        <th class="px-5 py-3">Payment</th>
                        <th class="px-5 py-3">Order Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @forelse($orders as $ord)
                    <tr class="hover:bg-cyber-sub/50 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-cyber-main font-sans tabular-nums">#{{ $ord->order_number }}</td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $ord->user->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-cyber-muted">{{ $ord->printRequest->service ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold uppercase {{ match($ord->payment_status) { 'confirmed'=>'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20', 'submitted'=>'bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20', default=>'bg-cyber-sub text-cyber-muted border border-cyber' } }}">
                                {{ $ord->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2 py-0.5 rounded text-[11px] font-sans tabular-nums font-semibold uppercase {{ match($ord->status) { 'production'=>'bg-blue-500/10 text-brand-700 dark:text-blue-400 border border-blue-500/20', 'ready_for_pickup'=>'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20', default=>'bg-cyber-sub text-cyber-muted border border-cyber' } }}">
                                {{ $ord->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('staff.orders.show', $ord) }}" 
                               class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center" 
                               title="Manage Order">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-cyber-muted">
                            No orders found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-cyber">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
