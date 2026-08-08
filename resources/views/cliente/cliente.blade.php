<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Panel de Cliente - TRIGESTION</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Theme Configuration -->
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
                            500: '#009ee3',
                            600: '#0081c2',
                            700: '#00669e',
                            800: '#005282',
                            900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-100/90 text-slate-800 min-h-screen">

    <div class="flex min-h-screen">

        <!-- ═══════════ SIDEBAR CLIENTE ═══════════ -->
        <aside class="w-72 bg-gradient-to-b from-trigestion-700 via-trigestion-800 to-trigestion-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">

            <!-- LOGO HEADER -->
            <div class="px-6 py-6 border-b border-white/10 bg-black/10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-trigestion-400 to-sky-300 flex items-center justify-center shadow-lg shadow-sky-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                        <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">PORTAL CLIENTE</span>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                
                <!-- SECCIÓN: MI CUENTA Y PEDIDOS -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MI MI CUENTA</p>
                    <div class="space-y-1">
                        <a href="{{ route('cliente.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md backdrop-blur-sm transition-all">
                            <svg class="w-5 h-5 text-trigestion-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Inicio</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                            </svg>
                            <span>Pedir Domicilio de Agua</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Mis Pedidos Anteriores</span>
                        </a>
                    </div>
                </div>

                <!-- SECCIÓN: CATÁLOGO Y PERFIL -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">CATÁLOGO Y MI PERFIL</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Catálogo de Presentaciones</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Mi Perfil y Dirección</span>
                        </a>
                    </div>
                </div>

            </nav>

            <!-- USER PROFILE FOOTER CARD -->
            <div class="p-4 border-t border-white/10 bg-black/20">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10 backdrop-blur-sm">
                    <div class="w-10 h-10 rounded-xl bg-trigestion-400 text-slate-950 flex items-center justify-center font-black text-base shadow-md shadow-trigestion-400/20 shrink-0">
                        {{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-extrabold text-white truncate">{{ Auth::user()->nombres }}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-sky-300 bg-sky-400/10 px-2 py-0.5 rounded-full mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                            Cliente Registrado
                        </span>
                    </div>
                </div>
            </div>

        </aside>

        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER BAR -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Bienvenido a tu Panel de Cliente</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Solicita tu agua purificada y gestiona tus pedidos a domicilio</p>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#" class="hidden sm:flex items-center gap-2 bg-trigestion-50 hover:bg-trigestion-100 text-trigestion-600 border border-trigestion-200/80 px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all shadow-sm">
                        <svg class="w-4 h-4 text-trigestion-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        <span>Pedido Rápido</span>
                    </a>

                    <!-- LOGOUT BUTTON -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200/60 px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-200 shadow-sm hover:shadow active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- MAIN BODY -->
            <main class="p-8 space-y-8 flex-1">

                <!-- WELCOME HERO BANNER CARD -->
                <div class="bg-gradient-to-r from-trigestion-500 via-trigestion-600 to-sky-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-trigestion-500/20">
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 right-24 w-48 h-48 bg-sky-300/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md border border-white/30 px-3 py-1 rounded-full text-xs font-bold text-white mb-3">
                                💧 Pureza e Hidratación Garantizada
                            </span>
                            <h2 class="text-3xl font-black tracking-tight leading-tight">
                                ¡Hola, {{ Auth::user()->nombres }}!
                            </h2>
                            <p class="text-sky-100 text-sm font-medium mt-1 max-w-xl leading-relaxed">
                                Recibe tu agua purificada directamente en tu hogar u oficina en tiempo récord.
                            </p>
                        </div>
                        <button class="bg-white hover:bg-sky-50 text-trigestion-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all transform hover:scale-[1.02] text-sm shrink-0 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-trigestion-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Pedir Domicilio Ahora</span>
                        </button>
                    </div>
                </div>

                <!-- KPI METRICS GRID (4 CARDS) -->
                @php
                    $misPedidosCount = \DB::table('venta')->where('id_usuario', Auth::id())->count();
                    $ultimoPedido = \DB::table('venta')->where('id_usuario', Auth::id())->orderByDesc('id_venta')->first();
                    $productosDisponibles = \DB::table('producto')->where('estado', 1)->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- KPI 1: MIS PEDIDOS -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-trigestion-600 flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-sky-600 bg-sky-50 border border-sky-200/60 px-2.5 py-1 rounded-full">
                                Historial
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $misPedidosCount }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">PEDIDOS REALIZADOS</p>
                    </div>

                    <!-- KPI 2: DIRECCIÓN -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-full">
                                Entrega
                            </span>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900 truncate leading-tight mt-1">{{ Auth::user()->direccion ?? 'No registrada' }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-2">DIRECCIÓN DE DESPACHO</p>
                    </div>

                    <!-- KPI 3: ÚLTIMO PEDIDO -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-amber-600 bg-amber-50 border border-amber-200/60 px-2.5 py-1 rounded-full">
                                Último
                            </span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $ultimoPedido ? '$'.number_format($ultimoPedido->total, 0, ',', '.') : 'Sin compras' }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">{{ $ultimoPedido ? ($ultimoPedido->fecha ?? 'Reciente') : 'HAZ TU PRIMER PEDIDO' }}</p>
                    </div>

                    <!-- KPI 4: PRODUCTOS DISPONIBLES -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-purple-600 bg-purple-50 border border-purple-200/60 px-2.5 py-1 rounded-full">
                                Catálogo
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $productosDisponibles }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">PRESENTACIONES ACTIVAS</p>
                    </div>

                </div>

                <!-- MAIN GRID CONTENT -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <!-- LEFT: PRODUCTS CATALOG PREVIEW (8 COLS) -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Presentaciones de Agua Disponibles</h3>
                                <p class="text-xs text-slate-500 font-medium">Elige la mejor opción para tu hogar o empresa</p>
                            </div>
                        </div>

                        @php
                            $productos = \DB::table('producto')->where('estado', 1)->limit(4)->get();
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($productos as $prod)
                                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                                    <div>
                                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-trigestion-500 flex items-center justify-center mb-4 border border-sky-100 group-hover:scale-105 transition-transform">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                            </svg>
                                        </div>
                                        <h4 class="font-extrabold text-slate-900 text-base mb-1">{{ $prod->nombre }}</h4>
                                        <p class="text-xs text-slate-500 font-medium mb-4">Agua ozonizada, libre de sodio e impurezas con sello hermético.</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <span class="text-xl font-black text-trigestion-600">${{ number_format($prod->precio, 0, ',', '.') }}</span>
                                        <button class="bg-trigestion-500 hover:bg-trigestion-600 text-white text-xs font-extrabold px-4 py-2.5 rounded-xl shadow-md shadow-trigestion-500/20 transition-all flex items-center gap-1.5">
                                            <span>Pedir este</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-white rounded-3xl p-10 text-center border border-slate-200/80">
                                    <div class="w-12 h-12 rounded-full bg-sky-50 text-trigestion-500 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Explora nuestro catálogo de productos</p>
                                    <p class="text-xs text-slate-400 mt-1">Disponemos de botellones de 20L, bolsas de 6L y paks de botellas.</p>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- RIGHT: WHATSAPP CONTACT & GUARANTEE (4 COLS) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- WHATSAPP DOMICILIO -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h3 class="text-base font-extrabold text-slate-900 mb-2">Pedir por WhatsApp</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed mb-4">
                                Chatea directamente con nuestro equipo de entregas para agendar tu horario preferido.
                            </p>
                            <a href="https://wa.me/" target="_blank" class="w-full flex items-center justify-center gap-2 p-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-xs transition-colors shadow-lg shadow-emerald-500/20">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Iniciar Chat por WhatsApp</span>
                            </a>
                        </div>

                        <!-- QUALITY PROMISE CARD -->
                        <div class="bg-gradient-to-br from-trigestion-600 to-sky-800 rounded-3xl p-6 text-white shadow-lg shadow-trigestion-600/20">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 border border-white/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-white">Compromiso Trigestion</h4>
                                    <p class="text-[10px] text-sky-200 font-semibold uppercase tracking-wider">ENVASES DESINFECTADOS Y SELLADOS</p>
                                </div>
                            </div>
                            <p class="text-xs text-sky-100 font-medium leading-relaxed">
                                Entregamos botellones sanitizados con sello inviolable. Agua 100% purificada con el estándar más alto del mercado.
                            </p>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

</body>
</html>
