@extends('layouts.internal')
@section('title', 'Executive Management Dashboard')
@section('page-title', 'Executive Management Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Executive Management Dashboard</h2>
            <p class="text-xs text-slate-400 mt-1">High-level enterprise overview of branch operations, order fulfillment, and network performance.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('management.reports.index') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-xs"></i> Executive Reports
            </a>
        </div>
    </div>

    {{-- Executive Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-cyan-400 p-5 flex items-center justify-between shadow-none hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-folder-tree text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-cyan-400 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">TOTAL ORDERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $totalOrders }}</div>
            </div>
        </div>

        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-blue-400 p-5 flex items-center justify-between shadow-none hover:border-blue-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-industry text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-blue-400 transition-all"></i>
                <div class="text-[11px] font-black text-blue-400 uppercase tracking-wider leading-tight max-w-[110px]">IN PRODUCTION</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $inProduction }}</div>
            </div>
        </div>

        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-emerald-400 p-5 flex items-center justify-between shadow-none hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-circle-check text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-emerald-400 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">COMPLETED / CLAIMED</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $completedOrders }}</div>
            </div>
        </div>

        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 border-l-4 border-l-amber-400 p-5 flex items-center justify-between shadow-none hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-boxes-stacked text-2xl text-slate-400 shrink-0 group-hover:scale-110 group-hover:text-amber-400 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">INVENTORY WARNINGS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-slate-900 dark:text-white font-display">{{ $lowStockCount }}</div>
            </div>
        </div>
    </div>

    {{-- Branch Performance Overview --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-4">
            <div>
                <h3 class="font-black text-white text-base">Multi-Branch Workload Performance</h3>
                <p class="text-xs text-slate-400 mt-0.5">Real-time load balancing across all connected branch nodes.</p>
            </div>
            <a href="{{ route('management.branches.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                Full Network <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
            @foreach($branches as $b)
            <div class="p-4 rounded-2xl border border-slate-800 bg-[#0D1520] space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <h4 class="font-black text-white text-sm">{{ $b->name }}</h4>
                    <span class="text-[10px] font-black text-cyan-400 uppercase bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/20">{{ $b->location }}</span>
                </div>
                <div class="flex justify-between text-slate-400 font-medium"><span>Active Production Load:</span><strong class="text-white font-mono">{{ $b->active_jobs }} jobs</strong></div>
                <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden mt-1">
                    <div class="h-full bg-gradient-to-r from-cyan-400 to-emerald-400 rounded-full" style="width: {{ $b->workload_percent }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Orders Monitor --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
            <div>
                <h3 class="font-black text-white text-base">Recent Customer Orders</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Live transaction tracking and branch routing</p>
            </div>
            <a href="{{ route('management.orders.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                    <tr>
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Service</th>
                        <th class="px-5 py-3.5">Assigned Branch</th>
                        <th class="px-5 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($recentOrders as $ord)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3.5 font-bold text-white font-mono">#{{ $ord->order_number }}</td>
                        <td class="px-5 py-3.5 text-slate-300 font-medium">{{ $ord->user->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-slate-400">{{ $ord->printRequest->service ?? '—' }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyan-400">{{ $ord->assigned_branch ?? 'Pending' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ match($ord->status) { 'claimed'=>'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'completed'=>'bg-emerald-500/15 text-emerald-400 border-emerald-500/30', 'ready_for_pickup'=>'bg-teal-500/15 text-teal-400 border-teal-500/30', 'production'=>'bg-cyan-500/15 text-cyan-400 border-cyan-500/30', default=>'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' } }}">
                                {{ $ord->status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

