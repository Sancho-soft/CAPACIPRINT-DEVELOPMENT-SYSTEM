@extends('layouts.app')
@section('title', 'Create Customer Account — CAPACIPRINT')
@section('meta_description', 'Register your CAPACIPRINT customer account to submit print requests, approve proofs, and track orders.')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-[#F1F3F7] dark:bg-[#0B1118] py-10 px-4 sm:px-6">
    <div class="w-full max-w-lg animate-fade-in-up">

        {{-- Main Registration Card --}}
        <div class="bg-[#F8F9FA] dark:bg-[#111A24] rounded-3xl shadow-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">

            {{-- Brand Header panel --}}
            <div class="pt-9 pb-2 px-8 text-center">
                <a href="{{ route('landing') }}" class="inline-block transition-transform hover:scale-105" title="Back to Home">
                    <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-24 w-auto object-contain mx-auto">
                </a>
            </div>

            {{-- Form Header & Body --}}
            <div class="px-8 pb-9 pt-2">
                <div class="text-center mb-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0E3386]/10 text-[#0E3386] dark:text-sky-400 text-[10px] font-black uppercase tracking-wider border border-[#0E3386]/20 mb-2.5">
                        <i class="fa-solid fa-user-plus"></i> Customer Registration
                    </span>
                    <h2 class="text-2xl font-black text-[#0E3386] dark:text-slate-100 font-display">Create Your Account</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium leading-relaxed max-w-sm mx-auto">
                        Submit print specifications, track your order status in real time, and approve digital proofs.
                    </p>
                </div>

                {{-- Validation Errors Alert --}}
                @if($errors->any())
                    <div class="mb-5 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-xs text-rose-700 dark:text-rose-300 flex items-start gap-3 leading-relaxed font-medium">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-500 text-sm"></i>
                        <div class="space-y-1">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" class="space-y-4" autocomplete="off">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 dark:border-slate-700 text-[#0E3386] dark:text-sky-400 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   placeholder="Juan Dela Cruz"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Email Address --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 dark:border-slate-700 text-[#0E3386] dark:text-sky-400 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   placeholder="juan.delacruz@example.com"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Phone Number (Optional) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                Contact Number
                            </label>
                            <span class="text-[11px] text-slate-400 font-medium">Optional</span>
                        </div>
                        <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 dark:border-slate-700 text-[#0E3386] dark:text-sky-400 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </span>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                   placeholder="+63 912 345 6789"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Passwords Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                                <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                       placeholder="Min. 8 chars"
                                       class="flex-1 py-3 px-3.5 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                                <button type="button" @click="show = !show"
                                        class="flex items-center justify-center w-10 text-slate-400 hover:text-[#0E3386] dark:hover:text-sky-400 transition bg-slate-50 dark:bg-slate-800/60 shrink-0 border-l border-slate-200 dark:border-slate-700">
                                    <i class="fa-solid text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div x-data="{ show2: false }">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">
                                Confirm <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                                <input id="password_confirmation" name="password_confirmation" :type="show2 ? 'text' : 'password'" required
                                       placeholder="Re-enter password"
                                       class="flex-1 py-3 px-3.5 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                                <button type="button" @click="show2 = !show2"
                                        class="flex items-center justify-center w-10 text-slate-400 hover:text-[#0E3386] dark:hover:text-sky-400 transition bg-slate-50 dark:bg-slate-800/60 shrink-0 border-l border-slate-200 dark:border-slate-700">
                                    <i class="fa-solid text-xs" :class="show2 ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Terms Note --}}
                    <div class="pt-2 text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        By creating an account, you agree to CAPACIPRINT's Terms of Service and Production &amp; Routing Guidelines.
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2.5 bg-[#0E3386] hover:bg-[#0a2663] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-[#0E3386]/25 active:scale-[0.98] cursor-pointer mt-2">
                        <i class="fa-solid fa-user-plus text-[#29bce8]"></i>
                        <span>Register Customer Account</span>
                    </button>
                </form>

                {{-- Sign In Redirection --}}
                <div class="mt-6 pt-5 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <span>Already have an account?</span>
                    <a href="{{ route('customer.portal') }}" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline">
                        Sign in to Customer Portal &rarr;
                    </a>
                </div>

                {{-- Back to Home --}}
                <div class="mt-3 text-center">
                    <a href="{{ route('landing') }}" class="text-[11px] text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium inline-flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-arrow-left text-[9px]"></i> Back to Main Landing Page
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
