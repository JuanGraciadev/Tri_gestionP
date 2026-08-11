<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduccionRequest;
use App\Models\InventarioMateriaPrima;
use App\Models\InventarioProductos;
use App\Models\Produccion;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProduccionController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $producciones = Produccion::with(['producto', 'usuario'])
            ->orderByDesc('id_produccion')
            ->paginate(12);

        $productos    = Producto::where('estado', 1)->orderBy('nombre')->get();
        $materiaPrima = InventarioMateriaPrima::with('detalle.lote')
            ->orderByDesc('id_inventario_materia')
            ->get();

        return view('produccion.index', compact('producciones', 'productos', 'materiaPrima'));
    }

    // ── Crear ──────────────────────────────────────────────────────────────────

    public function store(StoreProduccionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Produccion::create([
            'lote_produccion'       => $data['lote_produccion'],
            'cantidad'              => $data['cantidad'],
            'descripcion'           => $data['descripcion'] ?? null,
            'id_usuario'            => Auth::user()->id_usuario,
            'id_producto'           => $data['id_producto'],
            'id_inventario_materia' => $data['id_inventario_materia'] ?? null,
            'estado'                => 'En Producción',
        ]);

        return redirect()->route('produccion.index')
            ->with('alert', [
                'icon'  => 'success',
                'title' => 'Producción Iniciada',
                'text'  => 'La producción se ha registrado y está en curso.',
            ]);
    }

    // ── Finalizar ──────────────────────────────────────────────────────────────

    /**
     * Critical business rule (from original ProduccionController.php):
     *  1. Change production state to "Finalizada"
     *  2. Fetch production data
     *  3. Register finished-goods entry in inventario_productos
     *  4. Discount raw material from inventario_materia_prima (if linked)
     *
     * Guard: if already "Finalizada", the inventario_productos duplicate check
     * prevents a second inventory entry (mirrors the original PHP logic).
     */
    public function finalizar(int $id): RedirectResponse
    {
        /** @var Produccion $produccion */
        $produccion = Produccion::findOrFail($id);

        // Guard – mirror original: only run if NOT already finalised
        if ($produccion->estaFinalizada()) {
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Operación no permitida',
                    'text'  => 'Esta producción ya fue finalizada anteriormente.',
                ]);
        }

        // Full DB transaction to keep everything consistent
        DB::transaction(function () use ($produccion) {

            // Step 1 – update state
            $produccion->estado = 'Finalizada';
            $produccion->save();

            // Step 2 – register finished-goods inventory entry
            // Duplicate guard: mirrors the original PHP check for id_produccion
            $yaRegistrado = InventarioProductos::where('id_produccion', $produccion->id_produccion)->exists();

            if (!$yaRegistrado) {
                InventarioProductos::create([
                    'fecha'         => now(),
                    'bodega'        => 'Principal',
                    'id_produccion' => $produccion->id_produccion,
                    'id_producto'   => $produccion->id_producto,
                    'id_usuario'    => Auth::user()->id_usuario,
                    'cantidad'      => $produccion->cantidad,
                ]);
            }

            // Step 3 – discount raw material if linked (mirrors descontarStock())
            if ($produccion->id_inventario_materia) {
                /** @var InventarioMateriaPrima|null $mp */
                $mp = InventarioMateriaPrima::find($produccion->id_inventario_materia);
                if ($mp) {
                    $mp->ingreso = max(0, (int) $mp->ingreso - (int) $produccion->cantidad);
                    $mp->save();
                }
            }
        });

        return redirect()->route('produccion.index')
            ->with('alert', [
                'icon'  => 'success',
                'title' => '¡Producción Finalizada!',
                'text'  => 'La producción fue completada. Se actualizó el inventario de productos y se descontó la materia prima utilizada.',
            ]);
    }
}
