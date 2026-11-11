<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (Schema::hasTable('rol') && DB::table('rol')->count() === 0) {
            DB::table('rol')->insert([
                ['id_rol' => 1, 'nombre' => 'Administrador'],
                ['id_rol' => 2, 'nombre' => 'Trabajador'],
                ['id_rol' => 3, 'nombre' => 'Cliente'],
            ]);
        }
    }
}
