@extends('layouts.internal')
@section('title', 'Design & Pre-Press Workspace')
@section('page-title', 'Pre-Press Artwork & Proofing Queue')

@section('content')
<div class="space-y-6 max-w-7xl" x-data="{ view: 'table' }">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Design &amp; Pre-Press Workspace</h2>
            <p class="text-xs text-cyber-muted mt-1">Inspect client artwork, run pre-flight checks, generate digital proofs, and upload final production-ready vector files.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('staff.dashboard') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub text-cyber-main text-xs font-semibold border border-cyber flex items-center gap-2 transition">
                <i class="fa-solid fa-gauge-high text-xs text-brand-600 dark:text-blue-400"></i> Staff Dashboard
            </a>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-cyber-card p-3 sm:p-4 rounded-xl border border-cyber flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        {{-- Queue Tabs --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('designer.index', request()->only('search')) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-2 {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-cyber-sub text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] border border-cyber' }}">
                <span>All Jobs</span>
                <span class="px-1.5 py-0.5 rounded text-[11px] font-sans tabular-nums font-bold tabular-nums {{ !request('status') ? 'bg-white/20 text-white' : 'bg-cyber-card text-cyber-muted' }}">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'needs_proof'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-2 {{ request('status') === 'needs_proof' ? 'bg-indigo-600 text-white' : 'bg-cyber-sub text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] border border-cyber' }}">
                <span>Needs Proof</span>
                <span class="px-1.5 py-0.5 rounded text-[11px] font-sans tabular-nums font-bold tabular-nums {{ request('status') === 'needs_proof' ? 'bg-white/20 text-white' : 'bg-indigo-500/15 text-indigo-600 dark:text-indigo-400' }}">
                    {{ $counts['needs_proof'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'revision_requested'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-2 {{ request('status') === 'revision_requested' ? 'bg-amber-600 text-white' : 'bg-cyber-sub text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] border border-cyber' }}">
                <span>Revision Requested</span>
                <span class="px-1.5 py-0.5 rounded text-[11px] font-sans tabular-nums font-bold tabular-nums {{ request('status') === 'revision_requested' ? 'bg-white/20 text-white' : 'bg-amber-500/15 text-amber-700 dark:text-amber-400' }}">
                    {{ $counts['revision_requested'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'approved'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-2 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white' : 'bg-cyber-sub text-cyber-main hover:bg-black/[0.04] dark:hover:bg-white/[0.05] border border-cyber' }}">
                <span>Approved &amp; Ready</span>
                <span class="px-1.5 py-0.5 rounded text-[11px] font-sans tabular-nums font-bold tabular-nums {{ request('status') === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400' }}">
                    {{ $counts['approved'] ?? 0 }}
                </span>
            </a>
        </div>

        {{-- Search Input & View Switcher --}}
        <div class="flex items-center gap-2.5">
            <form method="GET" action="{{ route('designer.index') }}" class="relative min-w-[220px] sm:min-w-[260px]">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-sub">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search #REQ, customer..." 
                       class="w-full pl-9 pr-3.5 py-2 text-xs rounded-lg bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-sub focus:outline-none focus:ring-2 focus:ring-brand-600">
            </form>

            {{-- View Switcher Buttons --}}
            <div class="flex items-center bg-cyber-sub p-1 rounded-lg border border-cyber shrink-0">
                <button type="button" 
                        @click="view = 'table'" 
                        :class="view === 'table' ? 'bg-cyber-card text-brand-600 dark:text-blue-400 border border-cyber' : 'text-cyber-muted hover:text-cyber-main border border-transparent'"
                        class="px-2.5 py-1 rounded text-xs font-semibold transition flex items-center gap-1.5"
                        title="Table View">
                    <i class="fa-solid fa-table-list"></i>
                    <span class="hidden sm:inline">Table</span>
                </button>
                <button type="button" 
                        @click="view = 'grid'" 
                        :class="view === 'grid' ? 'bg-cyber-card text-brand-600 dark:text-blue-400 border border-cyber' : 'text-cyber-muted hover:text-cyber-main border border-transparent'"
                        class="px-2.5 py-1 rounded text-xs font-semibold transition flex items-center gap-1.5"
                        title="Grid Cards View">
                    <i class="fa-solid fa-grip"></i>
                    <span class="hidden sm:inline">Cards</span>
                </button>
            </div>
        </div>
    </div>

    {{-- VIEW 1: PRE-PRESS QUEUE TABLE (Default) --}}
    <div id="designer-queue-card" x-show="view === 'table'" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px] bg-cyber-sub">
                    <tr>
                        <th class="px-6 py-4">Request #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Service</th>
                        <th class="px-6 py-4">Specifications</th>
                        <th class="px-6 py-4">Client Artwork</th>
                        <th class="px-6 py-4">Proof Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($printRequests as $req)
                        @php $latestProof = $req->latestProof; @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            {{-- Request ID & Date --}}
                            <td class="px-6 py-4 font-sans tabular-nums">
                                <span class="font-bold text-slate-900 dark:text-white text-xs">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="block text-[11px] text-slate-400 mt-0.5">{{ $req->created_at->format('M d, Y') }}</span>
                            </td>

                            {{-- Customer --}}
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-xs">
                                {{ $req->user->name ?? 'Customer' }}
                            </td>

                            {{-- Service --}}
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-200 text-xs">
                                {{ $req->service }}
                            </td>

                            {{-- Specifications --}}
                            <td class="px-6 py-4">
                                <div class="text-[11px] space-y-0.5">
                                    <p class="font-sans tabular-nums text-slate-800 dark:text-slate-200"><span class="text-slate-400">Size:</span> {{ $req->size }}</p>
                                    <p class="text-slate-500 dark:text-slate-400 truncate max-w-[200px]"><span class="text-slate-400">Mat:</span> {{ $req->material }}</p>
                                </div>
                            </td>

                            {{-- Uploaded Client File --}}
                            <td class="px-6 py-4">
                                @if($req->design_file_path)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ Storage::url($req->design_file_path) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-medium border border-slate-200 dark:border-slate-700 transition"
                                           title="{{ $req->design_file_name }}">
                                            <i class="fa-solid fa-paperclip text-blue-600 dark:text-blue-400 text-xs"></i>
                                            <span class="truncate max-w-[130px]">{{ $req->design_file_name ?? 'Download Artwork' }}</span>
                                            <i class="fa-solid fa-download text-[11px] text-slate-400 ml-1"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">No file attached</span>
                                @endif
                            </td>

                            {{-- Proof Status & Rounds --}}
                            <td class="px-6 py-4">
                                @if($latestProof)
                                    <div>
                                        <span class="text-xs font-sans font-semibold
                                            {{ match($latestProof->status) {
                                                'approved'           => 'text-emerald-600 dark:text-emerald-400',
                                                'revision_requested' => 'text-amber-600 dark:text-amber-400',
                                                default              => 'text-blue-600 dark:text-blue-400',
                                            } }}">
                                            {{ $latestProof->status_label }}
                                        </span>
                                        <span class="block text-[11px] text-slate-400 font-sans tabular-nums mt-0.5">
                                            Version v{{ $latestProof->version }} &middot; {{ $req->designProofs->count() }} round(s)
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs font-sans font-semibold text-purple-600 dark:text-purple-400">
                                        Needs Proof v1
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('designer.show', $req) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition">
                                    <span>Open Canvas</span>
                                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">No design requests found in this queue.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Try clearing your search query or selecting a different status filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- VIEW 2: CARDS GRID VIEW --}}
    <div x-show="view === 'grid'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($printRequests as $req)
            @php $latestProof = $req->latestProof; @endphp
            <div class="bg-white dark:bg-[#151922] rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-sm hover:border-blue-500/40 dark:hover:border-blue-500/30 transition p-5 sm:p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3.5">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base font-bold group-hover:scale-105 transition-transform shrink-0">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-slate-900 dark:text-white font-display text-sm font-sans tabular-nums truncate">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $req->user->name ?? 'Customer' }}</p>
                            </div>
                        </div>

                        @if($latestProof)
                            <span class="text-xs font-sans font-semibold shrink-0
                                {{ match($latestProof->status) {
                                    'approved'           => 'text-emerald-600 dark:text-emerald-400',
                                    'revision_requested' => 'text-amber-600 dark:text-amber-400',
                                    default              => 'text-blue-600 dark:text-blue-400',
                                } }}">
                                {{ $latestProof->status_label }}
                            </span>
                        @else
                            <span class="text-xs font-sans font-semibold text-purple-600 dark:text-purple-400 shrink-0">
                                Needs Proof v1
                            </span>
                        @endif
                    </div>

                    {{-- Specs Box --}}
                    <div class="bg-slate-50 dark:bg-[#101319] p-3.5 rounded-2xl text-xs space-y-1.5 border border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Service:</span><strong class="text-slate-900 dark:text-white">{{ $req->service }}</strong></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Dimensions:</span><span class="text-slate-700 dark:text-slate-300 font-sans tabular-nums">{{ $req->size }}</span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Material:</span><span class="text-slate-700 dark:text-slate-300 truncate max-w-[170px]">{{ $req->material }}</span></div>
                    </div>

                    {{-- Uploaded Artwork Link --}}
                    @if($req->design_file_path)
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-[#101319] p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800">
                            <span class="truncate max-w-[160px] text-slate-700 dark:text-slate-300 text-xs font-medium">
                                <i class="fa-solid fa-paperclip text-blue-600 dark:text-blue-400 mr-1"></i> {{ $req->design_file_name ?? 'Uploaded File' }}
                            </span>
                            <a href="{{ Storage::url($req->design_file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-bold hover:underline flex items-center gap-1 text-xs">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-sans tabular-nums">
                        {{ $req->designProofs->count() }} proof round(s)
                    </span>
                    <a href="{{ route('designer.show', $req) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition">
                        <span>Open Canvas</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-[#151922] rounded-2xl border border-slate-200 dark:border-slate-800/80">
                <i class="fa-solid fa-paint-brush text-3xl mb-2 text-slate-400 dark:text-slate-600"></i>
                <p class="text-sm font-medium">No design requests found matching your filter.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($printRequests->hasPages())
        <div class="p-4 bg-white dark:bg-[#151922] rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-sm">
            {{ $printRequests->links() }}
        </div>
    @endif
</div>
@endsection
