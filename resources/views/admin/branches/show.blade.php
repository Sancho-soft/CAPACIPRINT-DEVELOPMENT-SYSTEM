@extends('layouts.internal')
@section('title', 'Branch Status: ' . $branch->name)
@section('page-title', 'Branch Status & Overview')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.branches.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main inline-flex items-center gap-2 text-xs font-semibold transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Press Plants</span>
        </a>
        <a href="{{ route('admin.branches.edit', $branch) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition">
            <i class="fa-solid fa-gear text-xs"></i>
            <span>Configure Plant</span>
        </a>
    </div>

    {{-- Top Summary Card (1 level of border + hairline metric split) --}}
    <div class="bg-cyber-card rounded-xl border border-cyber overflow-hidden">
        <div class="p-6 sm:p-7 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-bold font-display text-cyber-main tracking-tight">{{ $branch->name }}</h1>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $branch->status === 'active' ? 'text-emerald-700 dark:text-[#10B981]' : 'text-amber-700 dark:text-[#FFC531]' }}">
                        <span class="w-2 h-2 rounded-full {{ $branch->status === 'active' ? 'bg-[#10B981]' : 'bg-[#FFC531]' }}"></span>
                        {{ ucfirst($branch->status) }}
                    </span>
                </div>
                <p class="text-sm text-cyber-muted mt-1.5">
                    {{ $branch->location }} &middot; {{ $branch->address ?? 'No physical address configured' }}
                </p>
            </div>

            <div class="flex items-center divide-x divide-cyber border border-cyber rounded-lg bg-cyber-sub tabular-nums shrink-0">
                <div class="px-5 py-3 text-center">
                    <div class="text-xl font-bold font-mono text-[#2F7BFF] dark:text-[#60A5FA]">{{ $branch->max_daily_jobs }}</div>
                    <div class="text-xs text-cyber-muted font-medium mt-0.5">Daily Cap</div>
                </div>
                <div class="px-5 py-3 text-center">
                    <div class="text-xl font-bold font-mono text-cyber-main">{{ $branch->workload_percent }}%</div>
                    <div class="text-xs text-cyber-muted font-medium mt-0.5">Current Load</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Assigned Staff --}}
        <div class="bg-cyber-card p-5 sm:p-6 rounded-xl border border-cyber space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main font-display text-sm">Assigned Staff ({{ $branch->employees->count() }})</h3>
                <a href="{{ route('admin.employees.create') }}?branch_id={{ $branch->id }}" class="text-xs text-[#2F7BFF] dark:text-[#60A5FA] font-semibold hover:underline">+ Assign</a>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->employees as $emp)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-8 w-8 rounded-lg bg-[#2F7BFF]/12 text-[#2F7BFF] dark:text-[#60A5FA] flex items-center justify-center font-mono font-bold text-xs shrink-0">
                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-xs text-cyber-main truncate">{{ $emp->name }}</div>
                                <div class="text-xs text-cyber-muted truncate">{{ $emp->position }}</div>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $emp->availability_badge_class }} shrink-0">
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
        <div class="bg-cyber-card p-5 sm:p-6 rounded-xl border border-cyber space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main font-display text-sm">Press Machines ({{ $branch->machines->count() }})</h3>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->machines as $m)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-xs text-cyber-main truncate">{{ $m->name }}</div>
                            <div class="text-xs text-cyber-muted truncate">{{ $m->type ?? 'Commercial Press' }}</div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold shrink-0 {{ $m->status === 'available' ? 'text-emerald-700 dark:text-[#10B981]' : 'text-amber-700 dark:text-[#FFC531]' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $m->status === 'available' ? 'bg-[#10B981]' : 'bg-[#FFC531]' }}"></span>
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
        <div class="bg-cyber-card p-5 sm:p-6 rounded-xl border border-cyber space-y-4">
            <div class="flex items-center justify-between border-b border-cyber pb-3">
                <h3 class="font-bold text-cyber-main font-display text-sm">Stock Summary ({{ $branch->inventory->count() }})</h3>
            </div>

            <div class="divide-y divide-cyber-sub">
                @forelse($branch->inventory as $inv)
                    <div class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-xs text-cyber-main truncate">{{ $inv->material->name ?? 'Material' }}</div>
                            <div class="text-xs text-cyber-muted font-mono">Min: {{ number_format($inv->minimum_stock, 0) }} {{ $inv->material->unit ?? 'units' }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-xs font-mono tabular-nums {{ $inv->quantity <= $inv->minimum_stock ? 'text-amber-700 dark:text-[#FFC531]' : 'text-emerald-700 dark:text-[#10B981]' }}">
                                {{ number_format($inv->quantity, 0) }}
                            </div>
                            <div class="text-xs text-cyber-muted">{{ $inv->material->unit ?? 'units' }}</div>
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
