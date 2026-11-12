<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\StockService;
use App\Services\VentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CarritoController extends Controller
{
    public function __construct(
        protected VentaService $ventaService = new VentaService()
    ) {}

    public function agregar(Request $request): JsonResponse
    {
        $idProducto = (int) $request->input('id_producto', 0);
        $cantidad   = max(1, (int) $request->input('cantidad', 1));

        $prod = Producto::where('id_producto', $idProducto)->where('estado', 1)->first();
        if (!$prod) {
            return response()->json(['ok' => false, 'msg' => 'Producto no encontrado']);
        }

        $stock = StockService::disponible($idProducto);
        if ($stock < 1) {
            return response()->json(['ok' => false, 'msg' => 'Sin stock disponible para este producto']);
        }

        $carrito = session('carrito', []);

        if (isset($carrito[$idProducto])) {
            $nueva = $carrito[$idProducto]['cantidad'] + $cantidad;
            if ($nueva > $stock) {
                return response()->json(['ok' => false, 'msg' => "Stock máximo disponible: {$stock} unidades"]);
            }
            $carrito[$idProducto]['cantidad'] = $nueva;
        } else {
            $carrito[$idProducto] = [
                'id_producto'     => $idProducto,
                'nombre'          => $prod->nombre,
                'precio_unitario' => (float) $prod->precio,
                'img'             => $prod->img ?? '',
                'cantidad'        => $cantidad,
            ];
        }

        session(['carrito' => $carrito]);

        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        return response()->json(['ok' => true, 'total_items' => $totalItems, 'msg' => 'Producto añadido al carrito']);
    }

    public function obtener(): JsonResponse
    {
        $carrito = session('carrito', []);
        $total   = 0;
        foreach ($carrito as $item) {
            $total += $item['precio_unitario'] * $item['cantidad'];
        }
        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total]);
    }

    public function actualizar(Request $request): JsonResponse
    {
        $idProducto = (int) $request->input('id_producto', 0);
        $cantidad   = (int) $request->input('cantidad', 0);
        $carrito    = session('carrito', []);

        if ($cantidad <= 0) {
            unset($carrito[$idProducto]);
        } else {
            $stock = StockService::disponible($idProducto);
            if ($cantidad > $stock) {
                $cantidad = $stock;
            }
            if (isset($carrito[$idProducto])) {
                $carrito[$idProducto]['cantidad'] = $cantidad;
            }
        }

        session(['carrito' => $carrito]);

        $total = $totalItems = 0;
        foreach ($carrito as $item) {
            $total      += $item['precio_unitario'] * $item['cantidad'];
            $totalItems += $item['cantidad'];
        }

        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total, 'total_items' => $totalItems]);
    }

    public function eliminar(Request $request): JsonResponse
    {
        $idProducto = (int) $request->input('id_producto', 0);
        $carrito    = session('carrito', []);
        unset($carrito[$idProducto]);
        session(['carrito' => $carrito]);

        $total = $totalItems = 0;
        foreach ($carrito as $item) {
            $total      += $item['precio_unitario'] * $item['cantidad'];
            $totalItems += $item['cantidad'];
        }

        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total, 'total_items' => $totalItems]);
    }

    public function vaciar(Request $request): JsonResponse|RedirectResponse
    {
        session()->forget('carrito');

        if ($request->wantsJson()) {
            return response()->json([
                'ok'          => true,
                'carrito'     => [],
                'total'       => 0,
                'total_items' => 0,
                'msg'         => 'Carrito vaciado correctamente',
            ]);
        }

        return redirect()->route('productos.catalogo')
            ->with('alert', ['icon' => 'info', 'title' => 'Carrito vaciado', 'text' => 'Se han retirado los productos del carrito.']);
    }

    public function finalizar(Request $request): JsonResponse|RedirectResponse
    {
        $carrito = session('carrito', []);
        if (empty($carrito)) {
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'msg' => 'El carrito está vacío.']);
            }
            return redirect()->back()
                ->with('alert', ['icon' => 'warning', 'title' => 'Carrito vacío', 'text' => 'Agrega productos para continuar.']);
        }

        $notas     = $request->input('notas');
        $idUsuario = Auth::user()->id_usuario;

        try {
            $result = $this->ventaService->procesarCompraOnline($carrito, $notas, $idUsuario);

            session()->forget('carrito');

            if ($request->wantsJson()) {
                return response()->json([
                    'ok'       => true,
                    'id_venta' => $result['id'],
                    'total'    => $result['total'],
                    'msg'      => '¡Pedido registrado con éxito!',
                ]);
            }

            return redirect()->route('cliente.misCompras')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Pedido Registrado!',
                    'text'  => "Tu pedido #{$result['id']} fue registrado exitosamente.",
                ]);
        } catch (RuntimeException $e) {
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'msg' => $e->getMessage()]);
            }
            return redirect()->back()
                ->with('alert', ['icon' => 'error', 'title' => 'No se pudo procesar', 'text' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Error en finalizarCompra: ' . $e->getMessage(), ['exception' => $e]);
            if ($request->wantsJson()) {
                return response()->json(['ok' => false, 'msg' => 'No se pudo procesar la operación. Intenta de nuevo.']);
            }
            return redirect()->back()
                ->with('alert', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo procesar la operación. Intenta de nuevo.']);
        }
    }
}
