@extends('layouts.panel')

@section('title', 'Finalizar Compra')
@section('subtitle', 'Revisa tu pedido y confirma la compra')

@section('content')
@if(session('alert'))
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                    Swal.fire({icon:'{{ session("alert.icon") }}',title:'{!! addslashes(session("alert.title")) !!}',text:'{!! addslashes(session("alert.text")) !!}',confirmButtonColor:'#009ee3',customClass:{popup:'rounded-[2rem] font-outfit'}});
                });
            </script>
            @endif

            @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-xs font-semibold space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            @if(empty($carrito))
            {{-- Carrito vacío --}}
            <div class="glass-card rounded-[2.5rem] border border-white p-16 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fas fa-cart-shopping text-4xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-700 mb-2">Tu carrito está vacío</h2>
                <p class="text-slate-400 font-medium mb-6">Agrega productos desde el catálogo para continuar.</p>
                <a href="{{ route('productos.catalogo') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-trigestion-500 to-sky-500 text-white rounded-2xl font-bold shadow-lg hover:-translate-y-1 transition-all">
                    <i class="fas fa-store"></i> Ver Catálogo
                </a>
            </div>
            @else
            {{-- Carrito con productos --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Tabla de productos --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="glass-card rounded-[2.5rem] border border-white overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h2 class="text-xl font-black text-slate-800">
                                <i class="fas fa-cart-shopping text-trigestion-500 mr-2"></i> Tu Pedido
                            </h2>
                            <button type="button" onclick="vaciarCarrito()"
                                    class="text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors cursor-pointer">
                                <i class="fas fa-trash mr-1"></i> Vaciar
                            </button>
                        </div>

                        <div id="carritoItems" class="divide-y divide-slate-100">
                            @foreach($carrito as $item)
                            <div class="flex items-center gap-4 px-6 py-5 hover:bg-slate-50/50 transition-colors" id="item-{{ $item['id_producto'] }}">
                                {{-- Imagen --}}
                                <div class="w-14 h-14 rounded-2xl bg-sky-50 flex items-center justify-center border border-sky-100 shrink-0 overflow-hidden">
                                    @if($item['img'])
                                        <img src="{{ asset($item['img']) }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-contain p-1">
                                    @else
                                        <i class="fas fa-box text-sky-300 text-xl"></i>
                                    @endif
                                </div>
                                {{-- Nombre + precio --}}
                                <div class="flex-1 min-w-0">
                                    <div class="font-black text-slate-800 text-sm truncate">{{ $item['nombre'] }}</div>
                                    <div class="text-xs font-bold text-slate-400 mt-0.5">${{ number_format($item['precio'], 2) }} c/u</div>
                                </div>
                                {{-- Cantidad --}}
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            onclick="cambiarCantidad({{ $item['id_producto'] }}, {{ $item['cantidad'] - 1 }})"
                                            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-black flex items-center justify-center transition-colors cursor-pointer text-sm">−</button>
                                    <span class="w-8 text-center font-black text-slate-800 text-sm" id="qty-{{ $item['id_producto'] }}">{{ $item['cantidad'] }}</span>
                                    <button type="button"
                                            onclick="cambiarCantidad({{ $item['id_producto'] }}, {{ $item['cantidad'] + 1 }})"
                                            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-black flex items-center justify-center transition-colors cursor-pointer text-sm">+</button>
                                </div>
                                {{-- Subtotal --}}
                                <div class="text-right shrink-0 min-w-[70px]">
                                    <span class="font-black text-slate-800 text-sm" id="sub-{{ $item['id_producto'] }}">${{ number_format($item['subtotal'], 2) }}</span>
                                </div>
                                {{-- Eliminar --}}
                                <button type="button"
                                        onclick="eliminarDelCarrito({{ $item['id_producto'] }})"
                                        class="w-8 h-8 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shrink-0 cursor-pointer">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('productos.catalogo') }}"
                               class="text-xs font-bold text-trigestion-600 hover:text-trigestion-700 transition-colors">
                                <i class="fas fa-arrow-left mr-1"></i> Seguir comprando
                            </a>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total</span>
                                <div class="text-2xl font-black text-slate-800" id="totalDisplay">${{ number_format($total, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Formulario de pago --}}
                <div class="space-y-4">
                    <div class="glass-card rounded-[2.5rem] border border-white p-6">
                        <h2 class="text-xl font-black text-slate-800 mb-5">
                            <i class="fas fa-credit-card text-trigestion-500 mr-2"></i> Pago
                        </h2>
                        <form action="{{ route('carrito.finalizar') }}" method="POST" id="formCheckout" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Método de Pago</label>
                                <div class="relative">
                                    <i class="fas fa-wallet absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10 text-xs"></i>
                                    <select name="metodo_pago" required
                                            class="w-full pl-11 pr-9 py-3.5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-400 outline-none transition-all font-medium text-slate-700 appearance-none cursor-pointer">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Notas del Pedido <span class="text-slate-300 font-normal normal-case">(Opcional)</span></label>
                                <textarea name="notas" rows="3" placeholder="Instrucciones de entrega, dirección especial..."
                                          class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-400 outline-none transition-all text-slate-700 resize-none text-sm font-medium"></textarea>
                            </div>

                            {{-- Resumen --}}
                            <div class="bg-trigestion-50 border border-trigestion-100 rounded-2xl p-4 space-y-2">
                                <div class="flex justify-between text-sm font-bold text-slate-600">
                                    <span>Subtotal</span>
                                    <span id="resumenSubtotal">${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="border-t border-trigestion-200 pt-2 flex justify-between font-black text-slate-800 text-lg">
                                    <span>Total</span>
                                    <span id="resumenTotal">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-trigestion-500 to-sky-500 text-white rounded-2xl font-black text-base shadow-lg shadow-trigestion-500/30 hover:-translate-y-1 transition-all active:scale-95">
                                <i class="fas fa-check-circle"></i> Confirmar Pedido
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @endif
@endsection

@push('scripts')
<script>
    window.APP = window.APP || {};
    window.APP.rutas = window.APP.rutas || {};
    window.APP.rutas.carritoActualizar = '{{ route('carrito.actualizar') }}';
    window.APP.rutas.carritoEliminar = '{{ route('carrito.eliminar') }}';
    window.APP.rutas.carritoVaciar = '{{ route('carrito.vaciar') }}';
</script>
<script src="{{ asset('js/pages/checkout.js') }}"></script>
@endpush
