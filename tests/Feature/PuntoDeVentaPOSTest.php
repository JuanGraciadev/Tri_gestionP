<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PuntoDeVentaPOSTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cliente;
    protected Producto $producto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin   = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->cliente = User::factory()->create(['id_rol' => 3, 'estado' => 1]);

        $categoria = Categoria::create(['nombre' => 'General', 'estado' => 1]);
        $this->producto = Producto::create([
            'nombre'       => 'Botellón 20L',
            'precio'       => 12.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 5,
        ]);

        // Cargar stock en inventario
        InventarioProductos::create([
            'id_producto'   => $this->producto->id_producto,
            'cantidad'      => 100,
            'bodega'        => 'Principal',
            'fecha'         => now()->toDateString(),
            'id_usuario'    => $this->admin->id_usuario,
            'id_produccion' => null,
        ]);
    }

    /**
     * 2.2 Registrar venta POS con Efectivo.
     */
    public function test_pos_registra_venta_con_efectivo(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/ventas/crear-pos', [
            'id_cliente'   => $this->cliente->id_usuario,
            'pos_producto' => [$this->producto->id_producto],
            'pos_cantidad' => [2],
            'metodo_pago'  => 'Efectivo',
            'notas'        => 'Venta física en mostrador',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('venta', [
            'metodo_pago'  => 'Efectivo',
            'estado'       => 'Entregado',
            'estado_pago'  => 'Confirmado',
            'total'        => 24.00,
        ]);
    }

    /**
     * 2.2 Registrar venta POS con Transferencia.
     */
    public function test_pos_registra_venta_con_transferencia(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/ventas/crear-pos', [
            'id_cliente'   => $this->cliente->id_usuario,
            'pos_producto' => [$this->producto->id_producto],
            'pos_cantidad' => [1],
            'metodo_pago'  => 'Transferencia',
            'notas'        => 'Transferencia bancaria comprobada',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('venta', [
            'metodo_pago'  => 'Transferencia',
            'estado'       => 'Entregado',
            'estado_pago'  => 'Confirmado',
            'total'        => 12.00,
        ]);
    }

    /**
     * 2.2 Registrar venta POS con Tarjeta.
     */
    public function test_pos_registra_venta_con_tarjeta(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/ventas/crear-pos', [
            'id_cliente'   => $this->cliente->id_usuario,
            'pos_producto' => [$this->producto->id_producto],
            'pos_cantidad' => [3],
            'metodo_pago'  => 'Tarjeta',
            'notas'        => 'Pago con datafono',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('venta', [
            'metodo_pago'  => 'Tarjeta',
            'estado'       => 'Entregado',
            'estado_pago'  => 'Confirmado',
            'total'        => 36.00,
        ]);
    }

    /**
     * 2.2 Método de pago no permitido es rechazado por validación.
     */
    public function test_pos_rechaza_metodo_de_pago_invalido(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/ventas/crear-pos', [
            'id_cliente'   => $this->cliente->id_usuario,
            'pos_producto' => [$this->producto->id_producto],
            'pos_cantidad' => [1],
            'metodo_pago'  => 'Criptomoneda',
        ]);

        $response->assertSessionHasErrors('metodo_pago');
    }
}
