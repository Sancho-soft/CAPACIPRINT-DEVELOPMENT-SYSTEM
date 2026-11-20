@extends('layouts.internal')

@section('title', 'System Audit Trail & Security Logs')
@section('page-title', 'System Audit Logs')

@section('content')
<div class="space-y-6 max-w-7xl font-sans">

    <!-- Header -->
    <div>
        <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display">System Audit Trail &amp; Monitoring</h1>
        <p class="text-xs text-cyber-muted mt-1">Real-time immutable log of system events, financial approvals, user role changes, and inventory actions.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-cyber-card border border-cyber rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <form action="{{ route('management.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events or descriptions..." class="bg-cyber-sub dark:bg-cyber-sub border border-cyber dark:border-cyber rounded-xl px-3.5 py-2 text-xs text-cyber-main w-full md:w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder-cyber-muted">
            
            <select name="module" onchange="this.form.submit()" class="bg-cyber-sub dark:bg-cyber-sub border border-cyber dark:border-cyber rounded-xl px-3.5 py-2 text-xs text-cyber-main focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">All System Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-sm">Filter</button>
        </form>

        <span class="text-xs text-cyber-muted font-sans tabular-nums">Total Records: <strong class="text-cyber-main">{{ number_format($logs->total()) }}</strong></span>
    </div>

    <!-- Audit Logs Datatable -->
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main tabular-nums">
                <thead class="bg-cyber-sub/60 dark:bg-cyber-sub text-cyber-muted font-bold text-[11px] border-b border-cyber">
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
                        <td class="px-5 py-3.5 text-cyber-muted whitespace-nowrap font-sans tabular-nums text-[11px]">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyber-main">{{ $log->user->name ?? 'System / Anonymous' }}</td>
                        
                        {{-- Module: Unboxed and not colored (plain text) --}}
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-[11px] text-cyber-main font-sans tabular-nums">
                                {{ $log->module }}
                            </span>
                        </td>
                        
                        {{-- Event: Not colored (plain text) --}}
                        <td class="px-5 py-3.5 font-bold text-cyber-main">
                            {{ $log->event }}
                        </td>
                        
                        <td class="px-5 py-3.5 text-cyber-main max-w-md truncate">{{ $log->description }}</td>
                        <td class="px-5 py-3.5 text-right text-cyber-muted font-sans tabular-nums text-[11px]">{{ $log->ip_address ?? '127.0.0.1' }}</td>
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
