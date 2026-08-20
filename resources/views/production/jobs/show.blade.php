@extends('layouts.internal')
@section('title', 'Job Details')
@section('page-title', 'Job #' . $productionJob->job_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4 text-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-lg font-bold text-navy-900 font-display">Job #{{ $productionJob->job_number }}</h2>
                <p class="text-slate-500">Assigned Branch: <strong>{{ $productionJob->branch->name ?? '—' }}</strong></p>
            </div>
            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase {{ $productionJob->status_badge_class }}">
                {{ $productionJob->status_label }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div><span class="text-slate-400 block">Service</span><strong class="text-navy-900 text-sm">{{ $productionJob->order->printRequest->service ?? '—' }}</strong></div>
            <div><span class="text-slate-400 block">Quantity</span><strong class="text-navy-900 text-sm">{{ number_format($productionJob->order->printRequest->quantity ?? 0) }} pcs</strong></div>
            <div><span class="text-slate-400 block">Size / Specs</span><strong class="text-slate-800">{{ $productionJob->order->printRequest->size ?? '—' }}</strong></div>
            <div><span class="text-slate-400 block">Finishing</span><strong class="text-slate-800">{{ $productionJob->order->printRequest->finishing ?? '—' }}</strong></div>
            <div><span class="text-slate-400 block">Assigned Machine</span><strong class="text-brand-600">{{ $productionJob->machine->name ?? 'Not Set' }}</strong></div>
            <div><span class="text-slate-400 block">Priority</span><strong class="text-amber-600 uppercase">{{ $productionJob->priority }}</strong></div>
        </div>

        @if($productionJob->order->printRequest->design_file_path)
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <span class="font-bold text-slate-700">Design Artwork File:</span>
            <a href="{{ Storage::url($productionJob->order->printRequest->design_file_path) }}" target="_blank" class="bg-brand-50 text-brand-600 font-bold px-3 py-1.5 rounded-lg">
                <i class="fa-solid fa-download mr-1"></i> Open Artwork File
            </a>
        </div>
        @endif

        <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('production.jobs.index') }}" class="text-slate-500 font-bold">&larr; Back to My Jobs</a>
            <a href="{{ route('production.jobs.status-form', $productionJob) }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-5 py-2 rounded-xl">
                Update Production Progress &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
