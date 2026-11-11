@extends('layouts.panel')

@section('title', 'Inventario de Materia Prima')
@section('subtitle', 'Consulta el stock de insumos generado por los lotes registrados')

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
                confirmButtonColor: '#10b981',
                confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            });
        });
    </script>
    @endif

    <!-- 1. HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 font-bold text-sm mb-3 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Stock de Insumos y Envases</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                Inventario de <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Materia Prima</span>
            </h1>
            <p class="text-slate-500 mt-2 text-base font-medium max-w-xl">
                Registros de ingreso generados al añadir detalles a los lotes. El stock se descuenta al iniciar una producción.
            </p>
        </div>
        <a href="{{ route('lotes.index') }}"
           class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-3 overflow-hidden relative group cursor-pointer w-full sm:w-auto justify-center">
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 relative z-10 shrink-0">
                <i class="fas fa-box-open text-xl"></i>
            </div>
            <div class="relative z-10 text-left">
                <div class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Gestión</div>
                <div class="text-lg">Gestionar Lotes</div>
            </div>
        </a>
    </div>

    <!-- 2. KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Registros Totales -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Total</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalRegistros) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Registros Totales</div>
                <p class="text-[10px] text-slate-400 mt-1">Entradas de insumos registradas</p>
            </div>
        </div>

        <!-- Unidades Disponibles -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-sky-50 flex items-center justify-center text-sky-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-cubes"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Stock</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalIngreso) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Unidades Disponibles</div>
                <p class="text-[10px] text-slate-400 mt-1">Envases e insumos activos en bodega</p>
            </div>
        </div>

        <!-- Registros con Stock -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-amber-50 flex items-center justify-center text-amber-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-circle-check"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Activos</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($conStock) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Registros con Stock</div>
                <p class="text-[10px] text-slate-400 mt-1">Entradas con stock mayor a cero</p>
            </div>
        </div>
    </div>

    <!-- 3. MAIN TABLE CARD -->
    <div class="glass-card rounded-[2.5rem] border border-white/80 premium-shadow overflow-hidden">
        <!-- SEARCH & HEADER BAR -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[1.2rem] bg-emerald-500 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                    <i class="fas fa-dolly"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Stock en Bodega</h2>
                    <p class="text-slate-500 text-xs font-medium mt-0.5">Control de existencias por lote, tipo de envase y proveedor</p>
                </div>
            </div>

            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="text"
                       id="searchMP"
                       placeholder="Buscar por lote, envase, proveedor..."
                       class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-400 outline-none transition-all text-sm font-medium shadow-sm">
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Lote / Registro</th>
                        <th class="px-8 py-5">Tipo de Envase</th>
                        <th class="px-8 py-5">Capacidad</th>
                        <th class="px-8 py-5">Proveedor</th>
                        <th class="px-8 py-5">Stock (Ingreso)</th>
                        <th class="px-8 py-5">Bodega</th>
                        <th class="px-8 py-5">Fecha Ingreso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="mpBody">
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
                    <tr class="hover:bg-slate-50/60 transition-colors group mp-row"
                        data-search="{{ strtolower($codigoLote . ' ' . $tipoEnvase . ' ' . $proveedor . ' ' . $bodega) }}">

                        <!-- Lote -->
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[0.875rem] bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform shrink-0">
                                    <i class="fas fa-box text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 text-sm group-hover:text-emerald-600 transition-colors">{{ $codigoLote }}</div>
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black">
                                    <i class="fas fa-circle-xmark text-[10px]"></i>
                                    Sin stock (0)
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
                            <span class="text-slate-500 font-medium text-sm">{{ $fecha }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="7" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-emerald-400 shadow-sm">
                                <i class="fas fa-boxes-stacked text-3xl"></i>
                            </div>
                            <h4 class="text-slate-700 font-black text-xl mb-1">Sin registros de materia prima</h4>
                            <p class="text-slate-400 font-medium text-sm max-w-md mx-auto mb-6">
                                El inventario se alimenta automáticamente al registrar insumos en los lotes creados.
                            </p>
                            <a href="{{ route('lotes.index') }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 hover:-translate-y-1 transition-all cursor-pointer">
                                <i class="fas fa-box-open"></i> Ir a Gestión de Lotes
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
            <p class="text-slate-400 text-sm font-medium">No se encontraron insumos que coincidan con la búsqueda ingresada.</p>
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
<script src="{{ asset('js/pages/inventario_mp.js') }}"></script>
@endpush
