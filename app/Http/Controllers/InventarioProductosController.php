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
            ->get();

        // Stock total por producto: SUM(cantidad) agrupado por id_producto
        $stockPorProducto = InventarioProductos::selectRaw('id_producto, SUM(cantidad) as total_stock')
            ->groupBy('id_producto')
            ->pluck('total_stock', 'id_producto');

        // Total de unidades en todo el inventario
        $totalUnidades = $inventario->sum('cantidad');

        // Total de registros (entradas)
        $totalEntradas = $inventario->count();

        // Número de productos distintos con stock
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
