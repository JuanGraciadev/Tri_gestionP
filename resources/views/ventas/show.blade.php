@extends('layouts.panel')

@section('title', 'Detalle del Pedido #{{ str_pad($venta->id_venta, 4, "0", STR_PAD_LEFT) }}')
@section('subtitle', 'Información, trazabilidad y comprobante del pedido')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('ventas.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-sky-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Volver al Centro de Ventas</span>
        </a>

        <a href="{{ route('ventas.factura', $venta->id_venta) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-sky-500 to-indigo-600 text-white rounded-xl font-bold text-xs shadow-md hover:-translate-y-0.5 transition-all">
            <i class="fas fa-receipt"></i>
            <span>Ver Factura / Ticket POS</span>
        </a>
    </div>

    @php
        $estadoColor = match($venta->estado) {
            'Pendiente'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'En Proceso' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Entregado'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Cancelado'  => 'bg-rose-50 text-rose-700 border-rose-200',
            default      => 'bg-slate-50 text-slate-700 border-slate-200',
        };

        $pagoBadge = match($venta->estado_pago ?? 'Pendiente') {
            'Confirmado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Rechazado'  => 'bg-rose-50 text-rose-700 border-rose-200',
            default      => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Info general y productos --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">
                            Pedido #{{ str_pad($venta->id_venta, 4, '0', STR_PAD_LEFT) }}
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Información general del pedido</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider border {{ $estadoColor }}">
                            <i class="fas fa-circle text-[8px]"></i>
                            {{ $venta->estado ?? 'Pendiente' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider border {{ $pagoBadge }}">
                            <i class="fas fa-credit-card text-[10px]"></i>
                            Pago: {{ $venta->estado_pago ?? 'Pendiente' }}
                        </span>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Cliente</div>
                        <div class="font-bold text-slate-700">{{ $venta->cliente_nombre ?? ($venta->cliente->usuario->nombres ?? $venta->usuario->nombres ?? 'Cliente Mostrador') }}</div>
                        <div class="text-slate-400 text-xs">{{ $venta->cliente_email ?? ($venta->cliente->usuario->email ?? $venta->usuario->email ?? '') }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Fecha de Registro</div>
                        <div class="font-bold text-slate-700">{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : '—' }}</div>
                        <div class="text-slate-400 text-xs">Cajero: {{ $venta->usuario->nombres ?? 'Sistema' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Método de Pago</div>
                        <div class="font-bold text-slate-700 flex items-center gap-2">
                            <i class="fas fa-wallet text-sky-500"></i>
                            {{ $venta->metodo_pago ?? 'Efectivo' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Total Liquidado</div>
                        <div class="font-black text-emerald-600 text-2xl">${{ number_format($venta->total, 2) }}</div>
                    </div>
                    @if($venta->notas)
                    <div class="col-span-full">
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-1">Notas del Pedido</div>
                        <div class="font-medium text-slate-600 bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100 italic">{{ $venta->notas }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Detalles de productos --}}
            <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-black text-slate-800">Productos del Pedido</h2>
                    <p class="text-xs text-slate-400 font-medium">Ítems despachados en esta orden</p>
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
                            @forelse($venta->detalles as $d)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center overflow-hidden shrink-0">
                                            @if($d->producto && $d->producto->img)
                                                <img src="{{ asset($d->producto->img) }}" alt="" class="w-full h-full object-contain p-1">
                                            @else
                                                <i class="fas fa-box text-sky-400 text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-700 text-sm">{{ $d->producto->nombre ?? 'Producto #' . $d->id_producto }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-black text-slate-800">{{ $d->cantidad }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-600">${{ number_format($d->precio_unitario, 2) }}</td>
                                <td class="px-6 py-4 text-right font-black text-slate-800">${{ number_format($d->precio_unitario * $d->cantidad, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">Sin productos asociados</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="border-t-2 border-slate-200 bg-slate-50/50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-black text-slate-700 uppercase text-xs tracking-widest">Total:</td>
                                <td class="px-6 py-4 text-right font-black text-emerald-600 text-xl">${{ number_format($venta->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Acciones y Gestión de Estado --}}
        <div class="space-y-4">
            <div class="glass-card rounded-[2.5rem] border border-white p-6 space-y-4 shadow-sm">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <i class="fas fa-sliders"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-800">Gestión de Estado</h3>
                        <p class="text-[11px] text-slate-400 font-semibold">Flujo de vida del pedido</p>
                    </div>
                </div>

                @if($venta->esEditable())
                    @if($venta->estado === 'Pendiente')
                    <!-- Pasar a En Proceso -->
                    <form action="{{ route('ventas.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">
                        <input type="hidden" name="estado" value="En Proceso">
                        <button type="submit"
                                onclick="return confirm('¿Confirmar stock y pasar pedido a En Proceso?')"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all font-bold text-xs cursor-pointer shadow-sm">
                            <i class="fas fa-gears"></i> Pasar a "En Proceso"
                        </button>
                    </form>

                    <!-- Cancelar Pedido -->
                    <form action="{{ route('ventas.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">
                        <input type="hidden" name="estado" value="Cancelado">
                        <button type="submit"
                                onclick="return confirm('¿Seguro que deseas cancelar este pedido?')"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all font-bold text-xs cursor-pointer shadow-sm">
                            <i class="fas fa-times-circle"></i> Cancelar Pedido
                        </button>
                    </form>
                    @elseif($venta->estado === 'En Proceso')
                    <!-- Pasar a Entregado -->
                    <form action="{{ route('ventas.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">
                        <input type="hidden" name="estado" value="Entregado">
                        <button type="submit"
                                onclick="return confirm('¿Marcar pedido como Entregado?')"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all font-bold text-xs cursor-pointer shadow-sm">
                            <i class="fas fa-circle-check"></i> Marcar como "Entregado"
                        </button>
                    </form>

                    <!-- Cancelar Pedido -->
                    <form action="{{ route('ventas.cambiarEstado') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_venta" value="{{ $venta->id_venta }}">
                        <input type="hidden" name="estado" value="Cancelado">
                        <button type="submit"
                                onclick="return confirm('¿Cancelar pedido? La materia/stock se gestionará según las reglas de negocio.')"
                                class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-600 hover:text-white transition-all font-bold text-xs cursor-pointer shadow-sm">
                            <i class="fas fa-times-circle"></i> Cancelar Pedido
                        </button>
                    </form>
                    @endif
                @else
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                        <i class="fas fa-lock text-slate-400 text-lg mb-1"></i>
                        <p class="text-xs font-bold text-slate-600">Estado Final: {{ $venta->estado }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Este pedido está finalizado y no admite más transiciones.</p>
                    </div>
                @endif

                <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('ventas.factura', $venta->id_venta) }}" target="_blank"
                       class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-2xl bg-slate-900 text-white hover:bg-slate-800 transition-all font-bold text-xs cursor-pointer shadow-md">
                        <i class="fas fa-print"></i> Imprimir Comprobante POS
                    </a>

                    <a href="{{ route('ventas.index') }}"
                       class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all font-bold text-xs cursor-pointer">
                        <i class="fas fa-list"></i> Volver a Lista de Pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
