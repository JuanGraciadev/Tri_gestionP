<?php

namespace App\Services;

use App\Models\InventarioProductos;
use App\Models\DetalleVenta;
use App\Models\Venta;

/**
 * Replicates Venta::stockDisponible() from the original PHP model.
 *
 * Stock = total ingresado al inventario_productos
 *        − total vendido (excluyendo ventas Canceladas)
 */
class StockService
{
    public static function disponible(int $idProducto): int
    {
        // Total ingresado al inventario de productos terminados
        $ingresado = InventarioProductos::where('id_producto', $idProducto)->sum('cantidad');

        // Total vendido (estados que descuentan stock: todo excepto 'Cancelado')
        $vendido = DetalleVenta::query()
            ->where('detalle_venta.id_producto', $idProducto)
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->where(function ($q) {
                $q->whereNull('venta.estado')
                  ->orWhere('venta.estado', '!=', 'Cancelado');
            })
            ->sum('detalle_venta.cantidad');

        return (int) max(0, $ingresado - $vendido);
    }
}
