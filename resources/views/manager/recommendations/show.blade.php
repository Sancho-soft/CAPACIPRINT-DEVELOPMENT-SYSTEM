@extends('layouts.internal')
@section('title', 'Confirm Recommendation')
@section('page-title', 'Confirm / Override Branch Assignment')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h2 class="text-lg font-bold text-navy-900 font-display">System Recommendation Breakdown</h2>

        <div class="p-4 bg-brand-50/50 border border-brand-100 rounded-xl space-y-2 text-xs text-brand-900">
            <div class="flex items-center justify-between">
                <span class="font-bold text-sm">Recommended Branch: {{ $recommendation->recommendedBranch->name ?? '—' }}</span>
                <span class="text-lg font-black font-display text-navy-900">{{ $recommendation->recommendation_score }}/100 pts</span>
            </div>
            <p class="text-slate-600">{{ $recommendation->reason }}</p>
        </div>

        {{-- Override Form --}}
        <form method="POST" action="{{ route('manager.recommendations.confirm', $recommendation) }}" class="space-y-4 pt-2 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-navy-900 mb-1">Confirm or Select Alternative Branch</label>
                <select name="override_branch_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                    <option value="{{ $recommendation->recommended_branch_id }}">Use System Recommendation ({{ $recommendation->recommendedBranch->name ?? 'Recommended' }})</option>
                    @foreach(\App\Models\Branch::where('status', 'active')->get() as $br)
                        @if($br->id != $recommendation->recommended_branch_id)
                        <option value="{{ $br->id }}">Override: Assign to {{ $br->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Override Reason (Required if changing branch)</label>
                <input type="text" name="override_reason" placeholder="e.g. Requested by customer or equipment maintenance"
                       class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('manager.recommendations.index') }}" class="text-slate-500 font-bold">&larr; Back</a>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-emerald-600/20">
                    <i class="fa-solid fa-check mr-1"></i> Confirm Branch Assignment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
