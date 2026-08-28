<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CAPACIPRINT') — Intelligent Print Routing</title>
    <meta name="description" content="@yield('meta_description', 'CAPACIPRINT – Intelligent capacity-based print routing system.')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cyber: {
                            base: '#0B1118',
                            dark: '#0D1520',
                            card: '#111A24',
                            border: '#1E293B',
                            hover: '#182433',
                            muted: '#64748B',
                        },
                        navy: {
                            50: '#f2f5f8', 100: '#dde5ed', 200: '#bccadb', 300: '#92a9be',
                            400: '#6685a0', 500: '#4e6a84', 600: '#3d5068',
                            700: '#2e3d50', 800: '#1f2c3a', 900: '#141c26', 950: '#0b1016',
                        },
                        brand: {
                            50: '#e8f7fd', 100: '#c3ebf9', 200: '#8bd9f5', 300: '#4dc6ef',
                            400: '#00F2FE', 500: '#00D2E0', 600: '#00B4C4',
                            700: '#0090A0', 800: '#006E7D', 900: '#004C5A',
                        },
                        cyan: {
                            400: '#22d3ee', 500: '#06b6d4', 600: '#0891b2'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script>
        // Check theme preference or default to dark
        if (localStorage.theme === 'light') {
            document.documentElement.classList.add('light-theme');
            document.documentElement.classList.remove('dark-theme');
        } else {
            document.documentElement.classList.add('dark-theme');
            document.documentElement.classList.remove('light-theme');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Outfit', sans-serif; }
        :root {
            --brand-dark: #1f2c3a;
            --brand-mid:  #3d5068;
            --brand-blue: #29bce8;
        }

        /* Dark Theme Variables (Default) */
        html.dark-theme, html:not(.light-theme) {
            --bg-base: #0B1118;
            --bg-surface: #0D1520;
            --bg-card: #111A24;
            --bg-card-sub: #0D1520;
            --border-main: rgba(30, 41, 59, 0.8);
            --border-sub: rgba(30, 41, 59, 0.6);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --text-sub: #64748B;
        }

        /* Eye-Friendly Soft Light Theme Variables */
        html.light-theme {
            --bg-base: #F1F5F9;
            --bg-surface: #F8FAFC;
            --bg-card: #FFFFFF;
            --bg-card-sub: #F1F5F9;
            --border-main: #E2E8F0;
            --border-sub: #CBD5E1;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --text-sub: #94A3B8;
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
           when html.dark-theme (or not light-theme) is active.
           This prevents cards, tables, inputs from staying "white"
           when the user switches to dark mode.
           ══════════════════════════════════════════════════════════════ */

        /* Card / container backgrounds */
        html.dark-theme main .bg-white,
        html:not(.light-theme) main .bg-white {
            background-color: #111A24 !important;
        }
        html.dark-theme main .bg-slate-50,
        html:not(.light-theme) main .bg-slate-50 {
            background-color: #0D1520 !important;
        }
        html.dark-theme main .bg-slate-100,
        html:not(.light-theme) main .bg-slate-100 {
            background-color: #0D1520 !important;
        }

        /* Colored tint backgrounds (report category icons etc.) */
        html.dark-theme main .bg-blue-50,
        html:not(.light-theme) main .bg-blue-50 { background-color: rgba(59,130,246,0.1) !important; }
        html.dark-theme main .bg-emerald-50,
        html:not(.light-theme) main .bg-emerald-50 { background-color: rgba(16,185,129,0.1) !important; }
        html.dark-theme main .bg-purple-50,
        html:not(.light-theme) main .bg-purple-50 { background-color: rgba(168,85,247,0.1) !important; }
        html.dark-theme main .bg-amber-50,
        html:not(.light-theme) main .bg-amber-50 { background-color: rgba(245,158,11,0.1) !important; }
        html.dark-theme main .bg-red-50,
        html:not(.light-theme) main .bg-red-50 { background-color: rgba(239,68,68,0.1) !important; }
        html.dark-theme main .bg-teal-50,
        html:not(.light-theme) main .bg-teal-50 { background-color: rgba(20,184,166,0.1) !important; }
        html.dark-theme main .bg-cyan-50,
        html:not(.light-theme) main .bg-cyan-50 { background-color: rgba(6,182,212,0.1) !important; }
        html.dark-theme main .bg-indigo-50,
        html:not(.light-theme) main .bg-indigo-50 { background-color: rgba(99,102,241,0.1) !important; }

        /* Border colors */
        html.dark-theme main .border-slate-100,
        html:not(.light-theme) main .border-slate-100 {
            border-color: rgba(30,41,59,0.8) !important;
        }
        html.dark-theme main .border-slate-200,
        html:not(.light-theme) main .border-slate-200 {
            border-color: rgba(30,41,59,0.6) !important;
        }
        html.dark-theme main .border-slate-300,
        html:not(.light-theme) main .border-slate-300 {
            border-color: rgba(30,41,59,0.5) !important;
        }

        /* Dividers */
        html.dark-theme main .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html:not(.light-theme) main .divide-slate-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: rgba(30,41,59,0.6) !important;
        }

        /* Text colors — dark headings become light */
        html.dark-theme main .text-navy-900,
        html:not(.light-theme) main .text-navy-900 {
            color: #F8FAFC !important;
        }
        html.dark-theme main .text-slate-900,
        html:not(.light-theme) main .text-slate-900 {
            color: #F1F5F9 !important;
        }
        html.dark-theme main .text-slate-800,
        html:not(.light-theme) main .text-slate-800 {
            color: #E2E8F0 !important;
        }
        html.dark-theme main .text-slate-700,
        html:not(.light-theme) main .text-slate-700 {
            color: #CBD5E1 !important;
        }
        html.dark-theme main .text-slate-600,
        html:not(.light-theme) main .text-slate-600 {
            color: #94A3B8 !important;
        }

        /* Hover states for dark mode — light hovers become dark */
        html.dark-theme main .hover\:bg-slate-50:hover,
        html:not(.light-theme) main .hover\:bg-slate-50:hover,
        html.dark-theme main .hover\:bg-slate-100:hover,
        html:not(.light-theme) main .hover\:bg-slate-100:hover,
        html.dark-theme main .hover\:bg-slate-50\/50:hover,
        html:not(.light-theme) main .hover\:bg-slate-50\/50:hover {
            background-color: #182433 !important;
        }
        html.dark-theme main .hover\:shadow-md:hover,
        html:not(.light-theme) main .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 2px 4px -2px rgba(0,0,0,0.2) !important;
        }

        /* Form inputs in dark mode */
        html.dark-theme main input,
        html.dark-theme main select,
        html.dark-theme main textarea,
        html:not(.light-theme) main input,
        html:not(.light-theme) main select,
        html:not(.light-theme) main textarea {
            background-color: #0D1520 !important;
            border-color: rgba(30,41,59,0.8) !important;
            color: #F8FAFC !important;
        }
        html.dark-theme main input::placeholder,
        html:not(.light-theme) main input::placeholder {
            color: #64748B !important;
        }

        /* ══════════════════════════════════════════════════════════════
           GLOBAL LIGHT-THEME OVERRIDES
           Forces all hardcoded dark-mode Tailwind classes to switch
           when html.light-theme is active. This prevents cards, tables,
           headers, and text from staying "stuck" in dark mode.
           ══════════════════════════════════════════════════════════════ */
        html.light-theme .bg-\[\#111A24\],
        html.light-theme .bg-\[\#0D1520\],
        html.light-theme .bg-\[\#0B1118\] {
            background-color: #FFFFFF !important;
        }

        html.light-theme .bg-\[\#0D1520\]\/80 {
            background-color: #F8FAFC !important;
        }

        html.light-theme .bg-\[\#0B1118\]\/80 {
            background-color: #F1F5F9 !important;
        }

        /* Text colors — scoped to dark-hex containers only */
        html.light-theme .bg-\[\#111A24\] .text-white,
        html.light-theme .bg-\[\#0D1520\] .text-white,
        html.light-theme .bg-\[\#0B1118\] .text-white,
        html.light-theme .bg-\[\#111A24\].text-white,
        html.light-theme .bg-\[\#0D1520\].text-white,
        html.light-theme .bg-\[\#0B1118\].text-white,
        html.light-theme main .text-white:not([class*="bg-gradient"]):not([class*="bg-cyan"]):not([class*="bg-brand"]):not([class*="bg-navy"]):not([class*="bg-red"]):not([class*="bg-emerald"]):not([class*="bg-blue"]):not([class*="from-"]) {
            color: #1E293B !important;
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
            color: #1E293B !important;
        }

        html.light-theme .from-navy-900.to-navy-800 p,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] p {
            color: #64748B !important;
        }

        html.light-theme .from-navy-900.to-navy-800 .bg-navy-800,
        html.light-theme [class*="from-navy-900"][class*="to-navy-800"] .bg-navy-800 {
            background-color: #F1F5F9 !important;
            color: #1E293B !important;
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

        /* Border colors */
        html.light-theme .border-slate-800\/80,
        html.light-theme .border-slate-800,
        html.light-theme .border-slate-800\/60,
        html.light-theme .border-slate-700 {
            border-color: #E2E8F0 !important;
        }

        /* Dividers */
        html.light-theme .divide-slate-800\/60 > :not([hidden]) ~ :not([hidden]) {
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
            color: #94A3B8 !important;
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

        /* Light-theme soft diffused shadows & low-glare styling */
        html.light-theme .shadow-2xl {
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.03) !important;
        }
        html.light-theme .shadow-xl {
            box-shadow: 0 6px 16px -3px rgba(15, 23, 42, 0.04), 0 4px 6px -4px rgba(15, 23, 42, 0.03) !important;
        }
        html.light-theme .shadow-lg {
            box-shadow: 0 4px 10px -2px rgba(15, 23, 42, 0.04), 0 2px 4px -2px rgba(15, 23, 42, 0.02) !important;
        }
        html.light-theme .shadow-md {
            box-shadow: 0 2px 6px -1px rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02) !important;
        }

        html.light-theme .blur-3xl {
            opacity: 0.1 !important;
        }

        /* Hover border accent colors should keep their color in light mode */
        html.light-theme .hover\:border-cyan-500\/40:hover { border-color: rgba(6,182,212,0.4) !important; }
        html.light-theme .hover\:border-cyan-500\/30:hover { border-color: rgba(6,182,212,0.3) !important; }
        html.light-theme .hover\:border-indigo-500\/40:hover { border-color: rgba(99,102,241,0.4) !important; }
        html.light-theme .hover\:border-indigo-500\/30:hover { border-color: rgba(99,102,241,0.3) !important; }
        html.light-theme .hover\:border-emerald-500\/40:hover { border-color: rgba(16,185,129,0.4) !important; }
        html.light-theme .hover\:border-emerald-500\/30:hover { border-color: rgba(16,185,129,0.3) !important; }
        html.light-theme .hover\:border-amber-500\/30:hover { border-color: rgba(245,158,11,0.3) !important; }
        html.light-theme .hover\:border-purple-500\/40:hover { border-color: rgba(168,85,247,0.4) !important; }

        /* Selection color for light theme */
        html.light-theme .selection\:bg-cyan-500\/30::selection { background-color: rgba(6,182,212,0.15) !important; }
        html.light-theme .selection\:text-cyan-200::selection { color: #0E7490 !important; }

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
    </style>

    @yield('head')
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50">
@yield('body')

{{-- Alpine.js --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
