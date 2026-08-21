@extends('layouts.internal')
@section('title', 'Admin Dashboard')
@section('page-title', 'System Administrator Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-brand-500 text-white flex items-center justify-center text-3xl shadow-lg shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-display">System Administration Portal</h2>
                <p class="text-xs sm:text-sm text-slate-300">Centralized control for user accounts, branch topologies, role-based permissions, and audit monitoring.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                <i class="fa-solid fa-users-gear"></i> Manage Users
            </a>
        </div>
    </div>

    {{-- Quick Metric Cards --}}
    @php
        $totalUsers = \App\Models\User::count();
        $totalBranches = \App\Models\Branch::count();
        $totalOrders = \App\Models\Order::count();
        $totalRequests = \App\Models\PrintRequest::count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $totalUsers }}</div>
                <div class="text-xs text-slate-400 font-medium">Registered Users</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-network-wired"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $totalBranches }}</div>
                <div class="text-xs text-slate-400 font-medium">Active Branches</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $totalRequests }}</div>
                <div class="text-xs text-slate-400 font-medium">Print Requests</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $totalOrders }}</div>
                <div class="text-xs text-slate-400 font-medium">Completed Orders</div>
            </div>
        </div>
    </div>

    {{-- Role Switching Sandbox for Presentation/Testing --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Fast Role Simulator</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <a href="{{ route('demo.switch-role', 'staff') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2 group">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 group-hover:underline">Simulate <i class="fa-solid fa-arrow-right ml-1"></i></span>
                </div>
                <h4 class="font-bold text-navy-900 font-display">Sales &amp; Customer Service</h4>
                <p class="text-xs text-slate-500">Manage customer print requests, generate quotations, confirm payments, and process claims.</p>
            </a>

            <a href="{{ route('demo.switch-role', 'manager') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2 group">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 group-hover:underline">Simulate <i class="fa-solid fa-arrow-right ml-1"></i></span>
                </div>
                <h4 class="font-bold text-navy-900 font-display">Branch Manager</h4>
                <p class="text-xs text-slate-500">Multi-factor capacity evaluation, shop-floor workload balancing, and production planning.</p>
            </a>

            <a href="{{ route('demo.switch-role', 'management') }}" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2 group">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <span class="text-xs font-bold text-purple-600 group-hover:underline">Simulate <i class="fa-solid fa-arrow-right ml-1"></i></span>
                </div>
                <h4 class="font-bold text-navy-900 font-display">Owner / Executive</h4>
                <p class="text-xs text-slate-500">Access high-level company analytics, multi-branch revenue reports, and capacity metrics.</p>
            </a>
        </div>
    </div>
</div>
@endsection
