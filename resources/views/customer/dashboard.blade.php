@extends('layouts.customer')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main leading-tight">Welcome back, {{ auth()->user()->name }}</h2>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('customer.print-requests.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition">
                <i class="fa-solid fa-plus text-xs shrink-0"></i>
                <span>New Print Request</span>
            </a>
            <a href="{{ route('customer.orders.index') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition">
                <i class="fa-solid fa-magnifying-glass text-xs shrink-0 text-blue-600 dark:text-blue-400"></i>
                <span>View My Orders</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3 KPI METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="ACTIVE ORDERS"
            :value="$activeOrdersCount"
            icon="fa-solid fa-boxes-stacked"
            accent="blue"
            :link="route('customer.orders.index', ['status' => 'active'])"
        />

        <x-dashboard.kpi-card 
            title="PENDING QUOTATIONS"
            :value="$pendingQuotesCount"
            icon="fa-solid fa-file-invoice-dollar"
            accent="amber"
            :link="route('customer.quotations.index', ['status' => 'pending'])"
        />

        <x-dashboard.kpi-card 
            title="COMPLETED ORDERS"
            :value="$completedOrdersCount"
            icon="fa-solid fa-clipboard-check"
            accent="emerald"
            :link="route('customer.orders.index', ['status' => 'completed'])"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LATEST ORDER TRACKER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($latestOrder)
    <div class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 sm:px-6 py-4 bg-cyber-sub border-b border-cyber-sub flex items-center justify-between">
            <h3 class="font-bold text-cyber-main flex items-center gap-2 text-sm font-display">
                <i class="fa-solid fa-map-location-dot text-blue-600 dark:text-blue-400"></i> Latest Order Progress
            </h3>
            <span class="font-sans tabular-nums text-xs text-cyber-muted tabular-nums">{{ $latestOrder->order_number }}</span>
        </div>
        <div class="p-5 sm:p-6">
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
                    <h4 class="font-bold text-cyber-main text-base font-display">{{ $latestOrder->printRequest->service ?? '—' }}</h4>
                    <p class="text-xs font-sans text-cyber-muted mt-0.5 tabular-nums">
                        Qty: {{ $latestOrder->printRequest->quantity ?? '—' }} &middot;
                        {{ $latestOrder->printRequest->size ?? '' }}
                    </p>
                </div>
                <span class="text-xs font-sans font-semibold text-blue-600 dark:text-blue-400">
                    {{ $latestOrder->status_label }}
                </span>
            </div>

            {{-- Progress bar & steps --}}
            <div class="relative">
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-black/10 dark:bg-white/10 z-0"></div>
                <div class="absolute top-4 left-0 h-0.5 bg-blue-600 dark:bg-blue-500 z-0 transition-all duration-300" @style(['width: ' . $pct . '%'])></div>
                <div class="relative flex justify-between z-10">
                    @foreach($labels as $i => $label)
                    <div class="flex flex-col items-center">
                        <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-semibold border transition-colors
                            {{ $i < $current ? 'bg-blue-600 border-blue-600 text-white' : ($i === $current ? 'bg-blue-600 border-blue-600 text-white ring-2 ring-blue-600/25 dark:ring-blue-500/30' : 'bg-cyber-sub border-cyber text-cyber-sub') }}">
                            <i class="fa-solid {{ $icons[$i] ?? 'fa-circle' }}"></i>
                        </div>
                        <span class="hidden md:block text-[11px] font-sans font-medium text-cyber-muted mt-2 text-center leading-tight max-w-[64px]">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 p-4 bg-cyber-sub border border-cyber-sub rounded-xl flex items-center gap-4">
                <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400 text-base shrink-0"></i>
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-cyber-main">Current Status: <span class="text-blue-600 dark:text-blue-400">{{ $latestOrder->status_label }}</span></p>
                    @if($latestOrder->assigned_branch)
                        <p class="text-xs text-cyber-muted mt-0.5">Branch: {{ $latestOrder->assigned_branch }}</p>
                    @endif
                    @if($latestOrder->estimated_completion)
                        <p class="text-xs font-sans text-cyber-muted mt-0.5 tabular-nums">Est. Completion: {{ $latestOrder->estimated_completion->format('M d, Y') }}</p>
                    @endif
                </div>
                <a href="{{ route('customer.orders.tracking', $latestOrder) }}"
                   class="shrink-0 text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold transition flex items-center gap-1">
                    Track &rarr;
                </a>
            </div>
        </div>
    </div>
    @else
    <x-dashboard.empty-state 
        title="No active orders yet"
        description="Submit your first print request to start tracking capacity routing and press production."
        icon="fa-solid fa-print"
        :actionUrl="route('customer.print-requests.create')"
        actionLabel="Submit Print Request"
    />
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- RECENT ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($orders->count())
    <div class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 sm:px-6 py-4 bg-cyber-sub border-b border-cyber-sub flex items-center justify-between">
            <h3 class="font-bold text-cyber-main text-sm font-display">Recent Orders</h3>
            <a href="{{ route('customer.orders.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold">View all &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyber-sub text-xs tabular-nums">
                <thead class="bg-cyber-sub">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-bold text-cyber-muted">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-bold text-cyber-muted">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-bold text-cyber-muted">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-sans font-bold text-cyber-muted">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @foreach($orders as $order)
                    <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                        <td class="px-6 py-3.5 font-sans tabular-nums font-semibold text-cyber-main">{{ $order->order_number }}</td>
                        <td class="px-6 py-3.5 text-cyber-main font-medium">{{ $order->printRequest->service ?? '—' }}</td>
                        <td class="px-6 py-3.5">
                            <span class="text-xs font-sans font-semibold
                                {{ match($order->status) {
                                    'submitted'   => 'text-blue-600 dark:text-blue-400',
                                    'quotation'   => 'text-amber-600 dark:text-amber-400',
                                    'payment'     => 'text-orange-600 dark:text-orange-400',
                                    'production'  => 'text-indigo-600 dark:text-indigo-400',
                                    'completed'   => 'text-emerald-600 dark:text-emerald-400',
                                    'ready_for_pickup' => 'text-teal-600 dark:text-teal-400',
                                    default       => 'text-cyber-muted',
                                } }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1.5 rounded-lg hover:bg-blue-600/10 dark:hover:bg-blue-500/15 inline-flex items-center justify-center"
                               title="View Order Details">
                                <i class="fa-solid fa-eye"></i>
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
