@extends('layouts.internal')
@section('title', 'Branch Network Performance')
@section('page-title', 'Branch Network Performance')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl">
    @foreach($branches as $b)
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <div>
            <h3 class="font-bold text-navy-900 text-lg font-display">{{ $b->name }}</h3>
            <p class="text-xs text-slate-500">{{ $b->location }} &middot; {{ $b->manager_name }}</p>
        </div>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="bg-slate-50 p-2.5 rounded-xl"><span class="text-slate-400 block text-[10px]">Active</span><strong class="text-navy-900 text-sm">{{ $b->active_jobs }}</strong></div>
            <div class="bg-emerald-50 p-2.5 rounded-xl"><span class="text-emerald-700 block text-[10px]">Completed</span><strong class="text-emerald-900 text-sm">{{ $b->completed_jobs }}</strong></div>
            <div class="bg-red-50 p-2.5 rounded-xl"><span class="text-red-700 block text-[10px]">Delayed</span><strong class="text-red-900 text-sm">{{ $b->delayed_jobs }}</strong></div>
        </div>
        <div class="space-y-1 pt-1">
            <div class="flex justify-between text-[10px] font-bold">
                <span class="text-slate-400">Capacity Load</span>
                <span class="text-navy-900">{{ $b->workload_percent }}%</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-brand-500" style="width: {{ $b->workload_percent }}%"></div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
