<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id('id_detalles');

            $table->integer('unidades')->nullable();
            $table->string('tipo_envase', 50)->nullable();
            $table->string('capacidad', 50)->nullable();
            $table->string('proveedor', 100)->nullable();

            $table->unsignedBigInteger('id_lote')->nullable();

            $table->foreign('id_lote')
                ->references('id_lote')
                ->on('lote')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};