<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'precio',
        'img',
        'id_usuario',
        'id_categoria',
        'estado',
        'retornable',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
        'retornable' => 'boolean',
    ];

    /**
     * Relationship: Product belongs to a Category.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Relationship: Product belongs to a User (Creator/Admin).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
