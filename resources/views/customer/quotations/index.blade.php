@extends('layouts.customer')
@section('title', 'Quotations')
@section('page-title', 'Quotations')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">My Quotations</h2>
        <p class="text-sm text-slate-500 mt-1">Review pricing generated for your print requests.</p>
    </div>

    @if($quotations->isEmpty())
    <div class="bg-white border border-slate-100 rounded-xl p-12 text-center shadow-sm">
        <i class="fa-solid fa-file-invoice-dollar text-slate-300 text-4xl mb-3"></i>
        <h4 class="font-bold text-navy-900">No quotations yet</h4>
        <p class="text-sm text-slate-500 mt-1">Quotations are generated once your print request is reviewed.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($quotations as $quotation)
        <div class="bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <div class="p-5 flex-1 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Quotation No.</p>
                        <h4 class="text-base font-bold text-navy-900">#{{ $quotation->quotation_number }}</h4>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-black rounded uppercase {{ $quotation->status_badge_class }}">
                        {{ $quotation->status_label }}
                    </span>
                </div>

                <div class="space-y-1 text-xs text-slate-500">
                    <p>Service: <strong class="text-navy-900">{{ $quotation->printRequest->service ?? '—' }}</strong></p>
                    <p>Qty: <strong class="text-navy-900">{{ $quotation->printRequest->quantity ?? '—' }} copies · {{ $quotation->printRequest->size ?? '' }}</strong></p>
                    <p>Valid until: <strong class="text-navy-900">{{ $quotation->valid_until?->format('M d, Y') ?? 'N/A' }}</strong></p>
                </div>

                <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Total (VAT Inc.)</span>
                    <span class="text-lg font-black text-navy-900 font-display">₱{{ number_format($quotation->total_price, 2) }}</span>
                </div>
            </div>

            @if($quotation->status === 'pending')
            <div class="border-t border-slate-100 bg-slate-50 px-5 py-3 flex gap-3">
                <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Decline this quotation?')"
                            class="w-full border border-slate-200 hover:bg-slate-100 text-slate-700 font-medium py-2 rounded text-xs transition">
                        Decline
                    </button>
                </form>
                <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-2 rounded text-xs transition shadow">
                        Confirm &amp; Pay
                    </button>
                </form>
            </div>
            @else
            <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
                <a href="{{ route('customer.quotations.show', $quotation) }}"
                   class="text-xs text-brand-500 hover:text-brand-700 font-semibold">View Details &rarr;</a>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $quotations->links() }}</div>
    @endif
</div>
@endsection
