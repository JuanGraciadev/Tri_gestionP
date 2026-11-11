<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\DetalleVenta;
use App\Models\InventarioProductos;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VentaEstadoYStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $cliente;
    protected Producto $producto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->cliente = User::factory()->create(['id_rol' => 3, 'estado' => 1]);

        $categoria = Categoria::create(['nombre' => 'Bebidas', 'estado' => 1]);
        $this->producto = Producto::create([
            'nombre'       => 'Agua 500ml',
            'precio'       => 2.50,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 5,
        ]);
    }

    /**
     * 1.4 Pasar a 'En Proceso' valida stock y rechaza si no alcanza.
     */
    public function test_no_se_puede_pasar_a_en_proceso_sin_stock_suficiente(): void
    {
        $this->actingAs($this->admin);

        // Venta pendiente de 10 unidades sin stock ingresado
        $venta = Venta::create([
            'id_usuario'  => $this->cliente->id_usuario,
            'total'       => 25.00,
            'estado'      => 'Pendiente',
            'estado_pago' => 'Pendiente',
            'metodo_pago' => 'Efectivo',
        ]);
        DetalleVenta::create([
            'id_venta'        => $venta->id_venta,
            'id_producto'     => $this->producto->id_producto,
            'cantidad'        => 10,
            'precio_unitario' => 2.50,
        ]);

        $response = $this->post('/ventas/cambiar-estado', [
            'id_venta' => $venta->id_venta,
            'estado'   => 'En Proceso',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('alert');
        $this->assertEquals('error', session('alert.icon'));
        $this->assertEquals('Pendiente', $venta->fresh()->estado);
    }

    /**
     * 1.4 Pasar a 'En Proceso' con stock suficiente se procesa exitosamente.
     */
    public function test_pasa_a_en_proceso_si_hay_stock_suficiente(): void
    {
        $this->actingAs($this->admin);

        // Agregar 20 unidades al inventario
        InventarioProductos::create([
            'id_producto'   => $this->producto->id_producto,
            'cantidad'      => 20,
            'bodega'        => 'Principal',
            'fecha'         => now()->toDateString(),
            'id_usuario'    => $this->admin->id_usuario,
            'id_produccion' => null,
        ]);

        $venta = Venta::create([
            'id_usuario'  => $this->cliente->id_usuario,
            'total'       => 25.00,
            'estado'      => 'Pendiente',
            'estado_pago' => 'Pendiente',
            'metodo_pago' => 'Efectivo',
        ]);
        DetalleVenta::create([
            'id_venta'        => $venta->id_venta,
            'id_producto'     => $this->producto->id_producto,
            'cantidad'        => 10,
            'precio_unitario' => 2.50,
        ]);

        $response = $this->post('/ventas/cambiar-estado', [
            'id_venta' => $venta->id_venta,
            'estado'   => 'En Proceso',
        ]);

        $response->assertRedirect();
        $this->assertEquals('En Proceso', $venta->fresh()->estado);
    }

    /**
     * 1.4 Estados finales no se pueden cambiar (ej. Cancelado no puede pasar a Entregado).
     */
    public function test_no_se_puede_cambiar_un_estado_final(): void
    {
        $this->actingAs($this->admin);

        $venta = Venta::create([
            'id_usuario'  => $this->cliente->id_usuario,
            'total'       => 25.00,
            'estado'      => 'Cancelado',
            'estado_pago' => 'Pendiente',
            'metodo_pago' => 'Efectivo',
        ]);

        $response = $this->post('/ventas/cambiar-estado', [
            'id_venta' => $venta->id_venta,
            'estado'   => 'Entregado',
        ]);

        $response->assertRedirect();
        $this->assertEquals('error', session('alert.icon'));
        $this->assertEquals('Cancelado', $venta->fresh()->estado);
    }

    /**
     * 2.3 Al marcar el estado de pago como Rechazado, la venta pasa a Cancelado.
     */
    public function test_pago_rechazado_cancela_la_venta(): void
    {
        $this->actingAs($this->admin);

        $venta = Venta::create([
            'id_usuario'  => $this->cliente->id_usuario,
            'total'       => 25.00,
            'estado'      => 'Pendiente',
            'estado_pago' => 'Pendiente',
            'metodo_pago' => 'Transferencia',
        ]);

        $response = $this->post('/ventas/cambiar-estado-pago', [
            'id_venta'    => $venta->id_venta,
            'estado_pago' => 'Rechazado',
        ]);

        $response->assertRedirect();
        $venta->refresh();
        $this->assertEquals('Rechazado', $venta->estado_pago);
        $this->assertEquals('Cancelado', $venta->fresh()->estado);
    }
}
