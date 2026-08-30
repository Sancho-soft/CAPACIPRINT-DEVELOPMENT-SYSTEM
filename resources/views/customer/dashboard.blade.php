@extends('layouts.customer')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HERO WELCOME BANNER (ALIGNED WITH SUPER ADMIN CONTROL CENTER) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        {{-- Ambient cyan glow in background --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            {{-- Left Title & Icon --}}
            <div class="flex items-center gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-[0_0_25px_rgba(6,182,212,0.25)] shrink-0">
                    <i class="fa-solid fa-user-circle"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                    <p class="text-xs text-slate-400 mt-1">Submit artwork files, receive instant quotations, and monitor your print jobs routed to optimised branches.</p>
                </div>
            </div>

            {{-- Right Controls: Quick Navigation Actions --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('customer.print-requests.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Submit Print Request
                </a>
                <a href="{{ route('customer.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-magnifying-glass text-xs text-cyan-400"></i> View My Orders
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3 KPI METRICS WITH COLORED ACCENTS (ALIGNED WITH SUPER ADMIN UI) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        {{-- Card 1: Active Orders (Cyan) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-boxes-stacked text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">ACTIVE ORDERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $activeOrdersCount }}</div>
            </div>
        </div>

        {{-- Card 2: Pending Quotations (Amber) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-file-invoice-dollar text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">PENDING QUOTATIONS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $pendingQuotesCount }}</div>
            </div>
        </div>

        {{-- Card 3: Completed Orders (Emerald) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-clipboard-check text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">COMPLETED ORDERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $completedOrdersCount }}</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LATEST ORDER TRACKER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($latestOrder)
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 bg-[#0D1520] border-b border-slate-800/80 flex items-center justify-between">
            <h3 class="font-bold text-white flex items-center gap-2 text-sm">
                <i class="fa-solid fa-map-location-dot text-cyan-400"></i> Latest Order Progress
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
                    <h4 class="font-bold text-white text-base">{{ $latestOrder->printRequest->service ?? '—' }}</h4>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Qty: {{ $latestOrder->printRequest->quantity ?? '—' }} &middot;
                        {{ $latestOrder->printRequest->size ?? '' }}
                    </p>
                </div>
                <span class="px-3 py-1 text-xs font-bold rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    {{ $latestOrder->status_label }}
                </span>
            </div>

            {{-- Progress bar & steps --}}
            <div class="relative">
                <div class="absolute top-4 left-0 right-0 h-1 bg-slate-800 z-0"></div>
                <div class="absolute top-4 left-0 h-1 bg-cyan-400 z-0 transition-all duration-700 shadow-[0_0_10px_rgba(6,182,212,0.5)]" style="width: {{ $pct }}%"></div>
                <div class="relative flex justify-between z-10">
                    @foreach($labels as $i => $label)
                    <div class="flex flex-col items-center">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                            {{ $i < $current ? 'bg-cyan-400 border-cyan-400 text-slate-950' : ($i === $current ? 'bg-cyan-500 border-cyan-500 text-slate-950 ring-4 ring-cyan-500/30' : 'bg-[#0D1520] border-slate-700 text-slate-500') }}">
                            <i class="fa-solid {{ $icons[$i] ?? 'fa-circle' }}"></i>
                        </div>
                        <span class="hidden md:block text-[10px] font-semibold text-slate-400 mt-2 text-center leading-tight max-w-[56px]">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 p-4 bg-[#0D1520] border border-slate-800/80 rounded-xl flex items-center gap-4">
                <i class="fa-solid fa-circle-info text-cyan-400 text-lg shrink-0"></i>
                <div class="flex-1">
                    <p class="text-sm font-bold text-white">Current Status: <span class="text-cyan-400">{{ $latestOrder->status_label }}</span></p>
                    @if($latestOrder->assigned_branch)
                        <p class="text-xs text-slate-400 mt-0.5">Branch: {{ $latestOrder->assigned_branch }}</p>
                    @endif
                    @if($latestOrder->estimated_completion)
                        <p class="text-xs text-slate-400 mt-0.5">Est. Completion: {{ $latestOrder->estimated_completion->format('M d, Y') }}</p>
                    @endif
                </div>
                <a href="{{ route('customer.orders.tracking', $latestOrder) }}"
                   class="shrink-0 text-xs text-cyan-400 hover:text-cyan-300 font-bold transition flex items-center gap-1">
                    Track &rarr;
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl p-10 text-center">
        <i class="fa-solid fa-circle-question text-slate-600 text-4xl mb-3"></i>
        <h4 class="font-bold text-white">No active orders yet</h4>
        <p class="text-sm text-slate-400 mt-1">Submit your first print request to get started.</p>
        <a href="{{ route('customer.print-requests.create') }}"
           class="inline-block mt-4 px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs transition shadow-[0_0_20px_rgba(6,182,212,0.35)]">
            Submit Print Request
        </a>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- RECENT ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($orders->count())
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden">
        <div class="px-6 py-4 bg-[#0D1520] border-b border-slate-800/80 flex items-center justify-between">
            <h3 class="font-bold text-white text-sm">Recent Orders</h3>
            <a href="{{ route('customer.orders.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold">View all &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800/80 text-sm">
                <thead class="bg-[#0D1520]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach($orders as $order)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-white">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $order->printRequest->service ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded uppercase
                                {{ match($order->status) {
                                    'submitted'   => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                    'quotation'   => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                    'payment'     => 'bg-orange-500/10 text-orange-400 border border-orange-500/20',
                                    'production'  => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
                                    'completed'   => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                    'ready_for_pickup' => 'bg-teal-500/10 text-teal-400 border border-teal-500/20',
                                    'claimed'     => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
                                    default       => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
                                } }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-cyan-400 hover:bg-cyan-500/10 transition"
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
