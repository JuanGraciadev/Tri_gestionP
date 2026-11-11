<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarConexionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $trabajador;
    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin      = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->trabajador = User::factory()->create(['id_rol' => 2, 'estado' => 1]);
        $this->cliente    = User::factory()->create(['id_rol' => 3, 'estado' => 1]);
    }

    public function test_sidebar_admin_conecta_con_todos_los_modulos(): void
    {
        $this->actingAs($this->admin);

        // Módulos principales del sidebar del Administrador
        $rutas = [
            route('admin.dashboard'),
            route('inventario-mp.index'),
            route('lotes.index'),
            route('produccion.index'),
            route('categorias.index'),
            route('productos.index'),
            route('inventario-productos.index'),
            route('ventas.index'),
            route('devoluciones.index'),
            route('reportes.index'),
        ];

        foreach ($rutas as $ruta) {
            $response = $this->get($ruta);
            $response->assertStatus(200, "Error al conectar con la ruta: {$ruta}");
            // Verifica que el sidebar esté presente en el HTML
            $response->assertSee('sidebar-admin', false);
        }
    }

    public function test_sidebar_trabajador_conecta_con_todos_los_modulos(): void
    {
        $this->actingAs($this->trabajador);

        // Módulos del sidebar del Trabajador
        $rutas = [
            route('trabajador.dashboard'),
            route('produccion.index'),
            route('inventario-mp.index'),
            route('lotes.index'),
            route('inventario-productos.index'),
            route('devoluciones.index'),
        ];

        foreach ($rutas as $ruta) {
            $response = $this->get($ruta);
            $response->assertStatus(200, "Error al conectar con la ruta: {$ruta}");
            $response->assertSee('sidebar-trabajador', false);
        }
    }

    public function test_sidebar_cliente_conecta_con_todos_los_modulos(): void
    {
        $this->actingAs($this->cliente);

        // Módulos del sidebar del Cliente
        $rutas = [
            route('cliente.dashboard'),
            route('productos.catalogo'),
            route('cliente.misCompras'),
        ];

        foreach ($rutas as $ruta) {
            $response = $this->get($ruta);
            $response->assertStatus(200, "Error al conectar con la ruta: {$ruta}");
            $response->assertSee('sidebar-cliente', false);
        }
    }
}

