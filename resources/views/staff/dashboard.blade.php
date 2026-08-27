@extends('layouts.internal')
@section('title', 'Sales Staff Dashboard')
@section('page-title', 'Sales & Customer Service Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">New Requests</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $newRequestsCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Pending Quotations</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $pendingQuotesCount }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide leading-tight">Pending Payment Confirmations</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $pendingPaymentOrders->count() }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Active Orders</p>
                </div>
            </div>
            <h3 class="text-2xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $activeOrdersCount }}</h3>
        </div>
    </div>

    {{-- Pending Payment Confirmations --}}
    @if($pendingPaymentOrders->isNotEmpty())
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-amber-50/50 border-b border-amber-100 flex items-center justify-between">
            <div class="flex items-center gap-2 text-amber-900 font-bold text-sm">
                <i class="fa-solid fa-bell text-amber-600"></i> Orders Requiring Payment Verification
            </div>
            <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full">{{ $pendingPaymentOrders->count() }} Pending</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($pendingPaymentOrders as $ord)
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition text-sm">
                <div>
                    <span class="font-bold text-navy-900">Order #{{ $ord->order_number }}</span>
                    <span class="text-xs text-slate-500 ml-2">Customer: {{ $ord->user->name ?? '—' }}</span>
                    <p class="text-xs text-slate-400">Ref: {{ $ord->payment->payment_reference ?? 'N/A' }} &middot; Amount: ₱{{ number_format($ord->payment->amount ?? 0, 2) }}</p>
                </div>
                <form method="POST" action="{{ route('staff.orders.confirm-payment', $ord) }}">
                    @csrf
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-1.5 rounded-lg text-xs transition">
                        <i class="fa-solid fa-check mr-1"></i> Confirm Payment
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recent Customer Requests --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-navy-900">Recent Customer Requests</h3>
            <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">View All &rarr;</a>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Service</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Deadline</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentRequests as $req)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-3.5 font-bold text-navy-900">{{ $req->user->name ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-slate-700">{{ $req->service }}</td>
                    <td class="px-6 py-3.5 text-slate-600">{{ number_format($req->quantity) }} copies</td>
                    <td class="px-6 py-3.5 text-slate-500">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-3.5">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->status_badge_class }}">
                            {{ $req->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        <a href="{{ route('staff.print-requests.show', $req) }}" class="text-brand-600 font-bold hover:underline">Review &rarr;</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No print requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
