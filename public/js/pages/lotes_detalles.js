/**
 * Script de página: lotes_detalles.js
 */
function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function openEditModal(detalle) {
            const url = (window.APP.rutas.updateDetalle || "").replace(":id", detalle.id_detalles);
            document.getElementById('formEditarDetalle').action = url;
            document.getElementById('edit_unidades').value    = detalle.unidades;
            document.getElementById('edit_tipo_envase').value = detalle.tipo_envase;
            document.getElementById('edit_capacidad').value   = detalle.capacidad;
            document.getElementById('edit_proveedor').value   = detalle.proveedor;
            openModal('modalEditarDetalle');
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            ['modalCrearDetalle','modalEditarDetalle'].forEach(id => {
                if (e.target.id === id) closeModal(id);
            });
        });
