@extends('layouts.panel')

@section('title', 'Mi Dashboard')
@section('subtitle', 'Bienvenido a tu panel personal de Trigestion')

@section('content')
                @php
                    $userVentas = \DB::table('venta')->where('id_usuario', Auth::id())->get();
                    $totalPedidos = count($userVentas);
                    $totalGastado = $userVentas->sum('total');
                    $pedidosEntregados = $userVentas->where('estado', 'Entregado')->count();
                    $pedidosEnProceso = $userVentas->where('estado', 'En Proceso')->count();
                    $ultimosPedidos = $userVentas->sortByDesc('id_venta')->take(5);
                @endphp

                {{-- HERO WELCOME BANNER (ClienteDashboard style) --}}
                <div class="relative rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-sky-500 via-indigo-600 to-indigo-700 p-8 sm:p-12 text-white shadow-2xl shadow-sky-500/20">
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-10 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="inline-block px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white font-bold text-xs mb-4">
                                <i class="fas fa-droplet mr-1.5 text-sky-200"></i>Agua Purificada de M&aacute;xima Calidad
                            </div>
                            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                                &iexcl;Hola, {{ explode(' ', Auth::user()->nombres ?? Auth::user()->name ?? 'Cliente')[0] }}!
                            </h1>
                            <p class="text-sky-100 text-sm sm:text-base mt-2 max-w-lg font-medium opacity-90">
                                Bienvenido a tu panel de cliente. Monitorea el estado de tus pedidos y solicita nuevos garrafones o botellas cuando lo desees.
                            </p>
                        </div>
                        <a href="{{ route('productos.catalogo') }}" class="px-8 py-4 rounded-2xl bg-white text-indigo-700 font-black shadow-xl hover:bg-sky-50 transform hover:-translate-y-1 transition-all flex items-center gap-3 shrink-0 text-base">
                            <i class="fas fa-cart-shopping text-sky-600"></i>
                            <span>Comprar Ahora</span>
                        </a>
                    </div>
                </div>

                {{-- KPI STATS --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    {{-- Pedidos Realizados --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-sky-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-bag-shopping"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Historial</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $totalPedidos }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Pedidos Realizados</div>
                        </div>
                    </div>
                    {{-- Total Invertido --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-indigo-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Compras</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">${{ number_format($totalGastado, 2) }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Total Invertido</div>
                        </div>
                    </div>
                    {{-- Entregados --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-emerald-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-circle-check"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Completados</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $pedidosEntregados }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">Entregados</div>
                        </div>
                    </div>
                    {{-- En Camino --}}
                    <div class="glass-card rounded-2xl sm:rounded-[2rem] p-6 border border-white/80 premium-shadow hover:shadow-amber-100 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xl shadow-md">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">En Proceso</span>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-1">{{ $pedidosEnProceso }}</div>
                            <div class="text-xs sm:text-sm font-semibold text-slate-500">En Camino</div>
                        </div>
                    </div>
                </div>

                {{-- RECENT ORDERS CARD (ClienteDashboard style) --}}
                <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/80 premium-shadow">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Pedidos Recientes</h2>
                        <a href="{{ route('cliente.misCompras') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 hover:underline">
                            Ver todos los pedidos <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    @if ($ultimosPedidos->isEmpty())
                    <div class="py-12 text-center text-slate-400">
                        <div class="w-16 h-16 rounded-full bg-sky-50 text-sky-400 flex items-center justify-center text-2xl mx-auto mb-3">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <p class="font-bold text-slate-700">A&uacute;n no has realizado pedidos</p>
                        <p class="text-xs text-slate-400 mt-1">Visita el cat&aacute;logo para solicitar tus productos de agua pura.</p>
                    </div>
                    @else
                    <div class="divide-y divide-slate-100">
                        @foreach ($ultimosPedidos as $p)
                        @php
                            $estado = $p->estado ?? 'Pendiente';
                            $estadoStyles = [
                                'Entregado' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                'En Proceso' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                'Cancelado' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                'Pendiente' => 'bg-sky-50 text-sky-600 border border-sky-100',
                            ][$estado] ?? 'bg-sky-50 text-sky-600 border border-sky-100';
                        @endphp
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-sm">
                                    #{{ $p->id_venta }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 text-sm">Pedido de productos</div>
                                    <div class="text-xs text-slate-400">
                                        {{ optional(\Carbon\Carbon::parse($p->fecha))->format('d/m/Y') ?? '' }} &bull; {{ $p->metodo_pago ?? 'Efectivo' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4">
                                <div class="text-sm font-black text-slate-800">${{ number_format($p->total, 2) }}</div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $estadoStyles }}">{{ $estado }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- ACCESOS DIRECTOS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <a href="{{ route('productos.catalogo') }}" class="glass-card rounded-[2.5rem] border border-slate-100 p-8 flex items-center gap-5 hover:border-sky-300 hover:shadow-xl transition-all group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg">Cat&aacute;logo de Productos</h4>
                            <p class="text-sm text-slate-400 font-semibold mt-0.5">Explora y compra nuestros productos</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-sky-500 transition-colors"></i>
                    </a>
                    <a href="{{ route('cliente.misCompras') }}" class="glass-card rounded-[2.5rem] border border-slate-100 p-8 flex items-center gap-5 hover:border-indigo-300 hover:shadow-xl transition-all group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 text-lg">Mis Compras</h4>
                            <p class="text-sm text-slate-400 font-semibold mt-0.5">Revisa el estado de tus pedidos</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                    </a>
                </div>
@endsection
