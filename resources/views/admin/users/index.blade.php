@extends('layouts.internal')
@section('title', 'User & Access Management')
@section('page-title', 'User & Access Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-brand-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">User &amp; Access Management</h2>
                <p class="text-xs sm:text-sm text-slate-300">Manage user accounts, managerial roles, operational permissions, and system access.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm shadow-md shadow-brand-500/20 transition">
                <i class="fa-solid fa-user-plus"></i> Add New User
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 text-sm font-medium">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter & Search Card --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-6 md:col-span-7">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Search Users</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." 
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="sm:col-span-4 md:col-span-3">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Role Filter</label>
                <select name="role" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">All Roles</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-navy-900 hover:bg-navy-800 text-white rounded-xl text-sm font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">User</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Contact</th>
                        <th class="py-3.5 px-4">Joined Date</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold font-display text-sm shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-navy-900">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @php
                                    $roleBadge = match($u->role) {
                                        'superadmin' => 'bg-red-50 text-red-700 border-red-200',
                                        'admin'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'management' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'manager'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'planner'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'staff'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'designer'   => 'bg-pink-50 text-pink-700 border-pink-200',
                                        'production' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'inventory'  => 'bg-teal-50 text-teal-700 border-teal-200',
                                        default      => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $roleBadge }}">
                                    {{ $roles[$u->role] ?? ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-xs text-slate-700">{{ $u->phone ?? '—' }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-[150px]">{{ $u->address ?? 'No address' }}</div>
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-500">
                                {{ $u->created_at ? $u->created_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $u) }}" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition" title="Edit User">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if(auth()->id() !== $u->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition" title="Delete User">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-user-slash text-2xl mb-2"></i>
                                <p>No users found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
