@extends('layouts.customer')
@section('title', 'Order Tracking #' . $order->order_number)
@section('page-title', 'Order Tracking')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wide text-cyber-sub block mb-1">Production Telemetry</span>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Live Order Tracking</h2>
            <p class="text-xs text-cyber-muted mt-0.5 font-sans tabular-nums">Order Reference #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('customer.orders.show', $order) }}"
           class="text-xs text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold flex items-center gap-1.5 transition shrink-0">
            <span>Order Details</span>
            <i class="fa-solid fa-arrow-right text-[11px]"></i>
        </a>
    </div>

    {{-- Order Summary Banner --}}
    <div class="bg-cyber-card border border-cyber border-l-2 border-l-brand-600 dark:border-l-blue-500 rounded-xl p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-cyber-muted font-sans tabular-nums font-semibold uppercase tracking-wider mb-1">Print Service</p>
                <h3 class="text-lg font-bold text-cyber-main font-display">{{ $order->printRequest->service ?? 'Print Order' }}</h3>
                <p class="text-xs font-sans tabular-nums text-cyber-muted mt-1 tabular-nums">
                    {{ $order->printRequest->quantity ?? '' }} copies &middot;
                    {{ $order->printRequest->size ?? '' }} &middot;
                    {{ $order->printRequest->material ?? '' }}
                </p>
            </div>
            <div class="sm:text-right border-t sm:border-t-0 border-cyber-sub pt-3 sm:pt-0">
                <p class="text-[11px] text-cyber-muted font-sans tabular-nums font-semibold uppercase tracking-wider mb-1">Assigned Branch</p>
                <p class="font-semibold text-brand-600 dark:text-blue-400">{{ $order->assigned_branch ?? 'Branch routing in progress...' }}</p>
                @if($order->estimated_completion)
                    <p class="text-xs text-cyber-muted mt-1 font-sans tabular-nums">Est. Completion: {{ $order->estimated_completion->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-7">
        <div class="flex items-center justify-between mb-7 border-b border-cyber-sub pb-4">
            <h3 class="font-bold text-cyber-main text-base font-display flex items-center gap-2">
                <i class="fa-solid fa-timeline text-brand-600 dark:text-blue-400"></i> Production &amp; Handover Timeline
            </h3>
            <span class="px-2.5 py-1 rounded text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wider bg-brand-600/10 dark:bg-blue-500/15 text-brand-700 dark:text-blue-300 border border-brand-600/25 dark:border-blue-500/30">
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
                ['key' => 'claimed',             'label' => 'Order Claimed',                 'icon' => 'fa-handshake',           'desc' => 'Package handed over to client. Thank you for choosing CAPACIPRINT.'],
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
            <div class="flex items-start gap-5 relative {{ !$loop->last ? 'pb-7' : '' }}">
                {{-- Connector Line --}}
                @if(!$loop->last)
                <div class="absolute left-[19px] top-10 w-px bottom-0 transition-colors duration-200
                    {{ $isDone ? 'bg-brand-600 dark:bg-blue-500' : 'bg-cyber' }}" style="{{ !$isDone ? 'background-color: var(--border-main);' : '' }}"></div>
                @endif

                {{-- Checkpoint Node --}}
                <div class="h-10 w-10 rounded-lg border flex items-center justify-center text-sm shrink-0 z-10 transition-colors
                    {{ $isDone    ? 'bg-brand-600 dark:bg-blue-600 border-brand-600 dark:border-blue-500 text-white'
                     : ($isCurrent ? 'bg-brand-600 dark:bg-blue-600 border-brand-600 dark:border-blue-500 text-white ring-2 ring-brand-600/25 dark:ring-blue-500/30'
                     : 'bg-cyber-sub border-cyber text-cyber-sub') }}">
                    @if($isDone)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                    @endif
                </div>

                {{-- Description & Label --}}
                <div class="flex-1 pt-1">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <p class="font-bold text-sm {{ $isCurrent ? 'text-brand-600 dark:text-blue-400 font-display' : ($isDone ? 'text-cyber-main' : 'text-cyber-muted') }}">
                            {{ $step['label'] }}
                        </p>
                        @if($isCurrent)
                            <span class="text-[11px] font-sans tabular-nums font-semibold uppercase px-2 py-0.5 rounded bg-brand-600 text-white">
                                In Progress
                            </span>
                        @endif
                        @if($isDone)
                            <span class="text-[11px] font-sans tabular-nums font-semibold text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-[11px]"></i> Complete
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
    <div class="bg-cyber-card border border-brand-600/40 dark:border-blue-500/40 rounded-xl p-6 text-center space-y-4">
        <div class="h-12 w-12 mx-auto rounded-lg bg-brand-600/10 dark:bg-blue-500/15 border border-brand-600/25 dark:border-blue-500/30 text-brand-600 dark:text-blue-400 flex items-center justify-center text-xl">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div>
            <h3 class="font-bold text-cyber-main text-base font-display">Your Order Pickup Pass</h3>
            <p class="text-xs text-cyber-muted mt-1">Present your electronic QR code pass or Claim Reference to the branch counter staff.</p>
        </div>
        <div>
            <a href="{{ route('customer.claiming.show', $order->id) }}"
               class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-lg text-xs tracking-wide transition-colors">
                <i class="fa-solid fa-qrcode text-sm"></i>
                <span>View Electronic Claim Pass</span>
            </a>
        </div>
    </div>
    @endif

    <div class="pt-2">
        <a href="{{ route('customer.orders.index') }}"
           class="inline-flex items-center gap-2 text-xs text-cyber-muted hover:text-brand-600 dark:hover:text-blue-400 font-semibold transition">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to My Orders</span>
        </a>
    </div>

</div>
@endsection
