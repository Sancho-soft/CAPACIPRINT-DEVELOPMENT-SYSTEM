@extends('layouts.internal')
@section('title', 'Capacity Bottlenecks Report')
@section('page-title', 'Executive Capacity Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($branches as $b)
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-3">
            <h3 class="font-bold text-navy-900 font-display text-base">{{ $b->name }}</h3>
            <div class="text-xs text-slate-600 space-y-1">
                <div class="flex justify-between"><span>Machines Count:</span><strong>{{ $b->machines->count() }}</strong></div>
                <div class="flex justify-between"><span>Max Daily Capacity:</span><strong>{{ $b->max_daily_jobs }} jobs</strong></div>
                <div class="flex justify-between"><span>Active Jobs:</span><strong>{{ $b->productionJobs->count() }}</strong></div>
                <div class="flex justify-between"><span>Utilization:</span><strong class="text-brand-600 font-bold">{{ $b->workload_percent }}%</strong></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
