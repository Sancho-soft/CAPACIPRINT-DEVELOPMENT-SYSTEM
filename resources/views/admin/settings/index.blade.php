@extends('layouts.internal')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="space-y-6 max-w-7xl font-sans">

    <!-- Header Banner -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                <i class="fa-solid fa-user-gear text-cyan-500"></i> Account Settings
            </h1>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 flex items-center gap-3 text-xs font-medium shadow-md">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 flex items-center gap-3 text-xs font-medium shadow-md">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-base"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 text-xs space-y-1 shadow-md">
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
            <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-4">
                    <div class="h-8 w-8 rounded-xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-white text-sm">Personal Information</h2>
                    </div>
                </div>

                <form action="{{ route('admin.settings.update-profile') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required 
                                   class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                                   placeholder="Enter first name">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                                   class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                                   placeholder="Enter middle name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required 
                                   class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                                   placeholder="Enter last name">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Contact Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?: '+63 969 195 2485') }}" 
                                   class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                                   placeholder="+63 969 195 2485">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:outline-none placeholder-slate-400" 
                               placeholder="admin@capaciprint.com">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition cursor-pointer">
                            <i class="fa-solid fa-circle-check text-xs"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. Change Password Card -->
            <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-4">
                    <div class="h-8 w-8 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-white text-sm">Change Password</h2>
                    </div>
                </div>

                <form action="{{ route('admin.settings.update-password') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                        <div class="relative" x-data="{ show: false, pass: 'password' }">
                            <input :type="show ? 'text' : 'password'" name="current_password" x-model="pass" autocomplete="new-password" required 
                                   class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 pr-10 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none placeholder-slate-400" 
                                   placeholder="Enter current password">
                            <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="new_password" required 
                                       class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 pr-10 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none placeholder-slate-400" 
                                       placeholder="Enter new password">
                                <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <span class="text-[11px] text-slate-400 mt-1 block">Minimum 8 characters</span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="new_password_confirmation" required 
                                       class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2.5 pr-10 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none placeholder-slate-400" 
                                       placeholder="Confirm new password">
                                <button type="button" @click="show = !show" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition cursor-pointer">
                            <i class="fa-solid fa-key text-xs"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- RIGHT COLUMN (Spans 1 col) -->
        <div class="space-y-6">

            <!-- 1. System Information Card -->
            <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-xs">System Information</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-slate-500 dark:text-slate-400 font-normal">Username:</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-t border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-500 dark:text-slate-400 font-normal">Role:</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $user->role_label }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-t border-slate-100 dark:border-slate-800/60">
                        <span class="text-slate-500 dark:text-slate-400 font-normal">Account Created:</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-[11px] flex items-center gap-2.5">
                    <i class="fa-solid fa-scissors shrink-0"></i>
                    <span>Username &amp; role only editable by administrators.</span>
                </div>
            </div>

            <!-- 2. Database Backup & Restore Card -->
            @if($user->isAdmin())
            <div class="bg-white dark:bg-[#111A24] border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800/80 pb-3">
                    <div class="h-8 w-8 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-xs">Database Backup &amp; Restore</h3>
                </div>

                <!-- Backup Form -->
                <form action="{{ route('admin.settings.backup-db') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md transition cursor-pointer">
                        <i class="fa-solid fa-download text-xs"></i> Backup Database
                    </button>
                </form>

                <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>

                <!-- Restore Form -->
                <form action="{{ route('admin.settings.restore-db') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1.5">Restore from backup (.sql)</label>
                        <input type="file" name="backup_file" accept=".sql" required 
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-200 cursor-pointer">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md transition cursor-pointer"
                            onclick="return confirm('Are you sure you want to restore the database? Current data may be overwritten.');">
                        <i class="fa-solid fa-upload text-xs"></i> Restore Database
                    </button>
                </form>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection
