<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Catálogo de Productos - TRIGESTION</title>

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
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MENÚ PRINCIPAL</p>
                    <div class="space-y-1">
                        <a href="{{ route('cliente.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <svg class="w-5 h-5 text-sky-300/70 group-hover:text-sky-300 shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Inicio</span>
                        </a>

                        <a href="{{ route('productos.catalogo') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md backdrop-blur-sm transition-all">
                            <svg class="w-5 h-5 text-trigestion-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Catálogo de Agua</span>
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
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Catálogo de Presentaciones</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Explora nuestros productos de agua purificada y realiza tus pedidos</p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- USER PROFILE DROPDOWN MENU -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" 
                                class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all duration-200 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-trigestion-500/20 group cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-trigestion-400 text-slate-950 flex items-center justify-center font-black text-base shadow-md shadow-trigestion-400/20 shrink-0">
                                {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'C', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-xs font-extrabold text-slate-900 group-hover:text-trigestion-600 transition-colors truncate max-w-[160px]">
                                    {{ Auth::user()->nombres ?? Auth::user()->name }}
                                </p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200/60 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                                    Cliente Registrado
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
                                    <span>Mi Perfil y Dirección</span>
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
            <main class="p-8 space-y-8 flex-1 font-outfit">

                <!-- HERO BANNER -->
                <div class="bg-gradient-to-r from-trigestion-500 via-trigestion-600 to-sky-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-trigestion-500/20 animate-fade-up">
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md border border-white/30 px-3 py-1 rounded-full text-xs font-bold text-white mb-3">
                                💧 Pureza e Hidratación Garantizada
                            </span>
                            <h2 class="text-3xl font-black tracking-tight leading-tight">
                                Catálogo Oficial de Productos Trigestion
                            </h2>
                            <p class="text-sky-100 text-sm font-medium mt-1 max-w-xl leading-relaxed">
                                Selecciona entre nuestras diferentes presentaciones de agua purificada embotellada y sanitizada.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CATEGORY FILTERS & SEARCH BAR -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 glass-panel p-4 rounded-2xl animate-fade-up delay-100">
                    <!-- Category Chips -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 custom-scrollbar">
                        <a href="{{ route('productos.catalogo') }}" 
                           class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ !$filtroCat ? 'bg-trigestion-500 text-white shadow-md shadow-trigestion-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Todas las Categorías
                        </a>
                        @foreach ($categorias as $cat)
                        <a href="{{ route('productos.catalogo', ['cat' => $cat->id_categoria]) }}" 
                           class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ $filtroCat == $cat->id_categoria ? 'bg-trigestion-500 text-white shadow-md shadow-trigestion-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->nombre }}
                        </a>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <div class="relative min-w-[240px]">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                        <input type="text" id="searchCatalogo" placeholder="Buscar en el catálogo..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-400 outline-none transition-all text-xs font-semibold shadow-sm">
                    </div>
                </div>

                <!-- PRODUCTS GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-fade-up delay-200" id="catalogoGrid">
                    @foreach ($productos as $p)
                        @php
                            $imgUrl = $p->img ? asset($p->img) : null;
                        @endphp
                        <div class="catalogo-card glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(14,165,233,0.15)] hover:-translate-y-2 transition-all duration-300 group flex flex-col"
                             data-nombre="{{ strtolower($p->nombre) }}">

                            <!-- Image Area -->
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

                                <!-- Price Badge Top-Right -->
                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5 z-20">
                                    <span class="px-3 py-1 bg-white/95 backdrop-blur-md text-trigestion-700 text-sm font-black rounded-full shadow-sm border border-sky-100">
                                        ${{ number_format($p->precio, 2) }}
                                    </span>
                                    @if ($p->retornable)
                                    <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full border border-indigo-200 shadow-sm">
                                        <i class="fas fa-rotate-left mr-1"></i> Retornable
                                    </span>
                                    @endif
                                </div>

                                <!-- Category Chip Bottom-Left -->
                                <div class="absolute bottom-3 left-3 z-20">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 backdrop-blur-md rounded-xl text-[10px] font-black text-trigestion-700 border border-sky-100 shadow-sm">
                                        <i class="fas fa-folder text-[9px]"></i>
                                        {{ $p->categoria->nombre ?? 'General' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col bg-white">
                                <h3 class="text-base font-black text-slate-800 leading-tight mb-3 line-clamp-2 group-hover:text-trigestion-600 transition-colors flex-1">
                                    {{ $p->nombre }}
                                </h3>

                                <div class="mt-auto">
                                    <a href="#" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold text-xs transition-all shadow-md shadow-trigestion-500/20 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                        </svg>
                                        <span>Solicitar Pedido</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($productos->isEmpty())
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-sky-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-sky-300 border border-sky-100">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-700 mb-1">Sin productos disponibles</h3>
                        <p class="text-slate-400 text-xs font-semibold">No se encontraron presentaciones en esta categoría por el momento.</p>
                    </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

    <script>
        // Real-time Search
        document.getElementById('searchCatalogo').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.catalogo-card').forEach(card => {
                card.style.display = card.dataset.nombre.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
