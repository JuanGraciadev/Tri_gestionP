<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class VentaService
{
    /**
     * Procesa la compra online (checkout / carrito) con bloqueo atómico de stock.
     *
     * @param array $carrito
     * @param string|null $notas
     * @param int $idUsuario
     * @return array
     * @throws RuntimeException
     */
    public function procesarCompraOnline(array $carrito, ?string $notas, int $idUsuario): array
    {
        return DB::transaction(function () use ($carrito, $notas, $idUsuario) {
            foreach ($carrito as $item) {
                $stock = StockService::disponible((int) $item['id_producto'], true);
                if ($stock < (int) $item['cantidad']) {
                    throw new RuntimeException("Stock insuficiente para \"{$item['nombre']}\". Disponible: {$stock}");
                }
            }

            $cliente  = Cliente::firstOrCreateForUser($idUsuario);
            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['precio_unitario'] * $item['cantidad'];
            }
            $total = $subtotal;

            $venta = Venta::create([
                'id_cliente'  => $cliente->id_cliente,
                'id_usuario'  => $idUsuario,
                'estado'      => 'Pendiente',
                'estado_pago' => 'Pendiente',
                'total'       => $total,
                'notas'       => $notas,
                'metodo_pago' => 'Efectivo',
                'fecha'       => now()->toDateString(),
            ]);

            foreach ($carrito as $item) {
                DetalleVenta::create([
                    'id_venta'        => $venta->id_venta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento'       => 0,
                ]);
            }

            return ['id' => $venta->id_venta, 'total' => $total];
        });
    }

    /**
     * Procesa una venta en punto de venta (POS) con entrega inmediata.
     *
     * @param int $idClienteUsuario
     * @param array $items
     * @param string|null $notas
     * @param int $idAdmin
     * @param string $metodoPago
     * @return array
     * @throws RuntimeException
     */
    public function procesarVentaPOS(int $idClienteUsuario, array $items, ?string $notas, int $idAdmin, string $metodoPago = 'Efectivo'): array
    {
        return DB::transaction(function () use ($idClienteUsuario, $items, $notas, $idAdmin, $metodoPago) {
            foreach ($items as $item) {
                $stock = StockService::disponible((int) $item['id_producto'], true);
                if ($stock < (int) $item['cantidad']) {
                    $prod = Producto::find($item['id_producto']);
                    $nombre = $prod->nombre ?? 'ID ' . $item['id_producto'];
                    throw new RuntimeException("Stock insuficiente para \"{$nombre}\". Disponible: {$stock}");
                }
            }

            $cliente  = Cliente::firstOrCreateForUser($idClienteUsuario);
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += ($item['precio_unitario'] * $item['cantidad']) - ($item['descuento'] ?? 0);
            }
            $total = $subtotal;

            $venta = Venta::create([
                'id_cliente'  => $cliente->id_cliente,
                'id_usuario'  => $idAdmin,
                'estado'      => 'Entregado',
                'estado_pago' => 'Confirmado',
                'total'       => $total,
                'notas'       => $notas,
                'metodo_pago' => $metodoPago,
                'fecha'       => now()->toDateString(),
            ]);

            foreach ($items as $item) {
                DetalleVenta::create([
                    'id_venta'        => $venta->id_venta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento'       => $item['descuento'] ?? 0,
                ]);
            }

            return ['id' => $venta->id_venta, 'total' => $total];
        });
    }

    /**
     * Valida y ejecuta la transición de estado de una venta.
     *
     * @param int $idVenta
     * @param string $nuevoEstado
     * @return Venta
     * @throws RuntimeException
     */
    public function cambiarEstado(int $idVenta, string $nuevoEstado): Venta
    {
        $transicionesPermitidas = [
            'Pendiente'  => ['En Proceso', 'Cancelado'],
            'En Proceso' => ['Entregado', 'Cancelado'],
            'Entregado'  => [],
            'Cancelado'  => [],
        ];

        return DB::transaction(function () use ($idVenta, $nuevoEstado, $transicionesPermitidas) {
            /** @var Venta $venta */
            $venta = Venta::with('detalles.producto')->lockForUpdate()->findOrFail($idVenta);
            $estadoActual = $venta->estado;

            if ($estadoActual === $nuevoEstado) {
                return $venta;
            }

            $permitidos = $transicionesPermitidas[$estadoActual] ?? [];
            if (!in_array($nuevoEstado, $permitidos, true)) {
                if (in_array($estadoActual, ['Entregado', 'Cancelado'], true)) {
                    throw new RuntimeException("La venta se encuentra en estado \"{$estadoActual}\" (estado final) y no puede ser modificada.");
                }
                throw new RuntimeException("Transición de estado no permitida: de \"{$estadoActual}\" a \"{$nuevoEstado}\".");
            }

            // Al pasar a "En Proceso", re-validar stock disponible con bloqueo
            if ($nuevoEstado === 'En Proceso') {
                foreach ($venta->detalles as $detalle) {
                    $prodId   = (int) $detalle->id_producto;
                    $cantReq  = (int) $detalle->cantidad;
                    $prodNom  = $detalle->producto->nombre ?? "Producto #{$prodId}";

                    $stockDisponible = StockService::disponible($prodId, true);
                    if ($stockDisponible < $cantReq) {
                        throw new RuntimeException("Stock insuficiente para \"{$prodNom}\". Disponible: {$stockDisponible}, requerido: {$cantReq}.");
                    }
                }
            }

            $venta->estado = $nuevoEstado;
            $venta->save();

            return $venta;
        });
    }

    /**
     * Cambia el estado de pago de una venta.
     *
     * @param int $idVenta
     * @param string $nuevoEstadoPago
     * @return Venta
     */
    public function cambiarEstadoPago(int $idVenta, string $nuevoEstadoPago): Venta
    {
        return DB::transaction(function () use ($idVenta, $nuevoEstadoPago) {
            /** @var Venta $venta */
            $venta = Venta::lockForUpdate()->findOrFail($idVenta);
            $venta->estado_pago = $nuevoEstadoPago;

            if ($nuevoEstadoPago === 'Rechazado' && $venta->estado !== 'Entregado') {
                $venta->estado = 'Cancelado';
            }

            $venta->save();
            return $venta;
        });
    }
}
