@extends('layouts.internal')

@section('title', 'Purchasing & Material Requests')
@section('page-title', 'Procurement & Purchasing')

@section('content')
<div class="space-y-6 w-full max-w-7xl mx-auto" x-data="{ 
    detailsModalOpen: false, 
    selectedPR: null,
    newPrModalOpen: false,
    prQuantity: 1,
    prUnitCost: 0,
    updateUnitCost(e) {
        const selected = e.target.options[e.target.selectedIndex];
        this.prUnitCost = parseFloat(selected.dataset.cost || 0);
    }
}">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 shadow-xl overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-2xl shadow-sm shrink-0">
                    <i class="fa-solid fa-cart-flatbed"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black font-display tracking-tight text-cyber-main">Material Purchase Requests</h1>
                    <p class="text-xs sm:text-sm text-cyber-muted mt-0.5">Submit replenishment orders and manage material procurement for printing facilities.</p>
                </div>
            </div>

            <button @click="newPrModalOpen = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-500 text-white font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-brand-500/20 text-xs sm:text-sm transition cursor-pointer shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Create Purchase Request</span>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3 KEY PROCUREMENT KPI CARDS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        <x-dashboard.kpi-card 
            title="PENDING APPROVALS"
            :value="number_format($pendingCount)"
            icon="fa-solid fa-clock-rotate-left"
            accent="amber"
        />

        <x-dashboard.kpi-card 
            title="APPROVED PURCHASE ORDERS"
            :value="number_format($approvedCount)"
            icon="fa-solid fa-circle-check"
            accent="emerald"
        />

        <x-dashboard.kpi-card 
            title="TOTAL PROCUREMENT BUDGET"
            :value="'₱' . number_format($totalSpent, 2)"
            icon="fa-solid fa-coins"
            accent="cyan"
        />
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- PURCHASE REQUESTS TABLE --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-cyber-muted font-bold uppercase tracking-wider border-b border-cyber/60 text-[10px] bg-slate-50/50 dark:bg-slate-800/40">
                    <tr>
                        <th class="px-6 py-4">PR #</th>
                        <th class="px-6 py-4">Material</th>
                        <th class="px-6 py-4">Quantity</th>
                        <th class="px-6 py-4">Est. Cost</th>
                        <th class="px-6 py-4">Requested By</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber/60 text-cyber-main">
                    @forelse($requests as $req)
                    <tr class="hover:bg-cyber-hover/50 transition">
                        {{-- PR # --}}
                        <td class="px-6 py-4 font-mono font-bold text-cyber-main">
                            PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Material Name & Type --}}
                        <td class="px-6 py-4">
                            <span class="font-bold text-cyber-main block">{{ $req->material->name ?? 'Material #' . $req->material_id }}</span>
                            <span class="text-[10px] text-cyber-muted uppercase tracking-wider">{{ $req->material->type ?? 'Substrate' }}</span>
                        </td>

                        {{-- Quantity --}}
                        <td class="px-6 py-4 font-bold font-mono">
                            {{ number_format($req->quantity) }} <span class="text-cyber-muted text-[11px] font-sans font-normal">{{ $req->material->unit ?? 'units' }}</span>
                        </td>

                        {{-- Total Amount --}}
                        <td class="px-6 py-4 font-bold font-mono text-emerald-600 dark:text-emerald-400">
                            ₱{{ number_format($req->total_amount, 2) }}
                        </td>

                        {{-- Requester & Branch --}}
                        <td class="px-6 py-4">
                            <span class="font-medium text-cyber-main block">{{ $req->user->name ?? 'System' }}</span>
                            <span class="text-[10px] text-cyber-muted block">{{ $req->branch->name ?? 'Main Hub' }}</span>
                        </td>

                        {{-- Status (Clean, High-Res Plain Style) --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($req->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 font-bold text-xs text-amber-600 dark:text-amber-400">
                                    <i class="fa-solid fa-circle text-[7px] text-amber-500 animate-pulse"></i>
                                    Pending Approval
                                </span>
                            @elseif($req->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 font-bold text-xs text-emerald-600 dark:text-emerald-400">
                                    <i class="fa-solid fa-circle text-[7px] text-emerald-500"></i>
                                    Approved
                                </span>
                            @elseif($req->status === 'received')
                                <span class="inline-flex items-center gap-1.5 font-bold text-xs text-cyan-600 dark:text-cyan-400">
                                    <i class="fa-solid fa-circle text-[7px] text-cyan-500"></i>
                                    Received &amp; Stocked
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 font-bold text-xs text-rose-600 dark:text-rose-400">
                                    <i class="fa-solid fa-circle text-[7px] text-rose-500"></i>
                                    Rejected
                                </span>
                            @endif
                        </td>

                        {{-- Interactive Actions --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                
                                {{-- 1. View Details (Available on every row) --}}
                                <button type="button"
                                        @click="selectedPR = {{ json_encode([
                                            'id' => $req->id,
                                            'pr_number' => 'PR-' . str_pad($req->id, 5, '0', STR_PAD_LEFT),
                                            'material_name' => $req->material->name ?? 'Material #' . $req->material_id,
                                            'material_type' => ucfirst($req->material->type ?? 'Substrate'),
                                            'material_unit' => $req->material->unit ?? 'units',
                                            'quantity' => number_format($req->quantity),
                                            'unit_cost' => number_format($req->unit_cost, 2),
                                            'total_amount' => number_format($req->total_amount, 2),
                                            'requested_by' => $req->user->name ?? 'System',
                                            'branch_name' => $req->branch->name ?? 'Main Hub',
                                            'status' => $req->status,
                                            'notes' => $req->notes ?: 'No additional notes provided.',
                                            'created_at' => $req->created_at ? $req->created_at->format('M d, Y h:i A') : '—',
                                            'approve_url' => route('manager.purchasing.approve', $req),
                                            'reject_url' => route('manager.purchasing.reject', $req),
                                            'receive_url' => route('manager.purchasing.receive', $req),
                                            'cancel_url' => route('manager.purchasing.cancel', $req),
                                            'can_approve' => auth()->user()->isAdmin() || in_array(auth()->user()->role, ['owner', 'management']),
                                            'can_cancel' => (auth()->id() === $req->requested_by) || auth()->user()->isAdmin(),
                                        ]) }}; detailsModalOpen = true;"
                                        title="View PR Details"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border border-cyber bg-cyber-sub text-cyber-main hover:bg-cyber-card text-xs font-bold transition shadow-xs cursor-pointer">
                                    <i class="fa-solid fa-eye text-xs text-cyber-muted"></i>
                                    <span>Details</span>
                                </button>

                                {{-- 2. Pending Status: Executive Quick Approve & Reject --}}
                                @if($req->status === 'pending')
                                    @if(auth()->user()->isAdmin() || in_array(auth()->user()->role, ['owner', 'management']))
                                        <form action="{{ route('manager.purchasing.approve', $req) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    title="Quick Approve"
                                                    class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-2.5 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">
                                                <i class="fa-solid fa-check text-xs"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <form action="{{ route('manager.purchasing.reject', $req) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to reject PR-{{ str_pad($req->id, 5, '0', STR_PAD_LEFT) }}?')"
                                                    title="Quick Reject"
                                                    class="inline-flex items-center gap-1 bg-rose-600 hover:bg-rose-500 text-white font-bold px-2.5 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- For Requester: Self-Service Withdraw --}}
                                    @if(auth()->id() === $req->requested_by && !auth()->user()->isAdmin())
                                        <form action="{{ route('manager.purchasing.cancel', $req) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Withdraw this purchase request?')"
                                                    title="Withdraw Request"
                                                    class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 px-2 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                                <span>Withdraw</span>
                                            </button>
                                        </form>
                                    @endif

                                {{-- 3. Approved Status: Mark Stock Received --}}
                                @elseif($req->status === 'approved')
                                    <form action="{{ route('manager.purchasing.receive', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="Mark Material Stock Received"
                                                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition shadow-xs cursor-pointer">
                                            <i class="fa-solid fa-box-open text-xs"></i>
                                            <span>Receive Stock</span>
                                        </button>
                                    </form>

                                {{-- 4. Received & Stocked --}}
                                @elseif($req->status === 'received')
                                    <span class="text-cyber-muted text-xs font-medium inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i> Stock In
                                    </span>

                                {{-- 5. Rejected --}}
                                @else
                                    <span class="text-rose-400 text-xs font-medium inline-flex items-center gap-1">
                                        <i class="fa-solid fa-ban text-xs"></i> Declined
                                    </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-cyber-muted text-xs">
                            <i class="fa-solid fa-cart-flatbed text-cyber-sub text-3xl mb-2 block"></i>
                            No purchase requests found for this facility.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-cyber/60">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL 1: VIEW PURCHASE REQUEST DETAILS --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div x-show="detailsModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="relative bg-cyber-card border border-cyber rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl overflow-hidden"
             @click.away="detailsModalOpen = false">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-cyber">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black font-display text-cyber-main flex items-center gap-2">
                            <span>Requisition Details</span>
                            <span class="font-mono text-xs text-cyber-muted px-2 py-0.5 rounded-md bg-cyber-sub border border-cyber" x-text="selectedPR?.pr_number"></span>
                        </h3>
                        <p class="text-xs text-cyber-muted">Submitted on <span x-text="selectedPR?.created_at"></span></p>
                    </div>
                </div>
                <button @click="detailsModalOpen = false" class="text-cyber-muted hover:text-cyber-main text-lg p-1 transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Modal Body Details --}}
            <div class="py-5 space-y-4 text-xs">
                {{-- Material Specs --}}
                <div class="bg-cyber-sub rounded-2xl p-4 border border-cyber grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-cyber-muted block">Material Name</span>
                        <span class="text-sm font-black text-cyber-main block" x-text="selectedPR?.material_name"></span>
                        <span class="text-[11px] text-cyber-muted" x-text="selectedPR?.material_type"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-cyber-muted block">Requested Quantity</span>
                        <span class="text-sm font-black text-cyber-main font-mono block">
                            <span x-text="selectedPR?.quantity"></span> <span class="font-sans font-normal text-xs text-cyber-muted" x-text="selectedPR?.material_unit"></span>
                        </span>
                        <span class="text-[11px] text-cyber-muted">Est. Unit Cost: ₱<span x-text="selectedPR?.unit_cost"></span></span>
                    </div>
                </div>

                {{-- Total Budget Highlight --}}
                <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-emerald-700 dark:text-emerald-400 block">Total Requisition Cost</span>
                        <span class="text-xs text-cyber-muted">Calculated replenishment allocation</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl sm:text-2xl font-black font-mono text-emerald-600 dark:text-emerald-400">₱<span x-text="selectedPR?.total_amount"></span></span>
                    </div>
                </div>

                {{-- Submitter & Facility Info --}}
                <div class="grid grid-cols-2 gap-4 bg-cyber-sub rounded-2xl p-4 border border-cyber">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-cyber-muted block">Requested By</span>
                        <span class="font-bold text-cyber-main" x-text="selectedPR?.requested_by"></span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-cyber-muted block">Destination Facility</span>
                        <span class="font-bold text-cyber-main" x-text="selectedPR?.branch_name"></span>
                    </div>
                </div>

                {{-- Justification Notes --}}
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-extrabold text-cyber-muted block mb-1">Replenishment Justification / Notes</span>
                    <div class="bg-cyber-sub border border-cyber rounded-2xl p-3.5 text-cyber-main text-xs whitespace-pre-line leading-relaxed" x-text="selectedPR?.notes"></div>
                </div>
            </div>

            {{-- Modal Footer with Contextual Actions --}}
            <div class="pt-4 border-t border-cyber flex flex-wrap items-center justify-between gap-3">
                <button type="button" @click="detailsModalOpen = false" class="px-4 py-2 rounded-xl border border-cyber bg-cyber-sub hover:bg-cyber-card text-cyber-main font-bold text-xs transition cursor-pointer">
                    Close
                </button>

                <div class="flex items-center gap-2">
                    {{-- Direct Quick Approve inside Modal --}}
                    <template x-if="selectedPR?.status === 'pending' && selectedPR?.can_approve">
                        <div class="flex items-center gap-2">
                            <form :action="selectedPR?.approve_url" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-md cursor-pointer">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Approve Request</span>
                                </button>
                            </form>
                            <form :action="selectedPR?.reject_url" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Reject this purchase request?')" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-md cursor-pointer">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Reject</span>
                                </button>
                            </form>
                        </div>
                    </template>

                    {{-- Direct Receive Stock inside Modal --}}
                    <template x-if="selectedPR?.status === 'approved'">
                        <form :action="selectedPR?.receive_url" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-md cursor-pointer">
                                <i class="fa-solid fa-box-open"></i>
                                <span>Receive Stock</span>
                            </button>
                        </form>
                    </template>

                    {{-- Direct Withdraw inside Modal --}}
                    <template x-if="selectedPR?.status === 'pending' && selectedPR?.can_cancel && !selectedPR?.can_approve">
                        <form :action="selectedPR?.cancel_url" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Withdraw this purchase request?')" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition shadow-md cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Withdraw Request</span>
                            </button>
                        </form>
                    </template>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- MODAL 2: CREATE NEW PURCHASE REQUEST --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div x-show="newPrModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="relative bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl overflow-hidden"
             @click.away="newPrModalOpen = false">

            <div class="flex items-center justify-between pb-4 border-b border-cyber">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-brand-500/10 border border-brand-500/30 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-cart-plus"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black font-display text-cyber-main">Create Purchase Request</h3>
                        <p class="text-xs text-cyber-muted">Submit material replenishment to executive review.</p>
                    </div>
                </div>
                <button @click="newPrModalOpen = false" class="text-cyber-muted hover:text-cyber-main text-lg p-1 transition cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('manager.purchasing.store') }}" method="POST" class="space-y-4 pt-4 text-xs">
                @csrf

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-cyber-muted mb-1.5">Select Material</label>
                    <select name="material_id" required @change="updateUnitCost($event)" class="w-full bg-cyber-sub border border-cyber rounded-xl px-4 py-2.5 text-cyber-main font-medium focus:ring-2 focus:ring-brand-500/40 outline-none transition">
                        <option value="" disabled selected>-- Choose Material Substrate --</option>
                        @foreach($materials as $mat)
                            <option value="{{ $mat->id }}" data-cost="{{ $mat->cost_per_unit ?? 0 }}">
                                {{ $mat->name }} (₱{{ number_format($mat->cost_per_unit ?? 0, 2) }}/{{ $mat->unit ?? 'unit' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-cyber-muted mb-1.5">Quantity</label>
                        <input type="number" name="quantity" min="1" x-model.number="prQuantity" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-4 py-2.5 text-cyber-main font-mono font-bold focus:ring-2 focus:ring-brand-500/40 outline-none transition" placeholder="e.g. 50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-cyber-muted mb-1.5">Unit Cost (₱)</label>
                        <input type="number" step="0.01" min="0" name="unit_cost" x-model.number="prUnitCost" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-4 py-2.5 text-cyber-main font-mono font-bold focus:ring-2 focus:ring-brand-500/40 outline-none transition" placeholder="e.g. 150.00">
                    </div>
                </div>

                {{-- Estimated Budget Preview --}}
                <div class="p-3.5 rounded-2xl bg-cyber-sub border border-cyber flex items-center justify-between">
                    <span class="text-cyber-muted font-bold">Estimated Total:</span>
                    <span class="font-mono font-black text-sm text-emerald-600 dark:text-emerald-400">
                        ₱<span x-text="((prQuantity || 0) * (prUnitCost || 0)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                    </span>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-cyber-muted mb-1.5">Justification / Restocking Notes</label>
                    <textarea name="notes" rows="3" class="w-full bg-cyber-sub border border-cyber rounded-xl px-4 py-2.5 text-cyber-main text-xs focus:ring-2 focus:ring-brand-500/40 outline-none transition" placeholder="Provide reason for replenishment, target project, or urgent press stock needs..."></textarea>
                </div>

                <div class="flex justify-end gap-2.5 pt-3 border-t border-cyber">
                    <button type="button" @click="newPrModalOpen = false" class="px-4 py-2 rounded-xl text-cyber-muted hover:text-cyber-main font-bold text-xs transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="bg-brand-600 hover:bg-brand-500 text-white font-bold px-5 py-2 rounded-xl text-xs shadow-md shadow-brand-500/20 transition cursor-pointer">
                        Submit Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
