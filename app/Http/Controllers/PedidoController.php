<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\CambiarEstadoPagoRequest;
use App\Http\Requests\Ventas\CambiarEstadoVentaRequest;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Services\StockService;
use App\Services\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;

class PedidoController extends Controller
{
    public function __construct(
        protected VentaService $ventaService = new VentaService()
    ) {}

    public function index(): View
    {
        $ventas = Venta::with(['cliente.usuario', 'usuario', 'detalles.producto'])
            ->orderByDesc('id_venta')
            ->paginate(15);

        $clientes = User::where('id_rol', 3)->where('estado', 1)->orderBy('nombres')->get();
        $productos = Producto::where('estado', 1)->orderBy('nombre')->get()->map(function ($p) {
            $p->stock = StockService::disponible($p->id_producto);
            return $p;
        });

        $totalIngresos     = Venta::where('estado', '!=', 'Cancelado')->sum('total');
        $pedidosPendientes = Venta::where('estado', 'Pendiente')->count();
        $pedidosEnProceso  = Venta::where('estado', 'En Proceso')->count();
        $pedidosEntregados = Venta::where('estado', 'Entregado')->count();
        $pedidosCancelados = Venta::where('estado', 'Cancelado')->count();

        $stats = [
            'Pendiente'      => $pedidosPendientes,
            'En Proceso'     => $pedidosEnProceso,
            'Entregado'      => $pedidosEntregados,
            'Cancelado'      => $pedidosCancelados,
            'total_ingresos' => $totalIngresos,
        ];

        return view('ventas.pedidos', compact(
            'ventas',
            'clientes',
            'productos',
            'stats'
        ));
    }

    public function show(int $id): View
    {
        $venta = Venta::with(['detalles.producto', 'cliente.usuario', 'usuario'])->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }

    public function cambiarEstado(CambiarEstadoVentaRequest $request): RedirectResponse
    {
        $idVenta     = (int) $request->input('id_venta');
        $nuevoEstado = $request->input('estado');

        try {
            $this->ventaService->cambiarEstado($idVenta, $nuevoEstado);

            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Estado Actualizado',
                    'text'  => "El pedido #{$idVenta} pasó a \"{$nuevoEstado}\".",
                ]);
        } catch (RuntimeException $e) {
            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Operación no permitida',
                    'text'  => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado de venta: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Error al cambiar estado',
                    'text'  => 'No se pudo procesar la operación. Intenta de nuevo.',
                ]);
        }
    }

    public function cambiarEstadoPago(CambiarEstadoPagoRequest $request): RedirectResponse
    {
        $idVenta         = (int) $request->input('id_venta');
        $nuevoEstadoPago = $request->input('estado_pago');

        try {
            $this->ventaService->cambiarEstadoPago($idVenta, $nuevoEstadoPago);

            $mensaje = "El pago del pedido #{$idVenta} fue marcado como \"{$nuevoEstadoPago}\".";
            if ($nuevoEstadoPago === 'Rechazado') {
                $mensaje .= ' El pedido fue cancelado automáticamente.';
            }

            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Estado de Pago Actualizado',
                    'text'  => $mensaje,
                ]);
        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado de pago: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('ventas.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => 'No se pudo actualizar el estado de pago. Intenta de nuevo.',
                ]);
        }
    }
}
