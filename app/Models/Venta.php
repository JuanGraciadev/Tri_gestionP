<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table      = 'venta';
    protected $primaryKey = 'id_venta';
    public    $timestamps = false;

    protected $fillable = [
        'fecha',
        'cantidad',
        'precio',
        'estado',
        'id_cliente',
        'id_usuario',
        'id_producto',
        'total',
        'notas',
        'metodo_pago',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'precio'   => 'decimal:2',
        'total'    => 'decimal:2',
        'cantidad' => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}
