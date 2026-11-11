@extends('layouts.panel')

@section('title', 'Detalle del Pedido #{{ $venta->id_venta }}')
@section('subtitle', 'Información y comprobante del pedido')

@section('content')
<div class="mb-4">
    <a href="{{ route('ventas.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-sky-600 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Volver a Ventas</span>
    </a>
</div>

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
@endsection
