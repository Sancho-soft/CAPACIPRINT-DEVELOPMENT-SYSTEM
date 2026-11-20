@extends('layouts.internal')
@section('title', 'Quotations Management')
@section('page-title', 'Quotation Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Customer Quotations</h2>
            <p class="text-xs sm:text-sm text-cyber-muted mt-1">Manage price estimates, track quotation statuses, and issue formal offers.</p>
        </div>
        <a href="{{ route('staff.quotations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-xs shadow-sm transition inline-flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-plus text-xs"></i> Create Quotation
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter, Search & Sorting Controls --}}
    <div class="bg-cyber-card border border-cyber p-4 rounded-2xl shadow-sm">
        <form method="GET" action="{{ route('staff.quotations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            {{-- Search Bar --}}
            <div class="md:col-span-2 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-muted">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotation #, customer, service..."
                       class="w-full pl-9 pr-3 py-2 bg-cyber-sub border border-cyber rounded-xl text-xs text-cyber-main placeholder-cyber-muted focus:outline-none focus:border-blue-600 transition">
            </div>

            {{-- Date Assigned / Created Filter --}}
            <div class="relative">
                <input type="date" name="date_from" value="{{ request('date_from') }}" title="Date from"
                       class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-xl text-xs text-cyber-main focus:outline-none focus:border-blue-600 transition">
            </div>

            {{-- Status Filter --}}
            <div>
                <select name="status" class="w-full px-3 py-2 bg-cyber-sub border border-cyber rounded-xl text-xs text-cyber-main focus:outline-none focus:border-blue-600 transition">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            {{-- Sort By & Action --}}
            <div class="flex items-center gap-2">
                <select name="sort_by" class="flex-1 px-3 py-2 bg-cyber-sub border border-cyber rounded-xl text-xs text-cyber-main focus:outline-none focus:border-blue-600 transition">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date: Recent</option>
                    <option value="total_price" {{ request('sort_by') === 'total_price' ? 'selected' : '' }}>Total Amount</option>
                    <option value="valid_until" {{ request('sort_by') === 'valid_until' ? 'selected' : '' }}>Valid Until</option>
                    <option value="quotation_number" {{ request('sort_by') === 'quotation_number' ? 'selected' : '' }}>Quotation #</option>
                </select>
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-filter"></i>
                </button>
                @if(request()->hasAny(['search', 'date_from', 'status', 'sort_by']))
                <a href="{{ route('staff.quotations.index') }}" class="p-2 bg-cyber-sub hover:bg-rose-500/10 border border-cyber text-cyber-muted hover:text-rose-600 dark:hover:text-rose-400 rounded-xl text-xs transition" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <div id="quotations-table-card" class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-semibold border-b border-cyber text-xs">
                <tr>
                    <th class="px-6 py-3.5">Quotation No.</th>
                    <th class="px-6 py-3.5">Customer</th>
                    <th class="px-6 py-3.5">Service</th>
                    <th class="px-6 py-3.5">Total Amount</th>
                    <th class="px-6 py-3.5">Valid Until</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub text-cyber-main">
                @forelse($quotations as $q)
                <tr class="hover:bg-cyber-sub/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-cyber-main tabular-nums">#{{ $q->formatted_number }}</td>
                    <td class="px-6 py-4 font-semibold text-cyber-main">{{ $q->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-cyber-muted">{{ $q->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-cyber-main tabular-nums text-sm">₱{{ number_format($q->total_price, 2) }}</td>
                    <td class="px-6 py-4 text-cyber-muted tabular-nums">{{ $q->valid_until?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-semibold uppercase {{ match($q->status) { 'pending'=>'text-amber-600 dark:text-amber-400', 'confirmed'=>'text-emerald-600 dark:text-emerald-400', 'declined'=>'text-rose-600 dark:text-rose-400', default=>'text-cyber-muted' } }}">
                            {{ $q->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            {{-- View Button --}}
                            <a href="{{ route('staff.quotations.show', $q) }}"
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-flex items-center justify-center"
                               title="View Quotation Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            {{-- Edit Button --}}
                            <button type="button" 
                                    onclick="openEditQuotationModal({{ json_encode([
                                        'id' => $q->id,
                                        'number' => $q->quotation_number,
                                        'base_cost' => $q->base_cost,
                                        'material_cost' => $q->material_cost,
                                        'finishing_cost' => $q->finishing_cost,
                                        'valid_until' => $q->valid_until ? $q->valid_until->format('Y-m-d') : '',
                                        'notes' => $q->notes
                                    ]) }})"
                                    class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 transition text-sm p-1 inline-block cursor-pointer"
                                    title="Edit Quotation Details">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted">No quotations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="p-4 border-t border-cyber">{{ $quotations->links() }}</div>
    </div>
</div>

<!-- Edit Quotation Modal -->
<div id="edit-quotation-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-2xl p-6 sm:p-7 w-full max-w-lg shadow-lg space-y-4">
        <div class="flex items-center justify-between border-b border-cyber-sub pb-3">
            <h3 class="text-lg font-bold text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-indigo-600 dark:text-indigo-400"></i> Edit Quotation <span id="edit-q-number" class="text-indigo-600 dark:text-indigo-400 font-sans tabular-nums"></span>
            </h3>
            <button type="button" onclick="document.getElementById('edit-quotation-modal').classList.add('hidden')" aria-label="Close modal" title="Close modal" class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="edit-quotation-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Base Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-base" name="base_cost" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Material Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-material" name="material_cost" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">Finishing Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-finishing" name="finishing_cost" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">Valid Until Date</label>
                <input type="date" id="edit-q-valid" name="valid_until" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main tabular-nums focus:border-blue-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">Quotation Notes / Terms</label>
                <textarea id="edit-q-notes" name="notes" rows="3" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:border-blue-600 focus:outline-none" placeholder="Additional quotation terms or instructions"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber-sub">
                <button type="button" onclick="document.getElementById('edit-quotation-modal').classList.add('hidden')" class="px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold rounded-xl transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm cursor-pointer">
                    Update Quotation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditQuotationModal(q) {
        document.getElementById('edit-q-number').textContent = '#' + q.number;
        document.getElementById('edit-q-base').value = q.base_cost;
        document.getElementById('edit-q-material').value = q.material_cost;
        document.getElementById('edit-q-finishing').value = q.finishing_cost;
        document.getElementById('edit-q-valid').value = q.valid_until;
        document.getElementById('edit-q-notes').value = q.notes || '';
        
        const form = document.getElementById('edit-quotation-form');
        form.action = '/staff/quotations/' + q.id;
        
        document.getElementById('edit-quotation-modal').classList.remove('hidden');
    }
</script>
@endsection
