/**
 * Script de página: lotes.js
 */
function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function openEditModal(lote) {
            const url = (window.APP.rutas.updateLote || "").replace(":id", lote.id_lote);
            document.getElementById('formEditarLote').action = url;
            document.getElementById('edit_codigo_lote').value = lote.codigo_lote;
            openModal('modalEditarLote');
        }

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text:  'Se eliminará este lote junto con TODOS sus detalles y registros de inventario. ¡Esta acción es irreversible!',
                icon:  'warning',
                showCancelButton:    true,
                confirmButtonColor:  '#ef4444',
                cancelButtonColor:   '#94a3b8',
                confirmButtonText:   '<i class="fas fa-trash-alt mr-2"></i>Sí, eliminar lote',
                cancelButtonText:    'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            ['modalCrearLote','modalEditarLote'].forEach(id => {
                if (e.target.id === id) closeModal(id);
            });
        });
