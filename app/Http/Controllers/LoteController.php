<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoteDetalleRequest;
use App\Http\Requests\StoreLoteRequest;
use App\Http\Requests\UpdateLoteDetalleRequest;
use App\Http\Requests\UpdateLoteRequest;
use App\Models\InventarioMateriaPrima;
use App\Models\Lote;
use App\Models\LoteDetalle;
use App\Models\MovimientoInventario;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoteController extends Controller
{
    // ── Lotes ─────────────────────────────────────────────────────────────────

    /**
     * List all batches with their detail counts and the responsible user.
     */
    public function index(): View
    {
        $lotes = Lote::with('usuario')
            ->withCount('detalles')
            ->orderBy('id_lote', 'desc')
            ->paginate(15);

        return view('inventario.lotes', compact('lotes'));
    }

    /**
     * Create a new batch, assign the logged-in user, then immediately redirect
     * to the batch-details page so the operator can enter details in one flow.
     *
     * Original behaviour: crear → INSERT lote → lastInsertId → redirect to lote_detalles.php?id_lote=X
     */
    public function store(StoreLoteRequest $request): RedirectResponse
    {
        $user     = auth()->user();
        $idUsuario = $user->id_usuario ?? $user->id;

        $lote = Lote::create([
            'codigo_lote' => $request->validated('codigo_lote'),
            'id_usuario'  => $idUsuario,
        ]);

        if ($lote) {
            session()->flash('alert', [
                'icon'  => 'success',
                'title' => 'Lote Creado',
                'text'  => 'Lote creado con éxito. Ahora ingresa los detalles del lote.',
            ]);
            // ↓ Identical redirect pattern to the original (immediately to details page)
            return redirect()->route('lotes.detalles', $lote->id_lote);
        }

        session()->flash('alert', [
            'icon'  => 'error',
            'title' => 'Error',
            'text'  => 'No se pudo crear el lote.',
        ]);
        return redirect()->route('lotes.index');
    }

    /**
     * Update only the batch code (id_usuario is never changed on edit).
     */
    public function update(UpdateLoteRequest $request, Lote $lote): RedirectResponse
    {
        $updated = $lote->update([
            'codigo_lote' => $request->validated('codigo_lote'),
        ]);

        if ($updated) {
            session()->flash('alert', [
                'icon'  => 'success',
                'title' => '¡Actualizado!',
                'text'  => 'El código de lote se ha actualizado.',
            ]);
        } else {
            session()->flash('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se pudo actualizar el lote.',
            ]);
        }

        return redirect()->route('lotes.index');
    }

    /**
     * Delete a batch and ALL its related records:
     *   1. Delete inventario_materia_prima rows linked to the batch's detail ids
     *   2. Delete all detalles rows for this batch
     *   3. Delete the lote itself
     *
     * Matches the exact multi-step cascade in the original Lote::eliminar().
     */
    public function destroy(Lote $lote): RedirectResponse
    {
        // Step 1: collect detail IDs to clean up inventory entries
        $detalleIds = $lote->detalles()->pluck('id_detalles')->toArray();

        if (!empty($detalleIds)) {
            // Remove inventory_materia_prima rows linked to those details
            InventarioMateriaPrima::whereIn('id_detalles', $detalleIds)->delete();
        }

        // Step 2: delete all detalles (explicit, even if FK is set to nullOnDelete)
        $lote->detalles()->delete();

        // Step 3: delete the lote
        $deleted = $lote->delete();

        if ($deleted) {
            session()->flash('alert', [
                'icon'  => 'success',
                'title' => 'Lote Eliminado',
                'text'  => 'El lote y todos sus detalles asociados fueron eliminados correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se pudo eliminar el lote.',
            ]);
        }

        return redirect()->route('lotes.index');
    }

    // ── Lote Detalles ─────────────────────────────────────────────────────────

    /**
     * Show the detail page for a specific batch.
     * If id_lote is missing/invalid, redirect to the batch list (original behaviour).
     */
    public function detalles(Lote $lote): View
    {
        $detalles = $lote->detalles()->orderBy('id_detalles', 'desc')->get();

        return view('inventario.lotes-detalles', compact('lote', 'detalles'));
    }

    /**
     * Store a new detail line for a batch and automatically create the
     * corresponding inventario_materia_prima entry (Bodega Principal, ingreso = unidades).
     */
    public function storeDetalle(StoreLoteDetalleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $detalle = LoteDetalle::create([
            'id_lote'     => $data['id_lote'],
            'unidades'    => $data['unidades'],
            'tipo_envase' => $data['tipo_envase'],
            'capacidad'   => $data['capacidad'],
            'proveedor'   => $data['proveedor'],
        ]);

        if ($detalle) {
            // Auto-create inventory entry
            try {
                $inventario = InventarioMateriaPrima::create([
                    'ingreso'        => $detalle->unidades,
                    'fecha'          => now()->toDateString(),
                    'bodega'         => 'Bodega Principal',
                    'id_detalles'    => $detalle->id_detalles,
                    'id_retornables' => null,
                ]);

                // Registrar movimiento de auditoría (ENTRADA)
                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                    $inventario->id_inventario_materia,
                    ($detalle->tipo_envase ?? 'Materia Prima') . ' - Lote ' . ($detalle->lote->codigo_lote ?? ''),
                    MovimientoInventario::TIPO_ENTRADA,
                    (float) $detalle->unidades,
                    0,
                    (float) $detalle->unidades,
                    'LOTE',
                    auth()->id(),
                    $detalle->id_detalles,
                    'Ingreso de materia prima por creación de detalle de lote'
                );
            } catch (\Exception $e) {
                \Log::warning('InventarioMateriaPrima insert failed: ' . $e->getMessage());
            }

            session()->flash('alert', [
                'icon'  => 'success',
                'title' => 'Detalle Añadido',
                'text'  => 'Los detalles se registraron exitosamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se pudo registrar el detalle.',
            ]);
        }

        return redirect()->route('lotes.detalles', $data['id_lote']);
    }

    /**
     * Update an existing detail line and sync the inventory entry's ingreso
     * to match the new unidades value.
     */
    public function updateDetalle(UpdateLoteDetalleRequest $request, LoteDetalle $detalle): RedirectResponse
    {
        $data = $request->validated();

        $unidadesAnterior = (int) $detalle->unidades;
        $unidadesNueva    = (int) $data['unidades'];

        $updated = $detalle->update([
            'unidades'    => $data['unidades'],
            'tipo_envase' => $data['tipo_envase'],
            'capacidad'   => $data['capacidad'],
            'proveedor'   => $data['proveedor'],
        ]);

        if ($updated) {
            // Sincronizar el ingreso del inventario_materia_prima asociado
            $inventario = InventarioMateriaPrima::where('id_detalles', $detalle->id_detalles)->first();
            if ($inventario) {
                $stockAnterior = (int) $inventario->ingreso;
                $diferencia = $unidadesNueva - $unidadesAnterior;
                $nuevoStock = $stockAnterior + $diferencia;

                $inventario->ingreso = $nuevoStock;
                $inventario->save();

                // Registrar movimiento de auditoría (AJUSTE)
                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                    $inventario->id_inventario_materia,
                    ($detalle->tipo_envase ?? 'Materia Prima') . ' - Lote ' . ($detalle->lote->codigo_lote ?? ''),
                    MovimientoInventario::TIPO_AJUSTE,
                    (float) abs($diferencia),
                    (float) $stockAnterior,
                    (float) $nuevoStock,
                    'AJUSTE',
                    auth()->id(),
                    $detalle->id_detalles,
                    'Ajuste de unidades en detalle de lote'
                );
            }

            session()->flash('alert', [
                'icon'  => 'success',
                'title' => 'Detalle Actualizado',
                'text'  => 'Los detalles se actualizaron correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se pudo actualizar el detalle.',
            ]);
        }

        return redirect()->route('lotes.detalles', $data['id_lote']);
    }
}
