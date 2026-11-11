<?php

namespace App\Services;

use App\Models\InventarioProductos;
use App\Models\DetalleVenta;
use App\Models\Producto;

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

    /**
     * Retorna productos activos con stock disponible menor o igual a su stock_minimo.
     *
     * @return array
     */
    public static function productosConBajoStock(): array
    {
        $productos = Producto::where('estado', true)->get();
        $resultado = [];

        foreach ($productos as $producto) {
            $disponible = self::disponible($producto->id_producto);
            $minimo = $producto->stock_minimo ?? 10;
            if ($disponible <= $minimo) {
                $resultado[] = [
                    'id_producto'  => $producto->id_producto,
                    'nombre'       => $producto->nombre,
                    'disponible'   => $disponible,
                    'stock_minimo' => $minimo,
                    'agotado'      => $disponible <= 0,
                    'producto'     => $producto,
                ];
            }
        }

        return $resultado;
    }

    /**
     * Devuelve las alertas formateadas para la API / vistas, similar a MateriaPrima::obtenerAlertas.
     *
     * @return array
     */
    public static function alertas(): array
    {
        $bajos = self::productosConBajoStock();
        $agotados = array_values(array_filter($bajos, fn($p) => $p['disponible'] <= 0));
        $stockBajo = array_values(array_filter($bajos, fn($p) => $p['disponible'] > 0));

        return [
            'hayAlertas' => count($bajos) > 0,
            'agotados'   => $agotados,
            'stockBajo'  => $stockBajo,
            'total'      => count($bajos),
        ];
    }
}
