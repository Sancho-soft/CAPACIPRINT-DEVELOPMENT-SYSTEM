@extends('layouts.internal')
@section('title', 'Executive Orders & Sales Report')
@section('page-title', 'Financial & Orders Audit Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Orders &amp; Financial Audit Report</h2>
                <p class="text-xs sm:text-sm text-slate-300">Detailed transaction history, revenue logs, and order lifecycle fulfillment audit.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition">
                <i class="fa-solid fa-file-csv text-sm"></i> Export to CSV
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('management.reports.orders') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 text-xs">
            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block font-bold text-slate-600 mb-1">Branch</label>
                <select name="branch" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->name }}" {{ request('branch') === $b->name ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-600 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-navy-800 text-white rounded-xl font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['status', 'branch', 'from_date', 'to_date']))
                    <a href="{{ route('management.reports.orders') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Report Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $o)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5 font-bold text-navy-900 font-display">#{{ $o->order_number }}</td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ $o->user->name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $o->user->email ?? '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-navy-900">{{ $o->printRequest->service ?? 'Print Service' }}</span>
                                <div class="text-[10px] text-slate-400">{{ $o->printRequest->quantity ?? 1 }} copies</div>
                            </td>
                            <td class="py-4 px-4 font-bold text-emerald-700">
                                ₱{{ number_format($o->quotation->total_price ?? 0, 2) }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-700">
                                {{ $o->assigned_branch ?? 'Unassigned' }}
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $o->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $o->status_badge_class }}">
                                    {{ $o->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-file-invoice text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm">No orders found for the selected criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
