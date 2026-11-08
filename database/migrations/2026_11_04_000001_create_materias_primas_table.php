<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias_primas', function (Blueprint $table) {
            $table->id('id_materia_prima');
            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 150);
            $table->string('unidad_medida', 50)->default('unidades');
            $table->decimal('stock', 12, 3)->default(0);
            $table->decimal('stock_minimo', 12, 3)->default(10);
            $table->decimal('costo_unitario', 12, 2)->default(0);
            $table->string('bodega', 100)->default('Bodega Principal');
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias_primas');
    }
};
