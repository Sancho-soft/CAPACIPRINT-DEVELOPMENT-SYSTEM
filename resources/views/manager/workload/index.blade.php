@extends('layouts.internal')
@section('title', 'Workload Monitor')
@section('page-title', 'Branch Workload & Queue Monitor')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($branches as $b)
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
            <h3 class="font-bold text-navy-900 font-display text-base">{{ $b->name }}</h3>
            <div class="text-xs text-slate-600 space-y-1">
                <div class="flex justify-between"><span>Active Job Load:</span><strong class="text-slate-900">{{ $b->active_job_count }} jobs</strong></div>
                <div class="flex justify-between"><span>Delayed Jobs:</span><strong class="text-red-600">{{ $b->delayed_count }}</strong></div>
                <div class="flex justify-between"><span>Rush / Urgent:</span><strong class="text-amber-600">{{ $b->rush_count }}</strong></div>
            </div>
            <div class="space-y-1 pt-2">
                <div class="flex justify-between text-[10px] font-bold">
                    <span class="text-slate-400">Utilization</span>
                    <span class="text-navy-900 font-black">{{ $b->workload_percent }}%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500" style="width: {{ $b->workload_percent }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
