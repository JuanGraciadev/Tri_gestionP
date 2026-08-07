<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->id('id_venta');

            $table->date('fecha')->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('estado', 50)->nullable();

            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();

            $table->decimal('total', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->string('metodo_pago', 50)->default('Efectivo');

            $table->foreign('id_cliente')
                ->references('id_cliente')
                ->on('cliente')
                ->nullOnDelete();

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};