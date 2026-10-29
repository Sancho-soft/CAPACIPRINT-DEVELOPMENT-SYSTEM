<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation #{{ $quotation->quotation_number }} - Morning Star Printing Press Co.</title>
    <script src="https://cdn.tailwindcss.com" onerror="this.onerror=null; this.src='{{ asset('assets/js/tailwindcss.js') }}';"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" onerror="this.onerror=null; this.href='{{ asset('assets/fontawesome/css/all.min.css') }}';">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
            .print-card { border: none !important; box-shadow: none !important; padding: 0 !important; max-width: 100% !important; margin: 0 !important; }
            @page { margin: 12mm 15mm; size: auto; }
        }
        .form-line {
            border-bottom: 1px solid #1e293b;
            min-height: 22px;
            display: inline-block;
        }
        .quotation-grid-table th, .quotation-grid-table td {
            border: 1px solid #1e293b;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 sm:p-8 font-sans text-slate-900">

    <div class="max-w-4xl mx-auto space-y-4">

        {{-- Print Action Bar (Hidden when printing) --}}
        <div class="no-print flex items-center justify-between bg-slate-900 text-white px-6 py-3.5 rounded-2xl shadow-lg">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/morning-star-logo.png') }}" alt="Logo" class="h-8 w-8 object-contain rounded-full bg-white p-0.5">
                <div>
                    <span class="text-xs font-black font-display tracking-wide block">MORNING STAR PRINTING PRESS CO.</span>
                    <span class="text-[10px] text-slate-400">Official Printable Quotation Form &middot; #{{ $quotation->quotation_number }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.history.back()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold px-4 py-2 rounded-xl text-xs transition">
                    &larr; Back
                </button>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-5 py-2 rounded-xl text-xs shadow transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Print / Save as PDF
                </button>
            </div>
        </div>

        {{-- Printable Card (Replicates Physical Quotation Sheet) --}}
        <div class="print-card bg-white p-8 sm:p-12 rounded-2xl border border-slate-300 shadow-md space-y-5">

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
            @php
                $qty = max(1, (int)($quotation->printRequest->quantity ?? 1));
                $unitPrice = $quotation->total_price / $qty;
                $downPayment = $quotation->total_price * 0.50;
                $balancePayment = $quotation->total_price * 0.50;
            @endphp
            <div class="overflow-hidden">
                <table class="w-full text-left text-xs quotation-grid-table border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80 font-bold uppercase text-[11px] text-slate-900">
                            <th class="py-2 px-3 w-[12%] text-center">QTY</th>
                            <th class="py-2 px-4 w-[58%]">DESCRIPTION</th>
                            <th class="py-2 px-3 w-[15%] text-right">U/P</th>
                            <th class="py-2 px-3 w-[15%] text-right">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Primary Item Row --}}
                        <tr class="align-top">
                            <td class="py-2.5 px-3 text-center font-bold text-slate-950">
                                {{ number_format($qty) }}
                            </td>
                            <td class="py-2.5 px-4">
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
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 align-top">
                                {{ number_format($unitPrice, 2) }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-950 align-top">
                                {{ number_format($quotation->total_price, 2) }}
                            </td>
                        </tr>
                        {{-- 8 Empty Rows (Physical Form Style) --}}
                        @for($i = 0; $i < 8; $i++)
                        <tr>
                            <td class="py-2 px-3">&nbsp;</td>
                            <td class="py-2 px-4">&nbsp;</td>
                            <td class="py-2 px-3">&nbsp;</td>
                            <td class="py-2 px-3">&nbsp;</td>
                        </tr>
                        @endfor
                        {{-- TOTAL Row --}}
                        <tr class="font-bold bg-slate-50">
                            <td colspan="2" class="py-2.5 px-4 text-center font-black text-slate-900 uppercase tracking-wider text-xs">
                                TOTAL
                            </td>
                            <td class="py-2.5 px-3">&nbsp;</td>
                            <td class="py-2.5 px-3 text-right font-black font-mono text-slate-950">
                                {{ number_format($quotation->total_price, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer: Terms (Left) | Prepared By (Right) --}}
            <div class="pt-1 flex flex-col sm:flex-row items-end justify-between gap-6 text-xs">
                <div class="space-y-1">
                    <span class="font-black text-slate-950 uppercase tracking-wider underline block">TERMS:</span>
                    <p class="text-slate-800 font-medium">50% Downpayment</p>
                    <p class="text-slate-800 font-medium">50% after completion</p>
                    <p class="text-slate-800 font-medium">GCash No. 09176850444 Judith M</p>
                    <div class="pt-1 flex items-baseline gap-2">
                        <span class="font-bold text-slate-950">Due Date:</span>
                        <span class="form-line px-2 font-bold text-slate-950 min-w-[140px]">
                            {{ $quotation->valid_until ? $quotation->valid_until->format('F d, Y') : now()->addDays(7)->format('F d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="text-center shrink-0 min-w-[200px]">
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

</body>
</html>