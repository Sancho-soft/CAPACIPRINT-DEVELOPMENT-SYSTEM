@extends('layouts.internal')
@section('title', 'Inventory Reports')
@section('page-title', 'Material Inventory Reports')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h3 class="font-bold text-navy-900 text-sm">Stock Health Summary</h3>
        <div class="grid grid-cols-3 gap-4 text-xs">
            <div class="bg-slate-50 p-4 rounded-xl text-center"><span class="text-slate-400 block">Total Active Materials</span><strong class="text-xl font-bold text-navy-900">{{ $totalMaterials }}</strong></div>
            <div class="bg-amber-50 p-4 rounded-xl text-center"><span class="text-amber-700 block">Low Stock Items</span><strong class="text-xl font-bold text-amber-900">{{ $lowStockCount }}</strong></div>
            <div class="bg-red-50 p-4 rounded-xl text-center"><span class="text-red-700 block">Out of Stock Items</span><strong class="text-xl font-bold text-red-900">{{ $outOfStockCount }}</strong></div>
        </div>
    </div>
</div>
@endsection
