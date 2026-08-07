<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produccion', function (Blueprint $table) {
            $table->id('id_produccion');

            $table->string('lote_produccion', 100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('estado', 50)->nullable();
            $table->text('descripcion')->nullable();

            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->unsignedBigInteger('id_inventario_materia')->nullable();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->nullOnDelete();

            $table->foreign('id_inventario_materia')
                ->references('id_inventario_materia')
                ->on('inventario_materia_prima')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produccion');
    }
};