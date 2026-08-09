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
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'descuento',
    ];

    protected $casts = [
        'precio_unitario' => 'float',
        'descuento'       => 'float',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function subtotal(): float
    {
        return ($this->precio_unitario * $this->cantidad) - ($this->descuento ?? 0);
    }
}
