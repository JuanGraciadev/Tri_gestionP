<?php

namespace App\Http\Controllers;

use App\Models\InventarioMateriaPrima;
use App\Models\MovimientoInventario;
use Illuminate\View\View;

class InventarioMateriaPrimaController extends Controller
{
    /**
     * Display the raw-material inventory listing.
     */
    public function index(): View
    {
        $inventario = InventarioMateriaPrima::with(['detalle.lote'])
            ->orderByDesc('id_inventario_materia')
            ->paginate(15);

        // KPIs sobre el total real (sin paginar)
        $totalIngreso   = InventarioMateriaPrima::sum('ingreso');
        $totalRegistros = InventarioMateriaPrima::count();
        $conStock       = InventarioMateriaPrima::where('ingreso', '>', 0)->count();

        return view('inventario.materia-prima', compact(
            'inventario',
            'totalIngreso',
            'totalRegistros',
            'conStock'
        ));
    }
}
