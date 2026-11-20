@extends('layouts.internal')
@section('title', 'Schedule Production Job #' . $productionJob->job_number)
@section('page-title', 'Production Job Scheduling & Machine Routing')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('manager.production-planning.index') }}" class="inline-flex items-center gap-2 text-sm text-cyber-muted hover:text-cyber-main transition font-medium">
            <i class="fa-solid fa-arrow-left"></i> Back to Production Planning Queue
        </a>
    </div>

    {{-- Job Overview Banner --}}
    <div class="bg-cyber-card text-cyber-main border border-cyber p-6 rounded-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold font-display text-cyber-main">Job #{{ $productionJob->job_number }}</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $productionJob->priority_badge_class }}">
                    {{ $productionJob->priority }} Priority
                </span>
            </div>
            <p class="text-xs sm:text-sm text-cyber-muted mt-1">
                Order #{{ $productionJob->order->order_number ?? '—' }} &bull; Customer: {{ $productionJob->order->user->name ?? 'Customer' }} ({{ $productionJob->order->user->email ?? '—' }})
            </p>
        </div>

        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-cyber-sub text-cyber-main border border-cyber">
            {{ $productionJob->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Job Specifications Box --}}
        <div class="bg-cyber-card p-6 rounded-2xl border border-cyber shadow-sm space-y-4">
            <h3 class="font-bold text-cyber-main font-display text-sm border-b border-cyber pb-3 flex items-center gap-2">
                <i class="fa-solid fa-file-invoice text-amber-500"></i> Print Specifications
            </h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-cyber-muted">Service:</span><strong class="text-cyber-main">{{ $productionJob->order->printRequest->service ?? '—' }}</strong></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-cyber-muted">Quantity:</span><strong class="text-cyber-main">{{ number_format($productionJob->order->printRequest->quantity ?? 1) }} copies</strong></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-cyber-muted">Dimensions:</span><span class="text-cyber-main">{{ $productionJob->order->printRequest->size ?? '—' }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-cyber-muted">Material:</span><span class="text-cyber-main">{{ $productionJob->order->printRequest->material ?? '—' }}</span></div>
                <div class="flex justify-between py-1 border-b border-slate-50"><span class="text-cyber-muted">Finishing:</span><span class="text-cyber-main">{{ $productionJob->order->printRequest->finishing ?? 'None' }}</span></div>
                <div class="flex justify-between py-1"><span class="text-cyber-muted">Target Deadline:</span><strong class="text-amber-700">{{ $productionJob->order->printRequest->deadline?->format('M d, Y') ?? 'Flexible' }}</strong></div>
            </div>
        </div>

        {{-- Scheduling & Assignment Form --}}
        <div class="md:col-span-2 bg-cyber-card p-6 sm:p-8 rounded-2xl border border-cyber shadow-sm space-y-6">
            <div>
                <h3 class="font-bold text-cyber-main font-display text-base">Schedule Job &amp; Allocate Resources</h3>
                <p class="text-xs text-cyber-muted">Route job to target branch, assign specific production equipment, and delegate to a machine technician.</p>
            </div>

            <form method="POST" action="{{ route('manager.production-planning.assign', $productionJob) }}" class="space-y-5 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Target Branch --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Production Branch *</label>
                        <select name="branch_id" required class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs font-semibold">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $productionJob->branch_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }} ({{ $b->location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Machine Allocation --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Target Machine / Equipment</label>
                        <select name="machine_id" class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs font-semibold">
                            <option value="">-- Auto-Allocate / Any Machine --</option>
                            @foreach($machines as $m)
                                <option value="{{ $m->id }}" {{ $productionJob->machine_id == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} ({{ $m->type }} — {{ $m->status_label }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Assign Staff Technician --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Assigned Technician</label>
                        <select name="assigned_to" class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs font-semibold">
                            <option value="">-- Unassigned --</option>
                            @foreach($staff as $st)
                                <option value="{{ $st->id }}" {{ $productionJob->assigned_to == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }} ({{ $st->email }} — {{ ucfirst($st->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Estimated Run Hours --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Estimated Duration (Hours)</label>
                        <input type="number" name="estimated_hours" value="{{ $productionJob->estimated_hours ?? 4 }}" min="1" max="168"
                               class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs font-semibold">
                    </div>

                    {{-- Priority Level --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Production Priority *</label>
                        <select name="priority" required class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-lg focus:ring-2 focus:ring-brand-600 focus:outline-none text-xs font-semibold">
                            <option value="normal" {{ $productionJob->priority == 'normal' ? 'selected' : '' }}>Normal Priority</option>
                            <option value="rush" {{ $productionJob->priority == 'rush' ? 'selected' : '' }}>Rush Priority</option>
                            <option value="urgent" {{ $productionJob->priority == 'urgent' ? 'selected' : '' }}>Urgent / Express Priority</option>
                        </select>
                    </div>

                    {{-- Stage Status --}}
                    <div>
                        <label class="block font-bold text-cyber-muted mb-1.5">Current Stage</label>
                        <select name="status" class="w-full px-3.5 py-2.5 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs font-semibold">
                            @foreach(\App\Models\ProductionJob::STATUSES as $st)
                                <option value="{{ $st }}" {{ $productionJob->status == $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Remarks / Notes --}}
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-cyber-muted mb-1.5">Production Notes &amp; Special Handling Instructions</label>
                        <textarea name="remarks" rows="3" placeholder="e.g. Ensure UV varnish dry cycle completes before packaging..."
                                  class="w-full p-3 bg-cyber-sub border border-cyber rounded-xl focus:ring-2 focus:ring-amber-500 focus:outline-none text-xs">{{ old('remarks', $productionJob->remarks) }}</textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-cyber flex items-center justify-end gap-3">
                    <a href="{{ route('manager.production-planning.index') }}" class="px-5 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted font-bold transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-md shadow-amber-500/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i> Save Schedule &amp; Route Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
