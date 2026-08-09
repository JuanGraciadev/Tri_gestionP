{{--
    Top header bar: title + subtitle + user dropdown with logout.
    Props:
      $title    - page title (required)
      $subtitle - subtitle text (optional)
--}}
<header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $title ?? 'TRIGESTION' }}</h1>
        @if (!empty($subtitle))
            <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button @click="open = !open"
                class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
            <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">
                {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <p class="text-xs font-extrabold text-slate-900 hidden sm:block truncate max-w-[160px]">
                {{ Auth::user()->nombres ?? Auth::user()->name }}
            </p>
            <svg class="w-4 h-4 text-slate-400 shrink-0" :class="{ 'rotate-180': open }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition
             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50"
             style="display:none">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</header>
