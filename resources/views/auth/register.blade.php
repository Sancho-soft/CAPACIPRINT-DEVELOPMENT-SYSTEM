@extends('layouts.app')
@section('title', 'Create an Account — CAPACIPRINT')
@section('meta_description', 'Create your CAPACIPRINT customer account to submit print requests, approve proofs, and track orders.')

@section('body')
<style>
    .auth-hero-panel,
    .auth-hero-panel * {
        box-sizing: border-box;
    }
    html.light-theme .auth-hero-panel .hero-title,
    html.light-theme .auth-hero-panel h2 {
        color: #FFFFFF !important;
    }
    html.light-theme .auth-hero-panel .hero-desc,
    html.light-theme .auth-hero-panel p {
        color: #E2E8F0 !important;
    }
    html.light-theme .auth-hero-panel .hero-pill {
        color: #FFFFFF !important;
        background-color: rgba(15, 23, 42, 0.72) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #F8FAFC inset !important;
        -webkit-text-fill-color: #0F172A !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    html.dark input:-webkit-autofill,
    html.dark-theme input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #1E293B inset !important;
        -webkit-text-fill-color: #F8FAFC !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-[#0B0F17] py-8 sm:py-12 px-4 sm:px-6 relative overflow-hidden transition-colors duration-200"
     x-data="{
        firstName: '{{ old('first_name', '') }}',
        lastName: '{{ old('last_name', '') }}',
        fullName: '{{ old('name', '') }}',
        showPass: false,
        showConfirm: false,
        isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
        updateFullName() {
            this.fullName = (this.firstName.trim() + ' ' + this.lastName.trim()).trim();
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark', 'dark-theme');
                document.documentElement.classList.remove('light-theme');
                localStorage.theme = 'dark';
                document.getElementById('meta-theme-color')?.setAttribute('content', '#0B0F17');
            } else {
                document.documentElement.classList.add('light-theme');
                document.documentElement.classList.remove('dark', 'dark-theme');
                localStorage.theme = 'light';
                document.getElementById('meta-theme-color')?.setAttribute('content', '#F8FAFC');
            }
        },
        init() {
            if (this.fullName && (!this.firstName && !this.lastName)) {
                let parts = this.fullName.split(' ');
                this.firstName = parts[0] || '';
                this.lastName = parts.slice(1).join(' ') || '';
            }
        }
     }">

    {{-- Top Right Theme Toggle --}}
    <div class="fixed top-5 right-5 z-50">
        <button @click="toggleTheme()" type="button"
                class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm hover:border-blue-500/40 transition-colors cursor-pointer"
                title="Toggle Light / Dark Mode">
            <i class="fa-solid text-sm" :class="isDark ? 'fa-moon text-blue-400' : 'fa-sun text-amber-500'"></i>
            <span x-text="isDark ? 'Dark Mode' : 'Light Mode'" class="hidden sm:inline"></span>
        </button>
    </div>

    {{-- Main Container Card --}}
    <div class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3 sm:p-4 gap-4 relative z-10 animate-fade-in-up">

        {{-- Left Hero Visual Card --}}
        <div class="auth-hero-panel lg:col-span-5 relative rounded-xl overflow-hidden min-h-[320px] lg:min-h-[600px] flex flex-col justify-between p-6 sm:p-8 bg-cover bg-center"
             style="background-image: url('{{ asset('images/press-floor-dark.jpg') }}');">

            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/75 via-slate-900/55 to-slate-950/90 pointer-events-none"></div>

            {{-- Top Bar: Logo & Back Button --}}
            <div class="relative z-10 flex items-center justify-between gap-3">
                <a href="{{ route('landing') }}" class="inline-block" title="CAPACIPRINT">
                    <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-10 sm:h-11 w-auto object-contain">
                </a>

                <a href="{{ route('landing') }}"
                   class="hero-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900/75 hover:bg-slate-900/95 text-white text-xs font-semibold transition-colors border border-white/20 backdrop-blur-sm">
                    <span>Back to website</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>

            {{-- Bottom Tagline --}}
            <div class="relative z-10 space-y-4">
                <div class="space-y-2.5">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-semibold">
                        Create Your Account
                    </span>
                    <h2 class="hero-title text-2xl sm:text-3xl font-extrabold text-white font-display leading-tight tracking-tight">
                        Print smarter across<br>
                        <span class="text-blue-400">all our branches.</span>
                    </h2>
                    <p class="hero-desc text-sm text-slate-200 leading-relaxed font-normal max-w-xs">
                        Upload artwork online, review clear quotes, and follow your order straight to pickup.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <span class="w-8 h-1 rounded-full bg-blue-500"></span>
                    <span class="w-2 h-1 rounded-full bg-white/35"></span>
                    <span class="w-2 h-1 rounded-full bg-white/35"></span>
                </div>
            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="lg:col-span-7 flex flex-col justify-center px-4 sm:px-10 py-6 sm:py-8">

            <div class="mb-7 text-center">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight">Create an account</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold transition">Log in</a>
                </p>
            </div>

            @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl text-xs sm:text-sm text-rose-700 dark:text-rose-300 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                    <div class="space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-4" autocomplete="off" @submit="updateFullName()">
                @csrf

                <input type="hidden" name="name" :value="fullName">

                {{-- First Name & Last Name --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="first_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">First name</label>
                        <input id="first_name" name="first_name" type="text" x-model="firstName" @input="updateFullName()"
                               placeholder="Juan" required
                               class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Last name</label>
                        <input id="last_name" name="last_name" type="text" x-model="lastName" @input="updateFullName()"
                               placeholder="Dela Cruz" required
                               class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                    </div>
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           placeholder="you@company.com"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                </div>

                {{-- Contact Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Phone number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                           placeholder="+63 912 345 6789"
                           class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="showPass ? 'text' : 'password'" required
                               placeholder="At least 8 characters"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-4 pr-11 py-3 text-sm transition-colors outline-none">
                        <button type="button" @click="showPass = !showPass" tabindex="-1"
                                aria-label="Toggle password visibility"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                            <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Confirm password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" required
                               placeholder="Re-enter your password"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-4 pr-11 py-3 text-sm transition-colors outline-none">
                        <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                                aria-label="Toggle confirm password visibility"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                            <i class="fa-solid text-sm" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Primary Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Create account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
