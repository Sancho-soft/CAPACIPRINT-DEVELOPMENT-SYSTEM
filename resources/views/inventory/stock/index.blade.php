@extends('layouts.internal')
@section('title', 'Branch Stock Levels')
@section('page-title', 'Branch Inventory & Stock Levels')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Current Stock</th>
                    <th class="px-6 py-3.5">Minimum Stock</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Quick Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inventory as $inv)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $inv->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $inv->material->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-black text-navy-900 text-sm">{{ number_format($inv->quantity, 2) }} {{ $inv->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ number_format($inv->minimum_stock, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $inv->status_badge_class }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form method="POST" action="{{ route('inventory.stock.update', $inv) }}" class="flex items-center justify-end gap-2">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $inv->quantity }}" step="0.1" min="0" class="w-20 rounded-lg border border-slate-200 px-2 py-1 text-xs font-bold text-right">
                            <input type="hidden" name="minimum_stock" value="{{ $inv->minimum_stock }}">
                            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3 py-1 rounded-lg text-[10px]">Set</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No inventory entries found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $inventory->links() }}</div>
    </div>
</div>
@endsection
