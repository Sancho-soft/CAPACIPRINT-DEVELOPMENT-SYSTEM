@extends('layouts.internal')
@section('title', 'Dashboard Overview — Customer Service')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER: DASHBOARD OVERVIEW --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold font-display tracking-tight text-cyber-main">Dashboard Overview</h1>
            <p class="text-xs text-cyber-muted mt-1">Live customer intake, quotations, and fulfillment monitoring.</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4 KEY CUSTOMER SERVICE METRICS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
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
    <div id="requests-queue" class="bg-cyber-card border border-cyber rounded-2xl overflow-hidden flex flex-col scroll-mt-6 shadow-sm">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber-sub flex items-center justify-between bg-cyber-sub">
            <div>
                <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-blue-600 dark:text-blue-400"></i> Recent Print Requests Intake
                </h3>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.print-requests.create') }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[11px]"></i> New Request
                </a>
                <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="text-cyber-muted font-sans font-bold border-b border-cyber text-xs bg-cyber-sub">
                    <tr>
                        <th class="px-4 sm:px-6 py-3">Customer Details</th>
                        <th class="px-4 sm:px-6 py-3">Service &amp; Specifications</th>
                        <th class="px-4 sm:px-6 py-3">Intake Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub text-cyber-main">
                    @forelse($recentRequests as $req)
                        @php
                            $statusBadge = match($req->status) {
                                'submitted' => 'bg-amber-500/10 text-amber-800 dark:text-amber-300 border border-amber-500/25',
                                'verified'  => 'bg-blue-600/10 dark:bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-600/25',
                                'quoted'    => 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 border border-indigo-500/25',
                                'production'=> 'bg-teal-500/10 text-teal-700 dark:text-teal-300 border border-teal-500/25',
                                'completed' => 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/25',
                                default     => 'bg-cyber-sub text-cyber-muted border border-cyber',
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
                        <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-semibold text-cyber-main block">{{ $req->user->name ?? 'Customer' }}</span>
                                <span class="text-[11px] text-cyber-muted block">{{ $req->user->email ?? '' }} &middot; {{ $req->created_at ? $req->created_at->diffForHumans() : 'Recently' }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-semibold text-blue-600 dark:text-blue-400 block">{{ $req->service }}</span>
                                <span class="text-[11px] text-cyber-muted font-sans block tabular-nums">
                                    {{ number_format($req->quantity) }} pcs &middot; {{ $req->size }} &middot; {{ $req->material }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusBadge }} font-sans">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('staff.print-requests.show', $req->id) }}" 
                                   class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1.5 rounded-lg hover:bg-blue-600/10 dark:hover:bg-blue-500/15 inline-flex items-center justify-center"
                                   title="{{ $isPrimaryAction ? 'Verify Specs' : 'View Print Request' }}">
                                    <i class="fa-solid fa-eye"></i>
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

        @if(method_exists($recentRequests, 'hasPages') && $recentRequests->hasPages())
            <div class="px-5 py-3 border-t border-cyber-sub bg-cyber-sub">
                {{ $recentRequests->withQueryString()->fragment('requests-queue')->links() }}
            </div>
        @endif
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
