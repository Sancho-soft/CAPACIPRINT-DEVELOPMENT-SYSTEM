@extends('layouts.internal')
@section('title', 'Assign Production Job')
@section('page-title', 'Schedule Production Job #' . $productionJob->job_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h2 class="text-lg font-bold text-navy-900 font-display">Assign Staff &amp; Machine</h2>

        <form method="POST" action="{{ route('manager.production-planning.assign', $productionJob) }}" class="space-y-4 text-xs">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Select Machine</label>
                    <select name="machine_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="">-- Choose Machine --</option>
                        @foreach($machines as $m)
                        <option value="{{ $m->id }}" {{ $productionJob->machine_id == $m->id ? 'selected' : '' }}>
                            {{ $m->name }} ({{ $m->type }} - {{ $m->status_label }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Assign Production Staff</label>
                    <select name="assigned_to" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="">-- Choose Staff Member --</option>
                        @foreach($staff as $st)
                        <option value="{{ $st->id }}" {{ $productionJob->assigned_to == $st->id ? 'selected' : '' }}>
                            {{ $st->name }} ({{ $st->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Estimated Completion Hours</label>
                    <input type="number" name="estimated_hours" value="{{ $productionJob->estimated_hours ?? 4 }}" min="1"
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Priority Level</label>
                    <select name="priority" class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="normal" {{ $productionJob->priority == 'normal' ? 'selected' : '' }}>Normal Priority</option>
                        <option value="rush" {{ $productionJob->priority == 'rush' ? 'selected' : '' }}>Rush Order</option>
                        <option value="urgent" {{ $productionJob->priority == 'urgent' ? 'selected' : '' }}>Urgent / Express</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('manager.production-planning.index') }}" class="text-slate-500 font-bold">&larr; Back</a>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2 rounded-xl shadow-md shadow-brand-500/20">
                    Save Assignment &amp; Notify Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
