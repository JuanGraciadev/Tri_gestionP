<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDevolucionRequest;
use App\Models\DevolucionRetornables;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DevolucionController extends Controller
{
    /**
     * GET /devoluciones
     * Lists balances per client, KPIs, and return history.
     */
    public function index(): View
    {
        $balances  = DevolucionRetornables::obtenerBalancesClientes();
        $historial = DevolucionRetornables::with(['producto', 'usuario'])
            ->orderByDesc('fecha')
            ->get();

        $totalEntregados = array_sum(array_column($balances, 'total_entregado'));
        $totalDevueltos  = array_sum(array_column($balances, 'total_devuelto'));
        $totalEnConsumo  = array_sum(array_column($balances, 'en_consumo'));

        return view('devoluciones.index', compact(
            'balances',
            'historial',
            'totalEntregados',
            'totalDevueltos',
            'totalEnConsumo'
        ));
    }

    /**
     * POST /devoluciones
     * Stores a returned bottle transaction and updates inventory.
     */
    public function store(StoreDevolucionRequest $request): RedirectResponse
    {
        $idUsuario  = (int) $request->id_usuario;
        $idProducto = (int) $request->id_producto;
        $cantidad   = (int) $request->cantidad;
        $bodega     = $request->input('bodega', 'Bodega Principal');
        $danado     = $request->input('estado_envase') === 'danado';

        // Check client's consumption balance so they can't return more than delivered
        $balances = DevolucionRetornables::obtenerBalancesClientes();
        $match = null;
        foreach ($balances as $b) {
            if ($b['id_usuario'] == $idUsuario && $b['id_producto'] == $idProducto) {
                $match = $b;
                break;
            }
        }

        $enConsumo = $match ? $match['en_consumo'] : 0;
        if ($cantidad > $enConsumo) {
            return redirect()->route('devoluciones.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Cantidad no permitida',
                    'text'  => "El cliente solo tiene {$enConsumo} envase(s) en consumo pendientes de devolución.",
                ]);
        }

        $exito = DevolucionRetornables::registrarDevolucion($idUsuario, $idProducto, $cantidad, $bodega, $danado);

        if ($exito) {
            $msg = $danado
                ? "Se registraron {$cantidad} envase(s) devueltos como DAÑADOS. Han quedado fuera del stock disponible."
                : "Se han recibido {$cantidad} envase(s) y reingresado a Materia Prima para reutilización.";

            return redirect()->route('devoluciones.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Devolución Registrada!',
                    'text'  => $msg,
                ]);
        }

        return redirect()->route('devoluciones.index')
            ->with('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Hubo un error al intentar procesar la devolución.',
            ]);
    }
}
