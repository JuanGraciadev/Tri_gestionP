<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Lote {{ $lote->codigo_lote }} - TRIGESTION</title>

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
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(16,185,129,.15) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(245,158,11,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(14,165,233,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card,.glass-panel { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
        ::-webkit-scrollbar { width:8px;height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1;border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

    <div class="flex min-h-screen relative z-10">

        @include('partials.sidebar')

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 text-sm text-slate-400 font-bold mb-1">
                        <a href="{{ route('lotes.index') }}" class="hover:text-amber-500 transition-colors"><i class="fas fa-arrow-left mr-1"></i> Lotes</a>
                        <span>/</span>
                        <span class="text-slate-700">{{ $lote->codigo_lote }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                        Detalles del Lote: <span class="text-sky-600">{{ $lote->codigo_lote }}</span>
                    </h1>
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
            <main class="p-8 flex-1 font-outfit space-y-8">

                <!-- SweetAlert flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#0ea5e9',
                            confirmButtonText:  'Entendido',
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

                {{--
                    KEY BEHAVIOUR (original flow):
                    When the user just created a batch and there are no details yet,
                    the modal opens automatically so they are forced into Paso 2.
                --}}
                @if ($detalles->isEmpty() && !session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        openModal('modalCrearDetalle');
                    });
                </script>
                @endif

                <!-- HEADER BANNER -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden relative p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-sky-100 flex items-center justify-center text-sky-600 text-2xl font-bold shadow-sm shrink-0">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <a href="{{ route('lotes.index') }}" class="text-sm font-bold text-slate-400 hover:text-sky-500 transition-colors uppercase tracking-widest">
                                <i class="fas fa-arrow-left mr-1"></i> Volver a Lotes
                            </a>
                            <h2 class="text-3xl font-black text-slate-800 mt-1">
                                Lote: <span class="text-sky-600">{{ $lote->codigo_lote }}</span>
                            </h2>
                            <p class="text-xs text-slate-400 font-semibold mt-1">
                                Registrado por: {{ $lote->usuario->nombres ?? $lote->usuario->name ?? 'Desconocido' }}
                                &nbsp;·&nbsp; ID #{{ $lote->id_lote }}
                            </p>
                        </div>
                    </div>
                    <button onclick="openModal('modalCrearDetalle')"
                            class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:shadow-lg hover:shadow-emerald-200 text-white px-8 py-4 rounded-2xl font-bold transition-all transform hover:-translate-y-1 flex items-center gap-2 cursor-pointer w-full md:w-auto justify-center">
                        <i class="fas fa-plus-circle"></i> Añadir Detalle
                    </button>
                </div>

                <!-- DETAILS TABLE -->
                <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Unidades</th>
                                    <th class="px-8 py-5">Tipo de Envase</th>
                                    <th class="px-8 py-5">Capacidad</th>
                                    <th class="px-8 py-5">Proveedor</th>
                                    <th class="px-8 py-5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($detalles as $d)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <span class="font-black text-slate-800 text-lg">{{ $d->unidades }}</span>
                                        <span class="text-xs text-slate-400 font-bold ml-1">uds</span>
                                    </td>
                                    <td class="px-8 py-6 text-slate-600 font-bold">{{ $d->tipo_envase }}</td>
                                    <td class="px-8 py-6 text-slate-600 font-bold">{{ $d->capacidad }}</td>
                                    <td class="px-8 py-6 text-slate-600">{{ $d->proveedor }}</td>
                                    <td class="px-8 py-6 text-center">
                                        <button type="button" onclick="openEditModal({{ json_encode($d) }})"
                                                class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-600 hover:bg-amber-50 transition-all flex items-center justify-center mx-auto cursor-pointer"
                                                title="Editar Detalle">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="fas fa-clipboard-list text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-medium">Este lote aún no tiene detalles registrados.</p>
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

    <!-- ══ Modal Crear Detalle (Paso 2) ══ -->
    <div id="modalCrearDetalle" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
        <div class="glass-card rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,.18)] border border-white my-8">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-emerald-50/50">
                <div>
                    <h3 class="text-2xl font-bold text-emerald-800">Paso 2: Añadir Detalle</h3>
                    <p class="text-sm text-emerald-600/70 mt-0.5">Ingresa la información específica de los envases para el lote <strong>{{ $lote->codigo_lote }}</strong>.</p>
                </div>
                <button onclick="closeModal('modalCrearDetalle')" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:rotate-90 transition-all border border-slate-100 shadow-sm cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('lotes.detalles.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="id_lote" value="{{ $lote->id_lote }}">

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Unidades</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-layer-group"></i></span>
                            <input type="number" name="unidades" required min="1" placeholder="Ej. 100"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Tipo de Envase</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-bottle-water"></i></span>
                            <input type="text" name="tipo_envase" required placeholder="Ej. Garrafón"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Capacidad</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-flask"></i></span>
                            <input type="text" name="capacidad" required placeholder="Ej. 20 Litros"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Proveedor</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-truck-field"></i></span>
                            <input type="text" name="proveedor" required placeholder="Nombre del proveedor"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-slate-700">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalCrearDetalle')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-save"></i> Guardar Detalle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ Modal Editar Detalle ══ -->
    <div id="modalEditarDetalle" class="fixed inset-0 bg-slate-900/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm overflow-y-auto">
        <div class="glass-card rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,.18)] border border-white my-8">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-amber-50/50">
                <h3 class="text-2xl font-bold text-amber-800">Editar Detalle</h3>
                <button onclick="closeModal('modalEditarDetalle')" class="w-10 h-10 rounded-full bg-white text-slate-400 hover:text-red-500 hover:rotate-90 transition-all border border-slate-100 shadow-sm cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="formEditarDetalle" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')
                {{-- id_lote is required by UpdateLoteDetalleRequest to redirect back to this page --}}
                <input type="hidden" name="id_lote" value="{{ $lote->id_lote }}">

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Unidades</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-layer-group"></i></span>
                            <input type="number" name="unidades" id="edit_unidades" required min="1"
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Tipo de Envase</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-bottle-water"></i></span>
                            <input type="text" name="tipo_envase" id="edit_tipo_envase" required
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Capacidad</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-flask"></i></span>
                            <input type="text" name="capacidad" id="edit_capacidad" required
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all font-bold text-slate-700">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Proveedor</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400"><i class="fas fa-truck-field"></i></span>
                            <input type="text" name="proveedor" id="edit_proveedor" required
                                   class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all text-slate-700">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalEditarDetalle')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
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

        function openEditModal(detalle) {
            const url = "{{ route('lotes.detalles.update', ':id') }}".replace(':id', detalle.id_detalles);
            document.getElementById('formEditarDetalle').action = url;
            document.getElementById('edit_unidades').value    = detalle.unidades;
            document.getElementById('edit_tipo_envase').value = detalle.tipo_envase;
            document.getElementById('edit_capacidad').value   = detalle.capacidad;
            document.getElementById('edit_proveedor').value   = detalle.proveedor;
            openModal('modalEditarDetalle');
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            ['modalCrearDetalle','modalEditarDetalle'].forEach(id => {
                if (e.target.id === id) closeModal(id);
            });
        });
    </script>
</body>
</html>
