@extends('layouts.customer')
@section('title', 'Order Tracking #' . $order->order_number)
@section('page-title', 'Order Tracking')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-cyber-main font-display">Live Order Tracking</h2>
            <p class="text-xs text-cyber-muted mt-0.5 font-mono">Order Reference #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('customer.orders.show', $order) }}"
           class="text-xs text-cyan-400 hover:text-cyan-300 font-bold flex items-center gap-1.5 transition">
            <span>Order Details</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    {{-- Order Summary Banner --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-[10px] text-cyber-muted font-bold uppercase tracking-wider mb-1">Print Service</p>
                <h3 class="text-lg font-black text-cyber-main font-display">{{ $order->printRequest->service ?? 'Print Order' }}</h3>
                <p class="text-xs text-cyber-muted mt-1">
                    {{ $order->printRequest->quantity ?? '' }} copies &middot;
                    {{ $order->printRequest->size ?? '' }} &middot;
                    {{ $order->printRequest->material ?? '' }}
                </p>
            </div>
            <div class="sm:text-right border-t sm:border-t-0 border-cyber/60 pt-3 sm:pt-0">
                <p class="text-[10px] text-cyber-muted font-bold uppercase tracking-wider mb-1">Assigned Branch</p>
                <p class="font-bold text-cyan-400">{{ $order->assigned_branch ?? 'Branch routing in progress...' }}</p>
                @if($order->estimated_completion)
                    <p class="text-xs text-cyber-muted mt-1 font-mono">Est. Completion: {{ $order->estimated_completion->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl p-6 sm:p-8">
        <div class="flex items-center justify-between mb-8 border-b border-cyber/80 pb-4">
            <h3 class="font-black text-cyber-main text-base font-display flex items-center gap-2">
                <i class="fa-solid fa-timeline text-cyan-400"></i> Production &amp; Handover Timeline
            </h3>
            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">
                {{ $order->status_label }}
            </span>
        </div>

        @php
            $steps = [
                ['key' => 'submitted',          'label' => 'Request Submitted',             'icon' => 'fa-file-arrow-up',       'desc' => 'Your print request and specifications have been received.'],
                ['key' => 'quotation',           'label' => 'Quotation Generated',           'icon' => 'fa-file-invoice-dollar', 'desc' => 'Official pricing estimate prepared and confirmed.'],
                ['key' => 'payment',             'label' => 'Payment Confirmed',             'icon' => 'fa-credit-card',         'desc' => 'Transaction verified and receipt recorded.'],
                ['key' => 'branch_recommended',  'label' => 'Branch Assigned',               'icon' => 'fa-code-branch',         'desc' => 'Allocated to optimum branch based on machine line capacity.'],
                ['key' => 'production',          'label' => 'In Production',                 'icon' => 'fa-industry',            'desc' => 'Substrates queued and press execution underway on shop-floor.'],
                ['key' => 'completed',           'label' => 'Production Completed',          'icon' => 'fa-circle-check',        'desc' => 'Print run finished, quality inspected, and packaged.'],
                ['key' => 'ready_for_pickup',    'label' => 'Ready for Pickup',              'icon' => 'fa-truck-ramp-box',      'desc' => 'Order is ready at branch counter. Please present your claim pass.'],
                ['key' => 'claimed',             'label' => 'Order Claimed',                 'icon' => 'fa-handshake',           'desc' => 'Package handed over to client. Thank you for choosing CAPACIPRINT!'],
            ];
            $allStatuses = \App\Models\Order::statusSteps();
            $currentIdx  = array_search($order->status, $allStatuses);
            if ($currentIdx === false) $currentIdx = 0;
        @endphp

        <div class="relative space-y-0">
            @foreach($steps as $i => $step)
            @php
                $stepIdx = array_search($step['key'], $allStatuses);
                if ($stepIdx === false) $stepIdx = $i;
                $isDone    = $stepIdx < $currentIdx;
                $isCurrent = $stepIdx === $currentIdx;
                $isPending = $stepIdx > $currentIdx;
            @endphp
            <div class="flex items-start gap-5 relative {{ !$loop->last ? 'pb-8' : '' }}">
                {{-- Connector Line --}}
                @if(!$loop->last)
                <div class="absolute left-[19px] top-10 w-0.5 bottom-0 transition-colors duration-500
                    {{ $isDone ? 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.4)]' : 'bg-cyber-sub' }}"></div>
                @endif

                {{-- Checkpoint Node --}}
                <div class="h-10 w-10 rounded-full border-2 flex items-center justify-center text-sm shrink-0 z-10 transition-all duration-300
                    {{ $isDone    ? 'bg-cyan-500 border-cyan-400 text-slate-950 font-bold shadow-[0_0_10px_rgba(6,182,212,0.3)]'
                     : ($isCurrent ? 'bg-cyan-400 border-cyan-300 text-slate-950 ring-4 ring-cyan-500/30 scale-110 shadow-[0_0_15px_rgba(6,182,212,0.5)]'
                     : 'bg-cyber-sub border-cyber text-cyber-muted') }}">
                    @if($isDone)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                    @endif
                </div>

                {{-- Description & Label --}}
                <div class="flex-1 pt-1">
                    <div class="flex items-center gap-3">
                        <p class="font-bold text-sm {{ $isCurrent ? 'text-cyan-400 font-display' : ($isDone ? 'text-cyber-main' : 'text-cyber-muted') }}">
                            {{ $step['label'] }}
                        </p>
                        @if($isCurrent)
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-cyan-500 text-slate-950 shadow-sm animate-pulse">
                                In Progress
                            </span>
                        @endif
                        @if($isDone)
                            <span class="text-[9px] font-bold text-emerald-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[8px]"></i> Complete
                            </span>
                        @endif
                    </div>
                    <p class="text-xs mt-0.5 {{ $isPending ? 'text-cyber-sub' : 'text-cyber-muted' }} leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Claim Reference Pass Card --}}
    @if($order->claimReference && in_array($order->status, ['ready_for_pickup', 'claimed']))
    <div class="bg-cyber-card border border-cyan-500/40 rounded-3xl p-6 sm:p-7 text-center shadow-2xl space-y-4">
        <div class="h-14 w-14 mx-auto rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div>
            <h3 class="font-black text-cyber-main text-base font-display">Your Order Pickup Pass</h3>
            <p class="text-xs text-cyber-muted mt-1">Present your electronic QR code pass or Claim Reference to the branch counter staff.</p>
        </div>
        <div>
            <a href="{{ route('customer.claiming.show', $order->id) }}"
               class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-7 py-3 rounded-2xl text-xs uppercase tracking-wider transition shadow-[0_0_18px_rgba(6,182,212,0.4)]">
                <i class="fa-solid fa-qrcode text-sm"></i>
                <span>View Electronic Claim Pass</span>
            </a>
        </div>
    </div>
    @endif

    <div class="pt-2">
        <a href="{{ route('customer.orders.index') }}"
           class="inline-flex items-center gap-2 text-xs text-cyber-muted hover:text-cyan-400 font-bold transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Back to My Orders</span>
        </a>
    </div>

</div>
@endsection
