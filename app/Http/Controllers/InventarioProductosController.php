<?php

namespace App\Http\Controllers;

use App\Models\InventarioProductos;
use App\Models\Producto;
use Illuminate\View\View;

class InventarioProductosController extends Controller
{
    /**
     * Display the complete product inventory listing.
     *
     * Shows every inventario_productos entry with its related production,
     * product and user data — exactly as written by ProduccionController::finalizar().
     */
    public function index(): View
    {
        $inventario = InventarioProductos::with(['produccion', 'producto', 'usuario'])
            ->orderByDesc('id_inventario')
            ->paginate(15);

        // KPIs sobre el total real (sin paginar)
        $stockPorProducto = InventarioProductos::selectRaw('id_producto, SUM(cantidad) as total_stock')
            ->groupBy('id_producto')
            ->pluck('total_stock', 'id_producto');

        $totalUnidades     = InventarioProductos::sum('cantidad');
        $totalEntradas     = InventarioProductos::count();
        $productosConStock = $stockPorProducto->count();

        return view('inventario_productos.index', compact(
            'inventario',
            'stockPorProducto',
            'totalUnidades',
            'totalEntradas',
            'productosConStock'
        ));
    }
}
