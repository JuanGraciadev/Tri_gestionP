<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produccion', function (Blueprint $table) {
            $table->string('modalidad', 10)->default('UNIDADES')->after('cantidad'); // UNIDADES | PACAS
            $table->unsignedBigInteger('id_presentacion')->nullable()->after('modalidad');
            $table->string('nombre_presentacion', 120)->default('')->after('id_presentacion');
            $table->integer('cantidad_pacas')->nullable()->after('nombre_presentacion');
            $table->integer('unidades_por_paca')->default(1)->after('cantidad_pacas');
            $table->integer('unidades_fabricadas')->nullable()->after('unidades_por_paca');
            $table->json('materiales_consumidos')->nullable()->after('unidades_fabricadas');
        });
    }

    public function down(): void
    {
        Schema::table('produccion', function (Blueprint $table) {
            $table->dropColumn(['modalidad', 'id_presentacion', 'nombre_presentacion', 'cantidad_pacas', 'unidades_por_paca', 'unidades_fabricadas', 'materiales_consumidos']);
        });
    }
};
