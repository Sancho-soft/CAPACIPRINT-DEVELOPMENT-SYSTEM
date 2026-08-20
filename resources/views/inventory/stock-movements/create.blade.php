@extends('layouts.internal')
@section('title', 'Record Stock Movement')
@section('page-title', 'Record Material Stock Movement')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">Record Stock In / Stock Out / Adjustment</h2>
        </div>

        <form method="POST" action="{{ route('inventory.stock-movements.store') }}" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-navy-900 mb-1">Target Branch <span class="text-red-500">*</span></label>
                    <select name="branch_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-navy-900 mb-1">Select Material <span class="text-red-500">*</span></label>
                    <select name="material_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        @foreach($materials as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->unit }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Movement Type <span class="text-red-500">*</span></label>
                    <select name="movement_type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="stock_in">Stock In (+ Add)</option>
                        <option value="stock_out">Stock Out (- Deduct)</option>
                        <option value="adjustment">Stock Adjustment (Set Exact)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="quantity" required min="0.01"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-bold focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Movement Date <span class="text-red-500">*</span></label>
                    <input type="date" name="movement_date" value="{{ date('Y-m-d') }}" required
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Reference / PO #</label>
                <input type="text" name="reference" placeholder="e.g. PO-98431"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Reason / Remarks</label>
                <input type="text" name="remarks" placeholder="e.g. Delivered from main warehouse"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('inventory.stock-movements.index') }}" class="text-slate-500 font-bold">&larr; Cancel</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Record Movement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
