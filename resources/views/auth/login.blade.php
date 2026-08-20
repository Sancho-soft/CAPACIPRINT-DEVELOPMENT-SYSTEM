@extends('layouts.app')
@section('title', 'Sign In')
@section('meta_description', 'Sign in to your CAPACIPRINT customer account.')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4">
    <div class="w-full max-w-md animate-fade-in-up">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100/80 overflow-hidden">

            {{-- Clean White Header panel --}}
            <div class="bg-white pt-10 pb-2 px-8 text-center">
                <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-36 w-auto object-contain mx-auto mix-blend-multiply transition-transform hover:scale-105">
            </div>

            {{-- Form --}}
            <div class="px-8 pb-8 pt-2">
                <h2 class="text-lg font-bold text-navy-900 mb-1 text-center">Welcome back</h2>
                <p class="text-sm text-slate-500 mb-6 text-center">Sign in to your account</p>

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-5" x-data="{ emailFocused: false }">
                        <label for="email" class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Email Address</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0">
                                <i class="fa-solid fa-envelope text-base font-bold"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   :placeholder="emailFocused ? 'Enter your email address' : ''"
                                   @focus="emailFocused = true" @blur="emailFocused = false"
                                   autocomplete="off" data-lpignore="true"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 bg-white border-none focus:ring-0 focus:outline-none @error('email') bg-red-50 @enderror">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-6" x-data="{ show: false, passFocused: false }">
                        <label for="password" class="block text-xs font-semibold text-navy-800 uppercase tracking-wide mb-1.5">Password</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-12 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0">
                                <i class="fa-solid fa-lock text-base font-bold"></i>
                            </span>
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                   :placeholder="passFocused ? 'Enter your password' : ''"
                                   @focus="passFocused = true" @blur="passFocused = false"
                                   autocomplete="new-password" data-lpignore="true"
                                   class="flex-1 py-3 px-4 text-sm text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                            <button type="button" @click="show = !show"
                                    class="flex items-center justify-center w-12 text-navy-800 hover:text-brand-500 transition border-l border-slate-200 bg-slate-50 shrink-0">
                                <i class="fa-solid text-base font-bold" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember + Submit --}}
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-500 focus:ring-brand-400">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" id="login-btn"
                            class="w-full flex justify-center items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-400 hover:from-brand-600 hover:to-brand-500 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-brand-500/25 active:scale-[0.98]">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        Sign In
                    </button>
                </form>

                <p class="text-center text-sm text-slate-500 mt-6">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-brand-500 hover:text-brand-700 font-semibold">Create account</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
