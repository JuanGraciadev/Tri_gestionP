<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Dashboard - TRIGESTION</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:   ['"Plus Jakarta Sans"', 'sans-serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',
                            400:'#38bdf8',500:'#009ee3',600:'#0081c2',700:'#00669e',
                            800:'#005282',900:'#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family:'Outfit','Plus Jakarta Sans',sans-serif; }
        .bg-blobs { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc; }
        .blob-1,.blob-2,.blob-3 { position:absolute;filter:blur(80px);border-radius:50%;opacity:.45;animation:float 20s infinite alternate ease-in-out; }
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(14,165,233,.18) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(99,102,241,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(16,185,129,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card,.glass-panel { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        ::-webkit-scrollbar { width:8px;height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <div class="flex min-h-screen relative z-10">

        <!-- ═══ SIDEBAR CLIENTE (Rol 3 original) ═══ -->
        <aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
            <div class="px-6 py-6 border-b border-white/10 bg-black/10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-indigo-300 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                        <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">PORTAL CLIENTE</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <!-- MENÚ PRINCIPAL -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MENÚ PRINCIPAL</p>
                    <div class="space-y-1">
                        <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-sky-500/30 text-white border-l-4 border-sky-400 shadow-md backdrop-blur-sm">
                            <i class="fas fa-chart-pie text-sky-300 shrink-0"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- MI TIENDA -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MI TIENDA</p>
                    <div class="space-y-1">
                        <a href="{{ route('productos.catalogo') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-store text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Catálogo de Productos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-shopping-bag text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Mis Compras</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mi Dashboard</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Bienvenido a tu panel personal de Trigestion</p>
                </div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">
                            {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'C', 0, 1)) }}
                        </div>
                        <p class="text-xs font-extrabold text-slate-900 hidden sm:block truncate max-w-[160px]">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                        <svg class="w-4 h-4 text-slate-400 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50" style="display:none">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- BODY (Replicando la vista cliente.php original) -->
            <main class="p-8 space-y-8 flex-1 font-outfit">

                <!-- HERO DE BIENVENIDA -->
                <div class="relative rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-sky-500 via-indigo-600 to-indigo-700 p-10 text-white shadow-[0_20px_60px_rgba(14,165,233,0.35)]">
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 border border-white/20 text-white/90 text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-ping"></span>
                                Cuenta Activa
                            </div>
                            <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight mb-2">
                                ¡Hola, {{ explode(' ', Auth::user()->nombres ?? Auth::user()->name)[0] }}! 👋
                            </h1>
                            <p class="text-white/80 text-base font-medium max-w-lg">Bienvenido a tu panel personal de Trigestion. Aquí puedes ver el resumen de tus compras y gestionar tus pedidos.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full md:w-auto">
                            <a href="{{ route('productos.catalogo') }}" class="flex items-center justify-center gap-2 bg-white text-sky-600 px-7 py-4 rounded-2xl font-extrabold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-store text-lg"></i> Ir al Catálogo
                            </a>
                            <a href="#" class="flex items-center justify-center gap-2 bg-white/15 border border-white/25 text-white px-7 py-4 rounded-2xl font-extrabold backdrop-blur-md hover:bg-white/25 transition-all transform hover:-translate-y-0.5">
                                <i class="fas fa-bag-shopping text-lg"></i> Mis Pedidos
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KPI CARDS -->
                @php
                    $userVentas = \DB::table('venta')->where('id_usuario', Auth::id())->get();
                    $totalPedidos = count($userVentas);
                    $totalGastado = $userVentas->sum('total');
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl mb-4 font-black"><i class="fas fa-receipt"></i></div>
                        <div class="text-4xl font-black text-slate-800">{{ $totalPedidos }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Pedidos Totales</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl mb-4 font-black"><i class="fas fa-dollar-sign"></i></div>
                        <div class="text-3xl font-black text-slate-800">${{ number_format($totalGastado, 0) }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Total Gastado</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 border border-emerald-100 relative overflow-hidden group">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4 font-black"><i class="fas fa-circle-check"></i></div>
                        <div class="text-4xl font-black text-emerald-700">0</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Entregados</div>
                    </div>
                    <div class="glass-card rounded-[2rem] p-6 border border-amber-100 relative overflow-hidden group">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-4 font-black"><i class="fas fa-clock"></i></div>
                        <div class="text-4xl font-black text-amber-700">0</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">En Curso</div>
                    </div>
                </div>

                <!-- ACCESOS DIRECTOS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="{{ route('productos.catalogo') }}" class="glass-card rounded-[2.5rem] border border-slate-100 p-8 flex items-center gap-5 hover:border-sky-300 hover:shadow-xl transition-all group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg">Catálogo de Productos</h4>
                            <p class="text-sm text-slate-400 font-semibold mt-0.5">Explora y compra nuestros productos</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-sky-500 transition-colors"></i>
                    </a>
                    <a href="#" class="glass-card rounded-[2.5rem] border border-slate-100 p-8 flex items-center gap-5 hover:border-indigo-300 hover:shadow-xl transition-all group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg">Mis Compras</h4>
                            <p class="text-sm text-slate-400 font-semibold mt-0.5">Revisa el estado de tus pedidos</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                    </a>
                </div>

            </main>
        </div>
    </div>
</body>
</html>
