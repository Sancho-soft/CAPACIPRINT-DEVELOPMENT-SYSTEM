@extends('layouts.internal')
@section('title', 'Order #' . $order->order_number . ' — Executive Management')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto font-sans">

    {{-- Top Header & Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl">
        <div class="flex items-center gap-4">
            <a href="{{ route('management.orders.index') }}" 
               class="h-10 w-10 rounded-2xl bg-cyber-sub/60 hover:bg-cyber-sub border border-cyber flex items-center justify-center text-cyber-main hover:text-cyan-400 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-black text-black dark:text-white font-display tracking-tight">Order #{{ $order->order_number }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider font-mono {{ match($order->status) {
                        'production', 'in_production' => 'bg-cyan-100 dark:bg-cyan-500/20 text-cyan-800 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-500/30',
                        'ready_for_pickup'            => 'bg-teal-100 dark:bg-teal-500/20 text-teal-800 dark:text-teal-300 border border-teal-200 dark:border-teal-500/30',
                        'completed', 'claimed'        => 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30',
                        default                       => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
                    } }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Placed {{ $order->created_at->format('M d, Y · h:i A') }} ({{ $order->created_at->diffForHumans() }})
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" onclick="window.print()" 
                    class="px-4 py-2 rounded-xl bg-cyber-sub/50 hover:bg-cyber-sub text-cyber-main border border-cyber text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-print"></i> Print Details
            </button>
        </div>
    </div>

    {{-- Main Grid: 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT 2 COLS: Print Specifications & Production Floor Status --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. Print Specifications Card --}}
            <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-5">
                <div class="flex items-center gap-2.5 border-b border-cyber/60 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-500 flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-black dark:text-white text-sm font-display">Print Specifications</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Order item and production finishing requirements</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="bg-cyber-sub/30 p-3.5 rounded-2xl border border-cyber/40">
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-mono block">Primary Service</span>
                        <span class="text-sm font-bold text-black dark:text-white block mt-0.5">{{ $order->printRequest->service ?? 'Commercial Printing' }}</span>
                    </div>

                    <div class="bg-cyber-sub/30 p-3.5 rounded-2xl border border-cyber/40">
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-mono block">Volume / Quantity</span>
                        <span class="text-sm font-bold text-black dark:text-white font-mono block mt-0.5">
                            {{ number_format($order->printRequest->quantity ?? 1) }} copies / pcs
                        </span>
                    </div>

                    <div class="bg-cyber-sub/30 p-3.5 rounded-2xl border border-cyber/40">
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-mono block">Material & Paper Stock</span>
                        <span class="text-xs font-semibold text-black dark:text-white block mt-0.5">
                            {{ $order->printRequest->paper_type ?? $order->printRequest->material ?? 'Standard Glossy 150gsm' }}
                        </span>
                    </div>

                    <div class="bg-cyber-sub/30 p-3.5 rounded-2xl border border-cyber/40">
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-mono block">Dimensions / Paper Size</span>
                        <span class="text-xs font-semibold text-black dark:text-white block mt-0.5">
                            {{ $order->printRequest->size ?? 'Standard' }}
                        </span>
                    </div>

                    <div class="sm:col-span-2 bg-cyber-sub/30 p-3.5 rounded-2xl border border-cyber/40">
                        <span class="text-slate-400 dark:text-slate-500 text-[10px] uppercase font-mono block">Finishing & Enhancements</span>
                        <span class="text-xs font-semibold text-black dark:text-white block mt-0.5">
                            {{ $order->printRequest->finishing ?? 'Matte Lamination / Standard Trim' }}
                        </span>
                    </div>

                    @if(!empty($order->printRequest->notes))
                    <div class="sm:col-span-2 bg-amber-500/5 p-3.5 rounded-2xl border border-amber-500/20">
                        <span class="text-amber-600 dark:text-amber-400 text-[10px] uppercase font-bold block">Customer Special Instructions</span>
                        <p class="text-xs text-slate-700 dark:text-slate-300 mt-1">{{ $order->printRequest->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 2. Shop Floor Routing & Machine Status --}}
            <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 border-b border-cyber/60 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-500 flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-black dark:text-white text-sm font-display">Facility Assignment & Production Route</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Intelligent press line allocation and scheduling</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-2xl bg-cyber-sub/20 border border-cyber/40">
                        <span class="text-slate-400 text-[10px] uppercase font-mono block">Assigned Printing Facility</span>
                        <span class="font-bold text-cyan-600 dark:text-cyan-400 text-xs block mt-1">
                            {{ $order->assigned_branch ?? ($order->productionJob->branch->name ?? 'Pending Allocation') }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-cyber-sub/20 border border-cyber/40">
                        <span class="text-slate-400 text-[10px] uppercase font-mono block">Machine / Press Line</span>
                        <span class="font-bold text-black dark:text-white text-xs block mt-1">
                            {{ $order->productionJob->machine->name ?? 'Standard Press Unit' }}
                        </span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-cyber-sub/20 border border-cyber/40">
                        <span class="text-slate-400 text-[10px] uppercase font-mono block">Assigned Technician</span>
                        <span class="font-bold text-black dark:text-white text-xs block mt-1">
                            {{ $order->productionJob->assignedTo->name ?? 'Floor Queue' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT 1 COL: Customer & Financial Summary --}}
        <div class="space-y-6">

            {{-- Customer Card --}}
            <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 border-b border-cyber/60 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-500 flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-black dark:text-white text-sm font-display">Customer Information</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Client details and contact</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-mono">Full Name</span>
                        <span class="text-black dark:text-white font-bold text-sm">{{ $order->user->name ?? 'Direct Customer' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-mono">Email Address</span>
                        <span class="text-black dark:text-white font-medium">{{ $order->user->email ?? '—' }}</span>
                    </div>

                    @if(!empty($order->user->phone))
                    <div>
                        <span class="text-slate-400 text-[10px] uppercase block font-mono">Phone</span>
                        <span class="text-black dark:text-white font-mono">{{ $order->user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Financial & Payment Card --}}
            <div class="bg-cyber-card border border-cyber rounded-3xl p-6 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 border-b border-cyber/60 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-black dark:text-white text-sm font-display">Payment & Billing</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Order invoice and settlement</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    @php
                        $totalVal = $order->quotation->total_price ?? ($order->payment->amount ?? 1250);
                    @endphp
                    <div class="bg-emerald-500/5 p-4 rounded-2xl border border-emerald-500/20">
                        <span class="text-slate-400 text-[10px] uppercase block font-mono">Total Order Amount</span>
                        <span class="text-2xl font-black text-black dark:text-white font-display">
                            ₱{{ number_format($totalVal, 2) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-cyber/40">
                        <span class="text-slate-400">Payment Status</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ match($order->payment_status) {
                            'confirmed', 'paid' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300',
                            'submitted'         => 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300',
                            default             => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'
                        } }}">
                            {{ $order->payment_status_label ?? ucfirst($order->payment_status ?? 'Pending') }}
                        </span>
                    </div>

                    @if($order->payment)
                    <div class="flex items-center justify-between py-1 text-slate-400">
                        <span>Payment Reference</span>
                        <span class="font-mono font-bold text-black dark:text-white">{{ $order->payment->payment_reference ?? '—' }}</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
