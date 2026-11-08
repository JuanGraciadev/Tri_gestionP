<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->string('tipo_recurso', 30); // MATERIA_PRIMA | PRODUCTO_TERMINADO
            $table->unsignedBigInteger('id_recurso');
            $table->string('nombre_recurso', 150);
            $table->string('tipo_movimiento', 20); // ENTRADA | SALIDA | AJUSTE
            $table->decimal('cantidad', 12, 3);
            $table->decimal('stock_anterior', 12, 3)->default(0);
            $table->decimal('stock_nuevo', 12, 3)->default(0);
            $table->string('origen', 30); // PRODUCCION | VENTA | ENTRADA_MANUAL | SALIDA_MANUAL | AJUSTE | DEVOLUCION | INICIAL
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();

            $table->index(['tipo_recurso', 'id_recurso']);
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
