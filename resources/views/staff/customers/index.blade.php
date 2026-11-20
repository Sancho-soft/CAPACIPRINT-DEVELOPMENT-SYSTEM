@extends('layouts.internal')
@section('title', 'Customers Directory')
@section('page-title', 'Customer Directory')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Customers Directory</h2>
            <p class="text-xs sm:text-sm text-cyber-muted mt-1">Manage customer profiles, send direct portal notices, and issue quotations.</p>
        </div>
        <form method="GET" action="{{ route('staff.customers.index') }}" class="flex items-center">
            <div class="relative flex items-center w-full sm:w-96 rounded-lg border border-cyber bg-cyber-card p-1 focus-within:border-brand-600 transition">
                <div class="pl-3 pr-1 flex items-center pointer-events-none text-cyber-muted">
                    <i class="fa-solid fa-magnifying-glass text-xs text-brand-600 dark:text-blue-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search customer name, email, or phone..."
                       autocomplete="off"
                       class="w-full bg-transparent border-0 px-2.5 py-1.5 text-xs font-medium text-cyber-main placeholder-cyber-muted focus:outline-none focus:ring-0">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-1.5 rounded-md text-xs font-semibold transition shrink-0 cursor-pointer">
                    Search
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    <div id="customers-table-card" class="bg-cyber-card border border-cyber rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-xs tabular-nums">
            <thead class="bg-cyber-sub text-cyber-muted font-sans tabular-nums font-semibold border-b border-cyber text-[11px]">
                <tr>
                    <th class="px-5 py-3">Customer Name</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Phone</th>
                    <th class="px-5 py-3 text-right">Total Orders</th>
                    <th class="px-5 py-3 text-right">Print Requests</th>
                    <th class="px-5 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cyber-sub text-cyber-main tabular-nums">
                @forelse($customers as $c)
                <tr class="hover:bg-cyber-sub/50 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $c->name }}</td>
                    <td class="px-5 py-3.5 text-cyber-muted">{{ $c->email }}</td>
                    <td class="px-5 py-3.5 font-sans tabular-nums text-cyber-muted">{{ $c->phone ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-right font-sans tabular-nums font-bold text-cyber-main">{{ $c->orders_count }}</td>
                    <td class="px-5 py-3.5 text-right font-sans tabular-nums font-bold text-cyber-main">{{ $c->print_requests_count }}</td>
                    <td class="px-5 py-3.5 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <a href="{{ route('staff.customers.show', $c) }}" 
                               class="text-brand-600 hover:text-brand-700 dark:text-blue-400 dark:hover:text-blue-300 transition text-sm p-1 inline-block" 
                               title="View Customer Profile & History">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('staff.quotations.create') }}" 
                               class="text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 transition text-sm p-1 inline-block" 
                               title="Create Quotation for Customer">
                                <i class="fa-solid fa-file-circle-plus"></i>
                            </a>
                            <button type="button"
                                    data-id="{{ $c->id }}"
                                    data-name="{{ $c->name }}"
                                    onclick="openNotifyModal(this)"
                                    class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 transition text-sm p-1 inline-block cursor-pointer"
                                    title="Send Direct Portal Notification">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                            <button type="button"
                                    data-id="{{ $c->id }}"
                                    data-name="{{ $c->name }}"
                                    data-email="{{ $c->email }}"
                                    data-phone="{{ $c->phone }}"
                                    onclick="openEditCustomerModal(this)"
                                    class="text-cyber-muted hover:text-cyber-main transition text-sm p-1 inline-block cursor-pointer"
                                    title="Edit Contact Info">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-cyber-muted">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="p-4 border-t border-cyber">{{ $customers->links() }}</div>
    </div>
</div>

<!-- Send Notification Modal -->
<div id="notify-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-2xl shadow-sm p-6 w-full max-w-md shadow-lg space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-base font-bold text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-brand-600 dark:text-blue-400"></i> Notify <span id="notify-cust-name" class="text-brand-600 dark:text-blue-400"></span>
            </h3>
            <button type="button" onclick="document.getElementById('notify-modal').classList.add('hidden')" aria-label="Close notify modal" class="text-cyber-muted hover:text-cyber-main cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="notify-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1">Notification Title</label>
                <input type="text" name="title" required placeholder="e.g., Quotation Review Reminder" class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-brand-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1">Notice Category</label>
                <select name="type" class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-brand-600 focus:outline-none">
                    <option value="info">General Information</option>
                    <option value="quotation">Quotation Update</option>
                    <option value="order">Order Status Update</option>
                    <option value="urgent">Urgent Notice</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1">Message Content</label>
                <textarea name="body" rows="3" required placeholder="Write your message here..." class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-brand-600 focus:outline-none"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" onclick="document.getElementById('notify-modal').classList.add('hidden')" class="px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold rounded-lg transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg transition cursor-pointer">
                    Send Notice
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Contact Modal -->
<div id="edit-customer-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-cyber bg-cyber-sub flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="text-sm font-bold text-cyber-main font-display leading-tight">Edit Customer Info</h3>
            </div>
            <button type="button" 
                    onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" 
                    aria-label="Close edit modal"
                    class="h-8 w-8 rounded-xl text-cyber-muted hover:text-cyber-main hover:bg-cyber-card inline-flex items-center justify-center transition cursor-pointer"
                    title="Close">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <form id="edit-customer-form" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-4 text-xs">
                {{-- Full Name --}}
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">
                        Full Name
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted text-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" 
                               id="edit-cust-name" 
                               name="name" 
                               required 
                               placeholder="e.g. Juan Dela Cruz"
                               class="w-full bg-cyber-sub border border-cyber rounded-xl pl-10 pr-3.5 py-2.5 text-xs text-cyber-main placeholder-cyber-muted focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 focus:outline-none transition">
                    </div>
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">
                        Email Address
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" 
                               id="edit-cust-email" 
                               name="email" 
                               required 
                               placeholder="customer@example.com"
                               class="w-full bg-cyber-sub border border-cyber rounded-xl pl-10 pr-3.5 py-2.5 text-xs text-cyber-main placeholder-cyber-muted focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 focus:outline-none transition">
                    </div>
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-xs font-semibold text-cyber-main mb-1.5">
                        Phone Number
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-muted text-xs">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input type="text" 
                               id="edit-cust-phone" 
                               name="phone" 
                               placeholder="+63 912 345 6789"
                               class="w-full bg-cyber-sub border border-cyber rounded-xl pl-10 pr-3.5 py-2.5 text-xs font-sans tabular-nums text-cyber-main placeholder-cyber-muted focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 focus:outline-none transition">
                    </div>
                </div>
            </div>

            {{-- Footer Actions (Full-width divider bar) --}}
            <div class="px-6 py-4 bg-cyber-sub border-t border-cyber flex items-center justify-end gap-2.5">
                <button type="button" 
                        onclick="document.getElementById('edit-customer-modal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-2xl border border-cyber bg-cyber-card shadow-sm text-cyber-main hover:bg-cyber-sub font-semibold text-xs transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs inline-flex items-center gap-2 shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-check text-xs"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNotifyModal(target) {
        const data = target.dataset || target;
        document.getElementById('notify-cust-name').textContent = data.name;
        const form = document.getElementById('notify-form');
        form.action = '/staff/customers/' + data.id + '/notify';
        document.getElementById('notify-modal').classList.remove('hidden');
    }

    function openEditCustomerModal(target) {
        const data = target.dataset || target;
        document.getElementById('edit-cust-name').value = data.name || '';
        document.getElementById('edit-cust-email').value = data.email || '';
        document.getElementById('edit-cust-phone').value = data.phone || '';
        const form = document.getElementById('edit-customer-form');
        form.action = '/staff/customers/' + data.id;
        document.getElementById('edit-customer-modal').classList.remove('hidden');
    }

    // Close on outside backdrop click
    document.getElementById('edit-customer-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endsection
