<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertasBajoStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $trabajador;
    protected User $cliente;
    protected Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin      = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->trabajador = User::factory()->create(['id_rol' => 2, 'estado' => 1]);
        $this->cliente    = User::factory()->create(['id_rol' => 3, 'estado' => 1]);

        $this->categoria  = Categoria::create(['nombre' => 'Aguas Minerales', 'estado' => 1]);
    }

    /**
     * 2.4 El endpoint GET /api/productos/alertas devuelve productos con stock bajo y agotados.
     */
    public function test_endpoint_alertas_productos_detecta_bajo_stock_y_agotados(): void
    {
        // Producto 1: Agotado (stock 0, mínimo 10)
        $prodAgotado = Producto::create([
            'nombre'       => 'Agua con Gas 600ml',
            'precio'       => 3.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $this->categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);

        // Producto 2: Bajo stock (stock 5, mínimo 10)
        $prodBajo = Producto::create([
            'nombre'       => 'Agua Natural 1L',
            'precio'       => 4.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $this->categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);
        InventarioProductos::create([
            'id_producto'   => $prodBajo->id_producto,
            'cantidad'      => 5,
            'bodega'        => 'Principal',
            'fecha'         => now()->toDateString(),
            'id_usuario'    => $this->admin->id_usuario,
            'id_produccion' => null,
        ]);

        // Producto 3: Stock normal (stock 50, mínimo 10)
        $prodNormal = Producto::create([
            'nombre'       => 'Garrafón 20L',
            'precio'       => 15.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $this->categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);
        InventarioProductos::create([
            'id_producto'   => $prodNormal->id_producto,
            'cantidad'      => 50,
            'bodega'        => 'Principal',
            'fecha'         => now()->toDateString(),
            'id_usuario'    => $this->admin->id_usuario,
            'id_produccion' => null,
        ]);

        // Consulta como admin
        $response = $this->actingAs($this->admin)->getJson('/api/productos/alertas');

        $response->assertStatus(200);
        $response->assertJson([
            'ok'         => true,
            'hayAlertas' => true,
        ]);

        $data = $response->json();
        $this->assertCount(1, $data['agotados']);
        $this->assertEquals($prodAgotado->id_producto, $data['agotados'][0]['id_producto']);

        $this->assertCount(1, $data['stockBajo']);
        $this->assertEquals($prodBajo->id_producto, $data['stockBajo'][0]['id_producto']);
    }

    /**
     * 2.4 Trabajador (rol 2) también puede consultar las alertas de productos.
     */
    public function test_trabajador_puede_consultar_alertas_de_productos(): void
    {
        $response = $this->actingAs($this->trabajador)->getJson('/api/productos/alertas');
        $response->assertStatus(200);
    }

    /**
     * 2.4 Cliente (rol 3) es denegado (403) para consultar alertas de productos.
     */
    public function test_cliente_no_puede_consultar_alertas_de_productos(): void
    {
        $response = $this->actingAs($this->cliente)->getJson('/api/productos/alertas');
        $response->assertStatus(403);
    }
}
