@extends('layouts.internal')
@section('title', 'Branch & Capacity Dashboard')
@section('page-title', 'Branch & Capacity Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Branch &amp; Capacity Dashboard</h2>
            <p class="text-xs text-slate-400 mt-1">Multi-branch capacity planning, workload distribution, and production job routing.</p>
        </div>
        <a href="{{ route('manager.capacity.index') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-calculator text-xs"></i> Run Capacity Evaluation
        </a>
    </div>

    {{-- Production Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Active Jobs --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-4 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3 min-w-0">
                <i class="fa-solid fa-industry text-xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[10px] font-black text-cyan-400 uppercase tracking-wider leading-tight">ACTIVE JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-white font-display">{{ $totalActiveJobs }}</div>
            </div>
        </div>

        {{-- Due Today --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-4 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3 min-w-0">
                <i class="fa-solid fa-clock text-xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[10px] font-black text-emerald-400 uppercase tracking-wider leading-tight">DUE TODAY</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-white font-display">{{ $jobsDueToday }}</div>
            </div>
        </div>

        {{-- Due Tomorrow --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-4 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3 min-w-0">
                <i class="fa-solid fa-calendar-day text-xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[10px] font-black text-amber-400 uppercase tracking-wider leading-tight">DUE TOMORROW</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-white font-display">{{ $jobsDueTomorrow }}</div>
            </div>
        </div>

        {{-- Delayed Jobs --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-red-500 p-4 flex items-center justify-between shadow-lg hover:border-red-500/30 transition group">
            <div class="flex items-center gap-3 min-w-0">
                <i class="fa-solid fa-triangle-exclamation text-xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-red-500 transition-all"></i>
                <div class="text-[10px] font-black text-red-500 uppercase tracking-wider leading-tight">DELAYED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-red-400 font-display">{{ $delayedJobs }}</div>
            </div>
        </div>

        {{-- Rush / Urgent --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-orange-500 p-4 flex items-center justify-between shadow-lg hover:border-orange-500/30 transition group">
            <div class="flex items-center gap-3 min-w-0">
                <i class="fa-solid fa-bolt text-xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-orange-500 transition-all"></i>
                <div class="text-[10px] font-black text-orange-500 uppercase tracking-wider leading-tight">RUSH / URGENT</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-2xl font-black text-white font-display">{{ $rushJobs }}</div>
            </div>
        </div>
    </div>

    {{-- Branch Capacity & Workload Grid --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
            <div>
                <h3 class="font-black text-white text-base">Branch Capacity Utilization Monitor</h3>
                <p class="text-xs text-slate-400 mt-0.5">Real-time daily machine capacity and active job load across all branches.</p>
            </div>
            <a href="{{ route('manager.capacity.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                Details <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
            @foreach($branches as $b)
            <div class="p-4 rounded-2xl border border-slate-800 bg-[#0D1520] space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="font-black text-white text-sm">{{ $b->name }}</h4>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">{{ $b->location }}</span>
                </div>
                <div class="text-xs text-slate-400 space-y-1.5 font-medium">
                    <div class="flex justify-between"><span>Available Machines:</span><strong class="text-slate-200 font-mono">{{ $b->available_machines_count }} / {{ $b->machines->count() }}</strong></div>
                    <div class="flex justify-between"><span>Active Production Jobs:</span><strong class="text-cyan-400 font-mono">{{ $b->active_jobs_count }}</strong></div>
                    <div class="flex justify-between"><span>Daily Capacity Limit:</span><strong class="text-slate-200 font-mono">{{ $b->max_daily_jobs }} jobs/day</strong></div>
                </div>
                {{-- Workload Bar --}}
                <div class="space-y-1 pt-1">
                    <div class="flex justify-between text-[10px] font-black">
                        <span class="text-slate-400">Workload</span>
                        <span class="{{ $b->workload_percent >= 80 ? 'text-red-400' : ($b->workload_percent >= 50 ? 'text-amber-400' : 'text-emerald-400') }}">{{ $b->workload_percent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full {{ $b->workload_percent >= 80 ? 'bg-red-500' : ($b->workload_percent >= 50 ? 'bg-amber-400' : 'bg-emerald-400') }}"
                             style="width: {{ $b->workload_percent }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Active Production Planning Jobs --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
            <div>
                <h3 class="font-black text-white text-base">Active Production Jobs</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Real-time scheduling and assignments</p>
            </div>
            <a href="{{ route('manager.production-planning.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                    <tr>
                        <th class="px-5 py-3.5">Job No.</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Branch</th>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($recentJobs as $job)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5 font-bold text-white font-mono">{{ $job->job_number }}</td>
                        <td class="px-5 py-3.5 text-slate-300">{{ $job->order->user->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyan-400">{{ $job->branch->name ?? 'Unassigned' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $job->priority_badge_class }}">
                                {{ $job->priority }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $job->status_badge_class }}">
                                {{ $job->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('manager.production-planning.show', $job) }}"
                               class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition"
                               title="Plan & Assign Job Details">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-500">No active production jobs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

