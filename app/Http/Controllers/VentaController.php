<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ventas\CambiarEstadoPagoRequest;
use App\Http\Requests\Ventas\CambiarEstadoVentaRequest;
use App\Http\Requests\Ventas\CrearPosRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * VentaController (Fachada unificada para compatibilidad)
 * Delega en CarritoController, PosController, PedidoController y MisComprasController.
 */
class VentaController extends Controller
{
    public function __construct(
        protected CarritoController $carritoController = new CarritoController(),
        protected PosController $posController = new PosController(),
        protected PedidoController $pedidoController = new PedidoController(),
        protected MisComprasController $misComprasController = new MisComprasController()
    ) {}

    // ── Carrito ────────────────────────────────────────────────────────────────

    public function agregarAlCarrito(Request $request): JsonResponse
    {
        return $this->carritoController->agregar($request);
    }

    public function obtenerCarrito(): JsonResponse
    {
        return $this->carritoController->obtener();
    }

    public function actualizarCarrito(Request $request): JsonResponse
    {
        return $this->carritoController->actualizar($request);
    }

    public function eliminarDelCarrito(Request $request): JsonResponse
    {
        return $this->carritoController->eliminar($request);
    }

    public function finalizarCompra(Request $request): JsonResponse
    {
        return $this->carritoController->finalizar($request);
    }

    // ── POS ────────────────────────────────────────────────────────────────────

    public function crearPOS(Request $request): RedirectResponse
    {
        // Compatibilidad si llega como Request normal
        $posRequest = $request instanceof CrearPosRequest ? $request : CrearPosRequest::createFrom($request);
        return $this->posController->crear($posRequest);
    }

    public function obtenerFactura(int $idVenta): JsonResponse
    {
        return $this->posController->obtenerFactura($idVenta);
    }

    // ── Pedidos (Admin/Trabajador) ──────────────────────────────────────────────

    public function index(): View
    {
        return $this->pedidoController->index();
    }

    public function cambiarEstado(Request $request): RedirectResponse
    {
        $estadoRequest = $request instanceof CambiarEstadoVentaRequest ? $request : CambiarEstadoVentaRequest::createFrom($request);
        return $this->pedidoController->cambiarEstado($estadoRequest);
    }

    public function cambiarEstadoPago(Request $request): RedirectResponse
    {
        $pagoRequest = $request instanceof CambiarEstadoPagoRequest ? $request : CambiarEstadoPagoRequest::createFrom($request);
        return $this->pedidoController->cambiarEstadoPago($pagoRequest);
    }

    // ── Mis Compras (Cliente) ──────────────────────────────────────────────────

    public function misCompras(): View
    {
        return $this->misComprasController->index();
    }
}
