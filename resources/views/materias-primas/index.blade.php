@extends('layouts.panel')

@section('title', 'Insumos y Materias Primas')
@section('subtitle', 'Gestión de etiquetas, bolsas para pacas, tapas y suministros de planta')

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
                confirmButtonColor: '#0d9488',
                confirmButtonText:  '<i class="fas fa-check mr-2"></i>Entendido',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            });
        });
    </script>
    @endif

    @if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm">
        <ul class="list-disc list-inside text-xs font-semibold space-y-1">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <!-- 1. HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-50 border border-teal-100 text-teal-600 font-bold text-sm mb-3 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                <span>Insumos de Empaque y Envasado</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                Insumos y <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-600">Materias Primas</span>
            </h1>
            <p class="text-slate-500 mt-2 text-base font-medium max-w-xl">
                Control de etiquetas, bolsas para pacas, tapas y suministros necesarios para los procesos de purificación y envasado.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <button onclick="openModal('modalEntradaStock')"
                    class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-4 rounded-[1.25rem] font-bold shadow-sm transition-all flex items-center justify-center gap-2 text-sm cursor-pointer flex-1 sm:flex-none">
                <i class="fas fa-plus text-teal-600"></i> Entrada Rápida
            </button>
            <button onclick="openModal('modalCrearInsumo')"
                    class="bg-gradient-to-r from-teal-500 to-emerald-600 hover:shadow-[0_10px_25px_-5px_rgba(20,184,166,.4)] text-white px-8 py-4 rounded-[1.25rem] font-bold transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 tracking-wide cursor-pointer flex-1 sm:flex-none shadow-md">
                <i class="fas fa-plus-circle"></i> Nuevo Insumo
            </button>
        </div>
    </div>

    <!-- 2. KPI STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Insumos Registrados -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-teal-50 flex items-center justify-center text-teal-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-tags"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Catálogo</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalInsumos) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Insumos Registrados</div>
                <p class="text-[10px] text-slate-400 mt-1">Etiquetas, bolsas de pacas y tapas activas</p>
            </div>
        </div>

        <!-- Stock Total de Unidades -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] bg-sky-50 flex items-center justify-center text-sky-600 text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Total</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalStock) }}</div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Existencias en Bodega</div>
                <p class="text-[10px] text-slate-400 mt-1">Suma global de unidades/millares disponibles</p>
            </div>
        </div>

        <!-- Alertas de Stock Bajo -->
        <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-5 relative z-10">
                <div class="w-14 h-14 rounded-[1.2rem] {{ ($bajoStock + $agotados) > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-2xl border border-white/80 shadow-sm group-hover:scale-110 transition-transform">
                    <i class="fas {{ ($bajoStock + $agotados) > 0 ? 'fa-triangle-exclamation' : 'fa-check-circle' }}"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">Atención</span>
            </div>
            <div class="relative z-10">
                <div class="text-4xl font-black {{ ($bajoStock + $agotados) > 0 ? 'text-amber-600' : 'text-slate-800' }} tracking-tighter mb-1">
                    {{ $bajoStock + $agotados }}
                </div>
                <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Insumos por Reponer</div>
                <p class="text-[10px] text-slate-400 mt-1">
                    {{ $agotados }} agotado(s) · {{ $bajoStock }} bajo el mínimo
                </p>
            </div>
        </div>
    </div>

    <!-- 3. MAIN TABLE CARD -->
    <div class="glass-card rounded-[2.5rem] border border-white/80 premium-shadow overflow-hidden">
        <!-- SEARCH & HEADER BAR -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[1.2rem] bg-teal-600 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                    <i class="fas fa-boxes-packing"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Inventario de Insumos</h2>
                    <p class="text-slate-500 text-xs font-medium mt-0.5">Control de existencias de etiquetas, bolsas para pacas y tapas</p>
                </div>
            </div>

            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                <input type="text"
                       id="searchInsumo"
                       placeholder="Buscar por nombre, código o bodega..."
                       class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 outline-none transition-all text-sm font-medium shadow-sm">
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Código / Insumo</th>
                        <th class="px-8 py-5">Unidad</th>
                        <th class="px-8 py-5">Stock Actual</th>
                        <th class="px-8 py-5">Stock Mínimo</th>
                        <th class="px-8 py-5">Costo Unit.</th>
                        <th class="px-8 py-5">Bodega</th>
                        <th class="px-8 py-5 text-center">Estado</th>
                        <th class="px-8 py-5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="insumosBody">
                    @forelse ($materias as $item)
                    @php
                        $stockVal = (float) $item->stock;
                        $minVal   = (float) $item->stock_minimo;
                        $esAgotado = $stockVal <= 0;
                        $esBajo    = !$esAgotado && ($stockVal <= $minVal);

                        $badgeStock = match(true) {
                            $esAgotado => 'bg-rose-50 text-rose-700 border-rose-200',
                            $esBajo    => 'bg-amber-50 text-amber-700 border-amber-200',
                            default    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        };

                        $iconType = match(true) {
                            stripos($item->nombre, 'etiqueta') !== false => 'fa-tag',
                            stripos($item->nombre, 'bolsa') !== false || stripos($item->nombre, 'paca') !== false => 'fa-bag-shopping',
                            stripos($item->nombre, 'tapa') !== false => 'fa-circle-dot',
                            default => 'fa-box',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors group insumo-row"
                        data-search="{{ strtolower(($item->codigo ?? '') . ' ' . $item->nombre . ' ' . ($item->bodega ?? '')) }}">

                        <!-- Nombre e Icono -->
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[0.875rem] bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform shrink-0">
                                    <i class="fas {{ $iconType }} text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 text-sm group-hover:text-teal-600 transition-colors">{{ $item->nombre }}</div>
                                    <div class="text-[11px] font-bold text-slate-400">
                                        {{ $item->codigo ? 'Cód: ' . $item->codigo : 'ID #' . $item->id_materia_prima }}
                                    </div>
                                    @if($item->descripcion)
                                    <div class="text-[10px] text-slate-400 italic line-clamp-1">{{ $item->descripcion }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Unidad de Medida -->
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold">
                                {{ $item->unidad_medida ?? 'Unidades' }}
                            </span>
                        </td>

                        <!-- Stock Actual -->
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black border {{ $badgeStock }}">
                                @if($esAgotado)
                                    <i class="fas fa-times-circle text-[10px]"></i> Agotado (0)
                                @elseif($esBajo)
                                    <i class="fas fa-triangle-exclamation text-[10px]"></i> {{ number_format($stockVal) }} (Bajo)
                                @else
                                    <i class="fas fa-check-circle text-[10px]"></i> {{ number_format($stockVal) }}
                                @endif
                            </span>
                        </td>

                        <!-- Stock Mínimo -->
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-slate-500">{{ number_format($minVal) }} un.</span>
                        </td>

                        <!-- Costo Unitario -->
                        <td class="px-8 py-5">
                            <span class="text-xs font-black text-slate-700">${{ number_format($item->costo_unitario ?? 0, 2) }}</span>
                        </td>

                        <!-- Bodega -->
                        <td class="px-8 py-5">
                            <span class="text-slate-600 font-bold text-xs">{{ $item->bodega ?? 'Bodega Principal' }}</span>
                        </td>

                        <!-- Estado -->
                        <td class="px-8 py-5 text-center">
                            @if($item->estado)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-circle text-[6px]"></i> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                    <i class="fas fa-ban text-[6px]"></i> Inactivo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Botón Entrada Rápida -->
                                <button type="button"
                                        onclick="prepararEntrada({{ $item->id_materia_prima }}, '{{ addslashes($item->nombre) }}', '{{ $item->unidad_medida }}')"
                                        class="w-9 h-9 rounded-xl bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-600 transition-all flex items-center justify-center border border-teal-100 hover:border-teal-600 cursor-pointer shadow-sm"
                                        title="Registrar Entrada de Stock (+)">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>

                                <!-- Botón Editar -->
                                <button type="button"
                                        onclick="abrirEditarInsumo({{ json_encode($item) }})"
                                        class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-500 transition-all flex items-center justify-center cursor-pointer shadow-sm"
                                        title="Editar Insumo">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </button>

                                <!-- Botón Toggle Estado -->
                                <form action="{{ route('materias-primas.toggleEstado', $item->id_materia_prima) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="w-9 h-9 rounded-xl {{ $item->estado ? 'bg-amber-50 text-amber-600 hover:bg-amber-600' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600' }} hover:text-white transition-all flex items-center justify-center cursor-pointer shadow-sm"
                                            title="{{ $item->estado ? 'Desactivar Insumo' : 'Activar Insumo' }}">
                                        <i class="fas fa-power-off text-xs"></i>
                                    </button>
                                </form>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('materias-primas.destroy', $item->id_materia_prima) }}" method="POST" class="inline" id="deleteForm-{{ $item->id_materia_prima }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            onclick="confirmarEliminarInsumo({{ $item->id_materia_prima }}, '{{ addslashes($item->nombre) }}')"
                                            class="w-9 h-9 rounded-xl bg-rose-50 hover:bg-rose-600 hover:text-white text-rose-500 transition-all flex items-center justify-center cursor-pointer shadow-sm"
                                            title="Eliminar Insumo">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="8" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-teal-50 rounded-3xl flex items-center justify-center mx-auto mb-4 text-teal-500 shadow-sm">
                                <i class="fas fa-tags text-3xl"></i>
                            </div>
                            <h4 class="text-slate-700 font-black text-xl mb-1">Sin insumos registrados</h4>
                            <p class="text-slate-400 font-medium text-sm max-w-md mx-auto mb-6">
                                Registra etiquetas, bolsas para pacas, tapas y materias primas para controlar su inventario y costos.
                            </p>
                            <button onclick="openModal('modalCrearInsumo')"
                                    class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-teal-500/30 hover:-translate-y-1 transition-all cursor-pointer">
                                <i class="fas fa-plus-circle"></i> Registrar Primer Insumo
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="noResultsRow" class="hidden px-8 py-16 text-center border-t border-slate-100 bg-slate-50/50">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-300 text-2xl">
                <i class="fas fa-search"></i>
            </div>
            <h4 class="text-slate-700 font-black text-lg mb-1">Sin resultados encontrados</h4>
            <p class="text-slate-400 text-sm font-medium">No se encontraron insumos que coincidan con la búsqueda ingresada.</p>
        </div>
    </div>
</div>

<!-- ══ Modal Crear Insumo ══ -->
<div id="modalCrearInsumo" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
    <div class="glass-card rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl border border-white my-8">
        <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg font-bold">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800">Nuevo Insumo / Materia Prima</h3>
                    <p class="text-xs text-slate-400 font-medium">Etiquetas, bolsas para pacas, tapas, empaques...</p>
                </div>
            </div>
            <button onclick="closeModal('modalCrearInsumo')" class="text-slate-400 hover:text-rose-500 cursor-pointer text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('materias-primas.store') }}" method="POST" class="p-6 md:p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Código Insumo <span class="text-slate-300 font-normal">(Opcional)</span></label>
                    <input type="text" name="codigo" placeholder="Ej. ETIQ-20L, BOL-PACA15" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Unidad de Medida</label>
                    <select name="unidad_medida" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 cursor-pointer">
                        <option value="Unidades">Unidades (uds.)</option>
                        <option value="Millares">Millares (1.000 un.)</option>
                        <option value="Rollos">Rollos</option>
                        <option value="Paquetes">Paquetes</option>
                        <option value="Kilogramos">Kilogramos (kg)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Nombre del Insumo</label>
                <input type="text" name="nombre" required placeholder="Ej. Bolsas para Pacas x 15 unidades / Etiquetas 500ml" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Stock Inicial</label>
                    <input type="number" name="stock" value="0" min="0" step="1" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Stock Mínimo</label>
                    <input type="number" name="stock_minimo" value="10" min="0" step="1" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Costo Unit. ($)</label>
                    <input type="number" name="costo_unitario" value="0.00" min="0" step="0.01" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Bodega / Ubicación</label>
                    <input type="text" name="bodega" value="Bodega Insumos" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Descripción / Notas</label>
                    <input type="text" name="descripcion" placeholder="Plástico termoencogible calibre 1.5, etc." class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalCrearInsumo')" class="px-6 py-3 text-slate-500 font-bold text-sm cursor-pointer">Cancelar</button>
                <button type="submit" class="bg-gradient-to-r from-teal-500 to-emerald-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all cursor-pointer">
                    Guardar Insumo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══ Modal Entrada Rápida de Stock ══ -->
<div id="modalEntradaStock" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="glass-card rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl border border-white">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg font-bold">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">Entrada de Insumos</h3>
                    <p class="text-xs text-slate-400 font-medium">Incrementar stock por compra o recepción</p>
                </div>
            </div>
            <button onclick="closeModal('modalEntradaStock')" class="text-slate-400 hover:text-rose-500 cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('materias-primas.entrada') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Insumo a Recibir</label>
                <select name="id_materia_prima" id="entradaSelectInsumo" required class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 cursor-pointer">
                    <option value="">Seleccione insumo...</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id_materia_prima }}">
                            {{ $m->nombre }} (Stock: {{ number_format($m->stock) }} {{ $m->unidad_medida }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Cantidad a Ingresar (+)</label>
                <input type="number" name="cantidad" required min="1" step="1" placeholder="Ej. 500" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500">
            </div>

            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Bodega Destino</label>
                <input type="text" name="bodega" value="Bodega Insumos" class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none">
            </div>

            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Observación / Factura Proveedor</label>
                <input type="text" name="descripcion" placeholder="Ingreso de bolsas/etiquetas lote nuevo..." class="w-full mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEntradaStock')" class="px-5 py-2.5 text-slate-500 font-bold text-sm cursor-pointer">Cancelar</button>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-teal-700 transition-all cursor-pointer">
                    Sumar Stock
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══ Modal Editar Insumo ══ -->
<div id="modalEditarInsumo" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="glass-card rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl border border-white">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-black text-slate-800">Editar Insumo</h3>
            <button onclick="closeModal('modalEditarInsumo')" class="text-slate-400 hover:text-rose-500 cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="formEditarInsumo" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase">Código</label>
                    <input type="text" id="editCodigo" name="codigo" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase">Unidad</label>
                    <input type="text" id="editUnidad" name="unidad_medida" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="text-[11px] font-black text-slate-400 uppercase">Nombre</label>
                <input type="text" id="editNombre" name="nombre" required class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase">Stock</label>
                    <input type="number" id="editStock" name="stock" min="0" step="1" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase">Stock Mínimo</label>
                    <input type="number" id="editMinimo" name="stock_minimo" min="0" step="1" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
                <div>
                    <label class="text-[11px] font-black text-slate-400 uppercase">Costo Unit.</label>
                    <input type="number" id="editCosto" name="costo_unitario" min="0" step="0.01" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEditarInsumo')" class="px-5 py-2 text-slate-500 font-bold text-sm">Cancelar</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-md hover:bg-indigo-700">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
    }

    function prepararEntrada(id, nombre, unidad) {
        const select = document.getElementById('entradaSelectInsumo');
        if (select) {
            select.value = id;
        }
        openModal('modalEntradaStock');
    }

    function abrirEditarInsumo(item) {
        document.getElementById('formEditarInsumo').action = `/materias-primas/${item.id_materia_prima}`;
        document.getElementById('editCodigo').value = item.codigo || '';
        document.getElementById('editNombre').value = item.nombre || '';
        document.getElementById('editUnidad').value = item.unidad_medida || '';
        document.getElementById('editStock').value = item.stock || 0;
        document.getElementById('editMinimo').value = item.stock_minimo || 10;
        document.getElementById('editCosto').value = item.costo_unitario || 0;
        openModal('modalEditarInsumo');
    }

    function confirmarEliminarInsumo(id, nombre) {
        Swal.fire({
            title: '¿Eliminar insumo?',
            text: `Se eliminará '${nombre}' del inventario. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-[2rem] font-outfit' }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit();
            }
        });
    }

    // Buscador en tiempo real
    document.getElementById('searchInsumo')?.addEventListener('input', function() {
        const term = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.insumo-row');
        let visibles = 0;

        rows.forEach(r => {
            const match = r.dataset.search.includes(term);
            r.style.display = match ? '' : 'none';
            if (match) visibles++;
        });

        const noRes = document.getElementById('noResultsRow');
        if (noRes) {
            noRes.classList.toggle('hidden', visibles > 0 || rows.length === 0);
        }
    });
</script>
@endpush
