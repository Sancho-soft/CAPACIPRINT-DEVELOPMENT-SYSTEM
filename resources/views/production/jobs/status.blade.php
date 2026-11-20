@extends('layouts.internal')
@section('title', 'Update Job Status')
@section('page-title', 'Update Status for Job #' . $productionJob->job_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-cyber-card p-6 rounded-xl border border-cyber space-y-5">
        <div>
            <h2 class="text-lg font-bold text-cyber-main font-display">Update Production Status</h2>
            <p class="text-xs text-cyber-muted mt-0.5">Select current production stage for Job <span class="font-sans tabular-nums font-semibold text-cyber-main">#{{ $productionJob->job_number }}</span>.</p>
        </div>

        <form method="POST" action="{{ route('production.jobs.update-status', $productionJob) }}" class="space-y-4 text-xs"
              x-data="{
                status: '{{ $productionJob->status }}',
                qc: { print: false, count: false, defects: false, material: false, packaging: false },
                get qcAllChecked() { return Object.values(this.qc).every(v => v); },
                get canSubmit() { return this.status !== 'completed' || this.qcAllChecked; }
              }">
            @csrf
            <input type="hidden" name="qc_confirmed" :value="qcAllChecked ? '1' : '0'">

            <div>
                <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5">Production Stage <span class="text-rose-500">*</span></label>
                <select name="status" x-model="status" required class="w-full rounded-lg border border-cyber bg-cyber-sub text-cyber-main px-3.5 py-2.5 text-xs font-semibold focus:border-brand-600 focus:outline-none">
                    <option value="assigned">Assigned</option>
                    <option value="preparing">Preparing Materials / Pre-press</option>
                    <option value="in_production">In Production (Printing)</option>
                    <option value="quality_checking">Quality Checking &amp; Packaging</option>
                    <option value="completed">Completed (Triggers QR Claim Code)</option>
                    <option value="delayed">Delayed (Requires Reason)</option>
                </select>
            </div>

            {{-- QC Checklist: only shown when status = completed --}}
            <div x-show="status === 'completed'" x-transition class="rounded-xl border border-emerald-500/35 bg-emerald-500/10 p-4 space-y-3">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-clipboard-check text-emerald-600 dark:text-emerald-400"></i>
                    <h4 class="font-bold text-emerald-800 dark:text-emerald-300 text-sm font-display">Quality Control Sign-Off</h4>
                    <span class="ml-auto text-[11px] font-sans tabular-nums font-bold px-2 py-0.5 rounded tabular-nums"
                          :class="qcAllChecked ? 'bg-emerald-600 text-white' : 'bg-amber-500/20 text-amber-800 dark:text-amber-300'">
                        <span x-text="Object.values(qc).filter(v=>v).length"></span>/5 checked
                    </span>
                </div>
                <p class="text-[11px] text-emerald-800 dark:text-emerald-300 font-medium">All items below must be verified before marking this job as completed.</p>

                <div class="space-y-2">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.print" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-cyber-main group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition font-medium">
                            Print quality and color accuracy match the approved design proof
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.count" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-cyber-main group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition font-medium">
                            Page count / quantity is complete and matches the job order
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.defects" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-cyber-main group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition font-medium">
                            No defects detected (streaks, smudges, misalignment, or bleed issues)
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.material" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-cyber-main group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition font-medium">
                            Material / paper type matches job specifications (size, finish, stock weight)
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.packaging" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-cyber-main group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition font-medium">
                            Items are properly packaged, labeled, and ready for customer pickup
                        </span>
                    </label>
                </div>

                <div x-show="!qcAllChecked" class="text-[11px] text-amber-700 dark:text-amber-400 font-semibold flex items-center gap-1.5 pt-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Complete all 5 QC checks to enable submission.
                </div>
            </div>

            <div x-show="status === 'delayed'" class="space-y-1">
                <label class="block font-semibold text-rose-600 dark:text-rose-400 mb-1">Delay Reason <span class="text-rose-500">*</span></label>
                <input type="text" name="delay_reason" placeholder="e.g. Paper out of stock or machine breakdown"
                       class="w-full rounded-lg border border-rose-500/35 bg-rose-500/10 text-cyber-main px-3.5 py-2 text-xs focus:outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5">Production Notes / Remarks</label>
                <textarea name="remarks" rows="3" placeholder="e.g. Printed 500 copies on Heidelberg press without issues"
                          class="w-full rounded-lg border border-cyber bg-cyber-sub text-cyber-main px-3.5 py-2 text-xs focus:border-brand-600 focus:outline-none">{{ $productionJob->remarks }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-cyber">
                <a href="{{ route('production.jobs.show', $productionJob) }}" class="text-cyber-muted hover:text-cyber-main font-semibold">&larr; Cancel</a>
                <button type="submit"
                        :disabled="!canSubmit"
                        :class="canSubmit ? 'bg-brand-600 hover:bg-brand-700 cursor-pointer' : 'bg-slate-400/50 cursor-not-allowed'"
                        class="text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    <span x-text="status === 'completed' && !qcAllChecked ? 'Complete QC Checklist First' : 'Update Production Status'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
