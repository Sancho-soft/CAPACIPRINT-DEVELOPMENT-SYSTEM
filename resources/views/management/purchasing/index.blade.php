@extends('layouts.internal')

@section('title', 'Executive Procurement & Purchase Approvals')
@section('page-title', 'Procurement Control')

@section('content')
<div class="space-y-6">

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Approvals</span>
            <div class="text-3xl font-extrabold text-amber-500 font-display mt-1">{{ number_format($pendingCount) }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Approved Purchase Orders</span>
            <div class="text-3xl font-extrabold text-emerald-500 font-display mt-1">{{ number_format($approvedCount) }}</div>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Procurement Budget</span>
            <div class="text-3xl font-extrabold text-brand-500 font-display mt-1">₱{{ number_format($totalSpent, 2) }}</div>
        </div>
    </div>

    <!-- Procurement Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-semibold text-xs tracking-wider border-b border-slate-200 dark:border-slate-800">
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
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($requests as $req)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white">PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $req->branch->name ?? 'Main Hub' }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $req->material->name ?? 'Material' }}</td>
                        <td class="px-6 py-4 font-bold">{{ number_format($req->quantity) }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($req->total_amount, 2) }}</td>
                        <td class="px-6 py-4">{{ $req->user->name ?? 'Manager' }}</td>
                        <td class="px-6 py-4">
                            @if($req->status === 'pending')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Pending Review</span>
                            @elseif($req->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Approved</span>
                            @elseif($req->status === 'received')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">Stocked</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($req->status === 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('management.purchasing.approve', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">Approve</button>
                                    </form>
                                    <form action="{{ route('management.purchasing.reject', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400">No purchase requests submitted for review.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
