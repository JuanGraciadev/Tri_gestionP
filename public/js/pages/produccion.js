/**
 * produccion.js — Control de Producción
 */

// ── Modales ──────────────────────────────────────────────────────────────────
function openModal(id)  { const m = document.getElementById(id); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(id) { const m = document.getElementById(id); m.classList.add('hidden'); m.classList.remove('flex'); }

window.addEventListener('click', function(e) {
    if (e.target.id === 'modalIniciar') closeModal('modalIniciar');
});

// ── Confirmar Finalizar ───────────────────────────────────────────────────────
function confirmarFinalizar(id, lote) {
    Swal.fire({
        title: '¿Finalizar producción?',
        html:  `<p class="text-slate-600">El lote <strong>${lote}</strong> pasará a <strong>Finalizada</strong>.<br>Se registrará el ingreso al inventario de productos terminados.</p>`,
        icon:  'question',
        showCancelButton:   true,
        confirmButtonColor: '#10b981',
        cancelButtonColor:  '#94a3b8',
        confirmButtonText:  '<i class="fas fa-flag-checkered mr-2"></i>Sí, finalizar',
        cancelButtonText:   'Cancelar',
        customClass: { popup: 'rounded-[2rem] font-outfit' }
    }).then(r => { if (r.isConfirmed) document.getElementById('finalizarForm' + id).submit(); });
}

// ── Confirmar Cancelar ────────────────────────────────────────────────────────
function confirmarCancelar(id, lote) {
    Swal.fire({
        title: '¿Cancelar producción?',
        html:  `<p class="text-slate-600">El lote <strong>${lote}</strong> pasará a <strong>Cancelada</strong>.<br>La materia prima e insumos descontados serán devueltos al inventario.</p>`,
        icon:  'warning',
        showCancelButton:   true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor:  '#94a3b8',
        confirmButtonText:  '<i class="fas fa-times mr-2"></i>Sí, cancelar',
        cancelButtonText:   'Volver',
        customClass: { popup: 'rounded-[2rem] font-outfit' }
    }).then(r => { if (r.isConfirmed) document.getElementById('cancelarForm' + id).submit(); });
}

// ── Receta dinámica de insumos ────────────────────────────────────────────────
let filaIndex = 0;

function agregarFilaInsumo() {
    const insumos = window.INSUMOS || [];
    if (!insumos.length) {
        Swal.fire({ icon: 'warning', title: 'Sin insumos', text: 'No hay insumos registrados. Ve al módulo de Insumos y registra etiquetas, tapas o bolsas primero.', confirmButtonColor: '#0d9488' });
        return;
    }

    const container = document.getElementById('recetaContainer');
    const idx = filaIndex++;

    // Opciones del select
    const opts = insumos.map(i => {
        const badge = i.stock <= 0 ? ' ⚠ AGOTADO' : (i.stock <= i.stock_minimo ? ' ⚠ bajo' : '');
        return `<option value="${i.id}">${i.nombre} (${i.stock} ${i.unidad})${badge}</option>`;
    }).join('');

    const fila = document.createElement('div');
    fila.className = 'flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-3';
    fila.id = `receta-fila-${idx}`;
    fila.innerHTML = `
        <div class="flex-1">
            <select name="receta_insumo[]"
                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-teal-400 cursor-pointer">
                <option value="">Seleccione insumo…</option>
                ${opts}
            </select>
        </div>
        <div class="w-28">
            <input type="number" name="receta_cantidad[]"
                   min="0.001" step="0.001" placeholder="Cant/ud"
                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-teal-400 text-center"
                   title="Cantidad de este insumo por unidad producida">
        </div>
        <button type="button" onclick="eliminarFilaInsumo(${idx})"
                class="w-8 h-8 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shrink-0 cursor-pointer">
            <i class="fas fa-times text-xs"></i>
        </button>`;

    container.appendChild(fila);
}

function eliminarFilaInsumo(idx) {
    document.getElementById(`receta-fila-${idx}`)?.remove();
}
