@extends('layouts.app')
@section('title', 'Create an Account — CAPACIPRINT')
@section('meta_description', 'Create your CAPACIPRINT customer account to submit print requests, approve proofs, and track orders.')

@section('body')
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
    /* Keep inputs clean and plain; prevent browser autofill blue/yellow background discoloration */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
        -webkit-text-fill-color: #0f172a !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    html.dark input:-webkit-autofill,
    html.dark-theme input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #182332 inset !important;
        -webkit-text-fill-color: #ffffff !important;
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-[#F1F3F7] dark:bg-[#070C12] py-8 sm:py-12 px-4 sm:px-6 relative overflow-hidden transition-colors duration-300"
     x-data="{
        firstName: '{{ old('first_name', '') }}',
        lastName: '{{ old('last_name', '') }}',
        fullName: '{{ old('name', '') }}',
        showPass: false,
        showConfirm: false,
        isDark: document.documentElement.classList.contains('dark') || localStorage.theme !== 'light',
        updateFullName() {
            this.fullName = (this.firstName.trim() + ' ' + this.lastName.trim()).trim();
        },
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
        },
        init() {
            if (this.fullName && (!this.firstName && !this.lastName)) {
                let parts = this.fullName.split(' ');
                this.firstName = parts[0] || '';
                this.lastName = parts.slice(1).join(' ') || '';
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

    {{-- Main Container Card --}}
    <div class="w-full max-w-5xl bg-white dark:bg-[#111A24] border border-slate-200/80 dark:border-slate-800/80 rounded-[28px] sm:rounded-[32px] shadow-2xl shadow-slate-300/40 dark:shadow-black/60 overflow-hidden grid grid-cols-1 lg:grid-cols-12 p-3 sm:p-4 gap-4 relative z-10 animate-fade-in-up transition-colors duration-300">

        {{-- Left Hero Visual Card (Always Dark Commercial Print Photography) --}}
        <div class="auth-hero-panel lg:col-span-5 relative rounded-2xl sm:rounded-[24px] overflow-hidden min-h-[360px] lg:min-h-[620px] flex flex-col justify-between p-6 sm:p-8 bg-cover bg-center border border-black/10 dark:border-white/5"
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
                    <h2 class="hero-title text-2xl sm:text-3xl font-extrabold text-white font-display leading-tight drop-shadow-sm">
                        Precision in Every Print,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#29bce8] to-sky-300">Intelligent Routing</span>
                    </h2>
                    <p class="hero-desc text-xs text-slate-200/90 leading-relaxed font-normal max-w-xs">
                        Real-time capacity tracking, instant proofs approval, and end-to-end commercial print fulfillment.
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
            <div class="mb-6 text-center">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white font-display tracking-tight transition-colors">Create an account</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#0E3386] dark:text-[#29bce8] hover:underline font-semibold transition">Log in</a>
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

            {{-- Registration Form --}}
            <form method="POST" action="{{ route('register.submit') }}" class="space-y-4" autocomplete="off" @submit="updateFullName()">
                @csrf

                {{-- Hidden full name synchronized for Laravel's RegisterController --}}
                <input type="hidden" name="name" :value="fullName">

                {{-- First Name & Last Name (2-Column) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">First name</label>
                        <input id="first_name" name="first_name" type="text" x-model="firstName" @input="updateFullName()"
                               placeholder="Juan" required
                               class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl px-4 py-3 text-sm transition-all outline-none">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Last name</label>
                        <input id="last_name" name="last_name" type="text" x-model="lastName" @input="updateFullName()"
                               placeholder="Dela Cruz" required
                               class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl px-4 py-3 text-sm transition-all outline-none">
                    </div>
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                           placeholder="Enter your email address"
                           class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl px-4 py-3 text-sm transition-all outline-none">
                </div>

                {{-- Contact Phone --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Phone number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                           placeholder="+63 912 345 6789"
                           class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl px-4 py-3 text-sm transition-all outline-none">
                </div>

                {{-- Password with Eye Toggle --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="showPass ? 'text' : 'password'" required
                               placeholder="Enter your password (min. 8 characters)"
                               class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl pl-4 pr-11 py-3 text-sm transition-all outline-none">
                        <button type="button" @click="showPass = !showPass" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-[#29bce8] transition-colors p-1"
                                title="Toggle password visibility">
                            <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password with Eye Toggle --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" required
                               placeholder="Re-enter your password"
                               class="w-full bg-slate-50 hover:bg-slate-100/70 focus:bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:border-[#0E3386] focus:ring-2 focus:ring-[#0E3386]/20 dark:bg-[#182332]/70 dark:hover:bg-[#182332] dark:focus:bg-[#1b2839] dark:border-slate-700/80 dark:focus:border-[#29bce8] dark:focus:ring-1 dark:focus:ring-[#29bce8] dark:text-white dark:placeholder-slate-500 rounded-xl pl-4 pr-11 py-3 text-sm transition-all outline-none">
                        <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-[#29bce8] transition-colors p-1"
                                title="Toggle password visibility">
                            <i class="fa-solid text-sm" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>



                {{-- Primary Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#0E3386] hover:bg-[#0a2663] active:scale-[0.99] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-lg shadow-[#0E3386]/25 cursor-pointer">
                        <i class="fa-solid fa-user-plus text-[#29bce8]"></i>
                        <span>Create account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
