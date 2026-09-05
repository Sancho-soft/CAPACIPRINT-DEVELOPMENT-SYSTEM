@extends('layouts.internal')
@section('title', 'Sales & Customer Service Desk')
@section('page-title', 'Sales & Customer Service Desk')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: CUSTOMER SERVICE & SALES DESK --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Sales &amp; Customer Service Desk</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 font-mono">
                            Front Desk Active
                        </span>
                    </div>
                    <p class="text-xs text-cyber-muted mt-1 leading-relaxed">
                        Client print request intake, technical specification verification, quotation estimation, payment auditing, and QR claim handover.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0 w-full lg:w-auto justify-start lg:justify-end">
                <a href="{{ route('staff.quotations.create') }}" class="px-3.5 py-2 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> New Quotation
                </a>
                <a href="{{ route('staff.claim-scanner') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-qrcode text-xs text-cyan-400"></i> QR Claim Scanner
                </a>
                <a href="{{ route('staff.customers.index') }}" class="px-3.5 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-users text-xs text-emerald-400"></i> Customer Directory
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 1: ACTIONABLE ATTENTION CENTER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.attention-center 
        :items="$attentionItems"
        title="Front Desk Action Queue"
        subtitle="Unverified payment receipts and new customer print specifications awaiting approval"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY CUSTOMER SERVICE METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="NEW PRINT REQUESTS"
            :value="$newRequestsCount"
            icon="fa-solid fa-file-circle-plus"
            accent="cyan"
            trend="{{ $newRequestsCount > 0 ? 'Needs verification' : 'Up to date' }}"
            :trendType="$newRequestsCount > 0 ? 'warning' : 'up'"
            subtitle="Awaiting staff review"
            :link="route('staff.print-requests.index')"
        />

        <x-dashboard.kpi-card 
            title="PENDING QUOTATIONS"
            :value="$pendingQuotesCount"
            icon="fa-solid fa-file-invoice-dollar"
            accent="amber"
            trend="Price matrix calculations"
            trendType="neutral"
            subtitle="Estimates prepared"
            :link="route('staff.quotations.index')"
        />

        <x-dashboard.kpi-card 
            title="PAYMENT VERIFICATIONS"
            :value="$pendingPaymentOrders->count()"
            icon="fa-solid fa-credit-card"
            accent="emerald"
            trend="{{ $pendingPaymentOrders->count() > 0 ? 'Receipts attached' : 'No backlog' }}"
            :trendType="$pendingPaymentOrders->count() > 0 ? 'warning' : 'up'"
            subtitle="Proof slips submitted"
        />

        <x-dashboard.kpi-card 
            title="READY FOR PICKUP"
            :value="$readyForPickupCount"
            icon="fa-solid fa-box-open"
            accent="indigo"
            trend="Use QR scanner"
            trendType="neutral"
            subtitle="Awaiting client claim"
            :link="route('staff.claim-scanner')"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: CUSTOMER SERVICE INTAKE & FULFILLMENT PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Customer Service Order Intake & Fulfillment Flow"
        subtitle="Tracking client request verification, quote confirmation, payment clearance, and claiming"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: INTAKE QUEUES (REQUESTS & QUOTES) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: INCOMING PRINT REQUESTS --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
                <div>
                    <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Print Requests</h3>
                    <p class="text-[11px] text-cyber-muted mt-0.5">Customer specifications requiring technical review</p>
                </div>
                <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Customer</th>
                            <th class="px-4 sm:px-5 py-3">Service & Specs</th>
                            <th class="px-4 sm:px-5 py-3">Status</th>
                            <th class="px-4 sm:px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @forelse($recentRequests as $req)
                            @php
                                $statusBadge = match($req->status) {
                                    'submitted' => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                    'verified'  => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                                    'quoted'    => 'bg-indigo-500/15 text-indigo-400 border-indigo-500/30',
                                    'production'=> 'bg-teal-500/15 text-teal-400 border-teal-500/30',
                                    'completed' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                    default     => 'bg-slate-500/15 text-slate-400 border-slate-500/30',
                                };
                            @endphp
                            <tr class="hover:bg-cyber-hover/50 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-bold text-cyber-main block truncate max-w-[140px]">{{ $req->user->name ?? 'Customer' }}</span>
                                    <span class="text-[10px] text-cyber-muted block truncate max-w-[140px]">{{ $req->created_at ? $req->created_at->diffForHumans() : 'Recently' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-medium text-cyan-400 block truncate max-w-[160px]">{{ $req->service }}</span>
                                    <span class="text-[10px] text-cyber-muted font-mono block">
                                        {{ number_format($req->quantity) }} pcs &middot; {{ $req->size }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border {{ $statusBadge }} font-mono">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('staff.print-requests.show', $req->id) }}" class="px-2.5 py-1 rounded-lg bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition inline-flex items-center gap-1">
                                        <span>Verify</span>
                                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">No pending print requests.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT: RECENT QUOTATIONS --}}
        <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-cyber/80 flex items-center justify-between bg-cyber-sub/70">
                <div>
                    <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Quotations</h3>
                    <p class="text-[11px] text-cyber-muted mt-0.5">Pricing estimates prepared for clients</p>
                </div>
                <a href="{{ route('staff.quotations.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 flex items-center gap-1">
                    All Quotes <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                        <tr>
                            <th class="px-4 sm:px-5 py-3">Quote #</th>
                            <th class="px-4 sm:px-5 py-3">Customer</th>
                            <th class="px-4 sm:px-5 py-3">Total Est.</th>
                            <th class="px-4 sm:px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @forelse($recentQuotations as $q)
                            @php
                                $qBadge = match($q->status) {
                                    'confirmed' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                    'declined'  => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                    default     => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                                };
                            @endphp
                            <tr class="hover:bg-cyber-hover/50 transition">
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-mono font-bold text-cyber-main block">{{ $q->quotation_number }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[8px] font-black uppercase tracking-wider border {{ $qBadge }} font-mono inline-block mt-0.5">
                                        {{ ucfirst($q->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-5 py-3">
                                    <span class="font-bold text-cyber-main block truncate max-w-[140px]">{{ $q->user->name ?? 'Customer' }}</span>
                                    <span class="text-[10px] text-cyber-muted truncate max-w-[140px] block">{{ $q->printRequest->service ?? 'Print Order' }}</span>
                                </td>
                                <td class="px-4 sm:px-5 py-3 font-mono font-bold text-emerald-400 whitespace-nowrap">
                                    ₱{{ number_format($q->total_price, 2) }}
                                </td>
                                <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('staff.quotations.show', $q->id) }}" class="px-2.5 py-1 rounded-lg bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs transition inline-flex items-center gap-1">
                                        <span>View</span>
                                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-cyber-muted text-xs">No recent quotations issued.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: ACTIVE CLIENT ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Active Client Orders"
        subtitle="Live status tracking across payment confirmation, production, and claiming"
        :viewAllUrl="route('staff.orders.index')"
        viewAllLabel="All Client Orders"
    />

</div>
@endsection
