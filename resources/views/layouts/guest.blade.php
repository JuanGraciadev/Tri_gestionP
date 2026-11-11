<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen bg-slate-50 relative flex items-center justify-center p-4 selection:bg-sky-500 selection:text-white">
    {{-- Fondo decorativo (blobs) --}}
    <div class="bg-blobs" aria-hidden="true">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="blob-3"></div>
    </div>

    @hasSection('content')
        <div class="relative z-10 w-full flex items-center justify-center">
            @yield('content')
        </div>
    @else
        <div class="relative z-10 w-full sm:max-w-md bg-white/85 backdrop-blur-md p-8 rounded-3xl shadow-xl border border-white/60">
            <div class="flex justify-center mb-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-600 flex items-center justify-center text-white text-lg shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-droplet"></i>
                    </div>
                    <span class="text-xl font-black text-slate-800 tracking-tight">TRIGESTION</span>
                </a>
            </div>
            {{ $slot ?? '' }}
        </div>
    @endif

    @include('layouts.partials.alertas')

    @stack('scripts')
</body>
</html>
