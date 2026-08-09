<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Catálogo de Productos - TRIGESTION</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        outfit: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        trigestion: {
                            50: '#f0f9ff',100: '#e0f2fe',200: '#bae6fd',300: '#7dd3fc',
                            400: '#38bdf8',500: '#009ee3',600: '#0081c2',700: '#00669e',
                            800: '#005282',900: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }
        .bg-blobs { position: fixed; inset: 0; z-index: 0; overflow: hidden; pointer-events: none; background-color: #f8fafc; }
        .blob-1, .blob-2, .blob-3 { position: absolute; filter: blur(80px); border-radius: 50%; opacity: 0.5; animation: float 20s infinite alternate ease-in-out; }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, rgba(255,255,255,0) 70%); }
        .blob-2 { bottom: -20%; right: -10%; width: 60vw; height: 60vw; background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(255,255,255,0) 70%); animation-delay: -5s; }
        .blob-3 { top: 40%; left: 30%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, rgba(255,255,255,0) 70%); animation-delay: -10s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(5%, 5%) scale(1.1); } }

        .glass-card, .glass-panel { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05); }

        /* Cart Overlay & Sidebar */
        .cart-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 40; opacity: 0; visibility: hidden; transition: all 0.4s ease; }
        .cart-overlay.open { opacity: 1; visibility: visible; }
        .cart-sidebar { position: fixed; top: 0; right: -100%; width: 90%; max-width: 450px; height: 100vh; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(20px); z-index: 50; box-shadow: -10px 0 40px rgba(0, 0, 0, 0.1); transition: right 0.4s ease; display: flex; flex-direction: column; }
        .cart-sidebar.open { right: 0; }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-100/90 text-slate-800 min-h-screen">

    <div class="bg-blobs"><div class="blob-1"></div><div class="blob-2"></div><div class="blob-3"></div></div>

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

    <div class="flex min-h-screen relative z-10">

        <!-- SIDEBAR -->
        @include('partials.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 ml-72 flex flex-col min-w-0">

            <!-- TOPBAR -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Catálogo de Productos</h1>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Explora nuestros productos y realiza tus pedidos</p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Cart trigger button -->
                    <button onclick="openCart()"
                       class="relative flex items-center gap-2.5 bg-trigestion-500 hover:bg-trigestion-600 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition-all shadow-md shadow-trigestion-500/20 cursor-pointer">
                        <i class="fas fa-cart-shopping"></i>
                        <span class="hidden sm:inline">Carrito</span>
                        <span id="carritoCount"
                              class="w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center shadow-sm hidden">
                            0
                        </span>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" 
                                class="flex items-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-2xl transition-all shadow-sm cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-trigestion-400 text-slate-950 flex items-center justify-center font-black text-base shadow-md shrink-0">
                                {{ strtoupper(substr(Auth::user()->nombres ?? Auth::user()->name ?? 'C', 0, 1)) }}
                            </div>
                            <p class="text-xs font-extrabold text-slate-900 hidden sm:block truncate max-w-[160px]">
                                {{ Auth::user()->nombres ?? Auth::user()->name }}
                            </p>
                            <svg class="w-4 h-4 text-slate-400 shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 p-2 z-50" style="display:none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-red-600 bg-red-50/60 hover:bg-red-100/80 border border-red-200/50 transition-all">
                                    <i class="fas fa-right-from-bracket"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- BODY -->
            <main class="p-8 space-y-8 flex-1 font-outfit">

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

                <!-- CATEGORY FILTERS & SEARCH -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 glass-panel p-4 rounded-2xl">
                    <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 custom-scrollbar">
                        <a href="{{ route('productos.catalogo') }}" 
                           class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ !$filtroCat ? 'bg-trigestion-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Todas las Categorías
                        </a>
                        @foreach ($categorias as $cat)
                        <a href="{{ route('productos.catalogo', ['cat' => $cat->id_categoria]) }}" 
                           class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all shrink-0 {{ $filtroCat == $cat->id_categoria ? 'bg-trigestion-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->nombre }}
                        </a>
                        @endforeach
                    </div>

                    <div class="relative min-w-[240px]">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                        <input type="text" id="searchCatalogo" placeholder="Buscar producto..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-trigestion-500/10 focus:border-trigestion-400 outline-none transition-all text-xs font-semibold shadow-sm">
                    </div>
                </div>

                <!-- PRODUCTS GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="catalogoGrid">
                    @foreach ($productos as $p)
                        @php $imgUrl = $p->img ? asset($p->img) : null; @endphp
                        <div class="catalogo-card glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col"
                             data-nombre="{{ strtolower($p->nombre) }}">

                            <div class="h-52 relative overflow-hidden flex items-center justify-center p-6 bg-gradient-to-br from-sky-50 to-blue-50">
                                @if ($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $p->nombre }}" class="max-w-[72%] max-h-[72%] object-contain relative z-10 group-hover:scale-110 transition-transform duration-500 drop-shadow-md">
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
                            </div>

                            <div class="p-5 flex-1 flex flex-col bg-white">
                                <h3 class="text-base font-black text-slate-800 leading-tight mb-3 line-clamp-2 group-hover:text-trigestion-600 transition-colors flex-1">
                                    {{ $p->nombre }}
                                </h3>

                                <div class="mt-auto">
                                    <button
                                        type="button"
                                        onclick="agregarAlCarrito({{ $p->id_producto }}, '{{ addslashes($p->nombre) }}')"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-trigestion-500 hover:bg-trigestion-600 text-white font-extrabold text-xs transition-all shadow-md active:scale-95 cursor-pointer">
                                        <i class="fas fa-cart-plus text-sm"></i>
                                        <span>Agregar al Carrito</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($productos->isEmpty())
                    <div class="col-span-full py-16 text-center text-slate-400 font-medium">
                        No hay productos disponibles en esta categoría.
                    </div>
                    @endif
                </div>

            </main>
        </div>
    </div>

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
            document.getElementById('cartTotalDisplay').textContent = '$' + (total * 1.19).toFixed(2);

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
                            <p class="text-xs text-trigestion-600 font-extrabold mt-0.5">$${parseFloat(item.precio_unitario).toFixed(2)} c/u</p>
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
</body>
</html>
