@extends('layouts.internal')
@section('title', 'Executive Management Dashboard')
@section('page-title', 'Executive Management Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Executive Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Total Orders</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-industry"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">In Production</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $inProduction }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Completed / Claimed</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $completedOrders }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Inventory Warnings</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $lowStockCount }}</h3>
        </div>
    </div>

    {{-- Branch Performance Overview --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Multi-Branch Workload Performance</h3>
            <a href="{{ route('management.branches.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">Full Branch Network &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($branches as $b)
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold text-navy-900 text-sm">{{ $b->name }}</h4>
                    <span class="text-[10px] font-bold text-brand-600 uppercase">{{ $b->location }}</span>
                </div>
                <div class="flex justify-between text-slate-600"><span>Active Production Load:</span><strong class="text-slate-900">{{ $b->active_jobs }} jobs</strong></div>
                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mt-1">
                    <div class="h-full bg-brand-500" style="width: {{ $b->workload_percent }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Orders Monitor --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Recent Customer Orders</h3>
            <a href="{{ route('management.orders.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">View All &rarr;</a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Order #</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Assigned Branch</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentOrders as $ord)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3.5 font-bold text-navy-900">#{{ $ord->order_number }}</td>
                    <td class="px-6 py-3.5 text-slate-700 font-semibold">{{ $ord->user->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-slate-800">{{ $ord->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-3.5 font-bold text-brand-600">{{ $ord->assigned_branch ?? 'Pending' }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ match($ord->status) { 'ready_for_pickup'=>'bg-emerald-100 text-emerald-800', 'production'=>'bg-cyan-100 text-cyan-800', default=>'bg-slate-100 text-slate-600' } }}">
                            {{ $ord->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
