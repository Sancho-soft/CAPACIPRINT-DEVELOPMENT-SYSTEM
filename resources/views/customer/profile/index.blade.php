@extends('layouts.customer')
@section('title', 'My Profile & Account Settings')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page Title Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#111A24] border border-slate-800/80 p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-user-gear text-cyan-400"></i> Account Settings
            </h1>
        </div>
    </div>

    {{-- Profile Information Card --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-2xl shadow-xl p-6 sm:p-8 relative">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 pb-6 mb-6 border-b border-slate-800/80">
            <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 text-slate-950 font-black flex items-center justify-center text-3xl font-display shadow-[0_0_20px_rgba(6,182,212,0.35)] shrink-0 border-2 border-cyan-300">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="text-center sm:text-left space-y-1">
                <h3 class="text-xl font-black text-white font-display tracking-tight">{{ $user->name }}</h3>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-cyan-400"></i> {{ $user->email }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Full Name --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-user text-cyan-400 text-xs"></i> Full Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-[#0D1520] border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition font-medium">
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-slate-500 text-xs"></i> Email Address
                    </label>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full bg-[#0B1118] border border-slate-800/80 rounded-xl px-4 py-3 text-sm text-slate-400 font-mono cursor-not-allowed select-none">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[10px] text-slate-600"></i> Email cannot be modified for security.
                    </p>
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-phone text-cyan-400 text-xs"></i> Phone Number
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+63 969 195 2485"
                           class="w-full bg-[#0D1520] border border-slate-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2 font-sans">
                        <i class="fa-solid fa-location-dot text-cyan-400 text-xs"></i> Address
                    </label>
                    <textarea name="address" rows="3" placeholder="Enter complete business or home address..."
                              class="w-full bg-[#0D1520] border border-slate-800 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition resize-none font-medium">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition shadow-[0_0_20px_rgba(6,182,212,0.3)] flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-sm"></i> Save Profile Details
                </button>
            </div>
        </form>
    </div>

    {{-- Security & Password Card --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-2xl shadow-xl p-6 sm:p-8" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-800/80">
            <div>
                <h3 class="text-lg font-black text-white font-display tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-cyan-400 text-sm"></i> Security &amp; Password
                </h3>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Current Password --}}
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                        Current Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-xl pl-4 pr-10 py-3 text-sm text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                        New Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" name="password" required placeholder="Min. 8 characters"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-xl pl-4 pr-10 py-3 text-sm text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showNew = !showNew" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-2">
                        Confirm New Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required placeholder="Repeat new password"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-xl pl-4 pr-10 py-3 text-sm text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition shadow-[0_0_20px_rgba(6,182,212,0.3)] flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-key text-xs"></i> Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
