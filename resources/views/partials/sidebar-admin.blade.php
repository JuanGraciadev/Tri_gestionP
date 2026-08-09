{{-- Sidebar del Administrador (rol 1) --}}
<aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
    <div class="px-6 py-6 border-b border-white/10 bg-black/10">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
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
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('admin.dashboard') ? 'font-extrabold bg-indigo-600/30 text-white border-l-4 border-indigo-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-users {{ request()->routeIs('admin.dashboard') ? 'text-indigo-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Gestión Usuarios</span>
                </a>
            </div>
        </div>

        <!-- OPERACIONES -->
        <div>
            <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">OPERACIONES</p>
            <div class="space-y-1">
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
                <a href="{{ route('categorias.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('categorias.*') ? 'font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-tags {{ request()->routeIs('categorias.*') ? 'text-trigestion-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Categorías</span>
                </a>
                <a href="{{ route('productos.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('productos.index') || request()->routeIs('productos.catalogo') ? 'font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-tint {{ request()->routeIs('productos.*') ? 'text-trigestion-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Productos</span>
                </a>
                <a href="{{ route('produccion.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('produccion.*') ? 'font-extrabold bg-emerald-500/20 text-white border-l-4 border-emerald-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-industry {{ request()->routeIs('produccion.*') ? 'text-emerald-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Producción</span>
                </a>
                <a href="{{ route('inventario-productos.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('inventario-productos.index') ? 'font-extrabold bg-violet-500/20 text-white border-l-4 border-violet-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-cubes {{ request()->routeIs('inventario-productos.index') ? 'text-violet-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Inventario Productos</span>
                </a>
                <a href="{{ route('ventas.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('ventas.*') ? 'font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-cart-shopping {{ request()->routeIs('ventas.*') ? 'text-trigestion-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Ventas y Pedidos</span>
                </a>
                <a href="{{ route('devoluciones.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('devoluciones.*') ? 'font-extrabold bg-rose-500/20 text-white border-l-4 border-rose-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-rotate-left {{ request()->routeIs('devoluciones.*') ? 'text-rose-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Devoluciones</span>
                </a>
            </div>
        </div>

        <!-- ANÁLISIS -->
        <div>
            <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">ANÁLISIS</p>
            <div class="space-y-1">
                <a href="{{ route('reportes.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all group
                   {{ request()->routeIs('reportes.*') ? 'font-extrabold bg-indigo-600/30 text-white border-l-4 border-indigo-400 shadow-md' : 'text-sky-100/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-file-invoice-dollar {{ request()->routeIs('reportes.*') ? 'text-indigo-300' : 'text-sky-300/70 group-hover:text-sky-300' }} shrink-0"></i>
                    <span>Reportes Generales</span>
                </a>
            </div>
        </div>
    </nav>
</aside>
