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
            <div>
                <h2 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Sales &amp; Customer Service Desk</h2>
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
    {{-- 4 KEY CUSTOMER SERVICE METRICS (INSIDE PARENT CARD) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl p-5 sm:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-[#0E3386] dark:text-sky-400"></i> Customer Service Key Performance Indicators
            </h3>
            <span class="text-[11px] text-cyber-muted font-medium">Live Desk Overview</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <x-dashboard.kpi-card 
                title="NEW PRINT REQUESTS"
                :value="$newRequestsCount"
                icon="fa-solid fa-file-circle-plus"
                accent="cyan"
                :link="route('staff.print-requests.index')"
            />

            <x-dashboard.kpi-card 
                title="PENDING QUOTATIONS"
                :value="$pendingQuotesCount"
                icon="fa-solid fa-file-invoice-dollar"
                accent="amber"
                :link="route('staff.quotations.index')"
            />

            <x-dashboard.kpi-card 
                title="PAYMENT VERIFICATIONS"
                :value="$pendingPaymentOrders->count()"
                icon="fa-solid fa-credit-card"
                accent="emerald"
                :link="route('staff.orders.index', ['payment_status' => 'submitted'])"
            />

            <x-dashboard.kpi-card 
                title="READY FOR PICKUP"
                :value="$readyForPickupCount"
                icon="fa-solid fa-box-open"
                accent="indigo"
                :link="route('staff.orders.index', ['status' => 'ready_for_pickup'])"
            />
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2: CUSTOMER SERVICE INTAKE & FULFILLMENT PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Customer Service Order Intake & Fulfillment Flow"
        :subtitle="null"
    />

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: INTAKE QUEUE (INCOMING PRINT REQUESTS ONLY) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber/60 flex items-center justify-between bg-cyber-sub/40">
            <div>
                <h3 class="font-black text-cyber-main text-sm sm:text-base font-display tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-[#0E3386] dark:text-sky-400"></i> Recent Print Requests Intake
                </h3>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.print-requests.create') }}" class="px-3 py-1.5 rounded-xl bg-[#0E3386] hover:bg-[#1442a8] text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i> New Request
                </a>
                <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-bold text-[#0E3386] dark:text-sky-400 hover:underline flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px] bg-cyber-sub/70">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5">Customer Details</th>
                        <th class="px-4 sm:px-6 py-3.5">Service &amp; Specifications</th>
                        <th class="px-4 sm:px-6 py-3.5">Intake Status</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($recentRequests as $req)
                        @php
                            $statusBadge = match($req->status) {
                                'submitted' => 'bg-amber-500/15 text-amber-700 dark:text-amber-400',
                                'verified'  => 'bg-blue-500/15 text-[#0E3386] dark:text-sky-400',
                                'quoted'    => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400',
                                'production'=> 'bg-teal-500/15 text-teal-700 dark:text-teal-400',
                                'completed' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400',
                                default     => 'bg-slate-500/15 text-slate-700 dark:text-slate-400',
                            };

                            $reqActionLabel = match($req->status) {
                                'submitted'  => 'Verify Specs',
                                'production' => 'Track Job',
                                'completed'  => 'View Order',
                                'quoted'     => 'View Quote',
                                default      => 'Details',
                            };
                            $isPrimaryAction = ($req->status === 'submitted');
                        @endphp
                        <tr class="hover:bg-cyber-hover/50 transition">
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-bold text-cyber-main block">{{ $req->user->name ?? 'Customer' }}</span>
                                <span class="text-[10px] text-cyber-muted block">{{ $req->user->email ?? '' }} &middot; {{ $req->created_at ? $req->created_at->diffForHumans() : 'Recently' }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-medium text-[#0E3386] dark:text-sky-400 block">{{ $req->service }}</span>
                                <span class="text-[10px] text-cyber-muted font-mono block">
                                    {{ number_format($req->quantity) }} pcs &middot; {{ $req->size }} &middot; {{ $req->material }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $statusBadge }} font-mono">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('staff.print-requests.show', $req->id) }}" 
                                   class="px-3.5 py-1.5 rounded-xl bg-[#0E3386]/10 hover:bg-[#0E3386] text-[#0E3386] hover:text-white dark:text-sky-400 border border-[#0E3386]/20 inline-flex items-center gap-1.5 text-xs font-bold transition shadow-xs">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    <span>{{ $isPrimaryAction ? 'Verify Specs' : 'View' }}</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-cyber-muted text-xs">No pending print requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- LEVEL 2 & 4: ACTIVE CLIENT ORDERS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Active Client Orders"
        :subtitle="null"
        :viewAllUrl="route('staff.orders.index')"
        viewAllLabel="All Client Orders"
    />

</div>
@endsection
