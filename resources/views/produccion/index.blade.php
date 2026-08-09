<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Producción - TRIGESTION</title>

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
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(99,102,241,.10) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(245,158,11,.07) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
        @keyframes float { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(5%,5%) scale(1.1)} }
        .glass-card { background:rgba(255,255,255,.78);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.55);box-shadow:0 10px 30px -10px rgba(0,0,0,.06); }
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
        @include('partials.sidebar')

        <!-- ═══ MAIN ═══ -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOP HEADER -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Control de Producción</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Inicia, monitorea y finaliza los procesos de producción</p>
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
                                        @if ($prod->descripcion)
                                        <div class="w-full h-[1px] bg-slate-200/60"></div>
                                        <div class="text-[12px] text-slate-500 italic">"{{ $prod->descripcion }}"</div>
                                        @endif
                                    </div>

                                    <!-- Action button -->
                                    @if ($prod->estado !== 'Finalizada')
                                        <button type="button"
                                            onclick="confirmarFinalizar({{ $prod->id_produccion }}, '{{ addslashes($prod->lote_produccion) }}')"
                                            class="w-full block text-center py-3.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-600 font-bold hover:bg-emerald-500 hover:text-white hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-200 transition-all transform active:scale-95 text-[13px] cursor-pointer">
                                            <i class="fas fa-flag-checkered mr-1.5"></i> Finalizar Producción
                                        </button>
                                        <form id="finalizarForm{{ $prod->id_produccion }}" action="{{ route('produccion.finalizar', $prod->id_produccion) }}" method="GET" class="hidden"></form>
                                    @else
                                        <button disabled class="w-full block text-center py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 font-bold cursor-not-allowed text-[13px]">
                                            <i class="fas fa-check-double mr-1.5"></i> Lote Completado
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

            </main>
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
                                    MP-{{ $mp->id_inventario_materia }}:
                                    {{ $mp->detalle->tipo_envase ?? '—' }}
                                    {{ $mp->detalle->capacidad ?? '' }}
                                    (Lote: {{ $mp->detalle->lote->codigo_lote ?? 'N/A' }})
                                    — Qty: {{ $mp->ingreso ?? 0 }}
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

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('modalIniciar')" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-sky-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all transform active:scale-95 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-cogs"></i> Iniciar Proceso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function confirmarFinalizar(id, lote) {
            Swal.fire({
                title: '¿Finalizar producción?',
                html:  `<p class="text-slate-600">El lote <strong>${lote}</strong> pasará a estado <strong>Finalizada</strong>.<br>Se registrará el ingreso al inventario de productos terminados y se descontará la materia prima utilizada.</p>`,
                icon:  'warning',
                showCancelButton:    true,
                confirmButtonColor:  '#10b981',
                cancelButtonColor:   '#94a3b8',
                confirmButtonText:   '<i class="fas fa-flag-checkered mr-2"></i>Sí, finalizar',
                cancelButtonText:    'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('finalizarForm' + id).submit();
                }
            });
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalIniciar') closeModal('modalIniciar');
        });
    </script>
</body>
</html>
