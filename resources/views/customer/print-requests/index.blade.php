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

    <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
        {{-- Search Toolbar matching Sample --}}
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white dark:bg-[#111A24]">
            <form method="GET" action="{{ route('customer.print-requests.index') }}" class="flex items-center gap-2">
                <div class="inline-flex rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#0D1520] overflow-hidden shadow-2xs">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search name or ID..."
                           class="px-3.5 py-1.5 text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none w-56 sm:w-64">
                    <button type="submit" 
                            class="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border-l border-slate-300 dark:border-slate-700 text-slate-500 dark:text-slate-400 transition cursor-pointer"
                            title="Search">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
                @if(request('search'))
                    <a href="{{ route('customer.print-requests.index') }}" 
                       class="text-xs text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-medium flex items-center gap-1"
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
                    <h4 class="font-bold text-navy-900 dark:text-white text-sm">No results found</h4>
                    <p class="text-xs text-slate-500 mt-1">No print requests match "{{ request('search') }}".</p>
                    <a href="{{ route('customer.print-requests.index') }}"
                       class="inline-block mt-3 px-4 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 transition">
                        Clear Search
                    </a>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="fa-solid fa-file-circle-plus text-slate-300 text-4xl mb-3"></i>
                    <h4 class="font-bold text-navy-900 dark:text-white">No print requests yet</h4>
                    <p class="text-sm text-slate-500 mt-1">Submit your first request to get started.</p>
                    <a href="{{ route('customer.print-requests.create') }}"
                       class="inline-block mt-4 bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2.5 rounded-lg text-sm transition">
                        Submit Request
                    </a>
                </div>
            @endif
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                <thead class="bg-white dark:bg-[#111A24] border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#111A24] divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($printRequests as $req)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-xs">#{{ $req->id }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-200 text-xs">{{ $req->service }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium text-xs">{{ number_format($req->quantity) }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs">{{ $req->size ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $req->status_badge_class }}">
                                {{ $req->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('customer.print-requests.show', $req) }}"
                               class="text-cyan-500 hover:text-cyan-600 dark:text-cyan-400 dark:hover:text-cyan-300 transition text-sm p-1 inline-flex items-center justify-center"
                               title="View Print Request Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $printRequests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
