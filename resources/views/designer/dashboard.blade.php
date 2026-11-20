@extends('layouts.internal')
@section('title', 'Pre-Press & Proofing Queue')
@section('page-title', 'Pre-Press & Proofing')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Pre-Press &amp; Proofing</h1>
            <p class="text-sm text-cyber-muted mt-1">Incoming artwork pre-flight inspection, customer proof cycles, and print-ready file handoff.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('designer.index') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-compass-drafting text-xs"></i>
                <span>Open Design Workspace</span>
            </a>
        </div>
    </div>

    {{-- 3 KEY DESIGNER METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="Pending Artwork Review"
            :value="$needsProofCount ?? count($pendingProofs)"
            accent="blue"
            :link="route('designer.index', ['status' => 'needs_proof'])"
        />

        <x-dashboard.kpi-card 
            title="Approved Proofs"
            :value="$approvedProofs"
            accent="emerald"
            :link="route('designer.index', ['status' => 'approved'])"
        />

        <x-dashboard.kpi-card 
            title="Revision Requests"
            :value="$revisionRequests"
            accent="amber"
            :link="route('designer.index', ['status' => 'revision_requested'])"
        />
    </div>

    {{-- ARTWORK PRE-PRESS QUEUE TABLE --}}
    <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Artwork Pre-Press Queue</h3>
            <a href="{{ route('designer.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5 shrink-0">
                <span>View Full Queue</span>
                <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans font-semibold border-b border-cyber text-xs">
                    <tr>
                        <th class="px-5 py-3">Request #</th>
                        <th class="px-5 py-3">Customer &amp; Service</th>
                        <th class="px-5 py-3">Artwork Specs</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main">
                    @forelse($pendingProofs as $req)
                        <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                            <td class="px-5 py-3.5 font-mono font-semibold text-[#2F7BFF] dark:text-[#60A5FA] tabular-nums whitespace-nowrap">
                                #REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-cyber-main">{{ $req->user->name ?? 'Customer' }}</p>
                                <p class="text-xs text-cyber-muted mt-0.5 font-mono">{{ $req->service }} &middot; {{ number_format($req->quantity) }} pcs</p>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($req->design_file_path)
                                    @php
                                        $ext = strtolower(pathinfo($req->design_file_path, PATHINFO_EXTENSION));
                                        $displayName = $req->design_file_name ?? (strtoupper($ext) . ' Artwork');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 font-sans text-xs text-cyber-main font-medium" title="{{ $req->design_file_name ?? 'Artwork Uploaded' }}">
                                        <i class="fa-regular fa-file-lines text-xs text-[#2F7BFF]"></i>
                                        <span class="truncate max-w-[160px]">{{ $displayName }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 font-sans text-xs text-cyber-muted italic">
                                        <i class="fa-regular fa-file text-xs text-cyber-sub"></i> Pending Upload
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $statusLower = strtolower($req->status ?? '');
                                    $statusDot = match(true) {
                                        str_contains($statusLower, 'approved') => 'bg-[#10B981]',
                                        str_contains($statusLower, 'revision') || str_contains($statusLower, 'cancel') || str_contains($statusLower, 'reject') => 'bg-[#FFC531]',
                                        default => 'bg-[#2F7BFF]',
                                    };
                                    $statusText = match(true) {
                                        str_contains($statusLower, 'approved') => 'text-emerald-700 dark:text-[#10B981]',
                                        str_contains($statusLower, 'revision') || str_contains($statusLower, 'cancel') || str_contains($statusLower, 'reject') => 'text-amber-700 dark:text-[#FFC531]',
                                        default => 'text-[#2F7BFF] dark:text-[#60A5FA]',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs {{ $statusText }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ $req->status_label ?? ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('designer.show', $req) }}" 
                                   class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] transition text-xs inline-flex items-center justify-center" 
                                   title="Inspect Artwork &amp; Proofing Canvas">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-cyber-muted">
                                <i class="fa-solid fa-folder-open text-2xl text-cyber-sub block mb-2"></i>
                                No artwork files currently pending pre-flight review.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($pendingProofs, 'hasPages') && $pendingProofs->hasPages())
            <div class="px-5 py-3 border-t border-cyber bg-cyber-sub">
                {{ $pendingProofs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
