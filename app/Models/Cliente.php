<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table      = 'cliente';
    protected $primaryKey = 'id_cliente';
    public    $timestamps = false;

    protected $fillable = ['id_usuario'];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'id_cliente', 'id_cliente');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Mirror Venta::obtenerOcrearCliente() — find or create the cliente record
     * linked to a given id_usuario.
     */
    public static function firstOrCreateForUser(int $idUsuario): self
    {
        return static::firstOrCreate(['id_usuario' => $idUsuario]);
    }
}
