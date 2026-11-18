@extends('layouts.internal')
@section('title', 'Capacity Evaluation')
@section('page-title', 'Branch Capacity Evaluation Engine')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- Page Header (Clean & Unboxed) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-slate-900 dark:text-white">Multi-Factor Capacity Evaluation Engine</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Multi-branch workload scoring, press availability, and intelligent routing allocation.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('manager.recommendations.index') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-building-circle-check text-blue-500 text-xs"></i> Saved Recommendations
            </a>
            <a href="{{ route('manager.workload.index') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-chart-line text-emerald-500 text-xs"></i> Workload Matrix
            </a>
        </div>
    </div>

    {{-- Requests Table Container --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Print Requests Awaiting Capacity Evaluation</h3>
                <p class="text-[11px] text-cyber-muted mt-0.5">Verified customer print requests ready for multi-branch scoring and press allocation</p>
            </div>
            <span class="px-3 py-1 rounded-xl text-xs font-sans tabular-nums font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                {{ $pendingRequests->total() }} Queue Item(s)
            </span>
        </div>

        @if($pendingRequests->isEmpty())
            <div class="p-8">
                <x-dashboard.empty-state 
                    title="All Requests Evaluated"
                    description="Every active customer print request has already been assigned to a qualified production branch."
                    icon="fa-solid fa-circle-check"
                    actionUrl="{{ route('manager.recommendations.index') }}"
                    actionLabel="View Recommendations"
                />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="bg-cyber-sub text-cyber-muted font-bold border-b border-cyber text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">Req ID</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Service &amp; Qty</th>
                            <th class="px-6 py-3.5">Delivery Deadline</th>
                            <th class="px-6 py-3.5">Preferred Branch</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @foreach($pendingRequests as $req)
                        <tr class="hover:bg-cyber-sub/40 transition">
                            <td class="px-6 py-4">
                                <span class="font-sans tabular-nums font-bold text-cyber-main text-xs block">#PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[11px] text-cyber-sub block mt-0.5">{{ $req->created_at ? $req->created_at->diffForHumans() : 'Recently' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-cyber-main block">{{ $req->user->name ?? 'Direct Customer' }}</span>
                                <span class="text-[11px] text-cyber-muted block truncate">{{ $req->user->email ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-blue-400 block">{{ $req->service }}</span>
                                <span class="text-[11px] text-cyber-muted">{{ number_format($req->quantity) }} pcs &middot; {{ $req->size }}</span>
                            </td>
                            <td class="px-6 py-4 font-sans tabular-nums text-cyber-muted">
                                @if($req->deadline)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-cyber-sub border border-cyber text-[11px] text-cyber-main">
                                        <i class="fa-regular fa-calendar text-blue-400 text-[11px]"></i>
                                        {{ $req->deadline->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-cyber-sub">Flexible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyber-sub border border-cyber text-cyber-main">
                                    {{ $req->preferred_branch ?? 'Any Branch' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('manager.capacity.evaluate', $req) }}" 
                                   class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2 rounded-xl text-xs shadow-sm transition cursor-pointer">
                                    <i class="fa-solid fa-bolt text-xs"></i>
                                    <span>Run Algorithm</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($pendingRequests->hasPages())
                <div class="p-4 border-t border-cyber bg-cyber-sub/40">
                    {{ $pendingRequests->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
