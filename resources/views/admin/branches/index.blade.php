@extends('layouts.internal')
@section('title', 'Branch Management')
@section('page-title', 'Branch Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display tracking-tight">Branch Management</h2>
            <p class="text-xs text-slate-400 mt-1">Real-time operational node capacity, staff allocation, and machine telemetry.</p>
        </div>
        <div>
            <a href="{{ route('admin.branches.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Create Branch
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-triangle-exclamation text-red-400 text-base"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-[#111A24] p-5 rounded-3xl border border-slate-800/80 shadow-xl">
        <form method="GET" action="{{ route('admin.branches.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-7">
                <label class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Search Branches</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, location, or manager..." 
                           class="w-full pl-10 pr-4 py-2 text-xs bg-[#0D1520] border border-slate-800 text-white rounded-xl focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-slate-400 mb-1 uppercase tracking-wider">Status Filter</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-[#0D1520] border border-slate-800 text-white rounded-xl focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-cyan-500 hover:bg-cyan-400 text-slate-950 rounded-xl text-xs font-black transition shadow-[0_0_15px_rgba(6,182,212,0.25)]">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.branches.index') }}" class="py-2 px-3 bg-[#0D1520] hover:bg-slate-800 border border-slate-800 text-slate-400 rounded-xl text-xs font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Branches Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($branches as $b)
            <div class="bg-[#111A24] rounded-3xl border border-slate-800/80 shadow-xl hover:border-cyan-500/40 transition-all p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-4">
                    {{-- Title Row & Status Badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-lg font-bold group-hover:scale-105 transition-transform shadow-sm">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-white font-display text-base tracking-tight">{{ $b->name }}</h3>
                                <p class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-cyan-400 text-[10px]"></i> {{ $b->location }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $b->status === 'active' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30 shadow-[0_0_10px_rgba(16,185,129,0.15)]' : 'bg-slate-800/80 text-slate-400 border-slate-700' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $b->status === 'active' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                            {{ ucfirst($b->status) }}
                        </span>
                    </div>

                    {{-- Address Badge --}}
                    @if($b->address)
                        <div class="text-xs text-slate-300 bg-[#0D1520] px-3.5 py-2.5 rounded-2xl border border-slate-800/80 flex items-center gap-2 font-medium">
                            <i class="fa-solid fa-map-pin text-cyan-400 text-xs shrink-0"></i>
                            <span class="truncate">{{ $b->address }}</span>
                        </div>
                    @endif

                    {{-- Metrics Grid (Staff, Machines, Max Daily) --}}
                    <div class="grid grid-cols-3 gap-2.5 pt-1 text-center">
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800/80 space-y-0.5">
                            <div class="text-lg font-black text-white font-mono">{{ $b->employees_count }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">STAFF</div>
                        </div>
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800/80 space-y-0.5">
                            <div class="text-lg font-black text-white font-mono">{{ $b->machines_count }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">MACHINES</div>
                        </div>
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800/80 space-y-0.5">
                            <div class="text-lg font-black text-cyan-400 font-mono">{{ $b->max_daily_jobs }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">MAX DAILY</div>
                        </div>
                    </div>

                    {{-- Contact Phone Row --}}
                    <div class="text-xs text-slate-400 pt-1 font-medium border-t border-slate-800/80">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-[10px] text-slate-500"></i> Contact Phone:
                            </span>
                            <span class="font-bold text-white font-mono">{{ $b->phone ?? '0917-111-2233' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Footer --}}
                <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.branches.show', $b) }}" class="px-3.5 py-2 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 border border-cyan-500/30 text-cyan-400 hover:text-slate-950 text-xs font-bold transition flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-eye text-xs"></i> View Node Status
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.branches.edit', $b) }}" class="h-9 w-9 rounded-xl bg-[#0D1520] hover:bg-slate-800 border border-slate-800 hover:border-cyan-500/50 text-slate-400 hover:text-cyan-400 flex items-center justify-center text-xs transition shadow-sm" title="Edit Branch">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.branches.destroy', $b) }}" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="h-9 w-9 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 flex items-center justify-center text-xs transition shadow-sm" title="Delete Branch">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 bg-[#111A24] rounded-3xl border border-slate-800/80">
                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2 text-slate-600"></i>
                <p class="text-xs">No branches found matching your search.</p>
            </div>
        @endforelse
    </div>

    @if($branches->hasPages())
        <div class="p-4 bg-[#111A24] rounded-3xl border border-slate-800/80">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection

