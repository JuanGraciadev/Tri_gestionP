{{-- Sidebar del Trabajador (rol 2) --}}
<aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
    <div class="px-6 py-6 border-b border-white/10 bg-black/10">
        <a href="{{ route('trabajador.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-amber-300 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">OPERACIONES</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
        <!-- MENÚ PRINCIPAL -->
        <div>
            <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MENÚ PRINCIPAL</p>
            <div class="space-y-1">
                <a href="{{ route('trabajador.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('trabajador.dashboard') ? 'font-extrabold bg-sky-500/30 text-white border-l-4 border-sky-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-chart-pie {{ request()->routeIs('trabajador.dashboard') ? 'text-sky-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- ÁREA DE TRABAJO -->
        <div>
            <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">ÁREA DE TRABAJO</p>
            <div class="space-y-1">
                <a href="{{ route('produccion.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('produccion.*') ? 'font-extrabold bg-emerald-500/20 text-white border-l-4 border-emerald-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-industry {{ request()->routeIs('produccion.*') ? 'text-emerald-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Producción</span>
                </a>
                <a href="{{ route('inventario-mp.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('inventario-mp.index') ? 'font-extrabold bg-emerald-500/20 text-white border-l-4 border-emerald-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-boxes-stacked {{ request()->routeIs('inventario-mp.index') ? 'text-emerald-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Inventario MP</span>
                </a>
                <a href="{{ route('lotes.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('lotes.*') ? 'font-extrabold bg-amber-500/20 text-white border-l-4 border-amber-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-box-open {{ request()->routeIs('lotes.*') ? 'text-amber-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Gestión de Lotes</span>
                </a>
                <a href="{{ route('inventario-productos.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('inventario-productos.index') ? 'font-extrabold bg-violet-500/20 text-white border-l-4 border-violet-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-cubes {{ request()->routeIs('inventario-productos.index') ? 'text-violet-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Inventario Productos</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fas fa-rotate-left text-sky-300/70 group-hover:text-sky-300 shrink-0"></i>
                    <span>Devoluciones</span>
                </a>
            </div>
        </div>
    </nav>
</aside>
