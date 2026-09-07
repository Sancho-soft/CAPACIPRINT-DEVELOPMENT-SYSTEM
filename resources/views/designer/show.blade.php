@extends('layouts.internal')
@section('title', 'Design Workspace #REQ-' . str_pad($printRequest->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Pre-Press Artwork & Proofing Studio')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('designer.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Design Queue
        </a>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
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
            <div class="bg-white dark:bg-[#111A24] p-6 rounded-3xl border border-slate-200 dark:border-slate-800/80 shadow-xl space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="h-10 w-10 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white font-display text-sm">#REQ-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $printRequest->user->name ?? 'Customer' }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800/60"><span class="text-slate-400">Service:</span><strong class="text-slate-900 dark:text-white">{{ $printRequest->service }}</strong></div>
                    <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800/60"><span class="text-slate-400">Quantity:</span><strong class="text-slate-900 dark:text-white">{{ number_format($printRequest->quantity) }} pcs</strong></div>
                    <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800/60"><span class="text-slate-400">Dimensions:</span><span class="text-slate-700 dark:text-slate-300 font-mono">{{ $printRequest->size }}</span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800/60"><span class="text-slate-400">Material:</span><span class="text-slate-700 dark:text-slate-300">{{ $printRequest->material }}</span></div>
                    <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800/60"><span class="text-slate-400">Finishing:</span><span class="text-slate-700 dark:text-slate-300">{{ $printRequest->finishing }}</span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-400">Deadline:</span><strong class="text-amber-600 dark:text-amber-400">{{ $printRequest->deadline?->format('M d, Y') ?? 'Standard' }}</strong></div>
                </div>

                @if($printRequest->additional_instructions)
                    <div class="p-3 bg-slate-50 dark:bg-[#0D1520] rounded-2xl text-xs space-y-1 border border-slate-100 dark:border-slate-800">
                        <div class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Client Instructions:</div>
                        <p class="text-slate-700 dark:text-slate-300 italic">{{ $printRequest->additional_instructions }}</p>
                    </div>
                @endif

                @if($printRequest->design_file_path)
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block mb-2">Original Client File:</span>
                        <a href="{{ Storage::url($printRequest->design_file_path) }}" target="_blank" class="w-full flex items-center justify-between p-3 rounded-2xl bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-700 dark:text-cyan-400 font-bold text-xs border border-cyan-500/20 transition">
                            <span class="truncate max-w-[170px]"><i class="fa-solid fa-download mr-1"></i> {{ $printRequest->design_file_name ?? 'Download Artwork' }}</span>
                            <i class="fa-solid fa-arrow-down"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Upload New Proof Box --}}
            <div class="bg-white dark:bg-[#111A24] p-6 rounded-3xl border border-slate-200 dark:border-slate-800/80 shadow-xl space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-white font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-cyan-600 dark:text-cyan-400"></i> Upload New Proof Version
                </h3>
                <form method="POST" action="{{ route('designer.storeProof', $printRequest) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Proof File (PDF / Image) *</label>
                        <input type="file" name="proof_file" required accept=".pdf,.png,.jpg,.jpeg,.webp"
                               class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-500/10 file:text-cyan-700 dark:file:text-cyan-400 hover:file:bg-cyan-500/20">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Pre-Press Notes / Revision Details</label>
                        <textarea name="designer_notes" rows="3" placeholder="e.g. Adjusted 3mm bleed margin, CMYK converted to Fogra39..."
                                  class="w-full p-3 bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:outline-none text-slate-900 dark:text-white text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Send Proof to Client
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Proof Versions & Revision History --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-[#111A24] p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800/80 shadow-xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white font-display text-base flex items-center gap-2">
                            <i class="fa-solid fa-code-compare text-cyan-600 dark:text-cyan-400"></i> Proof History &amp; Revision Rounds
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Track every layout version sent to the client, along with their approval or revision feedback.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono font-bold text-xs">
                        {{ $printRequest->designProofs->count() }} version(s)
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($printRequest->designProofs->sortByDesc('version') as $proof)
                        <div class="p-5 rounded-2xl border {{ $proof->status === 'approved' ? 'bg-emerald-500/5 border-emerald-500/30' : ($proof->status === 'revision_requested' ? 'bg-amber-500/5 border-amber-500/30' : 'bg-slate-50 dark:bg-[#0D1520] border-slate-200 dark:border-slate-800') }} space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 rounded-xl bg-slate-900 dark:bg-slate-800 text-cyan-400 font-black flex items-center justify-center text-xs font-mono">
                                        v{{ $proof->version }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $proof->proof_file_name }}</div>
                                        <div class="text-[11px] text-slate-400">Uploaded by {{ $proof->designer->name ?? 'Layout Designer' }} &bull; {{ $proof->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $proof->status_badge_class }}">
                                    {{ $proof->status_label }}
                                </span>
                            </div>

                            @if($proof->designer_notes)
                                <div class="bg-white dark:bg-[#111A24] p-3 rounded-xl border border-slate-200 dark:border-slate-800 text-xs">
                                    <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block mb-0.5">Designer Comment:</span>
                                    <p class="text-slate-700 dark:text-slate-300">{{ $proof->designer_notes }}</p>
                                </div>
                            @endif

                            @if($proof->customer_feedback)
                                <div class="bg-amber-500/10 dark:bg-amber-500/15 p-3.5 rounded-xl border border-amber-500/20 text-xs">
                                    <span class="font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider text-[10px] block mb-0.5"><i class="fa-solid fa-comment-dots mr-1"></i> Customer Revision Feedback:</span>
                                    <p class="text-amber-900 dark:text-amber-300">{{ $proof->customer_feedback }}</p>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <a href="{{ Storage::url($proof->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition">
                                    <i class="fa-solid fa-eye text-cyan-600 dark:text-cyan-400"></i> View Proof
                                </a>

                                @if($proof->status === 'approved' && !$proof->production_file_path)
                                    <form method="POST" action="{{ route('designer.uploadProductionFile', $proof) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="production_file" required accept=".pdf,.ai,.eps,.zip,.tiff,.tif" class="text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-emerald-500/15 file:text-emerald-700 dark:file:text-emerald-400">
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
                        <div class="py-12 text-center text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-image text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                            <p class="text-sm">No proofs uploaded yet. Upload version 1 using the form on the left.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
