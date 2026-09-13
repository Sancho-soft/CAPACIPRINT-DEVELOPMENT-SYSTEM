@extends('layouts.internal')

@section('title', 'System Audit Trail & Security Logs')
@section('page-title', 'System Audit Logs')

@section('content')
<div class="space-y-6 max-w-7xl font-sans">

    <!-- Header -->
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-black dark:text-white font-display">System Audit Trail &amp; Monitoring</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Real-time immutable log of system events, financial approvals, user role changes, and inventory actions.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-cyber-card border border-cyber rounded-3xl p-4 sm:p-5 shadow-xl flex flex-col md:flex-row gap-4 justify-between items-center">
        <form action="{{ route('management.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events or descriptions..." class="bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber rounded-xl px-3.5 py-2 text-xs text-black dark:text-white w-full md:w-64 focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400">
            
            <select name="module" onchange="this.form.submit()" class="bg-slate-50 dark:bg-cyber-sub border border-slate-200 dark:border-cyber rounded-xl px-3.5 py-2 text-xs text-black dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                <option value="">All System Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-xs">Filter</button>
        </form>

        <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Total Records: <strong class="text-black dark:text-white">{{ number_format($logs->total()) }}</strong></span>
    </div>

    <!-- Audit Logs Datatable -->
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-black dark:text-white">
                <thead class="bg-slate-50/50 dark:bg-cyber-sub text-slate-600 dark:text-slate-300 uppercase font-bold text-[10px] tracking-wider border-b border-cyber">
                    <tr>
                        <th class="px-5 py-3.5">Timestamp</th>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Module</th>
                        <th class="px-5 py-3.5">Event</th>
                        <th class="px-5 py-3.5">Description</th>
                        <th class="px-5 py-3.5 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($logs as $log)
                    <tr class="hover:bg-cyber-sub/60 transition text-xs">
                        <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 whitespace-nowrap font-mono text-[11px]">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-5 py-3.5 font-bold text-black dark:text-white">{{ $log->user->name ?? 'System / Anonymous' }}</td>
                        
                        {{-- Module: Unboxed and not colored (plain text) --}}
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-[11px] uppercase tracking-wider text-black dark:text-white font-mono">
                                {{ $log->module }}
                            </span>
                        </td>
                        
                        {{-- Event: Not colored (plain text) --}}
                        <td class="px-5 py-3.5 font-bold text-black dark:text-white">
                            {{ $log->event }}
                        </td>
                        
                        <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 max-w-md truncate">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-right text-slate-500 dark:text-slate-400 font-mono text-[11px]">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">No system audit log records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-cyber">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
