@extends('layouts.customer')
@section('title', 'Print Request #' . str_pad($printRequest->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Print Request Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- Top Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-sans tabular-nums font-semibold text-cyber-sub block mb-1">Client Request Ticket</span>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight flex items-center gap-3 tabular-nums">
                Print Request #{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <p class="text-xs sm:text-sm text-cyber-muted mt-1 flex items-center gap-2 font-sans tabular-nums">
                <i class="fa-regular fa-calendar text-cyber-sub text-xs"></i>
                Submitted {{ $printRequest->created_at->format('F d, Y') }}
            </p>
        </div>
        <div class="self-start sm:self-center">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded text-[11px] font-sans tabular-nums font-semibold
                {{ match($printRequest->status) {
                    'submitted'   => 'bg-brand-600/10 text-brand-700 border border-brand-600/25 dark:bg-blue-500/15 dark:text-blue-300 dark:border-blue-500/30',
                    'quotation'   => 'bg-amber-500/10 text-amber-800 border border-amber-500/25 dark:text-amber-300',
                    'payment'     => 'bg-orange-500/10 text-orange-800 border border-orange-500/25 dark:text-orange-300',
                    'production'  => 'bg-indigo-500/10 text-indigo-700 border border-indigo-500/25 dark:text-indigo-300',
                    'ready_for_pickup', 'completed' => 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/25 dark:text-emerald-300',
                    'cancelled'   => 'bg-rose-500/10 text-rose-700 border border-rose-500/25 dark:text-rose-300',
                    default       => 'bg-cyber-sub text-cyber-muted border border-cyber',
                } }}">
                <span class="w-1.5 h-1.5 rounded-full
                    {{ match($printRequest->status) {
                        'submitted'   => 'bg-brand-600 dark:bg-blue-400',
                        'quotation'   => 'bg-amber-500',
                        'payment'     => 'bg-orange-500',
                        'production'  => 'bg-indigo-500',
                        'ready_for_pickup', 'completed' => 'bg-emerald-500',
                        'cancelled'   => 'bg-rose-500',
                        default       => 'bg-cyber-sub',
                    } }}"></span>
                {{ $printRequest->status_label }}
            </span>
        </div>
    </div>

    {{-- Progress Stepper Card --}}
    @php
        $stepper = [
            ['id' => 1, 'key' => 'submitted',  'label' => 'Submitted',  'desc' => 'Request Logged',      'icon' => 'fa-file-lines'],
            ['id' => 2, 'key' => 'quotation',  'label' => 'Quotation',  'desc' => 'Pricing Matrix',      'icon' => 'fa-file-invoice-dollar'],
            ['id' => 3, 'key' => 'payment',    'label' => 'Payment',    'desc' => 'Payment Cleared',     'icon' => 'fa-credit-card'],
            ['id' => 4, 'key' => 'production', 'label' => 'Production', 'desc' => 'Press Run & Handover', 'icon' => 'fa-industry'],
        ];

        $currentStep = match($printRequest->status) {
            'submitted'                  => 1,
            'quotation'                  => 2,
            'payment'                    => 3,
            'production'                 => 4,
            'ready_for_pickup', 'completed' => 5,
            default                      => 1,
        };
    @endphp

    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between mb-5 border-b border-cyber-sub pb-3">
            <h3 class="font-sans tabular-nums font-semibold text-xs text-cyber-muted flex items-center gap-2">
                <i class="fa-solid fa-route text-brand-600 dark:text-blue-400"></i> Request Progress Pipeline
            </h3>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-2 relative">
            @foreach($stepper as $step)
                @php
                    $isDone    = $currentStep > $step['id'];
                    $isCurrent = $currentStep === $step['id'];
                    $isPending = $currentStep < $step['id'];
                @endphp
                <div class="flex items-center gap-3 relative">
                    <div class="w-9 h-9 rounded-lg border flex items-center justify-center shrink-0 font-semibold text-xs transition-colors
                        {{ $isDone ? 'bg-emerald-600 border-emerald-600 text-white' : '' }}
                        {{ $isCurrent ? 'bg-brand-600 dark:bg-blue-600 border-brand-600 dark:border-blue-500 text-white ring-2 ring-brand-600/25' : '' }}
                        {{ $isPending ? 'bg-cyber-sub border-cyber text-cyber-sub' : '' }}">
                        @if($isDone)
                            <i class="fa-solid fa-check text-xs"></i>
                        @else
                            <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold leading-tight truncate
                            {{ $isDone ? 'text-cyber-main' : '' }}
                            {{ $isCurrent ? 'text-brand-600 dark:text-blue-400 font-bold' : '' }}
                            {{ $isPending ? 'text-cyber-sub' : '' }}">
                            {{ $step['label'] }}
                        </p>
                        <p class="text-[11px] text-cyber-muted truncate mt-0.5">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Main Specifications Card --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        
        {{-- Service Header Banner --}}
        <div class="px-6 py-4 bg-cyber-sub border-b border-cyber-sub flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-brand-600/10 dark:bg-blue-500/15 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-print"></i>
            </div>
            <div>
                <h3 class="font-bold text-base text-cyber-main font-display">
                    {{ $printRequest->service }}
                </h3>
                <p class="text-xs text-cyber-muted">Technical Specifications &amp; Job Attributes</p>
            </div>
        </div>

        {{-- Spec Grid (Section 1: Print & Substrate) --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-cyber-muted block">Quantity</span>
                <p class="text-sm font-bold text-cyber-main">
                    {{ number_format($printRequest->quantity) }} {{ $printRequest->quantity == 1 ? 'pc.' : 'pcs.' }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-cyber-muted block">Dimensions &amp; Size</span>
                <p class="text-sm font-bold text-cyber-main">
                    {{ $printRequest->size }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-cyber-muted block">Substrate / Material</span>
                <p class="text-sm font-bold text-cyber-main">
                    {{ $printRequest->material }}
                </p>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-cyber-muted block">Finishing &amp; Processing</span>
                <p class="text-sm font-bold text-cyber-main">
                    {{ $printRequest->finishing }}
                </p>
            </div>
        </div>

        {{-- Section 2: Fulfillment & Logistics --}}
        <div class="px-6 py-4 bg-cyber-sub/60 dark:bg-slate-900/30 border-t border-cyber">
            <h4 class="text-xs font-bold text-cyber-muted mb-4 flex items-center gap-2">
                <i class="fa-solid fa-truck-ramp-box text-blue-500"></i> Fulfillment &amp; Logistics
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-cyber-muted block">Collection Mode</span>
                    <p class="text-sm font-bold text-cyber-main capitalize flex items-center gap-2">
                        <i class="fa-solid fa-store text-xs text-cyber-muted"></i>
                        {{ str_replace('_', ' ', $printRequest->collection_mode) }}
                    </p>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-semibold text-cyber-muted block">Preferred Branch</span>
                    <p class="text-sm font-bold text-cyber-main flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-xs text-blue-500"></i>
                        {{ $printRequest->preferred_branch ?: 'Morning Star Printing Press' }}
                    </p>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-semibold text-cyber-muted block">Target Deadline</span>
                    <p class="text-sm font-bold text-cyber-main flex items-center gap-2">
                        <i class="fa-regular fa-calendar-check text-xs text-emerald-500"></i>
                        {{ $printRequest->deadline?->format('F d, Y') ?? '—' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Attached Design File Card --}}
    @if($printRequest->design_file_name)
    <div class="bg-cyber-card border border-cyber rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4">
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-lg">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div class="min-w-0">
                <p class="font-bold text-cyber-main text-sm truncate">{{ $printRequest->design_file_name }}</p>
                <p class="text-xs text-cyber-muted mt-0.5">{{ $printRequest->design_file_size ?? 'Design Asset' }}</p>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyber-sub text-cyber-muted shrink-0">
            Client Artwork
        </span>
    </div>
    @endif

    {{-- Design Proof & Approval Section --}}
    @if($printRequest->designProofs->isNotEmpty())
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="font-bold text-cyber-main flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles text-pink-500"></i> Layout &amp; Design Proofs
            </h3>
            <span class="text-xs text-cyber-muted font-medium">{{ $printRequest->designProofs->count() }} Version(s)</span>
        </div>

        @foreach($printRequest->designProofs->sortByDesc('version') as $proof)
            <div class="p-4 rounded-xl border {{ $proof->status === 'approved' ? 'bg-emerald-50/50 border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-800/40' : ($proof->status === 'revision_requested' ? 'bg-amber-50/50 border-amber-200 dark:bg-amber-950/20 dark:border-amber-800/40' : 'bg-cyber-sub border-cyber dark:bg-slate-900 dark:border-slate-800') }} space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-cyber-main text-sm">Version {{ $proof->version }}: {{ $proof->proof_file_name }}</span>
                        <p class="text-xs text-cyber-muted mt-0.5">{{ $proof->created_at->format('M d, Y · h:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $proof->status_badge_class }}">
                        {{ $proof->status_label }}
                    </span>
                </div>

                @if($proof->designer_notes)
                    <div class="text-xs text-cyber-muted bg-cyber-card p-3 rounded-lg border border-cyber">
                        <strong class="text-cyber-muted">Designer's Note:</strong> {{ $proof->designer_notes }}
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ Storage::url($proof->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-500/10 hover:bg-blue-600 text-blue-600 dark:text-blue-400 hover:text-white border border-blue-500/25 font-bold text-xs transition">
                        <i class="fa-solid fa-eye text-xs"></i> View Proof
                    </a>

                    @if($proof->status === 'pending_review')
                        <div class="flex items-center gap-2" x-data="{ showRevisionForm: false }">
                            <form method="POST" action="{{ route('customer.proof.review', $proof) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" onclick="return confirm('Approve this design proof for production?');" 
                                        class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                                    <i class="fa-solid fa-check mr-1"></i> Approve Proof
                                </button>
                            </form>
                            <button type="button" @click="showRevisionForm = !showRevisionForm" 
                                    class="px-3.5 py-1.5 rounded-xl bg-amber-100 hover:bg-amber-200 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 font-bold text-xs transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Request Revision
                            </button>

                            <div x-show="showRevisionForm" x-cloak class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 overscroll-contain">
                                <div class="bg-cyber-card p-6 rounded-2xl max-w-md w-full shadow-sm space-y-4 border border-cyber" @click.away="showRevisionForm = false">
                                    <h4 class="font-bold text-cyber-main">Request Design Revision (v{{ $proof->version }})</h4>
                                    <form method="POST" action="{{ route('customer.proof.review', $proof) }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="action" value="revise">
                                        <textarea name="customer_feedback" rows="4" required placeholder="Describe what adjustments or revisions you need..."
                                                  class="w-full p-3 text-xs bg-cyber-sub border border-cyber rounded-xl text-cyber-main focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="showRevisionForm = false" class="px-4 py-2 bg-cyber-sub text-cyber-muted rounded-xl font-bold text-xs">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-xs">Submit Revision</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Linked Quotation Card --}}
    @if($printRequest->quotation)
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold text-cyber-muted">Official Quotation Available</p>
            <p class="text-xl font-black text-cyber-main font-display mt-0.5">
                #{{ $printRequest->quotation->formatted_number }}
            </p>
            <p class="text-xs text-cyber-muted mt-1">
                Valid until {{ $printRequest->quotation->valid_until?->format('F d, Y') ?? '—' }}
            </p>
        </div>
        <div class="flex flex-col sm:items-end gap-2">
            <div class="text-left sm:text-right">
                <span class="text-2xl font-black text-blue-600 dark:text-blue-400 font-display">
                    ₱{{ number_format($printRequest->quotation->total_price, 2) }}
                </span>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded-full ml-2 {{ $printRequest->quotation->status_badge_class }}">
                    {{ $printRequest->quotation->status_label }}
                </span>
            </div>
            <a href="{{ route('customer.quotations.show', $printRequest->quotation) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm">
                <span>Review &amp; Accept Quotation</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>
    </div>
    @endif

    {{-- Bottom Action Bar --}}
    <div class="flex items-center justify-between pt-2">
        <a href="{{ route('customer.print-requests.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow-sm shadow-blue-600/20">
            <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to My Requests
        </a>

        @if($printRequest->status === 'submitted')
        <form method="POST" action="{{ route('customer.print-requests.cancel', $printRequest) }}"
              onsubmit="return confirm('Are you sure you want to cancel this print request?')">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/30 dark:hover:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40 font-bold text-xs transition shadow-sm">
                <i class="fa-solid fa-ban text-[11px]"></i> Cancel Request
            </button>
        </form>
        @endif
    </div>

</div>
@endsection
