@extends('layouts.internal')
@section('title', 'Design Workspace #REQ-' . str_pad($printRequest->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Pre-Press Artwork & Proofing Studio')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('designer.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-cyber-muted hover:text-cyber-main dark:text-cyber-muted dark:hover:text-white transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Design Queue
        </a>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-sans tabular-nums font-bold bg-cyber-sub text-cyber-main border border-cyber">
                Job #REQ-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Job Specs & Artwork Pre-Flight --}}
        <div class="space-y-6">
            {{-- Job Details Card --}}
            <div class="bg-cyber-card p-6 rounded-xl border border-cyber space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-cyber-sub">
                    <div class="h-10 w-10 rounded-lg bg-brand-600/10 dark:bg-blue-500/15 border border-brand-600/20 dark:border-blue-500/30 text-brand-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-cyber-main font-sans tabular-nums text-sm tabular-nums">#REQ-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-cyber-muted">{{ $printRequest->user->name ?? 'Customer' }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs tabular-nums">
                    <div class="flex justify-between py-1 border-b border-cyber-sub"><span class="text-cyber-muted">Service:</span><strong class="text-cyber-main">{{ $printRequest->service }}</strong></div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub"><span class="text-cyber-muted">Quantity:</span><strong class="text-cyber-main font-sans tabular-nums">{{ number_format($printRequest->quantity) }} pcs</strong></div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub"><span class="text-cyber-muted">Dimensions:</span><span class="text-cyber-main font-sans tabular-nums">{{ $printRequest->size }}</span></div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub"><span class="text-cyber-muted">Material:</span><span class="text-cyber-main">{{ $printRequest->material }}</span></div>
                    <div class="flex justify-between py-1 border-b border-cyber-sub"><span class="text-cyber-muted">Finishing:</span><span class="text-cyber-main">{{ $printRequest->finishing }}</span></div>
                    <div class="flex justify-between py-1"><span class="text-cyber-muted">Deadline:</span><strong class="text-amber-700 dark:text-amber-400 font-sans tabular-nums">{{ $printRequest->deadline?->format('M d, Y') ?? 'Standard' }}</strong></div>
                </div>

                @if($printRequest->additional_instructions)
                    <div class="p-3 bg-cyber-sub rounded-lg text-xs space-y-1 border border-cyber-sub">
                        <div class="font-sans tabular-nums font-semibold text-cyber-muted text-[11px]">Client Instructions:</div>
                        <p class="text-cyber-main italic">{{ $printRequest->additional_instructions }}</p>
                    </div>
                @endif

                @if($printRequest->design_file_path)
                    <div class="pt-3 border-t border-cyber-sub">
                        <span class="text-xs font-semibold text-cyber-muted block mb-2">Original Client File:</span>
                        <a href="{{ Storage::url($printRequest->design_file_path) }}" target="_blank" class="w-full flex items-center justify-between p-3 rounded-lg bg-brand-600/10 dark:bg-blue-500/15 hover:bg-brand-600/20 text-brand-700 dark:text-blue-300 font-semibold text-xs border border-brand-600/25 dark:border-blue-500/30 transition">
                            <span class="truncate max-w-[170px]"><i class="fa-solid fa-download mr-1"></i> {{ $printRequest->design_file_name ?? 'Download Artwork' }}</span>
                            <i class="fa-solid fa-arrow-down"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Upload New Proof Box --}}
            <div class="bg-cyber-card p-6 rounded-xl border border-cyber space-y-4">
                <h3 class="font-bold text-cyber-main font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-brand-600 dark:text-blue-400"></i> Upload New Proof Version
                </h3>
                <form method="POST" action="{{ route('designer.storeProof', $printRequest) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-cyber-main mb-1.5">Proof File (PDF / Image) *</label>
                        <input type="file" name="proof_file" required accept=".pdf,.png,.jpg,.jpeg,.webp"
                               class="w-full text-xs text-cyber-muted file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600/10 file:text-brand-700 dark:file:bg-blue-500/15 dark:file:text-blue-300 hover:file:bg-brand-600/20">
                    </div>
                    <div>
                        <label class="block font-semibold text-cyber-main mb-1.5">Pre-Press Notes / Revision Details</label>
                        <textarea name="designer_notes" rows="3" placeholder="e.g. Adjusted 3mm bleed margin, CMYK converted to Fogra39..."
                                  class="w-full p-3 bg-cyber-sub border border-cyber rounded-lg focus:ring-2 focus:ring-brand-600 focus:outline-none text-cyber-main text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i> Send Proof to Client
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Proof Versions & Revision History --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-cyber-card p-6 sm:p-7 rounded-xl border border-cyber space-y-6">
                <div class="flex items-center justify-between border-b border-cyber-sub pb-4">
                    <div>
                        <h3 class="font-bold text-cyber-main font-display text-base flex items-center gap-2">
                            <i class="fa-solid fa-code-compare text-brand-600 dark:text-blue-400"></i> Proof History &amp; Revision Rounds
                        </h3>
                        <p class="text-xs text-cyber-muted mt-0.5">Track every layout version sent to the client, along with their approval or revision feedback.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded bg-cyber-sub text-cyber-main border border-cyber font-sans tabular-nums font-semibold text-xs tabular-nums">
                        {{ $printRequest->designProofs->count() }} version(s)
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($printRequest->designProofs->sortByDesc('version') as $proof)
                        <div class="p-5 rounded-2xl border {{ $proof->status === 'approved' ? 'bg-emerald-500/5 border-emerald-500/30' : ($proof->status === 'revision_requested' ? 'bg-amber-500/5 border-amber-500/30' : 'bg-cyber-sub border-cyber') }} space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 rounded-xl bg-slate-900 dark:bg-slate-800 text-blue-400 font-black flex items-center justify-center text-xs font-sans tabular-nums">
                                        v{{ $proof->version }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-cyber-main text-sm">{{ $proof->proof_file_name }}</div>
                                        <div class="text-[11px] text-cyber-muted">Uploaded by {{ $proof->designer->name ?? 'Layout Designer' }} &bull; {{ $proof->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $proof->status_badge_class }}">
                                    {{ $proof->status_label }}
                                </span>
                            </div>

                            @if($proof->designer_notes)
                                <div class="bg-cyber-card p-3 rounded-xl border border-cyber text-xs">
                                    <span class="font-bold text-cyber-muted text-[11px] block mb-0.5">Designer Comment:</span>
                                    <p class="text-cyber-main">{{ $proof->designer_notes }}</p>
                                </div>
                            @endif

                            @if($proof->customer_feedback)
                                <div class="bg-amber-500/10 dark:bg-amber-500/15 p-3.5 rounded-xl border border-amber-500/20 text-xs">
                                    <span class="font-bold text-amber-700 dark:text-amber-400 text-[11px] block mb-0.5"><i class="fa-solid fa-comment-dots mr-1"></i> Customer Revision Feedback:</span>
                                    <p class="text-amber-900 dark:text-amber-300">{{ $proof->customer_feedback }}</p>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <a href="{{ Storage::url($proof->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-cyber-sub hover:bg-cyber-sub border border-cyber text-cyber-main font-bold text-xs transition">
                                    <i class="fa-solid fa-eye text-blue-500 dark:text-blue-400"></i> View Proof
                                </a>

                                @if($proof->status === 'approved' && !$proof->production_file_path)
                                    <form method="POST" action="{{ route('designer.uploadProductionFile', $proof) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="production_file" required accept=".pdf,.ai,.eps,.zip,.tiff,.tif" class="text-xs text-cyber-muted file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:bg-emerald-500/15 file:text-emerald-700 dark:file:text-emerald-400">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition shadow-sm">
                                            Upload Production File
                                        </button>
                                    </form>
                                @elseif($proof->production_file_path)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-circle-check mr-1"></i> Ready for Production</span>
                                        <a href="{{ Storage::url($proof->production_file_path) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-700 dark:text-emerald-400 font-bold text-xs transition border border-emerald-500/20">
                                            <i class="fa-solid fa-download mr-1"></i> Download Final Vector
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-cyber-muted">
                            <i class="fa-solid fa-image text-3xl mb-2 text-cyber-muted"></i>
                            <p class="text-sm">No proofs uploaded yet. Upload version 1 using the form on the left.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
