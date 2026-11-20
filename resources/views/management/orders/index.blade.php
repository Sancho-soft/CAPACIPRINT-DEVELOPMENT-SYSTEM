@extends('layouts.internal')
@section('title', 'Order Management — Capaciprint Executive')
@section('page-title', 'Order Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto font-sans">

    {{-- 1. EXECUTIVE KPI SUMMARY STRIP --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Pipeline Revenue --}}
        <div class="bg-cyber-card border border-cyber border-l-2 border-l-brand-600 dark:border-l-blue-500 rounded-xl p-4 sm:p-5 flex items-center justify-between gap-3">
            <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-sack-dollar text-xs text-brand-600 dark:text-blue-400 shrink-0"></i>
                    <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted block truncate">
                        Pipeline Revenue
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0 tabular-nums">
                <span class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight leading-none whitespace-nowrap">
                    ₱{{ number_format($pipelineValue, 2) }}
                </span>
            </div>
        </div>

        {{-- Card 2: Total Active Orders --}}
        <a href="{{ route('management.orders.index') }}" 
           class="bg-cyber-card border border-cyber border-l-2 border-l-indigo-600 dark:border-l-indigo-400 rounded-xl p-4 sm:p-5 flex items-center justify-between gap-3 hover:border-brand-600/40 transition-colors cursor-pointer">
            <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-xs text-indigo-600 dark:text-indigo-400 shrink-0"></i>
                    <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted block truncate">
                        Total System Orders
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0 tabular-nums">
                <span class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($totalOrdersCount) }}
                </span>
            </div>
        </a>

        {{-- Card 3: In Production --}}
        <a href="{{ route('management.orders.index', ['status' => 'in_production']) }}" 
           class="bg-cyber-card border border-cyber border-l-2 border-l-amber-600 dark:border-l-amber-500 rounded-xl p-4 sm:p-5 flex items-center justify-between gap-3 hover:border-brand-600/40 transition-colors cursor-pointer">
            <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-gears text-xs text-amber-600 dark:text-amber-400 shrink-0"></i>
                    <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted block truncate">
                        In Production (Floor)
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0 tabular-nums">
                <span class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($inProductionCount) }}
                </span>
            </div>
        </a>

        {{-- Card 4: Ready for Pickup --}}
        <a href="{{ route('management.orders.index', ['status' => 'ready_for_pickup']) }}" 
           class="bg-cyber-card border border-cyber border-l-2 border-l-emerald-600 dark:border-l-emerald-500 rounded-xl p-4 sm:p-5 flex items-center justify-between gap-3 hover:border-brand-600/40 transition-colors cursor-pointer">
            <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-xs text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                    <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider text-cyber-muted block truncate">
                        Ready for Pickup
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0 tabular-nums">
                <span class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($readyForPickupCount) }}
                </span>
            </div>
        </a>

    </div>

    {{-- 2. SMART SEARCH & MULTI-FILTER BAR --}}
    <div class="bg-cyber-card border border-cyber rounded-xl p-4 sm:p-5 space-y-3">
        <form method="GET" action="{{ route('management.orders.index') }}" class="space-y-3 text-xs">
            
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                
                {{-- Search Box --}}
                <div class="sm:col-span-4 relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search Order #, customer name, service..." 
                           class="w-full pl-9 pr-3.5 py-2 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-lg focus:outline-none focus:border-brand-600 text-xs">
                </div>

                {{-- Facility Filter --}}
                <div class="sm:col-span-3">
                    <select name="branch" class="w-full px-3 py-2 bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:outline-none focus:border-brand-600 text-xs">
                        <option value="">All Facilities</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->name }}" {{ request('branch') == $b->name ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Dropdown --}}
                <div class="sm:col-span-3">
                    <select name="status" class="w-full px-3 py-2 bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:outline-none focus:border-brand-600 text-xs">
                        <option value="">All Statuses</option>
                        <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                        <option value="ready_for_pickup" {{ request('status') == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed / Claimed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Routing</option>
                    </select>
                </div>

                {{-- Date Filter & Submit --}}
                <div class="sm:col-span-2 flex items-center gap-2">
                    <select name="date" class="w-full px-2.5 py-2 bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:outline-none focus:border-brand-600 text-xs">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>

                    <button type="submit" 
                            aria-label="Apply filters"
                            class="px-3.5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold transition shrink-0 cursor-pointer"
                            title="Apply Filters">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </button>
                </div>

            </div>

            {{-- Filter Status Summary & Reset --}}
            @if(request()->hasAny(['search', 'branch', 'status', 'date']))
            <div class="flex items-center justify-between pt-2 border-t border-cyber-sub text-[11px]">
                <span class="text-cyber-muted">
                    Filtered results active:
                    @if(request('search')) <span class="font-semibold text-cyber-main">"{{ request('search') }}"</span> @endif
                    @if(request('branch')) &bull; <span class="font-semibold text-brand-600 dark:text-blue-400">{{ request('branch') }}</span> @endif
                    @if(request('status')) &bull; <span class="font-semibold text-cyber-main">{{ ucfirst(request('status')) }}</span> @endif
                    @if(request('date')) &bull; <span class="font-semibold text-cyber-main">{{ ucfirst(request('date')) }}</span> @endif
                </span>
                <a href="{{ route('management.orders.index') }}" class="text-rose-600 dark:text-rose-400 hover:underline font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Clear Filters
                </a>
            </div>
            @endif

        </form>
    </div>

    {{-- 3. ENHANCED EXECUTIVE ORDERS TABLE --}}
    <div id="management-orders-table-card" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto font-sans">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                    <tr>
                        <th class="px-5 py-3">Order # &amp; Date</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Service &amp; Specs</th>
                        <th class="px-5 py-3">Assigned Facility</th>
                        <th class="px-5 py-3">Order Value</th>
                        <th class="px-5 py-3">Priority</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                    @forelse($orders as $o)
                        @php
                            $customerName = $o->user->name ?? 'Direct Customer';
                            $customerEmail = $o->user->email ?? '—';
                            $service = $o->printRequest->service ?? 'Commercial Printing';
                            $qty = $o->printRequest->quantity ?? 1;
                            $paper = $o->printRequest->paper_type ?? $o->printRequest->material ?? null;
                            $size = $o->printRequest->size ?? null;
                            $branch = $o->assigned_branch ?? ($o->productionJob->branch->name ?? 'Pending Allocation');
                            
                            $orderValue = $o->quotation->total_price ?? ($o->payment->amount ?? 1250);
                            $priority = strtolower($o->printRequest->urgency ?? ($o->productionJob->priority ?? 'normal'));
                            
                            $statusStr = strtolower($o->status ?? 'pending');
                            $statusBadge = match($statusStr) {
                                'in_production', 'production' => 'bg-blue-500/15 text-brand-700 dark:text-blue-300 border border-blue-500/25',
                                'ready_for_pickup'            => 'bg-teal-500/15 text-teal-700 dark:text-teal-300 border border-teal-500/25',
                                'completed', 'claimed'        => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/25',
                                'delayed'                     => 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/25',
                                default                       => 'bg-cyber-sub text-cyber-muted border border-cyber',
                            };

                            $statusLabel = match($statusStr) {
                                'in_production', 'production' => 'On Press',
                                'ready_for_pickup'            => 'Ready Pickup',
                                'quality_checking'            => 'Quality Check',
                                default                       => ucfirst(str_replace('_', ' ', $statusStr)),
                            };
                        @endphp
                        <tr class="hover:bg-cyber-sub/40 transition-colors">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-sans tabular-nums font-bold text-cyber-main text-xs block tracking-tight">
                                    #{{ $o->order_number }}
                                </span>
                                <span class="text-[11px] font-sans tabular-nums text-cyber-muted block mt-0.5">
                                    {{ $o->created_at ? $o->created_at->format('M d, Y') : 'Active' }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 min-w-[150px]">
                                <span class="font-semibold text-cyber-main block truncate max-w-[180px] text-xs leading-snug">
                                    {{ $customerName }}
                                </span>
                                <span class="text-[11px] text-cyber-muted block truncate max-w-[180px] mt-0.5">
                                    {{ $customerEmail }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 min-w-[160px]">
                                <span class="font-semibold text-cyber-main block truncate max-w-[190px] text-[11px]">
                                    {{ $service }}
                                </span>
                                <span class="text-[11px] text-cyber-muted block font-sans tabular-nums mt-0.5">
                                    {{ number_format($qty) }} pcs
                                    @if($paper) &bull; {{ $paper }} @endif
                                    @if($size) &bull; {{ $size }} @endif
                                </span>
                            </td>

                            <td class="px-5 py-3.5 min-w-[160px]">
                                <span class="font-semibold text-brand-600 dark:text-blue-400 block text-xs leading-tight">
                                    {{ $branch }}
                                </span>
                                <span class="text-[11px] text-cyber-muted block font-sans tabular-nums mt-0.5">
                                    {{ $o->productionJob->machine->name ?? 'Standard Press Unit' }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-bold text-cyber-main text-xs block font-sans tabular-nums">
                                    ₱{{ number_format($orderValue, 2) }}
                                </span>
                                <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider block mt-0.5 {{ match($o->payment_status) {
                                    'confirmed', 'paid' => 'text-emerald-600 dark:text-emerald-400',
                                    'submitted'         => 'text-amber-600 dark:text-amber-400',
                                    default             => 'text-cyber-sub'
                                } }}">
                                    {{ $o->payment_status_label ?? ucfirst($o->payment_status ?? 'Pending') }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="text-xs font-semibold uppercase tracking-wider {{ match($priority) {
                                    'urgent' => 'text-rose-600 dark:text-rose-400',
                                    'rush'   => 'text-amber-600 dark:text-amber-400',
                                    default  => 'text-emerald-600 dark:text-emerald-400',
                                } }}">
                                    {{ $priority }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="text-xs font-semibold uppercase tracking-wider {{ match($statusStr) {
                                    'in_production', 'production' => 'text-blue-600 dark:text-blue-400',
                                    'ready_for_pickup'            => 'text-teal-600 dark:text-teal-400',
                                    'completed', 'claimed'        => 'text-emerald-600 dark:text-emerald-400',
                                    'delayed'                     => 'text-rose-600 dark:text-rose-400',
                                    default                       => 'text-cyber-muted',
                                } }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('management.orders.show', $o) }}" 
                                   class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center"
                                   title="View Order Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                                <span class="font-bold text-sm block">No Orders Found</span>
                                <span class="text-xs text-slate-500 mt-1 block">Try adjusting your search terms or clearing your active filters.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Bar --}}
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-cyber/60 bg-cyber-sub/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-slate-500 dark:text-slate-400 font-medium">
                Showing <span class="font-bold text-slate-900 dark:text-white">{{ $orders->firstItem() }}</span> to <span class="font-bold text-slate-900 dark:text-white">{{ $orders->lastItem() }}</span> of <span class="font-bold text-slate-900 dark:text-white">{{ $orders->total() }}</span> orders
            </span>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
