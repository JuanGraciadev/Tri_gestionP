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

<!-- Fonts: Google Fonts Outfit -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Font Awesome 6.5.1 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Tailwind CDN + Config -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Outfit"', 'sans-serif'],
                },
                colors: {
                    trigestion: {
                        50:  '#f0f9ff',
                        100: '#e0f2fe',
                        200: '#bae6fd',
                        300: '#7dd3fc',
                        400: '#38bdf8',
                        500: '#0ea5e9',
                        600: '#0284c7',
                        700: '#00669e',
                        800: '#005282',
                        900: '#082f49',
                    }
                },
                animation: {
                    'fade': 'fadeIn 0.5s ease-out',
                },
                keyframes: {
                    fadeIn: {
                        '0%': { opacity: '0', transform: 'translateY(10px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    }
                }
            }
        }
    }
</script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Estilos Base Tri-gestión -->
<link rel="stylesheet" href="{{ asset('css/trigestion.css') }}">

@stack('styles')

