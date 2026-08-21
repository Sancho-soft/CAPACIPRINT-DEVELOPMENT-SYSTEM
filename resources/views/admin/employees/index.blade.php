@extends('layouts.internal')
@section('title', 'Employee & Staff Management')
@section('page-title', 'Employee & Staff Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-indigo-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Employee &amp; Staff Management</h2>
                <p class="text-xs sm:text-sm text-slate-300">Assign staff to branch locations, update job positions, and set operational availability statuses.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold text-sm shadow-md shadow-indigo-500/20 transition">
                <i class="fa-solid fa-user-plus"></i> Add Employee
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

    {{-- Filter Card --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-5">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Search Employee</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or position..." 
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Branch Filter</label>
                <select name="branch_id" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Availability</label>
                <select name="status" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    <option value="off_duty" {{ request('status') === 'off_duty' ? 'selected' : '' }}>Off Duty</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-navy-900 hover:bg-navy-800 text-white rounded-xl text-sm font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'status']))
                    <a href="{{ route('admin.employees.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Employee Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase font-extrabold text-slate-400 tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Employee Name</th>
                        <th class="py-3.5 px-4">Position</th>
                        <th class="py-3.5 px-4">Assigned Branch</th>
                        <th class="py-3.5 px-4">Linked User</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold font-display text-sm shrink-0">
                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                    </div>
                                    <div class="font-bold text-navy-900">{{ $emp->name }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-700">
                                {{ $emp->position }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-1.5 text-xs text-slate-700">
                                    <i class="fa-solid fa-shop text-slate-400"></i>
                                    {{ $emp->branch->name ?? 'Unassigned' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-500">
                                {{ $emp->user->email ?? 'No system account' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $emp->availability_badge_class }}">
                                    {{ $emp->availability_label }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.employees.edit', $emp) }}" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition" title="Edit Employee">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}" onsubmit="return confirm('Are you sure you want to remove this employee record?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition" title="Delete Employee">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-id-card-clip text-2xl mb-2"></i>
                                <p>No employees found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
