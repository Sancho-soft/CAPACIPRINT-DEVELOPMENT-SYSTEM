@extends('layouts.internal')
@section('title', 'Review Print Request')
@section('page-title', 'Print Request Specifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-navy-900 font-display">Print Job Profile #PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-xs text-slate-500">Submitted {{ $printRequest->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $printRequest->status_badge_class }}">
            {{ $printRequest->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Customer Info --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
            <h3 class="font-bold text-navy-900 text-sm border-b border-slate-100 pb-2">Customer Profile</h3>
            <div>
                <p class="text-xs text-slate-400">Name</p>
                <p class="font-bold text-slate-800 text-sm">{{ $printRequest->user->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Email</p>
                <p class="font-medium text-slate-700 text-xs">{{ $printRequest->user->email ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Phone</p>
                <p class="font-medium text-slate-700 text-xs">{{ $printRequest->user->phone ?? '—' }}</p>
            </div>
        </div>

        {{-- Specs --}}
        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="font-bold text-navy-900 text-sm border-b border-slate-100 pb-2">Print Job Specifications</h3>
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div><span class="text-slate-400 block">Service</span><strong class="text-navy-900 text-sm">{{ $printRequest->service }}</strong></div>
                <div><span class="text-slate-400 block">Quantity</span><strong class="text-navy-900 text-sm">{{ number_format($printRequest->quantity) }} copies</strong></div>
                <div><span class="text-slate-400 block">Dimensions / Size</span><strong class="text-slate-800">{{ $printRequest->size }}</strong></div>
                <div><span class="text-slate-400 block">Material</span><strong class="text-slate-800">{{ $printRequest->material }}</strong></div>
                <div><span class="text-slate-400 block">Finishing</span><strong class="text-slate-800">{{ $printRequest->finishing }}</strong></div>
                <div><span class="text-slate-400 block">Required Deadline</span><strong class="text-amber-700">{{ $printRequest->deadline?->format('F d, Y') ?? 'None' }}</strong></div>
                <div><span class="text-slate-400 block">Preferred Pickup Branch</span><strong class="text-brand-600">{{ $printRequest->preferred_branch ?? 'Any' }}</strong></div>
            </div>
            @if($printRequest->design_file_path)
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Design File Uploaded</span>
                <a href="{{ Storage::url($printRequest->design_file_path) }}" target="_blank" class="bg-brand-50 hover:bg-brand-100 text-brand-600 font-bold px-3 py-1.5 rounded-lg text-xs">
                    <i class="fa-solid fa-download mr-1"></i> View / Download Artwork
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Actions Bar --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Back to Requests</a>

        <div class="flex items-center gap-3">
            @if($printRequest->status === 'submitted')
            <form method="POST" action="{{ route('staff.print-requests.verify', $printRequest) }}">
                @csrf
                <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-xl text-xs">
                    Mark Verified
                </button>
            </form>
            <a href="{{ route('staff.quotations.create', ['print_request_id' => $printRequest->id]) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Prepare Quotation
            </a>
            @endif

            @if($printRequest->quotation)
            <a href="{{ route('staff.quotations.show', $printRequest->quotation) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold px-4 py-2 rounded-xl text-xs">
                View Quotation #{{ $printRequest->quotation->quotation_number }}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
