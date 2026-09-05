@extends('layouts.internal')
@section('title', 'System Admin — Operations Control Center')
@section('page-title', 'System Operations Control Center')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HEADER: COMMERCIAL PRINTING FLEET MONITOR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        {{-- Ambient cyan & indigo glow --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            {{-- Left Title & Icon --}}
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-sky-500/10 border border-sky-500/30 text-sky-500 dark:text-sky-400 flex items-center justify-center text-2xl shrink-0 shadow-sm">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">System Operations Control Center</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                            Fleet Healthy
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        Central enterprise telemetry: multi-branch capacity routing, machine press uptime, user roles, and operational throughput.
                    </p>
                </div>
            </div>

            {{-- Right Quick Navigation Actions --}}
            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-xs"></i> User Accounts
                </a>
                <a href="{{ route('management.branches.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-network-wired text-xs text-sky-500 dark:text-sky-400"></i> Branches & Presses
                </a>
                <a href="{{ route('management.audit-logs.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-xs text-slate-500 dark:text-slate-400"></i> Audit Trail
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 1: ACTIONABLE ATTENTION CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.attention-center 
        :items="$attentionItems"
        title="Enterprise Operational Attention Center"
        subtitle="Critical delayed jobs, rush deadlines, and material inventory alerts"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY SYSTEM METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="ACTIVE PRESS JOBS"
            :value="$activeJobs"
            icon="fa-solid fa-industry"
            accent="cyan"
            trend="{{ $delayedJobs > 0 ? $delayedJobs . ' delayed' : 'On schedule' }}"
            :trendType="$delayedJobs > 0 ? 'warning' : 'up'"
            subtitle="Shop-floor jobs running"
        />

        <x-dashboard.kpi-card 
            title="REGISTERED ACCOUNTS"
            :value="$totalUsers"
            icon="fa-solid fa-users-gear"
            accent="cyan"
            trend="9 operational roles"
            trendType="neutral"
            subtitle="Across all branch hubs"
            :link="route('admin.users.index')"
        />

        <x-dashboard.kpi-card 
            title="OPERATIONAL BRANCHES"
            :value="$totalBranches"
            icon="fa-solid fa-store"
            accent="emerald"
            trend="{{ $availableMachines }}/{{ $totalMachines }} presses ready"
            trendType="up"
            subtitle="Multi-branch network"
            :link="route('management.branches.index')"
        />

        <x-dashboard.kpi-card 
            title="TOTAL PRINT ORDERS"
            :value="$totalOrders"
            icon="fa-solid fa-receipt"
            accent="amber"
            trend="{{ $pendingRequests }} requests pending"
            trendType="neutral"
            subtitle="Lifetime transactions"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: COMMERCIAL PRINTING LIFECYCLE PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Commercial Printing Production Pipeline"
        subtitle="Live order progression from artwork intake and proofing to shop-floor pressing and claiming"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: MULTI-BRANCH CAPACITY & PRESS MONITOR --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.branch-workload-card 
        :branches="$branches"
        title="Multi-Branch Capacity & Press Machine Load"
        subtitle="Daily job volume vs capacity threshold and available printing equipment"
        :actionUrl="route('management.branches.index')"
        actionLabel="Manage Branches"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: LIVE PRODUCTION QUEUE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentJobs"
        title="Shop-Floor Production Queue (Active Runs)"
        subtitle="Priority orders currently on press or queued for operator setup"
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
                    <p class="text-[11px] text-cyber-muted mt-0.5">Staff, operators, and customer registrations</p>
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
                            @php
                                $roleBadge = match($u->role) {
                                    'super_admin', 'admin' => 'bg-slate-800 text-sky-400 border-slate-700 dark:bg-slate-800 dark:text-sky-300 dark:border-slate-700',
                                    'owner', 'management'  => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                    'manager'              => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
                                    'production_officer', 'production' => 'bg-slate-200/70 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700',
                                    'designer'             => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20',
                                    'inventory'            => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                                    default                => 'bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                };
                            @endphp
                            <tr class="hover:bg-cyber-hover/50 transition">
                                <td class="px-4 sm:px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-xl bg-slate-800 text-sky-300 border border-slate-700 font-bold flex items-center justify-center text-xs shrink-0 shadow-sm font-display">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-cyber-main block truncate max-w-[170px]">{{ $u->name }}</span>
                                            <span class="text-[10px] text-cyber-muted block truncate max-w-[170px] font-mono">{{ $u->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border {{ $roleBadge }} font-mono">
                                        {{ $u->role_label }}
                                    </span>
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
        </div>

        {{-- RIGHT 1 COL: SYSTEM AUDIT TRAIL --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
                <div>
                    <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">System Audit Trail</h3>
                    <p class="text-[11px] text-cyber-muted mt-0.5">Critical administrative and operator logs</p>
                </div>
                <a href="{{ route('management.audit-logs.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                    Full Log <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-4 sm:p-5 flex-1 divide-y divide-cyber/60 text-xs">
                @forelse($recentAuditLogs as $log)
                    <div class="py-3 first:pt-0 last:pb-0 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-cyber-main text-xs truncate max-w-[170px]">{{ $log->action ?? 'System Event' }}</span>
                            <span class="text-[10px] text-cyber-sub font-mono shrink-0">{{ $log->created_at ? $log->created_at->diffForHumans() : 'Recently' }}</span>
                        </div>
                        <p class="text-[11px] text-cyber-muted line-clamp-2 leading-relaxed">{{ $log->description ?? 'Action performed in system.' }}</p>
                        @if($log->user)
                            <span class="text-[10px] text-cyan-400 font-medium block">by {{ $log->user->name }}</span>
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
