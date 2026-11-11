@extends('layouts.panel')

@section('title', 'Inventario de Productos')
@section('subtitle', 'Consulta el stock de productos disponibles')

@section('content')
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
                <div id="contenedorAlertasStock" class="hidden glass-card rounded-[2rem] p-6 border border-amber-200 bg-amber-50/70 shadow-sm mb-6">
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
@endsection

@push('scripts')
<script src="{{ asset('js/pages/inventario_productos.js') }}"></script>
@endpush
