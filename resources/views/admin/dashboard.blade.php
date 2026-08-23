@extends('layouts.internal')
@section('title', 'Super Admin — System Control Center')
@section('page-title', 'System Control Center')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HERO BANNER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-gradient-to-r from-navy-950 via-navy-900 to-brand-900 rounded-3xl p-7 text-white shadow-2xl overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-400 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-400 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        </div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="h-16 w-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-sm text-white flex items-center justify-center text-3xl shadow-lg shrink-0">
                    <i class="fa-solid fa-shield-halved text-brand-300"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-lg bg-brand-500/30 border border-brand-400/30 text-brand-200 text-[10px] font-extrabold uppercase tracking-widest">Root Access</span>
                        <span class="px-2.5 py-0.5 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-extrabold uppercase tracking-widest"><i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i>System Online</span>
                    </div>
                    <h2 class="text-2xl font-black font-display tracking-tight">Super Admin Control Center</h2>
                    <p class="text-xs text-slate-300 mt-0.5">Full system authority — users, roles, branches, machines, audit trails, and disaster recovery.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-400 text-white font-bold text-xs shadow-lg shadow-brand-500/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-users-gear"></i> Manage Users
                </a>
                <a href="{{ route('admin.branches.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs transition flex items-center gap-2">
                    <i class="fa-solid fa-network-wired"></i> Manage Branches
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SYSTEM-WIDE KPI METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition">
            <div class="h-13 w-13 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="text-3xl font-black text-navy-900 font-display leading-none">{{ $totalUsers }}</div>
                <div class="text-[11px] text-slate-400 font-semibold mt-1">Registered Users</div>
                <div class="text-[10px] text-indigo-500 font-bold mt-0.5">All 9 Roles</div>
            </div>
        </div>

        {{-- Active Branches --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition">
            <div class="h-13 w-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <div class="text-3xl font-black text-navy-900 font-display leading-none">{{ $totalBranches }}</div>
                <div class="text-[11px] text-slate-400 font-semibold mt-1">Active Branches</div>
                <div class="text-[10px] text-blue-500 font-bold mt-0.5">Network Nodes</div>
            </div>
        </div>

        {{-- Print Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition">
            <div class="h-13 w-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <div class="text-3xl font-black text-navy-900 font-display leading-none">{{ $totalRequests }}</div>
                <div class="text-[11px] text-slate-400 font-semibold mt-1">Print Requests</div>
                <div class="text-[10px] text-emerald-500 font-bold mt-0.5">{{ $pendingRequests }} Pending</div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center gap-4 group hover:shadow-md transition">
            <div class="h-13 w-13 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <div class="text-3xl font-black text-navy-900 font-display leading-none">{{ $totalOrders }}</div>
                <div class="text-[11px] text-slate-400 font-semibold mt-1">Total Orders</div>
                <div class="text-[10px] text-purple-500 font-bold mt-0.5">{{ $activeJobs }} In Production</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT: Left (Role breakdown + Recent Users) + Right (Production Status + Actions) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- LEFT: Recent User Accounts (spans 2 cols) --}}
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <div>
                    <h3 class="font-bold text-navy-900 text-sm">Recent User Accounts</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Latest registered system users across all roles</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                    Manage All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">#</th>
                            <th class="px-5 py-3">Name & Email</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentUsers as $u)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono text-slate-300 text-[10px]">{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-7 w-7 rounded-full bg-gradient-to-br from-brand-400 to-indigo-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-navy-900 truncate max-w-[140px]">{{ $u->name }}</p>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[140px]">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-red-100 text-red-700',
                                        'owner' => 'bg-purple-100 text-purple-700',
                                        'management' => 'bg-purple-100 text-purple-700',
                                        'admin' => 'bg-orange-100 text-orange-700',
                                        'manager' => 'bg-blue-100 text-blue-700',
                                        'production_officer' => 'bg-cyan-100 text-cyan-700',
                                        'staff' => 'bg-emerald-100 text-emerald-700',
                                        'designer' => 'bg-indigo-100 text-indigo-700',
                                        'production' => 'bg-yellow-100 text-yellow-700',
                                        'inventory' => 'bg-teal-100 text-teal-700',
                                        'customer' => 'bg-slate-100 text-slate-600',
                                    ];
                                    $color = $roleColors[$u->role] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $color }}">
                                    {{ $u->role_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">{{ $u->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-slate-400">No users registered yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: Quick Stats & Actions --}}
        <div class="space-y-4">

            {{-- Production Health --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-5">
                <h4 class="font-bold text-navy-900 text-sm mb-4">Production Health</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 inline-block"></span> Active Jobs
                        </div>
                        <span class="font-black text-emerald-600 text-sm">{{ $activeJobs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-red-400 inline-block"></span> Delayed Jobs
                        </div>
                        <span class="font-black text-red-500 text-sm">{{ $delayedJobs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-amber-400 inline-block"></span> Pending Requests
                        </div>
                        <span class="font-black text-amber-600 text-sm">{{ $pendingRequests }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-blue-400 inline-block"></span> In Production
                        </div>
                        <span class="font-black text-blue-600 text-sm">{{ $inProductionRequests }}</span>
                    </div>
                </div>
            </div>

            {{-- Role Distribution --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-5">
                <h4 class="font-bold text-navy-900 text-sm mb-4">Role Distribution</h4>
                <div class="space-y-2">
                    @php
                        $roleMeta = [
                            'super_admin'       => ['label' => 'Super Admin',  'color' => 'bg-red-500'],
                            'admin'             => ['label' => 'System Admin',  'color' => 'bg-orange-500'],
                            'owner'             => ['label' => 'Owner',         'color' => 'bg-purple-500'],
                            'management'        => ['label' => 'Management',    'color' => 'bg-purple-400'],
                            'manager'           => ['label' => 'Branch Mgr',    'color' => 'bg-blue-500'],
                            'production_officer'=> ['label' => 'Prod. Officer', 'color' => 'bg-cyan-500'],
                            'staff'             => ['label' => 'CS Staff',      'color' => 'bg-emerald-500'],
                            'designer'          => ['label' => 'Designer',      'color' => 'bg-indigo-500'],
                            'production'        => ['label' => 'Operator',      'color' => 'bg-yellow-500'],
                            'inventory'         => ['label' => 'Inventory',     'color' => 'bg-teal-500'],
                            'customer'          => ['label' => 'Customer',      'color' => 'bg-slate-400'],
                        ];
                        $maxCount = max(array_values($roleBreakdown) ?: [1]);
                    @endphp
                    @foreach($roleMeta as $role => $meta)
                        @if(isset($roleBreakdown[$role]) && $roleBreakdown[$role] > 0)
                        <div>
                            <div class="flex justify-between text-[10px] font-semibold mb-0.5">
                                <span class="text-slate-500">{{ $meta['label'] }}</span>
                                <span class="text-navy-900 font-bold">{{ $roleBreakdown[$role] }}</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="{{ $meta['color'] }} h-full rounded-full transition-all" style="width: {{ round(($roleBreakdown[$role] / $maxCount) * 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- QUICK ACTION MODULES --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div>
        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Quick Access Modules</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-indigo-200 transition group block">
                <div class="h-11 w-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <h4 class="font-bold text-navy-900 text-sm font-display">User Management</h4>
                <p class="text-[11px] text-slate-400 mt-1">Create accounts, assign roles, reset credentials</p>
                <span class="text-[10px] text-indigo-600 font-bold mt-2 block">{{ $totalUsers }} Users →</span>
            </a>

            <a href="{{ route('admin.branches.index') }}" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition group block">
                <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h4 class="font-bold text-navy-900 text-sm font-display">Branch Registry</h4>
                <p class="text-[11px] text-slate-400 mt-1">Register branches, machines, and capacity limits</p>
                <span class="text-[10px] text-blue-600 font-bold mt-2 block">{{ $totalBranches }} Branches →</span>
            </a>

            <a href="{{ route('admin.employees.index') }}" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-emerald-200 transition group block">
                <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <h4 class="font-bold text-navy-900 text-sm font-display">Employee Registry</h4>
                <p class="text-[11px] text-slate-400 mt-1">Track staff assignments and availability status</p>
                <span class="text-[10px] text-emerald-600 font-bold mt-2 block">View Employees →</span>
            </a>

            <a href="{{ route('management.audit-logs.index') }}" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-purple-200 transition group block">
                <div class="h-11 w-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-scroll"></i>
                </div>
                <h4 class="font-bold text-navy-900 text-sm font-display">Audit Trail</h4>
                <p class="text-[11px] text-slate-400 mt-1">Monitor all system changes and access events</p>
                <span class="text-[10px] text-purple-600 font-bold mt-2 block">View Logs →</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- RECENT AUDIT LOGS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($recentAuditLogs->count() > 0)
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div>
                <h3 class="font-bold text-navy-900 text-sm">Recent Audit Activity</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Last 6 system actions recorded</p>
            </div>
            <a href="{{ route('management.audit-logs.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                Full Log <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($recentAuditLogs as $log)
            <div class="px-6 py-3.5 flex items-start gap-3 hover:bg-slate-50/70 transition">
                <div class="h-7 w-7 rounded-lg bg-navy-100 text-navy-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-navy-900">{{ $log->action ?? 'System Action' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $log->description ?? '' }}</p>
                </div>
                <span class="text-[10px] text-slate-400 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
