@extends('layouts.internal')
@section('title', 'Branch Network Performance')
@section('page-title', 'Branch Network Performance')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-cyber-main font-display">Branch Network Performance</h2>
            <p class="text-xs text-cyber-muted mt-1">Real-time load balancing, capacity distribution, and job throughput across all printing nodes.</p>
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <a href="{{ route('admin.branches.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Branch
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($branches as $b)
        <div class="bg-cyber-card p-6 rounded-3xl border border-cyber shadow-xl hover:border-cyan-500/30 transition space-y-4 flex flex-col justify-between group">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-black text-cyber-main text-lg font-display">{{ $b->name }}</h3>
                        <p class="text-xs text-cyber-muted mt-0.5"><i class="fa-solid fa-location-dot mr-1 text-cyan-500"></i> {{ $b->location }} &middot; <strong class="text-cyber-main">{{ $b->manager_name ?? 'Branch Manager' }}</strong></p>
                    </div>
                    <span class="text-[10px] font-black text-cyan-500 uppercase bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/20">Active Node</span>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center text-xs pt-1">
                    <div class="bg-cyber-sub p-2.5 rounded-2xl border border-cyber">
                        <span class="text-cyber-muted block text-[10px] font-bold uppercase tracking-wider">Active</span>
                        <strong class="text-cyber-main text-sm font-black font-mono">{{ $b->active_jobs }}</strong>
                    </div>
                    <div class="bg-emerald-500/10 p-2.5 rounded-2xl border border-emerald-500/20">
                        <span class="text-emerald-400 block text-[10px] font-bold uppercase tracking-wider">Done</span>
                        <strong class="text-emerald-400 text-sm font-black font-mono">{{ $b->completed_jobs }}</strong>
                    </div>
                    <div class="bg-red-500/10 p-2.5 rounded-2xl border border-red-500/20">
                        <span class="text-red-400 block text-[10px] font-bold uppercase tracking-wider">Delayed</span>
                        <strong class="text-red-400 text-sm font-black font-mono">{{ $b->delayed_jobs }}</strong>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5 pt-2 border-t border-cyber">
                <div class="flex justify-between text-[10px] font-black">
                    <span class="text-cyber-muted uppercase tracking-wider">Capacity Load</span>
                    <span class="{{ $b->workload_percent >= 80 ? 'text-red-400' : ($b->workload_percent >= 50 ? 'text-amber-400' : 'text-emerald-400') }} font-mono">{{ $b->workload_percent }}%</span>
                </div>
                <div class="w-full h-2 bg-cyber-sub rounded-full overflow-hidden border border-cyber">
                    <div class="h-full {{ $b->workload_percent >= 80 ? 'bg-red-500' : ($b->workload_percent >= 50 ? 'bg-amber-400' : 'bg-gradient-to-r from-cyan-400 to-emerald-400') }} rounded-full" 
                         style="width: {{ $b->workload_percent }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

