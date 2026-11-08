<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class MateriaPrimaController extends Controller
{
    /**
     * Listar insumos con filtros opcionales.
     */
    public function index(Request $request)
    {
        $query = MateriaPrima::query();

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                     ->orWhere('codigo', 'like', "%{$q}%")
                     ->orWhere('bodega', 'like', "%{$q}%");
            });
        }

        if ($request->boolean('solo_activos')) {
            $query->where('estado', true);
        }

        $materias = $query->orderBy('nombre')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'ok'    => true,
                'data'  => $materias,
                'total' => $materias->count(),
            ]);
        }

        return view('materias-primas.index', compact('materias'));
    }

    /**
     * Almacenar un nuevo insumo.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'        => 'nullable|string|max:50',
            'nombre'        => 'required|string|max:150',
            'unidad_medida' => 'nullable|string|max:50',
            'stock'         => 'nullable|numeric|min:0',
            'stock_minimo'  => 'nullable|numeric|min:0',
            'costo_unitario'=> 'nullable|numeric|min:0',
            'bodega'        => 'nullable|string|max:100',
            'descripcion'   => 'nullable|string',
            'estado'        => 'nullable|boolean',
        ]);

        $mp = MateriaPrima::create(array_merge([
            'stock'        => 0,
            'stock_minimo' => 10,
            'estado'       => true,
        ], $data));

        // Registrar movimiento inicial si hay stock
        if ((float) $mp->stock > 0) {
            MovimientoInventario::registrar(
                MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                $mp->id_materia_prima,
                $mp->nombre,
                MovimientoInventario::TIPO_ENTRADA,
                (float) $mp->stock,
                0,
                (float) $mp->stock,
                'INICIAL',
                $request->user()->id_usuario ?? 1,
                null,
                'Stock inicial al crear insumo'
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'data' => $mp], 201);
        }

        return redirect()->back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Insumo creado',
            'text'  => "El insumo '{$mp->nombre}' se registró correctamente.",
        ]);
    }

    /**
     * Actualizar un insumo.
     */
    public function update(Request $request, MateriaPrima $materiaPrima)
    {
        $data = $request->validate([
            'codigo'        => 'nullable|string|max:50',
            'nombre'        => 'required|string|max:150',
            'unidad_medida' => 'nullable|string|max:50',
            'stock'         => 'nullable|numeric|min:0',
            'stock_minimo'  => 'nullable|numeric|min:0',
            'costo_unitario'=> 'nullable|numeric|min:0',
            'bodega'        => 'nullable|string|max:100',
            'descripcion'   => 'nullable|string',
            'estado'        => 'nullable|boolean',
        ]);

        $stockAnterior = (float) $materiaPrima->stock;
        $stockNuevo = (float) ($data['stock'] ?? $stockAnterior);
        $materiaPrima->update($data);

        // Si el stock cambió, registrar ajuste
        if ($stockNuevo !== $stockAnterior) {
            $diferencia = $stockNuevo - $stockAnterior;
            MovimientoInventario::registrar(
                MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                $materiaPrima->id_materia_prima,
                $materiaPrima->nombre,
                $diferencia > 0 ? MovimientoInventario::TIPO_ENTRADA : MovimientoInventario::TIPO_AJUSTE,
                abs($diferencia),
                $stockAnterior,
                $stockNuevo,
                abs($diferencia) < ($stockAnterior * 0.01) ? 'AJUSTE' : 'ENTRADA_MANUAL',
                $request->user()->id_usuario ?? 1,
                null,
                'Actualización de insumo'
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'data' => $materiaPrima->fresh()]);
        }

        return redirect()->back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Insumo actualizado',
            'text'  => 'Los cambios se guardaron correctamente.',
        ]);
    }

    /**
     * Eliminar un insumo.
     */
    public function destroy(Request $request, MateriaPrima $materiaPrima)
    {
        $materiaPrima->delete();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Insumo eliminado',
            'text'  => 'El insumo se eliminó del sistema.',
        ]);
    }

    /**
     * Registrar entrada manual de stock a un insumo.
     */
    public function registrarEntrada(Request $request)
    {
        $data = $request->validate([
            'id_materia_prima' => 'required|exists:materias_primas,id_materia_prima',
            'cantidad'         => 'required|numeric|min:0.001',
            'bodega'           => 'nullable|string|max:100',
            'descripcion'     => 'nullable|string',
        ]);

        $mp = MateriaPrima::findOrFail($data['id_materia_prima']);
        $stockAnterior = (float) $mp->stock;
        $cantidad = (float) $data['cantidad'];
        $stockNuevo = $stockAnterior + $cantidad;

        $mp->update(['stock' => $stockNuevo]);
        if (!empty($data['bodega'])) {
            $mp->update(['bodega' => $data['bodega']]);
        }

        MovimientoInventario::registrar(
            MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
            $mp->id_materia_prima,
            $mp->nombre,
            MovimientoInventario::TIPO_ENTRADA,
            $cantidad,
            $stockAnterior,
            $stockNuevo,
            'ENTRADA_MANUAL',
            $request->user()->id_usuario ?? 1,
            null,
            $data['descripcion'] ?? 'Entrada manual de insumos'
        );

        if ($request->wantsJson()) {
            return response()->json([
                'ok'   => true,
                'data' => $mp->fresh(),
                'message' => "Se agregaron {$cantidad} {$mp->unidad_medida} a {$mp->nombre}",
            ]);
        }

        return redirect()->back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Entrada registrada',
            'text'  => "Se agregaron {$cantidad} {$mp->unidad_medida} a {$mp->nombre}.",
        ]);
    }

    /**
     * Obtener alertas de stock bajo y agotados.
     */
    public function alertas()
    {
        $alertas = MateriaPrima::obtenerAlertas();

        return response()->json([
            'ok'         => true,
            'hayAlertas' => $alertas['hayAlertas'],
            'agotados'   => $alertas['agotados'],
            'stockBajo'  => $alertas['stockBajo'],
        ]);
    }

    /**
     * Toggle estado del insumo.
     */
    public function toggleEstado(MateriaPrima $materiaPrima)
    {
        $materiaPrima->update(['estado' => !$materiaPrima->estado]);

        return redirect()->back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Estado actualizado',
            'text'  => 'El estado del insumo fue modificado.',
        ]);
    }
}
