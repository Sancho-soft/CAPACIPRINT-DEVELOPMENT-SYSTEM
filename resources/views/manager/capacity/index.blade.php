@extends('layouts.internal')
@section('title', 'Capacity Evaluation')
@section('page-title', 'Branch Capacity Evaluation Engine')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div>
        <h2 class="text-xl font-bold text-navy-900 font-display">Multi-Factor Capacity Evaluation Engine</h2>
        <p class="text-xs text-slate-500">Evaluates machine status, workload, staff, material inventory, and deadline feasibility for branch allocation.</p>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-navy-900 text-sm">Print Requests Awaiting Capacity Evaluation</h3>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Req #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service &amp; Qty</th>
                    <th class="px-6 py-3.5">Deadline</th>
                    <th class="px-6 py-3.5">Preferred Branch</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pendingRequests as $req)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $req->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $req->service }} ({{ number_format($req->quantity) }} pcs)</td>
                    <td class="px-6 py-4 text-slate-500">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $req->preferred_branch ?? 'Any' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('manager.capacity.evaluate', $req) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
                            <i class="fa-solid fa-calculator mr-1"></i> Run Algorithm
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">All print requests have been evaluated.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $pendingRequests->links() }}</div>
    </div>
</div>
@endsection
