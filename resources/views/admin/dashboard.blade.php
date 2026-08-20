@extends('layouts.internal')
@section('title', 'Admin Dashboard')
@section('page-title', 'System Administrator Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-navy-900 text-white p-8 rounded-3xl shadow-xl space-y-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-brand-500 text-white flex items-center justify-center text-3xl shadow-lg">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-display">System Administration Portal</h2>
                <p class="text-xs text-slate-300">Manage user accounts, RBAC permissions, branch configurations, and system-wide audit logs.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('demo.switch-role', 'staff') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2">
            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold"><i class="fa-solid fa-users font-bold"></i></div>
            <h3 class="font-bold text-navy-900 font-display">Sales &amp; Service Staff</h3>
            <p class="text-xs text-slate-500">Manage customer print requests, generate quotations, confirm payments, and process QR claims.</p>
        </a>

        <a href="{{ route('demo.switch-role', 'manager') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2">
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold"><i class="fa-solid fa-calculator font-bold"></i></div>
            <h3 class="font-bold text-navy-900 font-display">Branch Manager</h3>
            <p class="text-xs text-slate-500">Evaluate multi-factor branch capacity algorithms, schedule machine workloads, and assign staff.</p>
        </a>

        <a href="{{ route('demo.switch-role', 'management') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-2">
            <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold"><i class="fa-solid fa-chart-line font-bold"></i></div>
            <h3 class="font-bold text-navy-900 font-display">Executive Management</h3>
            <p class="text-xs text-slate-500">Access executive dashboards, branch profitability metrics, and capacity bottleneck analysis.</p>
        </a>
    </div>
</div>
@endsection
