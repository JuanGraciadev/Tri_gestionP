/**
 * catalogo.js — Catálogo de cliente
 * El carrito vive en /carrito (checkout). Aquí solo se agrega y se redirige.
 */
const CSRF = window.APP?.csrf || document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ── Badge de cantidad en el botón de carrito del topbar ─────────────────────
function actualizarBadge(count) {
    const badge = document.getElementById('cartBadge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// ── Agregar al carrito y redirigir al checkout ───────────────────────────────
function agregarAlCarrito(idProducto, nombre) {
    fetch(window.APP.rutas.carritoAgregar, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        },
        body: new URLSearchParams({ id_producto: idProducto, cantidad: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            actualizarBadge(data.total_items);
            Swal.fire({
                icon: 'success',
                title: '¡Añadido!',
                text: nombre + ' se agregó al carrito.',
                confirmButtonColor: '#009ee3',
                confirmButtonText: 'Ver Carrito',
                showCancelButton: true,
                cancelButtonText: 'Seguir Comprando',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then(r => {
                if (r.isConfirmed) {
                    window.location.href = window.APP.rutas.checkout;
                }
            });
        } else {
            Swal.fire({ icon: 'warning', title: 'Atención', text: data.msg, confirmButtonColor: '#009ee3' });
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar. Intenta de nuevo.', confirmButtonColor: '#009ee3' });
    });
}

// ── Cargar badge al iniciar la página ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (!window.APP?.rutas?.carritoObtener) return;
    fetch(window.APP.rutas.carritoObtener)
        .then(r => r.json())
        .then(data => {
            if (data.ok && data.carrito) {
                const total = data.carrito.reduce((acc, i) => acc + i.cantidad, 0);
                actualizarBadge(total);
            }
        })
        .catch(() => {});
});
