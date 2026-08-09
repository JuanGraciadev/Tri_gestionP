<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Pedidos - TRIGESTION</title>

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
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(56,189,248,.18) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(16,185,129,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(245,158,11,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        .water-gradient { background:linear-gradient(135deg, #009ee3 0%, #0081c2 100%); }
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

        <!-- MAIN -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOPBAR -->
            @include('partials.topbar', ['title' => 'Mis Pedidos', 'subtitle' => 'Sigue el progreso de tus compras en tiempo real'])

            <!-- BODY -->
            <main class="p-8 space-y-8 flex-1 font-outfit">

                <!-- Header Banner -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden p-10 flex flex-col md:flex-row items-center justify-between gap-6 relative">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl"></div>
                    <div class="z-10">
                        <h2 class="text-3xl font-bold text-slate-800">Estado de mis <span class="text-sky-600">Pedidos</span></h2>
                        <p class="text-slate-500 mt-1">Sigue el progreso de todas tus órdenes en tiempo real.</p>
                    </div>
                    <a href="{{ route('productos.catalogo') }}" class="z-10 water-gradient text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-sky-200 hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-cart-shopping"></i> Seguir Comprando
                    </a>
                </div>

                <!-- Legend of status badges -->
                <div class="flex flex-wrap gap-4 justify-center">
                    @php
                        $estados = [
                            'Pendiente'   => ['bg-amber-100 text-amber-700',  'fa-clock'],
                            'En Proceso'  => ['bg-blue-100 text-blue-700',    'fa-gears'],
                            'Entregado'   => ['bg-emerald-100 text-emerald-700','fa-circle-check'],
                            'Cancelado'   => ['bg-red-100 text-red-700',      'fa-circle-xmark'],
                        ];
                    @endphp
                    @foreach ($estados as $e => [$cls, $ico])
                    <span class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ $cls }}">
                        <i class="fas {{ $ico }}"></i> {{ $e }}
                    </span>
                    @endforeach
                </div>

                <!-- Lista de pedidos -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800">Mis Pedidos</h3>
                            <p class="text-slate-400 text-sm mt-1">{{ count($ventas) }} pedido(s) en total</p>
                        </div>
                        <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-500 text-2xl">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>

                    <div class="p-8">
                    @if ($ventas->isEmpty())
                        <div class="text-center py-16 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                <i class="fas fa-receipt text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-700 mb-2">Aún no tienes pedidos</h3>
                            <p class="text-slate-500 mb-6">¡Explora nuestro catálogo y realiza tu primer pedido!</p>
                            <a href="{{ route('productos.catalogo') }}" class="inline-block water-gradient text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-sky-200">
                                Ir al Catálogo
                            </a>
                        </div>
                    @else
                        <!-- Filtro por Fechas -->
                        <div class="mb-8 p-6 bg-slate-50 border border-slate-100 rounded-3xl flex flex-col xl:flex-row items-center justify-between gap-4 shadow-inner">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-sky-500 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-calendar-days"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-800 text-sm">Filtrar por Fechas</h4>
                                    <p class="text-[11px] text-slate-400 font-semibold">Busca tus pedidos por rango de fecha</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                                <div class="flex items-center gap-2 flex-1 xl:flex-none">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Desde:</span>
                                    <input type="date" id="fechaDesde" onchange="filtrarPorFechas()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none text-sm font-semibold text-slate-600 shadow-sm w-full">
                                </div>
                                <div class="flex items-center gap-2 flex-1 xl:flex-none">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hasta:</span>
                                    <input type="date" id="fechaHasta" onchange="filtrarPorFechas()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none text-sm font-semibold text-slate-600 shadow-sm w-full">
                                </div>
                                <button onclick="limpiarFiltroFechas()" class="px-5 py-2.5 bg-white hover:bg-slate-100 border border-slate-200 rounded-2xl font-bold text-xs text-slate-500 transition-all flex items-center justify-center gap-1.5 shadow-sm w-full xl:w-auto cursor-pointer">
                                    <i class="fas fa-trash-can"></i> Limpiar Filtro
                                </button>
                            </div>
                        </div>

                        <div class="space-y-6 max-h-[600px] overflow-y-auto pr-3 custom-scrollbar" id="listaPedidosContainer">
                        @foreach ($ventas as $v)
                            @php
                                $estado = $v->estado ?? 'Pendiente';
                                [$estadoCls, $estadoIco] = $estados[$estado] ?? ['bg-slate-100 text-slate-600','fa-circle'];
                                $fechaFormatted = optional($v->fecha)->format('d/m/Y') ?? 'N/A';
                                $fechaFiltro = optional($v->fecha)->format('Y-m-d') ?? '';
                            @endphp
                            <div class="pedido-item-card group relative bg-white/60 backdrop-blur-md border border-slate-100 hover:border-sky-300 rounded-[2rem] overflow-hidden transition-all hover:shadow-[0_20px_50px_-12px_rgba(14,165,233,0.15)] hover:-translate-y-1"
                                 data-fecha="{{ $fechaFiltro }}">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-3xl
                                    {{ $estado === 'Entregado' ? 'bg-emerald-400' : ($estado === 'Cancelado' ? 'bg-red-400' : ($estado === 'En Proceso' ? 'bg-blue-400' : 'bg-amber-400')) }}"></div>

                                <div class="p-6 pl-8">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-sky-50 flex items-center justify-center text-sky-600 text-xl font-black shrink-0">
                                                #{{ $v->id_venta }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-lg">Pedido #{{ $v->id_venta }}</h4>
                                                <p class="text-sm text-slate-500"><i class="fas fa-calendar-alt mr-1"></i>{{ $fechaFormatted }}</p>
                                                @if (!empty($v->productos_lista))
                                                <p class="text-xs text-slate-400 mt-1 font-medium line-clamp-1">
                                                    <i class="fas fa-box mr-1"></i>{{ $v->productos_lista }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6 md:flex-row flex-col md:items-center">
                                            <div class="text-center">
                                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Total</p>
                                                <p class="text-2xl font-black text-sky-600">${{ number_format($v->total, 2) }}</p>
                                            </div>
                                            <span class="px-5 py-2 rounded-full text-sm font-bold flex items-center gap-2 {{ $estadoCls }}">
                                                <i class="fas {{ $estadoIco }}"></i> {{ $estado }}
                                            </span>
                                        </div>
                                    </div>

                                    @php
                                        $pasos = ['Pendiente','En Proceso','Entregado'];
                                        $pasoActual = array_search($estado, $pasos);
                                        if ($pasoActual === false) $pasoActual = -1;
                                    @endphp
                                    @if ($estado !== 'Cancelado')
                                    <div class="mt-6 pt-4 border-t border-slate-100">
                                        <div class="flex items-center gap-2">
                                            @foreach ($pasos as $i => $paso)
                                                @php
                                                    $activo = $i <= $pasoActual;
                                                    $actual = $i === $pasoActual;
                                                    $iconos = ['fa-clock','fa-gears','fa-circle-check'];
                                                @endphp
                                                <div class="flex-1 flex flex-col items-center gap-1">
                                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all
                                                        {{ $activo ? ($actual ? 'water-gradient text-white shadow-lg' : 'bg-emerald-100 text-emerald-600') : 'bg-slate-100 text-slate-400' }}">
                                                        <i class="fas {{ $iconos[$i] }}"></i>
                                                    </div>
                                                    <p class="text-[10px] font-bold text-center {{ $activo ? 'text-slate-700' : 'text-slate-400' }}">{{ $paso }}</p>
                                                </div>
                                                @if ($i < count($pasos)-1)
                                                <div class="flex-1 h-0.5 mb-5 {{ $pasoActual > $i ? 'bg-emerald-300' : 'bg-slate-200' }}"></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @else
                                    <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-600 font-medium flex items-center gap-2">
                                        <i class="fas fa-circle-xmark"></i> Este pedido fue cancelado.
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        function filtrarPorFechas() {
            const desdeVal = document.getElementById('fechaDesde').value;
            const hastaVal = document.getElementById('fechaHasta').value;
            const rows = document.querySelectorAll('.pedido-item-card');

            rows.forEach(row => {
                const rowFechaStr = row.getAttribute('data-fecha');
                let show = true;
                if (desdeVal && rowFechaStr < desdeVal) show = false;
                if (hastaVal && rowFechaStr > hastaVal) show = false;
                row.style.display = show ? 'block' : 'none';
            });
        }

        function limpiarFiltroFechas() {
            document.getElementById('fechaDesde').value = '';
            document.getElementById('fechaHasta').value = '';
            filtrarPorFechas();
        }
    </script>
</body>
</html>
