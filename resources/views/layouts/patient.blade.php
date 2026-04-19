<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Booking jadwal dokter online — cepat, mudah, langsung terhubung ke WhatsApp Anda.">
    <title>{{ config('app.clinic_name', 'Klinik Sehat') }} — @yield('title', 'Booking Online')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-hero {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 40%, #075985 100%);
        }

        .card-glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 min-h-screen">

    {{-- Hero Header --}}
    <header class="gradient-hero text-white">
        <div class="max-w-lg mx-auto px-4 py-5 flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center p-1 overflow-hidden shadow-sm">
                <img src="{{ asset('assets/logo.png') }}" class="w-full h-full object-contain drop-shadow-md" alt="Logo Klinik">
            </div>
            <div>
                <h1 class="text-lg font-bold leading-tight">{{ config('app.clinic_name', 'Klinik Sehat') }}</h1>
                <p class="text-sky-200 text-xs">Booking Dokter Online</p>
            </div>
            <div class="ml-auto text-right hidden sm:block">
                <p class="text-sky-200 text-xs">{{ config('app.clinic_phone', '') }}</p>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="max-w-lg mx-auto px-4 py-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center py-6 text-slate-400 text-xs">
        <p>{{ config('app.clinic_name', 'Klinik Sehat') }} &copy; {{ date('Y') }}</p>
        <p class="mt-1">Powered by Hafarou Dev</p>
    </footer>

    @stack('scripts')
</body>

</html>