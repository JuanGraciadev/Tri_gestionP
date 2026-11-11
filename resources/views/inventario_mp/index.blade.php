@extends('layouts.panel')

@section('title', 'Inventario de Materia Prima')
@section('subtitle', 'Consulta el stock de insumos generado por los lotes registrados')

@section('content')
<!-- SweetAlert flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#10b981',
                            confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                            customClass: { popup: 'rounded-[2rem] font-outfit' }
                        });
                    });
                </script>
                @endif

                <!-- HEADER BANNER -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                    <div class="p-8 md:p-10 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-slate-50 to-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Stock de Insumos y Envases</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                                Inventario de <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Materia Prima</span>
                            </h2>
                            <p class="text-slate-500 mt-2 font-medium">Registros de ingreso generados al añadir detalles a los lotes. El stock se descuenta al finalizar una producción.</p>
                        </div>
                        <a href="{{ route('lotes.index') }}"
                           class="relative z-10 inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:shadow-[0_10px_25px_-5px_rgba(16,185,129,.4)] text-white px-8 py-4 rounded-[1.25rem] font-bold transition-all transform hover:-translate-y-1 tracking-wide cursor-pointer w-full md:w-auto justify-center">
                            <i class="fas fa-box-open"></i> Gestionar Lotes
                        </a>
                    </div>

                    <!-- KPI STATS -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-slate-100 border-b border-slate-100">
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-emerald-500">{{ $totalRegistros }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Registros Totales</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-trigestion-500">{{ number_format($totalIngreso) }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Unidades Disponibles</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-amber-500">{{ $conStock }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Registros con Stock</div>
                        </div>
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="relative max-w-sm">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="text"
                                   id="searchMP"
                                   placeholder="Buscar por lote, tipo de envase, proveedor..."
                                   class="pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-400 outline-none transition-all text-sm font-medium shadow-sm w-full">
                        </div>
                    </div>

                    <!-- INVENTORY TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Lote</th>
                                    <th class="px-8 py-5">Tipo de Envase</th>
                                    <th class="px-8 py-5">Capacidad</th>
                                    <th class="px-8 py-5">Proveedor</th>
                                    <th class="px-8 py-5">Stock (Ingreso)</th>
                                    <th class="px-8 py-5">Bodega</th>
                                    <th class="px-8 py-5">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="mpBody">
                                @forelse ($inventario as $item)
                                @php
                                    $codigoLote  = $item->detalle->lote->codigo_lote ?? '—';
                                    $tipoEnvase  = $item->detalle->tipo_envase ?? '—';
                                    $capacidad   = $item->detalle->capacidad ?? '—';
                                    $proveedor   = $item->detalle->proveedor ?? '—';
                                    $ingreso     = (int) $item->ingreso;
                                    $bodega      = $item->bodega ?? 'Bodega Principal';
                                    $fecha       = $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : '—';
                                    $sinStock    = $ingreso <= 0;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors group mp-row"
                                    data-search="{{ strtolower($codigoLote . ' ' . $tipoEnvase . ' ' . $proveedor . ' ' . $bodega) }}">

                                    <!-- Lote -->
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-[0.875rem] bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform shrink-0">
                                                <i class="fas fa-box text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800 text-sm group-hover:text-amber-600 transition-colors">{{ $codigoLote }}</div>
                                                <div class="text-[11px] font-bold text-slate-400">MP-{{ $item->id_inventario_materia }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Tipo Envase -->
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-slate-700 text-sm">{{ $tipoEnvase }}</span>
                                    </td>

                                    <!-- Capacidad -->
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                                            <i class="fas fa-flask text-slate-400 text-[10px]"></i>
                                            {{ $capacidad }}
                                        </span>
                                    </td>

                                    <!-- Proveedor -->
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2 text-slate-600 font-bold text-xs bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl w-max">
                                            <i class="fas fa-truck-field text-slate-400 text-[10px]"></i>
                                            {{ $proveedor }}
                                        </div>
                                    </td>

                                    <!-- Stock -->
                                    <td class="px-8 py-5">
                                        @if ($sinStock)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-black">
                                                <i class="fas fa-circle-xmark text-[10px]"></i>
                                                Sin stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-black">
                                                <i class="fas fa-check-circle text-[10px]"></i>
                                                {{ number_format($ingreso) }} uds.
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Bodega -->
                                    <td class="px-8 py-5">
                                        <span class="text-slate-600 font-bold text-sm">{{ $bodega }}</span>
                                    </td>

                                    <!-- Fecha -->
                                    <td class="px-8 py-5">
                                        <span class="text-slate-600 font-medium text-sm">{{ $fecha }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="7" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="fas fa-boxes-stacked text-3xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold text-lg mb-1">Sin registros de materia prima</p>
                                        <p class="text-slate-400 font-medium text-sm mb-5">El inventario se llena automáticamente al añadir detalles a un lote.</p>
                                        <a href="{{ route('lotes.index') }}"
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-1 transition-all">
                                            <i class="fas fa-box-open"></i> Ir a Gestión de Lotes
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- No results row (mostrado por JS cuando búsqueda no coincide) -->
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
<script src="{{ asset('js/pages/inventario_mp.js') }}"></script>
@endpush
