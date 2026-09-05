@extends('layouts.internal')
@section('title', 'Production Planning & Scheduling')
@section('page-title', 'Production Planning & Multi-Branch Routing')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Production Planning &amp; Scheduling</h2>
                <p class="text-xs sm:text-sm text-slate-300">Schedule shop-floor jobs, assign production machines, prioritize rush print queues, and route across branches.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('manager.capacity.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-navy-800 hover:bg-navy-700 text-white font-bold text-xs border border-navy-700 transition">
                <i class="fa-solid fa-calculator"></i> Capacity Evaluation
            </a>
            <a href="{{ route('manager.workload.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 transition">
                <i class="fa-solid fa-chart-line"></i> Workload Monitor
            </a>
        </div>
    </div>

    {{-- Quick Metric Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-blue-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-list-check text-2xl text-blue-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-blue-400 uppercase tracking-wider leading-tight max-w-[120px]">ACTIVE PRODUCTION JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $totalActiveJobs }}</div>
            </div>
        </div>

        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-rose-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-bolt text-2xl text-rose-500 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-rose-500 uppercase tracking-wider leading-tight max-w-[120px]">RUSH &amp; URGENT PRIORITY</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $urgentJobsCount }}</div>
            </div>
        </div>

        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-user-clock text-2xl text-amber-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[120px]">UNASSIGNED JOBS PENDING</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $unassignedJobs }}</div>
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter & Search Form --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('manager.production-planning.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-4">
                <label class="block font-bold text-slate-600 mb-1">Search Job # or Customer</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. JOB-8942 or John..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Filter Branch</label>
                <select name="branch_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">All Active Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Filter Priority</label>
                <select name="priority" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔴 Urgent / Express</option>
                    <option value="rush" {{ request('priority') === 'rush' ? 'selected' : '' }}>🟠 Rush Priority</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>🟢 Normal Priority</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-navy-800 text-white rounded-xl font-bold transition">
                    Filter Queue
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'priority', 'status']))
                    <a href="{{ route('manager.production-planning.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Production Jobs Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Job # / Service</th>
                        <th class="py-3.5 px-4">Client</th>
                        <th class="py-3.5 px-4">Branch</th>
                        <th class="py-3.5 px-4">Machine Assigned</th>
                        <th class="py-3.5 px-4">Technician</th>
                        <th class="py-3.5 px-4">Priority</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jobs as $j)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5">
                                <div class="font-bold text-navy-900 font-display text-sm">#{{ $j->job_number }}</div>
                                <div class="text-slate-400">{{ $j->order->printRequest->service ?? 'Print Job' }} &bull; {{ $j->order->printRequest->quantity ?? 1 }} copies</div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800">
                                {{ $j->order->user->name ?? 'Customer' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1 font-bold text-navy-800">
                                    <i class="fa-solid fa-shop text-blue-500"></i> {{ $j->branch->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($j->machine)
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-700">
                                        <i class="fa-solid fa-industry text-purple-500"></i> {{ $j->machine->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">No machine</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($j->assignedTo)
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($j->assignedTo->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $j->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $j->priority_badge_class }}">
                                    {{ $j->priority }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $j->status_badge_class }}">
                                    {{ $j->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('manager.production-planning.show', $j) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs shadow-sm transition">
                                    <i class="fa-solid fa-sliders"></i> Plan / Schedule
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">No production jobs found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
