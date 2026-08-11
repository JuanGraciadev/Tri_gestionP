<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRIGESTION — Agua Purificada Premium</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        trigestion: {
                            50:'#f0f9ff', 100:'#e0f2fe', 200:'#bae6fd', 300:'#7dd3fc',
                            400:'#38bdf8', 500:'#009ee3', 600:'#0081c2', 700:'#00669e',
                            800:'#005282', 900:'#072b48',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 9s ease-in-out infinite',
                        'pulse-soft': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-18px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .blob { position:absolute; border-radius:50%; filter:blur(80px); opacity:.35; pointer-events:none; }
        .nav-link { position:relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#009ee3; border-radius:2px; transition:width .3s ease; }
        .nav-link:hover::after { width:100%; }
        .faq-item p { max-height:0; overflow:hidden; transition:max-height .4s ease, opacity .3s ease; opacity:0; }
        .faq-item.open p { max-height:200px; opacity:1; }
        .faq-item .faq-icon { transition:transform .3s ease; }
        .faq-item.open .faq-icon { transform:rotate(45deg); }
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#94a3b8; border-radius:3px; }
    </style>
</head>

<body class="font-sans bg-white text-slate-900 antialiased">

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- HERO                                                       -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="relative min-h-screen flex flex-col overflow-hidden bg-gradient-to-br from-sky-50 via-white to-trigestion-50">

        <!-- Background blobs -->
        <div class="blob w-[700px] h-[700px] bg-trigestion-300 top-[-200px] right-[-200px]"></div>
        <div class="blob w-[500px] h-[500px] bg-sky-200 bottom-[-150px] left-[-150px]"></div>

        <!-- ── NAVBAR ── -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-sky-100/60 shadow-sm">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4 flex items-center justify-between">

                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-trigestion-500 flex items-center justify-center shadow-md shadow-trigestion-500/30">
                        <i class="fas fa-droplet text-white text-base"></i>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tight">TRI<span class="text-trigestion-500">GESTION</span></span>
                </a>

                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#beneficios" class="nav-link hover:text-trigestion-600 transition-colors">Beneficios</a>
                    <a href="#calculadora" class="nav-link hover:text-trigestion-600 transition-colors">Calculadora</a>
                    <a href="#productos" class="nav-link hover:text-trigestion-600 transition-colors">Productos</a>
                    <a href="#faq" class="nav-link hover:text-trigestion-600 transition-colors">Preguntas</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="font-bold text-trigestion-600 hover:text-trigestion-800 transition-colors">
                                <i class="fas fa-gauge mr-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-slate-700 hover:text-trigestion-500 transition-colors">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center gap-2 bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-trigestion-500/25 hover:-translate-y-0.5 transition-all">
                                    <i class="fas fa-user-plus text-xs"></i> Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>

                <!-- Mobile -->
                <div class="md:hidden flex items-center gap-2">
                    @if (Route::has('login'))
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 px-4 py-2 rounded-xl hover:bg-slate-100 transition-colors">Entrar</a>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 text-white text-sm font-bold px-4 py-2 rounded-xl shadow-sm">Registro</a>
                        @endguest
                    @endif
                </div>
            </div>
        </header>


        <!-- ── HERO CONTENT ── -->
        <main class="relative z-10 flex-1 max-w-7xl mx-auto px-6 lg:px-12 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- LEFT -->
            <div class="lg:col-span-7 flex flex-col items-start">

                <div class="inline-flex items-center gap-2 bg-trigestion-50 border border-trigestion-200 text-trigestion-700 px-4 py-1.5 rounded-full text-xs font-extrabold tracking-wider uppercase mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-trigestion-500 animate-pulse-soft"></span>
                    Pureza Certificada · Entrega a Domicilio
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                    La experiencia de agua pura que tu cuerpo
                    <span class="text-trigestion-500"> merece.</span>
                </h1>

                <p class="text-slate-500 text-base sm:text-lg max-w-xl mb-10 font-medium leading-relaxed">
                    Hidratación ligera y cristalina a domicilio. Pide tus botellones retornables en segundos y disfruta de la mejor calidad con la comodidad que buscas.
                </p>

                <!-- Stats bar -->
                <div class="flex flex-wrap items-center gap-6 bg-white/90 backdrop-blur border border-sky-100 px-6 py-4 rounded-2xl shadow-sm mb-10">
                    <div>
                        <span class="text-xl font-black text-trigestion-500 block">+15.000 L</span>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Entregados este mes</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div>
                        <span class="text-xl font-black text-trigestion-500 block">7 Fases</span>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ósmosis Inversa</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div>
                        <span class="text-xl font-black text-trigestion-500 block">4.9 ⭐</span>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Calificación</span>
                    </div>
                </div>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-3 bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-8 py-4 rounded-2xl text-base shadow-lg shadow-trigestion-500/30 hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-cart-shopping"></i> Haz tu pedido
                    </a>
                    <a href="#calculadora"
                       class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold px-8 py-4 rounded-2xl text-base shadow-sm hover:-translate-y-0.5 transition-all">
                        Calcula tu consumo <i class="fas fa-arrow-down text-slate-400 text-sm"></i>
                    </a>
                </div>
            </div>


            <!-- RIGHT: carousel card -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-sm">
                    <!-- glow ring -->
                    <div class="absolute inset-0 bg-trigestion-400/20 rounded-[3rem] blur-3xl scale-110 pointer-events-none"></div>

                    <div class="relative bg-white/90 backdrop-blur-2xl border border-white rounded-[2.5rem] shadow-2xl shadow-sky-900/10 p-10 text-center flex flex-col items-center min-h-[440px] justify-between overflow-hidden">

                        <!-- slide 0 -->
                        <div class="carousel-slide flex flex-col items-center w-full" id="slide-0">
                            <div class="w-24 h-24 bg-trigestion-50 border-2 border-trigestion-100 rounded-3xl flex items-center justify-center text-5xl mb-6 shadow-inner animate-float">💧</div>
                            <p class="text-xs font-extrabold text-trigestion-500 uppercase tracking-widest mb-1">Recarga Clásica</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-1">Bidón 20 Litros</h3>
                            <p class="text-slate-400 text-sm font-medium mb-6">Agua purificada 100%</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md hover:scale-105 transition-all">
                                Recarga · $19.000
                            </a>
                        </div>

                        <!-- slide 1 -->
                        <div class="carousel-slide hidden flex-col items-center w-full" id="slide-1">
                            <div class="w-24 h-24 bg-sky-50 border-2 border-sky-100 rounded-3xl flex items-center justify-center text-5xl mb-6 shadow-inner animate-float-slow">❄️</div>
                            <p class="text-xs font-extrabold text-trigestion-500 uppercase tracking-widest mb-1">Equipo Completo</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-1">Dispensador Frío/Calor</h3>
                            <p class="text-slate-400 text-sm font-medium mb-6">Control electrónico de temperatura</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md hover:scale-105 transition-all">
                                Equipo · $85.000
                            </a>
                        </div>

                        <!-- slide 2 -->
                        <div class="carousel-slide hidden flex-col items-center w-full" id="slide-2">
                            <div class="w-24 h-24 bg-indigo-50 border-2 border-indigo-100 rounded-3xl flex items-center justify-center text-5xl mb-6 shadow-inner animate-float">📦</div>
                            <p class="text-xs font-extrabold text-trigestion-500 uppercase tracking-widest mb-1">Pack Ahorro</p>
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-1">Pack Familiar 3×20L</h3>
                            <p class="text-slate-400 text-sm font-medium mb-6">3 Recargas + Envío Gratis</p>
                            <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-6 py-2.5 rounded-xl text-sm shadow-md hover:scale-105 transition-all">
                                Pack · $50.000
                            </a>
                        </div>

                        <!-- brand + dots -->
                        <div class="mt-6 flex flex-col items-center gap-3">
                            <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">TRIGESTION</span>
                            <div class="flex items-center gap-2" id="carouselDots">
                                <button onclick="setSlide(0)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-trigestion-500 transition-all"></button>
                                <button onclick="setSlide(1)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-sky-200 transition-all"></button>
                                <button onclick="setSlide(2)" class="carousel-dot w-2.5 h-2.5 rounded-full bg-sky-200 transition-all"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        <!-- wave bottom -->
        <div class="w-full overflow-hidden leading-none relative z-10">
            <svg viewBox="0 0 1200 80" preserveAspectRatio="none" class="w-full h-14 lg:h-20 text-white block">
                <path d="M0,0 C200,80 400,0 600,40 C800,80 1000,0 1200,40 L1200,80 L0,80 Z" fill="currentColor"/>
            </svg>
        </div>
    </div>
    <!-- END HERO -->


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- CALCULADORA                                                -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section id="calculadora" class="bg-white py-20 lg:py-28 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gradient-to-br from-trigestion-500 via-trigestion-600 to-sky-700 rounded-[2.5rem] p-8 lg:p-14 text-white shadow-2xl shadow-trigestion-500/20 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative overflow-hidden">

                <!-- bg decoration -->
                <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- left -->
                <div class="lg:col-span-7 relative z-10">
                    <span class="inline-block bg-white/20 backdrop-blur text-white font-extrabold text-xs uppercase tracking-widest px-4 py-1.5 rounded-full mb-5 border border-white/30">
                        <i class="fas fa-calculator mr-2"></i>Calculadora de Hidratación
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-4 tracking-tight">
                        ¿Cuánta agua necesita tu hogar?
                    </h2>
                    <p class="text-sky-100 text-base leading-relaxed mb-8 font-medium max-w-lg">
                        Calcula fácilmente la cantidad de botellones de 20 litros recomendados al mes según los integrantes de tu hogar u oficina.
                    </p>
                    <div class="bg-white/15 backdrop-blur border border-white/25 rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-bold text-sm"><i class="fas fa-users mr-2 text-sky-200"></i>Personas en el hogar:</span>
                            <span id="people-count" class="text-xl font-black text-sky-200">3 Personas</span>
                        </div>
                        <input type="range" id="people-slider" min="1" max="12" value="3"
                               class="w-full h-2 rounded-full appearance-none cursor-pointer accent-white bg-sky-300/40"
                               oninput="updateCalc()">
                        <div class="flex justify-between text-xs text-sky-200/70 font-bold mt-1">
                            <span>1</span><span>6</span><span>12</span>
                        </div>
                    </div>
                </div>

                <!-- right: result card -->
                <div class="lg:col-span-5 relative z-10 flex justify-center">
                    <div class="bg-white text-slate-900 rounded-3xl p-8 w-full max-w-xs text-center shadow-xl">
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-3">Consumo Recomendado</span>
                        <div id="result-bottles" class="text-5xl font-black text-trigestion-500 mb-1">4</div>
                        <div class="text-base font-bold text-slate-500 mb-1">Botellones al mes</div>
                        <div id="result-liters" class="text-xs font-bold text-slate-400 mb-6">80 Litros de agua pura</div>
                        <div class="pt-4 border-t border-slate-100 mb-6">
                            <span class="text-xs font-bold text-slate-400 block mb-1">Inversión mensual estimada</span>
                            <span id="result-price" class="text-2xl font-black text-slate-900">$76.000 / mes</span>
                        </div>
                        <a href="{{ route('register') }}" class="block w-full bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold py-3.5 rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5">
                            <i class="fas fa-cart-shopping mr-2"></i>Pedir Plan Sugerido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- BENEFICIOS                                                 -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section id="beneficios" class="bg-slate-50 py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/70">
        <div class="max-w-7xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest block mb-3">
                    Beneficios Exclusivos
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Calidad superior en cada gota
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-trigestion-500/25 group-hover:scale-110 transition-transform">
                        <i class="fas fa-droplet"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-3">100% Purificada</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        Ósmosis inversa avanzada de 7 fases que remueve impurezas conservando la ligereza y frescura óptima.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-trigestion-500/25 group-hover:scale-110 transition-transform">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-3">Entrega Express</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        Recibe tus botellones directamente en la puerta de tu hogar u oficina en tiempo récord.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-trigestion-500/25 group-hover:scale-110 transition-transform">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-3">Envases Ecológicos</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        Botellones retornables de alta durabilidad libres de BPA, respetuosos con el medio ambiente.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- PRODUCTOS                                                  -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section id="productos" class="bg-white py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/70">
        <div class="max-w-7xl mx-auto">

            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest block mb-3">
                    Nuestros Productos
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Formatos para cada necesidad
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Producto 1 -->
                <div class="relative bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <span class="absolute top-6 right-6 bg-trigestion-500 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">Más Vendido</span>
                    <div class="w-full h-44 bg-trigestion-50 rounded-2xl flex items-center justify-center mb-6 text-7xl">💧</div>
                    <span class="text-[11px] font-extrabold text-trigestion-600 uppercase tracking-widest">Recarga Clásica</span>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1 mb-2">Bidón 20 Litros</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium mb-6 flex-1">Agua purificada premium para dispensador o bomba manual. La más solicitada.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Recarga</span>
                            <span class="text-2xl font-black text-slate-900">$19.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm hover:scale-105 transition-all shadow-md shadow-trigestion-500/20">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Producto 2 -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-full h-44 bg-sky-50 rounded-2xl flex items-center justify-center mb-6 text-7xl">❄️</div>
                    <span class="text-[11px] font-extrabold text-trigestion-600 uppercase tracking-widest">Equipo Completo</span>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1 mb-2">Dispensador Frío/Calor</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium mb-6 flex-1">Dispensador moderno con control electrónico de temperatura fría y caliente.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Venta</span>
                            <span class="text-2xl font-black text-slate-900">$85.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm hover:scale-105 transition-all shadow-md shadow-trigestion-500/20">
                            Pedir ahora
                        </a>
                    </div>
                </div>

                <!-- Producto 3 -->
                <div class="relative bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-trigestion-200 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <span class="absolute top-6 right-6 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">Ahorra 15%</span>
                    <div class="w-full h-44 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 text-7xl">📦</div>
                    <span class="text-[11px] font-extrabold text-trigestion-600 uppercase tracking-widest">Pack Ahorro</span>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1 mb-2">Pack Familiar 3×20L</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium mb-6 flex-1">Combo de 3 recargas con envío prioritario gratis. El favorito de las familias.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Precio Combo</span>
                            <span class="text-2xl font-black text-slate-900">$50.000</span>
                        </div>
                        <a href="{{ route('register') }}" class="bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-5 py-2.5 rounded-xl text-sm hover:scale-105 transition-all shadow-md shadow-trigestion-500/20">
                            Pedir ahora
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- PROCESO EN 3 PASOS                                        -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section class="bg-gradient-to-br from-trigestion-500 to-sky-700 py-20 lg:py-28 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-sky-200 font-extrabold text-xs uppercase tracking-widest block mb-3">Proceso Simple</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Recibir agua pura es fácil
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-white">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center text-3xl font-black shadow-lg">
                        1
                    </div>
                    <h3 class="text-lg font-extrabold">Regístrate</h3>
                    <p class="text-sky-100 text-sm font-medium leading-relaxed max-w-xs">
                        Crea tu cuenta gratuita en nuestra plataforma en menos de 2 minutos.
                    </p>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center text-3xl font-black shadow-lg">
                        2
                    </div>
                    <h3 class="text-lg font-extrabold">Elige tu pedido</h3>
                    <p class="text-sky-100 text-sm font-medium leading-relaxed max-w-xs">
                        Selecciona el producto y la cantidad. El precio es siempre claro y sin sorpresas.
                    </p>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center text-3xl font-black shadow-lg">
                        3
                    </div>
                    <h3 class="text-lg font-extrabold">Recibe a domicilio</h3>
                    <p class="text-sky-100 text-sm font-medium leading-relaxed max-w-xs">
                        Tu pedido llega directamente a tu puerta. Entregamos tu botellón vacío y nos llevamos el lleno.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- FAQ                                                        -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section id="faq" class="bg-slate-50 py-20 lg:py-28 px-6 lg:px-12 border-t border-slate-200/70">
        <div class="max-w-3xl mx-auto">

            <div class="text-center mb-16">
                <span class="text-trigestion-600 font-extrabold text-xs uppercase tracking-widest block mb-3">
                    Preguntas Frecuentes
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Resolvemos tus dudas
                </h2>
            </div>

            <div class="space-y-3" id="faqContainer">

                <div class="faq-item bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left font-extrabold text-slate-800 text-sm hover:bg-slate-50 transition-colors">
                        <span>¿Cómo funciona el proceso de entrega de botellones?</span>
                        <i class="fas fa-plus faq-icon text-trigestion-500 text-sm shrink-0 ml-4"></i>
                    </button>
                    <p class="px-6 pb-5 text-slate-500 text-sm font-medium leading-relaxed">
                        Realizas tu pedido desde nuestra plataforma web. Asignamos la ruta óptima y un repartidor entrega tus botellones purificados en menos de 2 horas en el área de cobertura.
                    </p>
                </div>

                <div class="faq-item bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left font-extrabold text-slate-800 text-sm hover:bg-slate-50 transition-colors">
                        <span>¿Debo tener envase de botellón previo para el intercambio?</span>
                        <i class="fas fa-plus faq-icon text-trigestion-500 text-sm shrink-0 ml-4"></i>
                    </button>
                    <p class="px-6 pb-5 text-slate-500 text-sm font-medium leading-relaxed">
                        Sí, entregas tu botellón vacío estándar de 20L y recibes uno completamente purificado y sellado. Si no posees envase, puedes adquirir uno nuevo en tu primer pedido.
                    </p>
                </div>

                <div class="faq-item bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left font-extrabold text-slate-800 text-sm hover:bg-slate-50 transition-colors">
                        <span>¿Qué fases de purificación tiene el agua Trigestion?</span>
                        <i class="fas fa-plus faq-icon text-trigestion-500 text-sm shrink-0 ml-4"></i>
                    </button>
                    <p class="px-6 pb-5 text-slate-500 text-sm font-medium leading-relaxed">
                        Pasa por 7 etapas: Filtro de sedimentos, carbón activado en bloque, suavizador de agua, membrana de ósmosis inversa, filtro pulidor, esterilización UV y ozonización final.
                    </p>
                </div>

                <div class="faq-item bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left font-extrabold text-slate-800 text-sm hover:bg-slate-50 transition-colors">
                        <span>¿Cuáles son los métodos de pago aceptados?</span>
                        <i class="fas fa-plus faq-icon text-trigestion-500 text-sm shrink-0 ml-4"></i>
                    </button>
                    <p class="px-6 pb-5 text-slate-500 text-sm font-medium leading-relaxed">
                        Aceptamos pago en efectivo contra entrega, transferencia bancaria y tarjeta débito/crédito. El método se selecciona al momento de confirmar tu pedido.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- CTA FINAL                                                  -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <section class="bg-white py-20 lg:py-24 px-6 lg:px-12 border-t border-slate-200/70">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-trigestion-50 border border-trigestion-100 rounded-[2.5rem] p-12 lg:p-16 shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-trigestion-500 text-white flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg shadow-trigestion-500/25">
                    <i class="fas fa-droplet"></i>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                    ¿Listo para hidratarte mejor?
                </h2>
                <p class="text-slate-500 text-base font-medium mb-8 max-w-lg mx-auto leading-relaxed">
                    Únete a cientos de familias y empresas que confían en Trigestion para su hidratación diaria.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-3 bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold px-10 py-4 rounded-2xl text-base shadow-xl shadow-trigestion-500/30 hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-user-plus"></i> Crear cuenta gratis
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold px-10 py-4 rounded-2xl text-base shadow-sm hover:-translate-y-0.5 transition-all">
                        Ya tengo cuenta <i class="fas fa-arrow-right text-slate-400 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- FOOTER                                                     -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <footer class="bg-slate-950 text-white py-16 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">

                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-trigestion-500 flex items-center justify-center shadow-md">
                            <i class="fas fa-droplet text-white text-base"></i>
                        </div>
                        <span class="text-xl font-black tracking-tight">TRI<span class="text-trigestion-400">GESTION</span></span>
                    </div>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-sm">
                        Agua purificada premium con entrega a domicilio. Comprometidos con la calidad, la salud y el medio ambiente.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-extrabold text-sm uppercase tracking-widest text-slate-400 mb-4">Navegación</h4>
                    <ul class="space-y-2 text-sm font-semibold text-slate-300">
                        <li><a href="#beneficios" class="hover:text-trigestion-400 transition-colors">Beneficios</a></li>
                        <li><a href="#calculadora" class="hover:text-trigestion-400 transition-colors">Calculadora</a></li>
                        <li><a href="#productos" class="hover:text-trigestion-400 transition-colors">Productos</a></li>
                        <li><a href="#faq" class="hover:text-trigestion-400 transition-colors">Preguntas</a></li>
                    </ul>
                </div>

                <!-- Account -->
                <div>
                    <h4 class="font-extrabold text-sm uppercase tracking-widest text-slate-400 mb-4">Tu Cuenta</h4>
                    <ul class="space-y-2 text-sm font-semibold text-slate-300">
                        @if (Route::has('login'))
                            @guest
                                <li><a href="{{ route('login') }}" class="hover:text-trigestion-400 transition-colors">Iniciar Sesión</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-trigestion-400 transition-colors">Registrarse</a></li>
                            @else
                                <li><a href="{{ url('/dashboard') }}" class="hover:text-trigestion-400 transition-colors">Dashboard</a></li>
                            @endguest
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center">
                <p class="text-slate-500 text-sm font-medium">
                    &copy; {{ date('Y') }} TRIGESTION. Todos los derechos reservados.
                </p>
                <p class="text-slate-600 text-xs font-semibold">
                    Agua purificada · Calidad garantizada
                </p>
            </div>
        </div>
    </footer>

