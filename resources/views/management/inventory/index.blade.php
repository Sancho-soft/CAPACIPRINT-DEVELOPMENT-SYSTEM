@extends('layouts.internal')
@section('title', 'Inventory Overview')
@section('page-title', 'Inventory Stock Overview')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Quantity</th>
                    <th class="px-6 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inventory as $inv)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $inv->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $inv->material->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-black text-slate-900">{{ number_format($inv->quantity, 2) }} {{ $inv->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $inv->status_badge_class }}">{{ $inv->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">No inventory data available.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $inventory->links() }}</div>
    </div>
</div>
@endsection
