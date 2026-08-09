<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle Pedido #{{ $venta->id_venta }} - TRIGESTION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','sans-serif'],outfit:['"Outfit"','sans-serif']},colors:{trigestion:{50:'#f0f9ff',100:'#e0f2fe',500:'#009ee3',600:'#0081c2',800:'#005282',900:'#082f49'}}}}}</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body{font-family:'Outfit','Plus Jakarta Sans',sans-serif;}
        .bg-blobs{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;background:#f8fafc;}
        .blob-1{position:absolute;filter:blur(80px);border-radius:50%;opacity:.3;animation:float 20s infinite alternate ease-in-out;top:-10%;left:-10%;width:50vw;height:50vw;background:radial-gradient(circle,rgba(14,165,233,.18) 0,rgba(255,255,255,0) 70%);}
        @keyframes float{0%{transform:translate(0,0) scale(1)}100%{transform:translate(5%,5%) scale(1.1)}}
        .glass-card{background:rgba(255,255,255,.8);backdrop-filter:blur(14px);border:1px solid rgba(255,255,255,.6);box-shadow:0 10px 30px -10px rgba(0,0,0,.07);}
        ::-webkit-scrollbar{width:8px}::-webkit-scrollbar-track{background:#f1f5f9}::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:4px}
    </style>
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen">
<div class="bg-blobs"><div class="blob-1"></div></div>

<div class="flex min-h-screen relative z-10">
    @include('partials.sidebar')

    <div class="flex-1 ml-72 flex flex-col min-w-0">
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-slate-400 font-bold mb-1">
                    <a href="{{ route('ventas.index') }}" class="hover:text-trigestion-500 transition-colors"><i class="fas fa-arrow-left mr-1"></i> Ventas</a>
                    <span>/</span>
                    <span class="text-slate-700">Pedido #{{ $venta->id_venta }}</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detalle del Pedido #{{ $venta->id_venta }}</h1>
            </div>
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                    <div class="w-9 h-9 rounded-xl bg-trigestion-500 text-white flex items-center justify-center font-black text-base shadow-md shrink-0">{{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'A', 0, 1)) }}</div>
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

        <main class="p-8 flex-1 font-outfit">
            @php
                $estadoColor = match($venta->estado) {
                    'Completada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'Cancelada'  => 'bg-rose-50 text-rose-700 border-rose-200',
                    default      => 'bg-amber-50 text-amber-700 border-amber-200',
                };
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Info general --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h2 class="text-lg font-black text-slate-800">Información del Pedido</h2>
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $estadoColor }}">
                                {{ $venta->estado ?? 'Pendiente' }}
                            </span>
                        </div>
                        <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Cliente / Usuario</div>
                                <div class="font-bold text-slate-700">{{ $venta->usuario->nombres ?? '—' }}</div>
                                <div class="text-slate-400 text-xs">{{ $venta->usuario->email ?? '' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Fecha</div>
                                <div class="font-bold text-slate-700">{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Método de Pago</div>
                                <div class="font-bold text-slate-700">{{ $venta->metodo_pago }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Total</div>
                                <div class="font-black text-slate-800 text-xl">${{ number_format($venta->total, 2) }}</div>
                            </div>
                            @if($venta->notas)
                            <div class="col-span-2">
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Notas</div>
                                <div class="font-medium text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 italic">{{ $venta->notas }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Detalles de productos --}}
                    <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="text-lg font-black text-slate-800">Productos del Pedido</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-black tracking-widest border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-4">Producto</th>
                                        <th class="px-6 py-4 text-center">Cantidad</th>
                                        <th class="px-6 py-4 text-right">P. Unitario</th>
                                        <th class="px-6 py-4 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($venta->detalles as $d)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center overflow-hidden shrink-0">
                                                    @if($d->producto->img)
                                                        <img src="{{ asset($d->producto->img) }}" alt="" class="w-full h-full object-contain p-1">
                                                    @else
                                                        <i class="fas fa-box text-sky-300 text-sm"></i>
                                                    @endif
                                                </div>
                                                <div class="font-bold text-slate-700 text-sm">{{ $d->producto->nombre ?? '—' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-black text-slate-800">{{ $d->cantidad }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-600">${{ number_format($d->precio_unitario, 2) }}</td>
                                        <td class="px-6 py-4 text-right font-black text-slate-800">${{ number_format($d->precio_unitario * $d->cantidad, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t-2 border-slate-200">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-black text-slate-700 uppercase text-xs tracking-widest">Total</td>
                                        <td class="px-6 py-4 text-right font-black text-slate-900 text-lg">${{ number_format($venta->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Acciones de estado --}}
                <div class="space-y-4">
                    <div class="glass-card rounded-[2.5rem] border border-white p-6 space-y-3">
                        <h3 class="text-lg font-black text-slate-800 mb-4">Cambiar Estado</h3>
                        @if($venta->estado !== 'Completada')
                        <a href="{{ route('ventas.estado', [$venta->id_venta, 'Completada']) }}"
                           onclick="return confirm('¿Marcar como Completada?')"
                           class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all font-bold text-sm">
                            <i class="fas fa-check-circle"></i> Marcar como Completada
                        </a>
                        @endif
                        @if($venta->estado !== 'Pendiente')
                        <a href="{{ route('ventas.estado', [$venta->id_venta, 'Pendiente']) }}"
                           onclick="return confirm('¿Cambiar a Pendiente?')"
                           class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all font-bold text-sm">
                            <i class="fas fa-clock"></i> Marcar como Pendiente
                        </a>
                        @endif
                        @if($venta->estado !== 'Cancelada')
                        <a href="{{ route('ventas.estado', [$venta->id_venta, 'Cancelada']) }}"
                           onclick="return confirm('¿Cancelar este pedido? El stock será devuelto al inventario.')"
                           class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all font-bold text-sm">
                            <i class="fas fa-times-circle"></i> Cancelar Pedido
                        </a>
                        @endif
                        <a href="{{ route('ventas.index') }}"
                           class="flex items-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all font-bold text-sm">
                            <i class="fas fa-arrow-left"></i> Volver a la lista
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
