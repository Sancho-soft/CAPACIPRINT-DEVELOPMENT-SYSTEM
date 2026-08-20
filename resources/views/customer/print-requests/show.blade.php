@extends('layouts.customer')
@section('title', 'Print Request #' . $printRequest->id)
@section('page-title', 'Print Request Details')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Print Request #{{ $printRequest->id }}</h2>
            <p class="text-sm text-slate-500 mt-1">Submitted {{ $printRequest->created_at->diffForHumans() }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase
            {{ match($printRequest->status) {
                'submitted'   => 'bg-blue-100 text-blue-800',
                'quotation'   => 'bg-amber-100 text-amber-800',
                'payment'     => 'bg-orange-100 text-orange-800',
                'production'  => 'bg-cyan-100 text-cyan-800',
                'completed'   => 'bg-green-100 text-green-800',
                'cancelled'   => 'bg-slate-100 text-slate-500',
                default       => 'bg-slate-100 text-slate-600',
            } }}">
            {{ $printRequest->status_label }}
        </span>
    </div>

    {{-- Details Card --}}
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-navy-900">Job Specifications</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <tbody class="bg-white divide-y divide-slate-100">
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50 w-1/3">Service</td><td class="px-6 py-3 font-bold text-navy-900">{{ $printRequest->service }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Quantity</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->quantity }} copies</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Size</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->size }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Material</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->material }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Finishing</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->finishing }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Deadline</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->deadline?->format('F d, Y') ?? '—' }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Collection</td><td class="px-6 py-3 text-slate-800 capitalize">{{ str_replace('_',' ', $printRequest->collection_mode) }}</td></tr>
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Preferred Branch</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->preferred_branch ?: 'System recommendation' }}</td></tr>
                @if($printRequest->additional_instructions)
                <tr><td class="px-6 py-3 font-semibold text-slate-500 bg-slate-50/50">Instructions</td><td class="px-6 py-3 text-slate-800">{{ $printRequest->additional_instructions }}</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Design File --}}
    @if($printRequest->design_file_name)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-5 flex items-center gap-4">
        <i class="fa-solid fa-file text-brand-500 text-2xl shrink-0"></i>
        <div class="flex-1">
            <p class="font-bold text-navy-900 text-sm">{{ $printRequest->design_file_name }}</p>
            <p class="text-xs text-slate-400">{{ $printRequest->design_file_size }}</p>
        </div>
        <span class="text-xs text-slate-400 font-medium">Design File</span>
    </div>
    @endif

    {{-- Linked Quotation --}}
    @if($printRequest->quotation)
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Linked Quotation</p>
                <p class="font-bold text-navy-900 mt-0.5">#{{ $printRequest->quotation->quotation_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-black text-navy-900 font-display">₱{{ number_format($printRequest->quotation->total_price, 2) }}</p>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ $printRequest->quotation->status_badge_class }}">
                    {{ $printRequest->quotation->status_label }}
                </span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100">
            <a href="{{ route('customer.quotations.show', $printRequest->quotation) }}"
               class="text-sm text-brand-500 hover:text-brand-700 font-semibold">View Quotation &rarr;</a>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.print-requests.index') }}"
           class="text-sm text-slate-500 hover:text-navy-900 font-medium flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Requests
        </a>

        @if($printRequest->status === 'submitted')
        <form method="POST" action="{{ route('customer.print-requests.cancel', $printRequest) }}"
              onsubmit="return confirm('Are you sure you want to cancel this request?')">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold flex items-center gap-2">
                <i class="fa-solid fa-ban"></i> Cancel Request
            </button>
        </form>
        @endif
    </div>

</div>
@endsection
