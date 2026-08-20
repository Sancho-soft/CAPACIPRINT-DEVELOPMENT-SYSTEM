<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation #{{ $quotation->quotation_number }} - Morning Star Printing Press</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { border: none !important; shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-6 font-sans text-slate-800">

    <div class="max-w-3xl mx-auto space-y-4">

        {{-- Print Action Bar --}}
        <div class="no-print flex items-center justify-between bg-navy-900 text-white px-6 py-3.5 rounded-2xl shadow-lg">
            <span class="text-xs font-bold font-display"><i class="fa-solid fa-file-pdf text-brand-400 mr-2"></i> OFFICIAL PRINTABLE QUOTATION</span>
            <button onclick="window.print()" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2 rounded-xl text-xs shadow transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print / Save as PDF
            </button>
        </div>

        {{-- Printable Card --}}
        <div class="print-card bg-white p-10 rounded-2xl border border-slate-200 shadow-sm space-y-8">
            {{-- Header --}}
            <div class="flex items-start justify-between border-b border-slate-200 pb-6">
                <div class="space-y-1">
                    <h1 class="text-2xl font-black text-navy-900 tracking-tight font-display">MORNING STAR</h1>
                    <p class="text-xs font-bold text-brand-600 uppercase tracking-widest">Printing Press Co.</p>
                    <p class="text-xs text-slate-500">123 E. Rodriguez Sr. Ave, Quezon City, Metro Manila</p>
                    <p class="text-xs text-slate-500">Tel: (02) 8123-4567 | Email: sales@capaciprint.com</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Official Quotation</span>
                    <h2 class="text-xl font-bold font-mono text-brand-600">#{{ $quotation->quotation_number }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Date: {{ $quotation->created_at->format('M d, Y') }}</p>
                    <p class="text-xs font-bold text-red-500">Valid Until: {{ $quotation->valid_until->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- Bill To & Service Details --}}
            <div class="grid grid-cols-2 gap-6 text-xs">
                <div class="space-y-1">
                    <p class="font-bold text-slate-400 uppercase tracking-wider">Customer Details:</p>
                    <p class="font-bold text-navy-900 text-sm">{{ $quotation->user->name ?? 'Valued Customer' }}</p>
                    <p class="text-slate-600">{{ $quotation->user->email ?? '—' }}</p>
                    <p class="text-slate-600">{{ $quotation->user->phone ?? '—' }}</p>
                    <p class="text-slate-600">{{ $quotation->user->address ?? '—' }}</p>
                </div>
                <div class="space-y-1 text-right">
                    <p class="font-bold text-slate-400 uppercase tracking-wider">Specifications:</p>
                    <p class="font-bold text-navy-900 text-sm">{{ $quotation->printRequest->service ?? 'Print Order' }}</p>
                    <p class="text-slate-600">Quantity: <strong>{{ number_format($quotation->printRequest->quantity ?? 1) }}</strong></p>
                    <p class="text-slate-600">Size: <strong>{{ $quotation->printRequest->size ?? 'Standard' }}</strong></p>
                    <p class="text-slate-600">Material: <strong>{{ $quotation->printRequest->material ?? 'Standard Paper' }}</strong></p>
                </div>
            </div>

            {{-- Cost Breakdown Table --}}
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Cost Component</th>
                        <th class="p-3.5 text-right">Amount (₱)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-800">Base Printing &amp; Machine Setup Rate</td>
                        <td class="p-3.5 text-right font-mono font-bold text-slate-900">₱{{ number_format($quotation->base_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-800">Raw Material &amp; Paper Stock Rate</td>
                        <td class="p-3.5 text-right font-mono font-bold text-slate-900">₱{{ number_format($quotation->material_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="p-3.5 font-semibold text-slate-800">Finishing &amp; Binding Operations Rate</td>
                        <td class="p-3.5 text-right font-mono font-bold text-slate-900">₱{{ number_format($quotation->finishing_cost, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                    <tr>
                        <td class="p-3.5 text-navy-900 uppercase text-sm">Total Estimated Amount</td>
                        <td class="p-3.5 text-right text-lg text-brand-600 font-mono">₱{{ number_format($quotation->total_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- Notes & Terms --}}
            <div class="border-t border-slate-200 pt-4 space-y-2 text-xs text-slate-500">
                <p class="font-bold text-slate-700">Terms &amp; Conditions:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Prices quoted are valid until {{ $quotation->valid_until->format('M d, Y') }}.</li>
                    <li>Production starts upon 50% deposit confirmation.</li>
                    <li>Estimated turnaround time starts upon final layout proof approval.</li>
                </ul>
            </div>

            {{-- Signature Footer --}}
            <div class="pt-8 flex justify-between items-end border-t border-slate-200">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Prepared By:</p>
                    <p class="font-bold text-navy-900 text-xs mt-4">Sales &amp; Quotations Department</p>
                    <p class="text-[10px] text-slate-500">Morning Star Printing Press Co.</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Customer Acceptance Signature:</p>
                    <div class="border-b border-slate-400 w-48 mt-8"></div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
