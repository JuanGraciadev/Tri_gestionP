/**
 * Script de página: ventas.js
 */
const productosDisponibles = @json($productosActivos);

        function openModalEstado(id, estado) {
            document.getElementById('modalIdVenta').value = id;
            document.querySelectorAll('.estado-radio').forEach(r => r.checked = (r.value === estado));
            document.getElementById('modalEstado').classList.remove('hidden');
            document.getElementById('modalEstado').classList.add('flex');
        }
        function closeModal() {
            document.getElementById('modalEstado').classList.add('hidden');
            document.getElementById('modalEstado').classList.remove('flex');
        }

        function openModalPago(id, estadoPago) {
            document.getElementById('modalPagoIdVenta').value = id;
            document.querySelectorAll('.pago-radio').forEach(r => r.checked = (r.value === estadoPago));
            document.getElementById('modalPago').classList.remove('hidden');
            document.getElementById('modalPago').classList.add('flex');
        }
        function closeModalPago() {
            document.getElementById('modalPago').classList.add('hidden');
            document.getElementById('modalPago').classList.remove('flex');
        }

        function openModalPOS() {
            document.getElementById('modalPOS').classList.remove('hidden');
            document.getElementById('modalPOS').classList.add('flex');
            if (document.querySelectorAll('.pos-row').length === 0) addProductRow();
        }
        function closeModalPOS() {
            document.getElementById('modalPOS').classList.add('hidden');
            document.getElementById('modalPOS').classList.remove('flex');
        }

        function addProductRow() {
            const container = document.getElementById('posProductRows');
            const options = productosDisponibles.map(p =>
                `<option value="${p.id_producto}" data-precio="${p.precio}">${p.nombre} — $${parseFloat(p.precio).toFixed(2)} (Stock: ${p.stock})</option>`
            ).join('');

            const row = document.createElement('div');
            row.className = 'pos-row flex gap-3 items-center bg-slate-50 p-3 rounded-xl border border-slate-200';
            row.innerHTML = `
                <select name="pos_producto[]" required onchange="updatePOSTotal()" class="flex-1 p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold">
                    <option value="">Seleccione producto...</option>
                    ${options}
                </select>
                <input type="number" name="pos_cantidad[]" value="1" min="1" required onchange="updatePOSTotal()" oninput="updatePOSTotal()" class="w-20 p-2 bg-white border border-slate-200 rounded-lg text-center text-xs font-bold">
                <button type="button" onclick="this.closest('.pos-row').remove(); updatePOSTotal();" class="text-red-500 hover:text-red-700 p-2"><i class="fas fa-trash"></i></button>
            `;
            container.appendChild(row);
        }

        function updatePOSTotal() {
            let total = 0;
            document.querySelectorAll('.pos-row').forEach(row => {
                const select = row.querySelector('select');
                const qtyInput = row.querySelector('input[type="number"]');
                const opt = select.options[select.selectedIndex];
                if (opt && opt.value) {
                    const p = parseFloat(opt.dataset.precio || 0);
                    const q = parseInt(qtyInput.value || 0);
                    total += p * q;
                }
            });
            document.getElementById('posTotalDisplay').textContent = 'Total: $' + total.toFixed(2);
        }

        function imprimirFactura(idVenta) {
            fetch(`/ventas/factura/${idVenta}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg || 'No se pudo obtener la factura'
                        });
                        return;
                    }
                    const f = data.factura;
                    let rows = f.items.map(item => `
                        <tr>
                            <td style="padding:6px;border-bottom:1px solid #ddd;">${item.producto_nombre}</td>
                            <td style="padding:6px;border-bottom:1px solid #ddd;text-align:center;">${item.cantidad}</td>
                            <td style="padding:6px;border-bottom:1px solid #ddd;text-align:right;">$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                            <td style="padding:6px;border-bottom:1px solid #ddd;text-align:right;">$${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                        </tr>
                    `).join('');

                    const w = window.open('', '_blank');
                    w.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="utf-8">
                            <title>Factura #${f.id_venta} - TRIGESTION</title>
                            <style>
                                body { font-family: Arial, sans-serif; padding: 24px; color: #1e293b; max-width: 600px; margin: auto; }
                                h2 { text-align: center; margin-bottom: 4px; color: #0f172a; }
                                .subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 16px; }
                                .info { font-size: 13px; line-height: 1.6; margin-bottom: 20px; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; }
                                table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 10px; }
                                th { background: #f1f5f9; padding: 8px; text-align: left; border-bottom: 2px solid #cbd5e1; }
                                .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 20px; color: #059669; }
                                @media print { button { display: none; } }
                            </style>
                        </head>
                        <body>
                            <h2>TRIGESTION - MOOVA</h2>
                            <div class="subtitle">Comprobante de Venta #${f.id_venta}</div>
                            <div class="info">
                                <strong>Fecha:</strong> ${f.fecha}<br>
                                <strong>Cliente:</strong> ${f.cliente}<br>
                                <strong>Método de Pago:</strong> ${f.metodo_pago}<br>
                                ${f.notas ? '<strong>Notas:</strong> ' + f.notas + '<br>' : ''}
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th style="text-align:center;">Cant</th>
                                        <th style="text-align:right;">Precio</th>
                                        <th style="text-align:right;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                            <div class="total">Total: $${parseFloat(f.total).toFixed(2)}</div>
                            <script>window.onload = function() { window.print(); }<\/script>
                        </body>
                        </html>
                    `);
                    w.document.close();
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión al obtener la factura' });
                });
        }

        document.getElementById('searchVentas')?.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.pedido-row').forEach(r => {
                r.style.display = r.innerText.toLowerCase().includes(term) ? '' : 'none';
            });
        });

        function filtrarTabla(estado) {
            document.querySelectorAll('.pedido-row').forEach(r => {
                r.style.display = (estado === 'todos' || r.dataset.estado === estado) ? '' : 'none';
            });
        }
