<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\View\Components\AppLayout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacturaPosYTareasTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cliente;
    protected User $otroCliente;
    protected Producto $producto;
    protected Venta $venta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin       = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->cliente     = User::factory()->create(['id_rol' => 3, 'estado' => 1]);
        $this->otroCliente = User::factory()->create(['id_rol' => 3, 'estado' => 1]);

        $categoria = Categoria::create(['nombre' => 'General', 'estado' => 1]);
        $this->producto = Producto::create([
            'nombre'       => 'Botellón 20L Purificada',
            'precio'       => 15.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 5,
        ]);

        $clienteRecord = Cliente::firstOrCreateForUser($this->cliente->id_usuario);

        $this->venta = Venta::create([
            'fecha'        => now()->toDateString(),
            'estado'       => 'Entregado',
            'estado_pago'  => 'Confirmado',
            'total'        => 30.00,
            'notas'        => 'Venta de prueba para Factura POS',
            'metodo_pago'  => 'Efectivo',
            'id_cliente'   => $clienteRecord->id_cliente,
            'id_usuario'   => $this->admin->id_usuario,
        ]);

        DetalleVenta::create([
            'id_venta'        => $this->venta->id_venta,
            'id_producto'     => $this->producto->id_producto,
            'cantidad'        => 2,
            'precio_unitario' => 15.00,
            'subtotal'        => 30.00,
        ]);
    }

    /**
     * Requerimiento: Factura de venta en formato POS térmico (HTML y JSON).
     */
    public function test_factura_se_muestra_en_formato_pos(): void
    {
        $this->actingAs($this->admin);

        // Petición HTML (navegador o ventana emergente)
        $response = $this->get("/ventas/factura/{$this->venta->id_venta}");
        $response->assertStatus(200);
        $response->assertViewIs('ventas.factura-pos');
        $response->assertSee('FACTURA DE VENTA POS');
        $response->assertSee('TRIGESTION');
        $response->assertSee('Botellón 20L Purificada');
        $response->assertSee('30.00');
        $response->assertSee('TOTAL A PAGAR');

        // Petición JSON (API)
        $jsonResponse = $this->getJson("/ventas/factura/{$this->venta->id_venta}");
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJsonStructure([
            'ok',
            'venta' => ['id_venta', 'total', 'metodo_pago', 'items'],
            'factura' => ['id_venta', 'total', 'items'],
        ]);
    }

    /**
     * Cliente puede ver su propia factura POS pero no la de otros clientes.
     */
    public function test_cliente_puede_ver_su_propia_factura_pos(): void
    {
        $this->actingAs($this->cliente);

        $response = $this->get("/ventas/factura/{$this->venta->id_venta}");
        $response->assertStatus(200);
        $response->assertSee('FACTURA DE VENTA POS');

        // Otro cliente no puede verla
        $this->actingAs($this->otroCliente);
        $forbiddenResponse = $this->get("/ventas/factura/{$this->venta->id_venta}");
        $forbiddenResponse->assertStatus(403);
    }

    /**
     * Falla 1: ventas/checkout.blade.php y rutas carrito.vaciar / ventas.confirmar.
     */
    public function test_rutas_carrito_vaciar_y_ventas_confirmar_existen(): void
    {
        $this->actingAs($this->cliente);

        session(['carrito' => [
            $this->producto->id_producto => [
                'id_producto'     => $this->producto->id_producto,
                'nombre'          => $this->producto->nombre,
                'precio_unitario' => 15.00,
                'cantidad'        => 1,
            ],
        ]]);

        // Probar vaciar carrito
        $response = $this->postJson('/carrito/vaciar');
        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
        $this->assertEmpty(session('carrito'));

        // Probar que ventas.confirmar es accesible y responde
        $responseConfirmar = $this->postJson('/ventas/confirmar', ['notas' => 'Prueba']);
        // Carrito vacío devuelve error controlado ok=false
        $responseConfirmar->assertStatus(200);
        $responseConfirmar->assertJson(['ok' => false]);
    }

    /**
     * Falla 2: ventas/show.blade.php se muestra correctamente en /ventas/{id} con estados válidos.
     */
    public function test_ventas_show_se_muestra_con_estados_validos(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get("/ventas/{$this->venta->id_venta}");
        $response->assertStatus(200);
        $response->assertViewIs('ventas.show');
        $response->assertSee('Entregado');
        $response->assertSee('Botellón 20L Purificada');
        $response->assertDontSee('Completada');
    }

    /**
     * Falla 3: /api/materias-primas responde JSON siempre sin error de vista.
     */
    public function test_api_materias_primas_responde_json_sin_error_de_vista(): void
    {
        $this->actingAs($this->admin);

        // Sin header Accept: application/json
        $response = $this->get('/api/materias-primas');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJson(['ok' => true]);
    }

    /**
     * Falla 5: AppLayout component renderiza layouts.panel sin error.
     */
    public function test_app_layout_component_apunta_a_layouts_panel(): void
    {
        $component = new AppLayout();
        $view = $component->render();
        $this->assertEquals('layouts.panel', $view->name());
    }

    /**
     * Módulo de Materias Primas e Insumos (Etiquetas, Bolsas para Pacas, Tapas).
     */
    public function test_modulo_materias_primas_web_carga_y_opera_correctamente(): void
    {
        $this->actingAs($this->admin);

        // Crear insumo de prueba
        $responseCreate = $this->post('/materias-primas', [
            'codigo'        => 'BOL-TEST',
            'nombre'        => 'Bolsas para Pacas de Prueba',
            'unidad_medida' => 'Unidades',
            'stock'         => 100,
            'stock_minimo'  => 20,
            'costo_unitario'=> 0.20,
            'bodega'        => 'Bodega Insumos',
        ]);
        $responseCreate->assertRedirect();
        $responseCreate->assertSessionHas('alert');

        $this->assertDatabaseHas('materias_primas', [
            'codigo' => 'BOL-TEST',
            'nombre' => 'Bolsas para Pacas de Prueba',
        ]);

        // Ver módulo web
        $response = $this->get('/materias-primas');
        $response->assertStatus(200);
        $response->assertViewIs('materias-primas.index');
        $response->assertSee('Insumos y');
        $response->assertSee('Bolsas para Pacas');

        // Probar entrada rápida de stock
        $insumo = \App\Models\MateriaPrima::where('codigo', 'BOL-TEST')->first();
        $responseEntrada = $this->post('/materias-primas/entrada', [
            'id_materia_prima' => $insumo->id_materia_prima,
            'cantidad'         => 50,
            'bodega'           => 'Bodega Insumos',
        ]);
        $responseEntrada->assertRedirect();
        $this->assertEquals(150, (float) $insumo->fresh()->stock);
    }
}

