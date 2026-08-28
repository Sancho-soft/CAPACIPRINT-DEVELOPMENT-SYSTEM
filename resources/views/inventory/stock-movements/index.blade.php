@extends('layouts.internal')
@section('title', 'Stock Movements History')
@section('page-title', 'Stock Movements History')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-right-left"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-cyber-main font-display">Stock Movement Records</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Track all material inflows, outflows, and adjustments across branches.</p>
            </div>
        </div>
        <a href="{{ route('inventory.stock-movements.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.35)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus text-xs"></i> Record Stock Movement
        </a>
    </div>

    <div class="bg-[#111A24] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-[#0D1520]/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-800/80">
                <tr>
                    <th class="px-6 py-3.5">Branch</th>
                    <th class="px-6 py-3.5">Material</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Quantity</th>
                    <th class="px-6 py-3.5">Recorded By</th>
                    <th class="px-6 py-3.5">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-slate-300">
                @forelse($movements as $m)
                <tr class="hover:bg-slate-800/40 transition">
                    <td class="px-6 py-4 font-bold text-white">{{ $m->branch->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-300">{{ $m->material->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->movement_type_badge_class }}">
                            {{ $m->movement_type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-white font-mono">{{ number_format($m->quantity, 2) }} {{ $m->material->unit ?? 'units' }}</td>
                    <td class="px-6 py-4 text-slate-400">{{ $m->user->name ?? 'System' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $m->movement_date?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">No stock movements recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-800/80">{{ $movements->links() }}</div>
    </div>
</div>
@endsection

