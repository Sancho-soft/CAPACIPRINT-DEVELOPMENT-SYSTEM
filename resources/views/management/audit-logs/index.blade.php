@extends('layouts.internal')

@section('title', 'System Audit Trail & Security Logs')
@section('page-title', 'System Audit Logs')

@section('content')
<div class="space-y-6 max-w-7xl">

    <!-- Header -->
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display">System Audit Trail &amp; Monitoring</h1>
        <p class="text-xs text-cyber-muted mt-1">Real-time immutable log of system events, financial approvals, user role changes, and inventory actions.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-cyber-card border border-cyber rounded-3xl p-4 sm:p-5 shadow-xl flex flex-col md:flex-row gap-4 justify-between items-center">
        <form action="{{ route('management.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events or descriptions..." class="bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-xs text-cyber-main w-full md:w-64 focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500">
            
            <select name="module" onchange="this.form.submit()" class="bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-xs text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                <option value="">All System Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs transition shadow-[0_0_15px_rgba(6,182,212,0.25)]">Filter</button>
        </form>

        <span class="text-xs text-cyber-muted font-mono">Total Records: <strong class="text-cyan-400">{{ number_format($logs->total()) }}</strong></span>
    </div>

    <!-- Audit Logs Datatable -->
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main">
                <thead class="bg-cyber-sub text-cyber-muted uppercase font-bold text-[11px] tracking-wider border-b border-cyber">
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
                    <tr class="hover:bg-cyber-sub/60 transition font-mono text-xs">
                        <td class="px-5 py-3.5 text-cyber-muted whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-5 py-3.5 font-sans font-bold text-cyber-main">{{ $log->user->name ?? 'System / Anonymous' }}</td>
                        <td class="px-5 py-3.5"><span class="px-2 py-0.5 font-black text-[10px] uppercase rounded-md bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">{{ $log->module }}</span></td>
                        <td class="px-5 py-3.5 font-sans font-bold text-cyan-400">{{ $log->event }}</td>
                        <td class="px-5 py-3.5 font-sans text-cyber-main max-w-md truncate">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-right text-cyber-muted">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-cyber-muted">No system audit log records found.</td>
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

