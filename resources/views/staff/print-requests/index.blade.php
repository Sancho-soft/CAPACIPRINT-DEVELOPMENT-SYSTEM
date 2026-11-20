@extends('layouts.internal')
@section('title', 'Customer Print Requests')
@section('page-title', 'Customer Print Requests Desk')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Customer Print Requests</h1>
            <p class="text-xs text-cyber-muted mt-1">Review specifications, prepare quotations, and manage customer intake.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('staff.print-requests.create') }}" class="px-3.5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs transition flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-file-circle-plus text-xs"></i> New Print Request
            </a>
            <a href="{{ route('staff.quotations.create') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card border border-cyber text-cyber-main hover:bg-cyber-sub font-semibold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs text-brand-600 dark:text-blue-400"></i> New Quotation
            </a>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-cyber-card border border-cyber p-4 rounded-xl">
        <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs text-brand-600 dark:text-blue-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, email, or service..."
                           class="w-full rounded-lg border border-cyber bg-cyber-sub pl-9 pr-4 py-2 text-xs font-medium text-cyber-main placeholder-cyber-muted/60 focus:border-brand-600 focus:outline-none transition">
                </div>
                <select name="status" 
                        class="rounded-lg border border-cyber bg-cyber-sub px-3.5 py-2 text-xs font-medium text-cyber-main focus:border-brand-600 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status')=='submitted' ? 'selected':'' }}>Submitted (New)</option>
                    <option value="quotation" {{ request('status')=='quotation' ? 'selected':'' }}>Quotation Created</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Completed</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-xs font-semibold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('staff.print-requests.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main text-xs font-semibold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Requests Table Container --}}
    <div id="print-requests-table-card" class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden flex flex-col">
        @if($printRequests->isEmpty())
            <div class="p-8">
                <x-dashboard.empty-state 
                    title="No Print Requests Found"
                    description="No customer print requests match your filter parameters. Check other statuses or wait for new client submissions."
                    icon="fa-solid fa-file-circle-xmark"
                    actionUrl="{{ route('staff.print-requests.index') }}"
                    actionLabel="Clear Filter"
                />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="bg-cyber-sub text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                        <tr>
                            <th class="px-5 py-3">Req #</th>
                            <th class="px-5 py-3">Customer Name</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Service</th>
                            <th class="px-5 py-3">Specifications</th>
                            <th class="px-5 py-3">Deadline</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                        @foreach($printRequests as $req)
                        <tr class="hover:bg-cyber-sub/50 transition-colors">
                            <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-cyber-main">
                                PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                                <span class="text-[11px] text-cyber-sub block font-sans font-normal">{{ $req->created_at ? $req->created_at->diffForHumans() : '' }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-cyber-main whitespace-nowrap">
                                {{ $req->user->name ?? 'Direct Customer' }}
                            </td>
                            <td class="px-5 py-3.5 text-cyber-muted text-xs whitespace-nowrap">
                                {{ $req->user->email ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-brand-600 dark:text-blue-400">
                                {{ $req->service }}
                            </td>
                            <td class="px-5 py-3.5 text-cyber-muted">
                                <span class="font-semibold text-cyber-main block">{{ $req->quantity }} copies &middot; {{ $req->size }}</span>
                                <span class="text-[11px] text-cyber-sub block truncate max-w-[200px]">{{ $req->material }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-sans tabular-nums text-cyber-muted">
                                {{ $req->deadline?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="text-xs font-semibold font-sans tabular-nums {{ match($req->status) {
                                    'submitted' => 'text-amber-600 dark:text-amber-400',
                                    'quotation' => 'text-indigo-600 dark:text-indigo-400',
                                    'payment'   => 'text-orange-600 dark:text-orange-400',
                                    'production'=> 'text-brand-600 dark:text-blue-400',
                                    'completed' => 'text-emerald-600 dark:text-emerald-400',
                                    'cancelled' => 'text-rose-600 dark:text-rose-400',
                                    default     => 'text-cyber-muted',
                                } }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('staff.print-requests.show', $req) }}"
                                       class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center"
                                       title="Review Specifications &amp; Artwork">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if($req->status === 'submitted')
                                    <a href="{{ route('staff.quotations.create', ['print_request_id' => $req->id]) }}" 
                                       class="inline-flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                        <i class="fa-solid fa-file-invoice-dollar text-[11px]"></i> Quote
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($printRequests->hasPages())
                <div class="p-4 border-t border-cyber bg-cyber-sub/30">
                    {{ $printRequests->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
