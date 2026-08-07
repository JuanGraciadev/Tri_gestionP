<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');

            $table->string('nombres', 100);
            $table->string('direccion', 150)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('documento_numero', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('password', 255)->nullable();

            $table->unsignedBigInteger('id_rol')->nullable();

            $table->tinyInteger('estado')->default(1)
                ->comment('1: activo, 0: inactivo');

            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('rol')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};