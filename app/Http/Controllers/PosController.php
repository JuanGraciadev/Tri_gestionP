<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\CrearPosRequest;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PosController extends Controller
{
    public function __construct(
        protected VentaService $ventaService = new VentaService()
    ) {}

    public function crear(CrearPosRequest $request): RedirectResponse
    {
        $idAdmin = Auth::user()->id_usuario;

        $idClienteUsuario = (int) ($request->input('id_cliente') ?? $request->input('id_usuario_cliente'));
        $productosIds     = $request->input('pos_producto', $request->input('productos', []));
        $cantidades       = $request->input('pos_cantidad', $request->input('cantidades', []));
        $notas            = $request->input('notas', 'Venta en punto de venta');
        $metodoPago       = $request->input('metodo_pago', 'Efectivo');

        $productosMap = Producto::whereIn('id_producto', $productosIds)->get()->keyBy('id_producto');

        $items = [];
        for ($i = 0; $i < count($productosIds); $i++) {
            $pid = (int) $productosIds[$i];
            $qty = max(1, (int) ($cantidades[$i] ?? 1));
            if ($pid > 0 && isset($productosMap[$pid])) {
                $items[] = [
                    'id_producto'     => $pid,
                    'cantidad'        => $qty,
                    'precio_unitario' => (float) $productosMap[$pid]->precio,
                    'descuento'       => 0,
                ];
            }
        }

        if (empty($items)) {
            return redirect()->route('ventas.index')
                ->with('alert', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se encontraron productos válidos.']);
        }

        try {
            $result = $this->ventaService->procesarVentaPOS($idClienteUsuario, $items, $notas, $idAdmin, $metodoPago);

            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Venta Registrada!',
                    'text'  => "Venta #{$result['id']} registrada. Total: $" . number_format($result['total'], 2),
                ]);
        } catch (RuntimeException $e) {
            return redirect()->route('ventas.index')
                ->with('alert', ['icon' => 'error', 'title' => 'Stock insuficiente', 'text' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Error en crearPOS: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('ventas.index')
                ->with('alert', ['icon' => 'error', 'title' => 'Error al registrar', 'text' => 'No se pudo procesar la operación. Intenta de nuevo.']);
        }
    }

    public function obtenerFactura(int $idVenta): JsonResponse
    {
        $venta = Venta::with(['detalles.producto', 'cliente.usuario', 'usuario'])->find($idVenta);
        if (!$venta) {
            return response()->json(['ok' => false, 'msg' => 'Venta no encontrada'], 404);
        }

        $items = $venta->detalles->map(fn($d) => [
            'producto' => $d->producto->nombre ?? 'Producto',
            'cantidad' => $d->cantidad,
            'precio'   => (float) $d->precio_unitario,
            'subtotal' => (float) ($d->cantidad * $d->precio_unitario),
        ]);

        return response()->json([
            'ok'    => true,
            'venta' => [
                'id_venta'    => $venta->id_venta,
                'fecha'       => $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') : '',
                'total'       => (float) $venta->total,
                'estado'      => $venta->estado,
                'metodo_pago' => $venta->metodo_pago ?? 'Efectivo',
                'cliente'     => $venta->cliente->usuario->nombres ?? 'Cliente Mostrador',
                'vendedor'    => $venta->usuario->nombres ?? 'Sistema',
                'items'       => $items,
            ],
        ]);
    }
}
