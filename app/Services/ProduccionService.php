<?php

namespace App\Services;

use App\Models\InventarioMateriaPrima;
use App\Models\InventarioProductos;
use App\Models\MateriaPrima;
use App\Models\MovimientoInventario;
use App\Models\Produccion;
use Illuminate\Support\Facades\DB;

class ProduccionService
{
    /**
     * Inicia una orden de producción descontando la materia prima en transacción atómica.
     *
     * @param array $data
     * @param int $idUsuario
     * @return Produccion
     * @throws \RuntimeException
     */
    public function iniciarProduccion(array $data, int $idUsuario): Produccion
    {
        return DB::transaction(function () use ($data, $idUsuario) {

            // ── 1. Descontar inventario_materia_prima (envases/garrafones) ──────────
            if (!empty($data['id_inventario_materia'])) {
                /** @var InventarioMateriaPrima $mp */
                $mp = InventarioMateriaPrima::where('id_inventario_materia', $data['id_inventario_materia'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $mp->ingreso < (int) $data['cantidad']) {
                    throw new \RuntimeException("Materia prima insuficiente en el lote seleccionado. Disponible: {$mp->ingreso}, requerido: {$data['cantidad']}.");
                }

                $stockAnterior = (int) $mp->ingreso;
                $mp->ingreso   = (int) $mp->ingreso - (int) $data['cantidad'];
                $mp->save();

                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                    $mp->id_inventario_materia,
                    ($mp->detalle->tipo_envase ?? 'Materia Prima') . ' - Lote ' . ($mp->detalle->lote->codigo_lote ?? ''),
                    MovimientoInventario::TIPO_SALIDA,
                    (float) $data['cantidad'],
                    (float) $stockAnterior,
                    (float) $mp->ingreso,
                    'PRODUCCION',
                    $idUsuario,
                    null,
                    'Salida de materia prima al iniciar producción'
                );
            }

            // ── 2. Descontar insumos (etiquetas, tapas, bolsas) según receta ───────
            $receta = $data['receta'] ?? [];   // [ ['id_materia_prima'=>X,'cantidad_por_unidad'=>Y], ... ]
            $materialesConsumidos = [];

            foreach ($receta as $item) {
                $idInsumo  = (int) ($item['id_materia_prima'] ?? 0);
                $cantPorUd = (float) ($item['cantidad_por_unidad'] ?? 0);

                if ($idInsumo <= 0 || $cantPorUd <= 0) continue;

                $totalConsumo = $cantPorUd * (int) $data['cantidad'];

                /** @var MateriaPrima $insumo */
                $insumo = MateriaPrima::where('id_materia_prima', $idInsumo)
                    ->where('estado', true)
                    ->lockForUpdate()
                    ->first();

                if (!$insumo) continue;

                if ((float) $insumo->stock < $totalConsumo) {
                    throw new \RuntimeException(
                        "Stock insuficiente de \"{$insumo->nombre}\". Disponible: {$insumo->stock} {$insumo->unidad_medida}, requerido: {$totalConsumo}."
                    );
                }

                $stockAnteriorInsumo = (float) $insumo->stock;
                $insumo->stock       = $stockAnteriorInsumo - $totalConsumo;
                $insumo->save();

                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                    $insumo->id_materia_prima,
                    $insumo->nombre,
                    MovimientoInventario::TIPO_SALIDA,
                    $totalConsumo,
                    $stockAnteriorInsumo,
                    (float) $insumo->stock,
                    'PRODUCCION',
                    $idUsuario,
                    null,
                    "Consumo en producción: {$cantPorUd} × {$data['cantidad']} unidades"
                );

                $materialesConsumidos[] = [
                    'id_materia_prima'   => $idInsumo,
                    'nombre'             => $insumo->nombre,
                    'cantidad_por_unidad'=> $cantPorUd,
                    'total_consumido'    => $totalConsumo,
                    'unidad_medida'      => $insumo->unidad_medida,
                ];
            }

            // ── 3. Crear la orden de producción ─────────────────────────────────────
            return Produccion::create([
                'lote_produccion'       => $data['lote_produccion'],
                'cantidad'              => $data['cantidad'],
                'descripcion'           => $data['descripcion'] ?? null,
                'id_usuario'            => $idUsuario,
                'id_producto'           => $data['id_producto'],
                'id_inventario_materia' => $data['id_inventario_materia'] ?? null,
                'estado'                => 'En Producción',
                'materiales_consumidos' => !empty($materialesConsumidos) ? $materialesConsumidos : null,
            ]);
        });
    }

    /**
     * Finaliza la producción y suma al inventario de productos terminados.
     *
     * @param int $idProduccion
     * @param int $idUsuario
     * @return Produccion
     * @throws \RuntimeException
     */
    public function finalizarProduccion(int $idProduccion, int $idUsuario): Produccion
    {
        return DB::transaction(function () use ($idProduccion, $idUsuario) {
            /** @var Produccion|null $produccion */
            $produccion = Produccion::where('id_produccion', $idProduccion)->lockForUpdate()->firstOrFail();

            if ($produccion->estaFinalizada()) {
                throw new \RuntimeException('Esta producción ya fue finalizada anteriormente.');
            }

            if ($produccion->estado === 'Cancelada') {
                throw new \RuntimeException('Esta producción se encuentra cancelada y no puede ser finalizada.');
            }

            $produccion->estado = 'Finalizada';
            $produccion->save();

            $yaRegistrado = InventarioProductos::where('id_produccion', $produccion->id_produccion)->exists();
            if (!$yaRegistrado) {
                // Calcular stock anterior del producto
                $stockAnterior = (int) InventarioProductos::where('id_producto', $produccion->id_producto)->sum('cantidad');

                $inv = InventarioProductos::create([
                    'fecha'         => now(),
                    'bodega'        => 'Principal',
                    'id_produccion' => $produccion->id_produccion,
                    'id_producto'   => $produccion->id_producto,
                    'id_usuario'    => $idUsuario,
                    'cantidad'      => $produccion->cantidad,
                ]);

                // Registrar movimiento de auditoría (ENTRADA de producto terminado)
                $stockNuevo = $stockAnterior + (int) $produccion->cantidad;
                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_PRODUCTO_TERMINADO,
                    $produccion->id_producto,
                    $produccion->producto->nombre ?? 'Producto #' . $produccion->id_producto,
                    MovimientoInventario::TIPO_ENTRADA,
                    (float) $produccion->cantidad,
                    (float) $stockAnterior,
                    (float) $stockNuevo,
                    'PRODUCCION',
                    $idUsuario,
                    $produccion->id_produccion,
                    'Entrada de producto terminado al finalizar producción'
                );

                // Invalidar caché de stock del producto
                \App\Services\StockService::invalidarCache($produccion->id_producto);
            }

            return $produccion;
        });
    }

    /**
     * Cancela la producción y devuelve la materia prima descontada al inventario.
     *
     * @param int $idProduccion
     * @return Produccion
     * @throws \RuntimeException
     */
    public function cancelarProduccion(int $idProduccion): Produccion
    {
        return DB::transaction(function () use ($idProduccion) {
            /** @var Produccion|null $produccion */
            $produccion = Produccion::where('id_produccion', $idProduccion)->lockForUpdate()->firstOrFail();

            if ($produccion->estado !== 'En Producción') {
                throw new \RuntimeException("Solo se pueden cancelar producciones en estado \"En Producción\". Estado actual: {$produccion->estado}.");
            }

            // ── 1. Devolver inventario_materia_prima (envases/garrafones) ────────────
            if ($produccion->id_inventario_materia) {
                $mp = InventarioMateriaPrima::where('id_inventario_materia', $produccion->id_inventario_materia)
                    ->lockForUpdate()
                    ->first();

                if ($mp) {
                    $stockAnterior = (int) $mp->ingreso;
                    $mp->ingreso   = (int) $mp->ingreso + (int) $produccion->cantidad;
                    $mp->save();

                    MovimientoInventario::registrar(
                        MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                        $mp->id_inventario_materia,
                        ($mp->detalle->tipo_envase ?? 'Materia Prima') . ' - Lote ' . ($mp->detalle->lote->codigo_lote ?? ''),
                        MovimientoInventario::TIPO_ENTRADA,
                        (float) $produccion->cantidad,
                        (float) $stockAnterior,
                        (float) $mp->ingreso,
                        'PRODUCCION',
                        $produccion->id_usuario,
                        $produccion->id_produccion,
                        'Devolución de materia prima por cancelación de producción'
                    );
                }
            }

            // ── 2. Devolver insumos (etiquetas, tapas, bolsas) si se consumieron ────
            $materiales = $produccion->materiales_consumidos ?? [];
            foreach ($materiales as $mat) {
                $idInsumo       = (int) ($mat['id_materia_prima'] ?? 0);
                $totalConsumido = (float) ($mat['total_consumido'] ?? 0);

                if ($idInsumo <= 0 || $totalConsumido <= 0) continue;

                $insumo = MateriaPrima::where('id_materia_prima', $idInsumo)->lockForUpdate()->first();
                if (!$insumo) continue;

                $stockAnterioInsumo = (float) $insumo->stock;
                $insumo->stock      = $stockAnterioInsumo + $totalConsumido;
                $insumo->save();

                MovimientoInventario::registrar(
                    MovimientoInventario::TIPO_RECURSO_MATERIA_PRIMA,
                    $insumo->id_materia_prima,
                    $insumo->nombre,
                    MovimientoInventario::TIPO_ENTRADA,
                    $totalConsumido,
                    $stockAnterioInsumo,
                    (float) $insumo->stock,
                    'PRODUCCION',
                    $produccion->id_usuario,
                    $produccion->id_produccion,
                    'Devolución de insumo por cancelación de producción'
                );
            }

            $produccion->estado = 'Cancelada';
            $produccion->save();

            return $produccion;
        });
    }
}

