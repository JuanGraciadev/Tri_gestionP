<?php

namespace App\Http\Controllers;

use App\Models\InventarioMateriaPrima;
use Illuminate\View\View;

class InventarioMateriaPrimaController extends Controller
{
    /**
     * Display the raw-material inventory listing.
     *
     * Shows every inventario_materia_prima entry with its related
     * lote-detalle (tipo_envase, capacidad, proveedor, unidades)
     * and the parent lote (codigo_lote).
     *
     * Entries are created automatically by LoteController::storeDetalle()
     * and decremented by ProduccionController::finalizar().
     * There is no manual CRUD for this table.
     */
    public function index(): View
    {
        $inventario = InventarioMateriaPrima::with(['detalle.lote'])
            ->orderByDesc('id_inventario_materia')
            ->get();

        // KPI: total de unidades disponibles (campo ingreso = stock actual)
        $totalIngreso = $inventario->sum('ingreso');

        // KPI: total de registros
        $totalRegistros = $inventario->count();

        // KPI: registros con stock > 0
        $conStock = $inventario->where('ingreso', '>', 0)->count();

        return view('inventario_mp.index', compact(
            'inventario',
            'totalIngreso',
            'totalRegistros',
            'conStock'
        ));
    }
}
