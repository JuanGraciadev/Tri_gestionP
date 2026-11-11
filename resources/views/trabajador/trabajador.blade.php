@extends('layouts.panel')

@section('title', 'Panel de Trabajador')
@section('subtitle', 'Control operativo y gestión de planta')

@section('content')
                {{-- WELCOME BANNER (TrabajadorDashboard style) --}}
                <div class="glass-card rounded-[2.5rem] p-8 sm:p-10 border border-white/80 premium-shadow relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="text-center sm:text-left z-10">
                        <div class="inline-block px-3 py-1 rounded-full bg-sky-50 border border-sky-100 text-sky-600 font-bold text-xs mb-3 shadow-sm">
                            <i class="fas fa-hard-hat mr-1.5"></i>&Aacute;rea Operativa
                        </div>
                        <h2 class="text-2xl sm:text-4xl font-black text-slate-800 tracking-tight mb-2">
                            Panel de Operaciones de <span class="premium-gradient-text">Planta</span>
                        </h2>
                        <p class="text-slate-500 text-sm sm:text-base font-medium max-w-xl">
                            Gestiona producci&oacute;n, materia prima y control de inventario desde aqu&iacute;.
                        </p>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-sky-400 to-indigo-600 text-white rounded-3xl flex items-center justify-center text-3xl shadow-lg shadow-sky-500/20 z-10 shrink-0">
                        <i class="fas fa-flask"></i>
                    </div>
                </div>

                {{-- ALERTA DE STOCK EN PRODUCTOS (HU-010 / HU-023 / RF 7.10) --}}
                <div id="contenedorAlertasStockTrabajador" class="hidden rounded-[2rem] p-6 border border-amber-200 bg-amber-50/90 shadow-sm animate-fade">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-extrabold text-amber-900 tracking-tight">Alerta de Stock en Productos Terminados</h3>
                            <p class="text-xs text-amber-700 font-medium mt-0.5">Existen productos con stock disponible igual o por debajo del mínimo.</p>
                            <div id="listaAlertasStockTrabajador" class="mt-3 flex flex-wrap gap-2"></div>
                        </div>
                        <a href="{{ route('inventario-productos.index') }}" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700 transition-colors shrink-0">
                            Ver Inventario
                        </a>
                    </div>
                </div>

                {{-- QUICK ACTION GRID (TrabajadorDashboard style) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Producci&oacute;n --}}
                    <a href="{{ route('produccion.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-sky-300 hover:shadow-sky-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-sky-500 group-hover:text-white">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-sky-600 transition-colors">Producci&oacute;n</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra y finaliza &oacute;rdenes de producci&oacute;n diaria.</p>
                    </a>

                    {{-- Entrada de Insumos --}}
                    <a href="{{ route('inventario-mp.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-emerald-300 hover:shadow-emerald-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-emerald-500 group-hover:text-white">
                            <i class="fas fa-truck-ramp-box"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">Entrada de Insumos</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra compras y recepciones de materia prima al almac&eacute;n.</p>
                    </a>

                    {{-- Inventario MP --}}
                    <a href="{{ route('inventario-mp.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-teal-300 hover:shadow-teal-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-teal-500 group-hover:text-white">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-teal-600 transition-colors">Inventario MP</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Consulta gr&aacute;ficas de stock y trazabilidad de insumos.</p>
                    </a>

                    {{-- Gesti&oacute;n de Lotes --}}
                    <a href="{{ route('lotes.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-indigo-300 hover:shadow-indigo-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-indigo-500 group-hover:text-white">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Gesti&oacute;n de Lotes</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Registra lotes de botellas y asigna caracter&iacute;sticas de envases.</p>
                    </a>

                    {{-- Inventario Productos --}}
                    <a href="{{ route('inventario-productos.index') }}" class="glass-card rounded-[2rem] p-8 border border-white/80 premium-shadow transition-all duration-300 group flex flex-col items-center text-center hover:-translate-y-2 hover:border-amber-300 hover:shadow-amber-100">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold mb-5 transition-all shadow-inner group-hover:bg-amber-500 group-hover:text-white">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-amber-600 transition-colors">Inventario Productos</h3>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium">Verifica stock terminado y listo para distribuci&oacute;n.</p>
                    </a>
                </div>
@endsection

@push('scripts')
    <script>
        fetch('/api/productos/alertas')
            .then(res => res.json())
            .then(data => {
                if (data.ok && data.hayAlertas) {
                    const container = document.getElementById('contenedorAlertasStockTrabajador');
                    const lista = document.getElementById('listaAlertasStockTrabajador');
                    if (!container || !lista) return;

                    let html = '';
                    (data.agotados || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-100 text-rose-800 border border-rose-200 text-xs font-black">
                            <i class="fas fa-times-circle text-rose-500"></i> ${p.nombre} (Agotado)
                        </span>`;
                    });
                    (data.stockBajo || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-100 text-amber-800 border border-amber-200 text-xs font-black">
                            <i class="fas fa-exclamation-circle text-amber-500"></i> ${p.nombre} (${p.disponible}/${p.stock_minimo})
                        </span>`;
                    });
                    lista.innerHTML = html;
                    container.classList.remove('hidden');
                }
            })
            .catch(() => {});
    </script>
@endpush
