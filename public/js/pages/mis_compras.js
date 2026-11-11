/**
 * Script de página: mis_compras.js
 */
function filtrarPorFechas() {
            const desdeVal = document.getElementById('fechaDesde').value;
            const hastaVal = document.getElementById('fechaHasta').value;
            const rows = document.querySelectorAll('.pedido-item-card');

            rows.forEach(row => {
                const rowFechaStr = row.getAttribute('data-fecha');
                let show = true;
                if (desdeVal && rowFechaStr < desdeVal) show = false;
                if (hastaVal && rowFechaStr > hastaVal) show = false;
                row.style.display = show ? 'block' : 'none';
            });
        }

        function limpiarFiltroFechas() {
            document.getElementById('fechaDesde').value = '';
            document.getElementById('fechaHasta').value = '';
            filtrarPorFechas();
        }
