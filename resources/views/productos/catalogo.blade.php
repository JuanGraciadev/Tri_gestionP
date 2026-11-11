@extends('layouts.panel')

@section('title', 'Catálogo de Productos')
@section('subtitle', 'Explora nuestros productos y realiza tus pedidos')

@section('topbar-actions')
<button onclick="openCart()"
   class="relative flex items-center gap-2.5 bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition-all shadow-md shadow-sky-500/20 cursor-pointer">
    <i class="fas fa-cart-shopping"></i>
    <span class="hidden sm:inline">Carrito</span>
    <span id="cartBadge" class="hidden absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">0</span>
</button>
@endsection

@section('content')
<!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>

    <!-- Cart Sidebar Drawer -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-6 border-b border-slate-200/60 flex items-center justify-between bg-white/50">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Mi Pedido</h3>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revisión de Carrito</p>
            </div>
            <button onclick="closeCart()" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all flex items-center justify-center cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" id="cartItemsContainer">
            <!-- Dynamic Cart Items injected here -->
        </div>

        <div class="p-6 border-t border-slate-200/60 bg-white/80 backdrop-blur-md">
            <div class="flex justify-between items-end mb-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <span class="text-slate-500 font-bold uppercase tracking-wider text-xs">Total Estimado</span>
                <span class="text-2xl font-black text-trigestion-600" id="cartTotalDisplay">$0.00</span>
            </div>

            <div class="relative mb-4">
                <textarea id="notasCompra" placeholder="Instrucciones especiales para entrega..." rows="2"
                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-700 outline-none focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-500 transition-all resize-none shadow-sm"></textarea>
            </div>

            <button onclick="finalizarCompra()" id="btnCheckout"
                class="w-full py-4 bg-gradient-to-r from-trigestion-500 to-sky-600 text-white font-extrabold rounded-2xl shadow-lg shadow-trigestion-500/30 hover:shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-2 text-base cursor-pointer">
                <i class="fas fa-credit-card"></i> <span>Finalizar Compra</span>
            </button>
        </div>
    </div>


<!-- HERO BANNER -->
                <div class="bg-gradient-to-r from-trigestion-500 via-trigestion-600 to-sky-600 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-trigestion-500/20">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur-md border border-white/30 px-3 py-1 rounded-full text-xs font-bold text-white mb-3">
                                💧 Pureza e Hidratación Garantizada
                            </span>
                            <h2 class="text-3xl font-black tracking-tight leading-tight">
                                Catálogo Oficial Trigestion
                            </h2>
                            <p class="text-sky-100 text-sm font-medium mt-1 max-w-xl leading-relaxed">
                                Selecciona entre nuestras presentaciones de agua purificada y realiza tus pedidos en segundos.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CATEGORY FILTERS & SEARCH (HU-003 / RF 7.13) -->
                <form method="GET" action="{{ route('productos.catalogo') }}" class="glass-panel p-5 rounded-3xl space-y-4">
                    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                        {{-- Categorías rápidas --}}
                        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 custom-scrollbar">
                            <a href="{{ route('productos.catalogo', array_filter(['buscar' => $buscar, 'precio_min' => $precioMin, 'precio_max' => $precioMax])) }}" 
                               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ !$filtroCat ? 'bg-trigestion-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Todas
                            </a>
                            @foreach ($categorias as $cat)
                            <a href="{{ route('productos.catalogo', array_filter(['cat' => $cat->id_categoria, 'buscar' => $buscar, 'precio_min' => $precioMin, 'precio_max' => $precioMax])) }}" 
                               class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ $filtroCat == $cat->id_categoria ? 'bg-trigestion-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $cat->nombre }}
                            </a>
                            @endforeach
                        </div>

                        {{-- Input Búsqueda --}}
                        <div class="relative min-w-[240px]">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar por nombre o código..."
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-400 outline-none transition-all text-xs font-semibold shadow-sm">
                        </div>
                    </div>

                    {{-- Filtros avanzados: Categoría + Rango de Precio --}}
                    <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100 text-xs font-bold text-slate-600">
                        @if ($filtroCat)
                            <input type="hidden" name="cat" value="{{ $filtroCat }}">
                        @endif

                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 uppercase tracking-wider text-[10px]">Precio Mín:</span>
                            <div class="relative w-24">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">$</span>
                                <input type="number" step="0.01" min="0" name="precio_min" value="{{ $precioMin ?? '' }}" placeholder="0.00"
                                    class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-trigestion-500 font-semibold">
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 uppercase tracking-wider text-[10px]">Precio Máx:</span>
                            <div class="relative w-24">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">$</span>
                                <input type="number" step="0.01" min="0" name="precio_max" value="{{ $precioMax ?? '' }}" placeholder="99.00"
                                    class="w-full pl-6 pr-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs outline-none focus:border-trigestion-500 font-semibold">
                            </div>
                        </div>

                        <div class="flex items-center gap-2 ml-auto">
                            <button type="submit" class="px-4 py-2 bg-trigestion-500 hover:bg-trigestion-600 text-white rounded-xl text-xs font-black shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            @if ($buscar || $filtroCat || $precioMin || $precioMax)
                            <a href="{{ route('productos.catalogo') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                                <i class="fas fa-rotate-left mr-1"></i> Limpiar
                            </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- PRODUCTS GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="catalogoGrid">
                    @foreach ($productos as $p)
                        @php
                            $imgUrl   = $p->img ? asset($p->img) : null;
                            $stock    = $p->stock ?? 0;
                            $sinStock = $stock <= 0;
                        @endphp
                        <div class="catalogo-card glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col {{ $sinStock ? 'opacity-60' : '' }}"
                             data-nombre="{{ strtolower($p->nombre) }}">

                            <div class="h-52 relative overflow-hidden flex items-center justify-center p-6 bg-gradient-to-br from-sky-50 to-blue-50">
                                @if ($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $p->nombre }}" class="max-w-[72%] max-h-[72%] object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-md {{ $sinStock ? 'grayscale' : '' }}">
                                @else
                                    <div class="w-20 h-20 rounded-2xl bg-white/80 flex items-center justify-center relative z-10 group-hover:scale-110 transition-transform duration-500 shadow-md">
                                        <i class="fas fa-box text-3xl text-sky-400"></i>
                                    </div>
                                @endif

                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1 z-20">
                                    <span class="px-3 py-1 bg-white/95 text-trigestion-700 text-sm font-black rounded-full shadow-sm border border-sky-100">
                                        ${{ number_format($p->precio, 2) }}
                                    </span>
                                </div>

                                @if ($sinStock)
                                <div class="absolute inset-0 flex items-center justify-center z-20">
                                    <span class="px-4 py-2 bg-slate-800/80 text-white text-xs font-black rounded-full uppercase tracking-widest backdrop-blur-sm">
                                        Sin Stock
                                    </span>
                                </div>
                                @endif
                            </div>

                            <div class="p-5 flex-1 flex flex-col bg-white">
                                <h3 class="text-base font-black text-slate-800 leading-tight mb-1 line-clamp-2 group-hover:text-trigestion-600 transition-colors flex-1">
                                    {{ $p->nombre }}
                                </h3>

                                <p class="text-[11px] font-bold mb-3 {{ $sinStock ? 'text-rose-400' : 'text-emerald-500' }}">
                                    @if ($sinStock)
                                        <i class="fas fa-circle-xmark mr-1"></i> Agotado
                                    @else
                                        <i class="fas fa-circle-check mr-1"></i> {{ $stock }} en stock
                                    @endif
                                </p>

                                <div class="mt-auto">
                                    @if ($sinStock)
                                        <button type="button" disabled
                                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-200 text-slate-400 font-extrabold text-xs cursor-not-allowed">
                                            <i class="fas fa-ban text-sm"></i>
                                            <span>Sin Disponibilidad</span>
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            onclick="agregarAlCarrito({{ $p->id_producto }}, '{{ addslashes($p->nombre) }}')"
                                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold text-xs transition-all shadow-md active:scale-95 cursor-pointer">
                                            <i class="fas fa-cart-plus text-sm"></i>
                                            <span>Agregar al Carrito</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($productos->isEmpty())
                    <div class="col-span-full py-20 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-700 mb-1">
                            {{ $mensajeVacio ?? 'No hay productos disponibles.' }}
                        </h4>
                        <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">
                            Intenta ajustar los términos de búsqueda, la categoría o el rango de precio para encontrar lo que necesitas.
                        </p>
                        <a href="{{ route('productos.catalogo') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 rounded-xl bg-trigestion-500 text-white font-bold text-xs shadow-md hover:bg-trigestion-600 transition-all">
                            <i class="fas fa-rotate-left"></i> Restablecer Catálogo
                        </a>
                    </div>
                    @endif
                </div>
@endsection

@push('scripts')
<script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Search
        document.getElementById('searchCatalogo')?.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.catalogo-card').forEach(card => {
                card.style.display = card.dataset.nombre.includes(term) ? '' : 'none';
            });
        });

        // Cart Drawer Toggles
        function openCart() {
            document.getElementById('cartOverlay').classList.add('open');
            document.getElementById('cartSidebar').classList.add('open');
            cargarCarrito();
        }
        function closeCart() {
            document.getElementById('cartOverlay').classList.remove('open');
            document.getElementById('cartSidebar').classList.remove('open');
        }

        function actualizarBadge(count) {
            const badge = document.getElementById('carritoCount');
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        // Fetch Cart
        function cargarCarrito() {
            fetch('{{ route("carrito.obtener") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        renderCart(data.carrito, data.total);
                    }
                });
        }

        function renderCart(items, total) {
            const container = document.getElementById('cartItemsContainer');
            document.getElementById('cartTotalDisplay').textContent = '$' + parseFloat(total).toFixed(2);

            let totalItems = 0;
            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-16 text-slate-400">
                        <i class="fas fa-shopping-basket text-4xl opacity-30 mb-3 block"></i>
                        <p class="font-bold text-slate-600">Tu carrito está vacío</p>
                    </div>`;
                actualizarBadge(0);
                return;
            }

            let html = '';
            items.forEach(item => {
                totalItems += item.cantidad;
                html += `
                    <div class="flex items-center justify-between gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 text-sm truncate">${item.nombre}</h4>
                            <p class="text-xs text-trigestion-600 font-extrabold mt-0.5">$${parseFloat(item.precio ?? item.precio_unitario).toFixed(2)} c/u</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="actualizarCantidad(${item.id_producto}, ${item.cantidad - 1})" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs cursor-pointer">-</button>
                            <span class="font-black text-slate-800 text-sm px-1">${item.cantidad}</span>
                            <button onclick="actualizarCantidad(${item.id_producto}, ${item.cantidad + 1})" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs cursor-pointer">+</button>
                            <button onclick="eliminarItem(${item.id_producto})" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 font-bold text-xs cursor-pointer ml-1"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>`;
            });
            container.innerHTML = html;
            actualizarBadge(totalItems);
        }

        // Add
        function agregarAlCarrito(idProducto, nombre) {
            fetch('{{ route("carrito.agregar") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: new URLSearchParams({ id_producto: idProducto, cantidad: 1 })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    actualizarBadge(data.total_items);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Añadido al carrito!',
                        text: nombre + ' se agregó correctamente.',
                        confirmButtonColor: '#009ee3',
                        confirmButtonText: 'Ver Carrito',
                        showCancelButton: true,
                        cancelButtonText: 'Seguir Comprando',
                        customClass: { popup: 'rounded-[2rem] font-outfit' }
                    }).then(r => { if (r.isConfirmed) openCart(); });
                } else {
                    Swal.fire({ icon: 'warning', title: 'Atención', text: data.msg, confirmButtonColor: '#009ee3' });
                }
            });
        }

        // Update
        function actualizarCantidad(idProducto, cantidad) {
            fetch('{{ route("carrito.actualizar") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: new URLSearchParams({ id_producto: idProducto, cantidad: cantidad })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) renderCart(data.carrito, data.total);
            });
        }

        // Remove
        function eliminarItem(idProducto) {
            fetch('{{ route("carrito.eliminar") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: new URLSearchParams({ id_producto: idProducto })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) renderCart(data.carrito, data.total);
            });
        }

        // Finalize Checkout
        function finalizarCompra() {
            const notas = document.getElementById('notasCompra').value;
            fetch('{{ route("carrito.finalizar") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: new URLSearchParams({ notas: notas, metodo_pago: 'Efectivo' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    closeCart();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Pedido Realizado!',
                        text: 'Tu pedido #' + data.id_venta + ' ha sido registrado exitosamente.',
                        confirmButtonColor: '#009ee3',
                        confirmButtonText: 'Ver mis pedidos',
                        customClass: { popup: 'rounded-[2rem] font-outfit' }
                    }).then(() => {
                        window.location.href = '{{ route("cliente.misCompras") }}';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.msg, confirmButtonColor: '#009ee3' });
                }
            });
        }

        // Init badge
        document.addEventListener('DOMContentLoaded', () => {
            fetch('{{ route("carrito.obtener") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.ok && data.carrito) {
                        const totalCount = data.carrito.reduce((acc, i) => acc + i.cantidad, 0);
                        actualizarBadge(totalCount);
                    }
                });
        });
    </script>
@endpush
