@extends('layouts.internal')
@section('title', 'Edit Employee: ' . $employee->name)
@section('page-title', 'Update Employee Details')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Employee List
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Employee Full Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Employee Name *</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Position / Job Title --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Position / Role Title *</label>
                    <input type="text" name="position" value="{{ old('position', $employee->position) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm @error('position') border-rose-500 @enderror">
                    @error('position') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Assign Branch --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Assigned Branch *</label>
                    <select name="branch_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm @error('branch_id') border-rose-500 @enderror">
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ (old('branch_id', $employee->branch_id) == $b->id) ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->location }})
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Link to System User Account --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Link System Account</label>
                    <select name="user_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                        <option value="">No System Account</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id', $employee->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }} — {{ $u->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Availability Status --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Availability Status *</label>
                    <select name="availability_status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                        <option value="available" {{ old('availability_status', $employee->availability_status) === 'available' ? 'selected' : '' }}>Available (On Duty)</option>
                        <option value="on_leave" {{ old('availability_status', $employee->availability_status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="off_duty" {{ old('availability_status', $employee->availability_status) === 'off_duty' ? 'selected' : '' }}>Off Duty</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold text-sm shadow-md shadow-indigo-500/20 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
