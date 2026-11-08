<?php

namespace App\Http\Controllers;

use App\Models\PresentacionProducto;
use App\Models\Producto;
use Illuminate\Http\Request;

class PresentacionProductoController extends Controller
{
    /**
     * Listar presentaciones de un producto o todas.
     */
    public function index(Request $request, ?int $idProducto = null)
    {
        $query = PresentacionProducto::with('producto');

        if ($idProducto) {
            $query->where('id_producto', $idProducto);
        } elseif ($request->filled('id_producto')) {
            $query->where('id_producto', $request->id_producto);
        }

        $presentaciones = $query->orderBy('nombre')->get();

        return response()->json([
            'ok'   => true,
            'data' => $presentaciones,
        ]);
    }

    /**
     * Almacenar una nueva presentacion (paca).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_producto'        => 'required|exists:producto,id_producto',
            'nombre'             => 'required|string|max:120',
            'unidades_por_paca'  => 'required|integer|min:1',
            'precio_paca'        => 'nullable|numeric|min:0',
            'materiales_por_unidad' => 'nullable|array',
            'materiales_por_paca'   => 'nullable|array',
            'activo'             => 'nullable|boolean',
        ]);

        $pres = PresentacionProducto::create($data);

        return response()->json(['ok' => true, 'data' => $pres], 201);
    }

    /**
     * Actualizar una presentacion.
     */
    public function update(Request $request, PresentacionProducto $presentacion)
    {
        $data = $request->validate([
            'nombre'             => 'sometimes|string|max:120',
            'unidades_por_paca'  => 'sometimes|integer|min:1',
            'precio_paca'        => 'sometimes|nullable|numeric|min:0',
            'materiales_por_unidad' => 'sometimes|nullable|array',
            'materiales_por_paca'   => 'sometimes|nullable|array',
            'activo'             => 'sometimes|boolean',
        ]);

        $presentacion->update($data);

        return response()->json(['ok' => true, 'data' => $presentacion->fresh()]);
    }

    /**
     * Eliminar una presentacion.
     */
    public function destroy(PresentacionProducto $presentacion)
    {
        $presentacion->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Listar presentaciones agrupadas por producto (para admin).
     */
    public function porProducto(Producto $producto)
    {
        $presentaciones = $producto->presentaciones()->where('activo', true)->get();

        return response()->json([
            'ok'   => true,
            'data' => $presentaciones,
        ]);
    }
}
