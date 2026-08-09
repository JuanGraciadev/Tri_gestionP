<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventario de Productos - TRIGESTION</title>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family:'Outfit','Plus Jakarta Sans',sans-serif; }
        .bg-blobs { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc; }
        .blob-1,.blob-2,.blob-3 { position:absolute;filter:blur(80px);border-radius:50%;opacity:.45;animation:float 20s infinite alternate ease-in-out; }
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(99,102,241,.15) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(16,185,129,.10) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(245,158,11,.07) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card,.glass-panel { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        ::-webkit-scrollbar { width:8px;height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    <!-- Background Blobs -->
    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <div class="flex min-h-screen relative z-10">

        @include('partials.sidebar')

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Inventario de Productos</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Consulta el stock de productos terminados generado por producción</p>
                </div>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                        <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">
                            {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'U', 0, 1)) }}
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

            <!-- BODY -->
            <main class="p-8 flex-1 font-outfit space-y-8">

                <!-- SweetAlert flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#7c3aed',
                            confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                            customClass: { popup: 'rounded-[2rem] font-outfit' }
                        });
                    });
                </script>
                @endif

                <!-- HEADER BANNER -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                    <div class="p-8 md:p-10 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-slate-50 to-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                                <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Stock de Productos Terminados</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                                Inventario de <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-indigo-600">Productos</span>
                            </h2>
                            <p class="text-slate-500 mt-2 font-medium">Registro de entradas al inventario generadas al finalizar producciones.</p>
                        </div>
                    </div>

                    <!-- KPI STATS -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-slate-100 border-b border-slate-100">
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-violet-500">{{ $totalEntradas }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Entradas Registradas</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-emerald-500">{{ number_format($totalUnidades) }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Unidades en Stock</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-trigestion-500">{{ $productosConStock }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Productos con Stock</div>
                        </div>
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="relative max-w-sm">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="text"
                                   id="searchInventario"
                                   placeholder="Buscar por producto, lote o bodega..."
                                   class="pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 outline-none transition-all text-sm font-medium shadow-sm w-full">
                        </div>
                    </div>

                    <!-- INVENTORY TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Producto</th>
                                    <th class="px-8 py-5">Lote de Producción</th>
                                    <th class="px-8 py-5">Cantidad</th>
                                    <th class="px-8 py-5">Stock Acumulado</th>
                                    <th class="px-8 py-5">Bodega</th>
                                    <th class="px-8 py-5">Fecha</th>
                                    <th class="px-8 py-5">Registrado por</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="inventarioBody">
                                @forelse ($inventario as $item)
                                @php
                                    $stockAcumulado = $stockPorProducto[$item->id_producto] ?? 0;
                                    $nombreProducto = $item->producto->nombre ?? 'Producto desconocido';
                                    $loteProduccion = $item->produccion->lote_produccion ?? '—';
                                    $bodega = $item->bodega ?? 'Principal';
                                    $operario = $item->usuario->nombres ?? $item->usuario->name ?? 'Desconocido';
                                    $fecha = $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : '—';
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors group inventario-row"
                                    data-search="{{ strtolower($nombreProducto . ' ' . $loteProduccion . ' ' . $bodega) }}">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-[0.875rem] bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform shrink-0">
                                                <i class="fas fa-box text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800 text-sm group-hover:text-violet-600 transition-colors">{{ $nombreProducto }}</div>
                                                <div class="text-[11px] font-bold text-slate-400">ID Inv: #{{ $item->id_inventario }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                                            <i class="fas fa-industry text-slate-400 text-[10px]"></i>
                                            {{ $loteProduccion }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-black">
                                            <i class="fas fa-plus text-[10px]"></i>
                                            {{ number_format($item->cantidad) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 border border-violet-200 text-violet-700 text-sm font-black">
                                            <i class="fas fa-cubes text-[10px]"></i>
                                            {{ number_format($stockAcumulado) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-slate-600 font-bold text-sm">{{ $bodega }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-slate-600 font-medium text-sm">{{ $fecha }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2 text-slate-600 font-bold text-xs bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl w-max">
                                            <i class="fas fa-user-circle text-slate-400"></i>
                                            {{ $operario }}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="7" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="fas fa-cubes text-3xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold text-lg mb-1">Sin registros en el inventario</p>
                                        <p class="text-slate-400 font-medium text-sm">El inventario se llena automáticamente al finalizar una producción.</p>
                                        <a href="{{ route('produccion.index') }}"
                                           class="inline-flex items-center gap-2 mt-5 px-6 py-3 bg-gradient-to-r from-emerald-500 to-sky-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-1 transition-all">
                                            <i class="fas fa-industry"></i> Ir a Producción
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- No results row (hidden by default, shown by JS search) -->
                    @if ($inventario->isNotEmpty())
                    <div id="noResultsRow" class="hidden px-8 py-12 text-center border-t border-slate-100">
                        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                            <i class="fas fa-search text-xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold">Sin resultados para la búsqueda.</p>
                    </div>
                    @endif

                </div>

            </main>
        </div>
    </div>

    <script>
        // Real-time search — filters table rows by producto, lote, bodega
        document.getElementById('searchInventario').addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.inventario-row');
            let visible = 0;

            rows.forEach(function (row) {
                const match = row.dataset.search.includes(term);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const noResults = document.getElementById('noResultsRow');
            if (noResults) {
                noResults.classList.toggle('hidden', visible > 0);
            }
        });
    </script>
</body>
</html>
