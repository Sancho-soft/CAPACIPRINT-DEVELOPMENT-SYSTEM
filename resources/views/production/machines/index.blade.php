@extends('layouts.internal')

@section('title', 'Equipment & Machine Maintenance')
@section('page-title', 'Equipment Control')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-display">Equipment Maintenance & Incident Logs</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Report shop-floor machine breakdowns and log preventive maintenance logs.</p>
        </div>

        <button onclick="document.getElementById('report-issue-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-rose-500/20 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Report Equipment Breakdown
        </button>
    </div>

    <!-- Machine Logs Datatable -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-semibold text-xs tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Logged At</th>
                        <th class="px-6 py-4">Machine</th>
                        <th class="px-6 py-4">Branch</th>
                        <th class="px-6 py-4">Incident Type</th>
                        <th class="px-6 py-4">Issue Description</th>
                        <th class="px-6 py-4">Reported By</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $log->machine->name ?? 'Machine #' . $log->machine_id }}</td>
                        <td class="px-6 py-4">{{ $log->machine->branch->name ?? 'Main Branch' }}</td>
                        <td class="px-6 py-4 font-bold uppercase text-xs">
                            @if($log->log_type === 'breakdown')
                                <span class="text-rose-500">Breakdown</span>
                            @else
                                <span class="text-amber-500">{{ ucfirst($log->log_type) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300 max-w-sm">{{ $log->issue_description }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $log->reporter->name ?? 'Operator' }}</td>
                        <td class="px-6 py-4">
                            @if($log->status === 'open')
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">Open / Active Breakdown</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Resolved</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">No equipment incident logs recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- Report Issue Modal -->
<div id="report-issue-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display mb-4">Report Machine Issue</h3>
        <form action="{{ route('production.machines.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Target Machine</label>
                <select name="machine_id" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white">
                    @foreach($machines as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->machine_type }}) — {{ ucfirst($m->status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Incident Type</label>
                <select name="log_type" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white">
                    <option value="breakdown">Breakdown (Halts Production)</option>
                    <option value="maintenance">Preventive Maintenance</option>
                    <option value="inspection">Routine Inspection</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Description of Breakdown / Issue</label>
                <textarea name="issue_description" rows="4" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="Describe error codes, paper jams, or mechanical fault details..."></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('report-issue-modal').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-semibold text-sm">Cancel</button>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-5 py-2 rounded-xl text-sm shadow-md">Submit Incident Report</button>
            </div>
        </form>
    </div>
</div>
@endsection
