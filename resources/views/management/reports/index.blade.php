@extends('layouts.internal')
@section('title', 'Executive Reports')
@section('page-title', 'Executive Reports & Analytics')

@section('content')
<div class="space-y-6 max-w-5xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('management.reports.orders') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Orders Summary Report</h3>
            <p class="text-xs text-slate-500 mt-1">Full audit of all customer orders, payment confirmations, and fulfillment.</p>
        </a>
        <a href="{{ route('management.reports.production') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Production Output Report</h3>
            <p class="text-xs text-slate-500 mt-1">Production efficiency, delay tracking, and staff assignments.</p>
        </a>
        <a href="{{ route('management.reports.inventory') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Inventory Stock Report</h3>
            <p class="text-xs text-slate-500 mt-1">Material consumption and re-order warnings across branches.</p>
        </a>
        <a href="{{ route('management.reports.capacity') }}" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:border-brand-500 transition block">
            <h3 class="font-bold text-navy-900 text-base font-display">Branch Capacity Analysis</h3>
            <p class="text-xs text-slate-500 mt-1">Strategic view of machine availability and capacity bottlenecks.</p>
        </a>
    </div>
</div>
@endsection
