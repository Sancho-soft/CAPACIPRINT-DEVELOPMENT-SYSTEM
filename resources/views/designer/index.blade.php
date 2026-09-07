@extends('layouts.internal')
@section('title', 'Design & Pre-Press Workspace')
@section('page-title', 'Pre-Press Artwork & Proofing Queue')

@section('content')
<div class="space-y-6 max-w-7xl" x-data="{ view: 'table' }">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-display">Design &amp; Pre-Press Workspace</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Inspect client artwork, run pre-flight checks, generate digital proofs, and upload final production-ready vector files.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('designer.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 dark:bg-[#111A24] dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-800 flex items-center gap-2 transition shadow-xs">
                <i class="fa-solid fa-gauge-high text-xs text-cyan-600 dark:text-cyan-400"></i> Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white dark:bg-[#111A24] p-3 sm:p-4 rounded-3xl border border-slate-200 dark:border-slate-800/80 shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        {{-- Queue Tabs --}}
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('designer.index', request()->only('search')) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ !request('status') ? 'bg-cyan-500 text-slate-950 shadow-sm' : 'bg-slate-100 dark:bg-[#0D1520] text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span>All Jobs</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-mono font-black {{ !request('status') ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'needs_proof'])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request('status') === 'needs_proof' ? 'bg-purple-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-[#0D1520] text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span>Needs Proof</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-mono font-black {{ request('status') === 'needs_proof' ? 'bg-white/20 text-white' : 'bg-purple-500/15 text-purple-600 dark:text-purple-400' }}">
                    {{ $counts['needs_proof'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'revision_requested'])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request('status') === 'revision_requested' ? 'bg-amber-500 text-slate-950 shadow-sm' : 'bg-slate-100 dark:bg-[#0D1520] text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span>Revision Requested</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-mono font-black {{ request('status') === 'revision_requested' ? 'bg-slate-950/20 text-slate-950' : 'bg-amber-500/15 text-amber-600 dark:text-amber-400' }}">
                    {{ $counts['revision_requested'] ?? 0 }}
                </span>
            </a>

            <a href="{{ route('designer.index', array_merge(request()->only('search'), ['status' => 'approved'])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-[#0D1520] text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
                <span>Approved &amp; Ready</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-mono font-black {{ request('status') === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400' }}">
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
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search #REQ, customer..." 
                       class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </form>

            {{-- View Switcher Buttons --}}
            <div class="flex items-center bg-slate-100 dark:bg-[#0D1520] p-1 rounded-xl border border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" 
                        @click="view = 'table'" 
                        :class="view === 'table' ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
                        title="Table View">
                    <i class="fa-solid fa-table-list"></i>
                    <span class="hidden sm:inline">Table</span>
                </button>
                <button type="button" 
                        @click="view = 'grid'" 
                        :class="view === 'grid' ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-xs' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white'"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
                        title="Grid Cards View">
                    <i class="fa-solid fa-grip"></i>
                    <span class="hidden sm:inline">Cards</span>
                </button>
            </div>
        </div>
    </div>

    {{-- VIEW 1: PRE-PRESS QUEUE TABLE (Default) --}}
    <div x-show="view === 'table'" class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800/80 text-[10px] bg-slate-50/50 dark:bg-[#0D1520]">
                    <tr>
                        <th class="px-6 py-4">Request #</th>
                        <th class="px-6 py-4">Customer &amp; Service</th>
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
                            <td class="px-6 py-4 font-mono">
                                <span class="font-bold text-slate-900 dark:text-white text-xs">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="block text-[11px] text-slate-400 mt-0.5">{{ $req->created_at->format('M d, Y') }}</span>
                            </td>

                            {{-- Customer & Service --}}
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 dark:text-white text-xs">{{ $req->user->name ?? 'Customer' }}</p>
                                <p class="text-[11px] text-cyan-700 dark:text-cyan-400 font-medium mt-0.5">{{ $req->service }}</p>
                            </td>

                            {{-- Specifications --}}
                            <td class="px-6 py-4">
                                <div class="text-[11px] space-y-0.5">
                                    <p class="font-mono text-slate-800 dark:text-slate-200"><span class="text-slate-400">Size:</span> {{ $req->size }}</p>
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
                                            <i class="fa-solid fa-paperclip text-cyan-600 dark:text-cyan-400 text-xs"></i>
                                            <span class="truncate max-w-[130px]">{{ $req->design_file_name ?? 'Download Artwork' }}</span>
                                            <i class="fa-solid fa-download text-[10px] text-slate-400 ml-1"></i>
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $latestProof->status_badge_class }}">
                                            {{ $latestProof->status_label }}
                                        </span>
                                        <span class="block text-[10px] text-slate-400 font-mono mt-1">
                                            Version v{{ $latestProof->version }} &middot; {{ $req->designProofs->count() }} round(s)
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-700 dark:text-purple-300 border border-purple-500/20">
                                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Needs Proof v1
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('designer.show', $req) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition">
                                    <span>Open Canvas</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
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
            <div class="bg-white dark:bg-[#111A24] rounded-3xl border border-slate-200 dark:border-slate-800/80 shadow-xl hover:border-cyan-500/40 dark:hover:border-cyan-500/30 transition p-5 sm:p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3.5">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-base font-bold group-hover:scale-105 transition-transform shrink-0">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-slate-900 dark:text-white font-display text-sm font-mono truncate">#REQ-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $req->user->name ?? 'Customer' }}</p>
                            </div>
                        </div>

                        @if($latestProof)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border shrink-0 {{ $latestProof->status_badge_class }}">
                                {{ $latestProof->status_label }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-500/10 text-purple-700 dark:text-purple-300 border border-purple-500/20 shrink-0">
                                Needs Proof v1
                            </span>
                        @endif
                    </div>

                    {{-- Specs Box --}}
                    <div class="bg-slate-50 dark:bg-[#0D1520] p-3.5 rounded-2xl text-xs space-y-1.5 border border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Service:</span><strong class="text-slate-900 dark:text-white">{{ $req->service }}</strong></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Dimensions:</span><span class="text-slate-700 dark:text-slate-300 font-mono">{{ $req->size }}</span></div>
                        <div class="flex justify-between items-center"><span class="text-slate-500 dark:text-slate-400">Material:</span><span class="text-slate-700 dark:text-slate-300 truncate max-w-[170px]">{{ $req->material }}</span></div>
                    </div>

                    {{-- Uploaded Artwork Link --}}
                    @if($req->design_file_path)
                        <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-[#0D1520] p-2.5 rounded-2xl border border-slate-200 dark:border-slate-800">
                            <span class="truncate max-w-[160px] text-slate-700 dark:text-slate-300 text-xs font-medium">
                                <i class="fa-solid fa-paperclip text-cyan-600 dark:text-cyan-400 mr-1"></i> {{ $req->design_file_name ?? 'Uploaded File' }}
                            </span>
                            <a href="{{ Storage::url($req->design_file_path) }}" target="_blank" class="text-cyan-600 hover:text-cyan-700 dark:text-cyan-400 dark:hover:text-cyan-300 font-bold hover:underline flex items-center gap-1 text-xs">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                        {{ $req->designProofs->count() }} proof round(s)
                    </span>
                    <a href="{{ route('designer.show', $req) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition">
                        <span>Open Canvas</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-[#111A24] rounded-3xl border border-slate-200 dark:border-slate-800/80">
                <i class="fa-solid fa-paint-brush text-3xl mb-2 text-slate-400 dark:text-slate-600"></i>
                <p class="text-sm font-medium">No design requests found matching your filter.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($printRequests->hasPages())
        <div class="p-4 bg-white dark:bg-[#111A24] rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-sm">
            {{ $printRequests->links() }}
        </div>
    @endif
</div>
@endsection
