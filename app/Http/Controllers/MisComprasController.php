<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MisComprasController extends Controller
{
    public function index(): View
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
