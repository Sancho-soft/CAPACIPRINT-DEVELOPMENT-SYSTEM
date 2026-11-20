@extends('layouts.internal')

@section('title', 'User Accounts & Access Control')
@section('page-title', 'User Administration')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display tracking-tight text-cyber-main">User Accounts &amp; Access Control</h1>
            <p class="text-sm text-cyber-muted mt-1">
                @if(request('archived'))
                    Showing archived user accounts. Restoring an account re-enables system access.
                @else
                    Manage staff roles, branch assignments, password resets, and customer accounts.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <button type="button" 
                    id="btn-open-create-user"
                    class="inline-flex items-center gap-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs px-4 py-2.5 rounded-lg transition cursor-pointer">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Add New User</span>
            </button>

            <a href="{{ route('admin.users.index', array_merge(request()->except('archived'), ['archived' => request('archived') ? 0 : 1])) }}" 
               class="inline-flex items-center gap-2 bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-main font-semibold text-xs px-4 py-2.5 rounded-lg transition">
                <i class="fa-solid {{ request('archived') ? 'fa-users text-[#2F7BFF]' : 'fa-box-archive text-[#FFC531]' }} text-xs"></i>
                <span>{{ request('archived') ? 'View Active Accounts' : 'View Archived' }}</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-amber-500/10 border border-[#FFC531]/30 text-amber-800 dark:text-[#FFC531] text-xs font-medium space-y-1">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                <span>Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="bg-cyber-card border border-cyber rounded-xl p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-sm">
            @if(request('archived'))
                <input type="hidden" name="archived" value="1">
            @endif

            <!-- Search Field -->
            <div class="sm:col-span-5 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-cyber-sub">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search user name or email..." 
                       class="w-full pl-9 pr-3.5 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-sub rounded-lg focus:outline-none focus:border-[#2F7BFF] text-sm">
            </div>

            <!-- Role Filter -->
            <div class="sm:col-span-4">
                <select name="role" class="w-full px-3 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:outline-none focus:border-[#2F7BFF] text-sm">
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
                <select name="branch_id" class="w-full px-3 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-lg focus:outline-none focus:border-[#2F7BFF] text-sm">
                    <option value="">All Branch Hubs</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2.5 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition shrink-0 cursor-pointer" title="Apply Filter">
                    <i class="fa-solid fa-filter text-xs"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Users Datatable Card -->
    <div id="users-table-card" class="bg-cyber-card border border-cyber rounded-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main tabular-nums">
                <thead class="bg-cyber-sub text-cyber-muted font-sans font-semibold text-xs border-b border-cyber">
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
                        $isLeadership = in_array($roleSlug, ['admin', 'system_admin', 'owner', 'management', 'manager']);
                    @endphp
                    <tr class="hover:bg-cyber-sub/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs text-cyber-muted">#{{ str_pad((string)$usr->id, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $usr->name }}</td>
                        <td class="px-5 py-3.5 text-cyber-muted text-xs">{{ $usr->email }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="text-xs font-semibold {{ $isLeadership ? 'text-[#2F7BFF] dark:text-[#60A5FA]' : ($roleSlug === 'customer' ? 'text-cyber-muted font-medium' : 'text-cyber-main') }}">
                                {{ $displayRole }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-cyber-main">{{ $usr->branch->name ?? 'System-Wide / All' }}</td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($usr->is_archived)
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-[#FFC531]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#FFC531]"></span>
                                    Archived
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-[#10B981]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                {{-- View Details --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        data-email="{{ $usr->email }}"
                                        data-role-label="{{ $displayRole }}"
                                        data-branch-name="{{ $usr->branch->name ?? 'System-Wide / All' }}"
                                        data-status="{{ $usr->is_archived ? 'Archived' : 'Active' }}"
                                        data-created="{{ $usr->created_at ? $usr->created_at->format('M d, Y g:i A') : 'N/A' }}"
                                        class="btn-trigger-view h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] flex items-center justify-center text-xs transition cursor-pointer" 
                                        title="View Account Details">
                                    <i class="fa-solid fa-eye pointer-events-none"></i>
                                </button>

                                {{-- Edit User Details --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        data-email="{{ $usr->email }}"
                                        data-role="{{ $usr->role }}"
                                        data-branch-id="{{ $usr->branch_id ?? '' }}"
                                        class="btn-trigger-edit h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] flex items-center justify-center text-xs transition cursor-pointer" 
                                        title="Edit User Details">
                                    <i class="fa-solid fa-pen-to-square pointer-events-none"></i>
                                </button>

                                {{-- Reset Password --}}
                                <button type="button" 
                                        data-id="{{ $usr->id }}"
                                        data-name="{{ $usr->name }}"
                                        class="btn-trigger-reset h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main hover:border-[#2F7BFF] flex items-center justify-center text-xs transition cursor-pointer" 
                                        title="Reset Password">
                                    <i class="fa-solid fa-key pointer-events-none"></i>
                                </button>

                                {{-- Archive / Restore --}}
                                <form method="POST" action="{{ route('admin.users.toggle-archive', $usr) }}" 
                                      data-archived="{{ $usr->is_archived ? '1' : '0' }}"
                                      class="inline form-toggle-archive">
                                    @csrf
                                    <button type="submit" 
                                            class="h-8 w-8 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted flex items-center justify-center text-xs transition cursor-pointer {{ $usr->is_archived ? 'hover:text-[#10B981] hover:border-[#10B981]' : 'hover:text-[#FFC531] hover:border-[#FFC531]' }}" 
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
                            <i class="fa-solid fa-users text-3xl mb-2 text-cyber-muted block"></i>
                            <span class="font-bold text-sm block">No User Accounts Found</span>
                            <span class="text-xs text-cyber-muted mt-1 block">Try clearing filters or search query.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-3.5 border-t border-cyber bg-cyber-sub flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-cyber-muted font-medium">
                Showing <span class="font-mono font-bold text-cyber-main">{{ $users->firstItem() }}</span> to <span class="font-mono font-bold text-cyber-main">{{ $users->lastItem() }}</span> of <span class="font-mono font-bold text-cyber-main">{{ $users->total() }}</span> accounts
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- 1. ADD NEW USER MODAL -->
<div id="create-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-xl p-6 sm:p-7 w-full max-w-lg space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-bold text-cyber-main font-display">Add New User Account</h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="create-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-sm">
            @csrf
            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Full Name</label>
                <input type="text" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" placeholder="e.g. Kim Jack Lupogan">
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Email Address</label>
                <input type="email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" placeholder="user@capaciprint.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Assigned Role</label>
                    <select name="role" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
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
                    <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Branch Hub</label>
                    <select name="branch_id" class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
                        <option value="">System-Wide / All</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" 
                           id="create-user-password"
                           name="password" 
                           required 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-10 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                           placeholder="Minimum 8 characters">
                    <button type="button" 
                            data-target="create-user-password"
                            class="btn-toggle-pass absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-[#2F7BFF] transition cursor-pointer"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-cyber">
                <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs rounded-lg transition cursor-pointer" data-target="create-user-modal">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. EDIT USER DETAILS MODAL -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-xl p-6 sm:p-7 w-full max-w-lg space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-bold text-cyber-main font-display">Edit User Account</h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="edit-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="edit-user-form" method="POST" action="" class="space-y-4 text-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Full Name</label>
                <input type="text" id="edit-user-name" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Email Address</label>
                <input type="email" id="edit-user-email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Assigned Role</label>
                    <select id="edit-user-role" name="role" required class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
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
                    <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">Branch Hub</label>
                    <select id="edit-user-branch" name="branch_id" class="w-full bg-cyber-sub border border-cyber rounded-lg px-3 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none text-sm">
                        <option value="">System-Wide / All</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">New Password (leave blank to keep unchanged)</label>
                <div class="relative">
                    <input type="password" 
                           id="edit-user-password"
                           name="password" 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-10 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                           placeholder="Optional new password">
                    <button type="button" 
                            data-target="edit-user-password"
                            class="btn-toggle-pass absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-[#2F7BFF] transition cursor-pointer"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-cyber">
                <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs rounded-lg transition cursor-pointer" data-target="edit-user-modal">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 3. RESET PASSWORD MODAL -->
<div id="reset-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-xl p-6 sm:p-7 w-full max-w-lg space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-bold text-cyber-main font-display">Quick Password Reset</h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="reset-password-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="reset-password-form" method="POST" action="" class="space-y-4 text-sm">
            @csrf
            @method('PUT')
            <div>
                <p class="text-cyber-muted mb-4 text-xs">Resetting the password for <strong id="reset-name-display" class="text-cyber-main"></strong>.</p>
                <label class="block text-[13px] font-semibold text-cyber-main mb-1.5">New Password</label>
                <div class="relative">
                    <input type="password" 
                           id="reset-user-password"
                           name="password" 
                           required 
                           minlength="8" 
                           class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-10 py-2 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                           placeholder="Enter new password (min 8 chars)">
                    <button type="button" 
                            data-target="reset-user-password"
                            class="btn-toggle-pass absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-[#2F7BFF] transition cursor-pointer"
                            title="Toggle password visibility">
                        <i class="fa-solid fa-eye pointer-events-none"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-cyber">
                <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs rounded-lg transition cursor-pointer" data-target="reset-password-modal">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 4. VIEW USER DETAILS MODAL -->
<div id="view-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 overscroll-contain">
    <div class="bg-cyber-card border border-cyber rounded-xl p-6 sm:p-7 w-full max-w-md space-y-5">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-bold text-cyber-main font-display">User Account Details</h3>
            <button type="button" class="btn-close-modal text-cyber-muted hover:text-cyber-main cursor-pointer" data-target="view-user-modal">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="divide-y divide-cyber-sub border-y border-cyber text-xs">
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Account ID</span>
                <span id="view-user-id" class="font-mono font-bold text-cyber-main text-sm"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Full Name</span>
                <span id="view-user-name" class="font-bold text-cyber-main"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Email Address</span>
                <span id="view-user-email" class="text-[#2F7BFF] dark:text-[#60A5FA]"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Assigned Role</span>
                <span id="view-user-role" class="font-semibold text-cyber-main"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Branch Hub</span>
                <span id="view-user-branch" class="font-semibold text-cyber-main"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Status</span>
                <span id="view-user-status" class="font-semibold"></span>
            </div>
            <div class="flex justify-between items-center py-2.5">
                <span class="text-cyber-muted font-medium">Registered On</span>
                <span id="view-user-created" class="text-cyber-muted font-mono tabular-nums"></span>
            </div>
        </div>
        <div class="flex justify-end pt-1">
            <button type="button" class="btn-close-modal px-4 py-2 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs rounded-lg transition cursor-pointer" data-target="view-user-modal">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnOpenCreate = document.getElementById('btn-open-create-user');
        if (btnOpenCreate) {
            btnOpenCreate.addEventListener('click', function () {
                document.getElementById('create-user-modal').classList.remove('hidden');
            });
        }

        document.querySelectorAll('.btn-close-modal').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                if (targetId) {
                    const modal = document.getElementById(targetId);
                    if (modal) modal.classList.add('hidden');
                }
            });
        });

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
                    statusEl.className = 'font-semibold text-xs text-amber-700 dark:text-[#FFC531]';
                } else {
                    statusEl.className = 'font-semibold text-xs text-emerald-700 dark:text-[#10B981]';
                }
                
                document.getElementById('view-user-created').textContent = ds.created;
                document.getElementById('view-user-modal').classList.remove('hidden');
            });
        });

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

        document.querySelectorAll('.btn-trigger-reset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const ds = this.dataset;
                const form = document.getElementById('reset-password-form');
                form.action = '/admin/users/' + ds.id + '/password';
                
                document.getElementById('reset-name-display').textContent = ds.name;
                document.getElementById('reset-password-modal').classList.remove('hidden');
            });
        });

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
