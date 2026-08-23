@extends('layouts.internal')

@section('title', 'User Accounts & Access Control')
@section('page-title', 'User Administration')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-display">User Accounts & Role Permissions</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Create staff accounts, assign managerial roles, and configure system permissions.</p>
        </div>

        <button onclick="document.getElementById('new-user-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-brand-500/20 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Create New User Account
        </button>
    </div>

    <!-- Users Datatable -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-400 uppercase font-semibold text-xs tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Assigned Role</th>
                        <th class="px-6 py-4">Branch Hub</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($users as $usr)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($usr->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 dark:text-white">{{ $usr->name }}</div>
                                <div class="text-xs text-slate-400">ID #{{ $usr->id }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $usr->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-brand-100 text-brand-800 dark:bg-brand-900/40 dark:text-brand-300 uppercase">
                                {{ $usr->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $usr->branch->name ?? 'System-Wide / All' }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs text-emerald-500 font-bold">Active Account</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No user accounts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- New User Modal -->
<div id="new-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-display mb-4">Create User Account</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Full Name</label>
                <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="e.g. Maria Clara">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="e.g. maria@capaciprint.com">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">System Role</label>
                    <select name="role" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white">
                        <option value="customer">Customer</option>
                        <option value="staff">Customer Service Staff</option>
                        <option value="manager">Branch Manager</option>
                        <option value="production">Production Operator</option>
                        <option value="inventory">Inventory Staff</option>
                        <option value="management">Executive Owner</option>
                        <option value="admin">System Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Branch (Optional)</label>
                    <select name="branch_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white">
                        <option value="">System-Wide (All)</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white" placeholder="Minimum 8 characters">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('new-user-modal').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-semibold text-sm">Cancel</button>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2 rounded-xl text-sm shadow-md">Create Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
