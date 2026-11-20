@extends('layouts.internal')
@section('title', 'Claim & QR Scanner')
@section('page-title', 'Branch Order Pickup Terminal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ code: '' }">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-sm font-medium">
            <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-xs sm:text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 flex items-center gap-3 text-sm font-medium">
            <div class="h-8 w-8 rounded-lg bg-rose-500/20 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-base"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-xs sm:text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Top Live Stats Strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-dashboard.kpi-card 
            title="READY ON SHELF FOR PICKUP"
            :value="$readyCount ?? 0"
            icon="fa-solid fa-box-open"
            accent="amber"
        />

        <x-dashboard.kpi-card 
            title="CLAIMED & HANDED OVER TODAY"
            :value="$todayClaimedCount ?? 0"
            icon="fa-solid fa-handshake"
            accent="emerald"
        />
    </div>

    {{-- Scanner Console Terminal Card --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-cyber pb-5">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-brand-600/10 border border-brand-600/20 text-brand-600 dark:text-blue-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-cyber-main font-display tracking-tight">Branch Order Pickup Terminal</h2>
                    <p class="text-xs text-cyber-muted mt-1">Scan customer QR pass or manually enter the 8-character Claim Code / Order # to complete handover.</p>
                </div>
            </div>
        </div>

        {{-- Main Scan Input Form --}}
        <form method="POST" action="{{ route('staff.claim-verify') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-sans font-semibold text-cyber-main mb-2">
                    <i class="fa-solid fa-barcode text-brand-600 dark:text-blue-400 mr-1.5"></i> Enter or Scan Claim Code / Order #
                </label>
                <div class="relative">
                    <input type="text" 
                           id="claim_input"
                           name="claim_code" 
                           x-model="code" 
                           required 
                           autofocus 
                           autocomplete="off"
                           placeholder="E.G. CLM-A1B2C3D4 OR ORD-SEED001"
                           class="w-full rounded-xl border border-cyber bg-cyber-sub pl-5 pr-12 py-3.5 text-base sm:text-lg font-sans tabular-nums font-bold text-cyber-main placeholder-cyber-muted/60 focus:border-brand-600 focus:outline-none uppercase tracking-wide transition">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-cyber-muted">
                        <i class="fa-solid fa-barcode text-xl text-brand-600 dark:text-blue-400"></i>
                    </span>
                </div>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-3 rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Verify &amp; Handover Order</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Recent Pickup History Logs --}}
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-cyber bg-cyber-sub/30">
            <h3 class="font-bold text-cyber-main text-sm sm:text-base font-display">Recent Branch Pickups &amp; Claim Activity</h3>
            <p class="text-[11px] text-cyber-muted mt-0.5">Live claim codes and branch handover verification logs</p>
        </div>

        @if($recentClaims->isEmpty())
            <div class="p-8">
                <x-dashboard.empty-state 
                    title="No Pickups Recorded Yet"
                    description="No orders have been claimed at this branch station today. Handover records will automatically log here."
                    icon="fa-solid fa-handshake"
                />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs tabular-nums">
                    <thead class="bg-cyber-sub text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                        <tr>
                            <th class="px-5 py-3">Claim Code</th>
                            <th class="px-5 py-3">Order #</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Pickup Status</th>
                            <th class="px-5 py-3">Handover Timestamp</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                        @foreach($recentClaims as $clm)
                        <tr class="hover:bg-cyber-sub/50 transition-colors">
                            <td class="px-5 py-3.5 font-sans tabular-nums font-bold text-brand-600 dark:text-blue-400">
                                {{ $clm->claim_code }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-cyber-main font-sans tabular-nums">
                                #{{ $clm->order->order_number ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-cyber-main block">{{ $clm->order->user->name ?? 'Direct Customer' }}</span>
                                <span class="text-[11px] text-cyber-muted block">{{ $clm->order->printRequest->service ?? 'Print Order' }}</span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($clm->is_claimed)
                                    <span class="text-xs font-sans font-semibold text-emerald-600 dark:text-emerald-400">
                                        Claimed
                                    </span>
                                @else
                                    <span class="text-xs font-sans font-semibold text-amber-600 dark:text-amber-400">
                                        Ready on Shelf
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-sans tabular-nums text-cyber-muted text-[11px] whitespace-nowrap">
                                @if($clm->is_claimed && $clm->claimed_at)
                                    <span class="text-cyber-main">{{ $clm->claimed_at->format('M d, Y h:i A') }}</span>
                                @else
                                    <span class="text-amber-700 dark:text-amber-400 italic">Pending Handover</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                @if($clm->order_id)
                                    <a href="{{ route('staff.orders.show', $clm->order_id) }}" 
                                       class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center" 
                                       title="View Order">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
