@extends('layouts.panel')

@section('title', 'Control de Producción')
@section('subtitle', 'Planificación y registro de órdenes de producción')

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

                @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm mb-6">
                    <ul class="list-disc list-inside text-xs font-semibold space-y-1">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <!-- HEADER BANNER -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden mb-8">
                    <div class="p-8 md:p-10 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-slate-50 to-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Planta de Producción</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                                Control de <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-sky-600">Producción</span>
                            </h2>
                            <p class="text-slate-500 mt-2 font-medium">Inicia, monitorea y finaliza los procesos de producción de Trigestion.</p>
                        </div>
                        <button onclick="openModal('modalIniciar')"
                            class="relative z-10 bg-gradient-to-r from-emerald-500 to-sky-600 hover:shadow-[0_10px_25px_-5px_rgba(16,185,129,.4)] text-white px-8 py-4 rounded-[1.25rem] font-bold transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 tracking-wide cursor-pointer w-full md:w-auto">
                            <i class="fas fa-play"></i> Iniciar Producción
                        </button>
                    </div>

                    <!-- KPI STATS -->
                    @php
                        $enProceso  = $producciones->where('estado', 'En Producción')->count();
                        $finalizadas = $producciones->where('estado', 'Finalizada')->count();
                        $totalUnids  = $producciones->where('estado', 'Finalizada')->sum('cantidad');
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-px bg-slate-100 border-b border-slate-100">
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-amber-500">{{ $enProceso }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">En Producción</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-emerald-500">{{ $finalizadas }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Finalizadas</div>
                        </div>
                        <div class="bg-white/80 p-6 text-center">
                            <div class="text-3xl font-black text-trigestion-500">{{ number_format($totalUnids) }}</div>
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Unidades Producidas</div>
                        </div>
                    </div>

                    <!-- PRODUCTION CARDS GRID -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                            @forelse ($producciones as $prod)
                            <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group relative">
                                <div class="p-7 relative bg-gradient-to-b from-slate-50/50 to-white">

                                    <!-- Status Badge -->
                                    <div class="absolute top-5 right-5">
                                        @if ($prod->estado === 'Finalizada')
                                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i> Finalizada
                                            </span>
                                        @elseif ($prod->estado === 'Cancelada')
                                            <span class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm flex items-center gap-1">
                                                <i class="fas fa-times-circle"></i> Cancelada
                                            </span>
                                        @else
                                            <span class="px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm animate-pulse flex items-center gap-1">
                                                <i class="fas fa-spinner fa-spin"></i> En Proceso
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Icon -->
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-5 shadow-sm group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-industry"></i>
                                    </div>

                                    <h3 class="text-xl font-black text-slate-800 mb-1 group-hover:text-emerald-600 transition-colors">
                                        Lote: {{ $prod->lote_produccion }}
                                    </h3>
                                    <p class="text-[13px] font-bold text-slate-400 mb-5 tracking-wide">
                                        {{ $prod->producto->nombre ?? 'Producto Desconocido' }}
                                    </p>

                                    <!-- Details grid -->
                                    <div class="space-y-3 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-slate-500 font-medium flex items-center gap-2">
                                                <i class="fas fa-cubes text-slate-400 w-4"></i> Cantidad
                                            </span>
                                            <span class="font-black text-slate-700">{{ number_format($prod->cantidad) }} unds.</span>
                                        </div>
                                        <div class="w-full h-[1px] bg-slate-200/60"></div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-slate-500 font-medium flex items-center gap-2">
                                                <i class="fas fa-user-gear text-slate-400 w-4"></i> Operario
                                            </span>
                                            <span class="font-bold text-slate-700">{{ $prod->usuario->nombres ?? $prod->usuario->name ?? 'N/A' }}</span>
                                        </div>
                                        @if ($prod->id_inventario_materia)
                                        <div class="w-full h-[1px] bg-slate-200/60"></div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-slate-500 font-medium flex items-center gap-2">
                                                <i class="fas fa-boxes-stacked text-slate-400 w-4"></i> MP Vinculada
                                            </span>
                                            <span class="font-bold text-slate-700">#{{ $prod->id_inventario_materia }}</span>
                                        </div>
                                        @endif
                                        @if (!empty($prod->materiales_consumidos))
                                        <div class="w-full h-[1px] bg-slate-200/60"></div>
                                        <div>
                                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1 mb-1.5">
                                                <i class="fas fa-tags text-teal-400"></i> Insumos consumidos
                                            </span>
                                            @foreach($prod->materiales_consumidos as $mat)
                                            <div class="flex justify-between text-[11px] font-medium text-slate-500">
                                                <span>{{ $mat['nombre'] }}</span>
                                                <span class="font-black text-teal-600">{{ $mat['total_consumido'] }} {{ $mat['unidad_medida'] }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        @if ($prod->descripcion)
                                        <div class="w-full h-[1px] bg-slate-200/60"></div>
                                        <div class="text-[12px] text-slate-500 italic">"{{ $prod->descripcion }}"</div>
                                        @endif                                    </div>

                                    <!-- Action button -->
                                    @if ($prod->estado === 'En Producción')
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                onclick="confirmarFinalizar({{ $prod->id_produccion }}, '{{ addslashes($prod->lote_produccion) }}')"
                                                class="flex-1 text-center py-3.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 font-bold hover:bg-emerald-500 hover:text-white hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-200 transition-all transform active:scale-95 text-[13px] cursor-pointer">
                                                <i class="fas fa-flag-checkered mr-1.5"></i> Finalizar
                                            </button>
                                            <button type="button"
                                                onclick="confirmarCancelar({{ $prod->id_produccion }}, '{{ addslashes($prod->lote_produccion) }}')"
                                                class="px-4 py-3.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 font-bold hover:bg-rose-500 hover:text-white hover:border-rose-500 hover:shadow-lg hover:shadow-rose-200 transition-all transform active:scale-95 text-[13px] cursor-pointer"
                                                title="Cancelar producción">
                                                <i class="fas fa-times mr-1"></i> Cancelar
                                            </button>
                                        </div>
                                        <form id="finalizarForm{{ $prod->id_produccion }}" action="{{ route('produccion.finalizar', $prod->id_produccion) }}" method="POST" class="hidden">@csrf</form>
                                        <form id="cancelarForm{{ $prod->id_produccion }}" action="{{ route('produccion.cancelar', $prod->id_produccion) }}" method="POST" class="hidden">@csrf</form>
                                    @elseif ($prod->estado === 'Finalizada')
                                        <button disabled class="w-full block text-center py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 font-bold cursor-not-allowed text-[13px]">
                                            <i class="fas fa-check-double mr-1.5"></i> Lote Completado
                                        </button>
                                    @else
                                        <button disabled class="w-full block text-center py-3.5 rounded-xl border border-rose-200 bg-rose-50/50 text-rose-400 font-bold cursor-not-allowed text-[13px]">
                                            <i class="fas fa-ban mr-1.5"></i> Cancelada
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="col-span-full p-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                                    <i class="fas fa-clipboard-list text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium text-lg">No hay procesos de producción registrados.</p>
                                <button onclick="openModal('modalIniciar')" class="mt-4 text-emerald-600 font-bold hover:underline cursor-pointer">
                                    Iniciar primera producción
                                </button>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

<!-- ══ Modal Iniciar Producción ══ -->
    <div id="modalIniciar" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
        <div class="glass-card rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,.18)] border border-white my-8">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Iniciar Producción</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Configura un nuevo lote de producción.</p>
                </div>
                <button onclick="closeModal('modalIniciar')" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:rotate-90 transition-all border border-slate-100 shadow-sm cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('produccion.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Código de Lote -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Código de Lote</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-barcode"></i></span>
                            <input type="text" name="lote_produccion" required placeholder="Ej. LOTE-2024X"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <!-- Cantidad -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Cantidad a Producir</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-layer-group"></i></span>
                            <input type="number" name="cantidad" required min="1" placeholder="Ej. 100"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                </div>

                <!-- Producto -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Producto Terminado</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 z-10"><i class="fas fa-tint"></i></span>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10"></i>
                        <select name="id_producto" required class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-slate-700 appearance-none">
                            <option value="">Seleccione el producto...</option>
                            @foreach ($productos as $p)
                                <option value="{{ $p->id_producto }}">
                                    {{ $p->nombre }} — ${{ number_format($p->precio, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Materia Prima -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Materia Prima a Utilizar <span class="text-slate-400 font-normal normal-case">(Opcional)</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 z-10"><i class="fas fa-boxes-stacked"></i></span>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10"></i>
                        <select name="id_inventario_materia" class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-slate-700 appearance-none">
                            <option value="">No deducir / Lote Genérico...</option>
                            @foreach ($materiaPrima as $mp)
                                <option value="{{ $mp->id_inventario_materia }}">
                                    @if ($mp->id_retornables)
                                        MP-{{ $mp->id_inventario_materia }} (Retornables #{{ $mp->id_retornables }}) — Qty: {{ $mp->ingreso ?? 0 }}
                                    @else
                                        MP-{{ $mp->id_inventario_materia }}:
                                        {{ $mp->detalle->tipo_envase ?? '—' }}
                                        {{ $mp->detalle->capacidad ?? '' }}
                                        (Lote: {{ $mp->detalle->lote->codigo_lote ?? 'N/A' }})
                                        — Qty: {{ $mp->ingreso ?? 0 }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-xs text-slate-400 italic mt-1 ml-2">Asocia un registro de entrada de materia prima para la trazabilidad.</p>
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Descripción o Notas</label>
                    <div class="relative">
                        <span class="absolute top-4 left-0 flex items-start pl-5 text-slate-400"><i class="fas fa-comment-alt"></i></span>
                        <textarea name="descripcion" rows="3" placeholder="Instrucciones especiales o notas del proceso..."
                                  class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-slate-700 resize-none"></textarea>
                    </div>
                </div>

                <!-- Insumos de la Receta (etiquetas, tapas, bolsas) -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">
                        Insumos a Consumir
                        <span class="text-slate-400 font-normal normal-case">(Opcional — etiquetas, tapas, bolsas...)</span>
                    </label>

                    <div id="recetaContainer" class="space-y-2">
                        <!-- Filas dinámicas de insumos -->
                    </div>

                    <button type="button" onclick="agregarFilaInsumo()"
                        class="w-full py-2.5 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 hover:border-teal-400 hover:text-teal-500 font-bold text-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Agregar Insumo
                    </button>

                    @if($insumos->isEmpty())
                    <p class="text-[11px] text-amber-500 font-medium ml-1">
                        <i class="fas fa-triangle-exclamation mr-1"></i>
                        No hay insumos registrados. <a href="{{ route('materias-primas.index') }}" class="underline">Registrar insumos</a>
                    </p>
                    @endif
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalIniciar')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-sky-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-cogs"></i> Iniciar Proceso
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Datos de insumos para el selector dinámico
    window.INSUMOS = @json($insumos->map(fn($i) => [
        'id'           => $i->id_materia_prima,
        'nombre'       => $i->nombre,
        'stock'        => (float)$i->stock,
        'unidad'       => $i->unidad_medida ?? 'uds.',
        'stock_minimo' => (float)$i->stock_minimo,
    ]));
</script>
<script src="{{ asset('js/pages/produccion.js') }}"></script>
@endpush
