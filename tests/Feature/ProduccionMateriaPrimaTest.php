<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\InventarioMateriaPrima;
use App\Models\InventarioProductos;
use App\Models\Produccion;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduccionMateriaPrimaTest extends TestCase
{
    use RefreshDatabase;

    protected User $trabajador;
    protected Producto $producto;
    protected InventarioMateriaPrima $invMp;

    protected function setUp(): void
    {
        parent::setUp();

        // Producción está asignada a rol 2 (Trabajador)
        $this->trabajador = User::factory()->create(['id_rol' => 2, 'estado' => 1]);
        $categoria        = Categoria::create(['nombre' => 'General', 'estado' => 1]);

        $this->invMp = InventarioMateriaPrima::create([
            'ingreso'        => 100,
            'bodega'         => 'Bodega MP',
            'fecha'          => now()->toDateString(),
            'id_detalles'    => null,
            'id_retornables' => null,
        ]);

        $this->producto = Producto::create([
            'nombre'       => 'Botella 500ml',
            'precio'       => 1.50,
            'id_usuario'   => $this->trabajador->id_usuario,
            'id_categoria' => $categoria->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);
    }

    /**
     * 2.1 La producción descuenta materia prima al INICIAR (store).
     */
    public function test_produccion_descuenta_materia_prima_al_iniciar(): void
    {
        $this->actingAs($this->trabajador);

        $response = $this->post('/produccion', [
            'lote_produccion'       => 'LOT-PROD-001',
            'id_producto'           => $this->producto->id_producto,
            'id_inventario_materia' => $this->invMp->id_inventario_materia,
            'cantidad'              => 30,
            'descripcion'           => 'Lote de prueba',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->invMp->refresh();
        // 100 - 30 = 70
        $this->assertEquals(70, $this->invMp->ingreso);

        $this->assertDatabaseHas('produccion', [
            'lote_produccion' => 'LOT-PROD-001',
            'id_producto'     => $this->producto->id_producto,
            'cantidad'        => 30,
            'estado'          => 'En Producción',
        ]);
    }

    /**
     * 2.1 Si la materia prima no alcanza, rechaza la producción y no descuenta nada.
     */
    public function test_rechaza_produccion_si_no_hay_materia_prima_suficiente(): void
    {
        $this->actingAs($this->trabajador);

        $response = $this->post('/produccion', [
            'lote_produccion'       => 'LOT-PROD-EXCESS',
            'id_producto'           => $this->producto->id_producto,
            'id_inventario_materia' => $this->invMp->id_inventario_materia,
            'cantidad'              => 150, // Solo hay 100
            'descripcion'           => 'Lote excesivo',
        ]);

        $response->assertRedirect();
        $this->invMp->refresh();
        $this->assertEquals(100, $this->invMp->ingreso);
        $this->assertEquals('error', session('alert.icon'));
    }

    /**
     * 2.1 Finalizar producción NO vuelve a descontar materia prima y suma al inventario de productos.
     */
    public function test_finalizar_produccion_no_descuenta_mp_y_suma_inventario_productos(): void
    {
        $this->actingAs($this->trabajador);

        // Iniciar con 40 unidades (descuenta 40 MP -> quedan 60)
        $this->post('/produccion', [
            'lote_produccion'       => 'LOT-PROD-FIN',
            'id_producto'           => $this->producto->id_producto,
            'id_inventario_materia' => $this->invMp->id_inventario_materia,
            'cantidad'              => 40,
            'descripcion'           => 'Lote a finalizar',
        ]);

        $produccion = Produccion::latest('id_produccion')->first();
        $this->invMp->refresh();
        $this->assertEquals(60, $this->invMp->ingreso);

        // Finalizar producción por POST
        $response = $this->post("/produccion/{$produccion->id_produccion}/finalizar");
        $response->assertRedirect();

        // Stock de MP se mantiene en 60 (NO se vuelve a descontar)
        $this->invMp->refresh();
        $this->assertEquals(60, $this->invMp->ingreso);

        // Estado pasa a Finalizada
        $this->assertEquals('Finalizada', $produccion->fresh()->estado);

        // Se sumó al inventario de productos terminados
        $this->assertDatabaseHas('inventario_productos', [
            'id_producto'   => $this->producto->id_producto,
            'id_produccion' => $produccion->id_produccion,
            'cantidad'      => 40,
        ]);
    }

    /**
     * 2.1 Cancelar producción devuelve la materia prima y cambia el estado a Cancelada.
     */
    public function test_cancelar_produccion_devuelve_materia_prima(): void
    {
        $this->actingAs($this->trabajador);

        // Iniciar con 25 unidades (descuenta 25 MP -> quedan 75)
        $this->post('/produccion', [
            'lote_produccion'       => 'LOT-PROD-CANCEL',
            'id_producto'           => $this->producto->id_producto,
            'id_inventario_materia' => $this->invMp->id_inventario_materia,
            'cantidad'              => 25,
            'descripcion'           => 'Lote a cancelar',
        ]);

        $produccion = Produccion::latest('id_produccion')->first();
        $this->invMp->refresh();
        $this->assertEquals(75, $this->invMp->ingreso);

        // Cancelar producción por POST
        $response = $this->post("/produccion/{$produccion->id_produccion}/cancelar");
        $response->assertRedirect();

        // MP debe haber sido devuelta: 75 + 25 = 100
        $this->invMp->refresh();
        $this->assertEquals(100, $this->invMp->ingreso);

        // Estado de la producción
        $this->assertEquals('Cancelada', $produccion->fresh()->estado);
    }
}
