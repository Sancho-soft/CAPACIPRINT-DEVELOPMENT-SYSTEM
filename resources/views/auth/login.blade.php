@extends('layouts.app')
@section('title', ($portal ?? 'customer') === 'staff' ? 'Staff Portal Sign In — CAPACIPRINT' : 'Customer Sign In — CAPACIPRINT')
@section('meta_description', ($portal ?? 'customer') === 'staff' ? 'Internal operations ERP sign in.' : 'Sign in to your CAPACIPRINT customer account.')

@section('body')
    <style>
        .auth-hero-panel,
        .auth-hero-panel * {
            box-sizing: border-box;
        }
        html, body {
            letter-spacing: 0.005em;
        }
        .auth-heading {
            line-height: 1.13 !important;
            letter-spacing: -0.015em !important;
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
            background-color: rgba(15, 23, 42, 0.78) !important;
            border-color: rgba(255, 255, 255, 0.22) !important;
        }

        /* High-contrast Auth Inputs (minimum 14px) */
        .auth-input {
            width: 100%;
            font-size: 14px;
            line-height: 1.45;
            letter-spacing: 0.005em;
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            color: #0F172A;
            border-radius: 0.625rem;
            transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
        }
        .auth-input::placeholder {
            color: #64748B;
        }
        .auth-input:focus {
            background-color: #FFFFFF;
            border-color: #2F7BFF;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.16);
        }
        html.dark .auth-input,
        html.dark-theme .auth-input {
            background-color: #182235;
            border-color: #2B3A55;
            color: #F8FAFC;
        }
        html.dark .auth-input::placeholder,
        html.dark-theme .auth-input::placeholder {
            color: #8B9BB4;
        }
        html.dark .auth-input:focus,
        html.dark-theme .auth-input:focus {
            background-color: #1D2940;
            border-color: #2F7BFF;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.22);
        }

        /* Custom Brand-Blue Theme Checkbox */
        .auth-checkbox {
            -webkit-appearance: none;
            appearance: none;
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            border-radius: 5px;
            border: 1.5px solid #CBD5E1;
            background-color: #F8FAFC;
            display: inline-grid;
            place-content: center;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }
        html.dark .auth-checkbox,
        html.dark-theme .auth-checkbox {
            border-color: #2B3A55;
            background-color: #182235;
        }
        .auth-checkbox:hover {
            border-color: #2F7BFF;
        }
        .auth-checkbox:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.25);
        }
        .auth-checkbox:checked {
            background-color: #2F7BFF !important;
            border-color: #2F7BFF !important;
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
            background-size: 13px 13px;
            background-position: center;
            background-repeat: no-repeat;
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
            -webkit-box-shadow: 0 0 0 1000px #182235 inset !important;
            -webkit-text-fill-color: #F8FAFC !important;
        }
    </style>

@if(($portal ?? 'customer') === 'staff')
    {{-- ========================================================================= --}}
    {{-- STAFF & OPERATIONS PORTAL LOGIN --}}
    {{-- ========================================================================= --}}
    <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-[#0A0F1C] py-12 px-4 relative transition-colors duration-200"
         x-data="{
            isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark', 'dark-theme');
                    document.documentElement.classList.remove('light-theme');
                    localStorage.theme = 'dark';
                    document.getElementById('meta-theme-color')?.setAttribute('content', '#0A0F1C');
                } else {
                    document.documentElement.classList.add('light-theme');
                    document.documentElement.classList.remove('dark', 'dark-theme');
                    localStorage.theme = 'light';
                    document.getElementById('meta-theme-color')?.setAttribute('content', '#F8FAFC');
                }
            }
         }">

        {{-- Top Right Icon Theme Toggle --}}
        <div class="fixed top-5 right-5 z-50">
            <button @click="toggleTheme()" type="button"
                    aria-label="Toggle theme"
                    class="h-10 w-10 rounded-xl bg-white dark:bg-[#111827] border border-slate-200 dark:border-[#2B3A55] text-slate-700 dark:text-slate-200 shadow-sm hover:border-[#2F7BFF] flex items-center justify-center transition-colors cursor-pointer"
                    title="Toggle Light / Dark Mode">
                <i class="fa-solid text-sm" :class="isDark ? 'fa-moon text-[#2F7BFF]' : 'fa-sun text-[#FFC531]'"></i>
            </button>
        </div>

        <div class="w-full max-w-md animate-fade-in-up">
            <div class="bg-white dark:bg-[#111827] rounded-2xl border border-slate-200/90 dark:border-[#1E293B] shadow-xl shadow-slate-900/5 dark:shadow-black/40 p-8 sm:p-10">

                {{-- Brand & Left-Aligned Heading --}}
                <div class="mb-7 text-left">
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2.5" title="CAPACIPRINT">
                            <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-10 w-auto object-contain">
                            <span class="font-display font-extrabold text-lg text-slate-900 dark:text-white tracking-tight">
                                CAPACI<span class="text-[#2F7BFF]">PRINT</span>
                            </span>
                        </a>
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Staff Portal</span>
                    </div>
                    <h1 class="auth-heading text-2xl sm:text-[1.65rem] font-extrabold text-slate-900 dark:text-white font-display">Welcome back</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1.5">Sign in to manage branch queues, proofs, and dispatch.</p>
                </div>

                {{-- Validation errors --}}
                @if(isset($errors) && $errors->any())
                    <div class="mb-5 p-3.5 bg-amber-50 dark:bg-[#FFC531]/10 border border-amber-200 dark:border-[#FFC531]/35 rounded-xl text-xs sm:text-sm text-amber-800 dark:text-[#FFC531] flex items-start gap-3 leading-relaxed font-medium">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="portal_type" value="staff">

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-[#8B9BB4]">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   placeholder="staff@capaciprint.com"
                                   autocomplete="off" data-lpignore="true"
                                   class="auth-input pl-10 pr-4 py-2.5">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-5" x-data="{ show: false }">
                        <label for="password" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-[#8B9BB4]">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required
                                   placeholder="Enter your password"
                                   autocomplete="new-password" data-lpignore="true"
                                   class="auth-input pl-10 pr-11 py-2.5">
                            <button type="button" @click="show = !show"
                                    aria-label="Toggle password visibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 dark:text-[#8B9BB4] hover:text-[#2F7BFF] transition-colors cursor-pointer">
                                <i class="fa-solid text-sm" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me & Back Link --}}
                    <div class="flex items-center justify-between mb-6">
                        <label class="inline-flex items-center gap-2.5 text-[13px] font-medium text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="auth-checkbox">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('landing') }}" class="text-[13px] font-semibold text-slate-500 dark:text-slate-300 hover:text-[#2F7BFF] transition flex items-center gap-1.5">
                            <span>Back to website</span>
                            <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        </a>
                    </div>

                    <button type="submit" id="login-btn"
                            class="w-full flex justify-center items-center gap-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    {{-- ========================================================================= --}}
    {{-- SPLIT-CARD CUSTOMER LOGIN (LIGHT & DARK) --}}
    {{-- ========================================================================= --}}

    <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-[#0A0F1C] py-8 sm:py-10 px-4 sm:px-6 relative overflow-hidden transition-colors duration-200"
         x-data="{
            showPass: false,
            isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark', 'dark-theme');
                    document.documentElement.classList.remove('light-theme');
                    localStorage.theme = 'dark';
                    document.getElementById('meta-theme-color')?.setAttribute('content', '#0A0F1C');
                } else {
                    document.documentElement.classList.add('light-theme');
                    document.documentElement.classList.remove('dark', 'dark-theme');
                    localStorage.theme = 'light';
                    document.getElementById('meta-theme-color')?.setAttribute('content', '#F8FAFC');
                }
            }
         }">

        {{-- Top Right Icon Theme Toggle --}}
        <div class="fixed top-5 right-5 z-50">
            <button @click="toggleTheme()" type="button"
                    aria-label="Toggle theme"
                    class="h-10 w-10 rounded-xl bg-white dark:bg-[#111827] border border-slate-200 dark:border-[#2B3A55] text-slate-700 dark:text-slate-200 shadow-sm hover:border-[#2F7BFF] flex items-center justify-center transition-colors cursor-pointer"
                    title="Toggle Light / Dark Mode">
                <i class="fa-solid text-sm" :class="isDark ? 'fa-moon text-[#2F7BFF]' : 'fa-sun text-[#FFC531]'"></i>
            </button>
        </div>

        {{-- Main Split-Card Container (Consistent Size & Position with Sign-Up) --}}
        <div class="w-full max-w-5xl lg:min-h-[630px] bg-white dark:bg-[#111827] border border-slate-200/90 dark:border-[#1E293B] rounded-2xl shadow-xl shadow-slate-900/5 dark:shadow-black/40 overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3.5 sm:p-4 gap-4 relative z-10 animate-fade-in-up">

            {{-- Left Hero Visual Card (No Pill Badge, No Unused Carousel Dots) --}}
            <div class="auth-hero-panel lg:col-span-5 relative rounded-xl overflow-hidden min-h-[280px] lg:min-h-full flex flex-col justify-between p-6 sm:p-8 bg-cover bg-center"
                 style="background-image: url('{{ asset('images/press-floor-dark.jpg') }}');">

                <div class="absolute inset-0 bg-gradient-to-b from-[#0A0F1C]/80 via-[#0A0F1C]/55 to-[#0A0F1C]/95 pointer-events-none"></div>

                {{-- Top Bar: Logo & Back Button --}}
                <div class="relative z-10 flex items-center justify-between gap-3">
                    <a href="{{ route('landing') }}" class="inline-block" title="CAPACIPRINT">
                        <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-10 sm:h-11 w-auto object-contain">
                    </a>

                    <a href="{{ route('landing') }}"
                       class="hero-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#0A0F1C]/75 hover:bg-[#0A0F1C]/95 text-white text-xs font-semibold transition-colors border border-white/20 backdrop-blur-sm">
                        <span>Back to website</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                </div>

                {{-- Bottom Shop-Floor Context --}}
                <div class="relative z-10 space-y-4">
                    <div class="space-y-2.5">
                        <h2 class="auth-heading hero-title text-2xl sm:text-[1.85rem] font-extrabold text-white font-display">
                            Live press status across 3 Davao plants.
                        </h2>
                        <p class="hero-desc text-sm text-slate-200 leading-relaxed font-normal max-w-xs">
                            Check your assigned branch, approve pre-press digital proofs, and download your QR claim slip once your batch clears 4-point shop inspection.
                        </p>
                    </div>

                    <div class="pt-3.5 border-t border-white/15 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-300 tabular-nums">
                        <span class="inline-flex items-center gap-1.5 text-[#10B981] font-semibold">
                            <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                            3 Plants Online
                        </span>
                        <span class="text-slate-500" aria-hidden="true">&middot;</span>
                        <span>5,500+ sq.ft / day</span>
                        <span class="text-slate-500" aria-hidden="true">&middot;</span>
                        <span>4:00 PM rush cutoff</span>
                    </div>
                </div>
            </div>

            {{-- Right Form Panel (Left-Aligned Heading & Labels) --}}
            <div class="lg:col-span-7 flex flex-col justify-center px-5 sm:px-10 py-6 sm:py-8">

                <div class="mb-6 text-left">
                    <h1 class="auth-heading text-2xl sm:text-[1.85rem] font-extrabold text-slate-900 dark:text-white font-display">Welcome back</h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1.5">
                        Don&apos;t have an account?
                        <a href="{{ route('register') }}" class="text-[#2F7BFF] hover:underline font-semibold transition">Create an account</a>
                    </p>
                </div>

                @if(isset($errors) && $errors->any())
                    <div class="mb-5 p-3.5 bg-amber-50 dark:bg-[#FFC531]/10 border border-amber-200 dark:border-[#FFC531]/35 rounded-xl text-xs sm:text-sm text-amber-800 dark:text-[#FFC531] flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
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
                        <label for="email" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" value="" required
                               placeholder="you@company.com"
                               autocomplete="off"
                               class="auth-input px-4 py-3">
                    </div>

                    {{-- Password with Eye Toggle --}}
                    <div>
                        <label for="password" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="showPass ? 'text' : 'password'" required
                                   placeholder="Enter your password"
                                   autocomplete="new-password"
                                   class="auth-input pl-4 pr-11 py-3">
                            <button type="button" @click="showPass = !showPass" tabindex="-1"
                                    aria-label="Toggle password visibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 dark:text-[#8B9BB4] hover:text-[#2F7BFF] transition-colors cursor-pointer">
                                <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between pt-0.5 pb-1">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer text-[13px] font-medium text-slate-600 dark:text-slate-300 select-none">
                            <input type="checkbox" name="remember" class="auth-checkbox">
                            <span>Remember me</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="login-btn"
                            class="w-full flex items-center justify-center gap-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center justify-center my-6">
                    <div class="border-t border-slate-200 dark:border-[#1E293B] w-full"></div>
                    <span class="bg-white dark:bg-[#111827] px-3 text-xs font-medium text-slate-500 dark:text-slate-400 absolute">
                        Or continue with
                    </span>
                </div>

                {{-- Single Social Sign-In: Official Google Button --}}
                <div>
                    <a href="{{ route('auth.google') }}"
                       class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl bg-slate-50 dark:bg-[#182235] hover:bg-slate-100 dark:hover:bg-[#1D2940] border border-slate-200 dark:border-[#2B3A55] text-sm font-semibold text-slate-700 dark:text-slate-100 shadow-sm transition-colors cursor-pointer">
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
