@extends('layouts.internal')
@section('title', 'Customer Service & Intake')
@section('page-title', 'Customer Service & Intake')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">Customer Service &amp; Intake</h1>
            <p class="text-sm text-cyber-muted mt-1">Incoming print requests, pending quotations, payment verification, and order handoffs.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('staff.print-requests.create') }}" class="px-4 py-2 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>New Request</span>
            </a>
            <a href="{{ route('staff.claim-scanner') }}" class="px-3.5 py-2 rounded-lg bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-qrcode text-xs text-[#2F7BFF]"></i>
                <span>QR Claim Scanner</span>
            </a>
        </div>
    </div>

    {{-- 4 KEY CUSTOMER SERVICE METRICS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="New Print Requests"
            :value="$newRequestsCount"
            accent="blue"
            :link="route('staff.print-requests.index')"
        />

        <x-dashboard.kpi-card 
            title="Pending Quotations"
            :value="$pendingQuotesCount"
            accent="amber"
            :link="route('staff.quotations.index')"
        />

        <x-dashboard.kpi-card 
            title="Payment Verifications"
            :value="$pendingPaymentOrders->count()"
            accent="blue"
            :link="route('staff.orders.index', ['payment_status' => 'submitted'])"
        />

        <x-dashboard.kpi-card 
            title="Ready for Pickup"
            :value="$readyForPickupCount"
            accent="emerald"
            :link="route('staff.orders.index', ['status' => 'ready_for_pickup'])"
        />
    </div>

    {{-- CUSTOMER SERVICE INTAKE & FULFILLMENT PIPELINE --}}
    <x-dashboard.workflow-pipeline 
        :stages="$pipeline"
        title="Customer Service Order Intake &amp; Fulfillment Flow"
        :subtitle="null"
    />

    {{-- INTAKE QUEUE (INCOMING PRINT REQUESTS) --}}
    <div id="requests-queue" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col scroll-mt-6">
        <div class="px-5 sm:px-6 py-4 border-b border-cyber flex items-center justify-between bg-cyber-sub">
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display tracking-tight">Recent Print Requests Intake</h3>
            <div class="flex items-center gap-3">
                <a href="{{ route('staff.print-requests.index') }}" class="text-xs font-semibold text-[#2F7BFF] dark:text-[#60A5FA] hover:underline flex items-center gap-1.5">
                    <span>View All</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left text-xs tabular-nums">
                <thead class="text-cyber-muted font-sans font-semibold border-b border-cyber text-xs bg-cyber-sub">
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
                            $statusTone = match($req->status) {
                                'submitted' => ['dot' => 'bg-[#FFC531]', 'text' => 'text-amber-700 dark:text-[#FFC531]'],
                                'completed' => ['dot' => 'bg-[#10B981]', 'text' => 'text-emerald-700 dark:text-[#10B981]'],
                                default     => ['dot' => 'bg-[#2F7BFF]', 'text' => 'text-[#2F7BFF] dark:text-[#60A5FA]'],
                            };
                            $isPrimaryAction = ($req->status === 'submitted');
                        @endphp
                        <tr class="hover:bg-black/[0.02] dark:hover:bg-white/[0.025] transition-colors">
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-semibold text-cyber-main block">{{ $req->user->name ?? 'Customer' }}</span>
                                <span class="text-xs text-cyber-muted block mt-0.5">{{ $req->user->email ?? '' }} &middot; {{ $req->created_at ? $req->created_at->diffForHumans() : 'Recently' }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <span class="font-semibold text-[#2F7BFF] dark:text-[#60A5FA] block">{{ $req->service }}</span>
                                <span class="text-xs text-cyber-muted font-mono block mt-0.5 tabular-nums">
                                    {{ number_format($req->quantity) }} pcs &middot; {{ $req->size }} &middot; {{ $req->material }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $statusTone['text'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusTone['dot'] }}"></span>
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('staff.print-requests.show', $req->id) }}" 
                                   class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] transition text-xs inline-flex items-center justify-center"
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
            <div class="px-5 py-3 border-t border-cyber bg-cyber-sub">
                {{ $recentRequests->withQueryString()->fragment('requests-queue')->links() }}
            </div>
        @endif
    </div>

    {{-- ACTIVE CLIENT ORDERS TABLE --}}
    <x-dashboard.production-table 
        :jobs="$recentOrders"
        title="Active Client Orders"
        :subtitle="null"
        :viewAllUrl="route('staff.orders.index')"
        viewAllLabel="All Client Orders"
    />

</div>
@endsection
