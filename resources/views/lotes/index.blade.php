@extends('layouts.panel')

@section('title', 'Control de Lotes')
@section('subtitle', 'Registro y seguimiento de lotes de insumos')

@section('content')
<!-- SweetAlert flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#f59e0b',
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
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Trazabilidad</span>
                            </div>
                            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                                Control de <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Lotes</span>
                            </h2>
                            <p class="text-slate-500 mt-2 font-medium">Administra la trazabilidad y procedencia de materia prima por lotes.</p>
                        </div>
                        <button onclick="openModal('modalCrearLote')" class="relative z-10 bg-gradient-to-r from-amber-500 to-orange-600 hover:shadow-[0_10px_25px_-5px_rgba(245,158,11,.4)] text-white px-8 py-4 rounded-[1.25rem] font-bold transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 tracking-wide cursor-pointer w-full md:w-auto">
                            <i class="fas fa-plus"></i> Nuevo Lote
                        </button>
                    </div>

                    <!-- BATCHES TABLE -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Código de Lote</th>
                                    <th class="px-8 py-5">Registrado por</th>
                                    <th class="px-8 py-5">Detalles</th>
                                    <th class="px-8 py-5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($lotes as $l)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-[1rem] bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center font-bold shadow-sm group-hover:scale-110 transition-transform">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800 text-lg tracking-tight group-hover:text-amber-600 transition-colors">{{ $l->codigo_lote }}</div>
                                                <div class="text-[11px] font-bold text-slate-400">ID: #{{ $l->id_lote }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-600 font-bold text-[13px] flex items-center gap-2 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl w-max">
                                            <i class="fas fa-user-circle text-slate-400"></i>
                                            {{ $l->usuario->nombres ?? $l->usuario->name ?? 'Desconocido' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm
                                            {{ $l->detalles_count > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                            <i class="fas {{ $l->detalles_count > 0 ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                                            {{ $l->detalles_count }} Registro(s)
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <!-- View Details -->
                                            <a href="{{ route('lotes.detalles', $l->id_lote) }}"
                                               class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white hover:shadow-lg hover:shadow-sky-200 transition-all flex items-center justify-center border border-sky-100 hover:border-sky-500"
                                               title="Ver / Agregar Detalles">
                                                <i class="fas fa-list-ul"></i>
                                            </a>
                                            <!-- Edit -->
                                            <button type="button" onclick="openEditModal({{ json_encode($l) }})"
                                                    class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-200 transition-all flex items-center justify-center border border-amber-100 hover:border-amber-500 cursor-pointer"
                                                    title="Editar Código">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <!-- Delete -->
                                            <button type="button" onclick="confirmarEliminar({{ $l->id_lote }})"
                                                    class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center border border-rose-100 hover:border-rose-500 cursor-pointer"
                                                    title="Eliminar Lote">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <form id="deleteForm{{ $l->id_lote }}" action="{{ route('lotes.destroy', $l->id_lote) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="fas fa-boxes-stacked text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium">No hay lotes registrados.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

<!-- ══ Modal Crear Lote (Paso 1) ══ -->
    <div id="modalCrearLote" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="glass-card rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,.18)] border border-white">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Paso 1: Nuevo Lote</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Crea el lote para luego añadir sus detalles.</p>
                </div>
                <button onclick="closeModal('modalCrearLote')" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:rotate-90 transition-all border border-slate-100 shadow-sm cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('lotes.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Código del Lote</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-barcode"></i></span>
                        <input type="text" name="codigo_lote" required placeholder="Ej. L-202310A"
                               class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-slate-700">
                    </div>
                </div>
                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalCrearLote')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-sky-200 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                        Continuar <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ Modal Editar Lote ══ -->
    <div id="modalEditarLote" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="glass-card rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,.18)] border border-white">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-2xl font-bold text-slate-800">Editar Lote</h3>
                <button onclick="closeModal('modalEditarLote')" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:rotate-90 transition-all border border-slate-100 shadow-sm cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formEditarLote" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Código del Lote</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-barcode"></i></span>
                        <input type="text" name="codigo_lote" id="edit_codigo_lote" required
                               class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-slate-700">
                    </div>
                </div>
                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalEditarLote')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-amber-200 transition-all transform active:scale-95 cursor-pointer">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
        function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function openEditModal(lote) {
            const url = "{{ route('lotes.update', ':id') }}".replace(':id', lote.id_lote);
            document.getElementById('formEditarLote').action = url;
            document.getElementById('edit_codigo_lote').value = lote.codigo_lote;
            openModal('modalEditarLote');
        }

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text:  'Se eliminará este lote junto con TODOS sus detalles y registros de inventario. ¡Esta acción es irreversible!',
                icon:  'warning',
                showCancelButton:    true,
                confirmButtonColor:  '#ef4444',
                cancelButtonColor:   '#94a3b8',
                confirmButtonText:   '<i class="fas fa-trash-alt mr-2"></i>Sí, eliminar lote',
                cancelButtonText:    'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            ['modalCrearLote','modalEditarLote'].forEach(id => {
                if (e.target.id === id) closeModal(id);
            });
        });
    </script>
@endpush
