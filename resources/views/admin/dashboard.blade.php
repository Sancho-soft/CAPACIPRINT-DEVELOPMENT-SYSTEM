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
        <a href="{{ route('admin.users.index') }}" class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-none hover:border-cyan-500/50 hover:bg-[#15202D] transition-all group cursor-pointer block">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-users text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">REGISTERED USERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalUsers }}</div>
            </div>
        </a>

        {{-- Card 2: Active Branches (Indigo/Purple) --}}
        <a href="{{ route('management.branches.index') }}" class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-indigo-400 p-5 flex items-center justify-between shadow-none hover:border-indigo-500/50 hover:bg-[#15202D] transition-all group cursor-pointer block">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-store text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-indigo-400 transition-all"></i>
                <div class="text-[11px] font-black text-indigo-400 uppercase tracking-wider leading-tight max-w-[110px]">ACTIVE BRANCHES</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalBranches }}</div>
            </div>
        </a>

        {{-- Card 3: Print Requests (Amber) --}}
        <a href="{{ route('staff.print-requests.index') }}" class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-none hover:border-amber-500/50 hover:bg-[#15202D] transition-all group cursor-pointer block">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-file-lines text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">PRINT REQUESTS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalRequests }}</div>
            </div>
        </a>

        {{-- Card 4: Total Orders (Emerald) --}}
        <a href="{{ route('management.orders.index') }}" class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-none hover:border-emerald-500/50 hover:bg-[#15202D] transition-all group cursor-pointer block">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-bag-shopping text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">TOTAL ORDERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalOrders }}</div>
            </div>
        </a>
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
                            <td class="px-5 py-3.5 font-bold text-white truncate max-w-[180px]">{{ $u->name }}</td>
                            <td class="px-5 py-3.5 text-slate-400 text-xs truncate max-w-[180px]">{{ $u->email }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-300 uppercase text-xs">{{ $roleConfig['label'] }}</td>
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
            @if($recentUsers->hasPages())
            <div class="px-6 py-4 border-t border-slate-800/80 bg-[#0D1520] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <p class="text-slate-400 font-medium">
                    Showing <span class="font-bold text-white font-mono">{{ $recentUsers->firstItem() }}</span> to <span class="font-bold text-white font-mono">{{ $recentUsers->lastItem() }}</span> of <span class="font-bold text-cyan-400 font-mono">{{ $recentUsers->total() }}</span> accounts
                </p>
                <div class="flex items-center gap-1.5 font-bold">
                    {{-- Previous Page --}}
                    @if($recentUsers->onFirstPage())
                        <span class="px-3 py-1.5 rounded-xl bg-slate-800/40 border border-slate-800/60 text-slate-600 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </span>
                    @else
                        <a href="{{ $recentUsers->previousPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-[#111A24] hover:bg-cyan-500/20 border border-slate-700 hover:border-cyan-500/50 text-slate-300 hover:text-cyan-400 transition">
                            <i class="fa-solid fa-chevron-left text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($recentUsers->getUrlRange(1, $recentUsers->lastPage()) as $page => $url)
                        @if($page == $recentUsers->currentPage())
                            <span class="px-3.5 py-1.5 rounded-xl bg-cyan-500 text-slate-950 font-black border border-cyan-400 shadow-[0_0_12px_rgba(6,182,212,0.35)]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-1.5 rounded-xl bg-[#111A24] hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-white transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if($recentUsers->hasMorePages())
                        <a href="{{ $recentUsers->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-[#111A24] hover:bg-cyan-500/20 border border-slate-700 hover:border-cyan-500/50 text-slate-300 hover:text-cyan-400 transition">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-xl bg-slate-800/40 border border-slate-800/60 text-slate-600 cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
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

            {{-- Employee Task Assignments Card --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="h-7 w-7 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-xs shadow-sm">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <h4 class="font-black text-white text-sm tracking-tight">Task Assignments</h4>
                    </div>
                    <a href="{{ route('admin.employees.index') }}" class="text-[11px] font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="space-y-2.5 text-xs">
                    @forelse($activeAssignments as $job)
                    <div class="p-3 rounded-2xl bg-[#0D1520] border border-slate-800/80 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                        <div class="space-y-0.5 min-w-0">
                            <div class="font-bold text-white text-xs truncate">{{ $job->job_number }}</div>
                            <div class="text-[11px] text-slate-400 truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-user text-[10px] text-cyan-400"></i>
                                {{ $job->assignedTo->name ?? 'Unassigned' }}
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">
                                {{ $job->status_label }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <!-- Employee Assignment Items -->
                    <div class="p-3 rounded-2xl bg-[#0D1520] border border-slate-800/80 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                        <div class="space-y-0.5 min-w-0">
                            <div class="font-bold text-white text-xs truncate">Offset Printing • Batch A</div>
                            <div class="text-[11px] text-slate-400 truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-user-gear text-[10px] text-cyan-400"></i>
                                Alex Planner (Officer)
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-cyan-500/15 text-cyan-400 border border-cyan-500/30">
                            In Production
                        </span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#0D1520] border border-slate-800/80 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                        <div class="space-y-0.5 min-w-0">
                            <div class="font-bold text-white text-xs truncate">Banner Layout Prep</div>
                            <div class="text-[11px] text-slate-400 truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-palette text-[10px] text-purple-400"></i>
                                Rafael Creative (Designer)
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-purple-500/15 text-purple-400 border border-purple-500/30">
                            Designing
                        </span>
                    </div>

                    <div class="p-3 rounded-2xl bg-[#0D1520] border border-slate-800/80 flex items-center justify-between gap-3 hover:border-slate-700 transition">
                        <div class="space-y-0.5 min-w-0">
                            <div class="font-bold text-white text-xs truncate">Large Format Printing</div>
                            <div class="text-[11px] text-slate-400 truncate flex items-center gap-1.5">
                                <i class="fa-solid fa-print text-[10px] text-emerald-400"></i>
                                Pedro Operator (Operator)
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                            Preparing
                        </span>
                    </div>
                    @endforelse
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
                <span class="text-[10px] text-cyan-400 font-bold mt-2 block">{{ $totalUsers }} Users →</span>
            </a>

            <a href="{{ route('management.branches.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-indigo-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Branch Registry</h4>
                <span class="text-[10px] text-indigo-400 font-bold mt-2 block">{{ $totalBranches }} Branches →</span>
            </a>

            <a href="{{ route('admin.employees.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-emerald-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Employee Registry</h4>
                <span class="text-[10px] text-emerald-400 font-bold mt-2 block">View Employees →</span>
            </a>

            <a href="{{ route('management.audit-logs.index') }}" class="bg-[#111A24] border border-slate-800/80 rounded-2xl p-5 shadow-lg hover:border-purple-500/40 transition group block">
                <div class="h-11 w-11 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 class="font-black text-white text-sm font-display">Audit Trail</h4>
                <span class="text-[10px] text-purple-400 font-bold mt-2 block">View Logs →</span>
            </a>
        </div>
    </div>

</div>
@endsection

