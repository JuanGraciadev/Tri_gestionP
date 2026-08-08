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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0095e8',
                            600: '#007cc7',
                            700: '#0062a3',
                            800: '#004f82',
                            900: '#072b48',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-slate-50 text-slate-900 antialiased selection:bg-trigestion-500 selection:text-white">

    <!-- REFRESHING HERO SECTION WITH WATER BACKGROUND -->
    <div class="relative min-h-screen flex flex-col justify-between bg-cover bg-center bg-no-repeat"
         style="background-image: linear-gradient(180deg, rgba(240, 249, 255, 0.85) 0%, rgba(224, 242, 254, 0.6) 50%, rgba(255, 255, 255, 0.95) 100%), url('{{ asset("images/water_splash_bg.png") }}');">
        
        <!-- FLOATING MINIMALIST NAVBAR -->
        <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-sky-100/70 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4 flex items-center justify-between">
                
                <!-- BRAND LOGO -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/tr.png') }}" 
                         alt="TRIGESTION Logo" 
                         class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                         onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
                    <span class="text-xl font-extrabold text-slate-900 tracking-tight">TRIGESTION</span>
                </a>

                <!-- NAV MENU & ACTIONS -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#beneficios" class="hover:text-trigestion-500 transition-colors">Beneficios</a>
                    <a href="#calculadora" class="hover:text-trigestion-500 transition-colors">Calculadora</a>
                    <a href="#productos" class="hover:text-trigestion-500 transition-colors">Productos</a>
                    <a href="#faq" class="hover:text-trigestion-500 transition-colors">Preguntas</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-extrabold text-trigestion-600 hover:text-trigestion-800 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-slate-700 hover:text-trigestion-500 transition-colors">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-full text-sm shadow-md shadow-trigestion-500/20 hover:shadow-trigestion-500/35 transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                    <span>Registrarse</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>

                <!-- MOBILE ACTION -->
                <div class="md:hidden">
                    <a href="{{ route('register') }}" class="bg-trigestion-500 text-white text-xs font-bold px-4 py-2 rounded-full shadow-sm">
                        Registro
                    </a>
                </div>

            </div>
        </header>

        <!-- HERO MAIN CONTENT GRID -->
        <main class="max-w-7xl mx-auto px-6 lg:px-12 py-12 lg:py-24 flex-1 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center z-10">
            
            <!-- LEFT COLUMN -->
            <div class="lg:col-span-7 flex flex-col items-start text-left">
                
                <!-- BADGE -->
                <div class="inline-flex items-center gap-2 bg-white/90 backdrop-blur-md border border-sky-200/80 text-trigestion-600 px-4 py-1.5 rounded-full text-xs font-extrabold tracking-wider uppercase mb-6 shadow-sm">
                    <span class="text-xs">💧</span>
                    <span>PUREZA CERTIFICADA 100% FRESCA</span>
                </div>

                <!-- MAIN TITLE -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12] mb-6">
                    La experiencia de agua pura que tu cuerpo <span class="text-trigestion-500">merece.</span>
                </h1>

                <!-- DESCRIPTION -->
                <p class="text-slate-600 text-base sm:text-lg max-w-xl mb-8 font-medium leading-relaxed">
                    Hidratación ligera y cristalina a domicilio. Pide tus botellones retornables en segundos y disfruta de la mejor calidad con la comodidad que buscas.
                </p>

                <!-- STATS BAR -->
                <div class="flex items-center gap-6 sm:gap-8 bg-white/80 backdrop-blur-md px-5 py-3.5 rounded-2xl border border-sky-100 shadow-sm mb-10">
                    <div>
                        <span class="text-lg sm:text-xl font-black text-trigestion-500 block">+15.000L</span>
                        <span class="text-xs font-bold text-slate-400">Entregados este mes</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div>
                        <span class="text-lg sm:text-xl font-black text-trigestion-500 block">7 Fases</span>
                        <span class="text-xs font-bold text-slate-400">Ósmosis Inversa</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div>
                        <span class="text-lg sm:text-xl font-black text-trigestion-500 block">4.9 ⭐</span>
                        <span class="text-xs font-bold text-slate-400">Calificación Clientes</span>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto">
                    <a href="{{ route('register') }}" 
                       class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-8 py-4 rounded-2xl text-base shadow-lg shadow-trigestion-500/25 hover:shadow-trigestion-500/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Haz tu pedido</span>
                    </a>

                    <a href="#calculadora" 
                       class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold px-8 py-4 rounded-2xl text-base shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Calcula tu consumo</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: MINIMALIST FLOATING CARD -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-md">
                    
                    <!-- MINIMALIST SHOWCASE CARD -->
                    <div class="bg-white/90 backdrop-blur-2xl border border-white p-10 lg:p-12 rounded-[2.5rem] shadow-xl shadow-sky-900/10 text-center flex flex-col items-center justify-center min-h-[440px] relative overflow-hidden">
                        
                        <!-- SLIDE 1 -->
                        <div class="carousel-slide flex flex-col items-center w-full" id="slide-0">
                            <div class="w-20 h-20 bg-sky-50 border border-sky-100 rounded-3xl flex items-center justify-center text-4xl mb-6 shadow-sm">🚰</div>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Agua Premium</h3>
                            <p class="text-trigestion-500 font-bold text-base tracking-wide mb-6">100% Purificada (Bidón 20L)</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md transition-transform hover:scale-105 mb-6">
                                Recarga • $12.000
                            </a>
                        </div>

                        <!-- SLIDE 2 -->
                        <div class="carousel-slide hidden flex-col items-center w-full" id="slide-1">
                            <div class="w-20 h-20 bg-blue-50 border border-blue-100 rounded-3xl flex items-center justify-center text-4xl mb-6 shadow-sm">❄️</div>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Dispensador</h3>
                            <p class="text-trigestion-500 font-bold text-base tracking-wide mb-6">Frío / Calor Electrónico</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md transition-transform hover:scale-105 mb-6">
                                Equipo • $85.000
                            </a>
                        </div>

                        <!-- SLIDE 3 -->
                        <div class="carousel-slide hidden flex-col items-center w-full" id="slide-2">
                            <div class="w-20 h-20 bg-indigo-50 border border-indigo-100 rounded-3xl flex items-center justify-center text-4xl mb-6 shadow-sm">📦</div>
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">Pack Ahorro</h3>
                            <p class="text-trigestion-500 font-bold text-base tracking-wide mb-6">3 Recargas 20L + Envío Gratis</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md transition-transform hover:scale-105 mb-6">
                                Pack • $30.000
                            </a>
                        </div>

                        <!-- BRAND LOGO FOOTER IN CARD -->
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <img src="{{ asset('img/tr.png') }}" 
                                 alt="TRIGESTION" 
                                 class="h-5 w-auto object-contain"
                                 onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
                            <span class="font-extrabold text-slate-800 text-xs tracking-wider uppercase">TRIGESTION</span>
                        </div>

                        <!-- CAROUSEL DOTS -->
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="setSlide(0)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-trigestion-500 transition-all duration-300"></button>
                            <button onclick="setSlide(1)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-sky-200 transition-all duration-300"></button>
                            <button onclick="setSlide(2)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-sky-200 transition-all duration-300"></button>
                        </div>

                    </div>
                </div>
            </div>

        </main>

        <!-- WAVE DIVIDER -->
        <div class="w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-12 lg:h-16 text-white" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C150,90 350,-40 500,40 C650,120 900,10 1200,40 L1200,120 L0,120 Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- CALCULADORA DE HIDRATACIÓN WIDGET -->
    <section id="calculadora" class="bg-white py-20 lg:py-28 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">
            
            <div class="bg-gradient-to-br from-trigestion-500 via-trigestion-600 to-trigestion-700 rounded-[2.5rem] p-8 lg:p-14 text-white shadow-xl shadow-trigestion-500/20 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-7">
                    <span class="inline-block bg-white/20 text-white font-extrabold text-xs uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
                        CALCULADORA DE HIDRATACIÓN
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-4 tracking-tight">
                        ¿Cuánta agua necesita tu hogar?
                    </h2>
                    <p class="text-sky-100 text-base leading-relaxed mb-8 font-medium">
                        Calcula fácilmente la cantidad de botellones de 20 Litros recomendados al mes según el número de integrantes de tu casa u oficina.
                    </p>

                    <div class="bg-white/15 backdrop-blur-md border border-white/30 rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-sm">Personas en el hogar:</span>
                            <span id="people-count" class="text-xl font-black text-sky-200">3 Personas</span>
                        </div>
                        <input type="range" id="people-slider" min="1" max="10" value="3" 
                               class="w-full h-2 bg-sky-200/50 rounded-lg appearance-none cursor-pointer accent-sky-300"
                               oninput="updateCalc()">
                    </div>
                </div>

                <div class="lg:col-span-5 flex justify-center">
                    <div class="bg-white text-slate-900 rounded-3xl p-8 w-full max-w-sm text-center shadow-lg">
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-2">Consumo Recomendado</span>
                        <div id="result-bottles" class="text-4xl font-black text-trigestion-500 mb-1">4 Botellones</div>
                        <div id="result-liters" class="text-xs font-bold text-slate-500 mb-6">80 Litros de agua pura al mes</div>

                        <div class="pt-4 border-t border-slate-100 mb-6">
                            <span class="text-xs font-bold text-slate-400 block">Inversión mensual estimada</span>
                            <span id="result-price" class="text-2xl font-black text-slate-900">$48.000 / mes</span>
                        </div>

                        <a href="{{ route('register') }}" class="block w-full bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold py-3.5 px-4 rounded-xl text-sm shadow-md transition-all">
                            Pedir Plan Sugerido
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- BENEFICIOS SECTION -->
    <section id="beneficios" class="bg-slate-50 py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest mb-3 block">
                    BENEFICIOS EXCLUSIVOS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Calidad superior en cada gota
                </h2>
            </div>

            <!-- CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-trigestion-500/20">
                        💧
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3">100% Purificada</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Ósmosis inversa avanzada de 7 fases que remueve impurezas conservando la ligereza y frescura óptima.
                    </p>
                </div>

                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-trigestion-500/20">
                        ⚡
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3">Entrega Express</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        Recibe tus botellones directamente en la puerta de tu hogar u oficina en tiempo récord.
                    </p>
                </div>

                <div class="bg-white p-8 lg:p-10 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl font-bold mb-8 shadow-md shadow-trigestion-500/20">
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
    <section id="productos" class="bg-white py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest mb-3 block">
                    NUESTROS PRODUCTOS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Formatos para cada necesidad
                </h2>
            </div>

            <!-- PRODUCT CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Product 1 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative">
                    <span class="absolute top-6 right-6 bg-trigestion-500 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full">Más Vendido</span>
                    <div>
                        <div class="w-full h-44 bg-trigestion-50 rounded-2xl flex items-center justify-center mb-6 text-6xl">🚰</div>
                        <span class="text-[11px] font-black text-trigestion-600 uppercase tracking-widest">Recarga Clásica</span>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-2">Bidón 20 Litros</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-6">Agua purificada premium para dispensador o bomba manual.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Recarga</span>
                            <span class="text-2xl font-black text-slate-900">$12.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-full h-44 bg-sky-50 rounded-2xl flex items-center justify-center mb-6 text-6xl">❄️</div>
                        <span class="text-[11px] font-black text-trigestion-600 uppercase tracking-widest">Equipo Completo</span>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-2">Dispensador Frío/Calor</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-6">Dispensador moderno con control electrónico de temperatura.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Venta</span>
                            <span class="text-2xl font-black text-slate-900">$85.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-sky-200 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative">
                    <span class="absolute top-6 right-6 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full">Ahorra 15%</span>
                    <div>
                        <div class="w-full h-44 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 text-6xl">📦</div>
                        <span class="text-[11px] font-black text-trigestion-600 uppercase tracking-widest">Pack Ahorro</span>
                        <h3 class="text-2xl font-extrabold text-slate-900 mt-1 mb-2">Pack Familiar 3x20L</h3>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium mb-6">Combo de 3 recargas con envío prioritario gratis.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Combo</span>
                            <span class="text-2xl font-black text-slate-900">$30.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition-transform hover:scale-105">
                            Pedir ahora
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="bg-slate-50 py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/80">
        <div class="max-w-4xl mx-auto">
            
            <div class="text-center mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest mb-3 block">
                    PREGUNTAS FRECUENTES
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Resolvemos tus dudas
                </h2>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm cursor-pointer" onclick="this.querySelector('p').classList.toggle('hidden')">
                    <h3 class="font-extrabold text-slate-900 text-base flex justify-between items-center">
                        <span>¿Cómo funciona el proceso de entrega de botellones?</span>
                        <span class="text-trigestion-500 font-black">+</span>
                    </h3>
                    <p class="text-slate-600 text-sm font-medium mt-3 leading-relaxed hidden">
                        Realizas tu pedido desde nuestra plataforma web. Asignamos la ruta óptima y un repartidor entrega tus botellones purificados en menos de 2 horas.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm cursor-pointer" onclick="this.querySelector('p').classList.toggle('hidden')">
                    <h3 class="font-extrabold text-slate-900 text-base flex justify-between items-center">
                        <span>¿Debo tener envase de botellón previo para el intercambio?</span>
                        <span class="text-trigestion-500 font-black">+</span>
                    </h3>
                    <p class="text-slate-600 text-sm font-medium mt-3 leading-relaxed hidden">
                        Sí, entregas tu botellón vacío estándar de 20L y recibes uno completamente purificado y sellado. Si no posees envase, puedes comprar uno inicial.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm cursor-pointer" onclick="this.querySelector('p').classList.toggle('hidden')">
                    <h3 class="font-extrabold text-slate-900 text-base flex justify-between items-center">
                        <span>¿Qué fases de purificación tiene el agua Trigestion?</span>
                        <span class="text-trigestion-500 font-black">+</span>
                    </h3>
                    <p class="text-slate-600 text-sm font-medium mt-3 leading-relaxed hidden">
                        Pasa por 7 etapas: Filtro de sedimentos, carbón activado en bloque, suavizador de agua, ósmosis inversa, filtro pulidor, esterilización UV y ozonización.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-white py-16 px-6 lg:px-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
            
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/tr.png') }}" 
                     alt="TRIGESTION Logo" 
                     class="h-7 w-auto object-contain brightness-200 filter"
                     onerror="this.onerror=null; this.src='{{ asset('img/triges.png') }}';">
                <span class="font-extrabold text-lg tracking-wider">TRIGESTION</span>
            </div>

            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} TRIGESTION. Todos los derechos reservados.
            </p>

            <div class="flex items-center gap-8 text-sm font-bold text-slate-300">
                <a href="#beneficios" class="hover:text-trigestion-400 transition-colors">Beneficios</a>
                <a href="#productos" class="hover:text-trigestion-400 transition-colors">Productos</a>
                <a href="{{ route('login') }}" class="hover:text-trigestion-400 transition-colors">Acceso</a>
            </div>

        </div>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let slideIndex = 0;
        function setSlide(n) {
            slideIndex = n;
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            slides.forEach((slide, i) => {
                if(i === n) {
                    slide.classList.remove('hidden');
                    slide.classList.add('flex');
                } else {
                    slide.classList.add('hidden');
                    slide.classList.remove('flex');
                }
            });
            dots.forEach((dot, i) => {
                if(i === n) {
                    dot.classList.remove('bg-sky-200');
                    dot.classList.add('bg-trigestion-500');
                } else {
                    dot.classList.remove('bg-trigestion-500');
                    dot.classList.add('bg-sky-200');
                }
            });
        }

        setInterval(() => {
            slideIndex = (slideIndex + 1) % 3;
            setSlide(slideIndex);
        }, 4000);

        function updateCalc() {
            const people = document.getElementById('people-slider').value;
            document.getElementById('people-count').innerText = `${people} Personas`;
            const bottles = Math.max(2, Math.ceil(people * 1.3));
            const liters = bottles * 20;
            const price = bottles * 12000;
            document.getElementById('result-bottles').innerText = `${bottles} Botellones`;
            document.getElementById('result-liters').innerText = `${liters} Litros de agua pura al mes`;
            document.getElementById('result-price').innerText = `$${price.toLocaleString('es-CO')} / mes`;
        }
    </script>

</body>
</html>
