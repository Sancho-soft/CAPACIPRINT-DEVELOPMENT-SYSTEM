@extends('layouts.internal')
@section('title', 'Update Job Status')
@section('page-title', 'Update Status for Job #' . $productionJob->job_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-5">
        <div>
            <h2 class="text-lg font-bold text-navy-900 font-display">Update Production Status</h2>
            <p class="text-xs text-slate-500">Select current production stage for Job #{{ $productionJob->job_number }}.</p>
        </div>

        <form method="POST" action="{{ route('production.jobs.update-status', $productionJob) }}" class="space-y-4 text-xs" x-data="{ status: '{{ $productionJob->status }}' }">
            @csrf

            <div>
                <label class="block font-bold text-navy-900 mb-1">Production Stage <span class="text-red-500">*</span></label>
                <select name="status" x-model="status" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold focus:border-brand-500 focus:outline-none">
                    <option value="assigned">Assigned</option>
                    <option value="preparing">Preparing Materials / Pre-press</option>
                    <option value="in_production">In Production (Printing)</option>
                    <option value="quality_checking">Quality Checking &amp; Packaging</option>
                    <option value="completed">Completed (Triggers QR Claim Code)</option>
                    <option value="delayed">Delayed (Requires Reason)</option>
                </select>
            </div>

            <div x-show="status === 'delayed'" class="space-y-1">
                <label class="block font-bold text-red-600 mb-1">Delay Reason <span class="text-red-500">*</span></label>
                <input type="text" name="delay_reason" placeholder="e.g. Paper out of stock or machine breakdown"
                       class="w-full rounded-xl border border-red-200 bg-red-50/50 px-3.5 py-2 text-xs focus:outline-none">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Production Notes / Remarks</label>
                <textarea name="remarks" rows="3" placeholder="e.g. Printed 500 copies on Heideberg press without issues"
                          class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">{{ $productionJob->remarks }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('production.jobs.show', $productionJob) }}" class="text-slate-500 font-bold">&larr; Cancel</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Update Production Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
