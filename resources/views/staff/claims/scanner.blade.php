@extends('layouts.internal')
@section('title', 'Claim & QR Scanner')
@section('page-title', 'Branch Order Pickup Terminal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="{ code: '' }">

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-sm font-medium shadow-sm">
            <div class="h-8 w-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-xs sm:text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 flex items-center gap-3 text-sm font-medium shadow-sm">
            <div class="h-8 w-8 rounded-xl bg-rose-500/20 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-base"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-xs sm:text-sm">{{ session('error') }}</p>
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
            title="CLAIMED &amp; HANDED OVER TODAY"
            :value="$todayClaimedCount ?? 0"
            icon="fa-solid fa-handshake"
            accent="emerald"
        />
    </div>

    {{-- Scanner Console Terminal Card --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-8 shadow-2xl overflow-hidden space-y-6">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-cyber/60 pb-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl shrink-0 shadow-sm">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-cyber-main font-display">Branch Order Pickup Terminal</h2>
                    <p class="text-xs text-cyber-muted mt-1">Scan customer QR pass or manually enter the 8-character Claim Code / Order # to complete handover.</p>
                </div>
            </div>
        </div>

        {{-- Main Scan Input Form --}}
        <form method="POST" action="{{ route('staff.claim-verify') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-cyber-main mb-2 uppercase tracking-wider">
                    <i class="fa-solid fa-barcode text-cyan-600 dark:text-cyan-400 mr-1.5"></i> Enter or Scan Claim Code / Order #
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
                           class="w-full rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0D1520] px-5 py-4 text-base sm:text-lg font-mono font-black text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:border-cyan-500 focus:outline-none focus:ring-4 focus:ring-cyan-500/20 uppercase tracking-widest transition shadow-inner">
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-barcode text-2xl text-cyan-500"></i>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                <span class="text-xs text-cyber-muted flex items-center gap-1.5">
                    <i class="fa-solid fa-keyboard text-cyan-600 dark:text-cyan-400 text-xs"></i> 
                    Hardware USB scanner triggers Enter automatically, or click Verify.
                </span>
                <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white font-black px-8 py-3.5 rounded-2xl text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Verify &amp; Handover Order</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Orders Waiting on Shelf (Quick Handover Shelf) --}}
    @if(isset($readyOrders) && $readyOrders->isNotEmpty())
        <div class="bg-cyber-card border border-amber-500/30 rounded-3xl shadow-xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-amber-500/20 flex items-center justify-between bg-amber-500/5">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-xl bg-amber-500/15 text-amber-700 dark:text-amber-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Orders Ready on Branch Shelf</h3>
                        <p class="text-[11px] text-cyber-muted mt-0.5">Quick Handover: Click any order if client does not have their phone pass</p>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-500/30">
                    {{ $readyOrders->count() }} Waiting
                </span>
            </div>

            <div class="divide-y divide-cyber/60 text-xs">
                @foreach($readyOrders as $rOrd)
                    @php
                        $clmCode = $rOrd->claimReference?->claim_code ?? $rOrd->order_number;
                    @endphp
                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-cyber-hover/50 transition">
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="font-bold text-cyber-main text-sm font-mono">#{{ $rOrd->order_number }}</span>
                                <span class="text-xs text-cyber-muted">&bull; {{ $rOrd->user->name ?? 'Customer' }}</span>
                                @if($rOrd->claimReference)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 border border-cyan-500/20">
                                        {{ $rOrd->claimReference->claim_code }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-cyber-muted">
                                {{ $rOrd->printRequest->service ?? 'Print Order' }} &middot; {{ number_format($rOrd->printRequest->quantity ?? 1) }} pcs &middot; {{ $rOrd->printRequest->size ?? 'Standard' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" 
                                    @click="code = '{{ $clmCode }}'; $nextTick(() => document.getElementById('claim_input').focus())"
                                    class="px-3.5 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[10px]"></i>
                                <span>Fill &amp; Handover</span>
                            </button>
                            <a href="{{ route('staff.orders.show', $rOrd->id) }}" 
                               class="text-xs font-bold text-sky-600 hover:text-sky-700 dark:text-cyan-400 dark:hover:text-cyan-300 hover:underline px-2 py-1">
                                Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Pickup History Logs --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber/60">
            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Recent Branch Pickups &amp; Claim History</h3>
            <p class="text-[11px] text-cyber-muted mt-0.5">Recently scanned customer orders and handover confirmations</p>
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
                <table class="w-full text-left text-xs">
                    <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px]">
                        <tr>
                            <th class="px-6 py-3.5">Claim Code</th>
                            <th class="px-6 py-3.5">Order #</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Pickup Status</th>
                            <th class="px-6 py-3.5">Handover Timestamp</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cyber/60 text-cyber-main">
                        @foreach($recentClaims as $clm)
                        <tr class="hover:bg-cyber-hover/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-cyan-700 dark:text-cyan-400">
                                {{ $clm->claim_code }}
                            </td>
                            <td class="px-6 py-4 font-bold text-cyber-main font-mono">
                                #{{ $clm->order->order_number ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-cyber-main block">{{ $clm->order->user->name ?? 'Direct Customer' }}</span>
                                <span class="text-[10px] text-cyber-muted block">{{ $clm->order->printRequest->service ?? 'Print Order' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($clm->is_claimed)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 font-mono">
                                        <i class="fa-solid fa-check text-[9px]"></i> Claimed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-800 dark:text-amber-400 border border-amber-500/30 font-mono">
                                        <i class="fa-solid fa-clock text-[9px]"></i> Ready on Shelf
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-cyber-muted text-[11px] whitespace-nowrap">
                                {{ $clm->claimed_at?->format('M d, Y h:i A') ?? 'Pending Handover' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($clm->order_id)
                                    <a href="{{ route('staff.orders.show', $clm->order_id) }}" 
                                       class="h-8 w-8 rounded-xl bg-white hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700/80 border border-slate-300 dark:border-slate-700 hover:border-sky-400 dark:hover:border-cyan-500/40 inline-flex items-center justify-center transition-all shadow-xs group"
                                       title="View Order">
                                        <i class="fa-solid fa-eye text-sm text-slate-800 dark:text-slate-100 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors"></i>
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
