@extends('layouts.internal')
@section('title', 'Production Floor Dashboard')
@section('page-title', 'Production Floor Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Production Floor Dashboard</h2>
            <p class="text-xs text-slate-400 mt-1">Real-time production tracking, assigned line tasks, and press calibration.</p>
        </div>
        <a href="{{ route('production.jobs.index') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-list-check text-xs"></i> View Full Task Queue
        </a>
    </div>

    {{-- Operator Welcome & Shift Target Banner --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 sm:p-7 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 font-black font-display text-xl shrink-0 shadow-[0_0_20px_rgba(6,182,212,0.2)]">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg sm:text-xl font-black font-display text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 tracking-wider">Shift Active</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Ready for production. You have <strong class="text-cyan-400">{{ $inProductionCount }} active job(s)</strong> currently running on your assigned line.</p>
                </div>
            </div>

            {{-- Quick Shift Progress Widget --}}
            <div class="bg-[#0D1520] border border-slate-800 rounded-2xl p-4 min-w-[260px]">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-slate-400 font-semibold">Today's Shift Completion Target</span>
                    <span class="text-cyan-400 font-bold font-mono">{{ $completedCount }} / {{ max(1, $assignedCount + $completedCount) }} Jobs</span>
                </div>
                @php 
                    $totalShiftJobs = max(1, $assignedCount + $completedCount);
                    $pct = min(100, round(($completedCount / $totalShiftJobs) * 100));
                @endphp
                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-400 to-emerald-400 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-2 font-medium">
                    <span>Target Efficiency Rate</span>
                    <span class="text-emerald-400 font-bold font-mono">{{ $pct }}% Completed</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Assigned Jobs --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-list-check text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">ASSIGNED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $assignedCount }}</div>
            </div>
        </div>

        {{-- In Production --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-blue-400 p-5 flex items-center justify-between shadow-lg hover:border-blue-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-gears text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-blue-400 transition-all"></i>
                <div class="text-[11px] font-black text-blue-400 uppercase tracking-wider leading-tight max-w-[110px]">IN PRODUCTION</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $inProductionCount }}</div>
            </div>
        </div>

        {{-- Completed Jobs --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-circle-check text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">COMPLETED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $completedCount }}</div>
            </div>
        </div>

        {{-- Delayed / Priority --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">DELAYED / PRIORITY</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $delayedCount }}</div>
            </div>
        </div>
    </div>

    {{-- Main Workspace: Table + Sidebar Widgets --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Assigned Tasks Table --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
                    <div>
                        <h3 class="font-black text-white text-sm">My Assigned Production Tasks</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Real-time task queue prioritized by urgency and machine allocation</p>
                    </div>
                    <a href="{{ route('production.jobs.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        Full Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                            <tr>
                                <th class="px-5 py-3.5">Job #</th>
                                <th class="px-5 py-3.5">Service Details</th>
                                <th class="px-5 py-3.5">Machine</th>
                                <th class="px-5 py-3.5">Priority</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300">
                            @forelse($myJobs as $job)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-5 py-3.5 font-bold text-white font-mono">
                                    <a href="{{ route('production.jobs.show', $job) }}" class="text-cyan-400 hover:underline">
                                        {{ $job->job_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-white text-xs">{{ $job->order->printRequest->service ?? 'Print Service' }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 font-mono"><i class="fa-solid fa-layer-group text-[10px] mr-1 text-slate-500"></i> {{ number_format($job->order->printRequest->quantity ?? 0) }} pcs</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium text-[11px] border border-slate-200 dark:border-slate-700">
                                        <i class="fa-solid fa-print text-cyan-500"></i>
                                        {{ $job->machine->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $job->priority_badge_class }}">
                                        {{ $job->priority }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $job->status_badge_class }}">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('production.jobs.show', $job) }}"
                                           class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition"
                                           title="View Job Details">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('production.jobs.status-form', $job) }}" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm transition flex items-center gap-1">
                                            Update &rarr;
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="h-12 w-12 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center text-xl mx-auto">
                                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                        </div>
                                        <h4 class="font-bold text-white text-sm">All caught up!</h4>
                                        <p class="text-xs text-slate-500">There are no pending jobs currently assigned to your shift line.</p>
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
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                    <h4 class="font-black text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-microchip text-cyan-400"></i> Machine Status
                    </h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Operational
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="bg-[#0D1520] p-3.5 rounded-2xl border border-slate-800">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Primary Line</p>
                        <p class="font-black text-white text-sm mt-0.5">Heidelberg Speedmaster XL 106</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2 pt-2 border-t border-slate-800">
                            <span>Max Speed: <strong class="text-slate-200">15,000 sheet/hr</strong></span>
                            <span class="text-emerald-400 font-bold font-mono">96% Capacity</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Paper Feed</span>
                            <span class="font-black text-emerald-400 text-xs mt-0.5 block">Stock Ready</span>
                        </div>
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Ink Levels</span>
                            <span class="font-black text-cyan-400 text-xs mt-0.5 block">CMYK Optimal</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Standard Operating Protocol Checklist --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3">
                <h4 class="font-black text-white text-sm flex items-center gap-2 border-b border-slate-800/80 pb-3">
                    <i class="fa-solid fa-list-check text-emerald-400"></i> Operator Quality Protocol
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-400">
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Pre-Flight Artwork Check:</strong> Verify bleed and crop marks before starting run.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Color Calibration:</strong> Print sample sheet &amp; verify CMYK accuracy.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Post-Print Inspection:</strong> Check cutting alignment and count prior to marking completed.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection

