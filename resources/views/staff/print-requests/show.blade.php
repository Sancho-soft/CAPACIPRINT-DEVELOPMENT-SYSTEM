@extends('layouts.internal')
@section('title', 'Review Print Request')
@section('page-title', 'Print Request Specifications')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Top Header Card --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl font-bold text-cyber-main font-display tracking-tight">
                        Print Request <span class="font-sans tabular-nums">#PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $printRequest->status_badge_class }}">
                        {{ $printRequest->status_label }}
                    </span>
                </div>
                <p class="text-xs text-cyber-muted tabular-nums mt-1">Submitted {{ $printRequest->created_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
        <a href="{{ route('staff.print-requests.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold transition inline-flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Requests
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Customer Info --}}
        <div class="bg-cyber-card p-5 sm:p-6 rounded-2xl border border-cyber shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 border-b border-cyber-sub pb-3">
                <div class="h-8 w-8 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shrink-0">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h3 class="font-bold text-cyber-main font-display text-sm">Customer Profile</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs font-medium text-cyber-muted">Name</p>
                    <p class="font-semibold text-cyber-main text-sm mt-0.5">{{ $printRequest->user->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-cyber-muted">Email</p>
                    <p class="font-medium text-cyber-main text-xs mt-0.5 break-all">{{ $printRequest->user->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-cyber-muted">Phone</p>
                    <p class="font-medium text-cyber-main text-xs tabular-nums mt-0.5">{{ $printRequest->user->phone ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Specs --}}
        <div class="md:col-span-2 bg-cyber-card p-5 sm:p-6 rounded-2xl border border-cyber shadow-sm space-y-4">
            <div class="flex items-center gap-2.5 border-b border-cyber-sub pb-3">
                <div class="h-8 w-8 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs shrink-0">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h3 class="font-bold text-cyber-main font-display text-sm">Print Job Specifications</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Service</span>
                    <strong class="text-cyber-main font-semibold text-sm">{{ $printRequest->service }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Quantity</span>
                    <strong class="text-cyber-main font-semibold tabular-nums text-sm">{{ number_format($printRequest->quantity) }} copies</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Dimensions / Size</span>
                    <strong class="text-cyber-main font-semibold">{{ $printRequest->size }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Material</span>
                    <strong class="text-cyber-main font-semibold">{{ $printRequest->material }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Finishing</span>
                    <strong class="text-cyber-main font-semibold">{{ $printRequest->finishing }}</strong>
                </div>
                <div class="bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Required Deadline</span>
                    <strong class="text-amber-700 dark:text-amber-400 font-semibold tabular-nums">{{ $printRequest->deadline?->format('F d, Y') ?? 'None' }}</strong>
                </div>
                <div class="sm:col-span-2 bg-cyber-sub border border-cyber rounded-xl p-3.5">
                    <span class="text-cyber-muted block mb-1">Preferred Pickup Branch</span>
                    <strong class="text-blue-600 dark:text-blue-400 font-semibold">{{ $printRequest->preferred_branch ?? 'Any' }}</strong>
                </div>
            </div>
            @if($printRequest->design_file_path)
            <div class="pt-3 border-t border-cyber-sub flex items-center justify-between gap-3">
                <span class="text-xs font-semibold text-cyber-muted">Design File Uploaded</span>
                <a href="{{ Storage::url($printRequest->design_file_path) }}" target="_blank" class="bg-blue-600/10 hover:bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-600/25 font-semibold px-3.5 py-2 rounded-xl text-xs transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-download"></i> View / Download Artwork
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Actions Bar --}}
    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-semibold text-cyber-muted hover:text-cyber-main transition">&larr; Back to Requests</a>

        <div class="flex flex-wrap items-center gap-3">
            @if($printRequest->status === 'submitted')
            <form method="POST" action="{{ route('staff.print-requests.verify', $printRequest) }}">
                @csrf
                <button type="submit" class="bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold px-4 py-2.5 rounded-xl text-xs transition cursor-pointer">
                    Mark Verified
                </button>
            </form>
            <a href="{{ route('staff.quotations.create', ['print_request_id' => $printRequest->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-sm transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-file-invoice-dollar"></i> Prepare Quotation
            </a>
            @endif

            @if($printRequest->quotation)
            <a href="{{ route('staff.quotations.show', $printRequest->quotation) }}" class="bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/25 text-indigo-700 dark:text-indigo-400 font-semibold px-4 py-2.5 rounded-xl text-xs transition">
                View Quotation #{{ $printRequest->quotation->formatted_number }}
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
