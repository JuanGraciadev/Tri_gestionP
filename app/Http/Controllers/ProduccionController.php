<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduccionRequest;
use App\Models\InventarioMateriaPrima;
use App\Models\Produccion;
use App\Models\Producto;
use App\Services\ProduccionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProduccionController extends Controller
{
    public function __construct(
        protected ProduccionService $produccionService = new ProduccionService()
    ) {}

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

        // Insumos activos para la receta (etiquetas, tapas, bolsas, etc.)
        $insumos = \App\Models\MateriaPrima::where('estado', true)
            ->orderBy('nombre')
            ->get(['id_materia_prima', 'nombre', 'stock', 'unidad_medida', 'stock_minimo']);

        return view('produccion.index', compact('producciones', 'productos', 'materiaPrima', 'insumos'));
    }

    // ── Crear (Descontar materia prima al INICIAR) ──────────────────────────────

    public function store(StoreProduccionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Parsear receta desde los campos del formulario si viene como arrays separados
        if (empty($data['receta']) && $request->filled('receta_insumo')) {
            $ids       = $request->input('receta_insumo', []);
            $cantidades = $request->input('receta_cantidad', []);
            $receta = [];
            foreach ($ids as $i => $idInsumo) {
                $cant = (float) ($cantidades[$i] ?? 0);
                if ($idInsumo && $cant > 0) {
                    $receta[] = ['id_materia_prima' => (int) $idInsumo, 'cantidad_por_unidad' => $cant];
                }
            }
            $data['receta'] = $receta;
        }

        try {
            $this->produccionService->iniciarProduccion($data, Auth::user()->id_usuario);

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Producción Iniciada',
                    'text'  => 'La producción se ha registrado y los insumos fueron descontados.',
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
        try {
            $this->produccionService->finalizarProduccion($id, Auth::user()->id_usuario);

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => '¡Producción Finalizada!',
                    'text'  => 'La producción fue completada y se sumó al inventario de productos terminados.',
                ]);
        } catch (\RuntimeException $e) {
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Operación no permitida',
                    'text'  => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            Log::error('Error al finalizar producción: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Error al finalizar producción',
                    'text'  => 'No se pudo procesar la operación. Intenta de nuevo.',
                ]);
        }
    }

    // ── Cancelar (Devuelve la materia prima descontada) ────────────────────────

    public function cancelar(int $id): RedirectResponse
    {
        try {
            $this->produccionService->cancelarProduccion($id);

            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'success',
                    'title' => 'Producción Cancelada',
                    'text'  => 'La producción ha sido cancelada y la materia prima fue devuelta al inventario.',
                ]);
        } catch (\RuntimeException $e) {
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Operación no permitida',
                    'text'  => $e->getMessage(),
                ]);
        } catch (\Throwable $e) {
            Log::error('Error al cancelar producción: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('produccion.index')
                ->with('alert', [
                    'icon'  => 'error',
                    'title' => 'Error al cancelar producción',
                    'text'  => 'No se pudo procesar la operación. Intenta de nuevo.',
                ]);
        }
    }
}
