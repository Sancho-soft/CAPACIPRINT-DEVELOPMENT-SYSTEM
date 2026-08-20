@extends('layouts.customer')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h2 class="text-2xl font-bold text-navy-900 font-display">My Profile</h2>
        <p class="text-sm text-slate-500 mt-1">Manage your personal information and account settings.</p>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 pb-5 mb-5 border-b border-slate-100">
            <div class="h-16 w-16 rounded-full bg-brand-500 flex items-center justify-center text-white text-2xl font-bold font-display shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-navy-900">{{ $user->name }}</h3>
                <p class="text-xs text-slate-500">Customer Account &middot; Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Email Address</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="block w-full rounded-lg border border-slate-100 px-3.5 py-2.5 text-sm text-slate-500 bg-slate-50 cursor-not-allowed">
                    <p class="text-[11px] text-slate-400 mt-1">Email cannot be changed. Contact support if needed.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+63 912 345 6789"
                           class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Address</label>
                    <textarea name="address" rows="2" placeholder="Your delivery/billing address"
                              class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition resize-none">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-6 py-2.5 rounded-lg text-sm transition shadow">
                    <i class="fa-solid fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white border border-slate-100 rounded-xl shadow-sm p-6 space-y-4">
        <h3 class="font-bold text-navy-900">Change Password</h3>

        <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Current Password <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required placeholder="••••••••"
                       class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                @error('current_password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">New Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required placeholder="Min. 8 characters"
                       class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
                @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required placeholder="Repeat new password"
                       class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 focus:border-brand-400 focus:outline-none focus:ring-1 focus:ring-brand-400 transition">
            </div>

            <button type="submit"
                    class="border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fa-solid fa-key"></i> Update Password
            </button>
        </form>
    </div>

    {{-- Restrictions Note --}}
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800 flex items-start gap-3">
        <i class="fa-solid fa-shield-halved mt-0.5 shrink-0"></i>
        <p>For security, customers cannot modify pricing rules, branch data, production assignments, or other internal system records.</p>
    </div>

</div>
@endsection
