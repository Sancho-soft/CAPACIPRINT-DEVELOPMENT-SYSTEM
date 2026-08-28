@extends('layouts.internal')
@section('title', 'Branch Management')
@section('page-title', 'Branch Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-cyber-main font-display">Branch Management</h2>
            <p class="text-xs text-cyber-muted mt-1">Real-time branch status, machine capacity limits, staff allocation, and operational nodes.</p>
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
    <div class="bg-cyber-card p-5 rounded-3xl border border-cyber shadow-xl">
        <form method="GET" action="{{ route('admin.branches.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-7">
                <label class="block text-xs font-bold text-cyber-muted mb-1 uppercase tracking-wider">Search Branches</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, location, or manager..." 
                           class="w-full pl-10 pr-4 py-2 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-cyber-muted mb-1 uppercase tracking-wider">Status Filter</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:ring-2 focus:ring-cyan-500 focus:outline-none">
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
                    <a href="{{ route('admin.branches.index') }}" class="py-2 px-3 bg-cyber-sub hover:bg-slate-800 border border-cyber text-cyber-muted rounded-xl text-xs font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Branches Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($branches as $b)
            <div class="bg-cyber-card rounded-3xl border border-cyber shadow-xl hover:border-cyan-500/30 transition p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg font-bold group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-shop"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-cyber-main font-display text-base">{{ $b->name }}</h3>
                                <p class="text-xs text-cyber-muted"><i class="fa-solid fa-location-dot mr-1 text-cyan-500"></i> {{ $b->location }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $b->status === 'active' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </div>

                    @if($b->address)
                        <p class="text-xs text-cyber-muted bg-cyber-sub p-3 rounded-2xl border border-cyber font-medium">{{ $b->address }}</p>
                    @endif

                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-cyber text-center">
                        <div class="bg-cyber-sub p-2.5 rounded-2xl border border-cyber">
                            <div class="text-base font-black text-cyber-main font-display">{{ $b->employees_count }}</div>
                            <div class="text-[10px] text-cyber-muted font-bold uppercase tracking-wider">Staff</div>
                        </div>
                        <div class="bg-cyber-sub p-2.5 rounded-2xl border border-cyber">
                            <div class="text-base font-black text-cyber-main font-display">{{ $b->machines_count }}</div>
                            <div class="text-[10px] text-cyber-muted font-bold uppercase tracking-wider">Machines</div>
                        </div>
                        <div class="bg-cyber-sub p-2.5 rounded-2xl border border-cyber">
                            <div class="text-base font-black text-cyan-400 font-display">{{ $b->max_daily_jobs }}</div>
                            <div class="text-[10px] text-cyber-muted font-bold uppercase tracking-wider">Max Daily</div>
                        </div>
                    </div>

                    <div class="text-xs text-cyber-muted space-y-1.5 pt-1 font-medium">
                        <div class="flex items-center justify-between">
                            <span>Manager:</span>
                            <span class="font-bold text-cyber-main">{{ $b->manager_name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Contact Phone:</span>
                            <span class="font-bold text-cyber-main font-mono">{{ $b->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-cyber flex items-center justify-between gap-2">
                    <a href="{{ route('admin.branches.show', $b) }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 transition flex items-center gap-1.5">
                        <i class="fa-solid fa-eye text-xs"></i> View Node Status
                    </a>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.branches.edit', $b) }}" class="h-8 w-8 rounded-xl bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted hover:text-cyber-main flex items-center justify-center text-xs transition" title="Edit Branch">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.branches.destroy', $b) }}" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="h-8 w-8 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 flex items-center justify-center text-xs transition" title="Delete Branch">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-cyber-muted bg-cyber-card rounded-3xl border border-cyber">
                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2 text-slate-600"></i>
                <p class="text-xs">No branches found matching your search.</p>
            </div>
        @endforelse
    </div>

    @if($branches->hasPages())
        <div class="p-4 bg-cyber-card rounded-3xl border border-cyber">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection

