@extends('layouts.internal')
@section('title', 'Edit Branch: ' . $branch->name)
@section('page-title', 'Configure Branch')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.branches.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Branch List
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Branch Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Branch Name *</label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Location / City --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Location / City *</label>
                    <input type="text" name="location" value="{{ old('location', $branch->location) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('location') border-rose-500 @enderror">
                    @error('location') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Max Daily Capacity --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Max Daily Jobs (Capacity) *</label>
                    <input type="number" name="max_daily_jobs" value="{{ old('max_daily_jobs', $branch->max_daily_jobs) }}" min="1" max="1000" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm @error('max_daily_jobs') border-rose-500 @enderror">
                    @error('max_daily_jobs') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Branch Manager Name --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Assigned Manager</label>
                    <input type="text" name="manager_name" value="{{ old('manager_name', $branch->manager_name) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>

                {{-- Phone Contact --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                </div>

                {{-- Status --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Operational Status *</label>
                    <select name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">
                        <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active (Accepting Print Jobs)</option>
                        <option value="maintenance" {{ old('status', $branch->status) === 'maintenance' ? 'selected' : '' }}>Maintenance (Partial Operations)</option>
                        <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive (Offline)</option>
                    </select>
                </div>

                {{-- Full Address --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Full Physical Address</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm">{{ old('address', $branch->address) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.branches.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
