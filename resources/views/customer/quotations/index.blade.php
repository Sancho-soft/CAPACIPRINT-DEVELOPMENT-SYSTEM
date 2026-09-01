@extends('layouts.customer')
@section('title', 'Quotations')
@section('page-title', 'Quotations')

@section('content')
<div class="space-y-6 w-full">
    <div>
        <h2 class="text-2xl font-black text-cyber-main font-display">My Quotations</h2>
        <p class="text-sm text-cyber-muted mt-1">Review pricing generated for your print requests.</p>
    </div>

    @if($quotations->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl p-16 text-center shadow-xl">
        <div class="h-16 w-16 mx-auto rounded-2xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-2xl mb-4">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base">No quotations yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Quotations will appear here once your print requests are reviewed by our sales team.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($quotations as $quotation)
        <div class="bg-cyber-card border border-cyber rounded-2xl shadow-xl flex flex-col overflow-hidden hover:border-cyan-500/40 transition">
            <div class="p-6 flex-1 space-y-4">
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-cyber-muted tracking-wider">Quotation No.</p>
                        <h4 class="text-base font-black text-cyber-main font-display">#{{ $quotation->quotation_number }}</h4>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider {{ $quotation->status_badge_class }}">
                        {{ $quotation->status_label }}
                    </span>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Service</span>
                        <span class="font-bold text-cyber-main">{{ $quotation->printRequest->service ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Quantity &amp; Size</span>
                        <span class="font-bold text-cyber-main">{{ $quotation->printRequest->quantity ?? '—' }} copies · {{ $quotation->printRequest->size ?? '' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber/50">
                        <span class="text-cyber-muted">Valid Until</span>
                        <span class="font-bold text-cyber-main">{{ $quotation->valid_until?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="border-t border-cyber pt-4 flex items-center justify-between">
                    <span class="text-xs font-bold text-cyber-muted">Total (VAT Inc.)</span>
                    <span class="text-xl font-black text-cyan-400 font-display">₱{{ number_format($quotation->total_price, 2) }}</span>
                </div>
            </div>

            @if($quotation->status === 'pending')
            <div class="border-t border-cyber bg-cyber-sub/40 px-6 py-3.5 flex gap-3">
                <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Decline this quotation?')"
                            class="w-full border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-red-400 font-bold py-2 rounded-xl text-xs transition">
                        Decline
                    </button>
                </form>
                <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black py-2 rounded-xl text-xs uppercase tracking-wider transition shadow-[0_0_12px_rgba(6,182,212,0.3)]">
                        Confirm &amp; Pay
                    </button>
                </form>
            </div>
            @else
            <div class="border-t border-cyber bg-cyber-sub/40 px-6 py-3.5 flex items-center justify-between">
                <a href="{{ route('customer.quotations.show', $quotation) }}"
                   class="text-xs text-cyan-500 hover:text-cyan-400 font-bold flex items-center gap-1">
                    <span>View Details</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $quotations->links() }}</div>
    @endif
</div>
@endsection
