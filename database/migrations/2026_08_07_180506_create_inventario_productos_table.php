<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_productos', function (Blueprint $table) {
            $table->id('id_inventario');

            $table->date('fecha')->nullable();
            $table->string('bodega', 100)->nullable();

            $table->unsignedBigInteger('id_produccion')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();

            $table->integer('cantidad')->default(0);

            $table->foreign('id_produccion')
                ->references('id_produccion')
                ->on('produccion')
                ->nullOnDelete();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->nullOnDelete();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_productos');
    }
};