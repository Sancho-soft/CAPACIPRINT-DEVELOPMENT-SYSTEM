@extends('layouts.internal')
@section('title', 'Branch Management')
@section('page-title', 'Branch Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-sans font-semibold text-blue-600 dark:text-blue-400 block mb-1">Network Infrastructure</span>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Branch Management</h2>
            <p class="text-xs text-cyber-muted mt-1">Real-time operational node capacity, staff allocation, and machine telemetry.</p>
        </div>
        <div>
            <a href="{{ route('admin.branches.create') }}" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Create Branch
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-cyber-card p-4 sm:p-5 rounded-2xl shadow-sm border border-cyber">
        <form method="GET" action="{{ route('admin.branches.index') }}" data-table-target="branches-grid-card" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-7">
                <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Search Branches</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-sub">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, location, or manager..." 
                           class="w-full pl-10 pr-4 py-2.5 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none placeholder-cyber-sub">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Status Filter</label>
                <select name="status" class="w-full px-3 py-2.5 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.branches.index') }}" class="py-2.5 px-3 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted rounded-xl text-xs font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Branches Grid --}}
    <div id="branches-grid-card" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($branches as $b)
            <div class="bg-cyber-card rounded-2xl shadow-sm border border-cyber hover:border-blue-600/40 dark:hover:border-blue-500/40 transition-colors p-5 sm:p-6 flex flex-col justify-between space-y-4 group">
                <div class="space-y-4">
                    {{-- Title Row & Status Badge --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-blue-600/10 dark:bg-blue-500/15 border border-blue-600/25 dark:border-blue-500/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-cyber-main font-display text-base tracking-tight">{{ $b->name }}</h3>
                                <p class="text-[11px] text-cyber-muted font-sans tabular-nums flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-blue-600 dark:text-blue-400 text-[11px]"></i> {{ $b->location }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-sans font-semibold border {{ $b->status === 'active' ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-500/25' : 'bg-cyber-sub text-cyber-muted border-cyber' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $b->status === 'active' ? 'bg-emerald-500' : 'bg-cyber-sub' }}"></span>
                            {{ ucfirst($b->status) }}
                        </span>
                    </div>

                    {{-- Address Badge --}}
                    @if($b->address)
                        <div class="text-xs text-cyber-muted bg-cyber-sub px-3.5 py-2.5 rounded-xl border border-cyber-sub flex items-center gap-2" title="{{ $b->address }}">
                            <i class="fa-solid fa-map-pin text-blue-600 dark:text-blue-400 text-xs shrink-0"></i>
                            <span class="truncate" title="{{ $b->address }}">{{ $b->address }}</span>
                        </div>
                    @endif

                    {{-- Metrics Grid (Staff, Machines, Max Daily) --}}
                    <div class="grid grid-cols-3 gap-2.5 pt-1 text-center tabular-nums">
                        <div class="bg-cyber-sub p-3 rounded-xl border border-cyber-sub space-y-0.5">
                            <div class="text-lg font-bold text-cyber-main font-sans tabular-nums">{{ $b->employees_count }}</div>
                            <div class="text-[11px] text-cyber-muted font-sans font-semibold">Staff</div>
                        </div>
                        <div class="bg-cyber-sub p-3 rounded-xl border border-cyber-sub space-y-0.5">
                            <div class="text-lg font-bold text-cyber-main font-sans tabular-nums">{{ $b->machines_count }}</div>
                            <div class="text-[11px] text-cyber-muted font-sans font-semibold">Machines</div>
                        </div>
                        <div class="bg-cyber-sub p-3 rounded-xl border border-cyber-sub space-y-0.5">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400 font-sans tabular-nums">{{ $b->max_daily_jobs }}</div>
                            <div class="text-[11px] text-cyber-muted font-sans font-semibold">Max Daily</div>
                        </div>
                    </div>

                    {{-- Contact Phone Row --}}
                    <div class="text-xs text-cyber-muted pt-2 border-t border-cyber-sub">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-[11px] text-cyber-sub"></i> Contact Phone:
                            </span>
                            <span class="font-semibold text-cyber-main font-sans tabular-nums">{{ $b->phone ?? '0917-111-2233' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Footer --}}
                <div class="pt-4 border-t border-cyber-sub flex items-center justify-between gap-2">
                    <a href="{{ route('admin.branches.show', $b) }}" class="px-3.5 py-2 rounded-xl bg-blue-600/10 dark:bg-blue-500/15 hover:bg-blue-600 border border-blue-600/25 dark:border-blue-500/30 text-blue-700 dark:text-blue-400 hover:text-white text-xs font-semibold transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-eye text-xs"></i> View Node Status
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.branches.edit', $b) }}" aria-label="Edit Branch" class="h-8 w-8 rounded-xl bg-cyber-sub hover:border-blue-600/40 border border-cyber text-cyber-muted hover:text-blue-600 dark:hover:text-blue-400 flex items-center justify-center text-xs transition-colors" title="Edit Branch">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.branches.destroy', $b) }}" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Delete Branch" class="h-8 w-8 rounded-xl bg-rose-500/10 hover:bg-rose-600 border border-rose-500/25 text-rose-600 dark:text-rose-400 hover:text-white flex items-center justify-center text-xs transition-colors cursor-pointer" title="Delete Branch">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-dashboard.empty-state title="No branches found" description="No branches match your current filter criteria." icon="fa-solid fa-building-circle-xmark" />
            </div>
        @endforelse
    </div>

    @if($branches->hasPages())
        <div class="p-4 bg-cyber-card rounded-2xl shadow-sm border border-cyber">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection
