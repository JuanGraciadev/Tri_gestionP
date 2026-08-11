<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VentaController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════
    // CARRITO (AJAX – sesión)
    // ════════════════════════════════════════════════════════════════════════

    public function agregarAlCarrito(Request $request): JsonResponse
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

        $total_items = array_sum(array_column($carrito, 'cantidad'));
        return response()->json(['ok' => true, 'total_items' => $total_items, 'msg' => 'Producto añadido al carrito']);
    }

    public function obtenerCarrito(): JsonResponse
    {
        $carrito = session('carrito', []);
        $total   = 0;
        foreach ($carrito as $item) {
            $total += $item['precio_unitario'] * $item['cantidad'];
        }
        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total]);
    }

    public function actualizarCarrito(Request $request): JsonResponse
    {
        $idProducto = (int) $request->input('id_producto', 0);
        $cantidad   = (int) $request->input('cantidad', 0);
        $carrito    = session('carrito', []);

        if ($cantidad <= 0) {
            unset($carrito[$idProducto]);
        } else {
            $stock = StockService::disponible($idProducto);
            if ($cantidad > $stock) $cantidad = $stock;
            if (isset($carrito[$idProducto])) {
                $carrito[$idProducto]['cantidad'] = $cantidad;
            }
        }

        session(['carrito' => $carrito]);

        $total = $total_items = 0;
        foreach ($carrito as $item) {
            $total       += $item['precio_unitario'] * $item['cantidad'];
            $total_items += $item['cantidad'];
        }

        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total, 'total_items' => $total_items]);
    }

    public function eliminarDelCarrito(Request $request): JsonResponse
    {
        $idProducto = (int) $request->input('id_producto', 0);
        $carrito    = session('carrito', []);
        unset($carrito[$idProducto]);
        session(['carrito' => $carrito]);

        $total = $total_items = 0;
        foreach ($carrito as $item) {
            $total       += $item['precio_unitario'] * $item['cantidad'];
            $total_items += $item['cantidad'];
        }

        return response()->json(['ok' => true, 'carrito' => array_values($carrito), 'total' => $total, 'total_items' => $total_items]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // CHECKOUT — finalizar_compra
    // ════════════════════════════════════════════════════════════════════════

    public function finalizarCompra(Request $request): JsonResponse
    {
        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return response()->json(['ok' => false, 'msg' => 'El carrito está vacío']);
        }

        $idUsuario  = Auth::user()->id_usuario;
        $notas      = $request->input('notas', '');
        $metodoPago = $request->input('metodo_pago', 'Efectivo');

        $items = [];
        foreach ($carrito as $item) {
            $items[] = [
                'id_producto'     => $item['id_producto'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'descuento'       => 0,
            ];
        }

        try {
            $result = DB::transaction(function () use ($idUsuario, $items, $notas, $metodoPago) {
                foreach ($items as $item) {
                    $stock = StockService::disponible($item['id_producto']);
                    if ($stock < $item['cantidad']) {
                        $prod = Producto::find($item['id_producto']);
                        $nombre = $prod->nombre ?? 'ID ' . $item['id_producto'];
                        throw new \RuntimeException("Stock insuficiente para \"{$nombre}\". Disponible: {$stock}");
                    }
                }

                $cliente   = Cliente::firstOrCreateForUser($idUsuario);
                $subtotal  = 0;
                foreach ($items as $item) {
                    $subtotal += ($item['precio_unitario'] * $item['cantidad']) - ($item['descuento'] ?? 0);
                }
                $total = $subtotal;

                $venta = Venta::create([
                    'id_cliente'  => $cliente->id_cliente,
                    'id_usuario'  => $idUsuario,
                    'estado'      => 'Pendiente',
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

                return $venta->id_venta;
            });

            session(['carrito' => []]);

            $detalles = DetalleVenta::with('producto')
                ->where('id_venta', $result)
                ->get()
                ->map(fn($d) => [
                    'producto_nombre' => $d->producto->nombre ?? '',
                    'producto_img'    => $d->producto->img ?? '',
                    'cantidad'        => $d->cantidad,
                    'precio_unitario' => $d->precio_unitario,
                    'descuento'       => $d->descuento,
                ])->toArray();

            $user  = Auth::user();
            $total = array_sum(array_map(fn($i) => $i['precio_unitario'] * $i['cantidad'], $detalles));

            return response()->json([
                'ok'       => true,
                'id_venta' => $result,
                'msg'      => '¡Pedido realizado con éxito!',
                'factura'  => [
                    'id_venta'    => $result,
                    'fecha'       => now()->format('d/m/Y'),
                    'cliente'     => $user->nombres ?? $user->name,
                    'email'       => $user->email ?? '',
                    'telefono'    => $user->telefono ?? '',
                    'direccion'   => $user->direccion ?? '',
                    'metodo_pago' => $metodoPago,
                    'notas'       => $notas,
                    'items'       => $detalles,
                    'total'       => $total,
                ],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['ok' => false, 'msg' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'msg' => 'Error al procesar la compra: ' . $e->getMessage()]);
        }
    }

    public function obtenerFactura(int $idVenta): JsonResponse
    {
        $venta = Venta::with(['cliente.usuario', 'detalles.producto'])->find($idVenta);
        if (!$venta) {
            return response()->json(['ok' => false, 'msg' => 'Venta no encontrada']);
        }

        $clienteUser = $venta->cliente?->usuario;
        $authUser    = Auth::user();

        $detalles = $venta->detalles->map(fn($d) => [
            'producto_nombre' => $d->producto->nombre ?? '',
            'producto_img'    => $d->producto->img ?? '',
            'cantidad'        => $d->cantidad,
            'precio_unitario' => $d->precio_unitario,
            'descuento'       => $d->descuento,
        ])->toArray();

        return response()->json([
            'ok'      => true,
            'factura' => [
                'id_venta'    => $venta->id_venta,
                'fecha'       => optional($venta->fecha)->format('d/m/Y') ?? '',
                'cliente'     => $clienteUser->nombres ?? $authUser->nombres ?? $authUser->name,
                'email'       => $clienteUser->email ?? $authUser->email ?? '',
                'telefono'    => $clienteUser->telefono ?? $authUser->telefono ?? '',
                'direccion'   => $clienteUser->direccion ?? $authUser->direccion ?? '',
                'metodo_pago' => $venta->metodo_pago ?? 'Efectivo',
                'notas'       => $venta->notas ?? '',
                'items'       => $detalles,
                'total'       => $venta->total,
            ],
        ]);
    }

    // ════════════════════════════════════════════════════════════════════════
    // ADMIN VIEWS
    // ════════════════════════════════════════════════════════════════════════

    public function index(): View
    {
        $ventas = Venta::with(['cliente.usuario', 'detalles.producto'])
            ->orderByDesc('id_venta')
            ->paginate(15);

        // Mapear datos del cliente en cada venta
        $ventas->getCollection()->transform(function ($v) {
            $u = $v->cliente?->usuario;
            $v->cliente_nombre    = $u?->nombres ?? 'N/A';
            $v->cliente_email     = $u?->email ?? '';
            $v->cliente_telefono  = $u?->telefono ?? '';
            $v->cliente_direccion = $u?->direccion ?? '';
            return $v;
        });

        $stats = [
            'Pendiente'      => Venta::where('estado', 'Pendiente')->count(),
            'En Proceso'     => Venta::where('estado', 'En Proceso')->count(),
            'Entregado'      => Venta::where('estado', 'Entregado')->count(),
            'Cancelado'      => Venta::where('estado', 'Cancelado')->count(),
            'total_ingresos' => Venta::whereNotIn('estado', ['Pendiente', 'Cancelado'])->sum('total'),
        ];

        $productosActivos = Producto::where('estado', 1)->orderBy('nombre')->get()
            ->map(function ($p) {
                $p->stock = StockService::disponible($p->id_producto);
                return $p;
            });

        $clientes = User::where('id_rol', 3)->orderBy('nombres')->get();

        return view('ventas.index', compact('ventas', 'stats', 'productosActivos', 'clientes'));
    }

    public function cambiarEstado(Request $request): RedirectResponse
    {
        $request->validate([
            'id_venta' => ['required', 'integer', 'exists:venta,id_venta'],
            'estado'   => ['required', 'in:Pendiente,En Proceso,Entregado,Cancelado'],
        ]);

        $venta = Venta::findOrFail($request->id_venta);
        $venta->update([
            'estado'     => $request->estado,
            'id_usuario' => Auth::user()->id_usuario,
        ]);

        return redirect()->route('ventas.index')
            ->with('alert', [
                'icon'  => 'success',
                'title' => 'Estado Actualizado',
                'text'  => "El pedido #{$venta->id_venta} ahora está en estado: {$request->estado}",
            ]);
    }

    public function crearPOS(Request $request): RedirectResponse
    {
        $request->validate([
            'id_cliente'     => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'pos_producto'   => ['required', 'array', 'min:1'],
            'pos_producto.*' => ['integer'],
            'pos_cantidad'   => ['required', 'array'],
            'pos_cantidad.*' => ['integer', 'min:1'],
        ]);

        $idClienteUsuario = (int) $request->id_cliente;
        $notas            = $request->input('notas', 'Venta en punto de venta');
        $idAdmin          = Auth::user()->id_usuario;
        $productosIds     = $request->input('pos_producto', []);
        $cantidades       = $request->input('pos_cantidad', []);

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
            $result = DB::transaction(function () use ($idClienteUsuario, $items, $notas, $idAdmin) {
                foreach ($items as $item) {
                    $stock = StockService::disponible($item['id_producto']);
                    if ($stock < $item['cantidad']) {
                        $prod = Producto::find($item['id_producto']);
                        $nombre = $prod->nombre ?? 'ID ' . $item['id_producto'];
                        throw new \RuntimeException("Stock insuficiente para \"{$nombre}\". Disponible: {$stock}");
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
                    'total'       => $total,
                    'notas'       => $notas,
                    'metodo_pago' => 'Efectivo',
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

            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Venta Registrada!',
                    'text'  => "Venta #{$result['id']} registrada. Total: $" . number_format($result['total'], 2),
                ]);
        } catch (\Throwable $e) {
            return redirect()->route('ventas.index')
                ->with('alert', ['icon' => 'error', 'title' => 'Error al registrar', 'text' => $e->getMessage()]);
        }
    }

    // ════════════════════════════════════════════════════════════════════════
    // CLIENTE — Mis Compras
    // ════════════════════════════════════════════════════════════════════════

    public function misCompras(): View
    {
        $idUsuario = Auth::user()->id_usuario;

        $ventas = Venta::with(['detalles.producto'])
            ->whereHas('cliente', fn($q) => $q->where('id_usuario', $idUsuario))
            ->orderByDesc('id_venta')
            ->get()
            ->map(function ($v) {
                $v->productos_lista = $v->detalles
                    ->map(fn($d) => $d->producto->nombre ?? '')
                    ->filter()
                    ->implode(', ');
                return $v;
            });

        return view('ventas.mis-compras', compact('ventas'));
    }
}
