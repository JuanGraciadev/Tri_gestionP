{{--
    Top header bar: glass-panel, title + subtitle + user dropdown with logout.
    PATri-Gestion style.
    Props:
      $title    - page title (required)
      $subtitle - subtitle text (optional)
--}}
<header class="h-20 sm:h-24 glass-panel px-4 sm:px-8 flex items-center justify-between sticky top-0 z-20 gap-3">
    {{-- Left: Page Title --}}
    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight truncate">{{ $title ?? 'TRIGESTION' }}</h1>
            @if (!empty($subtitle))
                <p class="text-xs text-slate-400 font-medium hidden sm:block">{{ $subtitle }}</p>
            @else
                <p class="text-xs text-slate-400 font-medium hidden sm:block">Bienvenido al panel de control de Trigestion!</p>
            @endif
        </div>
    </div>

    {{-- Right: Actions & User Profile --}}
    <div class="flex items-center gap-3 sm:gap-5">
        {{-- Bell Notifications --}}
        <button class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-indigo-600 hover:border-indigo-300 hover:shadow-lg transition-all relative flex items-center justify-center" title="Sin notificaciones">
            <i class="fas fa-bell text-[17px]"></i>
            <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-slate-300 rounded-full border border-white"></span>
        </button>

        <div class="h-9 w-[1px] bg-slate-200 hidden sm:block"></div>

        {{-- User Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-3 rounded-2xl px-2 py-1.5 hover:bg-slate-100 transition-all cursor-pointer select-none">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-800 truncate max-w-[140px]">{{ Auth::user()->nombres ?? Auth::user()->name }}</div>
                    <div class="text-[11px] text-emerald-500 font-bold tracking-wide uppercase flex items-center justify-end gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Online
                    </div>
                </div>
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center text-indigo-600 text-base font-bold shadow-sm border border-indigo-200/50">
                    <i class="fas fa-user-tie"></i>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 hidden sm:block" :class="{ 'rotate-180': open }"></i>
            </button>

            {{-- Dropdown panel --}}
            <div x-show="open" x-transition
                 class="absolute right-0 top-[calc(100%+0.5rem)] w-56 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50"
                 style="display:none">
                {{-- User info --}}
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider mt-0.5">
                        @php
                            $rolId = (int)(Auth::user()->id_rol ?? 0);
                            echo $rolId === 1 ? 'Administrador' : ($rolId === 2 ? 'Trabajador' : 'Cliente');
                        @endphp
                    </p>
                </div>
                {{-- Cerrar sesi&oacute;n --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-rose-500 hover:bg-rose-50 transition-all">
                        <i class="fas fa-right-from-bracket text-sm"></i>
                        <span>Cerrar Sesi&oacute;n</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
