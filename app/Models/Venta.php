<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\StockService;

class Venta extends Model
{
    protected $table      = 'venta';
    protected $primaryKey = 'id_venta';
    public    $timestamps = false;

    protected $fillable = [
        'fecha',
        'estado',
        'estado_pago',
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
    const ESTADOS_PAGO = ['Pendiente', 'Confirmado', 'Rechazado'];

    /**
     * Transiciones de estado permitidas.
     * Clave = estado actual, Valor = estados a los que se puede cambiar.
     */
    const TRANSICIONES_VALIDAS = [
        'Pendiente'  => ['En Proceso', 'Cancelado'],
        'En Proceso' => ['Entregado', 'Cancelado'],
        'Entregado'  => [],   // Estado terminal: no se puede cambiar
        'Cancelado'  => [],   // Estado terminal: no se puede cambiar
    ];

    /**
     * Estados que descuentan stock real del inventario.
     */
    const ESTADOS_QUE_DESCUENTAN = ['En Proceso', 'Entregado'];

    public function esEditable(): bool
    {
        return !in_array($this->estado, ['Cancelado', 'Entregado']);
    }

    /**
     * Verifica si la venta puede cambiar al nuevo estado solicitado.
     */
    public function puedeTransicionarA(string $nuevoEstado): bool
    {
        $permitidos = self::TRANSICIONES_VALIDAS[$this->estado] ?? [];
        return in_array($nuevoEstado, $permitidos);
    }

    /**
     * Verifica si al cambiar a "En Proceso" hay stock suficiente
     * para todos los productos de esta venta.
     *
     * @return array ['ok' => bool, 'mensaje' => string]
     */
    public function validarStockParaProcesar(): array
    {
        if ($this->estado !== 'Pendiente') {
            return ['ok' => true, 'mensaje' => ''];
        }

        // Calcular stock que esta venta consumiría, sumando
        // lo que ya descuentan otras ventas confirmadas + esta venta
        foreach ($this->detalles as $detalle) {
            $stockDisponible = StockService::disponible($detalle->id_producto);

            // StockService::disponible ya resta las ventas En Proceso/Entregado
            // pero NO resta esta venta porque está Pendiente.
            // Al pasar a En Proceso, esta venta pasaría a descontar.
            // Verificamos que el stock actual (sin contar esta venta) cubra la cantidad.
            if ($stockDisponible < $detalle->cantidad) {
                $producto = $detalle->producto;
                $nombre = $producto ? $producto->nombre : "ID {$detalle->id_producto}";
                return [
                    'ok'      => false,
                    'mensaje' => "Stock insuficiente para \"{$nombre}\". Disponible: {$stockDisponible}, solicitado: {$detalle->cantidad}.",
                ];
            }
        }

        return ['ok' => true, 'mensaje' => ''];
    }

    public function subtotal(): float
    {
        return round((float) $this->total, 2);
    }
}
