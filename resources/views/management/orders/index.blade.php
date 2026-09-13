@extends('layouts.internal')
@section('title', 'Order Management — Capaciprint Executive')
@section('page-title', 'Order Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto font-sans">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. EXECUTIVE KPI SUMMARY STRIP --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Pipeline Revenue --}}
        <div class="bg-cyber-card border border-cyber rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-3 shadow-lg group hover:border-cyan-500/40 transition">
            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                <i class="fa-solid fa-sack-dollar text-2xl text-slate-400 dark:text-slate-400 shrink-0 group-hover:scale-110 transition-transform"></i>
                <div class="min-w-0">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-cyan-600 dark:text-cyan-400 block font-sans leading-tight">
                        Pipeline Revenue
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xl sm:text-2xl font-black text-black dark:text-white font-display tracking-tight leading-none whitespace-nowrap">
                    ₱{{ number_format($pipelineValue, 2) }}
                </span>
            </div>
        </div>

        {{-- Card 2: Total Active Orders --}}
        <a href="{{ route('management.orders.index') }}" 
           class="bg-cyber-card border border-cyber rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-3 shadow-lg group hover:border-blue-500/40 hover:-translate-y-0.5 transition cursor-pointer">
            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                <i class="fa-solid fa-boxes-stacked text-2xl text-slate-400 dark:text-slate-400 shrink-0 group-hover:scale-110 transition-transform"></i>
                <div class="min-w-0">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400 block font-sans leading-tight">
                        Total System Orders
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xl sm:text-2xl font-black text-black dark:text-white font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($totalOrdersCount) }}
                </span>
            </div>
        </a>

        {{-- Card 3: In Production --}}
        <a href="{{ route('management.orders.index', ['status' => 'in_production']) }}" 
           class="bg-cyber-card border border-cyber rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-3 shadow-lg group hover:border-teal-500/40 hover:-translate-y-0.5 transition cursor-pointer">
            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                <i class="fa-solid fa-gears text-2xl text-slate-400 dark:text-slate-400 shrink-0 group-hover:scale-110 transition-transform"></i>
                <div class="min-w-0">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-teal-600 dark:text-teal-400 block font-sans leading-tight">
                        In Production (Floor)
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xl sm:text-2xl font-black text-black dark:text-white font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($inProductionCount) }}
                </span>
            </div>
        </a>

        {{-- Card 4: Ready for Pickup --}}
        <a href="{{ route('management.orders.index', ['status' => 'ready_for_pickup']) }}" 
           class="bg-cyber-card border border-cyber rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-3 shadow-lg group hover:border-emerald-500/40 hover:-translate-y-0.5 transition cursor-pointer">
            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                <i class="fa-solid fa-circle-check text-2xl text-slate-400 dark:text-slate-400 shrink-0 group-hover:scale-110 transition-transform"></i>
                <div class="min-w-0">
                    <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block font-sans leading-tight">
                        Ready for Pickup
                    </span>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-xl sm:text-2xl font-black text-black dark:text-white font-display tracking-tight leading-none whitespace-nowrap">
                    {{ number_format($readyForPickupCount) }}
                </span>
            </div>
        </a>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. SMART SEARCH & MULTI-FILTER BAR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl p-5 shadow-xl space-y-4">
        <form method="GET" action="{{ route('management.orders.index') }}" class="space-y-4 text-xs">
            
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-center">
                
                {{-- Search Box --}}
                <div class="sm:col-span-4 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search Order #, customer name, service..." 
                           class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
                </div>

                {{-- Facility Filter --}}
                <div class="sm:col-span-3">
                    <select name="branch" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
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
                    <select name="status" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
                        <option value="">All Statuses</option>
                        <option value="in_production" {{ request('status') == 'in_production' ? 'selected' : '' }}>In Production</option>
                        <option value="ready_for_pickup" {{ request('status') == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed / Claimed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Routing</option>
                    </select>
                </div>

                {{-- Date Filter & Submit --}}
                <div class="sm:col-span-2 flex items-center gap-2">
                    <select name="date" class="w-full px-2.5 py-2.5 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>

                    <button type="submit" 
                            class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold transition shadow-xs shrink-0"
                            title="Apply Filters">
                        <i class="fa-solid fa-filter text-xs"></i>
                    </button>
                </div>

            </div>

            {{-- Filter Status Summary & Reset --}}
            @if(request()->hasAny(['search', 'branch', 'status', 'date']))
            <div class="flex items-center justify-between pt-2 border-t border-cyber/50 text-[11px]">
                <span class="text-slate-500 dark:text-slate-400">
                    Filtered results active:
                    @if(request('search')) <span class="font-semibold text-black dark:text-white">"{{ request('search') }}"</span> @endif
                    @if(request('branch')) &bull; <span class="font-semibold text-cyan-500">{{ request('branch') }}</span> @endif
                    @if(request('status')) &bull; <span class="font-semibold text-black dark:text-white">{{ ucfirst(request('status')) }}</span> @endif
                    @if(request('date')) &bull; <span class="font-semibold text-black dark:text-white">{{ ucfirst(request('date')) }}</span> @endif
                </span>
                <a href="{{ route('management.orders.index') }}" class="text-rose-500 hover:text-rose-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Clear Filters
                </a>
            </div>
            @endif

        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. ENHANCED EXECUTIVE ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto font-sans">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px] bg-slate-50/50 dark:bg-transparent">
                    <tr>
                        <th class="px-5 py-3.5">Order # &amp; Date</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Service &amp; Specs</th>
                        <th class="px-5 py-3.5">Assigned Facility</th>
                        <th class="px-5 py-3.5">Order Value</th>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-black dark:text-white">
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
                                'in_production', 'production' => 'bg-cyan-100 dark:bg-cyan-500/20 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30',
                                'ready_for_pickup'            => 'bg-teal-100 dark:bg-teal-500/20 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30',
                                'completed', 'claimed'        => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30',
                                'delayed'                     => 'bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30',
                                default                       => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                            };

                            $statusLabel = match($statusStr) {
                                'in_production', 'production' => 'On Press',
                                'ready_for_pickup'            => 'Ready Pickup',
                                'quality_checking'            => 'Quality Check',
                                default                       => ucfirst(str_replace('_', ' ', $statusStr)),
                            };
                        @endphp
                        <tr class="hover:bg-cyber-hover/50 transition">
                            
                            {{-- Order # & Date --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-black dark:text-white text-xs block tracking-tight">
                                    #{{ $o->order_number }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-0.5">
                                    {{ $o->created_at ? $o->created_at->format('M d, Y') : 'Active' }}
                                    <span class="text-[9px] text-slate-400">({{ $o->created_at ? $o->created_at->diffForHumans() : '' }})</span>
                                </span>
                            </td>

                            {{-- Customer --}}
                            <td class="px-5 py-4 min-w-[150px]">
                                <span class="font-bold text-black dark:text-white block truncate max-w-[180px] text-xs leading-snug">
                                    {{ $customerName }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block truncate max-w-[180px] mt-0.5">
                                    {{ $customerEmail }}
                                </span>
                            </td>

                            {{-- Service & Specs (Clean Black Text) --}}
                            <td class="px-5 py-4 min-w-[160px]">
                                <span class="font-semibold text-black dark:text-slate-200 block truncate max-w-[190px] text-[11px]">
                                    {{ $service }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block font-mono mt-0.5">
                                    {{ number_format($qty) }} pcs
                                    @if($paper) &bull; {{ $paper }} @endif
                                    @if($size) &bull; {{ $size }} @endif
                                </span>
                            </td>

                            {{-- Assigned Facility --}}
                            <td class="px-5 py-4 min-w-[160px]">
                                <span class="font-semibold text-cyan-600 dark:text-cyan-400 block text-xs leading-tight">
                                    {{ $branch }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block font-mono mt-0.5">
                                    {{ $o->productionJob->machine->name ?? 'Standard Press Unit' }}
                                </span>
                            </td>

                            {{-- Order Value & Payment --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-bold text-black dark:text-white text-xs block font-mono">
                                    ₱{{ number_format($orderValue, 2) }}
                                </span>
                                <span class="text-[9px] font-bold uppercase tracking-wider block mt-0.5 {{ match($o->payment_status) {
                                    'confirmed', 'paid' => 'text-emerald-600 dark:text-emerald-400',
                                    'submitted'         => 'text-amber-600 dark:text-amber-400',
                                    default             => 'text-slate-400'
                                } }}">
                                    {{ $o->payment_status_label ?? ucfirst($o->payment_status ?? 'Pending') }}
                                </span>
                            </td>

                            {{-- Priority (Clean Unboxed Text for Normal) --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(in_array($priority, ['normal', 'standard', 'low']))
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 font-mono">
                                        {{ $priority }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase font-mono border {{ match($priority) {
                                        'urgent' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                                        'rush'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border-amber-500/30',
                                        default  => 'bg-slate-100 text-slate-600 border-slate-200',
                                    } }}">
                                        {{ $priority }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusBadge }} font-mono">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('management.orders.show', $o) }}" 
                                   class="group h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 border border-cyan-500/25 hover:border-cyan-500 inline-flex items-center justify-center transition-all duration-200 shadow-xs"
                                   title="View Order Details">
                                    <i class="fa-solid fa-eye text-xs text-cyan-600 dark:text-cyan-400 group-hover:text-white dark:group-hover:text-slate-950 transition-colors"></i>
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
                Showing <span class="font-bold text-black dark:text-white">{{ $orders->firstItem() }}</span> to <span class="font-bold text-black dark:text-white">{{ $orders->lastItem() }}</span> of <span class="font-bold text-black dark:text-white">{{ $orders->total() }}</span> orders
            </span>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
