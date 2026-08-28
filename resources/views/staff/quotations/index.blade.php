@extends('layouts.internal')
@section('title', 'Quotations Management')
@section('page-title', 'Quotation Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Customer Quotations</h2>
            <p class="text-sm text-slate-500 mt-1">Manage price estimates, track quotation statuses, and issue formal offers.</p>
        </div>
        <a href="{{ route('staff.quotations.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-brand-500/20 transition flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-xs"></i> Create Quotation
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Quotation No.</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Total Amount</th>
                    <th class="px-6 py-3.5">Valid Until</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($quotations as $q)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $q->quotation_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $q->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $q->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-black text-navy-900 text-sm">₱{{ number_format($q->total_price, 2) }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $q->valid_until?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $q->status_badge_class }}">
                            {{ $q->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('staff.quotations.show', $q) }}"
                           class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                           title="View Quotation Details">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">No quotations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $quotations->links() }}</div>
    </div>
</div>
@endsection
