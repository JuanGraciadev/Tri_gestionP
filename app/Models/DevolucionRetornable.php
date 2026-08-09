<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DevolucionRetornable extends Model
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
        'fecha'    => 'datetime',
        'cantidad' => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    /**
     * El producto retornable (garrafón) que se devuelve.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * El usuario que registró la devolución.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * El registro de inventario de materia prima creado cuando
     * el garrafón devuelto fue marcado como APTO (vuelve a stock).
     * Si es null, el garrafón fue marcado como DAÑADO.
     */
    public function inventarioReingreso(): HasOne
    {
        return $this->hasOne(InventarioMateriaPrima::class, 'id_retornables', 'id_retornables');
    }
}
