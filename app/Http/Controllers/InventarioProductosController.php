<?php

namespace App\Http\Controllers;

use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Services\StockService;
use Illuminate\View\View;

class InventarioProductosController extends Controller
{
    /**
     * Display the complete product inventory listing.
     *
     * Shows every inventario_productos entry with its related production,
     * product and user data — exactly as written by ProduccionController::finalizar().
     *
     * Stock available = total produced − total sold in confirmed sales.
     */
    public function index(): View
    {
        $inventario = InventarioProductos::with(['produccion', 'producto', 'usuario'])
            ->orderByDesc('id_inventario')
            ->paginate(15);

        // Stock REAL disponible por producto (producido − vendido) — solo 2 queries
        $productosIds    = InventarioProductos::pluck('id_producto')->unique()->values()->toArray();
        $stockDisponible = StockService::disponibleBulk($productosIds);

        $totalUnidadesDisponibles = array_sum($stockDisponible);
        $productosConStock        = count(array_filter($stockDisponible, fn($s) => $s > 0));

        // KPIs históricos (total producido y entradas registradas)
        $totalUnidadesProducidas = InventarioProductos::sum('cantidad');
        $totalEntradas           = InventarioProductos::count();
        $productosDistintos      = $productosIds->count();

        return view('inventario.productos', compact(
            'inventario',
            'stockDisponible',
            'totalUnidadesProducidas',
            'totalUnidadesDisponibles',
            'totalEntradas',
            'productosConStock',
            'productosDistintos'
        ));
    }
}
