<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.clinic_name', 'Klinik Sehat') }} Admin — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed top-0 left-0 h-full w-64 bg-slate-900 z-30 flex flex-col transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="p-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1 flex-shrink-0 shadow-sm overflow-hidden">
                    <img src="{{ asset('assets/logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <div>
                    <p class="text-white font-bold text-sm">{{ config('app.clinic_name', 'Klinik Sehat') }}</p>
                    <p class="text-slate-400 text-xs">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <p class="text-slate-600 text-xs uppercase font-semibold tracking-widest px-4 py-2">Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.appointments.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Appointment
            </a>

            <a href="{{ route('admin.doctors.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Dokter
            </a>

            <a href="{{ route('admin.schedules.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Jadwal Dokter
            </a>

            <div class="pt-4 border-t border-white/10 mt-4">
                <a href="{{ route('booking') }}" target="_blank"
                   class="sidebar-link">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Halaman Pasien
                </a>
            </div>
        </nav>

        {{-- User --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">{{ mb_substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-slate-200 px-4 lg:px-6 py-4 flex items-center gap-4 sticky top-0 z-10">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100">
                <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1">
                <h1 class="text-lg font-bold text-slate-900">@yield('title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                <nav class="text-xs text-slate-400 mt-0.5">@yield('breadcrumb')</nav>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <span class="hidden sm:block text-xs text-slate-400">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </header>

        {{-- Page --}}
        <main class="flex-1 p-4 lg:p-6">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
