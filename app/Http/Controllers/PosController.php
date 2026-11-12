<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\CrearPosRequest;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
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

    public function obtenerFactura(Request $request, int $idVenta): JsonResponse|View
    {
        $venta = Venta::with(['detalles.producto', 'cliente.usuario', 'usuario'])->find($idVenta);
        if (!$venta) {
            if ($request->wantsJson() || $request->query('format') === 'json') {
                return response()->json(['ok' => false, 'msg' => 'Venta no encontrada'], 404);
            }
            abort(404, 'La venta solicitada no existe.');
        }

        // Validación de permisos por rol
        $user = Auth::user();
        if ($user && (int) $user->id_rol === 3) {
            $esPropia = ((int) $venta->id_usuario === (int) $user->id_usuario)
                || ((int) ($venta->cliente?->id_usuario ?? 0) === (int) $user->id_usuario);
            if (!$esPropia) {
                if ($request->wantsJson() || $request->query('format') === 'json') {
                    return response()->json(['ok' => false, 'msg' => 'No autorizado'], 403);
                }
                abort(403, 'No tienes autorización para ver este comprobante.');
            }
        }

        if ($request->wantsJson() || $request->query('format') === 'json') {
            $items = $venta->detalles->map(fn($d) => [
                'producto'        => $d->producto->nombre ?? 'Producto',
                'producto_nombre' => $d->producto->nombre ?? 'Producto',
                'cantidad'        => $d->cantidad,
                'precio'          => (float) $d->precio_unitario,
                'precio_unitario' => (float) $d->precio_unitario,
                'subtotal'        => (float) ($d->cantidad * $d->precio_unitario),
            ]);

            $payload = [
                'id_venta'    => $venta->id_venta,
                'fecha'       => $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') : '',
                'total'       => (float) $venta->total,
                'estado'      => $venta->estado,
                'metodo_pago' => $venta->metodo_pago ?? 'Efectivo',
                'cliente'     => $venta->cliente_nombre ?? ($venta->cliente->usuario->nombres ?? 'Cliente Mostrador'),
                'vendedor'    => $venta->usuario->nombres ?? 'Sistema',
                'notas'       => $venta->notas,
                'items'       => $items,
            ];

            return response()->json([
                'ok'      => true,
                'venta'   => $payload,
                'factura' => $payload,
            ]);
        }

        return view('ventas.factura-pos', compact('venta'));
    }
}
