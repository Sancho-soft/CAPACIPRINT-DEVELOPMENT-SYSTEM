@extends('layouts.internal')

@section('title', 'User Accounts & Access Control')
@section('page-title', 'User Administration')

@section('content')
<div class="space-y-6 max-w-7xl">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display">Executive Accounts &amp; Access Control</h1>
            <p class="text-xs text-cyber-muted mt-1">
                @if(request('archived'))
                    Showing archived executive accounts. Restoring an account will re-enable system access.
                @else
                    Create new executive accounts, edit profiles, reset passwords, and manage status.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="openCreateUserModal()" 
                    class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs px-4 py-2.5 rounded-xl transition shadow-[0_0_15px_rgba(6,182,212,0.3)] cursor-pointer">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Add New User</span>
            </button>

            <a href="{{ route('admin.users.index', ['archived' => request('archived') ? 0 : 1]) }}" 
               class="inline-flex items-center gap-2 bg-cyber-sub hover:bg-slate-800 border border-cyber text-cyber-main font-bold text-xs px-4 py-2.5 rounded-xl transition">
                <i class="fa-solid {{ request('archived') ? 'fa-users text-cyan-400' : 'fa-box-archive text-amber-400' }} text-xs"></i>
                {{ request('archived') ? 'View Active Accounts' : 'View Archived' }}
            </a>
        </div>
    </div>


    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-medium space-y-1">
            <div class="flex items-center gap-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span>Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Users Datatable -->
    <div class="bg-cyber-card border border-cyber rounded-3xl shadow-xl overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main">
                <thead class="bg-cyber-sub text-cyber-muted uppercase font-bold text-[11px] tracking-wider border-b border-cyber">
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
                    <tr class="hover:bg-cyber-sub/60 transition">
                        <td class="px-5 py-3.5 font-mono font-bold text-cyber-muted">#{{ $usr->id }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyber-main">{{ $usr->name }}</td>
                        <td class="px-5 py-3.5 font-mono text-cyber-muted text-xs">{{ $usr->email }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyber-main uppercase">{{ str_replace('_', ' ', $usr->role) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $usr->branch->name ?? 'System-Wide / All' }}</td>
                        <td class="px-5 py-3.5">
                            @if($usr->is_archived)
                                <span class="text-[10px] font-black uppercase text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded">Archived</span>
                            @else
                                <span class="text-[10px] font-black uppercase text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded">Active</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                {{-- 1. View Details Button --}}
                                <button type="button" 
                                        onclick="openViewUserModal({{ json_encode([
                                            'id' => $usr->id,
                                            'name' => $usr->name,
                                            'email' => $usr->email,
                                            'role' => $usr->role,
                                            'role_label' => str_replace('_', ' ', strtoupper($usr->role)),
                                            'branch_name' => $usr->branch->name ?? 'System-Wide / All',
                                            'status' => $usr->is_archived ? 'Archived' : 'Active',
                                            'created_at' => $usr->created_at ? $usr->created_at->format('M d, Y g:i A') : 'N/A'
                                        ]) }})" 
                                        class="h-8 w-8 rounded-xl bg-cyan-500/10 hover:bg-cyan-500 text-cyan-400 hover:text-slate-950 border border-cyan-500/30 flex items-center justify-center text-xs transition shadow-sm cursor-pointer" 
                                        title="View Account Details">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                {{-- 2. Edit User Details Button --}}
                                <button type="button" 
                                        onclick="openEditUserModal({{ json_encode([
                                            'id' => $usr->id,
                                            'name' => $usr->name,
                                            'email' => $usr->email,
                                            'role' => $usr->role,
                                            'branch_id' => $usr->branch_id
                                        ]) }})" 
                                        class="h-8 w-8 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-slate-950 border border-amber-500/30 flex items-center justify-center text-xs transition shadow-sm cursor-pointer" 
                                        title="Edit User Details">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                {{-- 3. Reset Password Button --}}
                                <button type="button" 
                                        onclick="openResetPasswordModal({{ json_encode([
                                            'id' => $usr->id,
                                            'name' => $usr->name
                                        ]) }})" 
                                        class="h-8 w-8 rounded-xl bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white border border-indigo-500/30 flex items-center justify-center text-xs transition shadow-sm cursor-pointer" 
                                        title="Reset Password">
                                    <i class="fa-solid fa-key"></i>
                                </button>

                                {{-- 4. Archive / Restore Button --}}
                                <form method="POST" action="{{ route('admin.users.toggle-archive', $usr) }}" 
                                      onsubmit="return confirm('Are you sure you want to {{ $usr->is_archived ? 'restore' : 'archive' }} this user account?');" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="h-8 w-8 rounded-xl {{ $usr->is_archived ? 'bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-slate-950 border border-emerald-500/30' : 'bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-slate-950 border border-rose-500/30' }} flex items-center justify-center text-xs transition shadow-sm cursor-pointer" 
                                            title="{{ $usr->is_archived ? 'Restore User Account' : 'Archive User Account' }}">
                                        <i class="fa-solid {{ $usr->is_archived ? 'fa-rotate-left' : 'fa-box-archive' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-cyber-muted">No {{ request('archived') ? 'archived' : 'active' }} user accounts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-cyber bg-cyber-sub flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <p class="text-cyber-muted font-medium">
                Showing <span class="font-bold text-cyber-main font-mono">{{ $users->firstItem() }}</span> to <span class="font-bold text-cyber-main font-mono">{{ $users->lastItem() }}</span> of <span class="font-bold text-cyan-400 font-mono">{{ $users->total() }}</span> accounts
            </p>
            <div class="flex items-center gap-1.5 font-bold">
                {{-- Previous Page --}}
                @if($users->onFirstPage())
                    <span class="px-3 py-1.5 rounded-xl bg-cyber-card/50 border border-cyber text-cyber-muted/40 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </span>
                @else
                    <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-cyber-card hover:bg-cyan-500/20 border border-cyber hover:border-cyan-500/50 text-cyber-main hover:text-cyan-400 transition">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="px-3.5 py-1.5 rounded-xl bg-cyan-500 text-slate-950 font-black border border-cyan-400 shadow-[0_0_12px_rgba(6,182,212,0.35)]">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $users->appends(request()->query())->url($page) }}" class="px-3.5 py-1.5 rounded-xl bg-cyber-card hover:bg-cyber-sub border border-cyber text-cyber-muted hover:text-cyber-main transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl bg-cyber-card hover:bg-cyan-500/20 border border-cyber hover:border-cyan-500/50 text-cyber-main hover:text-cyan-400 transition">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                @else
                    <span class="px-3 py-1.5 rounded-xl bg-cyber-card/50 border border-cyber text-cyber-muted/40 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </span>
                @endif
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
                <i class="fa-solid fa-user-plus text-cyan-400"></i> Add New Executive User
            </h3>
            <button type="button" onclick="closeCreateUserModal()" class="text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Full Name *</label>
                <input type="text" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="e.g. John Doe">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Email Address *</label>
                <input type="email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="user@capaciprint.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Assigned Role *</label>
                    <select name="role" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <option value="owner">Owner (Executive)</option>
                        <option value="admin">System Admin</option>
                        <option value="manager">Branch Manager</option>
                        <option value="management">Management</option>
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
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Password *</label>
                <input type="password" name="password" required minlength="8" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="Minimum 8 characters">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" onclick="closeCreateUserModal()" class="px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black rounded-xl transition shadow-[0_0_12px_rgba(6,182,212,0.3)]">
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
                <i class="fa-solid fa-pen-to-square text-amber-400"></i> Edit User Account
            </h3>
            <button type="button" onclick="closeEditUserModal()" class="text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="edit-user-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Full Name *</label>
                <input type="text" id="edit-user-name" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Email Address *</label>
                <input type="email" id="edit-user-email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Assigned Role *</label>
                    <select id="edit-user-role" name="role" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none">
                        <option value="owner">Owner (Executive)</option>
                        <option value="admin">System Admin</option>
                        <option value="manager">Branch Manager</option>
                        <option value="management">Management</option>
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
                <input type="password" name="password" minlength="8" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-amber-500 focus:outline-none placeholder-slate-500" placeholder="Optional new password">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-xl transition shadow-md shadow-amber-500/20">
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
                <i class="fa-solid fa-key text-indigo-400"></i> Quick Password Reset
            </h3>
            <button type="button" onclick="closeResetPasswordModal()" class="text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="reset-password-form" method="POST" action="" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <p class="text-cyber-muted mb-4 text-xs">Resetting the security password for <strong id="reset-name-display" class="text-cyber-main"></strong>.</p>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">New Password *</label>
                <input type="password" name="password" required minlength="8" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-slate-500" placeholder="Enter new password (min 8 chars)">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-black rounded-xl transition shadow-md shadow-indigo-500/20">
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
                <i class="fa-solid fa-user-gear text-cyan-400"></i> User Account Details
            </h3>
            <button type="button" onclick="closeViewUserModal()" class="text-cyber-muted hover:text-cyber-main">
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
                    <span id="view-user-email" class="font-mono text-cyan-400"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-cyber-muted font-bold uppercase tracking-wider text-[10px]">Assigned Role</span>
                    <span id="view-user-role" class="px-2.5 py-0.5 text-[10px] font-black rounded-md bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 uppercase"></span>
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
            <button type="button" onclick="closeViewUserModal()" class="px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-main font-bold text-xs rounded-xl transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    // Create User Modal Handlers
    function openCreateUserModal() {
        document.getElementById('create-user-modal').classList.remove('hidden');
    }
    function closeCreateUserModal() {
        document.getElementById('create-user-modal').classList.add('hidden');
    }

    // Edit User Modal Handlers
    function openEditUserModal(user) {
        const form = document.getElementById('edit-user-form');
        form.action = '/admin/users/' + user.id;
        
        document.getElementById('edit-user-name').value = user.name || '';
        document.getElementById('edit-user-email').value = user.email || '';
        document.getElementById('edit-user-role').value = user.role || 'admin';
        document.getElementById('edit-user-branch').value = user.branch_id || '';
        
        document.getElementById('edit-user-modal').classList.remove('hidden');
    }
    function closeEditUserModal() {
        document.getElementById('edit-user-modal').classList.add('hidden');
    }

    // Reset Password Modal Handlers
    function openResetPasswordModal(user) {
        const form = document.getElementById('reset-password-form');
        form.action = '/admin/users/' + user.id + '/password';
        
        document.getElementById('reset-name-display').textContent = user.name;
        document.getElementById('reset-password-modal').classList.remove('hidden');
    }
    function closeResetPasswordModal() {
        document.getElementById('reset-password-modal').classList.add('hidden');
    }

    // View User Modal Handlers
    function openViewUserModal(user) {
        document.getElementById('view-user-id').textContent = '#' + user.id;
        document.getElementById('view-user-name').textContent = user.name;
        document.getElementById('view-user-email').textContent = user.email;
        document.getElementById('view-user-role').textContent = user.role_label;
        document.getElementById('view-user-branch').textContent = user.branch_name;
        
        const statusEl = document.getElementById('view-user-status');
        statusEl.textContent = user.status;
        if (user.status === 'Archived') {
            statusEl.className = 'px-2 py-0.5 text-[10px] font-black uppercase rounded text-amber-400 bg-amber-500/10 border border-amber-500/20';
        } else {
            statusEl.className = 'px-2 py-0.5 text-[10px] font-black uppercase rounded text-emerald-400 bg-emerald-500/10 border border-emerald-500/20';
        }
        
        document.getElementById('view-user-created').textContent = user.created_at;
        document.getElementById('view-user-modal').classList.remove('hidden');
    }
    function closeViewUserModal() {
        document.getElementById('view-user-modal').classList.add('hidden');
    }
</script>
@endsection