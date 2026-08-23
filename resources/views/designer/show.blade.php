@extends('layouts.internal')
@section('title', 'Design Workspace #PR-' . str_pad($printRequest->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Design Proofing & Revision Workspace')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('designer.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Design Queue
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Job Specs & Original Artwork --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="h-10 w-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 font-display text-sm">#PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-slate-400">{{ $printRequest->user->name ?? 'Customer' }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Service:</span><strong class="text-navy-900">{{ $printRequest->service }}</strong></div>
                    <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Quantity:</span><strong class="text-navy-900">{{ number_format($printRequest->quantity) }} copies</strong></div>
                    <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Dimensions:</span><span class="text-slate-700">{{ $printRequest->size }}</span></div>
                    <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Material:</span><span class="text-slate-700">{{ $printRequest->material }}</span></div>
                    <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-slate-400">Finishing:</span><span class="text-slate-700">{{ $printRequest->finishing }}</span></div>
                    <div class="flex justify-between py-1"><span class="text-slate-400">Deadline:</span><strong class="text-amber-700">{{ $printRequest->deadline?->format('M d, Y') ?? 'Flexible' }}</strong></div>
                </div>

                @if($printRequest->additional_instructions)
                    <div class="p-3 bg-slate-50 rounded-2xl text-xs space-y-1">
                        <div class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Client Notes:</div>
                        <p class="text-slate-600 italic">{{ $printRequest->additional_instructions }}</p>
                    </div>
                @endif

                @if($printRequest->design_file_path)
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-500 block mb-2">Original Client File:</span>
                        <a href="{{ Storage::url($printRequest->design_file_path) }}" target="_blank" class="w-full flex items-center justify-between p-3 rounded-2xl bg-pink-50 hover:bg-pink-100 text-pink-700 font-bold text-xs transition">
                            <span class="truncate max-w-[170px]"><i class="fa-solid fa-download mr-1"></i> {{ $printRequest->design_file_name ?? 'Download File' }}</span>
                            <i class="fa-solid fa-arrow-down"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Upload New Proof Box --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-bold text-navy-900 font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-pink-500"></i> Upload New Proof Version
                </h3>
                <form method="POST" action="{{ route('designer.storeProof', $printRequest) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Proof File (PDF / Image) *</label>
                        <input type="file" name="proof_file" required accept=".pdf,.png,.jpg,.jpeg,.webp"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Designer Notes / Changes Made</label>
                        <textarea name="designer_notes" rows="3" placeholder="e.g. Adjusted bleed margins and Pantone color codes..."
                                  class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-xl shadow-md shadow-pink-500/20 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Send Proof to Client
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Proof Versions & Approval Timeline --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-navy-900 font-display text-base flex items-center gap-2">
                        <i class="fa-solid fa-code-compare text-pink-500"></i> Proof History &amp; Revision Rounds ({{ $printRequest->designProofs->count() }})
                    </h3>
                </div>

                <div class="space-y-6">
                    @forelse($printRequest->designProofs->sortByDesc('version') as $proof)
                        <div class="p-5 rounded-2xl border {{ $proof->status === 'approved' ? 'bg-emerald-50/40 border-emerald-200' : ($proof->status === 'revision_requested' ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50/60 border-slate-200') }} space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-8 w-8 rounded-xl bg-navy-900 text-white font-black flex items-center justify-center text-xs">
                                        v{{ $proof->version }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-navy-900 text-sm">{{ $proof->proof_file_name }}</div>
                                        <div class="text-[11px] text-slate-400">Uploaded by {{ $proof->designer->name ?? 'Layout Designer' }} &bull; {{ $proof->created_at->format('M d, Y h:i A') }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $proof->status_badge_class }}">
                                    {{ $proof->status_label }}
                                </span>
                            </div>

                            @if($proof->designer_notes)
                                <div class="bg-white p-3 rounded-xl border border-slate-100 text-xs">
                                    <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px] block mb-0.5">Designer Comment:</span>
                                    <p class="text-slate-700">{{ $proof->designer_notes }}</p>
                                </div>
                            @endif

                            @if($proof->customer_feedback)
                                <div class="bg-amber-50/60 p-3 rounded-xl border border-amber-200 text-xs">
                                    <span class="font-bold text-amber-800 uppercase tracking-wider text-[10px] block mb-0.5"><i class="fa-solid fa-comment-dots mr-1"></i> Customer Feedback:</span>
                                    <p class="text-amber-900">{{ $proof->customer_feedback }}</p>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                                <a href="{{ Storage::url($proof->proof_file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs transition">
                                    <i class="fa-solid fa-eye text-pink-500"></i> View Proof
                                </a>

                                @if($proof->status === 'approved' && !$proof->production_file_path)
                                    <form method="POST" action="{{ route('designer.uploadProductionFile', $proof) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="production_file" required accept=".pdf,.ai,.eps,.zip" class="text-xs text-slate-500 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                                            Upload Production File
                                        </button>
                                    </form>
                                @elseif($proof->production_file_path)
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-emerald-700"><i class="fa-solid fa-circle-check mr-1"></i> Ready for Print</span>
                                        <a href="{{ Storage::url($proof->production_file_path) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-xs transition">
                                            <i class="fa-solid fa-download mr-1"></i> Final File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-image text-3xl mb-2 text-slate-300"></i>
                            <p class="text-sm">No proofs uploaded yet. Upload version 1 using the form on the left.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
