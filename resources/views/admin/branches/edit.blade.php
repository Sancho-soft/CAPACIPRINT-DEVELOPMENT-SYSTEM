@extends('layouts.internal')
@section('title', 'Edit Branch: ' . $branch->name)
@section('page-title', 'Configure Branch')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold inline-flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Branch List
        </a>
    </div>

    <div class="bg-cyber-card p-6 sm:p-8 rounded-2xl border border-cyber shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-cyber-sub pb-4">
            <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-cyber-main font-display">Configure Branch: {{ $branch->name }}</h2>
                <p class="text-xs text-cyber-muted">Update facility location, daily job capacity, and operational status.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Branch Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Branch Name *</label>
                    <input type="text" name="name" value="{{ old('name', $branch->name) }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Location / City --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Location / City *</label>
                    <input type="text" name="location" value="{{ old('location', $branch->location) }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('location') border-rose-500 @enderror">
                    @error('location') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Max Daily Capacity --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Max Daily Jobs (Capacity) *</label>
                    <input type="number" name="max_daily_jobs" value="{{ old('max_daily_jobs', $branch->max_daily_jobs) }}" min="1" max="1000" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main tabular-nums rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('max_daily_jobs') border-rose-500 @enderror">
                    @error('max_daily_jobs') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Branch Manager Name --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Assigned Manager</label>
                    <input type="text" name="manager_name" value="{{ old('manager_name', $branch->manager_name) }}"
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs">
                </div>

                {{-- Phone Contact --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Contact Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main tabular-nums rounded-xl focus:border-blue-600 focus:outline-none text-xs">
                </div>

                {{-- Status --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Operational Status *</label>
                    <select name="status" required class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs">
                        <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active (Accepting Print Jobs)</option>
                        <option value="maintenance" {{ old('status', $branch->status) === 'maintenance' ? 'selected' : '' }}>Maintenance (Partial Operations)</option>
                        <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive (Offline)</option>
                    </select>
                </div>

                {{-- Full Address --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Full Physical Address</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs">{{ old('address', $branch->address) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-cyber-sub flex items-center justify-end gap-3">
                <a href="{{ route('admin.branches.index') }}" class="px-5 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
