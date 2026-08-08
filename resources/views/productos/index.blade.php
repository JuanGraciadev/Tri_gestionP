<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Productos - TRIGESTION</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
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
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }

        /* Background Blobs */
        .bg-blobs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; background-color: #f8fafc; }
        .blob-1, .blob-2, .blob-3 { position: absolute; filter: blur(80px); border-radius: 50%; opacity: 0.5; animation: float 20s infinite alternate ease-in-out; }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, rgba(255,255,255,0) 70%); }
        .blob-2 { bottom: -20%; right: -10%; width: 60vw; height: 60vw; background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(255,255,255,0) 70%); animation-delay: -5s; }
        .blob-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(255,255,255,0) 70%); animation-delay: -10s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(5%, 5%) scale(1.1); } }

        /* Glassmorphism */
        .glass-card, .glass-panel { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05); }
        
        /* Upload Zone */
        .upload-zone { border: 2px dashed #cbd5e1; border-radius: 1.5rem; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s; position: relative; overflow: hidden; background: #f8fafc; }
        .upload-zone:hover { border-color: #38bdf8; background: #f0f9ff; }
        .upload-zone.drag-over { border-color: #0ea5e9; background: #e0f2fe; }
        .upload-placeholder { pointer-events: none; }
        .preview-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; background: white; display: none; padding: 1rem; }
        .has-image .preview-img { display: block; }
        .has-image .upload-placeholder { display: none; }
        .remove-btn { position: absolute; top: 0.75rem; right: 0.75rem; width: 2rem; height: 2rem; background: #ef4444; color: white; border-radius: 50%; display: none; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .has-image .remove-btn { display: flex; }
        .remove-btn:hover { background: #dc2626; transform: scale(1.1); }

        /* Animations */
        .animate-fade-up { animation: slideUp 0.6s ease-out forwards; opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-100/90 text-slate-800 min-h-screen">

    <!-- Background Blobs -->
    <div class="bg-blobs">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="blob-3"></div>
    </div>

    <div class="flex min-h-screen relative z-10">

        <!-- ═══════════ SIDEBAR ═══════════ -->
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
                        <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">SISTEMA DE GESTIÓN</span>
                    </div>
                </a>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">PRINCIPAL</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">GESTIÓN DE CATÁLOGO</p>
                    <div class="space-y-1">
                        <a href="{{ route('categorias.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-folder-tree text-sky-300/70 group-hover:text-sky-300 shrink-0 text-lg"></i>
                            <span>Gestión Categorías</span>
                        </a>
                        <a href="{{ route('productos.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md backdrop-blur-sm transition-all">
                            <svg class="w-5 h-5 text-trigestion-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Catálogo Productos</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER BAR -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Gestión de Productos</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Administra artículos, precios, categorías y disponibilidad del catálogo</p>
                </div>

                <div class="flex items-center gap-5">
                    <!-- DATE BADGE -->
                    <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-3.5 py-2 rounded-xl border border-slate-200/80 text-xs font-bold text-slate-600">
                        <svg class="w-4 h-4 text-trigestion-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('d \d\e F, Y') }}</span>
                    </div>

                    <!-- USER PROFILE DROPDOWN MENU -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" 
                                class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all duration-200 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-trigestion-500/20 group cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shadow-trigestion-500/30 shrink-0">
                                {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-xs font-extrabold text-slate-900 group-hover:text-trigestion-600 transition-colors truncate max-w-[160px]">
                                    {{ Auth::user()->nombres ?? Auth::user()->name }}
                                </p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-trigestion-600 bg-trigestion-50 px-2 py-0.5 rounded-full border border-trigestion-200/60 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-trigestion-500 animate-pulse"></span>
                                    Administrador
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-transform duration-200 shrink-0 ml-1" 
                                 :class="{ 'rotate-180': open }" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- DROPDOWN MENU -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50 divide-y divide-slate-100"
                             style="display: none;">
                            <div class="px-3.5 py-3">
                                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Cuenta de Usuario</p>
                                <p class="text-xs font-black text-slate-900 truncate mt-0.5">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-trigestion-600 transition-colors group">
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-trigestion-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Mi Perfil</span>
                                </a>
                            </div>
                            <div class="pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all duration-200 group active:scale-98">
                                        <svg class="w-4 h-4 text-red-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN BODY -->
            <main class="p-8 space-y-10 flex-1 font-outfit">

                <!-- ── Header Banner ──────────────────────────────────────────────────────────── -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 animate-fade-up">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-50 border border-sky-100 text-sky-600 font-bold text-sm mb-4 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>Catálogo Activo
                        </div>
                        <h1 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                            Catálogo de<br class="hidden md:block"/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600">Productos</span>
                        </h1>
                        <p class="text-slate-500 mt-3 text-base font-medium max-w-md">Administra precios, imágenes y estado de cada artículo del inventario.</p>
                    </div>
                    <button onclick="openModal('modalCrear')"
                        class="bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-[0_10px_40px_rgba(14,165,233,0.4)] transition-all transform hover:-translate-y-2 hover:shadow-[0_15px_50px_rgba(99,102,241,0.5)] flex items-center gap-3 overflow-hidden relative group w-full md:w-auto justify-center cursor-pointer">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 relative z-10 shrink-0">
                            <i class="fas fa-plus text-xl"></i>
                        </div>
                        <div class="relative z-10 text-left">
                            <div class="text-xs text-white/80 uppercase tracking-wider font-bold">Agregar</div>
                            <div class="text-lg">Nuevo Producto</div>
                        </div>
                    </button>
                </div>

                <!-- ── Flash Alert via SweetAlert2 ───────────────────────────────────────────── -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text: '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#0ea5e9',
                            confirmButtonText: '<i class="fas fa-check mr-2"></i>Entendido',
                            customClass: { popup: 'rounded-[2rem] font-outfit' }
                        });
                    });
                </script>
                @endif

                <!-- Validation Errors Alert -->
                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fas fa-triangle-exclamation"></i>
                        <span>Por favor corrige los errores del formulario:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- ── Stats Grid ───────────────────────────────────────────────────────────── -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-up delay-100">
                    @php
                    $stats = [
                        ['Total Productos',  $totalProductos,  'fa-boxes-stacked', 'from-sky-400 to-indigo-500',    'text-sky-600',     'bg-sky-50',     'shadow-[0_10px_30px_rgba(14,165,233,0.15)]'],
                        ['Activos',          $activos,          'fa-circle-check',  'from-emerald-400 to-teal-500',  'text-emerald-600', 'bg-emerald-50', 'shadow-[0_10px_30px_rgba(16,185,129,0.15)]'],
                        ['Inactivos',        $inactivos,        'fa-circle-xmark',  'from-rose-400 to-red-500',      'text-rose-600',    'bg-rose-50',    'shadow-[0_10px_30px_rgba(225,29,72,0.15)]'],
                        ['Categorías',       $totalCategorias,  'fa-folder-open',   'from-violet-400 to-purple-500', 'text-violet-600',  'bg-violet-50',  'shadow-[0_10px_30px_rgba(139,92,246,0.15)]'],
                    ];
                    @endphp
                    @foreach ($stats as [$label, $val, $ico, $grad, $txt, $bg, $shadow])
                    <div class="stat-card glass-card rounded-[2.5rem] p-7 relative overflow-hidden group cursor-default">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br {{ $grad }} opacity-10 rounded-full blur-[30px] group-hover:opacity-25 group-hover:scale-150 transition-all duration-700"></div>
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div class="w-14 h-14 rounded-[1.2rem] {{ $bg }} flex items-center justify-center {{ $txt }} text-2xl border border-white/80 {{ $shadow }} transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-500">
                                <i class="fas {{ $ico }}"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <div class="text-5xl font-black text-slate-800 tracking-tighter mb-1 group-hover:translate-x-1 transition-transform">{{ $val }}</div>
                            <div class="text-slate-500 font-bold text-xs uppercase tracking-[0.2em] group-hover:text-slate-700 transition-colors">{{ $label }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- ── Grid de Productos ───────────────────────────────────────────────── -->
                <div class="animate-fade-up delay-200">
                    <!-- Toolbar -->
                    <div class="glass-panel rounded-[2rem] p-5 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[1.2rem] bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-sky-500/30 shrink-0">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800 tracking-tight">Todos los <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600">Productos</span></h2>
                                <p class="text-slate-400 text-xs font-medium mt-0.5">{{ $totalProductos }} artículos registrados</p>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-auto">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="text" id="searchProductos" placeholder="Buscar producto..."
                                class="pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all text-sm font-medium shadow-sm w-full sm:w-56">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productosGrid">
                        @foreach ($productos as $p)
                            @php
                                $activo = (bool) $p->estado;
                                $imgUrl = $p->img ? asset($p->img) : null;
                            @endphp
                            <div class="producto-card glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(14,165,233,0.15)] hover:-translate-y-2 transition-all duration-300 group flex flex-col"
                                 data-nombre="{{ strtolower($p->nombre) }}">

                                <!-- Image area -->
                                <div class="h-52 relative overflow-hidden flex items-center justify-center p-6"
                                     style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)">
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(14,165,233,0.12)_0,transparent_60%)] group-hover:opacity-150 transition-opacity duration-500"></div>

                                    @if ($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $p->nombre }}"
                                             class="max-w-[72%] max-h-[72%] object-contain relative z-10 group-hover:scale-110 transition-transform duration-700 drop-shadow-xl">
                                    @else
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-white/80 flex items-center justify-center relative z-10 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg border border-sky-100">
                                            <i class="fas fa-box text-4xl text-sky-300"></i>
                                        </div>
                                    @endif

                                    <!-- Badges top-right -->
                                    <div class="absolute top-3 right-3 flex flex-col items-end gap-2 z-20">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm backdrop-blur-md
                                            {{ $activo ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                            {{ $activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        @if ($p->retornable)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm backdrop-blur-md bg-indigo-50 text-indigo-600 border-indigo-200">
                                            <i class="fas fa-rotate-left mr-1"></i> Retornable
                                        </span>
                                        @endif
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-sky-700 text-[12px] font-black rounded-full shadow-sm border border-sky-100">
                                            ${{ number_format($p->precio, 2) }}
                                        </span>
                                    </div>

                                    <!-- Category chip bottom-left -->
                                    <div class="absolute bottom-3 left-3 z-20">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 backdrop-blur-md rounded-xl text-[10px] font-black text-indigo-600 border border-indigo-100 shadow-sm">
                                            <i class="fas fa-folder text-[9px]"></i>
                                            {{ $p->categoria->nombre ?? 'Sin categoría' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-5 flex-1 flex flex-col bg-white">
                                    <h3 class="text-base font-black text-slate-800 leading-tight mb-4 line-clamp-2 group-hover:text-sky-600 transition-colors flex-1">
                                        {{ $p->nombre }}
                                    </h3>

                                    <div class="flex items-center gap-2 mt-auto">
                                        <!-- Edit button -->
                                        <button onclick="openEditModal({{ json_encode($p) }})"
                                            class="flex-1 py-2.5 rounded-2xl bg-slate-50 border-2 border-slate-100 text-slate-500 font-bold hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-[0_4px_15px_rgba(99,102,241,0.15)] transition-all flex items-center justify-center gap-2 text-xs cursor-pointer">
                                            <i class="fas fa-pen text-xs"></i> Editar
                                        </button>

                                        <!-- Toggle Status link -->
                                        <a href="{{ route('productos.toggleEstado', $p->id_producto) }}"
                                            class="flex-1 py-2.5 rounded-2xl bg-slate-50 border-2 border-slate-100 font-bold transition-all flex items-center justify-center gap-2 text-xs
                                            {{ $activo ? 'text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 hover:shadow-[0_4px_15px_rgba(225,29,72,0.15)]' : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 hover:shadow-[0_4px_15px_rgba(16,185,129,0.15)]' }}">
                                            <i class="fas {{ $activo ? 'fa-ban' : 'fa-check-circle' }} text-xs"></i>
                                            {{ $activo ? 'Inhabilitar' : 'Activar' }}
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('productos.destroy', $p->id_producto) }}" method="POST" class="inline" id="deleteProductForm{{ $p->id_producto }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteProduct({{ $p->id_producto }})"
                                                class="w-10 h-10 rounded-2xl bg-slate-50 border-2 border-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 hover:shadow-[0_4px_15px_rgba(225,29,72,0.15)] transition-all flex items-center justify-center shrink-0 cursor-pointer"
                                                title="Eliminar producto">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($productos->isEmpty())
                        <div class="col-span-full py-24 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-sky-50 to-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-sky-300 shadow-inner border border-sky-100">
                                <i class="fas fa-box-open text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-700 mb-2">Sin productos registrados</h3>
                            <p class="text-slate-400 font-medium mb-6">Agrega tu primer artículo al catálogo.</p>
                            <button onclick="openModal('modalCrear')" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-sky-500/30 hover:-translate-y-1 transition-all cursor-pointer">
                                <i class="fas fa-plus"></i> Crear primer producto
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- ── Modal Crear Producto ──────────────────────────────────────────────── -->
    <div id="modalCrear" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300">
        <div class="glass-card rounded-[2.5rem] w-full max-w-xl overflow-hidden my-8 transform scale-95 transition-transform duration-300 border border-white shadow-[0_30px_60px_rgba(0,0,0,0.15)]" id="modalCrearContent">
            <div class="relative p-8 overflow-hidden bg-gradient-to-r from-sky-500 to-indigo-600">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 text-white text-xl">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-white tracking-tight">Nuevo Producto</h3>
                            <p class="text-white/70 text-sm font-medium mt-0.5">Agrega un artículo al catálogo</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModalAnim('modalCrear','modalCrearContent')" class="w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-rose-500 transition-all border border-white/30 flex items-center justify-center cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Nombre del Producto</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fas fa-tag text-sm"></i></span>
                        <input type="text" name="nombre" required placeholder="Ej. Garrafón 20L"
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700 placeholder:text-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Precio ($)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold text-sm">$</span>
                            <input type="number" name="precio" required min="0" step="0.01" placeholder="0.00"
                                class="w-full pl-8 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Categoría</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 z-10"><i class="fas fa-folder text-sm"></i></span>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10 text-xs"></i>
                            <select name="id_categoria" required class="w-full pl-11 pr-9 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer">
                                <option value="">Seleccione...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">¿Es Retornable?</label>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="retornable" value="1" id="create_retornable" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                            <span class="ml-3 text-sm font-medium text-slate-600">Sí, el envase es reutilizable y se devuelve</span>
                        </label>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Imagen del Producto <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-400 text-[10px] normal-case font-bold">Opcional</span>
                    </label>
                    <div class="upload-zone" id="createUploadZone" onclick="document.getElementById('create_img_file').click()">
                        <input type="file" name="img_file" id="create_img_file" accept="image/jpeg,image/png,image/webp,image/gif,image/jpg" class="hidden" onchange="handleFileSelect(this, 'createUploadZone', 'createPreview')">
                        <img id="createPreview" class="preview-img" alt="Preview">
                        <button type="button" class="remove-btn" onclick="event.stopPropagation(); removeImage('createUploadZone', 'create_img_file', 'createPreview')">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="upload-placeholder">
                            <div class="w-14 h-14 bg-sky-50 border border-sky-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-cloud-arrow-up text-2xl text-sky-400"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Haz clic o arrastra una imagen aquí</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP o GIF · Máx. 5MB</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModalAnim('modalCrear','modalCrearContent')" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-sky-500/30 transition-all transform active:scale-95 hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Modal Editar Producto ──────────────────────────────────────────────── -->
    <div id="modalEditar" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300">
        <div class="glass-card rounded-[2.5rem] w-full max-w-xl overflow-hidden my-8 transform scale-95 transition-transform duration-300 border border-white shadow-[0_30px_60px_rgba(0,0,0,0.15)]" id="modalEditarContent">
            <div class="relative p-8 overflow-hidden bg-gradient-to-r from-slate-800 to-slate-700">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-sky-500/20 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 text-white text-xl">
                            <i class="fas fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-white tracking-tight">Editar Producto</h3>
                            <p class="text-white/60 text-sm font-medium mt-0.5" id="editProductoSubtitle">Modifica los detalles del producto</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModalAnim('modalEditar','modalEditarContent')" class="w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white hover:text-rose-500 transition-all border border-white/20 flex items-center justify-center cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="formEditar" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="img_actual" id="edit_img_actual">
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Nombre del Producto</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"><i class="fas fa-tag text-sm"></i></span>
                        <input type="text" name="nombre" id="edit_nombre" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Precio ($)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold text-sm">$</span>
                            <input type="number" name="precio" id="edit_precio" required min="0" step="0.01"
                                class="w-full pl-8 pr-4 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Categoría</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 z-10"><i class="fas fa-folder text-sm"></i></span>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10 text-xs"></i>
                            <select name="id_categoria" id="edit_id_categoria" required class="w-full pl-11 pr-9 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer">
                                <option value="">Seleccione...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">¿Es Retornable?</label>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="retornable" value="1" id="edit_retornable" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                            <span class="ml-3 text-sm font-medium text-slate-600">Sí, el envase es reutilizable y se devuelve</span>
                        </label>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Imagen del Producto <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-400 text-[10px] normal-case font-bold">Vacío = mantener actual</span>
                    </label>
                    <div class="upload-zone" id="editUploadZone" onclick="document.getElementById('edit_img_file').click()">
                        <input type="file" name="img_file" id="edit_img_file" accept="image/jpeg,image/png,image/webp,image/gif,image/jpg" class="hidden" onchange="handleFileSelect(this, 'editUploadZone', 'editPreview')">
                        <img id="editPreview" class="preview-img" alt="Preview">
                        <button type="button" class="remove-btn" onclick="event.stopPropagation(); removeImage('editUploadZone', 'edit_img_file', 'editPreview')">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="upload-placeholder">
                            <div class="w-14 h-14 bg-sky-50 border border-sky-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-cloud-arrow-up text-2xl text-sky-400"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Haz clic o arrastra una nueva imagen</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP o GIF · Máx. 5MB</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModalAnim('modalEditar','modalEditarContent')" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-sky-500/30 transition-all transform active:scale-95 hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-floppy-disk"></i> Actualizar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── JavaScript Utilities & Modal Handlers ────────────────────────────── -->
    <script>
        function openModal(id) {
            const modal   = document.getElementById(id);
            const content = document.getElementById(id + 'Content');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.style.opacity = '1';
                if (content) content.style.transform = 'scale(1)';
            });
        }

        function closeModalAnim(modalId, contentId) {
            const modal   = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            modal.style.opacity = '0';
            if (content) content.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.style.opacity = '';
                if (content) content.style.transform = '';
            }, 300);
        }

        function openEditModal(producto) {
            const updateUrl = "{{ route('productos.update', ':id') }}".replace(':id', producto.id_producto);
            document.getElementById('formEditar').action = updateUrl;
            document.getElementById('edit_nombre').value = producto.nombre;
            document.getElementById('edit_precio').value = producto.precio;
            document.getElementById('edit_id_categoria').value = producto.id_categoria;
            document.getElementById('edit_img_actual').value = producto.img || '';
            document.getElementById('edit_retornable').checked = (producto.retornable == 1 || producto.retornable === true);
            document.getElementById('editProductoSubtitle').textContent = 'Editando: ' + producto.nombre;

            const zone    = document.getElementById('editUploadZone');
            const preview = document.getElementById('editPreview');
            if (producto.img) {
                preview.src = "{{ asset('') }}" + producto.img;
                zone.classList.add('has-image');
            } else {
                preview.src = '';
                zone.classList.remove('has-image');
            }
            document.getElementById('edit_img_file').value = '';
            openModal('modalEditar');
        }

        function confirmDeleteProduct(idProducto) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el producto permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteProductForm' + idProducto).submit();
                }
            });
        }

        // ── Upload zone handlers ──
        function handleFileSelect(input, zoneId, previewId) {
            const zone = document.getElementById(zoneId);
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'Archivo muy grande', 
                        text: 'La imagen no debe superar 5MB.', 
                        confirmButtonColor: '#0ea5e9', 
                        customClass: { popup: 'rounded-[2rem] font-outfit' } 
                    });
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    zone.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(zoneId, inputId, previewId) {
            const zone = document.getElementById(zoneId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            input.value = '';
            preview.src = '';
            zone.classList.remove('has-image');
        }

        // Drag & drop support
        document.querySelectorAll('.upload-zone').forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('drag-over');
            });
            zone.addEventListener('dragleave', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
            });
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                const fileInput = zone.querySelector('input[type="file"]');
                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        });

        // Close modal on outside click
        window.addEventListener('click', function(e) {
            ['modalCrear','modalEditar'].forEach(id => {
                if (e.target.id === id) closeModalAnim(id, id + 'Content');
            });
        });

        // Real-time Search
        document.getElementById('searchProductos').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.producto-card').forEach(card => {
                card.style.display = card.dataset.nombre.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
