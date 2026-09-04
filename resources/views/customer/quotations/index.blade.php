@extends('layouts.customer')
@section('title', 'My Quotations')
@section('page-title', 'Quotations')

@section('content')
<div x-data="{ viewMode: 'table', search: '' }" class="space-y-6 w-full">
    
    {{-- Header & View Controls --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-cyber-card border border-cyber p-5 rounded-2xl shadow-xl">
        <div>
            <h2 class="text-2xl font-black text-cyber-main font-display flex items-center gap-2.5">
                <i class="fa-solid fa-file-invoice-dollar text-cyan-400"></i>
                My Quotations
            </h2>
            <p class="text-xs text-cyber-muted mt-1">Review official pricing quotes and manage confirmations for your print requests.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- View Toggle --}}
            <div class="flex items-center p-1 bg-cyber-sub rounded-xl border border-cyber">
                <button type="button" @click="viewMode = 'table'"
                        class="py-1.5 px-3 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        :class="viewMode === 'table' ? 'bg-cyan-500 text-slate-950 font-black shadow' : 'text-cyber-muted hover:text-cyber-main'">
                    <i class="fa-solid fa-list text-[11px]"></i> Table View
                </button>
                <button type="button" @click="viewMode = 'grid'"
                        class="py-1.5 px-3 rounded-lg text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                        :class="viewMode === 'grid' ? 'bg-cyan-500 text-slate-950 font-black shadow' : 'text-cyber-muted hover:text-cyber-main'">
                    <i class="fa-solid fa-border-all text-[11px]"></i> Grid Cards
                </button>
            </div>
        </div>
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

    {{-- TABLE VIEW --}}
    <div x-show="viewMode === 'table'" class="bg-cyber-card border border-cyber rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-cyber-sub/80 border-b border-cyber text-cyber-muted uppercase tracking-wider font-bold text-[11px]">
                        <th class="py-4 px-6">Quotation No.</th>
                        <th class="py-4 px-6">Service Details</th>
                        <th class="py-4 px-6">Quantity &amp; Size</th>
                        <th class="py-4 px-6">Valid Until</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Total Price</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/40 text-cyber-main font-medium">
                    @foreach($quotations as $quotation)
                    <tr class="hover:bg-cyber-sub/30 transition">
                        {{-- Quotation No. --}}
                        <td class="py-4 px-6 whitespace-nowrap">
                            <a href="{{ route('customer.quotations.show', $quotation) }}" class="font-black text-sm text-cyber-main hover:text-cyan-400 font-display transition">
                                #{{ $quotation->quotation_number }}
                            </a>
                            <p class="text-[10px] text-cyber-muted mt-0.5">Created {{ $quotation->created_at->format('M d, Y') }}</p>
                        </td>

                        {{-- Service Details --}}
                        <td class="py-4 px-6">
                            <span class="font-bold text-cyber-main text-sm block">{{ $quotation->printRequest->service ?? '—' }}</span>
                            <span class="text-[11px] text-cyber-muted">{{ $quotation->printRequest->material ?? 'Standard Material' }}</span>
                        </td>

                        {{-- Quantity & Size --}}
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="font-bold block text-cyber-main">{{ $quotation->printRequest->quantity ?? '—' }} copies</span>
                            <span class="text-[11px] text-cyan-400 font-semibold">{{ $quotation->printRequest->size ?? 'Standard' }}</span>
                        </td>

                        {{-- Valid Until --}}
                        <td class="py-4 px-6 whitespace-nowrap text-cyber-muted">
                            <i class="fa-regular fa-calendar-clock mr-1 text-cyan-400"></i>
                            {{ $quotation->valid_until?->format('M d, Y') ?? 'N/A' }}
                        </td>

                        {{-- Status Badge --}}
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider inline-block {{ $quotation->status_badge_class }}">
                                {{ $quotation->status_label }}
                            </span>
                        </td>

                        {{-- Total Price --}}
                        <td class="py-4 px-6 whitespace-nowrap text-right">
                            <span class="text-base font-black text-cyan-400 font-display">₱{{ number_format($quotation->total_price, 2) }}</span>
                            <span class="block text-[10px] text-cyber-muted">VAT Inc.</span>
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($quotation->status === 'pending')
                                <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-3 py-1.5 rounded-lg text-xs uppercase tracking-wider transition shadow">
                                        Confirm
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Decline this quotation?')"
                                            class="border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-red-400 font-bold px-2.5 py-1.5 rounded-lg text-xs transition">
                                        Decline
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('customer.quotations.show', $quotation) }}"
                                   class="p-1.5 text-cyber-muted hover:text-cyan-400 transition" title="View Details">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRID CARDS VIEW --}}
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
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
