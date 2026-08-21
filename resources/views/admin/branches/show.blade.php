@extends('layouts.internal')
@section('title', 'Branch Status: ' . $branch->name)
@section('page-title', 'Branch Status & Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Branch List
        </a>
        <a href="{{ route('admin.branches.edit', $branch) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
            <i class="fa-solid fa-gear"></i> Configure Branch
        </a>
    </div>

    {{-- Top Summary Card --}}
    <div class="bg-navy-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-blue-500 text-white flex items-center justify-center text-3xl shadow-lg shrink-0">
                <i class="fa-solid fa-shop"></i>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold font-display">{{ $branch->name }}</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $branch->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-500/20 text-slate-300' }}">
                        {{ ucfirst($branch->status) }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-300 mt-1">
                    <i class="fa-solid fa-location-dot mr-1"></i> {{ $branch->location }} &bull; {{ $branch->address ?? 'No physical address configured' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-6 bg-navy-800/80 p-4 rounded-2xl border border-navy-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-400 font-display">{{ $branch->max_daily_jobs }}</div>
                <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Max Daily Cap</div>
            </div>
            <div class="h-8 w-px bg-navy-700"></div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white font-display">{{ $branch->workload_percent }}%</div>
                <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Current Load</div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Assigned Staff --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-navy-900 font-display flex items-center gap-2">
                    <i class="fa-solid fa-user-group text-blue-500"></i> Assigned Staff ({{ $branch->employees->count() }})
                </h3>
                <a href="{{ route('admin.employees.create') }}?branch_id={{ $branch->id }}" class="text-xs text-blue-600 font-semibold hover:underline">+ Assign</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($branch->employees as $emp)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-xs text-navy-900">{{ $emp->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $emp->position }}</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $emp->availability_badge_class }}">
                            {{ $emp->availability_label }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        No staff assigned to this branch yet.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Machines & Equipment --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-navy-900 font-display flex items-center gap-2">
                    <i class="fa-solid fa-industry text-purple-500"></i> Machines ({{ $branch->machines->count() }})
                </h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($branch->machines as $m)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-xs text-navy-900">{{ $m->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $m->type ?? 'General Printer' }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $m->status === 'available' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ ucfirst($m->status) }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        No machines registered for this branch.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Branch Inventory Stock --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-navy-900 font-display flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-emerald-500"></i> Stock Summary ({{ $branch->inventory->count() }})
                </h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($branch->inventory as $inv)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-xs text-navy-900">{{ $inv->material->name ?? 'Material' }}</div>
                            <div class="text-[11px] text-slate-400">Reorder at {{ $inv->reorder_level }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-xs {{ $inv->current_stock <= $inv->reorder_level ? 'text-rose-600' : 'text-navy-900' }}">
                                {{ $inv->current_stock }}
                            </div>
                            <div class="text-[10px] text-slate-400">{{ $inv->material->unit ?? 'units' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        No inventory allocated to this branch.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
