<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduccionRequest;
use App\Models\InventarioMateriaPrima;
use App\Models\InventarioProductos;
use App\Models\Produccion;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProduccionController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $producciones = Produccion::with(['producto', 'usuario'])
            ->orderByDesc('id_produccion')
            ->paginate(12);

        $productos    = Producto::where('estado', 1)->orderBy('nombre')->get();
        $materiaPrima = InventarioMateriaPrima::with(['detalle.lote', 'retornable'])
            ->orderByDesc('id_inventario_materia')
            ->get();

        return view('produccion.index', compact('producciones', 'productos', 'materiaPrima'));
    }

    // ── Crear (Descontar materia prima al INICIAR) ──────────────────────────────

    public function store(StoreProduccionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                if (!empty($data['id_inventario_materia'])) {
                    /** @var InventarioMateriaPrima $mp */
                    $mp = InventarioMateriaPrima::where('id_inventario_materia', $data['id_inventario_materia'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ((int) $mp->ingreso < (int) $data['cantidad']) {
                        throw new \RuntimeException("Materia prima insuficiente en el lote seleccionado. Disponible: {$mp->ingreso}, requerido: {$data['cantidad']}.");
                    }

                    $mp->ingreso = (int) $mp->ingreso - (int) $data['cantidad'];
                    $mp->save();
                }

                Produccion::create([
                    'lote_produccion'       => $data['lote_produccion'],
                    'cantidad'              => $data['cantidad'],
                    'descripcion'           => $data['descripcion'] ?? null,
                    'id_usuario'            => Auth::user()->id_usuario,
                    'id_producto'           => $data['id_producto'],
                    'id_inventario_materia' => $data['id_inventario_materia'] ?? null,
                    'estado'                => 'En Producción',
                ]);
            });

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Producción Iniciada',
                    'text'  => 'La producción se ha registrado y la materia prima fue descontada.',
                ]);
        } catch (\RuntimeException $e) {
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Stock insuficiente',
                    'text'  => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            Log::error('Error al iniciar producción: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Error al iniciar producción',
                    'text'  => 'No se pudo procesar la operación. Intenta de nuevo.',
                ]);
        }
    }

    // ── Finalizar (Suma a inventario de productos sin volver a descontar) ───────

    public function finalizar(int $id): RedirectResponse
    {
        return DB::transaction(function () use ($id) {
            /** @var Produccion|null $produccion */
            $produccion = Produccion::where('id_produccion', $id)->lockForUpdate()->firstOrFail();

            // Guard contra doble finalización
            if ($produccion->estaFinalizada()) {
                return redirect()->route('produccion.index')
                    ->with('alert', [
                        'icon'  => 'error',
                        'title' => 'Operación no permitida',
                        'text'  => 'Esta producción ya fue finalizada anteriormente.',
                    ]);
            }

            if ($produccion->estado === 'Cancelada') {
                return redirect()->route('produccion.index')
                    ->with('alert', [
                        'icon'  => 'error',
                        'title' => 'Operación no permitida',
                        'text'  => 'Esta producción se encuentra cancelada y no puede ser finalizada.',
                    ]);
            }

            // Step 1 – update state
            $produccion->estado = 'Finalizada';
            $produccion->save();

            // Step 2 – register finished-goods inventory entry
            $yaRegistrado = InventarioProductos::where('id_produccion', $produccion->id_produccion)->exists();

            if (!$yaRegistrado) {
                InventarioProductos::create([
                    'fecha'         => now(),
                    'bodega'        => 'Principal',
                    'id_produccion' => $produccion->id_produccion,
                    'id_producto'   => $produccion->id_producto,
                    'id_usuario'    => Auth::user()->id_usuario,
                    'cantidad'      => $produccion->cantidad,
                ]);
            }

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Producción Finalizada!',
                    'text'  => 'La producción fue completada y se sumó al inventario de productos terminados.',
                ]);
        });
    }

    // ── Cancelar (Devuelve la materia prima descontada) ────────────────────────

    public function cancelar(int $id): RedirectResponse
    {
        return DB::transaction(function () use ($id) {
            /** @var Produccion|null $produccion */
            $produccion = Produccion::where('id_produccion', $id)->lockForUpdate()->firstOrFail();

            if ($produccion->estado !== 'En Producción') {
                return redirect()->route('produccion.index')
                    ->with('alert', [
                        'icon'  => 'error',
                        'title' => 'Operación no permitida',
                        'text'  => "Solo se pueden cancelar producciones en estado \"En Producción\". Estado actual: {$produccion->estado}.",
                    ]);
            }

            // Devolver materia prima si estaba vinculada
            if ($produccion->id_inventario_materia) {
                $mp = InventarioMateriaPrima::where('id_inventario_materia', $produccion->id_inventario_materia)
                    ->lockForUpdate()
                    ->first();

                if ($mp) {
                    $mp->ingreso = (int) $mp->ingreso + (int) $produccion->cantidad;
                    $mp->save();
                }
            }

            $produccion->estado = 'Cancelada';
            $produccion->save();

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Producción Cancelada',
                    'text'  => 'La producción ha sido cancelada y la materia prima fue devuelta al inventario.',
                ]);
        });
    }
}
