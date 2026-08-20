@extends('layouts.customer')
@section('title', 'My Print Requests')
@section('page-title', 'My Print Requests')

@section('content')
<div class="space-y-6 max-w-6xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">My Print Requests</h2>
            <p class="text-sm text-slate-500 mt-1">All your submitted printing requests.</p>
        </div>
        <a href="{{ route('customer.print-requests.create') }}"
           class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Request
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-xl shadow-sm overflow-hidden">
        @if($printRequests->isEmpty())
        <div class="p-12 text-center">
            <i class="fa-solid fa-file-circle-plus text-slate-300 text-4xl mb-3"></i>
            <h4 class="font-bold text-navy-900">No print requests yet</h4>
            <p class="text-sm text-slate-500 mt-1">Submit your first request to get started.</p>
            <a href="{{ route('customer.print-requests.create') }}"
               class="inline-block mt-4 bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition">
                Submit Request
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Qty / Size</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-navy-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-navy-800 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($printRequests as $req)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-navy-900">#{{ $req->id }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $req->service }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $req->quantity }} × {{ $req->size }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded uppercase
                                {{ match($req->status) {
                                    'submitted'   => 'bg-blue-100 text-blue-800',
                                    'quotation'   => 'bg-amber-100 text-amber-800',
                                    'payment'     => 'bg-orange-100 text-orange-800',
                                    'production'  => 'bg-cyan-100 text-cyan-800',
                                    'completed'   => 'bg-green-100 text-green-800',
                                    'cancelled'   => 'bg-slate-100 text-slate-500',
                                    default       => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ $req->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.print-requests.show', $req) }}"
                               class="text-brand-500 hover:text-brand-700 font-bold text-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $printRequests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
