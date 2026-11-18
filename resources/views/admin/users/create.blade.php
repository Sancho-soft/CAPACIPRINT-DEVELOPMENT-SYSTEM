@extends('layouts.internal')
@section('title', 'Add New User')
@section('page-title', 'Create User Account')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main text-xs font-semibold inline-flex items-center gap-2 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to User List
        </a>
    </div>

    <div class="bg-cyber-card p-6 sm:p-8 rounded-2xl border border-cyber shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-cyber-sub pb-4">
            <div class="h-10 w-10 rounded-xl bg-blue-600/10 border border-blue-600/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-cyber-main font-display">Create User Account</h2>
                <p class="text-xs text-cyber-muted">Provision a new account and assign role permissions.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Full Name --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('name') border-rose-500 @enderror"
                           placeholder="e.g. John Doe">
                    @error('name') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('email') border-rose-500 @enderror"
                           placeholder="user@example.com">
                    @error('email') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role Selection --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Role Assignment *</label>
                    <select name="role" required class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('role') border-rose-500 @enderror">
                        <option value="">Select Role</option>
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted tabular-nums rounded-xl focus:border-blue-600 focus:outline-none text-xs"
                           placeholder="+63 912 345 6789">
                </div>

                {{-- Physical Address --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Address / Branch Location</label>
                    <input type="text" name="address" value="{{ old('address') }}"
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs"
                           placeholder="e.g. Branch Office, Street, City">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Password *</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs @error('password') border-rose-500 @enderror"
                           placeholder="Minimum 8 characters">
                    @error('password') <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-xs font-sans font-semibold text-cyber-main mb-1.5">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 bg-cyber-sub border border-cyber text-cyber-main placeholder-cyber-muted rounded-xl focus:border-blue-600 focus:outline-none text-xs"
                           placeholder="Repeat password">
                </div>
            </div>

            <div class="pt-4 border-t border-cyber-sub flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-cyber-sub hover:bg-cyber-card border border-cyber text-cyber-main font-semibold text-xs transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm transition cursor-pointer">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
