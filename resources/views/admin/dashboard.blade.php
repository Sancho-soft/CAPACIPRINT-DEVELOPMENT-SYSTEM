@extends('layouts.internal')
@section('title', 'System Admin — Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: DASHBOARD OVERVIEW --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
        </div>

        {{-- Right Quick Navigation Actions --}}
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-users-gear text-xs text-sky-500 dark:text-sky-400"></i> User Accounts
            </a>
            <a href="{{ route('management.branches.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-network-wired text-xs text-sky-500 dark:text-sky-400"></i> Branches & Presses
            </a>
            <a href="{{ route('management.audit-logs.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-shield-halved text-xs text-slate-500 dark:text-slate-400"></i> Audit Trail
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY SYSTEM METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="ACTIVE PRESS JOBS"
            :value="$activeJobs"
            icon="fa-solid fa-industry"
            accent="cyan"
            :link="route('manager.production-planning.index')"
        />

        <x-dashboard.kpi-card 
            title="REGISTERED ACCOUNTS"
            :value="$totalUsers"
            icon="fa-solid fa-users-gear"
            accent="cyan"
            :link="route('admin.users.index')"
        />

        <x-dashboard.kpi-card 
            title="OPERATIONAL BRANCHES"
            :value="$totalBranches"
            icon="fa-solid fa-store"
            accent="emerald"
            :link="route('management.branches.index')"
        />

        <x-dashboard.kpi-card 
            title="TOTAL PRINT ORDERS"
            :value="$totalOrders"
            icon="fa-solid fa-receipt"
            accent="amber"
            :link="route('management.reports.orders')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING LIFECYCLE PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Pipeline"
        :subtitle="null"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: MULTI-BRANCH CAPACITY & PRESS MONITOR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.branch-workload-card 
        :branches="$branches"
        title="Multi-Branch Capacity & Press Machine Load"
        :subtitle="null"
        :actionUrl="route('management.branches.index')"
        actionLabel="Manage Branches"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: LIVE PRODUCTION QUEUE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Shop-Floor Production Queue (Active Runs)"
        :subtitle="null"
        :viewAllUrl="route('manager.production-planning.index')"
        viewAllLabel="Production Planning"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 3 & 4: USER ACCOUNTS & AUDIT LOGS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT 2 COLS: RECENT USER ACCOUNTS --}}
        <div class="lg:col-span-2 bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
                <div>
                    <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent User Accounts</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                    Manage Accounts <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                        <tr>
                            <th class="px-4 sm:px-5 py-3.5">User</th>
                            <th class="px-4 sm:px-5 py-3.5">Role</th>
                            <th class="px-4 sm:px-5 py-3.5">Branch</th>
                            <th class="px-4 sm:px-5 py-3.5">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @forelse($recentUsers as $u)
                            <tr class="hover:bg-cyber-hover/50 transition">
                                <td class="px-4 sm:px-5 py-3.5">
                                    <div class="min-w-0">
                                        <span class="font-bold text-cyber-main block truncate max-w-[200px]">{{ $u->name }}</span>
                                        <span class="text-[10px] text-cyber-muted block truncate max-w-[200px] font-mono">{{ $u->email }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap text-cyber-main text-xs">
                                    {{ $u->role_label }}
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 text-cyber-muted whitespace-nowrap">
                                    {{ $u->branch->name ?? 'Headquarters' }}
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 text-cyber-sub font-mono text-[11px] whitespace-nowrap">
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
                <div class="px-5 py-3 border-t border-cyber/60 bg-cyber-sub/40">
                    {{ $recentUsers->links() }}
                </div>
            @endif
        </div>

        {{-- RIGHT 1 COL: SYSTEM AUDIT TRAIL --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
                <div>
                    <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">System Audit Trail</h3>
                </div>
                <a href="{{ route('management.audit-logs.index') }}" class="text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 flex items-center gap-1">
                    Full Log <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-4 sm:p-5 flex-1 divide-y divide-cyber/60 text-xs">
                @forelse($recentAuditLogs as $log)
                    @php
                        $actLower = strtolower($log->action ?? '');
                        $badge = match(true) {
                            str_contains($actLower, 'create') || str_contains($actLower, 'register') => [
                                'icon' => 'fa-solid fa-plus',
                                'class' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                            ],
                            str_contains($actLower, 'delete') || str_contains($actLower, 'cancel') || str_contains($actLower, 'reject') => [
                                'icon' => 'fa-solid fa-xmark',
                                'class' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
                            ],
                            str_contains($actLower, 'login') || str_contains($actLower, 'auth') => [
                                'icon' => 'fa-solid fa-key',
                                'class' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20',
                            ],
                            str_contains($actLower, 'update') || str_contains($actLower, 'status') || str_contains($actLower, 'edit') => [
                                'icon' => 'fa-solid fa-arrows-rotate',
                                'class' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                            ],
                            default => [
                                'icon' => 'fa-solid fa-shield-halved',
                                'class' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
                            ],
                        };
                    @endphp
                    <div class="py-3 first:pt-0 last:pb-0 space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-5 w-5 rounded-lg border flex items-center justify-center text-[10px] shrink-0 {{ $badge['class'] }}">
                                    <i class="{{ $badge['icon'] }}"></i>
                                </span>
                                <span class="font-bold text-cyber-main text-xs truncate">{{ $log->action ?? 'System Event' }}</span>
                            </div>
                            <span class="text-[10px] text-cyber-sub font-mono shrink-0">{{ $log->created_at ? $log->created_at->diffForHumans() : 'Recently' }}</span>
                        </div>
                        <p class="text-[11px] text-cyber-muted line-clamp-2 leading-relaxed pl-7">{{ $log->description ?? 'Action performed in system.' }}</p>
                        @if($log->user)
                            <span class="text-[10px] text-cyan-600 dark:text-cyan-400 font-medium block pl-7">by {{ $log->user->name }}</span>
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
