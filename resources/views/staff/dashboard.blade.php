@extends('layouts.internal')
@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Page Header --}}
    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">Dashboard Overview</h2>
        <p class="text-sm text-slate-500 mt-1">Customer service requests, quotation processing, and order updates.</p>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">New Requests</p>
                    <p class="text-[11px] text-blue-600 font-semibold mt-0.5 truncate">Awaiting review</p>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $newRequestsCount }}</h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Pending Quotes</p>
                    <p class="text-[11px] text-amber-600 font-semibold mt-0.5 truncate">Draft & sent</p>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $pendingQuotesCount }}</h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide leading-tight">Payment Verification</p>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-0.5 truncate">Pending approval</p>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $pendingPaymentOrders->count() }}</h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between group hover:shadow-md transition">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide truncate">Active Orders</p>
                    <p class="text-[11px] text-indigo-600 font-semibold mt-0.5 truncate">In workflow</p>
                </div>
            </div>
            <h3 class="text-3xl font-black text-navy-900 font-display shrink-0 ml-3">{{ $activeOrdersCount }}</h3>
        </div>
    </div>

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Pending Payment Confirmations Alert Card --}}
            @if($pendingPaymentOrders->isNotEmpty())
            <div class="bg-white border border-amber-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-amber-50/70 border-b border-amber-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-amber-900 font-bold text-sm">
                        <i class="fa-solid fa-bell text-amber-600 animate-bounce"></i> Orders Requiring Payment Verification
                    </div>
                    <span class="text-xs font-bold text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full">{{ $pendingPaymentOrders->count() }} Pending</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($pendingPaymentOrders as $ord)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition text-xs">
                        <div>
                            <span class="font-bold text-navy-900 text-sm">Order {{ $ord->order_number }}</span>
                            <span class="text-slate-500 ml-2">Customer: <strong class="text-slate-700">{{ $ord->user->name ?? '—' }}</strong></span>
                            <p class="text-slate-400 mt-0.5">Ref: {{ $ord->payment->payment_reference ?? 'N/A' }} &middot; Amount: <strong class="text-emerald-700">₱{{ number_format($ord->payment->amount ?? 0, 2) }}</strong></p>
                        </div>
                        <form method="POST" action="{{ route('staff.orders.confirm-payment', $ord) }}">
                            @csrf
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Confirm Payment
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recent Customer Requests Table --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-navy-900 text-base">Recent Customer Requests</h3>
                        <p class="text-xs text-slate-500">Latest incoming print inquiries requiring quotation or review</p>
                    </div>
                    <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1">
                        View All &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200/60">
                            <tr>
                                <th class="px-6 py-3.5">Customer</th>
                                <th class="px-6 py-3.5">Service</th>
                                <th class="px-6 py-3.5">Quantity</th>
                                <th class="px-6 py-3.5">Deadline</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentRequests as $req)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold text-navy-900">{{ $req->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ $req->service }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($req->quantity) }} pcs</td>
                                <td class="px-6 py-4 text-slate-500">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->status_badge_class }}">
                                        {{ $req->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('staff.print-requests.show', $req) }}"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                       title="Review Print Request Details">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-2xl text-slate-300 block mb-2"></i>
                                    No customer print requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Active Orders Overview --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="font-bold text-navy-900 text-base">Active Orders Tracker</h3>
                        <p class="text-xs text-slate-500">Real-time status of orders in production & fulfillment</p>
                    </div>
                    <a href="{{ route('staff.orders.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1">
                        All Orders &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200/60">
                            <tr>
                                <th class="px-6 py-3.5">Order No.</th>
                                <th class="px-6 py-3.5">Customer</th>
                                <th class="px-6 py-3.5">Service</th>
                                <th class="px-6 py-3.5">Total Amount</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-bold text-navy-900">{{ $order->order_number }}</td>
                                <td class="px-6 py-4 text-slate-700 font-medium">{{ $order->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $order->printRequest->service ?? 'Print Order' }}</td>
                                <td class="px-6 py-4 font-extrabold text-navy-900">₱{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('staff.orders.show', $order) }}"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                       title="View Order Details">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">No recent orders.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right Column: Quick Actions & Sidebar Widgets --}}
        <div class="space-y-6">

            {{-- Quick Staff Actions Grid --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-bolt text-brand-500"></i> Quick Staff Actions
                </h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <a href="{{ route('staff.quotations.create') }}" class="p-3 bg-slate-50 hover:bg-brand-50 hover:border-brand-200 border border-slate-200/60 rounded-xl transition flex flex-col items-center justify-center text-center gap-2 group shadow-sm">
                        <div class="h-9 w-9 rounded-lg bg-brand-500 text-white flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <span class="font-bold text-navy-900 group-hover:text-brand-700">New Quotation</span>
                    </a>

                    <a href="{{ route('staff.claim-scanner') }}" class="p-3 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 border border-slate-200/60 rounded-xl transition flex flex-col items-center justify-center text-center gap-2 group shadow-sm">
                        <div class="h-9 w-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <span class="font-bold text-navy-900 group-hover:text-emerald-700">QR Scanner</span>
                    </a>

                    <a href="{{ route('staff.pricing-rules.index') }}" class="p-3 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200/60 rounded-xl transition flex flex-col items-center justify-center text-center gap-2 group shadow-sm">
                        <div class="h-9 w-9 rounded-lg bg-blue-500 text-white flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <span class="font-bold text-navy-900 group-hover:text-blue-700">Pricing Matrix</span>
                    </a>

                    <a href="{{ route('staff.customers.index') }}" class="p-3 bg-slate-50 hover:bg-purple-50 hover:border-purple-200 border border-slate-200/60 rounded-xl transition flex flex-col items-center justify-center text-center gap-2 group shadow-sm">
                        <div class="h-9 w-9 rounded-lg bg-purple-500 text-white flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="font-bold text-navy-900 group-hover:text-purple-700">Customers</span>
                    </a>
                </div>
            </div>

            {{-- Recent Quotations Status --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice-dollar text-amber-500"></i> Quotations Status
                    </h4>
                    <a href="{{ route('staff.quotations.index') }}" class="text-[11px] text-brand-600 hover:underline font-bold">View All</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentQuotations as $quote)
                    <a href="{{ route('staff.quotations.show', $quote) }}" class="p-2.5 bg-slate-50 hover:bg-slate-100/80 transition rounded-xl border border-slate-100 flex items-center justify-between text-xs group">
                        <div>
                            <p class="font-bold text-navy-900 group-hover:text-brand-600">QT-{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[11px] text-slate-500">{{ $quote->user->name ?? 'Customer' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                {{ $quote->status }}
                            </span>
                            <p class="text-[11px] font-bold text-navy-900 mt-0.5">₱{{ number_format($quote->total_amount ?? 0, 2) }}</p>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-slate-400 text-center py-3">No recent quotations.</p>
                    @endforelse
                </div>
            </div>

            {{-- CS Workflow Checklist Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-list-check text-emerald-500"></i> Customer Service Workflow
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Print Requests:</strong> Review specs & generate accurate quotations within 24 hours.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Payment Verification:</strong> Confirm reference codes before forwarding jobs to production.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-brand-500 mt-0.5 shrink-0"></i>
                        <span><strong>Order Claims:</strong> Scan QR code upon pickup to release completed orders.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection
