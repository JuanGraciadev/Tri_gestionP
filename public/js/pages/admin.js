/**
 * Script de página: admin.js
 */
function openModal(id) {
            const m = document.getElementById(id);
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        function openEditModal(user) {
            document.getElementById('edit_nombres').value = user.nombres || '';
            document.getElementById('edit_direccion').value = user.direccion || '';
            document.getElementById('edit_telefono').value = user.telefono || '';
            document.getElementById('edit_rol').value = user.id_rol;
            const form = document.getElementById('formEditar');
            form.action = (window.APP.rutas.updateUser || '').replace('__ID__', user.id_usuario);
            openModal('modalEditar');
        }
        function filtrarUsuarios(filtro) {
            document.querySelectorAll('.filter-usr').forEach(b => {
                b.classList.remove('bg-white', 'text-slate-800', 'shadow-sm');
                b.classList.add('text-slate-500', 'hover:text-slate-800');
            });
            event.target.classList.add('bg-white', 'text-slate-800', 'shadow-sm');
            event.target.classList.remove('text-slate-500', 'hover:text-slate-800');

            document.querySelectorAll('.usuario-row').forEach(row => {
                if (filtro === 'todos' || row.dataset.rol === filtro) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        function filtrarSearch() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.usuario-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        }

        // Cargar alertas de stock de productos
        fetch('/api/productos/alertas')
            .then(res => res.json())
            .then(data => {
                if (data.ok && data.hayAlertas) {
                    const container = document.getElementById('contenedorAlertasStockAdmin');
                    const lista = document.getElementById('listaAlertasStockAdmin');
                    if (!container || !lista) return;

                    let html = '';
                    (data.agotados || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-100 text-rose-800 border border-rose-200 text-xs font-black">
                            <i class="fas fa-times-circle text-rose-500"></i> ${p.nombre} (Agotado)
                        </span>`;
                    });
                    (data.stockBajo || []).forEach(p => {
                        html += `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-100 text-amber-800 border border-amber-200 text-xs font-black">
                            <i class="fas fa-exclamation-circle text-amber-500"></i> ${p.nombre} (${p.disponible}/${p.stock_minimo})
                        </span>`;
                    });
                    lista.innerHTML = html;
                    container.classList.remove('hidden');
                }
            })
            .catch(() => {});
