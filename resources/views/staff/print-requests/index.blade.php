@extends('layouts.internal')
@section('title', 'Customer Requests')
@section('page-title', 'Customer Print Requests')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-navy-900 font-display">Manage Print Requests</h2>

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer or service..."
                   class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            <select name="status" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                <option value="">All Statuses</option>
                <option value="submitted" {{ request('status')=='submitted' ? 'selected':'' }}>Submitted</option>
                <option value="quotation" {{ request('status')=='quotation' ? 'selected':'' }}>Quotation</option>
                <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Completed</option>
            </select>
            <button type="submit" class="bg-navy-900 text-white px-4 py-2 rounded-xl text-xs font-bold">Filter</button>
        </form>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Req #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Specs</th>
                    <th class="px-6 py-3.5">Deadline</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($printRequests as $req)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $req->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-brand-600">{{ $req->service }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $req->quantity }} copies &middot; {{ $req->size }} &middot; {{ $req->material }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->status_badge_class }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('staff.print-requests.show', $req) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg text-xs">Review</a>
                        @if($req->status === 'submitted')
                        <a href="{{ route('staff.quotations.create', ['print_request_id' => $req->id]) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs">Quote</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">No print requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $printRequests->links() }}</div>
    </div>
</div>
@endsection
