@extends('layouts.internal')
@section('title', 'Capacity Bottleneck Monitor')
@section('page-title', 'Executive Capacity Monitor')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm space-y-4">
        <h3 class="font-bold text-cyber-main text-sm">Systemwide Capacity &amp; Machine Utilization</h3>
        <p class="text-xs text-cyber-muted">Overview of machine availability across all operational branches.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            @foreach(\App\Models\Branch::with('machines')->get() as $br)
            <div class="p-4 rounded-xl border border-cyber bg-cyber-sub/60 space-y-2">
                <strong class="text-cyber-main text-sm block">{{ $br->name }}</strong>
                <p class="text-cyber-muted">Machines: {{ $br->machines->count() }} ({{ $br->available_machines_count }} available)</p>
                <p class="text-cyber-muted">Max Daily Jobs: {{ $br->max_daily_jobs }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
