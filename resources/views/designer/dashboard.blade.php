@extends('layouts.internal')
@section('title', 'Layout Designer Workspace')
@section('page-title', 'Artwork & Design Proofing')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Designer Banner --}}
    <div class="bg-gradient-to-r from-purple-900 via-indigo-900 to-brand-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-purple-500/20 border border-purple-400/30 flex items-center justify-center text-white font-bold text-xl shrink-0 shadow-inner">
                    <i class="fa-solid fa-palette text-2xl text-purple-300"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold font-display text-white">Layout & Pre-Press Studio</h2>
                    <p class="text-xs text-purple-200 mt-0.5">Manage customer artwork proofs, bleed checks, vector pre-flighting, and design approvals.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-xl bg-purple-500/20 text-purple-200 text-xs font-bold border border-purple-400/30">
                    <i class="fa-solid fa-pen-ruler mr-1"></i> Pre-Flight Mode
                </span>
            </div>
        </div>
    </div>

    {{-- Designer KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending Artwork Review</p>
                <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-file-image"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ count($pendingProofs) }}</h3>
            <p class="text-[11px] text-purple-600 font-medium mt-1">Ready for pre-press verification</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Approved Proofs</p>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $approvedProofs }}</h3>
            <p class="text-[11px] text-emerald-600 font-medium mt-1">Cleared for plate printing</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revision Requests</p>
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $revisionRequests }}</h3>
            <p class="text-[11px] text-amber-600 font-medium mt-1">Awaiting client artwork re-upload</p>
        </div>
    </div>

    {{-- Customer Artwork Queue --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-navy-900 text-base">Artwork Pre-Press Queue</h3>
            <span class="text-xs text-slate-500 font-medium">Layout Designer Module &middot; Scoped View</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Request #</th>
                        <th class="px-6 py-3.5">Customer & Service</th>
                        <th class="px-6 py-3.5">Artwork Specs</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Pre-Press Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingProofs as $req)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $req->user->name ?? 'Customer' }}</p>
                            <p class="text-[11px] text-slate-500">{{ $req->service }} &middot; {{ number_format($req->quantity) }} pcs</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 font-semibold text-[11px]">
                                <i class="fa-solid fa-file-pdf mr-1"></i> PDF/AI (CMYK 300DPI)
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase bg-blue-100 text-blue-700">
                                {{ strtoupper($req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('staff.print-requests.show', $req) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition">
                                Inspect Artwork &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No artwork files currently pending pre-flight review.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
