@extends('layouts.internal')
@section('title', 'Production Operator Dashboard')
@section('page-title', 'My Production Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Operator Welcome & Shift Target Banner --}}
    <div class="bg-gradient-to-r from-navy-900 via-navy-800 to-brand-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-brand-500/20 border border-brand-400/30 flex items-center justify-center text-white font-bold font-display text-xl shrink-0 shadow-inner">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold font-display text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 tracking-wider">Shift Active</span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1">Ready for production. You have <strong class="text-brand-300">{{ $inProductionCount }} active job(s)</strong> currently running on your assigned line.</p>
                </div>
            </div>

            {{-- Quick Shift Progress Widget --}}
            <div class="bg-navy-950/60 border border-white/10 rounded-2xl p-4 min-w-[260px] backdrop-blur-md">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-slate-300 font-semibold">Today's Shift Completion Target</span>
                    <span class="text-brand-400 font-bold font-mono">{{ $completedCount }} / {{ max(1, $assignedCount + $completedCount) }} Jobs</span>
                </div>
                @php 
                    $totalShiftJobs = max(1, $assignedCount + $completedCount);
                    $pct = min(100, round(($completedCount / $totalShiftJobs) * 100));
                @endphp
                <div class="w-full bg-navy-800 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-brand-400 to-emerald-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1.5 font-medium">
                    <span>Target Efficiency Rate</span>
                    <span class="text-emerald-400 font-bold">{{ $pct }}% Completed</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Assigned Jobs</p>
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-list-check"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $assignedCount }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">Pending setup or execution</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">In Production</p>
                <div class="h-10 w-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg shrink-0 animate-pulse">
                    <i class="fa-solid fa-gears"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $inProductionCount }}</h3>
            <p class="text-[11px] text-cyan-600 font-semibold mt-1 flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full bg-cyan-500 animate-ping"></span> Active on press
            </p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Completed Jobs</p>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $completedCount }}</h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">Ready for inspection/dispatch</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Delayed / Priority</p>
                <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display mt-2">{{ $delayedCount }}</h3>
            <p class="text-[11px] text-amber-600 font-semibold mt-1">Requires immediate attention</p>
        </div>
    </div>

    {{-- Main Workspace: Table + Sidebar Widgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Assigned Tasks Table --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-navy-900 text-base">My Assigned Production Tasks</h3>
                        <p class="text-xs text-slate-500">Real-time task queue prioritized by urgency and machine allocation</p>
                    </div>
                    <a href="{{ route('production.jobs.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1">
                        View Full Queue &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200/60">
                            <tr>
                                <th class="px-6 py-3.5">Job #</th>
                                <th class="px-6 py-3.5">Service Details</th>
                                <th class="px-6 py-3.5">Machine</th>
                                <th class="px-6 py-3.5">Priority</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($myJobs as $job)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold text-navy-900">
                                    <a href="{{ route('production.jobs.show', $job) }}" class="text-brand-600 hover:underline">
                                        #{{ $job->job_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-xs">{{ $job->order->printRequest->service ?? 'Print Service' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5"><i class="fa-solid fa-layer-group text-[10px] mr-1"></i> {{ number_format($job->order->printRequest->quantity ?? 0) }} pcs</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium text-[11px]">
                                        <i class="fa-solid fa-print text-slate-400"></i>
                                        {{ $job->machine->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $job->priority_badge_class }}">
                                        {{ $job->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $job->status_badge_class }}">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('production.jobs.show', $job) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-xl text-xs transition">
                                            Details
                                        </a>
                                        <a href="{{ route('production.jobs.status-form', $job) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition flex items-center gap-1">
                                            Update &rarr;
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="h-12 w-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <h4 class="font-bold text-navy-900 text-sm">All caught up!</h4>
                                        <p class="text-xs text-slate-400">There are no pending jobs currently assigned to your shift line.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column: Equipment Status & Operator Quick Guide --}}
        <div class="space-y-6">

            {{-- Assigned Machine Health & Status --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-microchip text-brand-500"></i> Machine Status
                    </h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Operational
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Primary Equipment</p>
                        <p class="font-extrabold text-navy-900 text-sm mt-0.5">Heidelberg Speedmaster XL 106</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2 pt-2 border-t border-slate-200/60">
                            <span>Max Speed: <strong>15,000 sheet/hr</strong></span>
                            <span class="text-emerald-600 font-bold">96% Capacity</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Paper Feed</span>
                            <span class="font-extrabold text-navy-900 text-xs text-emerald-600">Stock Ready</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-[10px] text-slate-400 uppercase font-bold block">Ink Levels</span>
                            <span class="font-extrabold text-navy-900 text-xs text-brand-600">CMYK Optimal</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Standard Operating Protocol Checklist --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-emerald-500"></i> Operator Quality Steps
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Pre-Flight Artwork Check:</strong> Verify bleed and crop marks before starting run.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Color Calibration:</strong> Print sample sheet & verify CMYK accuracy.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Post-Print Inspection:</strong> Check cutting alignment and count prior to marking completed.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection
