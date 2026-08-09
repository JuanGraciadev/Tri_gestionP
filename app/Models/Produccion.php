<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Produccion extends Model
{
    protected $table      = 'produccion';
    protected $primaryKey = 'id_produccion';
    public    $timestamps = false;

    protected $fillable = [
        'lote_produccion',
        'cantidad',
        'estado',
        'descripcion',
        'id_usuario',
        'id_producto',
        'id_inventario_materia',
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

    public function inventarioMateriaPrima(): BelongsTo
    {
        return $this->belongsTo(InventarioMateriaPrima::class, 'id_inventario_materia', 'id_inventario_materia');
    }

    public function inventarioProducto(): HasOne
    {
        return $this->hasOne(InventarioProductos::class, 'id_produccion', 'id_produccion');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function estaFinalizada(): bool
    {
        return $this->estado === 'Finalizada';
    }
}
