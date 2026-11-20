@extends('layouts.internal')
@section('title', 'Press Plants & Branches')
@section('page-title', 'Press Plants & Branches')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold text-cyber-main font-display tracking-tight">Press Plants &amp; Branches</h1>
            <p class="text-sm text-cyber-muted mt-1">Press capacity, active crew, and finishing equipment across Davao plants.</p>
        </div>
        <div>
            <a href="{{ route('admin.branches.create') }}" class="px-4 py-2.5 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Add Plant Branch</span>
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-cyber-card p-4 rounded-xl border border-cyber">
        <form method="GET" action="{{ route('admin.branches.index') }}" data-table-target="branches-grid-card" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-7">
                <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Search Plants</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-sub">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by branch name, district, or street..." 
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-sub">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Operational Status</label>
                <select name="status" class="w-full px-3 py-2.5 text-sm bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:border-[#2F7BFF] focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.branches.index') }}" class="py-2.5 px-3 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted rounded-lg text-xs font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Branches Grid (Max 1 level of border: ruled hairline rows inside card) --}}
    <div id="branches-grid-card" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($branches as $b)
            @php
                $isActive = $b->status === 'active';
                $isMaint  = $b->status === 'maintenance';
                $statusDot = $isActive ? 'bg-[#10B981]' : ($isMaint ? 'bg-[#FFC531]' : 'bg-slate-400');
                $statusText = $isActive ? 'text-emerald-700 dark:text-[#10B981]' : ($isMaint ? 'text-amber-700 dark:text-[#FFC531]' : 'text-cyber-muted');
            @endphp
            <div class="bg-cyber-card rounded-xl border border-cyber hover:border-[#2F7BFF]/50 transition-colors p-5 sm:p-6 flex flex-col justify-between gap-4">
                <div class="space-y-4">
                    {{-- Plant Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="font-bold text-cyber-main font-display text-lg tracking-tight truncate">{{ $b->name }}</h3>
                            <p class="text-xs text-cyber-muted mt-0.5 truncate">{{ $b->location }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold shrink-0 {{ $statusText }}">
                            <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                            {{ ucfirst($b->status) }}
                        </span>
                    </div>

                    {{-- Ruled Telemetry Rows (No nested boxes) --}}
                    <div class="divide-y divide-cyber-sub border-t border-cyber text-xs tabular-nums">
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-cyber-muted">Assigned Crew</span>
                            <span class="font-mono font-semibold text-cyber-main">{{ $b->employees_count }} staff</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-cyber-muted">Press Fleet</span>
                            <span class="font-mono font-semibold text-cyber-main">{{ $b->machines_count }} units</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-cyber-muted">Daily Job Cap</span>
                            <span class="font-mono font-semibold text-[#2F7BFF] dark:text-[#60A5FA]">{{ $b->max_daily_jobs }}/day</span>
                        </div>
                        @if($b->address)
                            <div class="py-2.5 flex items-baseline justify-between gap-3">
                                <span class="text-cyber-muted shrink-0">Address</span>
                                <span class="text-cyber-main text-right truncate" title="{{ $b->address }}">{{ $b->address }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-2.5 pb-0">
                            <span class="text-cyber-muted">Contact</span>
                            <span class="font-mono font-medium text-cyber-main">{{ $b->phone ?? '0917-111-2233' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Footer --}}
                <div class="pt-4 border-t border-cyber flex items-center justify-between gap-2">
                    <a href="{{ route('admin.branches.show', $b) }}" class="px-3.5 py-2 rounded-lg bg-[#2F7BFF]/10 hover:bg-[#2F7BFF] text-[#2F7BFF] dark:text-[#60A5FA] hover:text-white text-xs font-semibold transition-colors inline-flex items-center gap-2">
                        <span>Inspect Plant &amp; Presses</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.branches.edit', $b) }}" aria-label="Edit Branch" class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] flex items-center justify-center text-xs transition-colors" title="Edit Branch">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.branches.destroy', $b) }}" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Delete Branch" class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-[#FFC531] hover:border-[#FFC531] flex items-center justify-center text-xs transition-colors cursor-pointer" title="Delete Branch">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-dashboard.empty-state title="No branches found" description="No press plants match your current filter criteria." icon="fa-solid fa-building-circle-xmark" />
            </div>
        @endforelse
    </div>

    @if($branches->hasPages())
        <div class="p-4 bg-cyber-card rounded-xl border border-cyber">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection
