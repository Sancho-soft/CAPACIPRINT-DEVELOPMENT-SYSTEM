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
            title="CLAIMED & HANDED OVER TODAY"
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

            <div class="flex justify-end pt-1">
                <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white font-black px-8 py-3.5 rounded-2xl text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Verify &amp; Handover Order</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Recent Pickup History Logs --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-cyber/60">
            <h3 class="font-black text-cyber-main text-sm sm:text-base font-display">Recent Branch Pickups &amp; Claim Activity</h3>
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
                                @if($clm->is_claimed && $clm->claimed_at)
                                    <span class="text-cyber-main">{{ $clm->claimed_at->format('M d, Y h:i A') }}</span>
                                @else
                                    <span class="text-amber-700/80 dark:text-amber-400/80 italic">Pending Handover</span>
                                @endif
                            </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if($clm->order_id)
                                        <a href="{{ route('staff.orders.show', $clm->order_id) }}" 
                                           class="group h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 border border-cyan-500/25 hover:border-cyan-500 inline-flex items-center justify-center transition-all duration-200 shadow-xs" 
                                           title="View Order">
                                            <i class="fa-solid fa-eye text-xs text-cyan-600 dark:text-cyan-400 group-hover:text-white dark:group-hover:text-slate-950 transition-colors"></i>
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
