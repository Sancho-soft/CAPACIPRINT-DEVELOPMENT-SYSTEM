<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" id="meta-theme-color" content="#F8FAFC">
    <title>@yield('title', 'CAPACIPRINT') — Intelligent Print Routing</title>
    <meta name="description" content="@yield('meta_description', 'CAPACIPRINT – Intelligent capacity-based print routing system.')">

    {{-- Fonts: Bricolage Grotesque (Display), Plus Jakarta Sans (Body/UI), IBM Plex Mono (Tickets/IDs) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=IBM+Plex+Mono:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" onerror="this.onerror=null; this.href='{{ asset('assets/fontsource/inter/index.css') }}';">

    {{-- FontAwesome (CDN with Local Fallback) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onerror="this.onerror=null; this.href='{{ asset('assets/fontawesome/css/all.min.css') }}';">

    {{-- Tailwind & ChartJS (CDN with Local Fallback) --}}
    <script src="https://cdn.tailwindcss.com" onerror="this.onerror=null; this.src='{{ asset('assets/js/tailwindcss.js') }}';"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" onerror="this.onerror=null; this.src='{{ asset('assets/js/chart.min.js') }}';"></script>
    <script>
        (function () {
            const family = "'Plus Jakarta Sans', system-ui, -apple-system, sans-serif";
            if (window.Chart) {
                Chart.defaults.font.family = family;
            }
            document.addEventListener('DOMContentLoaded', function () {
                if (window.Chart) {
                    Chart.defaults.font.family = family;
                }
            });
        })();
    </script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            base: '#0A0F1C',
                            dark: '#0D1322',
                            card: '#111827',
                            border: '#1E293B',
                            hover: '#161F33',
                            muted: '#94A3B8',
                        },
                        navy: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1',
                            400: '#94a3b8', 500: '#64748b', 600: '#475569',
                            700: '#1E293B', 800: '#111827', 900: '#0D1322', 950: '#0A0F1C',
                        },
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#2F7BFF', 600: '#2F7BFF',
                            700: '#1F68EB', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                        },
                        cubs: {
                            DEFAULT: '#2F7BFF',
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#2F7BFF', 600: '#2F7BFF',
                            700: '#1F68EB', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                        },
                        cyan: {
                            400: '#60a5fa', 500: '#2F7BFF', 600: '#2F7BFF'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', '"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
                    }
                }
            }
        }
    </script>
    <script>
        // Default to Light Mode; apply Dark Mode only when explicitly chosen
        (function () {
            const meta = document.getElementById('meta-theme-color');
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.add('dark', 'dark-theme');
                document.documentElement.classList.remove('light-theme');
                if (meta) meta.setAttribute('content', '#0A0F1C');
            } else {
                document.documentElement.classList.add('light-theme');
                document.documentElement.classList.remove('dark', 'dark-theme');
                if (meta) meta.setAttribute('content', '#F8FAFC');
            }
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        html, body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            letter-spacing: 0.005em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Bricolage Grotesque', 'Plus Jakarta Sans', system-ui, sans-serif !important;
            line-height: 1.14 !important;
            letter-spacing: -0.012em;
        }
        .font-sans { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important; letter-spacing: 0.005em; }
        .font-mono, .font-ticket {
            font-family: 'IBM Plex Mono', ui-monospace, SFMono-Regular, monospace !important;
            font-variant-numeric: tabular-nums;
        }
        .tabular-nums { font-variant-numeric: tabular-nums; }

        /* Web Interface Guidelines & Baseline UI Ergonomics */
        h1, h2, h3 { text-wrap: balance; }
        p { text-wrap: pretty; }
        [id] { scroll-margin-top: 5rem; }
        a, button, input, select, textarea { touch-action: manipulation; }
        :focus-visible { outline: 2px solid #2F7BFF; outline-offset: 2px; }
        html.dark :focus-visible, html.dark-theme :focus-visible { outline-color: #2F7BFF; }

        :root {
            --brand-dark: #111827;
            --brand-mid:  #3f4652;
            --brand-blue: #2F7BFF;
            --accent-primary: #2F7BFF;
        }

        /* Dark Theme — Print-Company Palette */
        html.dark-theme, html.dark, :root {
            color-scheme: dark;
            --bg-base: #0A0F1C;
            --bg-surface: #0D1322;
            --bg-card: #111827;
            --bg-card-sub: #0D1322;
            --border-main: #1E293B;
            --border-sub: #172033;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --text-sub: #64748B;
        }

        /* Clean Modern Light Theme (Default) */
        html.light-theme {
            color-scheme: light;
            --bg-base: #F8FAFC;
            --bg-surface: #F1F5F9;
            --bg-card: #FFFFFF;
            --bg-card-sub: #F8FAFC;
            --border-main: #E2E8F0;
            --border-sub: #F1F5F9;
            --text-main: #0F172A;
            --text-muted: #475569;
            --text-sub: #64748B;
        }

        /* ═══ Mechanical Theme Transitions ═══ */
        html.theme-transitioning,
        html.theme-transitioning *,
        html.theme-transitioning *::before,
        html.theme-transitioning *::after {
            transition: background-color 0.2s cubic-bezier(0.2, 0, 0, 1),
                        border-color 0.2s cubic-bezier(0.2, 0, 0, 1),
                        color 0.15s cubic-bezier(0.2, 0, 0, 1) !important;
            transition-delay: 0s !important;
        }

        /* ═══ Theme-aware utility classes ═══ */
        .bg-cyber-base { background-color: var(--bg-base) !important; }
        .bg-cyber-surface { background-color: var(--bg-surface) !important; }
        .bg-cyber-card { background-color: var(--bg-card) !important; }
        .bg-cyber-sub { background-color: var(--bg-card-sub) !important; }
        .border-cyber { border-color: var(--border-main) !important; }
        .border-cyber-sub { border-color: var(--border-sub) !important; }
        .text-cyber-main { color: var(--text-main) !important; }
        .text-cyber-muted { color: var(--text-muted) !important; }
        .text-cyber-sub { color: var(--text-sub) !important; }

        /* ══════════════════════════════════════════════════════════════
           GLOBAL DARK-THEME OVERRIDES
           Forces all hardcoded light-mode Tailwind classes to switch
           when html.dark-theme (or html.dark) is active.
           This prevents cards, tables, inputs from staying "white"
           when the user switches to dark mode.
           ══════════════════════════════════════════════════════════════ */

        /* Card / container backgrounds */
        html.dark-theme main .bg-white,
        html.dark main .bg-white {
            background-color: #111827 !important;
        }
        html.dark-theme main .bg-slate-50,
        html.dark main .bg-slate-50 {
            background-color: #0A0F1C !important;
        }
        html.dark-theme main .bg-slate-100,
        html.dark main .bg-slate-100 {
            background-color: #0D1322 !important;
        }

        /* Colored tint backgrounds */
        html.dark-theme main .bg-blue-50,
        html.dark main .bg-blue-50 { background-color: rgba(47,123,255,0.12) !important; }
        html.dark-theme main .bg-emerald-50,
        html.dark main .bg-emerald-50 { background-color: rgba(16,185,129,0.12) !important; }
        html.dark-theme main .bg-purple-50,
        html.dark main .bg-purple-50 { background-color: rgba(47,123,255,0.12) !important; }
        html.dark-theme main .bg-amber-50,
        html.dark main .bg-amber-50 { background-color: rgba(255,197,49,0.12) !important; }
        html.dark-theme main .bg-red-50,
        html.dark main .bg-red-50 { background-color: rgba(255,197,49,0.12) !important; }
        html.dark-theme main .bg-teal-50,
        html.dark main .bg-teal-50 { background-color: rgba(16,185,129,0.12) !important; }
        html.dark-theme main .bg-cyan-50,
        html.dark main .bg-cyan-50 { background-color: rgba(47,123,255,0.12) !important; }
        html.dark-theme main .bg-indigo-50,
        html.dark main .bg-indigo-50 { background-color: rgba(47,123,255,0.12) !important; }

        /* Border colors */
        html.dark-theme main .border-slate-100,
        html.dark main .border-slate-100 {
            border-color: #1E293B !important;
        }
        html.dark-theme main .border-slate-200,
        html.dark main .border-slate-200 {
            border-color: #1E293B !important;
        }
        html.dark-theme main .border-slate-300,
        html.dark main .border-slate-300 {
            border-color: #2B3A55 !important;
        }

        /* Dividers */
        html.dark-theme main .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark main .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html.dark-theme main .divide-slate-200 > :not([hidden]) ~ :not([hidden]),
        html.dark main .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #172033 !important;
        }

        /* Text colors — dark headings become crisp paper-white */
        html.dark-theme main .text-navy-900,
        html.dark main .text-navy-900 {
            color: #F8FAFC !important;
        }
        html.dark-theme main .text-slate-900,
        html.dark main .text-slate-900 {
            color: #F8FAFC !important;
        }
        html.dark-theme main .text-slate-800,
        html.dark main .text-slate-800 {
            color: #E2E8F0 !important;
        }
        html.dark-theme main .text-slate-700,
        html.dark main .text-slate-700 {
            color: #CBD5E1 !important;
        }
        html.dark-theme main .text-slate-600,
        html.dark main .text-slate-600 {
            color: #94A3B8 !important;
        }

        /* Hover states for dark mode */
        html.dark-theme main .hover\:bg-slate-50:hover,
        html.dark main .hover\:bg-slate-50:hover,
        html.dark-theme main .hover\:bg-slate-100:hover,
        html.dark main .hover\:bg-slate-100:hover,
        html.dark-theme main .hover\:bg-slate-50\/50:hover,
        html.dark main .hover\:bg-slate-50\/50:hover {
            background-color: #161F33 !important;
        }
        html.dark-theme main .hover\:shadow-md:hover,
        html.dark main .hover\:shadow-md:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.35) !important;
        }

        /* High-contrast form inputs in dark mode */
        html.dark-theme main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="hidden"]),
        html.dark-theme main select,
        html.dark-theme main textarea,
        html.dark main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="hidden"]),
        html.dark main select,
        html.dark main textarea {
            background-color: #182235 !important;
            border-color: #2B3A55 !important;
            color: #F8FAFC !important;
            font-size: 0.875rem !important;
        }
        html.dark-theme main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):focus,
        html.dark-theme main select:focus,
        html.dark-theme main textarea:focus,
        html.dark main input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):focus,
        html.dark main select:focus,
        html.dark main textarea:focus {
            border-color: #2F7BFF !important;
            box-shadow: 0 0 0 3px rgba(47, 123, 255, 0.16) !important;
            outline: none !important;
        }
        html.dark-theme main select option,
        html.dark main select option {
            background-color: #182235 !important;
            color: #F8FAFC !important;
        }
        html.dark-theme main input::placeholder,
        html.dark main input::placeholder,
        html.dark-theme main textarea::placeholder,
        html.dark main textarea::placeholder {
            color: #8B9BB4 !important;
        }

        /* ══════════════════════════════════════════════════════════════
           GLOBAL LIGHT-THEME OVERRIDES (Clean Modern Slate Tokens)
           ══════════════════════════════════════════════════════════════ */
        html.light-theme .bg-\[\#111A24\] {
            background-color: #FFFFFF !important;
        }

        html.light-theme .bg-\[\#0D1520\] {
            background-color: #F8FAFC !important;
        }

        html.light-theme .bg-\[\#0B1118\] {
            background-color: #F8FAFC !important;
        }

        html.light-theme .bg-\[\#0D1520\]\/80 {
            background-color: #F8FAFC !important;
        }

        html.light-theme .bg-\[\#0B1118\]\/80 {
            background-color: #F1F5F9 !important;
        }

        /* Sidebar container in light mode */
        html.light-theme aside.bg-\[\#111A24\],
        html.light-theme aside.bg-cyber-surface {
            background-color: #F1F5F9 !important;
            border-color: #E2E8F0 !important;
        }

        /* Top header in light mode */
        html.light-theme header.bg-cyber-surface\/90,
        html.light-theme header.bg-cyber-surface {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-color: #E2E8F0 !important;
        }

        /* Table header and inner metric sub-panels in Light Mode */
        html.light-theme .bg-cyber-base\/80,
        html.light-theme thead.bg-cyber-base\/80,
        html.light-theme thead {
            background-color: #F8FAFC !important;
            color: #334155 !important;
            font-weight: 700 !important;
            border-color: #E2E8F0 !important;
        }

        html.light-theme .bg-cyber-card\/60,
        html.light-theme .bg-cyber-sub\/60,
        html.light-theme .bg-cyber-sub\/70,
        html.light-theme .bg-cyber-sub\/80 {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
        }

        html.light-theme .bg-cyber-hover\/50:hover,
        html.light-theme tr.hover\:bg-cyber-hover\/50:hover {
            background-color: #F1F5F9 !important;
        }

        /* Text colors — scoped to dark-hex containers only */
        html.light-theme .bg-\[\#111A24\] .text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme .bg-\[\#0D1520\] .text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme .bg-\[\#0B1118\] .text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme .bg-\[\#111A24\].text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme .bg-\[\#0D1520\].text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme .bg-\[\#0B1118\].text-white:not(button):not(a[class*="bg-"]):not([class*="bg-"]),
        html.light-theme main .text-white:not([class*="bg-gradient"]):not([class*="bg-cyan"]):not([class*="bg-brand"]):not([class*="bg-navy"]):not([class*="bg-red"]):not([class*="bg-emerald"]):not([class*="bg-blue"]):not([class*="bg-sky"]):not([class*="bg-slate-800"]):not([class*="bg-slate-900"]):not([class*="bg-indigo"]):not([class*="bg-purple"]):not([class*="bg-teal"]):not([class*="bg-amber"]):not([class*="from-"]):not(button):not(a[class*="bg-"]):not(.btn) {
            color: #0F172A !important;
        }

        /* Explicitly guarantee white text on colored solid action buttons & pills */
        html.light-theme main button.text-white,
        html.light-theme main a.text-white,
        html.light-theme main [class*="bg-sky-"] .text-white,
        html.light-theme main [class*="bg-sky-"] *::before,
        html.light-theme main [class*="bg-brand-"] .text-white,
        html.light-theme main [class*="bg-emerald-"] .text-white,
        html.light-theme main [class*="bg-indigo-"] .text-white,
        html.light-theme main [class*="bg-rose-"] .text-white {
            color: #FFFFFF !important;
        }
        /* Hero banners in Light Mode (Production Planning, Reports, etc.) */
        html.light-theme .from-navy-900.to-navy-800,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] {
            background: #FFFFFF !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.04), 0 2px 4px -2px rgba(15, 23, 42, 0.04) !important;
        }

        html.light-theme .from-navy-900.to-navy-800 h2,
        html.light-theme .from-navy-900.to-navy-800 .text-white,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] h2,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] .text-white:not(button):not(.bg-cyan-500):not(.bg-brand-500):not(.bg-amber-500) {
            color: #0F172A !important;
        }

        html.light-theme .from-navy-900.to-navy-800 p,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] p {
            color: #64748B !important;
        }

        html.light-theme .from-navy-900.to-navy-800 .bg-navy-800,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] .bg-navy-800 {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
            border-color: #CBD5E1 !important;
        }
        html.light-theme .text-slate-200 {
            color: #334155 !important;
        }
        html.light-theme .text-slate-300 {
            color: #475569 !important;
        }
        html.light-theme .text-slate-400 {
            color: #64748B !important;
        }
        html.light-theme .text-slate-500 {
            color: #64748B !important;
        }

        /* Protect contrast only for specifically designated dark badges */
        html.light-theme .badge-dark,
        html.light-theme .tag-dark {
            color: #FFFFFF !important;
        }

        /* Table header cells and borders in Light Mode */
        html.light-theme table thead th {
            background-color: #F8FAFC !important;
            color: #334155 !important;
            font-weight: 700 !important;
            border-bottom: 1px solid #E2E8F0 !important;
        }

        table thead th {
            font-weight: 700 !important;
        }

        /* Inner metric boxes in branch workload cards & tables */
        html.light-theme .bg-cyber-card\/60,
        html.light-theme [class*="bg-cyber-card"] {
            background-color: #F8FAFC !important;
        }
        html.light-theme .bg-cyber-sub\/60,
        html.light-theme .bg-cyber-sub\/70,
        html.light-theme .bg-cyber-sub\/80,
        html.light-theme [class*="bg-cyber-sub"] {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
        }

        /* Border colors */
        html.light-theme .border-slate-800\/80,
        html.light-theme .border-slate-800,
        html.light-theme .border-slate-800\/60,
        html.light-theme .border-slate-700 {
            border-color: #E2E8F0 !important;
        }

        /* Dividers */
        html.light-theme .divide-slate-800\/60 > :not([hidden]) ~ :not([hidden]),
        html.light-theme .divide-slate-200 > :not([hidden]) ~ :not([hidden]),
        html.light-theme .divide-slate-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: #F1F5F9 !important;
        }
        html.light-theme .border-slate-200,
        html.light-theme .border-slate-100 {
            border-color: #E2E8F0 !important;
        }

        /* Background hover states in sidebar & tables */
        html.light-theme .hover\:bg-slate-800\/60:hover,
        html.light-theme .hover\:bg-slate-800\/40:hover,
        html.light-theme .hover\:bg-slate-800\/80:hover,
        html.light-theme .hover\:bg-slate-800:hover {
            background-color: #F1F5F9 !important;
        }
        html.light-theme .hover\:bg-slate-700\/80:hover {
            background-color: #E2E8F0 !important;
        }
        html.light-theme .hover\:text-slate-100:hover {
            color: #0F172A !important;
        }

        /* Sidebar active/inactive link overrides */
        html.light-theme .bg-slate-800\/80 {
            background-color: #E2E8F0 !important;
        }

        /* Table row hover */
        html.light-theme tr.hover\:bg-slate-800\/40:hover {
            background-color: #F1F5F9 !important;
        }

        /* Mono font text in tables */
        html.light-theme .font-mono.text-slate-500 {
            color: #64748B !important;
        }

        /* Chart/Donut border color adapts to light */
        html.light-theme canvas {
            --chart-border: #FFFFFF;
        }

        /* Notification dot ring */
        html.light-theme .ring-\[\#0D1520\] {
            --tw-ring-color: #FFFFFF !important;
        }

        /* Sidebar bottom section */
        html.light-theme .bg-\[\#0B1118\]\/80 {
            background-color: #F8FAFC !important;
        }

        /* Sidebar border bottom */
        html.light-theme .border-t.border-slate-800\/80 {
            border-color: #E2E8F0 !important;
        }

        /* Navy colors (used in buttons, sidebars in welcome/reports) */
        html.light-theme .bg-navy-800,
        html.light-theme .bg-navy-900 {
            background-color: #1E293B !important;
        }
        html.light-theme .bg-navy-900\/60 {
            background-color: #F1F5F9 !important;
        }
        html.light-theme .border-navy-700,
        html.light-theme .border-navy-900 {
            border-color: #E2E8F0 !important;
        }
        html.light-theme .hover\:bg-navy-700:hover,
        html.light-theme .hover\:bg-navy-800:hover {
            background-color: #334155 !important;
        }

        /* bg-cyber-dark overrides */
        html.light-theme .bg-cyber-dark {
            background-color: #F1F5F9 !important;
        }

        /* Subtle Modern Card Elevation */
        html.light-theme .shadow-2xl,
        html.light-theme .shadow-xl {
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        }
        html.light-theme .shadow-lg,
        html.light-theme .shadow-md {
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05) !important;
        }
        html.dark-theme main .shadow-2xl,
        html.dark-theme main .shadow-xl {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35) !important;
        }

        /* Modern Card Geometry & Table Styling */
        main .rounded-3xl {
            border-radius: 1rem !important;
        }
        main table {
            font-variant-numeric: tabular-nums;
        }
        html.dark-theme main table thead,
        html.dark-theme main table thead th {
            background-color: #0D1322 !important;
            color: #94A3B8 !important;
            border-color: #1E293B !important;
        }
        html.light-theme main table thead,
        html.light-theme main table thead th {
            background-color: #F8FAFC !important;
            color: #334155 !important;
            border-color: #E2E8F0 !important;
        }

        html.light-theme .blur-3xl {
            opacity: 0.06 !important;
        }

        /* Hover border accent colors */
        html.light-theme .hover\:border-cyan-500\/40:hover { border-color: rgba(47,123,255,0.45) !important; }
        html.light-theme .hover\:border-cyan-500\/30:hover { border-color: rgba(47,123,255,0.35) !important; }
        html.light-theme .hover\:border-indigo-500\/40:hover { border-color: rgba(47,123,255,0.4) !important; }
        html.light-theme .hover\:border-indigo-500\/30:hover { border-color: rgba(47,123,255,0.3) !important; }
        html.light-theme .hover\:border-emerald-500\/40:hover { border-color: rgba(16,185,129,0.4) !important; }
        html.light-theme .hover\:border-emerald-500\/30:hover { border-color: rgba(16,185,129,0.3) !important; }
        html.light-theme .hover\:border-amber-500\/30:hover { border-color: rgba(217,119,6,0.35) !important; }
        html.light-theme .hover\:border-purple-500\/40:hover { border-color: rgba(47,123,255,0.4) !important; }

        /* Selection color */
        html.light-theme .selection\:bg-cyan-500\/30::selection { background-color: rgba(47,123,255,0.14) !important; }
        html.light-theme .selection\:text-cyan-200::selection { color: #2F7BFF !important; }

        /* Clean Modern Slate Light Theme Surfaces */
        html.light-theme,
        html.light-theme body,
        html.light-theme .bg-cyber-base {
            background-color: #F8FAFC !important;
            color: #0F172A;
        }
        html.light-theme main .bg-white,
        html.light-theme .bg-white {
            background-color: #FFFFFF !important;
        }
        html.light-theme .bg-cyber-card {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
        }
        html.light-theme .bg-cyber-sub {
            background-color: #F8FAFC !important;
        }
        html.light-theme .border-cyber {
            border-color: #E2E8F0 !important;
        }
        html.light-theme .border-cyber-sub {
            border-color: #F1F5F9 !important;
        }

        /* Print-Company Typography Hierarchy */
        html, body, button, input, select, textarea, .font-sans {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            letter-spacing: 0.005em;
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Bricolage Grotesque', 'Plus Jakarta Sans', system-ui, sans-serif !important;
            line-height: 1.14 !important;
            letter-spacing: -0.012em;
        }
        .font-mono, .font-ticket {
            font-family: 'IBM Plex Mono', ui-monospace, SFMono-Regular, monospace !important;
            font-variant-numeric: tabular-nums;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        main table thead th {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.015em !important;
            text-transform: none !important;
        }
        main .uppercase.tracking-wider,
        main .uppercase.tracking-widest {
            letter-spacing: 0.025em !important;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease both; }
        input::-ms-reveal, input::-ms-clear { display: none; }
        /* Hide scrollbars for clean navigation appearance */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ═══ Responsive / All-Resolution Compatibility ═══ */
        @media (min-width: 768px) {
            aside {
                transform: none !important;
                position: sticky !important;
                top: 0 !important;
                left: 0 !important;
                display: flex !important;
            }
        }

        @media (max-width: 640px) {
            /* Smaller padding on small screens */
            main { padding: 1rem !important; }
            /* Stack metric cards vertically */
            .grid-cols-4 { grid-template-columns: 1fr 1fr !important; }
            /* Ensure tables scroll horizontally */
            table { font-size: 11px; }
            th, td { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        }

        @media (max-width: 1024px) {
            /* Ensure overflow-x scrolling for wide tables */
            .overflow-x-auto { -webkit-overflow-scrolling: touch; }
        }

        /* ═══ Fluid Full-Width Layout Across All Views ═══ */
        main .max-w-7xl,
        main .max-w-6xl,
        main .max-w-5xl {
            max-width: 100% !important;
            width: 100% !important;
        }

        /* ═══ Global Print Mode Styles ═══ */
        @media print {
            aside, header, nav, .print\:hidden {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>

    @yield('head')
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50">
@yield('body')

{{-- Alpine.js (CDN with Local Fallback) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" onerror="this.onerror=null; this.src='{{ asset('assets/js/alpine.min.js') }}';"></script>

{{-- Global Smart Pagination (In-Place Fast Swapping & Smooth Auto-Scroll to Top of Table) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    function findTableCard(el) {
        if (!el) return null;
        let current = el.parentElement;
        while (current && current !== document.body) {
            if (current.querySelector('table') && (
                current.classList.contains('rounded-3xl') || 
                current.classList.contains('rounded-2xl') || 
                current.classList.contains('rounded-xl') || 
                current.classList.contains('bg-cyber-card') || 
                current.classList.contains('bg-white') || 
                current.classList.contains('shadow-xl') || 
                current.classList.contains('shadow-2xl') ||
                current.classList.contains('overflow-hidden')
            )) {
                return current;
            }
            current = current.parentElement;
        }
        return el.closest('table')?.parentElement || el.closest('[data-pagination-container]') || el.parentElement?.parentElement || el;
    }

    function scrollToTableTop(targetEl) {
        if (!targetEl) return;
        const navOffset = 90; // offset for sticky/fixed top navigation header
        const elementPosition = targetEl.getBoundingClientRect().top + window.pageYOffset;
        const targetPosition = Math.max(0, elementPosition - navOffset);
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }

    // 1. On full page load: if URL contains page= or pagination flag in session, smoothly view top of table
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const hasPageParam = urlParams.has('page') || Array.from(urlParams.keys()).some(k => k.includes('page'));
        const pendingScroll = sessionStorage.getItem('capaciprint_scroll_pagination');

        if (hasPageParam || pendingScroll) {
            sessionStorage.removeItem('capaciprint_scroll_pagination');
            setTimeout(function () {
                const nav = document.querySelector('nav[data-pagination-nav], nav[aria-label="Pagination Navigation"]');
                if (nav) {
                    const card = findTableCard(nav);
                    scrollToTableTop(card || nav);
                }
            }, 120);
        }
    } catch (err) {
        console.warn('Pagination scroll check:', err);
    }

    // 2. Global click interceptor for pagination links (in-place instant view)
    document.addEventListener('click', function (e) {
        if (e.defaultPrevented) return;

        const link = e.target.closest('nav[data-pagination-nav] a, nav[aria-label="Pagination Navigation"] a, a.pagination-link, [data-pagination-container] a[href*="page="]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        const nav = link.closest('nav');
        const container = findTableCard(nav);
        if (!container) return;

        e.preventDefault();

        // Subtle visual loading feedback
        container.style.transition = 'opacity 0.2s ease';
        container.style.opacity = '0.55';
        container.style.pointerEvents = 'none';

        fetch(href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Network error');
            return res.text();
        })
        .then(function (html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            let newContainer = null;
            if (container.id) {
                newContainer = doc.getElementById(container.id);
            }

            // Match by card title / heading text if available
            if (!newContainer) {
                const heading = container.querySelector('h1, h2, h3, h4, h5, h6')?.textContent?.trim();
                if (heading) {
                    const docHeadings = doc.querySelectorAll('h1, h2, h3, h4, h5, h6');
                    for (let i = 0; i < docHeadings.length; i++) {
                        if (docHeadings[i].textContent.trim() === heading) {
                            newContainer = findTableCard(docHeadings[i]);
                            break;
                        }
                    }
                }
            }

            // Match by pagination nav position
            if (!newContainer) {
                const allNavs = Array.from(document.querySelectorAll('nav[data-pagination-nav], nav[aria-label="Pagination Navigation"]'));
                const navIdx = allNavs.indexOf(nav);
                const docNavs = Array.from(doc.querySelectorAll('nav[data-pagination-nav], nav[aria-label="Pagination Navigation"]'));
                if (docNavs[navIdx]) {
                    newContainer = findTableCard(docNavs[navIdx]);
                }
            }

            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
                window.history.pushState({ path: href }, '', href);

                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(container);
                }

                // Smoothly scroll directly to the top of the table so the user sees the next rows immediately
                scrollToTableTop(container);
            } else {
                sessionStorage.setItem('capaciprint_scroll_pagination', 'true');
                window.location.href = href;
            }
        })
        .catch(function () {
            sessionStorage.setItem('capaciprint_scroll_pagination', 'true');
            window.location.href = href;
        })
        .finally(function () {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    });

    // Handle browser back/forward buttons with pagination states
    window.addEventListener('popstate', function () {
        if (window.location.search.includes('page=') || window.location.search.includes('search=')) {
            window.location.reload();
        }
    });

    // 3. Global Live Search & Filter (Auto-updates table on typing and filter change)
    function findSearchTableCard(form) {
        if (!form) return null;
        
        // 1. Explicit data-table-target or id attribute
        if (form.dataset.tableTarget) {
            const el = document.getElementById(form.dataset.tableTarget);
            if (el) return el;
        }

        // 2. If form is inside the card
        let parent = form.parentElement;
        while (parent && parent !== document.body) {
            if (parent.querySelector('table, tbody, [data-empty-state], .empty-state') && (
                parent.classList.contains('overflow-hidden') ||
                parent.classList.contains('rounded-3xl') ||
                parent.classList.contains('rounded-2xl') ||
                parent.classList.contains('rounded-xl') ||
                parent.classList.contains('bg-cyber-card') ||
                parent.classList.contains('bg-white')
            )) {
                return parent;
            }
            parent = parent.parentElement;
        }

        // 3. Look for table card siblings or in main content container
        const mainArea = form.closest('.space-y-6, .space-y-5, main, [class*="max-w-"]') || document.body;
        const allCards = mainArea.querySelectorAll('.overflow-hidden, .bg-white, .bg-cyber-card, [class*="rounded-2xl"], [class*="rounded-3xl"], [class*="rounded-xl"]');
        for (let i = 0; i < allCards.length; i++) {
            const c = allCards[i];
            if (!c.contains(form) && (c.querySelector('table, tbody') || c.id?.includes('table') || c.classList.contains('overflow-hidden'))) {
                return c;
            }
        }

        // 4. Fallback to any table card on the page
        const firstTable = document.querySelector('table');
        if (firstTable) {
            return findTableCard(firstTable);
        }
        return null;
    }

    let liveSearchDebounce = null;
    let liveSearchAbort = null;

    function triggerGlobalLiveSearch(form, activeInput) {
        if (!form) return;
        const container = findSearchTableCard(form);
        if (!container) return;

        if (liveSearchAbort) {
            liveSearchAbort.abort();
        }
        liveSearchAbort = new AbortController();

        // Visual loading spinner feedback on magnifying glass
        const searchIcon = form.querySelector('.fa-magnifying-glass');
        let prevIconClass = null;
        if (searchIcon) {
            prevIconClass = searchIcon.className;
            searchIcon.className = 'fa-solid fa-spinner fa-spin text-xs text-cyan-500';
        }

        container.style.transition = 'opacity 0.15s ease';
        container.style.opacity = '0.55';

        // Collect all form data (search, status, date_from, sort_by, etc.)
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, val] of formData.entries()) {
            if (val !== '' && val !== null) {
                params.append(key, val);
            }
        }

        const actionUrl = form.getAttribute('action') || window.location.pathname;
        const targetUrl = new URL(actionUrl, window.location.origin);
        targetUrl.search = params.toString();

        const isInputActive = (activeInput && document.activeElement === activeInput);
        const cursorPos = activeInput ? (activeInput.selectionStart || activeInput.value.length) : 0;

        fetch(targetUrl.toString(), {
            signal: liveSearchAbort.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Live search request failed');
            return res.text();
        })
        .then(function (html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            let newContainer = null;
            if (container.id) {
                newContainer = doc.getElementById(container.id);
            }
            if (!newContainer) {
                const formAction = form.getAttribute('action');
                let docForm = null;
                if (formAction) {
                    docForm = doc.querySelector(`form[action="${formAction}"]`);
                }
                if (!docForm) {
                    docForm = doc.querySelector('form:has(input[name="search"]), form:has(input[placeholder*="Search" i])') || doc.querySelector('form');
                }
                if (docForm) {
                    newContainer = findSearchTableCard(docForm);
                }
            }
            if (!newContainer) {
                const docTable = doc.querySelector('table');
                if (docTable) newContainer = findTableCard(docTable);
            }

            if (newContainer && container) {
                container.innerHTML = newContainer.innerHTML;
                window.history.replaceState(null, '', targetUrl.toString());

                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(container);
                }

                if (isInputActive) {
                    const refreshedInput = container.querySelector('input[name="search"], input[placeholder*="Search" i]') || activeInput;
                    if (refreshedInput && refreshedInput !== activeInput) {
                        refreshedInput.focus();
                        try { refreshedInput.setSelectionRange(cursorPos, cursorPos); } catch (e) {}
                    }
                }
            } else {
                window.location.href = targetUrl.toString();
            }
        })
        .catch(function (err) {
            if (err.name !== 'AbortError') {
                console.error('Live search error:', err);
            }
        })
        .finally(function () {
            container.style.opacity = '1';
            if (searchIcon && prevIconClass) {
                searchIcon.className = prevIconClass;
            }
        });
    }

    // Auto-search on typing
    document.addEventListener('input', function (e) {
        const input = e.target;
        if (!input || input.tagName !== 'INPUT') return;
        const isSearch = (input.name === 'search' || input.id?.includes('search') || (input.placeholder && input.placeholder.toLowerCase().includes('search')));
        if (!isSearch) return;

        const form = input.form;
        if (!form || (form.method && form.method.toUpperCase() !== 'GET')) return;

        clearTimeout(liveSearchDebounce);
        liveSearchDebounce = setTimeout(function () {
            triggerGlobalLiveSearch(form, input);
        }, 250);
    });

    // Auto-search on select/date change inside search forms (e.g. status filter, date filter, sort_by)
    document.addEventListener('change', function (e) {
        const el = e.target;
        if (!el || (el.tagName !== 'SELECT' && el.type !== 'date')) return;
        const form = el.form;
        if (!form || (form.method && form.method.toUpperCase() !== 'GET')) return;
        if (!form.querySelector('input[name="search"], input[id*="search"], input[placeholder*="Search" i]')) return;

        clearTimeout(liveSearchDebounce);
        triggerGlobalLiveSearch(form, form.querySelector('input[name="search"], input[id*="search"]'));
    });

    // Intercept form submit (pressing Enter or clicking Search/Filter button)
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form || (form.method && form.method.toUpperCase() !== 'GET')) return;
        const searchInput = form.querySelector('input[name="search"], input[id*="search"], input[placeholder*="Search" i]');
        if (!searchInput) return;

        e.preventDefault();
        clearTimeout(liveSearchDebounce);
        triggerGlobalLiveSearch(form, searchInput);
    });
});
</script>
@yield('scripts')
</body>
</html>
