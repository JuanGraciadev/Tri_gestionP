@extends('layouts.panel')

@section('title', 'Inventario de Productos Terminados')
@section('subtitle', 'Consulta el stock de productos disponibles')

@section('content')
<div class="space-y-8">
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

    <!-- CONTENEDOR ALERTAS STOCK BAJO (RF 7.10 / HU-010 / HU-023) -->
    <div id="contenedorAlertasStock" class="hidden glass-card rounded-[2rem] p-6 border border-amber-200 bg-amber-50/90 shadow-sm animate-fade">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-extrabold text-amber-900 tracking-tight">Atención: Productos con Stock Bajo o Agotados</h3>
                <p class="text-xs text-amber-700 font-medium mt-0.5">Los siguientes productos terminados han alcanzado o están por debajo del stock mínimo configurado.</p>
                <div id="listaAlertasStock" class="mt-3 flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    <!-- 1. HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-50 border border-violet-100 text-violet-600 font-bold text-sm mb-3 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span>
                <span>Stock de Productos Terminados</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                Inventario de <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-indigo-600">Productos</span>
            </h1>
            <p class="text-slate-500 mt-2 text-base font-medium max-w-xl">
                Registro de entradas al inventario generadas automáticamente al finalizar las órdenes de producción.
            </p>
        </div>
        <a href="{{ route('produccion.index') }}"
           class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-lg shadow-violet-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-3 overflow-hidden relative group cursor-pointer w-full sm:w-auto justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 relative z-10 shrink-0">
                <i class="fas fa-industry text-xl"></i>
            </div>
            <div class="relative z-10 text-left">
                <div class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Producción</div>
                <div class="text-lg">Ir a Producción</div>
            </div>
        </a>
    </div>

    <!-- 2. KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Entradas Registradas -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-violet-50 flex items-center justify-center text-violet-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Entradas</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalEntradas) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Entradas Registradas</div>
                <p class="text-[10px] text-slate-400 mt-1">Lotes finalizados ingresados a bodega</p>
            </div>
        </div>

        <!-- Stock Disponible (producido − vendido) -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Disponible</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalUnidadesDisponibles) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Unidades Disponibles</div>
                <p class="text-[10px] text-slate-400 mt-1">Producido menos ventas confirmadas</p>
            </div>
        </div>

        <!-- Productos Distintos -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-amber-50 flex items-center justify-center text-amber-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Variedad</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($productosDistintos) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Productos en Catálogo</div>
                <p class="text-[10px] text-slate-400 mt-1">Productos con registro de inventario</p>
            </div>
        </div>
    </div>

    <!-- 3. MAIN TABLE CARD -->
    <div class="glass-card rounded-[2.5rem] border border-white/80 premium-shadow overflow-hidden">
        <!-- SEARCH & HEADER BAR -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[1.2rem] bg-violet-600 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                    <i class="fas fa-cubes-stacked"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Stock de Productos</h2>
                    <p class="text-slate-500 text-xs font-medium mt-0.5">Control de existencias de agua y botellones disponibles para venta</p>
                </div>
            </div>

            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="text"
                       id="searchInventario"
                       placeholder="Buscar producto, lote o bodega..."
                       class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 outline-none transition-all text-sm font-medium shadow-sm">
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Producto</th>
                        <th class="px-8 py-5">Lote de Producción</th>
                        <th class="px-8 py-5">Cantidad Entrada</th>
                        <th class="px-8 py-5">Stock Disponible</th>
                        <th class="px-8 py-5">Bodega</th>
                        <th class="px-8 py-5">Fecha</th>
                        <th class="px-8 py-5">Registrado por</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="inventarioBody">
                    @forelse ($inventario as $item)
                    @php
                        $stockDisponibleProducto = $stockDisponible[$item->id_producto] ?? 0;
                        $nombreProducto = $item->producto->nombre ?? 'Producto desconocido';
                        $loteProduccion = $item->produccion->lote_produccion ?? '—';
                        $bodega = $item->bodega ?? 'Bodega Principal';
                        $operario = $item->usuario->nombres ?? $item->usuario->name ?? 'Desconocido';
                        $fecha = $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : '—';
                        $sinStock = $stockDisponibleProducto <= 0;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors group inventario-row"
                        data-search="{{ strtolower($nombreProducto . ' ' . $loteProduccion . ' ' . $bodega) }}">

                        <!-- Producto -->
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[0.875rem] bg-violet-50 border border-violet-100 text-violet-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform shrink-0">
                                    <i class="fas fa-droplet text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 text-sm group-hover:text-violet-600 transition-colors">{{ $nombreProducto }}</div>
                                    <div class="text-[11px] font-bold text-slate-400">ID Inv: #{{ $item->id_inventario }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Lote Produccion -->
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                                <i class="fas fa-industry text-slate-400 text-[10px]"></i>
                                {{ $loteProduccion }}
                            </span>
                        </td>

                        <!-- Cantidad Entrada -->
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-black">
                                <i class="fas fa-plus text-[10px]"></i>
                                +{{ number_format($item->cantidad) }}
                            </span>
                        </td>

                        <!-- Stock Disponible (producido − vendido) -->
                        <td class="px-8 py-5">
                            @if ($sinStock)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-black">
                                    <i class="fas fa-circle-xmark text-[10px]"></i>
                                    Agotado (0)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 border border-violet-200 text-violet-700 text-sm font-black">
                                    <i class="fas fa-cubes text-[10px]"></i>
                                    {{ number_format($stockDisponibleProducto) }} uds.
                                </span>
                            @endif
                        </td>

                        <!-- Bodega -->
                        <td class="px-8 py-5">
                            <span class="text-slate-600 font-bold text-sm">{{ $bodega }}</span>
                        </td>

                        <!-- Fecha -->
                        <td class="px-8 py-5">
                            <span class="text-slate-500 font-medium text-sm">{{ $fecha }}</span>
                        </td>

                        <!-- Operario -->
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
                            <div class="w-20 h-20 bg-violet-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-violet-400 shadow-sm">
                                <i class="fas fa-cubes text-3xl"></i>
                            </div>
                            <h4 class="text-slate-700 font-black text-xl mb-1">Sin registros en el inventario</h4>
                            <p class="text-slate-400 font-medium text-sm max-w-md mx-auto mb-6">
                                El inventario de productos terminados se actualiza automáticamente al finalizar una orden de producción.
                            </p>
                            <a href="{{ route('produccion.index') }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-violet-500/30 hover:-translate-y-1 transition-all cursor-pointer">
                                <i class="fas fa-industry"></i> Ir a Producción
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- No results row (shown by JS when search doesn't match) -->
        <div id="noResultsRow" class="hidden px-8 py-16 text-center border-t border-slate-100 bg-slate-50/50">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-300 text-2xl">
                <i class="fas fa-search"></i>
            </div>
            <h4 class="text-slate-700 font-black text-lg mb-1">Sin resultados encontrados</h4>
            <p class="text-slate-400 text-sm font-medium">No se encontraron productos que coincidan con la búsqueda ingresada.</p>
        </div>

        <!-- Pagination -->
        @if ($inventario->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $inventario->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/inventario_productos.js') }}"></script>
@endpush
