@extends('layouts.internal')
@section('title', 'Add Employee')
@section('page-title', 'Register & Assign Employee')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold inline-flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Employee List
        </a>
    </div>

    <div class="bg-cyber-card p-6 sm:p-8 rounded-2xl border border-cyber shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-cyber-sub pb-4">
            <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-cyber-main font-display">Register &amp; Assign Employee</h2>
                <p class="text-xs text-cyber-muted">Add a staff member, assign a branch node, and link an optional system account.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Employee Full Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Employee Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('name') border-rose-500 @enderror"
                           placeholder="e.g. Maria Santos">
                    @error('name') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Position / Job Title --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Position / Role Title *</label>
                    <input type="text" name="position" value="{{ old('position') }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('position') border-rose-500 @enderror"
                           placeholder="e.g. Senior Machine Operator / Cashier">
                    @error('position') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Assign Branch --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Assigned Branch *</label>
                    <select name="branch_id" required class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('branch_id') border-rose-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ (old('branch_id', request('branch_id')) == $b->id) ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->location }})
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Link to System User Account --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Link System Account (Optional)</label>
                    <select name="user_id" class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs">
                        <option value="">No System Account</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }} — {{ $u->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Availability Status --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Availability Status *</label>
                    <select name="availability_status" required class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs">
                        <option value="available" {{ old('availability_status') === 'available' ? 'selected' : '' }}>Available (On Duty)</option>
                        <option value="on_leave" {{ old('availability_status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="off_duty" {{ old('availability_status') === 'off_duty' ? 'selected' : '' }}>Off Duty</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-cyber-sub flex items-center justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">
                    Save Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
