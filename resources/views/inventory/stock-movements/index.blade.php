@extends('layouts.internal')
@section('title', 'Stock Movements History')
@section('page-title', 'Stock Movements History')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Stock Movement Records</h2>
        <a href="{{ route('inventory.stock-movements.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
            <i class="fa-solid fa-plus mr-1"></i> Record Stock Movement
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Quantity</th>
                    <th class="px-6 py-3.5">Recorded By</th>
                    <th class="px-6 py-3.5">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($movements as $m)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $m->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $m->material->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->movement_type_badge_class }}">
                            {{ $m->movement_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-900">{{ number_format($m->quantity, 2) }} {{ $m->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $m->user->name ?? 'System' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $m->movement_date?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No stock movements recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $movements->links() }}</div>
    </div>
</div>
@endsection
