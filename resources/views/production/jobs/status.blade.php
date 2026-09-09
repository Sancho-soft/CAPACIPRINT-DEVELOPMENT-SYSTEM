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

            {{-- QC Checklist: only shown when status = completed --}}
            <div x-show="status === 'completed'" x-transition class="rounded-2xl border-2 border-emerald-200 bg-emerald-50/60 p-4 space-y-3">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i>
                    <h4 class="font-black text-emerald-800 text-sm">Quality Control Sign-Off</h4>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                          :class="qcAllChecked ? 'bg-emerald-200 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                        <span x-text="Object.values(qc).filter(v=>v).length"></span>/5 checked
                    </span>
                </div>
                <p class="text-[11px] text-emerald-700 font-medium">All items below must be verified before marking this job as completed.</p>

                <div class="space-y-2">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.print" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-slate-700 group-hover:text-emerald-700 transition font-medium">
                            Print quality and color accuracy match the approved design proof
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.count" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-slate-700 group-hover:text-emerald-700 transition font-medium">
                            Page count / quantity is complete and matches the job order
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.defects" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-slate-700 group-hover:text-emerald-700 transition font-medium">
                            No defects detected (streaks, smudges, misalignment, or bleed issues)
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.material" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-slate-700 group-hover:text-emerald-700 transition font-medium">
                            Material / paper type matches job specifications (size, finish, stock weight)
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" x-model="qc.packaging" class="mt-0.5 h-4 w-4 accent-emerald-600 cursor-pointer">
                        <span class="text-xs text-slate-700 group-hover:text-emerald-700 transition font-medium">
                            Items are properly packaged, labeled, and ready for customer pickup
                        </span>
                    </label>
                </div>

                <div x-show="!qcAllChecked" class="text-[11px] text-amber-600 font-semibold flex items-center gap-1.5 pt-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Complete all 5 QC checks to enable submission.
                </div>
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
                <button type="submit"
                        :disabled="!canSubmit"
                        :class="canSubmit ? 'bg-brand-500 hover:bg-brand-600 shadow-md shadow-brand-500/20 cursor-pointer' : 'bg-slate-300 cursor-not-allowed'"
                        class="text-white font-bold px-6 py-2 rounded-xl transition">
                    <span x-text="status === 'completed' && !qcAllChecked ? 'Complete QC Checklist First' : 'Update Production Status'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
