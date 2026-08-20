@extends('layouts.internal')
@section('title', 'Capacity Bottleneck Monitor')
@section('page-title', 'Executive Capacity Monitor')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h3 class="font-bold text-navy-900 text-sm">Systemwide Capacity &amp; Machine Utilization</h3>
        <p class="text-xs text-slate-500">Overview of machine availability across all operational branches.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            @foreach(\App\Models\Branch::with('machines')->get() as $br)
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-2">
                <strong class="text-navy-900 text-sm block">{{ $br->name }}</strong>
                <p class="text-slate-600">Machines: {{ $br->machines->count() }} ({{ $br->available_machines_count }} available)</p>
                <p class="text-slate-600">Max Daily Jobs: {{ $br->max_daily_jobs }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
