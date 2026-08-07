<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id('id_detalle_de_venta');

            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->float('descuento')->nullable();

            $table->unsignedBigInteger('id_venta')->nullable();
            $table->integer('cantidad')->default(1);
            $table->unsignedBigInteger('id_producto')->default(0);

            $table->foreign('id_venta')
                ->references('id_venta')
                ->on('venta')
                ->nullOnDelete();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};