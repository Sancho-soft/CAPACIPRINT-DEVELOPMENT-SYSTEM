@extends('layouts.internal')
@section('title', 'Capacity Bottlenecks Report')
@section('page-title', 'Executive Capacity Report')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($branches as $b)
        <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm space-y-3">
            <h3 class="font-bold text-cyber-main font-display text-base">{{ $b->name }}</h3>
            <div class="text-xs text-cyber-muted space-y-1">
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
