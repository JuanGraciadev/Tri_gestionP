<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDevolucionRequest;
use App\Models\DevolucionRetornable;
use App\Models\InventarioMateriaPrima;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DevolucionController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    /**
     * Listado de todas las devoluciones registradas.
     * Solo se muestran productos retornables (garrafones).
     */
    public function index(): View
    {
        $devoluciones = DevolucionRetornable::with(['producto', 'usuario', 'inventarioReingreso'])
            ->orderByDesc('id_retornables')
            ->get();

        // Solo productos retornables disponibles para el formulario
        $productosRetornables = Producto::where('retornable', 1)
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get();

        // KPIs
        $totalDevoluciones   = $devoluciones->count();
        $totalUnidades       = $devoluciones->sum('cantidad');
        // Aptas = tienen registro en inventario_materia_prima
        $devolucionesAptas   = $devoluciones->filter(fn($d) => $d->inventarioReingreso !== null)->count();
        // Dañadas = NO tienen registro en inventario_materia_prima
        $devolucionesDanadas = $devoluciones->filter(fn($d) => $d->inventarioReingreso === null)->count();

        return view('devoluciones.index', compact(
            'devoluciones',
            'productosRetornables',
            'totalDevoluciones',
            'totalUnidades',
            'devolucionesAptas',
            'devolucionesDanadas'
        ));
    }

    // ── Store ──────────────────────────────────────────────────────────────────

    /**
     * Registra una devolución de garrafón.
     *
     * Regla de negocio:
     *  - Solo productos con retornable = 1
     *  - Si apto = 1: INSERT devolucion_retornables + INSERT inventario_materia_prima
     *    (garrafón vuelve al stock de envases disponibles)
     *  - Si apto = 0 (dañado): solo INSERT devolucion_retornables
     *    (garrafón NO vuelve al stock)
     *
     * Se usa DB::transaction porque en el caso "apto" se modifican
     * dos tablas relacionadas simultáneamente.
     */
    public function store(StoreDevolucionRequest $request): RedirectResponse
    {
        $apto = (int) $request->apto === 1;

        DB::transaction(function () use ($request, $apto) {

            // Paso 1: registrar la devolución
            $devolucion = DevolucionRetornable::create([
                'cantidad'    => $request->cantidad,
                'id_producto' => $request->id_producto,
                'id_usuario'  => Auth::user()->id_usuario,
                // fecha se auto-asigna por el default CURRENT_TIMESTAMP
            ]);

            // Paso 2: si el garrafón está APTO → vuelve a inventario de envases
            if ($apto) {
                InventarioMateriaPrima::create([
                    'ingreso'        => $devolucion->cantidad,
                    'fecha'          => now()->toDateString(),
                    'bodega'         => 'Bodega Principal',
                    'id_detalles'    => null,
                    'id_retornables' => $devolucion->id_retornables,
                ]);
            }
            // Si DAÑADO: no se crea registro en inventario_materia_prima
            // → el garrafón no vuelve al stock disponible
        });

        $msg = $apto
            ? 'El garrafón fue devuelto y reingresado al inventario de envases disponibles.'
            : 'El garrafón fue registrado como dañado y NO reingresó al inventario.';

        return redirect()->route('devoluciones.index')
            ->with('alert', [
                'icon'  => 'success',
                'title' => 'Devolución Registrada',
                'text'  => $msg,
            ]);
    }
}
