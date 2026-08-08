<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LoteDetalle extends Model
{
    protected $table = 'detalles';
    protected $primaryKey = 'id_detalles';
    public $timestamps = false;

    protected $fillable = [
        'unidades',
        'tipo_envase',
        'capacidad',
        'proveedor',
        'id_lote',
    ];

    protected $casts = [
        'unidades' => 'integer',
    ];

    /**
     * The batch this detail line belongs to.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * The raw-material inventory record auto-created when this detail is saved.
     */
    public function inventarioMateriaPrima(): HasOne
    {
        return $this->hasOne(InventarioMateriaPrima::class, 'id_detalles', 'id_detalles');
    }
}
