@extends('layouts.internal')
@section('title', 'Claim & QR Scanner')
@section('page-title', 'Order Claim & QR Verification Tool')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Scanner / Input Card --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <div class="h-12 w-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-navy-900 font-display">Branch Order Pickup Scanner</h2>
                <p class="text-xs text-slate-500">Scan customer QR code or manually enter the 8-character claim code or Order # to complete pickup.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('staff.claim-verify') }}" class="space-y-4 pt-2" x-data="{ code: '' }">
            @csrf
            <div>
                <label class="block text-xs font-bold text-navy-900 mb-1.5 uppercase tracking-wide">Enter or Scan Claim Code / Order #</label>
                <div class="relative">
                    <input type="text" name="claim_code" x-model="code" required autofocus autocomplete="off"
                           placeholder="e.g. CLM-A1B2C3D4 or ORD-SEED001"
                           class="w-full rounded-2xl border-2 border-slate-200 px-4 py-3.5 text-base font-mono font-bold text-navy-900 focus:border-brand-500 focus:outline-none uppercase tracking-wider">
                    <div class="absolute right-3 top-3 text-slate-400">
                        <i class="fa-solid fa-barcode text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400"><i class="fa-solid fa-circle-info mr-1"></i> Press Enter or click Verify to process.</span>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-8 py-3 rounded-xl text-sm shadow-md shadow-brand-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Verify &amp; Complete Pickup
                </button>
            </div>
        </form>
    </div>

    {{-- Recent Pickup Logs --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-navy-900 text-sm">Recent Branch Pickups &amp; Claim History</h3>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Claim Code</th>
                    <th class="px-6 py-3.5">Order #</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5">Claimed At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentClaims as $clm)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-mono font-bold text-brand-600">{{ $clm->claim_code }}</td>
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $clm->order->order_number ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-800 font-semibold">{{ $clm->order->user->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $clm->status === 'claimed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ ucfirst($clm->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $clm->claimed_at?->format('M d, Y h:i A') ?? 'Not claimed yet' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No claim records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
