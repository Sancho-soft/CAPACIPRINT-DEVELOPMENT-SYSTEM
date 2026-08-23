@extends('layouts.internal')
@section('title', 'Design & Layout Management')
@section('page-title', 'Design & Layout Workspace')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-pink-900 to-rose-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-pink-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Design &amp; Layout Management</h2>
                <p class="text-xs sm:text-sm text-slate-300">Create layouts, upload proofs for customer approval, handle revision rounds, and prepare final production files.</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('designer.index') }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-navy-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Jobs
            </a>
            <a href="{{ route('designer.index', ['status' => 'needs_proof']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'needs_proof' ? 'bg-navy-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Needs Proof
            </a>
            <a href="{{ route('designer.index', ['status' => 'revision_requested']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'revision_requested' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Revision Requested
            </a>
            <a href="{{ route('designer.index', ['status' => 'approved']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'approved' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Approved &amp; Ready
            </a>
        </div>
    </div>

    {{-- Design Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($printRequests as $req)
            @php $latestProof = $req->latestProof; @endphp
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg font-bold">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-navy-900 font-display text-sm">#PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-xs text-slate-400">{{ $req->user->name ?? 'Customer' }}</p>
                            </div>
                        </div>
                        @if($latestProof)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $latestProof->status_badge_class }}">
                                {{ $latestProof->status_label }} (v{{ $latestProof->version }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                No Proof Yet
                            </span>
                        @endif
                    </div>

                    <div class="bg-slate-50 p-3 rounded-2xl text-xs space-y-1">
                        <div class="flex justify-between"><span class="text-slate-400">Service:</span><strong class="text-navy-900">{{ $req->service }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-400">Dimensions:</span><span class="text-slate-700">{{ $req->size }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-400">Material:</span><span class="text-slate-700">{{ $req->material }}</span></div>
                    </div>

                    @if($req->design_file_path)
                        <div class="flex items-center justify-between text-xs text-slate-500 bg-pink-50/50 p-2.5 rounded-xl border border-pink-100">
                            <span class="truncate max-w-[160px]"><i class="fa-solid fa-paperclip text-pink-500 mr-1"></i> {{ $req->design_file_name ?? 'Uploaded Artwork' }}</span>
                            <a href="{{ Storage::url($req->design_file_path) }}" target="_blank" class="text-pink-600 font-bold hover:underline">
                                <i class="fa-solid fa-download"></i> Download
                            </a>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">
                        {{ $req->designProofs->count() }} proof version(s)
                    </span>
                    <a href="{{ route('designer.show', $req) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-pink-500 hover:bg-pink-600 text-white font-bold text-xs shadow-md shadow-pink-500/20 transition">
                        <i class="fa-solid fa-pen-ruler"></i> Open Workspace
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-100">
                <i class="fa-solid fa-paint-brush text-3xl mb-2 text-slate-300"></i>
                <p class="text-sm">No design requests found matching your filter.</p>
            </div>
        @endforelse
    </div>

    @if($printRequests->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-100">
            {{ $printRequests->links() }}
        </div>
    @endif
</div>
@endsection
