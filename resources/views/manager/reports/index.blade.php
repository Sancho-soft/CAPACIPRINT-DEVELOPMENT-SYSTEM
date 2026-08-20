@extends('layouts.internal')
@section('title', 'Operational Reports')
@section('page-title', 'Manager Operational Reports')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('manager.reports.production') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Production Performance Report</h3>
            <p class="text-xs text-slate-500 mt-1">Completion rates, delayed job logs, and priority breakdowns.</p>
        </a>
        <a href="{{ route('manager.reports.capacity') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Capacity Utilization Report</h3>
            <p class="text-xs text-slate-500 mt-1">Branch machine workload analysis and daily job thresholds.</p>
        </a>
    </div>
</div>
@endsection
