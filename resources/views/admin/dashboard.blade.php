@extends('layouts.internal')
@section('title', 'Super Admin — System Control Center')
@section('page-title', 'System Control Center')

@section('content')
<div class="space-y-6 w-full">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- HERO CONTROL CENTER BANNER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        {{-- Ambient cyan glow in background --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            {{-- Left Title & Icon --}}
            <div class="flex items-center gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-[0_0_25px_rgba(6,182,212,0.25)] shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-white">Super Admin Control Center</h2>
                    <p class="text-xs text-slate-400 mt-1">Full system authority — users, roles, branches, machines, audit trails, and capacity routing.</p>
                </div>
            </div>

            {{-- Right Controls: Quick Navigation Actions --}}
            <div class="flex flex-wrap items-center gap-3 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-xs"></i> Manage Users
                </a>
                <a href="{{ route('management.branches.index') }}" class="px-4 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-network-wired text-xs text-cyan-400"></i> Manage Branches
                </a>
                <a href="{{ route('management.audit-logs.index') }}" class="px-4 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-xs text-purple-400"></i> Audit Logs
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 SYSTEM-WIDE KPI METRICS WITH COLORED ACCENTS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Registered Users (Cyan) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="min-w-0">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">REGISTERED USERS</div>
                <div class="text-3xl font-black text-white font-display mt-1">{{ $totalUsers }}</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-1">All 9 roles</div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        {{-- Card 2: Active Branches (Indigo/Purple) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-indigo-400 p-5 flex items-center justify-between shadow-lg hover:border-indigo-500/30 transition group">
            <div class="min-w-0">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">ACTIVE BRANCHES</div>
                <div class="text-3xl font-black text-white font-display mt-1">{{ $totalBranches }}</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-1">Network nodes</div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-store"></i>
            </div>
        </div>

        {{-- Card 3: Print Requests (Amber) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="min-w-0">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PRINT REQUESTS</div>
                <div class="text-3xl font-black text-white font-display mt-1">{{ $totalRequests }}</div>
                <div class="text-[11px] text-amber-400 font-semibold mt-1">{{ $pendingRequests }} pending review</div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-lines"></i>
            </div>
        </div>

        {{-- Card 4: Total Orders (Emerald) --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="min-w-0">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL ORDERS</div>
                <div class="text-3xl font-black text-white font-display mt-1">{{ $totalOrders }}</div>
                <div class="text-[11px] text-emerald-400 font-semibold mt-1">{{ $activeJobs }} in production</div>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT: Left (Recent Users Table) + Right (Health & Role Distribution) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Recent User Accounts (spans 2 cols) --}}
        <div class="lg:col-span-2 bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
                <div>
                    <h3 class="font-black text-white text-sm">Recent User Accounts</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Latest registered users across all roles</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                    Manage All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                        <tr>
                            <th class="px-5 py-3.5">ID</th>
                            <th class="px-5 py-3.5">NAME</th>
                            <th class="px-5 py-3.5">EMAIL</th>
                            <th class="px-5 py-3.5">ROLE</th>
                            <th class="px-5 py-3.5">JOINED</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($recentUsers as $u)
                        @php
                            $roleConfig = match($u->role) {
                                'super_admin'        => ['badge' => 'bg-red-500/15 text-red-400 border-red-500/30', 'avatar' => 'from-red-500 to-rose-700', 'label' => 'SUPER ADMIN'],
                                'admin'              => ['badge' => 'bg-orange-500/15 text-orange-400 border-orange-500/30', 'avatar' => 'from-orange-500 to-amber-700', 'label' => 'SYSTEM ADMIN'],
                                'owner', 'management'=> ['badge' => 'bg-amber-500/15 text-amber-400 border-amber-500/30', 'avatar' => 'from-amber-500 to-yellow-600', 'label' => 'OWNER (EXECUTIVE)'],
                                'manager'            => ['badge' => 'bg-blue-500/15 text-blue-400 border-blue-500/30', 'avatar' => 'from-blue-500 to-indigo-700', 'label' => 'BRANCH MANAGER'],
                                'production_officer' => ['badge' => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30', 'avatar' => 'from-cyan-400 to-teal-600', 'label' => 'PRODUCTION OFFICER'],
                                'staff'              => ['badge' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'avatar' => 'from-emerald-500 to-teal-700', 'label' => 'CS STAFF'],
                                'designer'           => ['badge' => 'bg-purple-500/15 text-purple-400 border-purple-500/30', 'avatar' => 'from-purple-500 to-pink-600', 'label' => 'LAYOUT DESIGNER'],
                                'production'         => ['badge' => 'bg-sky-500/15 text-sky-400 border-sky-500/30', 'avatar' => 'from-sky-400 to-blue-600', 'label' => 'PRODUCTION OPERATOR'],
                                'inventory'          => ['badge' => 'bg-teal-500/15 text-teal-400 border-teal-500/30', 'avatar' => 'from-teal-400 to-emerald-600', 'label' => 'INVENTORY STAFF'],
                                default              => ['badge' => 'bg-slate-500/15 text-slate-400 border-slate-500/30', 'avatar' => 'from-slate-500 to-slate-700', 'label' => 'CUSTOMER'],
                            };
                            $initials = collect(explode(' ', $u->name))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('');
                        @endphp
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-5 py-3.5 font-mono text-slate-500 font-bold text-xs">{{ $u->id }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br {{ $roleConfig['avatar'] }} text-white flex items-center justify-center text-[10px] font-black shrink-0 shadow-sm border border-white/10">
                                        {{ $initials }}
                                    </div>
                                    <p class="font-bold text-white truncate max-w-[160px]">{{ $u->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400 text-xs truncate max-w-[180px]">{{ $u->email }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $roleConfig['badge'] }}">
                                    {{ $roleConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">{{ $u->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">No users registered yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: Production Health & Role Distribution --}}
        <div class="space-y-6">

            {{-- Production Health Card --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl">
                <h4 class="font-black text-white text-sm mb-4 tracking-tight">Production Health</h4>
                <div class="space-y-3.5 text-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-slate-300 font-medium">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] inline-block"></span> Active Jobs
                        </div>
                        <span class="font-black text-emerald-400 text-sm font-mono">{{ $activeJobs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-slate-300 font-medium">
                            <span class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)] inline-block"></span> Delayed Jobs
                        </div>
                        <span class="font-black text-red-500 text-sm font-mono">{{ $delayedJobs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-slate-300 font-medium">
                            <span class="h-2 w-2 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)] inline-block"></span> Pending Requests
                        </div>
                        <span class="font-black text-amber-400 text-sm font-mono">{{ $pendingRequests }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 text-slate-300 font-medium">
                            <span class="h-2 w-2 rounded-full bg-cyan-400 shadow-[0_0_8px_rgba(34,211,238,0.8)] inline-block"></span> In Production
                        </div>
                        <span class="font-black text-cyan-400 text-sm font-mono">{{ $inProductionRequests }}</span>
                    </div>
                </div>
            </div>

            {{-- Role Distribution Donut Chart & Legend --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl">
                <h4 class="font-black text-white text-sm mb-4 tracking-tight">Role Distribution</h4>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    {{-- Chart Canvas --}}
                    <div class="relative h-32 w-32 shrink-0 flex items-center justify-center">
                        <canvas id="roleDonutChart" class="max-h-full max-w-full"></canvas>
                    </div>

                    {{-- Legend with Exact Numbers --}}
                    <div class="flex-1 w-full space-y-1.5 text-[11px]">
                        @php
                            $roleMeta = [
                                'super_admin'        => ['label' => 'Super Admin',  'color' => '#EF4444'],
                                'admin'              => ['label' => 'System Admin', 'color' => '#F97316'],
                                'owner'              => ['label' => 'Owner',        'color' => '#F59E0B'],
                                'manager'            => ['label' => 'Branch Mgr',   'color' => '#3B82F6'],
                                'production_officer' => ['label' => 'Prod. Officer','color' => '#06B6D4'],
                                'staff'              => ['label' => 'CS Staff',     'color' => '#10B981'],
                                'designer'           => ['label' => 'Designer',     'color' => '#A855F7'],
                                'production'         => ['label' => 'Operator',     'color' => '#0EA5E9'],
                                'inventory'          => ['label' => 'Inventory',    'color' => '#14B8A6'],
                                'customer'           => ['label' => 'Customer',     'color' => '#64748B'],
                            ];
                        @endphp
                        @foreach($roleMeta as $rk => $rm)
                            @php $cnt = $roleBreakdown[$rk] ?? ($rk === 'owner' ? ($roleBreakdown['owner'] ?? 0) + ($roleBreakdown['management'] ?? 0) : 0); @endphp
                            @if($cnt > 0)
                            <div class="flex items-center justify-between text-slate-300">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full shrink-0" style="background-color: {{ $rm['color'] }}"></span>
                                    <span class="truncate">{{ $rm['label'] }}</span>
                                </div>
                                <span class="font-black text-white font-mono ml-2">{{ $cnt }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- QUICK ACCESS MODULES --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div>
        <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">Quick Access Modules</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-cyan-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">User Management</h4>
                <p class="text-[11px] text-slate-400 mt-1">Create accounts, assign roles, reset credentials</p>
                <span class="text-[10px] text-cyan-400 font-bold mt-2 block">{{ $totalUsers }} Users →</span>
            </a>

            <a href="{{ route('management.branches.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-indigo-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Branch Registry</h4>
                <p class="text-[11px] text-slate-400 mt-1">Register branches, machines, and capacity limits</p>
                <span class="text-[10px] text-indigo-400 font-bold mt-2 block">{{ $totalBranches }} Branches →</span>
            </a>

            <a href="{{ route('admin.employees.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-emerald-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Employee Registry</h4>
                <p class="text-[11px] text-slate-400 mt-1">Track staff assignments and availability status</p>
                <span class="text-[10px] text-emerald-400 font-bold mt-2 block">View Employees →</span>
            </a>

            <a href="{{ route('management.audit-logs.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-purple-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Audit Trail</h4>
                <p class="text-[11px] text-slate-400 mt-1">Monitor all system changes and access events</p>
                <span class="text-[10px] text-purple-400 font-bold mt-2 block">View Logs →</span>
            </a>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('roleDonutChart');
    if (!ctx) return;

    @php
        $labels = [];
        $data = [];
        $colors = [];
        foreach($roleMeta as $rk => $rm) {
            $cnt = $roleBreakdown[$rk] ?? ($rk === 'owner' ? ($roleBreakdown['owner'] ?? 0) + ($roleBreakdown['management'] ?? 0) : 0);
            if ($cnt > 0) {
                $labels[] = $rm['label'];
                $data[] = $cnt;
                $colors[] = $rm['color'];
            }
        }
    @endphp

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                data: {!! json_encode($data) !!},
                backgroundColor: {!! json_encode($colors) !!},
                borderColor: '#111A24',
                borderWidth: 3,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0D1520',
                    titleColor: '#fff',
                    bodyColor: '#22d3ee',
                    borderColor: '#1E293B',
                    borderWidth: 1,
                    padding: 8,
                    displayColors: true
                }
            }
        }
    });
});
</script>
@endsection

