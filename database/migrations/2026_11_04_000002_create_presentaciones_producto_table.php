<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentaciones_producto', function (Blueprint $table) {
            $table->id('id_presentacion');
            $table->unsignedBigInteger('id_producto');
            $table->string('nombre', 120);
            $table->integer('unidades_por_paca')->default(15);
            $table->decimal('precio_paca', 12, 2)->default(0);
            $table->json('materiales_por_unidad')->nullable();
            $table->json('materiales_por_paca')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->cascadeOnDelete();

            $table->index('id_producto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentaciones_producto');
    }
};
