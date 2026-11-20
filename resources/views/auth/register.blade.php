@extends('layouts.app')
@section('title', 'Create an Account — CAPACIPRINT')
@section('meta_description', 'Create your CAPACIPRINT customer account to submit print requests, approve proofs, and track orders.')

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

<div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-[#0A0F1C] py-8 sm:py-10 px-4 sm:px-6 relative overflow-hidden transition-colors duration-200"
     x-data="{
        firstName: '{{ old('first_name', '') }}',
        lastName: '{{ old('last_name', '') }}',
        fullName: '{{ old('name', '') }}',
        passwordVal: '',
        showPass: false,
        showConfirm: false,
        isDark: document.documentElement.classList.contains('dark') || localStorage.theme === 'dark',
        get passHasLen() { return this.passwordVal.length >= 8; },
        get passHasNumOrSym() { return /[\d\W_]/.test(this.passwordVal); },
        get passStrength() {
            if (!this.passwordVal.length) return 0;
            let score = 1;
            if (this.passHasLen) score++;
            if (this.passHasNumOrSym) score++;
            return score;
        },
        updateFullName() {
            this.fullName = (this.firstName.trim() + ' ' + this.lastName.trim()).trim();
        },
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
        },
        init() {
            if (this.fullName && (!this.firstName && !this.lastName)) {
                let parts = this.fullName.split(' ');
                this.firstName = parts[0] || '';
                this.lastName = parts.slice(1).join(' ') || '';
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

    {{-- Main Split-Card Container (Matches Login Card Size & Position) --}}
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
                        Direct routing to an open 3.2m press.
                    </h2>
                    <p class="hero-desc text-sm text-slate-200 leading-relaxed font-normal max-w-xs">
                        Submit tarpaulin, offset, vinyl, or Sintra jobs online. Orders automatically route to Central Hub, South Press Plant, or North Workshop based on roll stock and queue load.
                    </p>
                </div>

                <div class="pt-3.5 border-t border-white/15 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-300 tabular-nums">
                    <span class="inline-flex items-center gap-1.5 text-[#10B981] font-semibold">
                        <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                        3 Plants Online
                    </span>
                    <span class="text-slate-500" aria-hidden="true">&middot;</span>
                    <span>24&ndash;48h standard turn</span>
                    <span class="text-slate-500" aria-hidden="true">&middot;</span>
                    <span>Same-day rush</span>
                </div>
            </div>
        </div>

        {{-- Right Form Panel (Left-Aligned Heading & Labels) --}}
        <div class="lg:col-span-7 flex flex-col justify-center px-5 sm:px-10 py-6 sm:py-8">

            <div class="mb-5 text-left">
                <h1 class="auth-heading text-2xl sm:text-[1.85rem] font-extrabold text-slate-900 dark:text-white font-display">Create an account</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1.5">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#2F7BFF] hover:underline font-semibold transition">Log in</a>
                </p>
            </div>

            @if(isset($errors) && $errors->any())
                <div class="mb-4 p-3.5 bg-amber-50 dark:bg-[#FFC531]/10 border border-amber-200 dark:border-[#FFC531]/35 rounded-xl text-xs sm:text-sm text-amber-800 dark:text-[#FFC531] flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>
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
                        <label for="first_name" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">First name</label>
                        <input id="first_name" name="first_name" type="text" x-model="firstName" @input="updateFullName()"
                               placeholder="Juan" required
                               class="auth-input px-4 py-2.5">
                    </div>
                    <div>
                        <label for="last_name" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Last name</label>
                        <input id="last_name" name="last_name" type="text" x-model="lastName" @input="updateFullName()"
                               placeholder="Dela Cruz" required
                               class="auth-input px-4 py-2.5">
                    </div>
                </div>

                {{-- Email Address & Phone Number --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="email" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               placeholder="you@company.com"
                               class="auth-input px-4 py-2.5">
                    </div>
                    <div>
                        <label for="phone" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Phone number</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                               placeholder="+63 912 345 6789"
                               class="auth-input px-4 py-2.5">
                    </div>
                </div>

                {{-- Password & Confirm Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="password" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Password</label>
                        <div class="relative">
                            <input id="password" name="password" x-model="passwordVal" :type="showPass ? 'text' : 'password'" required
                                   placeholder="At least 8 characters"
                                   class="auth-input pl-4 pr-11 py-2.5">
                            <button type="button" @click="showPass = !showPass" tabindex="-1"
                                    aria-label="Toggle password visibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 dark:text-[#8B9BB4] hover:text-[#2F7BFF] transition-colors cursor-pointer">
                                <i class="fa-solid text-sm" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[13px] font-semibold text-slate-700 dark:text-slate-200 mb-1.5">Confirm password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'" required
                                   placeholder="Re-enter your password"
                                   class="auth-input pl-4 pr-11 py-2.5">
                            <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                                    aria-label="Toggle confirm password visibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 dark:text-[#8B9BB4] hover:text-[#2F7BFF] transition-colors cursor-pointer">
                                <i class="fa-solid text-sm" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Password Strength & Requirements Hint --}}
                <div class="pt-0.5 space-y-2">
                    <div class="grid grid-cols-3 gap-1.5">
                        <div class="h-1.5 rounded-full transition-colors duration-200"
                             :class="passStrength >= 1 ? (passStrength === 3 ? 'bg-[#10B981]' : (passStrength === 2 ? 'bg-[#2F7BFF]' : 'bg-[#FFC531]')) : 'bg-slate-200 dark:bg-[#1E293B]'"></div>
                        <div class="h-1.5 rounded-full transition-colors duration-200"
                             :class="passStrength >= 2 ? (passStrength === 3 ? 'bg-[#10B981]' : 'bg-[#2F7BFF]') : 'bg-slate-200 dark:bg-[#1E293B]'"></div>
                        <div class="h-1.5 rounded-full transition-colors duration-200"
                             :class="passStrength >= 3 ? 'bg-[#10B981]' : 'bg-slate-200 dark:bg-[#1E293B]'"></div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1 text-xs">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                            <span class="inline-flex items-center gap-1.5 transition-colors" :class="passHasLen ? 'text-[#10B981] font-medium' : 'text-slate-500 dark:text-slate-400'">
                                <i class="fa-solid text-[11px]" :class="passHasLen ? 'fa-circle-check' : 'fa-circle'"></i>
                                At least 8 characters
                            </span>
                            <span class="inline-flex items-center gap-1.5 transition-colors" :class="passHasNumOrSym ? 'text-[#10B981] font-medium' : 'text-slate-500 dark:text-slate-400'">
                                <i class="fa-solid text-[11px]" :class="passHasNumOrSym ? 'fa-circle-check' : 'fa-circle'"></i>
                                Includes a number or symbol
                            </span>
                        </div>
                        <span x-show="passStrength > 0"
                              x-text="passStrength === 3 ? 'Strong password' : (passStrength === 2 ? 'Good password' : 'Weak password')"
                              :class="passStrength === 3 ? 'text-[#10B981]' : (passStrength === 2 ? 'text-[#2F7BFF]' : 'text-[#FFC531]')"
                              class="font-semibold" x-cloak></span>
                    </div>
                </div>

                {{-- Primary Submit Button --}}
                <div class="pt-1">
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#2F7BFF] hover:bg-[#1F68EB] text-white font-semibold py-3 px-4 rounded-xl text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Create account</span>
                    </button>
                </div>

                {{-- Terms & Privacy Line --}}
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-1">
                    By creating an account, you agree to CapaciPrint&apos;s Terms of Service and Privacy Policy for job quoting, proof approval, and pickup SMS/email notifications.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
