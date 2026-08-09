<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Compras - TRIGESTION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','sans-serif'],outfit:['"Outfit"','sans-serif']},colors:{trigestion:{50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#009ee3',600:'#0081c2',700:'#00669e',800:'#005282',900:'#082f49'}}}}}</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body{font-family:'Outfit','Plus Jakarta Sans',sans-serif;}
        .bg-blobs{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc;}
        .blob-1,.blob-2{position:absolute;filter:blur(80px);border-radius:50%;opacity:.4;animation:float 20s infinite alternate ease-in-out;}
        .blob-1{top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(14,165,233,.18) 0,rgba(255,255,255,0) 70%);}
        .blob-2{bottom:-20%;right:-10%;width:60vw;height:60vw;background:radial-gradient(circle,rgba(99,102,241,.12) 0,rgba(255,255,255,0) 70%);animation-delay:-5s;}
        @keyframes float{0%{transform:translate(0,0) scale(1)}100%{transform:translate(5%,5%) scale(1.1)}}
        .glass-card{background:rgba(255,255,255,.8);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.6);box-shadow:0 10px 30px -10px rgba(0,0,0,.07);}
        ::-webkit-scrollbar{width:8px}::-webkit-scrollbar-track{background:#f1f5f9}::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">
<div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div></div>

<div class="flex min-h-screen relative z-10">
    <!-- SIDEBAR -->
    <aside class="w-72 bg-gradient-to-b from-trigestion-800 via-trigestion-900 to-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
        <div class="px-6 py-6 border-b border-white/10 bg-black/10">
            <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-indigo-300 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <span class="text-xl font-black tracking-wider text-white block leading-none">TRIGESTION</span>
                    <span class="text-[10px] text-sky-300 font-bold tracking-widest uppercase mt-1 block">PORTAL CLIENTE</span>
                </div>
            </a>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
            <div>
                <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MENÚ PRINCIPAL</p>
                <div class="space-y-1">
                    <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fas fa-chart-pie text-sky-300/70 group-hover:text-sky-300 shrink-0"></i><span>Dashboard</span>
                    </a>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-black text-sky-300/70 tracking-widest uppercase px-3 mb-2">MI TIENDA</p>
                <div class="space-y-1">
                    <a href="{{ route('productos.catalogo') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fas fa-store text-sky-300/70 group-hover:text-sky-300 shrink-0"></i><span>Catálogo de Productos</span>
                    </a>
                    <a href="{{ route('ventas.checkout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fas fa-cart-shopping text-sky-300/70 group-hover:text-sky-300 shrink-0"></i><span>Carrito / Checkout</span>
                    </a>
                    <a href="{{ route('ventas.mis-compras') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-indigo-500/20 text-white border-l-4 border-indigo-400 shadow-md">
                        <i class="fas fa-shopping-bag text-indigo-300 shrink-0"></i><span>Mis Compras</span>
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <div class="flex-1 ml-72 flex flex-col min-w-0">
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mis Compras</h1>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Historial de pedidos realizados</p>
            </div>
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                    <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">{{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'C', 0, 1)) }}</div>
                    <p class="text-xs font-extrabold text-slate-900 hidden sm:block truncate max-w-[160px]">{{ Auth::user()->nombres ?? Auth::user()->name }}</p>
                    <svg class="w-4 h-4 text-slate-400 shrink-0" :class="{'rotate-180':open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50" style="display:none">
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-8 flex-1 font-outfit space-y-8">

            @if(session('alert'))
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                    Swal.fire({icon:'{{ session("alert.icon") }}',title:'{!! addslashes(session("alert.title")) !!}',text:'{!! addslashes(session("alert.text")) !!}',confirmButtonColor:'#009ee3',customClass:{popup:'rounded-[2rem] font-outfit'}});
                });
            </script>
            @endif

            <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Historial de Pedidos</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-800">Mis <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-sky-500">Compras</span></h2>
                    </div>
                </div>

                @forelse($ventas as $venta)
                @php
                    $estadoColor = match($venta->estado) {
                        'Completada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Cancelada'  => 'bg-rose-50 text-rose-700 border-rose-200',
                        default      => 'bg-amber-50 text-amber-700 border-amber-200',
                    };
                    $estadoIcon = match($venta->estado) {
                        'Completada' => 'fa-check-circle',
                        'Cancelada'  => 'fa-times-circle',
                        default      => 'fa-clock',
                    };
                @endphp
                <div class="border-b border-slate-100 last:border-b-0">
                    <div class="px-8 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl font-bold shrink-0">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div>
                                <div class="font-black text-slate-800">Pedido #{{ $venta->id_venta }}</div>
                                <div class="text-xs font-bold text-slate-400 mt-0.5">
                                    {{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : '—' }}
                                    · {{ $venta->metodo_pago }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $estadoColor }}">
                                <i class="fas {{ $estadoIcon }} text-[10px]"></i> {{ $venta->estado ?? 'Pendiente' }}
                            </span>
                            <div class="text-right">
                                <div class="font-black text-slate-800 text-lg">${{ number_format($venta->total, 2) }}</div>
                                <div class="text-[11px] font-bold text-slate-400">{{ $venta->detalles->sum('cantidad') }} unidad(es)</div>
                            </div>
                        </div>
                    </div>
                    {{-- Detalles del pedido --}}
                    @if($venta->detalles->count())
                    <div class="px-8 pb-5">
                        <div class="bg-slate-50 rounded-2xl overflow-hidden border border-slate-100">
                            @foreach($venta->detalles as $d)
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 last:border-b-0 text-sm">
                                <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden">
                                    @if($d->producto->img)
                                        <img src="{{ asset($d->producto->img) }}" alt="" class="w-full h-full object-contain p-0.5">
                                    @else
                                        <i class="fas fa-box text-slate-300 text-xs"></i>
                                    @endif
                                </div>
                                <span class="font-bold text-slate-700 flex-1">{{ $d->producto->nombre ?? '—' }}</span>
                                <span class="font-black text-slate-600">× {{ $d->cantidad }}</span>
                                <span class="font-black text-slate-800">${{ number_format($d->precio_unitario * $d->cantidad, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($venta->notas)
                    <div class="px-8 pb-4">
                        <p class="text-xs text-slate-500 italic bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                            <i class="fas fa-sticky-note mr-1"></i> {{ $venta->notas }}
                        </p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="px-8 py-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="fas fa-shopping-bag text-3xl"></i>
                    </div>
                    <p class="text-slate-500 font-bold text-lg mb-1">No tienes compras registradas</p>
                    <p class="text-slate-400 font-medium text-sm mb-5">Explora el catálogo y realiza tu primer pedido.</p>
                    <a href="{{ route('productos.catalogo') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-trigestion-500 to-sky-500 text-white rounded-2xl font-bold text-sm shadow-lg hover:-translate-y-1 transition-all">
                        <i class="fas fa-store"></i> Ver Catálogo
                    </a>
                </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
</body>
</html>
