/**
 * Script de página: produccion.js
 */
function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

        function confirmarFinalizar(id, lote) {
            Swal.fire({
                title: '¿Finalizar producción?',
                html:  `<p class="text-slate-600">El lote <strong>${lote}</strong> pasará a estado <strong>Finalizada</strong>.<br>Se registrará el ingreso al inventario de productos terminados.</p>`,
                icon:  'question',
                showCancelButton:    true,
                confirmButtonColor:  '#10b981',
                cancelButtonColor:   '#94a3b8',
                confirmButtonText:   '<i class="fas fa-flag-checkered mr-2"></i>Sí, finalizar',
                cancelButtonText:    'Cancelar',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('finalizarForm' + id).submit();
                }
            });
        }

        function confirmarCancelar(id, lote) {
            Swal.fire({
                title: '¿Cancelar producción?',
                html:  `<p class="text-slate-600">El lote <strong>${lote}</strong> pasará a estado <strong>Cancelada</strong>.<br>La materia prima descontada al inicio será devuelta al inventario.</p>`,
                icon:  'warning',
                showCancelButton:    true,
                confirmButtonColor:  '#ef4444',
                cancelButtonColor:   '#94a3b8',
                confirmButtonText:   '<i class="fas fa-times mr-2"></i>Sí, cancelar producción',
                cancelButtonText:    'Volver',
                customClass: { popup: 'rounded-[2rem] font-outfit' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelarForm' + id).submit();
                }
            });
        }

        // Close modal on backdrop click
        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalIniciar') closeModal('modalIniciar');
        });
