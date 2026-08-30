@extends('layouts.internal')
@section('title', 'Customers Directory')
@section('page-title', 'Customer Directory')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 font-display">Customers Directory</h2>
            <p class="text-sm text-slate-500 mt-1">Manage customer profiles, send direct portal notices, and issue quotations.</p>
        </div>
        <form method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name or email..."
                   class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-brand-500 focus:outline-none">
        </form>
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
                    <th class="px-6 py-3.5">Customer Name</th>
                    <th class="px-6 py-3.5">Email</th>
                    <th class="px-6 py-3.5">Phone</th>
                    <th class="px-6 py-3.5">Total Orders</th>
                    <th class="px-6 py-3.5">Print Requests</th>
                    <th class="px-6 py-3.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($customers as $c)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-bold text-navy-900">{{ $c->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $c->email }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $c->phone ?? '—' }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $c->orders_count }}</td>
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $c->print_requests_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            {{-- View History --}}
                            <a href="{{ route('staff.customers.show', $c) }}" 
                               class="text-cyan-500 hover:text-cyan-400 transition text-sm p-1 inline-block" 
                               title="View Customer Profile & History">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            {{-- Create Quotation --}}
                            <a href="{{ route('staff.quotations.create') }}" 
                               class="text-emerald-500 hover:text-emerald-400 transition text-sm p-1 inline-block" 
                               title="Create Quotation for Customer">
                                <i class="fa-solid fa-file-circle-plus"></i>
                            </a>

                            {{-- Send Direct Notification --}}
                            <button type="button"
                                    onclick="openNotifyModal({{ json_encode([
                                        'id' => $c->id,
                                        'name' => $c->name
                                    ]) }})"
                                    class="text-indigo-500 hover:text-indigo-400 transition text-sm p-1 inline-block"
                                    title="Send Direct Portal Notification">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>

                            {{-- Edit Contact Details --}}
                            <button type="button"
                                    onclick="openEditCustomerModal({{ json_encode([
                                        'id' => $c->id,
                                        'name' => $c->name,
                                        'email' => $c->email,
                                        'phone' => $c->phone
                                    ]) }})"
                                    class="text-slate-500 hover:text-slate-700 transition text-sm p-1 inline-block"
                                    title="Edit Contact Info">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">{{ $customers->links() }}</div>
    </div>
</div>

<!-- Send Notification Modal -->
<div id="notify-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 w-full max-w-md shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-navy-900 font-display flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-indigo-500"></i> Notify <span id="notify-cust-name" class="text-indigo-600"></span>
            </h3>
            <button type="button" onclick="document.getElementById('notify-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="notify-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Notification Title</label>
                <input type="text" name="title" required placeholder="e.g., Quotation Review Reminder" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Notice Category</label>
                <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="info">General Information</option>
                    <option value="quotation">Quotation Update</option>
                    <option value="order">Order Status Update</option>
                    <option value="urgent">Urgent Notice</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Message Content</label>
                <textarea name="body" rows="3" required placeholder="Write your message here..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('notify-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition shadow-md shadow-indigo-500/20">
                    Send Notice
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Contact Modal -->
<div id="edit-customer-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 w-full max-w-md shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-black text-navy-900 font-display flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-slate-600"></i> Edit Customer Info
            </h3>
            <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="edit-customer-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Full Name</label>
                <input type="text" id="edit-cust-name" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-slate-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Email Address</label>
                <input type="email" id="edit-cust-email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-slate-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">Phone Number</label>
                <input type="text" id="edit-cust-phone" name="phone" placeholder="+63 912 345 6789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-slate-500 focus:outline-none">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-navy-900 hover:bg-navy-800 text-white font-black rounded-xl transition shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNotifyModal(c) {
        document.getElementById('notify-cust-name').textContent = c.name;
        const form = document.getElementById('notify-form');
        form.action = '/staff/customers/' + c.id + '/notify';
        document.getElementById('notify-modal').classList.remove('hidden');
    }

    function openEditCustomerModal(c) {
        document.getElementById('edit-cust-name').value = c.name;
        document.getElementById('edit-cust-email').value = c.email;
        document.getElementById('edit-cust-phone').value = c.phone || '';
        const form = document.getElementById('edit-customer-form');
        form.action = '/staff/customers/' + c.id;
        document.getElementById('edit-customer-modal').classList.remove('hidden');
    }
</script>
@endsection
