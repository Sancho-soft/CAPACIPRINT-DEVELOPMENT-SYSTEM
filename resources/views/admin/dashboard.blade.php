@extends('layouts.internal')
@section('title', 'System Admin — Press Network Overview')
@section('page-title', 'Press Network Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Press Network Overview</h1>
            <p class="text-sm text-cyber-muted mt-1">Live branch capacity, active press queues, and staff activity across Davao plants.</p>
        </div>

        {{-- Quick Navigation Actions --}}
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-xs text-[#2F7BFF]"></i>
                <span>User Accounts</span>
            </a>
            <a href="{{ route('admin.branches.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-network-wired text-xs text-[#2F7BFF]"></i>
                <span>Press Plants</span>
            </a>
            <a href="{{ route('management.audit-logs.index') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-xs text-cyber-muted"></i>
                <span>Audit Trail</span>
            </a>
        </div>
    </div>

    {{-- UNIFIED 4-COLUMN NETWORK TELEMETRY STRIP (1 level of border) --}}
    <div class="bg-cyber-card border border-cyber rounded-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-cyber overflow-hidden">
        <a href="{{ route('manager.production-planning.index') }}" class="p-4 sm:p-5 flex flex-col justify-between gap-2 hover:bg-cyber-sub/50 transition-colors group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-cyber-muted group-hover:text-cyber-main transition-colors">Active Press Jobs</span>
                <span class="w-2 h-2 rounded-full bg-[#2F7BFF]" aria-hidden="true"></span>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-[1.75rem] font-bold font-display text-cyber-main tabular-nums leading-none">{{ $activeJobs }}</span>
                <span class="text-xs font-mono text-cyber-muted group-hover:text-[#2F7BFF] transition-colors">Queue &rarr;</span>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="p-4 sm:p-5 flex flex-col justify-between gap-2 hover:bg-cyber-sub/50 transition-colors group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-cyber-muted group-hover:text-cyber-main transition-colors">Registered Accounts</span>
                <span class="w-2 h-2 rounded-full bg-[#2F7BFF]" aria-hidden="true"></span>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-[1.75rem] font-bold font-display text-cyber-main tabular-nums leading-none">{{ $totalUsers }}</span>
                <span class="text-xs font-mono text-cyber-muted group-hover:text-[#2F7BFF] transition-colors">Access &rarr;</span>
            </div>
        </a>

        <a href="{{ route('management.branches.index') }}" class="p-4 sm:p-5 flex flex-col justify-between gap-2 hover:bg-cyber-sub/50 transition-colors group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-cyber-muted group-hover:text-cyber-main transition-colors">Operational Plants</span>
                <span class="w-2 h-2 rounded-full bg-[#10B981]" aria-hidden="true"></span>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-[1.75rem] font-bold font-display text-cyber-main tabular-nums leading-none">{{ $totalBranches }}</span>
                <span class="text-xs font-mono text-emerald-600 dark:text-[#10B981]">Online</span>
            </div>
        </a>

        <a href="{{ route('management.reports.orders') }}" class="p-4 sm:p-5 flex flex-col justify-between gap-2 hover:bg-cyber-sub/50 transition-colors group">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[13px] font-medium text-cyber-muted group-hover:text-cyber-main transition-colors">Total Print Orders</span>
                <span class="w-2 h-2 rounded-full bg-[#FFC531]" aria-hidden="true"></span>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-[1.75rem] font-bold font-display text-cyber-main tabular-nums leading-none">{{ $totalOrders }}</span>
                <span class="text-xs font-mono text-cyber-muted group-hover:text-[#2F7BFF] transition-colors">Reports &rarr;</span>
            </div>
        </a>
    </div>

    {{-- COMMERCIAL PRINTING PRODUCTION PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Pipeline"
        :subtitle="null"
    />

    {{-- MULTI-BRANCH CAPACITY & PRESS MACHINE LOAD --}}
    <x-dashboard.branch-workload-card 
        :branches="$branches"
        title="Multi-Branch Capacity &amp; Press Machine Load"
        :subtitle="null"
        :actionUrl="route('management.branches.index')"
        actionLabel="Manage Branches"
    />

    {{-- SHOP-FLOOR PRODUCTION QUEUE --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Shop-Floor Production Queue (Active Runs)"
        :subtitle="null"
        :viewAllUrl="route('manager.production-planning.index')"
        viewAllLabel="Production Planning"
    />

    {{-- USER ACCOUNTS & SYSTEM AUDIT TRAIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT 2 COLS: RECENT USER ACCOUNTS --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent User Accounts</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5">
                    <span>Manage Accounts</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="bg-cyber-sub text-cyber-muted font-sans font-semibold border-b border-cyber text-xs">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">User</th>
                            <th class="px-4 sm:px-5 py-3">Role</th>
                            <th class="px-4 sm:px-5 py-3">Branch</th>
                            <th class="px-4 sm:px-5 py-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main">
                        @forelse($recentUsers as $u)
                            <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                                <td class="px-4 sm:px-5 py-3.5">
                                    <div class="min-w-0">
                                        <span class="font-semibold text-cyber-main block truncate max-w-[200px]">{{ $u->name }}</span>
                                        <span class="text-xs text-cyber-muted block truncate max-w-[200px] font-sans">{{ $u->email }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-cyber-main text-xs font-medium">
                                    {{ $u->role_label }}
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 text-cyber-muted whitespace-nowrap">
                                    {{ $u->branch->name ?? 'Headquarters' }}
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 text-cyber-muted font-mono text-xs whitespace-nowrap tabular-nums">
                                    {{ $u->created_at ? $u->created_at->format('M d, Y') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">No registered accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recentUsers->hasPages())
                <div class="px-5 py-3 border-t border-cyber bg-cyber-sub">
                    {{ $recentUsers->links() }}
                </div>
            @endif
        </div>

        {{-- RIGHT 1 COL: SYSTEM AUDIT TRAIL --}}
        <div class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">System Audit Trail</h3>
                <a href="{{ route('management.audit-logs.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5">
                    <span>Full Log</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            <div class="p-4 sm:p-5 flex-1 divide-y divide-cyber-sub text-xs">
                @forelse($recentAuditLogs as $log)
                    @php
                        $actLower = strtolower($log->action ?? '');
                        // Strict 3-status color limit: Green (create/register), Warm Yellow (delete/cancel/update warning), Brand Blue (default/auth)
                        $dotClass = match(true) {
                            str_contains($actLower, 'create') || str_contains($actLower, 'register') || str_contains($actLower, 'approve') => 'bg-[#10B981]',
                            str_contains($actLower, 'delete') || str_contains($actLower, 'cancel') || str_contains($actLower, 'reject') || str_contains($actLower, 'archive') => 'bg-[#FFC531]',
                            default => 'bg-[#2F7BFF]',
                        };
                    @endphp
                    <div class="py-3 first:pt-0 last:pb-0 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} shrink-0"></span>
                                <span class="font-semibold text-cyber-main text-xs truncate">{{ $log->action ?? 'System Event' }}</span>
                            </div>
                            <span class="text-xs text-cyber-muted font-mono shrink-0 tabular-nums">{{ $log->created_at ? $log->created_at->diffForHumans(null, true) : 'now' }}</span>
                        </div>
                        <p class="text-xs text-cyber-muted line-clamp-2 leading-relaxed pl-3.5">{{ $log->description ?? 'Action performed in system.' }}</p>
                        @if($log->user)
                            <span class="text-xs text-[#2F7BFF] dark:text-[#60A5FA] font-medium block pl-3.5">by {{ $log->user->name }}</span>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-cyber-muted text-xs">
                        <i class="fa-solid fa-shield-halved text-cyber-sub text-2xl mb-2 block"></i>
                        No recent audit log entries recorded.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
