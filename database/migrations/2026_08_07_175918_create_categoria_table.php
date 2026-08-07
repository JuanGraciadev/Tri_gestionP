<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->boolean('estado')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria');
    }
};