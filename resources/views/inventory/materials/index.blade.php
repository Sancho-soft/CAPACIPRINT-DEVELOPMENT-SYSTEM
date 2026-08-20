@extends('layouts.internal')
@section('title', 'Materials Catalog')
@section('page-title', 'Materials Catalog & Specification')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-navy-900 font-display">Printing Materials Catalog</h2>
        <a href="{{ route('inventory.materials.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20">
            <i class="fa-solid fa-plus mr-1"></i> Add New Material
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3.5">Material Name</th>
                    <th class="px-6 py-3.5">Type</th>
                    <th class="px-6 py-3.5">Unit</th>
                    <th class="px-6 py-3.5">Active</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materials as $m)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $m->name }}</td>
                    <td class="px-6 py-4 text-slate-700 font-semibold">{{ $m->type_label }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $m->unit }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $m->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <a href="{{ route('inventory.materials.show', $m) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3 py-1.5 rounded-lg text-xs">View</a>
                        <a href="{{ route('inventory.materials.edit', $m) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3 py-1.5 rounded-lg text-xs">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">No materials found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $materials->links() }}</div>
    </div>
</div>
@endsection
