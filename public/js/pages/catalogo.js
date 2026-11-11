/**
 * Script de página: catalogo.js
 */
const CSRF = window.APP.csrf || document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            fetch(window.APP.rutas.carritoObtener)
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
            fetch(window.APP.rutas.carritoAgregar, {
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
            fetch(window.APP.rutas.carritoActualizar, {
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
            fetch(window.APP.rutas.carritoEliminar, {
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
            fetch(window.APP.rutas.carritoFinalizar, {
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
                        window.location.href = window.APP.rutas.misCompras;
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.msg, confirmButtonColor: '#009ee3' });
                }
            });
        }

        // Init badge
        document.addEventListener('DOMContentLoaded', () => {
            fetch(window.APP.rutas.carritoObtener)
                .then(r => r.json())
                .then(data => {
                    if (data.ok && data.carrito) {
                        const totalCount = data.carrito.reduce((acc, i) => acc + i.cantidad, 0);
                        actualizarBadge(totalCount);
                    }
                });
        });
