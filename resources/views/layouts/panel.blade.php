<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen bg-slate-50 relative selection:bg-sky-500 selection:text-white"
      x-data="{ mobileSidebar: false }">

    {{-- Fondo decorativo (blobs) --}}
    <div class="bg-blobs" aria-hidden="true">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="blob-3"></div>
    </div>

    <div class="flex min-h-screen relative z-10">
        {{-- Sidebar según rol --}}
        @include('layouts.partials.sidebar')

        {{-- Contenedor principal con margen adaptado al sidebar fijo --}}
        <div class="flex-1 flex flex-col min-w-0 lg:pl-[280px] transition-all duration-300">
            @php
                $pageTitle = $title ?? (trim($__env->yieldContent('title')) ?: 'TRIGESTION');
                $pageSubtitle = $subtitle ?? (trim($__env->yieldContent('subtitle')) ?: '');
            @endphp

            @include('layouts.partials.topbar', ['title' => $pageTitle, 'subtitle' => $pageSubtitle])

            <main class="flex-1 p-4 sm:p-8 md:p-10 min-w-0 space-y-8 animate-fade">
                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    @include('layouts.partials.alertas')

    @stack('scripts')
</body>
</html>

