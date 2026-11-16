@extends('layouts.internal')
@section('title', 'Manage Orders')
@section('page-title', 'Order Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-slate-900 dark:text-white">Customer Orders</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Search and manage client job orders, payments, and fulfillment.</p>
        </div>

        <form id="order-search-form" method="GET" action="{{ route('staff.orders.index') }}" class="flex items-center">
            <div class="relative flex items-center w-full sm:w-96 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/60 p-1 shadow-2xs focus-within:border-cyan-500 focus-within:ring-1 focus-within:ring-cyan-500 transition">
                <div class="pl-3 pr-1 flex items-center pointer-events-none text-slate-400">
                    <i id="order-search-icon" class="fa-solid fa-magnifying-glass text-xs text-cyan-500"></i>
                </div>
                <input type="text" 
                       id="order-search-input"
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search by Order ID or Customer Name..."
                       autocomplete="off"
                       class="w-full bg-transparent border-0 px-2.5 py-1.5 text-xs font-medium text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-0">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold transition shadow-xs shrink-0 cursor-pointer">
                    Search
                </button>
            </div>
        </form>
    </div>

    <div id="orders-table-card" class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-[#0D1520] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800 text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Order #</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Service</th>
                        <th class="px-6 py-3.5">Payment</th>
                        <th class="px-6 py-3.5">Order Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
                    @forelse($orders as $ord)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white font-mono">#{{ $ord->order_number }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $ord->user->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $ord->printRequest->service ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ match($ord->payment_status) { 'confirmed'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300', 'submitted'=>'bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300', default=>'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' } }}">
                                {{ $ord->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ match($ord->status) { 'production'=>'bg-cyan-100 text-cyan-800 dark:bg-cyan-950/40 dark:text-cyan-300', 'ready_for_pickup'=>'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300', default=>'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' } }}">
                                {{ $ord->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('staff.orders.show', $ord) }}" 
                               class="text-cyan-500 hover:text-cyan-600 dark:text-cyan-400 dark:hover:text-cyan-300 transition text-sm p-1 inline-flex items-center justify-center" 
                               title="Manage Order">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                            No orders found matching your search.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
