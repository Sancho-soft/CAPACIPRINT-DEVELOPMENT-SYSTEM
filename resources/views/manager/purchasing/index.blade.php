@extends('layouts.internal')

@section('title', 'Purchasing & Material Requests')
@section('page-title', 'Procurement & Purchasing')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-display">Material Purchase Requests</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Submit material replenishment orders to Executive Management for approval.</p>
        </div>

        <button onclick="document.getElementById('new-pr-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-brand-500/20 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Purchase Request
        </button>
    </div>

    <!-- Purchase Requests Datatable -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-semibold text-xs tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">PR #</th>
                        <th class="px-6 py-4">Material</th>
                        <th class="px-6 py-4">Quantity</th>
                        <th class="px-6 py-4">Est. Cost</th>
                        <th class="px-6 py-4">Requested By</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($requests as $req)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white">PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $req->material->name ?? 'Material #' . $req->material_id }}</td>
                        <td class="px-6 py-4 font-bold">{{ number_format($req->quantity) }} units</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">₱{{ number_format($req->total_amount, 2) }}</td>
                        <td class="px-6 py-4">{{ $req->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4">
                            @if($req->status === 'pending')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Pending Approval</span>
                            @elseif($req->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Approved</span>
                            @elseif($req->status === 'received')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">Received & Stocked</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($req->status === 'approved')
                                <form action="{{ route('manager.purchasing.receive', $req) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                        Mark Stock Received
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">No purchase requests submitted yet.</td>
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

<!-- New PR Modal -->
<div id="new-pr-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display mb-4">Create Purchase Request</h3>
        <form action="{{ route('manager.purchasing.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Select Material</label>
                <select name="material_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white">
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}">{{ $mat->name }} (₱{{ number_format($mat->cost_per_unit, 2) }}/unit)</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="e.g. 50">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Unit Cost (₱)</label>
                    <input type="number" step="0.01" name="unit_cost" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="e.g. 150.00">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Notes / Justification</label>
                <textarea name="notes" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="Explain replenishment justification..."></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('new-pr-modal').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-semibold text-sm">Cancel</button>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2 rounded-xl text-sm shadow-md">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
