@extends('layouts.internal')
@section('title', 'Branch Network Performance')
@section('page-title', 'Branch Network Performance')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: BRANCH NETWORK PERFORMANCE (UNBOXED & CLEAN) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-cyber-main">Branch Network Performance</h1>
            <p class="text-xs text-cyber-muted mt-1">Real-time status, press lines, and telemetry across all print facilities.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fa-solid fa-print text-blue-500 dark:text-blue-400 text-xs"></i> Print Report
            </button>
            <a href="{{ route('management.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-chart-line text-xs text-indigo-500 dark:text-indigo-400"></i> Executive Dashboard
            </a>
            @if(auth()->user()->isAdmin() && Route::has('admin.branches.create'))
                <a href="{{ route('admin.branches.create') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Add Branch
                </a>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 NETWORK SUMMARY KPI CARDS (RIGHT-ALIGNED NUMBERS) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="NETWORK ACTIVE RUNS"
            :value="$totalActiveJobs"
            icon="fa-solid fa-gears"
            accent="cyan"
            :link="route('management.production.index')"
        />

        <x-dashboard.kpi-card 
            title="FULFILLED RUNS"
            :value="$totalCompletedJobs"
            icon="fa-solid fa-circle-check"
            accent="emerald"
            :link="route('management.orders.index', ['status' => 'completed'])"
        />

        <x-dashboard.kpi-card 
            title="NETWORK DELAYS"
            :value="$totalDelayedJobs"
            icon="fa-solid fa-triangle-exclamation"
            accent="rose"
            :link="route('management.orders.index', ['status' => 'delayed'])"
        />

        <x-dashboard.kpi-card 
            title="FLEET PRESS CAPACITY"
            :value="$totalMachines . ' units'"
            icon="fa-solid fa-print"
            accent="indigo"
            :link="route('management.dashboard')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MULTI-BRANCH TELEMETRY CARDS GRID --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($branches as $b)
            @php
                $pct = $b->workload_percent ?? 0;
                $availMachines = $b->available_machines_count ?? ($b->machines ? $b->machines->where('status', 'available')->count() : 0);
                $totalMachines = $b->machines_count ?? 0;
            @endphp
            <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm hover:border-blue-500/30 transition-all duration-200 flex flex-col justify-between space-y-4 group">
                <div class="space-y-3.5">
                    {{-- Branch Title & Location --}}
                    <div class="flex items-start justify-between gap-2 border-b border-cyber/60 pb-3">
                        <div class="min-w-0">
                            <h3 class="font-black text-cyber-main text-base sm:text-lg font-display truncate group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">
                                {{ $b->name }}
                            </h3>
                            <span class="text-xs text-cyber-muted mt-0.5 flex items-center gap-1.5 truncate">
                                <i class="fa-solid fa-location-dot text-blue-500 text-[11px]"></i>
                                <span>{{ $b->location ?? 'Hub' }}</span>
                                <span class="text-slate-300 dark:text-cyber-main">&middot;</span>
                                <strong class="text-cyber-main font-semibold truncate">{{ $b->manager_name ?? 'Branch Manager' }}</strong>
                            </span>
                        </div>
                    </div>

                    {{-- Fleet & Quota Specs Box --}}
                    <div class="space-y-1.5 text-xs text-cyber-muted font-medium bg-cyber-sub/50 p-3 rounded-2xl border border-cyber/50">
                        <div class="flex justify-between items-center">
                            <span>Press Fleet:</span>
                            <span class="font-sans tabular-nums font-bold text-cyber-main">
                                <strong class="text-blue-600 dark:text-blue-400 font-extrabold">{{ $availMachines }}</strong> / {{ $totalMachines }} operational
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Daily Quota:</span>
                            <span class="font-sans tabular-nums font-bold text-cyber-muted">{{ $b->max_daily_jobs ?? 25 }} jobs/day</span>
                        </div>
                    </div>

                    {{-- 3 High-Contrast Metric Chips --}}
                    <div class="grid grid-cols-3 gap-2 text-center text-xs pt-1">
                        <div class="bg-blue-500/10 dark:bg-blue-500/15 p-2.5 rounded-2xl border border-blue-500/25">
                            <span class="text-blue-700 dark:text-blue-300 block text-[11px] font-bold font-sans">Active</span>
                            <strong class="text-blue-800 dark:text-blue-200 text-sm font-black font-sans tabular-nums mt-0.5 block">{{ $b->active_jobs }}</strong>
                        </div>
                        <div class="bg-emerald-500/10 dark:bg-emerald-500/15 p-2.5 rounded-2xl border border-emerald-500/25">
                            <span class="text-emerald-700 dark:text-emerald-300 block text-[11px] font-bold font-sans">Done</span>
                            <strong class="text-emerald-800 dark:text-emerald-200 text-sm font-black font-sans tabular-nums mt-0.5 block">{{ $b->completed_jobs }}</strong>
                        </div>
                        <div class="bg-rose-500/10 dark:bg-rose-500/15 p-2.5 rounded-2xl border border-rose-500/25">
                            <span class="text-rose-700 dark:text-rose-300 block text-[11px] font-bold font-sans">Delayed</span>
                            <strong class="text-rose-800 dark:text-rose-200 text-sm font-black font-sans tabular-nums mt-0.5 block">{{ $b->delayed_jobs }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Capacity Load Progress Bar --}}
                <div class="space-y-3 pt-2">
                    <div class="space-y-1.5 border-t border-cyber/60 pt-3">
                        <div class="flex justify-between items-center text-[11px] font-semibold">
                            <span class="text-cyber-muted">Capacity Load</span>
                            <span class="{{ $pct >= 80 ? 'text-rose-600 dark:text-rose-400' : ($pct >= 50 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }} font-sans tabular-nums text-xs">
                                {{ $pct }}%
                            </span>
                        </div>
                        <div class="w-full h-2 bg-cyber-base rounded-full overflow-hidden border border-cyber/60 p-0.5">
                            <div class="h-full rounded-full transition-all duration-500 ease-out {{ $pct >= 80 ? 'bg-rose-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }}" 
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    {{-- Bottom Action Link --}}
                    <div class="pt-2 border-t border-cyber/60 flex items-center justify-between text-xs">
                        <a href="{{ route('management.orders.index', ['branch_id' => $b->id]) }}" 
                           class="text-sky-600 dark:text-blue-400 hover:text-sky-700 dark:hover:text-blue-300 font-bold inline-flex items-center gap-1.5 text-xs transition">
                            <span>View Branch Orders</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-cyber-muted text-xs bg-cyber-card border border-cyber rounded-2xl">
                <i class="fa-solid fa-network-wired text-cyber-sub text-3xl mb-2 block"></i>
                No active branches configured in the network.
            </div>
        @endforelse
    </div>

</div>
@endsection
