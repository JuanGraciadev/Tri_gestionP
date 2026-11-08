<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Trabajador - TRIGESTION</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Outfit"', 'sans-serif'] },
                    colors: {
                        trigestion: { 50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#0ea5e9',600:'#0284c7',700:'#00669e',800:'#005282',900:'#082f49' }
                    },
                    animation: { 'fade': 'fadeIn 0.5s ease-out' },
                    keyframes: { fadeIn: { '0%': { opacity: '0', transform: 'translateY(10px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } } }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-blobs { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none; }
        .blob-1 { position:absolute;top:-10%;right:-5%;width:500px;height:500px;border-radius:9999px;background:rgba(56,189,248,0.08);filter:blur(80px); }
        .blob-2 { position:absolute;bottom:-10%;left:10%;width:450px;height:450px;border-radius:9999px;background:rgba(79,70,229,0.05);filter:blur(90px); }
        .blob-3 { position:absolute;top:40%;left:30%;width:350px;height:350px;border-radius:9999px;background:rgba(14,165,233,0.05);filter:blur(70px); }
        .glass-card { background:rgba(255,255,255,0.85);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.8); }
        .glass-panel { background:rgba(255,255,255,0.8);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-bottom:1px solid rgba(226,232,240,0.8); }
        .premium-gradient-text { background:linear-gradient(135deg, #0ea5e9 0%, #4f46e5 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .premium-shadow { box-shadow:0 20px 40px -15px rgba(14,165,233,0.08); }
        .custom-scrollbar::-webkit-scrollbar { width:5px;height:5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:rgba(15,23,42,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:rgba(14,165,233,0.2);border-radius:10px; }
        ::-webkit-scrollbar { width:8px;height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen bg-slate-50">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <div class="flex min-h-screen relative z-10">
        @include('partials.sidebar')

        <div class="flex-1 ml-[280px] flex flex-col min-w-0">
            @include('partials.topbar', ['title' => 'Panel de Trabajador', 'subtitle' => 'Control operativo y gesti&oacute;n de planta'])

            <main class="p-4 sm:p-8 md:p-10 flex-1 min-w-0 space-y-8 animate-fade">
                {{-- WELCOME BANNER (TrabajadorDashboard style) --}}
                <div class="glass-card rounded-[2.5rem] p-8 sm:p-10 border border-white/80 premium-shadow relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="text-center sm:text-left z-10">
                        <div class="inline-block px-3 py-1 rounded-full bg-sky-50 border border-sky-100 text-sky-600 font-bold text-xs mb-3 shadow-sm">
                            <i class="fas fa-hard-hat mr-1.5"></i>&Aacute;rea Operativa
                        </div>
                        <h2 class="text-2xl sm:text-4xl font-black text-slate-800 tracking-tight mb-2">
                            Panel de Operaciones de <span class="premium-gradient-text">Planta</span>
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base font-medium max-w-xl">
                            Gestiona producci&oacute;n, materia prima y control de inventario desde aqu&iacute;.
                        </p>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-sky-400 to-indigo-600 text-white rounded-3xl flex items-center justify-center text-3xl shadow-lg shadow-sky-500/20 z-10 shrink-0">
                        <i class="fas fa-flask"></i>
                    </div>
                </div>

                {{-- QUICK ACTION GRID (TrabajadorDashboard style) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Producci&oacute;n --}}
                    <a href="{{ route('produccion.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-sky-300 hover:shadow-sky-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-sky-500 group-hover:text-white">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-sky-600 transition-colors">Producci&oacute;n</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra y finaliza &oacute;rdenes de producci&oacute;n diaria.</p>
                    </a>

                    {{-- Entrada de Insumos --}}
                    <a href="{{ route('inventario-mp.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-emerald-300 hover:shadow-emerald-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-emerald-500 group-hover:text-white">
                            <i class="fas fa-truck-ramp-box"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">Entrada de Insumos</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra compras y recepciones de materia prima al almac&eacute;n.</p>
                    </a>

                    {{-- Inventario MP --}}
                    <a href="{{ route('inventario-mp.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-teal-300 hover:shadow-teal-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-teal-500 group-hover:text-white">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-teal-600 transition-colors">Inventario MP</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Consulta gr&aacute;ficas de stock y trazabilidad de insumos.</p>
                    </a>

                    {{-- Gesti&oacute;n de Lotes --}}
                    <a href="{{ route('lotes.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-indigo-300 hover:shadow-indigo-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-indigo-500 group-hover:text-white">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Gesti&oacute;n de Lotes</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra lotes de botellas y asigna caracter&iacute;sticas de envases.</p>
                    </a>

                    {{-- Inventario Productos --}}
                    <a href="{{ route('inventario-productos.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-amber-300 hover:shadow-amber-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-amber-500 group-hover:text-white">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-amber-600 transition-colors">Inventario Productos</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Verifica stock terminado y listo para distribuci&oacute;n.</p>
                    </a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
