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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f2f5f8', 100: '#dde5ed', 200: '#bccadb', 300: '#92a9be',
                            400: '#6685a0', 500: '#4e6a84', 600: '#3d5068',
                            700: '#2e3d50', 800: '#1f2c3a', 900: '#141c26', 950: '#0b1016',
                        },
                        brand: {
                            50: '#e8f7fd', 100: '#c3ebf9', 200: '#8bd9f5', 300: '#4dc6ef',
                            400: '#29bce8', 500: '#15a9d6', 600: '#0e8db5',
                            700: '#0c7296', 800: '#0b5a76', 900: '#0a4a60',
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

    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Outfit', sans-serif; }
        :root {
            --brand-dark: #1f2c3a;
            --brand-mid:  #3d5068;
            --brand-blue: #29bce8;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease both; }
        input::-ms-reveal, input::-ms-clear { display: none; }
        .sidebar-link {
            @apply flex w-full items-center px-3 py-2 text-sm font-medium rounded-lg transition-all gap-3 text-slate-300 hover:bg-navy-700 hover:text-white;
        }
        .sidebar-link.active {
            @apply bg-brand-500/20 text-brand-400 border-l-4 border-brand-400 pl-2;
        }
        .sidebar-section {
            @apply text-[10px] uppercase tracking-widest font-bold text-navy-400 px-3 pt-5 pb-1;
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
