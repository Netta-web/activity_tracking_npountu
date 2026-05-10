<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SupportOps Tracker') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            400: '#c084fc', 500: '#a855f7', 600: '#9333ea',
                            700: '#7e22ce', 800: '#6b21a8', 900: '#581c87',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-bg {
            background: linear-gradient(135deg, #1a1729 0%, #2d1b69 50%, #1a1729 100%);
        }
    </style>
</head>
<body class="antialiased min-h-screen auth-bg flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-brand-500 to-brand-700 rounded-2xl shadow-2xl shadow-brand-900/50 mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">SupportOps Tracker</h1>
            <p class="text-sm text-purple-300 mt-1">Applications Support Management System</p>
        </div>

        <!-- Card -->
        <div class="bg-white/[0.04] backdrop-blur-sm border border-white/[0.08] rounded-3xl p-8 shadow-2xl">
            {{ $slot }}
        </div>

        <p class="text-center text-xs text-white/30 mt-6">
            &copy; {{ date('Y') }} SupportOps Tracker. Enterprise Edition.
        </p>
    </div>
</body>
</html>
