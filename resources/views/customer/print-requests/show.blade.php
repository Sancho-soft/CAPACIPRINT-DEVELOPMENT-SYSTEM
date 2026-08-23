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

    {{-- Design Proof & Approval Section --}}
    @if($printRequest->designProofs->isNotEmpty())
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-navy-900 flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles text-pink-500"></i> Layout &amp; Design Proofs
            </h3>
            <span class="text-xs text-slate-400 font-medium">{{ $printRequest->designProofs->count() }} Version(s)</span>
        </div>

        @foreach($printRequest->designProofs->sortByDesc('version') as $proof)
            <div class="p-4 rounded-xl border {{ $proof->status === 'approved' ? 'bg-emerald-50/50 border-emerald-200' : ($proof->status === 'revision_requested' ? 'bg-amber-50/50 border-amber-200' : 'bg-slate-50 border-slate-200') }} space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-navy-900 text-sm">Version {{ $proof->version }}: {{ $proof->proof_file_name }}</span>
                        <p class="text-xs text-slate-400">{{ $proof->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $proof->status_badge_class }}">
                        {{ $proof->status_label }}
                    </span>
                </div>

                @if($proof->designer_notes)
                    <div class="text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">
                        <strong class="text-slate-500">Designer's Note:</strong> {{ $proof->designer_notes }}
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ Storage::url($proof->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-pink-50 hover:bg-pink-100 text-pink-700 font-bold text-xs transition">
                        <i class="fa-solid fa-eye"></i> View Proof
                    </a>

                    @if($proof->status === 'pending_review')
                        <div class="flex items-center gap-2" x-data="{ showRevisionForm: false }">
                            <form method="POST" action="{{ route('customer.proof.review', $proof) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" onclick="return confirm('Approve this design proof for production?');" 
                                        class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm shadow-emerald-600/20">
                                    <i class="fa-solid fa-check mr-1"></i> Approve Proof
                                </button>
                            </form>
                            <button type="button" @click="showRevisionForm = !showRevisionForm" 
                                    class="px-3.5 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold text-xs transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Request Revision
                            </button>

                            <div x-show="showRevisionForm" x-cloak class="fixed inset-0 bg-navy-950/50 flex items-center justify-center p-4 z-50">
                                <div class="bg-white p-6 rounded-2xl max-w-md w-full shadow-2xl space-y-4" @click.away="showRevisionForm = false">
                                    <h4 class="font-bold text-navy-900">Request Design Revision (v{{ $proof->version }})</h4>
                                    <form method="POST" action="{{ route('customer.proof.review', $proof) }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="action" value="revise">
                                        <textarea name="customer_feedback" rows="4" required placeholder="Describe what adjustments or revisions you need..."
                                                  class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="showRevisionForm = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs">Cancel</button>
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
