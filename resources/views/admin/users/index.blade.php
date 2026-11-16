@extends('layouts.internal')

@section('title', 'User Accounts & Access Control')
@section('page-title', 'User Administration')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Actions (Unboxed & Clean) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black font-display tracking-tight text-slate-900 dark:text-white">User Accounts &amp; Access Control</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                @if(request('archived'))
                    Showing archived user accounts. Restoring an account will re-enable system access.
                @else
                    Create new user accounts, edit profiles, reset passwords, and manage status across all system roles.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button type="button" 
                    id="btn-open-create-user"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-xs cursor-pointer">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Add New User</span>
            </button>

            <a href="{{ route('admin.users.index', array_merge(request()->except('archived'), ['archived' => request('archived') ? 0 : 1])) }}" 
               class="inline-flex items-center gap-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-xs">
                <i class="fa-solid {{ request('archived') ? 'fa-users text-cyan-500 dark:text-cyan-400' : 'fa-box-archive text-amber-500 dark:text-amber-400' }} text-xs"></i>
                <span>{{ request('archived') ? 'View Active Accounts' : 'View Archived' }}</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-medium space-y-1">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span>Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-500 dark:text-rose-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-cyber-card border border-cyber rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-xs">
            @if(request('archived'))
                <input type="hidden" name="archived" value="1">
            @endif

            <!-- Search Field -->
            <div class="sm:col-span-5 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search user name or email..." 
                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
            </div>

            <!-- Role Filter -->
            <div class="sm:col-span-4">
                <select name="role" class="w-full px-3 py-2 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
                    <option value="">All Internal Staff</option>
                    <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner (Executive)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>System Admin</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Branch Manager</option>
                    <option value="production_officer" {{ request('role') == 'production_officer' ? 'selected' : '' }}>Production Officer</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Customer Service (CS)</option>
                    <option value="production" {{ request('role') == 'production' ? 'selected' : '' }}>Production Operator</option>
                    <option value="inventory" {{ request('role') == 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer Accounts</option>
                </select>
            </div>

            <!-- Branch Hub Filter -->
            <div class="sm:col-span-3 flex items-center gap-2">
                <select name="branch_id" class="w-full px-3 py-2 bg-slate-50 dark:bg-cyber-sub/40 border border-slate-200 dark:border-cyber text-black dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs">
                    <option value="">All Branch Hubs</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold transition shadow-xs shrink-0 cursor-pointer" title="Apply Filter">
                    <i class="fa-solid fa-filter text-xs"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Users Datatable Card -->
    <div id="users-table-card" class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main">
                <thead class="bg-cyber-sub text-cyber-muted uppercase font-bold text-[10px] tracking-wider border-b border-cyber">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Assigned Role</th>
                        <th class="px-5 py-3.5">Branch Hub</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($users as $usr)
                    @php
                        $roleSlug = $usr->role;
                        $roleBadge = match($roleSlug) {
                            'owner', 'management'     => 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30',
                            'admin', 'system_admin'   => 'bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30',
                            'manager'                 => 'bg-blue-500/15 text-blue-700 dark:text-blue-400 border border-blue-500/30',
                            'production_officer'      => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-400 border border-indigo-500/30',
                            'staff', 'designer'       => 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-400 border border-cyan-500/30',
                            'production'              => 'bg-orange-500/15 text-orange-700 dark:text-orange-400 border border-orange-500/30',
                            'inventory'               => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30',
                            'customer'                => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border border-slate-500/30',
                            default                   => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20',
                        };

                        $displayRole = match($roleSlug) {
                            'owner', 'management'     => 'Owner (Executive)',
                            'admin', 'system_admin'   => 'System Admin',
                            'manager'                 => 'Branch Manager',
                            'production_officer'      => 'Production Officer',
                            'staff', 'designer'       => 'Customer Service',
                            'production'              => 'Production Operator',
                            'inventory'               => 'Inventory Staff',
                            'customer'                => 'Customer',
                            default                   => ucfirst(str_replace('_', ' ', $roleSlug)),
                        };
                    @endphp
                    <tr class="hover:bg-cyber-sub/60 transition">
                        <td class="px-5 py-3.5 font-mono font-bold text-cyber-muted">#{{ $usr->id }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyber-main">{{ $usr->name }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyber-muted text-xs">{{ $usr->email }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold tracking-tight {{ $roleBadge }}">
                                {{ $displayRole }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $usr->branch->name ?? 'System-Wide / All' }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($usr->is_archived)
                                <span class="text-[10px] font-black uppercase text-amber-500 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded">Archived</span>
                            @else
                                <span class="text-[10px] font-black uppercase text-emerald-500 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded">Active</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                {{-- 1. View Details Button --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        data-email="{{ $usr->email }}"
                                        data-role-label="{{ $displayRole }}"
                                        data-branch-name="{{ $usr->branch->name ?? 'System-Wide / All' }}"
                                        data-status="{{ $usr->is_archived ? 'Archived' : 'Active' }}"
                                        data-created="{{ $usr->created_at ? $usr->created_at->format('M d, Y g:i A') : 'N/A' }}"
                                        class="btn-trigger-view h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-600 dark:text-cyan-400 hover:text-slate-950 border border-cyan-500/30 flex items-center justify-center text-xs transition shadow-2xs cursor-pointer" 
                                        title="View Account Details">
                                    <i class="fa-solid fa-eye pointer-events-none"></i>
                                </button>

                                {{-- 2. Edit User Details Button --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        data-email="{{ $usr->email }}"
                                        data-role="{{ $usr->role }}"
                                        data-branch-id="{{ $usr->branch_id ?? '' }}"
                                        class="btn-trigger-edit h-8 w-8 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-600 dark:text-amber-400 hover:text-slate-950 border border-amber-500/30 flex items-center justify-center text-xs transition shadow-2xs cursor-pointer" 
                                        title="Edit User Details">
                                    <i class="fa-solid fa-pen-to-square pointer-events-none"></i>
                                </button>

                                {{-- 3. Reset Password Button --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        class="btn-trigger-reset h-8 w-8 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-white border border-indigo-500/30 flex items-center justify-center text-xs transition shadow-2xs cursor-pointer" 
                                        title="Reset Password">
                                    <i class="fa-solid fa-key pointer-events-none"></i>
                                </button>

                                {{-- 4. Archive / Restore Button --}}
                                <form method="POST" action="{{ route('admin.users.toggle-archive', $usr) }}" 
                                      data-archived="{{ $usr->is_archived ? '1' : '0' }}"
                                      class="inline form-toggle-archive">
                                    @csrf
                                    <button type="submit" 
                                            class="h-8 w-8 rounded-xl {{ $usr->is_archived ? 'bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 dark:text-emerald-400 hover:text-slate-950 border border-emerald-500/30' : 'bg-rose-500/10 hover:bg-rose-500 text-rose-600 dark:text-rose-400 hover:text-slate-950 border border-rose-500/30' }} flex items-center justify-center text-xs transition shadow-2xs cursor-pointer" 
                                            title="{{ $usr->is_archived ? 'Restore User Account' : 'Archive User Account' }}">
                                        <i class="fa-solid {{ $usr->is_archived ? 'fa-rotate-left' : 'fa-box-archive' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-cyber-muted">
                            <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 dark:text-slate-600 block"></i>
                            <span class="font-bold text-sm block">No User Accounts Found</span>
                            <span class="text-xs text-slate-500 mt-1 block">Try clearing filters or search query.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-3.5 border-t border-cyber bg-cyber-sub/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-cyber-muted font-medium">
                Showing <span class="font-bold text-cyber-main">{{ $users->firstItem() }}</span> to <span class="font-bold text-cyber-main">{{ $users->lastItem() }}</span> of <span class="font-bold text-cyber-main">{{ $users->total() }}</span> accounts
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- 1. ADD NEW USER MODAL -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-black text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-cyan-500 dark:text-cyan-400"></i> Add New User Account
            </h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="create-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" placeholder="e.g. Kim Jack Lupogan">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" placeholder="user@capaciprint.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Assigned Role</label>
                    <select name="role" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <optgroup label="Executive & Administration">
                            <option value="owner">Owner (Executive)</option>
                            <option value="admin">System Admin</option>
                            <option value="manager">Branch Manager</option>
                        </optgroup>
                        <optgroup label="Operations & Production Floor">
                            <option value="production_officer">Production Officer</option>
                            <option value="production">Production Operator</option>
                        </optgroup>
                        <optgroup label="Customer Service & Design">
                            <option value="staff" selected>Customer Service (CS)</option>
                        </optgroup>
                        <optgroup label="Warehouse & Logistics">
                            <option value="inventory">Inventory Staff</option>
                        </optgroup>
                        <optgroup label="Clients">
                            <option value="customer">Customer</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Branch Hub</label>
                    <select name="branch_id" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <option value="">System-Wide / All</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Password</label>
                <div class="relative">
                    <input type="password" 
                           id="create-user-password"
                           name="password" 
                           required 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-xl pl-3.5 pr-10 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                           placeholder="Minimum 8 characters">
                    <button type="button" 
                            data-target="create-user-password"
                            class="btn-toggle-pass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-cyan-500 dark:hover:text-cyan-400 transition cursor-pointer p-1"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end pt-2 border-t border-cyber">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition shadow-xs cursor-pointer">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. EDIT USER DETAILS MODAL -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-black text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-amber-500 dark:text-amber-400"></i> Edit User Account
            </h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="edit-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="edit-user-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Full Name</label>
                <input type="text" id="edit-user-name" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Email Address</label>
                <input type="email" id="edit-user-email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Assigned Role</label>
                    <select id="edit-user-role" name="role" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        <optgroup label="Executive & Administration">
                            <option value="owner">Owner (Executive)</option>
                            <option value="admin">System Admin</option>
                            <option value="manager">Branch Manager</option>
                        </optgroup>
                        <optgroup label="Operations & Production Floor">
                            <option value="production_officer">Production Officer</option>
                            <option value="production">Production Operator</option>
                        </optgroup>
                        <optgroup label="Customer Service & Design">
                            <option value="staff">Customer Service (CS)</option>
                        </optgroup>
                        <optgroup label="Warehouse & Logistics">
                            <option value="inventory">Inventory Staff</option>
                        </optgroup>
                        <optgroup label="Clients">
                            <option value="customer">Customer</option>
                        </optgroup>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Branch Hub</label>
                    <select id="edit-user-branch" name="branch_id" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        <option value="">System-Wide / All</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">New Password (Leave blank to keep unchanged)</label>
                <div class="relative">
                    <input type="password" 
                           id="edit-user-password"
                           name="password" 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-xl pl-3.5 pr-10 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none placeholder-slate-400" 
                           placeholder="Optional new password">
                    <button type="button" 
                            data-target="edit-user-password"
                            class="btn-toggle-pass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-500 transition cursor-pointer p-1"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition cursor-pointer" data-target="edit-user-modal">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl transition shadow-xs cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. RESET PASSWORD MODAL -->
<div id="reset-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-black text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-key text-indigo-500 dark:text-indigo-400"></i> Quick Password Reset
            </h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="reset-password-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="reset-password-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <p class="text-cyber-muted mb-4 text-xs">Resetting the security password for <strong id="reset-name-display" class="text-cyber-main"></strong>.</p>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">New Password</label>
                <div class="relative">
                    <input type="password" 
                           id="reset-user-password"
                           name="password" 
                           required 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-xl pl-3.5 pr-10 py-2 text-cyber-main focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-slate-400" 
                           placeholder="Enter new password (min 8 chars)">
                    <button type="button" 
                            data-target="reset-user-password"
                            class="btn-toggle-pass absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition cursor-pointer p-1"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition cursor-pointer" data-target="reset-password-modal">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition shadow-xs cursor-pointer">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. VIEW USER DETAILS MODAL -->
<div id="view-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-md shadow-2xl space-y-5">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-black text-cyber-main font-display flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-cyan-500 dark:text-cyan-400"></i> User Account Details
            </h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="view-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="space-y-4 text-xs">
            <div class="bg-cyber-sub p-4 rounded-2xl border border-cyber space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Account ID</span>
                    <span id="view-user-id" class="font-mono font-bold text-cyber-main text-sm"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Full Name</span>
                    <span id="view-user-name" class="font-bold text-cyber-main"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Email Address</span>
                    <span id="view-user-email" class="font-mono text-cyan-600 dark:text-cyan-400"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Assigned Role</span>
                    <span id="view-user-role" class="px-2.5 py-0.5 text-[10px] font-black rounded-full bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30 uppercase"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Branch Hub</span>
                    <span id="view-user-branch" class="font-semibold text-cyber-main"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Status</span>
                    <span id="view-user-status" class="px-2 py-0.5 text-[10px] font-black uppercase rounded"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Registered On</span>
                    <span id="view-user-created" class="text-cyber-muted font-medium"></span>
                </div>
            </div>
        </div>
        <div class="flex justify-end pt-2 border-t border-cyber">
            <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-main font-bold text-xs rounded-xl transition cursor-pointer" data-target="view-user-modal">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Open Create Modal
        const btnOpenCreate = document.getElementById('btn-open-create-user');
        if (btnOpenCreate) {
            btnOpenCreate.addEventListener('click', function () {
                document.getElementById('create-user-modal').classList.remove('hidden');
            });
        }

        // Close Modals
        document.querySelectorAll('.btn-close-modal').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                if (targetId) {
                    const modal = document.getElementById(targetId);
                    if (modal) modal.classList.add('hidden');
                }
            });
        });

        // Trigger View Modal
        document.querySelectorAll('.btn-trigger-view').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const ds = this.dataset;
                document.getElementById('view-user-id').textContent = '#' + ds.id;
                document.getElementById('view-user-name').textContent = ds.name;
                document.getElementById('view-user-email').textContent = ds.email;
                document.getElementById('view-user-role').textContent = ds.roleLabel;
                document.getElementById('view-user-branch').textContent = ds.branchName;
                
                const statusEl = document.getElementById('view-user-status');
                statusEl.textContent = ds.status;
                if (ds.status === 'Archived') {
                    statusEl.className = 'px-2 py-0.5 text-[10px] font-black uppercase rounded text-amber-500 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20';
                } else {
                    statusEl.className = 'px-2 py-0.5 text-[10px] font-black uppercase rounded text-emerald-500 dark:text-emerald-400 bg-emerald-500/10 border border-emerald-500/20';
                }
                
                document.getElementById('view-user-created').textContent = ds.created;
                document.getElementById('view-user-modal').classList.remove('hidden');
            });
        });

        // Trigger Edit Modal
        document.querySelectorAll('.btn-trigger-edit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const ds = this.dataset;
                const form = document.getElementById('edit-user-form');
                form.action = '/admin/users/' + ds.id;
                
                document.getElementById('edit-user-name').value = ds.name || '';
                document.getElementById('edit-user-email').value = ds.email || '';
                document.getElementById('edit-user-role').value = ds.role || 'staff';
                document.getElementById('edit-user-branch').value = ds.branchId || '';
                
                document.getElementById('edit-user-modal').classList.remove('hidden');
            });
        });

        // Trigger Reset Password Modal
        document.querySelectorAll('.btn-trigger-reset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const ds = this.dataset;
                const form = document.getElementById('reset-password-form');
                form.action = '/admin/users/' + ds.id + '/password';
                
                document.getElementById('reset-name-display').textContent = ds.name;
                document.getElementById('reset-password-modal').classList.remove('hidden');
            });
        });

        // Toggle Password Visibility
        document.querySelectorAll('.btn-toggle-pass').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (!input) return;
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        });

        // Archive / Restore Confirmation
        document.querySelectorAll('.form-toggle-archive').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                const isArchived = this.dataset.archived === '1';
                const msg = isArchived 
                    ? 'Are you sure you want to restore this user account?' 
                    : 'Are you sure you want to archive this user account?';
                if (!confirm(msg)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection
