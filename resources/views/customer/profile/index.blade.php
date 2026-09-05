@extends('layouts.customer')
@section('title', 'My Profile & Account Settings')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- Page Title Heading --}}
    <div class="flex items-center justify-between pb-1">
        <h1 class="text-xl sm:text-2xl font-black text-navy-900 dark:text-white font-display tracking-tight flex items-center gap-2.5">
            <i class="fa-solid fa-user-gear text-cyan-400"></i> Account Settings
        </h1>
    </div>

    {{-- Profile Information Card --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-xl shadow-md p-4 sm:p-5 relative">
        <div class="flex items-center gap-3.5 pb-3 mb-4 border-b border-slate-800/80">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 text-slate-950 font-black flex items-center justify-center text-lg font-display shadow-md shrink-0 border border-cyan-300">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="space-y-0.5">
                <h3 class="text-base font-bold text-white font-display tracking-tight">{{ $user->name }}</h3>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-envelope text-cyan-400 text-[11px]"></i> {{ $user->email }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                {{-- Full Name --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-user text-cyan-400 text-[10px]"></i> Full Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-[#0D1520] border border-slate-800 rounded-lg px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition font-medium">
                </div>

                {{-- Email Address --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-slate-500 text-[10px]"></i> Email Address
                    </label>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full bg-[#0B1118] border border-slate-800/80 rounded-lg px-3.5 py-2 text-xs text-slate-400 font-mono cursor-not-allowed select-none">
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[9px] text-slate-600"></i> Email cannot be modified for security.
                    </p>
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-cyan-400 text-[10px]"></i> Phone Number
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+63 969 195 2485"
                           class="w-full bg-[#0D1520] border border-slate-800 rounded-lg px-3.5 py-2 text-xs text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1.5 font-sans">
                        <i class="fa-solid fa-location-dot text-cyan-400 text-[10px]"></i> Address
                    </label>
                    <textarea name="address" rows="2" placeholder="Enter complete business or home address..."
                              class="w-full bg-[#0D1520] border border-slate-800 rounded-lg px-3.5 py-2 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition resize-none font-medium">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition shadow-md flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i> Save Profile Details
                </button>
            </div>
        </form>
    </div>

    {{-- Security & Password Card --}}
    <div class="bg-[#111A24] border border-slate-800/80 rounded-xl shadow-md p-4 sm:p-5" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        <div class="flex items-center justify-between pb-3 mb-3.5 border-b border-slate-800/80">
            <div>
                <h3 class="text-sm font-bold text-white font-display tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-cyan-400 text-xs"></i> Security &amp; Password
                </h3>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                {{-- Current Password --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1">
                        Current Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-lg pl-3.5 pr-9 py-2 text-xs text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password')<p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1">
                        New Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" name="password" required placeholder="Min. 8 characters"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-lg pl-3.5 pr-9 py-2 text-xs text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showNew = !showNew" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')<p class="text-red-400 text-[10px] mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Confirm New Password --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1 flex items-center gap-1">
                        Confirm New Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required placeholder="Repeat new password"
                               class="w-full bg-[#0D1520] border border-slate-800 rounded-lg pl-3.5 pr-9 py-2 text-xs text-white font-mono placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-cyan-400 transition text-xs">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition shadow-md flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-key text-xs"></i> Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
