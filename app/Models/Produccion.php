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
        'modalidad',
        'id_presentacion',
        'nombre_presentacion',
        'cantidad_pacas',
        'unidades_por_paca',
        'unidades_fabricadas',
        'materiales_consumidos',
    ];

    protected $casts = [
        'cantidad'             => 'integer',
        'cantidad_pacas'       => 'integer',
        'unidades_por_paca'    => 'integer',
        'unidades_fabricadas'  => 'integer',
        'materiales_consumidos'=> 'array',
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
