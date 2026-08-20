@extends('layouts.customer')
@section('title', 'Order Tracking #' . $order->order_number)
@section('page-title', 'Order Tracking')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Order Tracking</h2>
            <p class="text-sm text-slate-500 mt-1">Order #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('customer.orders.show', $order) }}"
           class="text-sm text-brand-500 hover:text-brand-700 font-semibold flex items-center gap-2">
            View Details &rarr;
        </a>
    </div>

    {{-- Order Summary Banner --}}
    <div class="bg-gradient-to-r from-navy-800 to-navy-700 text-white rounded-2xl p-6 border border-navy-900">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs text-navy-300 font-semibold uppercase tracking-wider mb-1">Print Service</p>
                <h3 class="text-lg font-bold font-display">{{ $order->printRequest->service ?? '—' }}</h3>
                <p class="text-sm text-navy-200 mt-1">
                    {{ $order->printRequest->quantity ?? '' }} copies &middot;
                    {{ $order->printRequest->size ?? '' }} &middot;
                    {{ $order->printRequest->material ?? '' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-navy-300 font-semibold uppercase tracking-wider mb-1">Branch</p>
                <p class="font-bold text-brand-300">{{ $order->assigned_branch ?? 'Routing in progress...' }}</p>
                @if($order->estimated_completion)
                    <p class="text-xs text-navy-300 mt-1">Est. {{ $order->estimated_completion->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-navy-900 mb-8">Progress Timeline</h3>

        @php
            $steps = [
                ['key' => 'submitted',          'label' => 'Request Submitted',             'icon' => 'fa-file-arrow-up',       'desc' => 'Your print request has been received by our system.'],
                ['key' => 'quotation',           'label' => 'Quotation Generated',           'icon' => 'fa-file-invoice-dollar', 'desc' => 'A quotation has been generated based on your specifications.'],
                ['key' => 'payment',             'label' => 'Payment Confirmed',             'icon' => 'fa-credit-card',         'desc' => 'Payment has been verified by our team.'],
                ['key' => 'branch_recommended',  'label' => 'Branch Assigned',               'icon' => 'fa-code-branch',         'desc' => 'Production branch has been assigned based on capacity analysis.'],
                ['key' => 'production',          'label' => 'In Production',                 'icon' => 'fa-industry',            'desc' => 'Your job is currently being produced at the assigned branch.'],
                ['key' => 'completed',           'label' => 'Production Completed',          'icon' => 'fa-circle-check',        'desc' => 'Your print job has been completed successfully.'],
                ['key' => 'ready_for_pickup',    'label' => 'Ready for Pickup',              'icon' => 'fa-truck-ramp-box',      'desc' => 'Your order is ready for collection. Please bring your QR claim reference.'],
                ['key' => 'claimed',             'label' => 'Order Claimed',                 'icon' => 'fa-handshake',           'desc' => 'Order has been collected. Thank you for choosing CAPACIPRINT!'],
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
                {{-- Connector --}}
                @if(!$loop->last)
                <div class="absolute left-[19px] top-10 w-0.5 bottom-0
                    {{ $isDone || $isCurrent ? 'bg-brand-400' : 'bg-slate-200' }}"></div>
                @endif

                {{-- Icon --}}
                <div class="h-10 w-10 rounded-full border-2 flex items-center justify-center text-sm shrink-0 z-10
                    {{ $isDone    ? 'bg-brand-400 border-brand-400 text-white'
                     : ($isCurrent ? 'bg-brand-500 border-brand-500 text-white ring-4 ring-brand-100'
                     : 'bg-white border-slate-200 text-slate-400') }}">
                    @if($isDone)
                        <i class="fa-solid fa-check text-xs"></i>
                    @else
                        <i class="fa-solid {{ $step['icon'] }}"></i>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 pt-1">
                    <div class="flex items-center gap-3">
                        <p class="font-bold text-sm {{ $isCurrent ? 'text-brand-600' : ($isDone ? 'text-navy-900' : 'text-slate-400') }}">
                            {{ $step['label'] }}
                        </p>
                        @if($isCurrent)
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-brand-100 text-brand-700">Current</span>
                        @endif
                        @if($isDone)
                            <span class="text-[10px] font-bold text-slate-400">Completed</span>
                        @endif
                    </div>
                    <p class="text-xs mt-0.5 {{ $isPending ? 'text-slate-300' : 'text-slate-500' }}">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Claim Reference --}}
    @if($order->claimReference && in_array($order->status, ['ready_for_pickup', 'claimed']))
    <div class="bg-white border border-teal-200 rounded-xl shadow-sm p-6 text-center">
        <h3 class="font-bold text-navy-900 mb-1">Your Claim Reference</h3>
        <p class="text-sm text-slate-500 mb-4">Present this QR code when collecting your order.</p>
        <a href="{{ route('customer.claiming.show', $order->id) }}"
           class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-bold px-6 py-3 rounded-lg text-sm transition">
            <i class="fa-solid fa-qrcode mr-2"></i> View QR Claim Reference
        </a>
    </div>
    @endif

    <a href="{{ route('customer.orders.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 font-medium">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to My Orders
    </a>
</div>
@endsection
