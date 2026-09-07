@extends('layouts.internal')
@section('title', 'Production Planning & Scheduling')
@section('page-title', 'Production Planning & Multi-Branch Routing')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Header Banner --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display text-cyber-main">Production Planning &amp; Scheduling</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full md:w-auto justify-start md:justify-end">
                <a href="{{ route('manager.capacity.index') }}" 
                   class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 flex items-center gap-2 transition shadow-xs">
                    <i class="fa-solid fa-calculator text-cyan-600 dark:text-cyan-400"></i>
                    <span>Capacity Evaluation</span>
                </a>
                <a href="{{ route('manager.workload.index') }}" 
                   class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                    <span>Workload Monitor</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Functional, Clickable KPI Cards Strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <x-dashboard.kpi-card 
            title="ACTIVE PRODUCTION JOBS"
            :value="$totalActiveJobs"
            icon="fa-solid fa-list-check"
            accent="cyan"
            :link="route('manager.production-planning.index')"
            :active="!request('priority') && !request('unassigned')"
        />

        <x-dashboard.kpi-card 
            title="RUSH &amp; URGENT PRIORITY"
            :value="$urgentJobsCount"
            icon="fa-solid fa-bolt"
            accent="rose"
            :link="route('manager.production-planning.index', ['priority' => 'rush_urgent'])"
            :active="request('priority') === 'rush_urgent' || in_array(request('priority'), ['urgent', 'rush'])"
        />

        <x-dashboard.kpi-card 
            title="UNASSIGNED JOBS PENDING"
            :value="$unassignedJobs"
            icon="fa-solid fa-user-clock"
            accent="amber"
            :link="route('manager.production-planning.index', ['unassigned' => '1'])"
            :active="request('unassigned') === '1'"
        />
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-sm font-medium shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-lg shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter & Search Form --}}
    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm">
        <form method="GET" action="{{ route('manager.production-planning.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-4">
                <label class="block font-bold text-cyber-main mb-1.5 uppercase text-[11px] tracking-wider">Search Job # or Customer</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. JOB-8942 or John..."
                           class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-cyan-500 focus:outline-none text-xs">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-cyber-main mb-1.5 uppercase text-[11px] tracking-wider">Filter Branch</label>
                <select name="branch_id" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-cyan-500 focus:outline-none text-xs">
                    <option value="">All Active Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-cyber-main mb-1.5 uppercase text-[11px] tracking-wider">Filter Priority</label>
                <select name="priority" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0D1520] border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-cyan-500 focus:outline-none text-xs">
                    <option value="">All Priorities</option>
                    <option value="rush_urgent" {{ request('priority') === 'rush_urgent' ? 'selected' : '' }}>⚡ Rush &amp; Urgent Priority</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔴 Urgent / Express</option>
                    <option value="rush" {{ request('priority') === 'rush' ? 'selected' : '' }}>🟠 Rush Priority</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>🟢 Normal Priority</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl font-black text-xs uppercase tracking-wider transition shadow-sm cursor-pointer">
                    Filter Queue
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'priority', 'status', 'unassigned']))
                    <a href="{{ route('manager.production-planning.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition border border-slate-200 dark:border-slate-700 shrink-0" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Production Jobs Table with Separated Job # and Service Columns --}}
    <div class="bg-cyber-card rounded-3xl border border-cyber shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px] bg-slate-50/50 dark:bg-transparent">
                    <tr>
                        <th class="py-3.5 px-5">Job #</th>
                        <th class="py-3.5 px-4">Service &amp; Specs</th>
                        <th class="py-3.5 px-4">Client</th>
                        <th class="py-3.5 px-4">Branch</th>
                        <th class="py-3.5 px-4">Machine Assigned</th>
                        <th class="py-3.5 px-4">Technician</th>
                        <th class="py-3.5 px-4">Priority</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($jobs as $j)
                        <tr class="hover:bg-cyber-hover/50 transition">
                            {{-- Column 1: Job Number --}}
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="font-mono font-bold text-sm text-cyber-main block">#{{ $j->job_number }}</span>
                                <span class="text-[10px] text-cyber-muted block font-sans mt-0.5">
                                    {{ $j->created_at ? $j->created_at->diffForHumans() : 'Active' }}
                                </span>
                            </td>

                            {{-- Column 2: Service & Specifications (Separated) --}}
                            <td class="py-4 px-4">
                                <span class="font-bold text-cyber-main text-xs block">
                                    {{ $j->order->printRequest->service ?? 'Commercial Print' }}
                                </span>
                                <span class="text-[11px] text-cyber-muted block mt-0.5">
                                    {{ number_format($j->order->printRequest->quantity ?? 1) }} copies &bull; {{ $j->order->printRequest->paper_type ?? $j->order->printRequest->size ?? 'Standard' }}
                                </span>
                            </td>

                            {{-- Column 3: Client --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="font-semibold text-cyber-main text-xs block">{{ $j->order->user->name ?? 'Direct Customer' }}</span>
                                <span class="text-[10px] text-cyber-muted block">{{ $j->order->user->email ?? '' }}</span>
                            </td>

                            {{-- Column 4: Branch --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="font-bold text-xs text-cyber-main">
                                    {{ $j->branch->name ?? 'Unassigned' }}
                                </span>
                            </td>

                            {{-- Column 5: Machine Assigned --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($j->machine)
                                    <span class="font-semibold text-xs text-cyber-main">
                                        {{ $j->machine->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono text-amber-700 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20">
                                        No Machine
                                    </span>
                                @endif
                            </td>

                            {{-- Column 6: Technician Assignee --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($j->assignedTo)
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center font-bold text-[10px] border border-slate-200 dark:border-slate-700 shrink-0">
                                            {{ strtoupper(substr($j->assignedTo->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-cyber-main text-xs">{{ $j->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20 font-mono">
                                        Unassigned
                                    </span>
                                @endif
                            </td>

                            {{-- Column 7: Priority --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                @php
                                    $prioBadge = match($j->priority) {
                                        'urgent' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                                        'rush'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400 border-amber-500/30',
                                        default  => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase font-mono border {{ $prioBadge }}">
                                    {{ $j->priority }}
                                </span>
                            </td>

                            {{-- Column 8: Status --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                @php
                                    $statBadge = match($j->status) {
                                        'in_production', 'production' => 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-400 border-cyan-500/30',
                                        'completed'                   => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border-emerald-500/30',
                                        'delayed'                     => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border-rose-500/30',
                                        'quality_checking'            => 'bg-purple-500/15 text-purple-700 dark:text-purple-400 border-purple-500/30',
                                        default                       => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border-slate-500/30',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase font-mono border {{ $statBadge }}">
                                    {{ $j->status_label }}
                                </span>
                            </td>

                            {{-- Column 9: Action --}}
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('manager.production-planning.show', $j) }}" 
                                   class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition">
                                    <span>Plan / Schedule</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-cyber-muted">
                                <x-dashboard.empty-state 
                                    title="No Production Jobs in Queue"
                                    description="No shop-floor print jobs match your current filter criteria. Try resetting the filters or check back when new orders are confirmed."
                                    icon="fa-solid fa-calendar-xmark"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
            <div class="p-4 border-t border-cyber/60">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
