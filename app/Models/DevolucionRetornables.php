<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class DevolucionRetornables extends Model
{
    protected $table      = 'devolucion_retornables';
    protected $primaryKey = 'id_retornables';
    public    $timestamps = false;

    protected $fillable = [
        'cantidad',
        'id_producto',
        'id_usuario',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function inventarioMateriaPrima(): HasOne
    {
        return $this->hasOne(InventarioMateriaPrima::class, 'id_retornables', 'id_retornables');
    }

    // ── Business Logic ─────────────────────────────────────────────────────────

    /**
     * Registers a returnable bottle return.
     *
     * Rule:
     * 1. Inserts record into devolucion_retornables.
     * 2. If NOT damaged ($danado = false):
     *    Re-adds empty bottle stock into inventario_materia_prima so it can be refilled/produced.
     * 3. If DAMAGED ($danado = true):
     *    Does NOT add raw material inventory to inventario_materia_prima, ensuring damaged garrafones
     *    leave available inventory permanently.
     */
    public static function registrarDevolucion(int $idUsuario, int $idProducto, int $cantidad, string $bodega = 'Bodega Principal', bool $danado = false): bool
    {
        // Guard: check that the product is marked as returnable
        $producto = Producto::find($idProducto);
        if (!$producto || !$producto->retornable) {
            return false;
        }

        return DB::transaction(function () use ($idUsuario, $idProducto, $cantidad, $bodega, $danado) {
            // 1. Insert return record
            $devolucion = static::create([
                'cantidad'    => $cantidad,
                'id_producto' => $idProducto,
                'id_usuario'  => $idUsuario,
                'fecha'       => now(),
            ]);

            // 2. If damaged, do NOT re-enter stock in raw material inventory
            if ($danado) {
                return true;
            }

            // 3. Find container detail (id_detalles) via produccion link or fallbacks
            $idDetalles = DB::table('produccion as p')
                ->join('inventario_materia_prima as imp', 'p.id_inventario_materia', '=', 'imp.id_inventario_materia')
                ->where('p.id_producto', $idProducto)
                ->value('imp.id_detalles');

            if (!$idDetalles) {
                $idDetalles = DB::table('detalles')
                    ->where('tipo_envase', 'LIKE', '%Garrafon%')
                    ->value('id_detalles');
            }

            if (!$idDetalles) {
                $idDetalles = DB::table('detalles')->value('id_detalles') ?? 1;
            }

            // 4. Insert into inventario_materia_prima (increasing raw material/empty bottle stock)
            InventarioMateriaPrima::create([
                'ingreso'        => $cantidad,
                'fecha'          => now()->toDateString(),
                'bodega'         => $bodega,
                'id_detalles'    => $idDetalles,
                'id_retornables' => $devolucion->id_retornables,
            ]);

            return true;
        });
    }

    /**
     * Gets bottle return balance per client and returnable product.
     * Replicates DevolucionRetornables::obtenerBalancesClientes() from original PHP.
     */
    public static function obtenerBalancesClientes(): array
    {
        $results = DB::select("
            SELECT * FROM (
                SELECT
                    u.id_usuario,
                    u.nombres AS cliente_nombre,
                    u.documento_numero AS cliente_documento,
                    p.id_producto,
                    p.nombre AS producto_nombre,
                    COALESCE((
                        SELECT SUM(dv.cantidad)
                        FROM detalle_venta dv
                        JOIN venta v ON dv.id_venta = v.id_venta
                        JOIN cliente c ON v.id_cliente = c.id_cliente
                        WHERE c.id_usuario = u.id_usuario
                          AND dv.id_producto = p.id_producto
                          AND v.estado = 'Entregado'
                    ), 0) AS total_entregado,
                    COALESCE((
                        SELECT SUM(dr.cantidad)
                        FROM devolucion_retornables dr
                        WHERE dr.id_usuario = u.id_usuario
                          AND dr.id_producto = p.id_producto
                    ), 0) AS total_devuelto
                FROM usuarios u
                JOIN cliente c ON c.id_usuario = u.id_usuario
                CROSS JOIN producto p
                WHERE p.retornable = 1
            ) AS subquery
            WHERE total_entregado > 0 OR total_devuelto > 0
            ORDER BY cliente_nombre ASC
        ");

        $array = json_decode(json_encode($results), true);
        foreach ($array as &$r) {
            $r['en_consumo'] = max(0, intval($r['total_entregado']) - intval($r['total_devuelto']));
        }
        return $array;
    }
}
