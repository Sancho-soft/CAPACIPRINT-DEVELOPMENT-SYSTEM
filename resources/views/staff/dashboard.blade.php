@extends('layouts.internal')
@section('title', 'Sales & Service Dashboard')
@section('page-title', 'Sales & Service Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-white font-display">Sales &amp; Service Dashboard</h2>
            <p class="text-xs text-slate-400 mt-1">Customer service requests, quotation processing, and order updates.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('staff.quotations.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> New Quotation
            </a>
            <a href="{{ route('staff.claim-scanner') }}" class="px-4 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-slate-200 font-bold text-xs transition flex items-center gap-2">
                <i class="fa-solid fa-qrcode text-xs"></i> QR Scanner
            </a>
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- New Requests --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-cyan-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-file-circle-plus text-2xl text-cyan-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-cyan-400 uppercase tracking-wider leading-tight max-w-[110px]">NEW REQUESTS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $newRequestsCount }}</div>
            </div>
        </div>

        {{-- Pending Quotes --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-amber-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-file-invoice-dollar text-2xl text-amber-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-wider leading-tight max-w-[110px]">PENDING QUOTES</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $pendingQuotesCount }}</div>
            </div>
        </div>

        {{-- Payment Verification --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-emerald-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-credit-card text-2xl text-emerald-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-emerald-400 uppercase tracking-wider leading-tight max-w-[110px]">PAYMENT VERIFY</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $pendingPaymentOrders->count() }}</div>
            </div>
        </div>

        {{-- Active Orders --}}
        <div class="bg-[#111A24] rounded-2xl border border-slate-800/80 p-5 flex items-center justify-between shadow-lg hover:border-indigo-500/30 transition group">
            <div class="flex items-center gap-3.5 min-w-0">
                <i class="fa-solid fa-boxes-stacked text-2xl text-indigo-400 shrink-0 group-hover:scale-110 transition-all"></i>
                <div class="text-[11px] font-black text-indigo-400 uppercase tracking-wider leading-tight max-w-[110px]">ACTIVE ORDERS</div>
            </div>
            <div class="text-right shrink-0">
                <div class="text-3xl font-black text-white font-display">{{ $activeOrdersCount }}</div>
            </div>
        </div>
    </div>

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Pending Payment Confirmations Alert Card --}}
            @if($pendingPaymentOrders->isNotEmpty())
            <div class="bg-[#111A24] border border-amber-500/30 rounded-3xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-amber-500/10 border-b border-amber-500/20 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-amber-300 font-bold text-xs">
                        <i class="fa-solid fa-bell text-amber-400 animate-bounce"></i> Orders Requiring Payment Verification
                    </div>
                    <span class="text-[10px] font-black text-amber-300 bg-amber-500/20 border border-amber-500/30 px-2.5 py-0.5 rounded-full">{{ $pendingPaymentOrders->count() }} Pending</span>
                </div>
                <div class="divide-y divide-slate-800/60 text-xs">
                    @foreach($pendingPaymentOrders as $ord)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-800/40 transition">
                        <div>
                            <span class="font-bold text-white text-sm">Order {{ $ord->order_number }}</span>
                            <span class="text-slate-400 ml-2">Customer: <strong class="text-slate-200">{{ $ord->user->name ?? '—' }}</strong></span>
                            <p class="text-slate-400 mt-0.5">Ref: <span class="font-mono text-cyan-400">{{ $ord->payment->payment_reference ?? 'N/A' }}</span> &middot; Amount: <strong class="text-emerald-400 font-mono">₱{{ number_format($ord->payment->amount ?? 0, 2) }}</strong></p>
                        </div>
                        <form method="POST" action="{{ route('staff.orders.confirm-payment', $ord) }}">
                            @csrf
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold px-3.5 py-1.5 rounded-xl text-xs shadow-sm transition flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Confirm Payment
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recent Customer Requests Table --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
                    <div>
                        <h3 class="font-black text-white text-sm">Recent Customer Requests</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Latest incoming print inquiries requiring quotation or review</p>
                    </div>
                    <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                            <tr>
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">Service</th>
                                <th class="px-5 py-3.5">Quantity</th>
                                <th class="px-5 py-3.5">Deadline</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300">
                            @forelse($recentRequests as $req)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-5 py-3.5 font-bold text-white">{{ $req->user->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-300">{{ $req->service }}</td>
                                <td class="px-5 py-3.5 text-slate-400 font-mono">{{ number_format($req->quantity) }} pcs</td>
                                <td class="px-5 py-3.5 text-slate-400">{{ $req->deadline?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $req->status_badge_class }}">
                                        {{ $req->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('staff.print-requests.show', $req) }}"
                                       class="text-cyan-400 hover:text-cyan-300 transition text-sm p-1 inline-block"
                                       title="Review Print Request Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                                    <i class="fa-solid fa-folder-open text-2xl text-slate-600 block mb-2"></i>
                                    No customer print requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Active Orders Overview --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-[#0D1520]">
                    <div>
                        <h3 class="font-black text-white text-sm">Active Orders Tracker</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Real-time status of orders in production &amp; fulfillment</p>
                    </div>
                    <a href="{{ route('staff.orders.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                        All Orders <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                            <tr>
                                <th class="px-5 py-3.5">Order No.</th>
                                <th class="px-5 py-3.5">Customer</th>
                                <th class="px-5 py-3.5">Service</th>
                                <th class="px-5 py-3.5">Total Amount</th>
                                <th class="px-5 py-3.5">Status</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-300">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-5 py-3.5 font-bold text-white">{{ $order->order_number }}</td>
                                <td class="px-5 py-3.5 text-slate-300">{{ $order->user->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-400">{{ $order->printRequest->service ?? 'Print Order' }}</td>
                                <td class="px-5 py-3.5 font-black text-emerald-400 font-mono">₱{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('staff.orders.show', $order) }}"
                                       class="text-cyan-400 hover:text-cyan-300 transition text-sm p-1 inline-block"
                                       title="View Order Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500">No recent orders.</td>
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
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-4">
                <h4 class="font-black text-white text-sm flex items-center gap-2 border-b border-slate-800/80 pb-3">
                    <i class="fa-solid fa-bolt text-cyan-400"></i> Quick Staff Actions
                </h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <a href="{{ route('staff.quotations.create') }}" class="p-3 bg-[#0D1520] hover:bg-slate-800/80 border border-slate-800 rounded-2xl transition flex flex-col items-center justify-center text-center gap-2 group">
                        <div class="h-9 w-9 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <span class="font-bold text-slate-200 group-hover:text-cyan-400">New Quotation</span>
                    </a>

                    <a href="{{ route('staff.claim-scanner') }}" class="p-3 bg-[#0D1520] hover:bg-slate-800/80 border border-slate-800 rounded-2xl transition flex flex-col items-center justify-center text-center gap-2 group">
                        <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <span class="font-bold text-slate-200 group-hover:text-emerald-400">QR Scanner</span>
                    </a>

                    <a href="{{ route('staff.pricing-rules.index') }}" class="p-3 bg-[#0D1520] hover:bg-slate-800/80 border border-slate-800 rounded-2xl transition flex flex-col items-center justify-center text-center gap-2 group">
                        <div class="h-9 w-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <span class="font-bold text-slate-200 group-hover:text-indigo-400">Pricing Matrix</span>
                    </a>

                    <a href="{{ route('staff.customers.index') }}" class="p-3 bg-[#0D1520] hover:bg-slate-800/80 border border-slate-800 rounded-2xl transition flex flex-col items-center justify-center text-center gap-2 group">
                        <div class="h-9 w-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="font-bold text-slate-200 group-hover:text-purple-400">Customers</span>
                    </a>
                </div>
            </div>

            {{-- Recent Quotations Status --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3">
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                    <h4 class="font-black text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice-dollar text-amber-400"></i> Quotations Status
                    </h4>
                    <a href="{{ route('staff.quotations.index') }}" class="text-[11px] text-cyan-400 hover:text-cyan-300 font-bold">View All</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentQuotations as $quote)
                    <a href="{{ route('staff.quotations.show', $quote) }}" class="p-3 bg-[#0D1520] hover:bg-slate-800/80 transition rounded-2xl border border-slate-800 flex items-center justify-between text-xs group">
                        <div>
                            <p class="font-bold text-white group-hover:text-cyan-400">QT-{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[11px] text-slate-400">{{ $quote->user->name ?? 'Customer' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                {{ $quote->status }}
                            </span>
                            <p class="text-[11px] font-black text-emerald-400 mt-1 font-mono">₱{{ number_format($quote->total_amount ?? 0, 2) }}</p>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-slate-500 text-center py-3">No recent quotations.</p>
                    @endforelse
                </div>
            </div>

            {{-- CS Workflow Checklist Card --}}
            <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl p-5 shadow-xl space-y-3">
                <h4 class="font-black text-white text-sm flex items-center gap-2 border-b border-slate-800/80 pb-3">
                    <i class="fa-solid fa-list-check text-emerald-400"></i> Customer Service Protocol
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-400">
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Print Requests:</strong> Review specs &amp; generate accurate quotations within 24 hours.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Payment Verification:</strong> Confirm reference codes before forwarding jobs to production.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <i class="fa-solid fa-square-check text-cyan-400 mt-0.5 shrink-0"></i>
                        <span><strong class="text-slate-200">Order Claims:</strong> Scan QR code upon pickup to release completed orders.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection

