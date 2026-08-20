@extends('layouts.internal')
@section('title', 'Add Material')
@section('page-title', 'Add New Printing Material')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">New Material Specification</h2>
        </div>

        <form method="POST" action="{{ route('inventory.materials.store') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-navy-900 mb-1">Material Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Glossy Paper 80gsm A4"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Material Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="paper">Paper / Media</option>
                        <option value="ink">Ink / Toner</option>
                        <option value="lamination">Lamination</option>
                        <option value="binding">Binding Wire / Comb</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Unit of Measurement <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" required placeholder="e.g. reams, cartridges, rolls"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Additional specifications..."
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('inventory.materials.index') }}" class="text-slate-500 font-bold">&larr; Cancel</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Add Material
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
