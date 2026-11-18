@extends('layouts.customer')
@section('title', 'My Quotations')
@section('page-title', 'Quotations')

@section('content')
<div x-data="{ viewMode: 'table', search: '' }" class="space-y-6 w-full">
    
    {{-- Header & View Controls --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-cyber-card border border-cyber p-5 rounded-xl">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight flex items-center gap-2.5">
                <i class="fa-solid fa-file-invoice-dollar text-brand-600 dark:text-blue-400"></i>
                My Quotations
            </h2>
            <p class="text-xs text-cyber-muted mt-1">Review official pricing quotes and manage confirmations for your print requests.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- View Toggle --}}
            <div class="flex items-center p-1 bg-cyber-sub rounded-lg border border-cyber">
                <button type="button" @click="viewMode = 'table'"
                        class="py-1.5 px-3 rounded-md text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer"
                        :class="viewMode === 'table' ? 'bg-brand-600 text-white' : 'text-cyber-muted hover:text-cyber-main'">
                    <i class="fa-solid fa-list text-[11px]"></i> Table View
                </button>
                <button type="button" @click="viewMode = 'grid'"
                        class="py-1.5 px-3 rounded-md text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer"
                        :class="viewMode === 'grid' ? 'bg-brand-600 text-white' : 'text-cyber-muted hover:text-cyber-main'">
                    <i class="fa-solid fa-border-all text-[11px]"></i> Grid Cards
                </button>
            </div>
        </div>
    </div>

    {{-- Filter, Search & Sorting Bar --}}
    <div class="bg-cyber-card border border-cyber p-4 rounded-xl">
        <form method="GET" action="{{ route('customer.quotations.index') }}" data-table-target="customer-quotations-results-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            {{-- Search Bar --}}
            <div class="md:col-span-2 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-muted">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotation #, service, material..."
                       class="w-full pl-9 pr-3 py-2 bg-cyber-sub border border-cyber rounded-lg text-xs text-cyber-main placeholder-cyber-muted focus:outline-none focus:border-brand-600 transition">
            </div>

            {{-- Date Assigned / Created Filter --}}
            <div class="relative">
                <input type="date" name="date_from" value="{{ request('date_from') }}" title="Date from"
                       class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-lg text-xs text-cyber-main focus:outline-none focus:border-brand-600 transition">
            </div>

            {{-- Status Filter --}}
            <div>
                <select name="status" class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-lg text-xs text-cyber-main focus:outline-none focus:border-brand-600 transition">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            {{-- Sort By & Action --}}
            <div class="flex items-center gap-2">
                <select name="sort_by" class="flex-1 px-3 py-2 bg-cyber-sub border border-cyber rounded-lg text-xs text-cyber-main focus:outline-none focus:border-brand-600 transition">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date: Recent</option>
                    <option value="total_price" {{ request('sort_by') === 'total_price' ? 'selected' : '' }}>Total Price</option>
                    <option value="valid_until" {{ request('sort_by') === 'valid_until' ? 'selected' : '' }}>Valid Until</option>
                    <option value="quotation_number" {{ request('sort_by') === 'quotation_number' ? 'selected' : '' }}>Quotation #</option>
                </select>
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                <button type="submit" aria-label="Filter quotations" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-xs font-semibold transition flex items-center justify-center">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'date_from', 'status', 'sort_by']))
                <a href="{{ route('customer.quotations.index') }}" class="p-2 bg-cyber-sub hover:bg-rose-500/15 text-cyber-muted hover:text-rose-600 rounded-lg text-xs transition" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div id="customer-quotations-results-container">
    @if($quotations->isEmpty())
    <div class="bg-cyber-card border border-cyber rounded-xl p-14 text-center">
        <div class="h-14 w-14 mx-auto rounded-xl bg-cyber-sub text-cyber-muted border border-cyber flex items-center justify-center text-xl mb-4">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <h4 class="font-bold text-cyber-main text-base font-display">No quotations yet</h4>
        <p class="text-xs text-cyber-muted mt-1">Quotations will appear here once your print requests are reviewed by our sales team.</p>
    </div>
    @else

    {{-- TABLE VIEW --}}
    <div x-show="viewMode === 'table'" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse tabular-nums">
                <thead>
                    <tr class="bg-cyber-sub border-b border-cyber text-cyber-muted uppercase tracking-wider font-sans tabular-nums font-semibold text-[11px]">
                        <th class="py-3.5 px-5">Quotation No.</th>
                        <th class="py-3.5 px-5">Date Created</th>
                        <th class="py-3.5 px-5">Service Details</th>
                        <th class="py-3.5 px-5">Quantity</th>
                        <th class="py-3.5 px-5">Size</th>
                        <th class="py-3.5 px-5">Valid Until</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Total Price</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main font-medium tabular-nums">
                    @foreach($quotations as $quotation)
                    <tr class="hover:bg-cyber-sub/50 transition-colors">
                        {{-- Quotation No. --}}
                        <td class="py-3.5 px-5 whitespace-nowrap">
                            <a href="{{ route('customer.quotations.show', $quotation) }}" class="font-bold text-sm text-cyber-main hover:text-brand-600 dark:hover:text-blue-400 font-sans tabular-nums transition">
                                #{{ $quotation->formatted_number }}
                            </a>
                        </td>

                        {{-- Date Created --}}
                        <td class="py-3.5 px-5 whitespace-nowrap font-sans tabular-nums text-cyber-muted">
                            {{ $quotation->created_at->format('M d, Y') }}
                        </td>

                        {{-- Service Details --}}
                        <td class="py-3.5 px-5">
                            <span class="font-semibold text-cyber-main text-sm block">{{ $quotation->printRequest->service ?? '—' }}</span>
                            <span class="text-[11px] text-cyber-muted">{{ $quotation->printRequest->material ?? 'Standard Material' }}</span>
                        </td>

                        {{-- Quantity --}}
                        <td class="py-3.5 px-5 whitespace-nowrap font-sans tabular-nums">
                            <span class="font-bold text-cyber-main">{{ number_format($quotation->printRequest->quantity ?? 0) }}</span>
                            <span class="block text-[11px] text-cyber-muted">copies</span>
                        </td>

                        {{-- Size --}}
                        <td class="py-3.5 px-5 whitespace-nowrap font-sans tabular-nums">
                            <span class="font-semibold text-brand-600 dark:text-blue-400">{{ $quotation->printRequest->size ?? '—' }}</span>
                        </td>

                        {{-- Valid Until --}}
                        <td class="py-3.5 px-5 whitespace-nowrap font-sans tabular-nums text-cyber-muted">
                            <i class="fa-regular fa-calendar mr-1 text-brand-600 dark:text-blue-400"></i>
                            {{ $quotation->valid_until?->format('M d, Y') ?? 'N/A' }}
                        </td>

                        {{-- Status Badge --}}
                        <td class="py-3.5 px-5 whitespace-nowrap">
                            <span class="px-2 py-0.5 text-[11px] font-sans tabular-nums font-semibold rounded uppercase tracking-wider inline-block {{ $quotation->status_badge_class }}">
                                {{ $quotation->status_label }}
                            </span>
                        </td>

                        {{-- Total Price --}}
                        <td class="py-3.5 px-5 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-brand-600 dark:text-blue-400 font-sans tabular-nums">₱{{ number_format($quotation->total_price, 2) }}</span>
                            <span class="block text-[11px] text-cyber-muted">VAT Inc.</span>
                        </td>

                        {{-- Actions --}}
                        <td class="py-3.5 px-5 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($quotation->status === 'pending')
                                <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                        Confirm
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Decline this quotation?')"
                                            class="border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-rose-600 font-semibold px-2.5 py-1.5 rounded-lg text-xs transition">
                                        Decline
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('customer.quotations.show', $quotation) }}"
                                   class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
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
        <div class="bg-cyber-card border border-cyber rounded-xl flex flex-col overflow-hidden hover:border-brand-600/40 dark:hover:border-blue-500/40 transition-colors">
            <div class="p-5 flex-1 space-y-4">
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <p class="text-[11px] font-sans tabular-nums uppercase font-semibold text-cyber-muted tracking-wider">Quotation No.</p>
                        <h4 class="text-base font-bold text-cyber-main font-sans tabular-nums">#{{ $quotation->formatted_number }}</h4>
                    </div>
                    <span class="px-2 py-0.5 text-[11px] font-sans tabular-nums font-semibold rounded uppercase tracking-wider {{ $quotation->status_badge_class }}">
                        {{ $quotation->status_label }}
                    </span>
                </div>

                <div class="space-y-1.5 text-xs tabular-nums">
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Date Created</span>
                        <span class="font-sans tabular-nums font-semibold text-cyber-main">{{ $quotation->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Service</span>
                        <span class="font-semibold text-cyber-main">{{ $quotation->printRequest->service ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Quantity</span>
                        <span class="font-sans tabular-nums font-semibold text-cyber-main">{{ number_format($quotation->printRequest->quantity ?? 0) }} copies</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Size</span>
                        <span class="font-sans tabular-nums font-semibold text-brand-600 dark:text-blue-400">{{ $quotation->printRequest->size ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub">
                        <span class="text-cyber-muted">Valid Until</span>
                        <span class="font-sans tabular-nums font-semibold text-cyber-main">{{ $quotation->valid_until?->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="border-t border-cyber pt-3 flex items-center justify-between">
                    <span class="text-xs font-semibold text-cyber-muted">Total (VAT Inc.)</span>
                    <span class="text-lg font-bold text-brand-600 dark:text-blue-400 font-sans tabular-nums">₱{{ number_format($quotation->total_price, 2) }}</span>
                </div>
            </div>

            @if($quotation->status === 'pending')
            <div class="border-t border-cyber bg-cyber-sub/50 px-5 py-3 flex gap-2.5">
                <form method="POST" action="{{ route('customer.quotations.decline', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Decline this quotation?')"
                            class="w-full border border-cyber hover:bg-cyber-sub text-cyber-muted hover:text-rose-600 font-semibold py-2 rounded-lg text-xs transition">
                        Decline
                    </button>
                </form>
                <form method="POST" action="{{ route('customer.quotations.confirm', $quotation) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2 rounded-lg text-xs transition">
                        Confirm &amp; Pay
                    </button>
                </form>
            </div>
            @else
            <div class="border-t border-cyber bg-cyber-sub/50 px-5 py-3 flex items-center justify-between">
                <a href="{{ route('customer.quotations.show', $quotation) }}"
                   class="text-xs text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 font-semibold flex items-center gap-1">
                    <span>View Details</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $quotations->links() }}</div>
    @endif
    </div>
</div>
@endsection
