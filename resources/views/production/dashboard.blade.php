@extends('layouts.internal')
@section('title', 'Production Staff Dashboard')
@section('page-title', 'My Production Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase">Assigned Jobs</p>
                <h3 class="text-2xl font-black text-navy-900 font-display mt-0.5">{{ $assignedCount }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-gears font-bold"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase">In Production</p>
                <h3 class="text-2xl font-black text-navy-900 font-display mt-0.5">{{ $inProductionCount }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase">Completed Jobs</p>
                <h3 class="text-2xl font-black text-navy-900 font-display mt-0.5">{{ $completedCount }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase">Delayed Jobs</p>
                <h3 class="text-2xl font-black text-navy-900 font-display mt-0.5">{{ $delayedCount }}</h3>
            </div>
        </div>
    </div>

    {{-- My Assigned Jobs Table --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">My Assigned Production Tasks</h3>
            <a href="{{ route('production.jobs.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">View All &rarr;</a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Job #</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Machine</th>
                    <th class="px-6 py-3">Priority</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Update Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($myJobs as $job)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $job->job_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $job->order->printRequest->service ?? '—' }} ({{ number_format($job->order->printRequest->quantity ?? 0) }} pcs)</td>
                    <td class="px-6 py-4 text-slate-600">{{ $job->machine->name ?? 'Not Set' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->priority_badge_class }}">
                            {{ $job->priority }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->status_badge_class }}">
                            {{ $job->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('production.jobs.show', $job) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3 py-1.5 rounded-lg text-xs">Details</a>
                        <a href="{{ route('production.jobs.status-form', $job) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs">Update &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No jobs currently assigned to you.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
