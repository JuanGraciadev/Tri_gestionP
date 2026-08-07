<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devolucion_retornables', function (Blueprint $table) {
            $table->id('id_retornables');

            $table->integer('cantidad')->nullable();

            $table->unsignedBigInteger('id_producto')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();

            $table->dateTime('fecha')->useCurrent();

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
        Schema::dropIfExists('devolucion_retornables');
    }
};