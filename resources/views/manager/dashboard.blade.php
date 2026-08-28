@extends('layouts.internal')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">Dashboard Overview</h2>
        <p class="text-sm text-slate-500 mt-1">Multi-branch capacity planning, workload distribution, and production job routing.</p>
    </div>

    {{-- Production Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">Active Jobs</p>
                </div>
            </div>
            <h3 class="text-xl font-black text-navy-900 font-display shrink-0 ml-2">{{ $totalActiveJobs }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clock font-bold"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">Due Today</p>
                </div>
            </div>
            <h3 class="text-xl font-black text-navy-900 font-display shrink-0 ml-2">{{ $jobsDueToday }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">Due Tomorrow</p>
                </div>
            </div>
            <h3 class="text-xl font-black text-navy-900 font-display shrink-0 ml-2">{{ $jobsDueTomorrow }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">Delayed Jobs</p>
                </div>
            </div>
            <h3 class="text-xl font-black text-navy-900 font-display shrink-0 ml-2">{{ $delayedJobs }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3 min-w-0">
                <div class="h-10 w-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide truncate">Rush / Urgent</p>
                </div>
            </div>
            <h3 class="text-xl font-black text-navy-900 font-display shrink-0 ml-2">{{ $rushJobs }}</h3>
        </div>
    </div>

    {{-- Branch Capacity & Workload Grid --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-navy-900">Branch Capacity Utilization Monitor</h3>
                <p class="text-xs text-slate-500">Real-time daily machine capacity and active job load across all branches.</p>
            </div>
            <a href="{{ route('manager.capacity.index') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-calculator mr-1"></i> Run Capacity Evaluation
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            @foreach($branches as $b)
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-navy-900 text-sm">{{ $b->name }}</h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $b->location }}</span>
                </div>
                <div class="text-xs text-slate-600 space-y-1">
                    <div class="flex justify-between"><span>Available Machines:</span><strong class="text-slate-900">{{ $b->available_machines_count }} / {{ $b->machines->count() }}</strong></div>
                    <div class="flex justify-between"><span>Active Production Jobs:</span><strong class="text-slate-900">{{ $b->active_jobs_count }}</strong></div>
                    <div class="flex justify-between"><span>Daily Capacity Limit:</span><strong class="text-slate-900">{{ $b->max_daily_jobs }} jobs/day</strong></div>
                </div>
                {{-- Workload Bar --}}
                <div class="space-y-1">
                    <div class="flex justify-between text-[10px] font-bold">
                        <span class="text-slate-400">Workload</span>
                        <span class="{{ $b->workload_percent >= 80 ? 'text-red-600' : ($b->workload_percent >= 50 ? 'text-amber-600' : 'text-emerald-600') }}">{{ $b->workload_percent }}%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full {{ $b->workload_percent >= 80 ? 'bg-red-500' : ($b->workload_percent >= 50 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                             style="width: {{ $b->workload_percent }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Active Production Planning Jobs --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Active Production Jobs</h3>
            <a href="{{ route('manager.production-planning.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">View All &rarr;</a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Job No.</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Branch</th>
                    <th class="px-6 py-3">Priority</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentJobs as $job)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3.5 font-bold text-navy-900">{{ $job->job_number }}</td>
                    <td class="px-6 py-3.5 text-slate-700">{{ $job->order->user->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 font-semibold text-brand-600">{{ $job->branch->name ?? 'Unassigned' }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->priority_badge_class }}">
                            {{ $job->priority }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->status_badge_class }}">
                            {{ $job->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        <a href="{{ route('manager.production-planning.show', $job) }}"
                           class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                           title="Plan & Assign Job Details">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No active production jobs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
