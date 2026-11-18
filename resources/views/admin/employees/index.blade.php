@extends('layouts.internal')
@section('title', 'Employee & Staff Management')
@section('page-title', 'Employee & Staff Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    {{-- Header Banner --}}
    <div class="bg-cyber-card border border-cyber p-6 rounded-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight">Employee &amp; Staff Management</h2>
                <p class="text-xs sm:text-sm text-cyber-muted mt-0.5">Assign staff to branch locations, update job positions, and set operational availability statuses.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition">
                <i class="fa-solid fa-user-plus"></i> Add Employee
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-base"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 dark:text-rose-400 text-base"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-cyber-card p-5 rounded-2xl border border-cyber shadow-sm">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-5">
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">Search Employee</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyber-sub">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or position..." 
                           class="w-full pl-9 pr-4 py-2.5 text-xs bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">Branch Filter</label>
                <select name="branch_id" class="w-full px-3 py-2.5 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-cyber-main mb-1.5">Availability</label>
                <select name="status" class="w-full px-3 py-2.5 text-xs bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="off_duty" {{ request('status') === 'off_duty' ? 'selected' : '' }}>Off Duty</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'status']))
                    <a href="{{ route('admin.employees.index') }}" class="py-2.5 px-3 bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted rounded-xl text-xs font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Employee Table --}}
    <div id="employees-table-card" class="bg-cyber-card rounded-2xl border border-cyber shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-cyber-main tabular-nums">
                <thead class="bg-cyber-sub text-xs font-semibold text-cyber-muted border-b border-cyber">
                    <tr>
                        <th class="py-3.5 px-5">Employee Name</th>
                        <th class="py-3.5 px-4">Position</th>
                        <th class="py-3.5 px-4">Assigned Branch</th>
                        <th class="py-3.5 px-4">Linked User</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyber-sub">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-cyber-sub/50 transition-colors">
                            <td class="py-3.5 px-5 font-bold text-cyber-main">
                                {{ $emp->name }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-cyber-main">
                                {{ $emp->position }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5 text-xs text-cyber-main">
                                    <i class="fa-solid fa-shop text-blue-600 dark:text-blue-400"></i>
                                    {{ $emp->branch->name ?? 'Unassigned' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-cyber-muted">
                                {{ $emp->user->email ?? 'No system account' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $emp->availability_badge_class }}">
                                    {{ $emp->availability_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.employees.edit', $emp) }}" class="h-8 w-8 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-muted hover:text-blue-600 dark:hover:text-blue-400 flex items-center justify-center text-xs transition" title="Edit Employee">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}" onsubmit="return confirm('Are you sure you want to remove this employee record?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-xl bg-rose-500/10 hover:bg-rose-600 border border-rose-500/25 text-rose-600 dark:text-rose-400 hover:text-white flex items-center justify-center text-xs transition cursor-pointer" title="Delete Employee">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-cyber-muted text-xs">
                                <i class="fa-solid fa-id-card-clip text-2xl mb-2 block text-cyber-sub"></i>
                                <p>No employees found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="p-4 border-t border-cyber">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
