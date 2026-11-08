{{-- Sidebar del Cliente (rol 3) - Dark theme PATri-Gestion --}}
<aside class="w-[280px] shrink-0 bg-[#090e17] text-slate-400 flex flex-col z-40 fixed inset-y-0 left-0 shadow-[10px_0_40px_-15px_rgba(0,0,0,0.5)] border-r border-white/5">
    {{-- Glow Decals --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 flex flex-col h-full">
        {{-- Logo Branding --}}
        <div class="h-24 flex items-center px-6 border-b border-white/5 gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-400 to-emerald-500 flex items-center justify-center text-white text-lg shadow-md shadow-sky-500/20">
                <i class="fas fa-droplet"></i>
            </div>
            <div>
                <div class="text-[10px] font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-400 tracking-[0.2em] uppercase">SISTEMA</div>
                <div class="text-xl font-bold text-white tracking-wide">TRIGESTION</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 custom-scrollbar">
            <div class="px-4 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Men&uacute; Principal</div>

            <a href="{{ route('cliente.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('cliente.dashboard') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-chart-pie text-base {{ request()->routeIs('cliente.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Mi Dashboard</span>
            </a>

            <div class="pt-5 px-4 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Mi Tienda</div>

            <a href="{{ route('productos.catalogo') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('productos.catalogo') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-store text-base {{ request()->routeIs('productos.catalogo') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Cat&aacute;logo de Productos</span>
            </a>

            <a href="{{ route('cliente.misCompras') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm transition-all relative overflow-hidden group
               {{ request()->routeIs('cliente.misCompras') ? 'bg-gradient-to-r from-sky-500 to-indigo-600 text-white shadow-[0_10px_20px_-10px_rgba(14,165,233,0.5)] font-bold border border-sky-400/50 scale-[1.02] transform' : 'text-slate-400 hover:bg-slate-800/80 hover:text-white border border-transparent font-medium' }}">
                <i class="fas fa-shopping-bag text-base {{ request()->routeIs('cliente.misCompras') ? 'text-white' : 'text-slate-400 group-hover:text-sky-400' }} shrink-0"></i>
                <span class="flex-1 truncate">Mis Compras</span>
            </a>

            <div class="pt-4"></div>
        </nav>
    </div>
</aside>
