{{--
    Top header bar: glass-panel, title + subtitle + user dropdown with logout.
    Props:
      $title    - page title
      $subtitle - subtitle text
--}}
<header class="h-20 sm:h-24 glass-panel px-4 sm:px-8 flex items-center justify-between sticky top-0 z-20 gap-3">
    {{-- Left: Mobile Toggle & Page Title --}}
    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
        {{-- Mobile Hamburger --}}
        <button @click="mobileSidebar = !mobileSidebar"
                type="button"
                class="lg:hidden w-11 h-11 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:text-sky-600 hover:border-sky-300 hover:shadow-md transition-all flex items-center justify-center cursor-pointer shrink-0"
                title="Abrir menú de navegación">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 tracking-tight truncate">
                {{ $title ?? 'TRIGESTION' }}
            </h1>
            @if (!empty($subtitle))
                <p class="text-xs text-slate-400 font-medium hidden sm:block truncate">{{ $subtitle }}</p>
            @else
                <p class="text-xs text-slate-400 font-medium hidden sm:block">Panel de Control &mdash; TRIGESTION</p>
            @endif
        </div>
    </div>

    {{-- Right: Actions & User Profile --}}
    <div class="flex items-center gap-3 sm:gap-5">
        {{-- Custom Page Actions (e.g. Cart) --}}
        @hasSection('topbar-actions')
            @yield('topbar-actions')
        @endif

        {{-- Date Badge (Desktop) --}}
        <div class="hidden md:flex items-center gap-2 bg-white/80 border border-slate-200 px-3.5 py-2 rounded-2xl text-xs font-bold text-slate-600 shadow-sm">
            <i class="fas fa-calendar-day text-sky-500"></i>
            <span>{{ now()->format('d/m/Y') }}</span>
        </div>

        <div class="h-8 w-[1px] bg-slate-200 hidden sm:block"></div>

        {{-- User Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                    type="button"
                    class="flex items-center gap-3 rounded-2xl px-2 py-1.5 hover:bg-slate-100 transition-all cursor-pointer select-none">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-800 truncate max-w-[140px]">
                        {{ Auth::user()->nombres ?? Auth::user()->name }}
                    </div>
                    <div class="text-[11px] text-emerald-500 font-bold tracking-wide uppercase flex items-center justify-end gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Online
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-sky-400 to-indigo-600 flex items-center justify-center text-white text-base font-black shadow-md shadow-sky-500/20">
                    {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200 hidden sm:block" :class="{ 'rotate-180': open }"></i>
            </button>

            {{-- Dropdown panel --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 top-[calc(100%+0.5rem)] w-60 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden z-50 divide-y divide-slate-100"
                 style="display:none">
                {{-- User info --}}
                <div class="px-4 py-3 bg-slate-50">
                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                    <p class="text-[11px] text-sky-600 font-bold uppercase tracking-wider mt-0.5">
                        @php
                            $rolId = (int)(Auth::user()->id_rol ?? 0);
                            echo $rolId === 1 ? 'Administrador' : ($rolId === 2 ? 'Trabajador' : 'Cliente');
                        @endphp
                    </p>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                </div>

                {{-- Links --}}
                <div class="p-1">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-sky-600 transition-all">
                        <i class="fas fa-user-gear text-sm text-slate-400"></i>
                        <span>Mi Perfil</span>
                    </a>
                </div>

                {{-- Cerrar sesión --}}
                <div class="p-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-rose-500 hover:bg-rose-50 transition-all cursor-pointer">
                            <i class="fas fa-right-from-bracket text-sm"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

