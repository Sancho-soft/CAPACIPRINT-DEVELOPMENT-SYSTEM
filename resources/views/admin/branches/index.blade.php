@extends('layouts.internal')
@section('title', 'Branch Management')
@section('page-title', 'Branch Management')

@section('content')
<div class="space-y-6 max-w-7xl">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-blue-500 text-white flex items-center justify-center text-2xl shadow-lg shrink-0">
                <i class="fa-solid fa-network-wired"></i>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-display">Branch Management</h2>
                <p class="text-xs sm:text-sm text-slate-300">Configure printing branches, daily capacity thresholds, contact details, and locations.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition">
                <i class="fa-solid fa-plus"></i> Create Branch
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
        <form method="GET" action="{{ route('admin.branches.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-7">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Search Branches</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, location, or manager..." 
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status Filter</label>
                <select name="status" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-4 bg-navy-900 hover:bg-navy-800 text-white rounded-xl text-sm font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.branches.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Branches Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($branches as $b)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                                <i class="fa-solid fa-shop"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-navy-900 font-display text-base">{{ $b->name }}</h3>
                                <p class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> {{ $b->location }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $b->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </div>

                    @if($b->address)
                        <p class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl">{{ $b->address }}</p>
                    @endif

                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <div class="text-base font-bold text-navy-900">{{ $b->employees_count }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">Staff</div>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <div class="text-base font-bold text-navy-900">{{ $b->machines_count }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">Machines</div>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl">
                            <div class="text-base font-bold text-blue-600">{{ $b->max_daily_jobs }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">Max Daily</div>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Manager:</span>
                            <span class="font-semibold text-slate-700">{{ $b->manager_name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Contact Phone:</span>
                            <span class="font-semibold text-slate-700">{{ $b->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('admin.branches.show', $b) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                        <i class="fa-solid fa-eye"></i> View Branch Status
                    </a>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.branches.edit', $b) }}" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs transition" title="Edit Branch">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.branches.destroy', $b) }}" onsubmit="return confirm('Are you sure you want to delete this branch?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="h-8 w-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition" title="Delete Branch">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-100">
                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2"></i>
                <p class="text-sm">No branches found matching your search.</p>
            </div>
        @endforelse
    </div>

    @if($branches->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-100">
            {{ $branches->links() }}
        </div>
    @endif
</div>
@endsection
