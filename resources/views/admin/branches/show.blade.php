@extends('layouts.internal')
@section('title', 'Branch Status: ' . $branch->name)
@section('page-title', 'Branch Status & Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-cyber-muted hover:text-cyan-400 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Branch List
        </a>
        <a href="{{ route('admin.branches.edit', $branch) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-main font-bold text-xs transition">
            <i class="fa-solid fa-gear"></i> Configure Branch
        </a>
    </div>

    {{-- Top Summary Card --}}
    <div class="bg-cyber-card p-6 sm:p-8 rounded-3xl border border-cyber shadow-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-3xl shadow-[0_0_20px_rgba(6,182,212,0.25)] shrink-0">
                <i class="fa-solid fa-shop"></i>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black font-display text-cyber-main">{{ $branch->name }}</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $branch->status === 'active' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700' }}">
                        {{ ucfirst($branch->status) }}
                    </span>
                </div>
                <p class="text-xs text-cyber-muted mt-1">
                    <i class="fa-solid fa-location-dot mr-1 text-cyan-500"></i> {{ $branch->location }} &bull; {{ $branch->address ?? 'No physical address configured' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-6 bg-cyber-sub p-4 rounded-2xl border border-cyber">
            <div class="text-center">
                <div class="text-2xl font-black text-cyan-400 font-display">{{ $branch->max_daily_jobs }}</div>
                <div class="text-[10px] text-cyber-muted uppercase tracking-wider font-bold">Max Daily Cap</div>
            </div>
            <div class="h-8 w-px bg-cyber"></div>
            <div class="text-center">
                <div class="text-2xl font-black text-cyber-main font-display">{{ $branch->workload_percent }}%</div>
                <div class="text-[10px] text-cyber-muted uppercase tracking-wider font-bold">Current Load</div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Assigned Staff --}}
        <div class="bg-cyber-card p-6 rounded-3xl border border-cyber shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-black text-cyber-main font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user-group text-cyan-400"></i> Assigned Staff ({{ $branch->employees->count() }})
                </h3>
                <a href="{{ route('admin.employees.create') }}?branch_id={{ $branch->id }}" class="text-xs text-cyan-400 font-bold hover:underline">+ Assign</a>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->employees as $emp)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-xl bg-cyber-sub border border-cyber text-cyber-main flex items-center justify-center font-black text-xs">
                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-xs text-cyber-main">{{ $emp->name }}</div>
                                <div class="text-[11px] text-cyber-muted">{{ $emp->position }}</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $emp->availability_badge_class }}">
                            {{ $emp->availability_label }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-cyber-muted text-xs">
                        No staff assigned to this branch yet.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Machines & Equipment --}}
        <div class="bg-cyber-card p-6 rounded-3xl border border-cyber shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-black text-cyber-main font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-industry text-purple-400"></i> Machines ({{ $branch->machines->count() }})
                </h3>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->machines as $m)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-xs text-cyber-main">{{ $m->name }}</div>
                            <div class="text-[11px] text-cyber-muted">{{ $m->type ?? 'General Printer' }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $m->status === 'available' ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/15 text-amber-400 border-amber-500/30' }}">
                            {{ ucfirst($m->status) }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-cyber-muted text-xs">
                        No machines registered for this branch.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Branch Inventory Stock --}}
        <div class="bg-cyber-card p-6 rounded-3xl border border-cyber shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-black text-cyber-main font-display text-sm flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-400"></i> Stock Summary ({{ $branch->inventory->count() }})
                </h3>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->inventory as $inv)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-xs text-cyber-main">{{ $inv->material->name ?? 'Material' }}</div>
                            <div class="text-[11px] text-cyber-muted">Min: {{ number_format($inv->minimum_stock, 0) }} {{ $inv->material->unit ?? 'units' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-xs font-mono {{ $inv->quantity <= $inv->minimum_stock ? 'text-red-400' : 'text-emerald-400' }}">
                                {{ number_format($inv->quantity, 0) }}
                            </div>
                            <div class="text-[10px] text-cyber-muted">{{ $inv->material->unit ?? 'units' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-cyber-muted text-xs">
                        No inventory allocated to this branch.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

