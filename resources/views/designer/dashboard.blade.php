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
            <span class="px-3.5 py-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 text-xs font-black border border-purple-500/20 flex items-center gap-2">
                <i class="fa-solid fa-pen-ruler text-xs"></i> Pre-Flight Studio
            </span>
        </div>
    </div>

    {{-- Designer KPI Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        {{-- Pending Artwork Review --}}
        <div class="bg-white dark:bg-[#111A24] rounded-2xl border border-slate-200 dark:border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-purple-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-file-image text-2xl text-purple-500 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider leading-tight max-w-[120px]">PENDING ARTWORK REVIEW</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ count($pendingProofs) }}</div>
            </div>
        </div>

        {{-- Approved Proofs --}}
        <div class="bg-white dark:bg-[#111A24] rounded-2xl border border-slate-200 dark:border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-circle-check text-2xl text-emerald-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider leading-tight max-w-[120px]">APPROVED PROOFS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $approvedProofs }}</div>
            </div>
        </div>

        {{-- Revision Requests --}}
        <div class="bg-white dark:bg-[#111A24] rounded-2xl border border-slate-200 dark:border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-arrows-rotate text-2xl text-amber-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-wider leading-tight max-w-[120px]">REVISION REQUESTS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $revisionRequests }}</div>
            </div>
        </div>
    </div>

    {{-- Customer Artwork Pre-Press Queue Table --}}
    <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/50 dark:bg-[#0D1520]">
            <div>
                <h3 class="font-black text-slate-900 dark:text-white text-sm">Artwork Pre-Press Queue</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Layout Designer Module &middot; Scoped View</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/70 dark:bg-[#0D1520]/80 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800/80">
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
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $req->status_badge_class }}">
                                {{ $req->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('staff.print-requests.show', $req) }}" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition inline-flex items-center gap-1">
                                Inspect Artwork &rarr;
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
