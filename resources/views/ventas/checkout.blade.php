<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - TRIGESTION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans:['"Plus Jakarta Sans"','sans-serif'], outfit:['"Outfit"','sans-serif'] }, colors: { trigestion: { 50:'#f0f9ff',100:'#e0f2fe',200:'#bae6fd',300:'#7dd3fc',400:'#38bdf8',500:'#009ee3',600:'#0081c2',700:'#00669e',800:'#005282',900:'#082f49' } } } }
        }
    </script>
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
                    <a href="{{ route('ventas.checkout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-extrabold bg-trigestion-500/30 text-white border-l-4 border-trigestion-400 shadow-md">
                        <i class="fas fa-cart-shopping text-trigestion-300 shrink-0"></i><span>Carrito / Checkout</span>
                    </a>
                    <a href="{{ route('ventas.mis-compras') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-sky-100/80 hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fas fa-shopping-bag text-sky-300/70 group-hover:text-sky-300 shrink-0"></i><span>Mis Compras</span>
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 ml-72 flex flex-col min-w-0">
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-20 shadow-sm px-8 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Checkout</h1>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Revisa tu pedido y confirma la compra</p>
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
                        <form action="{{ route('ventas.confirmar') }}" method="POST" id="formCheckout" class="space-y-4">
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
        </main>
    </div>
</div>

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function actualizarTotalesUI(total) {
        const fmt = '$' + parseFloat(total.replace(',','')).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        const el1 = document.getElementById('totalDisplay');
        const el2 = document.getElementById('resumenSubtotal');
        const el3 = document.getElementById('resumenTotal');
        if (el1) el1.textContent = '$' + total;
        if (el2) el2.textContent = '$' + total;
        if (el3) el3.textContent = '$' + total;
    }

    function cambiarCantidad(idProducto, nuevaCantidad) {
        fetch('{{ route("carrito.actualizar") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto, cantidad: nuevaCantidad }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                if (data.eliminado) {
                    const el = document.getElementById('item-' + idProducto);
                    if (el) el.remove();
                } else {
                    const qty = document.getElementById('qty-' + idProducto);
                    const sub = document.getElementById('sub-' + idProducto);
                    if (qty) qty.textContent = nuevaCantidad;
                    if (sub) sub.textContent = '$' + data.subtotal;
                }
                actualizarTotalesUI(data.total);
            } else {
                Swal.fire({ icon: 'warning', title: 'Sin stock', text: data.mensaje, confirmButtonColor: '#009ee3', customClass: { popup: 'rounded-[2rem] font-outfit' } });
            }
        });
    }

    function eliminarDelCarrito(idProducto) {
        fetch('{{ route("carrito.eliminar") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const el = document.getElementById('item-' + idProducto);
                if (el) el.remove();
                actualizarTotalesUI(data.total);
                if (data.carrito_count === 0) location.reload();
            }
        });
    }

    function vaciarCarrito() {
        Swal.fire({
            title: '¿Vaciar carrito?',
            text: 'Se eliminarán todos los productos del carrito.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-[2rem] font-outfit' }
        }).then(result => {
            if (result.isConfirmed) {
                fetch('{{ route("carrito.vaciar") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                })
                .then(r => r.json())
                .then(() => location.reload());
            }
        });
    }
</script>
</body>
</html>
