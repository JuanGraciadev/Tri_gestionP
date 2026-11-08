<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresentacionProducto extends Model
{
    protected $table = 'presentaciones_producto';
    protected $primaryKey = 'id_presentacion';
    public $timestamps = true;

    protected $fillable = [
        'id_producto',
        'nombre',
        'unidades_por_paca',
        'precio_paca',
        'materiales_por_unidad',
        'materiales_por_paca',
        'activo',
    ];

    protected $casts = [
        'unidades_por_paca' => 'integer',
        'precio_paca'       => 'decimal:2',
        'materiales_por_unidad' => 'array',
        'materiales_por_paca'   => 'array',
        'activo'            => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
