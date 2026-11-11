<?php

namespace App\Services;

use App\Models\InventarioMateriaPrima;
use App\Models\InventarioProductos;
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

            return Produccion::create([
                'lote_produccion'       => $data['lote_produccion'],
                'cantidad'              => $data['cantidad'],
                'descripcion'           => $data['descripcion'] ?? null,
                'id_usuario'            => $idUsuario,
                'id_producto'           => $data['id_producto'],
                'id_inventario_materia' => $data['id_inventario_materia'] ?? null,
                'estado'                => 'En Producción',
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
                InventarioProductos::create([
                    'fecha'         => now(),
                    'bodega'        => 'Principal',
                    'id_produccion' => $produccion->id_produccion,
                    'id_producto'   => $produccion->id_producto,
                    'id_usuario'    => $idUsuario,
                    'cantidad'      => $produccion->cantidad,
                ]);
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

            return $produccion;
        });
    }
}

