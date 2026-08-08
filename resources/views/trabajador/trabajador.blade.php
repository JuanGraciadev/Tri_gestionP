<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Operativo - TRIGESTION</title>

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

        <!-- ═══════════ SIDEBAR TRABAJADOR ═══════════ -->
        <aside class="w-72 bg-gradient-to-b from-slate-900 via-sky-950 to-black text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">

            <!-- LOGO HEADER -->
            <div class="px-6 py-6 border-b border-white/10 bg-black/20">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-400 to-amber-200 flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                        <span class="text-[10px] text-amber-300 font-bold tracking-widest uppercase mt-1 block">PLANTA & OPERACIONES</span>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                
                <!-- SECCIÓN: OPERACIONES DE PLANTA -->
                <div>
                    <p class="text-[10px] font-black text-amber-300/70 tracking-widest uppercase px-3 mb-2">OPERACIONES DE PLANTA</p>
                    <div class="space-y-1">
                        <a href="{{ route('trabajador.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-amber-400/20 text-amber-300 border-l-4 border-amber-400 shadow-md backdrop-blur-sm transition-all">
                            <svg class="w-5 h-5 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Resumen Operativo</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span>Lotes de Producción</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                            <span>Inventario de Productos</span>
                        </a>
                    </div>
                </div>

                <!-- SECCIÓN: LOGÍSTICA Y DESPACHOS -->
                <div>
                    <p class="text-[10px] font-black text-amber-300/70 tracking-widest uppercase px-3 mb-2">LOGÍSTICA Y DESPACHOS</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <span>Pedidos por Alistar</span>
                        </a>

                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <span>Materia Prima e Insumos</span>
                        </a>
                    </div>
                </div>

            </nav>

            <!-- USER PROFILE FOOTER CARD -->
            <div class="p-4 border-t border-white/10 bg-black/30">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/10 backdrop-blur-sm">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-black text-base shadow-md shadow-amber-400/20 shrink-0">
                        {{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-extrabold text-white truncate">{{ Auth::user()->nombres }}</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-300 bg-amber-400/10 px-2 py-0.5 rounded-full mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            Trabajador / Operador
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
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Módulo Operativo de Planta</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Gestión de envasado, filtrado, inventario y despachos</p>
                </div>

                <div class="flex items-center gap-5">
                    <!-- DATE BADGE -->
                    <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-3.5 py-2 rounded-xl border border-slate-200/80 text-xs font-bold text-slate-600">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
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
                <div class="bg-gradient-to-r from-slate-900 via-sky-950 to-trigestion-800 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-slate-900/20">
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-amber-400/20 border border-amber-400/30 px-3 py-1 rounded-full text-xs font-bold text-amber-300 mb-3">
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                                Planta Operativa Activa
                            </span>
                            <h2 class="text-3xl font-black tracking-tight leading-tight">
                                ¡Hola, {{ Auth::user()->nombres }}! 🏭
                            </h2>
                            <p class="text-slate-300 text-sm font-medium mt-1 max-w-xl leading-relaxed">
                                Administra los lotes de embotellado, la recepción de materia prima y el alistamiento de pedidos para entrega.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KPI METRICS GRID (4 CARDS) -->
                @php
                    $produccionesCount = \DB::table('produccion')->count();
                    $inventarioUds = \DB::table('inventario_productos')->sum('cantidad');
                    $pedidosPendientes = \DB::table('venta')->whereIn('estado', ['pendiente', 'en_proceso'])->count();
                    $materiaPrimaCount = \DB::table('inventario_materia_prima')->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- KPI 1: LOTES -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-trigestion-600 flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-sky-600 bg-sky-50 border border-sky-200/60 px-2.5 py-1 rounded-full">
                                Lotes
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $produccionesCount }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">LOTES REGISTRADOS</p>
                    </div>

                    <!-- KPI 2: STOCK -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200/60 px-2.5 py-1 rounded-full">
                                Stock
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $inventarioUds }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">UNIDADES EN BODEGA</p>
                    </div>

                    <!-- KPI 3: PEDIDOS PENDIENTES -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-amber-600 bg-amber-50 border border-amber-200/60 px-2.5 py-1 rounded-full">
                                Pendientes
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $pedidosPendientes }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">PEDIDOS POR ALISTAR</p>
                    </div>

                    <!-- KPI 4: MATERIA PRIMA -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-200/60 px-2.5 py-1 rounded-full">
                                Insumos
                            </span>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $materiaPrimaCount }}</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">MATERIA PRIMA</p>
                    </div>

                </div>

                <!-- MAIN GRID CONTENT -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <!-- LEFT: BATCHES TABLE (8 COLS) -->
                    <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Lotes de Producción Recientes</h3>
                                    <p class="text-xs text-slate-500 font-medium">Historial de embotellado y tratamiento de agua</p>
                                </div>
                                <button class="text-xs font-extrabold text-white bg-trigestion-500 hover:bg-trigestion-600 px-3.5 py-2 rounded-xl shadow-md shadow-trigestion-500/20 transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Nuevo Lote</span>
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100">
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">LOTE</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">CANTIDAD</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">ESTADO</th>
                                            <th class="px-6 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-wider">DESCRIPCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @php
                                            $lotes = \DB::table('produccion')->orderByDesc('id_produccion')->limit(6)->get();
                                        @endphp
                                        @forelse ($lotes as $lote)
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="px-6 py-4 text-xs font-black text-slate-900">{{ $lote->lote_produccion ?? '#LOT-'.$lote->id_produccion }}</td>
                                                <td class="px-6 py-4 text-xs font-black text-trigestion-600">{{ $lote->cantidad ?? 0 }} uds</td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-block text-[10px] font-extrabold px-2.5 py-1 rounded-full border bg-emerald-50 text-emerald-600 border-emerald-200">
                                                        {{ ucfirst($lote->estado ?? 'Procesado') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-xs text-slate-500 font-medium max-w-xs truncate">{{ $lote->descripcion ?? 'Agua microfiltrada y embotellada' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center">
                                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-600">No hay lotes registrados actualmente</p>
                                                    <p class="text-xs text-slate-400 mt-0.5">Los nuevos lotes de producción se mostrarán aquí.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: ACTIONS & QUALITY CONTROL (4 COLS) -->
                    <div class="lg:col-span-4 space-y-6">

                        <!-- OPERATOR ACTIONS -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                            <h3 class="text-base font-extrabold text-slate-900 mb-4">Acciones Rápidas del Operador</h3>
                            
                            <div class="space-y-3">
                                <button class="w-full flex items-center justify-between p-3.5 rounded-2xl bg-sky-50 hover:bg-sky-100 border border-sky-100 text-sky-900 font-extrabold text-xs transition-colors group">
                                    <span>Registrar Lote de Producción</span>
                                    <svg class="w-4 h-4 text-trigestion-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>

                                <button class="w-full flex items-center justify-between p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-900 font-extrabold text-xs transition-colors group">
                                    <span>Entrada de Stock a Bodega</span>
                                    <svg class="w-4 h-4 text-emerald-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>

                                <button class="w-full flex items-center justify-between p-3.5 rounded-2xl bg-amber-50 hover:bg-amber-100 border border-amber-100 text-amber-900 font-extrabold text-xs transition-colors group">
                                    <span>Marcar Pedido Despachado</span>
                                    <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- QUALITY CONTROL CARD -->
                        <div class="bg-gradient-to-br from-emerald-600 to-teal-800 rounded-3xl p-6 text-white shadow-lg shadow-emerald-600/20">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 border border-white/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-extrabold text-white">Control Sanitario OK</h4>
                                    <p class="text-[10px] text-emerald-200 font-semibold uppercase tracking-wider">NORMA SANITARIA COMPLETA</p>
                                </div>
                            </div>
                            <p class="text-xs text-emerald-100 font-medium leading-relaxed">
                                Los niveles de purificación, filtrado de ozono y laboratorio microbiano cumplen al 100% las normativas de calidad.
                            </p>
                        </div>

                    </div>

                </div>

            </main>
        </div>

    </div>

</body>
</html>
