@extends('layouts.internal')
@section('title', 'Materials Catalog')
@section('page-title', 'Materials Catalog & Specification')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-500 flex items-center justify-center text-lg shrink-0 shadow-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-cyber-main font-display">Printing Materials Catalog</h2>
                <p class="text-xs text-cyber-muted mt-0.5">Manage raw materials, media substrates, and packaging specs.</p>
            </div>
        </div>
        <a href="{{ route('inventory.materials.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs shadow-[0_0_20px_rgba(6,182,212,0.3)] transition flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus text-xs"></i> Add New Material
        </a>
    </div>

    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-cyber-base/80 text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber text-[10px]">
                <tr>
                    <th class="px-6 py-3.5">Material Name</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Unit</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber/60 text-cyber-main">
                @forelse($materials as $m)
                <tr class="hover:bg-cyber-hover/50 transition">
                    <td class="px-6 py-4 font-bold text-cyber-main">{{ $m->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-cyber-muted">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            {{ $m->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-cyber-muted font-medium">{{ $m->unit }}</td>
                    <td class="px-6 py-4">
                        @if($m->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 shadow-xs">
                                <i class="fa-solid fa-circle-check text-[9px]"></i> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-xs">
                                <i class="fa-solid fa-circle-pause text-[9px]"></i> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-2">
                            {{-- View Material Specification --}}
                            <a href="{{ route('inventory.materials.show', $m) }}" 
                               class="group h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-white dark:hover:text-slate-950 border border-cyan-500/20 hover:border-cyan-500 flex items-center justify-center text-xs transition-all duration-200 shadow-xs" 
                               title="View Material Specification">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            {{-- Edit Material Details --}}
                            <a href="{{ route('inventory.materials.edit', $m) }}" 
                               class="group h-8 w-8 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 dark:text-amber-400 hover:text-white border border-amber-500/20 hover:border-amber-500 flex items-center justify-center text-xs transition-all duration-200 shadow-xs" 
                               title="Edit Material Details">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-cyber-muted text-xs">No materials found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-cyber/60">{{ $materials->links() }}</div>
    </div>
</div>
@endsection
