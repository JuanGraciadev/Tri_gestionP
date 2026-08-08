<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Lotes - TRIGESTION</title>

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
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(245,158,11,.18) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(99,102,241,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(234,88,12,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
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

        <!-- ═══ SIDEBAR ═══ -->
        <aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
            <div class="px-6 py-6 border-b border-white/10 bg-black/10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-trigestion-400 to-sky-300 flex items-center justify-center shadow-lg shadow-sky-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                        <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">SISTEMA DE GESTIÓN</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <!-- MENÚ PRINCIPAL -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MENÚ PRINCIPAL</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-users text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Gestión Usuarios</span>
                        </a>
                    </div>
                </div>

                <!-- OPERACIONES -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">OPERACIONES</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-boxes-stacked text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Inventario MP</span>
                        </a>
                        <a href="{{ route('lotes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-amber-500/20 text-white border-l-4 border-amber-400 shadow-md">
                            <i class="fas fa-box-open text-amber-300 shrink-0"></i>
                            <span>Gestión de Lotes</span>
                        </a>
                        <a href="{{ route('categorias.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-tags text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Categorías</span>
                        </a>
                        <a href="{{ route('productos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-tint text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Productos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-cubes text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Inventario Productos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-cart-shopping text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Ventas y Pedidos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-rotate-left text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Devoluciones</span>
                        </a>
                    </div>
                </div>

                <!-- ANÁLISIS -->
                <div>
                    <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">ANÁLISIS</p>
                    <div class="space-y-1">
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                            <i class="fas fa-file-invoice-dollar text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                            <span>Reportes Generales</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Control de Lotes</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Administra la trazabilidad y procedencia de materia prima por lotes</p>
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
            <main class="p-8 flex-1 font-outfit">

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

            </main>
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
</body>
</html>
