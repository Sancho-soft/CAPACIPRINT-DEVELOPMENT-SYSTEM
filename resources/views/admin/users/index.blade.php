@extends('layouts.internal')

@section('title', 'User Accounts & Access Control')
@section('page-title', 'User Administration')

@section('content')
<div class="space-y-6 max-w-7xl">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-cyber-main font-display">User Accounts &amp; Access Control</h1>
            <p class="text-xs text-cyber-muted mt-1">Create staff accounts, assign managerial roles, and configure system permissions.</p>
        </div>

        <button onclick="document.getElementById('new-user-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.35)] transition">
            <i class="fa-solid fa-user-plus text-xs"></i>
            Create New User Account
        </button>
    </div>

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
                        <th class="px-5 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($users as $usr)
                    <tr class="hover:bg-cyber-sub/60 transition">
                        <td class="px-5 py-3.5 font-mono font-bold text-cyber-muted">#{{ $usr->id }}</td>
                        <td class="px-5 py-3.5 font-bold text-cyber-main">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-black flex items-center justify-center text-xs shrink-0">
                                    {{ strtoupper(substr($usr->name, 0, 2)) }}
                                </div>
                                <div class="font-bold text-cyber-main">{{ $usr->name }}</div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-cyber-muted text-xs">{{ $usr->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-0.5 text-[10px] font-black rounded-md bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 uppercase tracking-wider">
                                {{ str_replace('_', ' ', $usr->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-cyber-main">{{ $usr->branch->name ?? 'System-Wide / All' }}</td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="text-[10px] font-black uppercase text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded">Active</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-cyber-muted">No user accounts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-cyber">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- New User Modal -->
<div id="new-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-cyber-card border border-cyber rounded-3xl p-6 sm:p-7 w-full max-w-lg shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-cyber pb-3">
            <h3 class="text-lg font-black text-cyber-main font-display">Create User Account</h3>
            <button onclick="document.getElementById('new-user-modal').classList.add('hidden')" class="text-cyber-muted hover:text-cyber-main">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="e.g. Maria Clara">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="e.g. maria@capaciprint.com">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">System Role</label>
                    <select name="role" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <option value="customer">Customer</option>
                        <option value="staff">Customer Service Staff</option>
                        <option value="designer">Pre-Press Designer</option>
                        <option value="manager">Branch Manager</option>
                        <option value="production">Production Operator</option>
                        <option value="inventory">Inventory Staff</option>
                        <option value="management">Executive Owner</option>
                        <option value="admin">System Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Branch Allocation</label>
                    <select name="branch_id" class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none">
                        <option value="">System-Wide / All</option>
                        @foreach(\App\Models\Branch::all() as $br)
                            <option value="{{ $br->id }}">{{ $br->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-cyber-muted mb-1">Default Password</label>
                <input type="password" name="password" required class="w-full bg-cyber-sub border border-cyber rounded-xl px-3.5 py-2 text-cyber-main focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-500" placeholder="Enter default password">
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-cyber">
                <button type="button" onclick="document.getElementById('new-user-modal').classList.add('hidden')" class="px-4 py-2 bg-cyber-sub hover:bg-slate-700 border border-cyber text-cyber-muted font-bold rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black rounded-xl transition shadow-[0_0_15px_rgba(6,182,212,0.25)]">
                    Save Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
