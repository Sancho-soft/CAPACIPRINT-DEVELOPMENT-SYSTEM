@extends('layouts.internal')
@section('title', 'Quotations Management')
@section('page-title', 'Quotation Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Customer Quotations</h2>
        <a href="{{ route('staff.quotations.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
            <i class="fa-solid fa-plus mr-1"></i> Create Quotation
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Quotation #</th>
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
                    <td class="px-6 py-4 font-bold text-navy-900">#{{ $q->quotation_number }}</td>
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
                        <a href="{{ route('staff.quotations.show', $q) }}" class="text-brand-600 font-bold hover:underline">View &rarr;</a>
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
