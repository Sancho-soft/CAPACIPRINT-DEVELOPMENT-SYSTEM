@extends('layouts.internal')
@section('title', 'Design & Layout Management')
@section('page-title', 'Design & Layout Workspace')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Design &amp; Pre-Press Workspace</h2>
            <p class="text-xs text-slate-400 mt-1">Create layouts, upload proofs for customer approval, handle revision rounds, and prepare final production files.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-[#111A24] p-4 rounded-3xl border border-slate-800/80 shadow-xl flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('designer.index') }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-cyan-500 text-slate-950 shadow-sm' : 'bg-[#0D1520] text-slate-300 hover:bg-slate-800 border border-slate-800' }}">
                All Jobs
            </a>
            <a href="{{ route('designer.index', ['status' => 'needs_proof']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'needs_proof' ? 'bg-cyan-500 text-slate-950 shadow-sm' : 'bg-[#0D1520] text-slate-300 hover:bg-slate-800 border border-slate-800' }}">
                Needs Proof
            </a>
            <a href="{{ route('designer.index', ['status' => 'revision_requested']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'revision_requested' ? 'bg-amber-500 text-slate-950 shadow-[0_0_15px_rgba(245,158,11,0.3)]' : 'bg-[#0D1520] text-slate-300 hover:bg-slate-800 border border-slate-800' }}">
                Revision Requested
            </a>
            <a href="{{ route('designer.index', ['status' => 'approved']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'approved' ? 'bg-emerald-500 text-slate-950 shadow-[0_0_15px_rgba(16,185,129,0.3)]' : 'bg-[#0D1520] text-slate-300 hover:bg-slate-800 border border-slate-800' }}">
                Approved &amp; Ready
            </a>
        </div>
    </div>

    {{-- Design Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($printRequests as $req)
            @php $latestProof = $req->latestProof; @endphp
            <div class="bg-[#111A24] rounded-3xl border border-slate-800/80 shadow-xl hover:border-pink-500/30 transition p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-pink-500/10 border border-pink-500/20 text-pink-400 flex items-center justify-center text-lg font-bold group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-white font-display text-sm">#PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-xs text-slate-400">{{ $req->user->name ?? 'Customer' }}</p>
                            </div>
                        </div>
                        @if($latestProof)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $latestProof->status_badge_class }}">
                                {{ $latestProof->status_label }} (v{{ $latestProof->version }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-800 text-slate-400 border border-slate-700">
                                No Proof Yet
                            </span>
                        @endif
                    </div>

                    <div class="bg-[#0D1520] p-3.5 rounded-2xl text-xs space-y-1.5 border border-slate-800">
                        <div class="flex justify-between"><span class="text-slate-400">Service:</span><strong class="text-white">{{ $req->service }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-400">Dimensions:</span><span class="text-slate-300 font-mono">{{ $req->size }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Material:</span><span class="text-slate-300">{{ $req->material }}</span></div>
                    </div>

                    @if($req->design_file_path)
                        <div class="flex items-center justify-between text-xs text-slate-400 bg-[#0D1520] p-2.5 rounded-2xl border border-pink-500/20">
                            <span class="truncate max-w-[160px] text-slate-300"><i class="fa-solid fa-paperclip text-pink-400 mr-1"></i> {{ $req->design_file_name ?? 'Uploaded Artwork' }}</span>
                            <a href="{{ Storage::url($req->design_file_path) }}" target="_blank" class="text-pink-400 font-bold hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 font-mono">
                        {{ $req->designProofs->count() }} proof version(s)
                    </span>
                    <a href="{{ route('designer.show', $req) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-pink-500 hover:bg-pink-400 text-slate-950 font-bold text-xs shadow-[0_0_15px_rgba(244,63,94,0.3)] transition">
                        <i class="fa-solid fa-pen-ruler"></i> Open Workspace
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 bg-[#111A24] rounded-3xl border border-slate-800/80">
                <i class="fa-solid fa-paint-brush text-3xl mb-2 text-slate-600"></i>
                <p class="text-sm">No design requests found matching your filter.</p>
            </div>
        @endforelse
    </div>

    @if($printRequests->hasPages())
        <div class="p-4 bg-[#111A24] rounded-2xl border border-slate-800/80">
            {{ $printRequests->links() }}
        </div>
    @endif
</div>
@endsection

