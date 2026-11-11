{{-- Sidebar del Administrador (rol 1) - Dark theme PATri-Gestion --}}
<aside id="sidebar-admin"
       class="w-[280px] shrink-0 bg-[#090e17] text-slate-400 flex flex-col z-40 fixed inset-y-0 left-0 transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-[10px_0_40px_-15px_rgba(0,0,0,0.5)] border-r border-white/5"
       :class="{ 'translate-x-0': mobileSidebar, '-translate-x-full lg:translate-x-0': !mobileSidebar }">
    {{-- Glow Decals --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-sky-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 flex flex-col h-full">
        {{-- Logo Branding --}}
        <div class="h-24 flex items-center justify-between px-6 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-600 flex items-center justify-center text-white text-lg shadow-md shadow-sky-500/20">
                    <i class="fas fa-droplet"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400 tracking-[0.2em] uppercase">SISTEMA</div>
                    <div class="text-xl font-bold text-white tracking-wide">TRIGESTION</div>
                </div>
            </div>
            <button @click="mobileSidebar = false" class="lg:hidden text-slate-400 hover:text-white p-2">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 custom-scrollbar">
            <div class="px-4 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Menú Principal</div>

            {{-- Dashboard General / Gestión Usuarios --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-gauge-high text-base {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Dashboard General</span>
            </a>

            <div class="pt-5 px-4 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Operaciones</div>

            {{-- Entrada de Insumos / Inventario MP --}}
            <a href="{{ route('inventario-mp.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('inventario-mp.index') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-truck-ramp-box text-base {{ request()->routeIs('inventario-mp.index') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Inventario MP</span>
            </a>

            {{-- Lotes --}}
            <a href="{{ route('lotes.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('lotes.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-box-open text-base {{ request()->routeIs('lotes.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Gestión de Lotes</span>
            </a>

            {{-- Categorías --}}
            <a href="{{ route('categorias.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('categorias.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-tags text-base {{ request()->routeIs('categorias.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Categorías</span>
            </a>

            {{-- Productos --}}
            <a href="{{ route('productos.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('productos.index') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-tint text-base {{ request()->routeIs('productos.index') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Productos</span>
            </a>

            {{-- Inventario Productos --}}
            <a href="{{ route('inventario-productos.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('inventario-productos.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-cubes text-base {{ request()->routeIs('inventario-productos.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Inventario Productos</span>
            </a>

            {{-- Ventas y Pedidos --}}
            <a href="{{ route('ventas.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('ventas.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-cart-shopping text-base {{ request()->routeIs('ventas.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Ventas y Pedidos</span>
            </a>

            {{-- Devoluciones --}}
            <a href="{{ route('devoluciones.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('devoluciones.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-rotate-left text-base {{ request()->routeIs('devoluciones.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Devoluciones</span>
            </a>

            <div class="pt-5 px-4 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Análisis</div>

            {{-- Reportes Generales --}}
            <a href="{{ route('reportes.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('reportes.*') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-file-invoice-dollar text-base {{ request()->routeIs('reportes.*') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Reportes Generales</span>
            </a>

            <div class="pt-4"></div>
        </nav>
    </div>
</aside>

