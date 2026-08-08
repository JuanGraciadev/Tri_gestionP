<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrador - TRIGESTION</title>

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

        <!-- ═══════════ SIDEBAR ADMIN ═══════════ -->
        <aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">

            <!-- LOGO HEADER -->
            <div class="px-6 py-6 border-b border-white/10 bg-black/10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-trigestion-400 to-sky-300 flex items-center justify-center shadow-lg shadow-sky-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                        <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">ADMINISTRACIÓN</span>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                
                <!-- SECCIÓN: PRINCIPAL -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">PRINCIPAL</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md backdrop-blur-sm transition-all">
                            <svg class="w-5 h-5 text-trigestion-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard General</span>
                        </a>
                    </div>
                </div>

                <!-- SECCIÓN: GESTIÓN OPERATIVA -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">GESTIÓN DEL SISTEMA</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Usuarios y Roles</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Catálogo Productos</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Ventas y Pedidos</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span>Lotes de Producción</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            <span>Inventario Bodega</span>
                        </a>
                    </div>
                </div>

                <!-- SECCIÓN: REPORTES Y CONFIGURACIÓN -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">ANÁLISIS Y CONTROL</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Informes & Métricas</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Configuración General</span>
                        </a>
                    </div>
                </div>

            </nav>

            <!-- USER PROFILE FOOTER CARD -->
            <div class="p-4 border-t border-white/10 bg-black/20">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10 backdrop-blur-sm">
                    <div class="w-10 h-10 rounded-xl bg-trigestion-500 flex items-center justify-center font-black text-white text-base shadow-md shadow-trigestion-500/30 shrink-0">
                        {{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-extrabold text-white truncate">{{ Auth::user()->nombres }}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Administrador
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
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Panel de Administración</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Monitoreo y gestión integral del sistema Trigestion</p>
                </div>

                <div class="flex items-center gap-5">
                    <!-- DATE BADGE -->
                    <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-3.5 py-2 rounded-xl border border-slate-200/80 text-xs font-bold text-slate-600">
                        <svg class="w-4 h-4 text-trigestion-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('d \d\e F, Y') }}</span>
                    </div>

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

                <!-- WELCOME BANNER CARD -->
                <div class="bg-gradient-to-r from-trigestion-500 via-trigestion-600 to-sky-700 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-trigestion-500/20">
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 right-24 w-48 h-48 bg-sky-300/20 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md border border-white/30 px-3 py-1 rounded-full text-xs font-bold text-white mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                Control Total Activado
                            </span>
                            <h2 class="text-3xl font-black tracking-tight leading-tight">
                                ¡Bienvenido, {{ Auth::user()->nombres }}! 👋
                            </h2>
                            <p class="text-sky-100 text-sm font-medium mt-1 max-w-xl leading-relaxed">
                                Tienes acceso administrativo completo a la plataforma. Consulta el estado de producción, ventas y usuarios en tiempo real.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KPI METRICS GRID (4 CARDS) -->
                @php
                    $totalUsuarios = \App\Models\User::count();
                    $totalProductos = \DB::table('producto')->where('estado', 1)->count();
                    $totalVentas = \DB::table('venta')->count();
                    $ventasHoy = \DB::table('venta')->whereDate('fecha', today())->count();
                    $totalIngresos = \DB::table('venta')->sum('total');
                    $produccionActiva = \DB::table('produccion')->where('estado', 'activa')->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- KPI 1: USUARIOS -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-trigestion-500 flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-full">
                                Activos
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalUsuarios }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">USUARIOS REGISTRADOS</p>
                    </div>

                    <!-- KPI 2: PRODUCTOS -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
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
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalProductos }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">PRODUCTOS ACTIVOS</p>
                    </div>

                    <!-- KPI 3: VENTAS -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-amber-600 bg-amber-50 border border-amber-200/60 px-2.5 py-1 rounded-full">
                                {{ $ventasHoy }} hoy
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalVentas }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">VENTAS TOTALES</p>
                    </div>

                    <!-- KPI 4: INGRESOS -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-full">
                                Acumulado
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">${{ number_format($totalIngresos, 0, ',', '.') }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">INGRESOS TOTALES</p>
                    </div>

                </div>

                <!-- MAIN GRID CONTENT (TABLE + RIGHT SIDEBAR) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <!-- LEFT: RECENT SALES TABLE (8 COLS) -->
                    <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Últimas Ventas Registradas</h3>
                                    <p class="text-xs text-slate-500 font-medium">Transacciones recientes en el sistema</p>
                                </div>
                                <a href="#" class="text-xs font-extrabold text-trigestion-500 hover:text-trigestion-700 bg-sky-50 px-3 py-1.5 rounded-xl border border-sky-100 transition-colors">
                                    Ver todas
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100">
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">ID VENTA</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">FECHA</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">TOTAL</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">ESTADO</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">PAGO</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @php
                                            $ultimasVentas = \DB::table('venta')->orderByDesc('id_venta')->limit(6)->get();
                                        @endphp
                                        @forelse ($ultimasVentas as $venta)
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="px-6 py-4 text-xs font-black text-slate-900">#{{ $venta->id_venta }}</td>
                                                <td class="px-6 py-4 text-xs text-slate-600 font-medium">{{ $venta->fecha ?? '—' }}</td>
                                                <td class="px-6 py-4 text-xs font-black text-slate-900">${{ number_format($venta->total, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $estadoClass = match(strtolower($venta->estado ?? '')) {
                                                            'completada','entregada' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                            'pendiente' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                            'cancelada' => 'bg-red-50 text-red-600 border-red-200',
                                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                                        };
                                                    @endphp
                                                    <span class="inline-block text-[10px] font-extrabold px-2.5 py-1 rounded-full border {{ $estadoClass }}">
                                                        {{ ucfirst($venta->estado ?? 'Registrado') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-slate-500 font-medium">{{ ucfirst($venta->metodo_pago ?? 'Efectivo') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center">
                                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-600">No hay ventas registradas aún</p>
                                                    <p class="text-xs text-slate-400 mt-0.5">Las ventas realizadas aparecerán listadas en esta tabla.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: STATS & ACTIONS (4 COLS) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- WIDGET 1: ESTADO DE PRODUCCIÓN -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h3 class="text-base font-extrabold text-slate-900 mb-4">Estado de Producción</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                        <span class="text-slate-600">Producciones Activas</span>
                                        <span class="text-trigestion-600 font-black">{{ $produccionActiva }}</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-trigestion-500 rounded-full transition-all" style="width: {{ $produccionActiva > 0 ? min($produccionActiva * 20, 100) : 10 }}%"></div>
                                    </div>
                                </div>

                                @php
                                    $inventarioTotal = \DB::table('inventario_productos')->sum('cantidad');
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                        <span class="text-slate-600">Productos en Stock</span>
                                        <span class="text-emerald-600 font-black">{{ $inventarioTotal }} uds</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ min($inventarioTotal, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WIDGET 2: USUARIOS POR ROL -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h3 class="text-base font-extrabold text-slate-900 mb-4">Usuarios por Rol</h3>
                            
                            @php
                                $admins = \App\Models\User::where('id_rol', 1)->count();
                                $trabajadores = \App\Models\User::where('id_rol', 2)->count();
                                $clientes = \App\Models\User::where('id_rol', 3)->count();
                            @endphp

                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-sky-50/60 border border-sky-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-trigestion-500"></div>
                                        <span class="text-xs font-extrabold text-slate-700">Administradores</span>
                                    </div>
                                    <span class="text-sm font-black text-slate-900">{{ $admins }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-2xl bg-amber-50/60 border border-amber-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                        <span class="text-xs font-extrabold text-slate-700">Trabajadores</span>
                                    </div>
                                    <span class="text-sm font-black text-slate-900">{{ $trabajadores }}</span>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                        <span class="text-xs font-extrabold text-slate-700">Clientes</span>
                                    </div>
                                    <span class="text-sm font-black text-slate-900">{{ $clientes }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- WIDGET 3: ACCIONES RÁPIDAS -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h3 class="text-base font-extrabold text-slate-900 mb-4">Acciones Rápidas</h3>
                            
                            <div class="space-y-2.5">
                                <a href="#" class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-trigestion-50 hover:bg-trigestion-100 border border-trigestion-100 text-trigestion-700 font-extrabold text-xs transition-all group">
                                    <div class="w-8 h-8 rounded-xl bg-trigestion-500 text-white flex items-center justify-center shadow-md shadow-trigestion-500/20 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <span>Crear Nuevo Producto</span>
                                </a>

                                <a href="#" class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 font-extrabold text-xs transition-all group">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <span>Registrar Nueva Venta</span>
                                </a>

                                <a href="#" class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-purple-50 hover:bg-purple-100 border border-purple-100 text-purple-700 font-extrabold text-xs transition-all group">
                                    <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-md shadow-purple-600/20 group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </div>
                                    <span>Agregar Nuevo Usuario</span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

</body>
</html>
