<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    @hasSection('title')
        @yield('title') - {{ config('app.name', 'TRIGESTION') }}
    @else
        {{ config('app.name', 'TRIGESTION') }} - Agua Purificada Premium
    @endif
</title>

<!-- Fuente Outfit (Google Fonts) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Font Awesome (local CDN — solo íconos, sin bloqueo de render) -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

<!-- Tailwind + Alpine + Estilos Trigestion (build local — sin CDN) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

<!-- Utilidades JS Globales -->
<script src="{{ asset('js/pages/_utils.js') }}" defer></script>

@stack('styles')
