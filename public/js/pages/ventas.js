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
            const url = `/ventas/factura/${idVenta}?autoprint=1`;
            const win = window.open(url, 'TicketPOS_' + idVenta, 'width=420,height=750,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes');
            if (win) {
                win.focus();
            } else {
                window.location.href = url;
            }
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
