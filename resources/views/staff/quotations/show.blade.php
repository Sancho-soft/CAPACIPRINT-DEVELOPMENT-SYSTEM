@extends('layouts.internal')
@section('title', 'Quotation Details')
@section('page-title', 'Quotation #' . $quotation->quotation_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Top Minimalist Navigation & Actions Toolbar (Option A: Clean, No Redundancy) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-cyber-card border border-cyber px-5 py-3.5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.quotations.index') }}" class="px-3.5 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main hover:text-blue-500 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                <span>Back to Quotations</span>
            </a>
            <span class="text-cyber-muted/40 text-xs">|</span>
            @php
                $statusColor = match(strtolower($quotation->status)) {
                    'confirmed' => 'text-emerald-600 dark:text-emerald-400',
                    'pending'   => 'text-amber-600 dark:text-amber-400',
                    'rejected', 'declined' => 'text-rose-600 dark:text-rose-400',
                    default     => 'text-slate-500 dark:text-slate-400',
                };
            @endphp
            <div class="flex items-center gap-1.5 text-xs font-black uppercase tracking-wider {{ $statusColor }}">
                <span class="inline-block w-2 h-2 rounded-full bg-current"></span>
                <span>{{ $quotation->status_label }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0 w-full sm:w-auto justify-end">
            <form method="POST" action="{{ route('staff.quotations.send', $quotation) }}" class="inline">
                @csrf
                <button type="submit" class="px-3.5 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-paper-plane text-xs text-sky-400"></i>
                    <span>Resend to Customer</span>
                </button>
            </form>
            <a href="{{ route('staff.quotations.download', $quotation) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm shadow-blue-500/20">
                <i class="fa-solid fa-print"></i>
                <span>Print / Download PDF</span>
            </a>
        </div>
    </div>

    {{-- Official Morning Star Quotation Sheet (Hero Document) --}}
    @php
        $qty = max(1, (int)($quotation->printRequest->quantity ?? 1));
        $unitPrice = $quotation->total_price / $qty;
        $downPayment = $quotation->total_price * 0.50;
        $balancePayment = $quotation->total_price * 0.50;
    @endphp

    <div class="bg-white text-slate-900 p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-2xl space-y-5">

        {{-- Header: Logo LEFT + Company Name CENTER --}}
        <div class="flex items-center gap-5 pb-4 border-b border-slate-900">
            <img src="{{ asset('images/morning-star-logo.png') }}"
                 alt="Morning Star Printing Press Co."
                 class="h-20 w-20 sm:h-24 sm:w-24 object-contain shrink-0">
            <div class="flex-1 text-center">
                <h1 class="text-xl sm:text-2xl font-black text-slate-950 uppercase tracking-[0.15em] leading-tight">
                    MORNING  STAR  PRINTING  PRESS  CO.
                </h1>
                <p class="text-[12px] text-slate-700 font-medium mt-1">G/F La Fontana Bldg., Bolton Street, Davao City</p>
                <p class="text-[12px] text-slate-700 font-medium">315-1064 &bull; 0917 685 0444 &bull; 0933 859 8980</p>
            </div>
        </div>

        {{-- QUOTATION Title — Centered Standalone --}}
        <div class="text-center py-1">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-950 uppercase tracking-widest">QUOTATION</h2>
        </div>

        {{-- Customer Info — Bordered Grid (Physical Form Style) --}}
        <div class="text-xs">
            <table class="border-collapse w-full">
                <tr>
                    <td class="border border-slate-900 py-1.5 px-2 font-bold text-slate-900 w-28 bg-slate-50">Date:</td>
                    <td class="border border-slate-900 py-1.5 px-2 text-slate-950 font-semibold">
                        {{ $quotation->created_at->format('m/d/Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-slate-900 py-1.5 px-2 font-bold text-slate-900 bg-slate-50">Company:</td>
                    <td class="border border-slate-900 py-1.5 px-2 text-slate-950">
                        {{ $quotation->user->name ?? 'Valued Customer' }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-slate-900 py-1.5 px-2 font-bold text-slate-900 bg-slate-50">Address:</td>
                    <td class="border border-slate-900 py-1.5 px-2 text-slate-800">
                        {{ $quotation->user->address ?? 'Davao City' }}
                    </td>
                </tr>
                <tr>
                    <td class="border border-slate-900 py-1.5 px-2 font-bold text-slate-900 bg-slate-50">Contact No.:</td>
                    <td class="border border-slate-900 py-1.5 px-2 text-slate-800">
                        {{ $quotation->user->phone ?? '—' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Accounting Table: QTY | DESCRIPTION | U/P | AMOUNT --}}
        <div class="overflow-hidden border border-slate-900">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 font-bold uppercase text-[11px] text-slate-900 border-b border-slate-900">
                        <th class="py-2 px-3 w-[12%] text-center border-r border-slate-900">QTY</th>
                        <th class="py-2 px-4 w-[58%] border-r border-slate-900">DESCRIPTION</th>
                        <th class="py-2 px-3 w-[15%] text-right border-r border-slate-900">U/P</th>
                        <th class="py-2 px-3 w-[15%] text-right">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data Row --}}
                    <tr class="align-top border-b border-slate-300">
                        <td class="py-2.5 px-3 text-center font-bold text-slate-950 border-r border-slate-900">
                            {{ number_format($qty) }}
                        </td>
                        <td class="py-2.5 px-4 border-r border-slate-900">
                            <span class="font-bold text-slate-950 block">
                                {{ $quotation->printRequest->service ?? 'Custom Print Service' }}
                            </span>
                            <div class="text-[11px] text-slate-700 space-y-0.5 mt-0.5">
                                <p>{{ $quotation->printRequest->size ?? '' }}</p>
                                @if($quotation->printRequest->material)
                                    <p>{{ $quotation->printRequest->material }}</p>
                                @endif
                                @if($quotation->printRequest->finishing)
                                    <p>{{ $quotation->printRequest->finishing }}</p>
                                @endif
                                @if($quotation->notes)
                                    <p class="italic text-slate-600">{{ $quotation->notes }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 border-r border-slate-900 align-top">
                            {{ number_format($unitPrice, 2) }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-950 align-top">
                            {{ number_format($quotation->total_price, 2) }}
                        </td>
                    </tr>
                    {{-- 8 Empty Rows (Physical Form Style) --}}
                    @for($i = 0; $i < 8; $i++)
                    <tr class="border-b border-slate-200">
                        <td class="py-2 px-3 border-r border-slate-900">&nbsp;</td>
                        <td class="py-2 px-4 border-r border-slate-900">&nbsp;</td>
                        <td class="py-2 px-3 border-r border-slate-900">&nbsp;</td>
                        <td class="py-2 px-3">&nbsp;</td>
                    </tr>
                    @endfor
                    {{-- TOTAL Row --}}
                    <tr class="border-t border-slate-900 bg-slate-50 font-bold">
                        <td colspan="2" class="py-2.5 px-4 text-center font-black text-slate-900 border-r border-slate-900 uppercase tracking-wider text-xs">
                            TOTAL
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-900">&nbsp;</td>
                        <td class="py-2.5 px-3 text-right font-black font-mono text-slate-950">
                            {{ number_format($quotation->total_price, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Terms & Sign-off --}}
        <div class="pt-1 flex flex-col sm:flex-row items-end justify-between gap-6 text-xs">
            <div class="space-y-1">
                <span class="font-black text-slate-950 uppercase tracking-wider underline block">TERMS:</span>
                <p class="text-slate-800 font-medium">50% Downpayment</p>
                <p class="text-slate-800 font-medium">50% after completion</p>
                <p class="text-slate-800 font-medium">GCash No. 09176850444 Judith M</p>
                <div class="pt-1 flex items-baseline gap-2">
                    <span class="font-bold text-slate-950">Due Date:</span>
                    <span class="border-b border-slate-900 px-2 font-bold text-slate-950 min-w-[140px]">
                        {{ $quotation->valid_until ? $quotation->valid_until->format('F d, Y') : now()->addDays(7)->format('F d, Y') }}
                    </span>
                </div>
            </div>

            <div class="text-center shrink-0 min-w-[200px]">
                {{-- Signature space --}}
                <div class="h-10 border-b border-slate-900 w-full"></div>
                <div class="mt-1 flex items-center justify-between gap-2">
                    <span class="font-bold text-slate-900">Prepared by:</span>
                    <span class="font-bold text-slate-950 text-[11px]">
                        {{ auth()->user()->name ?? 'Customer Service' }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-700 text-right">Customer service</p>
            </div>
        </div>

    </div>

</div>
@endsection