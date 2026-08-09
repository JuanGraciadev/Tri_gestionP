<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Centro de Ventas y Pedidos - TRIGESTION</title>

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
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(79,70,229,.15) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(16,185,129,.10) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(245,158,11,.07) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card,.glass-panel { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        .premium-gradient { background:linear-gradient(135deg, #4f46e5 0%, #009ee3 100%); }
        .premium-gradient-text { background:linear-gradient(135deg, #4f46e5 0%, #009ee3 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
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
            @include('partials.topbar', ['title' => 'Centro de Ventas y Pedidos', 'subtitle' => 'Supervisa pedidos, ingresos y estados de entrega'])

            <!-- BODY -->
            <main class="p-8 space-y-10 flex-1 font-outfit">

                <!-- Alert flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                            customClass: { popup: 'rounded-[2rem] font-outfit' }
                        });
                    });
                </script>
                @endif

                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm mb-6">
                    <ul class="list-disc list-inside text-xs font-semibold space-y-1">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- HEADER SECTION -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-sm mb-3 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>Gestión en Tiempo Real
                        </div>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                            Centro de <span class="premium-gradient-text">Ventas</span>
                        </h1>
                        <p class="text-slate-500 mt-2 text-base font-medium max-w-md">Supervisa pedidos, ingresos y estados de entrega desde un solo lugar.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        @if ($stats['Pendiente'] > 0)
                        <div class="flex items-center gap-3 bg-white border border-amber-200 text-amber-600 px-6 py-4 rounded-2xl font-bold text-sm shadow-[0_8px_30px_rgb(245,158,11,0.2)] relative overflow-hidden group cursor-pointer hover:scale-105 transition-transform">
                            <div class="relative z-10 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                <i class="fas fa-bell text-lg animate-bounce"></i>
                            </div>
                            <div class="relative z-10 flex flex-col">
                                <span class="text-xs text-amber-500 uppercase tracking-wider font-extrabold">Urgente</span>
                                <span>{{ $stats['Pendiente'] }} pedido(s)</span>
                            </div>
                        </div>
                        @endif
                        <button onclick="openModalPOS()" class="premium-gradient text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-[0_10px_40px_rgba(79,70,229,0.4)] transition-all transform hover:-translate-y-1 flex items-center gap-3 overflow-hidden relative group cursor-pointer">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 relative z-10">
                                <i class="fas fa-cash-register text-lg"></i>
                            </div>
                            <div class="relative z-10 text-left">
                                <div class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Venta Rápida</div>
                                <div class="text-base font-black">Terminal POS</div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- KPI STATS -->
                @php
                    $totalVentas = max(1, count($ventas));
                    $tarjetas = [
                        ['Pendientes', $stats['Pendiente'],  'fa-clock',        'from-amber-400 to-orange-500',  'text-amber-600',  'bg-amber-50',  'bg-amber-400',  'Esperando atención'],
                        ['En Proceso', $stats['En Proceso'], 'fa-gears',        'from-blue-400 to-indigo-500',   'text-blue-600',   'bg-blue-50',   'bg-blue-400',   'En preparación'],
                        ['Entregados', $stats['Entregado'],  'fa-circle-check', 'from-emerald-400 to-teal-500',  'text-emerald-600','bg-emerald-50','bg-emerald-400','Completados con éxito'],
                        ['Cancelados', $stats['Cancelado'],  'fa-circle-xmark', 'from-rose-400 to-red-500',      'text-rose-600',   'bg-rose-50',   'bg-rose-400',   'No procesados'],
                    ];
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($tarjetas as [$label, $val, $ico, $grad, $txt, $bg, $barColor, $desc])
                    @php $pct = $totalVentas > 0 ? round(($val / $totalVentas) * 100) : 0; @endphp
                    <div class="glass-card rounded-[2.5rem] p-7 relative overflow-hidden group cursor-default">
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div class="w-14 h-14 rounded-[1.2rem] {{ $bg }} flex items-center justify-center {{ $txt }} text-2xl border border-white/80 transform group-hover:rotate-6 group-hover:scale-110 transition-all duration-500 shadow-sm">
                                <i class="fas {{ $ico }}"></i>
                            </div>
                            @if ($label === 'Pendientes' && $val > 0)
                            <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white text-[9px] font-black px-2.5 py-1 rounded-full animate-pulse uppercase tracking-widest">¡NUEVO!</div>
                            @else
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $pct }}%</span>
                            @endif
                        </div>

                        <div class="relative z-10">
                            <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ $val }}</div>
                            <div class="text-slate-500 font-bold text-xs uppercase tracking-[0.2em] mb-1">{{ $label }}</div>
                            <div class="text-[10px] text-slate-400 font-medium mb-4">{{ $desc }}</div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- TOTAL INGRESOS BANNER -->
                <div class="relative rounded-[3rem] p-8 md:p-10 overflow-hidden shadow-2xl bg-[#0B1120] text-white">
                    <div class="absolute -top-32 -right-32 w-[400px] h-[400px] bg-gradient-to-br from-indigo-500/40 to-rose-500/40 rounded-full blur-[100px] pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 w-full">
                            <div class="w-20 h-20 rounded-[1.8rem] bg-gradient-to-br from-indigo-500 via-purple-500 to-rose-500 p-[3px] shrink-0">
                                <div class="w-full h-full bg-[#0B1120] rounded-[1.6rem] flex items-center justify-center text-indigo-300 text-3xl">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                            <div class="text-center md:text-left">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-indigo-300 text-[10px] font-bold uppercase tracking-[0.2em] mb-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Balance General Confirmado
                                </div>
                                <div class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tighter flex items-start justify-center md:justify-start gap-1">
                                    <span class="text-indigo-400 text-2xl mt-1">$</span>{{ number_format($stats['total_ingresos'], 2) }}
                                </div>
                                <p class="text-slate-400 text-xs font-medium mt-1">Ingresos totales de ventas entregadas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE PEDIDOS -->
                @php
                    $filtros = [
                        'Pendiente'  => [$stats['Pendiente'], 'amber'],
                        'En Proceso' => [$stats['En Proceso'], 'blue'],
                        'Entregado'  => [$stats['Entregado'], 'emerald'],
                        'Cancelado'  => [$stats['Cancelado'], 'red'],
                    ];
                @endphp
                <div>
                    <!-- Filtros -->
                    <div class="glass-panel rounded-[2rem] p-6 mb-6 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[1.2rem] bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Registro de <span class="premium-gradient-text">Pedidos</span></h2>
                                <p class="text-slate-500 text-xs font-medium mt-0.5">Explora y gestiona todo el historial de ventas</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto items-stretch sm:items-center">
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                                <input type="text" id="searchVentas" placeholder="Buscar cliente, pedido..."
                                    class="w-full sm:w-56 pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 outline-none transition-all text-sm font-medium shadow-sm">
                            </div>
                            <div class="flex flex-wrap gap-2 p-2 bg-slate-100/60 rounded-2xl backdrop-blur-md border border-white shadow-inner">
                                <button onclick="filtrarTabla('todos')" class="filter-btn active flex-1 xl:flex-none px-4 py-2 rounded-xl font-bold text-xs transition-all bg-white text-indigo-600 shadow-sm border border-slate-100" data-filter="todos">
                                    Todos <span class="ml-1 px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-[10px]">{{ count($ventas) }}</span>
                                </button>
                                @foreach ($filtros as $f => [$cnt, $color])
                                <button onclick="filtrarTabla('{{ $f }}')" class="filter-btn flex-1 xl:flex-none px-4 py-2 rounded-xl font-bold text-xs transition-all text-slate-500 hover:text-{{ $color }}-600 hover:bg-white" data-filter="{{ $f }}" data-color="{{ $color }}">
                                    {{ $f }} <span class="ml-1 px-2 py-0.5 rounded-lg bg-slate-200/50 text-slate-600 text-[10px] cnt-badge">{{ $cnt }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar relative min-h-[350px] pb-10">
                        <table class="w-full text-left border-separate border-spacing-y-3" id="tablaVentas">
                            <thead class="text-slate-400 text-[11px] font-black uppercase tracking-[0.15em] bg-slate-50/80 backdrop-blur-sm">
                                <tr>
                                    <th class="px-6 py-4 rounded-tl-2xl">ID Pedido</th>
                                    <th class="px-6 py-4">Información de Cliente</th>
                                    <th class="px-6 py-4">Fecha</th>
                                    <th class="px-6 py-4">Productos</th>
                                    <th class="px-6 py-4 text-right">Total Pagar</th>
                                    <th class="px-6 py-4 text-center">Estado</th>
                                    <th class="px-6 py-4 text-center rounded-tr-2xl">Gestión</th>
                                </tr>
                            </thead>
                            <tbody id="tablaBody">
                            @forelse ($ventas as $v)
                                @php
                                    $estado = $v->estado ?? 'Pendiente';
                                    $badgeStyles = [
                                        'Pendiente'  => 'bg-amber-100/80 text-amber-700 border-amber-200',
                                        'En Proceso' => 'bg-blue-100/80 text-blue-700 border-blue-200',
                                        'Entregado'  => 'bg-emerald-100/80 text-emerald-700 border-emerald-200',
                                        'Cancelado'  => 'bg-red-100/80 text-red-700 border-red-200',
                                    ][$estado] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <tr class="bg-white/80 hover:bg-white shadow-sm border border-slate-100 rounded-2xl pedido-row transition-all" data-estado="{{ $estado }}">
                                    <td class="px-6 py-5 rounded-l-2xl">
                                        <div class="inline-flex items-center gap-2 px-3 me-2 py-1.5 rounded-xl bg-slate-100 border border-slate-200 font-black text-slate-700 text-sm">
                                            <i class="fas fa-receipt text-indigo-400 text-xs"></i> #{{ str_pad($v->id_venta, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-base shrink-0">
                                                {{ strtoupper(substr($v->cliente_nombre ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-800 text-sm">{{ $v->cliente_nombre ?? 'N/A' }}</div>
                                                <div class="text-xs text-slate-400 font-medium">
                                                    @if (!empty($v->cliente_email))<span>{{ $v->cliente_email }}</span>@endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-xs font-bold text-slate-700">{{ optional($v->fecha)->format('d/m/Y') ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1 max-w-[280px]">
                                            @foreach ($v->detalles->take(2) as $d)
                                            <div class="text-xs font-bold text-slate-700 truncate">
                                                {{ $d->producto->nombre ?? 'Producto' }} <span class="text-slate-400">x{{ $d->cantidad }}</span>
                                            </div>
                                            @endforeach
                                            @if ($v->detalles->count() > 2)
                                            <div class="text-[10px] text-indigo-500 font-bold">+{{ $v->detalles->count() - 2 }} más</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-sm font-black text-emerald-600">${{ number_format($v->total, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black uppercase border {{ $badgeStyles }}">
                                            {{ $estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center rounded-r-2xl">
                                        @if ($v->esEditable())
                                        <button onclick="openModalEstado({{ $v->id_venta }}, '{{ $estado }}')"
                                            class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-500 transition-all flex items-center justify-center mx-auto cursor-pointer"
                                            title="Cambiar Estado">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </button>
                                        @else
                                        <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-300 flex items-center justify-center mx-auto">
                                            <i class="fas fa-lock text-xs"></i>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-16 text-center text-slate-400 font-medium">
                                        No hay pedidos registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- MODAL ESTADO -->
    <div id="modalEstado" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="glass-card rounded-[2.5rem] w-full max-w-md overflow-hidden border border-white">
            <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold">Actualizar Estado</h3>
                <button onclick="closeModal()" class="text-white/70 hover:text-white cursor-pointer"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('ventas.cambiarEstado') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_venta" id="modalIdVenta">
                <div class="space-y-2">
                    @foreach (['Pendiente', 'En Proceso', 'Entregado', 'Cancelado'] as $opt)
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                        <input type="radio" name="estado" value="{{ $opt }}" class="estado-radio text-indigo-600" required>
                        <span class="font-bold text-sm text-slate-700">{{ $opt }}</span>
                    </label>
                    @endforeach
                </div>
                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-500 font-bold text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md cursor-pointer">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POS -->
    <div id="modalPOS" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="glass-card rounded-[2.5rem] w-full max-w-3xl overflow-hidden my-8 border border-white">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-xl font-bold"><i class="fas fa-cash-register mr-2"></i>Punto de Venta Directa</h3>
                <button onclick="closeModalPOS()" class="text-white/70 hover:text-white cursor-pointer"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('ventas.crearPOS') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Cliente</label>
                    <select name="id_cliente" required class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 text-sm">
                        <option value="">Seleccione cliente...</option>
                        @foreach ($clientes as $cli)
                            <option value="{{ $cli->id_usuario }}">{{ $cli->nombres }} ({{ $cli->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-slate-500 uppercase">Productos</label>
                        <button type="button" onclick="addProductRow()" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 cursor-pointer">+ Agregar Fila</button>
                    </div>
                    <div id="posProductRows" class="space-y-3 max-h-[250px] overflow-y-auto pr-1"></div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Notas</label>
                    <input type="text" name="notas" placeholder="Venta en punto de venta..." class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                    <div class="text-xl font-black text-indigo-600" id="posTotalDisplay">Total: $0.00</div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModalPOS()" class="px-5 py-2.5 text-slate-500 font-bold text-sm cursor-pointer">Cancelar</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md cursor-pointer">Procesar Venta</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const productosDisponibles = @json($productosActivos);

        function openModalEstado(id, estado) {
            document.getElementById('modalIdVenta').value = id;
            document.querySelectorAll('.estado-radio').forEach(r => r.checked = (r.value === estado));
            document.getElementById('modalEstado').classList.remove('hidden');
            document.getElementById('modalEstado').classList.add('flex');
        }
        function closeModal() {
            document.getElementById('modalEstado').classList.add('hidden');
            document.getElementById('modalEstado').classList.remove('flex');
        }

        function openModalPOS() {
            document.getElementById('modalPOS').classList.remove('hidden');
            document.getElementById('modalPOS').classList.add('flex');
            if (document.querySelectorAll('.pos-row').length === 0) addProductRow();
        }
        function closeModalPOS() {
            document.getElementById('modalPOS').classList.add('hidden');
            document.getElementById('modalPOS').classList.remove('flex');
        }

        function addProductRow() {
            const container = document.getElementById('posProductRows');
            const options = productosDisponibles.map(p =>
                `<option value="${p.id_producto}" data-precio="${p.precio}">${p.nombre} — $${parseFloat(p.precio).toFixed(2)} (Stock: ${p.stock})</option>`
            ).join('');

            const row = document.createElement('div');
            row.className = 'pos-row flex gap-3 items-center bg-slate-50 p-3 rounded-xl border border-slate-200';
            row.innerHTML = `
                <select name="pos_producto[]" required onchange="updatePOSTotal()" class="flex-1 p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold">
                    <option value="">Seleccione producto...</option>
                    ${options}
                </select>
                <input type="number" name="pos_cantidad[]" value="1" min="1" required onchange="updatePOSTotal()" oninput="updatePOSTotal()" class="w-20 p-2 bg-white border border-slate-200 rounded-lg text-center text-xs font-bold">
                <button type="button" onclick="this.closest('.pos-row').remove(); updatePOSTotal();" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
            `;
            container.appendChild(row);
        }

        function updatePOSTotal() {
            let total = 0;
            document.querySelectorAll('.pos-row').forEach(row => {
                const select = row.querySelector('select');
                const qtyInput = row.querySelector('input[type="number"]');
                const opt = select.options[select.selectedIndex];
                if (opt && opt.value) {
                    const p = parseFloat(opt.dataset.precio || 0);
                    const q = parseInt(qtyInput.value || 0);
                    total += p * q;
                }
            });
            document.getElementById('posTotalDisplay').textContent = 'Total: $' + (total * 1.19).toFixed(2);
        }

        document.getElementById('searchVentas')?.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.pedido-row').forEach(r => {
                r.style.display = r.innerText.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        function filtrarTabla(estado) {
            document.querySelectorAll('.pedido-row').forEach(r => {
                r.style.display = (estado === 'todos' || r.dataset.estado === estado) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
