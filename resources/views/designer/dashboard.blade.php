@extends('layouts.internal')
@section('title', 'Layout Designer Workspace')
@section('page-title', 'Artwork & Design Proofing')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Designer Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-display">Layout &amp; Pre-Press Studio</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage customer artwork proofs, bleed checks, vector pre-flighting, and design approvals.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('designer.index') }}" class="px-3.5 py-2 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 text-purple-700 dark:text-purple-400 text-xs font-bold border border-purple-500/20 flex items-center gap-2 transition shadow-xs">
                <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> Pre-Flight Workspace
            </a>
        </div>
    </div>

    {{-- Designer KPI Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
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

    {{-- Operational Attention Center for Pre-Press --}}
    <x-dashboard.attention-center 
        :items="$attentionItems ?? []"
        title="Pre-Press Attention Center"
        subtitle="Customer revision requests and incoming artwork requiring immediate designer action"
    />

    {{-- Customer Artwork Pre-Press Queue Table --}}
    <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/50 dark:bg-[#0D1520]">
            <div>
                <h3 class="font-black text-slate-900 dark:text-white text-sm">Artwork Pre-Press Queue</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Layout Designer Module &middot; Scoped View</p>
            </div>
            <a href="{{ route('designer.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 dark:text-cyan-400 dark:hover:text-cyan-300 flex items-center gap-1 shrink-0">
                View Full Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800/80 text-[10px]">
                    <tr>
                        <th class="px-6 py-3.5">Request #</th>
                        <th class="px-6 py-3.5">Customer &amp; Service</th>
                        <th class="px-6 py-3.5">Artwork Specs</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Pre-Press Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($pendingProofs as $req)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white font-mono">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 dark:text-white">{{ $req->user->name ?? 'Customer' }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $req->service }} &middot; {{ number_format($req->quantity) }} pcs</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[11px] border border-purple-500/20">
                                <i class="fa-solid fa-file-pdf"></i> PDF/AI (CMYK 300DPI)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $req->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('designer.show', $req) }}" class="bg-sky-600 hover:bg-sky-500 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                <span>Inspect Artwork</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                            <i class="fa-solid fa-folder-open text-2xl text-slate-400 dark:text-slate-600 block mb-2"></i>
                            No artwork files currently pending pre-flight review.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
