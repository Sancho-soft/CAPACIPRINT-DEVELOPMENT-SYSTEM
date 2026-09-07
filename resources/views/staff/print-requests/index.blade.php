@extends('layouts.internal')
@section('title', 'Customer Print Requests')
@section('page-title', 'Customer Print Requests Desk')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-file-circle-question"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Customer Print Requests</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 font-mono">
                            Front Desk Queue
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        Audit incoming artwork submissions, review customer specifications, and generate price quotations.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('staff.quotations.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs shadow-[0_0_15px_rgba(6,182,212,0.3)] transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> New Quotation
                </a>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-cyber-card border border-cyber p-4 rounded-2xl shadow-md">
        <form method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs text-cyan-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, email, or service..."
                           class="w-full rounded-xl border border-cyber bg-cyber-sub pl-9 pr-4 py-2 text-xs font-medium text-cyber-main placeholder-cyber-muted/60 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition">
                </div>
                <select name="status" 
                        class="rounded-xl border border-cyber bg-cyber-sub px-3.5 py-2 text-xs font-medium text-cyber-main focus:border-cyan-400 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status')=='submitted' ? 'selected':'' }}>Submitted (New)</option>
                    <option value="quotation" {{ request('status')=='quotation' ? 'selected':'' }}>Quotation Created</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Completed</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white px-5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter text-[10px]"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('staff.print-requests.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-cyber-main text-xs font-bold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Requests Table Container --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
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
                <table class="w-full text-left text-xs">
                    <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                        <tr>
                            <th class="px-6 py-3.5">Req #</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Service</th>
                            <th class="px-6 py-3.5">Specifications</th>
                            <th class="px-6 py-3.5">Deadline</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @foreach($printRequests as $req)
                        <tr class="hover:bg-cyber-sub/40 transition">
                            <td class="px-6 py-4 font-mono font-bold text-cyber-main">
                                PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                                <span class="text-[10px] text-cyber-sub block font-sans">{{ $req->created_at ? $req->created_at->diffForHumans() : '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-cyber-main block">{{ $req->user->name ?? 'Direct Customer' }}</span>
                                <span class="text-[10px] text-cyber-muted block truncate max-w-[150px]">{{ $req->user->email ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-cyan-400">
                                {{ $req->service }}
                            </td>
                            <td class="px-6 py-4 text-cyber-muted">
                                <span class="font-semibold text-cyber-main block">{{ $req->quantity }} copies &middot; {{ $req->size }}</span>
                                <span class="text-[10px] text-cyber-sub block truncate max-w-[200px]">{{ $req->material }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-cyber-muted">
                                {{ $req->deadline?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $req->status_badge_class }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('staff.print-requests.show', $req) }}"
                                       class="h-8 w-8 rounded-xl bg-cyber-sub hover:bg-cyan-500/20 text-cyber-muted hover:text-cyan-400 border border-cyber hover:border-cyan-500/40 flex items-center justify-center transition shadow-sm"
                                       title="Review Specifications &amp; Artwork">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    @if($req->status === 'submitted')
                                    <a href="{{ route('staff.quotations.create', ['print_request_id' => $req->id]) }}" 
                                       class="inline-flex items-center gap-1.5 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-3 py-1.5 rounded-xl text-xs shadow-sm transition">
                                        <i class="fa-solid fa-file-invoice-dollar text-[10px]"></i> Quote
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
                <div class="p-4 border-t border-cyber bg-cyber-sub/40">
                    {{ $printRequests->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
