@extends('layouts.internal')
@section('title', 'Edit ' . $material->name)
@section('page-title', 'Edit Material Specification')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('inventory.materials.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Catalog
        </a>
        <a href="{{ route('inventory.materials.show', $material) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 hover:text-cyan-700 transition">
            <i class="fa-solid fa-eye"></i> View Details
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">Edit Material: {{ $material->name }}</h2>
            <p class="text-xs text-slate-400 mt-0.5">Update catalog specifications and availability state.</p>
        </div>

        <form method="POST" action="{{ route('inventory.materials.update', $material) }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-navy-900 mb-1">Material Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $material->name) }}" required placeholder="e.g. Glossy Paper 80gsm A4"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Material Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        @foreach(['paper' => 'Paper / Media', 'ink' => 'Ink / Toner', 'lamination' => 'Lamination', 'binding' => 'Binding Wire / Comb', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $material->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Unit of Measurement <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $material->unit) }}" required placeholder="e.g. reams, cartridges, rolls"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Additional specifications..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">{{ old('description', $material->description) }}</textarea>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between">
                <div>
                    <p class="font-bold text-navy-900">Active Status</p>
                    <p class="text-[11px] text-slate-500">Inactive materials are hidden from new print job allocations.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $material->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('inventory.materials.index') }}" class="text-slate-500 font-bold">&larr; Cancel</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
