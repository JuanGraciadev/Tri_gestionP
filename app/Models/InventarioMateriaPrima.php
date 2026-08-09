<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioMateriaPrima extends Model
{
    protected $table = 'inventario_materia_prima';
    protected $primaryKey = 'id_inventario_materia';
    public $timestamps = false;

    protected $fillable = [
        'ingreso',
        'fecha',
        'bodega',
        'id_detalles',
        'id_retornables',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * The detail record that generated this inventory entry.
     */
    public function detalle(): BelongsTo
    {
        return $this->belongsTo(LoteDetalle::class, 'id_detalles', 'id_detalles');
    }

    /**
     * Producciones that consumed this raw-material inventory entry.
     */
    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_inventario_materia', 'id_inventario_materia');
    }
}
