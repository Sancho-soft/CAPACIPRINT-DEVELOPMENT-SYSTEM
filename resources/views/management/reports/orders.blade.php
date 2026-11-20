@extends('layouts.internal')
@section('title', 'Executive Orders & Sales Report')
@section('page-title', 'Financial & Orders Audit Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-cyber-card text-cyber-main border border-cyber p-6 sm:p-8 rounded-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display text-cyber-main">Orders &amp; Financial Audit Report</h2>
                <p class="text-xs sm:text-sm text-cyber-muted">Detailed transaction history, revenue logs, and order lifecycle fulfillment audit.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-file-csv text-sm"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm">
        <form method="GET" action="{{ route('management.reports.orders') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-3">
                <label class="block font-bold text-cyber-muted mb-1">Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-cyber-muted mb-1">Branch</label>
                <select name="branch" class="w-full px-3.5 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->name }}" {{ request('branch') === $b->name ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-cyber-muted mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-cyber-muted mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['status', 'branch', 'from_date', 'to_date']))
                    <a href="{{ route('management.reports.orders') }}" class="py-2 px-3 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-cyber-card rounded-2xl border border-cyber shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-muted tabular-nums">
                <thead class="bg-cyber-sub text-[11px] font-extrabold text-cyber-muted border-b border-cyber">
                    <tr>
                        <th class="py-3.5 px-5">Order #</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Service &amp; Qty</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Branch</th>
                        <th class="py-3.5 px-4">Date Placed</th>
                        <th class="py-3.5 px-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($orders as $o)
                        <tr class="hover:bg-cyber-sub/60 transition">
                            <td class="py-4 px-5 font-bold text-cyber-main font-display">#{{ $o->order_number }}</td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-cyber-main">{{ $o->user->name ?? '—' }}</div>
                                <div class="text-[11px] text-cyber-muted">{{ $o->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-cyber-main">{{ $o->printRequest->service ?? 'Print Service' }}</span>
                                <div class="text-[11px] text-cyber-muted">{{ $o->printRequest->quantity ?? 1 }} copies</div>
                            </td>
                            <td class="py-4 px-4 font-bold text-emerald-700">
                                ₱{{ number_format($o->quotation->total_price ?? 0, 2) }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-cyber-main">
                                {{ $o->assigned_branch ?? 'Unassigned' }}
                            </td>
                            <td class="py-4 px-4 text-cyber-muted">
                                {{ $o->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $o->status_badge_class }}">
                                    {{ $o->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-cyber-muted">
                                <i class="fa-solid fa-file-invoice text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">No orders found for the selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-cyber">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
