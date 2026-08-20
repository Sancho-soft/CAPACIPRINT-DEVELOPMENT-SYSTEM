@extends('layouts.internal')
@section('title', 'Evaluate Capacity')
@section('page-title', 'Run Multi-Factor Capacity Algorithm')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Request Overview --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">Print Request #PR-{{ str_pad($printRequest->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p class="text-xs text-slate-500">Service: <strong>{{ $printRequest->service }}</strong> &middot; Qty: <strong>{{ number_format($printRequest->quantity) }} pcs</strong> &middot; Customer: {{ $printRequest->user->name ?? '—' }}</p>
        </div>
        <form method="POST" action="{{ route('manager.capacity.run-evaluation', $printRequest) }}">
            @csrf
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow-md shadow-brand-500/20">
                <i class="fa-solid fa-bolt mr-1"></i> Compute &amp; Save Recommendation
            </button>
        </form>
    </div>

    {{-- Multi-Factor Algorithm Results Table --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden space-y-4 p-6">
        <h3 class="font-bold text-navy-900 text-sm">Branch Score Matrix Breakdown (Max 100 Points)</h3>

        <div class="grid grid-cols-1 gap-4">
            @foreach($evaluations as $eval)
            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <div class="flex items-center gap-3">
                        <h4 class="font-bold text-navy-900 text-base">{{ $eval['branch']->name }}</h4>
                        <span class="text-xs text-slate-500">({{ $eval['branch']->location }})</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ match($eval['capacity_status']) { 'qualified'=>'bg-emerald-100 text-emerald-800', 'near_capacity'=>'bg-amber-100 text-amber-800', default=>'bg-red-100 text-red-800' } }}">
                            {{ ucfirst(str_replace('_', ' ', $eval['capacity_status'])) }}
                        </span>
                        <div class="text-right">
                            <span class="text-2xl font-black font-display text-navy-900">{{ $eval['total_score'] }}</span>
                            <span class="text-xs text-slate-400 font-semibold">/100 pts</span>
                        </div>
                    </div>
                </div>

                {{-- Detailed Scoring Factors --}}
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Machine (30pts)</span>
                        <strong class="text-navy-900 text-sm">{{ $eval['machine_score'] }}</strong>
                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $eval['available_machines'] }} available</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Workload (20pts)</span>
                        <strong class="text-navy-900 text-sm">{{ $eval['workload_score'] }}</strong>
                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $eval['workload_pct'] }}% load</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Employee (20pts)</span>
                        <strong class="text-navy-900 text-sm">{{ $eval['employee_score'] }}</strong>
                        <p class="text-[10px] text-slate-500 mt-0.5">Staff available</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Material (20pts)</span>
                        <strong class="text-navy-900 text-sm">{{ $eval['material_score'] }}</strong>
                        <p class="text-[10px] text-slate-500 mt-0.5">Stock ready</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block text-[10px]">Deadline (10pts)</span>
                        <strong class="{{ $eval['deadline_feasible'] ? 'text-emerald-600' : 'text-red-600' }} text-sm">{{ $eval['deadline_score'] }}</strong>
                        <p class="text-[10px] text-slate-500 mt-0.5">Est: {{ $eval['estimated_completion'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
