@extends('layouts.internal')
@section('title', 'Edit User: ' . $user->name)
@section('page-title', 'Edit User Account')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-navy-900 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to User List
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Full Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm @error('name') border-rose-500 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role Selection --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Role Assignment *</label>
                    <select name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm @error('role') border-rose-500 @enderror">
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm">
                </div>

                {{-- Physical Address --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Address / Branch Location</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm">
                </div>

                <div class="sm:col-span-2 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Change Password (Leave blank to keep current)</h4>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">New Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm @error('password') border-rose-500 @enderror"
                           placeholder="Enter new password">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:outline-none text-sm"
                           placeholder="Repeat new password">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm shadow-md shadow-brand-500/20 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
