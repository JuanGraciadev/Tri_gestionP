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
        'estado',
        'total',
        'notas',
        'metodo_pago',
        'id_cliente',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'float',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    const ESTADOS_VALIDOS = ['Pendiente', 'En Proceso', 'Entregado', 'Cancelado'];

    public function esEditable(): bool
    {
        return !in_array($this->estado, ['Cancelado', 'Entregado']);
    }

    public function subtotal(): float
    {
        return round($this->total / 1.19, 2);
    }
}
