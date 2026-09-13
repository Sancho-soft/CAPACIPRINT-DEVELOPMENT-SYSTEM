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
            <h1 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('designer.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-compass-drafting text-xs text-cyan-500 dark:text-cyan-400"></i> Design Workspace
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
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Artwork Pre-Press Queue</h3>
            </div>
            <a href="{{ route('designer.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 flex items-center gap-1 shrink-0">
                View Full Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs">
                <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Request #</th>
                        <th class="px-5 py-3.5">Customer &amp; Service</th>
                        <th class="px-5 py-3.5">Artwork Specs</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($pendingProofs as $req)
                        <tr class="hover:bg-cyber-hover/50 transition">
                            <td class="px-5 py-3.5 font-bold text-cyber-main font-mono whitespace-nowrap">
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
                                            'jpg', 'jpeg', 'png' => 'fa-solid fa-image text-cyan-500',
                                            default => 'fa-solid fa-file text-cyber-muted',
                                        };
                                        $displayName = $req->design_file_name ?? (strtoupper($ext) . ' Artwork');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 font-mono text-[11px] text-cyber-main font-semibold" title="{{ $req->design_file_name ?? 'Artwork Uploaded' }}">
                                        <i class="{{ $fileIcon }} text-xs"></i>
                                        <span class="truncate max-w-[140px]">{{ $displayName }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 font-mono text-[11px] text-cyber-muted italic">
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
                                        default => 'bg-cyan-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 font-medium text-cyber-main text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ $req->status_label ?? ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('designer.show', $req) }}" 
                                   class="group h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 border border-cyan-500/25 hover:border-cyan-500 inline-flex items-center justify-center transition-all duration-200 shadow-xs" 
                                   title="Inspect Artwork &amp; Proofing Canvas">
                                    <i class="fa-solid fa-eye text-xs text-cyan-600 dark:text-cyan-400 group-hover:text-white dark:group-hover:text-slate-950 transition-colors"></i>
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
