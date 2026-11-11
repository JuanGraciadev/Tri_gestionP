<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('layouts.partials.head')
</head>
<body class="font-sans antialiased text-slate-800 bg-white selection:bg-sky-500 selection:text-white">
    @yield('content')

    @include('layouts.partials.alertas')

    @stack('scripts')
</body>
</html>

