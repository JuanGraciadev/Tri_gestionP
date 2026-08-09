<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VentaController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Stock disponible = SUM(inventario_productos.cantidad) - SUM(detalle_venta.cantidad) de ventas activas.
     */
    private function stockDisponible(int $idProducto): int
    {
        $entradas = InventarioProductos::where('id_producto', $idProducto)->sum('cantidad');
        $vendido  = DetalleVenta::whereHas('venta', fn($q) => $q->whereNotIn('estado', ['Cancelada']))
            ->where('id_producto', $idProducto)
            ->sum('cantidad');
        return max(0, (int) $entradas - (int) $vendido);
    }

    // ── Vista catálogo (cliente) ───────────────────────────────────────────────

    /**
     * GET /ventas/catalogo  — ya existe route('productos.catalogo') que usa ProductoController,
     * por lo que no duplicamos esa ruta.
     */

    // ── Mis compras (cliente) ─────────────────────────────────────────────────

    /**
     * GET /mis-compras
     * Lista las ventas del cliente autenticado, con detalles.
     */
    public function misCompras(): View
    {
        $ventas = Venta::with(['detalles.producto'])
            ->where('id_usuario', Auth::user()->id_usuario)
            ->orderByDesc('id_venta')
            ->get();

        return view('ventas.mis_compras', compact('ventas'));
    }

    // ── Checkout (finalizar compra) ───────────────────────────────────────────

    /**
     * GET /checkout
     * Muestra la página de confirmación del carrito.
     */
    public function checkout(): View
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return view('ventas.checkout', ['carrito' => [], 'total' => 0]);
        }

        $total = array_sum(array_column($carrito, 'subtotal'));

        return view('ventas.checkout', compact('carrito', 'total'));
    }

    /**
     * POST /checkout/confirmar
     * Finaliza la compra:
     *  1. Valida stock de cada ítem del carrito.
     *  2. Crea el registro en venta.
     *  3. Crea un detalle_venta por cada producto.
     *  4. Descuenta el stock (inserta registro negativo en inventario_productos).
     *  5. Limpia el carrito de sesión.
     *
     * Usa DB::transaction para garantizar consistencia entre
     * venta + detalles + inventario.
     */
    public function confirmarCompra(Request $request): RedirectResponse
    {
        $request->validate([
            'metodo_pago' => 'required|string|max:50',
            'notas'       => 'nullable|string|max:500',
        ]);

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('productos.catalogo')
                ->with('alert', [
                    'icon'  => 'warning',
                    'title' => 'Carrito vacío',
                    'text'  => 'Agrega productos antes de continuar.',
                ]);
        }

        // Verificar stock antes de iniciar la transacción
        foreach ($carrito as $item) {
            $stock = $this->stockDisponible((int) $item['id_producto']);
            if ($item['cantidad'] > $stock) {
                $nombre = $item['nombre'];
                return redirect()->route('ventas.checkout')
                    ->with('alert', [
                        'icon'  => 'error',
                        'title' => 'Stock insuficiente',
                        'text'  => "El producto '{$nombre}' solo tiene {$stock} unidades disponibles.",
                    ]);
            }
        }

        $total = array_sum(array_column($carrito, 'subtotal'));
        $user  = Auth::user();

        DB::transaction(function () use ($carrito, $total, $request, $user) {

            // Primer producto del carrito para los campos legacy de venta
            $primerItem = array_values($carrito)[0];

            // ── Paso 1: crear la venta ────────────────────────────────────────
            $venta = Venta::create([
                'fecha'       => now()->toDateString(),
                'cantidad'    => array_sum(array_column($carrito, 'cantidad')),
                'precio'      => $primerItem['precio'],
                'estado'      => 'Pendiente',
                'id_usuario'  => $user->id_usuario,
                'id_producto' => $primerItem['id_producto'],
                'total'       => $total,
                'notas'       => $request->notas,
                'metodo_pago' => $request->metodo_pago,
            ]);

            // ── Paso 2: crear detalles + descontar stock ──────────────────────
            foreach ($carrito as $item) {
                // Detalle de venta
                DetalleVenta::create([
                    'id_venta'       => $venta->id_venta,
                    'id_producto'    => $item['id_producto'],
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento'      => 0,
                ]);

                // Descuento de inventario (valor negativo = salida)
                InventarioProductos::create([
                    'fecha'        => now()->toDateString(),
                    'bodega'       => 'Principal',
                    'id_produccion'=> null,
                    'id_producto'  => $item['id_producto'],
                    'id_usuario'   => $user->id_usuario,
                    'cantidad'     => -(int) $item['cantidad'],
                ]);
            }
        });

        // ── Paso 3: limpiar carrito ───────────────────────────────────────────
        session()->forget('carrito');

        return redirect()->route('ventas.mis-compras')
            ->with('alert', [
                'icon'  => 'success',
                'title' => '¡Pedido Realizado!',
                'text'  => 'Tu pedido fue registrado correctamente. En breve será procesado.',
            ]);
    }

    // ── Administración de ventas (admin) ──────────────────────────────────────

    /**
     * GET /ventas
     * Lista todas las ventas para el administrador.
     */
    public function index(): View
    {
        $ventas = Venta::with(['usuario', 'detalles.producto'])
            ->orderByDesc('id_venta')
            ->get();

        $totalVentas    = $ventas->count();
        $totalRecaudado = $ventas->whereNotIn('estado', ['Cancelada'])->sum('total');
        $pendientes     = $ventas->where('estado', 'Pendiente')->count();
        $completadas    = $ventas->where('estado', 'Completada')->count();

        return view('ventas.index', compact(
            'ventas',
            'totalVentas',
            'totalRecaudado',
            'pendientes',
            'completadas'
        ));
    }

    /**
     * GET /ventas/{venta}
     * Detalle de una venta específica (admin).
     */
    public function show(Venta $venta): View
    {
        $venta->load(['usuario', 'detalles.producto']);
        return view('ventas.show', compact('venta'));
    }

    /**
     * GET /ventas/{venta}/estado/{estado}
     * Cambia el estado de una venta.
     * Estados permitidos: Pendiente, Completada, Cancelada
     */
    public function cambiarEstado(Venta $venta, string $estado): RedirectResponse
    {
        $permitidos = ['Pendiente', 'Completada', 'Cancelada'];

        if (!in_array($estado, $permitidos)) {
            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Estado inválido',
                    'text'  => 'El estado indicado no es válido.',
                ]);
        }

        // Si se cancela una venta que estaba activa, devolver stock
        $estadoAnterior = $venta->estado;

        DB::transaction(function () use ($venta, $estado, $estadoAnterior) {
            $venta->estado = $estado;
            $venta->save();

            // Si se cancela: revertir el descuento de stock (crear entradas positivas)
            if ($estado === 'Cancelada' && $estadoAnterior !== 'Cancelada') {
                foreach ($venta->detalles as $detalle) {
                    InventarioProductos::create([
                        'fecha'        => now()->toDateString(),
                        'bodega'       => 'Principal',
                        'id_produccion'=> null,
                        'id_producto'  => $detalle->id_producto,
                        'id_usuario'   => Auth::user()->id_usuario,
                        'cantidad'     => (int) $detalle->cantidad, // positivo = devolución al stock
                    ]);
                }
            }

            // Si se reactiva una venta cancelada: volver a descontar stock
            if ($estadoAnterior === 'Cancelada' && $estado !== 'Cancelada') {
                foreach ($venta->detalles as $detalle) {
                    // Verificar stock antes
                    $stock = InventarioProductos::where('id_producto', $detalle->id_producto)->sum('cantidad');
                    // Solo descuenta, no bloquea (comportamiento simple)
                    InventarioProductos::create([
                        'fecha'        => now()->toDateString(),
                        'bodega'       => 'Principal',
                        'id_produccion'=> null,
                        'id_producto'  => $detalle->id_producto,
                        'id_usuario'   => Auth::user()->id_usuario,
                        'cantidad'     => -(int) $detalle->cantidad,
                    ]);
                }
            }
        });

        $venta->load('detalles');

        return redirect()->route('ventas.index')
            ->with('alert', [
                'icon'  => 'success',
                'title' => 'Estado Actualizado',
                'text'  => "La venta #" . $venta->id_venta . " fue marcada como {$estado}.",
            ]);
    }
}
