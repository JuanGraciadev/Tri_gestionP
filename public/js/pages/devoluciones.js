/**
 * Script de página: devoluciones.js
 */
const balancesJS = @json($balances);

        document.getElementById('searchBalances')?.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.balance-row').forEach(row => {
                row.style.display = row.dataset.search.includes(query) ? '' : 'none';
            });
        });

        function openModalReturn() {
            document.getElementById('modalReturn').classList.remove('hidden');
            document.getElementById('modalReturn').classList.add('flex');
        }
        function closeModalReturn() {
            document.getElementById('modalReturn').classList.add('hidden');
            document.getElementById('modalReturn').classList.remove('flex');
        }

        function onClientSelect() {
            const id_usuario = document.getElementById('modal_id_usuario').value;
            const prodSelect = document.getElementById('modal_id_producto');
            prodSelect.innerHTML = '<option value="">Seleccione un producto...</option>';
            document.getElementById('modal_cantidad').value = '';
            document.getElementById('modal_cantidad').removeAttribute('max');
            document.getElementById('max_qty_info').textContent = '';

            if (!id_usuario) return;

            const items = balancesJS.filter(b => b.id_usuario == id_usuario && b.en_consumo > 0);
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id_producto;
                opt.textContent = `${item.producto_nombre} (En consumo: ${item.en_consumo})`;
                prodSelect.appendChild(opt);
            });
        }

        function onProductSelect() {
            const id_usuario = document.getElementById('modal_id_usuario').value;
            const id_producto = document.getElementById('modal_id_producto').value;
            const inputQty = document.getElementById('modal_cantidad');
            const infoText = document.getElementById('max_qty_info');

            if (!id_usuario || !id_producto) {
                inputQty.removeAttribute('max');
                infoText.textContent = '';
                return;
            }

            const matched = balancesJS.find(b => b.id_usuario == id_usuario && b.id_producto == id_producto);
            if (matched) {
                inputQty.setAttribute('max', matched.en_consumo);
                infoText.textContent = `Máximo permitido: ${matched.en_consumo} unidades`;
            }
        }

        function quickReturn(b) {
            openModalReturn();
            document.getElementById('modal_id_usuario').value = b.id_usuario;
            onClientSelect();
            document.getElementById('modal_id_producto').value = b.id_producto;
            onProductSelect();
            document.getElementById('modal_cantidad').value = b.en_consumo;
        }

        window.addEventListener('click', function(e) {
            if (e.target.id === 'modalReturn') closeModalReturn();
        });
