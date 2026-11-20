@extends('layouts.customer')
@section('title', 'My Print Requests')
@section('page-title', 'My Print Requests')

@section('content')
<div class="space-y-6 max-w-6xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-cyber-main font-display">My Print Requests</h2>
            <p class="text-sm text-cyber-muted mt-1">All your submitted printing requests.</p>
        </div>
        <a href="{{ route('customer.print-requests.create') }}"
           class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-lg text-sm transition shadow flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Request
        </a>
    </div>

    <div id="customer-requests-table-card" class="bg-cyber-card border border-cyber rounded-xl shadow-sm overflow-hidden">
        {{-- Search Toolbar matching Sample --}}
        <div class="p-4 border-b border-cyber flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-cyber-card">
            <form method="GET" action="{{ route('customer.print-requests.index') }}" class="flex items-center gap-2">
                <div class="inline-flex rounded-lg border border-cyber bg-cyber-sub overflow-hidden shadow-sm">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search name or ID..."
                           class="px-3.5 py-1.5 text-xs text-cyber-main placeholder-cyber-muted focus:outline-none w-56 sm:w-64">
                    <button type="submit" 
                            class="px-3 py-1.5 bg-cyber-sub hover:bg-cyber-sub border-l border-cyber text-cyber-muted transition cursor-pointer"
                            title="Search">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
                @if(request('search'))
                    <a href="{{ route('customer.print-requests.index') }}" 
                       class="text-xs text-cyber-muted hover:text-cyber-main dark:text-cyber-muted dark:hover:text-slate-200 font-medium flex items-center gap-1"
                       title="Clear search">
                        <i class="fa-solid fa-xmark"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        @if($printRequests->isEmpty())
            @if(request('search'))
                <div class="p-12 text-center">
                    <i class="fa-solid fa-magnifying-glass text-slate-300 text-3xl mb-2"></i>
                    <h4 class="font-bold text-cyber-main text-sm">No results found</h4>
                    <p class="text-xs text-cyber-muted mt-1">No print requests match "{{ request('search') }}".</p>
                    <a href="{{ route('customer.print-requests.index') }}"
                       class="inline-block mt-3 px-4 py-1.5 bg-cyber-sub hover:bg-cyber-sub rounded-lg text-xs font-semibold text-cyber-main transition">
                        Clear Search
                    </a>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fa-solid fa-file-circle-plus text-slate-300 text-4xl mb-3"></i>
                    <h4 class="font-bold text-cyber-main">No print requests yet</h4>
                    <p class="text-sm text-cyber-muted mt-1">Submit your first request to get started.</p>
                    <a href="{{ route('customer.print-requests.create') }}"
                       class="inline-block mt-4 bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition">
                        Submit Request
                    </a>
                </div>
            @endif
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cyber-sub text-sm tabular-nums">
                <thead class="bg-cyber-card border-b border-cyber">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">ID</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">Service</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">Quantity</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">Size</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">Deadline</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-cyber-main">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-cyber-main">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-cyber-card divide-y divide-cyber-sub">
                    @foreach($printRequests as $req)
                    <tr class="hover:bg-cyber-sub dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-cyber-main text-xs">#{{ $req->id }}</td>
                        <td class="px-6 py-4 font-medium text-cyber-main text-xs">{{ $req->service }}</td>
                        <td class="px-6 py-4 text-cyber-muted font-medium text-xs">{{ number_format($req->quantity) }}</td>
                        <td class="px-6 py-4 text-cyber-muted text-xs">{{ $req->size ?? '—' }}</td>
                        <td class="px-6 py-4 text-cyber-muted text-xs">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ match($req->status) {
                                'submitted'  => 'text-amber-600 dark:text-amber-400',
                                'quotation'  => 'text-indigo-600 dark:text-indigo-400',
                                'payment'    => 'text-orange-600 dark:text-orange-400',
                                'production' => 'text-blue-600 dark:text-blue-400',
                                'completed'  => 'text-emerald-600 dark:text-emerald-400',
                                'cancelled'  => 'text-rose-600 dark:text-rose-400',
                                default      => 'text-cyber-muted',
                            } }}">
                                {{ $req->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.print-requests.show', $req) }}"
                               class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center"
                               title="View Print Request Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-cyber">
            {{ $printRequests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
