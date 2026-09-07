@extends('layouts.internal')

@section('title', 'Executive Procurement & Purchase Approvals')
@section('page-title', 'Procurement Control')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: PROCUREMENT CONTROL --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-500 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Executive Procurement &amp; Purchase Approvals</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('management.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back to Dashboard
                </a>
                <a href="{{ route('management.inventory.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-xs"></i> Inventory Hub
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3 KEY PROCUREMENT KPI CARDS (RIGHT-ALIGNED NUMBERS) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="PENDING APPROVALS"
            :value="number_format($pendingCount)"
            icon="fa-solid fa-clock-rotate-left"
            accent="amber"
        />

        <x-dashboard.kpi-card 
            title="APPROVED PURCHASE ORDERS"
            :value="number_format($approvedCount)"
            icon="fa-solid fa-circle-check"
            accent="emerald"
        />

        <x-dashboard.kpi-card 
            title="TOTAL PROCUREMENT BUDGET"
            :value="'₱' . number_format($totalSpent, 2)"
            icon="fa-solid fa-coins"
            accent="cyan"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PURCHASE REQUISITIONS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px]">
                    <tr>
                        <th class="px-6 py-4">PR #</th>
                        <th class="px-6 py-4">Branch</th>
                        <th class="px-6 py-4">Material</th>
                        <th class="px-6 py-4">Quantity</th>
                        <th class="px-6 py-4">Total Budget</th>
                        <th class="px-6 py-4">Requested By</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Approval Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($requests as $req)
                    <tr class="hover:bg-cyber-hover/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-cyber-main">PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-semibold text-cyber-main">{{ $req->branch->name ?? 'Main Hub' }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $req->material->name ?? 'Material' }}</td>
                        <td class="px-6 py-4 font-bold font-mono">{{ number_format($req->quantity) }}</td>
                        <td class="px-6 py-4 font-bold font-mono text-emerald-600 dark:text-emerald-400">₱{{ number_format($req->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-cyber-muted">{{ $req->user->name ?? 'Manager' }}</td>
                        <td class="px-6 py-4">
                            @if($req->status === 'pending')
                                <span class="px-2.5 py-1 text-[10px] font-bold font-mono uppercase rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30">Pending Review</span>
                            @elseif($req->status === 'approved')
                                <span class="px-2.5 py-1 text-[10px] font-bold font-mono uppercase rounded-full bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30">Approved</span>
                            @elseif($req->status === 'received')
                                <span class="px-2.5 py-1 text-[10px] font-bold font-mono uppercase rounded-full bg-sky-500/15 text-sky-700 dark:text-sky-400 border border-sky-500/30">Stocked</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold font-mono uppercase rounded-full bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if($req->status === 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('management.purchasing.approve', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">Approve</button>
                                    </form>
                                    <form action="{{ route('management.purchasing.reject', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-cyber-muted text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-cyber-muted text-xs">
                            <i class="fa-solid fa-box-archive text-cyber-sub text-3xl mb-2 block"></i>
                            No purchase requests submitted for review.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-cyber/60">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
