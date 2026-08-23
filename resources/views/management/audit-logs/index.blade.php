@extends('layouts.internal')

@section('title', 'System Audit Trail & Security Logs')
@section('page-title', 'System Audit Logs')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-display">System Audit Trail & Monitoring</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm">Real-time immutable log of system events, financial approvals, user role changes, and inventory actions.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <form action="{{ route('management.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events or descriptions..." class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-800 dark:text-white w-full md:w-64">
            
            <select name="module" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-800 dark:text-white">
                <option value="">All System Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-4 py-2 rounded-xl text-sm transition">Filter</button>
        </form>

        <span class="text-xs text-slate-400 font-mono">Total Records: {{ number_format($logs->total()) }}</span>
    </div>

    <!-- Audit Logs Datatable -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-semibold text-xs tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Module</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition font-mono text-xs">
                        <td class="px-6 py-4 text-slate-400 whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-6 py-4 font-sans font-semibold text-slate-900 dark:text-white">{{ $log->user->name ?? 'System / Anonymous' }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 font-bold rounded-md bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">{{ $log->module }}</span></td>
                        <td class="px-6 py-4 font-sans font-bold text-brand-600 dark:text-brand-400">{{ $log->event }}</td>
                        <td class="px-6 py-4 font-sans text-slate-700 dark:text-slate-300 max-w-md truncate">{{ $log->description }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">No system audit log records found.</td>
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
@endsection
