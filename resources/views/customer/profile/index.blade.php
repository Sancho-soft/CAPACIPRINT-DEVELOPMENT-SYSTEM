@extends('layouts.customer')
@section('title', 'My Profile & Account Settings')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- Page Title Heading --}}
    <div class="flex items-center justify-between pb-1">
        <div>
            <span class="text-[11px] font-sans tabular-nums font-semibold uppercase tracking-wide text-cyber-sub block mb-1">Client Account</span>
            <h1 class="text-xl sm:text-2xl font-bold text-cyber-main font-display tracking-tight flex items-center gap-2.5">
                <i class="fa-solid fa-user-gear text-brand-600 dark:text-blue-400"></i> Account Settings
            </h1>
        </div>
    </div>

    {{-- Profile Information Card --}}
    <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 relative">
        <div class="flex items-center gap-3.5 pb-4 mb-5 border-b border-cyber-sub">
            <div class="h-11 w-11 rounded-lg bg-brand-600 text-white font-sans tabular-nums font-bold flex items-center justify-center text-base shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="space-y-0.5">
                <h3 class="text-base font-bold text-cyber-main font-display tracking-tight">{{ $user->name }}</h3>
                <div class="flex items-center gap-2 text-xs font-sans tabular-nums text-cyber-muted">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-brand-600 dark:text-blue-400 text-[11px]"></i> {{ $user->email }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Full Name --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-user text-brand-600 dark:text-blue-400 text-[11px]"></i> Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-xs text-cyber-main placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition font-medium">
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-cyber-sub text-[11px]"></i> Email Address
                    </label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-xs text-cyber-sub font-sans tabular-nums cursor-not-allowed select-none opacity-80">
                    <p class="text-[11px] text-cyber-sub mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[11px]"></i> Email cannot be modified for security.
                    </p>
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-brand-600 dark:text-blue-400 text-[11px]"></i> Phone Number
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+63 969 195 2485"
                           class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-xs text-cyber-main font-sans tabular-nums placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-brand-600 dark:text-blue-400 text-[11px]"></i> Address
                    </label>
                    <textarea name="address" rows="2" placeholder="Enter complete business or home address..."
                              class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-xs text-cyber-main placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition resize-none font-medium">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs tracking-wide transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Save Profile Details
                </button>
            </div>
        </form>
    </div>

    {{-- Security & Password Card --}}
    <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-cyber-sub">
            <h3 class="text-sm font-bold text-cyber-main font-display tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-brand-600 dark:text-blue-400 text-xs"></i> Security &amp; Password
            </h3>
        </div>

        <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Current Password --}}
                <div>
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5">
                        Current Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                               class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-9 py-2.5 text-xs text-cyber-main font-sans tabular-nums placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        <button type="button" @click="showCurrent = !showCurrent" aria-label="Toggle current password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-brand-600 dark:hover:text-blue-400 transition text-xs cursor-pointer">
                            <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5">
                        New Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" name="password" required placeholder="Min. 8 characters"
                               class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-9 py-2.5 text-xs text-cyber-main font-sans tabular-nums placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        <button type="button" @click="showNew = !showNew" aria-label="Toggle new password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-brand-600 dark:hover:text-blue-400 transition text-xs cursor-pointer">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label class="block text-[11px] font-sans tabular-nums font-semibold text-cyber-muted mb-1.5">
                        Confirm New Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required placeholder="Repeat new password"
                               class="w-full bg-cyber-sub border border-cyber rounded-lg pl-3.5 pr-9 py-2.5 text-xs text-cyber-main font-sans tabular-nums placeholder-cyber-sub focus:outline-none focus:border-brand-600 focus:ring-1 focus:ring-brand-600 transition">
                        <button type="button" @click="showConfirm = !showConfirm" aria-label="Toggle confirm password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-cyber-muted hover:text-brand-600 dark:hover:text-blue-400 transition text-xs cursor-pointer">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs tracking-wide transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-key text-xs"></i> Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
