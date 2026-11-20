@extends('layouts.app')
@section('title', ($portal ?? 'customer') === 'staff' ? 'Staff Portal Sign In — CAPACIPRINT' : 'Customer Sign In — CAPACIPRINT')
@section('meta_description', ($portal ?? 'customer') === 'staff' ? 'Internal operations ERP sign in.' : 'Sign in to your CAPACIPRINT customer account.')

@section('body')
@if(($portal ?? 'customer') === 'staff')
    {{-- ========================================================================= --}}
    {{-- STAFF & OPERATIONS PORTAL LOGIN --}}
    {{-- ========================================================================= --}}
    <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-[#0B0F17] py-12 px-4 transition-colors duration-200">
        <div class="w-full max-w-md animate-fade-in-up">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm p-8 sm:p-10">

                {{-- Brand & Header --}}
                <div class="flex flex-col items-center text-center mb-6">
                    <a href="{{ route('landing') }}" class="flex items-center justify-center gap-3 mb-5">
                        <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-11 w-auto object-contain">
                        <span class="font-display font-extrabold text-xl text-slate-900 dark:text-white tracking-tight">
                            CAPACI<span class="text-blue-600 dark:text-blue-400">PRINT</span>
                        </span>
                    </a>
                    <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight">Welcome back</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sign in to your staff operations account</p>
                </div>

                    {{-- Validation errors --}}
                    @if(isset($errors) && $errors->any())
                        <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl text-xs sm:text-sm text-rose-700 dark:text-rose-300 flex items-start gap-3 leading-relaxed font-medium">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-600 dark:text-rose-400"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="portal_type" value="staff">

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </span>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                       placeholder="Enter your email address"
                                       autocomplete="off" data-lpignore="true"
                                       class="w-full pl-10 pr-4 py-3 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 placeholder-slate-400 transition-colors">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-5" x-data="{ show: false }">
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>
                                <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                       placeholder="Enter your password"
                                       autocomplete="new-password" data-lpignore="true"
                                       class="w-full pl-10 pr-11 py-3 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 placeholder-slate-400 transition-colors">
                                <button type="button" @click="show = !show"
                                        aria-label="Toggle password visibility"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                                    <i class="fa-solid text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember me --}}
                        <div class="flex items-center mb-6">
                            <label class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" id="login-btn"
                                class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                            Sign In
                        </button>
                    </form>
            </div>
        </div>
    </div>
@else
    {{-- ========================================================================= --}}
    {{-- SPLIT-CARD CUSTOMER LOGIN (LIGHT & DARK) --}}
    {{-- ========================================================================= --}}
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
            showPass: false,
            isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
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

        {{-- Main Split-Card Container --}}
        <div class="w-full max-w-5xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3 sm:p-4 gap-4 relative z-10 animate-fade-in-up">

            {{-- Left Hero Visual Card --}}
            <div class="auth-hero-panel lg:col-span-5 relative rounded-xl overflow-hidden min-h-[320px] lg:min-h-[580px] flex flex-col justify-between p-6 sm:p-8 bg-cover bg-center"
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
                            Customer Portal
                        </span>
                        <h2 class="hero-title text-2xl sm:text-3xl font-extrabold text-white font-display leading-tight tracking-tight">
                            Your print orders,<br>
                            <span class="text-blue-400">always on schedule.</span>
                        </h2>
                        <p class="hero-desc text-sm text-slate-200 leading-relaxed font-normal max-w-xs">
                            Request quotes, approve proofs online, and track live print progress across all our branches.
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
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight">Welcome back</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">
                        Don&apos;t have an account?
                        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold transition">Create an account</a>
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

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4" autocomplete="off"
                      x-data
                      x-init="setTimeout(() => { if (!{{ json_encode(old('email')) }}) { let el = document.getElementById('email'); if(el) el.value = ''; } let p = document.getElementById('password'); if(p) p.value = ''; }, 50)">
                    @csrf
                    <input type="hidden" name="portal_type" value="customer">

                    <input type="text" name="fake_username" style="display:none" aria-hidden="true" autocomplete="username">
                    <input type="password" name="fake_password" style="display:none" aria-hidden="true" autocomplete="new-password">

                    {{-- Email Address --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" value="" required
                               placeholder="you@company.com"
                               autocomplete="off"
                               class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                    </div>

                    {{-- Password with Eye Toggle --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="showPass ? 'text' : 'password'" required
                                   placeholder="Enter your password"
                                   autocomplete="new-password"
                                   class="w-full bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white placeholder-slate-400 border border-slate-200 dark:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-4 pr-11 py-3 text-sm transition-colors outline-none">
                            <button type="button" @click="showPass = !showPass" tabindex="-1"
                                    aria-label="Toggle password visibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer">
                                <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs sm:text-sm text-slate-600 dark:text-slate-300 select-none">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer">
                            <span>Remember me</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="login-btn"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center justify-center my-6">
                    <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                    <span class="bg-white dark:bg-slate-900 px-3 text-xs font-medium text-slate-400 dark:text-slate-500 absolute">
                        Or continue with
                    </span>
                </div>

                {{-- Single Social Sign-In: Official Google Button --}}
                <div>
                    <a href="{{ route('auth.google') }}"
                       class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl bg-white dark:bg-slate-800/70 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm transition-colors cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endif
@endsection
