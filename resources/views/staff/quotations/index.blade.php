@extends('layouts.internal')
@section('title', 'Quotations Management')
@section('page-title', 'Quotation Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Customer Quotations</h2>
            <p class="text-sm text-slate-500 mt-1">Manage price estimates, track quotation statuses, and issue formal offers.</p>
        </div>
        <a href="{{ route('staff.quotations.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-brand-500/20 transition flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-xs"></i> Create Quotation
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider">
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
            <tbody class="divide-y divide-slate-100">
                @forelse($quotations as $q)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $q->quotation_number }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $q->user->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-700">{{ $q->printRequest->service ?? '—' }}</td>
                    <td class="px-6 py-4 font-black text-navy-900 text-sm">₱{{ number_format($q->total_price, 2) }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $q->valid_until?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $q->status_badge_class }}">
                            {{ $q->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            {{-- View Button --}}
                            <a href="{{ route('staff.quotations.show', $q) }}"
                               class="text-cyan-500 hover:text-cyan-400 transition text-sm p-1 inline-block"
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
                                    class="text-indigo-500 hover:text-indigo-400 transition text-sm p-1 inline-block"
                                    title="Edit Quotation Details">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">No quotations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $quotations->links() }}</div>
    </div>
</div>

<!-- Edit Quotation Modal -->
<div id="edit-quotation-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-black text-navy-900 font-display flex items-center gap-2">
                <i class="fa-solid fa-file-pen text-indigo-500"></i> Edit Quotation <span id="edit-q-number" class="text-indigo-600 font-mono"></span>
            </h3>
            <button type="button" onclick="document.getElementById('edit-quotation-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="edit-quotation-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Base Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-base" name="base_cost" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Material Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-material" name="material_cost" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Finishing Cost (₱)</label>
                    <input type="number" step="0.01" id="edit-q-finishing" name="finishing_cost" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Valid Until Date</label>
                <input type="date" id="edit-q-valid" name="valid_until" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Quotation Notes / Terms</label>
                <textarea id="edit-q-notes" name="notes" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Additional quotation terms or instructions"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-quotation-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition shadow-md shadow-indigo-500/20">
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
