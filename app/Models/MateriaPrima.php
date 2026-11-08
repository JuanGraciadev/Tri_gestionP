<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriaPrima extends Model
{
    protected $table = 'materias_primas';
    protected $primaryKey = 'id_materia_prima';
    public $timestamps = true;

    protected $fillable = [
        'codigo',
        'nombre',
        'unidad_medida',
        'stock',
        'stock_minimo',
        'costo_unitario',
        'bodega',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'stock'         => 'decimal:3',
        'stock_minimo'  => 'decimal:3',
        'costo_unitario'=> 'decimal:2',
        'estado'        => 'boolean',
    ];

    /**
     * Determina si el insumo está agotado.
     */
    public function getAgotadoAttribute(): bool
    {
        return (float) $this->stock <= 0;
    }

    /**
     * Determina si el insumo está bajo el mínimo.
     */
    public function getStockBajoAttribute(): bool
    {
        return (float) $this->stock > 0 && (float) $this->stock <= (float) $this->stock_minimo;
    }

    /**
     * Obtiene alertas de stock: agotados y bajo mínimo.
     */
    public static function obtenerAlertas(): array
    {
        $agotados = self::where('estado', true)->where('stock', '<=', 0)->get();
        $stockBajo = self::where('estado', true)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->get();

        return [
            'hayAlertas'   => $agotados->isNotEmpty() || $stockBajo->isNotEmpty(),
            'agotados'     => $agotados,
            'stockBajo'    => $stockBajo,
        ];
    }
}
