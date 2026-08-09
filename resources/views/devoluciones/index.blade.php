<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de Retornables - TRIGESTION</title>

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
        .blob-1 { top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(56,189,248,.18) 0,rgba(255,255,255,0) 70%); }
        .blob-2 { bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(99,102,241,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s; }
        .blob-3 { top:40%;left:30%;width:40vw;height:40vw;background:radial-gradient(circle,rgba(16,185,129,.08) 0,rgba(255,255,255,0) 70%);animation-delay:-10s; }
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

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- MAIN -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOPBAR -->
            @include('partials.topbar', ['title' => 'Control de Retornables', 'subtitle' => 'Supervisa envases entregados, en consumo con clientes y registra devoluciones a bodega'])

            <!-- BODY -->
            <main class="p-8 space-y-10 flex-1 font-outfit">

                <!-- Alert Flash -->
                @if (session('alert'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon:  '{{ session('alert.icon') }}',
                            title: '{!! addslashes(session('alert.title')) !!}',
                            text:  '{!! addslashes(session('alert.text')) !!}',
                            confirmButtonColor: '#009ee3',
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

                <!-- HEADER SECTION -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-6">
                    <div>
                        <div class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 font-bold text-sm mb-3 shadow-sm">
                            <i class="fas fa-rotate-left mr-1.5"></i>Logística Inversa
                        </div>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                            Control de <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600">Retornables</span>
                        </h1>
                        <p class="text-slate-500 mt-2 text-base font-medium max-w-xl">Supervisa envases entregados, en consumo con clientes y registra devoluciones a bodega.</p>
                    </div>
                    <button onclick="openModalReturn()"
                        class="bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-8 py-5 rounded-[1.5rem] font-bold shadow-lg shadow-sky-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-3 overflow-hidden relative group cursor-pointer w-full sm:w-auto justify-center">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 relative z-10 shrink-0">
                            <i class="fas fa-arrow-down-left-and-arrow-up-right-to-center text-xl"></i>
                        </div>
                        <div class="relative z-10 text-left">
                            <div class="text-[10px] text-white/80 uppercase tracking-wider font-bold">Operación</div>
                            <div class="text-lg">Recibir Devolución</div>
                        </div>
                    </button>
                </div>

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Entregados -->
                    <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div class="w-14 h-14 rounded-[1.2rem] bg-sky-50 flex items-center justify-center text-sky-600 text-2xl border border-white/80 shadow-sm">
                                <i class="fas fa-truck-ramp-box"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalEntregados) }}</div>
                            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Entregados a Clientes</div>
                            <p class="text-[10px] text-slate-400 mt-1">Envases despachados en ventas</p>
                        </div>
                    </div>

                    <!-- Devueltos -->
                    <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div class="w-14 h-14 rounded-[1.2rem] bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl border border-white/80 shadow-sm">
                                <i class="fas fa-rotate-left"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalDevueltos) }}</div>
                            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">Retornados a Bodega</div>
                            <p class="text-[10px] text-slate-400 mt-1">Reingresados como Materia Prima</p>
                        </div>
                    </div>

                    <!-- En Consumo -->
                    <div class="glass-card rounded-[2.5rem] p-6 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-5 relative z-10">
                            <div class="w-14 h-14 rounded-[1.2rem] bg-amber-50 flex items-center justify-center text-amber-600 text-2xl border border-white/80 shadow-sm">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                        <div class="relative z-10">
                            <div class="text-4xl font-black text-slate-800 tracking-tighter mb-1">{{ number_format($totalEnConsumo) }}</div>
                            <div class="text-slate-500 font-bold text-xs uppercase tracking-wider">En Consumo (Pendientes)</div>
                            <p class="text-[10px] text-slate-400 mt-1">Envases en poder del cliente</p>
                        </div>
                    </div>
                </div>

                <!-- TABLA BALANCES POR CLIENTE -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-users text-indigo-500"></i> Balance de Envases por Cliente
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">Saldo individual de garrafones entregados y devueltos.</p>
                        </div>
                        <div class="relative w-72">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="searchBalances" placeholder="Buscar cliente o producto..."
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-sky-500/10 focus:border-sky-400 outline-none transition-all text-sm font-medium shadow-sm">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="tablaBalances">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-5">Cliente</th>
                                    <th class="px-8 py-5">Producto Retornable</th>
                                    <th class="px-8 py-5 text-center">Entregados</th>
                                    <th class="px-8 py-5 text-center">Devueltos</th>
                                    <th class="px-8 py-5 text-center">En Consumo</th>
                                    <th class="px-8 py-5 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @if (empty($balances))
                                <tr>
                                    <td colspan="6" class="px-8 py-16 text-center text-slate-400 font-medium">
                                        No hay saldos pendientes registrados. Aparecerán cuando se entreguen pedidos de garrafones retornables.
                                    </td>
                                </tr>
                                @else
                                @foreach ($balances as $b)
                                <tr class="hover:bg-slate-50/50 transition-colors balance-row" data-search="{{ strtolower($b['cliente_nombre'] . ' ' . $b['producto_nombre']) }}">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($b['cliente_nombre'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm">{{ $b['cliente_nombre'] }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold">{{ $b['cliente_documento'] ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-slate-700 font-semibold text-sm">
                                        {{ $b['producto_nombre'] }}
                                    </td>
                                    <td class="px-8 py-5 text-center text-slate-600 font-black font-mono">
                                        {{ $b['total_entregado'] }}
                                    </td>
                                    <td class="px-8 py-5 text-center text-emerald-600 font-black font-mono">
                                        {{ $b['total_devuelto'] }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black font-mono shadow-sm
                                            {{ $b['en_consumo'] > 0 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                            {{ $b['en_consumo'] }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if ($b['en_consumo'] > 0)
                                        <button onclick="quickReturn({{ json_encode($b) }})"
                                                class="px-4 py-2 bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white border border-sky-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 mx-auto cursor-pointer">
                                            <i class="fas fa-rotate-left"></i> Devolver
                                        </button>
                                        @else
                                        <span class="text-xs font-bold text-slate-400 italic">Al día</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABLA HISTORIAL DE DEVOLUCIONES -->
                <div class="glass-card rounded-[2.5rem] border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-clock-rotate-left text-emerald-500"></i> Historial de Devoluciones Recibidas
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">Registro cronológico de retornos físicos.</p>
                        </div>
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-full">{{ count($historial) }} registros</span>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto max-h-[400px] custom-scrollbar">
                        <table class="w-full text-left" id="tablaHistorial">
                            <thead class="bg-slate-50/50 text-slate-500 uppercase text-xs font-bold tracking-widest sticky top-0 z-10 border-b border-slate-200">
                                <tr>
                                    <th class="px-8 py-4">Fecha / Hora</th>
                                    <th class="px-8 py-4">Cliente</th>
                                    <th class="px-8 py-4">Producto</th>
                                    <th class="px-8 py-4">Cantidad Recibida</th>
                                    <th class="px-8 py-4">Bodega / Reingreso</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($historial as $h)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-4 text-slate-600 text-sm">
                                        {{ optional($h->fecha)->format('d/m/Y H:i') ?? 'N/A' }}
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $h->usuario->nombres ?? $h->usuario->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-slate-700 font-medium text-sm">
                                        {{ $h->producto->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 font-black px-3 py-1 rounded-full text-sm">
                                            <i class="fas fa-plus text-[10px]"></i>{{ $h->cantidad }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="text-slate-600 font-semibold flex items-center gap-2">
                                            <i class="fas fa-warehouse text-slate-400"></i> {{ $h->inventarioMateriaPrima->bodega ?? 'Bodega Principal' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-medium">
                                        Aún no se registran retornos.
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

    <!-- MODAL REGISTRAR DEVOLUCIÓN -->
    <div id="modalReturn" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="glass-card rounded-[2.5rem] w-full max-w-lg overflow-hidden border border-white shadow-2xl">
            <div class="p-8 bg-gradient-to-r from-sky-500 to-indigo-600 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-extrabold tracking-tight">Recibir Devolución</h3>
                    <p class="text-white/70 text-sm font-medium mt-0.5">Ingresar envases vacíos a Materia Prima</p>
                </div>
                <button type="button" onclick="closeModalReturn()" class="w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white hover:text-rose-500 transition-all flex items-center justify-center cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('devoluciones.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <!-- Selector Cliente -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Seleccionar Cliente</label>
                    <select id="modal_id_usuario" name="id_usuario" required onchange="onClientSelect()"
                            class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl font-medium text-slate-700 outline-none focus:border-sky-400 text-sm cursor-pointer">
                        <option value="">Seleccione un cliente...</option>
                        @php
                            $clientesUnicos = [];
                            foreach ($balances as $b) {
                                if ($b['en_consumo'] > 0) {
                                    $clientesUnicos[$b['id_usuario']] = $b['cliente_nombre'] . " — " . ($b['cliente_documento'] ?? '');
                                }
                            }
                        @endphp
                        @foreach ($clientesUnicos as $cid => $cname)
                            <option value="{{ $cid }}">{{ $cname }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector Producto -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Producto Envase (Solo Retornables)</label>
                    <select id="modal_id_producto" name="id_producto" required onchange="onProductSelect()"
                            class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl font-medium text-slate-700 outline-none focus:border-sky-400 text-sm cursor-pointer">
                        <option value="">Primero seleccione un cliente...</option>
                    </select>
                </div>

                <!-- Estado del Envase (Bueno vs Dañado) -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Estado Físico del Envase</label>
                    <select name="estado_envase" required class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl font-medium text-slate-700 outline-none focus:border-sky-400 text-sm cursor-pointer">
                        <option value="bueno">✅ Bueno / Reutilizable (Reingresa a Existencias MP)</option>
                        <option value="danado">⚠️ Dañado / Inutilizable (Sale definitivamente del inventario)</option>
                    </select>
                </div>

                <!-- Cantidad y Bodega -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Cantidad a Devolver</label>
                        <input type="number" id="modal_cantidad" name="cantidad" required min="1" placeholder="Ej. 1"
                               class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl font-bold text-slate-700 outline-none focus:border-sky-400 text-sm">
                        <p class="text-[10px] text-slate-400 font-semibold pl-1" id="max_qty_info"></p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Bodega Destino</label>
                        <input type="text" name="bodega" value="Bodega Principal" required
                               class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl font-medium text-slate-700 outline-none focus:border-sky-400 text-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModalReturn()" class="px-6 py-3.5 text-slate-500 font-bold text-sm cursor-pointer">Cancelar</button>
                    <button type="submit" class="bg-gradient-to-r from-sky-500 to-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-md text-sm cursor-pointer">
                        <i class="fas fa-save mr-1"></i> Registrar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const balancesJS = @json($balances);

        document.getElementById('searchBalances')?.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.balance-row').forEach(row => {
                row.style.display = row.dataset.search.includes(query) ? '' : 'none';
            });
        });

        function openModalReturn() {
            document.getElementById('modalReturn').classList.remove('hidden');
            document.getElementById('modalReturn').classList.add('flex');
        }
        function closeModalReturn() {
            document.getElementById('modalReturn').classList.add('hidden');
            document.getElementById('modalReturn').classList.remove('flex');
        }

        function onClientSelect() {
            const id_usuario = document.getElementById('modal_id_usuario').value;
            const prodSelect = document.getElementById('modal_id_producto');
            prodSelect.innerHTML = '<option value="">Seleccione un producto...</option>';
            document.getElementById('modal_cantidad').value = '';
            document.getElementById('modal_cantidad').removeAttribute('max');
            document.getElementById('max_qty_info').textContent = '';

            if (!id_usuario) return;

            const items = balancesJS.filter(b => b.id_usuario == id_usuario && b.en_consumo > 0);
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id_producto;
                opt.textContent = `${item.producto_nombre} (En consumo: ${item.en_consumo})`;
                prodSelect.appendChild(opt);
            });
        }

        function onProductSelect() {
            const id_usuario = document.getElementById('modal_id_usuario').value;
            const id_producto = document.getElementById('modal_id_producto').value;
            const inputQty = document.getElementById('modal_cantidad');
            const infoText = document.getElementById('max_qty_info');

            if (!id_usuario || !id_producto) {
                inputQty.removeAttribute('max');
                infoText.textContent = '';
                return;
            }

            const matched = balancesJS.find(b => b.id_usuario == id_usuario && b.id_producto == id_producto);
            if (matched) {
                inputQty.setAttribute('max', matched.en_consumo);
                infoText.textContent = `Máximo permitido: ${matched.en_consumo} unidades`;
            }
        }

        function quickReturn(b) {
            openModalReturn();
            document.getElementById('modal_id_usuario').value = b.id_usuario;
            onClientSelect();
            document.getElementById('modal_id_producto').value = b.id_producto;
            onProductSelect();
            document.getElementById('modal_cantidad').value = b.en_consumo;
        }

        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalReturn') closeModalReturn();
        });
    </script>
</body>
</html>
