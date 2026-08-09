<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioProductos extends Model
{
    protected $table      = 'inventario_productos';
    protected $primaryKey = 'id_inventario';
    public    $timestamps = false;

    protected $fillable = [
        'fecha',
        'bodega',
        'id_produccion',
        'id_producto',
        'id_usuario',
        'cantidad',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function produccion(): BelongsTo
    {
        return $this->belongsTo(Produccion::class, 'id_produccion', 'id_produccion');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
