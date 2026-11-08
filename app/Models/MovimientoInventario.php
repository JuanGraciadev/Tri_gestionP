<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = true;

    protected $fillable = [
        'tipo_recurso',
        'id_recurso',
        'nombre_recurso',
        'tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'origen',
        'referencia_id',
        'descripcion',
        'id_usuario',
        'fecha',
    ];

    protected $casts = [
        'cantidad'        => 'decimal:3',
        'stock_anterior'  => 'decimal:3',
        'stock_nuevo'     => 'decimal:3',
        'fecha'           => 'datetime',
    ];

    const TIPO_RECURSO_MATERIA_PRIMA = 'MATERIA_PRIMA';
    const TIPO_RECURSO_PRODUCTO_TERMINADO = 'PRODUCTO_TERMINADO';

    const TIPO_ENTRADA = 'ENTRADA';
    const TIPO_SALIDA = 'SALIDA';
    const TIPO_AJUSTE = 'AJUSTE';

    /**
     * Registra un movimiento de inventario.
     */
    public static function registrar(
        string $tipoRecurso,
        int $idRecurso,
        string $nombreRecurso,
        string $tipoMovimiento,
        float $cantidad,
        float $stockAnterior,
        float $stockNuevo,
        string $origen,
        ?int $idUsuario = null,
        ?int $referenciaId = null,
        ?string $descripcion = null
    ): self {
        return self::create([
            'tipo_recurso'    => $tipoRecurso,
            'id_recurso'      => $idRecurso,
            'nombre_recurso'  => $nombreRecurso,
            'tipo_movimiento' => $tipoMovimiento,
            'cantidad'        => $cantidad,
            'stock_anterior'  => $stockAnterior,
            'stock_nuevo'     => $stockNuevo,
            'origen'          => $origen,
            'id_usuario'      => $idUsuario,
            'referencia_id'   => $referenciaId,
            'descripcion'     => $descripcion,
        ]);
    }
}
