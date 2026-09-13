@extends('layouts.app')
@section('title', ($portal ?? 'customer') === 'staff' ? 'Staff Portal Sign In' : 'Customer Portal Sign In')
@section('meta_description', 'Sign in to your CAPACIPRINT account.')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-[#F1F3F7] dark:bg-[#0B1118] py-12 px-4">
    <div class="w-full max-w-md animate-fade-in-up">

        {{-- Card --}}
        <div class="bg-[#F8F9FA] dark:bg-[#111A24] rounded-3xl shadow-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">

            {{-- Brand Header panel --}}
            <div class="bg-transparent pt-10 pb-2 px-8 text-center">
                <a href="{{ route('landing') }}" class="inline-block transition-transform hover:scale-105">
                    <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-28 w-auto object-contain mx-auto">
                </a>
            </div>

            {{-- Form --}}
            <div class="px-8 pb-8 pt-2">
                <div class="text-center mb-6">
                    @if(($portal ?? 'customer') === 'staff')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider border border-amber-500/20 mb-2">
                            <i class="fa-solid fa-shield-halved"></i> Staff &amp; Operations ERP
                        </span>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-slate-100 font-display">Staff Portal Sign In</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Internal production scheduling &amp; multi-branch operations</p>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0E3386]/10 text-[#0E3386] dark:text-sky-400 text-[10px] font-black uppercase tracking-wider border border-[#0E3386]/20 mb-2">
                            <i class="fa-solid fa-user-check"></i> Customer Portal
                        </span>
                        <h2 class="text-2xl font-black text-[#0E3386] dark:text-slate-100 font-display">Customer Sign In</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Manage your print orders, approve quotations &amp; view proofs</p>
                    @endif
                </div>

                {{-- Validation errors / Role Denial Warnings --}}
                @if($errors->any())
                    <div class="mb-5 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-xl text-xs text-rose-700 dark:text-rose-300 flex items-start gap-3 leading-relaxed font-medium">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-500"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="portal_type" value="{{ $portal ?? 'customer' }}">

                    {{-- Email --}}
                    <div class="mb-5" x-data="{ emailFocused: false }">
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">Email Address</label>
                        <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 dark:border-slate-700 text-[#0E3386] dark:text-sky-400 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   :placeholder="emailFocused ? 'Enter your registered email' : ''"
                                   @focus="emailFocused = true" @blur="emailFocused = false"
                                   autocomplete="off" data-lpignore="true"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none @error('email') bg-red-50 @enderror">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-6" x-data="{ show: false, passFocused: false }">
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-1.5">Password</label>
                        <div class="flex rounded-xl border border-slate-300 dark:border-slate-700 overflow-hidden focus-within:border-[#0E3386] focus-within:ring-2 focus-within:ring-[#0E3386]/20 transition-all shadow-xs bg-white dark:bg-[#0D1520]">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 dark:border-slate-700 text-[#0E3386] dark:text-sky-400 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                   :placeholder="passFocused ? 'Enter your password' : ''"
                                   @focus="passFocused = true" @blur="passFocused = false"
                                   autocomplete="new-password" data-lpignore="true"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 dark:text-slate-200 bg-transparent border-none focus:ring-0 focus:outline-none">
                            <button type="button" @click="show = !show"
                                    class="flex items-center justify-center w-12 text-slate-500 hover:text-[#0E3386] dark:hover:text-sky-400 transition border-l border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 shrink-0">
                                <i class="fa-solid text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember + Submit --}}
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#0E3386] focus:ring-[#0E3386]">
                            Remember me
                        </label>
                        @if(($portal ?? 'customer') === 'customer')
                            <a href="{{ route('register') }}" class="text-xs text-[#0E3386] dark:text-sky-400 hover:underline font-bold">Create Account</a>
                        @endif
                    </div>

                    <button type="submit" id="login-btn"
                            class="w-full flex justify-center items-center gap-2 bg-[#0E3386] hover:bg-[#0a2663] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-[#0E3386]/25 active:scale-[0.98] cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-[#29bce8]"></i>
                        {{ ($portal ?? 'customer') === 'staff' ? 'Sign In to Operations' : 'Sign In as Customer' }}
                    </button>
                </form>

                {{-- Fast 1-Click Demo Fill --}}
                <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500" x-data>
                    <span>1-Click Test:</span>
                    @if(($portal ?? 'customer') === 'staff')
                        <button type="button" @click="document.getElementById('email').value='manager@capaciprint.com'; document.getElementById('password').value='password';" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline cursor-pointer">Fill Staff Demo</button>
                    @else
                        <button type="button" @click="document.getElementById('email').value='customer@capaciprint.com'; document.getElementById('password').value='password';" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline cursor-pointer">Fill Customer Demo</button>
                    @endif
                </div>

                {{-- Portal Links --}}
                <div class="mt-5 p-3 rounded-xl bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/60 text-[11px] text-slate-500 dark:text-slate-400 text-center">
                    @if(($portal ?? 'customer') === 'staff')
                        <span>Are you a client? <a href="{{ route('customer.portal') }}" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline">Go to Customer Portal &rarr;</a></span>
                    @else
                        <a href="{{ route('landing') }}" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline flex items-center justify-center gap-1.5"><i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Main Landing Page</a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
