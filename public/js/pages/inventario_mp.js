/**
 * Script de página: inventario_mp.js
 */
// Búsqueda en tiempo real — filtra filas por lote, tipo_envase, proveedor, bodega
        document.getElementById('searchMP').addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.mp-row');
            let visible = 0;

            rows.forEach(function (row) {
                const match = row.dataset.search.includes(term);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const noResults = document.getElementById('noResultsRow');
            if (noResults) {
                noResults.classList.toggle('hidden', visible > 0);
            }
        });
