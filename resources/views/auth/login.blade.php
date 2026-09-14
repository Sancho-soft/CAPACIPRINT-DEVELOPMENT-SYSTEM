@extends('layouts.app')
@section('title', ($portal ?? 'customer') === 'staff' ? 'Staff Portal Sign In — CAPACIPRINT' : 'Customer Sign In — CAPACIPRINT')
@section('meta_description', ($portal ?? 'customer') === 'staff' ? 'Internal operations ERP sign in.' : 'Sign in to your CAPACIPRINT customer account.')

@section('body')
@if(($portal ?? 'customer') === 'staff')
    {{-- ========================================================================= --}}
    {{-- ORIGINAL UNCHANGED STAFF & OPERATIONS ERP LOGIN (SUBDOMAIN / PORTAL ONLY) --}}
    {{-- ========================================================================= --}}
    <div class="min-h-screen flex items-center justify-center bg-[#F1F3F7] dark:bg-[#0B1118] py-12 px-4">
        <div class="w-full max-w-md animate-fade-in-up">
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
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider border border-amber-500/20 mb-2">
                            <i class="fa-solid fa-shield-halved"></i> Staff &amp; Operations ERP
                        </span>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-slate-100 font-display">Staff Portal Sign In</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Internal production scheduling &amp; multi-branch operations</p>
                    </div>

                    {{-- Validation errors --}}
                    @if(isset($errors) && $errors->any())
                        <div class="mb-5 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-xl text-xs text-rose-700 dark:text-rose-300 flex items-start gap-3 leading-relaxed font-medium">
                            <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-500"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="portal_type" value="staff">

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
                        </div>

                        <button type="submit" id="login-btn"
                                class="w-full flex justify-center items-center gap-2 bg-[#0E3386] hover:bg-[#0a2663] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-[#0E3386]/25 active:scale-[0.98] cursor-pointer">
                            <i class="fa-solid fa-arrow-right-to-bracket text-[#29bce8]"></i>
                            Sign In to Operations
                        </button>
                    </form>

                    {{-- Fast 1-Click Demo Fill for Staff --}}
                    <div class="mt-6 pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500" x-data>
                        <span>1-Click Test:</span>
                        <button type="button" @click="document.getElementById('email').value='manager@capaciprint.com'; document.getElementById('password').value='password';" class="text-[#0E3386] dark:text-sky-400 font-bold hover:underline cursor-pointer">Fill Staff Demo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- ========================================================================= --}}
    {{-- REDESIGNED SPLIT-CARD CUSTOMER LOGIN (FULL LIGHT & DARK MODE SUPPORT)     --}}
    {{-- ========================================================================= --}}
    <style>
        /* Isolate hero panel text colors so global light-theme overrides do not affect it */
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
            color: rgba(226, 232, 240, 0.9) !important;
        }
        html.light-theme .auth-hero-panel .hero-pill {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>

    <div class="min-h-screen flex items-center justify-center bg-[#F1F3F7] dark:bg-[#070C12] py-8 sm:py-12 px-4 sm:px-6 relative overflow-hidden transition-colors duration-300"
         x-data="{ 
            showPass: false,
            isDark: document.documentElement.classList.contains('dark') || localStorage.theme !== 'light',
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark', 'dark-theme');
                    document.documentElement.classList.remove('light-theme');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.add('light-theme');
                    document.documentElement.classList.remove('dark', 'dark-theme');
                    localStorage.theme = 'light';
                }
            }
         }">

        {{-- Floating Theme Toggle Button in Top Right --}}
        <div class="fixed top-5 right-5 z-50">
            <button @click="toggleTheme()" type="button"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-[#182433] border border-slate-200 dark:border-slate-700 shadow-md hover:shadow-lg text-xs font-semibold text-slate-700 dark:text-slate-200 transition-all cursor-pointer hover:scale-105"
                    title="Toggle Light / Dark Mode">
                <i class="fa-solid text-amber-500 text-sm" :class="isDark ? 'fa-moon text-sky-400' : 'fa-sun text-amber-500'"></i>
                <span x-text="isDark ? 'Dark Mode' : 'Light Mode'" class="hidden sm:inline"></span>
            </button>
        </div>

        {{-- Subtle Ambient Glows --}}
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#0E3386]/10 dark:bg-[#0E3386]/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-[#29bce8]/10 dark:bg-[#29bce8]/15 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Main Split-Card Container --}}
        <div class="w-full max-w-5xl bg-white dark:bg-[#111A24] border border-slate-200/80 dark:border-slate-800/80 rounded-[28px] sm:rounded-[32px] shadow-2xl shadow-slate-300/40 dark:shadow-black/60 overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3 sm:p-4 gap-4 relative z-10 animate-fade-in-up transition-colors duration-300">

            {{-- Left Hero Visual Card (Always Dark Commercial Print Photography) --}}
            <div class="auth-hero-panel lg:col-span-5 relative rounded-2xl sm:rounded-[24px] overflow-hidden min-h-[340px] lg:min-h-[580px] flex flex-col justify-between p-6 sm:p-8 bg-cover bg-center border border-black/10 dark:border-white/5"
                 style="background-image: url('{{ asset('images/press-floor-dark.jpg') }}');">

                {{-- Dark Atmospheric Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-b from-[#0B1118]/90 via-[#0B1118]/65 to-[#070D14]/95 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[#0E3386]/20 mix-blend-overlay pointer-events-none"></div>

                {{-- Top Bar: Logo & Back Button --}}
                <div class="relative z-10 flex items-center justify-between gap-3">
                    <a href="{{ route('landing') }}" class="inline-block transition-transform hover:scale-105" title="CAPACIPRINT">
                        <img src="{{ asset('images/caplogo.png') }}" alt="CAPACIPRINT" class="h-10 sm:h-12 w-auto object-contain drop-shadow-md">
                    </a>

                    <a href="{{ route('landing') }}"
                       class="hero-pill inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/15 hover:bg-white/25 backdrop-blur-md text-white text-xs font-medium transition-all duration-200 border border-white/20 shadow-sm group">
                        <span>Back to website</span>
                        <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                    </a>
                </div>

                {{-- Bottom Tagline & Carousel Indicator --}}
                <div class="relative z-10 space-y-4">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-[#29bce8]/20 border border-[#29bce8]/40 text-[#29bce8] text-[10px] font-bold uppercase tracking-wider">
                            Customer Portal
                        </div>
                        <h2 class="hero-title text-2xl sm:text-3xl font-extrabold text-white font-display leading-tight drop-shadow-sm">
                            Precision in Every Print,<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#29bce8] to-sky-300">Intelligent Routing</span>
                        </h2>
                        <p class="hero-desc text-xs text-slate-200/90 leading-relaxed font-normal max-w-xs">
                            Track real-time queue states, approve design layouts, and monitor order delivery anytime.
                        </p>
                    </div>

                    {{-- Carousel Dots --}}
                    <div class="flex items-center gap-2 pt-2">
                        <span class="hero-dot w-7 h-1.5 rounded-full bg-white shadow-xs"></span>
                        <span class="hero-dot w-2 h-1.5 rounded-full bg-white/40"></span>
                        <span class="hero-dot w-2 h-1.5 rounded-full bg-white/40"></span>
                    </div>
                </div>
            </div>

            {{-- Right Form Panel --}}
            <div class="lg:col-span-7 flex flex-col justify-center px-4 sm:px-8 py-6 sm:py-8">

                {{-- Form Header --}}
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight transition-colors">Welcome back</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#0E3386] dark:text-[#29bce8] hover:underline font-semibold transition">Create an account</a>
                    </p>
                </div>

                {{-- Validation Errors Alert --}}
                @if(isset($errors) && $errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900/60 rounded-xl text-xs text-rose-700 dark:text-rose-300 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0 text-rose-500"></i>
                        <div class="space-y-1">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Customer Login Form --}}
                <form method="POST" action="{{ route('login.submit') }}" class="space-y-4" autocomplete="off">
                    @csrf
                    <input type="hidden" name="portal_type" value="customer">

                    {{-- Email Address --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               placeholder="name@example.com"
                               class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl px-4 py-3 text-sm transition-all outline-none">
                    </div>

                    {{-- Password with Eye Toggle --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Password</label>
                            <a href="#" class="text-xs text-slate-500 dark:text-slate-400 hover:text-[#0E3386] dark:hover:text-[#29bce8] transition">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" :type="showPass ? 'text' : 'password'" required
                                   placeholder="Enter your password"
                                   class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl pl-4 pr-11 py-3 text-sm transition-all outline-none">
                            <button type="button" @click="showPass = !showPass" tabindex="-1"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-[#29bce8] transition-colors p-1"
                                    title="Toggle password visibility">
                                <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-600 dark:text-slate-400 select-none">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded bg-white dark:bg-[#182332] border-slate-300 dark:border-slate-700 text-[#0E3386] focus:ring-[#0E3386] dark:focus:ring-[#29bce8] focus:ring-offset-0 cursor-pointer">
                            <span>Remember me on this device</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="login-btn"
                            class="w-full flex items-center justify-center gap-2 bg-[#0E3386] hover:bg-[#0a2663] active:scale-[0.99] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-[#0E3386]/25 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-[#29bce8]"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center justify-center my-5">
                    <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                    <span class="bg-white dark:bg-[#111A24] px-3 text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-semibold absolute transition-colors">
                        Or continue with
                    </span>
                </div>

                {{-- Quick Options / Fast Test --}}
                <div class="grid grid-cols-2 gap-3" x-data>
                    <button type="button"
                            class="flex items-center justify-center gap-2.5 py-2.5 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-[#182332]/60 dark:hover:bg-[#182332] border border-slate-200 hover:border-slate-300 dark:border-slate-700/70 text-xs font-semibold text-slate-700 dark:text-slate-300 dark:hover:text-white transition cursor-pointer">
                        <i class="fa-brands fa-google text-rose-500"></i>
                        <span>Google</span>
                    </button>

                    <button type="button"
                            @click="document.getElementById('email').value='customer@capaciprint.com'; document.getElementById('password').value='password';"
                            class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-[#182332]/60 dark:hover:bg-[#182332] border border-slate-200 hover:border-slate-300 dark:border-slate-700/70 text-xs font-semibold text-[#0E3386] dark:text-[#29bce8] hover:underline transition cursor-pointer"
                            title="Fill sample customer credentials">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Demo Customer</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
@endif
@endsection
