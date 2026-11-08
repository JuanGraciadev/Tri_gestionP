<?php

namespace App\Http\Controllers;

use App\Models\SolicitudDevolucion;
use App\Models\DevolucionRetornables;
use Illuminate\Http\Request;

class SolicitudDevolucionController extends Controller
{
    /**
     * Cliente solicita devolución de envase.
     */
    public function solicitar(Request $request)
    {
        $data = $request->validate([
            'id_producto' => 'required|exists:producto,id_producto',
            'id_venta'    => 'required|exists:venta,id_venta',
            'cantidad'    => 'required|integer|min:1',
            'notas'       => 'nullable|string',
        ]);

        // Verificar que no exista una solicitud pendiente para la misma venta+producto
        $existente = SolicitudDevolucion::where('id_venta', $data['id_venta'])
            ->where('id_producto', $data['id_producto'])
            ->where('estado', 'pendiente')
            ->exists();

        if ($existente) {
            return response()->json([
                'ok'      => false,
                'message' => 'Ya tienes una solicitud pendiente para este producto.',
            ], 422);
        }

        $solicitud = SolicitudDevolucion::create(array_merge($data, [
            'id_usuario' => $request->user()->id_usuario,
            'estado'     => 'pendiente',
            'leida'      => false,
        ]));

        return response()->json([
            'ok'   => true,
            'data' => $solicitud,
            'message' => 'Solicitud enviada correctamente.',
        ], 201);
    }

    /**
     * Admin/Trabajador lista todas las solicitudes.
     */
    public function index(Request $request)
    {
        $query = SolicitudDevolucion::with(['usuario', 'producto', 'venta']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $solicitudes = $query->orderByDesc('id_solicitud')->get();

        return response()->json([
            'ok'          => true,
            'data'        => $solicitudes,
            'noLeidas'    => SolicitudDevolucion::where('leida', false)->count(),
        ]);
    }

    /**
     * Cliente ve sus propias solicitudes.
     */
    public function misSolicitudes(Request $request)
    {
        $solicitudes = SolicitudDevolucion::with(['producto', 'venta'])
            ->where('id_usuario', $request->user()->id_usuario)
            ->orderByDesc('id_solicitud')
            ->get();

        return response()->json([
            'ok'          => true,
            'solicitudes' => $solicitudes,
        ]);
    }

    /**
     * Admin procesa o rechaza una solicitud.
     */
    public function procesar(Request $request, SolicitudDevolucion $solicitud)
    {
        $data = $request->validate([
            'accion' => 'required|in:procesar,rechazar',
        ]);

        if ($data['accion'] === 'procesar') {
            // Procesar: registrar devolución del envase
            DevolucionRetornables::registrarDevolucion(
                $solicitud->id_usuario,
                $solicitud->id_producto,
                $solicitud->cantidad
            );
            $solicitud->update(['estado' => 'procesada', 'leida' => true]);
        } else {
            $solicitud->update(['estado' => 'rechazada', 'leida' => true]);
        }

        return response()->json([
            'ok'      => true,
            'data'    => $solicitud->fresh(),
            'message' => $data['accion'] === 'procesar'
                ? 'Solicitud procesada. El envase se reingresó a bodega.'
                : 'Solicitud rechazada.',
        ]);
    }

    /**
     * Marcar solicitudes como leídas (para notificaciones).
     */
    public function marcarLeidas()
    {
        SolicitudDevolucion::where('leida', false)->update(['leida' => true]);

        return response()->json(['ok' => true]);
    }
}
