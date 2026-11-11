<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\MateriaPrima;
use App\Models\Produccion;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RutasVerboHttpTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $trabajador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'id_rol' => 1,
            'estado' => 1,
        ]);

        $this->trabajador = User::factory()->create([
            'id_rol' => 2,
            'estado' => 1,
        ]);
    }

    /**
     * 1.3 Rutas que cambian datos por GET ya no responden (405 Method Not Allowed).
     */
    public function test_rutas_toggle_y_finalizar_rechazan_peticiones_get_con_405(): void
    {
        $this->actingAs($this->admin);

        $otroUsuario = User::factory()->create(['id_rol' => 3, 'estado' => 1]);
        $categoria   = Categoria::create(['nombre' => 'Aguas', 'estado' => 1]);
        $producto    = Producto::create([
            'nombre'       => 'Bidón 20L',
            'precio'       => 15.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $categoria->id_categoria,
            'estado'       => 1,
        ]);
        $materiaPrima = MateriaPrima::create([
            'nombre'       => 'Tapa Azul',
            'unidad_medida'=> 'unidad',
            'stock'        => 100,
            'stock_minimo' => 10,
            'costo_unitario'=> 0.50,
            'estado'       => 1,
        ]);
        $produccion = Produccion::create([
            'id_producto'     => $producto->id_producto,
            'id_usuario'      => $this->admin->id_usuario,
            'cantidad'        => 50,
            'fecha'           => now()->toDateString(),
            'lote_produccion' => 'LOT-TEST-405',
            'estado'          => 'En Producción',
        ]);

        // GET a admin/usuarios/{usuario}/toggle-estado -> 405
        $this->get("/admin/usuarios/{$otroUsuario->id_usuario}/toggle-estado")
            ->assertStatus(405);

        // GET a categorias/{categoria}/toggle-estado -> 405
        $this->get("/categorias/{$categoria->id_categoria}/toggle-estado")
            ->assertStatus(405);

        // GET a productos/{producto}/toggle-estado -> 405
        $this->get("/productos/{$producto->id_producto}/toggle-estado")
            ->assertStatus(405);

        // GET a api/materias-primas/{materiaPrima}/toggle-estado -> 405
        $this->get("/api/materias-primas/{$materiaPrima->id_materia_prima}/toggle-estado")
            ->assertStatus(405);

        // GET a produccion/{id}/finalizar -> 405 (como trabajador con acceso a producción)
        $this->actingAs($this->trabajador)
            ->get("/produccion/{$produccion->id_produccion}/finalizar")
            ->assertStatus(405);
    }

    /**
     * POST a las rutas de mutación con CSRF funciona correctamente.
     */
    public function test_rutas_toggle_funcionan_por_post(): void
    {
        $this->actingAs($this->admin);

        $otroUsuario = User::factory()->create(['id_rol' => 3, 'estado' => 1]);
        $categoria   = Categoria::create(['nombre' => 'Aguas 2', 'estado' => 1]);

        $this->post("/admin/usuarios/{$otroUsuario->id_usuario}/toggle-estado")
            ->assertRedirect();
        $this->assertEquals(0, $otroUsuario->fresh()->estado);

        $this->post("/categorias/{$categoria->id_categoria}/toggle-estado")
            ->assertRedirect();
        $this->assertEquals(0, $categoria->fresh()->estado);
    }
}
