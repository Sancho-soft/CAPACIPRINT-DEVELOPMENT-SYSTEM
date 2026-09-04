@extends('layouts.internal')
@section('title', 'Production Floor Dashboard')
@section('page-title', 'Production Floor Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl font-sans">

    {{-- Page Header Title & Queue Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white font-display tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-industry text-cyan-400"></i> Production Floor Dashboard
            </h1>
        </div>
        <a href="{{ route('production.jobs.index') }}" class="px-5 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-[0_0_20px_rgba(6,182,212,0.3)] transition flex items-center gap-2 shrink-0 cursor-pointer">
            <i class="fa-solid fa-list-check text-xs"></i> View Full Task Queue
        </a>
    </div>

    {{-- Operator Welcome & Shift Target Banner --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 sm:p-7 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 text-slate-950 font-black font-display text-2xl flex items-center justify-center shrink-0 shadow-[0_0_20px_rgba(6,182,212,0.3)] border-2 border-cyan-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg sm:text-xl font-black font-display text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 tracking-wider flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Shift Active
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Shift Progress Widget --}}
            <div class="bg-[#0D1520] border border-slate-800 rounded-2xl p-4 min-w-[280px] shadow-inner">
                @php 
                    $totalShiftJobs = max(1, $assignedCount + $completedCount);
                    $pct = min(100, round(($completedCount / $totalShiftJobs) * 100));
                @endphp
                <div class="flex items-center justify-between text-xs mb-2 gap-3">
                    <span class="text-slate-400 font-semibold truncate">Shift Completion Target</span>
                    <span class="text-cyan-400 font-bold font-mono shrink-0">{{ $completedCount }} / {{ $totalShiftJobs }} Jobs</span>
                </div>
                <div class="w-full bg-slate-800/80 rounded-full h-2.5 overflow-hidden p-0.5 border border-slate-700/50">
                    <div class="bg-gradient-to-r from-cyan-400 to-emerald-400 h-1.5 rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(6,182,212,0.5)]" style="width: {{ $pct }}%"></div>
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
                <i class="fa-solid fa-list-check text-2xl text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight">ASSIGNED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $assignedCount }}</div>
            </div>
        </div>

        {{-- In Production --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-blue-400 p-5 flex items-center justify-between shadow-lg hover:border-blue-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-gears text-2xl text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                <div class="text-[11px] font-black text-blue-400 uppercase tracking-wider leading-tight">IN PRODUCTION</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $inProductionCount }}</div>
            </div>
        </div>

        {{-- Completed Jobs --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-circle-check text-2xl text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight">COMPLETED JOBS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $completedCount }}</div>
            </div>
        </div>

        {{-- Delayed / Priority --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-slate-500 group-hover:text-amber-400 transition-colors"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight">DELAYED / PRIORITY</div>
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
                    <h3 class="font-black text-white text-sm">My Assigned Production Tasks</h3>
                    <a href="{{ route('production.jobs.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1.5 transition">
                        Full Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#0B1118] text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
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
                                    <p class="text-[11px] text-slate-400 mt-0.5 font-mono"><i class="fa-solid fa-layer-group text-[10px] mr-1 text-cyan-500/70"></i> {{ number_format($job->order->printRequest->quantity ?? 0) }} pcs</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#0D1520] text-slate-300 font-medium text-[11px] border border-slate-800">
                                        <i class="fa-solid fa-print text-cyan-400"></i>
                                        {{ $job->machine->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if(strtolower($job->priority) === 'rush' || strtolower($job->priority) === 'high')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                            {{ $job->priority }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">
                                            {{ $job->priority }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('production.jobs.show', $job) }}"
                                           class="inline-flex items-center justify-center h-8 w-8 rounded-xl bg-[#0D1520] hover:bg-slate-800 text-slate-300 hover:text-cyan-400 border border-slate-800 transition shadow-sm"
                                           title="View Job Details">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('production.jobs.status-form', $job) }}" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-3 py-1.5 rounded-xl text-xs shadow-sm transition flex items-center gap-1 cursor-pointer">
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

            {{-- Secondary Row: Shift Calibration Log & Operator Quick Actions --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Shift Logs & Activity --}}
                <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                        <h4 class="font-black text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-cyan-400"></i> Press Calibration Log
                        </h4>
                    </div>
                    <div class="space-y-2.5 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#0D1520] border border-slate-800/80">
                            <div class="flex items-center gap-2.5">
                                <span class="h-2 w-2 rounded-full bg-emerald-400 shrink-0"></span>
                                <span class="text-slate-300 font-medium">Color Density Check Passed</span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">15m ago</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#0D1520] border border-slate-800/80">
                            <div class="flex items-center gap-2.5">
                                <span class="h-2 w-2 rounded-full bg-cyan-400 shrink-0"></span>
                                <span class="text-slate-300 font-medium">Paper Feeder Re-aligned</span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">1h ago</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#0D1520] border border-slate-800/80">
                            <div class="flex items-center gap-2.5">
                                <span class="h-2 w-2 rounded-full bg-slate-500 shrink-0"></span>
                                <span class="text-slate-300 font-medium">Plate Mounted: XL 106</span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">3h ago</span>
                        </div>
                    </div>
                </div>

                {{-- Operator Floor Shortcuts --}}
                <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                        <h4 class="font-black text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-amber-400"></i> Production Quick Actions
                        </h4>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <button type="button" onclick="alert('Maintenance request logged for primary press.')"
                                class="p-3 rounded-2xl bg-[#0D1520] hover:bg-slate-800 border border-slate-800 text-left transition space-y-1.5 group cursor-pointer">
                            <i class="fa-solid fa-wrench text-amber-400 group-hover:scale-110 transition-transform block"></i>
                            <span class="block font-bold text-white text-xs">Log Maintenance</span>
                        </button>
                        <button type="button" onclick="alert('Stock request sent to inventory warehouse.')"
                                class="p-3 rounded-2xl bg-[#0D1520] hover:bg-slate-800 border border-slate-800 text-left transition space-y-1.5 group cursor-pointer">
                            <i class="fa-solid fa-boxes-packing text-cyan-400 group-hover:scale-110 transition-transform block"></i>
                            <span class="block font-bold text-white text-xs">Request Stock</span>
                        </button>
                    </div>
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
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Operational
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="bg-[#0D1520] p-4 rounded-2xl border border-slate-800">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Primary Production Line</p>
                        <p class="font-black text-white text-sm mt-0.5 flex items-center gap-2">
                            <i class="fa-solid fa-print text-cyan-400 text-xs"></i> Heidelberg Speedmaster XL 106
                        </p>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2.5 pt-2.5 border-t border-slate-800">
                            <span>Max Speed: <strong class="text-slate-200 font-mono">15,000 sheet/hr</strong></span>
                            <span class="text-emerald-400 font-bold font-mono">96% Capacity</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-center">
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Paper Feed</span>
                            <span class="font-black text-emerald-400 text-xs mt-1 block flex items-center justify-center gap-1">
                                <i class="fa-solid fa-check text-[10px]"></i> Stock Ready
                            </span>
                        </div>
                        <div class="bg-[#0D1520] p-3 rounded-2xl border border-slate-800">
                            <span class="text-[10px] text-slate-500 uppercase font-bold block">Ink Levels</span>
                            <span class="font-black text-cyan-400 text-xs mt-1 block flex items-center justify-center gap-1">
                                <i class="fa-solid fa-droplet text-[10px]"></i> CMYK Optimal
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Standard Operating Protocol Checklist (Interactive Alpine.js) --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3" x-data="{ c1: true, c2: false, c3: false }">
                <h4 class="font-black text-white text-sm flex items-center gap-2 border-b border-slate-800/80 pb-3">
                    <i class="fa-solid fa-list-check text-emerald-400"></i> Operator Quality Protocol
                </h4>
                <ul class="space-y-3 text-xs text-slate-400">
                    <li class="flex items-start gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-800/30 transition" @click="c1 = !c1">
                        <input type="checkbox" x-model="c1" class="mt-0.5 accent-cyan-500 rounded cursor-pointer pointer-events-none">
                        <span :class="c1 ? 'text-slate-200 font-medium' : 'text-slate-500 line-through'">
                            <strong class="text-white">Pre-Flight Artwork Check:</strong> Verify bleed and crop marks before starting run.
                        </span>
                    </li>
                    <li class="flex items-start gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-800/30 transition" @click="c2 = !c2">
                        <input type="checkbox" x-model="c2" class="mt-0.5 accent-cyan-500 rounded cursor-pointer pointer-events-none">
                        <span :class="c2 ? 'text-slate-200 font-medium' : 'text-slate-500 line-through'">
                            <strong class="text-white">Color Calibration:</strong> Print sample sheet &amp; verify CMYK accuracy.
                        </span>
                    </li>
                    <li class="flex items-start gap-3 cursor-pointer p-1.5 rounded-xl hover:bg-slate-800/30 transition" @click="c3 = !c3">
                        <input type="checkbox" x-model="c3" class="mt-0.5 accent-cyan-500 rounded cursor-pointer pointer-events-none">
                        <span :class="c3 ? 'text-slate-200 font-medium' : 'text-slate-500 line-through'">
                            <strong class="text-white">Post-Print Inspection:</strong> Check cutting alignment and count prior to marking completed.
                        </span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection
