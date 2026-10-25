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
        <div class="print-card bg-white p-8 sm:p-12 rounded-2xl border border-slate-300 shadow-md space-y-7">
            
            {{-- Header: Logo + Company Info (Left) | QUOTATION (Right) --}}
            <div class="flex items-start justify-between gap-4 border-b border-slate-900 pb-5">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/morning-star-logo.png') }}" 
                         alt="Morning Star Printing Press Co." 
                         class="h-20 w-20 object-contain shrink-0">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-950 tracking-tight font-display uppercase leading-tight">
                            MORNING STAR
                        </h1>
                        <h2 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider">
                            PRINTING PRESS CO.
                        </h2>
                        <p class="text-[11px] text-slate-700 font-medium mt-1">G/F La Fontana Bldg., Bolton Street, Davao City</p>
                        <p class="text-[11px] text-slate-700 font-medium">315-1064 &bull; 0917 685 0444 &bull; 0933 859 8980</p>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-950 font-display tracking-tight uppercase">
                        QUOTATION
                    </h2>
                    <span class="inline-block mt-1 font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-300">
                        #{{ $quotation->quotation_number }}
                    </span>
                </div>
            </div>

            {{-- Customer & Transaction Meta (Matching physical underline format) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-xs">
                {{-- Left: Customer & Address --}}
                <div class="space-y-3">
                    <div class="flex items-baseline">
                        <span class="font-bold text-slate-900 w-32 shrink-0">Customer Name:</span>
                        <span class="form-line flex-1 font-semibold text-slate-950 px-2">
                            {{ $quotation->user->name ?? 'Valued Customer' }}
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span class="font-bold text-slate-900 w-32 shrink-0">Address:</span>
                        <span class="form-line flex-1 text-slate-800 px-2">
                            {{ $quotation->user->address ?? 'Davao City' }}
                        </span>
                    </div>
                </div>

                {{-- Right: Date & Contact No. --}}
                <div class="space-y-3">
                    <div class="flex items-baseline">
                        <span class="font-bold text-slate-900 w-28 shrink-0">Date:</span>
                        <span class="form-line flex-1 font-semibold text-slate-950 px-2">
                            {{ $quotation->created_at->format('F d, Y') }}
                        </span>
                    </div>
                    <div class="flex items-baseline">
                        <span class="font-bold text-slate-900 w-28 shrink-0">Contact No.:</span>
                        <span class="form-line flex-1 text-slate-800 px-2">
                            {{ $quotation->user->phone ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Accounting Table: Quantity | Description | Unit Price | Amount --}}
            @php
                $qty = max(1, (int)($quotation->printRequest->quantity ?? 1));
                $unitPrice = $quotation->total_price / $qty;
                $downPayment = $quotation->total_price * 0.80;
                $balancePayment = $quotation->total_price * 0.20;
            @endphp
            <div class="overflow-hidden">
                <table class="w-full text-left text-xs quotation-grid-table border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80 font-bold uppercase text-[11px] text-slate-900">
                            <th class="py-2.5 px-3 w-[15%] text-center">Quantity</th>
                            <th class="py-2.5 px-4 w-[55%]">Description</th>
                            <th class="py-2.5 px-3 w-[15%] text-right">Unit Price</th>
                            <th class="py-2.5 px-4 w-[15%] text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Primary Item Row --}}
                        <tr class="align-top">
                            <td class="py-3 px-3 text-center font-bold text-slate-950">
                                {{ number_format($qty) }} {{ $qty > 1 ? 'pcs' : 'pc' }}
                            </td>
                            <td class="py-3 px-4 space-y-1">
                                <span class="font-black text-sm text-slate-950 block">
                                    {{ $quotation->printRequest->service ?? 'Custom Print Service' }}
                                </span>
                                <div class="text-[11px] text-slate-700 space-y-0.5">
                                    <p><strong>Size:</strong> {{ $quotation->printRequest->size ?? 'Standard Specification' }}</p>
                                    <p><strong>Material:</strong> {{ $quotation->printRequest->material ?? 'Standard Media' }}</p>
                                    @if($quotation->printRequest->finishing)
                                        <p><strong>Finishing / Binding:</strong> {{ $quotation->printRequest->finishing }}</p>
                                    @endif
                                    @if($quotation->notes)
                                        <p class="italic text-slate-600 mt-1"><strong>Special Notes:</strong> {{ $quotation->notes }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">
                                ₱{{ number_format($unitPrice, 2) }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-950">
                                ₱{{ number_format($quotation->total_price, 2) }}
                            </td>
                        </tr>



                        {{-- Total Summary Row --}}
                        <tr class="font-bold text-sm bg-slate-50">
                            <td colspan="2" class="border-r-0 py-2.5 px-4 text-slate-600 text-xs italic">
                                Official pricing inclusive of standard materials, pre-press setup &amp; processing.
                            </td>
                            <td class="py-2.5 px-3 text-right uppercase tracking-wider font-black text-slate-900 border-l-0">
                                Total:
                            </td>
                            <td class="py-2.5 px-4 text-right font-black font-mono text-base text-slate-950">
                                ₱{{ number_format($quotation->total_price, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer: Terms (Left) | Prepared By (Right) --}}
            <div class="pt-2 flex flex-col sm:flex-row items-start justify-between gap-8 text-xs">
                {{-- Terms & Due Date --}}
                <div class="space-y-2 min-w-[280px]">
                    <div>
                        <span class="font-black text-slate-950 uppercase tracking-wider underline block mb-1">Terms:</span>
                        <div class="pl-4 space-y-1 text-slate-800 font-medium">
                            <p>: 80% down payment <span class="font-bold text-slate-950 font-mono">(₱{{ number_format($downPayment, 2) }})</span></p>
                            <p>: 20% after completion <span class="font-bold text-slate-950 font-mono">(₱{{ number_format($balancePayment, 2) }})</span></p>
                        </div>
                    </div>
                    
                    <div class="pt-2 flex items-baseline gap-2">
                        <span class="font-bold text-slate-950">Due Date:</span>
                        <span class="form-line px-2 font-bold text-slate-950 min-w-[160px]">
                            {{ $quotation->valid_until ? $quotation->valid_until->format('F d, Y') : now()->addDays(7)->format('F d, Y') }}
                        </span>
                    </div>
                </div>

                {{-- Sign-off: Prepared By Customer Service --}}
                <div class="text-center sm:text-right shrink-0 w-full sm:w-auto">
                    <p class="font-bold text-slate-900 mb-10 text-left sm:text-right">Prepared by:</p>
                    <div class="inline-block text-center min-w-[220px]">
                        <div class="border-b border-slate-900 pb-1">
                            <span class="font-bold text-slate-950 block text-xs">{{ auth()->user()->name ?? 'Alex Beniga' }}</span>
                        </div>
                        <span class="font-black text-slate-950 uppercase tracking-wider text-[11px] block mt-1">
                            Customer Service
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>