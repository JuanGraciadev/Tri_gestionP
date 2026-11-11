/**
 * Script de página: inventario_productos.js
 */
// Real-time search — filters table rows by producto, lote, bodega
        document.getElementById('searchInventario').addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.inventario-row');
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

        // Consulta de alertas de bajo stock
        fetch('/api/productos/alertas')
            .then(res => res.json())
            .then(data => {
                if (data.ok && data.hayAlertas) {
                    const container = document.getElementById('contenedorAlertasStock');
                    const lista = document.getElementById('listaAlertasStock');
                    if (!container || !lista) return;

                    let html = '';
                    (data.agotados || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-100 text-rose-800 border border-rose-200 text-xs font-black">
                            <i class="fas fa-times-circle text-rose-500"></i> ${p.nombre} (Agotado - 0 un.)
                        </span>`;
                    });
                    (data.stockBajo || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-100 text-amber-800 border border-amber-200 text-xs font-black">
                            <i class="fas fa-exclamation-circle text-amber-500"></i> ${p.nombre} (Stock: ${p.disponible} / Mín: ${p.stock_minimo})
                        </span>`;
                    });
                    lista.innerHTML = html;
                    container.classList.remove('hidden');
                }
            })
            .catch(() => {});
