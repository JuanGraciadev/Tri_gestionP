<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirige al usuario al dashboard según su rol.
     */
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        return match ((int) $user?->id_rol) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('trabajador.dashboard'),
            3 => redirect()->route('cliente.dashboard'),
            default => redirect()->route('cliente.dashboard'),
        };
    }

    /**
     * Muestra el panel operativo del trabajador.
     */
    public function trabajador(): View
    {
        return view('dashboards.trabajador');
    }

    /**
     * Muestra el panel del cliente con sus métricas y compras recientes.
     */
    public function cliente(): View
    {
        $user = Auth::user();
        $totalPedidos      = Venta::where('id_usuario', $user->id_usuario)->count();
        $pedidosEnProceso  = Venta::where('id_usuario', $user->id_usuario)->where('estado', 'En Proceso')->count();
        $pedidosEntregados = Venta::where('id_usuario', $user->id_usuario)->where('estado', 'Entregado')->count();
        $ultimosPedidos    = Venta::where('id_usuario', $user->id_usuario)->orderByDesc('id_venta')->limit(5)->get();

        return view('dashboards.cliente', compact('totalPedidos', 'pedidosEnProceso', 'pedidosEntregados', 'ultimosPedidos'));
    }
}

