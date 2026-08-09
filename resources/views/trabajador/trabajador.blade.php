<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Trabajador - TRIGESTION</title>

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
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(245,158,11,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
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

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Panel de Trabajador</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Control operativo y gestión de planta</p>
                </div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">
                            {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'T', 0, 1)) }}
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

            <!-- BODY (Replicando la vista trabajador.php original) -->
            <main class="p-8 space-y-8 flex-1 font-outfit">

                <!-- WELCOME BANNER -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden relative p-8 md:p-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 mb-2">Bienvenido a tu <span class="text-sky-600">Área de Trabajo</span></h2>
                        <p class="text-slate-500 text-base font-medium">Selecciona una de las herramientas de operaciones para comenzar.</p>
                    </div>
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center text-sky-600 text-3xl shadow-sm shrink-0">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                </div>

                <!-- CARDS DE ÁREA DE TRABAJO -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Producción -->
                    <a href="{{ route('produccion.index') }}" class="glass-card rounded-[2.5rem] p-8 border border-slate-100 hover:border-sky-300 hover:shadow-2xl hover:shadow-sky-100 transition-all group flex flex-col items-center text-center hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-sky-500 group-hover:text-white flex items-center justify-center text-2xl font-bold mb-4 transition-all shadow-sm">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-sky-600 transition-colors">Producción</h3>
                        <p class="text-sm text-slate-500">Registra y controla el flujo de producción diaria.</p>
                    </a>

                    <!-- Inventario Materia Prima -->
                    <a href="{{ route('inventario-mp.index') }}" class="glass-card rounded-[2.5rem] p-8 border border-slate-100 hover:border-emerald-300 hover:shadow-2xl hover:shadow-emerald-100 transition-all group flex flex-col items-center text-center hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-emerald-500 group-hover:text-white flex items-center justify-center text-2xl font-bold mb-4 transition-all shadow-sm">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">Inventario MP</h3>
                        <p class="text-sm text-slate-500">Consulta insumos y materia prima disponible.</p>
                    </a>

                    <!-- Gestión de Lotes -->
                    <a href="{{ route('lotes.index') }}" class="glass-card rounded-[2.5rem] p-8 border border-slate-100 hover:border-amber-300 hover:shadow-2xl hover:shadow-amber-100 transition-all group flex flex-col items-center text-center hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center text-2xl font-bold mb-4 transition-all shadow-sm">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-amber-600 transition-colors">Gestión de Lotes</h3>
                        <p class="text-sm text-slate-500">Administra lotes y detalles de envases.</p>
                    </a>

                    <!-- Inventario Productos -->
                    <a href="{{ route('inventario-productos.index') }}" class="glass-card rounded-[2.5rem] p-8 border border-slate-100 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-100 transition-all group flex flex-col items-center text-center hover:-translate-y-2">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-indigo-500 group-hover:text-white flex items-center justify-center text-2xl font-bold mb-4 transition-all shadow-sm">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Inventario Prod.</h3>
                        <p class="text-sm text-slate-500">Visualiza y controla el stock de producto terminado.</p>
                    </a>

                </div>

            </main>
        </div>
    </div>
</body>
</html>
