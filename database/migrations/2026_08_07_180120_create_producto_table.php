<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');

            $table->string('nombre', 100)->nullable();
            $table->decimal('precio', 10, 2)->nullable();

            // En el SQL original es BLOB, pero realmente contiene
            // rutas como ../img/productos/...
            $table->string('img', 255)->nullable();

            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_categoria')->nullable();

            $table->boolean('estado')->default(true);
            $table->boolean('retornable')->default(false);

            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->nullOnDelete();

            $table->foreign('id_categoria')
                ->references('id_categoria')
                ->on('categoria')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};