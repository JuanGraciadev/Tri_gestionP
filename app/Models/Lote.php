<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    protected $table = 'lote';
    protected $primaryKey = 'id_lote';
    public $timestamps = false;

    protected $fillable = [
        'codigo_lote',
        'id_usuario',
    ];

    /**
     * The user (admin/trabajador) who registered this batch.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * All detail lines belonging to this batch.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(LoteDetalle::class, 'id_lote', 'id_lote');
    }
}
