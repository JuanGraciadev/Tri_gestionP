<?php

namespace App\Services;

use App\Models\InventarioProductos;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Calcula el stock disponible de productos terminados.
 *
 * Stock disponible = total ingresado (inventario_productos)
 *                  − total vendido en ventas CONFIRMADAS (En Proceso | Entregado)
 *
 * Pendiente y Cancelado nunca descuentan stock.
 */
class StockService
{
    private const ESTADOS_QUE_DESCUENTAN = ['En Proceso', 'Entregado'];

    // ── Single ──────────────────────────────────────────────────────────────

    /**
     * Stock disponible para UN producto.
     * Cachea 60 segundos. Se invalida automáticamente al modificar stock (ver invalidarCache).
     */
    public static function disponible(int $idProducto): int
    {
        return Cache::remember("stock_producto_{$idProducto}", 60, function () use ($idProducto) {
            $ingresado = (int) InventarioProductos::where('id_producto', $idProducto)
                ->sum('cantidad');

            $vendido = (int) DetalleVenta::query()
                ->where('detalle_venta.id_producto', $idProducto)
                ->join('venta', 'detalle_venta.id_venta', '=', 'venta.id_venta')
                ->whereIn('venta.estado', self::ESTADOS_QUE_DESCUENTAN)
                ->sum('detalle_venta.cantidad');

            return max(0, $ingresado - $vendido);
        });
    }

    /**
     * Invalida el caché de stock de uno o varios productos.
     * Llamar después de cualquier operación que modifique el stock.
     */
    public static function invalidarCache(int|array $ids): void
    {
        foreach ((array) $ids as $id) {
            Cache::forget("stock_producto_{$id}");
        }
    }

    // ── Bulk (2 queries para N productos) ───────────────────────────────────

    /**
     * Stock disponible para MÚLTIPLES productos en solo 2 queries.
     *
     * @param  int[]|null $ids  IDs de productos; null = todos los que tengan inventario.
     * @return array<int,int>   Map [id_producto => stock_disponible]
     */
    public static function disponibleBulk(?array $ids = null): array
    {
        // Query 1: total producido por producto
        $ingresoQ = DB::table('inventario_productos')
            ->selectRaw('id_producto, SUM(cantidad) as total')
            ->groupBy('id_producto');

        if ($ids !== null) {
            $ingresoQ->whereIn('id_producto', $ids);
        }

        $ingresado = $ingresoQ->pluck('total', 'id_producto')
            ->map(fn($v) => (int) $v)
            ->toArray();

        if (empty($ingresado)) {
            return [];
        }

        $idsConInventario = array_keys($ingresado);

        // Query 2: total vendido en ventas confirmadas por producto
        $vendido = DB::table('detalle_venta as dv')
            ->selectRaw('dv.id_producto, SUM(dv.cantidad) as total')
            ->join('venta as v', 'dv.id_venta', '=', 'v.id_venta')
            ->whereIn('v.estado', self::ESTADOS_QUE_DESCUENTAN)
            ->whereIn('dv.id_producto', $idsConInventario)
            ->groupBy('dv.id_producto')
            ->pluck('total', 'id_producto')
            ->map(fn($v) => (int) $v)
            ->toArray();

        // Calcular disponible por producto
        $resultado = [];
        foreach ($ingresado as $idProducto => $totalIngresado) {
            $totalVendido = $vendido[$idProducto] ?? 0;
            $resultado[$idProducto] = max(0, $totalIngresado - $totalVendido);
        }

        return $resultado;
    }

    // ── Alertas ─────────────────────────────────────────────────────────────

    /**
     * Productos activos con stock <= stock_minimo.
     * Usa disponibleBulk() — solo 2 queries totales.
     */
    public static function productosConBajoStock(): array
    {
        $productos = Producto::where('estado', true)->get();

        if ($productos->isEmpty()) {
            return [];
        }

        $ids   = $productos->pluck('id_producto')->toArray();
        $stock = self::disponibleBulk($ids);

        $resultado = [];
        foreach ($productos as $producto) {
            $disponible = $stock[$producto->id_producto] ?? 0;
            $minimo     = $producto->stock_minimo ?? 10;

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
     * Alertas formateadas para API / vistas.
     */
    public static function alertas(): array
    {
        $bajos     = self::productosConBajoStock();
        $agotados  = array_values(array_filter($bajos, fn($p) => $p['disponible'] <= 0));
        $stockBajo = array_values(array_filter($bajos, fn($p) => $p['disponible'] > 0));

        return [
            'hayAlertas' => count($bajos) > 0,
            'agotados'   => $agotados,
            'stockBajo'  => $stockBajo,
            'total'      => count($bajos),
        ];
    }
}
