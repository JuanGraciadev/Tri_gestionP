/**
 * Script de página: checkout.js
 */
const CSRF = window.APP.csrf || document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
        fetch(window.APP.rutas.carritoActualizar, {
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
        fetch(window.APP.rutas.carritoEliminar, {
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
                fetch(window.APP.rutas.carritoVaciar, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                })
                .then(r => r.json())
                .then(() => location.reload());
            }
        });
    }
