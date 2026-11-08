<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_devolucion', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_venta');
            $table->integer('cantidad');
            $table->text('notas')->nullable();
            $table->string('estado', 20)->default('pendiente'); // pendiente | procesada | rechazada
            $table->boolean('leida')->default(false);
            $table->timestamps();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->cascadeOnDelete();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->cascadeOnDelete();

            $table->foreign('id_venta')
                ->references('id_venta')
                ->on('venta')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_devolucion');
    }
};
