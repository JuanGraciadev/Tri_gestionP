<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_materia_prima', function (Blueprint $table) {
            $table->id('id_inventario_materia');

            $table->integer('ingreso')->nullable();
            $table->date('fecha')->nullable();
            $table->string('bodega', 100)->nullable();

            $table->unsignedBigInteger('id_detalles')->nullable();
            $table->unsignedBigInteger('id_retornables')->nullable();

            $table->foreign('id_detalles')
                ->references('id_detalles')
                ->on('detalles')
                ->nullOnDelete();

            $table->foreign('id_retornables')
                ->references('id_retornables')
                ->on('devolucion_retornables')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_materia_prima');
    }
};