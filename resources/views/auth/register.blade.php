@extends('layouts.app')
@section('title', 'Create Account')
@section('meta_description', 'Register a CAPACIPRINT customer account.')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-white py-6 px-4">
    <div class="w-full max-w-md animate-fade-in-up">

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100/80 overflow-hidden">

            {{-- Compressed Header --}}
            <div class="bg-white pt-5 pb-1 px-6 text-center">
                <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-20 w-auto object-contain mx-auto mix-blend-multiply transition-transform hover:scale-105">
            </div>

            {{-- Form --}}
            <div class="px-6 pb-6 pt-1">
                <h2 class="text-base font-bold text-navy-900 mb-3 text-center">Create your account</h2>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="block text-[11px] font-semibold text-navy-800 uppercase tracking-wide mb-1">Full Name</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-10 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid fa-user text-sm font-bold"></i>
                            </span>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   placeholder="Kim Maclup"
                                   class="flex-1 py-2.5 px-3.5 text-xs text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="block text-[11px] font-semibold text-navy-800 uppercase tracking-wide mb-1">Email Address</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-10 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid fa-envelope text-sm font-bold"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   placeholder="you@example.com"
                                   class="flex-1 py-2.5 px-3.5 text-xs text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Phone (optional) --}}
                    <div class="mb-3">
                        <label for="phone" class="block text-[11px] font-semibold text-navy-800 uppercase tracking-wide mb-1">Phone Number <span class="text-slate-400 font-normal">(optional)</span></label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-10 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid fa-phone text-sm font-bold"></i>
                            </span>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                   placeholder="+63 912 345 6789"
                                   class="flex-1 py-2.5 px-3.5 text-xs text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3" x-data="{ show: false }">
                        <label for="password" class="block text-[11px] font-semibold text-navy-800 uppercase tracking-wide mb-1">Password</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-10 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid fa-lock text-sm font-bold"></i>
                            </span>
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                   placeholder="Min. 8 characters"
                                   class="flex-1 py-2.5 px-3.5 text-xs text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                            <button type="button" @click="show = !show"
                                    class="flex items-center justify-center w-10 text-navy-800 hover:text-brand-500 transition border-l border-slate-200 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid text-sm font-bold" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-4" x-data="{ show2: false }">
                        <label for="password_confirmation" class="block text-[11px] font-semibold text-navy-800 uppercase tracking-wide mb-1">Confirm Password</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden focus-within:border-brand-400 focus-within:ring-3 focus-within:ring-brand-400/15 transition-all shadow-sm">
                            <span class="flex items-center justify-center w-10 border-r border-slate-200 text-navy-800 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid fa-lock text-sm font-bold"></i>
                            </span>
                            <input id="password_confirmation" name="password_confirmation" :type="show2 ? 'text' : 'password'" required
                                   placeholder="Re-enter password"
                                   class="flex-1 py-2.5 px-3.5 text-xs text-slate-800 bg-white border-none focus:ring-0 focus:outline-none">
                            <button type="button" @click="show2 = !show2"
                                    class="flex items-center justify-center w-10 text-navy-800 hover:text-brand-500 transition border-l border-slate-200 bg-slate-50 shrink-0 text-xs">
                                <i class="fa-solid text-sm font-bold" :class="show2 ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-400 hover:from-brand-600 hover:to-brand-500 text-white font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-md shadow-brand-500/25 active:scale-[0.98]">
                        <i class="fa-solid fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <p class="text-center text-xs text-slate-500 mt-4">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-700 font-semibold">Sign in</a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
