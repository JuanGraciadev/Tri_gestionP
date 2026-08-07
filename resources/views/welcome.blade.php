<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRIGESTION - Agua Purificada Premium</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        },
                        colors: {
                            cobalt: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                200: '#bfdbfe',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                800: '#1e40af',
                                900: '#0f172a',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        /* INTENSE BLUE LIQUID BACKGROUND */
        .hero-container {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 35%, #e0f7fa 70%, #ffffff 100%), 
                        url('{{ asset("images/water_splash_bg.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .text-intense-blue {
            color: #1d4ed8;
        }

        .btn-intense-blue {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
            box-shadow: 0 10px 25px -5px rgba(29, 78, 216, 0.4);
        }

        .btn-intense-blue:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 15px 30px -5px rgba(29, 78, 216, 0.55);
        }

        .minimal-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.08), 0 18px 36px -18px rgba(37, 99, 235, 0.12);
        }
    </style>
</head>
<body class="text-slate-900 antialiased min-h-screen flex flex-col selection:bg-cobalt-600 selection:text-white">

    <!-- HERO SECTION -->
    <div class="hero-container relative min-h-screen flex flex-col justify-between">
        
        <!-- HEADER / NAVIGATION -->
        <header class="w-full max-w-7xl mx-auto px-6 lg:px-12 py-8 flex items-center justify-between z-20">
            
            <!-- OFFICIAL TRIGESTION BRAND LOGO FROM RESOURCES/IMG -->
            <a href="{{ url('/') }}" class="flex items-center group">
                <img src="{{ asset('img/Trigestion.png') }}" 
                     alt="TRIGESTION Logo" 
                     class="h-10 sm:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                     onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
            </a>

            <!-- NAVIGATION LINKS -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-700">
                <a href="#beneficios" class="hover:text-cobalt-600 transition-colors">Beneficios</a>
                <a href="#productos" class="hover:text-cobalt-600 transition-colors">Productos</a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-extrabold text-cobalt-700 hover:text-cobalt-900 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-slate-700 hover:text-cobalt-600 transition-colors">
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="btn-intense-blue text-white font-extrabold px-6 py-3 rounded-full text-sm inline-flex items-center gap-2 transform hover:-translate-y-0.5 transition-all">
                                <span>Registrarse</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                </svg>
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>

            <!-- MOBILE NAV TOGGLE -->
            <div class="md:hidden">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-black text-cobalt-700">Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-intense-blue text-white text-xs font-bold px-4 py-2 rounded-full">Registro</a>
                    @endauth
                @endif
            </div>

        </header>

        <!-- HERO MAIN CONTENT -->
        <main class="w-full max-w-7xl mx-auto px-6 lg:px-12 py-12 lg:py-24 flex-1 flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-16 z-10">
            
            <!-- LEFT COLUMN: TEXT & CTA -->
            <div class="w-full lg:w-7/12 flex flex-col items-start text-left">
                
                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 bg-white/90 border border-sky-200 text-cobalt-600 px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest mb-8 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-cobalt-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>FRESCURA Y PUREZA</span>
                </div>

                <!-- MAIN TITLE -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1] mb-6">
                    La mejor <span class="text-intense-blue">hidratación</span> para tu familia.
                </h1>

                <!-- DESCRIPTION -->
                <p class="text-slate-600 text-base sm:text-lg max-w-lg mb-10 font-medium leading-relaxed">
                    Agua purificada de máxima calidad, directo a tu puerta. Realiza tu pedido en segundos con la plataforma Trigestion.
                </p>

                <!-- ACTION BUTTONS -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto">
                    <!-- Primary Button -->
                    <a href="#productos" 
                       class="btn-intense-blue text-white text-base font-extrabold px-8 py-4 rounded-2xl inline-flex items-center justify-center gap-3 transform hover:-translate-y-0.5 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Haz tu pedido</span>
                    </a>

                    <!-- Secondary Button -->
                    <a href="#beneficios" 
                       class="bg-white hover:bg-slate-50 text-slate-800 border border-slate-200/90 text-base font-bold px-8 py-4 rounded-2xl inline-flex items-center justify-center gap-2 shadow-sm transform hover:-translate-y-0.5 transition-all">
                        <span>Conoce más</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: FLOATING CARD WITH OFFICIAL LOGO IMAGE -->
            <div class="w-full lg:w-5/12 flex items-center justify-center">
                <div class="w-full max-w-md">
                    <!-- Floating Card -->
                    <div class="minimal-card rounded-[2.5rem] p-12 lg:p-14 border border-white flex flex-col items-center justify-center text-center min-h-[440px] relative">
                        
                        <!-- OFFICIAL LOGO IMAGE CENTERED -->
                        <div class="flex items-center justify-center mb-10">
                            <img src="{{ asset('img/Trigestion.png') }}" 
                                 alt="TRIGESTION" 
                                 class="h-14 lg:h-16 w-auto object-contain"
                                 onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
                        </div>

                        <!-- CARD TEXT -->
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-2">
                            Agua Premium
                        </h3>
                        <p class="text-intense-blue font-extrabold text-lg lg:text-xl tracking-wide mb-12">
                            100% Purificada
                        </p>

                        <!-- CAROUSEL PAGINATION DOTS -->
                        <div class="flex items-center justify-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-300"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-300"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-cobalt-600"></span>
                        </div>

                    </div>
                </div>
            </div>

        </main>

        <!-- BOTTOM WAVE DIVIDER -->
        <div class="w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-12 lg:h-16 text-white" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,90 350,-40 500,40 C650,120 900,10 1200,40 L1200,120 L0,120 Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- BENEFICIOS SECTION -->
    <section id="beneficios" class="bg-white py-24 lg:py-32 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-cobalt-600 font-extrabold text-xs uppercase tracking-widest mb-3 block">
                    BENEFICIOS EXCLUSIVOS
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">
                    Calidad superior en cada gota
                </h2>
            </div>

            <!-- CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
                
                <!-- Benefit 1 -->
                <div class="bg-slate-50/70 rounded-3xl p-10 border border-slate-100 hover:border-cobalt-200 transition-all duration-300 hover:shadow-xl hover:shadow-cobalt-600/5 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-cobalt-600 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-cobalt-600/30">
                        💧
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3">100% Purificada</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Ósmosis inversa avanzada de 7 fases que remueve impurezas conservando la ligereza y frescura óptima.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-slate-50/70 rounded-3xl p-10 border border-slate-100 hover:border-cobalt-200 transition-all duration-300 hover:shadow-xl hover:shadow-cobalt-600/5 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-cobalt-600 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-cobalt-600/30">
                        ⚡
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Entrega Express</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Recibe tus botellones directamente en la puerta de tu hogar u oficina en tiempo récord.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-slate-50/70 rounded-3xl p-10 border border-slate-100 hover:border-cobalt-200 transition-all duration-300 hover:shadow-xl hover:shadow-cobalt-600/5 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-cobalt-600 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-cobalt-600/30">
                        🌱
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Envases Ecológicos</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Botellones retornables de alta durabilidad libres de BPA, respetuosos con el medio ambiente.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- PRODUCTOS SECTION -->
    <section id="productos" class="bg-slate-50 py-24 lg:py-32 px-6 lg:px-12 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-cobalt-600 font-extrabold text-xs uppercase tracking-widest mb-3 block">
                    NUESTROS PRODUCTOS
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">
                    Formatos para cada necesidad
                </h2>
            </div>

            <!-- PRODUCT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
                
                <!-- Product 1 -->
                <div class="bg-white rounded-3xl p-9 shadow-sm border border-slate-200/90 flex flex-col justify-between hover:shadow-2xl hover:shadow-cobalt-600/10 transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="w-full h-48 bg-cobalt-50 rounded-2xl flex items-center justify-center mb-8 text-6xl">
                            🚰
                        </div>
                        <span class="text-[11px] font-black text-cobalt-600 uppercase tracking-widest">Recarga Clásica</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1 mb-3">Bidón 20 Litros</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-8">Agua purificada premium para dispensador o bomba manual.</p>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio</span>
                            <span class="text-3xl font-black text-slate-900">$12.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="btn-intense-blue text-white font-extrabold px-6 py-3 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-3xl p-9 shadow-sm border border-slate-200/90 flex flex-col justify-between hover:shadow-2xl hover:shadow-cobalt-600/10 transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="w-full h-48 bg-sky-50 rounded-2xl flex items-center justify-center mb-8 text-6xl">
                            ❄️
                        </div>
                        <span class="text-[11px] font-black text-cobalt-600 uppercase tracking-widest">Equipo Completo</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1 mb-3">Dispensador Frío/Calor</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-8">Dispensador moderno con control electrónico de temperatura.</p>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio</span>
                            <span class="text-3xl font-black text-slate-900">$85.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="btn-intense-blue text-white font-extrabold px-6 py-3 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-3xl p-9 shadow-sm border border-slate-200/90 flex flex-col justify-between hover:shadow-2xl hover:shadow-cobalt-600/10 transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="w-full h-48 bg-indigo-50 rounded-2xl flex items-center justify-center mb-8 text-6xl">
                            📦
                        </div>
                        <span class="text-[11px] font-black text-cobalt-600 uppercase tracking-widest">Pack Ahorro</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-1 mb-3">Pack Familiar 3x20L</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-8">Combo de 3 recargas con envío prioritario gratis.</p>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio</span>
                            <span class="text-3xl font-black text-slate-900">$30.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="btn-intense-blue text-white font-extrabold px-6 py-3 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-white py-16 px-6 lg:px-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
            
            <div class="flex items-center">
                <img src="{{ asset('img/Trigestion.png') }}" 
                     alt="TRIGESTION Logo" 
                     class="h-8 w-auto object-contain brightness-200 filter"
                     onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
            </div>

            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} TRIGESTION. Todos los derechos reservados.
            </p>

            <div class="flex items-center gap-8 text-sm font-bold text-slate-300">
                <a href="#beneficios" class="hover:text-cobalt-400 transition-colors">Beneficios</a>
                <a href="#productos" class="hover:text-cobalt-400 transition-colors">Productos</a>
                <a href="{{ route('login') }}" class="hover:text-cobalt-400 transition-colors">Acceso</a>
            </div>

        </div>
    </footer>

</body>
</html>
