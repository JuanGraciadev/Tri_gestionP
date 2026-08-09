<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table      = 'detalle_venta';
    protected $primaryKey = 'id_detalle_de_venta';
    public    $timestamps = false;

    protected $fillable = [
        'precio_unitario',
        'descuento',
        'id_venta',
        'cantidad',
        'id_producto',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'descuento'       => 'float',
        'cantidad'        => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
