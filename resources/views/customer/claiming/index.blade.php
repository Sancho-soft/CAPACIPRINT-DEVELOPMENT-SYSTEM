@extends('layouts.customer')
@section('title', 'QR Claiming')
@section('page-title', 'QR / Claiming')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">My Claim References</h2>
        <p class="text-sm text-slate-500 mt-1">Present your QR code when collecting your completed order.</p>
    </div>

    @if($claims->isEmpty())
    <div class="bg-white border border-slate-100 rounded-xl p-12 text-center shadow-sm">
        <i class="fa-solid fa-qrcode text-slate-300 text-4xl mb-3"></i>
        <h4 class="font-bold text-navy-900">No claim references yet</h4>
        <p class="text-sm text-slate-500 mt-1">Claim references are generated when your order is ready for pickup.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @foreach($claims as $claim)
        <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Order</p>
                    <p class="font-bold text-navy-900">#{{ $claim->order->order_number ?? '—' }}</p>
                </div>
                <span class="px-2.5 py-0.5 text-[10px] font-bold rounded uppercase {{ $claim->is_claimed ? 'bg-slate-100 text-slate-500' : 'bg-teal-100 text-teal-700' }}">
                    {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                </span>
            </div>
            <div class="p-5 space-y-3">
                <div class="space-y-1 text-xs text-slate-500">
                    <p>Service: <strong class="text-navy-900">{{ $claim->order->printRequest->service ?? '—' }}</strong></p>
                    <p>Branch: <strong class="text-navy-900">{{ $claim->pickup_branch ?? '—' }}</strong></p>
                    <p>Completion: <strong class="text-navy-900">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</strong></p>
                    @if($claim->is_claimed)
                    <p>Claimed: <strong class="text-navy-900">{{ $claim->claimed_at?->format('M d, Y h:i A') }}</strong></p>
                    @endif
                </div>
                @if(!$claim->is_claimed)
                <a href="{{ route('customer.claiming.show', $claim->order_id) }}"
                   class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                    <i class="fa-solid fa-qrcode mr-2"></i> View QR Code
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
