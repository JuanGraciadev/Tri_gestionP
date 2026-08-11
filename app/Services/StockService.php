<?php

namespace App\Services;

use App\Models\InventarioProductos;
use App\Models\DetalleVenta;

/**
 * Calcula el stock disponible de un producto.
 *
 * Stock disponible = total ingresado al inventario_productos
 *                  − total vendido en ventas CONFIRMADAS (En Proceso o Entregado)
 *
 * Las ventas en estado 'Pendiente' NO descuentan stock hasta que el admin
 * las pone en proceso, evitando bloquear inventario por pedidos sin confirmar.
 * Las ventas 'Cancelado' nunca descuentan.
 */
class StockService
{
    /** Estados que sí consumen stock real */
    private const ESTADOS_QUE_DESCUENTAN = ['En Proceso', 'Entregado'];

    public static function disponible(int $idProducto): int
    {
        // Total ingresado al inventario de productos terminados
        $ingresado = InventarioProductos::where('id_producto', $idProducto)->sum('cantidad');

        // Total vendido solo en ventas confirmadas
        $vendido = DetalleVenta::query()
            ->where('detalle_venta.id_producto', $idProducto)
            ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
            ->whereIn('venta.estado', self::ESTADOS_QUE_DESCUENTAN)
            ->sum('detalle_venta.cantidad');

        return (int) max(0, $ingresado - $vendido);
    }
}
