@extends('layouts.customer')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-8 max-w-7xl">

    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">Dashboard Overview</h2>
        <p class="text-sm text-slate-500 mt-1">Welcome to your personal print management portal.</p>
    </div>

    {{-- ── Welcome Banner ────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-700 text-white rounded-2xl p-6 md:p-8 shadow-md border border-navy-950 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="relative z-10 max-w-xl">
            <h2 class="text-2xl md:text-3xl font-bold font-display">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="mt-2 text-sm text-navy-100 leading-relaxed">
                Submit artwork files, receive instant quotations, and monitor your print jobs routed to optimised branches.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('customer.print-requests.create') }}"
                   class="bg-brand-400 hover:bg-brand-500 text-navy-950 font-bold px-5 py-2.5 rounded-lg text-sm transition-all shadow flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Submit Print Request
                </a>
                <a href="{{ route('customer.orders.index') }}"
                   class="border border-navy-300 hover:bg-navy-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> View My Orders
                </a>
            </div>
        </div>
    </div>

    {{-- ── Stats Cards ────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-4 min-w-0">
                <div class="h-12 w-12 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Active Orders</p>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-navy-900 font-display shrink-0 ml-3">{{ $activeOrdersCount }}</h3>
        </div>
        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-4 min-w-0">
                <div class="h-12 w-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Pending Quotations</p>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-navy-900 font-display shrink-0 ml-3">{{ $pendingQuotesCount }}</h3>
        </div>
        <div class="bg-white p-6 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-4 min-w-0">
                <div class="h-12 w-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Completed Orders</p>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-navy-900 font-display shrink-0 ml-3">{{ $completedOrdersCount }}</h3>
        </div>
    </div>

    {{-- ── Latest Order Tracker ───────────────────────────── --}}
    @if($latestOrder)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-brand-500"></i> Latest Order Progress
            </h3>
        </div>
        <div class="p-6">
            @php
                $steps   = \App\Models\Order::statusSteps();
                $current = array_search($latestOrder->status, $steps);
                if ($current === false) $current = 0;
                $pct     = count($steps) > 1 ? ($current / (count($steps) - 1)) * 100 : 0;
                $labels  = ['Submitted','Quotation','Payment','Branch','Production','Completed','Pickup','Claimed'];
                $icons   = ['fa-file-arrow-up','fa-file-invoice-dollar','fa-credit-card','fa-code-branch','fa-industry','fa-circle-check','fa-truck-ramp-box','fa-handshake'];
            @endphp

            <div class="flex items-center justify-between mb-6 gap-4">
                <div>
                    <h4 class="font-bold text-navy-900">{{ $latestOrder->printRequest->service ?? '—' }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Qty: {{ $latestOrder->printRequest->quantity ?? '—' }} &middot;
                        {{ $latestOrder->printRequest->size ?? '' }}
                    </p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-brand-100 text-brand-800">
                    {{ $latestOrder->status_label }}
                </span>
            </div>

            {{-- Progress bar & steps --}}
            <div class="relative">
                <div class="absolute top-4 left-0 right-0 h-1 bg-slate-100 z-0"></div>
                <div class="absolute top-4 left-0 h-1 bg-brand-400 z-0 transition-all duration-700" style="width: {{ $pct }}%"></div>
                <div class="relative flex justify-between z-10">
                    @foreach($labels as $i => $label)
                    <div class="flex flex-col items-center">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                            {{ $i < $current ? 'bg-brand-400 border-brand-400 text-navy-950' : ($i === $current ? 'bg-brand-500 border-brand-500 text-white ring-4 ring-brand-200' : 'bg-white border-slate-200 text-slate-400') }}">
                            <i class="fa-solid {{ $icons[$i] ?? 'fa-circle' }}"></i>
                        </div>
                        <span class="hidden md:block text-[10px] font-semibold text-navy-800 mt-2 text-center leading-tight max-w-[56px]">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 p-4 bg-slate-50 border border-slate-100 rounded-lg flex items-center gap-4">
                <i class="fa-solid fa-circle-info text-brand-500 text-lg shrink-0"></i>
                <div class="flex-1">
                    <p class="text-sm font-bold text-navy-900">Current Status: <span class="text-brand-600">{{ $latestOrder->status_label }}</span></p>
                    @if($latestOrder->assigned_branch)
                        <p class="text-xs text-slate-500 mt-0.5">Branch: {{ $latestOrder->assigned_branch }}</p>
                    @endif
                    @if($latestOrder->estimated_completion)
                        <p class="text-xs text-slate-500 mt-0.5">Est. Completion: {{ $latestOrder->estimated_completion->format('M d, Y') }}</p>
                    @endif
                </div>
                <a href="{{ route('customer.orders.tracking', $latestOrder) }}"
                   class="shrink-0 text-xs text-brand-600 hover:text-brand-800 font-bold transition">
                    Track &rarr;
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-10 text-center">
        <i class="fa-solid fa-circle-question text-slate-300 text-4xl mb-3"></i>
        <h4 class="font-bold text-navy-900">No active orders yet</h4>
        <p class="text-sm text-slate-500 mt-1">Submit your first print request to get started.</p>
        <a href="{{ route('customer.print-requests.create') }}"
           class="inline-block mt-4 bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition">
            Submit Print Request
        </a>
    </div>
    @endif

    {{-- ── Recent Orders Table ────────────────────────────── --}}
    @if($orders->count())
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Recent Orders</h3>
            <a href="{{ route('customer.orders.index') }}" class="text-xs text-brand-500 hover:text-brand-700 font-semibold">View all &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $order->printRequest->service ?? '—' }}</td>
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
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-brand-600 hover:bg-brand-50 hover:text-brand-800 transition"
                               title="View Order Details">
                                <i class="fa-solid fa-eye text-base"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
