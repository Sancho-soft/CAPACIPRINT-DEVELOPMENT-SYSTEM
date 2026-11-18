@extends('layouts.internal')
@section('title', 'Layout Designer Workspace')
@section('page-title', 'Artwork & Design Proofing')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 block mb-1">Pre-Press &amp; Artwork</span>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('designer.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-compass-drafting text-xs text-blue-600 dark:text-blue-400"></i> Design Workspace
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3 KEY DESIGNER METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="PENDING ARTWORK REVIEW"
            :value="$needsProofCount ?? count($pendingProofs)"
            icon="fa-solid fa-file-image"
            accent="purple"
            :link="route('designer.index', ['status' => 'needs_proof'])"
        />

        <x-dashboard.kpi-card 
            title="APPROVED PROOFS"
            :value="$approvedProofs"
            icon="fa-solid fa-circle-check"
            accent="emerald"
            :link="route('designer.index', ['status' => 'approved'])"
        />

        <x-dashboard.kpi-card 
            title="REVISION REQUESTS"
            :value="$revisionRequests"
            icon="fa-solid fa-arrows-rotate"
            accent="amber"
            :link="route('designer.index', ['status' => 'revision_requested'])"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ARTWORK PRE-PRESS QUEUE TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden flex flex-col shadow-sm">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber-sub flex items-center justify-between bg-cyber-sub">
            <div>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Artwork Pre-Press Queue</h3>
            </div>
            <a href="{{ route('designer.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1 shrink-0">
                View Full Queue <i class="fa-solid fa-arrow-right text-[11px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans font-bold border-b border-cyber text-xs">
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
                            <td class="px-5 py-3.5 font-bold text-cyber-main font-sans tabular-nums whitespace-nowrap">
                                #REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-bold text-cyber-main">{{ $req->user->name ?? 'Customer' }}</p>
                                <p class="text-[11px] text-cyber-muted mt-0.5">{{ $req->service }} &middot; {{ number_format($req->quantity) }} pcs</p>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($req->design_file_path)
                                    @php
                                        $ext = strtolower(pathinfo($req->design_file_path, PATHINFO_EXTENSION));
                                        $fileIcon = match($ext) {
                                            'pdf' => 'fa-solid fa-file-pdf text-rose-500',
                                            'ai', 'eps' => 'fa-solid fa-file-lines text-amber-500',
                                            'psd' => 'fa-solid fa-file-image text-indigo-500',
                                            'jpg', 'jpeg', 'png' => 'fa-solid fa-image text-blue-500',
                                            default => 'fa-solid fa-file text-cyber-muted',
                                        };
                                        $displayName = $req->design_file_name ?? (strtoupper($ext) . ' Artwork');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 font-sans text-xs text-cyber-main font-semibold" title="{{ $req->design_file_name ?? 'Artwork Uploaded' }}">
                                        <i class="{{ $fileIcon }} text-xs"></i>
                                        <span class="truncate max-w-[140px]">{{ $displayName }}</span>
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
                                        str_contains($statusLower, 'approved') => 'bg-emerald-500',
                                        str_contains($statusLower, 'revision') => 'bg-amber-500',
                                        str_contains($statusLower, 'cancel') || str_contains($statusLower, 'reject') => 'bg-rose-500',
                                        default => 'bg-blue-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 font-medium text-cyber-main text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ $req->status_label ?? ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('designer.show', $req) }}" 
                                   class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center" 
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
            <div class="px-5 py-3 border-t border-cyber/60 bg-cyber-sub/40">
                {{ $pendingProofs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
