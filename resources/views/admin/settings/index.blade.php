@extends('layouts.internal')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto font-sans">

    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-[1.75rem] font-bold font-display text-cyber-main tracking-tight">Account Settings</h1>
        <p class="text-sm text-cyber-muted mt-1">Manage your operator profile, security credentials, and system database backups.</p>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-[#10B981]/30 text-emerald-700 dark:text-[#10B981] flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-circle-check text-[#10B981] text-sm"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3.5 rounded-xl bg-amber-500/10 border border-[#FFC531]/30 text-amber-800 dark:text-[#FFC531] flex items-center gap-3 text-xs font-medium">
            <i class="fa-solid fa-triangle-exclamation text-[#FFC531] text-sm"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-3.5 rounded-xl bg-amber-500/10 border border-[#FFC531]/30 text-amber-800 dark:text-[#FFC531] text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark"></i> Please correct the following errors:
            </div>
            <ul class="list-disc list-inside pl-2 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN (Spans 2 cols) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. Personal Information Card -->
            <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 space-y-5">
                <div class="border-b border-cyber pb-3.5">
                    <h2 class="font-bold text-cyber-main font-display text-base">Personal Information</h2>
                    <p class="text-xs text-cyber-muted mt-0.5">Name, direct contact number, and login email address.</p>
                </div>

                <form action="{{ route('admin.settings.update-profile') }}" method="POST" class="space-y-4 text-sm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required 
                                   class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                   placeholder="Enter first name">
                        </div>

                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                                   class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                   placeholder="Enter middle name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required 
                                   class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                   placeholder="Enter last name">
                        </div>

                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Contact Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?: '+63 969 195 2485') }}" 
                                   class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 font-mono tabular-nums text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                   placeholder="+63 969 195 2485">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                               placeholder="admin@capaciprint.com">
                    </div>

                    <div class="flex justify-end pt-2 border-t border-cyber-sub">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition cursor-pointer">
                            <span>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Change Password Card -->
            <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 space-y-5">
                <div class="border-b border-cyber pb-3.5">
                    <h2 class="font-bold text-cyber-main font-display text-base">Change Password</h2>
                    <p class="text-xs text-cyber-muted mt-0.5">Update your sign-in password for security compliance.</p>
                </div>

                <form action="{{ route('admin.settings.update-password') }}" method="POST" class="space-y-4 text-sm">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Current Password</label>
                        <div class="relative" x-data="{ show: false, pass: 'password' }">
                            <input :type="show ? 'text' : 'password'" name="current_password" x-model="pass" autocomplete="new-password" required 
                                   class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 pr-10 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                   placeholder="Enter current password">
                            <button type="button" @click="show = !show" aria-label="Toggle current password visibility" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-cyber-muted hover:text-cyber-main cursor-pointer">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="new_password" required 
                                       class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 pr-10 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                       placeholder="Enter new password">
                                <button type="button" @click="show = !show" aria-label="Toggle new password visibility" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-cyber-muted hover:text-cyber-main cursor-pointer">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <span class="text-xs font-sans text-cyber-muted mt-1.5 block">Minimum 8 characters</span>
                        </div>

                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Confirm New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="new_password_confirmation" required 
                                       class="w-full bg-cyber-sub border border-cyber rounded-lg px-3.5 py-2.5 pr-10 text-cyber-main focus:border-[#2F7BFF] focus:outline-none placeholder-cyber-muted text-sm" 
                                       placeholder="Confirm new password">
                                <button type="button" @click="show = !show" aria-label="Toggle password confirmation visibility" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-cyber-muted hover:text-cyber-main cursor-pointer">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-cyber-sub">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold text-xs transition cursor-pointer">
                            <span>Change Password</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- RIGHT COLUMN (Spans 1 col) -->
        <div class="space-y-6">

            <!-- 1. System Information Card -->
            <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 space-y-4">
                <div class="border-b border-cyber pb-3">
                    <h3 class="font-bold text-cyber-main font-display text-base">System Information</h3>
                </div>

                <div class="divide-y divide-cyber-sub text-xs tabular-nums">
                    <div class="flex justify-between items-center py-2.5 pt-0">
                        <span class="text-cyber-muted">Username</span>
                        <span class="font-mono font-medium text-cyber-main">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5">
                        <span class="text-cyber-muted">Role</span>
                        <span class="font-semibold text-[#2F7BFF] dark:text-[#60A5FA]">{{ $user->role_label }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 pb-0">
                        <span class="text-cyber-muted">Account Created</span>
                        <span class="font-mono tabular-nums font-medium text-cyber-main">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="p-3 rounded-lg bg-cyber-sub border border-cyber text-cyber-muted text-xs flex items-center gap-2.5">
                    <i class="fa-solid fa-lock text-[#FFC531] shrink-0"></i>
                    <span>Username and role changes require administrator access.</span>
                </div>
            </div>

            <!-- 2. Database Backup & Restore Card -->
            @if($user->isAdmin())
            <div class="bg-cyber-card border border-cyber rounded-xl p-5 sm:p-6 space-y-4">
                <div class="border-b border-cyber pb-3">
                    <h3 class="font-bold text-cyber-main font-display text-base">Database Backup &amp; Restore</h3>
                </div>

                <!-- Backup Form -->
                <form action="{{ route('admin.settings.backup-db') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-cyber-sub hover:border-[#2F7BFF] border border-cyber text-cyber-main font-semibold text-xs flex items-center justify-center gap-2 transition cursor-pointer">
                        <i class="fa-solid fa-download text-xs text-[#2F7BFF]"></i>
                        <span>Download SQL Backup</span>
                    </button>
                </form>

                <div class="border-t border-cyber pt-4">
                    <!-- Restore Form -->
                    <form action="{{ route('admin.settings.restore-db') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <label class="block text-[13px] font-sans font-semibold text-cyber-main mb-1.5">Restore from backup (.sql)</label>
                            <input type="file" name="backup_file" accept=".sql" required 
                                   class="w-full text-xs text-cyber-muted file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-cyber file:text-xs file:font-semibold file:bg-cyber-sub file:text-cyber-main cursor-pointer">
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg border border-amber-500/40 bg-amber-500/10 hover:bg-amber-500/20 text-amber-800 dark:text-[#FFC531] font-semibold text-xs flex items-center justify-center gap-2 transition cursor-pointer"
                                onclick="return confirm('Are you sure you want to restore the database? Current data may be overwritten.');">
                            <i class="fa-solid fa-upload text-xs"></i>
                            <span>Restore Database</span>
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
