<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->string('tipo_venta', 10)->default('UNIDAD')->after('cantidad'); // UNIDAD | PACA
            $table->unsignedBigInteger('id_presentacion')->nullable()->after('tipo_venta');
            $table->string('nombre_presentacion', 120)->default('')->after('id_presentacion');
            $table->integer('cantidad_pacas')->nullable()->after('nombre_presentacion');
            $table->integer('unidades_por_paca')->default(1)->after('cantidad_pacas');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropColumn(['tipo_venta', 'id_presentacion', 'nombre_presentacion', 'cantidad_pacas', 'unidades_por_paca']);
        });
    }
};
