<?php

namespace App\Http\Controllers;

use App\Models\InventarioProductos;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Carrito de compras basado en sesión.
 *
 * Estructura de la sesión:
 * carrito = [
 *   id_producto => [
 *     'id_producto' => int,
 *     'nombre'      => string,
 *     'precio'      => float,
 *     'cantidad'    => int,
 *     'img'         => string|null,
 *     'subtotal'    => float,
 *   ],
 *   ...
 * ]
 */
class CarritoController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Calcula el stock disponible de un producto sumando inventario_productos
     * menos las ventas confirmadas (estado != 'Cancelada').
     */
    private function stockDisponible(int $idProducto): int
    {
        $entradas = InventarioProductos::where('id_producto', $idProducto)->sum('cantidad');

        $vendido = \App\Models\DetalleVenta::whereHas('venta', function ($q) {
                $q->whereNotIn('estado', ['Cancelado']);
            })
            ->where('id_producto', $idProducto)
            ->sum('cantidad');

        return max(0, (int) $entradas - (int) $vendido);
    }

    // ── Agregar / Aumentar ─────────────────────────────────────────────────────

    /**
     * POST /carrito/agregar
     * Body: { id_producto, cantidad }
     * Response JSON: { ok, mensaje, carrito_count, total }
     */
    public function agregar(Request $request): JsonResponse
    {
        $request->validate([
            'id_producto' => 'required|exists:producto,id_producto',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $idProducto = (int) $request->id_producto;
        $cantidad   = (int) $request->cantidad;

        $producto = Producto::where('id_producto', $idProducto)
            ->where('estado', 1)
            ->first();

        if (!$producto) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Producto no disponible.',
            ], 422);
        }

        $carrito         = session('carrito', []);
        $cantidadActual  = $carrito[$idProducto]['cantidad'] ?? 0;
        $cantidadNueva   = $cantidadActual + $cantidad;
        $stock           = $this->stockDisponible($idProducto);

        if ($cantidadNueva > $stock) {
            return response()->json([
                'ok'      => false,
                'mensaje' => "Stock insuficiente. Disponibles: {$stock} unidad(es).",
            ], 422);
        }

        $carrito[$idProducto] = [
            'id_producto' => $idProducto,
            'nombre'      => $producto->nombre,
            'precio'      => (float) $producto->precio,
            'cantidad'    => $cantidadNueva,
            'img'         => $producto->img,
            'subtotal'    => round((float) $producto->precio * $cantidadNueva, 2),
        ];

        session(['carrito' => $carrito]);

        return response()->json([
            'ok'           => true,
            'mensaje'      => 'Producto agregado al carrito.',
            'carrito_count' => $this->contarItems($carrito),
            'total'        => $this->calcularTotal($carrito),
        ]);
    }

    // ── Actualizar cantidad ────────────────────────────────────────────────────

    /**
     * POST /carrito/actualizar
     * Body: { id_producto, cantidad }
     */
    public function actualizar(Request $request): JsonResponse
    {
        $request->validate([
            'id_producto' => 'required|integer',
            'cantidad'    => 'required|integer|min:0',
        ]);

        $idProducto = (int) $request->id_producto;
        $cantidad   = (int) $request->cantidad;
        $carrito    = session('carrito', []);

        if (!isset($carrito[$idProducto])) {
            return response()->json(['ok' => false, 'mensaje' => 'Producto no está en el carrito.'], 422);
        }

        if ($cantidad === 0) {
            unset($carrito[$idProducto]);
            session(['carrito' => $carrito]);
            return response()->json([
                'ok'            => true,
                'mensaje'       => 'Producto eliminado del carrito.',
                'eliminado'     => true,
                'carrito_count' => $this->contarItems($carrito),
                'total'         => $this->calcularTotal($carrito),
            ]);
        }

        $stock = $this->stockDisponible($idProducto);

        if ($cantidad > $stock) {
            return response()->json([
                'ok'      => false,
                'mensaje' => "Stock insuficiente. Disponibles: {$stock} unidad(es).",
            ], 422);
        }

        $carrito[$idProducto]['cantidad'] = $cantidad;
        $carrito[$idProducto]['subtotal'] = round($carrito[$idProducto]['precio'] * $cantidad, 2);

        session(['carrito' => $carrito]);

        return response()->json([
            'ok'            => true,
            'mensaje'       => 'Cantidad actualizada.',
            'subtotal'      => number_format($carrito[$idProducto]['subtotal'], 2),
            'carrito_count' => $this->contarItems($carrito),
            'total'         => $this->calcularTotal($carrito),
        ]);
    }

    // ── Eliminar ítem ──────────────────────────────────────────────────────────

    /**
     * POST /carrito/eliminar
     * Body: { id_producto }
     */
    public function eliminar(Request $request): JsonResponse
    {
        $request->validate(['id_producto' => 'required|integer']);

        $idProducto = (int) $request->id_producto;
        $carrito    = session('carrito', []);

        unset($carrito[$idProducto]);
        session(['carrito' => $carrito]);

        return response()->json([
            'ok'            => true,
            'mensaje'       => 'Producto eliminado del carrito.',
            'carrito_count' => $this->contarItems($carrito),
            'total'         => $this->calcularTotal($carrito),
        ]);
    }

    // ── Obtener carrito ────────────────────────────────────────────────────────

    /**
     * GET /carrito
     * Response JSON: { items, total, count }
     */
    public function obtener(): JsonResponse
    {
        $carrito = session('carrito', []);

        return response()->json([
            'ok'    => true,
            'items' => array_values($carrito),
            'count' => $this->contarItems($carrito),
            'total' => $this->calcularTotal($carrito),
        ]);
    }

    // ── Vaciar ────────────────────────────────────────────────────────────────

    /**
     * POST /carrito/vaciar
     */
    public function vaciar(): JsonResponse
    {
        session()->forget('carrito');

        return response()->json(['ok' => true, 'mensaje' => 'Carrito vaciado.']);
    }

    // ── Helpers internos ──────────────────────────────────────────────────────

    private function contarItems(array $carrito): int
    {
        return array_sum(array_column($carrito, 'cantidad'));
    }

    private function calcularTotal(array $carrito): string
    {
        $total = array_sum(array_column($carrito, 'subtotal'));
        return number_format($total, 2);
    }
}
