@extends('layouts.internal')
@section('title', 'Executive Production Performance Report')
@section('page-title', 'Production Execution & Efficiency Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-industry"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Production Execution Report</h2>
                <p class="text-xs sm:text-sm text-slate-300">Track shop-floor machine runtimes, technician allocations, job priorities, and completion durations.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 transition">
                <i class="fa-solid fa-file-csv text-sm"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('management.reports.production') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Branch</label>
                <select name="branch_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Priority</label>
                <select name="priority" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <option value="">All Priorities</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                    <option value="rush" {{ request('priority') === 'rush' ? 'selected' : '' }}>🟠 Rush</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>🟢 Normal</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-navy-800 text-white rounded-xl font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['branch_id', 'priority', 'from_date', 'to_date']))
                    <a href="{{ route('management.reports.production') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Job #</th>
                        <th class="py-3.5 px-4">Service &amp; Qty</th>
                        <th class="py-3.5 px-4">Branch</th>
                        <th class="py-3.5 px-4">Machine</th>
                        <th class="py-3.5 px-4">Technician</th>
                        <th class="py-3.5 px-4">Priority</th>
                        <th class="py-3.5 px-4">Timeline</th>
                        <th class="py-3.5 px-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jobs as $j)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-navy-900 font-display">#{{ $j->job_number }}</td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-navy-900">{{ $j->order->printRequest->service ?? 'Print Service' }}</span>
                                <div class="text-[10px] text-slate-400">{{ $j->order->printRequest->quantity ?? 1 }} copies</div>
                            </td>
                            <td class="py-4 px-4 font-bold text-navy-800">
                                {{ $j->branch->name ?? '—' }}
                            </td>
                            <td class="py-4 px-4 text-slate-700">
                                {{ $j->machine->name ?? 'Unassigned' }}
                            </td>
                            <td class="py-4 px-4 text-slate-700">
                                {{ $j->assignedTo->name ?? 'Unassigned' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $j->priority_badge_class }}">
                                    {{ $j->priority }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-500 text-[11px]">
                                <div><span class="text-slate-400">Started:</span> {{ $j->started_at ? $j->started_at->format('M d, H:i') : '—' }}</div>
                                <div><span class="text-slate-400">Done:</span> {{ $j->completed_at ? $j->completed_at->format('M d, H:i') : 'Pending' }}</div>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $j->status_badge_class }}">
                                    {{ $j->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-industry text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">No production jobs found for the selected criteria.</p>
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
