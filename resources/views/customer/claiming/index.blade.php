@extends('layouts.customer')
@section('title', 'QR Claiming')
@section('page-title', 'QR / Claiming')

@section('content')
<div class="space-y-6 w-full">
    <div>
        <h2 class="text-2xl font-black text-cyber-main font-display">My Claim References</h2>
        <p class="text-sm text-cyber-muted mt-1">Present your QR code or unique claim code when collecting your completed order.</p>
    </div>

    @if($claims->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl p-16 text-center shadow-xl">
        <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base">No claim references yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Claim references and QR codes will automatically appear here when your order is ready for pickup.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($claims as $claim)
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden hover:border-cyan-500/40 transition">
            <div class="px-5 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub/30">
                <div>
                    <p class="text-[10px] uppercase font-bold text-cyber-muted tracking-wider">Order</p>
                    <p class="font-black text-cyber-main font-display text-base">#{{ $claim->order->order_number ?? '—' }}</p>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg uppercase tracking-wider {{ $claim->is_claimed ? 'bg-slate-500/15 text-slate-400 border border-slate-500/20' : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' }}">
                    {{ $claim->is_claimed ? 'Claimed' : 'Ready for Pickup' }}
                </span>
            </div>
            <div class="p-5 space-y-4">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Service</span>
                        <span class="font-bold text-cyber-main">{{ $claim->order->printRequest->service ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Branch</span>
                        <span class="font-bold text-cyber-main">{{ $claim->pickup_branch ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Completion</span>
                        <span class="font-bold text-cyber-main">{{ $claim->completion_date?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    @if($claim->is_claimed)
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Claimed At</span>
                        <span class="font-bold text-cyber-main">{{ $claim->claimed_at?->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                </div>
                @if(!$claim->is_claimed)
                <a href="{{ route('customer.claiming.show', $claim->order_id) }}"
                   class="block w-full text-center bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                    <i class="fa-solid fa-qrcode mr-1.5"></i> View QR Code
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
