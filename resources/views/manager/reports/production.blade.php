@extends('layouts.internal')
@section('title', 'Production Performance Report')
@section('page-title', 'Production Performance Report')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: PRODUCTION REPORT --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('manager.reports.index') }}" class="h-12 w-12 rounded-2xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main flex items-center justify-center text-lg shadow-sm transition hover:scale-105 shrink-0" title="Back to Reports Hub">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-sky-600 dark:text-cyan-400 font-mono">OPERATIONAL TELEMETRY</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Production Performance &amp; Job Status Logs</h2>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="fa-solid fa-print text-cyan-400 text-xs"></i> Print Audit
                </button>
                <a href="{{ route('manager.production-planning.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-xs"></i> Plan Schedule
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SEARCH & STATUS FILTER TOOLBAR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <form method="GET" action="{{ route('manager.reports.production') }}" class="bg-cyber-card border border-cyber rounded-2xl p-4 shadow-sm flex flex-col md:flex-row items-center justify-between gap-3">
        <div class="w-full md:w-80 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-cyber-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search job #, customer, branch..."
                   class="w-full pl-9 pr-4 py-2 text-xs bg-cyber-sub border border-cyber rounded-xl text-cyber-main placeholder:text-cyber-muted focus:outline-hidden focus:border-cyan-500 transition font-sans">
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-start md:justify-end">
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-cyber-sub border border-cyber rounded-xl text-cyber-main focus:outline-hidden focus:border-cyan-500 font-sans">
                <option value="">All Statuses</option>
                <option value="in_production" {{ request('status') === 'in_production' ? 'selected' : '' }}>In Production</option>
                <option value="quality_checking" {{ request('status') === 'quality_checking' ? 'selected' : '' }}>Quality Checking</option>
                <option value="delayed" {{ request('status') === 'delayed' ? 'selected' : '' }}>Delayed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
            </select>

            <button type="submit" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold transition shadow-xs">
                Filter
            </button>

            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('manager.reports.production') }}" class="px-3 py-2 text-xs bg-cyber-sub hover:bg-cyber-card border border-cyber rounded-xl text-cyber-muted hover:text-cyber-main transition">
                    Clear
                </a>
            @endif
        </div>
    </form>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PRODUCTION AUDIT LOG DATA TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px]">
                    <tr>
                        <th class="px-5 py-3.5">Job # &amp; Date</th>
                        <th class="px-5 py-3.5">Customer &amp; Service</th>
                        <th class="px-5 py-3.5">Branch &amp; Press</th>
                        <th class="px-5 py-3.5">Priority</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Timeline</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($jobs as $job)
                        @php
                            $customerName = $job->order->user->name ?? 'Direct Customer';
                            $service = $job->order->printRequest->service ?? 'Print Order';
                            $branchName = $job->branch->name ?? 'Branch Hub';
                            $machineName = $job->machine->name ?? 'Assigned Press';

                            $priority = strtolower($job->priority ?? 'normal');
                            $priorityBadge = match($priority) {
                                'urgent' => 'bg-rose-500/15 text-rose-700 dark:text-rose-400',
                                'rush'   => 'bg-amber-500/15 text-amber-800 dark:text-amber-400',
                                default  => 'bg-slate-500/15 text-slate-700 dark:text-slate-400',
                            };

                            $statusStr = strtolower($job->status ?? 'pending');
                            $statusBadge = match($statusStr) {
                                'in_production', 'production' => 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-400',
                                'completed'                   => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400',
                                'delayed'                     => 'bg-rose-500/15 text-rose-700 dark:text-rose-400',
                                'quality_checking'            => 'bg-purple-500/15 text-purple-700 dark:text-purple-400',
                                default                       => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400',
                            };

                            $detailRoute = Route::has('manager.production-planning.show') 
                                ? route('manager.production-planning.show', $job->id) 
                                : null;
                        @endphp
                        <tr class="hover:bg-cyber-hover/50 transition">
                            {{-- Job Number & Timestamp --}}
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-cyber-main text-xs block">{{ $job->job_number }}</span>
                                <span class="text-[10px] text-cyber-muted block mt-0.5">{{ $job->created_at ? $job->created_at->format('M d, Y') : '—' }}</span>
                            </td>

                            {{-- Customer & Service --}}
                            <td class="px-5 py-4 min-w-[160px]">
                                <span class="font-bold text-cyber-main block truncate max-w-[180px]">{{ $customerName }}</span>
                                <span class="text-[11px] text-sky-600 dark:text-cyan-400 font-semibold block truncate max-w-[180px]">{{ $service }}</span>
                            </td>

                            {{-- Branch & Machine --}}
                            <td class="px-5 py-4 min-w-[150px]">
                                <span class="font-medium text-cyber-main block truncate max-w-[160px]">{{ $branchName }}</span>
                                <span class="text-[10px] text-cyber-muted block truncate max-w-[160px] font-mono">{{ $machineName }}</span>
                            </td>

                            {{-- Priority --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $priorityBadge }} font-mono">
                                    @if($priority === 'urgent') <i class="fa-solid fa-bolt mr-0.5"></i> @endif
                                    {{ $priority }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $statusBadge }} font-mono">
                                    {{ $job->status_label }}
                                </span>
                                @if($statusStr === 'delayed' && !empty($job->delay_reason))
                                    <span class="text-[9px] text-rose-400 block mt-1 truncate max-w-[130px]" title="{{ $job->delay_reason }}">
                                        {{ $job->delay_reason }}
                                    </span>
                                @endif
                            </td>

                            {{-- Timeline --}}
                            <td class="px-5 py-4 whitespace-nowrap text-cyber-muted text-[11px] font-mono">
                                @if($job->completed_at)
                                    <span class="text-emerald-600 dark:text-emerald-400">Done {{ $job->completed_at->format('M d, H:i') }}</span>
                                @elseif($job->started_at)
                                    <span>Run {{ $job->started_at->format('M d, H:i') }}</span>
                                @else
                                    <span>Queued</span>
                                @endif
                            </td>

                            {{-- Action with View Eye Icon --}}
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                @if($detailRoute)
                                    <a href="{{ $detailRoute }}" 
                                       class="h-8 w-8 rounded-xl bg-white hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700/80 border border-slate-300 dark:border-slate-700 hover:border-sky-400 dark:hover:border-cyan-500/40 inline-flex items-center justify-center transition-all shadow-xs group"
                                       title="View Job Details">
                                        <i class="fa-solid fa-eye text-sm text-slate-800 dark:text-slate-100 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors"></i>
                                    </a>
                                @else
                                    <span class="text-cyber-muted">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-cyber-muted text-xs">
                                <i class="fa-solid fa-box-archive text-cyber-sub text-3xl mb-2 block"></i>
                                No production jobs match the specified criteria.
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
