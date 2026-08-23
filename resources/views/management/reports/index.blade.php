@extends('layouts.internal')
@section('title', 'Executive Reports & Analytics')
@section('page-title', 'Executive Reports & Analytics Hub')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-brand-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Executive Analytics &amp; Reports</h2>
                <p class="text-xs sm:text-sm text-slate-300">Generate, filter, and export detailed system logs, revenue summaries, production runtimes, and stock metrics.</p>
            </div>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $totalOrdersCount }}</div>
                <div class="text-xs text-slate-400 font-medium">Lifetime Orders</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-peso-sign"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-emerald-600 font-display">₱{{ number_format($totalRevenue, 2) }}</div>
                <div class="text-xs text-slate-400 font-medium">Total Quoted Revenue</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $completedOrdersCount }}</div>
                <div class="text-xs text-slate-400 font-medium">Fulfilled Orders</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-shop"></i>
            </div>
            <div>
                <div class="text-2xl font-bold text-navy-900 font-display">{{ $activeBranchesCount }}</div>
                <div class="text-xs text-slate-400 font-medium">Active Branches</div>
            </div>
        </div>
    </div>

    {{-- Report Categories Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('management.reports.orders') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-3 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600 group-hover:underline">Open Report &rarr;</span>
            </div>
            <div>
                <h3 class="font-bold text-navy-900 text-base font-display">Orders &amp; Financial Report</h3>
                <p class="text-xs text-slate-500 mt-1">Full audit of all customer orders, payment confirmations, date-range filters, and CSV export.</p>
            </div>
        </a>

        <a href="{{ route('management.reports.production') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-3 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <span class="text-xs font-bold text-amber-600 group-hover:underline">Open Report &rarr;</span>
            </div>
            <div>
                <h3 class="font-bold text-navy-900 text-base font-display">Production Output &amp; Efficiency</h3>
                <p class="text-xs text-slate-500 mt-1">Shop-floor efficiency, delay logs, technician allocations, machine runtimes, and CSV export.</p>
            </div>
        </a>

        <a href="{{ route('management.reports.inventory') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-3 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span class="text-xs font-bold text-teal-600 group-hover:underline">Open Report &rarr;</span>
            </div>
            <div>
                <h3 class="font-bold text-navy-900 text-base font-display">Inventory &amp; Material Valuation</h3>
                <p class="text-xs text-slate-500 mt-1">Branch material stock levels, reorder warnings, stock status indicators, and CSV export.</p>
            </div>
        </a>

        <a href="{{ route('management.reports.capacity') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition block space-y-3 group">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <span class="text-xs font-bold text-purple-600 group-hover:underline">Open Report &rarr;</span>
            </div>
            <div>
                <h3 class="font-bold text-navy-900 text-base font-display">Branch Capacity Analysis</h3>
                <p class="text-xs text-slate-500 mt-1">Multi-branch capacity score matrix, available machines, and operational bottlenecks.</p>
            </div>
        </a>
    </div>
</div>
@endsection
