<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reportes Generales e Inteligencia de Negocio - TRIGESTION</title>

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

    <!-- Chart.js & PDF Export Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body { font-family:'Outfit','Plus Jakarta Sans',sans-serif; }
        .bg-blobs { position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc; }
        .blob-1,.blob-2,.blob-3 { position:absolute;filter:blur(80px);border-radius:50%;opacity:.45;animation:float 20s infinite alternate ease-in-out; }
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(79,70,229,.15) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(16,185,129,.10) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(245,158,11,.07) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }

        .glass-card,.glass-panel { background:rgba(255,255,255,.82);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.6);box-shadow:0 10px 30px -10px rgba(0,0,0,.05); }

        /* Print & PDF styling */
        @media print {
            body > *:not(#reporteWrapper) { display: none !important; }
            #reporteWrapper { display: block !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
            aside, header, nav, .no-print, button, form { display: none !important; }
            .glass-card, .glass-panel { background: white !important; backdrop-filter: none !important; box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .page-break { page-break-after: always; }
        }

        #pdfOverlay {
            display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px);
            z-index: 9999; align-items: center; justify-content: center; flex-direction: column; gap: 1rem;
        }
        #pdfOverlay.active { display: flex; }
        #pdfSpinner { width: 50px; height: 50px; border: 4px solid rgba(255,255,255,0.2); border-top-color: #38bdf8; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <!-- PDF Loading Overlay -->
    <div id="pdfOverlay">
        <div id="pdfSpinner"></div>
        <p class="text-white font-bold text-base">Generando PDF oficial, por favor espera...</p>
    </div>

    <div class="flex min-h-screen relative z-10" id="reporteWrapper">

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- MAIN -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOPBAR -->
            @include('partials.topbar', ['title' => 'Reportes Generales e Inteligencia de Negocio', 'subtitle' => 'Métricas completas de rendimiento y estado operacional'])

            <!-- BODY -->
            <main class="p-8 space-y-8 flex-1 font-outfit" id="reporteContenido">

                <!-- HEADER SECTION & FILTER BAR -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 p-8 flex flex-col xl:flex-row xl:items-center justify-between gap-6 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-xs mb-3 shadow-sm">
                            <i class="fas fa-chart-line"></i> Inteligencia de Negocio
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">
                            Reportes <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-sky-500">Generales</span>
                        </h1>
                        <p class="text-slate-500 text-sm font-medium mt-1">Período evaluado: <strong class="text-indigo-600">{{ $dateRange['label'] }}</strong></p>
                    </div>

                    <!-- DATE RANGE FILTER FORM & ACTIONS -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 no-print">
                        <form action="{{ route('reportes.index') }}" method="GET" id="filterForm" class="flex flex-wrap items-center gap-3">
                            <div class="relative">
                                <select name="filtro" id="filtroSelect" onchange="toggleCustomDates()"
                                        class="px-4 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-xs text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 outline-none transition-all shadow-sm appearance-none pr-9 cursor-pointer">
                                    <option value="hoy" {{ $dateRange['filtro'] === 'hoy' ? 'selected' : '' }}>Hoy</option>
                                    <option value="ayer" {{ $dateRange['filtro'] === 'ayer' ? 'selected' : '' }}>Ayer</option>
                                    <option value="7dias" {{ $dateRange['filtro'] === '7dias' ? 'selected' : '' }}>Últimos 7 días</option>
                                    <option value="30dias" {{ $dateRange['filtro'] === '30dias' ? 'selected' : '' }}>Últimos 30 días</option>
                                    <option value="este_mes" {{ $dateRange['filtro'] === 'este_mes' ? 'selected' : '' }}>Este mes</option>
                                    <option value="mes_anterior" {{ $dateRange['filtro'] === 'mes_anterior' ? 'selected' : '' }}>Mes anterior</option>
                                    <option value="este_ano" {{ $dateRange['filtro'] === 'este_ano' ? 'selected' : '' }}>Este año</option>
                                    <option value="personalizado" {{ $dateRange['filtro'] === 'personalizado' ? 'selected' : '' }}>Personalizado...</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>

                            <div id="customDatesWrapper" class="flex items-center gap-2 {{ $dateRange['filtro'] === 'personalizado' ? '' : 'hidden' }}">
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $dateRange['fecha_inicio'] }}"
                                    class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 outline-none shadow-sm">
                                <span class="text-xs font-bold text-slate-400">a</span>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ $dateRange['fecha_fin'] }}"
                                    class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 outline-none shadow-sm">
                            </div>

                            <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-indigo-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <button onclick="generarPDF()" class="px-5 py-3 bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-bold text-xs rounded-2xl shadow-md shadow-indigo-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button onclick="window.print()" class="px-5 py-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 font-bold text-xs rounded-2xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Ingresos Totales -->
                    <div class="glass-card rounded-[2rem] p-6 border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-black border border-emerald-100 shadow-sm">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">Confirmado</span>
                        </div>
                        <h3 class="text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Ingresos Totales</h3>
                        <p class="text-3xl font-black text-slate-800">${{ number_format($kpis['ingresos_totales'], 2) }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Total vendido sin cancelaciones</p>
                    </div>

                    <!-- Ventas Válidas -->
                    <div class="glass-card rounded-[2rem] p-6 border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-black border border-indigo-100 shadow-sm">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">Completadas</span>
                        </div>
                        <h3 class="text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Ventas Procesadas</h3>
                        <p class="text-3xl font-black text-slate-800">{{ number_format($kpis['ventas_validas']) }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Ticket Promedio: ${{ number_format($kpis['ticket_promedio'], 2) }}</p>
                    </div>

                    <!-- Productos Vendidos -->
                    <div class="glass-card rounded-[2rem] p-6 border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-black border border-sky-100 shadow-sm">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-sky-600 bg-sky-50 px-2.5 py-1 rounded-full border border-sky-100">Unidades</span>
                        </div>
                        <h3 class="text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Productos Despachados</h3>
                        <p class="text-3xl font-black text-slate-800">{{ number_format($kpis['productos_vendidos']) }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Unidades totales comercializadas</p>
                    </div>

                    <!-- Devoluciones & Retornables -->
                    <div class="glass-card rounded-[2rem] p-6 border border-slate-100 relative overflow-hidden group hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-black border border-amber-100 shadow-sm">
                                <i class="fas fa-rotate-left"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">Logística</span>
                        </div>
                        <h3 class="text-slate-400 font-bold text-[11px] uppercase tracking-wider mb-1">Envases en Consumo</h3>
                        <p class="text-3xl font-black text-slate-800">{{ number_format($kpis['devoluciones']['en_consumo']) }}</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-1">Devueltos a bodega: {{ $kpis['devoluciones']['devueltos'] }}</p>
                    </div>
                </div>

                <!-- CHARTS SECTION (2x2 GRID) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Chart 1: Ventas e Ingresos por Día -->
                    <div class="glass-card rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="fas fa-chart-line text-indigo-500"></i> Evolución de Ventas e Ingresos
                            </h3>
                        </div>
                        <div class="relative h-64">
                            @if (empty($ventasPorDia['labels']))
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 italic">
                                <i class="fas fa-chart-area text-3xl opacity-30 mb-2"></i>
                                No existen ventas registradas para el período seleccionado.
                            </div>
                            @else
                            <canvas id="chartVentasDia"></canvas>
                            @endif
                        </div>
                    </div>

                    <!-- Chart 2: Ventas por Estado -->
                    <div class="glass-card rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="fas fa-chart-pie text-emerald-500"></i> Pedidos por Estado
                            </h3>
                        </div>
                        <div class="relative h-64">
                            @if (empty($ventasPorEstado['labels']))
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 italic">
                                <i class="fas fa-chart-pie text-3xl opacity-30 mb-2"></i>
                                No existen pedidos para el período seleccionado.
                            </div>
                            @else
                            <canvas id="chartVentasEstado"></canvas>
                            @endif
                        </div>
                    </div>

                    <!-- Chart 3: Ventas por Categoría -->
                    <div class="glass-card rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="fas fa-tags text-sky-500"></i> Ventas por Categoría
                            </h3>
                        </div>
                        <div class="relative h-64">
                            @if (empty($ventasCategoria))
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 italic">
                                <i class="fas fa-folder-open text-3xl opacity-30 mb-2"></i>
                                No existen datos de categorías para el período seleccionado.
                            </div>
                            @else
                            <canvas id="chartVentasCategoria"></canvas>
                            @endif
                        </div>
                    </div>

                    <!-- Chart 4: Top 10 Productos Más Vendidos -->
                    <div class="glass-card rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="fas fa-trophy text-amber-500"></i> Top Productos Más Vendidos
                            </h3>
                        </div>
                        <div class="relative h-64">
                            @if (empty($topProductos))
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 italic">
                                <i class="fas fa-award text-3xl opacity-30 mb-2"></i>
                                No existen ventas de productos en este período.
                            </div>
                            @else
                            <canvas id="chartTopProductos"></canvas>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- TABLAS DE DETALLE -->
                <div class="space-y-8">

                    <!-- Tabla Top 10 Productos -->
                    <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-award text-amber-500"></i> Ranking: Productos Más Vendidos
                            </h3>
                            <span class="text-xs font-bold text-slate-400">Top {{ count($topProductos) }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-4">#</th>
                                        <th class="px-6 py-4">Producto</th>
                                        <th class="px-6 py-4 text-right">Precio Unitario</th>
                                        <th class="px-6 py-4 text-center">Unidades Vendidas</th>
                                        <th class="px-6 py-4 text-right">Ingresos Generados</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse ($topProductos as $idx => $p)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-black text-indigo-600">#{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4 font-bold text-slate-800">{{ $p['nombre'] }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-slate-600">${{ number_format($p['precio'], 2) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 bg-sky-100 text-sky-700 font-black rounded-full text-xs">
                                                {{ number_format($p['total_unidades']) }} uds
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-emerald-600">${{ number_format($p['total_ingresos'], 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                            No existen datos para el período seleccionado.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabla Estado del Inventario -->
                    <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-boxes-stacked text-sky-500"></i> Estado Actual del Inventario de Productos
                            </h3>
                            <div class="flex gap-2 text-xs font-bold">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full">Disponibles: {{ $kpis['inventario']['disponibles'] }}</span>
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full">Bajo Stock: {{ $kpis['inventario']['bajo_stock'] }}</span>
                                <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full">Agotados: {{ $kpis['inventario']['agotados'] }}</span>
                            </div>
                        </div>
                        <div class="overflow-x-auto max-h-[350px] custom-scrollbar">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4">Producto</th>
                                        <th class="px-6 py-4">Categoría</th>
                                        <th class="px-6 py-4 text-right">Precio</th>
                                        <th class="px-6 py-4 text-center">Stock Actual</th>
                                        <th class="px-6 py-4 text-center">Estado Inventario</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    @forelse ($estadoInventario as $inv)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold text-slate-800">{{ $inv['nombre'] }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-500">{{ $inv['categoria'] }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-slate-600">${{ number_format($inv['precio'], 2) }}</td>
                                        <td class="px-6 py-4 text-center font-black font-mono text-base text-slate-700">{{ $inv['stock'] }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase border {{ $inv['badge'] }}">
                                                {{ $inv['estado'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                            Sin productos registrados en el catálogo.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>

    <!-- SCRIPTS DE CONFIGURACIÓN DE GRÁFICOS Y PDF -->
    <script>
        function toggleCustomDates() {
            const select = document.getElementById('filtroSelect');
            const wrapper = document.getElementById('customDatesWrapper');
            if (select.value === 'personalizado') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                document.getElementById('filterForm').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b';

            // ── Chart 1: Ventas por Día (Line) ──────────────────────────────
            @if (!empty($ventasPorDia['labels']))
            new Chart(document.getElementById('chartVentasDia').getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($ventasPorDia['labels']),
                    datasets: [
                        {
                            label: 'Ingresos ($)',
                            data: @json($ventasPorDia['ingresos']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            yAxisID: 'yIngresos',
                        },
                        {
                            label: 'Cantidad Ventas',
                            data: @json($ventasPorDia['cantidades']),
                            borderColor: '#38bdf8',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4,
                            borderWidth: 2,
                            yAxisID: 'yVentas',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yIngresos: { type: 'linear', position: 'left', beginAtZero: true, grid: { color: '#f1f5f9' } },
                        yVentas:   { type: 'linear', position: 'right', beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
            @endif

            // ── Chart 2: Ventas por Estado (Doughnut) ────────────────────────
            @if (!empty($ventasPorEstado['labels']))
            new Chart(document.getElementById('chartVentasEstado').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($ventasPorEstado['labels']),
                    datasets: [{
                        data: @json($ventasPorEstado['data']),
                        backgroundColor: @json($ventasPorEstado['colors']),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'right' } }
                }
            });
            @endif

            // ── Chart 3: Ventas por Categoría (Bar) ─────────────────────────
            @php
                $catLabels = array_column($ventasCategoria, 'categoria_nombre');
                $catIngresos = array_column($ventasCategoria, 'total_ingresos');
            @endphp
            @if (!empty($catLabels))
            new Chart(document.getElementById('chartVentasCategoria').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($catLabels),
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: @json($catIngresos),
                        backgroundColor: '#38bdf8',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });
            @endif

            // ── Chart 4: Top Productos (Horizontal Bar) ─────────────────────
            @php
                $topLabels = array_column($topProductos, 'nombre');
                $topUnidades = array_column($topProductos, 'total_unidades');
            @endphp
            @if (!empty($topLabels))
            new Chart(document.getElementById('chartTopProductos').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($topLabels),
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: @json($topUnidades),
                        backgroundColor: '#f59e0b',
                        borderRadius: 8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } }
                }
            });
            @endif
        });

        // Generador de PDF oficial
        async function generarPDF() {
            const overlay = document.getElementById('pdfOverlay');
            overlay.classList.add('active');

            try {
                const { jsPDF } = window.jspdf;
                const contenido = document.getElementById('reporteContenido');

                const canvas = await html2canvas(contenido, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                });

                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('reporte_trigestion_{{ $dateRange['filtro'] }}.pdf');
            } catch (err) {
                console.error(err);
                alert('Hubo un error al generar el PDF.');
            } finally {
                overlay.classList.remove('active');
            }
        }
    </script>
</body>
</html>
